<?php
/**
 * Job queue base code.
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
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * Class to handle enqueueing and running of background jobs
 *
 * @ingroup JobQueue
 * @since 1.21
 */
abstract class JobQueue {
	/** @var string DB domain ID */
	protected $domain;
	/** @var string Job type */
	protected $type;
	/** @var string Job priority for pop() */
	protected $order;
	/** @var int Time to live in seconds */
	protected $claimTTL;
	/** @var int Maximum number of times to try a job */
	protected $maxTries;
	/** @var string|bool Read only rationale (or false if r/w) */
	protected $readOnlyReason;
	/** @var StatsdDataFactoryInterface */
	protected $stats;

	/** @var BagOStuff */
	protected $dupCache;

	const QOS_ATOMIC = 1; // integer; "all-or-nothing" job insertions

	const ROOTJOB_TTL = 2419200; // integer; seconds to remember root jobs (28 days)

	/**
	 * @param array $params
	 * @throws JobQueueError
	 */
	protected function __construct( array $params ) {
		$this->domain = $params['domain'] ?? $params['wiki']; // b/c
		$this->type = $params['type'];
		$this->claimTTL = $params['claimTTL'] ?? 0;
		$this->maxTries = $params['maxTries'] ?? 3;
		if ( isset( $params['order'] ) && $params['order'] !== 'any' ) {
			$this->order = $params['order'];
		} else {
			$this->order = $this->optimalOrder();
		}
		if ( !in_array( $this->order, $this->supportedOrders() ) ) {
			throw new JobQueueError( __CLASS__ . " does not support '{$this->order}' order." );
		}
		$this->readOnlyReason = $params['readOnlyReason'] ?? false;
		$this->stats = $params['stats'] ?? new NullStatsdDataFactory();
		$this->dupCache = $params['stash'] ?? new EmptyBagOStuff();
	}

	/**
	 * Get a job queue object of the specified type.
	 * $params includes:
	 *   - class      : What job class to use (determines job type)
	 *   - domain     : Database domain ID of the wiki the jobs are for (defaults to current wiki)
	 *   - type       : The name of the job types this queue handles
	 *   - order      : Order that pop() selects jobs, one of "fifo", "timestamp" or "random".
	 *                  If "fifo" is used, the queue will effectively be FIFO. Note that job
	 *                  completion will not appear to be exactly FIFO if there are multiple
	 *                  job runners since jobs can take different times to finish once popped.
	 *                  If "timestamp" is used, the queue will at least be loosely ordered
	 *                  by timestamp, allowing for some jobs to be popped off out of order.
	 *                  If "random" is used, pop() will pick jobs in random order.
	 *                  Note that it may only be weakly random (e.g. a lottery of the oldest X).
	 *                  If "any" is choosen, the queue will use whatever order is the fastest.
	 *                  This might be useful for improving concurrency for job acquisition.
	 *   - claimTTL   : If supported, the queue will recycle jobs that have been popped
	 *                  but not acknowledged as completed after this many seconds. Recycling
	 *                  of jobs simply means re-inserting them into the queue. Jobs can be
	 *                  attempted up to three times before being discarded.
	 *   - readOnlyReason : Set this to a string to make the queue read-only.
	 *   - stash      : A BagOStuff instance that can be used for root job deduplication
	 *   - stats      : A StatsdDataFactoryInterface [optional]
	 *
	 * Queue classes should throw an exception if they do not support the options given.
	 *
	 * @param array $params
	 * @return JobQueue
	 * @throws JobQueueError
	 */
	final public static function factory( array $params ) {
		$class = $params['class'];
		if ( !class_exists( $class ) ) {
			throw new JobQueueError( "Invalid job queue class '$class'." );
		}
		$obj = new $class( $params );
		if ( !( $obj instanceof self ) ) {
			throw new JobQueueError( "Class '$class' is not a " . __CLASS__ . " class." );
		}

		return $obj;
	}

	/**
	 * @return string Database domain ID
	 */
	final public function getDomain() {
		return $this->domain;
	}

