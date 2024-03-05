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
		'jsdoc-plugin-allow-dots-in-modules',
		'plugins/markdown',
		'plugins/summarize'
	],
	source: {
		include: [
			'resources/src/'
		],
		exclude: [
			/* The following modules are temporarily disabled as we haven't
			 got round to reviewing them and incorporating them into the documentation page yet. */
			'resources/src/codex',
			'resources/src/codex-search',
			'resources/src/mediawiki.router',
			'resources/src/mediawiki.language.specialCharacters',
			'resources/src/moment',
			'resources/src/oojs-global.js',
			'resources/src/mediawiki.notification.convertmessagebox.js',
			'resources/src/ooui-local.js',
			'resources/src/vue'
		]
	},
	templates: {
		cleverLinks: true,
		default: {
			useLongnameInNav: true
		},
		wmf: {
			repository: 'https://gerrit.wikimedia.org/g/mediawiki/core/',
			linkMap: {
				Array: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array',
				Blob: 'https://developer.mozilla.org/en-US/docs/Web/API/Blob',
				CSSStyleSheet: 'https://developer.mozilla.org/en-US/docs/Web/API/CSSStyleSheet',
				Event: 'https://developer.mozilla.org/en-US/docs/Web/API/Event',
				File: 'https://developer.mozilla.org/en-US/docs/Web/API/File',
				HTMLElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement',
				HTMLInputElement: 'https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement',
				'JSON.parse': 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON/parse',
				jQuery: 'https://api.jquery.com/jQuery/',
				'jQuery.fn': 'https://api.jquery.com/jQuery/',
				'jQuery.Deferred': 'https://api.jquery.com/Types/#Deferred',
				'jQuery.Event': 'https://api.jquery.com/Types/#Event',
				'jQuery.Promise': 'https://api.jquery.com/Types/#Promise',
				Node: 'https://developer.mozilla.org/en-US/docs/Web/API/Node',
				'OO.ui.ActionFieldLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ActionFieldLayout.html',
				'OO.ui.ButtonGroupWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonGroupWidget.html',
				'OO.ui.ButtonOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonOptionWidget.html',
				'OO.ui.ButtonMenuSelectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonMenuSelectWidget.html',
				'OO.ui.BookletLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.BookletLayout.html',
				'OO.ui.ButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ButtonWidget.html',
				'OO.ui.CheckboxInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.CheckboxInputWidget',
				'OO.ui.CopyTextLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.CopyTextLayout.html',
				'OO.ui.DropdownInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.DropdownInputWidget.html',
				'OO.ui.DropdownWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.OO.ui.DropdownWidget.html',
				'OO.ui.FieldLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.FieldLayout.html',
				'OO.ui.FormLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.FormLayout.html',
				'OO.ui.InputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.InputWidget.html',
				'OO.ui.MenuOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuOptionWidget.html',
				'OO.ui.MenuSectionOptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuSectionOptionWidget.html',
				'OO.ui.MenuSelectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuSelectWidget.html',
				'OO.ui.MenuTagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MenuTagMultiselectWidget.html',
				'OO.ui.MessageDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.MessageDialog.html',
				'OO.ui.OptionWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.OptionWidget.html',
				'OO.ui.PopupButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PopupButtonWidget.html',
				'OO.ui.PopupWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PopupWidget.html',
				'OO.ui.PageLayout': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.PageLayout.html',
				'OO.ui.ProcessDialog': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ProcessDialog.html',
				'OO.ui.SearchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchWidget.html',
				'OO.ui.SearchInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SearchInputWidget.html',
				'OO.ui.SelectFileInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.SelectFileInputWidget.html',
				'OO.ui.TagItemWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagItemWidget.html',
				'OO.ui.TagMultiselectWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TagMultiselectWidget.html',
				'OO.ui.TextInputWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.TextInputWidget.html',
				'OO.ui.ToggleButtonWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ToggleButtonWidget.html',
				'OO.ui.ToggleSwitchWidget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.ToggleSwitchWidget.html',
				'OO.ui.Widget': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.Widget.html',
				'OO.ui.WindowManager': 'https://doc.wikimedia.org/oojs-ui/master/js/OO.ui.WindowManager.html',
				Promise: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise',
				Set: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set',
				URLSearchParams: 'https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams'
			}
		}
	}
};
