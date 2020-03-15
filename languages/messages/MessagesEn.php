<?php
/**
 * This is the default English localisation file containing language specific
 * information excluding interface strings, which are stored in JSON files.
 *
 * Please see https://www.mediawiki.org/wiki/Localisation for more information.
 * To improve a translation please visit https://translatewiki.net
 */

/**
 * Fallback language, used for all unspecified messages and behavior. This
 * is English by default, for all files other than this one.
 *
 * Do NOT set this to false in any other message file! Leave the line out to
 * accept the default fallback to "en".
 */
$fallback = false;

/**
 * Is the language written right-to-left?
 */
$rtl = false;

/**
 * Should all nouns (not just proper ones) be capitalized?
 * Enabling this property will add the capitalize-all-nouns class to the <body> tag
 */
$capitalizeAllNouns = false;

/**
 * Optional array mapping ASCII digits 0-9 to local digits.
 */
$digitTransformTable = null;

/**
 * Transform table for decimal point '.' and thousands separator ','
 */
$separatorTransformTable = null;

/**
 * URLs do not specify their encoding. UTF-8 is used by default, but if the
 * URL is not a valid UTF-8 sequence, we have to try to guess what the real
 * encoding is. The encoding used in this case is defined below, and must be
 * supported by iconv().
 */
$fallback8bitEncoding = 'windows-1252';

/**
 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
 */
$linkPrefixExtension = false;

/**
 * Namespace names. NS_PROJECT is always set to $wgMetaNamespace after the
 * settings are loaded, it will be ignored even if you specify it here.
 *
 * NS_PROJECT_TALK will be set to $wgMetaNamespaceTalk if that variable is
 * set, otherwise the string specified here will be used. The string may
 * contain "$1", which will be replaced by the name of NS_PROJECT. It may
 * also contain a grammatical transformation, e.g.
 *
 *     NS_PROJECT_TALK => 'Keskustelu_{{grammar:elative|$1}}'
 *
 * Only one grammatical transform may be specified in the string. For
 * performance reasons, this transformation is done locally by the language
 * module rather than by the full wikitext parser. As a result, no other
 * parser features are available.
 */
$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
];

/**
 * Array of namespace aliases, mapping from name to NS_xxx index.
 *
 * Note that 'namespaceAliases' is a mergable language attribute,
 * which means it is combined with other languages in the fallback chain.
 */
$namespaceAliases = [
	// The canonical names of namespaces 6 and 7 are, as of MediaWik 1.14,
	// "File" and "File_talk".  The old names "Image" and "Image_talk" are
	// retained as aliases for backwards compatibility.
	// This must apply regardless of site language (and does, given 'en' is at
	// the end of all fallback chains.)
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
];

/**
 * Array of gender specific. namespace aliases.
 * Mapping NS_xxx to array of GENDERKEY to alias.
 * Example:
 * @code
 * $namespaceGenderAliases = [
 *   NS_USER => [ 'male' => 'Male_user', 'female' => 'Female_user' ],
 * ];
 * @endcode
 */
$namespaceGenderAliases = [];

/**
 * A list of date format preference keys, which can be selected in user
 * preferences. New preference keys can be added, provided they are supported
 * by the language class's timeanddate(). Only the 5 keys listed below are
 * supported by the wikitext converter (parser/DateFormatter.php).
 *
 * The special key "default" is an alias for either dmy or mdy depending on
 * $wgAmericanDates
 */
$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy or mdy';

/**
 * Associative array mapping old numeric date formats, which may still be
 * stored in user preferences, to the new string formats.
 */
$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

/**
 * These are formats for dates generated by MediaWiki (as opposed to the wikitext
 * DateFormatter). Documentation for the format string can be found in
 * Language.php, search for sprintfDate.
 *
 * This array is automatically inherited by all subclasses. Individual keys can be
 * overridden.
 */
$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy monthonly' => 'F Y',
	'mdy both' => 'H:i, F j, Y',
	'mdy pretty' => 'F j',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy monthonly' => 'F Y',
	'dmy both' => 'H:i, j F Y',
	'dmy pretty' => 'j F',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd monthonly' => 'Y F',
	'ymd both' => 'H:i, Y F j',
	'ymd pretty' => 'F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 monthonly' => 'xnY-xnm',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
	'ISO 8601 pretty' => 'xnm-xnd'
];

