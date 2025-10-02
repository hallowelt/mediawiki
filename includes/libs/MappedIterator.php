<?php
/**
 * Convenience class for generating iterators from iterators.
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Convenience class for generating iterators from iterators.
 *
 * @since 1.21
 */
class MappedIterator extends FilterIterator {
	/** @var callable */
	protected $vCallback;
	/** @var callable|null */
	protected $aCallback;
	/** @var array */
	protected $cache = [];

	/** @var bool whether rewind() has been called */
	protected $rewound = false;

	/**
	 * Build a new iterator from a base iterator by having the former wrap the
	 * later, returning the result of "value" callback for each current() invocation.
	 * The callback takes the result of current() on the base iterator as an argument.
	 * The keys of the base iterator are reused verbatim.
	 *
	 * An "accept" callback can also be provided which will be called for each value in
	 * the base iterator (post-callback) and will return true if that value should be
	 * included in iteration of the MappedIterator (otherwise it will be filtered out).
	 *
	 * @param Iterator|array $iter
	 * @param callable $vCallback Value transformation callback
	 * @param array $options Options map (includes "accept") (since 1.22)
	 * @phan-param array{accept?:callable} $options
	 * @throws UnexpectedValueException
	 */
	public function __construct( $iter, $vCallback, array $options = [] ) {
		if ( is_array( $iter ) ) {
			$baseIterator = new ArrayIterator( $iter );
		} elseif ( $iter instanceof Iterator ) {
			$baseIterator = $iter;
		} else {
			throw new UnexpectedValueException( "Invalid base iterator provided." );
		}
		parent::__construct( $baseIterator );
		$this->vCallback = $vCallback;
		$this->aCallback = $options['accept'] ?? null;
	}

	public function next(): void {
		$this->cache = [];
		parent::next();
	}

	public function rewind(): void {
		$this->rewound = true;
		$this->cache = [];
		parent::rewind();
	}

	public function accept(): bool {
		$inner = $this->getInnerIterator();
		'@phan-var Iterator $inner';
		$value = ( $this->vCallback )( $inner->current() );
		$ok = ( $this->aCallback ) ? ( $this->aCallback )( $value ) : true;
		if ( $ok ) {
			$this->cache['current'] = $value;
		}

		return $ok;
	}

	/** @inheritDoc */
	#[\ReturnTypeWillChange]
	public function key() {
		$this->init();

		return parent::key();
	}

	public function valid(): bool {
		$this->init();

		return parent::valid();
	}

	/** @inheritDoc */
	#[\ReturnTypeWillChange]
	public function current() {
		$this->init();
		if ( parent::valid() ) {
			return $this->cache['current'];
		} else {
			return null; // out of range
		}
	}

	/**
	 * Obviate the usual need for rewind() before using a FilterIterator in a manual loop
	 */
	protected function init() {
		if ( !$this->rewound ) {
			$this->rewind();
		}
	}
}
