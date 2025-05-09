<?php

use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\JobQueue;
use MediaWiki\JobQueue\JobQueueDB;
use MediaWiki\JobQueue\Jobs\DuplicateJob;
use MediaWiki\JobQueue\Jobs\NullJob;
use MediaWiki\MediaWikiServices;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @group JobQueue
 * @group medium
 * @group Database
 * @covers \MediaWiki\JobQueue\JobQueue
 */
class JobQueueTest extends MediaWikiIntegrationTestCase {
	protected ?JobQueue $queueRand;
	protected ?JobQueue $queueRandTTL;
	protected ?JobQueue $queueTimestamp;
	protected ?JobQueue $queueTimestampTTL;
	protected ?JobQueue $queueFifo;
	protected ?JobQueue $queueFifoTTL;

	protected function setUp(): void {
		global $wgJobTypeConf;
		parent::setUp();

		$services = $this->getServiceContainer();
		if ( $this->getCliArg( 'use-jobqueue' ) ) {
			$name = $this->getCliArg( 'use-jobqueue' );
			if ( !isset( $wgJobTypeConf[$name] ) ) {
				throw new RuntimeException( "No \$wgJobTypeConf entry for '$name'." );
			}
			$baseConfig = $wgJobTypeConf[$name];
		} else {
			$baseConfig = [ 'class' => JobQueueDBSingle::class ];
		}
		$baseConfig['type'] = 'null';
		$baseConfig['domain'] = WikiMap::getCurrentWikiDbDomain()->getId();
		$baseConfig['stash'] = new HashBagOStuff();
		$baseConfig['wanCache'] = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$baseConfig['idGenerator'] = $services->getGlobalIdGenerator();
		$variants = [
			'queueRand' => [ 'order' => 'random', 'claimTTL' => 0 ],
			'queueRandTTL' => [ 'order' => 'random', 'claimTTL' => 10 ],
			'queueTimestamp' => [ 'order' => 'timestamp', 'claimTTL' => 0 ],
			'queueTimestampTTL' => [ 'order' => 'timestamp', 'claimTTL' => 10 ],
			'queueFifo' => [ 'order' => 'fifo', 'claimTTL' => 0 ],
			'queueFifoTTL' => [ 'order' => 'fifo', 'claimTTL' => 10 ],
		];
		foreach ( $variants as $q => $settings ) {
			$this->$q = JobQueue::factory( $settings + $baseConfig );
		}
	}