/**
 * Default list of book sources
 */
$bookstoreList = [
	'BWB' => 'https://www.betterworldbooks.com/product/detail/-$1',
	'OpenLibrary' => 'https://openlibrary.org/isbn/$1',
	'Worldcat' => 'https://www.worldcat.org/search?q=isbn:$1',
];

/**
 * Magic words
 * Customizable syntax for wikitext and elsewhere.
 *
 * IDs must be valid identifiers, they cannot contain hyphens.
 * CASE is 0 to match all case variants, 1 for case-sensitive
 *
 * Note to localisers:
 *   - Include the English magic words as synonyms. This allows people from
 *     other wikis that do not speak the language to contribute more easily.
 *   - The first alias listed MUST be the preferred alias in that language.
 *     Tools (like Visual Editor) are expected to use the first listed alias
 *     when editing or creating new content.
 *   - Order the other aliases so that common aliases occur before more rarely
 *     used aliases. The aliases SHOULD be sorted by the following convention:
 *     1. Local first, English last, then
 *     2. Most common first, least common last.
 */
$magicWords = [
#   ID                               CASE  SYNONYMS
	'redirect'                => [ 0, '#REDIRECT' ],
	'notoc'                   => [ 0, '__NOTOC__' ],
	'nogallery'               => [ 0, '__NOGALLERY__' ],
	'forcetoc'                => [ 0, '__FORCETOC__' ],
	'toc'                     => [ 0, '__TOC__' ],
	'noeditsection'           => [ 0, '__NOEDITSECTION__' ],
	'!'                       => [ 1, '!' ],
	'currentmonth'            => [ 1, 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'           => [ 1, 'CURRENTMONTH1' ],
	'currentmonthname'        => [ 1, 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'     => [ 1, 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'      => [ 1, 'CURRENTMONTHABBREV' ],
	'currentday'              => [ 1, 'CURRENTDAY' ],
	'currentday2'             => [ 1, 'CURRENTDAY2' ],
	'currentdayname'          => [ 1, 'CURRENTDAYNAME' ],
	'currentyear'             => [ 1, 'CURRENTYEAR' ],
	'currenttime'             => [ 1, 'CURRENTTIME' ],
	'currenthour'             => [ 1, 'CURRENTHOUR' ],
	'localmonth'              => [ 1, 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'             => [ 1, 'LOCALMONTH1' ],
	'localmonthname'          => [ 1, 'LOCALMONTHNAME' ],
	'localmonthnamegen'       => [ 1, 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'        => [ 1, 'LOCALMONTHABBREV' ],
	'localday'                => [ 1, 'LOCALDAY' ],
	'localday2'               => [ 1, 'LOCALDAY2' ],
	'localdayname'            => [ 1, 'LOCALDAYNAME' ],
	'localyear'               => [ 1, 'LOCALYEAR' ],
	'localtime'               => [ 1, 'LOCALTIME' ],
	'localhour'               => [ 1, 'LOCALHOUR' ],
	'numberofpages'           => [ 1, 'NUMBEROFPAGES' ],
	'numberofarticles'        => [ 1, 'NUMBEROFARTICLES' ],
	'numberoffiles'           => [ 1, 'NUMBEROFFILES' ],
	'numberofusers'           => [ 1, 'NUMBEROFUSERS' ],
	'numberofactiveusers'     => [ 1, 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'           => [ 1, 'NUMBEROFEDITS' ],
	'pagename'                => [ 1, 'PAGENAME' ],
	'pagenamee'               => [ 1, 'PAGENAMEE' ],
	'namespace'               => [ 1, 'NAMESPACE' ],
	'namespacee'              => [ 1, 'NAMESPACEE' ],
	'namespacenumber'         => [ 1, 'NAMESPACENUMBER' ],
	'talkspace'               => [ 1, 'TALKSPACE' ],
	'talkspacee'              => [ 1, 'TALKSPACEE' ],
	'subjectspace'            => [ 1, 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'           => [ 1, 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'            => [ 1, 'FULLPAGENAME' ],
	'fullpagenamee'           => [ 1, 'FULLPAGENAMEE' ],
	'subpagename'             => [ 1, 'SUBPAGENAME' ],
	'subpagenamee'            => [ 1, 'SUBPAGENAMEE' ],
	'rootpagename'            => [ 1, 'ROOTPAGENAME' ],
	'rootpagenamee'           => [ 1, 'ROOTPAGENAMEE' ],
	'basepagename'            => [ 1, 'BASEPAGENAME' ],
	'basepagenamee'           => [ 1, 'BASEPAGENAMEE' ],
	'talkpagename'            => [ 1, 'TALKPAGENAME' ],
	'talkpagenamee'           => [ 1, 'TALKPAGENAMEE' ],
	'subjectpagename'         => [ 1, 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'        => [ 1, 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                     => [ 0, 'MSG:' ],
	'subst'                   => [ 0, 'SUBST:' ],
	'safesubst'               => [ 0, 'SAFESUBST:' ],
	'msgnw'                   => [ 0, 'MSGNW:' ],
	'img_thumbnail'           => [ 1, 'thumb', 'thumbnail' ],
	'img_manualthumb'         => [ 1, 'thumbnail=$1', 'thumb=$1' ],
	'img_right'               => [ 1, 'right' ],
	'img_left'                => [ 1, 'left' ],
	'img_none'                => [ 1, 'none' ],
	'img_width'               => [ 1, '$1px' ],
	'img_center'              => [ 1, 'center', 'centre' ],
	'img_framed'              => [ 1, 'frame', 'framed', 'enframed' ],
	'img_frameless'           => [ 1, 'frameless' ],
	'img_lang'                => [ 1, 'lang=$1' ],
	'img_page'                => [ 1, 'page=$1', 'page $1' ],
	'img_upright'             => [ 1, 'upright', 'upright=$1', 'upright $1' ],
	'img_border'              => [ 1, 'border' ],
	'img_baseline'            => [ 1, 'baseline' ],
	'img_sub'                 => [ 1, 'sub' ],
	'img_super'               => [ 1, 'super', 'sup' ],
	'img_top'                 => [ 1, 'top' ],
	'img_text_top'            => [ 1, 'text-top' ],
	'img_middle'              => [ 1, 'middle' ],
	'img_bottom'              => [ 1, 'bottom' ],
	'img_text_bottom'         => [ 1, 'text-bottom' ],
	'img_link'                => [ 1, 'link=$1' ],
	'img_alt'                 => [ 1, 'alt=$1' ],
	'img_class'               => [ 1, 'class=$1' ],
	'int'                     => [ 0, 'INT:' ],
	'sitename'                => [ 1, 'SITENAME' ],
	'ns'                      => [ 0, 'NS:' ],
	'nse'                     => [ 0, 'NSE:' ],
	'localurl'                => [ 0, 'LOCALURL:' ],
	'localurle'               => [ 0, 'LOCALURLE:' ],
	'articlepath'             => [ 0, 'ARTICLEPATH' ],
	'pageid'                  => [ 0, 'PAGEID' ],
	'server'                  => [ 0, 'SERVER' ],
	'servername'              => [ 0, 'SERVERNAME' ],
	'scriptpath'              => [ 0, 'SCRIPTPATH' ],
	'stylepath'               => [ 0, 'STYLEPATH' ],
	'grammar'                 => [ 0, 'GRAMMAR:' ],
	'gender'                  => [ 0, 'GENDER:' ],
	'bidi'                    => [ 0, 'BIDI:' ],
	'notitleconvert'          => [ 0, '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'        => [ 0, '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'             => [ 1, 'CURRENTWEEK' ],
	'currentdow'              => [ 1, 'CURRENTDOW' ],
	'localweek'               => [ 1, 'LOCALWEEK' ],
	'localdow'                => [ 1, 'LOCALDOW' ],
	'revisionid'              => [ 1, 'REVISIONID' ],
	'revisionday'             => [ 1, 'REVISIONDAY' ],
	'revisionday2'            => [ 1, 'REVISIONDAY2' ],
	'revisionmonth'           => [ 1, 'REVISIONMONTH' ],
	'revisionmonth1'          => [ 1, 'REVISIONMONTH1' ],
	'revisionyear'            => [ 1, 'REVISIONYEAR' ],
	'revisiontimestamp'       => [ 1, 'REVISIONTIMESTAMP' ],
	'revisionuser'            => [ 1, 'REVISIONUSER' ],
	'revisionsize'            => [ 1, 'REVISIONSIZE' ],
	'plural'                  => [ 0, 'PLURAL:' ],
	'fullurl'                 => [ 0, 'FULLURL:' ],
	'fullurle'                => [ 0, 'FULLURLE:' ],
	'canonicalurl'            => [ 0, 'CANONICALURL:' ],
	'canonicalurle'           => [ 0, 'CANONICALURLE:' ],
	'lcfirst'                 => [ 0, 'LCFIRST:' ],
	'ucfirst'                 => [ 0, 'UCFIRST:' ],
	'lc'                      => [ 0, 'LC:' ],
	'uc'                      => [ 0, 'UC:' ],
	'raw'                     => [ 0, 'RAW:' ],
	'displaytitle'            => [ 1, 'DISPLAYTITLE' ],
	'rawsuffix'               => [ 1, 'R' ],
	'nocommafysuffix'         => [ 0, 'NOSEP' ],
	'newsectionlink'          => [ 1, '__NEWSECTIONLINK__' ],
	'nonewsectionlink'        => [ 1, '__NONEWSECTIONLINK__' ],
	'currentversion'          => [ 1, 'CURRENTVERSION' ],
	'urlencode'               => [ 0, 'URLENCODE:' ],
	'anchorencode'            => [ 0, 'ANCHORENCODE' ],
	'currenttimestamp'        => [ 1, 'CURRENTTIMESTAMP' ],
	'localtimestamp'          => [ 1, 'LOCALTIMESTAMP' ],
	'directionmark'           => [ 1, 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                => [ 0, '#LANGUAGE:' ],
	'contentlanguage'         => [ 1, 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagelanguage'            => [ 1, 'PAGELANGUAGE' ],
	'pagesinnamespace'        => [ 1, 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'          => [ 1, 'NUMBEROFADMINS' ],
	'formatnum'               => [ 0, 'FORMATNUM' ],
	'padleft'                 => [ 0, 'PADLEFT' ],
	'padright'                => [ 0, 'PADRIGHT' ],
	'special'                 => [ 0, 'special' ],
	'speciale'                => [ 0, 'speciale' ],
	'defaultsort'             => [ 1, 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                => [ 0, 'FILEPATH:' ],
	'tag'                     => [ 0, 'tag' ],
	'hiddencat'               => [ 1, '__HIDDENCAT__' ],
	'expectunusedcategory'    => [ 1, '__EXPECTUNUSEDCATEGORY__', ],
	'pagesincategory'         => [ 1, 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                => [ 1, 'PAGESIZE' ],
	'index'                   => [ 1, '__INDEX__' ],
	'noindex'                 => [ 1, '__NOINDEX__' ],
	'numberingroup'           => [ 1, 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'          => [ 1, '__STATICREDIRECT__' ],
	'protectionlevel'         => [ 1, 'PROTECTIONLEVEL' ],
	'protectionexpiry'        => [ 1, 'PROTECTIONEXPIRY' ],
	'cascadingsources'        => [ 1, 'CASCADINGSOURCES' ],
	'formatdate'              => [ 0, 'formatdate', 'dateformat' ],
	'url_path'                => [ 0, 'PATH' ],
	'url_wiki'                => [ 0, 'WIKI' ],
	'url_query'               => [ 0, 'QUERY' ],
	'defaultsort_noerror'     => [ 0, 'noerror' ],
	'defaultsort_noreplace'   => [ 0, 'noreplace' ],
	'displaytitle_noerror'    => [ 0, 'noerror' ],
	'displaytitle_noreplace'  => [ 0, 'noreplace' ],
	'pagesincategory_all'     => [ 0, 'all' ],
	'pagesincategory_pages'   => [ 0, 'pages' ],
	'pagesincategory_subcats' => [ 0, 'subcats' ],
	'pagesincategory_files'   => [ 0, 'files' ],
];

/**
 * Alternate names of special pages. All names are case-insensitive. The first
 * listed alias will be used as the default. Aliases from the fallback
 * localisation (usually English) will be included by default.
 */
$specialPageAliases = [
	'Activeusers'               => [ 'ActiveUsers' ],
	'Allmessages'               => [ 'AllMessages' ],
	'AllMyUploads'              => [ 'AllMyUploads', 'AllMyFiles' ],
	'Allpages'                  => [ 'AllPages' ],
	'ApiHelp'                   => [ 'ApiHelp' ],
	'ApiSandbox'                => [ 'ApiSandbox' ],
	'Ancientpages'              => [ 'AncientPages' ],
	'AutoblockList'             => [ 'AutoblockList', 'ListAutoblocks' ],
	'Badtitle'                  => [ 'Badtitle' ],
	'Blankpage'                 => [ 'BlankPage' ],
	'Block'                     => [ 'Block', 'BlockIP', 'BlockUser' ],
	'BlockList'                 => [ 'BlockList', 'ListBlocks', 'IPBlockList' ],
	'Booksources'               => [ 'BookSources' ],
	'BotPasswords'              => [ 'BotPasswords' ],
	'BrokenRedirects'           => [ 'BrokenRedirects' ],
	'Categories'                => [ 'Categories' ],
	'ChangeContentModel'        => [ 'ChangeContentModel' ],
	'ChangeCredentials'         => [ 'ChangeCredentials' ],
	'ChangeEmail'               => [ 'ChangeEmail' ],
	'ChangePassword'            => [ 'ChangePassword', 'ResetPass', 'ResetPassword' ],
	'ComparePages'              => [ 'ComparePages' ],
	'Confirmemail'              => [ 'ConfirmEmail' ],
	'Contributions'             => [ 'Contributions', 'Contribs' ],
	'CreateAccount'             => [ 'CreateAccount' ],
	'Deadendpages'              => [ 'DeadendPages' ],
	'DeletedContributions'      => [ 'DeletedContributions' ],
	'Diff'                      => [ 'Diff' ],
	'DoubleRedirects'           => [ 'DoubleRedirects' ],
	'EditPage'                  => [ 'EditPage', 'Edit' ],
	'EditTags'                  => [ 'EditTags' ],
	'EditWatchlist'             => [ 'EditWatchlist' ],
	'Emailuser'                 => [ 'EmailUser', 'Email' ],
	'ExpandTemplates'           => [ 'ExpandTemplates' ],
	'Export'                    => [ 'Export' ],
	'Fewestrevisions'           => [ 'FewestRevisions' ],
	'FileDuplicateSearch'       => [ 'FileDuplicateSearch' ],
	'Filepath'                  => [ 'FilePath' ],
	'GoToInterwiki'             => [ 'GoToInterwiki' ],
	'Import'                    => [ 'Import' ],
	'Invalidateemail'           => [ 'InvalidateEmail' ],
	'JavaScriptTest'            => [ 'JavaScriptTest' ],
	'LinkSearch'                => [ 'LinkSearch' ],
	'LinkAccounts'              => [ 'LinkAccounts' ],
	'Listadmins'                => [ 'ListAdmins' ],
	'Listbots'                  => [ 'ListBots' ],
	'Listfiles'                 => [ 'ListFiles', 'FileList', 'ImageList' ],
	'Listgrouprights'           => [ 'ListGroupRights', 'UserGroupRights' ],
	'Listgrants'                => [ 'ListGrants' ],
	'Listredirects'             => [ 'ListRedirects' ],
	'ListDuplicatedFiles'       => [ 'ListDuplicatedFiles', 'ListFileDuplicates' ],
	'Listusers'                 => [ 'ListUsers', 'UserList', 'Users' ],
	'Lockdb'                    => [ 'LockDB' ],
	'Log'                       => [ 'Log', 'Logs' ],
	'Lonelypages'               => [ 'LonelyPages', 'OrphanedPages' ],
	'Longpages'                 => [ 'LongPages' ],
	'MediaStatistics'           => [ 'MediaStatistics' ],
	'MergeHistory'              => [ 'MergeHistory' ],
	'MIMEsearch'                => [ 'MIMESearch' ],
	'Mostcategories'            => [ 'MostCategories' ],
	'Mostimages'                => [ 'MostLinkedFiles', 'MostFiles', 'MostImages' ],
	'Mostinterwikis'            => [ 'MostInterwikis' ],
	'Mostlinked'                => [ 'MostLinkedPages', 'MostLinked' ],
	'Mostlinkedcategories'      => [ 'MostLinkedCategories', 'MostUsedCategories' ],
	'Mostlinkedtemplates'       => [ 'MostTranscludedPages', 'MostLinkedTemplates', 'MostUsedTemplates' ],
	'Mostrevisions'             => [ 'MostRevisions' ],
	'Movepage'                  => [ 'MovePage' ],
	'Mute'                      => [ 'Mute' ],
	'Mycontributions'           => [ 'MyContributions' ],
	'MyLanguage'                => [ 'MyLanguage' ],
	'Mypage'                    => [ 'MyPage' ],
	'Mytalk'                    => [ 'MyTalk' ],
	'Myuploads'                 => [ 'MyUploads', 'MyFiles' ],
	'Newimages'                 => [ 'NewFiles', 'NewImages' ],
	'NewSection'                => [ 'NewSection', 'Newsection' ],
	'Newpages'                  => [ 'NewPages' ],
	'PagesWithProp'             => [ 'PagesWithProp', 'Pageswithprop', 'PagesByProp', 'Pagesbyprop' ],
	'PageData'                  => [ 'PageData' ],
	'PageHistory'               => [ 'PageHistory', 'History' ],
	'PageInfo'                  => [ 'PageInfo', 'Info' ],
	'PageLanguage'              => [ 'PageLanguage' ],
	'PasswordPolicies'          => [ 'PasswordPolicies' ],
	'PasswordReset'             => [ 'PasswordReset' ],
	'PermanentLink'             => [ 'PermanentLink', 'PermaLink' ],
	'Preferences'               => [ 'Preferences' ],
	'Prefixindex'               => [ 'PrefixIndex' ],
	'Protectedpages'            => [ 'ProtectedPages' ],
	'Protectedtitles'           => [ 'ProtectedTitles' ],
	'Purge'                     => [ 'Purge' ],
	'Randompage'                => [ 'Random', 'RandomPage' ],
	'RandomInCategory'          => [ 'RandomInCategory' ],
	'Randomredirect'            => [ 'RandomRedirect' ],
	'Randomrootpage'            => [ 'RandomRootpage' ],
	'Recentchanges'             => [ 'RecentChanges' ],
	'Recentchangeslinked'       => [ 'RecentChangesLinked', 'RelatedChanges' ],
	'Redirect'                  => [ 'Redirect' ],
	'RemoveCredentials'         => [ 'RemoveCredentials' ],
	'ResetTokens'               => [ 'ResetTokens' ],
	'Revisiondelete'            => [ 'RevisionDelete' ],
	'RunJobs'                   => [ 'RunJobs' ],
	'Search'                    => [ 'Search' ],
	'Shortpages'                => [ 'ShortPages' ],
	'Specialpages'              => [ 'SpecialPages' ],
	'Statistics'                => [ 'Statistics', 'Stats' ],
	'Tags'                      => [ 'Tags' ],
	'TrackingCategories'        => [ 'TrackingCategories' ],
	'Unblock'                   => [ 'Unblock' ],
	'Uncategorizedcategories'   => [ 'UncategorizedCategories' ],
	'Uncategorizedimages'       => [ 'UncategorizedFiles', 'UncategorizedImages' ],
	'Uncategorizedpages'        => [ 'UncategorizedPages' ],
	'Uncategorizedtemplates'    => [ 'UncategorizedTemplates' ],
	'Undelete'                  => [ 'Undelete' ],
	'UnlinkAccounts'            => [ 'UnlinkAccounts' ],
	'Unlockdb'                  => [ 'UnlockDB' ],
	'Unusedcategories'          => [ 'UnusedCategories' ],
	'Unusedimages'              => [ 'UnusedFiles', 'UnusedImages' ],
	'Unusedtemplates'           => [ 'UnusedTemplates' ],
	'Unwatchedpages'            => [ 'UnwatchedPages' ],
	'Upload'                    => [ 'Upload' ],
	'UploadStash'               => [ 'UploadStash' ],
	'Userlogin'                 => [ 'UserLogin', 'Login' ],
	'Userlogout'                => [ 'UserLogout', 'Logout' ],
	'Userrights'                => [ 'UserRights', 'MakeSysop', 'MakeBot' ],
	'Version'                   => [ 'Version' ],
	'Wantedcategories'          => [ 'WantedCategories' ],
	'Wantedfiles'               => [ 'WantedFiles' ],
	'Wantedpages'               => [ 'WantedPages', 'BrokenLinks' ],
	'Wantedtemplates'           => [ 'WantedTemplates' ],
	'Watchlist'                 => [ 'Watchlist' ],
	'Whatlinkshere'             => [ 'WhatLinksHere' ],
	'Withoutinterwiki'          => [ 'WithoutInterwiki' ],
];

/**
 * Regular expression matching the "link trail", e.g. "ed" in [[Toast]]ed, as
 * the first group, and the remainder of the string as the second group.
 */
$linkTrail = '/^([a-z]+)(.*)$/sD';

/**
 * Regular expression charset matching the "link prefix", e.g. "foo" in
 * foo[[bar]]. UTF-8 characters may be used.
 */
$linkPrefixCharset = 'a-zA-Z\\x{80}-\\x{10ffff}';

/**
 * A list of messages to preload for each request.
 * Here we add messages that are needed for a typical anonymous parser cache hit.
 */
$preloadedMessages = [
	'aboutpage',
	'aboutsite',
	'accesskey-ca-edit',
	'accesskey-ca-history',
	'accesskey-ca-nstab-main',
	'accesskey-ca-talk',
	'accesskey-ca-viewsource',
	'accesskey-n-currentevents',
	'accesskey-n-help',
	'accesskey-n-mainpage-description',
	'accesskey-n-portal',
	'accesskey-n-randompage',
	'accesskey-n-recentchanges',
	'accesskey-p-logo',
	'accesskey-pt-login',
	'accesskey-pt-createaccount',
	'accesskey-search',
	'accesskey-search-fulltext',
	'accesskey-search-go',
	'accesskey-t-info',
	'accesskey-t-permalink',
	'accesskey-t-print',
	'accesskey-t-recentchangeslinked',
	'accesskey-t-specialpages',
	'accesskey-t-whatlinkshere',
	'actions',
	'anonnotice',
	'brackets',
	'comma-separator',
	'currentevents',
	'currentevents-url',
	'disclaimerpage',
	'disclaimers',
	'edit',
	'editsection',
	'editsectionhint',
	'help',
	'helppage',
	'interlanguage-link-title',
	'jumpto',
	'jumptonavigation',
	'jumptosearch',
	'lastmodifiedat',
	'mainpage',
	'mainpage-description',
	'mainpage-nstab',
	'namespaces',
	'navigation',
	'nav-login-createaccount',
	'nstab-main',
	'opensearch-desc',
	'pagecategories',
	'pagecategorieslink',
	'pagetitle',
	'pagetitle-view-mainpage',
	'permalink',
	'personaltools',
	'portal',
	'portal-url',
	'printableversion',
	'privacy',
	'privacypage',
	'randompage',
	'randompage-url',
	'recentchanges',
	'recentchangeslinked-toolbox',
	'recentchanges-url',
	'retrievedfrom',
	'search',
	'searcharticle',
	'searchbutton',
	'searchsuggest-search',
	'sidebar',
	'navigation-heading',
	'site-atom-feed',
	'sitenotice',
	'specialpages',
	'tagline',
	'talk',
	'toolbox',
	'tooltip-ca-edit',
	'tooltip-ca-history',
	'tooltip-ca-nstab-main',
	'tooltip-ca-talk',
	'tooltip-ca-viewsource',
	'tooltip-n-currentevents',
	'tooltip-n-help',
	'tooltip-n-mainpage-description',
	'tooltip-n-portal',
	'tooltip-n-randompage',
	'tooltip-n-recentchanges',
	'tooltip-p-logo',
	'tooltip-pt-login',
	'tooltip-pt-createaccount',
	'tooltip-search',
	'tooltip-search-fulltext',
	'tooltip-search-go',
	'tooltip-t-info',
	'tooltip-t-permalink',
	'tooltip-t-print',
	'tooltip-t-recentchangeslinked',
	'tooltip-t-specialpages',
	'tooltip-t-whatlinkshere',
	'variants',
	'vector-view-edit',
	'vector-view-history',
	'vector-view-view',
	'viewcount',
	'views',
	'whatlinkshere',
	'word-separator',
];
