<?php
/** Sindhi (سنڌي)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aursani
 */

$fallback8bitEncoding = 'windows-1256';
$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'ذريعات',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'بحث',
	NS_USER             => 'واپرائيندڙ',
	NS_USER_TALK        => 'واپرائيندڙ_بحث',
	NS_PROJECT_TALK     => '$1_بحث',
	NS_FILE             => 'فائل',
	NS_FILE_TALK        => 'فائل_بحث',
	NS_MEDIAWIKI        => 'ذريعات_وڪي',
	NS_MEDIAWIKI_TALK   => 'ذريعات_وڪي_بحث',
	NS_TEMPLATE         => 'سانچو',
	NS_TEMPLATE_TALK    => 'سانچو_بحث',
	NS_HELP             => 'مدد',
	NS_HELP_TALK        => 'مدد_بحث',
	NS_CATEGORY         => 'زمرو',
	NS_CATEGORY_TALK    => 'زمرو_بحث',
];

$namespaceAliases = [
	'يوزر' => NS_USER,
	'يوزر_بحث' => NS_USER_TALK,
	'عڪس' => NS_FILE,
	'عڪس_بحث' => NS_FILE_TALK,
	'سنچو' => NS_TEMPLATE,
	'سنچو_بحث' => NS_TEMPLATE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'سڀ نياپا' ],
	'Allpages'                  => [ 'سڀ صفحا' ],
	'Ancientpages'              => [ 'قديم صفحا' ],
	'Block'                     => [ 'آءِ پي بندش' ],
	'BlockList'                 => [ 'آءِ پي بندش فهرست' ],
	'BrokenRedirects'           => [ 'ٽٽل چورڻا' ],
	'Categories'                => [ 'زمرا' ],
	'Confirmemail'              => [ 'برقٽپال تصديقيو' ],
	'Contributions'             => [ 'ڀاڱيداريون' ],
	'CreateAccount'             => [ 'کاتو کوليو' ],
	'DoubleRedirects'           => [ 'ٻٽا چورڻا' ],
	'Emailuser'                 => [ 'برقٽپال يوزر' ],
	'Export'                    => [ 'برآمد' ],
	'FileDuplicateSearch'       => [ 'ساڳيا فائيل ڳولا' ],
	'Filepath'                  => [ 'فائيل ڏس' ],
	'Import'                    => [ 'درآمد' ],
	'Invalidateemail'           => [ 'ناقابلڪار برقٽپال' ],
	'Listadmins'                => [ 'منتظمين فهرست' ],
	'Listbots'                  => [ 'بوٽس فهرست' ],
	'Listfiles'                 => [ 'عڪس فهرست' ],
	'Listredirects'             => [ 'چورڻا فهرست' ],
	'Listusers'                 => [ 'يوزر فهرست' ],
	'Lockdb'                    => [ 'اعدادخانو بند' ],
	'Log'                       => [ 'لاگس' ],
	'Lonelypages'               => [ 'يتيم صفحا' ],
	'Longpages'                 => [ 'طويل صفحا' ],
	'MergeHistory'              => [ 'سوانح ضماءُ' ],
	'MIMEsearch'                => [ 'مائيم ڳولا' ],
	'Movepage'                  => [ 'صفحو چوريو' ],
	'Mycontributions'           => [ 'منهنجون ڀاڱيداريون' ],
	'Mypage'                    => [ 'منهنجو صفحو' ],
	'Mytalk'                    => [ 'مون سان ڳالهه' ],
	'Newimages'                 => [ 'نوان عڪس' ],
	'Newpages'                  => [ 'نوان صفحا' ],
	'Preferences'               => [ 'ترجيحات' ],
	'Prefixindex'               => [ 'اڳياڙي ڏسڻي' ],
	'Protectedpages'            => [ 'تحفظيل صفحا' ],
	'Protectedtitles'           => [ 'تحفظيل عنوان' ],
	'Randompage'                => [ 'بلا ترتيب' ],
	'Randomredirect'            => [ 'بلا ترتيب چورڻو' ],
	'Recentchanges'             => [ 'تازيون تبديليون' ],
	'Search'                    => [ 'ڳولا' ],
	'Shortpages'                => [ 'مختصر صفحا' ],
	'Specialpages'              => [ 'خاص صفحا' ],
	'Statistics'                => [ 'انگ اکر' ],
	'Uncategorizedcategories'   => [ 'اڻ زمرايل زمرا' ],
	'Uncategorizedimages'       => [ 'اڻ زمرايل عڪس' ],
	'Uncategorizedpages'        => [ 'اڻزمرايل صفحا' ],
	'Uncategorizedtemplates'    => [ 'اڻ زمرايل سانچا' ],
	'Undelete'                  => [ 'اڻ ڊاهيو' ],
	'Unlockdb'                  => [ 'اعدادخانو کول' ],
	'Unusedcategories'          => [ 'اڻ استعماليل زمرا' ],
	'Unusedimages'              => [ 'اڻ استعماليل عڪس' ],
	'Unusedtemplates'           => [ 'اڻ استعماليل سانچا' ],
	'Unwatchedpages'            => [ 'اڻٽيٽيل صفحا' ],
	'Upload'                    => [ 'چاڙهيو' ],
	'Userlogin'                 => [ 'يوزر لاگ اِن' ],
	'Userlogout'                => [ 'يوزر لاگ آئوٽ' ],
	'Userrights'                => [ 'يوزر حق' ],
	'Version'                   => [ 'ورزن' ],
	'Wantedcategories'          => [ 'گھربل زمرا' ],
	'Wantedpages'               => [ 'گھربل صفحا' ],
	'Watchlist'                 => [ 'ٽيٽ فهرست' ],
	'Whatlinkshere'             => [ 'هتان ڳنڍيل صفحا' ],
	'Withoutinterwiki'          => [ 'ري بين الوڪي' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'contentlanguage'           => [ '1', 'موادٻولي', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentdow'                => [ '1', 'اڄوڪوڏينهن', 'CURRENTDOW' ],
	'currenttimestamp'          => [ '1', 'هلندڙوقتمهر', 'CURRENTTIMESTAMP' ],
	'currentweek'               => [ '1', 'هلندڙهفتو', 'CURRENTWEEK' ],
	'directionmark'             => [ '1', 'طرفنشان', 'DIRECTIONMARK', 'DIRMARK' ],
	'filepath'                  => [ '0', 'فائيلڏس', 'FILEPATH:' ],
	'fullpagename'              => [ '1', 'صحفيجوپورونالو', 'FULLPAGENAME' ],
	'fullurl'                   => [ '0', 'مڪمليوآريل', 'FULLURL:' ],
	'grammar'                   => [ '0', 'وياڪرڻ', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__ لڪل زمرو __', '__HIDDENCAT__' ],
	'img_bottom'                => [ '1', 'تَرُ', 'bottom' ],
	'img_center'                => [ '1', 'مرڪز', 'center', 'centre' ],
	'img_left'                  => [ '1', 'کاٻو', 'left' ],
	'img_middle'                => [ '1', 'وچ', 'middle' ],
	'img_none'                  => [ '1', 'ڪجهنه', 'none' ],
	'img_right'                 => [ '1', 'ساڄو', 'right' ],
	'img_top'                   => [ '1', 'سِرُ', 'top' ],
	'img_width'                 => [ '1', '$1 عڪسلون', '$1px' ],
	'language'                  => [ '0', '#ٻولي:', '#LANGUAGE:' ],
	'localday'                  => [ '1', 'مقاميڏينهن', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'مقاميڏينهن2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'مقاميڏينهننالو', 'LOCALDAYNAME' ],
	'localhour'                 => [ '1', 'مقاميڪلاڪ', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'مقاميمهينو', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'مقاميمهينونالو', 'LOCALMONTHNAME' ],
	'localtime'                 => [ '1', 'مقاميوقت', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'مقاميوقتمهر', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'مقامييوآريل', 'LOCALURL:' ],
	'localweek'                 => [ '1', 'مقاميهفتو', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'مقاميسال', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'نياپو:', 'MSG:' ],
	'namespace'                 => [ '1', 'نانئپولار', 'NAMESPACE' ],
	'ns'                        => [ '0', 'نپ', 'NS:' ],
	'numberofadmins'            => [ '1', 'منتظمينجوتعداد', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'مضموننجوتعداد', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'ترميمنجوتعداد', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'فائيلنجوتعداد', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'صفحنجوتعداد', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'يوزرسجوتعداد', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'صفحيجوعنوان', 'PAGENAME' ],
	'pagesincategory'           => [ '1', 'زمريجاصفحا', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'نپ۾صفحا', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'صفحيجيماپ', 'PAGESIZE' ],
	'plural'                    => [ '0', 'جمع', 'PLURAL:' ],
	'redirect'                  => [ '0', '#چوريو', '#REDIRECT' ],
	'sitename'                  => [ '1', 'سرزميننالو', 'SITENAME' ],
	'special'                   => [ '0', 'خاص', 'special' ],
	'subjectspace'              => [ '1', 'مضمونپولار', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'talkspace'                 => [ '1', 'بحثپولار', 'TALKSPACE' ],
];
