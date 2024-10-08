( function () {

	let jqcookie;
	const NOW = 9012, // miliseconds
		DEFAULT_DURATION = 5678, // seconds
		defaults = {
			prefix: 'mywiki',
			domain: 'example.org',
			path: '/path',
			expires: DEFAULT_DURATION,
			secure: false
		},
		mwCookie = require( 'mediawiki.cookie' ),
		setDefaults = mwCookie.setDefaults,
		expiryDate = new Date();

	expiryDate.setTime( NOW + ( DEFAULT_DURATION * 1000 ) );

	QUnit.module( 'mediawiki.cookie', {
		beforeEach: function () {
			jqcookie = sinon.stub( mwCookie.jar, 'cookie' ).returns( null );
			this.clock = sinon.useFakeTimers( NOW );
			this.savedDefaults = setDefaults( defaults );
		},
		afterEach: function () {
			jqcookie.restore();
			this.clock.restore();
			setDefaults( this.savedDefaults );
		}
	} );

	QUnit.test( 'set( key, value )', ( assert ) => {
		let call;

		// Simple case
		mw.cookie.set( 'foo', 'bar' );

		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'mywikifoo' );
		assert.strictEqual( call[ 1 ], 'bar' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'example.org',
			path: '/path',
			secure: false
		} );

		mw.cookie.set( 'foo', null );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], null, 'null removes cookie' );

		mw.cookie.set( 'foo', undefined );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'undefined', 'undefined is value' );

		mw.cookie.set( 'foo', false );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], 'false', 'false is a value' );

		mw.cookie.set( 'foo', 0 );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 1 ], '0', '0 is value' );
	} );

	QUnit.test( 'set( key, value, expires )', ( assert ) => {
		let date, options;

		date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, expiryDate, 'default expiration' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration as Date' );

		date = new Date();
		date.setDate( date.getDate() + 1 );

		mw.cookie.set( 'foo', 'bar', 86400 );
		options = jqcookie.lastCall.args[ 2 ];
		assert.deepEqual( options.expires, date, 'custom expiration as lifetime in seconds' );

		mw.cookie.set( 'foo', 'bar', null );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'null forces session cookie' );

		// Per MainConfigSchema, if the CookieExpiration setting is 0,
		// then the default should be session cookies
		setDefaults( Object.assign( {}, defaults, { expires: 0 } ) );

		mw.cookie.set( 'foo', 'bar' );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, undefined, 'wgCookieExpiration=0 results in session cookies by default' );

		mw.cookie.set( 'foo', 'bar', date );
		options = jqcookie.lastCall.args[ 2 ];
		assert.strictEqual( options.expires, date, 'custom expiration (with wgCookieExpiration=0)' );
	} );

	QUnit.test( 'set( key, value, options )', ( assert ) => {
		mw.cookie.set( 'foo', 'bar', {
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		let call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: expiryDate,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (without expires)' );

		const date = new Date();
		date.setTime( 1234 );

		mw.cookie.set( 'foo', 'bar', {
			expires: date,
			prefix: 'myPrefix',
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		} );

		assert.strictEqual( jqcookie.callCount, 2 );
		call = jqcookie.lastCall.args;
		assert.strictEqual( call[ 0 ], 'myPrefixfoo' );
		assert.deepEqual( call[ 2 ], {
			expires: date,
			domain: 'myDomain',
			path: 'myPath',
			secure: true
		}, 'Options (incl. expires)' );
	} );

	QUnit.test( 'get( key ) - no values', ( assert ) => {
		let key, value;

		mw.cookie.get( 'foo' );

		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Default prefix' );

		mw.cookie.get( 'foo', undefined );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for undefined' );

		mw.cookie.get( 'foo', null );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'mywikifoo', 'Use default prefix for null' );

		mw.cookie.get( 'foo', '' );
		key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'foo', 'Don\'t use default prefix for empty string' );

		value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, null, 'Return null by default' );

		value = mw.cookie.get( 'foo', null, 'bar' );
		assert.strictEqual( value, 'bar', 'Custom default value' );
	} );

	QUnit.test( 'get( key ) - with value', ( assert ) => {
		jqcookie.returns( 'bar' );

		const value = mw.cookie.get( 'foo' );
		assert.strictEqual( value, 'bar', 'Return value of cookie' );
	} );

	QUnit.test( 'get( key, prefix )', ( assert ) => {
		mw.cookie.get( 'foo', 'bar' );

		const key = jqcookie.lastCall.args[ 0 ];
		assert.strictEqual( key, 'barfoo' );
	} );

}() );
