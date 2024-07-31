<?php
/** Yiddish (ייִדיש)
 *
 * @file
 * @ingroup Languages
 *
 * @author Chaim Shel
 * @author Jiddisch
 * @author Joystick
 * @author Kaganer
 * @author Reedy
 * @author Teak
 * @author Yidel
 * @author ווארצגאנג
 * @author לערי ריינהארט
 * @author פוילישער
 */

$fallback = 'he';

$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'מעדיע',
	NS_SPECIAL          => 'באַזונדער',
	NS_TALK             => 'רעדן',
	NS_USER             => 'באַניצער',
	NS_USER_TALK        => 'באַניצער_רעדן',
	NS_PROJECT_TALK     => '$1_רעדן',
	NS_FILE             => 'טעקע',
	NS_FILE_TALK        => 'טעקע_רעדן',
	NS_MEDIAWIKI        => 'מעדיעװיקי',
	NS_MEDIAWIKI_TALK   => 'מעדיעװיקי_רעדן',
	NS_TEMPLATE         => 'מוסטער',
	NS_TEMPLATE_TALK    => 'מוסטער_רעדן',
	NS_HELP             => 'הילף',
	NS_HELP_TALK        => 'הילף_רעדן',
	NS_CATEGORY         => 'קאַטעגאָריע',
	NS_CATEGORY_TALK    => 'קאַטעגאָריע_רעדן',
];

