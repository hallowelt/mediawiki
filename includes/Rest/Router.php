<?php

namespace MediaWiki\Rest;

use BagOStuff;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\RouteFileModule;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use Throwable;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * The REST router is responsible for gathering module configuration, matching
 * an input path against the defined modules, and constructing
 * and executing the relevant module for a request.
 */
class Router {
	private const PREFIX_PATTERN = '!^/([-_.\w]+(?:/v\d+)?)(/.*)$!';

	/** @var string[] */
	private $routeFiles;

	/** @var array[] */
	private $extraRoutes;

	/** @var null|array[] */
	private $moduleMap = null;

	/** @var Module[] */
	private $modules = [];

	/** @var int[]|null */
	private $moduleFileTimestamps = null;

	/** @var string */
	private $baseUrl;

	/** @var string */
	private $privateBaseUrl;

	/** @var string */
	private $rootPath;

	/** @var BagOStuff */
	private $cacheBag;

	/** @var string|null */
	private $configHash = null;

	/** @var ResponseFactory */
	private $responseFactory;

	/** @var BasicAuthorizerInterface */
	private $basicAuth;

	/** @var Authority */
	private $authority;

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var Validator */
	private $restValidator;

	/** @var CorsUtils|null */
	private $cors;

	/** @var ErrorReporter */
	private $errorReporter;

	/** @var HookContainer */
	private $hookContainer;

	/** @var Session */
	private $session;

	/** @var ?StatsdDataFactoryInterface */
	private $stats = null;

	/**
	 * @internal
	 * @var array
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CanonicalServer,
		MainConfigNames::InternalServer,
		MainConfigNames::RestPath,
	];

	/**
	 * @param string[] $routeFiles
	 * @param array[] $extraRoutes
	 * @param ServiceOptions $options
	 * @param BagOStuff $cacheBag A cache in which to store the matcher trees
	 * @param ResponseFactory $responseFactory
	 * @param BasicAuthorizerInterface $basicAuth
	 * @param Authority $authority
	 * @param ObjectFactory $objectFactory
	 * @param Validator $restValidator
	 * @param ErrorReporter $errorReporter
	 * @param HookContainer $hookContainer
	 * @param Session $session
	 * @internal
	 */
	public function __construct(
		array $routeFiles,
		array $extraRoutes,
		ServiceOptions $options,
		BagOStuff $cacheBag,
		ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth,
		Authority $authority,
		ObjectFactory $objectFactory,
		Validator $restValidator,
		ErrorReporter $errorReporter,
		HookContainer $hookContainer,
		Session $session
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->routeFiles = $routeFiles;
		$this->extraRoutes = $extraRoutes;
		$this->baseUrl = $options->get( MainConfigNames::CanonicalServer );
		$this->privateBaseUrl = $options->get( MainConfigNames::InternalServer );
		$this->rootPath = $options->get( MainConfigNames::RestPath );
		$this->cacheBag = $cacheBag;
		$this->responseFactory = $responseFactory;
		$this->basicAuth = $basicAuth;
		$this->authority = $authority;
		$this->objectFactory = $objectFactory;
		$this->restValidator = $restValidator;
		$this->errorReporter = $errorReporter;
		$this->hookContainer = $hookContainer;
		$this->session = $session;
	}

	/**
	 * Remove the path prefix $this->rootPath. Return the part of the path with the
	 * prefix removed, or false if the prefix did not match.
	 *
	 * @param string $path
	 * @return false|string
	 */
	private function getRelativePath( $path ) {
		if ( !str_starts_with( $path, $this->rootPath ) ) {
			return false;
		}
		return substr( $path, strlen( $this->rootPath ) );
	}

	/**
	 * @param string $fullPath
	 *
	 * @return string[] [ string $module, string $path ]
	 */
	private function splitPath( string $fullPath ): array {
		$pathWithModule = $this->getRelativePath( $fullPath );

		if ( !$pathWithModule ) {
			throw new LocalizedHttpException(
				( new MessageValue( 'rest-prefix-mismatch' ) )
					->plaintextParams( $fullPath, $this->rootPath ),
				404
			);
		}

		if ( !preg_match( self::PREFIX_PATTERN, $pathWithModule, $matches ) ) {
			throw new LocalizedHttpException(
				( new MessageValue( 'rest-bad-prefix' ) )
					->plaintextParams( $pathWithModule ),
				404
			);
		}

		[ , $module, $pathUnderModule ] = $matches;

		if ( !$this->getModuleInfo( $module ) ) {
			// Prefix doesn't match any module, try the prefix-less module...
			// TODO: At some point in the future, we'll want to warn and redirect...
			$module = '';
			$pathUnderModule = $pathWithModule;
		}

		return [ $module, $pathUnderModule ];
	}

