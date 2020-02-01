/*!
 * JavaScript for Special:Preferences: signature field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var signatureInput, $signatureInput, fancyToggleInput, $fancyToggleInput;

		$signatureInput = $root.find( '#mw-input-wpnickname' );
		if (
			// This preference could theoretically be disabled ($wgHiddenPrefs)
			!$signatureInput.length ||
			$signatureInput.closest( '.mw-htmlform-autoinfuse-lazy' ).length
		) {
			return;
		}

		signatureInput = OO.ui.infuse( $signatureInput );

		// Add a visible length limit
		mw.widgets.visibleCodePointLimit( signatureInput );

		// Use appropriate font
		function updateFont( useEditFont ) {
			signatureInput.$element.toggleClass(
				'mw-editfont-' + mw.user.options.get( 'editfont' ),
				useEditFont
			);
		}
		$fancyToggleInput = $root.find( '#mw-input-wpfancysig' );
		if ( $fancyToggleInput.length ) {
			fancyToggleInput = OO.ui.infuse( $fancyToggleInput );
			fancyToggleInput.on( 'change', function () {
				updateFont( fancyToggleInput.isSelected() );
			} );
			// !!+ casts '0' to false
			updateFont( !!+mw.user.options.get( 'fancysig' ) );
		}

	} );
}() );