	/**
	 * @return string Wiki ID
	 * @deprecated 1.33
	 */
	final public function getWiki() {
		return WikiMap::getWikiIdFromDbDomain( $this->domain );
	}

	/**
	 * @return string Job type that this queue handles
	 */
	final public function getType() {
		return $this->type;
	}

	/**
	 * @return string One of (random, timestamp, fifo, undefined)
	 */
	final public function getOrder() {
		return $this->order;
	}

	/**
	 * Get the allowed queue orders for configuration validation
	 *
	 * @return array Subset of (random, timestamp, fifo, undefined)
	 */
	abstract protected function supportedOrders();

	/**
	 * Get the default queue order to use if configuration does not specify one
	 *
	 * @return string One of (random, timestamp, fifo, undefined)
	 */
	abstract protected function optimalOrder();

	/**
	 * Find out if delayed jobs are supported for configuration validation
	 *
	 * @return bool Whether delayed jobs are supported
	 */
	protected function supportsDelayedJobs() {
		return false; // not implemented
	}

	/**
	 * @return bool Whether delayed jobs are enabled
	 * @since 1.22
	 */
	final public function delayedJobsEnabled() {
		return $this->supportsDelayedJobs();
	}

	/**
	 * @return string|bool Read-only rational or false if r/w
	 * @since 1.27
	 */
	public function getReadOnlyReason() {
		return $this->readOnlyReason;
	}

	/**
	 * Quickly check if the queue has no available (unacquired, non-delayed) jobs.
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * If caching is used, this might return false when there are actually no jobs.
	 * If pop() is called and returns false then it should correct the cache. Also,
	 * calling flushCaches() first prevents this. However, this affect is typically
	 * not distinguishable from the race condition between isEmpty() and pop().
	 *
	 * @return bool
	 * @throws JobQueueError
	 */
	final public function isEmpty() {
		$res = $this->doIsEmpty();

		return $res;
	}

	/**
	 * @see JobQueue::isEmpty()
	 * @return bool
	 */
	abstract protected function doIsEmpty();

	/**
	 * Get the number of available (unacquired, non-delayed) jobs in the queue.
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * If caching is used, this number might be out of date for a minute.
	 *
	 * @return int
	 * @throws JobQueueError
	 */
	final public function getSize() {
		$res = $this->doGetSize();

		return $res;
	}

	/**
	 * @see JobQueue::getSize()
	 * @return int
	 */
	abstract protected function doGetSize();

	/**
	 * Get the number of acquired jobs (these are temporarily out of the queue).
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * If caching is used, this number might be out of date for a minute.
	 *
	 * @return int
	 * @throws JobQueueError
	 */
	final public function getAcquiredCount() {
		$res = $this->doGetAcquiredCount();

		return $res;
	}

	/**
	 * @see JobQueue::getAcquiredCount()
	 * @return int
	 */
	abstract protected function doGetAcquiredCount();

	/**
	 * Get the number of delayed jobs (these are temporarily out of the queue).
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * If caching is used, this number might be out of date for a minute.
	 *
	 * @return int
	 * @throws JobQueueError
	 * @since 1.22
	 */
	final public function getDelayedCount() {
		$res = $this->doGetDelayedCount();

		return $res;
	}

	/**
	 * @see JobQueue::getDelayedCount()
	 * @return int
	 */
	protected function doGetDelayedCount() {
		return 0; // not implemented
	}

	/**
	 * Get the number of acquired jobs that can no longer be attempted.
	 * Queue classes should use caching if they are any slower without memcached.
	 *
	 * If caching is used, this number might be out of date for a minute.
	 *
	 * @return int
	 * @throws JobQueueError
	 */
	final public function getAbandonedCount() {
		$res = $this->doGetAbandonedCount();

		return $res;
	}

	/**
	 * @see JobQueue::getAbandonedCount()
	 * @return int
	 */
	protected function doGetAbandonedCount() {
		return 0; // not implemented
	}

