<?php
/**
 * Job queue task base code.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @defgroup JobQueue JobQueue
 */

/**
 * Class to both describe a background job and handle jobs.
 * To push jobs onto queues, use JobQueueGroup::singleton()->push();
 *
 * @ingroup JobQueue
 */
abstract class Job implements IJobSpecification {
	/** @var string */
	public $command;

	/** @var array Array of job parameters */
	public $params;

	/** @var array Additional queue metadata */
	public $metadata = [];

	/** @var Title */
	protected $title;

	/** @var bool Expensive jobs may set this to true */
	protected $removeDuplicates = false;

	/** @var string Text for error that occurred last */
	protected $error;

	/** @var callable[] */
	protected $teardownCallbacks = [];

	/** @var int Bitfield of JOB_* class constants */
	protected $executionFlags = 0;

	/** @var int Job must not be wrapped in the usual explicit LBFactory transaction round */
	const JOB_NO_EXPLICIT_TRX_ROUND = 1;

	/**
	 * Run the job
	 * @return bool Success
	 */
	abstract public function run();

	/**
	 * Create the appropriate object to handle a specific job
	 *
	 * @param string $command Job command
	 * @param array $params Job parameters
	 * @throws InvalidArgumentException
	 * @return Job
	 */
	public static function factory( $command, $params = [] ) {
		global $wgJobClasses;

		if ( $params instanceof Title ) {
			// Backwards compatibility for old signature ($command, $title, $params)
			$title = $params;
			$params = func_num_args() >= 3 ? func_get_arg( 2 ) : [];
		} else {
			// Subclasses can override getTitle() to return something more meaningful
			$title = Title::makeTitle( NS_SPECIAL, 'Blankpage' );
		}

		if ( isset( $wgJobClasses[$command] ) ) {
			$handler = $wgJobClasses[$command];

			if ( is_callable( $handler ) ) {
				$job = call_user_func( $handler, $title, $params );
			} elseif ( class_exists( $handler ) ) {
				$job = new $handler( $title, $params );
			} else {
				$job = null;
			}

			if ( $job instanceof Job ) {
				$job->command = $command;

				return $job;
			} else {
				throw new InvalidArgumentException( "Could instantiate job '$command': bad spec!" );
			}
		}

		throw new InvalidArgumentException( "Invalid job command '{$command}'" );
	}

	/**
	 * @param string $command
	 * @param array $params
	 */
	public function __construct( $command, $params = [] ) {
		if ( $params instanceof Title ) {
			// Backwards compatibility for old signature ($command, $title, $params)
			$title = $params;
			$params = func_num_args() >= 3 ? func_get_arg( 2 ) : [];
		} else {
			// Subclasses can override getTitle() to return something more meaningful
			$title = Title::makeTitle( NS_SPECIAL, 'Blankpage' );
		}

		$this->command = $command;
		$this->title = $title;
		$this->params = is_array( $params ) ? $params : []; // sanity
		if ( !isset( $this->params['requestId'] ) ) {
			$this->params['requestId'] = WebRequest::getRequestId();
		}
	}

