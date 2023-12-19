'use strict';
module.exports = {
	opts: {
		destination: 'docs/js',
		package: 'resources/package.json',
		pedantic: true,
		readme: 'resources/README.md',
		recurse: true,
		template: 'node_modules/jsdoc-wmf-theme'
	},
	plugins: [
		'plugins/markdown',
		'plugins/summarize'
	],
	source: {
		include: [
			'resources/src/mediawiki.Title',
			'resources/src/mediawiki.cookie',
			'resources/src/mediawiki.storage.js',
			'resources/src/mediawiki.String.js',
			'resources/src/jsdoc.types.js',
			'resources/src/jquery.lengthLimit.js',
			'resources/src/mediawiki.api',
			'resources/src/mediawiki.base',
			'resources/src/mediawiki.experiments.js',
			'resources/src/mediawiki.notification',
			'resources/src/mediawiki.page.preview.js',
			'resources/src/mediawiki.util',
			'resources/src/startup',
			'resources/src/mediawiki.user.js',
			'resources/src/mediawiki.Uri'

		],
		exclude: [
			/* The following modules are temporarily disabled as we haven't
			 got round to reviewing them and incorporating them into the documentation page yet. */
			'resources/src/codex',
			'resources/src/mediawiki.page.image.pagination.js',
			'resources/src/codex-search',
			'resources/src/mediawiki.page.media.js',
			'resources/src/jquery/*',
			'resources/src/jquery.spinner',
			'resources/src/mediawiki.page.ready',
			'resources/src/jquery.tablesorter',
			'resources/src/mediawiki.page.watch.ajax',
			'resources/src/mediawiki.ForeignApi',
			'resources/src/mediawiki.pulsatingdot',
			'resources/src/mediawiki.ForeignStructuredUpload.BookletLayout',
			'resources/src/mediawiki.rcfilters',
			'resources/src/mediawiki.ForeignUpload.js',
			'resources/src/mediawiki.router',
			'resources/src/mediawiki.searchSuggest',
			'resources/src/mediawiki.skinning',
			'resources/src/mediawiki.Upload.BookletLayout',
			'resources/src/mediawiki.special',
			'resources/src/mediawiki.Upload.Dialog.js',
			'resources/src/mediawiki.special.apisandbox',
			'resources/src/mediawiki.Upload.js',
			'resources/src/mediawiki.special.block.js',
			'resources/src/mediawiki.special.changecredentails.js',
			'resources/src/mediawiki.action',
			'resources/src/mediawiki.special.changeemail.js',
			'resources/src/mediawiki.action.edit',
			'resources/src/mediawiki.special.changeslist',
			'resources/src/mediawiki.special.changeslist.legend.js',
			'resources/src/mediawiki.apipretty',
			'resources/src/mediawiki.special.changeslist.watchlistexpiry',
			'resources/src/mediawiki.checkboxtoggle.js',
			'resources/src/mediawiki.special.contributions.js',
			'resources/src/mediawiki.special.createaccount',
			'resources/src/mediawiki.cldr',
			'resources/src/mediawiki.special.editrecovery',
			'resources/src/mediawiki.confirmCloseWindow.js',
			'resources/src/mediawiki.special.preferences.ooui',
			'resources/src/mediawiki.special.search',
			'resources/src/mediawiki.debug',
			'resources/src/mediawiki.special.search.commonsInterwikiWidget.js',
			'resources/src/mediawiki.deflate',
			'resources/src/mediawiki.diff',
			'resources/src/mediawiki.special.unwatchedPages',
			'resources/src/mediawiki.editRecovery',
			'resources/src/mediawiki.special.upload',
			'resources/src/mediawiki.feedback',
			'resources/src/mediawiki.feedlink',
			'resources/src/mediawiki.special.userrights.js',
			'resources/src/mediawiki.filewarning',
			'resources/src/resources/src/mediawiki.special.watchlist',
			'resources/src/mediawiki.helplink',
			'resources/src/mediawiki.tempUserBanner',
			'resources/src/mediawiki.htmlform',
			'resources/src/mediawiki.tempUserCreated',
			'resources/src/mediawiki.htmlform.ooui',
			'resources/src/mediawiki.template.js',
			'resources/src/mediawiki.template.mustache.js',
			'resources/src/mediawiki.toc',
			'resources/src/mediawiki.inspect.js',
			'resources/src/mediawiki.jqueryMsg',
			'resources/src/mediawiki.language',
			'resources/src/mediawiki.language.months',
			'resources/src/mediawiki.language.names',
			'resources/src/mediawiki.language.specialCharacters',
			'resources/src/mediawiki.userSuggest.js',
			'resources/src/mediawiki.libs.jpegmeta',
			'resources/src/mediawiki.visibleTimeout',
			'resources/src/mediawiki.libs.pluralruleparser',
			'resources/src/mediawiki.watchstar.widgets',
			'resources/src/mediawiki.messagePoster',
			'resources/src/mediawiki.widgets',
			'resources/src/mediawiki.misc-authed-curate',
			'resources/src/mediawiki.widgets.datetime',
			'resources/src/mediawiki.misc-authed-ooui',
			'resources/src/mediawiki.widgets.visibleLengthLimit',
			'resources/src/mediawiki.misc-authed-pref',
			'resources/src/moment',
			'resources/src/oojs-global.js',
			'resources/src/mediawiki.notification.convertmessagebox.js',
			'resources/src/ooui-local.js',
			'resources/src/qunitjs',
			'resources/src/mediawiki.page.gallery.js',
			'resources/src/skip-web2017-polyfills.js',
			'resources/src/mediawiki.page.gallery.slideshow.js',
			'resources/src/vue'
		]
	},
	templates: {
		cleverLinks: true,
		default: {
			useLongnameInNav: true
		},
		wmf: {
			linkMap: {
				Array: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array',
				Blob: 'https://developer.mozilla.org/en-US/docs/Web/API/Blob',
				CSSStyleSheet: 'https://developer.mozilla.org/en-US/docs/Web/API/CSSStyleSheet',
				File: 'https://developer.mozilla.org/en-US/docs/Web/API/File',
				HTMLElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement',
				HTMLInputElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement',
				'JSON.parse': 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON/parse',
				jQuery: 'https://api.jquery.com/jQuery/',
				'jQuery.fn': 'https://api.jquery.com/jQuery/',
				'jQuery.Event': 'https://api.jquery.com/Types/#Event',
				'jQuery.Promise': 'https://api.jquery.com/Types/#Promise',
				Promise: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise',
				Set: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set',
				URLSearchParams: 'https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams'
			}
		}
	}
};