	/**
	 * Push one or more jobs into the queue.
	 * This does not require $wgJobClasses to be set for the given job type.
	 * Outside callers should use JobQueueGroup::push() instead of this function.
	 *
	 * @param IJobSpecification|IJobSpecification[] $jobs
	 * @param int $flags Bitfield (supports JobQueue::QOS_ATOMIC)
	 * @return void
	 * @throws JobQueueError
	 */
	final public function push( $jobs, $flags = 0 ) {
		$jobs = is_array( $jobs ) ? $jobs : [ $jobs ];
		$this->batchPush( $jobs, $flags );
	}

	/**
	 * Push a batch of jobs into the queue.
	 * This does not require $wgJobClasses to be set for the given job type.
	 * Outside callers should use JobQueueGroup::push() instead of this function.
	 *
	 * @param IJobSpecification[] $jobs
	 * @param int $flags Bitfield (supports JobQueue::QOS_ATOMIC)
	 * @return void
	 * @throws JobQueueError
	 */
	final public function batchPush( array $jobs, $flags = 0 ) {
		$this->assertNotReadOnly();

		if ( $jobs === [] ) {
			return; // nothing to do
		}

		foreach ( $jobs as $job ) {
			if ( $job->getType() !== $this->type ) {
				throw new JobQueueError(
					"Got '{$job->getType()}' job; expected a '{$this->type}' job." );
			} elseif ( $job->getReleaseTimestamp() && !$this->supportsDelayedJobs() ) {
				throw new JobQueueError(
					"Got delayed '{$job->getType()}' job; delays are not supported." );
			}
		}

		$this->doBatchPush( $jobs, $flags );

		foreach ( $jobs as $job ) {
			if ( $job->isRootJob() ) {
				$this->deduplicateRootJob( $job );
			}
		}
	}

	/**
	 * @see JobQueue::batchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 */
	abstract protected function doBatchPush( array $jobs, $flags );

