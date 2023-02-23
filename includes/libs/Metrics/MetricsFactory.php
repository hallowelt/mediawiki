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

namespace Wikimedia\Metrics;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use TypeError;
use UDPTransport;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;
use Wikimedia\Metrics\Exceptions\UndefinedPrefixException;
use Wikimedia\Metrics\Metrics\BaseMetric;
use Wikimedia\Metrics\Metrics\CounterMetric;
use Wikimedia\Metrics\Metrics\GaugeMetric;
use Wikimedia\Metrics\Metrics\NullMetric;
use Wikimedia\Metrics\Metrics\TimingMetric;

/**
 * MetricsFactory Implementation
 *
 * This is the primary interface for validating metrics definitions
 * caching defined metrics, and returning metric instances from cache
 * if previously defined.
 *
 * @author Cole White
 * @since 1.38
 */
class MetricsFactory {

	/** @var array */
	private const DEFAULT_METRIC_CONFIG = [
		// 'name' => required,
		// 'component' => required,
		'labels' => [],
		'sampleRate' => 1.0,
		'service' => '',
		'format' => 'statsd',
	];

	/** @var MetricsCache */
	private MetricsCache $cache;

	/** @var string|null */
	private ?string $target;

	/** @var int */
	private int $format;

	/** @var string */
	private string $prefix;

	/** @var LoggerInterface */
	private LoggerInterface $logger;

	/**
	 * MetricsFactory builds, configures, and caches Metrics.
	 *
	 * @param array $config associative array:
	 *   - prefix (string): The prefix applied to all metrics.  This could be the service name.
	 *   - target (string): The URI of the statsd/statsd-exporter server.
	 *   - format (string): The output format. See: MetricsFactory::SUPPORTED_OUTPUT_FORMATS
	 *   - component: (string) The MediaWiki component this MetricsFactory instance is for.
	 * @param MetricsCache $cache
	 * @param LoggerInterface $logger
	 */
	public function __construct( array $config, MetricsCache $cache, LoggerInterface $logger ) {
		$this->cache = $cache;
		$this->logger = $logger;
		$this->target = $config['target'] ?? null;
		$this->prefix = MetricUtils::normalizeString( $config['prefix'] ?? '' );
		$this->validateInstanceConfig();
		$this->format = OutputFormats::getFormatFromString( $config['format'] ?? 'null' );
	}

	/**
	 * Makes a new CounterMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return CounterMetric|NullMetric
	 */
	public function getCounter( array $config = [] ) {
		return $this->getMetric( $config, CounterMetric::class );
	}

	/**
	 * Makes a new GaugeMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   name: (string) The metric name.
	 *   component: (string) The component generating the metric.
	 *   labels: (array) Labels that further identify the metric.
	 * @return GaugeMetric|NullMetric
	 */
	public function getGauge( array $config = [] ) {
		return $this->getMetric( $config, GaugeMetric::class );
	}

	/**
	 * Makes a new TimingMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return TimingMetric|NullMetric
	 */
	public function getTiming( array $config = [] ) {
		return $this->getMetric( $config, TimingMetric::class );
	}

	/**
	 * Send all buffered metrics to the target and destroy the cache.
	 */
	public function flush(): void {
		if ( $this->target ) {
			$this->send( UDPTransport::newFromString( $this->target ) );
		}
		$this->cache->clear();
	}

	/**
	 * Render the buffer of samples, group them into payloads, and send them through the
	 * provided UDPTransport instance.
	 *
	 * @param UDPTransport $transport
	 */
	protected function send( UDPTransport $transport ): void {
		if ( $this->format > OutputFormats::NULL ) {
			$renderer = new MetricsRenderer( $this->cache );
			$emitter = new MetricsUDPEmitter( $renderer->withFormat( $this->format )->withPrefix( $this->prefix ) );
			$emitter->withTransport( $transport )->send();
		}
	}

	/**
	 * Fetches a metric from cache or makes a new metric.
	 *
	 * If a metric name collision occurs, returns a NullMetric to suppress runtime exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @param string $className
	 * @return CounterMetric|TimingMetric|GaugeMetric|NullMetric
	 */
	private function getMetric( array $config, string $className ) {
		$config = $this->getValidConfig( $config );
		$name = MetricUtils::normalizeString( $config['name'] );
		$component = MetricUtils::normalizeString( $config['component'] );
		try {
			MetricUtils::validateMetricName( $name );
			$metric = $this->cache->get( $component, $name, $className );
		} catch ( TypeError | InvalidArgumentException | InvalidConfigurationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		if ( $metric === null ) {
			$metric = new $className( new BaseMetric( $component, $name ), $this->logger );
			$metric->withSampleRate( $config['sampleRate'] );
			foreach ( $config['labels'] as $labelKey ) {
				$metric->withLabelKey( MetricUtils::normalizeString( $labelKey ) );
			}
			$this->cache->set( $component, $name, $metric );
		} else {
			try {
				MetricUtils::validateLabels( $metric->getLabelKeys(), $config['labels'] );
			} catch ( InvalidLabelsException $ex ) {
				$this->logger->error( $ex->getMessage() );
				return new NullMetric;
			}
		}
		return $metric;
	}

	/**
	 * Renders a valid configuration.
	 *
	 * 1. Checks for required options.
	 * 2. Normalize provided options.
	 * 3. Merge provided configuration with default configuration.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return array
	 * @throws InvalidConfigurationException
	 */
	private function getValidConfig( array $config = [] ): array {
		if ( !isset( $config['name'] ) ) {
			throw new InvalidConfigurationException(
				'\'name\' configuration option is required and cannot be empty.'
			);
		}
		if ( !isset( $config['component'] ) ) {
			throw new InvalidConfigurationException(
				'\'component\' configuration option is required and cannot be empty.'
			);
		}

		$config['prefix'] = $this->prefix;
		$config['format'] = $this->format;
		$config['name'] = MetricUtils::normalizeString( $config['name'] );
		$config['component'] = MetricUtils::normalizeString( $config['component'] );
		$config['labels'] = MetricUtils::normalizeArray( $config['labels'] ?? [] );

		return $config + self::DEFAULT_METRIC_CONFIG;
	}

	/**
	 * Throw exception on invalid instance configuration.
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	private function validateInstanceConfig(): void {
		if ( $this->prefix === "" ) {
			throw new UndefinedPrefixException( "'prefix' option is required and cannot be empty." );
		}
	}
}
