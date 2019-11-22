<?php

namespace MediaWiki\Rest;

use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;

abstract class Handler {

	/**
	 * (string) ParamValidator constant to specify the source of the parameter.
	 * Value must be 'path', 'query', or 'post'.
	 */
	const PARAM_SOURCE = 'rest-param-source';

	/** @var Router */
	private $router;

	/** @var RequestInterface */
	private $request;

	/** @var array */
	private $config;

	/** @var ResponseFactory */
	private $responseFactory;

	/** @var array|null */
	private $validatedParams;

	/** @var mixed */
	private $validatedBody;

	/** @var ConditionalHeaderUtil */
	private $conditionalHeaderUtil;

	/**
	 * Initialise with dependencies from the Router. This is called after construction.
	 * @internal
	 * @param Router $router
	 * @param RequestInterface $request
	 * @param array $config
	 * @param ResponseFactory $responseFactory
	 */
	public function init( Router $router, RequestInterface $request, array $config,
		ResponseFactory $responseFactory
	) {
		$this->router = $router;
		$this->request = $request;
		$this->config = $config;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * Get the Router. The return type declaration causes it to raise
	 * a fatal error if init() has not yet been called.
	 * @return Router
	 */
	protected function getRouter(): Router {
		return $this->router;
	}

	/**
	 * Get the current request. The return type declaration causes it to raise
	 * a fatal error if init() has not yet been called.
	 *
	 * @return RequestInterface
	 */
	public function getRequest(): RequestInterface {
		return $this->request;
	}

	/**
	 * Get the configuration array for the current route. The return type
	 * declaration causes it to raise a fatal error if init() has not
	 * been called.
	 *
	 * @return array
	 */
	public function getConfig(): array {
		return $this->config;
	}

	/**
	 * Get the ResponseFactory which can be used to generate Response objects.
	 * This will raise a fatal error if init() has not been
	 * called.
	 *
	 * @return ResponseFactory
	 */
	public function getResponseFactory(): ResponseFactory {
		return $this->responseFactory;
	}

	/**
	 * Validate the request parameters/attributes and body. If there is a validation
	 * failure, a response with an error message should be returned or an
	 * HttpException should be thrown.
	 *
	 * @param Validator $restValidator
	 * @throws HttpException On validation failure.
	 */
	public function validate( Validator $restValidator ) {
		$validatedParams = $restValidator->validateParams( $this->getParamSettings() );
		$validatedBody = $restValidator->validateBody( $this->request, $this );
		$this->validatedParams = $validatedParams;
		$this->validatedBody = $validatedBody;
	}

	/**
	 * Get a ConditionalHeaderUtil object.
	 *
	 * On the first call to this method, the object will be initialized with
	 * validator values by calling getETag(), getLastModified() and
	 * hasRepresentation().
	 *
	 * @return ConditionalHeaderUtil
	 */
	protected function getConditionalHeaderUtil() {
		if ( $this->conditionalHeaderUtil === null ) {
			$this->conditionalHeaderUtil = new ConditionalHeaderUtil;
			$this->conditionalHeaderUtil->setValidators(
				$this->getETag(),
				$this->getLastModified(),
				$this->hasRepresentation() );
		}
		return $this->conditionalHeaderUtil;
	}

	/**
	 * Check the conditional request headers and generate a response if appropriate.
	 * This is called by the Router before execute() and may be overridden.
	 *
	 * @return ResponseInterface|null
	 */
	public function checkPreconditions() {
		$status = $this->getConditionalHeaderUtil()->checkPreconditions( $this->getRequest() );
		if ( $status ) {
			$response = $this->getResponseFactory()->create();
			$response->setStatus( $status );
			return $response;
		} else {
			return null;
		}
	}

	/**
	 * Modify the response, adding Last-Modified and ETag headers as indicated
	 * the values previously returned by ETag and getLastModified(). This is
	 * called after execute() returns, and may be overridden.
	 *
	 * @param ResponseInterface $response
	 */
	public function applyConditionalResponseHeaders( ResponseInterface $response ) {
		$this->getConditionalHeaderUtil()->applyResponseHeaders( $response );
	}

	/**
	 * Fetch ParamValidator settings for parameters
	 *
	 * Every setting must include self::PARAM_SOURCE to specify which part of
	 * the request is to contain the parameter.
	 *
	 * @return array[] Associative array mapping parameter names to
	 *  ParamValidator settings arrays
	 */
	public function getParamSettings() {
		return [];
	}

	/**
	 * Fetch the BodyValidator
	 * @param string $contentType Content type of the request.
	 * @return BodyValidator
	 */
	public function getBodyValidator( $contentType ) {
		return new NullBodyValidator();
	}

	/**
	 * Fetch the validated parameters. This must be called after validate() is
	 * called. During execute() is fine.
	 *
	 * @return array Array mapping parameter names to validated values
	 * @throws \RuntimeException If validate() has not been called
	 */
	public function getValidatedParams() {
		if ( $this->validatedParams === null ) {
			throw new \RuntimeException( 'getValidatedParams() called before validate()' );
		}
		return $this->validatedParams;
	}

	/**
	 * Fetch the validated body
	 * @return mixed Value returned by the body validator, or null if validate() was
	 *  not called yet, validation failed, there was no body, or the body was form data.
	 */
	public function getValidatedBody() {
		return $this->validatedBody;
	}

	/**
	 * The subclass should override this to provide the maximum last modified
	 * timestamp for the current request. This is called before execute() in
	 * order to decide whether to send a 304.
	 *
	 * The timestamp can be in any format accepted by ConvertibleTimestamp, or
	 * null to indicate that the timestamp is unknown.
	 *
	 * @return bool|string|int|float|\DateTime|null
	 */
	protected function getLastModified() {
		return null;
	}

	/**
	 * The subclass should override this to provide an ETag for the current
	 * request. This is called before execute() in order to decide whether to
	 * send a 304.
	 *
	 * This must be a complete ETag, including double quotes.
	 *
	 * See RFC 7232 § 2.3 for semantics.
	 *
	 * @return string|null
	 */
	protected function getETag() {
		return null;
	}

	/**
	 * The subclass should override this to indicate whether the resource
	 * exists. This is used for wildcard validators, for example "If-Match: *"
	 * fails if the resource does not exist.
	 *
	 * @return bool|null
	 */
	protected function hasRepresentation() {
		return null;
	}

	/**
	 * Indicates whether this route requires read rights.
	 *
	 * The handler should override this if it does not need to read from the
	 * wiki. This is uncommon, but may be useful for login and other account
	 * management APIs.
	 *
	 * @return bool
	 */
	public function needsReadAccess() {
		return true;
	}

	/**
	 * Indicates whether this route requires write access.
	 *
	 * The handler should override this if the route does not need to write to
	 * the database.
	 *
	 * This should return true for routes that may require synchronous database writes.
	 * Modules that do not need such writes should also not rely on master database access,
	 * since only read queries are needed and each master DB is a single point of failure.
	 *
	 * @return bool
	 */
	public function needsWriteAccess() {
		return true;
	}

	/**
	 * Execute the handler. This is called after parameter validation. The
	 * return value can either be a Response or any type accepted by
	 * ResponseFactory::createFromReturnValue().
	 *
	 * To automatically construct an error response, execute() should throw a
	 * RestException. Such exceptions will not be logged like a normal exception.
	 *
	 * If execute() throws any other kind of exception, the exception will be
	 * logged and a generic 500 error page will be shown.
	 *
	 * @return mixed
	 */
	abstract public function execute();
}
