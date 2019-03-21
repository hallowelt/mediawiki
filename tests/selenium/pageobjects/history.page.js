const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class HistoryPage extends Page {
	get heading() { return browser.element( '#firstHeading' ); }
	get headingText() { return browser.getText( '#firstHeading' ); }
	get comment() { return browser.element( '#pagehistory .comment' ); }
	get rollback() { return browser.element( '.mw-rollback-link' ); }
	get rollbackConfirmable() { return browser.element( '.mw-rollback-link .jquery-confirmable-text' ); }
	get rollbackConfirmableYes() { return browser.element( '.mw-rollback-link .jquery-confirmable-button-yes' ); }
	get rollbackConfirmableNo() { return browser.element( '.mw-rollback-link .jquery-confirmable-button-no' ); }

	open( title ) {
		super.openTitle( title, { action: 'history' } );
	}

	vandalizePage( name, content ) {
		let vandalUsername = 'Evil_' + browser.options.username;

		browser.call( function () {
			return Api.edit( name, content );
		} );

		browser.call( function () {
			return Api.createAccount(
				vandalUsername, browser.options.password
			);
		} );

		browser.call( function () {
			Api.edit(
				name,
				'Vandalized: ' + content,
				vandalUsername
			);
		} );
	}
}

module.exports = new HistoryPage();