	/**
	 * Pop a job off of the queue.
	 * This requires $wgJobClasses to be set for the given job type.
	 * Outside callers should use JobQueueGroup::pop() instead of this function.
	 *
	 * @throws JobQueueError
	 * @return Job|bool Returns false if there are no jobs
	 */
	final public function pop() {
		$this->assertNotReadOnly();

		$job = $this->doPop();

		// Flag this job as an old duplicate based on its "root" job...
		try {
			if ( $job && $this->isRootJobOldDuplicate( $job ) ) {
				$this->incrStats( 'dupe_pops', $this->type );
				$job = DuplicateJob::newFromJob( $job ); // convert to a no-op
			}
		} catch ( Exception $e ) {
			// don't lose jobs over this
		}

		return $job;
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job|bool
	 */
	abstract protected function doPop();

	/**
	 * Acknowledge that a job was completed.
	 *
	 * This does nothing for certain queue classes or if "claimTTL" is not set.
	 * Outside callers should use JobQueueGroup::ack() instead of this function.
	 *
	 * @param Job $job
	 * @return void
	 * @throws JobQueueError
	 */
	final public function ack( Job $job ) {
		$this->assertNotReadOnly();
		if ( $job->getType() !== $this->type ) {
			throw new JobQueueError( "Got '{$job->getType()}' job; expected '{$this->type}'." );
		}

		$this->doAck( $job );
	}

	/**
	 * @see JobQueue::ack()
	 * @param Job $job
	 */
	abstract protected function doAck( Job $job );

	/**
	 * Register the "root job" of a given job into the queue for de-duplication.
	 * This should only be called right *after* all the new jobs have been inserted.
	 * This is used to turn older, duplicate, job entries into no-ops. The root job
	 * information will remain in the registry until it simply falls out of cache.
	 *
	 * This requires that $job has two special fields in the "params" array:
	 *   - rootJobSignature : hash (e.g. SHA1) that identifies the task
	 *   - rootJobTimestamp : TS_MW timestamp of this instance of the task
	 *
	 * A "root job" is a conceptual job that consist of potentially many smaller jobs
	 * that are actually inserted into the queue. For example, "refreshLinks" jobs are
	 * spawned when a template is edited. One can think of the task as "update links
	 * of pages that use template X" and an instance of that task as a "root job".
	 * However, what actually goes into the queue are range and leaf job subtypes.
	 * Since these jobs include things like page ID ranges and DB master positions,
	 * and can morph into smaller jobs recursively, simple duplicate detection
	 * for individual jobs being identical (like that of job_sha1) is not useful.
	 *
	 * In the case of "refreshLinks", if these jobs are still in the queue when the template
	 * is edited again, we want all of these old refreshLinks jobs for that template to become
	 * no-ops. This can greatly reduce server load, since refreshLinks jobs involves parsing.
	 * Essentially, the new batch of jobs belong to a new "root job" and the older ones to a
	 * previous "root job" for the same task of "update links of pages that use template X".
	 *
	 * This does nothing for certain queue classes.
	 *
	 * @param IJobSpecification $job
	 * @throws JobQueueError
	 * @return bool
	 */
	final public function deduplicateRootJob( IJobSpecification $job ) {
		$this->assertNotReadOnly();
		if ( $job->getType() !== $this->type ) {
			throw new JobQueueError( "Got '{$job->getType()}' job; expected '{$this->type}'." );
		}

		return $this->doDeduplicateRootJob( $job );
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @param IJobSpecification $job
	 * @throws JobQueueError
	 * @return bool
	 */
	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		if ( !$job->hasRootJobParams() ) {
			throw new JobQueueError( "Cannot register root job; missing parameters." );
		}
		$params = $job->getRootJobParams();

		$key = $this->getRootJobCacheKey( $params['rootJobSignature'] );
		// Callers should call JobQueueGroup::push() before this method so that if the insert
		// fails, the de-duplication registration will be aborted. Since the insert is
		// deferred till "transaction idle", do the same here, so that the ordering is
		// maintained. Having only the de-duplication registration succeed would cause
		// jobs to become no-ops without any actual jobs that made them redundant.
		$timestamp = $this->dupCache->get( $key ); // current last timestamp of this job
		if ( $timestamp && $timestamp >= $params['rootJobTimestamp'] ) {
			return true; // a newer version of this root job was enqueued
		}

		// Update the timestamp of the last root job started at the location...
		return $this->dupCache->set( $key, $params['rootJobTimestamp'], self::ROOTJOB_TTL );
	}

	/**
	 * Check if the "root" job of a given job has been superseded by a newer one
	 *
	 * @param Job $job
	 * @throws JobQueueError
	 * @return bool
	 */
	final protected function isRootJobOldDuplicate( Job $job ) {
		if ( $job->getType() !== $this->type ) {
			throw new JobQueueError( "Got '{$job->getType()}' job; expected '{$this->type}'." );
		}
		$isDuplicate = $this->doIsRootJobOldDuplicate( $job );

		return $isDuplicate;
	}

	/**
	 * @see JobQueue::isRootJobOldDuplicate()
	 * @param Job $job
	 * @return bool
	 */
	protected function doIsRootJobOldDuplicate( Job $job ) {
		if ( !$job->hasRootJobParams() ) {
			return false; // job has no de-deplication info
		}
		$params = $job->getRootJobParams();

		$key = $this->getRootJobCacheKey( $params['rootJobSignature'] );
		// Get the last time this root job was enqueued
		$timestamp = $this->dupCache->get( $key );

		// Check if a new root job was started at the location after this one's...
		return ( $timestamp && $timestamp > $params['rootJobTimestamp'] );
	}

	/**
	 * @param string $signature Hash identifier of the root job
	 * @return string
	 */
	protected function getRootJobCacheKey( $signature ) {
		return $this->dupCache->makeGlobalKey(
			'jobqueue',
			$this->domain,
			$this->type,
			'rootjob',
			$signature
		);
	}

	/**
	 * Deleted all unclaimed and delayed jobs from the queue
	 *
	 * @throws JobQueueError
	 * @since 1.22
	 * @return void
	 */
	final public function delete() {
		$this->assertNotReadOnly();

		$this->doDelete();
	}

	/**
	 * @see JobQueue::delete()
	 * @throws JobQueueError
	 */
	protected function doDelete() {
		throw new JobQueueError( "This method is not implemented." );
	}

	/**
	 * Wait for any replica DBs or backup servers to catch up.
	 *
	 * This does nothing for certain queue classes.
	 *
	 * @return void
	 * @throws JobQueueError
	 */
	final public function waitForBackups() {
		$this->doWaitForBackups();
	}

	/**
	 * @see JobQueue::waitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
	}

	/**
	 * Clear any process and persistent caches
	 *
	 * @return void
	 */
	final public function flushCaches() {
		$this->doFlushCaches();
	}

	/**
	 * @see JobQueue::flushCaches()
	 * @return void
	 */
	protected function doFlushCaches() {
	}

	/**
	 * Get an iterator to traverse over all available jobs in this queue.
	 * This does not include jobs that are currently acquired or delayed.
	 * Note: results may be stale if the queue is concurrently modified.
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 */
	abstract public function getAllQueuedJobs();

	/**
	 * Get an iterator to traverse over all delayed jobs in this queue.
	 * Note: results may be stale if the queue is concurrently modified.
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.22
	 */
	public function getAllDelayedJobs() {
		return new ArrayIterator( [] ); // not implemented
	}

	/**
	 * Get an iterator to traverse over all claimed jobs in this queue
	 *
	 * Callers should be quick to iterator over it or few results
	 * will be returned due to jobs being acknowledged and deleted
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.26
	 */
	public function getAllAcquiredJobs() {
		return new ArrayIterator( [] ); // not implemented
	}

	/**
	 * Get an iterator to traverse over all abandoned jobs in this queue
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.25
	 */
	public function getAllAbandonedJobs() {
		return new ArrayIterator( [] ); // not implemented
	}

	/**
	 * Do not use this function outside of JobQueue/JobQueueGroup
	 *
	 * @return string
	 * @since 1.22
	 */
	public function getCoalesceLocationInternal() {
		return null;
	}

	/**
	 * Check whether each of the given queues are empty.
	 * This is used for batching checks for queues stored at the same place.
	 *
	 * @param array $types List of queues types
	 * @return array|null (list of non-empty queue types) or null if unsupported
	 * @throws JobQueueError
	 * @since 1.22
	 */
	final public function getSiblingQueuesWithJobs( array $types ) {
		return $this->doGetSiblingQueuesWithJobs( $types );
	}

	/**
	 * @see JobQueue::getSiblingQueuesWithJobs()
	 * @param array $types List of queues types
	 * @return array|null (list of queue types) or null if unsupported
	 */
	protected function doGetSiblingQueuesWithJobs( array $types ) {
		return null; // not supported
	}

	/**
	 * Check the size of each of the given queues.
	 * For queues not served by the same store as this one, 0 is returned.
	 * This is used for batching checks for queues stored at the same place.
	 *
	 * @param array $types List of queues types
	 * @return array|null (job type => whether queue is empty) or null if unsupported
	 * @throws JobQueueError
	 * @since 1.22
	 */
	final public function getSiblingQueueSizes( array $types ) {
		return $this->doGetSiblingQueueSizes( $types );
	}

	/**
	 * @see JobQueue::getSiblingQueuesSize()
	 * @param array $types List of queues types
	 * @return array|null (list of queue types) or null if unsupported
	 */
	protected function doGetSiblingQueueSizes( array $types ) {
		return null; // not supported
	}

	/**
	 * @throws JobQueueReadOnlyError
	 */
	protected function assertNotReadOnly() {
		if ( $this->readOnlyReason !== false ) {
			throw new JobQueueReadOnlyError( "Job queue is read-only: {$this->readOnlyReason}" );
		}
	}

	/**
	 * Call wfIncrStats() for the queue overall and for the queue type
	 *
	 * @param string $key Event type
	 * @param string $type Job type
	 * @param int $delta
	 * @since 1.22
	 */
	protected function incrStats( $key, $type, $delta = 1 ) {
		$this->stats->updateCount( "jobqueue.{$key}.all", $delta );
		$this->stats->updateCount( "jobqueue.{$key}.{$type}", $delta );
	}
}
