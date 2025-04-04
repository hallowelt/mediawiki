/*!
 * Add search suggestions to the search form.
 */
( function () {
	// eslint-disable-next-line no-jquery/no-map-util
	const searchNS = $.map( mw.config.get( 'wgFormattedNamespaces' ), ( nsName, nsID ) => {
		if ( nsID >= 0 && mw.user.options.get( 'searchNs' + nsID ) ) {
		// Cast string key to number
			return Number( nsID );
		}
	} );

	/**
	 * Convenience library for making searches for titles that match a string.
	 * Loaded via the `mediawiki.searchSuggest` ResourceLoader library.
	 *
	 * @example
	 * mw.loader.using('mediawiki.searchSuggest').then(() => {
	 *   var api = new mw.Api();
	 *   mw.searchSuggest.request(api, 'Dogs that', ( results ) => {
	 *     alert( `Results that match: ${results.join( '\n' );}` );
	 *   });
	 * });
	 * @namespace mw.searchSuggest
	 */
	mw.searchSuggest = {
		/**
		 * @typedef {Object} mw.searchSuggest~ResponseMetaData
		 * @property {string} type the contents of the X-OpenSearch-Type response header.
		 * @property {string} searchId the contents of the X-Search-ID response header.
		 * @property {string} query
		 */
		/**
		 * @callback mw.searchSuggest~ResponseFunction
		 * @param {string[]} titles titles of pages that match search
		 * @param {ResponseMetaData} meta meta data relating to search.
		 */
		/**
		 * Queries the wiki and calls response with the result.
		 *
		 * @param {mw.Api} api
		 * @param {string} query
		 * @param {ResponseFunction} response
		 * @param {string|number} [limit]
		 * @param {string|number|string[]|number[]} [namespace]
		 * @return {jQuery.Deferred}
		 */
		request: function ( api, query, response, limit, namespace ) {
			return api.get( {
				formatversion: 2,
				action: 'opensearch',
				search: query,
				namespace: namespace || searchNS,
				limit
			} ).done( ( data, jqXHR ) => {
				response( data[ 1 ], {
					type: jqXHR.getResponseHeader( 'X-OpenSearch-Type' ),
					searchId: jqXHR.getResponseHeader( 'X-Search-ID' ),
					query
				} );
			} );
		}
	};

	$( () => {
		let api;
		// Region where the suggestions box will appear directly below
		// (using the same width). Can be a container element or the input
		// itself, depending on what suits best in the environment.
		// For Vector the suggestion box should align with the simpleSearch
		// container's borders, in other skins it should align with the input
		// element (not the search form, as that would leave the buttons
		// vertically between the input and the suggestions).
		const $searchRegion = $( '#simpleSearch, #searchInput' ).first(),
			$searchInput = $( '#searchInput' );
		let previousSearchText = $searchInput.val();

		function serializeObject( fields ) {
			const obj = {};

			for ( let i = 0; i < fields.length; i++ ) {
				obj[ fields[ i ].name ] = fields[ i ].value;
			}

			return obj;
		}

		// Compute form data for search suggestions functionality.
		function getFormData( context ) {
			if ( !context.formData ) {
				// Compute common parameters for links' hrefs
				const $form = context.config.$region.closest( 'form' );

				let baseHref = $form.attr( 'action' ) || '';
				baseHref += baseHref.includes( '?' ) ? '&' : '?';

				const linkParams = serializeObject( $form.serializeArray() );

				context.formData = {
					textParam: context.data.$textbox.attr( 'name' ),
					linkParams: linkParams,
					baseHref: baseHref
				};
			}

			return context.formData;
		}

		/**
		 * Callback that's run when the user changes the search input text
		 * 'this' is the search input box (jQuery object)
		 *
		 * @ignore
		 */
		function onBeforeUpdate() {
			const searchText = this.val();

			if ( searchText && searchText !== previousSearchText ) {
				mw.track( 'mediawiki.searchSuggest', {
					action: 'session-start'
				} );
			}
			previousSearchText = searchText;
		}

		/**
		 * Defines the location of autocomplete. Typically either
		 * header, which is in the top right of vector (for example)
		 * and content which identifies the main search bar on
		 * Special:Search. Defaults to header for skins that don't set
		 * explicitly.
		 *
		 * @ignore
		 * @param {Object} context
		 * @return {string}
		 */
		function getInputLocation( context ) {
			return context.config.$region
				.closest( 'form' )
				.find( '[data-search-loc]' )
				.data( 'search-loc' ) || 'header';
		}

		/**
		 * Callback that's run when suggestions have been updated either from the cache or the API
		 * 'this' is the search input box (jQuery object)
		 *
		 * @ignore
		 * @param {Object} metadata
		 */
		function onAfterUpdate( metadata ) {
			const context = this.data( 'suggestionsContext' );

			mw.track( 'mediawiki.searchSuggest', {
				action: 'impression-results',
				numberOfResults: context.config.suggestions.length,
				resultSetType: metadata.type || 'unknown',
				searchId: metadata.searchId || null,
				query: metadata.query,
				inputLocation: getInputLocation( context )
			} );
		}

		// The function used to render the suggestions.
		function renderFunction( text, context ) {
			const formData = getFormData( context ),
				textboxConfig = context.data.$textbox.data( 'mw-searchsuggest' ) || {};

			// linkParams object is modified and reused
			formData.linkParams[ formData.textParam ] = text;

			// Allow trackers to attach tracking information, such
			// as wprov, to clicked links.
			mw.track( 'mediawiki.searchSuggest', {
				action: 'render-one',
				formData: formData,
				index: context.config.suggestions.indexOf( text )
			} );

			// this is the container <div>, jQueryfied
			this.text( text );

			// wrap only as link, if the config doesn't disallow it
			if ( textboxConfig.wrapAsLink !== false ) {
				this.wrap(
					$( '<a>' )
						.attr( 'href', formData.baseHref + $.param( formData.linkParams ) )
						.attr( 'title', text )
						.addClass( 'mw-searchSuggest-link' )
				);
			}
		}

		// The function used when the user makes a selection
		function selectFunction( $input, source ) {
			const context = $input.data( 'suggestionsContext' ),
				text = $input.val(),
				url = $( this ).parent( 'a' ).attr( 'href' );

			// We want to track a click-result XOR a submit-form action.
			// If the source was 'click' (or otherwise non-'keyboard'),
			// track it and then let the rest of the event proceed as normal.
			// If the source was 'keyboard', and we have a URL
			// (from the <a> that the result was wrapped in, see renderFunction()),
			// then also track a click, prevent the regular form submit,
			// and instead directly navigate to the URL as if it had been clicked.
			// If the source was 'keyboard', but we have no URL,
			// then we have to let the regular form submit go through,
			// so skip the click tracking in that case to avoid duplicate tracking.
			if ( source === 'keyboard' && url || source !== 'keyboard' ) {
				mw.track( 'mediawiki.searchSuggest', {
					action: 'click-result',
					numberOfResults: context.config.suggestions.length,
					index: context.config.suggestions.indexOf( text )
				} );

				if ( source === 'keyboard' ) {
					window.location.assign( url );
					// prevent default and stop propagation
					return false;
				}
			}

			// allow the form to be submitted
			return true;
		}

		function specialRenderFunction( query, context ) {
			const $el = this,
				formData = getFormData( context );

			// linkParams object is modified and reused
			formData.linkParams[ formData.textParam ] = query;

			mw.track( 'mediawiki.searchSuggest', {
				action: 'render-one',
				formData: formData,
				index: context.config.suggestions.indexOf( query )
			} );

			if ( mw.user.options.get( 'search-match-redirect' ) && $el.children().length === 0 ) {
				$el
					.append(
						$( '<div>' )
							.addClass( 'special-label' )
							.text( mw.msg( 'searchsuggest-containing' ) ),
						$( '<div>' )
							.addClass( 'special-query' )
							.text( query )
					)
					.show();
			} else {
				$el.find( '.special-query' )
					.text( query );
			}

			// eslint-disable-next-line no-jquery/no-class-state
			if ( $el.parent().hasClass( 'mw-searchSuggest-link' ) ) {
				$el.parent().attr( 'href', formData.baseHref + $.param( formData.linkParams ) + '&fulltext=1' );
			} else {
				$el.wrap(
					$( '<a>' )
						.attr( 'href', formData.baseHref + $.param( formData.linkParams ) + '&fulltext=1' )
						.addClass( 'mw-searchSuggest-link' )
				);
			}
		}

		// Generic suggestions functionality for all search boxes
		const searchboxesSelectors = [
			// Primary searchbox on every page in standard skins
			'#searchInput',
			// Generic selector for skins with multiple searchboxes (used by CologneBlue)
			// and for MediaWiki itself (special pages with page title inputs)
			'.mw-searchInput'
		];
		$( searchboxesSelectors.join( ', ' ) )
			.suggestions( {
				fetch: function ( query, response, maxRows ) {
					const node = this[ 0 ];

					api = api || new mw.Api();

					$.data( node, 'request', mw.searchSuggest.request( api, query, response, maxRows ) );
				},
				cancel: function () {
					const node = this[ 0 ],
						request = $.data( node, 'request' );

					if ( request ) {
						request.abort();
						$.removeData( node, 'request' );
					}
				},
				result: {
					render: renderFunction,
					select: function () {
						// allow the form to be submitted
						return true;
					}
				},
				update: {
					before: onBeforeUpdate,
					after: onAfterUpdate
				},
				cache: true,
				highlightInput: true
			} )
			.on( 'paste cut drop', function () {
				// make sure paste and cut events from the mouse and drag&drop events
				// trigger the keypress handler and cause the suggestions to update
				$( this ).trigger( 'keypress' );
			} )
			// In most skins (at least Monobook and Vector), the font-size is messed up in <body>.
			// (they use 2 elements to get a sensible font-height). So, instead of making exceptions for
			// each skin or adding more stylesheets, just copy it from the active element so auto-fit.
			.each( function () {
				const $this = $( this );
				$this
					.data( 'suggestions-context' )
					.data.$container.css( 'fontSize', $this.css( 'fontSize' ) );
			} );

		// Ensure that the thing is actually present!
		if ( $searchRegion.length === 0 ) {
			// Don't try to set anything up if simpleSearch is disabled sitewide.
			// The loader code loads us if the option is present, even if we're
			// not actually enabled (anymore).
			return;
		}

		// Special suggestions functionality and tracking for skin-provided search box
		$searchInput.suggestions( {
			update: {
				before: onBeforeUpdate,
				after: onAfterUpdate
			},
			result: {
				render: renderFunction,
				select: selectFunction
			},
			special: {
				render: specialRenderFunction,
				select: function ( $input, source ) {
					const context = $input.data( 'suggestionsContext' ),
						text = $input.val();
					if ( source === 'mouse' ) {
						// mouse click won't trigger form submission, so we need to send a click event
						mw.track( 'mediawiki.searchSuggest', {
							action: 'click-result',
							numberOfResults: context.config.suggestions.length,
							index: context.config.suggestions.indexOf( text )
						} );
					} else {
						$input.closest( 'form' )
							.append(
								$( '<input>' )
									.prop( {
										type: 'hidden',
										value: 1
									} )
									.attr( 'name', 'fulltext' )
							);
					}
					return true; // allow the form to be submitted
				}
			},
			$region: $searchRegion
		} );

		const $searchForm = $searchInput.closest( 'form' );
		$searchForm
			// Track the form submit event.
			// Note that the form is mainly submitted for manual user input;
			// selecting a suggestion is tracked as a click instead (see selectFunction()).
			.on( 'submit', () => {
				const context = $searchInput.data( 'suggestionsContext' );
				mw.track( 'mediawiki.searchSuggest', {
					action: 'submit-form',
					numberOfResults: context.config.suggestions.length,
					$form: context.config.$region.closest( 'form' ),
					inputLocation: getInputLocation( context ),
					index: context.config.suggestions.indexOf(
						context.data.$textbox.val()
					)
				} );
			} );

		// Check to see if the fulltext search button is placed before the go search button
		if ( $searchForm.find( '.mw-fallbackSearchButton ~ .searchButton' ).length ) {
			// Submitting the form with enter should always trigger "search within pages"
			// for JavaScript capable browsers.
			// If it is, remove the "full text search" fallback button.
			// In skins, where the "full text search" button
			// precedes the "search by title" button, e.g. Vector this is done for
			// non-JavaScript support. If the "search by title" button is first,
			// and two search buttons are shown e.g. MonoBook no change is needed.
			$searchForm.find( '.mw-fallbackSearchButton' ).remove();
		}
	} );

}() );
