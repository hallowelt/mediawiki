<?php

namespace MediaWiki\Rest\Validator;

use FormatJson;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Do-nothing body validator
 */
class JsonBodyValidator implements BodyValidator {

	/**
	 * @var array[]
	 */
	private $bodyParamSettings;

	/**
	 * @param array[] $bodyParamSettings
	 */
	public function __construct( array $bodyParamSettings ) {
		$this->bodyParamSettings = $bodyParamSettings;
	}

	public function validateBody( RequestInterface $request ) {
		$jsonStream = $request->getBody();
		$status = FormatJson::parse( "$jsonStream", FormatJson::FORCE_ASSOC );

		if ( !$status->isOK() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-json-body-parse-error', [ "$status" ] ),
				400
			);
		}

		$data = $status->value;

		if ( !is_array( $data ) ) {
			throw new LocalizedHttpException( new MessageValue( 'rest-bad-json-body' ), 400 );
		}

		$uncheckedBodyKeys = array_fill_keys( array_keys( $data ), true );
		foreach ( $this->bodyParamSettings as $name => $settings ) {
			if ( !empty( $settings[ParamValidator::PARAM_REQUIRED] ) && !isset( $data[$name] ) ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-missing-body-field', [ $name ] ), 400
				);
			}

			if ( !isset( $data[$name] ) ) {
				$data[$name] = $settings[ParamValidator::PARAM_DEFAULT] ?? null;
			}

			unset( $uncheckedBodyKeys[$name] );
			// TODO: use a ParamValidator to check field value, etc!
		}
		if ( $uncheckedBodyKeys ) {
			throw new LocalizedHttpException(
				new MessageValue(
					'rest-extraneous-body-fields',
					[ new ListParam( ListType::COMMA, array_keys( $uncheckedBodyKeys ) ) ]
				),
				400
			);
		}

		return $data;
	}

}
