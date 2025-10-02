<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Exceptions;

/**
 * @newable
 * @since 1.22
 * @ingroup JobQueue
 */
class JobQueueConnectionError extends JobQueueError {
}

/** @deprecated class alias since 1.44 */
class_alias( JobQueueConnectionError::class, 'JobQueueConnectionError' );
