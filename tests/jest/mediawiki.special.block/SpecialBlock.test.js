'use strict';

const { nextTick, VueWrapper } = require( 'vue' );
const { flushPromises } = require( '@vue/test-utils' );
const { getSpecialBlock } = require( './SpecialBlock.setup.js' );
const useBlockStore = require( '../../../resources/src/mediawiki.special.block/stores/block.js' );

/**
 * Mock postWithEditToken to return an actual Deferred object,
 * so that jQuery promise chain methods (e.g. always()) will execute in the test.
 *
 * @param {Object} [config] Configuration to override the defaults.
 * @param {Object} [postResponse] Response to return from the postWithEditToken call.
 * @return {VueWrapper}
 */
const withSubmission = ( config, postResponse ) => {
	const jQuery = jest.requireActual( '../../../resources/lib/jquery/jquery.js' );
	mw.Api.prototype.postWithEditToken = jest.fn( () => jQuery.Deferred().resolve( postResponse ).promise() );
	HTMLFormElement.prototype.checkValidity = jest.fn().mockReturnValue( true );
	return getSpecialBlock( config );
};

describe( 'SpecialBlock', () => {
	let wrapper;

	it( 'should show no banner and no "Add block" button on page load', async () => {
		wrapper = getSpecialBlock();
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-submit' ).exists() ).toBeFalsy();
	} );

	it( 'should show no banner and an "Add block" button after selecting a valid target', async () => {
		wrapper = getSpecialBlock();
		expect( wrapper.find( '.cdx-message__content' ).exists() ).toBeFalsy();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'ipbsubmit' );
	} );

	it( 'should show a banner and no "New block" button based on if user is already blocked', () => {
		expect( wrapper.find( '.mw-block-messages .cdx-message--error' ).exists() ).toBeFalsy();
		wrapper = getSpecialBlock( {
			blockAlreadyBlocked: true,
			blockTargetUser: 'ExampleUser',
			blockPreErrors: [ 'ExampleUser is already blocked.' ]
		} );
		// Server-generated message, hence why it's in English.
		expect( wrapper.find( '.mw-block-messages .cdx-message--error' ).text() )
			.toStrictEqual( 'ExampleUser is already blocked.' );
		expect( wrapper.find( '.mw-block-log__create-button' ).exists() ).toBeFalsy();
	} );

	it( 'should submit an API request to block the user', async () => {
		wrapper = withSubmission( undefined, { block: { user: 'ExampleUser' } } );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '2999-01-23T12:34' );
		await wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		const spy = jest.spyOn( mw.Api.prototype, 'postWithEditToken' );
		const submitButton = wrapper.find( '.mw-block-submit' );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		await submitButton.trigger( 'click' );
		expect( spy ).toHaveBeenCalledWith( {
			action: 'block',
			user: 'ExampleUser',
			expiry: '2999-01-23T12:34Z',
			reason: 'This is a test',
			nocreate: 1,
			allowusertalk: 1,
			autoblock: 1,
			errorformat: 'html',
			errorlang: 'en',
			errorsuselocal: true,
			uselang: 'en',
			format: 'json',
			formatversion: 2
		} );
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
	} );

	it( 'should add an error state to invalid fields on submission', async () => {
		wrapper = withSubmission();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		await wrapper.find( '.cdx-radio__input[value=datetime]' ).setValue( true );
		// Add invalid date
		await wrapper.find( '[name=wpExpiry-other]' ).setValue( '0000-01-23T12:34:56' );
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await nextTick();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeFalsy();
		expect( wrapper.find( '.mw-block-expiry-field__datetime .cdx-text-input' ).attributes().class )
			.toContain( 'cdx-text-input--status-error' );
		expect( wrapper.find( '.mw-block-expiry-field__datetime .cdx-message--error' ).exists() )
			.toBeTruthy();
	} );

	it( 'should require confirmation for the hide-user option', async () => {
		wrapper = getSpecialBlock( { blockHideUser: true } );
		const store = useBlockStore();
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		// Assert 'hide username' is not yet visible.
		expect( wrapper.find( '.mw-block-hideuser input' ).exists() ).toBeFalsy();
		expect( store.hideUserVisible ).toBeFalsy();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		// Set the expiry to 'infinite' to enable the hide-user option.
		store.expiry = 'infinite';
		mw.util.isInfinity = jest.fn().mockReturnValue( true );
		await nextTick();
		// Assert 'hide username' is now clickable.
		expect( wrapper.find( '.mw-block-hideuser input' ).attributes().disabled ).toBeUndefined();
		expect( store.hideUserVisible ).toBeTruthy();
		expect( store.hideUser ).toBeFalsy();
		await wrapper.find( '.mw-block-hideuser input' ).trigger( 'click' );
		expect( store.hideUser ).toBeTruthy();
		// Assert confirmation is required.
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-confirmhideuser' );
		expect( store.confirmationNeeded ).toBeTruthy();
		expect( store.formSubmitted ).toBeFalsy();
		expect( wrapper.vm.confirmationOpen ).toBeFalsy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeFalsy();
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		expect( store.formSubmitted ).toBeTruthy();
		expect( wrapper.vm.confirmationOpen ).toBeTruthy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeTruthy();
		mw.util.isInfinity = jest.fn().mockReturnValue( false );
	} );

	it( 'should require confirmation for self-blocking', async () => {
		wrapper = getSpecialBlock( { wgUserName: 'ExampleUser' } );
		const store = useBlockStore();
		expect( wrapper.find( '.cdx-message--error' ).exists() ).toBeFalsy();
		expect( store.confirmationNeeded ).toBeFalsy();
		expect( store.confirmationMessage ).toStrictEqual( '' );
		await wrapper.find( '[name=wpTarget]' ).setValue( 'ExampleUser' );
		await wrapper.find( '[name=wpTarget]' ).trigger( 'change' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		store.expiry = '3 days';
		expect( store.confirmationNeeded ).toBeTruthy();
		expect( store.confirmationMessage ).toStrictEqual( 'ipb-blockingself' );
		expect( store.formSubmitted ).toBeFalsy();
		expect( wrapper.vm.confirmationOpen ).toBeFalsy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeFalsy();
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await nextTick();
		expect( store.formSubmitted ).toBeTruthy();
		expect( wrapper.vm.confirmationOpen ).toBeTruthy();
		expect( document.body.querySelector( '.mw-block-confirm' ) ).toBeTruthy();
	} );

	it( 'should reset form refs after blocking', async () => {
		wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser' },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		expect( wrapper.find( '[data-test=edit-block-button]' ).exists() ).toBeTruthy();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'block-update' );
		wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reason ).toStrictEqual( 'other' );
		expect( store.reasonOther ).toStrictEqual( 'This is a test' );
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await flushPromises();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeFalsy();
		expect( store.reason ).toStrictEqual( 'other' );
		expect( store.reasonOther ).toStrictEqual( '' );
		expect( store.blockId ).toStrictEqual( null );
	} );

	it( 'should use pre-set values when creating a new block', async () => {
		wrapper = getSpecialBlock( {
			blockTargetUser: 'ExampleUser',
			blockTargetExists: true,
			blockTypePreset: 'partial',
			blockPageRestrictions: 'Foo\nBar',
			blockExpiryPreset: '99 hours'
		} );
		const store = useBlockStore();
		await flushPromises();
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		expect( store.type ).toStrictEqual( 'partial' );
		expect( store.pages ).toStrictEqual( [ 'Foo', 'Bar' ] );
		expect( store.expiry ).toStrictEqual( '99 hours' );
		expect( wrapper.find( '[name=expiryType]:checked' ).element.value ).toStrictEqual( 'custom-duration' );
		expect( wrapper.find( 'input[type=number]' ).element.value ).toStrictEqual( '99' );
		expect( wrapper.find( '.mw-block-pages' ).text() ).toStrictEqual( 'FooBar' );
	} );

	afterEach( () => {
		wrapper.unmount();
	} );
} );

