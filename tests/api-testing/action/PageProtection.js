'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Page protection', function () {
	// users
	let admin, wikiUser;
	const anonymousUser = action.getAnon();

	before( async () => {
		admin = await action.mindy();
		wikiUser = await action.alice();
	} );

	describe( 'levels', function () {
		const protectedPage = utils.title( 'Protected_' );
		const semiProtectedPage = utils.title( 'SemiProtected_' );

		before( async () => {
			// Get edit token for admin
			const adminEditToken = await admin.token();

			// Create Protected page
			await admin.edit( protectedPage, { text: 'Protected Page' } );

			// Create SemiProtected page
			await admin.edit( semiProtectedPage, { text: 'Semi Protected Page' } );

			// Add edit protections to only allow members of sysop group to edit Protected page
			const addSysopProtection = await admin.action( 'protect', { title: protectedPage, token: adminEditToken, protections: 'edit=sysop' }, 'POST' );
			assert.equal( addSysopProtection.protect.protections[ 0 ].edit, 'sysop' );

			// Add edit protections to only allow auto confirmed users to edit Semi Protected page
			const addAutoConfirmedProtection = await admin.action( 'protect', { title: semiProtectedPage, token: adminEditToken, protections: 'edit=autoconfirmed' }, 'POST' );
			assert.equal( addAutoConfirmedProtection.protect.protections[ 0 ].edit, 'autoconfirmed' );
		} );

		it( 'should allow admin to edit Protected page', async () => {
			await admin.edit( protectedPage, { text: 'Admin editing protected page' } );
		} );

		it( 'should allow admin to edit Semi Protected page', async () => {
			await admin.edit( semiProtectedPage, { text: 'Admin editing semi protected page' } );
		} );

		it( 'should NOT allow autoconfirmed user to edit Protected page', async () => {
			const token = await wikiUser.token();
			const editPage = await wikiUser.actionError( 'edit', {
				title: protectedPage,
				token,
				text: 'wikiUser editing protected page'
			}, 'POST' );

			assert.equal( editPage.code, 'protectedpage' );
		} );

		it( 'should allow autoconfirmed user to edit Semi Protected page', async () => {
			await wikiUser.edit( semiProtectedPage, { text: 'wikiUser editing semi protected page' } );
		} );

		it( 'should NOT allow anonymous user to edit Protected page', async () => {
			const token = await anonymousUser.token();
			const editPage = await anonymousUser.actionError( 'edit', {
				title: protectedPage,
				token,
				text: 'anonymous user editing protected page'
			}, 'POST' );

			assert.equal( editPage.code, 'protectedpage' );
		} );

		it( 'should NOT allow anonymous user to edit Semi Protected page', async () => {
			const token = await anonymousUser.token();
			const editPage = await anonymousUser.actionError( 'edit', {
				title: semiProtectedPage,
				token,
				text: 'anonymous user editing semi protected page'
			}, 'POST' );

			assert.equal( editPage.code, 'protectedpage' );
		} );

		it( 'should allow a restriction to be specified that allows users to edit it and only sysops to move a page', async () => {
			const adminEditToken = await admin.token();
			const token = await anonymousUser.token();
			const title = utils.title( 'TestExpiry_' );
			await admin.edit( title, {
				text: 'Test for Expiry'
			} );
			await admin.action( 'protect', {
				title,
				token: adminEditToken,
				expiry: 'infinite',
				protections: 'edit=autoconfirmed|move=sysop'
			}, 'POST' );

			const editPage = await anonymousUser.actionError( 'edit', {
				title,
				token,
				text: 'wikiUser editing protected page'
			}, 'POST' );

			const movePage = await anonymousUser.actionError( 'move', {
				from: title,
				to: 'Page with new Title',
				token,
				reason: 'to test that the page cannot be moved by a wikiUser'
			}, 'POST' );

			assert.equal( editPage.code, 'protectedpage' );
			assert.equal( movePage.code, 'cantmove-anon' );
		} );
	} );

	describe( '"expiry" parameter', function () {

		const testForExpiry = async ( expiry ) => {
			const adminEditToken = await admin.token();
			const token = await wikiUser.token();
			const title = utils.title( 'TestExpiry_' );
			await admin.edit( title, {
				text: 'Test for Expiry'
			} );
			await admin.action( 'protect', {
				title,
				token: adminEditToken,
				expiry: expiry,
				protections: 'edit=sysop'
			}, 'POST' );

			const editPage = await wikiUser.actionError( 'edit', {
				title,
				token,
				text: 'wikiUser editing protected page'
			}, 'POST' );
			return editPage.code;
		};

		it( 'should apply protections to a page for an indefinite duration', async () => {
			const errorCode = await testForExpiry( 'indefinite' );
			assert.equal( errorCode, 'protectedpage' );
		} );

		it( 'should apply protections to a page for an infinite duration', async () => {
			const errorCode = await testForExpiry( 'infinite' );
			assert.equal( errorCode, 'protectedpage' );
		} );

		it( 'should apply protections to a page for an infinity duration', async () => {
			const errorCode = await testForExpiry( 'infinity' );
			assert.equal( errorCode, 'protectedpage' );
		} );

		it( 'should apply protections to a page for some centuries to come', async () => {
			const errorCode = await testForExpiry( '9999-01-01T00:00:00Z' );
			assert.equal( errorCode, 'protectedpage' );
		} );
	} );

	describe( '"watchlist" parameter', function () {
		// users
		let eve;

		const testWatchlist = utils.title( 'TestWatchlist_' );

		before( async () => {
			eve = action.getAnon();
			await eve.account( 'Eve' );
			const root = await action.root();
			await root.addGroups( eve.username, [ 'sysop' ] );

			// Create TestWatchlist page
			await eve.edit( testWatchlist, {
				text: 'Protecting this page'
			} );
		} );

		const protectWithWatchParam = async ( watchedBefore, watchlistParam, title ) => {
			const watchArgs = {
				title: title,
				token: await eve.token( 'watch' )
			};
			if ( watchedBefore !== 'watched' ) {
				watchArgs.unwatch = true;
			}
			await eve.action( 'watch', watchArgs, 'POST' );
			const token = await eve.token();
			await eve.action( 'protect', {
				title,
				token,
				protections: 'edit=autoconfirmed',
				watchlist: watchlistParam
			}, 'POST' );
			const res = await eve.action( 'query', {
				list: 'watchlistraw',
				wrfromtitle: title,
				wrtotitle: title
			} );
			return res.watchlistraw;
		};

		it( 'should have added the page the the user\'s watchlist per default', async () => {
			const list = await eve.action( 'query', {
				list: 'watchlistraw',
				wrfromtitle: testWatchlist,
				wrtotitle: testWatchlist
			} );
			assert.sameTitle( list.watchlistraw[ 0 ].title, testWatchlist );
		} );

		it( 'should add the page to the watchlist if set to "preferences"', async () => {
			const list = await protectWithWatchParam( 'watched', 'preferences', testWatchlist );
			assert.sameTitle( list[ 0 ].title, testWatchlist );
		} );

		it( 'should not change the pages on the watchlist if set to "nochange"', async () => {
			const list = await protectWithWatchParam( 'watched', 'nochange', testWatchlist );
			assert.sameTitle( list[ 0 ].title, testWatchlist );
		} );

		it( 'should remove a page from the users watchlist if set to "unwatch"', async () => {
			const list = await protectWithWatchParam( 'watched', 'unwatch', testWatchlist );
			assert.isEmpty( list );
		} );

		it( 'should add a page to the users watchlist if set to "watch"', async () => {
			const list = await protectWithWatchParam( 'unwatched', 'watch', testWatchlist );
			assert.sameTitle( list[ 0 ].title, testWatchlist );
		} );
	} );
} );
