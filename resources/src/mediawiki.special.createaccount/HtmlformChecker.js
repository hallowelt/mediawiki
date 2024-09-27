const util = require( 'mediawiki.util' );

/**
 * A helper class to add validation to non-OOUI HTMLForm fields.
 *
 * @private
 * @class HtmlformChecker
 *
 * @constructor
 * @param {jQuery} $element Form field generated by HTMLForm
 * @param {Function} validator Validation callback
 * @param {string} validator.value Value of the form field to be validated
 * @param {jQuery.Promise} validator.return The promise should be resolved
 *  with an object with two properties: Boolean 'valid' to indicate success
 *  or failure of validation, and an array 'messages' to be passed to
 *  setErrors() on failure.
 */
function HtmlformChecker( $element, validator ) {
	this.validator = validator;
	this.$element = $element;

	this.$errorBox = $element.next( '.html-form-error' );
	if ( !this.$errorBox.length ) {
		this.$errorBox = $( '<div>' ).addClass( 'html-form-error' );
		this.$errorBox.hide();
		$element.after( this.$errorBox );
	}

	this.currentValue = this.$element.val();
}

/**
 * Attach validation events to the form element
 *
 * @param {jQuery} [$extraElements] Additional elements to listen for change
 *  events on.
 * @return {HtmlformChecker}
 * @chainable
 */
HtmlformChecker.prototype.attach = function ( $extraElements ) {
	let $e = this.$element,
		// We need to hook to all of these events to be sure we are
		// notified of all changes to the value of an <input type=text>
		// field.
		events = 'keyup keydown change mouseup cut paste focus blur';

	if ( $extraElements ) {
		$e = $e.add( $extraElements );
	}
	$e.on( events, mw.util.debounce( this.validate.bind( this ), 1000 ) );

	return this;
};

/**
 * Validate the form element
 *
 * @return {jQuery.Promise|undefined}
 */
HtmlformChecker.prototype.validate = function () {
	let currentRequestInternal,
		that = this,
		value = this.$element.val();

	// Abort any pending requests.
	if ( this.currentRequest && this.currentRequest.abort ) {
		this.currentRequest.abort();
	}

	if ( value === '' ) {
		this.currentValue = value;
		this.setErrors( true, [] );
		return;
	}

	this.currentRequest = currentRequestInternal = this.validator( value )
		.done( ( info ) => {
			const forceReplacement = value !== that.currentValue;

			// Another request was fired in the meantime, the result we got here is no longer current.
			// This shouldn't happen as we abort pending requests, but you never know.
			if ( that.currentRequest !== currentRequestInternal ) {
				return;
			}
			// If we're here, then the current request has finished, avoid calling .abort() needlessly.
			that.currentRequest = undefined;

			that.currentValue = value;

			that.setErrors( info.valid, info.messages, forceReplacement );
		} ).fail( () => {
			that.currentValue = null;
			that.setErrors( true, [] );
		} );

	return currentRequestInternal;
};

/**
 * Display errors associated with the form element
 *
 * @param {boolean} valid Whether the input is still valid regardless of the messages
 * @param {Array} errors Errorbox messages. Each errorbox message will be appended to a
 *  `<div>` or `<li>`, as with jQuery.append().
 * @param {boolean} [forceReplacement] Set true to force a visual replacement even
 *  if the errors are the same. Ignored if errors are empty.
 * @return {HtmlformChecker}
 * @chainable
 */
HtmlformChecker.prototype.setErrors = function ( valid, errors, forceReplacement ) {
	let $oldErrorBox,
		showFunc,
		$text,
		replace,
		$errorBox = this.$errorBox;

	if ( errors.length === 0 ) {
		// FIXME: Use CSS transition
		// eslint-disable-next-line no-jquery/no-slide
		$errorBox.slideUp( () => {
			$errorBox
				.removeAttr( 'class' )
				.empty();
		} );
	} else {
		// Animate the replacement if told to by the caller (i.e. to make it visually
		// obvious that the changed field value gives the same errorbox) or if
		// the errorbox text changes (because it makes more sense than
		// changing the text with no animation).
		replace = forceReplacement;
		if ( !replace ) {
			$text = $( '<div>' );
			// Match behavior of HTMLFormField::formatErrors()
			if ( errors.length === 1 ) {
				$text.append( errors[ 0 ] );
			} else {
				$text.append(
					$( '<ul>' ).append(
						errors.map( ( e ) => $( '<li>' ).append( e ) )
					)
				);
			}
			if ( $text.text() !== $errorBox.text() ) {
				replace = true;
			}
		}

		$oldErrorBox = $errorBox;
		if ( replace ) {
			this.$errorBox = $errorBox = $( '<div>' );
			$errorBox.hide();
			$oldErrorBox.after( this.$errorBox );
		}

		const oldErrorType = this.oldErrorType || 'notice';
		const errorType = valid ? 'warning' : 'error';
		this.oldErrorType = errorType;
		showFunc = function () {
			if ( $oldErrorBox !== $errorBox ) {
				$oldErrorBox
					.removeAttr( 'class' )
					.detach();
			}
			$errorBox.empty();
			// Match behavior of HTMLFormField::formatErrors()
			let errorHtml;
			if ( errors.length === 1 ) {
				errorHtml = errors[ 0 ][ 0 ];
			} else {
				errorHtml = $( '<ul>' ).append(
					errors.map( ( e ) => $( '<li>' ).append( e ) )
				)[ 0 ];
			}
			$errorBox.append(
				util.messageBox( errorHtml, errorType )
			);
			// FIXME: Use CSS transition
			// eslint-disable-next-line no-jquery/no-slide
			$errorBox.slideDown();
		};
		if (
			$oldErrorBox !== $errorBox &&
			( oldErrorType === 'error' || oldErrorType === 'warning' )
		) {
			// eslint-disable-next-line no-jquery/no-slide
			$oldErrorBox.slideUp( showFunc );
		} else {
			showFunc();
		}
	}

	return this;
};

module.exports = HtmlformChecker;
