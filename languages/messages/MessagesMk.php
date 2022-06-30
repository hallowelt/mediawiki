<?php
/** Macedonian (македонски)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 *
 * @author Bjankuloski06
 * @author Brainmachine
 * @author Brest
 * @author Brest2008
 * @author FlavrSavr
 * @author Glupav
 * @author INkubusse
 * @author Kaganer
 * @author Misos
 * @author Rancher
 * @author Spacebirdy
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Медиум',
	NS_SPECIAL          => 'Специјална',
	NS_TALK             => 'Разговор',
	NS_USER             => 'Корисник',
	NS_USER_TALK        => 'Разговор_со_корисник',
	NS_PROJECT_TALK     => 'Разговор_за_$1',
	NS_FILE             => 'Податотека',
	NS_FILE_TALK        => 'Разговор_за_податотека',
	NS_MEDIAWIKI        => 'МедијаВики',
	NS_MEDIAWIKI_TALK   => 'Разговор_за_МедијаВики',
	NS_TEMPLATE         => 'Предлошка',
	NS_TEMPLATE_TALK    => 'Разговор_за_предлошка',
	NS_HELP             => 'Помош',
	NS_HELP_TALK        => 'Разговор_за_помош',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_за_категорија',
];

$namespaceAliases = [
	'Медија'             => NS_MEDIA,
	'Специјални'         => NS_SPECIAL,
	'Слика'              => NS_FILE,
	'Разговор_за_слика'  => NS_FILE_TALK,
	'Шаблон'             => NS_TEMPLATE,
	'Разговор_за_шаблон' => NS_TEMPLATE_TALK,
];

$datePreferences = [
	'default',
	'dmy mk',
	'ymd mk',
	'ymdt mk',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

$defaultDateFormat = 'dmy or mdy';

$dateFormats = [
	'dmy mk time' => 'H:i',
	'dmy mk date' => 'j.m.Y',
	'dmy mk both' => 'H:i, j.m.Y',

	'ymd mk time' => 'H:i',
	'ymd mk date' => 'Y.m.j',
	'ymd mk both' => 'H:i, Y.m.j',

	'ymdt mk time' => 'H:i:s',
	'ymdt mk date' => 'Y.m.j',
	'ymdt mk both' => 'Y.m.j, H:i:s',

	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'H:i, F j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'H:i, j F Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'АктивниКорисници' ],
	'Allmessages'               => [ 'СитеПораки' ],
	'AllMyUploads'              => [ 'СитеМоиПодигања' ],
	'Allpages'                  => [ 'СитеСтраници' ],
	'Ancientpages'              => [ 'НајстариСтраници' ],
	'ApiHelp'                   => [ 'ИзвршникПомош' ],
	'Badtitle'                  => [ 'Лошнаслов' ],
	'Blankpage'                 => [ 'ПразнаСтраница' ],
	'Block'                     => [ 'Блокирање', 'БлокIP', 'БлокирајКорисник' ],
	'BlockList'                 => [ 'СписокНаБлокираниIP' ],
	'Booksources'               => [ 'ПечатенИзвор' ],
	'BrokenRedirects'           => [ 'ПрекинатиПренасочувања' ],
	'Categories'                => [ 'Категории' ],
	'ChangeEmail'               => [ 'СмениЕ-пошта' ],
	'ChangePassword'            => [ 'СмениЛозинка' ],
	'ComparePages'              => [ 'СпоредиСтраници' ],
	'Confirmemail'              => [ 'Потврди_е-пошта' ],
	'Contributions'             => [ 'Придонеси' ],
	'CreateAccount'             => [ 'СоздајКорисничкаСметка' ],
	'Deadendpages'              => [ 'СлепиСтраници' ],
	'DeletedContributions'      => [ 'ИзбришаниПридонеси' ],
	'Diff'                      => [ 'Разлики' ],
	'DoubleRedirects'           => [ 'ДвојниПренасочувања' ],
	'EditWatchlist'             => [ 'УредиНабљудувања' ],
	'Emailuser'                 => [ 'Пиши_е-пошта_на_корисникот' ],
	'ExpandTemplates'           => [ 'ПрошириПредлошки', 'ПрошириШаблони' ],
	'Export'                    => [ 'Извоз' ],
	'Fewestrevisions'           => [ 'НајмалкуПреработки' ],
	'FileDuplicateSearch'       => [ 'ПребарувањеДупликатПодатотека' ],
	'Filepath'                  => [ 'ПатДоПодатотека' ],
	'Import'                    => [ 'Увоз' ],
	'Invalidateemail'           => [ 'ПогрешнаЕпошта' ],
	'JavaScriptTest'            => [ 'ПробаНаJavaСкрипта' ],
	'LinkSearch'                => [ 'ПребарајВрска' ],
	'Listadmins'                => [ 'СписокНаАдминистратори' ],
	'Listbots'                  => [ 'СписокНаБотови' ],
	'ListDuplicatedFiles'       => [ 'ИспишиДуплираниПодатотеки' ],
	'Listfiles'                 => [ 'СписокНаПодатотеки', 'СписокНаСлики' ],
	'Listgrouprights'           => [ 'СписокНаГрупниПрава' ],
	'Listredirects'             => [ 'СписокНаПренасочувања' ],
	'Listusers'                 => [ 'СписокНаКорисници', 'СписокКорисници' ],
	'Lockdb'                    => [ 'ЗаклучиБаза' ],
	'Log'                       => [ 'Дневник', 'Дневници' ],
	'Lonelypages'               => [ 'ОсамениСтраници', 'СтранциСирачиња' ],
	'Longpages'                 => [ 'ДолгиСтраници' ],
	'MediaStatistics'           => [ 'МедиумскиСтатистики' ],
	'MergeHistory'              => [ 'СпојувањеИсторија' ],
	'MIMEsearch'                => [ 'MIMEПребарување' ],
	'Mostcategories'            => [ 'НајмногуКатегории' ],
	'Mostimages'                => [ 'НајмногуСлики', 'НајмногуПодатотеки', 'ПодатотекиСоНајмногуВрски' ],
	'Mostinterwikis'            => [ 'НајмногуМеѓувикија' ],
	'Mostlinked'                => [ 'СоНајмногуВрски', 'СтранициСоНајмногуВрски' ],
	'Mostlinkedcategories'      => [ 'НајупотребуваниКатегории' ],
	'Mostlinkedtemplates'       => [ 'НајупотребуваниПредлошки', 'НајупотребуваниШаблони' ],
	'Mostrevisions'             => [ 'НајмногуПреработки' ],
	'Movepage'                  => [ 'ПреместиСтраница' ],
	'Mycontributions'           => [ 'МоиПридонеси' ],
	'MyLanguage'                => [ 'МојЈазик' ],
	'Mypage'                    => [ 'МојаСтраница' ],
	'Mytalk'                    => [ 'МојРазговор', 'МоиРазговори' ],
	'Myuploads'                 => [ 'МоиПодигања' ],
	'Newimages'                 => [ 'НовиСлики', 'НовиПодатотеки' ],
	'Newpages'                  => [ 'НовиСтраници' ],
	'PageLanguage'              => [ 'ЈазикНаСтраницата' ],
	'PagesWithProp'             => [ 'СтранициСоСвојство' ],
	'PasswordReset'             => [ 'ПроменаНаЛозинка' ],
	'PermanentLink'             => [ 'ПостојанаВрска' ],
	'Preferences'               => [ 'Нагодувања' ],
	'Prefixindex'               => [ 'ИндексНаПретставки' ],
	'Protectedpages'            => [ 'ЗаштитениСтраници' ],
	'Protectedtitles'           => [ 'ЗаштитениНаслови' ],
	'RandomInCategory'          => [ 'СлучајнаВоКатегорија' ],
	'Randompage'                => [ 'Случајна', 'СлучајнаСтраница' ],
	'Randomredirect'            => [ 'СлучајноПренасочување' ],
	'Randomrootpage'            => [ 'СлучајнаОсновнаСтраница' ],
	'Recentchanges'             => [ 'СкорешниПромени' ],
	'Recentchangeslinked'       => [ 'ПоврзаниПромени' ],
	'Redirect'                  => [ 'Пренасочување' ],
	'ResetTokens'               => [ 'ВратиОдновоЗнаци' ],
	'Revisiondelete'            => [ 'БришењеПреработка' ],
	'RunJobs'                   => [ 'ПуштиЗадачи' ],
	'Search'                    => [ 'Барај' ],
	'Shortpages'                => [ 'КраткиСтраници' ],
	'Specialpages'              => [ 'СлужбениСтраници' ],
	'Statistics'                => [ 'Статистики' ],
	'Tags'                      => [ 'Oзнаки', 'Приврзоци' ],
	'TrackingCategories'        => [ 'КатегорииЗаСледење' ],
	'Unblock'                   => [ 'Одблокирај' ],
	'Uncategorizedcategories'   => [ 'НекатегоризираниКатегории' ],
	'Uncategorizedimages'       => [ 'НекатегоризираниСлики' ],
	'Uncategorizedpages'        => [ 'НекатегоризираниСтраници' ],
	'Uncategorizedtemplates'    => [ 'НекатегоризираниПредлошки', 'НекатегоризираниШаблони' ],
	'Undelete'                  => [ 'Врати' ],
	'Unlockdb'                  => [ 'ОтклучиБаза' ],
	'Unusedcategories'          => [ 'НеискористениКатегории' ],
	'Unusedimages'              => [ 'НеискористениСлики', 'НеискористениПодатотеки' ],
	'Unusedtemplates'           => [ 'НеискористениПредлошки', 'НеискористениШаблони' ],
	'Unwatchedpages'            => [ 'НенабљудуваниСтраници' ],
	'Upload'                    => [ 'Подигање' ],
	'UploadStash'               => [ 'СкриениПодигања' ],
	'Userlogin'                 => [ 'Најавување' ],
	'Userlogout'                => [ 'Одјавување' ],
	'Userrights'                => [ 'КорисничкиПрава' ],
	'Version'                   => [ 'Верзија' ],
	'Wantedcategories'          => [ 'ПотребниКатегории' ],
	'Wantedfiles'               => [ 'ПотребниПодатотеки' ],
	'Wantedpages'               => [ 'ПотребниСтраници' ],
	'Wantedtemplates'           => [ 'ПотребниПредлошки', 'ПотребниШаблони' ],
	'Watchlist'                 => [ 'СписокНаНабљудувања' ],
	'Whatlinkshere'             => [ 'ШтоВодиОвде' ],
	'Withoutinterwiki'          => [ 'БезМеѓувики' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'ШИФРИРАЈКОТВА', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'ПАТЕКАНАСТАТИЈА', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'ОСНОВНАСТРАНИЦА', 'BASEPAGENAME' ],
	'canonicalurl'              => [ '0', 'КАНОНСКАURL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'КАНОНСКАURLE:', 'CANONICALURLE:' ],
	'cascadingsources'          => [ '1', 'КАСКАДНИИЗВОРИ', 'CASCADINGSOURCES' ],
	'contentlanguage'           => [ '1', 'ЈАЗИКНАСОДРЖИНАТА', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ТЕКОВЕНДЕН', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ТЕКОВЕНДЕН2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ТЕКОВЕНДЕНИМЕ', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ТЕКОВЕНДЕНВОСЕДМИЦАТА', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ТЕКОВЕНЧАС', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'ТЕКОВЕНМЕСЕЦ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ТЕКОВЕНМЕСЕЦ1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'ТЕКОВЕНМЕСЕЦСКР', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'ТЕКОВЕНМЕСЕЦИМЕ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ТЕКОВЕНМЕСЕЦИМЕРОД', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'ТЕКОВНОВРЕМЕ', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'ОЗНАЧЕНОТЕКОВНОВРЕМЕ', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'ТЕКОВНАВЕРЗИЈА', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'ТЕКОВНАСЕДМИЦА', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'ТЕКОВНАГОДИНА', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'ОСНОВНОПОДРЕДУВАЊЕ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'       => [ '0', 'безгрешки', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', 'беззамена', 'noreplace' ],
	'directionmark'             => [ '1', 'ОЗНАКАЗАНАСОКА', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'ПРИКАЖИНАСЛОВ', 'DISPLAYTITLE' ],
	'displaytitle_noerror'      => [ '0', 'безгрешка', 'noerror' ],
	'displaytitle_noreplace'    => [ '0', 'незаменувај', 'noreplace' ],
	'filepath'                  => [ '0', 'ПОДАТОТЕЧНАПАТЕКА:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__СОСОДРЖИНА__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'форматдатум', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'ФОРМАТБРОЈ', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'ЦЕЛОСНОИМЕНАСТРАНИЦА', 'FULLPAGENAME' ],
	'fullurl'                   => [ '0', 'ПОЛНАURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ПОЛНАURLE:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'ПОЛ:', 'GENDER:' ],
	'grammar'                   => [ '0', 'ГРАМАТИКА:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__СКРИЕНАКАТ__', '__СКРИЕНАКАТЕГОРИЈА__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'алт=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', 'основналинија', 'baseline' ],
	'img_border'                => [ '1', 'граничник', 'граница', 'border' ],
	'img_bottom'                => [ '1', 'дно', 'најдолу', 'bottom' ],
	'img_center'                => [ '1', 'центар', 'ц', 'center', 'centre' ],
	'img_class'                 => [ '1', 'класа=$1', 'class=$1' ],
	'img_framed'                => [ '1', 'рамка', 'ворамка', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'безрамка', 'frameless' ],
	'img_lang'                  => [ '1', 'јаз=$1', 'lang=$1' ],
	'img_left'                  => [ '1', 'лево', 'л', 'left' ],
	'img_link'                  => [ '1', 'врска=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'мини-слика=$1', 'мини=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'средина', 'middle' ],
	'img_none'                  => [ '1', 'н', 'нема', 'none' ],
	'img_page'                  => [ '1', 'страница=$1', 'страница_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'десно', 'д', 'right' ],
	'img_sub'                   => [ '1', 'долениндекс', 'дол', 'sub' ],
	'img_super'                 => [ '1', 'горениндекс', 'гор', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'текст-дно', 'текст-најдолу', 'text-bottom' ],
	'img_text_top'              => [ '1', 'текст-врв', 'текст-најгоре', 'text-top' ],
	'img_thumbnail'             => [ '1', 'мини', 'мини-слика', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'врв', 'најгоре', 'top' ],
	'img_upright'               => [ '1', 'исправено', 'исправено=$1', 'исправено_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1пкс', '$1п', '$1px' ],
	'index'                     => [ '1', '__ИНДЕКС__', '__INDEX__' ],
	'language'                  => [ '0', '#ЈАЗИК:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'МБ', 'LC:' ],
	'lcfirst'                   => [ '0', 'ПРВОМБ', 'LCFIRST:' ],
	'localday'                  => [ '1', 'ДЕН_ЛОКАЛНО', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ДЕН2_ЛОКАЛНО', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ИМЕНАДЕН_ЛОКАЛНО', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'ЛОКАЛЕНДЕНВОСЕДМИЦАТА', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'ЧАС_ЛОКАЛНО', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'МЕСЕЦ_ЛОКАЛНО', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'МЕСЕЦ_ЛОКАЛНО1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'МЕСЕЦИМЕ_ЛОКАЛНО_КРАТЕНКА', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'МЕСЕЦИМЕ_ЛОКАЛНО', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'МЕСЕЦИМЕ_ЛОКАЛНО_ГЕНИТИВ', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'ВРЕМЕ_ЛОКАЛНО', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'ОЗНАЧЕНОЛОКАЛНОВРЕМЕ', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'ЛОКАЛНААДРЕСА:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ЛОКАЛНААДРЕСАИ:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'СЕДМИЦА_ЛОКАЛНО', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'ГОДИНА_ЛОКАЛНО', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'ПОР:', 'MSG:' ],
	'msgnw'                     => [ '0', 'ИЗВЕШТNW:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'ИМЕПРОСТОР', 'ИМЕНСКИПРОСТОР', 'NAMESPACE' ],
	'newsectionlink'            => [ '1', '__ВРСКАНОВПОДНАСЛОВ__', '__NEWSECTIONLINK__' ],
	'nocommafysuffix'           => [ '0', 'БЕЗПОДЕЛ', 'NOSEP' ],
	'nocontentconvert'          => [ '0', '__БЕЗПРЕТВОРАЊЕСОДРЖИНА__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__БЕЗ_УРЕДУВАЊЕ_НА_ПОДНАСЛОВИ__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__БЕЗГАЛЕРИЈА__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__БЕЗИНДЕКС__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__БЕЗВРСКАНОВПОДНАСЛОВ__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__БЕЗПРЕТВОРАЊЕНАСЛОВ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__БЕЗСОДРЖИНА__', '__NOTOC__' ],
	'numberingroup'             => [ '1', 'БРОЈВОГРУПА', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'БРОЈНААКТИВНИКОРИСНИЦИ', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'БРОЈНААДМИНИСТРАТОРИ', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'БРОЈСТАТИИ', 'БРОЈНАСТАТИИ', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'БРОЈНАУРЕДУВАЊА', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'БРОЈНАПОДАТОТЕКИ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'БРОЈНАСТРАНИЦИ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'БРОЈНАКОРИСНИЦИ', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'ПОСТАВИЛЕВО', 'PADLEFT' ],
	'padright'                  => [ '0', 'ПОСТАВИДЕСНО', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'НАЗНАКАНАСТРАНИЦА', 'PAGEID' ],
	'pagename'                  => [ '1', 'СТРАНИЦА', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'СТРАНИЦАИ', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'СТРАНИЦИВОКАТЕГОРИЈА', 'СТРАНИЦИВОКАТ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'сите', 'all' ],
	'pagesincategory_files'     => [ '0', 'податотеки', 'files' ],
	'pagesincategory_pages'     => [ '0', 'страници', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'поткатегории', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'СТРАНИЦИВОИМЕНСКИПРОСТОР', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'ГОЛЕМИНА_НА_СТРАНИЦА', 'PAGESIZE' ],
	'plural'                    => [ '0', 'МНОЖИНА:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'НИВОНАЗАШТИТА', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'СИРОВО:', 'RAW:' ],
	'rawsuffix'                 => [ '1', 'П', 'R' ],
	'redirect'                  => [ '0', '#пренасочување', '#види', '#Пренасочување', '#ПРЕНАСОЧУВАЊЕ', '#REDIRECT' ],
	'revisionday'               => [ '1', 'ДЕННАПРЕРАБОТКА', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ДЕННАПРЕРАБОТКА2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'НАЗНАКАНАПРЕРАБОТКА', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'МЕСЕЦНАПРЕРАБОТКА', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'МЕСЕЦНАПРЕРАБОТКА1', 'REVISIONMONTH1' ],
	'revisionsize'              => [ '1', 'ГОЛЕМИНАНАПРЕРАБОТКА', 'REVISIONSIZE' ],
	'revisiontimestamp'         => [ '1', 'ВРЕМЕНАПРЕРАБОТКА', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'КОРИСНИКНАНАПРЕРАБОТКА', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'ГОДИНАНАПРЕРАБОТКА', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'БЕЗБЗАМЕНИ', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'ПАТЕКАНАСКРИПТА', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'ОПСЛУЖУВАЧ', 'SERVER' ],
	'servername'                => [ '0', 'ИМЕНАОПСЛУЖУВАЧ', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'ИМЕНАМРЕЖНОМЕСТО', 'SITENAME' ],
	'special'                   => [ '0', 'службена', 'службени', 'special' ],
	'staticredirect'            => [ '1', '__СТАТИЧНОПРЕНАСОЧУВАЊЕ__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'СТИЛСКАПАТЕКА', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'ИМЕНАСТАТИЈА', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subpagename'               => [ '1', 'ПОТСТРАНИЦА', 'SUBPAGENAME' ],
	'subst'                     => [ '0', 'ЗАМЕНИ:', 'SUBST:' ],
	'tag'                       => [ '0', 'ознака', 'tag' ],
	'talkpagename'              => [ '1', 'СТРАНИЦАЗАРАЗГОВОР', 'TALKPAGENAME' ],
	'talkspace'                 => [ '1', 'РАЗГОВОРПРОСТОР', 'TALKSPACE' ],
	'toc'                       => [ '0', '__СОДРЖИНА__', '__TOC__' ],
	'uc'                        => [ '0', 'ГБ', 'UC:' ],
	'ucfirst'                   => [ '0', 'ПРВОГБ', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'ШИФРИРАЈURL:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'ПАТЕКА', 'PATH' ],
	'url_query'                 => [ '0', 'БАРАЊЕ', 'QUERY' ],
	'url_wiki'                  => [ '0', 'ВИКИ', 'WIKI' ],
];

$linkTrail = '/^([a-zабвгдѓежзѕијклљмнњопрстќуфхцчџш]+)(.*)$/sDu';
$separatorTransformTable = [ ',' => '.', '.' => ',' ];