	/**
	 * @param int $flag JOB_* class constant
	 * @return bool
	 * @since 1.31
	 */
	public function hasExecutionFlag( $flag ) {
		return ( $this->executionFlags & $flag ) === $flag;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->command;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @param string|null $field Metadata field or null to get all the metadata
	 * @return mixed|null Value; null if missing
	 * @since 1.33
	 */
	public function getMetadata( $field = null ) {
		if ( $field === null ) {
			return $this->metadata;
		}

		return $this->metadata[$field] ?? null;
	}

	/**
	 * @param string $field Key name to set the value for
	 * @param mixed $value The value to set the field for
	 * @return mixed|null The prior field value; null if missing
	 * @since 1.33
	 */
	public function setMetadata( $field, $value ) {
		$old = $this->getMetadata( $field );
		if ( $value === null ) {
			unset( $this->metadata[$field] );
		} else {
			$this->metadata[$field] = $value;
		}

		return $old;
	}

	/**
	 * @return int|null UNIX timestamp to delay running this job until, otherwise null
	 * @since 1.22
	 */
	public function getReleaseTimestamp() {
		return isset( $this->params['jobReleaseTimestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->params['jobReleaseTimestamp'] )
			: null;
	}

	/**
	 * @return int|null UNIX timestamp of when the job was queued, or null
	 * @since 1.26
	 */
	public function getQueuedTimestamp() {
		return isset( $this->metadata['timestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->metadata['timestamp'] )
			: null;
	}

	/**
	 * @return string|null Id of the request that created this job. Follows
	 *  jobs recursively, allowing to track the id of the request that started a
	 *  job when jobs insert jobs which insert other jobs.
	 * @since 1.27
	 */
	public function getRequestId() {
		return $this->params['requestId'] ?? null;
	}

	/**
	 * @return int|null UNIX timestamp of when the job was runnable, or null
	 * @since 1.26
	 */
	public function getReadyTimestamp() {
		return $this->getReleaseTimestamp() ?: $this->getQueuedTimestamp();
	}

	/**
	 * Whether the queue should reject insertion of this job if a duplicate exists
	 *
	 * This can be used to avoid duplicated effort or combined with delayed jobs to
	 * coalesce updates into larger batches. Claimed jobs are never treated as
	 * duplicates of new jobs, and some queues may allow a few duplicates due to
	 * network partitions and fail-over. Thus, additional locking is needed to
	 * enforce mutual exclusion if this is really needed.
	 *
	 * @return bool
	 */
	public function ignoreDuplicates() {
		return $this->removeDuplicates;
	}

	/**
	 * @return bool Whether this job can be retried on failure by job runners
	 * @since 1.21
	 */
	public function allowRetries() {
		return true;
	}

	/**
	 * @return int Number of actually "work items" handled in this job
	 * @see $wgJobBackoffThrottling
	 * @since 1.23
	 */
	public function workItemCount() {
		return 1;
	}

	/**
	 * Subclasses may need to override this to make duplication detection work.
	 * The resulting map conveys everything that makes the job unique. This is
	 * only checked if ignoreDuplicates() returns true, meaning that duplicate
	 * jobs are supposed to be ignored.
	 *
	 * @return array Map of key/values
	 * @since 1.21
	 */
	public function getDeduplicationInfo() {
		$info = [
			'type' => $this->getType(),
			'namespace' => $this->getTitle()->getNamespace(),
			'title' => $this->getTitle()->getDBkey(),
			'params' => $this->getParams()
		];
		if ( is_array( $info['params'] ) ) {
			// Identical jobs with different "root" jobs should count as duplicates
			unset( $info['params']['rootJobSignature'] );
			unset( $info['params']['rootJobTimestamp'] );
			// Likewise for jobs with different delay times
			unset( $info['params']['jobReleaseTimestamp'] );
			// Identical jobs from different requests should count as duplicates
			unset( $info['params']['requestId'] );
			// Queues pack and hash this array, so normalize the order
			ksort( $info['params'] );
		}

		return $info;
	}

	/**
	 * Get "root job" parameters for a task
	 *
	 * This is used to no-op redundant jobs, including child jobs of jobs,
	 * as long as the children inherit the root job parameters. When a job
	 * with root job parameters and "rootJobIsSelf" set is pushed, the
	 * deduplicateRootJob() method is automatically called on it. If the
	 * root job is only virtual and not actually pushed (e.g. the sub-jobs
	 * are inserted directly), then call deduplicateRootJob() directly.
	 *
	 * @see JobQueue::deduplicateRootJob()
	 *
	 * @param string $key A key that identifies the task
	 * @return array Map of:
	 *   - rootJobIsSelf    : true
	 *   - rootJobSignature : hash (e.g. SHA1) that identifies the task
	 *   - rootJobTimestamp : TS_MW timestamp of this instance of the task
	 * @since 1.21
	 */
	public static function newRootJobParams( $key ) {
		return [
			'rootJobIsSelf'    => true,
			'rootJobSignature' => sha1( $key ),
			'rootJobTimestamp' => wfTimestampNow()
		];
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return array
	 * @since 1.21
	 */
	public function getRootJobParams() {
		return [
			'rootJobSignature' => $this->params['rootJobSignature'] ?? null,
			'rootJobTimestamp' => $this->params['rootJobTimestamp'] ?? null
		];
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool
	 * @since 1.22
	 */
	public function hasRootJobParams() {
		return isset( $this->params['rootJobSignature'] )
			&& isset( $this->params['rootJobTimestamp'] );
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool Whether this is job is a root job
	 */
	public function isRootJob() {
		return $this->hasRootJobParams() && !empty( $this->params['rootJobIsSelf'] );
	}

	/**
	 * @param callable $callback A function with one parameter, the success status, which will be
	 *   false if the job failed or it succeeded but the DB changes could not be committed or
	 *   any deferred updates threw an exception. (This parameter was added in 1.28.)
	 * @since 1.27
	 */
	protected function addTeardownCallback( $callback ) {
		$this->teardownCallbacks[] = $callback;
	}

	/**
	 * Do any final cleanup after run(), deferred updates, and all DB commits happen
	 * @param bool $status Whether the job, its deferred updates, and DB commit all succeeded
	 * @since 1.27
	 */
	public function teardown( $status ) {
		foreach ( $this->teardownCallbacks as $callback ) {
			call_user_func( $callback, $status );
		}
	}

	/**
	 * @return string
	 */
	public function toString() {
		$paramString = '';
		if ( $this->params ) {
			foreach ( $this->params as $key => $value ) {
				if ( $paramString != '' ) {
					$paramString .= ' ';
				}
				if ( is_array( $value ) ) {
					$filteredValue = [];
					foreach ( $value as $k => $v ) {
						$json = FormatJson::encode( $v );
						if ( $json === false || mb_strlen( $json ) > 512 ) {
							$filteredValue[$k] = gettype( $v ) . '(...)';
						} else {
							$filteredValue[$k] = $v;
						}
					}
					if ( count( $filteredValue ) <= 10 ) {
						$value = FormatJson::encode( $filteredValue );
					} else {
						$value = "array(" . count( $value ) . ")";
					}
				} elseif ( is_object( $value ) && !method_exists( $value, '__toString' ) ) {
					$value = "object(" . get_class( $value ) . ")";
				}

				$flatValue = (string)$value;
				if ( mb_strlen( $value ) > 1024 ) {
					$flatValue = "string(" . mb_strlen( $value ) . ")";
				}

				$paramString .= "$key={$flatValue}";
			}
		}

		$metaString = '';
		foreach ( $this->metadata as $key => $value ) {
			if ( is_scalar( $value ) && mb_strlen( $value ) < 1024 ) {
				$metaString .= ( $metaString ? ",$key=$value" : "$key=$value" );
			}
		}

		$s = $this->command;
		if ( is_object( $this->title ) ) {
			$s .= " {$this->title->getPrefixedDBkey()}";
		}
		if ( $paramString != '' ) {
			$s .= " $paramString";
		}
		if ( $metaString != '' ) {
			$s .= " ($metaString)";
		}

		return $s;
	}

	protected function setLastError( $error ) {
		$this->error = $error;
	}

	public function getLastError() {
		return $this->error;
	}
}