describe( 'SpecialBlock (multiblocks)', () => {
	it( 'should show an "Add block" button in the Active blocks accordion', async () => {
		const wrapper = getSpecialBlock( {
			blockEnableMultiblocks: true,
			blockTargetExists: true
		} );
		expect( wrapper.find( '.mw-block-log__create-button' ).exists() ).toBeTruthy();
	} );

	it( 'should reset the form to the initial state for subsequent blocks (T384822)', async () => {
		const wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true, blockEnableMultiblocks: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		// Edit a block.
		expect( wrapper.find( '[data-test=edit-block-button]' ).exists() ).toBeTruthy();
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block-submit' ).text() ).toStrictEqual( 'block-update' );
		wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reason ).toStrictEqual( 'other' );
		expect( store.reasonOther ).toStrictEqual( 'This is a test' );
		// Submit.
		await wrapper.find( '.mw-block-submit' ).trigger( 'click' );
		await flushPromises();
		expect( wrapper.find( '.mw-block-success' ).exists() ).toBeTruthy();
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeFalsy();
		expect( store.reason ).toStrictEqual( 'other' );
		expect( store.reasonOther ).toStrictEqual( '' );
		// Add a new block.
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		expect( wrapper.find( '.mw-block__block-form' ).exists() ).toBeTruthy();
		expect( store.blockId ).toBeNull();
		expect( store.reason ).toStrictEqual( 'other' );
		expect( wrapper.find( '[name=wpReason-other]' ).element.value ).toStrictEqual( '' );
	} );

	it( 'should reset the form to the initial state for new blocks when the form is open', async () => {
		const wrapper = withSubmission(
			{ blockTargetUser: 'ActiveBlockedUser', blockTargetExists: true, blockEnableMultiblocks: true },
			{ block: { user: 'ActiveBlockedUser' } }
		);
		const store = useBlockStore();
		await flushPromises();
		// Edit a block.
		await wrapper.find( '[data-test=edit-block-button]' ).trigger( 'click' );
		wrapper.find( '[name=wpReason-other]' ).setValue( 'This is a test' );
		expect( store.reasonOther ).toStrictEqual( 'This is a test' );
		// Open the 'Active blocks' accordion and add a new block.
		await wrapper.find( '.mw-block-log__type-active' ).trigger( 'click' );
		await wrapper.find( '.mw-block-log__create-button' ).trigger( 'click' );
		expect( store.blockId ).toBeNull();
		expect( store.reasonOther ).toStrictEqual( '' );
		expect( wrapper.find( '[name=wpReason-other]' ).element.value ).toStrictEqual( '' );
	} );
} );