	/**
	 * Get the cache data, or false if it is missing or invalid
	 *
	 * @return ?array
	 */
	private function fetchCachedModuleMap(): ?array {
		$moduleMapCacheKey = $this->getModuleMapCacheKey();
		$cacheData = $this->cacheBag->get( $moduleMapCacheKey );
		if ( $cacheData && $cacheData[Module::CACHE_CONFIG_HASH_KEY] === $this->getModuleMapHash() ) {
			unset( $cacheData[Module::CACHE_CONFIG_HASH_KEY] );
			return $cacheData;
		} else {
			return null;
		}
	}

	private function fetchCachedModuleData( string $module ): ?array {
		$moduleDataCacheKey = $this->getModuleDataCacheKey( $module );
		$cacheData = $this->cacheBag->get( $moduleDataCacheKey );
		return $cacheData ?: null;
	}

	private function cacheModuleMap( array $map ) {
		$map[Module::CACHE_CONFIG_HASH_KEY] = $this->getModuleMapHash();
		$moduleMapCacheKey = $this->getModuleMapCacheKey();
		$this->cacheBag->set( $moduleMapCacheKey, $map );
	}

	private function cacheModuleData( string $module, array $map ) {
		$moduleDataCacheKey = $this->getModuleDataCacheKey( $module );
		$this->cacheBag->set( $moduleDataCacheKey, $map );
	}

	private function getModuleDataCacheKey( string $module ): string {
		if ( $module === '' ) {
			// Proper key for the prefix-less module.
			$module = '-';
		}
		return $this->cacheBag->makeKey( __CLASS__, 'module', $module );
	}

	private function getModuleMapCacheKey(): string {
		return $this->cacheBag->makeKey( __CLASS__, 'map', '1' );
	}

