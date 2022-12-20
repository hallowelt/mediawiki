<?php
/** Talysh (tolışi)
 *
 * @file
 * @ingroup Languages
 *
 * @author Erdemaslancan
 * @author Ganbarzada
 * @author Tuzkozbir
 * @author Гусейн
 */

$namespaceNames = [
	NS_MEDIA            => 'Medja',
	NS_SPECIAL          => 'Xususi',
	NS_TALK             => 'Nopegət',
	NS_USER             => 'Okoədə',
	NS_USER_TALK        => 'Okoədəj_nopegət',
	NS_PROJECT_TALK     => '$1_Nopegət',
	NS_FILE             => 'Fajl',
	NS_FILE_TALK        => 'Fajl_nopegət',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nopegət',
	NS_TEMPLATE         => 'Numunə',
	NS_TEMPLATE_TALK    => 'Numunə_nopegət',
	NS_HELP             => 'Koməg',
	NS_HELP_TALK        => 'Koməg_nopegət',
	NS_CATEGORY         => 'Tispir',
	NS_CATEGORY_TALK    => 'Tispir_nopegət',
];

$namespaceAliases = [
	'$1_Nopegətəti'    => NS_PROJECT_TALK,
	'Fajli_nopegət'    => NS_FILE_TALK,
	'Koməgi_nopegət'   => NS_HELP_TALK,
	'Tispiron_nopegət' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allpages'                  => [ 'Həmməy_səhifon' ],
	'Blankpage'                 => [ 'Təyliyə_səhifə' ],
	'ChangeEmail'               => [ 'E-nomə_dəqiş_kardey' ],
	'ChangePassword'            => [ 'Paroli_dəqiş_kardey' ],
	'Emailuser'                 => [ 'Bə_iştirokəkə_nomə_vığandey' ],
	'Longpages'                 => [ 'Dırozə_səhifon' ],
	'Movepage'                  => [ 'Səhifə_nomi_dəqiş_kardey' ],
	'MyLanguage'                => [ 'Çımı_zıvon' ],
	'Mypage'                    => [ 'Çımı_səhifə' ],
	'Mytalk'                    => [ 'Çımı_mızokirə' ],
	'Myuploads'                 => [ 'Çımı_bo_jə_bıə_çiyon' ],
	'Newimages'                 => [ 'Nuyə_faylon' ],
	'Newpages'                  => [ 'Nuyə_səhifon' ],
	'PasswordReset'             => [ 'Paroli_ləğv_kardey' ],
	'Protectedpages'            => [ 'Mıdofiyə_kardə_bıə_səhifon' ],
	'Protectedtitles'           => [ 'Mıdofiyə_kardə_bıə_nomon' ],
	'Randompage'                => [ 'Rayrastə_səhifə', 'Rayrastə' ],
	'Recentchanges'             => [ 'Ən_nuyə_dəqişon' ],
	'Recentchangeslinked'       => [ 'Anqıl_kardə_bıə_dəqişon' ],
	'Revisiondelete'            => [ 'Rədd_kardə_bıə_dəqişon' ],
	'Search'                    => [ 'Nəve' ],
	'Shortpages'                => [ 'Kırtə_səhifon' ],
	'Tags'                      => [ 'Nışonon' ],
	'Undelete'                  => [ 'Bərpo_kardey' ],
	'Version'                   => [ 'Rəvoyət' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'SƏHİFƏ_NOMİ_ƏSOS', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'SƏHİFƏ_NOMİ_ƏSOS_2', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'MIĞDORİ_ZIVON', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ESƏTNƏ_RUJ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ESƏTNƏ_RUJ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ESƏTNƏ_RUJİ_NOM', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ESƏTNƏ_HAFTƏ_RUJ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ESƏTNƏ_SAAT', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'ESƏTNƏ_MANQ', 'ESƏTNƏ_MANQ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ESƏTNƏ_MANQ_1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'ESƏTNƏ_MANQİ_NOM', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ESƏTNƏ_MANQİ_NOM_CİNS', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'ESƏTNƏ_VAXT', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'ESƏTNƏ_VAXTİ_ĞEYD', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'ESƏTNƏ_RƏVOYƏT', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'ESƏTNƏ_HAFTƏ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'ESƏTNƏ_SOR', 'CURRENTYEAR' ],
	'directionmark'             => [ '1', 'NOMƏ_İSTİĞOMƏT', 'DIRECTIONMARK', 'DIRMARK' ],
	'forcetoc'                  => [ '0', '__MƏCBURİYƏ_MINDƏRİCOT__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'SƏHİFƏ_PURƏ_NOM', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'SƏHİFƏ_PURƏ_NOM_2', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'PURƏ_UNVON:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'PURƏ_UNVON_2:', 'FULLURLE:' ],
	'img_center'                => [ '1', 'mərənqo', 'center', 'centre' ],
	'img_left'                  => [ '1', 'çəpo', 'left' ],
	'img_manualthumb'           => [ '1', 'miniatyur=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'be', 'none' ],
	'img_page'                  => [ '1', 'səhifə=$1', 'səhifə_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'rosto', 'right' ],
	'img_thumbnail'             => [ '1', 'miniatyur', 'thumbnail', 'thumb' ],
	'language'                  => [ '0', '#ZIVON:', '#LANGUAGE:' ],
	'localday'                  => [ '1', 'BUMİNƏ_RUJ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'BUMİNƏ_RUJ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'BUMİNƏ_RUJİ_NOM', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'BUMİNƏ_HAFTƏ_RUJ', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'BUMİNƏ_SAAT', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'BUMİNƏ_MANQ', 'BUMİNƏ_MANQ_2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'BUMİNƏ_MANQ_1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'BUMİNƏ_MANQİ_NOM', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'BUMİNƏ_MANQİ_NOM_CİNS', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'BUMİNƏ_VAXT', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'BUMİNƏ_VAXTİ_ĞEYD', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'BUMİNƏ_UNVON:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'BUMİNƏ_UNVON_2:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'BUMİNƏ_HAFTƏ', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'BUMİNƏ_SOR', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'XƏBƏ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'BEVİKİ_XƏBƏ:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'NOMON_MƏKON', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NOMON_MƏKON_2', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'NOMON_MƏKON_ĞƏDƏR', 'NAMESPACENUMBER' ],
	'notoc'                     => [ '0', '__BEMINDƏRİCOT__', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'TİLİKƏ_İŞTİROKƏKON_ĞƏDƏR', 'NUMBEROFACTIVEUSERS' ],
	'numberofarticles'          => [ '1', 'MƏĞOLON_ĞƏDƏR', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'DƏQİŞON_ĞƏDƏR', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'FAYLON_ĞƏDƏR', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'SƏHİFON_ĞƏDƏR', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'İŞTİROKƏKON_ĞƏDƏR', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'SƏHİFƏ_NOM', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SƏHİFƏ_NOM_2', 'PAGENAMEE' ],
	'pagesinnamespace'          => [ '1', 'SƏHİFON_BƏ_NOMON_MƏKONƏDƏ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'SƏHİFƏ_PAMYƏ', 'PAGESIZE' ],
	'redirect'                  => [ '0', '#TOJƏDƏN_İSTİĞOMƏT_DOY', '#REDIRECT' ],
	'revisionday'               => [ '1', 'RƏVOYƏTİ_RUJ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'RƏVOYƏTİ_RUJ_2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'RƏVOYƏTİ_ID', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'RƏVOYƏTİ_MANQ', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'RƏVOYƏTİ_MANQ_2', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'RƏVOYƏTİ_VAXTİ_ĞEYD', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'İŞTİROKƏKƏ_RƏVOYƏT', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'RƏVOYƏTİ_SOR', 'REVISIONYEAR' ],
	'sitename'                  => [ '1', 'SAYTİ_NOM', 'SITENAME' ],
	'subjectpagename'           => [ '1', 'MƏĞOLƏ_SƏHİFƏ_NOM', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'MƏĞOLƏ_SƏHİFƏ_NOM_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'MƏĞOLON_MƏKON', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'MƏĞOLON_MƏKON_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'JİNTONƏDƏ_SƏHİFƏ_NOM', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'JİNTONƏDƏ_SƏHİFƏ_NOM_2', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'ƏVƏZ_KARDE:', 'SUBST:' ],
	'talkpagename'              => [ '1', 'MIZOKİRƏ_SƏHİFƏ_NOM', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'MIZOKİRƏ_SƏHİFƏ_NOM_2', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'MIZOKİRON_MƏKON', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'MIZOKİRON_MƏKON_2', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__MINDƏRİCOT__', '__TOC__' ],
	'url_wiki'                  => [ '0', 'VİKİ', 'WIKI' ],
];
