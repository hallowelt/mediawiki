<?php
/**
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
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

use InvalidArgumentException;
use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\Sample;
use Wikimedia\Stats\StatsUtils;

/**
 * Base Metric Implementation V1
 *
 * Implements shared Metric functionality:
 *   * Label validation and handling
 *   * Sample rate validation and handling
 *   * Sample generation
 *   * Common properties
 *   * StatsD transition support
 *
 * @author Cole White
 * @since 1.41
 */
class BaseMetric implements BaseMetricInterface {

	private float $sampleRate = StatsUtils::DEFAULT_SAMPLE_RATE;
	private string $name;
	private string $component;

	/** @var array key-value pairs of metric-specific labels */
	private array $workingLabels = [];

	/** @var string[] ordered array of label keys */
	private array $labelKeys = [];

	/** @var Sample[] */
	private array $samples = [];

	private ?IBufferingStatsdDataFactory $statsdDataFactory = null;

	/** @var string[] */
	private array $statsdNamespaces = [];

	/** @inheritDoc */
	public function __construct( string $component, string $name ) {
		$this->component = $component;
		$this->name = StatsUtils::normalizeString( $name );
	}

	/** @inheritDoc */
	public function addSample( Sample $sample ): void {
		$this->samples[] = $sample;
	}

	/** @inheritDoc */
	public function setSampleRate( float $sampleRate ): void {
		if ( $this->hasSamples() ) {
			throw new IllegalOperationException(
				"Stats: Cannot change sample rate on metric with recorded samples." );
		}
		StatsUtils::validateNewSampleRate( $sampleRate );
		$this->sampleRate = $sampleRate;
	}

	/** @inheritDoc */
	public function getName(): string {
		return $this->name;
	}

	/** @inheritDoc */
	public function getSampleRate(): float {
		return $this->sampleRate;
	}

	/** @inheritDoc */
	public function getSamples(): array {
		return StatsUtils::getFilteredSamples( $this->sampleRate, $this->samples );
	}

	/** @inheritDoc */
	public function getSampleCount(): int {
		return count( $this->samples );
	}

	/** @inheritDoc */
	public function addLabel( string $key, string $value ): void {
		StatsUtils::validateLabelValue( $value );
		$key = StatsUtils::normalizeString( $key );
		StatsUtils::validateLabelKey( $key );
		$this->addLabelKey( $key );
		$this->workingLabels[$key] = StatsUtils::normalizeString( $value );
	}

	/** @inheritDoc */
	public function getStatsdDataFactory() {
		return $this->statsdDataFactory;
	}

	/** @inheritDoc */
	public function withStatsdDataFactory( $statsdDataFactory ): BaseMetric {
		$this->statsdDataFactory = $statsdDataFactory;
		return $this;
	}

	/** @inheritDoc */
	public function setStatsdNamespaces( $statsdNamespaces ): void {
		if ( $this->statsdDataFactory === null ) {
			return;
		}
		$statsdNamespaces = is_array( $statsdNamespaces ) ? $statsdNamespaces : [ $statsdNamespaces ];

		foreach ( $statsdNamespaces as $namespace ) {
			if ( $namespace === '' ) {
				throw new InvalidArgumentException( "Stats: StatsD namespace cannot be empty." );
			}
			if ( !is_string( $namespace ) ) {
				throw new InvalidArgumentException( "Stats: StatsD namespace must be a string." );
			}
		}
		$this->statsdNamespaces = $statsdNamespaces;
	}

	/** @inheritDoc */
	public function getStatsdNamespaces(): array {
		return $this->statsdNamespaces;
	}

	/**
	 * Registers a label key
	 *
	 * @param string $key
	 * @return void
	 */
	private function addLabelKey( string $key ): void {
		if ( in_array( $key, $this->labelKeys, true ) ) {
			return;  // key already exists
		}
		if ( $this->hasSamples() ) {
			throw new IllegalOperationException(
				"Stats: Cannot add labels to a metric containing samples for '" . $this->name . "'"
			);
		}
		$this->labelKeys[] = $key;
	}

	/** @return string[] */
	public function getLabelKeys(): array {
		return $this->labelKeys;
	}

	/**
	 * Get label values in the order of labelKeys.
	 *
	 * @return string[]
	 */
	public function getLabelValues(): array {
		$output = [];
		# make sure all labels are accounted for
		if ( array_diff( $this->labelKeys, array_keys( $this->workingLabels ) ) ) {
			throw new IllegalOperationException(
				"Stats: Cannot associate label keys with label values: "
				. "Not all initialized labels have an assigned value." );
		}

		foreach ( $this->labelKeys as $labelKey ) {
			$output[] = $this->workingLabels[$labelKey];
		}
		return $output;
	}

	/** @inheritDoc */
	public function clearLabels(): void {
		$this->workingLabels = [];
	}

	/** @inheritDoc */
	public function getComponent(): string {
		return $this->component;
	}

	private function hasSamples(): bool {
		return $this->samples !== [];
	}

}
