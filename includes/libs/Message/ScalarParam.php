<?php

namespace Wikimedia\Message;

use InvalidArgumentException;
use Stringable;

/**
 * Value object representing a message parameter holding a single value.
 *
 * Message parameter classes are pure value objects and are safely newable.
 *
 * @newable
 */
class ScalarParam extends MessageParam {

	/**
	 * Construct a text parameter
	 *
	 * @stable to call.
	 *
	 * @param string|ParamType $type One of the ParamType constants.
	 * @param string|int|float|MessageSpecifier|Stringable $value
	 */
	public function __construct( string|ParamType $type, $value ) {
		if ( is_string( $type ) ) {
			wfDeprecated( __METHOD__ . ' with string type', '1.45' );
			$type = ParamType::from( $type );
		}
		if ( $type === ParamType::LIST ) {
			throw new InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( $value instanceof MessageSpecifier ) {
			// Ensure that $this->value is JSON-serializable, even if $value is not
			$value = MessageValue::newFromSpecifier( $value );
		} elseif ( is_object( $value ) && $value instanceof Stringable ) {
			$value = (string)$value;
		} elseif ( !is_string( $value ) && !is_numeric( $value ) ) {
			$valType = get_debug_type( $value );
			if ( $value === null || is_bool( $value ) ) {
				trigger_error(
					"Using $valType as a message parameter was deprecated in MediaWiki 1.43",
					E_USER_DEPRECATED
				);
				$value = (string)$value;
			} else {
				throw new InvalidArgumentException(
					"Scalar parameter must be a string, number, Stringable, or MessageSpecifier; got $valType"
				);
			}
		}

		$this->type = $type;
		$this->value = $value;
	}

	public function dump(): string {
		if ( $this->value instanceof MessageValue ) {
			$contents = $this->value->dump();
		} else {
			$contents = htmlspecialchars( (string)$this->value );
		}
		return "<{$this->type->value}>" . $contents . "</{$this->type->value}>";
	}

	public function isSameAs( MessageParam $mp ): bool {
		if ( !( $mp instanceof ScalarParam && $this->type === $mp->type ) ) {
			return false;
		}
		if ( $this->value instanceof MessageValue ) {
			return $mp->value instanceof MessageValue &&
				$this->value->isSameAs( $mp->value );
		}
		return $this->value === $mp->value;
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			$this->type->value => $this->value,
		];
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		// If this is not a scalar value (string|int|float) then it's
		// probably a MessageValue, and we can hint it as such to
		// reduce serialization overhead.
		return MessageValue::hint();
	}

	public static function newFromJsonArray( array $json ): ScalarParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 1 ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}

		$type = key( $json );
		$value = current( $json );

		return new self( ParamType::from( $type ), $value );
	}
}
