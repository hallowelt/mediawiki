<?php
/** Esperanto (Esperanto)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'Aŭdvidaĵo',
	NS_SPECIAL          => 'Specialaĵo',
	NS_TALK             => 'Diskuto',
	NS_USER             => 'Uzanto',
	NS_USER_TALK        => 'Uzanto-Diskuto',
	NS_PROJECT_TALK     => '$1-Diskuto',
	NS_FILE             => 'Dosiero',
	NS_FILE_TALK        => 'Dosiero-Diskuto',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-Diskuto',
	NS_TEMPLATE         => 'Ŝablono',
	NS_TEMPLATE_TALK    => 'Ŝablono-Diskuto',
	NS_HELP             => 'Helpo',
	NS_HELP_TALK        => 'Helpo-Diskuto',
	NS_CATEGORY         => 'Kategorio',
	NS_CATEGORY_TALK    => 'Kategorio-Diskuto',
];

$namespaceAliases = [
	'Speciala'             => NS_SPECIAL,
	'Vikipediisto'         => NS_USER,
	'Vikipediista_diskuto' => NS_USER_TALK,
	'Uzulo'                => NS_USER,
	'Uzanto'               => NS_USER,
	'Uzula_diskuto'        => NS_USER_TALK,
	'Uzanta_diskuto'       => NS_USER_TALK,
	'$1_diskuto'           => NS_PROJECT_TALK,
	'Dosiera_diskuto'      => NS_FILE_TALK,
	'MediaVikio'            => NS_MEDIAWIKI,
	'MediaWiki_diskuto'    => NS_MEDIAWIKI_TALK,
	'MediaVikia_diskuto'   => NS_MEDIAWIKI_TALK,
	'Ŝablona_diskuto'      => NS_TEMPLATE_TALK,
	'Helpa_diskuto'        => NS_HELP_TALK,
	'Kategoria_diskuto'    => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Uzanto', 'female' => 'Uzantino' ],
	NS_USER_TALK => [ 'male' => 'Uzanto-Diskuto', 'female' => 'Uzantino-Diskuto' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Aktivaj_uzantoj' ],
	'Allmessages'               => [ 'Ĉiuj_mesaĝoj' ],
	'Allpages'                  => [ 'Ĉiuj_paĝoj' ],
	'Ancientpages'              => [ 'Malnovaj_paĝoj' ],
	'Badtitle'                  => [ 'Malbona_titolo' ],
	'Blankpage'                 => [ 'Malplena_paĝo' ],
	'Block'                     => [ 'Forbari_IP-adreson' ],
	'BlockList'                 => [ 'Forbarlisto_de_IP-adresoj', 'IP-adresa_forbarlisto' ],
	'Booksources'               => [ 'Citoj_el_libroj' ],
	'BrokenRedirects'           => [ 'Rompitaj_alidirektiloj' ],
	'Categories'                => [ 'Kategorioj' ],
	'ChangeEmail'               => [ 'Ŝanĝi_retpoŝton' ],
	'ChangePassword'            => [ 'Ŝanĝi_pasvorton' ],
	'ComparePages'              => [ 'Kompari_paĝojn', 'Komparu_paĝojn' ],
	'Confirmemail'              => [ 'Konfirmi_per_retpoŝto' ],
	'Contributions'             => [ 'Kontribuoj' ],
	'CreateAccount'             => [ 'Krei_konton' ],
	'Deadendpages'              => [ 'Paĝoj_sen_interna_ligilo' ],
	'DeletedContributions'      => [ 'Forigitaj_kontribuoj' ],
	'DoubleRedirects'           => [ 'Duoblaj_alidirektiloj' ],
	'EditWatchlist'             => [ 'Redakti_atentaron' ],
	'Emailuser'                 => [ 'Retpoŝti_uzanton' ],
	'ExpandTemplates'           => [ 'Malfaldi_ŝablonon' ],
	'Export'                    => [ 'Elporti', 'Eksporti' ],
	'Fewestrevisions'           => [ 'Plej_malmultaj_revizioj' ],
	'FileDuplicateSearch'       => [ 'Serĉi_pri_duoblaj_dosieroj' ],
	'Filepath'                  => [ 'Pado_de_dosiero', 'Dosiero-pado' ],
	'Import'                    => [ 'Enporti', 'Importi' ],
	'Invalidateemail'           => [ 'Malvalidigi_retpoŝton' ],
	'LinkSearch'                => [ 'Serĉi_ligilon' ],
	'Listadmins'                => [ 'Listigi_administrantojn' ],
	'Listbots'                  => [ 'Listigi_robotojn' ],
	'Listfiles'                 => [ 'Listigi_dosierojn', 'Listigi_bildojn', 'Bildolisto' ],
	'Listgrouprights'           => [ 'Gruprajtoj_de_uzantoj' ],
	'Listredirects'             => [ 'Listigi_alidirektilojn', 'Listigi_alidirektojn' ],
	'Listusers'                 => [ 'Listo_de_uzantoj' ],
	'Lockdb'                    => [ 'Ŝlosi_datumbazon' ],
	'Log'                       => [ 'Protokolo', 'Protokoloj' ],
	'Lonelypages'               => [ 'Neligitaj_paĝoj' ],
	'Longpages'                 => [ 'Longaj_paĝoj' ],
	'MergeHistory'              => [ 'Unuigi_kronologion', 'Kunigi_kronologion', 'Kunigi_historion' ],
	'MIMEsearch'                => [ 'MIME-Serĉo' ],
	'Mostcategories'            => [ 'Plej_multaj_kategorioj' ],
	'Mostimages'                => [ 'Plej_ligitaj_bildoj' ],
	'Mostlinked'                => [ 'Plej_ligitaj_paĝoj' ],
	'Mostlinkedcategories'      => [ 'Plej_ligitaj_kategorioj', 'Plej_uzataj_kategorioj' ],
	'Mostlinkedtemplates'       => [ 'Plej_ligitaj_ŝablonoj', 'Plej_uzataj_ŝablonoj' ],
	'Mostrevisions'             => [ 'Plej_multaj_revizioj' ],
	'Movepage'                  => [ 'Alinomigi_paĝon' ],
	'Mycontributions'           => [ 'Miaj_kontribuoj', 'MiajKontribuoj' ],
	'MyLanguage'                => [ 'Mia_lingvo' ],
	'Mypage'                    => [ 'Mia_paĝo', 'MiaPaĝo' ],
	'Mytalk'                    => [ 'Mia_diskutpaĝo', 'MiaDiskutpaĝo' ],
	'Myuploads'                 => [ 'Miaj_alŝutaĵoj' ],
	'Newimages'                 => [ 'Novaj_bildoj' ],
	'Newpages'                  => [ 'Novaj_paĝoj' ],
	'PasswordReset'             => [ 'Ŝanĝo_de_pasvorto' ],
	'PermanentLink'             => [ 'Daŭra_ligilo' ],
	'Preferences'               => [ 'Preferoj' ],
	'Prefixindex'               => [ 'Indekso_de_prefiksoj' ],
	'Protectedpages'            => [ 'Protektitaj_paĝoj' ],
	'Protectedtitles'           => [ 'Protektitaj_titoloj' ],
	'Randompage'                => [ 'Hazarda_paĝo' ],
	'Randomredirect'            => [ 'Hazarda_alidirektilo', 'Hazarda_alidirekto' ],
	'Recentchanges'             => [ 'Lastaj_ŝanĝoj' ],
	'Recentchangeslinked'       => [ 'Rilataj_ŝanĝoj' ],
	'Revisiondelete'            => [ 'Forigi_revizion' ],
	'Search'                    => [ 'Serĉi' ],
	'Shortpages'                => [ 'Mallongaj_paĝoj' ],
	'Specialpages'              => [ 'Specialaj_paĝoj' ],
	'Statistics'                => [ 'Statistikoj' ],
	'Tags'                      => [ 'Etikedoj' ],
	'Unblock'                   => [ 'Malforbari' ],
	'Uncategorizedcategories'   => [ 'Kategorioj_sen_kategorio' ],
	'Uncategorizedimages'       => [ 'Bildoj_sen_kategorio' ],
	'Uncategorizedpages'        => [ 'Paĝoj_sen_kategorio' ],
	'Uncategorizedtemplates'    => [ 'Ŝablonoj_sen_kategorio' ],
	'Undelete'                  => [ 'Restarigi' ],
	'Unlockdb'                  => [ 'Malŝlosi_datumbazon' ],
	'Unusedcategories'          => [ 'Malplenaj_kategorioj' ],
	'Unusedimages'              => [ 'Neuzataj_bildoj' ],
	'Unusedtemplates'           => [ 'Neuzataj_ŝablonoj' ],
	'Unwatchedpages'            => [ 'Neatentitaj_paĝoj' ],
	'Upload'                    => [ 'Alŝuti' ],
	'Userlogin'                 => [ 'Ensaluti' ],
	'Userlogout'                => [ 'Elsaluti' ],
	'Userrights'                => [ 'Rajtoj_de_uzantoj' ],
	'Version'                   => [ 'Versio' ],
	'Wantedcategories'          => [ 'Dezirataj_kategorioj' ],
	'Wantedfiles'               => [ 'Dezirataj_dosieroj' ],
	'Wantedpages'               => [ 'Dezirataj_paĝoj', 'Rompitaj_ligiloj' ],
	'Wantedtemplates'           => [ 'Dezirataj_ŝablonoj' ],
	'Watchlist'                 => [ 'Atentaro' ],
	'Whatlinkshere'             => [ 'Kio_ligas_ĉi_tien?' ],
	'Withoutinterwiki'          => [ 'Sen_intervikia_ligilo' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'articlepath'               => [ '0', 'ARTIKOLAPADO', 'ARTIKOLAVOJO', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'PATRAPAĜONOMO', 'PATRAPAGXONOMO', 'PATRAPAĜNOMO', 'PATRAPAGXNOMO', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'PATRAPAĜONOMOO', 'PATRAPAGXONOMOO', 'PATRAPAĜNOMOO', 'PATRAPAGXNOMOO', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'ENHAVA-LINGVO', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'NUNATAGO', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'NUNATAGO2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NUNATAGNOMO', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'NUNAHORO', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'NUNAMONATO', 'NUNAMONATO2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'NUNAMONATO1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'NUNAMONATNOMOMAL', 'NUNAMONATONOMOMAL', 'NUNAMONATANOMOMAL', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'NUNAMONATNOMO', 'NUNAMONATONOMO', 'NUNAMONATANOMO', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'NUNAMONATNOMOGEN', 'NUNAMONATONOMOGEN', 'NUNAMONATANOMOGEN', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'NUNATEMPO', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'NUNATEMPINDIKO', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'NUNAVERSIO', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'NUNASEMAJNO', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'NUNAJARO', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'DEFAŬLTORDIGO:', 'DEFAUXLTORDIGO:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'MONTRUTITOLON:', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'DOSIERO-VORO', 'DOSIERVOJO', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__FI__', '__FORTUINDEKSON__', '__FT__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'TUTAPAĜONOMO', 'TUTAPAGXONOMO', 'TUTAPAĜNOMO', 'TUTAPAGXNOMO', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'TUTAPAĜONOMOO', 'TUTAPAGXONOMOO', 'TUTAPAĜNOMOO', 'TUTAPAGXNOMOO', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'PLENALIGILO:', 'PLENLIG:', 'TUTATTT:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'PLENALIGILOO:', 'PLENLIGG:', 'TUTATTTT:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'SEKSO:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMATIKO:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__KK__', '__KAŜITAKATEGORIO__', '__KASXITAKATEGORIO__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'alternative=$1', 'alt=$1' ],
	'img_border'                => [ '1', 'kadra', 'kadrita', 'kadrigita', 'kadrite', 'kadrigite', 'border' ],
	'img_bottom'                => [ '1', 'malalte', 'bottom' ],
	'img_center'                => [ '1', 'centra', 'meza', 'center', 'centre' ],
	'img_class'                 => [ '1', 'klaso=$1', 'class=$1' ],
	'img_framed'                => [ '1', 'kadro', 'enkadrita', 'enkadrite', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'senkadra', 'frameless' ],
	'img_left'                  => [ '1', 'maldekstra', 'maldekstre', 'left' ],
	'img_link'                  => [ '1', 'ligilo=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'eta=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'meze', 'middle' ],
	'img_none'                  => [ '1', 'neniu', 'nenio', 'none' ],
	'img_page'                  => [ '1', 'paĝo=$1', 'paĝo $1', 'pagxo=$1', 'pagxo_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'dekstra', 'dekstre', 'right' ],
	'img_sub'                   => [ '1', 'sube', 'malsupre', 'sub' ],
	'img_super'                 => [ '1', 'supre', 'malsube', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'suba-teksto', 'text-bottom' ],
	'img_text_top'              => [ '1', 'tekst-alte', 'text-top' ],
	'img_thumbnail'             => [ '1', 'eta', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'alte', 'top' ],
	'img_upright'               => [ '1', 'altdekstre', 'altdekstre=$1', 'altdekstre_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1ra', '$1px' ],
	'index'                     => [ '1', '__INDEKSU__', '__INDEKSI__', '__INDEX__' ],
	'int'                       => [ '0', 'ENE:', 'INT:' ],
	'language'                  => [ '0', '#LINGVO:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'MALMAJUSKLE:', 'MINUSKLE:', 'LC:' ],
	'lcfirst'                   => [ '0', 'MALMAJUSKLEUNUA:', 'MINUSKLEUNUA:', 'MMU:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'LOKATAGO', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKATAGO2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'LOKATAGNOMO', 'LOKATAGONOMO', 'LOKATAGANOMO', 'LOCALDAYNAME' ],
	'localhour'                 => [ '1', 'LOKAHORO', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'LOKAMONATO', 'LOKAMONATO2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKAMONATO1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'LOKAMONATNOMOMAL', 'LOKAMONATONOMOMAL', 'LOKAMONATANOMOMAL', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'LOKAMONATNOMO', 'LOKAMONATONOMO', 'LOKAMONATANOMO', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'LOKAMONATNOMOGEN', 'LOKAMONATONOMOGEN', 'LOKAMONATANOMOGEN', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'LOKATEMPO', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'LOKATEMPINDIKO', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'LOKATTT:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKATTTT:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKASEMAJNO', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'LOKAJARO', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MSĜ:', 'MSGX:', 'MSG:' ],
	'msgnw'                     => [ '0', 'NVMSĜ:', 'NVMSGX:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'NOMSPACO', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NOMSPACOO', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'NUMERODENOMSPACO', 'NOMSPACNUMERO', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__LIGILOALNOVASEKCIO__', '__NSL__', '__LNS__', '__LANS__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__NEKONVERTUENHAVON__', '__NKH__', '__NCC__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__SRS__', '__NES__', '__SENREDAKTISEKCIOJN__', '__SENREDAKTISEKCION__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__NG__', '__SENBILDARO__', '__SB__', '__SG__', '__SENGALERIO__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__NEINDEKSU__', '__NIU__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__SENLIGILOALNOVASEKCIO__', '__NNSL__', '__SLNS__', '__SLANS__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__NEKONVERTUTITOLON__', '__NKT__', '__NTC__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__NI__', '__NEINDEKSO__', '__NT__', '__NOTOC__' ],
	'nse'                       => [ '0', 'NSS:', 'NSO:', 'NSE:' ],
	'numberofactiveusers'       => [ '1', 'NOMBRODEAKTIVAJUZANTOJ', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'NOMBRODEADMINOJ', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NOMBRODEARTIKOLOJ', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NOMBRODEREDAKTOJ', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NOMBRODEDOSIEROJ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NOMBRODEPAĜOJ', 'NOMBRODEPAGXOJ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NOMBRODEUZANTOJ', 'NUMBEROFUSERS' ],
	'pageid'                    => [ '0', 'IDENTIGILODEPAĜO', 'PAĜID', 'PAGEID' ],
	'pagename'                  => [ '1', 'PAĜONOMO', 'PAGXONOMO', 'PAĜNOMO', 'PAGXNOMO', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'PAĜONOMOO', 'PAGXONOMOO', 'PAĜNOMOO', 'PAGXNOMOO', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'PAĜOJENKATEGORIO', 'PAGXOJENKATEGORIO', 'PAĜOJENKAT', 'PAGXOJENKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'PAĜOJENNOMSPACO', 'PAGXOJENNOMSPACO', 'PAĜOJENS', 'PAGXOJENNS', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'PAĜOPEZO', 'PAGXOPEZO', 'PEZODEPAĜO', 'PEZODEPAGXO', 'PAGESIZE' ],
	'plural'                    => [ '0', 'PLURALA:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'PROTEKTONIVELO', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#ALIDIREKTI', '#ALIDIREKTU', '#AL', '#REDIRECT' ],
	'revisionyear'              => [ '1', 'JARODEREVIZIO', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'SEKURANSTAT:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'SKRIPTO-VOJO', 'SKRIPTOVOJO', 'SKRIPTVOJO', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'SERVILO', 'SERVER' ],
	'servername'                => [ '0', 'NOMODESERVILO', 'SERVILANOMO', 'SERVILONOMO', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'TTT-NOMO', 'RETPAĜNOMO', 'RETPAGXNOMO', 'RETEJNOMO', 'SITENAME' ],
	'special'                   => [ '0', 'speciala', 'special' ],
	'staticredirect'            => [ '1', '__STATIKAALIDIREKTO__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'STILO-VOJO', 'STILOVOJO', 'STILVOJO', 'STYLEPATH' ],
	'subpagename'               => [ '1', 'SUBPAĜONOMO', 'SUBPAGXONOMO', 'SUBPAĜNOMO', 'SUBPAGXNOMO', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'SUBPAĜONOMOO', 'SUBPAGXONOMOO', 'SUBPAĜNOMOO', 'SUBPAGXNOMOO', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'ANSTAT:', 'SUBST:' ],
	'tag'                       => [ '0', 'marko', 'etikedo', 'tag' ],
	'talkpagename'              => [ '1', 'DISKUTPAĜONOMO', 'DISKUTPAGXONOMO', 'DISKUTPAĜNOMO', 'DISKUTPAGXNOMO', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'DISKUTPAĜONOMOO', 'DISKUTPAGXONOMOO', 'DISKUTPAĜNOMOO', 'DISKUTPAGXNOMOO', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'DISKUTNOMSPACO', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUTNOMSPACOO', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__I__', '__T__', '__INDEKSO__', '__TOC__' ],
	'uc'                        => [ '0', 'MAJUSKLE:', 'MALMINUSKLE:', 'UC:' ],
	'ucfirst'                   => [ '0', 'MAJUSKLEUNUA:', 'MALMINUSKLEUNUA:', 'MU:', 'UCFIRST:' ],
	'url_path'                  => [ '0', 'VOJO', 'PATH' ],
	'url_query'                 => [ '0', 'INFORMPETO', 'QUERY' ],
	'url_wiki'                  => [ '0', 'VIKIO', 'WIKI' ],
];

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j M. Y',
	'dmy both' => 'H:i, j M. Y',
];