	/**
	 * Get a config version hash for cache invalidation
	 */
	private function getModuleMapHash(): string {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				$this->extraRoutes,
				$this->getModuleFileTimestamps()
			] ) );
		}
		return $this->configHash;
	}

	private function buildModuleMap(): array {
		$modules = [];
		$noPrefixFiles = [];

		foreach ( $this->routeFiles as $file ) {
			// NOTE: we end up loading the file here (for the meta-data) as well
			// as in the Module object (for the routes). But since we have
			// caching on both levels, that shouldn't matter.
			$spec = Module::loadJsonFile( $file );

			if ( isset( $spec['routes'] ) ) {
				if ( !isset( $spec['module'] ) ) {
					throw new ModuleConfigurationException(
						"Missing module name in $file"
					);
				}

				// Intermediate format, containing a "routes" key and a prefix
				// in the "module" field.
				$name = $spec['module'];

				if ( isset( $modules[$name] ) ) {
					$otherFiles = implode( ' and ', $modules[$name]['routeFiles'] );
					throw new ModuleConfigurationException(
						"Duplicate module $name in $file, also used in $otherFiles"
					);
				}

				$modules[$name] = [
					'class' => RouteFileModule::class,
					'pathPrefix' => $name,
					'routeFiles' => [ $file ]
				];
				// TODO: also support OpenAPI spec files
			} else {
				// Old-style route file containing a flat list of routes.
				$noPrefixFiles[] = $file;
			}
		}

		// The prefix-less module will be used when no prefix is matched.
		// It provides a mechanism to integrate extra routes and route files
		// registered by extensions.
		if ( $noPrefixFiles || $this->extraRoutes ) {
			$modules[''] = [
				'class' => RouteFileModule::class,
				'pathPrefix' => '',
				'routeFiles' => $noPrefixFiles,
				'extraRoutes' => $this->extraRoutes,
			];
		}

		return $modules;
	}

	/**
	 * Get an array of last modification times of the defined route files.
	 *
	 * @return int[] Last modification times
	 */
	private function getModuleFileTimestamps() {
		if ( $this->moduleFileTimestamps === null ) {
			$this->moduleFileTimestamps = [];
			foreach ( $this->routeFiles as $fileName ) {
				$this->moduleFileTimestamps[$fileName] = filemtime( $fileName );
			}
		}
		return $this->moduleFileTimestamps;
	}

	private function getModuleMap(): array {
		if ( !$this->moduleMap ) {
			$map = $this->fetchCachedModuleMap();

			if ( !$map ) {
				$map = $this->buildModuleMap();
				$this->cacheModuleMap( $map );
			}

			$this->moduleMap = $map;
		}
		return $this->moduleMap;
	}

	private function getModuleInfo( $module ): ?array {
		$map = $this->getModuleMap();
		return $map[$module] ?? null;
	}

	/**
	 * @return string[]
	 */
	public function getModuleNames(): array {
		return array_keys( $this->getModuleMap() );
	}

	public function getModuleForPath( string $fullPath ): ?Module {
		[ $moduleName, ] = $this->splitPath( $fullPath );
		return $this->getModule( $moduleName );
	}

	public function getModule( string $name ): ?Module {
		if ( isset( $this->modules[$name] ) ) {
			return $this->modules[$name];
		}

		$info = $this->getModuleInfo( $name );

		if ( !$info ) {
			return null;
		}

		$module = new RouteFileModule(
			$info['routeFiles'] ?? [],
			$info['extraRoutes'] ?? [],
			$this,
			$info['pathPrefix'] ?? $name,
			$this->responseFactory,
			$this->basicAuth,
			$this->objectFactory,
			$this->restValidator,
			$this->errorReporter
		);

		$cacheData = $this->fetchCachedModuleData( $name );

		if ( $cacheData !== null ) {
			$cacheOk = $module->initFromCacheData( $cacheData );
		} else {
			$cacheOk = false;
		}

		if ( !$cacheOk ) {
			$cacheData = $module->getCacheData();
			$this->cacheModuleData( $name, $cacheData );
		}

		if ( $this->cors ) {
			$module->setCors( $this->cors );
		}

		if ( $this->stats ) {
			$module->setStats( $this->stats );
		}

		$this->modules[$name] = $module;
		return $module;
	}

	/**
	 * @since 1.42
	 */
	public function getRoutePath(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		$routeWithModulePrefix = $this->substPathParams( $routeWithModulePrefix, $pathParams );
		$path = $this->rootPath . $routeWithModulePrefix;
		return wfAppendQuery( $path, $queryParams );
	}

	public function getRouteUrl(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->baseUrl . $this->getRoutePath( $routeWithModulePrefix, $pathParams, $queryParams );
	}

	public function getPrivateRouteUrl(
		string $routeWithModulePrefix,
		array $pathParams = [],
		array $queryParams = []
	): string {
		return $this->privateBaseUrl . $this->getRoutePath( $routeWithModulePrefix, $pathParams, $queryParams );
	}

	/**
	 * @param string $route
	 * @param array $pathParams
	 *
	 * @return string
	 */
	protected function substPathParams( string $route, array $pathParams ): string {
		foreach ( $pathParams as $param => $value ) {
			// NOTE: we use rawurlencode here, since execute() uses rawurldecode().
			// Spaces in path params must be encoded to %20 (not +).
			// Slashes must be encoded as %2F.
			$route = str_replace( '{' . $param . '}', rawurlencode( (string)$value ), $route );
		}
		return $route;
	}

	public function execute( RequestInterface $request ): ResponseInterface {
		try {
			$fullPath = $request->getUri()->getPath();
			$response = $this->doExecute( $fullPath, $request );
		} catch ( HttpException $e ) {
			$response = $this->responseFactory->createFromException( $e );
		} catch ( Throwable $e ) {
			$this->errorReporter->reportError( $e, null, $request );
			$response = $this->responseFactory->createFromException( $e );
		}

		return $response;
	}

	private function doExecute( string $fullPath, RequestInterface $request ): ResponseInterface {
		[ $modulePrefix, $path ] = $this->splitPath( $fullPath );
		$module = $this->getModule( $modulePrefix );

		if ( !$module ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->plaintextParams( $modulePrefix ),
				404,
				[ 'prefix' => $modulePrefix ]
			);
		}

		return $module->execute( $path, $request );
	}

	/**
	 * Prepare the handler by injecting relevant service objects and state
	 * into $handler.
	 *
	 * @internal
	 */
	public function prepareHandler( Handler $handler ) {
		// Injecting services in the Router class means we don't have to inject
		// them into each Module.
		$handler->initServices(
			$this->authority,
			$this->responseFactory,
			$this->hookContainer
		);

		$handler->initSession( $this->session );
	}

	/**
	 * @param CorsUtils $cors
	 * @return self
	 */
	public function setCors( CorsUtils $cors ): self {
		$this->cors = $cors;

		return $this;
	}

	/**
	 * @param StatsdDataFactoryInterface $stats
	 *
	 * @return self
	 */
	public function setStats( StatsdDataFactoryInterface $stats ): self {
		$this->stats = $stats;

		return $this;
	}

}
