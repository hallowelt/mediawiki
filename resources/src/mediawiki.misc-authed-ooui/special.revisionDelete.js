/*!
 * JavaScript for Special:RevisionDelete
 */
( function () {
	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Revisiondelete' ) {
		return;
	}

	const wpRevDeleteReasonList = OO.ui.infuse( $( '#wpRevDeleteReasonList' ) ),
		wpReason = OO.ui.infuse( $( '#wpReason' ) );

	mw.widgets.visibleCodePointLimitWithDropdown( wpReason, wpRevDeleteReasonList, mw.config.get( 'wgCommentCodePointLimit' ) );
}() );
