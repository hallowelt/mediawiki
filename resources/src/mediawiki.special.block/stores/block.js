const { defineStore } = require( 'pinia' );
const { computed, ComputedRef, ref, Ref, watch } = require( 'vue' );
const api = new mw.Api();

/**
 * Pinia store for the SpecialBlock application.
 */
module.exports = exports = defineStore( 'block', () => {
	// ** State properties (refs) **

	// Form fields.

	/**
	 * Whether the multiblocks feature is enabled with $wgEnableMultiBlocks.
	 *
	 * @type {boolean}
	 */
	const enableMultiblocks = mw.config.get( 'blockEnableMultiblocks' ) || false;

	/**
	 * The target user to block. Beyond the initial value,
	 * this is set only by the UserLookup component.
	 *
	 * @type {Ref<string>}
	 */
	const targetUser = ref( mw.config.get( 'blockTargetUser' ) || '' );
	/**
	 * The block ID of the block to modify.
	 *
	 * @type {Ref<number|null>}
	 */
	const blockId = ref( mw.config.get( 'blockId' ) || null );
	/**
	 * The block type, either `sitewide` or `partial`. This is set by the BlockTypeField component.
	 *
	 * @type {Ref<string>}
	 */
	const type = ref( mw.config.get( 'blockTypePreset' ) || 'sitewide' );
	/**
	 * The pages to restrict the partial block to.
	 *
	 * @type {Ref<string[]>}
	 */
	const pages = ref( ( mw.config.get( 'blockPageRestrictions' ) || '' )
		.split( '\n' )
		.filter( Boolean )
	);
	/**
	 * The namespaces to restrict the partial block to.
	 *
	 * @type {Ref<number[]>}
	 */
	const namespaces = ref( ( mw.config.get( 'blockNamespaceRestrictions' ) || '' )
		.split( '\n' )
		.filter( Boolean )
		.map( Number )
	);
	/**
	 * Actions to apply the partial block to,
	 * i.e. `ipb-action-create`, `ipb-action-move`, `ipb-action-upload`.
	 *
	 * @type {Ref<string[]>}
	 */
	const partialOptions = ref( [] );
	/**
	 * The expiry of the block.
	 *
	 * @type {Ref<string>}
	 */
	const expiry = ref(
		// From URL, ?wpExpiry=...
		mw.config.get( 'blockExpiryPreset' ) ||
		// From [[MediaWiki:ipb-default-expiry]] or [[MediaWiki:ipb-default-expiry-ip]].
		mw.config.get( 'blockExpiryDefault' ) ||
		''
	);
	/**
	 * The block summary, as selected from via the dropdown in the ReasonField component.
	 * These options are ultimately defined by [[MediaWiki:Ipbreason-dropdown]].
	 *
	 * @type {Ref<string>}
	 * @todo Combine with `reasonOther` here within the store.
	 */
	const reason = ref( 'other' );
	/**
	 * The free-form text for the block summary.
	 *
	 * @type {Ref<string>}
	 * @todo Combine with `reason` here within the store.
	 */
	const reasonOther = ref( mw.config.get( 'blockReasonOtherPreset' ) || '' );
	const details = mw.config.get( 'blockDetailsPreset' ) || [];
	/**
	 * Whether to block an IP or IP range from creating accounts.
	 *
	 * @type {Ref<boolean>}
	 */
	const createAccount = ref( details.indexOf( 'wpCreateAccount' ) !== -1 );
	/**
	 * Whether to disable the target's ability to send email via Special:EmailUser.
	 *
	 * @type {Ref<boolean>}
	 */
	const disableEmail = ref( details.indexOf( 'wpDisableEmail' ) !== -1 );
	/**
	 * Whether to disable the target's ability to edit their own user talk page.
	 *
	 * @type {Ref<boolean>}
	 */
	const disableUTEdit = ref( details.indexOf( 'wpDisableUTEdit' ) !== -1 );
	const additionalDetails = mw.config.get( 'blockAdditionalDetailsPreset' ) || [];
	/**
	 * Whether to autoblock IP addresses used by the target.
	 *
	 * @type {Ref<boolean>}
	 * @see https://www.mediawiki.org/wiki/Autoblock
	 */
	const autoBlock = ref( additionalDetails.indexOf( 'wpAutoBlock' ) !== -1 );
	/**
	 * Whether to impose a "suppressed" block, hiding the target's username
	 * from block log, the active block list, and the user list.
	 *
	 * @type {Ref<boolean>}
	 */
	const hideUser = ref( additionalDetails.indexOf( 'wpHideUser' ) !== -1 );
	/**
	 * Whether to watch the target's user page and talk page.
	 *
	 * @type {Ref<boolean>}
	 */
	const watchUser = ref( additionalDetails.indexOf( 'wpWatch' ) !== -1 );
	/**
	 * Whether to apply a hard block, blocking accounts using the same IP address.
	 *
	 * @type {Ref<boolean>}
	 */
	const hardBlock = ref( additionalDetails.indexOf( 'wpHardBlock' ) !== -1 );
	/*
	 * The removal reason, used in the remove-block confirmation dialog.
	 * Note that the target and watchuser values in that form are shared with the main form.
	 *
	 * @type {Ref<string>}
	 */
	const removalReason = ref( '' );

	// Other refs that don't have corresponding form fields.

	/**
	 * Errors pertaining the form as a whole, shown at the top.
	 *
	 * @type {Ref<string[]>}
	 */
	const formErrors = ref( mw.config.get( 'blockPreErrors' ) || [] );
	/**
	 * Whether the form has been submitted. This is watched by UserLookup
	 * and ExpiryField to trigger validation on form submission.
	 * After submission, this remains true until a form field is altered.
	 * This is to ensure post-submission formErrors are not prematurely cleared.
	 *
	 * @type {Ref<boolean>}
	 */
	const formSubmitted = ref( false );
	/**
	 * Whether the form is visible. This is set by the SpecialBlock component,
	 * and unset by a watcher when the target user changes.
	 *
	 * @type {Ref<boolean>}
	 */
	const formVisible = ref( false );
	/**
	 * Whether the block was added successfully.
	 *
	 * @type {Ref<boolean>}
	 */
	const blockAdded = ref( false );
	/**
	 * Whether the block was removed successfully.
	 *
	 * @type {Ref<boolean>}
	 */
	const blockRemoved = ref( false );
	/**
	 * Whether the target user is already blocked. This is set
	 * after fetching block log data from the API.
	 *
	 * @type {Ref<boolean>}
	 */
	const alreadyBlocked = ref( mw.config.get( 'blockAlreadyBlocked' ) || false );
	/**
	 * Keep track of all UI-blocking API requests that are currently in flight.
	 *
	 * @type {Ref<Set<Promise|jQuery.Promise>>}
	 */
	const promises = ref( new Set() );
	/**
	 * Confirmation dialog message. When not null, the confirmation dialog will be
	 * shown on submission. This is set automatically by a watcher in the store.
	 *
	 * @type {Ref<string>}
	 */
	const confirmationMessage = ref( '' );
	/**
	 * Whether the target user exists. This is set by the UserLookup component.
	 *
	 * @type {Ref<boolean>}
	 */
	const targetExists = ref( false );

	// ** Getters (computed properties) **
	/**
	 * Whether the form is disabled due to an in-flight API request.
	 *
	 * @type {ComputedRef<boolean>}
	 */
	const formDisabled = computed( () => !!promises.value.size );
	/**
	 * Controls visibility of the 'Hide username' checkbox. True when the user has the
	 * hideuser right (this is passed from PHP), and the block is sitewide and infinite.
	 *
	 * @type {ComputedRef<boolean>}
	 */
	const hideUserVisible = computed( () => {
		const typeVal = type.value;
		return mw.config.get( 'blockHideUser' ) &&
			typeVal === 'sitewide' &&
			mw.util.isInfinity( expiry.value );
	} );
	/**
	 * Whether the 'Editing own talk page' checkbox is visible.
	 */
	const disableUTEditVisible = computed( () => {
		const isVisibleByConfig = mw.config.get( 'blockDisableUTEditVisible' ) || false;
		const isPartial = type.value === 'partial';
		const blocksUT = namespaces.value.indexOf( mw.config.get( 'wgNamespaceIds' ).user_talk ) !== -1;
		return isVisibleByConfig && ( !isPartial || ( isPartial && blocksUT ) );
	} );
	/**
	 * Convenience computed prop indicating if confirmation is needed on submission.
	 *
	 * @type {ComputedRef<boolean>}
	 */
	const confirmationNeeded = computed( () => !!confirmationMessage.value );

	// ** Watchers **

	// Show confirmation dialog if 'Hide username' is visible and selected,
	// or if the target user is the current user.
	watch(
		computed( () => [ targetUser.value, hideUser.value, hideUserVisible.value ] ),
		( [ newTargetUser, newHideUser, newHideUserVisible ] ) => {
			if ( newHideUserVisible && newHideUser ) {
				confirmationMessage.value = mw.message( 'ipb-confirmhideuser' ).parse();
			} else if ( newTargetUser === mw.config.get( 'wgUserName' ) ) {
				confirmationMessage.value = mw.msg( 'ipb-blockingself' );
			} else {
				confirmationMessage.value = '';
			}
		},
		// Ensure confirmationMessage is set on initial load.
		{ immediate: true }
	);

	// Hide the form and clear form-related refs when the target user changes.
	watch( targetUser, resetFormInternal );

	/**
	 * The current in-flight API request for block log data. This is used to
	 * avoid redundant API queries when rendering multiple BlockLog components.
	 *
	 * @type {Promise|null}
	 */
	let blockLogPromise = null;
	// Reset the blockLogPromise when the target user changes or the form is submitted.
	watch( [ targetUser, formSubmitted ], () => {
		blockLogPromise = null;
	} );

	// ** Actions (exported functions) **

	/**
	 * Load block data from an action=blocks API response.
	 *
	 * @param {Object} blockData The block's item from the API.
	 * @param {boolean} [loadingFromParam=false] Whether the data is being loaded from URL parameters.
	 */
	function loadFromData( blockData, loadingFromParam = false ) {
		if ( loadingFromParam ) {
			targetUser.value = blockData.user;
			formVisible.value = true;
		}
		blockId.value = blockData.id;
		type.value = blockData.partial ? 'partial' : 'sitewide';
		pages.value = ( blockData.restrictions.pages || [] ).map( ( i ) => i.title );
		namespaces.value = blockData.restrictions.namespaces || [];
		expiry.value = blockData.expiry;
		partialOptions.value = ( blockData.restrictions.actions || [] ).map( ( i ) => 'ipb-action-' + i );
		// The reason is a single string that possibly starts with one of the predefined reasons,
		// and can have an 'other' value separated by a colon.
		// Here we replicate what's done in PHP in HTMLSelectAndOtherField at https://w.wiki/CPMs
		reason.value = 'other';
		reasonOther.value = blockData.reason;
		for ( const opt of mw.config.get( 'blockReasonOptions' ) ) {
			const possPrefix = opt.value + mw.msg( 'colon-separator' );
			if ( reasonOther.value.startsWith( possPrefix ) ) {
				reason.value = opt.value;
				reasonOther.value = reasonOther.value.slice( possPrefix.length );
				break;
			}
		}
		createAccount.value = blockData.nocreate;
		disableEmail.value = blockData.noemail;
		disableUTEdit.value = !blockData.allowusertalk;
		hardBlock.value = !blockData.anononly;
		hideUser.value = blockData.hidden;
		autoBlock.value = blockData.autoblock;
		// We do not need to set watchUser as its state is never loaded from a block.
	}

	/**
	 * Reset the form to default values, optionally clearing the target user.
	 * The values here should be the defaults set on the OOUI elements in SpecialBlock.php.
	 * These are not the same as the *preset* values fetched from URL parameters.
	 *
	 * @param {boolean} [full=false] Whether to clear the target user.
	 * @todo Infuse default values once we have Codex PHP (T377529).
	 *   Until then this needs to be manually kept in sync with the PHP defaults.
	 */
	function resetForm( full = false ) {
		// Form fields
		if ( full ) {
			targetUser.value = '';
		}
		blockId.value = null;
		type.value = 'sitewide';
		pages.value = [];
		namespaces.value = [];
		partialOptions.value = [];
		expiry.value = '';
		reason.value = 'other';
		reasonOther.value = '';
		createAccount.value = true;
		disableEmail.value = false;
		disableUTEdit.value = false;
		autoBlock.value = true;
		hideUser.value = false;
		watchUser.value = false;
		hardBlock.value = false;
		// Other refs
		resetFormInternal();
	}

	/**
	 * Clear form behavioural refs.
	 *
	 * @internal
	 */
	function resetFormInternal() {
		formErrors.value = [];
		formSubmitted.value = false;
		formVisible.value = false;
		blockAdded.value = false;
		blockRemoved.value = false;
		alreadyBlocked.value = false;
		promises.value.clear();
	}

	/**
	 * Execute the block.
	 *
	 * @return {jQuery.Promise}
	 */
	function doBlock() {
		const params = {
			action: 'block',
			format: 'json',
			user: targetUser.value,
			expiry: expiry.value,
			// Localize errors
			uselang: mw.config.get( 'wgUserLanguage' ),
			errorlang: mw.config.get( 'wgUserLanguage' ),
			errorsuselocal: true
		};

		if ( !enableMultiblocks && alreadyBlocked.value ) {
			params.reblock = 1;
		}

		if ( enableMultiblocks ) {
			if ( blockId.value ) {
				params.id = blockId.value;
				delete params.user;
			} else {
				params.newblock = 1;
			}
		}

		// Reason selected concatenated with 'Other' field
		if ( reason.value === 'other' ) {
			params.reason = reasonOther.value;
		} else {
			params.reason = reason.value + (
				reasonOther.value ? mw.msg( 'colon-separator' ) + reasonOther.value : ''
			);
		}

		if ( type.value === 'partial' ) {
			params.partial = 1;
			params.actionrestrictions = Object.keys( partialOptions.value )
				.map( ( i ) => partialOptions.value[ i ].replace( 'ipb-action-', '' ) )
				.join( '|' );
			if ( pages.value.length ) {
				params.pagerestrictions = pages.value.join( '|' );
			}
			if ( namespaces.value.length ) {
				params.namespacerestrictions = namespaces.value.join( '|' );
			}
		}

		if ( createAccount.value ) {
			params.nocreate = 1;
		}

		if ( disableEmail.value ) {
			params.noemail = 1;
		}

		if ( !disableUTEditVisible.value || !disableUTEdit.value ) {
			params.allowusertalk = 1;
		}

		if ( autoBlock.value ) {
			params.autoblock = 1;
		}

		if ( hideUserVisible.value && hideUser.value ) {
			params.hidename = 1;
		}

		if ( watchUser.value ) {
			params.watchuser = 1;
		}

		if ( !hardBlock.value && mw.util.isIPAddress( targetUser.value, true ) ) {
			params.anononly = 1;
		}

		// Clear any previous errors.
		formErrors.value = [];

		return pushPromise( api.postWithEditToken( params ) );
	}

	/**
	 * Send the API request to remove a single block.
	 *
	 * @return {Promise|jQuery.Promise}
	 */
	function doRemoveBlock() {
		const params = {
			action: 'unblock',
			reason: removalReason.value
		};
		if ( blockId.value ) {
			params.id = blockId.value;
		} else {
			params.user = targetUser.value;
		}
		if ( watchUser.value ) {
			params.watchuser = 1;
		}
		// Reset the blockLogPromise so the log will be re-requested after the removal.
		blockLogPromise = null;
		return pushPromise( api.postWithEditToken( params ) );
	}

	/**
	 * Query the API for data needed by the BlockLog component. This method caches the response
	 * by target user to consolidate API requests across multiple BlockLog components.
	 * The cache is cleared when the target user changes by a watcher in the store.
	 *
	 * @param {string} blockLogType Which data to fetch. One of 'recent', 'active', or 'suppress'.
	 * @return {Promise|jQuery.Promise}
	 */
	function getBlockLogData( blockLogType ) {
		if ( blockLogPromise && blockLogType !== 'suppress' ) {
			// Serve block log data from cache if available.
			return blockLogPromise;
		}

		const params = {
			action: 'query',
			format: 'json',
			leprop: 'ids|title|type|user|timestamp|parsedcomment|details',
			letitle: `User:${ targetUser.value }`,
			list: 'logevents',
			formatversion: 2
		};

		if ( blockLogType === 'suppress' ) {
			const localPromises = [];
			// Query both the block and reblock actions of the suppression log.
			params.leaction = 'suppress/block';
			localPromises.push( pushPromise( api.get( params ) ) );
			params.leaction = 'suppress/reblock';
			localPromises.push( pushPromise( api.get( params ) ) );
			return Promise.all( localPromises );
		}

		// Cache miss for block log data.
		// Add params needed to fetch block log and active blocks in one request.
		params.list = 'logevents|blocks';
		params.letype = 'block';
		params.bkprop = 'id|user|by|timestamp|expiry|reason|parsedreason|range|flags|restrictions';
		params.bkusers = targetUser.value;

		const actualPromise = api.get( params );
		actualPromise.then( ( data ) => {
			alreadyBlocked.value = data.query.blocks.length > 0;
		} );
		blockLogPromise = Promise.all( [ actualPromise ] );
		return pushPromise( blockLogPromise );
	}

	/**
	 * Add a promise to the `Set` of pending promises.
	 * This is used solely to disable the form while waiting for a response,
	 * and should only be used for requests that need to block UI interaction.
	 * The promise will be removed from the Set when it resolves, and
	 * once the Set is empty, the form will be re-enabled.
	 *
	 * @param {Promise|jQuery.Promise} promise
	 * @return {Promise|jQuery.Promise} The same unresolved promise that was passed in.
	 */
	function pushPromise( promise ) {
		promises.value.add( promise );
		// Can't use .finally() because it's not supported in jQuery.
		promise.then(
			() => promises.value.delete( promise ),
			() => promises.value.delete( promise )
		);
		return promise;
	}

	return {
		enableMultiblocks,
		formDisabled,
		formErrors,
		formSubmitted,
		formVisible,
		targetUser,
		blockAdded,
		blockRemoved,
		blockId,
		alreadyBlocked,
		type,
		expiry,
		partialOptions,
		pages,
		namespaces,
		reason,
		reasonOther,
		createAccount,
		disableEmail,
		disableUTEdit,
		disableUTEditVisible,
		autoBlock,
		hideUser,
		hideUserVisible,
		watchUser,
		hardBlock,
		confirmationMessage,
		confirmationNeeded,
		removalReason,
		loadFromData,
		resetForm,
		doBlock,
		doRemoveBlock,
		getBlockLogData,
		targetExists
	};
} );
