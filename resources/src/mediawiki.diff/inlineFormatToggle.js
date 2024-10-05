/*!
 * JavaScript for diff inline toggle
 */
module.exports = function ( $inlineToggleSwitchLayout ) {
	const url = new URL( location.href ),
		api = new mw.Api(),
		$inlineLegendContainer = $( '.mw-diff-inline-legend' ),
		inlineToggleSwitchLayout = OO.ui.FieldLayout.static.infuse( $inlineToggleSwitchLayout ),
		inlineToggleSwitch = inlineToggleSwitchLayout.getField();

	inlineToggleSwitch.on( 'change', ( e ) => {
		onDiffTypeInlineChange( e, true );
	} );
	inlineToggleSwitch.on( 'disable', onDiffTypeInlineDisabled );

	const $wikitextDiffContainer = $( 'table.diff[data-mw="interface"]' );
	const $wikitextDiffHeader = $wikitextDiffContainer.find( 'tr.diff-title' )
		.add( $wikitextDiffContainer.find( 'td.diff-multi, td.diff-notice' ).parent() );
	let $wikitextDiffBody = $wikitextDiffContainer.find( 'tr' ).not( $wikitextDiffHeader );

	let $wikitextDiffBodyInline, $wikitextDiffBodyTable;
	if ( inlineToggleSwitch.getValue() ) {
		$wikitextDiffBodyInline = $wikitextDiffBody;
	} else {
		$wikitextDiffBodyTable = $wikitextDiffBody;
	}

	/**
	 * Handler for inlineToggleSwitch onChange event
	 *
	 * @param {boolean} isInline
	 * @param {boolean} saveDiffTypeOption
	 */
	function onDiffTypeInlineChange( isInline, saveDiffTypeOption ) {
		if ( ( isInline && typeof $wikitextDiffBodyInline === 'undefined' ) ||
			( !isInline && typeof $wikitextDiffBodyTable === 'undefined' ) ) {
			fetchDiff( isInline );
		} else {
			toggleDiffTypeVisibility( isInline );
		}

		if ( saveDiffTypeOption ) {
			api.saveOption( 'diff-type', isInline ? 'inline' : 'table' )
				.fail( ( error ) => {
					if ( error === 'notloggedin' ) {
						// Can't save preference, so use query parameter stickiness
						switchQueryParams( isInline );
					}
				} );
		}
	}

	/**
	 * Toggle legend and the DOM containers according to the format selected.
	 *
	 * @param {boolean} isInline
	 */
	function toggleDiffTypeVisibility( isInline ) {
		$inlineLegendContainer.toggleClass( 'oo-ui-element-hidden', !isInline );
		if ( typeof $wikitextDiffBodyInline !== 'undefined' ) {
			$wikitextDiffBodyInline.toggleClass( 'oo-ui-element-hidden', !isInline );
		}

		if ( typeof $wikitextDiffBodyTable !== 'undefined' ) {
			$wikitextDiffBodyTable.toggleClass( 'oo-ui-element-hidden', isInline );
		}
	}

	/**
	 * Change the query parameters to maintain the new diff type.
	 * This is used for anonymous users.
	 *
	 * @param {boolean} isInline
	 */
	function switchQueryParams( isInline ) {
		$( '#differences-prevlink, #differences-nextlink' )
			.each( function () {
				let linkUrl;
				try {
					linkUrl = new URL( this.href );
				} catch ( e ) {
					return;
				}
				if ( isInline ) {
					linkUrl.searchParams.set( 'diff-type', 'inline' );
				} else {
					linkUrl.searchParams.delete( 'diff-type' );
				}
				this.href = linkUrl.toString();
			} );
	}

	/**
	 * Toggle the legend when the toggle switch disabled state changes.
	 *
	 * @param {boolean} disabled
	 */
	function onDiffTypeInlineDisabled( disabled ) {
		if ( disabled ) {
			$inlineLegendContainer.addClass( 'oo-ui-element-hidden' );
		} else {
			$inlineLegendContainer.toggleClass( 'oo-ui-element-hidden', !inlineToggleSwitch.getValue() );
			// When Inline Switch is enabled, toggle wikitext according to its value.
			// Do not save user 'diff-type' preference
			onDiffTypeInlineChange( inlineToggleSwitch.getValue(), false );
		}
	}

	/**
	 * Fetch the diff through mw.API in the given format.
	 *
	 * @param {boolean} isInline
	 */
	function fetchDiff( isInline ) {
		const diffType = isInline ? 'inline' : 'table',
			oldRevId = mw.config.get( 'wgDiffOldId' ),
			newRevId = mw.config.get( 'wgDiffNewId' );

		let oldPageName, newPageName;
		if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'ComparePages' ) {
			oldPageName = newPageName = mw.config.get( 'wgRelevantPageName' );
		} else {
			oldPageName = url.searchParams.get( 'page1' );
			newPageName = url.searchParams.get( 'page2' );
		}

		const apiParams = {
			action: 'compare',
			fromtitle: oldPageName,
			totitle: newPageName,
			fromrev: oldRevId,
			torev: newRevId,
			difftype: diffType
		};

		api.get( apiParams ).done( ( diffData ) => {
			if ( isInline ) {
				$wikitextDiffBodyInline = $( diffData.compare[ '*' ] );
			} else {
				$wikitextDiffBodyTable = $( diffData.compare[ '*' ] );
			}

			toggleDiffTypeVisibility( inlineToggleSwitch.getValue() );

			$wikitextDiffBody.last().after( isInline ? $wikitextDiffBodyInline : $wikitextDiffBodyTable );
			$wikitextDiffBody = $wikitextDiffContainer.find( 'tr' ).not( $wikitextDiffHeader );
			/**
			 * Fired when the wikitext DOM is updated so others can react accordingly.
			 *
			 * @event ~'wikipage.diff.wikitextDiffBody'
			 * @memberof Hooks
			 * @param {jQuery} $wikitextDiffBody
			 */
			mw.hook( 'wikipage.diff.wikitextBodyUpdate' ).fire( $wikitextDiffBody );
		} );
	}

	/**
	 * Fired when the diff type switch is present so others can decide
	 * how to manipulate the DOM.
	 *
	 * @event ~'wikipage.diff.diffTypeSwitch'
	 * @memberof Hooks
	 * @param {OO.ui.ToggleSwitchWidget} inlineToggleSwitch
	 */
	mw.hook( 'wikipage.diff.diffTypeSwitch' ).fire( inlineToggleSwitch );
};