	protected function tearDown(): void {
		foreach (
			[
				'queueRand', 'queueRandTTL', 'queueTimestamp', 'queueTimestampTTL',
				'queueFifo', 'queueFifoTTL'
			] as $q
		) {
			if ( $this->$q ) {
				$this->$q->delete();
			}
			$this->$q = null;
		}
		parent::tearDown();
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testGetType( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}
		$this->assertEquals( 'null', $queue->getType(), "Proper job type ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testBasicOperations( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertNull( $queue->push( $this->newJob() ), "Push worked ($desc)" );
		$this->assertNull( $queue->batchPush( [ $this->newJob() ] ), "Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 2, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );
		$jobs = iterator_to_array( $queue->getAllQueuedJobs() );
		$this->assertCount( 2, $jobs, "Queue iterator size is correct ($desc)" );

		$job1 = $queue->pop();
		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 1, $queue->getSize(), "Queue size is correct ($desc)" );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertSame( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$job2 = $queue->pop();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertEquals( 2, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job1 );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertSame( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job2 );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );

		$this->assertNull( $queue->batchPush( [ $this->newJob(), $this->newJob() ] ),
			"Push worked ($desc)" );
		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->delete();
		$queue->flushCaches();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testBasicDeduplication( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertNull(
			$queue->batchPush(
				[ $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() ]
			),
			"Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 1, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$this->assertNull(
			$queue->batchPush(
				[ $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() ]
			),
			"Push worked ($desc)"
		);

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 1, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$job1 = $queue->pop();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		if ( $recycles ) {
			$this->assertSame( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job1 );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testDeduplicationWhileClaimed( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$job = $this->newDedupedJob();
		$queue->push( $job );

		// De-duplication does not apply to already-claimed jobs
		$j = $queue->pop();
		$queue->push( $job );
		$queue->ack( $j );

		$j = $queue->pop();
		// Make sure ack() of the twin did not delete the sibling data
		$this->assertInstanceOf( NullJob::class, $j );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testRootDeduplication( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$root1 = Job::newRootJobParams( "nulljobspam:testId" ); // task ID/timestamp
		for ( $i = 0; $i < 5; ++$i ) {
			$this->assertNull( $queue->push( $this->newJob( 0, $root1 ) ), "Push worked ($desc)" );
		}
		$queue->deduplicateRootJob( $this->newJob( 0, $root1 ) );

		$root2 = $root1;
		# Add a second to UNIX epoch and format back to TS_MW
		$root2_ts = strtotime( $root2['rootJobTimestamp'] );
		$root2_ts++;
		$root2['rootJobTimestamp'] = wfTimestamp( TS_MW, $root2_ts );

		$this->assertNotEquals( $root1['rootJobTimestamp'], $root2['rootJobTimestamp'],
			"Root job signatures have different timestamps." );
		for ( $i = 0; $i < 5; ++$i ) {
			$this->assertNull( $queue->push( $this->newJob( 0, $root2 ) ), "Push worked ($desc)" );
		}
		$queue->deduplicateRootJob( $this->newJob( 0, $root2 ) );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 10, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$dupcount = 0;
		$jobs = [];
		do {
			$job = $queue->pop();
			if ( $job ) {
				$jobs[] = $job;
				$queue->ack( $job );
			}
			if ( $job instanceof DuplicateJob ) {
				++$dupcount;
			}
		} while ( $job );

		$this->assertCount( 10, $jobs, "Correct number of jobs popped ($desc)" );
		$this->assertEquals( 5, $dupcount, "Correct number of duplicate jobs popped ($desc)" );
	}

	/**
	 * @dataProvider provider_fifoQueueLists
	 */
	public function testJobOrder( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		for ( $i = 0; $i < 10; ++$i ) {
			$this->assertNull( $queue->push( $this->newJob( $i ) ), "Push worked ($desc)" );
		}

		for ( $i = 0; $i < 10; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $i, $params['i'], "Job popped from queue is FIFO ($desc)" );
			$queue->ack( $job );
		}

		$this->assertFalse( $queue->pop(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertSame( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertSame( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );
	}

	public function testQueueAggregateTable() {
		$this->hideDeprecated( 'JobQueue::getWiki' );

		$queue = $this->queueFifo;
		if ( !$queue || !method_exists( $queue, 'getServerQueuesWithJobs' ) ) {
			$this->markTestSkipped();
		}

		$this->assertNotContains(
			[ $queue->getType(), $queue->getWiki() ],
			$queue->getServerQueuesWithJobs(),
			"Null queue not in listing"
		);

		$queue->push( $this->newJob( 0 ) );

		$this->assertContains(
			[ $queue->getType(), $queue->getWiki() ],
			$queue->getServerQueuesWithJobs(),
			"Null queue in listing"
		);
	}

	public static function provider_queueLists() {
		return [
			[ 'queueRand', false, 'Random queue without ack()' ],
			[ 'queueRandTTL', true, 'Random queue with ack()' ],
			[ 'queueTimestamp', false, 'Time ordered queue without ack()' ],
			[ 'queueTimestampTTL', true, 'Time ordered queue with ack()' ],
			[ 'queueFifo', false, 'FIFO ordered queue without ack()' ],
			[ 'queueFifoTTL', true, 'FIFO ordered queue with ack()' ]
		];
	}

	public static function provider_fifoQueueLists() {
		return [
			[ 'queueFifo', false, 'Ordered queue without ack()' ],
			[ 'queueFifoTTL', true, 'Ordered queue with ack()' ]
		];
	}

	protected function newJob( $i = 0, $rootJob = [] ) {
		$params = [
			'namespace' => NS_MAIN,
			'title' => 'Main_Page',
			'lives' => 0,
			'usleep' => 0,
			'removeDuplicates' => 0,
			'i' => $i
		] + $rootJob;

		return $this->getServiceContainer()->getJobFactory()->newJob( 'null', $params );
	}

	protected function newDedupedJob( $i = 0, $rootJob = [] ) {
		$params = [
				'namespace' => NS_MAIN,
				'title' => 'Main_Page',
				'lives' => 0,
				'usleep' => 0,
				'removeDuplicates' => 1,
				'i' => $i
			] + $rootJob;

		return $this->getServiceContainer()->getJobFactory()->newJob( 'null', $params );
	}
}

class JobQueueDBSingle extends JobQueueDB {
	protected function getDB( $index ) {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		// Override to not use CONN_TRX_AUTOCOMMIT so that we see the same temporary `job` table
		return $lb->getConnection( $index, [], $this->domain );
	}
}