$namespaceAliases = [
	'באזונדער' => NS_SPECIAL,
	'באנוצער' => NS_USER,
	'באנוצער_רעדן' => NS_USER_TALK,
	'משתמש' => NS_USER,
	'שיחת_משתמש' => NS_USER_TALK,
	'משתמשת' => NS_USER,
	'שיחת_משתמשת' => NS_USER_TALK,
	'בילד' => NS_FILE,
	'בילד_רעדן' => NS_FILE_TALK,
	'מעדיעוויקי' => NS_MEDIAWIKI,
	'מעדיעוויקי_רעדן' => NS_MEDIAWIKI_TALK,
	'קאטעגאריע' => NS_CATEGORY,
	'קאטעגאריע_רעדן' => NS_CATEGORY_TALK,
	'באניצער' => NS_USER,
	'באניצער_רעדן' => NS_USER_TALK,
];
$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'באַניצער', 'female' => 'באַניצערין' ],
	NS_USER_TALK => [ 'male' => 'באַניצער_רעדן', 'female' => 'באַניצערין_רעדן' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'טעטיגע_באניצער' ],
	'Allmessages'               => [ 'סיסטעם_מעלדונגען' ],
	'Allpages'                  => [ 'אלע_בלעטער' ],
	'Ancientpages'              => [ 'אוראלטע_בלעטער' ],
	'Blankpage'                 => [ 'ליידיגער_בלאט' ],
	'Block'                     => [ 'בלאקירן' ],
	'BlockList'                 => [ 'בלאקירן_ליסטע' ],
	'BrokenRedirects'           => [ 'צעבראכענע_ווייטערפירונגען' ],
	'Categories'                => [ 'קאטעגאריעס' ],
	'ChangePassword'            => [ 'ענדערן_פאסווארט' ],
	'ComparePages'              => [ 'פארגלייהן_בלעטער' ],
	'Confirmemail'              => [ 'באשטעטיגן_ע-פאסט' ],
	'Contributions'             => [ 'בײַשטײַערונגען' ],
	'CreateAccount'             => [ 'שאפֿן_קאנטע' ],
	'Deadendpages'              => [ 'בלעטער_אן_פארבינדונגען' ],
	'DeletedContributions'      => [ 'אויסגעמעקעטע_בײַשטײַערונגען' ],
	'DoubleRedirects'           => [ 'פארטאפלטע_ווייטערפירונגען' ],
	'Emailuser'                 => [ 'שיקן_אן_ע-פאסט_צום_באניצער' ],
	'Export'                    => [ 'עקספארט' ],
	'Fewestrevisions'           => [ 'ווייניגסטע_רעוויזיעס' ],
	'Import'                    => [ 'אימפארט' ],
	'Listadmins'                => [ 'ליסטע_פון_סיסאפן' ],
	'Listbots'                  => [ 'ליסטע_פון_באטס' ],
	'Listfiles'                 => [ 'בילדער' ],
	'Listredirects'             => [ 'ווייטערפירונגען' ],
	'Listusers'                 => [ 'ליסטע_פון_באניצערס' ],
	'Lockdb'                    => [ 'פארשליסן_דאטנבאזע' ],
	'Log'                       => [ 'לאגביכער' ],
	'Lonelypages'               => [ 'פאר\'יתומ\'טע_בלעטער' ],
	'Longpages'                 => [ 'לאנגע_בלעטער' ],
	'MergeHistory'              => [ 'צונויפמישן_היסטאריע' ],
	'MIMEsearch'                => [ 'זוכן_MIME' ],
	'Mostcategories'            => [ 'מערסטע_קאטעגאריעס' ],
	'Mostimages'                => [ 'מערסטע_פארבונדענע_בילדער' ],
	'Mostinterwikis'            => [ 'מערסטע_פארבונדענע_אינטערוויקיס' ],
	'Mostlinked'                => [ 'מערסטע_פארבונדענע_בלעטער' ],
	'Mostlinkedcategories'      => [ 'מערסטע_פארבונדענע_קאטעגאריעס' ],
	'Mostlinkedtemplates'       => [ 'מערסטע_פארבונדענע_מוסטערן' ],
	'Mostrevisions'             => [ 'מערסטע_רעוויזיעס' ],
	'Movepage'                  => [ 'באוועגן_בלאט' ],
	'Mycontributions'           => [ 'מיינע_ביישטייערן' ],
	'Mypage'                    => [ 'מײַן_בלאט' ],
	'Mytalk'                    => [ 'מײַן_שמועס_בלאט' ],
	'Myuploads'                 => [ 'מיינע_ארויפלאדונגען' ],
	'Newimages'                 => [ 'נייע_בילדער' ],
	'Newpages'                  => [ 'נייע_בלעטער' ],
	'Preferences'               => [ 'פרעפערענצן' ],
	'Prefixindex'               => [ 'בלעטער_וואס_הייבן_אן_מיט' ],
	'Protectedpages'            => [ 'געשיצטע_בלעטער' ],
	'Protectedtitles'           => [ 'געשיצטע_קעפלעך' ],
	'Randompage'                => [ 'צופעליג', 'צופעליגער_בלאט' ],
	'Randomredirect'            => [ 'צופעליק_ווײַטערפֿירן' ],
	'Recentchanges'             => [ 'לעצטע_ענדערונגען' ],
	'Redirect'                  => [ 'ווײַטערפירונג' ],
	'Revisiondelete'            => [ 'אויסמעקן_ווערסיעס' ],
	'Search'                    => [ 'זוכן' ],
	'Shortpages'                => [ 'קורצע_בלעטער' ],
	'Specialpages'              => [ 'באזונדערע_בלעטער' ],
	'Statistics'                => [ 'סטאטיסטיק' ],
	'Tags'                      => [ 'טאגן' ],
	'Unblock'                   => [ 'אויפבלאקירן' ],
	'Uncategorizedcategories'   => [ 'קאטעגאריעס_אן_קאטעגאריעס' ],
	'Uncategorizedimages'       => [ 'בילדער_אן_קאטעגאריעס' ],
	'Uncategorizedpages'        => [ 'בלעטער_אן_קאטעגאריעס' ],
	'Uncategorizedtemplates'    => [ 'מוסטערן_אן_קאטעגאריעס' ],
	'Unusedcategories'          => [ 'אומבאניצטע_קאטעגאריעס' ],
	'Unusedimages'              => [ 'אומבאניצטע_בילדער' ],
	'Unusedtemplates'           => [ 'אומבאניצטע_מוסטערן' ],
	'Unwatchedpages'            => [ 'נישט_אויפגעפאסטע_בלעטער' ],
	'Upload'                    => [ 'ארויפלאדן' ],
	'Userlogin'                 => [ 'באניצער_איינלאגירן' ],
	'Userlogout'                => [ 'ארויסלאגירן' ],
	'Userrights'                => [ 'באניצער_רעכטן' ],
	'Version'                   => [ 'ווערזיע' ],
	'Wantedcategories'          => [ 'געזוכטע_קאטעגאריעס' ],
	'Wantedfiles'               => [ 'געזוכטע_טעקעס' ],
	'Wantedpages'               => [ 'געזוכטע_בלעטער' ],
	'Wantedtemplates'           => [ 'געזוכטע_מוסטערן' ],
	'Watchlist'                 => [ 'אויפֿפאסן_ליסטע', 'מיין_אויפֿפאסן_ליסטע' ],
	'Whatlinkshere'             => [ 'בלעטער_וואס_פארבונדן_אהער' ],
	'Withoutinterwiki'          => [ 'בלעטער_אָן_אינטערוויקי' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'לויפיקער_טאג', 'יום נוכחי', 'CURRENTDAY' ],
	'currentmonth'              => [ '1', 'לויפיקער_מאנאט', 'חודש נוכחי', 'חודש נוכחי 2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currenttime'               => [ '1', 'לויפֿיקע_צײַט', 'שעה נוכחית', 'CURRENTTIME' ],
	'currentyear'               => [ '1', 'לויפֿיקע_יאָר', 'שנה נוכחית', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'גרונטסארטיר:', 'מיון רגיל:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'ווייזן_קעפל', 'כותרת תצוגה', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'טעקעשטעג:', 'נתיב לקובץ:', 'FILEPATH:' ],
	'fullpagename'              => [ '1', 'פולבלאטנאמען', 'שם הדף המלא', 'FULLPAGENAME' ],
	'fullurl'                   => [ '0', 'פֿולער_נאמען:', 'כתובת מלאה:', 'FULLURL:' ],
	'grammar'                   => [ '0', 'גראמאטיק:', 'דקדוק:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__באהאלטענע_קאטעגאריע__', '__באהאלטענע_קאט__', '__קטגוריה_מוסתרת__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'טעקסט=$1', 'טקסט=$1', 'alt=$1' ],
	'img_bottom'                => [ '1', 'אונטן', 'למטה', 'bottom' ],
	'img_center'                => [ '1', 'צענטער', 'מרכז', 'center', 'centre' ],
	'img_left'                  => [ '1', 'לינקס', 'שמאל', 'left' ],
	'img_link'                  => [ '1', 'לינק=$1', 'קישור=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'קליין=$1', 'ממוזער=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'אינמיטן', 'באמצע', 'middle' ],
	'img_none'                  => [ '1', 'אן', 'ללא', 'none' ],
	'img_right'                 => [ '1', 'רעכטס', 'ימין', 'right' ],
	'img_sub'                   => [ '1', 'אונטער', 'תחתי', 'sub' ],
	'img_super'                 => [ '1', 'איבער', 'עילי', 'super', 'sup' ],
	'img_thumbnail'             => [ '1', 'קליין', 'ממוזער', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'אויבן', 'למעלה', 'top' ],
	'img_width'                 => [ '1', '$1פיקס', '$1 פיקסלים', '$1px' ],
	'language'                  => [ '0', '#שפראך', '#שפה', '#LANGUAGE' ],
	'namespace'                 => [ '1', 'נאמענטייל', 'מרחב השם', 'NAMESPACE' ],
	'noeditsection'             => [ '0', '__נישט_רעדאקטירן__', '__ללא_עריכה__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__קיין_גאלעריע__', '__ללא_גלריה__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__קיין_אינהאלט_טאבעלע__', '__ללא_תוכן_עניינים__', '__ללא_תוכן__', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'צאל_טעטיקע_באניצער', 'מספר משתמשים פעילים', 'NUMBEROFACTIVEUSERS' ],
	'numberofarticles'          => [ '1', 'צאל_ארטיקלען', 'מספר ערכים', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'צאל_רעדאקטירונגען', 'מספר עריכות', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'צאל_טעקעס', 'מספר קבצים', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'צאל_בלעטער', 'מספר דפים כולל', 'מספר דפים', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'צאל_באניצער', 'מספר משתמשים', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'בלאטנאמען', 'שם הדף', 'PAGENAME' ],
	'pagesincategory_pages'     => [ '0', 'בלעטער', 'דפים', 'pages' ],
	'pagesize'                  => [ '1', 'בלאטגרייס', 'גודל דף', 'PAGESIZE' ],
	'plural'                    => [ '0', 'מערצאל:', 'רבים:', 'PLURAL:' ],
	'raw'                       => [ '0', 'רוי:', 'ללא עיבוד:', 'RAW:' ],
	'redirect'                  => [ '0', '#ווייטערפירן', '#הפניה', '#REDIRECT' ],
	'special'                   => [ '0', 'באזונדער', 'מיוחד', 'special' ],
	'subpagename'               => [ '1', 'אונטערבלאטנאמען', 'שם דף המשנה', 'SUBPAGENAME' ],
	'subst'                     => [ '0', 'ס:', 'SUBST:' ],
	'tag'                       => [ '0', 'טאג', 'תג', 'תגית', 'tag' ],
	'talkpagename'              => [ '1', 'רעדנבלאטנאמען', 'שם דף השיחה', 'TALKPAGENAME' ],
	'toc'                       => [ '0', '__אינהאלט__', '__תוכן_עניינים__', '__תוכן__', '__TOC__' ],
	'url_path'                  => [ '0', 'שטעג', 'נתיב', 'PATH' ],
	'url_wiki'                  => [ '0', 'וויקי', 'ויקי', 'WIKI' ],
];
