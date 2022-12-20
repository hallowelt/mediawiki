<?php
/** Maltese (Malti)
 *
 * @file
 * @ingroup Languages
 *
 * @author Chrisportelli
 * @author Giangian15
 * @author Kaganer
 * @author Malafaya
 * @author Roderick Mallia
 * @author Urhixidur
 */

$namespaceNames = [
	NS_MEDIA            => 'Medja',
	NS_SPECIAL          => 'Speċjali',
	NS_TALK             => 'Diskussjoni',
	NS_USER             => 'Utent',
	NS_USER_TALK        => 'Diskussjoni_utent',
	NS_PROJECT_TALK     => 'Diskussjoni_$1',
	NS_FILE             => 'Stampa',
	NS_FILE_TALK        => 'Diskussjoni_stampa',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskussjoni_MediaWiki',
	NS_TEMPLATE         => 'Mudell',
	NS_TEMPLATE_TALK    => 'Diskussjoni_mudell',
	NS_HELP             => 'Għajnuna',
	NS_HELP_TALK        => 'Diskussjoni_għajnuna',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskussjoni_kategorija',
];

$namespaceAliases = [
	'Midja' => NS_MEDIA,
	'Diskuti' => NS_TALK,
	'Diskuti_utent' => NS_USER_TALK,
	'$1_diskuti' => NS_PROJECT_TALK,
	'$1_diskussjoni' => NS_PROJECT_TALK,
	'Diskuti_stampa' => NS_FILE_TALK,
	'MedjaWiki' => NS_MEDIAWIKI,
	'Diskuti_MedjaWiki' => NS_MEDIAWIKI_TALK,
	'Diskuti_template' => NS_TEMPLATE_TALK,
	'Diskuti_għajnuna' => NS_HELP_TALK,
	'Diskuti_kategorija' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'UtentiAttivi' ],
	'Allmessages'               => [ 'MessaġġiKollha' ],
	'Allpages'                  => [ 'PaġniKollha' ],
	'Ancientpages'              => [ 'PaġniQodma', 'PaġniAntiki' ],
	'Badtitle'                  => [ 'TitluĦażin' ],
	'Blankpage'                 => [ 'PaġnaVojta' ],
	'Block'                     => [ 'BlokkaIP' ],
	'BlockList'                 => [ 'ListaIPImblukkati' ],
	'Booksources'               => [ 'SorsiKotba' ],
	'BrokenRedirects'           => [ 'RindirizziMiksura' ],
	'Categories'                => [ 'Kategoriji' ],
	'ChangePassword'            => [ 'BiddelPassword' ],
	'ComparePages'              => [ 'IkkomparaPaġni' ],
	'Confirmemail'              => [ 'KonfermaPostaElettronika' ],
	'Contributions'             => [ 'Kontribuzzjonijiet' ],
	'CreateAccount'             => [ 'OħloqKont' ],
	'Deadendpages'              => [ 'PaġniWieqfa' ],
	'DeletedContributions'      => [ 'KontribuzzjonijietImħassra' ],
	'DoubleRedirects'           => [ 'RindirizziDoppji' ],
	'Emailuser'                 => [ 'IbgħatUtent' ],
	'ExpandTemplates'           => [ 'EspandiMudelli' ],
	'Export'                    => [ 'Esporta' ],
	'Fewestrevisions'           => [ 'L-InqasReviżjonijiet' ],
	'FileDuplicateSearch'       => [ 'FittexFajlDuplikat' ],
	'Filepath'                  => [ 'PostFajl' ],
	'Import'                    => [ 'Importa' ],
	'Invalidateemail'           => [ 'PostaElettronikaInvalida' ],
	'LinkSearch'                => [ 'FittexĦolqa' ],
	'Listadmins'                => [ 'ListaAmmin' ],
	'Listbots'                  => [ 'ListaBots' ],
	'Listfiles'                 => [ 'ListaStampi', 'ListaFajls' ],
	'Listgrouprights'           => [ 'ListaDrittijietGruppi' ],
	'Listredirects'             => [ 'ListaRindirizzi' ],
	'Listusers'                 => [ 'Utenti', 'ListaUtenti' ],
	'Lockdb'                    => [ 'AgħlaqDB' ],
	'Log'                       => [ 'Reġistru', 'Reġistri' ],
	'Lonelypages'               => [ 'PaġniOrfni' ],
	'Longpages'                 => [ 'PaġniTwal' ],
	'MergeHistory'              => [ 'WaħħadKronoloġija' ],
	'MIMEsearch'                => [ 'FittexMIME' ],
	'Mostcategories'            => [ 'L-AktarKategoriji' ],
	'Mostimages'                => [ 'L-AktarStampiMarbuta' ],
	'Mostlinked'                => [ 'L-AktarPaġniMarbuta' ],
	'Mostlinkedcategories'      => [ 'L-AktarKategorijiMarbuta' ],
	'Mostlinkedtemplates'       => [ 'L-AktarMudelliMarbuta' ],
	'Mostrevisions'             => [ 'L-AktarReviżjonijiet' ],
	'Movepage'                  => [ 'Mexxi', 'MexxiPaġna' ],
	'Mycontributions'           => [ 'KontribuzzjonijietTiegħi' ],
	'Mypage'                    => [ 'PaġnaTiegħi' ],
	'Mytalk'                    => [ 'DiskussjonijietTiegħi' ],
	'Newimages'                 => [ 'StampiĠodda', 'FajlsĠodda' ],
	'Newpages'                  => [ 'PaġniĠodda' ],
	'Preferences'               => [ 'Preferenzi' ],
	'Prefixindex'               => [ 'IndiċiPrefiss' ],
	'Protectedpages'            => [ 'PaġniProtetti' ],
	'Protectedtitles'           => [ 'TitliProtetti' ],
	'Randompage'                => [ 'PaġnaKwalunkwe' ],
	'Randomredirect'            => [ 'RiindirizzKwalunkwe' ],
	'Recentchanges'             => [ 'TibdilRiċenti' ],
	'Recentchangeslinked'       => [ 'TibdilRelatat' ],
	'Revisiondelete'            => [ 'ĦassarReviżjoni' ],
	'Search'                    => [ 'Fittex' ],
	'Shortpages'                => [ 'PaġniQosra' ],
	'Specialpages'              => [ 'PaġniSpeċjali' ],
	'Statistics'                => [ 'Statistika' ],
	'Unblock'                   => [ 'Żblokka' ],
	'Uncategorizedcategories'   => [ 'KategorijiMhuxKategorizzati' ],
	'Uncategorizedimages'       => [ 'StampiMhuxKategorizzati' ],
	'Uncategorizedpages'        => [ 'PaġniMhuxKategorizzati' ],
	'Uncategorizedtemplates'    => [ 'MudelliMhuxKategorizzati' ],
	'Undelete'                  => [ 'Irkupra' ],
	'Unlockdb'                  => [ 'IftaħDB' ],
	'Unusedcategories'          => [ 'KategorijiMhuxUżati' ],
	'Unusedimages'              => [ 'StampiMhuxUżati', 'FajlsMhuxUżati' ],
	'Unusedtemplates'           => [ 'MudelliMhuxUżati' ],
	'Unwatchedpages'            => [ 'PaġniMhuxOsservati' ],
	'Upload'                    => [ 'Tella\'' ],
	'Userlogin'                 => [ 'UtentDħul' ],
	'Userlogout'                => [ 'UtentĦruġ' ],
	'Userrights'                => [ 'DrittijietUtent' ],
	'Version'                   => [ 'Verżjoni' ],
	'Wantedcategories'          => [ 'KategorijiRikjesti' ],
	'Wantedfiles'               => [ 'FajlsRikjesti' ],
	'Wantedpages'               => [ 'PaġniRikjesti', 'ĦoloqMiksura' ],
	'Wantedtemplates'           => [ 'MudelliRikjesti' ],
	'Watchlist'                 => [ 'ListaOsservazzjoni' ],
	'Whatlinkshere'             => [ 'XiJwassalHawn' ],
	'Withoutinterwiki'          => [ 'PaġniMingħajrInterwiki', 'BlaInterwiki' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'ANKRAKODIĊI', 'ANCHORENCODE' ],
	'basepagename'              => [ '1', 'ISEMBAŻIKUTAL-PAĠNA', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ISEMTAL-PAĠNATAL-BAŻIE', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'LINGWATAL-KONTENUT', 'LINGKONTENUT', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ĠURNATAKURRENTI', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ĠURNATAKURRENTI2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ISEMĠURNATAKURRENTI', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ĠTĠKURRENTI', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'SIEGĦAKURRENTI', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'XAHARKURRENTI', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'ABBREVXAHARKURRENTI', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'ISEMXAHARKURRENTI', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ĠENISEMXAHARKURRENTI', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'ĦINKURRENTI', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'TIMBRUTAL-ĦINKURRENTI', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'VERŻJONIKURRENTI', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'ĠIMGĦAKURRENTI', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'SENAKURRENTI', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'DEFAULTSORTJA:', 'DEFAULTSORTJAĊAVETTA:', 'DEFAULTKATEGORIJISORTJA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'directionmark'             => [ '1', 'MARKATAD-DIREZZJONI', 'MARKADIRE', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'URITITLU', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'DESTINAZZJONITAL-FAJL:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__SFORZAWERREJ__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'formatdata', 'dataformat', 'formatdate', 'dateformat' ],
	'fullpagename'              => [ '1', 'ISEMSĦIĦTAL-PAĠNA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ISEMTAL-PAĠNASĦIĦAE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLSĦIĦA:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLSĦIĦAE:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'SESS:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMMATIKA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__KATMOĦBIJA__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'bażi_tal-linja', 'baseline' ],
	'img_border'                => [ '1', 'bordura', 'burdura', 'border' ],
	'img_bottom'                => [ '1', 'taħt', 'bottom' ],
	'img_center'                => [ '1', 'nofs', 'ċentrali', 'ċentru', 'center', 'centre' ],
	'img_framed'                => [ '1', 'tilat', 'b\'tilar', 'tilar', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'bla_tilar', 'frameless' ],
	'img_left'                  => [ '1', 'xellug', 'left' ],
	'img_link'                  => [ '1', 'ħolqa=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'daqsminuri=$1', 'minuri=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'xejn', 'none' ],
	'img_page'                  => [ '1', 'paġna=$1', 'paġna $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'lemin', 'right' ],
	'img_sub'                   => [ '1', 'bid', 'sub' ],
	'img_super'                 => [ '1', 'tajjeb', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'test-taħt', 'text-bottom' ],
	'img_text_top'              => [ '1', 'test-fuq', 'text-top' ],
	'img_thumbnail'             => [ '1', 'daqsminuri', 'minuri', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'fuq', 'top' ],
	'img_upright'               => [ '1', 'wieqaf', 'wieqaf=$1', 'wieqaf $1', 'upright', 'upright=$1', 'upright $1' ],
	'index'                     => [ '1', '__INDIĊI__', '__INDEX__' ],
	'language'                  => [ '0', '#LINGWA:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'KŻ:', 'LC:' ],
	'lcfirst'                   => [ '0', 'IBDAKŻ:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'ĠURNATALOKALI', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ĠURNATALOKALI2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ISEMTAL-ĠURNATALOKALI', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'ĠTĠLOKALI', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'SIEGĦALOKALI', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'XAHARLOKALI', 'XAHARLOKALI2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'XAHARLOKALI1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'ABBREVXAHARLOKALI', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'ISEMXAHARLOKALI', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'ĠENISEMXAHARLOKALI', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'ĦINLOKALI', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'TIMBRUTAL-ĦINLOKALI', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'URLLOKALI:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'URLLOKALIE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'ĠIMGĦALOKALI', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'SENALOKALI', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MSĠ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'MSĠEW:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'SPAZJUTAL-ISEM', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'SPAZJUTAL-ISEME', 'NAMESPACEE' ],
	'newsectionlink'            => [ '1', '__ĦOLQASEZZJONIĠDIDA__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__EBDAKONVERTURKONTENUT__', '__EBDAKK__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__EBDASEZZJONIMODIFIKA__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__EBDAGALLERIJA__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__EBDAINDIĊI__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__EBDAĦOLQASEZZJONIĠDIDA__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__EBDAKONVERTURTITLU__', '__EBDAKT__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__EBDAWERREJ__', '__NOTOC__' ],
	'ns'                        => [ '0', 'IS:', 'NS:' ],
	'numberingroup'             => [ '1', 'NUMRUFIL-GRUPP', 'NUMFIL-GRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'NUMRUTA\'UTENTIATTIVI', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'NUMRUTA\'AMMIN', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NUMRUTA\'ARTIKLI', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NUMBRUTA\'MODIFIKI', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NUMRUTA\'FAJLS', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NUMRUTA\'PAĠNI', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NUMRUTA\'UTENTI', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'PADXELLUG', 'PADLEFT' ],
	'padright'                  => [ '0', 'PADLEMIN', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'ISEMTAL-PAĠNA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ISEMTAL-PAĠNAE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'PAĠNIFIL-KATEGORIJA', 'PAĠNIFILK', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'PAĠNIFL-ISPAZJUTAL-ISEM:', 'PAĠNISI:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'DAQSTAL-PAĠNI', 'PAGESIZE' ],
	'protectionlevel'           => [ '1', 'LIVELLITA\'PROTEZZJONI', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#RINDIRIZZA', '#REDIRECT' ],
	'revisionday'               => [ '1', 'ĠURNATATAR-REVIŻJONI', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ĠURNATATAR-REVIŻJONI2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'IDTAR-REVIŻJONI', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'XAHARTAR-REVIŻJONI', 'REVISIONMONTH' ],
	'revisiontimestamp'         => [ '1', 'TIMBRUTAR-REVIŻJONI', 'REVISIONTIMESTAMP' ],
	'revisionyear'              => [ '1', 'SENATAR-REVIŻJONI', 'REVISIONYEAR' ],
	'scriptpath'                => [ '0', 'DESTINAZZJONITA\'SKRITT', 'SCRIPTPATH' ],
	'servername'                => [ '0', 'ISEMTAS-SERVER', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'ISEMTAS-SIT', 'SITENAME' ],
	'special'                   => [ '0', 'speċjali', 'special' ],
	'staticredirect'            => [ '1', '__RIINDIRIZZSTATIKU__', '__STATICREDIRECT__' ],
	'subjectpagename'           => [ '1', 'ISEMTAS-SUĠĠETTTAL-PAĠNA', 'ISEMTAL-ARTIKLUTAL-PAĠNA', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'ISEMTAS-SUĠĠETTTAL-PAĠNAE', 'ISEMTAL-ARTIKLUTAL-PAĠNAE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'SPAZJUTAS-SUĠĠETT', 'SPAZJUTAL-ARTIKLU', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subpagename'               => [ '1', 'ISEMTAS-SOTTOPAĠNA', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ISEMTAS-SUBPAĠNAE', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'BIDDEL:', 'SUBST:' ],
	'tag'                       => [ '0', 'tabella', 'tag' ],
	'talkpagename'              => [ '1', 'ISEMPAĠNATA\'DISKUSSJONI', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ISEMTAL-PAĠNATAD-DISKUSSJONIE', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'SPAZJUTA\'DISKUSSJONI', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'SPAZJUTA\'DISKUSSJONIE', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__WERREJ__', '__TOC__' ],
	'uc'                        => [ '0', 'KK:', 'UC:' ],
	'ucfirst'                   => [ '0', 'IBDAKK:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'URLKODIĊI:', 'URLENCODE:' ],
];

$linkPrefixCharset = 'A-\\x{10ffff}';
