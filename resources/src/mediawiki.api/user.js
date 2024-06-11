( function () {

	/**
	 * @typedef {Object} mw.Api.UserInfo
	 * @property {string[]} groups User groups that the current user belongs to
	 * @property {string[]} rights Current user's rights
	 */

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {

		/**
		 * Get the current user's groups and rights.
		 *
		 * @since 1.27
		 * @return {jQuery.Promise<mw.Api.UserInfo>}
		 */
		getUserInfo: function () {
			return this.get( {
				action: 'query',
				meta: 'userinfo',
				uiprop: [ 'groups', 'rights' ]
			} ).then( ( data ) => {
				if ( data.query && data.query.userinfo ) {
					return data.query.userinfo;
				}
				return $.Deferred().reject().promise();
			} );
		},

		/**
		 * Extend an API parameter object with an assertion that the user won't change.
		 *
		 * This is useful for API calls which create new revisions or log entries. When the current
		 * page was loaded when the user was logged in, but at the time of the API call the user
		 * is not logged in anymore (e.g. due to session expiry), their IP is recorded in the page
		 * history or log, which can cause serious privacy issues. Extending the API parameters via
		 * this method ensures that that won't happen, by checking the user's identity that was
		 * embedded into the page when it was rendered against the active session on the server.
		 *
		 * When the assertion fails, the API request will fail, with one of the following error codes:
		 * - apierror-assertanonfailed: when the client-side logic thinks the user is anonymous
		 *   but the server thinks it is logged in;
		 * - apierror-assertuserfailed: when the client-side logic thinks the user is logged in but the
		 *   server thinks it is anonymous;
		 * - apierror-assertnameduserfailed: when both the client-side logic and the server thinks the
		 *   user is logged in but they see it logged in under a different username.
		 *
		 * @example
		 * api.postWithToken( 'csrf', api.assertCurrentUser( { action: 'edit', ... } ) )
		 *
		 * @since 1.27
		 * @param {Object} query Query parameters. The object will not be changed.
		 * @return {Object}
		 */
		assertCurrentUser: function ( query ) {
			var user = mw.config.get( 'wgUserName' ),
				assertParams = {};

			if ( user !== null ) {
				assertParams.assert = 'user';
				assertParams.assertuser = user;
			} else {
				assertParams.assert = 'anon';
			}

			return Object.assign( assertParams, query );
		}

	} );

}() );
