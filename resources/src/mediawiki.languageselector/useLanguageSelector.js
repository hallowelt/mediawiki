const { ref, computed, onBeforeUnmount, unref } = require( 'vue' );
const languageSearchClient = require( './languageSearch.js' );
const debounce = require( './debounce.js' );

/**
 * Composable for language selection logic
 *
 * @param {import('vue').Ref<Object>|Object} [selectableLanguages]
 * @param {import('vue').Ref<string|string[]>} selected
 * @param {string} searchApiUrl
 * @param {number} debounceDelayMs
 * @param {boolean} isMultiple Whether multiple selection is allowed
 * @return {Object} Language selector state and methods
 */
function useLanguageSelector(
	selectableLanguages,
	selected,
	searchApiUrl,
	debounceDelayMs,
	isMultiple = false
) {
	const searchQuery = ref( '' );
	const searchResults = ref( [] );
	const searchQueryHits = ref( {} );
	const isSearching = ref( false );

	/**
	 * Normalized language list that defaults to an empty object.
	 * Used for all data lookups and returned to the caller.
	 */
	const languages = computed( () => unref( selectableLanguages ) || {} );

	const selection = computed( () => {
		const currentSelected = unref( selected );

		if ( isMultiple ) {
			return ( currentSelected || [] ).map( ( langCode ) => ( {
				value: langCode,
				label: languages.value[ langCode ] || langCode
			} ) );
		} else {
			return {
				value: currentSelected,
				label: languages.value[ currentSelected ] || currentSelected
			};
		}
	} );

	const selectedValues = computed( () => {
		if ( isMultiple ) {
			return selection.value.map( ( item ) => item.value );
		}
		return selection.value.value;
	} );

	let languageClient = null;
	const fetchLanguages = async ( query ) => {
		if ( !languageClient ) {
			languageClient = languageSearchClient( searchApiUrl );
		}
		const searchRequest = languageClient.searchLanguages( query );
		isSearching.value = true;

		try {
			const response = await searchRequest;
			searchQueryHits.value = response.languagesearch || {};
			const responseLanguageCodes = Object.keys( searchQueryHits.value );

			// Only filter results if the caller explicitly provided a subset of languages.
			if ( unref( selectableLanguages ) ) {
				const languagesKeys = Object.keys( languages.value );
				searchResults.value = responseLanguageCodes.filter(
					( code ) => languagesKeys.includes( code )
				);
			} else {
				searchResults.value = [];
			}
		} catch ( error ) {
			searchQueryHits.value = {};
			mw.log.error( 'Language search failed:', error );
		} finally {
			isSearching.value = false;
		}
	};

	const search = ( query ) => {
		searchQuery.value = query;
		if ( !query || query.trim().length === 0 ) {
			searchResults.value = [];
			return;
		}

		if ( unref( selectableLanguages ) ) {
			fetchLanguages( query );
		}
	};

	const debouncedSearch = debounce( search, debounceDelayMs );

	const clearSearchQuery = () => {
		searchQuery.value = null;
	};

	const isSelectionUpdated = ( newValue ) => {
		if ( isMultiple ) {
			const current = selectedValues.value || [];
			return newValue.length !== current.length ||
				newValue.some( ( val, idx ) => val !== current[ idx ] );
		}

		return selectedValues.value !== newValue;
	};

	onBeforeUnmount( () => {
		debouncedSearch.cancel();
	} );

	return {
		clearSearchQuery,
		languages,
		searchQuery,
		searchQueryHits,
		searchResults,
		selection,
		selectedValues,
		search: debouncedSearch,
		isSearching,
		isSelectionUpdated
	};
}

module.exports = useLanguageSelector;
