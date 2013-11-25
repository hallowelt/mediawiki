<?php
/** Zazaki (Zazaki)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Asmen
 * @author Aspar
 * @author Belekvor
 * @author Erdemaslancan
 * @author George Animal
 * @author Gorizon
 * @author Kaganer
 * @author Marmase
 * @author Mirzali
 * @author Nemo bis
 * @author Neribij
 * @author Olvörg
 * @author Reedy
 * @author Sahim
 * @author Xoser
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Xısusi',
	NS_TALK             => 'Mesac',
	NS_USER             => 'Karber',
	NS_USER_TALK        => 'Karber_mesac',
	NS_PROJECT_TALK     => '$1_mesac',
	NS_FILE             => 'Dosya',
	NS_FILE_TALK        => 'Dosya_mesac',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_mesac',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_mesac',
	NS_HELP             => 'Peşti',
	NS_HELP_TALK        => 'Peşti_mesac',
	NS_CATEGORY         => 'Kategoriye',
	NS_CATEGORY_TALK    => 'Kategoriye_mesac',
);

$namespaceAliases = array(
	'Werênayış'            => NS_TALK,
	'Karber_werênayış'     => NS_USER_TALK,
	'$1_werênayış'         => NS_PROJECT_TALK,
	'Dosya_werênayış'      => NS_FILE_TALK,
	'MediaWiki_werênayış'  => NS_MEDIAWIKI_TALK,
	'Şablon_werênayış'     => NS_TEMPLATE_TALK,
	'Desteg'               => NS_HELP,
	'Desteg_werênayış'     => NS_HELP_TALK,
	'Peşti_werênayış'      => NS_HELP_TALK,
	'Kategori'             => NS_CATEGORY,
	'Kategori_werênayış'   => NS_CATEGORY_TALK,
	'Kategoriye_werênayış' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'KarberéAktivi', 'AktivKarberi' ),
	'Allmessages'               => array( 'MesaciPéro' ),
	'Allpages'                  => array( 'PeleyPéro' ),
	'Ancientpages'              => array( 'PeleyKehani' ),
	'Badtitle'                  => array( 'SernameyoXırab' ),
	'Blankpage'                 => array( 'PeréVengi' ),
	'Block'                     => array( 'Bloqe', 'BloqeIP', 'BloqeyéKarber' ),
	'Blockme'                   => array( 'BloqeyéMe' ),
	'Booksources'               => array( 'KıtabeÇıme' ),
	'BrokenRedirects'           => array( 'HeténayışoXırab' ),
	'Categories'                => array( 'Kategoriyan' ),
	'ChangeEmail'               => array( 'EpostaVırnayış' ),
	'ChangePassword'            => array( 'ParolaBıvırné', 'ParolaResetke' ),
	'ComparePages'              => array( 'PeraPéverke' ),
	'Confirmemail'              => array( 'EpostayAraştke' ),
	'Contributions'             => array( 'Dekerdışi' ),
	'CreateAccount'             => array( 'HesabVırazé' ),
	'Deadendpages'              => array( 'PeraBıgiré' ),
	'DeletedContributions'      => array( 'DekerdışékeBesterneyayé' ),
	'Disambiguations'           => array( 'Arézekerdış' ),
	'DoubleRedirects'           => array( 'HetanayışoDılet' ),
	'EditWatchlist'             => array( 'ListeyaSeyrkerdışiVırnayış' ),
	'Emailuser'                 => array( 'EpostayaKarberi' ),
	'Export'                    => array( 'Ateberde' ),
	'Fewestrevisions'           => array( 'TewrtaynRewizyon' ),
	'FileDuplicateSearch'       => array( 'KopyaydosyaCıgeyrayış', 'DıletdosyaCıgeyrayış' ),
	'Filepath'                  => array( 'RayaDosya', 'HerunaDosya', 'CayêDosya' ),
	'Import'                    => array( 'Azeredé', 'Atewrke' ),
	'Invalidateemail'           => array( 'EpostaAraştkerdışiBıterkné' ),
	'BlockList'                 => array( 'ListeyéBloqan', 'IPBloqi', 'Blokqeyé_IP' ),
	'LinkSearch'                => array( 'GreCıgeyrayış' ),
	'Listadmins'                => array( 'Listeyaİdarekaran' ),
	'Listbots'                  => array( 'ListeyaBotan' ),
	'Listfiles'                 => array( 'ListeyaDosyayan', 'DosyayaListeke', 'ListeyéResiman' ),
	'Listgrouprights'           => array( 'ListeyaHeqandéGruban', 'HeqéGrubdeKarberan' ),
	'Listredirects'             => array( 'ListeyaArézekerdışan' ),
	'Listusers'                 => array( 'ListeyaKarberan', 'KarberaListeke' ),
	'Lockdb'                    => array( 'DBKilitke' ),
	'Log'                       => array( 'Qeyd', 'Qeydi' ),
	'Lonelypages'               => array( 'PeréBéwayıri' ),
	'Longpages'                 => array( 'PeréDergi' ),
	'MergeHistory'              => array( 'VerénanPétewrke' ),
	'MIMEsearch'                => array( 'NIMECıgeyrayış' ),
	'Mostcategories'            => array( 'TewrvéşiKategoriyıni' ),
	'Mostimages'                => array( 'DosyeyékeCırévéşiGreDeyayo' ),
	'Mostinterwikis'            => array( 'TewrvéşiTeberwiki' ),
	'Mostlinked'                => array( 'PerékeCırévéşiGreDeyayo' ),
	'Mostlinkedcategories'      => array( 'KategoriyayékeCırévéşiGreDeyayo' ),
	'Mostlinkedtemplates'       => array( 'ŞablonékeCırévéşiGreDeyayo' ),
	'Mostrevisions'             => array( 'TewrvéşiRevizyon' ),
	'Movepage'                  => array( 'PelerBeré' ),
	'Mycontributions'           => array( 'DekerdenéMe' ),
	'Mypage'                    => array( 'PeréMe' ),
	'Mytalk'                    => array( 'VatenayışéMe' ),
	'Myuploads'                 => array( 'BarkerdışéMe' ),
	'Newimages'                 => array( 'DosyeyéNewey', 'ResiméNewey' ),
	'Newpages'                  => array( 'PeréNewey' ),
	'PasswordReset'             => array( 'ParolaResetkerdış' ),
	'PermanentLink'             => array( 'GreyoDaimi' ),
	'Popularpages'              => array( 'PeréPopuleri' ),
	'Preferences'               => array( 'Tercihi' ),
	'Prefixindex'               => array( 'SerVerole' ),
	'Protectedpages'            => array( 'PerékeStaryayé' ),
	'Protectedtitles'           => array( 'SernameyékeStaryayé' ),
	'Randompage'                => array( 'Raştameye', 'PelayakeRaştamé' ),
	'Randomredirect'            => array( 'HetenayışoRaştameye' ),
	'Recentchanges'             => array( 'VırnayışéPeyéni' ),
	'Recentchangeslinked'       => array( 'GreyéVırnayışéPeyénan' ),
	'Redirect'                  => array( 'Hetenayış' ),
	'Revisiondelete'            => array( 'RewizyoniBesterne' ),
	'Search'                    => array( 'Cıgeyre' ),
	'Shortpages'                => array( 'PeleyéKılmi' ),
	'Specialpages'              => array( 'PeréBexsey' ),
	'Statistics'                => array( 'İstatistiki' ),
	'Tags'                      => array( 'Etiketi' ),
	'Unblock'                   => array( 'BloqiWedarne' ),
	'Uncategorizedcategories'   => array( 'KategoriyayékeKategoriyanébiyé' ),
	'Uncategorizedimages'       => array( 'DosyeyékeKategoriyanébiyé' ),
	'Uncategorizedpages'        => array( 'PeleyékeKategoriyanébiyé' ),
	'Uncategorizedtemplates'    => array( 'ŞablonékeKategoriyanébiyé' ),
	'Undelete'                  => array( 'Peyserbiya' ),
	'Unlockdb'                  => array( 'DBKılitiAke' ),
	'Unusedcategories'          => array( 'KategoriyayékeNékariyayé' ),
	'Unusedimages'              => array( 'DosyeyékeNékariyayé' ),
	'Unusedtemplates'           => array( 'ŞablonékeNékariyayé' ),
	'Unwatchedpages'            => array( 'PeleyékeNéweyneyéné' ),
	'Upload'                    => array( 'Barkerdış' ),
	'UploadStash'               => array( 'BarkerdışéNımıtey' ),
	'Userlogin'                 => array( 'KarberCıkewtış' ),
	'Userlogout'                => array( 'KarberVıcyayış' ),
	'Userrights'                => array( 'HeqéKarberan', 'SysopKerdış', 'BotKerdış' ),
	'Version'                   => array( 'Versiyon' ),
	'Wantedcategories'          => array( 'KategoriyayékeWazéné' ),
	'Wantedfiles'               => array( 'DosyeyékeWazéné' ),
	'Wantedpages'               => array( 'PerékeWazéné' ),
	'Wantedtemplates'           => array( 'ŞablonékeWazéné' ),
	'Watchlist'                 => array( 'ListeySeyran' ),
	'Whatlinkshere'             => array( 'PerarêGre' ),
	'Withoutinterwiki'          => array( 'Béİnterwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#HETENAYIŞ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ESTENÇINO__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GALERİÇINO__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ESTENZARURET__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ESTEN__', '__TOC__' ),
	'noeditsection'             => array( '0', '__TİMARKERDIŞÇINO__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'AŞMİYANEWKİ', 'MEWCUDAŞMİ2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'AŞMİYANEWKİ1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NAMEYAŞMDANEWKİ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'AŞMACIYANEWKİ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'AŞMİYANEWKİKILMKERDIŞ', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ROCENEWKİ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ROCENEWKİ2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NAMEYÊROCENEWKİ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'SERRENEWKİ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DEMENEWKİ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'SEHATNEWKİ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'WAREYAŞMİ', 'WAREYAŞMİ2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'WAREYAŞMİ1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'NAMEYÊWAREYAŞMİ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'NAMEYWAREDÊAŞMİDACI', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'WAREYAŞMİKILMKERDIŞ', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'WAREYROCE', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'WAREYROCE2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NAMEYÊWAREYROCE', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'WAREYSERRE', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'WAREYDEME', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'WAREYSEHAT', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'AMARİYAPELAN', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'AMARİYAWESİQAN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'AMARİYADOSYAYAN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'AMARİYAKARBERAN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AMARİYAAKTİVKARBERAN', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'AMARİYAVURNAYIŞAN', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'AMARİYAMOCNAYIŞAN', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'NAMEYPELA', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NAMEYPELAA', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'CANAME', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'CANAMEE', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'AMARİYACANAME', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'CAYÊWERÊNAYIŞİ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'CAYÊWERÊNAYIŞAN', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'CAYÊMESEL', 'CAYÊWESİQE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'CAYÊMESELAN', 'CAYÊWESİQAN', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'NAMEYPELAPÊRO', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'NAMEYPELAPÊRON', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'NAMEYBINPELA', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'NAMEYBINPELAA', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NAMEYSERPELA', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NAMEYSERPELAA', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NAMEYPELAWERÊNAYIŞ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'NAMEYPELAWERÊNAYIŞAN', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'NAMEYPELAMESEL', 'NAMEYPELAWESİQE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'NAMEYPELAMESELER', 'NAMEYPELAQESİQER', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'MSC', 'MSG:' ),
	'subst'                     => array( '0', 'KOPYAKE', 'ATEBERDE', 'SUBST:' ),
	'safesubst'                 => array( '0', 'EMELEYATEBERDE', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'MSJNW:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'resmoqıckek', 'qıckek', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'resmoqıckek=$1', 'qıckek=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'raşt', 'right' ),
	'img_left'                  => array( '1', 'çep', 'left' ),
	'img_none'                  => array( '1', 'çıniyo', 'none' ),
	'img_width'                 => array( '1', '$1pik', '$1piksel', '$1px' ),
	'img_center'                => array( '1', 'werte', 'miyan', 'center', 'centre' ),
	'img_framed'                => array( '1', 'çerçeweya', 'çerçeweniyo', 'çerçewe', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'bêçerçewe', 'frameless' ),
	'img_page'                  => array( '1', 'pela=$1', 'pela_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'disleg', 'disleg=$1', 'disleg_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'sinor', 'border' ),
	'img_baseline'              => array( '1', 'Sinorêerdi', 'baseline' ),
	'img_sub'                   => array( '1', 'bın', 'sub' ),
	'img_super'                 => array( '1', 'corên', 'cor', 'super', 'sup' ),
	'img_top'                   => array( '1', 'gedug', 'top' ),
	'img_text_top'              => array( '1', 'gedug-metin', 'text-top' ),
	'img_middle'                => array( '1', 'merkez', 'middle' ),
	'img_bottom'                => array( '1', 'erd', 'bottom' ),
	'img_text_bottom'           => array( '1', 'erd-metin', 'text-bottom' ),
	'img_link'                  => array( '1', 'gre=$1', 'link=$1' ),
	'int'                       => array( '0', 'İNT:', 'INT:' ),
	'sitename'                  => array( '1', 'NAMEYSİTA', 'SITENAME' ),
	'ns'                        => array( '0', 'CN', 'NS:' ),
	'nse'                       => array( '0', 'CNV', 'NSE:' ),
	'localurl'                  => array( '0', 'LOKALGRE', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALGREV', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'SOPAWESİQAN', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'NIMREYPELA', 'PAGEID' ),
	'server'                    => array( '0', 'ARDEN', 'SERVER' ),
	'servername'                => array( '0', 'NAMEYARDEN', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'RAYASCRIPTİ', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'TERZÊTEWRİ', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMER:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'CİNSİYET:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__SERNAMEVURNAYIŞÇINO__', '__SVÇ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__ZERREVURNAYIŞÇINO__', '__ZVÇ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'MEVCUDHEFTE', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'MEVCUDWAREYHEFTİ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'WAREYHEFTİ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'WAREYROCAHEFTİ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'NIMREYREVİZYONİ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ROCAREVİZYONİ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ROCAREVİZYON1', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'AŞMAREVİZYONİ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'AŞMAREVİZYONİ1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'SERRAREVİZYONİ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'MELUMATÊREVİZYONÊDEMİ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'REVİZYONKARBER', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'ZAFEN:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'GREPÊRO:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'GREYOPÊRON:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'GREYÊKANONİK:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', 'GREYOKANONİK:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', 'KHİLK:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'BHİLK:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KH:', 'LC:' ),
	'uc'                        => array( '0', 'BH:', 'UC:' ),
	'raw'                       => array( '0', 'XAM:', 'RAW:' ),
	'displaytitle'              => array( '1', 'SERNAMİBIMOCNE', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__GREYÊSERNAMEDÊNEWİ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__GREYÊSERNAMEDÊNEWİÇINO__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'VERSİYONÊNEWKİ', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'WAREYSEHATÊNEWKİ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'MALUMATÊWAREYSEHAT', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'HETANIŞANKERDIŞ', 'HETNIŞAN', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ZIWAN', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'ZIWANÊESTİN', 'ZIWESTEN', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PELEYÊKECADÊNAMİDEYÊ', 'PELECN', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'AMARİYAXİZMETKARAN', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'BABETNAYIŞ', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ÇEPİPIRKE', 'PADLEFT' ),
	'padright'                  => array( '0', 'RAŞTİPIRKE', 'PADRIGHT' ),
	'special'                   => array( '0', 'xısusi', 'special' ),
	'speciale'                  => array( '0', 'xısusiye', 'speciale' ),
	'defaultsort'               => array( '1', 'RATNAYIŞOHESBNAYIŞ', 'SIRMEYRATNAYIŞOHESBNAYIŞ', 'KATEGORİYARATNAYIŞOHESBNAYIŞ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'RAYADOSYA:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'etiket', 'tag' ),
	'hiddencat'                 => array( '1', '__KATEGORİYANIMITİ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PELEYÊKEKATEGORİDEYÊ', 'KATDÊPELEY', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'EBATÊPELA', 'PAGESIZE' ),
	'index'                     => array( '1', '__SERSIQ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__SERSIQÇINYO__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'GRUBDEAMARE', 'AMARİYAGRUBER', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATİKHETENAYIŞ__', '__STATICHETENAYIŞ__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'SEWİYEYÊSTARE', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'demêformati', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'RAY', 'PATH' ),
	'url_wiki'                  => array( '0', 'WİKİ', 'WIKI' ),
	'url_query'                 => array( '0', 'PERSİYE', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', 'xırabinçıniya', 'noerror' ),
	'defaultsort_noreplace'     => array( '0', 'cewabçıniyo', 'noreplace' ),
	'pagesincategory_all'       => array( '0', 'pêro', 'all' ),
	'pagesincategory_pages'     => array( '0', 'peley', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'bınkati', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'dosyey', 'files' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Bınê gırey de xete bance:',
'tog-justify' => 'Paragrafan eyar ke',
'tog-hideminor' => 'Vurnayışanê şenıkan pela vurnayışanê peyênan de bınımne',
'tog-hidepatrolled' => 'Vurnayışanê qontrolkerdeyan pela vurnayışê peyêni de bınımne',
'tog-newpageshidepatrolled' => 'Pelanê qontrolkerdeyan lista pelanê neweyan de bınımne',
'tog-extendwatchlist' => 'Lista seyrkerdışi hera bıke ke vurnayışi pêro basê, tenya tewr peyêni nê',
'tog-usenewrc' => 'Pera vurnayışanê grube de vurnayışê peyêni u lista seyrkerdışi (JavaScript lazımo)',
'tog-numberheadings' => 'Sernuşteyan be xo numre cı şane',
'tog-showtoolbar' => 'Toolbar virnayisi bivin (JavaScript lazımo)',
'tog-editondblclick' => 'Per virnayisi di dilet klik bike (JavaScript lazımo)',
'tog-editsection' => 'Vurnayışê qısımi be gıreyanê [bıvurne] ra feal ke',
'tog-editsectiononrightclick' => 'Qısıman be tıknayışê serrêze ra ebe gocega raşte bıvurne (JavaScript lazımo)',
'tog-showtoc' => 'Tabloyê tedeesteyan bımocne (de pelanê be hirê sernuşteyan ra vêşêri de)',
'tog-rememberpassword' => 'Parola mı nê cıgeyrayoği de bia xo viri (seba tewr zêde $1 {{PLURAL:$1|roce|rocan}}).',
'tog-watchcreations' => 'Pelê ke mı afernayê u dosyeyê ke mı bar kerdê lista mına seyrkerdışi ke',
'tog-watchdefault' => 'Pel u dosyeyê ke mı vurnayê lista mına seyrkerdışi ke',
'tog-watchmoves' => 'Pel u dosyeyê ke mı kırıştê lista mına seyrkerdışi ke',
'tog-watchdeletion' => 'Pel u dosyeyê ke mı esterıtê lista mına seyrkerdışi ke',
'tog-minordefault' => "Vurnayışanê xo pêrune ''vurnayışo qıckek'' nışan bıde",
'tog-previewontop' => 'Verqayti pela nuştışi ser de bımocne',
'tog-previewonfirst' => 'Vurnayışo verên de verqayti tım bımocne',
'tog-nocache' => 'Pelanê cıgeyrayoği meya xo viri',
'tog-enotifwatchlistpages' => 'Yew pele ya zi dosyaya ke lista mına seyrkerdışi de vurnayê mı rê e-poste bırışe',
'tog-enotifusertalkpages' => 'Pela mına werênayışi ke vurnayê mı rê e-poste bırışe',
'tog-enotifminoredits' => 'Vurnayışanê qıckekanê pelan u dosyeyan de zi mı rê e-poste bırışe',
'tog-enotifrevealaddr' => 'Adresa e-posteyê mı posteyê xeberan de bımocne',
'tog-shownumberswatching' => 'Amarê karberanê seyrkerdoğan bımocne',
'tog-oldsig' => 'İmzaya mewcude:',
'tog-fancysig' => 'İmza rê mameleyê wikimeqaley bıke (bê gıreyo otomatik)',
'tog-uselivepreview' => 'Verqayt di weseye  karneno (JavaScript lazım o) (Cerbino)',
'tog-forceeditsummary' => 'Mı ke xulasa kerde cı vira, hay be mı ser de',
'tog-watchlisthideown' => 'Vurnayışanê mı lista mına seyrkerdışi de bınımne',
'tog-watchlisthidebots' => 'Lista seyrkerdışi ra vurnayışanê boti bınımne',
'tog-watchlisthideminor' => 'Vurnayışanê qıckekan lista mına seyrkerdışi de bınımne',
'tog-watchlisthideliu' => 'Lista seyrkerdışi ra vurnayışanê karberanê cıkewteyan bınımne',
'tog-watchlisthideanons' => 'Lista seyrkerdışi ra vurnayışanê karberanê anoniman bınımne',
'tog-watchlisthidepatrolled' => 'Lista seyrkerdışi ra vurnayışanê qontrolkerdeyan bınımne',
'tog-ccmeonemails' => 'E-posteyanê ke ez karberanê binan rê rışenan, mı rê kopya inan bırışe',
'tog-diffonly' => 'Qıyasê versiyonan de tek ferqan bımocne, pela butıne nê',
'tog-showhiddencats' => 'Kategoriyanê dızdiye bımocne',
'tog-noconvertlink' => 'Greyê sernami çerx kerdışi bıqefılne',
'tog-norollbackdiff' => 'Peyserardışi ra dıme ferqi caverde',
'tog-useeditwarning' => 'Wexto ke mı yew pela nizami be vurnayışanê nêqeydbiyayeyan caverdê, hay be mı ser de',

'underline-always' => 'Tım',
'underline-never' => 'Qet',
'underline-default' => 'Cild ya zi cıgeyrayoğo hesebiyaye',

# Font style option in Special:Preferences
'editfont-style' => 'Cayê vurnayışi de terzê nuştışi:',
'editfont-default' => 'Cıgeyrayoğo hesabiyaye',
'editfont-monospace' => 'Terzê nusteyê sabıtcagırewtoği',
'editfont-sansserif' => 'Babetê Sans-serifi',
'editfont-serif' => 'Babetê serifi',

# Dates
'sunday' => 'Bazar',
'monday' => 'Berarek',
'tuesday' => 'Telete',
'wednesday' => 'Çarşeme',
'thursday' => 'Panşeme',
'friday' => 'Éne',
'saturday' => 'Bahdé éni',
'sun' => 'Baz',
'mon' => 'Bbz',
'tue' => 'Tlt',
'wed' => 'Çrş',
'thu' => 'Pşm',
'fri' => 'Êne',
'sat' => 'Bdé',
'january' => 'Çele',
'february' => 'Zemherı',
'march' => 'Mert',
'april' => 'Lisan',
'may_long' => 'Gúlan',
'june' => 'Heziran',
'july' => 'Temuz',
'august' => 'Ağustos',
'september' => 'Keşkelun',
'october' => 'Cetan',
'november' => 'Kelverdan',
'december' => 'Gağand',
'january-gen' => 'Çele',
'february-gen' => 'Zemherı',
'march-gen' => 'Mert',
'april-gen' => 'Lisan',
'may-gen' => 'Gúlan',
'june-gen' => 'Heziran',
'july-gen' => 'Temuz',
'august-gen' => 'Ağustos',
'september-gen' => 'Keşkelun',
'october-gen' => 'Cetan',
'november-gen' => 'Kelverdan',
'december-gen' => 'Gağand',
'jan' => 'Çel',
'feb' => 'Sbt',
'mar' => 'Adr',
'apr' => 'Nsn',
'may' => 'Gln',
'jun' => 'Hez',
'jul' => 'Tmz',
'aug' => 'Tbx',
'sep' => 'Kşk',
'oct' => 'Tşv',
'nov' => 'Tşp',
'dec' => 'Kan',
'january-date' => 'Çele  $1',
'february-date' => 'Şıbate $1',
'march-date' => 'Adar $1',
'april-date' => 'Nisane $1',
'may-date' => 'Gulane $1',
'june-date' => 'Hezirane $1',
'july-date' => 'Temuze $1',
'august-date' => 'Tebaxe $1',
'september-date' => 'Keşkelun $1',
'october-date' => 'Cıtan $1',
'november-date' => 'Qasım $1',
'december-date' => 'Kanun $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategori|Kategoriy}}',
'category_header' => 'Perré ke kategori da "$1" de yé',
'subcategories' => 'Kategoriyê bınêni',
'category-media-header' => 'Dosyeyê ke kategoriya "$1" derê',
'category-empty' => "''Ena kategoriye de hewna qet nuştey ya zi medya çıniyê.''",
'hidden-categories' => '{{PLURAL:$1|Kategoriya nımıtiye|Kategoriyê nımıtey}}',
'hidden-category-category' => 'Kategoriyê nımıtey',
'category-subcat-count' => '{{PLURAL:$2|Na kategoriya de $1 bınkategoriyay estê.|$2 kategoriyan ra $1 bınkategoriyay asenê.}}',
'category-subcat-count-limited' => 'Na kategoriye de {{PLURAL:$1|ena kategoriya bınêne esta|enê $1 kategoriyê bınêni estê}}.',
'category-article-count' => '{{PLURAL:$2|Na kategoriye de teyna ena pele esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ena pele esta|enê $1 peli estê.}}, be $2 ra pêro pia}}',
'category-article-count-limited' => '{{PLURAL:$1|Pela cêrêne|$1 Pelê cêrêni}} na kategoriye derê.',
'category-file-count' => '<noinclude>{{PLURAL:$2|Na kategoriye tenya dosyayanê cêrênan muhtewa kena.}}</noinclude>
*Na kategoriye de $2 dosyayan ra {{PLURAL:$1|yew dosya tenêka esta| $1 dosyey asenê}}.',
'category-file-count-limited' => '{{PLURAL:$1|Dosya cêrêne|$1 Dosyê cêrêni}} na kategoriye derê.',
'listingcontinuesabbrev' => 'dewam...',
'index-category' => 'Pelê endeksıni',
'noindex-category' => 'Pelê ke zerrekê cı çıniyo',
'broken-file-category' => 'Peleye ke gıreyê dosyeyanê ğeletan muhtewa kenê',
'categoryviewer-pagedlinks' => '($1) ($2)',

'about' => 'Heqdé cı',
'article' => 'Wesiqe',
'newwindow' => '(pençereyê newey de beno a)',
'cancel' => 'Bıterkne',
'moredotdotdot' => 'Vêşi...',
'morenotlisted' => 'Vêşi lista nêbi...',
'mypage' => 'Per',
'mytalk' => 'Vaten',
'anontalk' => 'Pela werênayışê nê IPy',
'navigation' => 'Pusula',
'and' => '&#32;u',

# Cologne Blue skin
'qbfind' => 'Bıvêne',
'qbbrowse' => 'Rovete',
'qbedit' => 'Timar ke',
'qbpageoptions' => 'Ena pele',
'qbmyoptions' => 'Peré mı',
'qbspecialpages' => 'Pelê xısusiy',
'faq' => 'PZP (Persê ke zehf persiyenê)',
'faqpage' => 'Project: PZP',

# Vector skin
'vector-action-addsection' => 'Mewzu vıraze',
'vector-action-delete' => 'Bestere',
'vector-action-move' => 'Bere',
'vector-action-protect' => 'Star ke',
'vector-action-undelete' => 'Esterıtışi peyser bıgê',
'vector-action-unprotect' => 'Starkerdışi bıvurne',
'vector-simplesearch-preference' => 'Çuweya cı geyreyış de rehater aktiv ke (Tenya vector skin de)',
'vector-view-create' => 'Vıraze',
'vector-view-edit' => 'Bıvurne',
'vector-view-history' => 'Versiyonê verêni',
'vector-view-view' => 'Bıwane',
'vector-view-viewsource' => 'Çımey bıvêne',
'actions' => 'Hereketi',
'namespaces' => 'Cayê namey',
'variants' => 'Varyanti',

'navigation-heading' => 'Menuya Navigasyoni',
'errorpagetitle' => 'Xeta',
'returnto' => 'Peyser şo $1.',
'tagline' => '{{SITENAME}} ra',
'help' => 'Desteg',
'search' => 'Cı geyre',
'searchbutton' => 'Cı geyre',
'go' => 'Şo',
'searcharticle' => 'Şo',
'history' => 'Verora perer',
'history_short' => 'Verén',
'updatedmarker' => 'cıkewtena mına peyêne ra dıme biyo rocane',
'printableversion' => 'Asayışê çapkerdışi',
'permalink' => 'Gıreyo jûqere',
'print' => 'Nusten ke',
'view' => 'Bıvêne',
'edit' => 'Bıvurnên',
'create' => 'Vıraze',
'editthispage' => 'Ena pele bıvurne',
'create-this-page' => 'Na pele bınuse',
'delete' => 'Bestere',
'deletethispage' => 'Ena perer besternê',
'undeletethispage' => 'Na perer mebesterne',
'undelete_short' => '{{PLURAL:$1|Yew vurnayışi|$1 Vurnayışan}} mestere',
'viewdeleted_short' => '{{PLURAL:$1|Yew vurnayışo esterıte|$1 Vurnayışanê esterıtan}} bımocne',
'protect' => 'Star ke',
'protect_change' => 'bıvurne',
'protectthispage' => 'Ena pele bıpawe',
'unprotect' => 'Starkerdışi bıvurne',
'unprotectthispage' => 'Starkerdışe ena peler bıvurne',
'newpage' => 'Pera newiye',
'talkpage' => 'Ena pele sero werêne',
'talkpagelinktext' => 'Vaten',
'specialpage' => 'Pela xısusiye',
'personaltools' => 'Hacetê şexsiy',
'postcomment' => 'Qısımo newe',
'articlepage' => 'Pela zerreki bıvêne',
'talk' => 'Vaten',
'views' => 'Asayışi',
'toolbox' => 'Haceti',
'userpage' => 'Pela karberi bıvêne',
'projectpage' => 'Pela procey bıvêne',
'imagepage' => 'Pela dosya bımocne',
'mediawikipage' => 'Pela mesaci bımocne',
'templatepage' => 'Pela şabloni bımocne',
'viewhelppage' => 'Pela peşti bıvêne',
'categorypage' => 'Pela kategoriye bıvêne',
'viewtalkpage' => 'Werênayışi bıvêne',
'otherlanguages' => 'Zıwananê binan de',
'redirectedfrom' => '(Pele da $1 ra heteneyê)',
'redirectpagesub' => 'Pela berdışi',
'lastmodifiedat' => 'Ena pele tewr peyên roca $2, $1 de biya rocaniye.',
'viewcount' => 'Ena pele {{PLURAL:$1|rae|$1 rey}} vêniya.',
'protectedpage' => 'Pela pawıtiye',
'jumpto' => 'Şo:',
'jumptonavigation' => 'Pusula',
'jumptosearch' => 'cı geyre',
'view-pool-error' => 'Qaytê qısuri mekerên, serverê ma enıka zêde bar gırewto xo ser.
Hedê xo ra zêde karberi kenê ke seyrê na pele bıkerê.
Şıma rê zehmet, tenê vınderên, heta ke reyna kenê ke ena pele kewê.

$1',
'pool-timeout' => 'Kılitbiyayışi sero wextê vınetışi',
'pool-queuefull' => 'Rêza hewze pırra',
'pool-errorunknown' => 'Xeta nêzanıtiye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Heqdé {{SITENAME}}',
'aboutpage' => 'Project:Heqdê cı',
'copyright' => 'Zerrekacı $1 bındı not biya.',
'copyrightpage' => '{{ns:project}}:Heqa telifi',
'currentevents' => 'Veng u vac',
'currentevents-url' => 'Project:Veng u vac',
'disclaimers' => 'Redê mesuliyeti',
'disclaimerpage' => 'Project:Reddê mesuliyetê bıngey',
'edithelp' => 'Peştdariya vurnayışi',
'helppage' => 'Help:Zerrek',
'mainpage' => 'Pera Seri',
'mainpage-description' => 'Pera Seri',
'policy-url' => 'Project:Terzê hereketi',
'portal' => 'Portalê cemaeti',
'portal-url' => 'Project:Portalê cemaeti',
'privacy' => 'Madeyê dızdiye',
'privacypage' => 'Project:Xısusiyetê nımtışi',

'badaccess' => 'Xeta mısadey',
'badaccess-group0' => 'Heqa şıma çıniya, karo ke şıma waşt, bıkerê.',
'badaccess-groups' => 'No fealiyeto ke şıma waşt, tenya karberanê {{PLURAL:$2|grubi|gruban ra yewi}} rê akerdeyo: $1.',

'versionrequired' => 'No $1 MediaWiki lazımo',
'versionrequiredtext' => 'Seba gurenayışê na pele versiyonê MediaWiki $1 lazımo. 
[[Special:Version|Versiyonê pele]] bıvêne.',

'ok' => 'Temam',
'pagetitle' => '"$1" adres ra gerya.',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle' => '← $1',
'retrievedfrom' => '"$1" ra ard',
'youhavenewmessages' => 'To rê $1 esto ($2).',
'youhavenewmessagesfromusers' => 'Zey $1 ra {{PLURAL:$3|zewbi karber|$3 karberi}} ($2) esto.',
'youhavenewmessagesmanyusers' => '$1 ra tay karberi ($2) dı estê.',
'newmessageslinkplural' => '{{PLURAL:$1|yew mesaco newe|999=mesacê newey}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|vurnayışo peyên|999=vurnayışê peyêni}}',
'youhavenewmessagesmulti' => '$1 mesaco newe esto',
'editsection' => 'bıvurne',
'editold' => 'bıvurne',
'viewsourceold' => 'çımey cı bıvinê',
'editlink' => 'bıvurne',
'viewsourcelink' => 'Çımi bıvin',
'editsectionhint' => 'Leteyo ke bıvuriyo: $1',
'toc' => 'Sernameyê meselan',
'showtoc' => 'bımocne',
'hidetoc' => 'bınımne',
'collapsible-collapse' => 'Kılm ke',
'collapsible-expand' => 'Hera ke',
'thisisdeleted' => 'Bıvêne ya zi $1 peyser bia?',
'viewdeleted' => '$1 bıvêne?',
'restorelink' => '{{PLURAL:$1|jew vurnayış besteriya|$1 vurnayışi besteriyaye}}',
'feedlinks' => 'Warikerdış:',
'feed-invalid' => 'Qeydey cıresnayışê  beğşi nêvêreno.',
'feed-unavailable' => 'Cıresnayışê şebekey çıniyê',
'site-rss-feed' => '$1 Cıresnayışê RSSi',
'site-atom-feed' => '$1 Cıresnayışê atomi',
'page-rss-feed' => '"$1" Cıresnayışê RSSi',
'page-atom-feed' => '"$1" Cıresnayışê atomi',
'feed-atom' => 'Atom',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (çınîya)',
'sort-descending' => 'Ratnayışê qemeyayışi',
'sort-ascending' => 'Ratnayışê Zeydnayışi',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Wesiqe',
'nstab-user' => 'Pera Karberi',
'nstab-media' => 'Pera Medya',
'nstab-special' => 'Pera bağsi',
'nstab-project' => 'Pera proci',
'nstab-image' => 'Dosya',
'nstab-mediawiki' => 'Mesac',
'nstab-template' => 'Tewre',
'nstab-help' => 'Pela peşti',
'nstab-category' => 'Kategori',

# Main script and global functions
'nosuchaction' => 'Fealiyeto wınasi çıniyo',
'nosuchactiontext' => 'URL ra kar qebul nêbı.
Şıma belka URL şaş nuşt, ya zi gıreyi şaş ra ameyi.
Keyepelê {{SITENAME}} eşkeno xeta eşkera bıkero.',
'nosuchspecialpage' => 'Pela xasa wınasiye çıniya',
'nospecialpagetext' => '<strong>To yew pela xasa nêvêrdiye waşte.</strong>

Seba lista pelanê xasanê vêrdeyan reca kena: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Ğeta',
'databaseerror' => 'Ğetay ardoği',
'databaseerror-query' => 'Perskerdış:$1',
'databaseerror-function' => 'Fonksiyon: $1',
'databaseerror-error' => 'Xırab: $1',
'laggedslavemode' => 'Diqet: Pel de newe vıraşteyi belka çini .',
'readonly' => 'database kılit biyo',
'enterlockreason' => 'Database kılit biyo',
'readonlytext' => 'Qey pawıtış ri yew sebeb vace. Texmini yew tarix vace şıma key pawıtış wedarneni:  $1',
'missing-article' => "Banqa, pela be namê \"\$1\" \$2 ke gunê bıbo, nêdiye.

Ena belki seba yew vurnayışo kıhan ya zi tarixê gırê yew pele esteriya.

Eke wına niyo, belki ''software''i de yew xeta esta.
Kerem kerê, naye be namey ''URL''i yew [[Special:ListUsers/sysop|karber]]i ra vacê.",
'missingarticle-rev' => '(rewizyon#: $1)',
'missingarticle-diff' => '(Ferq: $1, $2)',
'readonly_lag' => 'Daegeh (database) otomatikmen kılit bi, sureo ke  daegehê bınêni resay daegehê serêni.',
'internalerror' => 'Xeta zerreki',
'internalerror_info' => 'Xeta zerreki: $1',
'fileappenderrorread' => 'Surey pırakerdene de "$1" nêşa bıwaniyo.',
'fileappenderror' => 'Dosyayê "$1" têyor nêbeno dosyayê "$2" ri.',
'filecopyerror' => '"$1" qaydê na "$2" dosya nêbeno.',
'filerenameerror' => 'nameyê "$1" dosya nêvuriya no name "$2" ri.',
'filedeleteerror' => 'Na "$1" dosya hewn a nêşi .',
'directorycreateerror' => '"$1" rêzkiyê ey nêvırazya',
'filenotfound' => 'Na "$1" dosya nêasena.',
'fileexistserror' => '"$1" nênusiya dosya re çunke : na dosya ca ra esta',
'unexpected' => 'Endek texmin nêbeni: "$1"="$2".',
'formerror' => 'Xeta: Form nêerşawiyeno',
'badarticleerror' => 'Kar  ke şıma kenê, qebul nêbi.',
'cannotdelete' => 'Pel  "$1" o ke şıma nişane kerd hewn a neşı.
Belka yewna ten kerdo hewn a.',
'cannotdelete-title' => 'şıma  "$1" nê şenê besternê.',
'delete-hook-aborted' => 'Esterıtışi terefê çengeli ra ibtal bi.
Qet tesrih beyan nêbi.',
'badtitle' => 'Sernameo xırabın',
'badtitletext' => 'Sernamey pela ke şıma waşt, nêvêrd, thalo/vengo ya ki zıwano miyanêno ğelet gırêdae ya ki sernamey wiki.
Beno ke, tede yew ya zi zêdê işareti estê ke sernaman de nêxebetiyenê.',
'perfcached' => 'Datay cı ver hazır biye. No semedê ra nıkayin niyo! tewr zaf {{PLURAL:$1|netice|$1 netice}} debêno de',
'perfcachedts' => 'Cêr de malumatê nımıteyi esti, demdê newe kerdışo peyın: $1. Tewr zaf {{PLURAL:$4|netice|$4 neticey cı}} debyayo de',
'querypage-no-updates' => 'Nıka newe kerdış nêbeno. no datayi ca de newe nêbeni .',
'wrong_wfQuery_params' => 'wfQuery() parametreyo şaş<br />
Fonksiyon: $1<br />
Perse: $2',
'viewsource' => 'Çımi bıvin',
'viewsource-title' => "Cı geyrayışê $1'i bıvin",
'actionthrottled' => 'Kerden peysnaya',
'actionthrottledtext' => 'Riyê tedbirê anti-spami ra,  wextê do kılmek de şıma nê fealiyeti nêşkenê zaf zêde bıkerê, şıma ki no hedi viyarna ra.
Çend deqey ra tepeya reyna bıcerrebnên.',
'protectedpagetext' => 'Na per qey nêvuriyayiş ho pawyeno ya zi kerdışe bini.',
'viewsourcetext' => 'To şikinay çımey na pele bıvêne u kopya kerê:',
'viewyourtext' => "Na pela '''Vurnayışê ke kerdê''' re şıma şenê kopya kerê:",
'protectedinterface' => 'Na pela qandê nusnerin destegê verri dana u kes xırabin nêqero deye kerda kılit.',
'editinginterface' => "'''İqaz:''' Şıma hayo yew pela ke seba nuşteyê meqalanê cayanê bırnayeyan dana, vurnenê.
Vurnayışê na pele karberanê binan rê serpela karberi kena ke bımocno.
Seba çarnayışi, yardımê [//translatewiki.net/wiki/Main_Page?setlang=diq translatewiki.net]i ra procêdoşkerdışi rê diqet kerên.",
'cascadeprotected' => 'No pel de vurnayiş qedexe biyo, çunke şıma tuşa "kademeyın" aqtif kerdo u no {{PLURAL:$1|pelo|pelo}} pawıteyo de xebıtyeno:
$2',
'namespaceprotected' => "No '''$1''' ca de icazetê şıma çino şıma pel rêz keri.",
'customcssprotected' => 'Mısadeyê şıma çıniyo ke na pela CSSi bıvurnên, çıke na pela xısusiye eyaranê karberan muhtewa kena.',
'customjsprotected' => 'Mısadeyê şıma çıniyo ke na pela Java Scripti bıvurnên, çıke na pela xısusiye eyaranê karberan muhtewa kena.',
'mycustomcssprotected' => "Na pera CSS'i re tenya idarekari şene bıvurne",
'mycustomjsprotected' => "Na pera JavaScript'i re tenya idarekari şene bıvurne",
'myprivateinfoprotected' => 'Ğısusi malumatana ğo timar kerdışire icazeta şıma çıniya.',
'mypreferencesprotected' => 'Terciha timar kerdışire icazeta şıam çıniya.',
'ns-specialprotected' => 'Pelê xısusiy nênê vurnayış.',
'titleprotected' => 'Eno [[User:$1|$1]] zerreyê ena peli nişeno vuriye.
Sebeb: "\'\'$2\'\'".',
'filereadonlyerror' => 'Dosyay vurnayışê "$1" nê abê no lakin depoy dosya da "$2" mod dê  salt wendi deyo.

Xızmetkarê  kılitkerdışi wa bewni ro enay wa çımra ravyarno: "$3".',
'invalidtitle-knownnamespace' => 'Canemey "$2" u metnê "$3" xırabo',
'invalidtitle-unknownnamespace' => 'Sernameye nêşınasiya yana amraiya canameyo  $1 u metno "$2" xırab',
'exception-nologin' => 'Şıma cıkewtış nêvıraşto',
'exception-nologin-text' => 'Na pera ya zi na karkerdışi de  na wiki de [[Special:Userlogin|cıkewtış]] icab keno.',

# Virus scanner
'virus-badscanner' => "Eyaro şaş: no virus-cıgerayox nêzanyeno: ''$1''",
'virus-scanfailed' => 'cıgerayiş tamam nêbı (kod $1)',
'virus-unknownscanner' => 'antiviruso ke nêzanyeno:',

# Login and logout pages
'logouttext' => "'''Şıma hesabra newke vicyay.'''

Wexta ke verhafızayê cıgerayoxê şıma pak beno no benate de taye peli de hesabe şıma akerde aseno.",
'welcomeuser' => 'Xeyr amey, $1!',
'welcomecreation-msg' => 'Hesabê şıma abiyo.
[[Special:Preferences|{{SITENAME}} vurnayişê tercihanê xo]], xo vir ra mekere.',
'yourname' => 'Nameyê karberi:',
'userlogin-yourname' => 'Nameyê karberi',
'userlogin-yourname-ph' => 'Namey ğoyé karberi cı kewe',
'createacct-another-username-ph' => 'Namey karberi de fi',
'yourpassword' => 'Parola',
'userlogin-yourpassword' => 'Parola',
'userlogin-yourpassword-ph' => 'Parolaya xo cıkewe',
'createacct-yourpassword-ph' => 'Parola cıkewe',
'yourpasswordagain' => 'Parola reyna bınusne:',
'createacct-yourpasswordagain' => 'Parola tesdiq ke',
'createacct-yourpasswordagain-ph' => 'Parola fına cıkewe',
'remembermypassword' => 'Parola mı nê cıgeyrayoği de bia xo viri (seba tewr zêde $1 {{PLURAL:$1|roce|rocan}})',
'userlogin-remembermypassword' => 'Mı biya xo viri',
'userlogin-signwithsecure' => 'Ebe teqdimkerê asayişın cıkewe',
'yourdomainname' => 'Nameyê şıma yo meydani',
'password-change-forbidden' => 'Şıma na wiki de nêşenê parola bıvurnê.',
'externaldberror' => 'Ya database de xeta esta ya zi heqê şıma çino şıma no hesab bıvurni.',
'login' => 'Cı kewe',
'nav-login-createaccount' => 'Dekew de / hesab vıraze',
'loginprompt' => "{{SITENAME}} dı ronıştış akerdışi rê ''çerezan'' aktiv kerdış icab keno.",
'userlogin' => 'Cı kewe / hesab vıraze',
'userloginnocreate' => 'Cı kewe',
'logout' => 'Bıveciye',
'userlogout' => 'Bıveciye',
'notloggedin' => 'Şıma cıkewtış nêvıraşto',
'userlogin-noaccount' => 'Hesabê şıma çıniyo?',
'userlogin-joinproject' => 'Cıkewe {{SITENAME}}',
'nologin' => "Hesabê şıma çıniyo? '''$1'''.",
'nologinlink' => 'Yew hesab ake',
'createaccount' => 'Hesab vıraze',
'gotaccount' => "Hesabê şıma esto? '''$1'''.",
'gotaccountlink' => 'Cı kewe',
'userlogin-resetlink' => 'Melumatê cıkewtışi xo vira kerdê?',
'userlogin-resetpassword-link' => 'Parola xo kerda xo vira?',
'helplogin-url' => 'Help:Qeydbiyayış',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Desteg be qeydbiyayış ra]]',
'userlogin-createanother' => 'Zewbi hesab vıraz',
'createacct-join' => 'Cêr melumatê xo cı ke',
'createacct-emailrequired' => 'Adresa e-postey',
'createacct-emailoptional' => 'Adresa e-postey (mecburi niya)',
'createacct-email-ph' => 'Adresa e-posteyê xo cıkewe',
'createacct-another-email-ph' => 'Adresa e-posta de fi',
'createaccountmail' => 'Yew parolaya rastameyiya ravêrdiye bıgurene û parola ena adresa e-postey rê bırışe',
'createacct-realname' => 'Nameyo raştıkên (mecburi niyo)',
'createaccountreason' => 'Sebeb:',
'createacct-reason' => 'Sebeb',
'createacct-reason-ph' => 'Şımaye çı xo re zewbi hesab vırazeni?',
'createacct-captcha' => 'Qontrolê asayişi',
'createacct-imgcaptcha-ph' => 'Nuşteyo ke cor aseno ey cı ke',
'createacct-submit' => 'Hesabê xo vıraze',
'createacct-another-submit' => 'Zewbi hesab vıraz',
'createacct-benefit-heading' => '{{SITENAME}} meş de merduman şi',
'createacct-benefit-body1' => '{{PLURAL:$1|vurnayış|vurnayışi}}',
'createacct-benefit-body2' => '{{PLURAL:$1|pele|peli}}',
'createacct-benefit-body3' => '{{PLURAL:$1|iştıraqkerdoğo nıkayên|iştıraqkerdoğê nıkayêni}}',
'badretype' => 'Parolayê ke şıma nuşti yewbini nêtepışneni.',
'userexists' => 'Jewna karber enê nami karneno.
Mara reca xorê jewna name bınusnê.',
'loginerror' => 'Xetayê hesab ekerdışi',
'createacct-error' => 'Xetaya vıraştışê hesabi',
'createaccounterror' => 'Hesab nêvırazyeno: $1',
'nocookiesnew' => 'Hesabê karberi vıraziya, labelê şıma nêşay cı kewê.
Semedê akerdışê hesabi çerezê {{SITENAME}}i gurêniyenê.
Şıma çerezi qapan kerdi.
Ravêri inan akerê, dıma be name u parola şımawa newiye cı kewê.',
'nocookieslogin' => 'Semedê akerdışê hesabi çerezê {{SITENAME}}i gurêniyenê.
Şıma çerezi qapan kerdi.
Ravêri inan akerê u reyna bıcerrebnê.',
'nocookiesfornew' => 'Hesabê karberi nêvıraziya, MA nêzana sebebê cı kotirawo.
Akerdış dê çerezarê xo emel bê uena pela fına barkerê.',
'nocookiesforlogin' => '{{int:nocookieslogin}}',
'noname' => 'Yew nameyo maqbul bınuse.',
'loginsuccesstitle' => 'Hesab abıya',
'loginsuccess' => "'''{{SITENAME}} dı name dê \"\$1\" şıma hesab akerdo.'''",
'nosuchuser' => 'Ebe namey "$1"i yew karber çıniyo.
Nuştışê namanê karberan de herfa pil u qıce rê diqet kerên.
Nuştışê xo qonrol kerên, ya zi [[Special:UserLogin/signup|yew hesabo newe akerên]].',
'nosuchusershort' => 'No "$1" name de yew ten çino. Kontrolê nuştışi bıkere.',
'nouserspecified' => 'Şıma gani yew name bıde.',
'login-userblocked' => 'No karber/na karbere blokekerdeyo/blokekerdiya. Cıkewtışi rê musade çıniyo.',
'wrongpassword' => 'Parola ğeleta. Rêna / fına bıcerrebne .',
'wrongpasswordempty' => 'Parola tola, venga. tekrar bınuse.',
'passwordtooshort' => 'Derganiya parola wa tewr tayn {{PLURAL:$1|1 karakter|$1 karakteran}} dı bo.',
'password-name-match' => 'Parola u nameyê şıma gani zeypê (seypê) nêbo.',
'password-login-forbidden' => 'No namey karberi u parola karkerdışê cı  kerdo xırab.',
'mailmypassword' => 'E-mail sera parola newiye bırışe',
'passwordremindertitle' => "Qandê {{SITENAME}}'i idareten parolaya newiye",
'passwordremindertext' => 'Yew ten (muhtemelen, şıma na aderesê IP ra $1 ) {{SITENAME}} ($4) newe yew parola waşt. "$2" no name ri emanet yew parola vıraziya "$3". Eke na şıma waşta, hesabê xo akere u newe yew parola bıvıraze. Muddetê parolayê şıma yo emanet {{PLURAL:$5|1 roc|$5 roci}}.

Eke vurnayişê parolayi, şıma nêwaşt ya zi parolayê şıma ameyo şıma vir u şıma hini qayil nşye parolayê xo bıvurni; no mesaj peygoş kere u bıewne gureyê xo.',
'noemail' => '"$1" No name de yew e-posta çiniyo.',
'noemailcreate' => 'Şıma gani yew parolayo meqbul peda bıkeri',
'passwordsent' => '"$1" No name de yew e-posta erşawiya (ruşya). hesabê xo, şıma wext mesaj gırewt u çax akere.',
'blocked-mailpassword' => 'Cıkewetışê na keyepel de şıma qedexe biye, ey ra newe yew şifre nêerşawyeno.',
'eauthentsent' => 'Adresok şıma qeyd kerdo wıcayré e-posta rışiyé.
Hetana şıma ne e-posta néwweyniyé, şımaé zewbi e-posta do nérışiyo.',
'throttled-mailpassword' => 'Eyarkerdışê parola xora zerreyê {{PLURAL:$1|yew saete|$1 saetan}} erşawiya.
Seba xırabgurenayışê xızmete ra, her {{PLURAL:$1|yew saete|$1 saetan}} de rey tenya yew eyarkerdışê parola erşawiyeno.',
'mailerror' => 'Erşawıtışe xetayê e-posta: $1',
'acct_creation_throttle_hit' => 'Yew ten IP adresê şıma xebıtnayo u kewto no wiki, roco peyin de {{PLURAL:$1|1 hesab|$1 hesab}} vıraşto.
xulasa ney kesê ke IP adresê şıma xebıtneni hini nêeşkeni ney ra zêdêr hesab akeri.',
'emailauthenticated' => 'E-postay şıma $2 sehat $3 dı biya araşt',
'emailnotauthenticated' => 'Adresa e-pota da şıma qebul nébiya.
Qandé céréna şımaré teba do nérışiyo.',
'noemailprefs' => 'Hesab biyo a.',
'emailconfirmlink' => 'E-postayê xo araşt kerê',
'invalidemailaddress' => 'No format de nuştışê e-postayi qebul nêbeno. Yew formato meqbul de adresê e-posta bınuse ya zi veng bıverde.',
'cannotchangeemail' => 'E-postay hesabi ena wiki sera nêvurneyêno.',
'emaildisabled' => 'Na site ra e-posta nêrışêno.',
'accountcreated' => 'Hesab iycat bı',
'accountcreatedtext' => 'Qandê [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|talk]]) hesabê karberi vıraziyayo.',
'createaccount-title' => 'Qandé  {{SITENAME}} hesabé karberi iycat kerdış',
'createaccount-text' => 'Kesê, be e-posteyê şıma ra {{SITENAME}} ($4) de, ebe nameyê "$2" u parola "$3" ra yew hesab vıraşto.
Şıma gani cı kewê u parola xo nıka bıvurnê.',
'usernamehasherror' => 'Namey karberi de karakteri gani têmiyan ra mebê',
'login-throttled' => 'Demekê $1 cıwa ver de şıma zah teşebbusê hesab akerdış kerd.
Bıne vındere u newe ra dest pê bıkere.',
'login-abort-generic' => 'Dekewtışê şıma xırabo-terkneyayo',
'loginlanguagelabel' => 'Zıwan: $1',
'suspicious-userlogout' => 'Waştişê tu ya veciyayişi kebul nibiya cunki ihtimal o ke waştiş yew browser ya zi proksiyê heripiyaye ra ameya.',

# Email sending
'php-mail-error-unknown' => "PHP's mail() fonksiyoni de xırabin vıcyê.",
'user-mail-no-addy' => 'Bê E-posta kerd ju e-posta bırşo cırê.',
'user-mail-no-body' => 'Veng yana vireyo ke makbul niyo eya xebtina.',

# Change password dialog
'resetpass' => 'Parola bıvurne',
'resetpass_announce' => 'Şıma pê yew parolayê muweqqet hesab kerd a, qey qedyayişe dekewtış newe yew parola bınuse:',
'resetpass_text' => 'Parolayê hesab bıvurn',
'resetpass_header' => 'Parola hesabi bıvurne',
'oldpassword' => 'Parola kıhane:',
'newpassword' => 'Parola newiye:',
'retypenew' => 'Parola newiye tekrar ke:',
'resetpass_submit' => 'Parola eyar kere u newe ra dekewe',
'changepassword-success' => 'Parola şıma be serkewtış vuriye!',
'resetpass_forbidden' => 'parolayi nêvuryayi',
'resetpass-no-info' => 'şıma gani hesab akere u hona bıeşke bırese cı',
'resetpass-submit-loggedin' => 'Parola bıvurne',
'resetpass-submit-cancel' => 'Bıterkne',
'resetpass-wrong-oldpass' => 'parolayo parola maqbul niyo.
şıma ya parolaye xo vurnayo ya zi parolayo muwaqqat waşto.',
'resetpass-temp-password' => 'parolayo muweqet:',
'resetpass-abort-generic' => 'Parola vurnayış jew derganey ra tepya ibtal biyo',

# Special:PasswordReset
'passwordreset' => 'Parola reset ke',
'passwordreset-text-one' => 'Na form de parola reset kerdış temamiye',
'passwordreset-text-many' => '{{PLURAL:$1|Qande parola reset kerdışi cayanra taynın pırkeri}}',
'passwordreset-legend' => 'Parola reset ke',
'passwordreset-disabled' => 'Parola reset kerdış ena viki sera qefılneyayo.',
'passwordreset-emaildisabled' => 'Na wikid hısusiyeté e-posta dewera vıcyayé',
'passwordreset-username' => 'Nameyê karberi:',
'passwordreset-domain' => 'Domain:',
'passwordreset-capture' => 'neticey e-postay bımocne?',
'passwordreset-capture-help' => 'Şıma na dorek morkerê se, e-posta (idareten eposta ya) şıma rê yana karbera rê rışêno.',
'passwordreset-email' => 'Adresa e-postey:',
'passwordreset-emailtitle' => 'Hesab timarê {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Jeweri, {{SITENAME}} ra (ma heta şımayê, $1 IP adresi ra) ($4) teferuatê hesabdê şıma  va wa biyaro xo viri. Karbero ke cêrdeyo {{PLURAL:$3|hesaba|eno hesaba}} ena e-posta adresiya aleqey cı esto:

$2

{{PLURAL:$3|ena parola idaretena|ena parola idareten}} {{PLURAL:$5|jew roc|$5  roca}}rêya.
Ena parolaya deqewe de u xorê ju parolaya newi bıweçine. Parolaya şıma emaya şıma viri se  yana  ena e-posta şıma nê weştase u şıma qayıl niye parolaya xo bıvurnese, ena mesacer peygoş bıkerê.',
'passwordreset-emailtext-user' => '$1 enê karberi, {{SITENAME}}  ra ($4) teferuatê hesab dê şıma  va wa biyaro xo viri. Karbero ke cêrdeyo {{PLURAL:$3|hesaba|eno hesaba}} ena e-posta adresiya aleqey cı esto:

$2

{{PLURAL:$3|ena parola idaretena|ena parola idareten}} {{PLURAL:$5|jew roc|$5  roca}}rêya.
Ena parolaya deqewe de u xorê ju parolaya newi bıweçine. Parolaya şıma emaya şıma viri se  yana  ena e-posta şıma nê weştase u şıma qayıl niye parolaya xo bıvurnese, ena mesacer peygoş bıkerê.',
'passwordreset-emailelement' => 'Namey karberi: $1
Parola vêrdiye: $2',
'passwordreset-emailsent' => 'Yew e-posteyê esterıtışê parola rışiya.',
'passwordreset-emailsent-capture' => 'Yew e-posteyê esterıtışê parolayo ke rışiya, no cêr mocniyayo.',
'passwordreset-emailerror-capture' => 'Yew e-posteyê esterıtışê parolayo ke rışiya, no cêr mocniyayo, ema {{GENDER:$2|karber}}i rê rıştış de mıwefeq nêbi: $1',

# Special:ChangeEmail
'changeemail' => 'E-posta adresa xo bıvurnê',
'changeemail-header' => 'E-posya adresta hesabdê xo bıvurnê',
'changeemail-text' => 'Şıma qayılê ke e-postay xo bıvurnê se enê formi pırkerê. Qandê araşt kerdışi zi parolay xo şıma de bınusnê',
'changeemail-no-info' => 'Resayışê ena pela rê Dekewtış icab keno.',
'changeemail-oldemail' => 'E-postay şımaya newki:',
'changeemail-newemail' => 'E-posta adresiyo newe:',
'changeemail-none' => '(Çıno)',
'changeemail-password' => 'Parolaya şımaya {{SITENAME}}i:',
'changeemail-submit' => 'E-postay xo bıvurne',
'changeemail-cancel' => 'Bıterkne',

# Special:ResetTokens
'resettokens' => 'Reset fi ye',
'resettokens-no-tokens' => 'Ena reset nefina',
'resettokens-legend' => 'Reset fi ye',
'resettokens-tokens' => 'Beli kerdeni:',
'resettokens-token-label' => '$1 (weziyeta newki: $2)',
'resettokens-done' => 'Reset fi',
'resettokens-resetbutton' => 'Reset fiyayış weçin',

# Edit page toolbar
'bold_sample' => 'Metno qalın',
'bold_tip' => 'Metno qalın',
'italic_sample' => 'Metno vırandere',
'italic_tip' => 'Metno vırandere',
'link_sample' => 'Namey gırê',
'link_tip' => 'Gıreyê miyani',
'extlink_sample' => 'http://www.example.com şınasiya adresi',
'extlink_tip' => 'Greyê teberi (adresi vero http:// dekerê de)',
'headline_sample' => 'nuştey xeta seri',
'headline_tip' => '2.ki sewiye sername',
'nowiki_sample' => 'Non-format nuşte itiya ra bıerz',
'nowiki_tip' => 'Format kerdışê wiki bıterknê',
'image_sample' => 'Misal resim.jpg',
'image_tip' => 'Dosyaya gumın',
'media_sample' => 'misal.jpg',
'media_tip' => 'Gıreyê dosya',
'sig_tip' => 'İmza u wext',
'hr_tip' => 'Çıxiza dimdayi (hend akar mefiye)',

# Edit pages
'summary' => "<font style=\"color:Blue\">'''Xulasa:'''</font>",
'subject' => 'Mewzu/sernuşte:',
'minoredit' => "'''Eno vurnayışo de qıckeko'''",
'watchthis' => "'''Ena pele seyr ke'''",
'savearticle' => 'Pele qeyd ke',
'preview' => 'Verqayt',
'showpreview' => 'Verqayti bımocne',
'showlivepreview' => 'Verqayto cıwın',
'showdiff' => 'Vurnayışan bımocne',
'anoneditwarning' => 'Teme!: Şıma bı hesabê xo nıkewtê cı. Hurêndiya namey şıma dı IP-adresa şıma qeyd bena u asena.',
'anonpreviewwarning' => "''Ti hama nicikewte. Qeyd kerdiş zerre tarixê pele de adresê IP yê tu keyd keno.''",
'missingsummary' => "'''DİQET:''' Şıma kılmnuşte nıkerd.
Eke şıma reyna butonê qaydker ser a ne pel bê kılmnuşte qayd beno.",
'missingcommenttext' => 'Cêr de yew xulasa binuse.',
'missingcommentheader' => "Vir ardoğ:''' Şıma qey na mesela sername nuşte nênuşt.
Eke şıma reyna \"{{int:savearticle}}\" qayd ker bıtıkni pel bê sername qayd beno.",
'summary-preview' => 'Verqeydê qıssa:',
'subject-preview' => 'Mesela/Sername  verqayd seyr kerdış:',
'blockedtitle' => 'Karber (eza) blok biyo',
'blockedtext' => '\'\'\'No name ya zi na IP adresê şıma ri musade çino.\'\'\'

Oyo ke musade nêkeno: $1.<br />
Sebebê musade nêdayiş: \'\'$2\'\'.

* Dest pê kerdışê musade nêdayiş: $8
* Qedyayişê musade nêdayiş: $6
* Oyo ke cı rê musade nêdeyêno: $7

Eke şıma sebebê musade nêdayiş ri itiraz keni, $1 de ya zi yewna [[{{MediaWiki:Grouppage-sysop}}|xızmetkar]] de şıma eşkeni na mesela de qıse bıkeri. [[Special:Preferences|Tercihê]] eke şıma na qısme de pey yew e-postayo raşt nêkewte cı, şıma xususiyetê "Karber ri e-posta bırışê" ra nêeşkeni istifade bıkeri, eke şıma tercihanê xo bıerz zerreyê e-postayê xo şıma hıni şenê ep-posta bırışê.
<br />IP adresê şıma yo nıkayın $3, numreya musade nêdayiş #$5.
<br />Eke şıma qayile yew xızmkar çiko bıpers, no malumatan not bıkere ney şıma rê lazım beni.',
'autoblockedtext' => 'IP adresê şıma otomotikmen kerda kılit, çıkı $1 verniya nê hesabi grota.
Sebebê cı zi:

:\'\'$2\'\'

* Dest pê kerdışê verni grotışi: $8
* Qedyayişê verni grotışi: $6
* Qayile ke bloqe bıbo: $7

Şıma qayile qey weri kewtışê na mesela,  $1 ya na [[{{MediaWiki:Grouppage-sysop}}|serkaran ra]] yewi ra şenê irtibat kewê.

Not, [[Special:Preferences|Tercihê karberi]] eke şıma yew e-postayo raşt nênuşt se şıma nêşenê na xususiyet ra "karber rê e-posta bırışê" istifade bıkeri.

IP adresiya şıma yo nıkayên $3 u ID şıma yo ke musade nêdaye #$5. Eke şıma yew tehqiqat vırazeni malumatê corênan xo vira mekerê.',
'blockednoreason' => 'sebeb nidaniyo',
'whitelistedittext' => 'Qandê vurnayış kerdışi rê $1.',
'confirmedittext' => 'Eka ti wazene binusi, adresê xo e-maili confirme bike.
Adresê xo e-maili [[Special:Preferences|user preferences]] de confirme bike.',
'nosuchsectiontitle' => 'Eno qısım çıniyo',
'nosuchsectiontext' => 'To waşt ke yew qısım kewê, oyo ke çıniyo.
Heta ke werte de qısım çıniyo, ca çıniyo ke tı raştkerdışê xo qeyd bıkerê.',
'loginreqtitle' => 'Cıkewtış lazımo',
'loginreqlink' => 'cı kewe',
'loginreqpagetext' => 'Eka ti wazeno peleyanê bini bivini, ti gani $1.',
'accmailtitle' => 'Paralo şirawiyayo.',
'accmailtext' => "[[User talk:$1|$1]] parolayo ke raşt ameyo şırawiyo na adres $2.

Qey na hesabê newe parola, cıkewtış dıma şıma eşkeni na qısım de ''[[Special:ChangePassword|parola bıvurn]]'' bıvurni.",
'newarticle' => '(Newe)',
'newarticletext' => "Ena pele, database ma de hona çiniyo.
Eka tı wazene yew bıvırazi, bınê eno nuşte de yew quti esto u uca de bınuse (bıvinin [[{{MediaWiki:Helppage}}|help page]] qe informasyonê zafyeri).
Eka tı ita semed yew heta ra amey, ser gocekê '''back'''i klik bıkin.",
'anontalkpagetext' => "----''No pel, pel o karbero hesab a nêkerdeyan o, ya zi karbero hesab akerdeyan o labele pê hesabê xo nêkewto de. No sebeb ra ma IP adres şuxulneni û ney IP adresan herkes eşkeno bıvino. Eke şıma qayil niye ina bo xo ri [[Special:UserLogin/signup|yew hesab bıvıraze]] veyaxut [[Special:UserLogin|hesab akere]].''",
'noarticletext' => 'Ena pele de hewna theba çıniyo.
Tı şenay zerreyê pelanê binan de [[Special:Search/{{PAGENAME}}|seba sernamey ena pele cı geyre]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cıkewtışê aidi rê cı geyre],
ya zi [{{fullurl:{{FULLPAGENAME}}|action=edit}} ena pele bıvurne]</span>.',
'noarticletext-nopermission' => 'Na pela dı eno metin enewke vengo
Na sernuşteya şıma [[Special:Search/{{PAGENAME}}|pelanê binan de şeni bıgeyri]]
ya zi <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} itara şeni bıgeyri cı].</span> feqet şıma nişeni biizın teba bıkeri.',
'missing-revision' => 'Rewizyonê name dê pela da #$1 "{{PAGENAME}}" dı çıniyo.

No normal de tarix dê pelanê besterneyan dı ena xırabin asena.
Detayê besternayışi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tiya dı] aseno.',
'userpage-userdoesnotexist' => 'Hesabê karberi "<nowiki>$1</nowiki>" qeyd nêbiyo.
Kerem ke, tı ke wazenay na pele bafernê/bıvurnê, qontrol ke.',
'userpage-userdoesnotexist-view' => 'Hesabê karberi "$1" qeyd nêbiyo.',
'blocked-notice-logextract' => 'No karber/na karbere emanet blokekerdeyo/blokekediya.
Cıkewtışo tewr peyêno ke bloke biyo, cêr seba referansi belikerdeyo:',
'clearyourcache' => "'''Not:''' Bahde sazkerdışi, gani hafızayê cı gerayoğ pak bıbo.
*'''Mozilla / Firefox / Safari:''' ''Shift'' ri gıştê şıma ser nayi pel newe ra bar kere yana zi''Ctrl-Shift-R'' bıkere u (qey Apple Mac ''Cmd-Shift-R'');,
*'''IE:''' ''Ctrl-F5' piya pıploxnê ke wa newe bo', 
* '''Operar:'''hacetan ra şı rê →tercihan ra bıvurnen",
'usercssyoucanpreview' => "'''Yardim:''' Ser \"{{int:showpreview}}\" sima eskeni CSSe newe test bikeri.",
'userjsyoucanpreview' => "'''Yardim:''' Ser \"{{int:showpreview}}\" sima eskeni CSSe newe test bikeri.",
'usercsspreview' => "'''şıma tena verqaydê dosyayê CSS vineni.''' '''Dosyayê Karberi CSS hema qayd nebiyo!'''",
'userjspreview' => "'''şıma tena test keni ya ziverqayn seyr keni - karberê JavaScript'i hema qayd nebiyo.'''",
'sitecsspreview' => "'''Şımayê enewke tenya verqaytê dosya da CSS vınenê.''' 
'''Hewna qayd nêbı!'''",
'sitejspreview' => "'''Şımayê enewke tenya verqaytê kodê dosya da JavaScriptê karberi vınenê.''' 
'''hewna qayd nebı!'''",
'userinvalidcssjstitle' => "'''Teme:''' Mewzuyê \"\$1\" çıniyo.
Dosyanê be namey .css u .js'i de herfa werdiye bıgurêne, mesela herında {{ns:user}}:Foo/Vector.css'i de {{ns:user}}:Foo/vector.css bınuse.",
'updated' => '(Rozeneya)',
'note' => "'''Not:'''",
'previewnote' => "'''Xo vira mekerê ke ena yew verqayta.'''
Vurnayışê şıma hona qeyd nêbiyo!",
'continue-editing' => 'Şo herunda vurnayışi',
'previewconflict' => 'No seyrkerdışê verqaydi serê qutiyê nuşte tezim kerdış de yo, eke şıma qayile vurnayişê maddeyi seyino bıvini, no mocneno şıma.',
'session_fail_preview' => 'Ma ef kere. Vindibiyayişê tayê datay ra a kerdışê hesabê şıma de ma vurnayişê şıma qayd nêkerd. Newe ra tesel (cereb) bıkere. Eke no qayde zi nêbo, [[Special:UserLogout|hesabê xo bıqefelne]] u newera a kere.',
'session_fail_preview_html' => "'''Ma meluli! Sebayê vindbiyayişê datasistemi ma vurnayişê şıma nêeşkeni qaydker.'''

''Çunke keyepelê {{SITENAME}} de raw HTML aqtifo, seyrkerdışê verqayd semedê galayê (alızyayiş) JavaScript ri nımıyayo.''

'''Eke no vurnayiş heqê şımayo, newe ra tesel bıker (bıcerebi). eke hona zi nêxebıtya, [[Special:UserLogout|vec]] newe ra hesabê xo aker.'''",
'token_suffix_mismatch' => "'''Vurnayişê şıma tepeya ameyo çunke qutiyê imla xerıbya.
Vurnayişê şıma qey nêxerepyayişê peli tepeya geyra a.
Eke şıma servisê proksi yo anonim şuxulneni sebebê ey noyo.'''",
'edit_form_incomplete' => "'''Qandê form dê vurnayışa tay wastera ma nêreşti; Vurnayışê ke şıma kerdê nêalızyayê, çım ra ravyarnê u fına bıcerbnê.'''",
'editing' => 'Şımayê <font style="color:red">$1</font> vurnenê',
'creating' => 'Pela <font style="color:blue">$1</font> vırazê',
'editingsection' => 'Per da $1 de şımaye kenê ke leti bıvurnê',
'editingcomment' => '$1 vuryeno (qısmo newe)',
'editconflict' => 'Vurnayişê ke yewbini nêtepışeni: $1',
'explainconflict' => "Wexta ke şıma pel vurneyene yewna ten zi pel vurna.
Nuşteyo corin; halê pelo nıkayin mocneno.
Vurnayişê şıma cêr de mocya ( musya).
Vurnayişanê peyinan şıma gani qayd bıkeri.
Wexta ke şıma butonê \"{{int:savearticle}}\" tıkna '''teyna''' nuşteyo corin qayd beno.",
'yourtext' => 'nuşteyê şıma',
'storedversion' => 'Nuşteyo qaydbiyaye',
'nonunicodebrowser' => "'''DİQET: Browserê şıma u unicode yewbini nêgeni. Qey izin dayişê vurnayişê pelan: Karakteri ke ASCII niyê; zerreyê qutiyê vurnayişi de kodi (cod) şiyes-şiyes aseni.'''",
'editingold' => "'''DİQET: Şıma pelo revizebiyaye de vurnayiş keni. Eke şıma qayd bıkeri vurnayişi ke pelo revizebiyayiş ra heta ewro biyê, pêroyê ey beni vini.'''",
'yourdiff' => 'pêverronayiş',
'copyrightwarning' => "'''Recayê ikazi:''' Sita da {{SITENAME}} ra iştıraqi pêro umışin da $2 zerredeyo (teferruata rê $1'i bıvinê).
İştıraqê şıma, şıma kayıl niyê ke yewna merdumi kerpeyina bıvurnê yana yewna caya ra vılakerê se, iştıraq mekewê.<br />
Fına zi qayılê ke  iştıraq kewê, Şıma qayılê kê şar vaco eno nuşte felani nuşnayo yana resmi meqeman ra zanayışê cı  u malumatê cı esto/ Xoseri cayan ra groti rê şıma qerenti danê. '''Tiya dı, şıma wêrê telifira mısade nêgroto se eserê cı tiya vıla mekerê! '''",
'copyrightwarning2' => 'Ney bızane ke nuşteyê ke şıma ruşneni (şaweni) keyepelê {{SITENAME}} herkes eşkeno nê nuşteyanê şıma ser kay bıkero. Eke şıma qayil niye kes bıvurno, nuşetyanê xo meerze ita. <br />
Wexta ke şıma nuşte zi erzeni ita; şıma gani taahhud bıde koti ra ardo (qey teferruati referans: $1).',
'longpageerror' => "'''Xırab: Dergeya nuşte dê şıma nezdi {{PLURAL:$1|kilobayto|$1 kilobayto}}, feqet {{PLURAL:$2|kilobayt|$2 kilobayt}} ra vêşiyo. Qeyd biyayişê cı nêbeno'''",
'readonlywarning' => "'''Diqet: Semedê mıqayti, database kılit biyo. No sebeb ra vurnayişê şıma qayd nêbeno. Nuşteyanê şıma yewna serkar eşkeno wedaro u pey ra şıma eşkeni reyna ita de qayd bıker'''

Serkar o ke kılit kerdo; no beyanat dayo: $1",
'protectedpagewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri:",
'semiprotectedpagewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, log bivini:",
'cascadeprotectedwarning' => "'''Diqet:''' Na pele kılit biya, tenya karberê idarekeri şenê ke naye bıvurnê, çıke na zerrey {{PLURAL:$1|na pela şipa-kılitkerdiye|nê pelanê şipanê-kılitkerdiyan}} dera:",
'titleprotectedwarning' => "'''Diqet: Na pele kılit biya, [[Special:ListGroupRights|heqê xususiy]] lazımê ke naye vırazê.'''
Loge peniye cor de este:",
'templatesused' => '{{PLURAL:$1|Şablon|Şabloni}} ke na pela de xebtênê:',
'templatesusedpreview' => '{{PLURAL:$1|Sablon|Sabloni}}  ke na verqayt de xebetnayê:',
'templatesusedsection' => '{{PLURAL:$1|Template|Templateyan}}  ke na qısım de xebetniyenê:',
'template-protected' => '(kılit biyo)',
'template-semiprotected' => '(nimey ena pele kılit biya)',
'hiddencategories' => 'Ena per de {{PLURAL:$1|1 kategoriyo nımıte|$1 kategoriyê nımıtey}} muhtewa benê:',
'edittools' => '<!-- Text here will be shown below edit and upload forms. -->',
'edittools-upload' => '-',
'nocreatetext' => '{{SITENAME}}, Pelê neweyi vıraştış re destur çino.
şıma eşkeni tepiya şêri u eke şıma qayd biyaye yê [[Special:UserLogin|şıma eşkeni hesab akeri]], eke niye [[Special:UserLogin|şıma eşkeni qayd bıbiy]].',
'nocreate-loggedin' => 'İcaze şıma çino şıma pelo newe akeri.',
'sectioneditnotsupported-title' => 'Destekê vurnayışiê qısımi çıniyo',
'sectioneditnotsupported-text' => 'Destekê vurnayışiê qısımi ena pela vurnayışi de çıniyo.',
'permissionserrors' => 'İdari xeta',
'permissionserrorstext' => 'Qey {{PLURAL:$1|sebebê|sebebê}} cêrini ra icazeyê şıma çin o:',
'permissionserrorstext-withaction' => '{{PLURAL:$1|Sebeba|Sebeb da}} cêri ra icazetê $2 çıniyo:',
'recreate-moveddeleted-warn' => "'''Hişyari: no pel o ke şıma vırazeni vere cû vırazyayo.'''

Diqet bıkeri no vurnayişê şıma re gerek esto:",
'moveddeleted-notice' => 'Na per besternyaya.
Qeydé  besternayışi uq hewadayışi cér dé deyayo.',
'log-fulllog' => 'Temamê rocaneyi bıvine',
'edit-hook-aborted' => 'Vurnayiş vınderiya.
Yew sebeb beyan nibı.',
'edit-gone-missing' => 'Pel rocanebiyaye niyo.
Hewna kerde aseno.',
'edit-conflict' => 'Vurnayişê pêverdiyaye .',
'edit-no-change' => 'Vurnayişê şıma qebul nêbı, çunke nuşte de yew vurnayiş n3evıraziya.',
'postedit-confirmation' => 'Vurnayış qeyd be',
'edit-already-exists' => 'Pelo newe nêvıraziyeno.
Pel ca ra esto.',
'defaultmessagetext' => 'Hesıbyaye metne mesaci',
'content-failed-to-parse' => 'Qandê madela $3 zereyê $1, $2 sero nêagozyayo',
'invalid-content-data' => 'Zerrey malumati nêravêrdeyo',
'content-not-allowed-here' => '"$1" sero per da [[$2]] rê mısade nêdeyêno',
'editwarning-warning' => 'ihtimal o ke wexta şıma peli ra bıveci, vurnayiş o ke şıma kerdo, hewna şiyêro .
eke şıma kewtê hesabê xo, no hişyari tercihanê xo ra şıma eşkeni "{{int:prefs-editing}}" bıvındarnî .',

# Content models
'content-model-wikitext' => 'wikimetin',
'content-model-text' => 'duz metin',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Hişyari: No pel de fonksiyoni zaf esti.

No $2 daweti ra gani tay bıbo, na hel {{PLURAL:$1|1 dawet esto|$1 dawet esto}}.',
'expensive-parserfunction-category' => 'Pelê ke tede zaf fonksiyoni esti',
'post-expand-template-inclusion-warning' => 'Tembe: zerreyê şabloni zaf gırdo.
Taye şabloni zerre pel de nêmociyayeni.',
'post-expand-template-inclusion-category' => 'Pelê şabloni ke hed ra veceyi',
'post-expand-template-argument-warning' => 'Tembe: No per de tewr tay yew şablono herayi esto.Nê vurnayeni ser çebyay',
'post-expand-template-argument-category' => 'Pelê ke şablonê eyi qebul niye',
'parser-template-loop-warning' => 'Gıreyê şabloni ca biyo: [[$1]]',
'parser-template-recursion-depth-warning' => 'limitê şablonê newekerdışi biyo de ($1)',
'language-converter-depth-warning' => 'xoritiya çarnekarê zıwanan viyarnê ra ($1)',
'node-count-exceeded-category' => 'Pela ra hetê kotya amardışê cı ravêrya',
'node-count-exceeded-warning' => 'Amariya pela ravêrya.',
'expansion-depth-exceeded-category' => 'Pela dı hetê canaya zoriya herayin ravêrya',
'expansion-depth-exceeded-warning' => 'Ravêriya pela xori herayêna',
'parser-unstrip-loop-warning' => 'Unstrip lete vineya',
'parser-unstrip-recursion-limit' => 'Sinorê limit dê qayış dê ($1) ravêrya',
'converter-manual-rule-error' => 'Rehberê zıwan açarnayışi dı xırabin tesbit biya',

# "Undo" feature
'undo-success' => 'No vurnayiş tepeye geryeno. pêverronayişêyê cêrıni kontrol bıkeri.',
'undo-failure' => 'Sebayê pêverameyişê vurnayişan karo tepêya gırewtış nêbı.',
'undo-norev' => 'Vurnayiş tepêya nêgeryeno çunke ya vere cû hewna biyo ya zi ca ra çino.',
'undo-summary' => 'Peysergırewtışê teshisê $1i be terefê [[Special:Contributions/$2|$2i]] ([[User talk:$2|Werênayış]])',
'undo-summary-username-hidden' => "Rewizyona veri $1'i hewada",

# Account creation failure
'cantcreateaccounttitle' => 'Nêşenay hesab rakerê',
'cantcreateaccount-text' => "Hesabvıraştışê na IP adrese ('''$1''') terefê [[User:$3|$3]] kılit biyo.

Sebebo ke terefê $3 ra diyao ''$2''",

# History pages
'viewpagelogs' => 'Heq dê ena perer qeydan bıvinên',
'nohistory' => 'Verê vurnayışanê na pele çıniyo.',
'currentrev' => 'Halo nıkayên',
'currentrev-asof' => 'Revizyonanê peniyan, tarixê $1',
'revisionasof' => 'Verziyonê roca $1ine',
'revision-info' => 'Vıraştena cı karber $2 ra rewizyona $1',
'previousrevision' => '← Çımraviyarnayışo kıhanêr',
'nextrevision' => 'Rewizyono newên →',
'currentrevisionlink' => 'Tewr halê rocaniye bımocne',
'cur' => 'ferq',
'next' => 'bahdoyên',
'last' => 'peyên',
'page_first' => 'verên',
'page_last' => 'peyên',
'histlegend' => "'''Ferqê weçinayışi:''' Qutiya versiyonan mor ke u  ''enter''i bıpıloxne ya zi makera cêrêne bıpıloxne.<br /> 
Lecant: '''({{int:cur}})''' = ferqê versiyonê peyêni,
'''({{int:last}})''' = ferqê versiyonê verêni, '''{{int:minoreditletter}}''' = vurnayışo werdi.",
'history-fieldset-title' => 'Bewni tarixer',
'history-show-deleted' => 'Tenya esterıt',
'histfirst' => 'Verênêr',
'histlast' => 'Peyênêr',
'historysize' => '({{PLURAL:$1|1 bayt|$1 bayti}})',
'historyempty' => '(veng)',

# Revision feed
'history-feed-title' => 'Tarixê çımraviyarnayışi',
'history-feed-description' => 'Wiki de tarixê çımraviyarnayışê na pele',
'history-feed-item-nocomment' => '$1 miyanê $2i de',
'history-feed-empty' => 'Pela cıgeyrayiye çıniya.
Beno ke ena esteriya, ya zi namê cı vuriyo.
Seba pelanê muhimanê newan [[Special:Search|cıgeyrayışê wiki de]] bıcerebne.',

# Revision deletion
'rev-deleted-comment' => '(Timarkerdışe enay hewadeyayo)',
'rev-deleted-user' => '(namey karberi esteriyo)',
'rev-deleted-event' => '(fealiyetê cıkewtışi esteriyo)',
'rev-deleted-user-contribs' => '[namey karberi ya zi adresa IPy esteriya - vurnayış iştırakan ra nımniyo]',
'rev-deleted-text-permission' => "Çımraviyarnayışê ena pele '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-deleted-text-unhide' => "Çımra viyarnayışê ena pele '''besterêno'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] de teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê çımra viyarnayışi bıvinê], eke wazenê dewam kerê.",
'rev-suppressed-text-unhide' => "Çımra viyarnayışê ena pele '''Degusneyayo'''.
Beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} degustış] de teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê çımraviyarnayışi bıvênê], eke wazenê dewam kerê.",
'rev-deleted-text-view' => "Çımra viyarnayışê ena pele '''besternêno'''.
Şıma be idarekerina xo ra şenê ey bıvênê; beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] de teferruat esto.",
'rev-suppressed-text-view' => "Çımraviyarnayışê ena pele '''degusneyayo'''.
Şıma be idarekerina xo ra şenê ey bıvênê; beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} degusnayış] de teferruat esto.",
'rev-deleted-no-diff' => "Şıma nêşenê nê ferqi bıvênê, çıke çımraviyarnayışan ra  yew '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-suppressed-no-diff' => "Revizyon '''esteriyayo\"' aye ra ti nieşkeno ena diff bivine.",
'rev-deleted-unhide-diff' => "Çımra viyarnayışanê na ferqi ra  yew '''besterneyayo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] dı teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê ferqi bıvênê], eke wazenê dewam kerê.",
'rev-suppressed-unhide-diff' => "Nê Timarkerdışi ra yewi '''çap biyo'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rocaneyê vındertışi] de teferru'ati esti.
Eke şıma serkari u devam bıkeri [$1 no vurnayiş şıma eşkeni bıvini].",
'rev-deleted-diff-view' => "Jew timarkerdışê ena versiyon '''wedariyayo''.
Îdarekarî şenê ena versiyon bivîne; belki tiya de [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wedarnayişî] de teferruat esto.",
'rev-suppressed-diff-view' => "Jew timarkerdışê ena versiyon '''Ploxneyış'' biyo.
Îdarekarî eşkeno ena dif bivîne; belki tiya de [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ploxnayış] de teferruat esto.",
'rev-delundel' => 'bımocne/bınımne',
'rev-showdeleted' => 'bımocne',
'revisiondelete' => 'Bestere/çımraviyarnayışan peyser bia',
'revdelete-nooldid-title' => 'Çımraviyarnayışo waşte nêvêreno',
'revdelete-nooldid-text' => 'Şıma vıraştışê nê fonksiyoni rê ya yew çımraviyarnayışo waşte diyar nêkerdo, çımraviyarnayışo diyarkerde çıniyo, ya ki şıma wazenê ke çımraviyarnayışê nıkayêni bınımnê.',
'revdelete-nologtype-title' => 'Qet qeydê cınêdiya',
'revdelete-nologtype-text' => 'Qeydê şımawo diyar çıniyo ke nê fealiyet kewê.',
'revdelete-nologid-title' => 'Cıkewtış qebul nêbi',
'revdelete-nologid-text' => 'Şıma vıraştışê nê fonksiyoni rê ya yew cıkewtışo waşte diyar nêkerdo, ya ki çıkewtışo diyarkerde çıniyo.',
'revdelete-no-file' => 'Dosya diyarkerdiye çıniya.',
'revdelete-show-file-confirm' => 'Şıma eminê ke wazenê çımraviyarnayışê esterıtey na dosya "<nowiki>$1</nowiki>" $2 ra $3 de bıvênê?',
'revdelete-show-file-submit' => 'E',
'revdelete-selected' => "'''[[:$1]]: ra {{PLURAL:$2|çımraviyarnayışo weçinıte|çımraviyarnayışê weçinıtey}}'''",
'logdelete-selected' => "'''{{PLURAL:$1|Qeydbiyayışo weçinıte|Qeydbiyayışê weçinıtey}}:'''",
'revdelete-text' => "'''Çımraviyarnayışê esterıtey u kerdışi hewna tarixê pele u qeydan de asenê, hema parçeyê zerrekê dinan areze nêbenê.'''
Eke şertê ilawekerdey ke niyê ro, idarekerê bini {{SITENAME}} de nêşenê hewna bıresê zerrekê nımıtey u şenê ey anciya na eyni miyanpele ra peyser biarê.",
'revdelete-confirm' => 'Ma rica keno testiq bike ti ena hereket keno u ti zano neticeyanê herketanê xo u ti ena hereket pê ena [[{{MediaWiki:Policy-url}}|polici]] ra keno.',
'revdelete-suppress-text' => "Wedardış gani '''tenya''' nê halanê cêrênan de bıxebıtiyo:
* Melumatê kıfırio mıhtemel
* Melumatê şexio bêmınasıb
*: ''adresa keyey u numreyê têlefoni, numreyê siğorta sosyale, uêb.''",
'revdelete-legend' => 'Şertanê vênayışi rone',
'revdelete-hide-text' => 'Nuştey revizyoni',
'revdelete-hide-image' => 'zerreyê dosyay bınımnê',
'revdelete-hide-name' => "hedef u vaqa' bınımne",
'revdelete-hide-comment' => 'Menıni timar ke',
'revdelete-hide-user' => 'IP asresa/namey  vırnoği',
'revdelete-hide-restricted' => 'Malumatan pa serkaran u karberan ra bınım.',
'revdelete-radio-same' => '(mevurne)',
'revdelete-radio-set' => 'Nımnaye',
'revdelete-radio-unset' => 'Aseno',
'revdelete-suppress' => 'Hem ê binan ra hem zi serkaran ra malumatan bınım',
'revdelete-unsuppress' => 'reizyonê ke tepiya anciye serbest ker',
'revdelete-log' => 'Sebeb:',
'revdelete-submit' => 'Cewab be {{PLURAL:$1|çımraviyarnayışi|çımraviyarnayışan}} de',
'revdelete-success' => "''''Esayişê revizyoni bi muvaffaqi eyar bi.'''",
'revdelete-failure' => "'''Esayişê revizyoni eyar nibeno:'''
$1",
'logdelete-success' => "'''Esayişê rocaneyi bı muvaffaqi eyar bı.'''",
'logdelete-failure' => "'''Esayişê rocaneyi eyar nêbı:'''
$1",
'revdel-restore' => 'asayışi bıvurne',
'revdel-restore-deleted' => 'revizyonê wedariyaye',
'revdel-restore-visible' => 'revizyonê ke asenê',
'pagehist' => 'Verora perer',
'deletedhist' => 'tarixê hewna şiyaye',
'revdelete-hide-current' => '$2 $1 ney çiye ke wexta diyayene wera (wedar dayiş) xeta da: no reviyon nınımiyeno.',
'revdelete-show-no-access' => '$2 $1 wexta ke ney tarix de mociyayene xeta da: ne çi "vergırewtı" nişane biyo.
resayişê şıma çino.',
'revdelete-modify-no-access' => '$2 $1 no çi yê ke wexta vuriyayene xeta da: no çi "vergırewtı" nişane biyo.
resayişê şıma çino.',
'revdelete-modify-missing' => "$1 ID' de wexta ke çiyek vuriyayene xeta vıraziya: database vindbiyaye yo!",
'revdelete-no-change' => "'''Hişyari:'''  $2 $1 no çi re ca ra eyarê esayişi waziyayo.",
'revdelete-concurrent-change' => '$2 $1 no çi wexta ke vuriya xeta da: wina aseno ke wexta şıma vurnayiş kerdene o enate de yewna te vurnayiş kerdo.
rocaneyan kontrol bıkere.',
'revdelete-only-restricted' => 'Xetawa ke maddeyanê rocanê $2, $1ine nımnena: şıma nêşenê maddeyanê ke terefê idarekeran ra nêdiyaeyan, bê weçinıtışê tercihanê vêniyaoğanê binan ra zi yewi, çap kerê.',
'revdelete-reason-dropdown' => '*Sebebê besternayış de umumi
** İhlalê telifi
** Malumatê şexsiyo ke munasib niye
** Nameyo xırab
** Malumatê iftira çekerdışi',
'revdelete-otherreason' => 'ê bini/sebebê bini',
'revdelete-reasonotherlist' => 'sebebê bini',
'revdelete-edit-reasonlist' => 'sebebê hewna kerdışani bıvurn',
'revdelete-offender' => 'nuştoxê revizyoni:',

# Suppression log
'suppressionlog' => 'qeydê pinani kerdışi',
'suppressionlogtext' => "Cêr de, kahyayan ra zerreko nımıte esto,eno listey besterneya u merdumê bloke kerdışiyo. 
Listey xırabi u bloki re pelay [[Special:BlockList|IP'yê ke bloke biyê]] bivinê.",

# History merging
'mergehistory' => 'vere cûye pelan bıhewelın',
'mergehistory-header' => 'No pel, reviyonê yew peli eşkeno yewna pelo newe de piyawano.
no vurnayişo ke şıma keni kontrol bıkere yew pelo kehen nêbo.',
'mergehistory-box' => 'revizyonê pelanî yew bike:',
'mergehistory-from' => 'Para Çımi:',
'mergehistory-into' => 'Pela destinasyonî',
'mergehistory-list' => 'tarixê vurnayîşî ke eşkeno yew bi.',
'mergehistory-merge' => '[[:$1]] qey ney revizyonê cêrini [[:$2]] şıma ekeni piyawani. Benatê wexto muwaqqet de piyayanayişê rezizyonan de tuşa radyo bıxebitne.',
'mergehistory-go' => 'Vernayîşê yewbiyayeni bimocne',
'mergehistory-submit' => 'revizyonî yew bike',
'mergehistory-empty' => 'Revizyonî yew nibenê.',
'mergehistory-success' => '$3 {{PLURAL:$3|revizyonê|revizyonê}} [[:$1]] u [[:$2]] yew biyê.',
'mergehistory-fail' => 'Tarixê pele yew nibeno, ma rica kenê ke pel u wext control bike.',
'mergehistory-no-source' => 'Pela çime $1 çini yo.',
'mergehistory-no-destination' => 'Pela destinasyoni $1 çini yo.',
'mergehistory-invalid-source' => 'Pela çime gani yew seroğê raşt biy.',
'mergehistory-invalid-destination' => 'Pela destinasyonî gani yew seroğê raşt biy.',
'mergehistory-autocomment' => '[[:$1]] u [[:$2]] yew biyê',
'mergehistory-comment' => '[[:$1]] u [[:$2]] yew biyê: $3',
'mergehistory-same-destination' => 'Pela çime u destinasyonî gani eyni nibiy.',
'mergehistory-reason' => 'Sebeb:',
'mergehistory-revisionrow' => '$1 ($2) $3 . . $4 $5 $6',

# Merge log
'mergelog' => 'Qeydé zew kerdışi',
'pagemerge-logentry' => '[[$1]] u [[$2]] yew kerd (revizyonî heta $3)',
'revertmerge' => 'Abırnê',
'mergelogpagetext' => 'Cêr de yew liste esta ke mocnena ra, raya tewr peyêne kamci pela tarixi be a bine ra şanawa pê.',

# Diffs
'history-title' => 'Rewizyonê $1:',
'difference-title' => 'Pela "$1" ferqê çım ra viyarnayışan',
'difference-title-multipage' => 'Ferkê pelan dê "$1" u "$2"',
'difference-multipage' => '(Ferqê pelan)',
'lineno' => 'Xeta $1i:',
'compareselectedversions' => 'Rewizyonanê weçineyan pêver ke',
'showhideselectedversions' => 'Revizyonanê weçinıtan bımocne/bınımne',
'editundo' => 'peyser bıgi',
'diff-empty' => '(Babetna niyo)',
'diff-multi' => '({{PLURAL:$1|Yew revizyono miyanên|$1 revizyonê miyanêni}} terefê {{PLURAL:$2|yew karberi|$2 karberan}} nêmocno)',
'diff-multi-manyusers' => '({{PLURAL:$1|jew timar kerdışo qıckeko|$1 timar kerdışo qıckeko}} timar kerdo, $2 {{PLURAL:$2|Karber|karberi}} memocne)',
'difference-missing-revision' => 'Ferqê {{PLURAL:$2|Yew rewizyonê|$2 rewizyonê}} {{PLURAL:$2|dı|dı}} ($1) sero çıniyo.

No normal de werênayış dê pelanê besterneyan dı ena xırabin asena.
Detayê besternayışi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tiya dı] aseno.',

# Search results
'searchresults' => 'Neticeya geyrayışi',
'searchresults-title' => 'Qandê "$1" neticeyê geyrayışi',
'searchresulttext' => 'Zerrey {{SITENAME}} de heqa cıgeyrayışi de seba melumat gırewtışi, şenay qaytê [[{{MediaWiki:Helppage}}|{{int:help}}]] ke.',
'searchsubtitle' => 'Tı semedê \'\'\'[[:$1]]\'\'\' cıgeyra. ([[Special:Prefixindex/$1|pelê ke pêro be "$1" ra dest niyaê pıra]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|pelê ke pêro be "$1"\' ra gırê xo esto]])',
'searchsubtitleinvalid' => "Tı cıgeyra qe '''$1'''",
'toomanymatches' => 'Zêde teki (zewci) peyser çarnay, şıma rê zehmet, be persê do bin ra bıcerrebnên.',
'titlematches' => 'Tekê (zewcê) sernameyê pele',
'notitlematches' => 'Tekê (zewcê) sernameyê pele çıniyê.',
'textmatches' => 'Tekê (zewcê) nuştey pele',
'notextmatches' => 'tekê (zewcê) nuştey pele çıniyê',
'prevn' => '{{PLURAL:$1|$1}} verên',
'nextn' => '{{PLURAL:$1|$1}} peyên',
'prevn-title' => '$1o verên  {{PLURAL:$1|netice|neticeyan}}',
'nextn-title' => '$1o ke yeno {{PLURAL:$1|netice|neticey}}',
'shown-title' => 'bimocne $1î  {{PLURAL:$1|netice|neticeyan}} ser her pel',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) bıvênên',
'searchmenu-legend' => 'bıgeyre tercihan (sae bıke)',
'searchmenu-exists' => "''Ena 'Wikipediya de ser \"[[:\$1]]\" yew pel esto'''",
'searchmenu-new' => "''Na Wiki de pelay \"[[:\$1]]\" vıraze!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|pê eno prefix ser pelan de bigêre]]',
'searchprofile-articles' => 'Perré muhteway',
'searchprofile-project' => 'Pera Destegi uw Procan',
'searchprofile-images' => 'Multimedya',
'searchprofile-everything' => 'Heme çi',
'searchprofile-advanced' => 'Ravérden',
'searchprofile-articles-tooltip' => '$1 de bigêre',
'searchprofile-project-tooltip' => '$1 de bigêre',
'searchprofile-images-tooltip' => 'Dosya cı geyr',
'searchprofile-everything-tooltip' => 'Tedeestey hemine cı geyre (pelanê mınaqeşey zi tey)',
'searchprofile-advanced-tooltip' => 'qe cayê nimeyî bigêre',
'search-result-size' => '$1 ({{PLURAL:$2|1 çekuyo|$2 çekuyê}})',
'search-result-category-size' => '{{PLURAL:$1|1 eza|$1 ezayan}} ({{PLURAL:$2|1 kategoriyê bini|$2 kategirayanê binan}}, {{PLURAL:$3|1 dosya|$3 dosyayan}})',
'search-result-score' => 'Eleqa: $1%',
'search-redirect' => '(ber $1)',
'search-section' => '(qısmê $1)',
'search-suggest' => 'To va: $1',
'search-interwiki-caption' => 'Projey Bıray',
'search-interwiki-default' => '$1 neticeyan:',
'search-interwiki-more' => '(véşi)',
'search-relatedarticle' => 'Eleqeyın',
'mwsuggest-disable' => 'Tewsiyay AJAXi bıgê',
'searcheverything-enable' => 'cayê nameyê hemi de bigêre',
'searchrelated' => 'eleqeyın',
'searchall' => 'pêro',
'showingresults' => "#$2 netican ra {{PLURAL:$1|'''1''' netica|'''$1''' neticey}} cêr deyê.",
'showingresultsnum' => "'''$2''' netican ra nata  {{PLURAL:$3|'''1''' netice|'''$3''' neticeyê}} cêrde liste biyê.",
'showingresultsheader' => "{{PLURAL:$5|Neticeyê '''$1''' of '''$3'''|Neticeyanê '''$1 - $2''' hetê '''$3'''}} qe '''$4'''",
'nonefound' => "'''Teme''': Teyna tay namecayan cıgeyro beno.
Pe verbendi ''all:'', vaceyê xo bıvurni ki contenti hemi cıgeyro (pelanê mınaqeşe, templatenan, ucb.) ya zi cıgeyro ser namecay ki tı wazeni.",
'search-nonefound' => 'Zey perskerdışê şıma netice nêvêniya.',
'powersearch' => 'Cıgeyrayışo hera',
'powersearch-legend' => 'Cıgeyrayışo hera',
'powersearch-ns' => 'Cayanê nameyan de cıgeyrayış:',
'powersearch-redir' => 'Listeya hetenayışan',
'powersearch-field' => 'Seba cı seyr ke',
'powersearch-togglelabel' => 'Qontrol ke:',
'powersearch-toggleall' => 'Pêro',
'powersearch-togglenone' => 'Çıniyo',
'search-external' => 'Cıgeyrayışê teberi',
'searchdisabled' => '{{SITENAME}} no keyepel de cıgerayiş muweqqet bıryayo. no benatê de şıma pê Google eşkeni zerreyê {{SITENAME}} de cıgerayiş bıkeri.',

# Preferences page
'preferences' => 'Tercihi',
'mypreferences' => 'Tercihi',
'prefs-edits' => 'Amarê vurnayışan:',
'changepassword' => 'Parola bıvurne',
'prefs-skin' => 'Çerme',
'skin-preview' => 'Verasayış',
'datedefault' => 'Tercih çıniyo',
'prefs-beta' => 'Xacetê beta',
'prefs-datetime' => 'Tarix u wext',
'prefs-labs' => 'Xacetê labs',
'prefs-user-pages' => 'Pela Karberi',
'prefs-personal' => 'Pela karberi',
'prefs-rc' => 'Vurnayışê peyêni',
'prefs-watchlist' => 'Lista seyrkerdışi',
'prefs-watchlist-days' => 'Rocê ke lista seyrkerdışi de bêrê ramocnaene',
'prefs-watchlist-days-max' => 'tewr vêşi $1 {{PLURAL:$1|roci|roci}}',
'prefs-watchlist-edits' => 'tewr zêde amarê vurnayışi ke lista seyrkerdışia herakerdiye de bıasê:',
'prefs-watchlist-edits-max' => 'Amerê tewr zafî: 1000',
'prefs-watchlist-token' => 'Lista seyrkerdışia nışani:',
'prefs-misc' => 'ê bini',
'prefs-resetpass' => 'Parola bıvurne',
'prefs-changeemail' => 'E-postay bıvurne',
'prefs-setemail' => 'E-posta adresiyê xo saz kerê',
'prefs-email' => 'Tercihê e-maili',
'prefs-rendering' => 'Asayış',
'saveprefs' => 'Qeyd ke',
'resetprefs' => 'Vurnayışê ke qeyd nêbiy, pak ke',
'restoreprefs' => 'Sazanê hesıbyaya pêron newe ke',
'prefs-editing' => 'Cay pela nustısi',
'rows' => 'Xeti:',
'columns' => 'Estûni:',
'searchresultshead' => 'Cı geyre',
'resultsperpage' => 'Serê pele  amarê cıkewtoğan:',
'stub-threshold' => 'Baraj ke <a href="#" class="stub">stub link</a> ho şekil dano (bîtî):',
'stub-threshold-disabled' => 'Astengın',
'recentchangesdays' => 'Rocê ke vurnayışanê peyênan de bıasê:',
'recentchangesdays-max' => 'Tewr zaf $1 {{PLURAL:$1|roc|roci}}',
'recentchangescount' => 'Amarê vurnayışê ke hesıbyaye deye bımocneyê:',
'prefs-help-recentchangescount' => 'Ney de vurnayışê peyêni, tarixê pelan u cıkewteni asenê.',
'savedprefs' => 'Tecihey şıma qeyd biyey.',
'timezonelegend' => 'Warey saete:',
'localtime' => 'saeta mehelliye:',
'timezoneuseserverdefault' => 'Zey karkerdışê Wiki ($1)',
'timezoneuseoffset' => 'Zewbina (offseti beli bıke)',
'timezoneoffset' => 'Offset¹:',
'servertime' => 'Wextê serveri:',
'guesstimezone' => 'Browser ra pırr ke',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Asya',
'timezoneregion-atlantic' => 'Okyanuso Atlantik',
'timezoneregion-australia' => 'Awıstralya',
'timezoneregion-europe' => 'Ewropa',
'timezoneregion-indian' => 'Okyanuso Hind',
'timezoneregion-pacific' => 'Okyanuso Pasifik',
'allowemail' => 'Karberê bini wa bışê mı rê e-posta bırışê.',
'prefs-searchoptions' => 'Cı geyre',
'prefs-namespaces' => 'Cayê namey',
'defaultns' => 'Eke heni, enê cayanê namey de cı geyre (sae ke):',
'default' => 'qısur',
'prefs-files' => 'Dosyey',
'prefs-custom-css' => 'CSSê xasi',
'prefs-custom-js' => 'JSê xasi',
'prefs-common-css-js' => 'CSS/JavaScript pê şablonanê peran de pay biya:',
'prefs-reset-intro' => 'ena pele de şıma tercihanê xo şenê bıçarnê be tercihanê keyepelê ke verê coy eyar biy.
Na game tepeya nêerziyena.',
'prefs-emailconfirm-label' => 'Tesdiqiya E-posta:',
'youremail' => 'E-Mail (mecbur niyo) *:',
'username' => '{{GENDER:$1|Nameyê karberi}}:',
'uid' => 'Kamiya {{GENDER:$1|karberi}}:',
'prefs-memberingroups' => '{{GENDER:$2|Ezayê}} {{PLURAL:$1|grube|gruban}}:',
'prefs-memberingroups-type' => '$1',
'prefs-registration' => 'Wextê qeydbiyayışi',
'prefs-registration-date-time' => '$1',
'yourrealname' => 'Nameyo raştay',
'yourlanguage' => 'Zıwan:',
'yourvariant' => 'Varyante miyandê zuwani:',
'prefs-help-variant' => 'Zerrey ena viki mocnayışi rê varyant yana ortografi re şıre tercihan dê xo.',
'yournick' => 'imza:',
'prefs-help-signature' => 'Peran de vatenana de vatışi"<nowiki>~~~~</nowiki>" ya do imza bé, no bahdo beno çerğé imza u wahdey zemani',
'badsig' => 'Îmzayê tu raşt niyo.
Etiketê HTMLî kontrol bike.',
'badsiglength' => 'İmzayê şıma zaf dergo.
$1 gani bınê no {{PLURAL:$1|karakter|karakter}} de bıbo.',
'yourgender' => 'Çıçiy cı esto?',
'gender-unknown' => 'Ez detay nivana',
'gender-male' => 'Perané wiki camérd deyne ezo vırnena',
'gender-female' => 'Perané wiki cıni deyne eza vırnena',
'prefs-help-gender' => 'Na tercih keyfiya.
Na nustenek ercana qısan de qandé grameri karneyéna, na malumater herkes şeno bıvino .',
'email' => 'E-posta',
'prefs-help-realname' => 'Nameyo raşt waştena şıma rê mendo.
Eka tu wazene ke nameyo raşt xo bide, ma nameyo raşt ti iştirakanê ti de mocnenê.',
'prefs-help-email' => 'Dayışê adresa e-postey keyfiyo, labelê seba eyarê parola lazıma, wexto ke şıma naye xo vira kerê.',
'prefs-help-email-others' => 'Pera ğoya kerderi de zew link vırazése karberé bini şımaré şenê mesac bırşé. Lakin e-posta adresa şıma héç cayé de niasena.',
'prefs-help-email-required' => 'E-mail adrese mecburiya.',
'prefs-info' => 'Melumata şıma',
'prefs-i18n' => 'Şar şélıg kerdış',
'prefs-signature' => 'İmza',
'prefs-dateformat' => 'Formatê tarixi',
'prefs-timeoffset' => 'Wext offset',
'prefs-advancedediting' => 'Herayen weçinayış',
'prefs-editor' => 'Timarkar',
'prefs-preview' => 'Verqayt',
'prefs-advancedrc' => 'Tercihê raverberdey',
'prefs-advancedrendering' => 'Tercihê raverberdey',
'prefs-advancedsearchoptions' => 'Tercihê raverberdey',
'prefs-advancedwatchlist' => 'Tercihê raverberdey',
'prefs-displayrc' => 'Tercihan bımocne',
'prefs-displaysearchoptions' => 'Weçinayışê mocnayışi',
'prefs-displaywatchlist' => 'Weçinayışê mocnayışi',
'prefs-tokenwatchlist' => 'Morge',
'prefs-diffs' => 'Ferqi',
'prefs-help-prefershttps' => 'Na tercih, fına dekewten dı bena aktiv.',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'e-posta adresi raştayo',
'email-address-validity-invalid' => 'e-postayo raştay defiye de',

# User rights
'userrights' => 'İdarey heqanê karberan',
'userrights-lookup-user' => 'Grubanê karberi/karbere idare bıke',
'userrights-user-editname' => 'Yew nameyê karberi cı kewe:',
'editusergroup' => 'Grupanê karberi/karbere bıvurne (bıbedelne)',
'editinguser' => "'''[[User:$1|$1]]''' keno weziyetê $2'i bıvurno",
'userrights-editusergroup' => 'Grubanê karberi/karbere sero bıgureye (bıxebetiye)',
'saveusergroups' => 'Grubanê karberi qeyd bıke',
'userrights-groupsmember' => 'Ezayê:',
'userrights-groupsmember-auto' => 'Ezao daxıl/ezaa daxıle ê:',
'userrights-groups-help' => 'şıma şenê grubanê nê karberi/na karbere, oyo/aya ke tede, bıvurnê:
* qutiya ke nışankerdiya, mocnena ke karber/e na grube dero/dera.
* qutiya ke nışankerdiye niya, mocnena ke karber/ na grube de niyo/niya.
* Yew estare * mocneno ke, gruba ke şıma kerda ra ser (daxıl kerda), şıma nêşenê wedarê/hewa dê ya ki dêmlaşta/tersê cı.',
'userrights-reason' => 'Sebeb:',
'userrights-no-interwiki' => 'Heqa şıma çıniya ke heqanê karberanê Wikipediyanê binan sero bıgureyê.',
'userrights-nodatabase' => 'Database $1 çıniyo ya zi mehelli niyo.',
'userrights-nologin' => 'Eke şıma wazenê ke heqa karberi/karbere cı dê, şıma gani be [[Special:UserLogin|cikewtiye]] pê yew hesabê idarekeran cı kewê',
'userrights-notallowed' => 'Hesabdê şımadı heqanê xo hewadayış u xorê heq dekerdış çıno.',
'userrights-changeable-col' => 'Grubê ke şıma şenê bıvurnê',
'userrights-unchangeable-col' => 'Grubê ke şıma nêşenê bıvurnê',
'userrights-irreversible-marker' => '$1*',

# Groups
'group' => 'Grube:',
'group-user' => 'Karberi',
'group-autoconfirmed' => 'Karberê ke otomatikmen biyê araşt',
'group-bot' => 'Boti',
'group-sysop' => 'İdarekari',
'group-bureaucrat' => 'Burokrati',
'group-suppress' => 'Çımpawıteni',
'group-all' => '(pêro)',

'group-user-member' => '{{GENDER:$1|karber}}',
'group-autoconfirmed-member' => '{{GENDER:$1|Karberê ke otomatikmen biyê araşt}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|İdarekar}}',
'group-bureaucrat-member' => '{{GENDER:$1|buroqrat}}',
'group-suppress-member' => '{{GENDER:$1|Temaşekar}}',

'grouppage-user' => '{{ns:project}}:Karberi',
'grouppage-autoconfirmed' => '{{ns:project}}:Karberê ke otomatikmen biyê araşt',
'grouppage-bot' => '{{ns:project}}:Boti',
'grouppage-sysop' => '{{ns:project}}:İdarekeri',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrati',
'grouppage-suppress' => '{{ns:project}}:Qontrol',

# Rights
'right-read' => 'Pera bıwané',
'right-edit' => 'Pele bıvurne',
'right-createpage' => 'Pele vıraze (pelê ke ê werênayışi niyê)',
'right-createtalk' => 'Pela werênayışi vıraze',
'right-createaccount' => 'Hesabê karberi vıraze',
'right-minoredit' => 'Vurnayışan qıckek nışan bıde.',
'right-move' => 'Pele bere',
'right-move-subpages' => 'Pele be bınpelanê cı ra pia bere',
'right-move-rootuserpages' => 'Pelanê kaberiê rıstımi bere',
'right-movefile' => 'Dosyan bere',
'right-suppressredirect' => 'Wexto ke pelan benê, pelanê çımey ra neql mevıraze',
'right-upload' => 'Dosya bar bıke',
'right-reupload' => 'Dosyeyê ke estê, inan serde bınuse',
'right-reupload-own' => 'Dosyeyê ke to bar kerdi, inan sero bınuse',
'right-reupload-shared' => 'Dosyeyê ke ambarê medyao barekerde de, inan mehelli wedare',
'right-upload_by_url' => 'Yew URL ra dosyan bar bıke',
'right-purge' => 'Virê sita seba yew pele bêdestur bestere.',
'right-autoconfirmed' => 'Perê ke nême kılit biyê, inan bıvurne',
'right-bot' => 'Zey yew karê xoserkerdey be',
'right-nominornewtalk' => 'Pelanê werênayışan rê vurnayışê qıckeki çıniyê, qutiya mesacanê newiyan bıgurene',
'right-apihighlimits' => 'Persanê API de sinoranê berzêran bıgurene',
'right-writeapi' => 'İstıfadey APIyê nuştey',
'right-delete' => 'Pele bestere',
'right-bigdelete' => 'Pelanê be tarixanê dergan bestere',
'right-deletelogentry' => 'besternayış u mebesternayışa re qeyde definayışê xısusi',
'right-deleterevision' => 'Vurnayışê xısusiyê ke ê pelanê, inan bestere ya zi peyser bia',
'right-deletedhistory' => 'Qeydanê tarixanê esterıteyan de qayt ke, bê nuştey inan',
'right-deletedtext' => 'Mabênê newede vurnayışanê esterıtiyan de qaytê nuştey esterıtey u vurnayışan ke',
'right-browsearchive' => 'Pelanê esterıteyan bıgeyre',
'right-undelete' => 'Jû pela esterıtiye peyser bia',
'right-suppressrevision' => 'İdarekeran ra dızdeni/miyanki, newede vurnayışan de qayt ke u newede vıraze',
'right-suppressionlog' => 'Rocekanê xasan bıvêne',
'right-block' => 'Karberanê binan karê vurnayışi ra bloke bıke',
'right-blockemail' => 'Yew karberê erşawıtışê/rıştena e-maili ra bloke bıke',
'right-hideuser' => 'Yew namey karberi  şari ra dızdeni/miyanki bloke bıke',
'right-ipblock-exempt' => 'Blokanê IPi, oto-blokan u blokanê menzıli ra ravêre',
'right-proxyunbannable' => 'Blokanê otomatikiê proksiyan ra ravêre',
'right-unblockself' => 'Blokeyınan ake',
'right-protect' => 'Sewiyanê pawıtışi (mıhafezey) bıvurne u pelanê kılitbiyaiyan sero bıgureye.',
'right-editprotected' => 'Pera pawıtiyan sero bıxebteye (bê pawıtena kaskadi (game be game))',
'right-editsemiprotected' => 'Xısusi pera timaryayış "{{int:protect-level-autoconfirmed}}"',
'right-editinterface' => 'Interfaceê karberi sero bıgureye',
'right-editusercssjs' => 'CSS u dosyanê JSiê karberanê binan sero bıgureye',
'right-editusercss' => 'Dosyanê CSSiê karberanê binan sero bıgureye',
'right-edituserjs' => 'Dosyanê JSiê karberanê binan sero bıgureye',
'right-viewmywatchlist' => 'Lista seyr de xo bıvin',
'right-editmyoptions' => 'Tercihané ğo bıvırn',
'right-rollback' => 'Lez/herbi vurnayışanê karberê peyêni tekrar bıke, oyo ke yew be yew pelê sero gureyao',
'right-markbotedits' => 'Vurnayışanê peyd ameyan, vurnayışê boti deye nışan kerê',
'right-noratelimit' => 'Sinoranê xızi (rate limit) ra tesir nêbi',
'right-import' => 'Pelan wikiyanê binan ra bia',
'right-importupload' => 'Pelî dosya bar kerdişî ra import bike',
'right-patrol' => 'Vurnayîşanê karberê binî nîşan bike ke patrol biyê',
'right-autopatrol' => 'Vurnayîşanê xo otomatik nîşan bike ke patrol biyê',
'right-patrolmarks' => 'Vurnayîşanê peniyî nîşan patrol biyê bivîne',
'right-unwatchedpages' => 'Yew listeyê pelanê seyrnibiye bivîne',
'right-mergehistory' => 'Tarixê pelan yew ke',
'right-userrights' => 'Heqanê karberi pêro bıvurne',
'right-userrights-interwiki' => 'Heqqa karberanê ke ho wîkîyo binî de ey bivurne',
'right-siteadmin' => 'Database kilit bike u a bike',
'right-override-export-depth' => 'Peleyanê ke tede linkanê 5 ra zafyer estê ay export bike',
'right-sendemail' => 'Karberanê binî ra e-mail bişirav',
'right-passwordreset' => 'E-postayanê parola reset kerdışa vineno',

# Special:Log/newusers
'newuserlogpage' => 'Cıkewtışê hesabvıraştışi',
'newuserlogpagetext' => 'Ena log de viraştişê karberî esta.',

# User rights log
'rightslog' => 'Qeydê heqanê karberi',
'rightslogtext' => 'Ena listeyê loganê ke heqqa karbaranî mucneno.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ena pela wanayış',
'action-edit' => 'ena pela bıvurnê',
'action-createpage' => 'pelan bıvıraze',
'action-createtalk' => 'pelanê werênayışi bıvıraze',
'action-createaccount' => 'hesabê nê karberi bıvıraze',
'action-minoredit' => 'nê vurnayışi be qıckek işaret ke',
'action-move' => 'ena pele bere',
'action-move-subpages' => 'ena pele, u pelanê daê bınênan bere',
'action-move-rootuserpages' => 'pelanê karberiyê bıngeyan bere',
'action-movefile' => 'ena dosya bere',
'action-upload' => 'ena dosya bar ke',
'action-reupload' => 'dosyayê ke database de esto ser ey binuse',
'action-reupload-shared' => 'dosyayê ki ho embarê medyayî de esto ser ay binusne',
'action-upload_by_url' => 'Ena dosya yew URL ra bar bike',
'action-writeapi' => 'ser nuşte API gure bike',
'action-delete' => 'ena perer besternê',
'action-deleterevision' => 'nê çımraviyarnayışi bestere',
'action-deletedhistory' => 'tarixê ena pel ki estereyî biya, ey bivine',
'action-browsearchive' => 'pelanê esterıteyan bıgeyre',
'action-undelete' => 'ena pele reyna biyere',
'action-suppressrevision' => 'revizyone ki nimnaye biye reyna bivîne u restore bike',
'action-suppressionlog' => 'Ena bağse qeydi bıvin',
'action-block' => 'enê karberi vurnayışi ra bıreyne',
'action-protect' => 'seviyeyê pawitişî se ena pele bivurne',
'action-rollback' => 'Lez/herbi vurnayışanê karberê peyêni tekrar bıke, oyo ke yew be yew pelê sero gureyao',
'action-import' => 'ena pele yewna wikira azered',
'action-importupload' => 'ena pele yew dosyayê bar kerdışira azered',
'action-patrol' => 'vurnayîşê karberanê binî nişan bike patrol biye',
'action-autopatrol' => 'vurnayîşê xoye nişan bike ke belli biyo patrol biye',
'action-unwatchedpages' => 'listeyê pelanê seyirnibiya bivîne',
'action-mergehistory' => 'tarixê ena pele yew ke',
'action-userrights' => 'heqqa karberanê hemî bivurne',
'action-userrights-interwiki' => 'heqqa karberanê ke wikiyê binî de hemî bivurne',
'action-siteadmin' => 'database kilit bike ya zi a bike',
'action-sendemail' => 'e-posta bırşe',
'action-editmywatchlist' => 'Listeyseyran de xo bıvırne',
'action-viewmywatchlist' => 'Listeyseyran de xo bıvin',
'action-viewmyprivateinfo' => 'Xısusi tercihane xo bıvin',
'action-editmyprivateinfo' => 'Xısusi malumate xo bıvurne',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|fın vurna|fıni vurna}}',
'enhancedrc-since-last-visit' => '$1 {{PLURAL:$1|ra yok wazino}}',
'enhancedrc-history' => 'verenayış',
'recentchanges' => 'Vurnayışê peyêni',
'recentchanges-legend' => 'Tercihê vurnayışanê peyênan',
'recentchanges-summary' => 'Ena pele de wiki sero vurnayışanê peyênan teqib ke.',
'recentchanges-noresult' => 'Zey kiterandé şıma vırnayış névineya',
'recentchanges-feed-description' => 'Ena feed dı vurnayişanê tewr peniyan teqip bık.',
'recentchanges-label-newpage' => 'Enê vurnayışi pelaya newi vıraşt',
'recentchanges-label-minor' => 'Eno yew vurnayışo qıckeko',
'recentchanges-label-bot' => 'Yew boti xo het ra no vurnayış vıraşto',
'recentchanges-label-unpatrolled' => 'Eno vurnayış hewna dewriya nêbiyo',
'recentchanges-legend-newpage' => '$1 - pela newi',
'rcnote' => "Bıni dı  {{PLURAL:$2|roc|'''$2''' rocan}}  ra {{PLURAL:$1|'''1''' vurnayış|'''$1''' vurnayışi}} éyé cér de yé , $5 ra hetana $4.",
'rcnotefrom' => "Cêr de '''$2''' ra nata vurnayışiyê asenê (tewr vêşi <b> '''$1'''</b> asenê).",
'rclistfrom' => '$1 ra tepya vırnayışané newan bıasne',
'rcshowhideminor' => 'Vurnayışanê werdiyan $1',
'rcshowhidebots' => 'Botan $1',
'rcshowhideliu' => 'Karberanê qeydınan $1',
'rcshowhideanons' => 'Karberê bênamey $1',
'rcshowhidepatr' => '$1 vurnayışê ke dewriya geyrayê',
'rcshowhidemine' => 'Vurnayışanê mı $1',
'rclinks' => '$2 rocan peynira $1 vurnayışan bıasne <br />$3',
'diff' => 'ferq',
'hist' => 'verên',
'hide' => 'Bınımne',
'show' => 'Bımocne',
'minoreditletter' => 'q',
'newpageletter' => 'N',
'boteditletter' => 'b',
'unpatrolledletter' => '!',
'number_of_watching_users_pageview' => '[$1 ho seyr keno {{PLURAL:$1|karber|karberî}}]',
'rc_categories' => 'Kategoriyanî rê limît bike (pê "|" ciya bike)',
'rc_categories_any' => 'Her yew',
'rc-change-size' => '$1',
'rc-change-size-new' => 'Vurnayışa dıma $1 {{PLURAL:$1|bayt|bayt}}',
'newsectionsummary' => '/* $1 */ qısımo newe',
'rc-enhanced-expand' => 'Detaya bıvin (JavaScript lazımo)',
'rc-enhanced-hide' => 'Detaya bınımnê',
'rc-old-title' => '"$1"i orcinalê cı vıraşt',

# Recent changes linked
'recentchangeslinked' => 'Vurnayışê elaqeyıni',
'recentchangeslinked-feed' => 'Vurnayışê elaqeyıni',
'recentchangeslinked-toolbox' => 'Vurnayışê elaqeyıni',
'recentchangeslinked-title' => 'vurnayışan ser "$1"',
'recentchangeslinked-summary' => "Lista cêrêne, pela bêlikerdiye rê (ya zi karberanê kategoriya bêlikerdiye rê) pelanê gırêdayoğan de lista de vurnayışê peyênana.
[[Special:Watchlist|Lista şımaya seyrkedışi de]] peli be nuşteyo '''qolınd''' bêli kerdê.",
'recentchangeslinked-page' => 'Nameyê pele:',
'recentchangeslinked-to' => 'Pelayan ke ena pela ri gire bi, ser ayi vurnayışi bımoc',

# Upload
'upload' => 'Dosya bar ke',
'uploadbtn' => 'Dosya bar ke',
'reuploaddesc' => 'Barkerdışi iptal ke u peyser şo formê barkerdışi',
'upload-tryagain' => 'Deskripyonê dosyayî ke vurîya ey qeyd bike',
'uploadnologin' => 'Şıma cıkewtış nêvıraşto',
'uploadnologintext' => 'Ti şeni $1 dosya bar bikere.',
'upload_directory_missing' => 'Direktorê dosyayê ($1)î biyo vînî u webserver de nieşkeno viraziye.',
'upload_directory_read_only' => 'Direktorê dosyayê ($1)î webserver de nieşkeno binuse.',
'uploaderror' => 'Ğeletê bar kerdişî',
'upload-recreate-warning' => "'''Diqet: Yew dosya pê ena name wedariya ya zi vurniya.'''

Logê wedariyayiş u berdişi seba ena pele a ti ra xezir kerda:",
'uploadtext' => "Qey barkerdişê dosyayî, formê cêrinî bişuxulne.
Dosyayê ke vera cû bar biyê eke şima qayîl e ney dosyayan bivînê ya zî bigerî biewnê[[Special:FileList|listeyê dosyayê bar bîyaye]] (tekrar) bar bîyaye [[Special:Log/upload|rocaneyê barkerdişî]] de, hewn a şîyaye zî tîya de [[Special:Log/delete|rocaneyê hewn a kerdişî]] pawiyene.

wexta şima qayîl e yew peli re dosya bierzî, formanê cêrinan ra yewi bişuxulne;
* Qey xebitnayişê dosyayî: '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dosya.jpg]]</nowiki></code>'''
*Heto çep de zerreyê yew qutî de, qey xebitnayişi 'nuşteyê binîn' û 200 pikseli: '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dosya.png|200px|thumb|left|alt metin]]</nowiki></code>'''
* Dosya memocın, dosya te direk gırey bıerz: '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dosya.ogg]]</nowiki></code>'''",
'upload-permitted' => 'Tipanê dosyayi ke izin ey estê: $1.',
'upload-preferred' => 'Tipanê dosyayi ke tercihe ey estê: $1',
'upload-prohibited' => 'Babetê dosyayanê tometebiyayeyan: $1.',
'uploadlog' => 'cıkewtışê barkerdışi',
'uploadlogpage' => 'Cıkewtışê bar-kerdışi',
'uploadlogpagetext' => 'cêr de [[Special:NewFiles|listeyê dosyayan]] estî.',
'filename' => 'Namey Dosya',
'filedesc' => 'Xulasa',
'fileuploadsummary' => 'Xulasa:',
'filereuploadsummary' => 'Vurnayîşê dosyayî:',
'filestatus' => 'Weziyetê heqa telifi:',
'filesource' => 'Çıme:',
'uploadedfiles' => 'Dosyayê ke bar biye',
'ignorewarning' => 'Îkazi kebul meke u dosya reyna bar bike',
'ignorewarnings' => 'Îkazi kebul meke',
'minlength1' => 'Nameyanê dosyayî de gani bî ezamî yew herf est biyê.',
'illegalfilename' => '"$1" no nameyê dosya de tayê karakteri nêşuxulyenî. newe ra tesel bıkerê',
'filename-toolong' => 'Nameyê dosyayan 240 bayt ra derg do nêbo.',
'badfilename' => "Nameyanê dosyayî ''$1'' rê vurneyî biye.",
'filetype-mime-mismatch' => 'Derg kerdıştê Dosyada ".$1" u ($2) MIME tipiya cıya pêro nina.',
'filetype-badmime' => 'Dosyaye ke tipê MIME "$1"î de bar nibeno.',
'filetype-bad-ie-mime' => 'na dosya bar nebena çunke Internet Explorer na dosya "$1" zerarın vinena.',
'filetype-unwanted-type' => "'''\".\$1\"''' na tewırê dosyayi nêwazyena. pêşniyaz biyaye {{PLURAL:\$3|tewırê dosyayi|tewırê dosyayi}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|Ena babeta dosya qebul ne vinena|Ena babeta dosya qebul ne vinena|Ena babeta dosya qebul ne vinena}}. Eke cırê izin deyayo $2. {{PLURAL:$3|Babatan dosyayan|babeta dosyayan}}',
'filetype-missing' => 'Ena dosya de extention (ze ".jpg") çini yo.',
'empty-file' => 'Dosyaya ke şıma rışta venga.',
'file-too-large' => 'Dosyaye ke şıma rışta zaf gırda.',
'filename-tooshort' => 'Namayê dosyayi zaf kilm a.',
'filetype-banned' => 'Tipê ena dosya qedexe biya.',
'verification-error' => 'Ena dosya taramayê dosyayi temam nikena.',
'hookaborted' => 'Vurnayişê tu ke to cerbna pê yew çengal ra terkneya.',
'illegal-filename' => 'Ena nameyê dosyayi kebul nibena.',
'overwrite' => 'Ser yew dosyayê ke hama esta, ser ey qeyd nibena.',
'unknown-error' => 'Yew xeteyê nizanyeni biya.',
'tmp-create-error' => 'Yew dosyayê gecici niviraziyeya.',
'tmp-write-error' => 'Dosyayê gecici de xeta biya.',
'large-file' => 'gırdîyê dosyayan re, na gırdî $1 ra wet pêşniyazi çino;
gırdîyê na dosyayi $2.',
'largefileserver' => 'Ena dosya zaf girde ke server kebul nikeno.',
'emptyfile' => 'dosya ya ke şıma bar kerda veng asena, nameyê dosyayi şaş nusyaya belka.',
'windows-nonascii-filename' => 'Na wiki namen de dosyayan de xısusi karaxtera karkerdışa peşti nêdana.',
'fileexists' => 'no name de yew dosya ca ra esta.
Eke şıma emin niyê bıvurni bıewne na dosya<strong>[[:$1]]</strong>
[[$1|thumb]]',
'filepageexists' => 'qey na dosya pelê eşkera kerdışi <strong>[[:$1]]</strong> na adresi de ca ra vıraziyayo labele no name de yew dosya nêasena.
kılmnuşteyê şıma nêasena eke şıma qayili bıvini gani şıma pê dest bıvurni
[[$1|resimo qıc]]',
'fileexists-extension' => 'zey no nameyê dosyayi yewna nameyê dosyayi esta: [[$2|thumb]]
* dosyaya ke bar biya: <strong>[[:$1]]</strong>
* dosyaya ke ca ra esta: <strong>[[:$2]]</strong>
kerem kere yewna name bıvıcinê',
'fileexists-thumbnail-yes' => "na dosya wina asena ke versiyona yew resmê qıc biyayeya ''(thumbnail)''. [[$1|thumb]]
kerem kerê <strong>[[:$1]]</strong> na dosya konrol bıkerê .",
'file-thumbnail-no' => "nameyê na dosyayi pê ney <strong>$1</strong> dest keno pê.
na manena ke versiyona yew resmê qıc biyaye ya ''(thumbnail)''",
'fileexists-forbidden' => 'no name de yew dosya ca ra esta u ser nuştış nêbeno.
eke şıma qayile dosyaya xo bar keri tepiya agerê u yew nameyo newe bınusi. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'no name de yew dosya hewza ortaxi de ca ra esta.
eke şıma hhene zi qayili dosyaya xo bar keri ager3e u newe yew name bışuxulnê. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Ena pel yew kopyayê ena {{PLURAL:$1|pel|pelan}} o:',
'file-deleted-duplicate' => 'Jû dosya be zey na dosya ([[:$1]]) verê coy esteriyawa.
Semedê ancia barkerdışi dewamkerdış ra ver tarixê esterışê dosya gani qontrol kerê.',
'uploadwarning' => 'Îkazê bar kerdişî',
'uploadwarning-text' => 'Bînê de deskripyonê dosyayî bivurne u reyna qeyd bike.',
'savefile' => 'Dosya qeyd ke',
'uploadedimage' => '"[[$1]]" bar bi',
'overwroteimage' => 'yew versiyonê newvî ye "[[$1]]"î bar bike',
'uploaddisabled' => 'bar kerdişî iptal biyo',
'copyuploaddisabled' => 'URL bar kerdiş kefiliyeyo.',
'uploadfromurl-queued' => 'Bar kerdişê tu ha sira de vindeno.',
'uploaddisabledtext' => 'Bar kerdişê dosyayî iptal biyo',
'php-uploaddisabledtext' => 'barkerdışê dosyayê PHP nıka çino. kerem kere eyarê file_uploads korol bıkerê.',
'uploadscripted' => 'Ena dosya de yew HTML ya zi kodê scriptî este ke belki browserê webî fam nikeno.',
'uploadvirus' => 'Ena dosya de yew virus estê: Qe detayan: $1',
'uploadjava' => 'Dosya, zerre de cıdı jew Java .class dosyaya ZIP esta.
Dosyayn de Java barkerdışi rê icazet nêdeyê, çıkı emeleya merduman nêbena.',
'upload-source' => 'Dosyayê henî',
'sourcefilename' => 'Nameyê dosyaye çimeyî',
'sourceurl' => "URL'yê Çımi",
'destfilename' => 'Destînasyonê nameyêdosya',
'upload-maxfilesize' => 'Ebatêî dosya tewr girdî: $1',
'upload-description' => 'Deskripsiyonê dosyayî',
'upload-options' => 'Tercihanê bar kerdişî',
'watchthisupload' => 'Ena dosya seyr bike',
'filewasdeleted' => 'no name de yew dosya yew wexto nızdi de bar biya u dıma zi serkaran hewn a kerdo. wexya ke şıma dosya bar keni bıewnê no pel $1.',
'filename-bad-prefix' => "name yo ke şıma bar keni zey nameyê kamerayê dijital î, pê ney '''\"\$1\"''' destpêkeno .
kerem kere yewna nameyo eşkera bıvicinê.",
'filename-prefix-blacklist' => ' #<!-- leave this line exactly as it is --> <pre>
# Syntax is as follows:
#   * Everything from a "#" character to the end of the line is a comment
#   * Every non-blank line is a prefix for typical file names assigned automatically by digital cameras
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobile phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- leave this line exactly as it is -->',
'upload-success-subj' => 'bar biyo',
'upload-success-msg' => '[$2] barkerdışê şıma qebul bı. Barkerdışê şımayo itado: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problem bar bike',
'upload-failure-msg' => '[$1] delal: $2 ra barkerdıştê şıman ra jew xelat vıcyayo.',
'upload-warning-subj' => 'İqazê barkerdışi',
'upload-warning-msg' => 'Barkerdış dê [$2] de xırabey vıcyê. Xırabi timar kerdışi re  peyser şırê  [[Special:Upload/stash/$1|heruna barkerdışi]].',

'upload-proto-error' => 'Porotokol raşt ni yo.',
'upload-proto-error-text' => 'Bar kerdişê durî gani  URLî estbiye ke pe <code>http://</code> ya zi <code>ftp://</code> başli beno.',
'upload-file-error' => 'Xeta daxılkiye',
'upload-file-error-text' => 'Peşkeşwan de wexta yew dosya vıraziyayene xeta bı.
kerem kerê [[Special:ListUsers/sysop|serkari]]de irtibat kewe.',
'upload-misc-error' => 'Ğeletê bar kerdişî nizanyeno',
'upload-misc-error-text' => 'wextê barkerdişî de yew xetayo mechul vırazîya.
konrol bıkeri şıma besteyi? Ya zi şıma karo raşt keni?
Eke problem dewam kerd [[Special:ListUsers/sysop|serkari]] de irtibat kewe.',
'upload-too-many-redirects' => 'Eno URL de zaf redireksiyonî esto.',
'upload-unknown-size' => 'Ebat nizanyeno',
'upload-http-error' => 'Yew ğeletê HTTPî biyo: $1',
'upload-copy-upload-invalid-domain' => 'Na domain ra kopyayê barkerdışanê nêbenê.',

# File backend
'backend-fail-stream' => '$1 nê vırazeyê',
'backend-fail-backup' => '$1 nê wendeyê',
'backend-fail-notexists' => '$1 name dı dosya çına.',
'backend-fail-hashes' => 'Şınasiya dosyaya gırotışê cı nêgêriya.',
'backend-fail-notsame' => 'Zey $1 ju dosya xora  esta.',
'backend-fail-invalidpath' => '$1 rayê da depo kerdışa raştay niya.',
'backend-fail-delete' => '$1 nê besterneyê',
'backend-fail-describe' => 'Qande dosya da "$1" metadata nêvurêna.',
'backend-fail-alreadyexists' => "Dosyay $1'ya nêwanêna",
'backend-fail-store' => '$1 ra $2 berdışo nê wanêno',
'backend-fail-copy' => '$1 ra $2 kopya kerdışena dosyayo nêbeno',
'backend-fail-move' => '$1 ra $2 berdışo nê wanêno',
'backend-fail-opentemp' => 'Teferruatê dosyayo nêwanêno',
'backend-fail-writetemp' => 'Dosyaya idari nênusneyê.',
'backend-fail-closetemp' => 'Dosyaya idari nêracneyê',
'backend-fail-read' => 'Na "$1" dosya nê wanêna',
'backend-fail-create' => 'Dosyay $1 nê vırazıyê',
'backend-fail-maxsize' => 'Dosyay $1 aya nênusneyêna feqet gırdeya cı {{PLURAL:$2|bayta|$2 bayto}}',
'backend-fail-readonly' => 'Depo kerdışê "$1" enewke salt wanêno.Sebebê cı zi:"\'\'$2\'\'"',
'backend-fail-synced' => 'Dosyay " $1 " miyan de depo kerdışeyda cıdı pê nêtepıştey esta',
'backend-fail-connect' => 'Depo kerdışê "$1" peyni de nêgrêdeya.',
'backend-fail-internal' => 'Depo kerdışê "$1" peyni de ju xırabin vıcyê.',
'backend-fail-contenttype' => 'Qandê depo kerdışi zerrey babeta dosya da "$1" nêvineya.',
'backend-fail-batchsize' => 'Depo kerdışê  dosya da $1 {{PLURAL:$1|operasyon de|operasyonê}} cı groto; sinorê  {{PLURAL:$2|operasyoni|operasyona}} $2.',
'backend-fail-usable' => 'Dosyay $1 nênusneyê çıkı ratnayışê cı racnayeyo yana karkerdışe cı kemiyo.',

# File journal errors
'filejournal-fail-dbconnect' => 'Depo kerdış de "$1" qande malumatê gurweynayışi cıya irtibat nêkewiya.',
'filejournal-fail-dbquery' => 'Depo kerdış de "$1" qande malumatê gurweynayışi cıyo nêbeno.',

# Lock manager
'lockmanager-notlocked' => 'Dosyay "$1" kılit nêbiya; kesi kılit nêkerda.',
'lockmanager-fail-closelock' => 'Dosyay kıliti nêracneyê "$1".',
'lockmanager-fail-deletelock' => 'Dosyay kıliti nêbesterneyê "$1".',
'lockmanager-fail-acquirelock' => 'Kılitê cı nêgêriya "$1".',
'lockmanager-fail-openlock' => 'Dosyay kıliti nêracneyê qandê "$1".',
'lockmanager-fail-releaselock' => 'Dosyay kıliti nêvıradeyê "$1".',
'lockmanager-fail-db-bucket' => 'Kılite malumat da sitıl de $1 irtibat kewtışi re bes nêkeno.',
'lockmanager-fail-db-release' => 'Malumatê kıliti nêvıradeyê $1.',
'lockmanager-fail-svr-acquire' => 'Kılitê teqdimkarê $1i nêvêniyenê.',
'lockmanager-fail-svr-release' => 'Wasterê kıliti nêvıradeyê $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Dosya ZIP kontrol kerdışi re akerdin de jew xırabin amê.',
'zip-wrong-format' => "Dosyaya ke nışan biya dosyay ZIP'i niya.",
'zip-bad' => 'Dosya xırabiya yana zewbi sebeb ra ZIP dosyaya nêwanêna.
Kontrolê emeleyey oyo veş nêbeno.',
'zip-unsupported' => 'Dosya MediaWiki ra ZIP dosyaya nêwanêna yana derganiya ZIP de cı aya pıro nina. Kontrolê emeleyey oyo veş nêbeno.',

# Special:UploadStash
'uploadstash' => 'Nımıtışê barkerdışi',
'uploadstash-summary' => "Na pela barkerdış (yana hewna barbenayış dı) hema hewna wiki'dedosyeyê ke nêpêseryayê enarê rasayış gre danop. Enê dosyay o ke a dosya keno bar tek o şena a dosya bıvino.",
'uploadstash-clear' => 'Dosyeyê ke idareten bıvıryê ena besternê',
'uploadstash-nofiles' => 'Dosyeyê ke idareten bıvıryê çınyê.',
'uploadstash-badtoken' => 'Karkerdışê cı nêbı, muhtemelen desture şımayê timarkerdışi zeman do şıma ravêrdo. Fına bıcerbnê.',
'uploadstash-errclear' => 'Besternayışê dosyayan nêbı',
'uploadstash-refresh' => 'Listanê dosyayan aneweke',
'invalid-chunk-offset' => 'Ofseto nêravyarde',

# img_auth script messages
'img-auth-accessdenied' => 'Cıresnayış vındarnayo.',
'img-auth-nopathinfo' => 'PATH_INFO kemiyo.
Teqdimkerê şıma seba ravurnayışê nê melumati eyar nêkerdo.
Beno ke be CGI-bıngeyın bo u img_auth rê desteg nêbeno.
https://www.mediawiki.org/wiki/Manual:Image_Authorization Selahiyetê resımi bıvêne.',
'img-auth-notindir' => 'Patikayê ke ti wazeno direktorê bar biyayişî de çin o.',
'img-auth-badtitle' => '"$1" ra nieşkeno yew seroğê raştî virazî.',
'img-auth-nologinnWL' => 'Ti cikewte ni yo u "$1" listeyo sipê de çin o.',
'img-auth-nofile' => "Dosyayê ''$1''î çin o.",
'img-auth-isdir' => '"$1" şıma gêrenî bıresî tiya.
şıma têna eşkenî bıresi dosya.',
'img-auth-streaming' => '"$1" stream keno.',
'img-auth-public' => "img_auth.php'nin fonksiyonê ney; wiki ra dosyaya xususiyan vetışo.
no wiki bı umumi eyar biyo.
qey pawıtışi, img_auth.php battal verdiyayo.",
'img-auth-noread' => 'Heqqa karberanî çino ke "$1" biwendi',
'img-auth-bad-query-string' => "URL'dı ratnayışo nêravêrde esto.",

# HTTP errors
'http-invalid-url' => 'URL raşt niya: $1',
'http-invalid-scheme' => 'URLan ke pê şablonê "$1"i rê destek cini ya.',
'http-request-error' => 'Waştişê tu HTTP de xeta biya seba yew xetayê ke nizanyeno.',
'http-read-error' => 'Wendişê HTTP de xeta esta.',
'http-timed-out' => 'Waştişê HTTP qediya.',
'http-curl-error' => 'Xetayê URLi: $1',
'http-bad-status' => 'Waştişê tu HTTP yew problem biya: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL rê nieşkeno biraso',
'upload-curl-error6-text' => 'URL yo ke nişane biyo nêresiyeno
kerem kerê bıewnê URLyê şıma raşta ya zi bıewnê keyepel akerdeyo.',
'upload-curl-error28' => 'Wextê bar kerdişî qediya',
'upload-curl-error28-text' => 'cewab dayişê no keyepel zaf hereyo.
bıewnê keyepel akerdeyo ya zi bıne vınderê u newe ra tesel bıkerê.
keyepel nıka zaf meşğulo yew dema herayi de newe ra tesel bıkerê.',

'license' => 'Lisans:',
'license-header' => 'Lisansdayış',
'nolicense' => 'Theba nêweçineya',
'license-nopreview' => '(verqeydî çin o)',
'upload_source_url' => '(yew URLê raştî, şar rê akerde yo)',
'upload_source_file' => '(komputerê ti de yew dosya)',

# Special:ListFiles
'listfiles-summary' => 'Na pera bağsiya; heme resima bar biyayeyan mocnena.',
'listfiles_search_for' => 'Qe nameyê medyayî bigêre:',
'imgfile' => 'dosya',
'listfiles' => 'Lista Dosya',
'listfiles_thumb' => 'Resmo qıckek',
'listfiles_date' => 'Deme',
'listfiles_name' => 'Name',
'listfiles_user' => 'Karber',
'listfiles_size' => 'Gırdiye',
'listfiles_description' => 'Sılasnayış',
'listfiles_count' => 'Versiyoni',
'listfiles-show-all' => 'Asayışa versiyonandé verénan',
'listfiles-latestversion' => 'Versiyono verin',
'listfiles-latestversion-yes' => 'E',
'listfiles-latestversion-no' => 'Nê',

# File description page
'file-anchor-link' => 'Dosya',
'filehist' => 'Ravêrdê dosya',
'filehist-help' => 'bıploxne ser yew tarih u aye tarih dı versionê dosya bıvin.',
'filehist-deleteall' => 'pêro bestere',
'filehist-deleteone' => 'bestere',
'filehist-revert' => 'reyna biyere',
'filehist-current' => 'nıkayên',
'filehist-datetime' => 'Tarix/Zeman',
'filehist-thumb' => 'Resmo qıckek',
'filehist-thumbtext' => 'Thumbnail qe versiyonê $1',
'filehist-nothumb' => 'Thumbnail çin o.',
'filehist-user' => 'Karber',
'filehist-dimensions' => 'Ebati',
'filehist-filesize' => 'Ebatê dosyayî',
'filehist-comment' => 'Vacayış',
'filehist-missing' => 'Dosya nieseno',
'imagelinks' => 'Gurenayışê dosya',
'linkstoimage' => 'Ena {{PLURAL:$1|pela|$1 pela}} gıreye ena dosya:',
'linkstoimage-more' => '$1 ra ziyed {{PLURAL:$1|pel|pel}} re gırey dano.
listeya ke ha ver a têna na {{PLURAL:$1|dosyaya ewwili|dosyaya $1 ewwili}} mocnena.
[[Special:WhatLinksHere/$2|pêroyê liste]] mevcud o.',
'nolinkstoimage' => 'Pelanê ser ena dosyayê link biyê çin o.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|Linkanê zafyerî]] ena pele ra link biyo bivîne.',
'linkstoimage-redirect' => '$1 (Dosya raçarnayış) $2',
'duplicatesoffile' => 'a {{PLURAL:$1|dosya|$1 dosya}}, kopyayê na dosyayi ([[Special:FileDuplicateSearch/$2|teferruati]]):',
'sharedupload' => 'Ena dosya $1 ra u belki projeyê binan dı hewitiyeno.',
'sharedupload-desc-there' => 'depoyê $1 u projeyê bini na dosyayi xebıtneni. qey teferruati bıewnê [$2 teferruati dosyayi].',
'sharedupload-desc-here' => 'depoyê $1 u projeyê bini na dosyayi xebıtneni. qey teferruati bıewnê [$2 teferruati dosyayi].',
'sharedupload-desc-edit' => 'Na dosya $1 proceyan dê binandı ke şeno bıgurweyno.
Şıma qayılê ke malumatê cı bıvurnê se şıre [pela da $2 ].',
'sharedupload-desc-create' => 'Na dosya $1 proceyan dê binandı ke şeno bıgurweyno.
Şıma qayılê ke malumatê cı bıvurnê se şıre [pela da $2 ].',
'filepage-nofile' => 'Ena name de dosya çin o.',
'filepage-nofile-link' => 'Ena name de dosya çin o. Feqet ti eşkeno [$1 bar bike].',
'uploadnewversion-linktext' => 'Versiyonê newiyerê ena dosya bar ke',
'shared-repo-from' => '$1 ra',
'shared-repo' => 'yew embarê repositoryî',
'shared-repo-name-wikimediacommons' => 'Wikimedia Commons',
'filepage.css' => '/* CSS placed here is included on the file description page, also included on foreign client wikis */',
'upload-disallowed-here' => 'Şıma nêşenê serê na dosya ra bınusên.',

# File reversion
'filerevert' => '$1 reyna biyere',
'filerevert-legend' => 'Dosya ber weziyet do verên',
'filerevert-intro' => "Ti ho ena dosyayê '''[[Media:$1|$1]]'''î  [$4 versiyonê $3, $2] rê reyna anî.",
'filerevert-comment' => 'Sebeb:',
'filerevert-defaultcomment' => 'Versiyonê $2, $1 rê reyna ard',
'filerevert-submit' => 'Reyna biyere',
'filerevert-success' => "'''[[Media:$1|$1]]''', [$4 versiyonê $3, $2]î reyna berd.",
'filerevert-badversion' => 'Vesiyonê lokalê verniyê eno dosya pê ena pulêwext de çin o.',

# File deletion
'filedelete' => '$1 bestere',
'filedelete-legend' => 'Dosya bestere',
'filedelete-intro' => "Ti ho dosyayê '''[[Media:$1|$1]]'''i u tarixê ey dosyayê hemî estereno.",
'filedelete-intro-old' => "Ti ho versiyonê '''[[Media:$1|$1]]'''i [$4 $3, $2] estereno.",
'filedelete-comment' => 'Sebeb:',
'filedelete-submit' => 'Bestere',
'filedelete-success' => "'''$1'''  esteriyayo.",
'filedelete-success-old' => "Versiyonê'''[[Media:$1|$1]]'''î $3, $2 esteriyayo.",
'filedelete-nofile' => "'''$1''' çin o.",
'filedelete-nofile-old' => "Versiyonê arşivi ye '''$1'''î pê enê detayanê xasî çin o.",
'filedelete-otherreason' => 'Sebebo bin/ilaweyın:',
'filedelete-reason-otherlist' => 'Sebebo bin',
'filedelete-reason-dropdown' => '*sebebê hewna kerdışi
** ihlalê heqê telifi
** Çift/dosyaya kopyayın',
'filedelete-edit-reasonlist' => 'Sebebanê esterıtışi bıvurne',
'filedelete-maintenance' => 'Esterayîş u resterasyonê dosyayî wextê texmirî de nibenê.',
'filedelete-maintenance-title' => 'Dosyaya nêbesterneyêna',

# MIME search
'mimesearch' => 'MIME bigêre',
'mimesearch-summary' => 'no pel, no tewır dosyayan MIME kontrol kena. kewteye: tipa zerreyi/tipa bıni, e.g. <code>resim/jpeg</code>.',
'mimetype' => 'Babetê NIME',
'download' => 'bar ke',

# Unwatched pages
'unwatchedpages' => 'Pelanê seyrnibiyeyî',

# List redirects
'listredirects' => 'Listeya Hetenayışan',

# Unused templates
'unusedtemplates' => 'Şablonê ke nê xebtênê',
'unusedtemplatestext' => 'no pel, {{ns:template}} pelê ke pelê binan de nêaseni, ninan keno.',
'unusedtemplateswlh' => 'linkanê binî',

# Random page
'randompage' => 'Pela raştameyiye',
'randompage-nopages' => 'Ena {{PLURAL:$2|cayêname|cayênameyî}} de enê pelan çin o: $1.',

# Random page in category
'randomincategory' => 'Ğoseri pera kategoriya',
'randomincategory-invalidcategory' => '"$1" namedı kategori çıniya',
'randomincategory-selectcategory-submit' => 'Şo',

# Random redirect
'randomredirect' => 'Xoseri hetenayış',
'randomredirect-nopages' => 'Ena cayênameyê "$1"î de redereksiyonî çin o.',

# Statistics
'statistics' => 'İstatistiki',
'statistics-header-pages' => 'İstatistikê pele',
'statistics-header-edits' => 'Îstatistikê vurnayîşî',
'statistics-header-views' => 'Îstatistiksê vînayîşî',
'statistics-header-users' => 'Îstatistiksê karberî',
'statistics-header-hooks' => 'Îstatistiksê binî',
'statistics-articles' => 'Pelanê tedesteyî',
'statistics-pages' => 'Peli',
'statistics-pages-desc' => 'Pelanê hemî ke wîkî de estê, pelanê mineqeşeyî, redireksiyon ucb... dehil o.',
'statistics-files' => 'Dosyayê bar biye',
'statistics-edits' => '{{SITENAME}} saz kerdış ra hetana newke amora vırnayışan',
'statistics-edits-average' => 'Ser her pele de amarê vurnayîşîyê averageyî',
'statistics-views-total' => 'Yekunî bivîne',
'statistics-views-total-desc' => 'Peleyê ke çınyê yana xısusiyê e nina zerre nêkerdê',
'statistics-views-peredit' => 'Ser her vurnayîşî de vînayîşî',
'statistics-users' => 'Qeyd biye [[Special:ListUsers|karberî]]',
'statistics-users-active' => 'Karberê aktifi',
'statistics-users-active-desc' => '{{PLURAL:$1|roco peyin de|$1 roco peyin de}} karber ê ke kar kerdê.',
'statistics-mostpopular' => 'Pelayanê ke tewr zafî vînî biye',

'pageswithprop' => 'Peli be yew xısusiyetê pele',
'pageswithprop-legend' => 'Peli be yew xısusiyetê pele',
'pageswithprop-text' => 'Na per pimanen pera kena liste.',
'pageswithprop-prop' => 'Nameyo xısusi:',
'pageswithprop-submit' => 'Şo',

'doubleredirects' => 'Hetenayışê dıletıni',
'doubleredirectstext' => 'no pel pelê ray motışani liste keno.
gıreyê her satıri de gıreyi; raş motışê yewın u dıyıni esto.
<del>serê ey nuşteyi</del> safi biye.',
'double-redirect-fixed-move' => '[[$1]] kırışiya, hıni ray dana [[$2]] no pel',
'double-redirect-fixed-maintenance' => 'raçarnayışo dıletê [[$1]] ra  pela da [[$2]] timarêno',
'double-redirect-fixer' => 'Fixerî redirek bike',

'brokenredirects' => 'Hetenayışê vengi',
'brokenredirectstext' => 'Redireksiyonê ey ki pelanê hama çiniyeno ra link dano:',
'brokenredirects-edit' => 'bıvurne',
'brokenredirects-delete' => 'bestere',

'withoutinterwiki' => 'Pelê ke zıwananê binan rê gıreyê cı çıniyo',
'withoutinterwiki-summary' => 'Enê pelî ke versiyonê ziwanî binî ra link nidano.',
'withoutinterwiki-legend' => 'Verole',
'withoutinterwiki-submit' => 'Bımocne',

'fewestrevisions' => 'Pelê be senık çımraviyarnayışi',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bayt|bayti}}',
'ncategories' => '$1 {{PLURAL:$1|Kategori|Kategoriy}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikiy}}',
'nlinks' => '$1 {{PLURAL:$1|link|linkî}}',
'nmembers' => '$1 {{PLURAL:$1|eza|ezayan}}',
'nrevisions' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}}',
'nviews' => '$1 {{PLURAL:$1|vênayış|vênayışi}}',
'nimagelinks' => '$1 {{PLURAL:$1|pele de|pelan de}} gureyeno',
'ntransclusions' => '$1 {{PLURAL:$1|pele de|pelan de}} gureyeno',
'specialpage-empty' => 'Seba na rapore netice çıniyo.',
'lonelypages' => 'Pelê seyi',
'lonelypagestext' => 'Ena pelî link nibiyê ya zi pelanê binî {{SITENAME}} de transclude biy.',
'uncategorizedpages' => 'Pelayanê ke kategorî nibiye',
'uncategorizedcategories' => 'Kategoriyê ke bê kategorîyê',
'uncategorizedimages' => 'Dosyayê ke bê kategoriyê',
'uncategorizedtemplates' => 'Şablonê ke bêkategoriyê',
'unusedcategories' => 'Kategoriyê ke nê xebtênê',
'unusedimages' => 'Dosyeyê ke nê xebtênê',
'popularpages' => 'Pelî ke populer o.',
'wantedcategories' => 'Kategoriye ke waştênê',
'wantedpages' => 'Peleye ke waştênê',
'wantedpages-badtitle' => 'sernuşte meqbul niyo: $1',
'wantedfiles' => 'Dosyeyê cıgeyriyayey',
'wantedfiletext-cat' => 'Dosyaya cêrên karvıstedeya lakin çınya. Mewcud dosyayan de xeriba miyan de liste bena. Xırabiya wınisin dana <del>ateber</del>. Zewbi zi, şırê pela da dosyeyê ke çınyaya [[:$1]].',
'wantedfiletext-nocat' => 'Dosyeyê cêrêni estê lekin karnêvıstê. Dosyeyê xeribi liste benê. bo babeta dano <del>ateber</del>',
'wantedtemplates' => 'Şablonê ke waziyenê',
'mostlinked' => 'Pelî ke tewr zafî lînk bîy.',
'mostlinkedcategories' => 'Kategorî ke tewr zafî lînk bîy.',
'mostlinkedtemplates' => 'Şablonê ke tewr zafî pela re gıre bîye.',
'mostcategories' => 'Pelan ke tewr zaf kategorî estê.',
'mostimages' => 'Dosyayan ke tewr zaf link estê.',
'mostinterwikis' => 'Pelan ke tewr zaf interwiki biyê.',
'mostrevisions' => 'Pelan ke tewr zaf revizyonî biyê.',
'prefixindex' => 'Veroleya peley pêro',
'prefixindex-namespace' => 'Peleyê Veroleyıni ($1 cay nami)',
'prefixindex-strip' => 'Listeya réz bıyayışi',
'shortpages' => 'Pelê kılmeki',
'longpages' => 'Peleyê dergeki',
'deadendpages' => 'Pelê nêgıredayey',
'deadendpagestext' => 'Ena pelan ke {{SITENAME}} de zerrî ey de link çini yo.',
'protectedpages' => 'Pelê pawıtiyey',
'protectedpages-indef' => 'têna pawıteyê bêmuddeti',
'protectedpages-cascade' => 'Kilit biyaye ke teyna cascadiye',
'protectedpagestext' => 'pelê cêrınî pawiyenê',
'protectedpagesempty' => 'pê ney parametreyan pelê pawiteyi çinî',
'protectedtitles' => 'Sernameyê pawıtiyey',
'protectedtitlestext' => 'sernameyê cêrıni pawıte yî',
'protectedtitlesempty' => 'pê ney parametreyan sernuşteyê pawite çinê',
'listusers' => 'Listeyê Karberan',
'listusers-editsonly' => 'Teyna karberan bimucne ke ey nuştê',
'listusers-creationsort' => 'goreyê wextê vıraştışi rêz ker',
'usereditcount' => '$1 {{PLURAL:$1|vurnayîş|vurnayîşî}}',
'usercreated' => '$2 de $1 {{GENDER:$3|viraziya}}',
'newpages' => 'Pelê newey',
'newpages-username' => 'Nameyê karberi:',
'ancientpages' => 'Wesiqeyê ke vurnayışê ciyê peyeni tewr kehani',
'move' => 'Bere',
'movethispage' => 'Na pele bere',
'unusedimagestext' => 'Enê dosyey estê, feqet zerrey yew pele de wedardey niyê.
Xo vira mekerê ke, sıteyê webiê bini şenê direkt ebe URLi yew dosya ra gırê bê, u wına şenê verba gurênayışo feal de tiya hewna lista bê.',
'unusedcategoriestext' => 'kategoriyê cêrıni bıbo zi çı nêşuxulyena.',
'notargettitle' => 'Hedef çini yo',
'notargettext' => 'qey xebıtnayişê ney fonksiyoni şıma yew hedef nişane nêkerd.',
'nopagetitle' => 'wina yew pelê hedefi çin o.',
'nopagetext' => 'pelê hedefi ke şıma nişane kerdo çin o.',
'pager-newer-n' => '{{PLURAL:$1|newiyer 1|newiyer $1}}',
'pager-older-n' => '{{PLURAL:$1|deha kehan 1|deha kehan $1}}',
'suppress' => 'Çımpawıten',
'querypage-disabled' => 'Na pelaya xısusi,sebeb de performansi ra qefılneyê.',

# Book sources
'booksources' => 'Çımey kitaban',
'booksources-search-legend' => 'Ser çımey kitaban bıgeyr',
'booksources-isbn' => 'ISBN:',
'booksources-go' => 'Şo',
'booksources-text' => 'listeya cêrıni, keyepelê kitap rotoxan o.',
'booksources-invalid-isbn' => 'ISBN raşt nêasena bıewnê çımeyê orjinali, raşt kopya biya nê nêbiyaya?',

# Special:Log
'specialloguserlabel' => 'Kerdoğ:',
'speciallogtitlelabel' => 'Menzil (sernuşte yana karber):',
'log' => 'Qeydi',
'all-logs-page' => 'Umumi qeydi pêro',
'alllogstext' => 'qey {{SITENAME}}i mocnayişê heme rocaneyani.
tipa rocaneyi, nameyê karberi (herfa pil u qıci re hessas a), ya zi peli (reyna hessasiyê herfa pil u qıciyi) bıweçine u esayiş qıc kerê.',
'logempty' => 'qaydi de weina yew malumat çino',
'log-title-wildcard' => 'sername yê ke pê ney nuşteyi destkenêpê bıgêr.',
'showhideselectedlogentries' => 'Qeydê weçinayışê bımocne/bınımne dekerê',

# Special:AllPages
'allpages' => 'Peri pêro',
'alphaindexline' => '$1 ra $2ine',
'nextpage' => 'Pela badê cû ($1)',
'prevpage' => 'Pela verêne ($1)',
'allpagesfrom' => 'Pelanê ke be ena herfe dest pêkenê bımocne',
'allpagesto' => 'Pelanê ke be ena herfe qediyenê bımocne:',
'allarticles' => 'Wesiqey pêro',
'allinnamespace' => 'Peli pênro ( $1 cayênameyî)',
'allnotinnamespace' => 'Pelanê hemî ($1 cayênameyî de niyo)',
'allpagesprev' => 'Verên',
'allpagesnext' => 'Bahdo',
'allpagessubmit' => 'Şo',
'allpagesprefix' => 'herfê ke şıma tiya de nuşti, pê ney herfan pelê ke destpêkenê liste ker:',
'allpagesbadtitle' => 'pel o ke şıma kewenî cı, nameyê no peli de gıreyê zıwanan u wikiyi re elaqa esto, ê ra cıkewtış qebul niyo. ya zi sernameyan de karakterê qedexeyi tede esto.',
'allpages-bad-ns' => '{{SITENAME}} keyepel de wina "$1" yew nameyê cayi çino.',
'allpages-hide-redirects' => 'Raçarnaya bınımne',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Şıma rê verhafıza versiyonê na pela aseno, hetana $1 cı kehani.',
'cachedspecial-viewing-cached-ts' => 'Na pela raşt niya, şımayê enewke versiyonê verhafızada na pela vinenê.',
'cachedspecial-refresh-now' => 'Peyêni bıvin.',

# Special:Categories
'categories' => 'Kategoriy',
'categoriespagetext' => '{{PLURAL:$1|kategoriyê|kategoriyê}} cêrıni de pel u media esto.
[[Special:UnusedCategories|kategoriyê ke nê xebtênê]] tiya de nêmociyeni.
hem zi bıewnê [[Special:WantedCategories|kategori yê ke waziyeni]].',
'categoriesfrom' => 'kategori yê ke pê ninan destpêkeno ramocın:',
'special-categories-sort-count' => 'goreyê çendi rêz ker.',
'special-categories-sort-abc' => 'alfabetik rêz ker',

# Special:DeletedContributions
'deletedcontributions' => 'İştiraqê karberan de besternayına',
'deletedcontributions-title' => 'Îştirakê karberî wederna',
'sp-deletedcontributions-contribs' => 'iştıraqi',

# Special:LinkSearch
'linksearch' => 'Gıreyê teberi cı geyrê',
'linksearch-pat' => 'bıgêr motif:',
'linksearch-ns' => 'Heruna naman:',
'linksearch-ok' => 'Cı geyre',
'linksearch-text' => 'Jokeri ê zey "*.wikipedia.org"i benê ke bıgureniyê.
Tewr senık yew sewiya serêna cayê tesiri lazıma, mesela "*.org".<br />
Qeydeyê {{PLURAL:$2|protoqol|protoqoli}}:destegbiyayey: <code>$1</code> (qet yew qeydeyo hesabiyaye http:// ke name nêbiyo).',
'linksearch-line' => '$1, $2 ra link biya',
'linksearch-error' => 'jokeri têna nameyê makina ya serekini de aseni/eseni.',

# Special:ListUsers
'listusersfrom' => 'karber ê ke pey ıney detpêkeni ramocın:',
'listusers-submit' => 'Bımocne',
'listusers-noresult' => 'karber nêdiyayo/a.',
'listusers-blocked' => '(blok biy)',

# Special:ActiveUsers
'activeusers' => 'Listey karberan de aktivan',
'activeusers-intro' => 'Ena yew listeya karberê ke $1 {{PLURAL:$1|roc|rocan}} ra tepya iştiraq kerdo ênan mocneno.',
'activeusers-count' => '$1 peyni {{PLURAL:$3|roz de|$3 rozan de}} $1 {{PLURAL:$1|hereket|hereketi}} kerdê',
'activeusers-from' => 'Enê karberi ra tepya bımocne:',
'activeusers-hidebots' => 'Botan bınımne',
'activeusers-hidesysops' => 'İdarekerdoğan bınımne',
'activeusers-noresult' => 'Karberi nêdiyayê.',

# Special:ListGroupRights
'listgrouprights' => 'heqê grubê karberi',
'listgrouprights-summary' => 'wikiya cêrın a ke tede grubê karberi nişane biyê, listeya heqê cıresayişê inan o.
qey heqê şexsi de [[{{MediaWiki:Listgrouprights-helppage}}|hema malumato ziyed]] belka esto.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Heqa daiye</span>
* <span class="listgrouprights-revoked">Heqa gıreti</span>',
'listgrouprights-group' => 'Grube',
'listgrouprights-rights' => 'Heqqî',
'listgrouprights-helppage' => 'Help:Heqqanê gruban',
'listgrouprights-members' => '(listey ezayan)',
'listgrouprights-right-display' => '<span class="listgrouprights-granted">$1 <code>($2)</code></span>',
'listgrouprights-right-revoked' => '<span class="listgrouprights-revoked">$1 <code>($2)</code></span>',
'listgrouprights-addgroup' => '{{PLURAL:$2|Grube|Gruban}} cı kerê: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|Grube|Gruban}} bıvecê: $1',
'listgrouprights-addgroup-all' => 'şıma hesabê xo re heme gruban eşkeni têare bıkeri',
'listgrouprights-removegroup-all' => 'şıma hesabê xo ra heme gruban eşkeni veci',
'listgrouprights-addgroup-self' => 'Hesabê xo rê {{PLURAL:$2|grube|gruban}} cı kerê: $1',
'listgrouprights-removegroup-self' => 'Hesabê xo ra {{PLURAL:$2|grube|gruban}} bıvecê: $1',
'listgrouprights-addgroup-self-all' => 'şıma eşkeni hesabê xo re heme gruban têare bıkerî',
'listgrouprights-removegroup-self-all' => 'şıma hesabê xo ra eşkeni heme gruban bıveci',

# Email user
'mailnologin' => 'adresa erşawıtışi/ruşnayişi çina.',
'mailnologintext' => 'qey karberanê binan re e-posta erşawıtış de gani şıma [[Special:UserLogin|hesab aker]]ê [[Special:Preferences|pelê tercihani]] de gani yew e-postayo meqbul bıbo.',
'emailuser' => 'Ena karberi rê mesac bırse',
'emailuser-title-target' => 'Na E-postaya {{GENDER:$1|karberi}}ya',
'emailuser-title-notarget' => 'E-postaya karberi',
'emailpage' => 'karberi re e-posta bırışê',
'emailpagetext' => 'Şıma şenê nê formê cêrêni nê {{GENDER:$1|karber}}i rê e-poste rıştış de bıgurenê.
[[Special:Preferences|Tercihanê şımayê karberi]] de adresa e-posteya ke şıma daya, na adrese qısmê adresa e-postey de "kami ra" asena, no sebeb ra gırewtoğ şeno direkt cewab bıdero şıma.',
'usermailererror' => 'xizmetê e-postayi xeta da:',
'defemailsubject' => '"$1" ra e-postay {{SITENAME}} amê',
'usermaildisabled' => 'E-mailê karberani kafiliyeya',
'usermaildisabledtext' => 'Ti nieşkena ena wiki de karberanê binan rê e-mail bişave',
'noemailtitle' => 'adresa e-postayi çina',
'noemailtext' => 'no/na karber yew e-postayo meqbul nêdawa/o',
'nowikiemailtitle' => 'E-postayan re destur çino',
'nowikiemailtext' => 'no/na karber/e, karberanê binani ra gırewtışê e-postayi tercih nêkerd.',
'emailnotarget' => 'Qandê Gêreninamey karberiyo wuna çınyo yana xırabo.',
'emailtarget' => 'Namey Karberi defiyê de.',
'emailusername' => 'Nameyê karberi:',
'emailusernamesubmit' => 'İtaet',
'email-legend' => 'karberê {{SITENAME}} binan re e-posta bıerşaw',
'emailfrom' => 'Kami ra:',
'emailto' => 'Kami rê:',
'emailsubject' => 'Mewzu:',
'emailmessage' => 'Mesac:',
'emailsend' => 'Bırışe',
'emailccme' => 'kopyayekê mesaji mı re bıerşaw',
'emailccsubject' => '$2 kopyaya mesaj a ke şıma erşawıto/a $1:',
'emailsent' => 'E-posta bırşê',
'emailsenttext' => 'e-mailê şıma erşawiya/ruşiya',
'emailuserfooter' => 'na e-posta hetê ıney ra $1 erşawiya $2 no/na karberi/e re. pê fonksiyonê "Karberi/e re e-posta bıerşaw" no {{SITENAME}} keyepeli erşawiya.',

# User Messenger
'usermessage-summary' => 'Mesacê sistemi caverde.',
'usermessage-editor' => 'Mesaj berdoxe sistemi',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist' => 'Lista seyrkerdışi',
'mywatchlist' => 'Lista seyrkerdışi',
'watchlistfor2' => 'Qandê $1 ($2)',
'nowatchlist' => 'listeya temaşa kerdıişê şıma de yew madde zi çina.',
'watchlistanontext' => 'qey vurnayişê maddeya listeya temaşakerdişi $1.',
'watchnologin' => 'Şıma cıkewtış nêvıraşto',
'watchnologintext' => 'qey vurnayişê listeya temaşakerdışi [[Special:UserLogin|gani şıma hesab akeri]].',
'addwatch' => 'Listeyê seyri deke',
'addedwatchtext' => 'Ma pele "[[:$1]]" zerri [[Special:Watchlist|watchlist]]ê tı kerd de.
Ena deme ra, ma qe vurnayışan ser ena pele tı haberdar keni.',
'removewatch' => 'Listedê mınê seyr kerdışi ra hewad',
'removedwatchtext' => 'Ena pela "[[:$1]]" biya wedariya [[Special:Watchlist|listeyê seyr-kerdışi şıma]].',
'watch' => 'Temaşe ke',
'watchthispage' => 'Na pele seyr ke',
'unwatch' => 'Teqib mekerê',
'unwatchthispage' => 'temaşa kerdışê peli vındarn.',
'notanarticle' => 'mebhesê peli niyo',
'notvisiblerev' => 'Revizyon esteriyayo',
'watchlist-details' => '{{PLURAL:$1|$1 pele|$1 peleyan}} listeyê seyr-kerdışi şıma dı, peleyanê vurnayışi dahil niyo.',
'wlheader-enotif' => 'E-mail xeber dayiş abiyo.',
'wlheader-showupdated' => "ziyaretê şıma ye peyini de vuryayişê peli pê '''nuşteyo qalıni''' mocyayo.",
'watchmethod-recent' => 'pel ê ke şıma temaşa kenî vuryayişê peyinê ey konrol beno',
'watchmethod-list' => 'pel ê ke şıma temaşa kenî vuryayişê peyinê ey konrol beno',
'watchlistcontains' => 'listeya seyrkerdışê şıma de $1 tene {{PLURAL:$1|peli|peli}} estî.',
'iteminvalidname' => "pê no '$1' unsuri problem bı, nameyo nemeqbul...",
'wlnote' => "$3 seate u bahde $4 deqa dıma {{PLURAL:$2|ju seate dı|'''$2''' ju seate dı}} {{PLURAL:$1|vurnayışe peyeni|vurnayışe '''$1''' peyeni}} cêrdeyê",
'wlshowlast' => 'Peyni de vurnayışan ra  $1 seata u $2 roca $3 bımocnê',
'watchlist-options' => 'Tercihê liste da seyri',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Seyr ke...',
'unwatching' => 'Seyr meke...',
'watcherrortext' => 'Sazanê listeda seyri vurnayış de pox ta "$1" xırabey vıcyê .',

'enotif_mailer' => 'postaya xeberdayişi {{SITENAME}}',
'enotif_reset' => 'Pela pêro ziyaret kerde deye mor ke',
'enotif_impersonal_salutation' => '{{SITENAME}} karber',
'enotif_subject_deleted' => '{{SITENAME}} de pera $1 {{gender:$2|$2}} esterıt.',
'enotif_subject_created' => '{{SITENAME}} de pera $1 {{gender:$2|$2}} vıraşt',
'enotif_subject_moved' => '{{SITENAME}} de  pera $1 {{gender:$2|$2}} berd',
'enotif_subject_restored' => '{{SITENAME}} de pera $1 {{gender:$2|$2}} timar ke',
'enotif_subject_changed' => '{{SITENAME}} de pera $1 {{gender:$2|$2}} vurne',
'enotif_body_intro_deleted' => 'Pela {{SITENAME}} terefê $1, $2 ra roca $PAGEEDITDATE de {{GENDER:$2|esteriye}}, bıvênên: $3.',
'enotif_body_intro_created' => '{{SITENAME}} de pera $1 $PAGEEDITDATE de {{gender:$2|$2}}i vıraşt, rewizyonê $3 bıvin.',
'enotif_body_intro_moved' => '{{SITENAME}} de pera $1 $PAGEEDITDATE de {{gender:$2|$2}}i berd, rewizyonê $3 bıvin.',
'enotif_body_intro_restored' => '{{SITENAME}} de pera $1 $PAGEEDITDATE de {{gender:$2|$2}}i timar ke, rewizyonê $3 bıvin.',
'enotif_body_intro_changed' => '{{SITENAME}} de pera $1 $PAGEEDITDATE de {{gender:$2|$2}}i vurne, rewizyonê $3 bıvin.',
'enotif_lastvisited' => 'ziyareta şıma ye peyini ra nata heme vuryayiş ê ke biyê bıewnê $1i re..',
'enotif_lastdiff' => 'qey vinayişê ney vurnayişi bıewnê pelê $1i',
'enotif_anon_editor' => 'karbero anonim $1',
'enotif_body' => 'Erciyayê $WATCHINGUSERNAME,

$PAGEINTRO $NEWPAGE

eniya timaroği: $PAGESUMMARY $PAGEMINOREDIT

Timaroğiya irtibat:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

no pel o ke behs beno heta ziyaret kerdışê yewna heli, mesajê vuriyayişi nêşawiyeno.

           {{SITENAME}} sistemê hişyariyê keyepeli.

--
Qey vurnayişê eyari:
{{canonicalurl:{{#Special:Watchlist/edit}}}}

Qey vurnayişê eyaran de lista seyri:
{{canonicalurl:{{#special:EditWatchlist}}}}

Qey wedarayişê ena pele liste xo ra seyr kerdişi, şo
$UNWATCHURL

Qey hemkari u pêşniyazi:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'viraziya',
'changed' => 'vurneya',

# Delete
'deletepage' => 'Pele bestere',
'confirm' => 'Testiq ke',
'excontent' => "Zerrey cı: '$1'",
'excontentauthor' => "Zerrey cı: '$1' no/na ('[[Special:Contributions/$2|$2]]'  teyna iştıraq kerdo)",
'exbeforeblank' => "behsê verê esteriyayişi: '$1'",
'exblank' => 'zerreyê peli vengo',
'delete-confirm' => '"$1" bestere',
'delete-legend' => 'Bestere',
'historywarning' => "'''Teme:''' Pela ke şıma esterenê tede yew viyarte be teqriben $1 {{PLURAL:$1|versiyon esto|versiyoni estê}}:",
'confirmdeletetext' => 'Tı ho yew pele u tarixê pele wederneno.
Tı ra rica keno, tı zani tı ho sekeno, tı zani neticeyanê eno wedarnayışi u tı zani tı ser [[{{MediaWiki:Policy-url}}|poliçe]] kar keno.',
'actioncomplete' => 'Xebten temam biyo',
'actionfailed' => 'kar nêbı',
'deletedtext' => '"$1" biya wedariya.
Qe qeydê wedarnayışi, $2 bevinin.',
'dellogpage' => 'Qeydê esterniye',
'dellogpagetext' => 'listeya cêrıni heme qaydê hewn a kerdeyan o.',
'deletionlog' => 'qaydê hewnakerdışani',
'reverted' => 'revizyono verin tepiya anciyayo',
'deletecomment' => 'Sebeb:',
'deleteotherreason' => 'Sebebo bin:',
'deletereasonotherlist' => 'Sebebo bin',
'deletereason-dropdown' => '*Sebebé esterıti
** Spam
** Vandalizm
** İhlala heqdé telifi
** Waştışé nustoği
** Xırab hetenayış',
'delete-edit-reasonlist' => 'Sebebê vurnayışan bıvurne',
'delete-toobig' => 'no pel, pê $1 {{PLURAL:$1|tene vuriyayiş|tene vuriyayiş}}i wayirê yew tarixo kehen o.
qey hewna nêşiyayişi wina pelani u {{SITENAME}}nêxerebnayişê keyepeli yew hed niyaya ro.',
'delete-warning-toobig' => 'no pel wayirê tarixê vurnayiş ê derg o, $1 {{PLURAL:$1|revizyonê|revizyonê}} seri de.
hewn a kerdışê ıney {{SITENAME}} şuxul bıne gırano;
bı diqqet dewam kerê.',

# Rollback
'rollback' => 'vurnayişan tepiya bıger',
'rollback_short' => 'Peyser bia',
'rollbacklink' => 'peyser bia',
'rollbacklinkcount' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}} peyd gıroti',
'rollbacklinkcount-morethan' => '$1 {{PLURAL:$1|vurnayış|vuranyışi}} tewr peyd gırot',
'rollbackfailed' => 'Peyserardış nêbi',
'cantrollback' => 'karbero peyin têna paşt dayo, no semedi ra vuriyayiş tepiya nêgeriyeni.',
'alreadyrolled' => '[[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}} hetê [[Special:Contributions/$2|{{int:contribslink}}]]) ra pelê ıney[[:$1]] de vurnayiş biyo u no vurnayiş tepiya nêgeriyeno;
yewna ten pel de vurnayiş kerdo u pel tepiya nêgeriyeno.

oyo ke vurnayişo peyin kerdo: [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "kılmnuşteyê vurnayişibi: \"''\$1''\".",
'revertpage' => 'Hetê [[Special:Contributions/$2|$2]] ([[User talk:$2|Mesac]]) ra vurnayiş biyo u ney vurnayişi tepiya geriyayo u no [[User:$1|$1]] kes o ke cuwa ver revizyon kerdo revizyonê no kesi tepiya anciyayo.',
'revertpage-nouser' => 'No keso ke vuriyayiş kerdo vuriyayişé{{GENDER:$1|[[User:$1|$1]]}} ker o',
'rollback-success' => 'vurnayişê no kesi $1 tepiya geriyayo u hetê no
$2 kesi ra cıwa ver o ke revizyon biyo no revizyon tepiya anciyayo.',

# Edit tokens
'sessionfailure-title' => 'Seans xeripiya',
'sessionfailure' => 'cıkewtışê hesabê şıma de yew problem aseno;
no kar semedê dızdiyê hesabi ibtal biyo.
kerem kerê "tepiya" şiyerê u pel o ke şıma tera ameyî u o pel newe ra bar kerê , newe ra tesel/cereb kerê.',

# Protect
'protectlogpage' => 'Qeydê staryayan',
'protectlogtext' => 'Şıma vurnayişê gırewtışê/wedarnayışê pawıtişi vinenê.
Qey malumato ziyede [[Special:ProtectedPages|Peleyê ke star biye]] bewni rê êna .',
'protectedarticle' => '"[[$1]]" kılit biyo',
'modifiedarticleprotection' => 'Qe "[[$1]]", seviye kılit kerdişi vurnayi biyo',
'unprotectedarticle' => 'Starkerdışê "[[$1]]" hewadeya',
'movedarticleprotection' => 'eyarê pawıtışi no "[[$2]]" peli ra kırışiya no "[[$1]]" peli',
'protect-title' => 'qey "$1" yew seviyaya pawıtışi bıvıcinê',
'protect-title-notallowed' => 'Star kerdış sewiyeyê "$1" bıvinê',
'prot_1movedto2' => 'nameyê [[$1]] peli yo newe: [[$2]]',
'protect-badnamespace-title' => 'Heruna naman itad starêna',
'protect-badnamespace-text' => 'Na herunda namide peley nêstarênê.',
'protect-norestrictiontypes-text' => 'Na perdi mahne esto cokira tipeci nikarnina',
'protect-norestrictiontypes-title' => 'Pera starneyin',
'protect-legend' => 'Pawıtışi araşt ke',
'protectcomment' => 'Sebeb:',
'protectexpiry' => 'Qediyeno:',
'protect_expiry_invalid' => 'Demo qediyayışi raşt niyo.',
'protect_expiry_old' => 'Demo qediyayışi tarix dı.',
'protect-unchain-permissions' => 'Zobina tercihanê mıhafezekerdışi kilıt meke',
'protect-text' => "Tı eşkeno bıvin u seviyê kılit-kerdışi bıvin '''$1'''.",
'protect-locked-blocked' => "seviyeya qedexe biyayeyan nevuriyeno.
'''$1''' eyarê peli:",
'protect-locked-dblock' => "semedê kılidê database ya aktifi şıma neeşkeni seviyeya pawıtışi buvurni.
'''$1''' eyarê no peli:",
'protect-locked-access' => "Karber hesabê şıma nêşeno  staryaye sewiyey ena peler bıvurno.
Hesıbyayê sazê pela da '''$1''' enêyê:",
'protect-cascadeon' => 'Ena pele nıka kılit biya. Çınki ena pele zerre listeyê {{PLURAL:$1|pele, ki|peleyan, which li}} bınê "cascading protection"iyo.
Tı eşkeno seviyeye kılit kerdışi bıvurno, feqat tı nıeşken "cascading protection"i bıvurno.',
'protect-default' => 'Destur bıde karberan pêrune',
'protect-fallback' => 'Tenya karberanê be izna "$1" rê destur bıde',
'protect-level-autoconfirmed' => 'Karberanê neweyan u qeyd-nêbiyaoğan kılit ke',
'protect-level-sysop' => 'Tenya idarekeran rê destur bıde',
'protect-summary-cascade' => 'çırrayış',
'protect-expiring' => 'qediyeno $1 (UTC)',
'protect-expiring-local' => '$1 do bı qedyo',
'protect-expiry-indefinite' => 'bê hed u hesab',
'protect-cascade' => 'Ena pela dı pelayan kılit-biya ca geno (cascading protection)',
'protect-cantedit' => 'Tı nêşenay sinorê kılit-biyayışê ena pele bıvurnê, çıke desturê to be vurnayışi çıniyo.',
'protect-othertime' => 'Wextê binî:',
'protect-othertime-op' => 'wextê binî',
'protect-existing-expiry' => 'wextê qediyayişi yê mewcudi: $3, $2',
'protect-otherreason' => 'sebebo bin/sebebê ilaveyi',
'protect-otherreason-op' => 'Sebebo bin',
'protect-dropdown' => '*sebebê pawıtışi ye pêroyiye
** vandalizmo hed ra vecaye
** spamo hed ra vecaye
** şêrê/herbê vurnayişi
** pel o ke zaf wayirê trafiki yo',
'protect-edit-reasonlist' => 'sebebê pawıtışi bıvurn',
'protect-expiry-options' => '1 seet:1 hour,1 roc:1 day,1 hefte:1 week,2 hefteyi:2 weeks,1 aşme:1 month,3 aşmî:3 months,6 aşmî:6 months,1 serre:1 year,bê hedd u hesab:infinite',
'restriction-type' => 'Destur:',
'restriction-level' => 'Sinorê desturi:',
'minimum-size' => 'Ebatê minumî',
'maximum-size' => 'Ebatê maximumî',
'pagesize' => '(bitî)',

# Restrictions (nouns)
'restriction-edit' => 'Bıvurne',
'restriction-move' => 'Bere',
'restriction-create' => 'İycad ke',
'restriction-upload' => 'Bar ke',

# Restriction levels
'restriction-level-sysop' => 'tam pawiyayo',
'restriction-level-autoconfirmed' => 'nêm pawiyayo',
'restriction-level-all' => 'heme yew sewiya',

# Undelete
'undelete' => 'Peleyê ke besterneyayê enê bımocnê',
'undeletepage' => 'bıewn revizyonê peli yê hewn a şiyayeyan u tepiya biyar',
'undeletepagetitle' => "'''pelo [[:$1|$1]] cêrın, wayirê revizyonê hewn a şiyayeyan o'''.",
'viewdeletedpage' => 'bıewn pelê hewn a şiyayeyani',
'undeletepagetext' => '{{PLURAL:$1|pelo|$1 pelo}} cerın hewn a şiyo labele hema zi arşiv de yo u tepiya geriyeno.
Arşiv daimi pak beno.',
'undelete-fieldset-title' => 'revizyonan tepiya bar ker',
'undeleteextrahelp' => "Qey ardışê pel u verê pelani tuşê '''tepiya biya!'''yi bıtıknê. qey ciya ciya ardışê verê pelani zi qutiye tesdiqi nişane kerê u tuşê '''tepiya biya!'''yi bıtıknê '''''{{int:undeletebtn}}'''''.. qey hewn a kerdışê qutiya tesdiqan u qey sıfır kerdışê cayê sebebani zi tuşê '''agêr caverd/aça ker'''i bıtıknê '''''{{int:undeletebtn}}'''''..",
'undeleterevisions' => '$1 {{PLURAL:$1|revizyon|revizyon}} arşiw bi',
'undeletehistory' => 'eke şıma pel tepiya biyari heme revizyonî zi tepiya yeni.
eke yew pel hewn a biyo u pê nameyê o peli newe ra yew pel bıvıraziyo, revizyonê o pelê verıni zerreyê no pel de aseno.',
'undeleterevdel' => 'eke pelo serın de netice bıdo ya zi revizyoni qısmen hewn a bıbiy hewn a kerdışi tepiya nêgeriyeno.',
'undeletehistorynoadmin' => 'na madde hewn a biya. sebebê hewna kerdışi u teferruatê karber ê ke maddeyi vıraştı cêr de diyayî. revizyonê hewn a biyayeyani têna serkari vineni',
'undelete-revision' => 'hetê ıney $3 ra revizyonê pelê ıney $1 hewn a biyo, nêy revizyoni ($4 tarixi ra nat, $5 seeti de):',
'undeleterevision-missing' => 'revizyonê nemeqbul u vindbiyayeyi.
Revizyoni ya hewn a biyê ya arşiw ra veciyayê ya zi cıresayişê şımayi şaş o.',
'undelete-nodiff' => 'revizyonê verıni nidiya',
'undeletebtn' => 'Timar bike',
'undeletelink' => 'bıvêne/peyser bia',
'undeleteviewlink' => 'bıvin',
'undeletereset' => 'Reset kerê',
'undeleteinvert' => 'vicnayeyi qeldaye açarn',
'undeletecomment' => 'Sebeb:',
'undeletedrevisions' => 'pêro piya{{PLURAL:$1|1 qeyd|$1 qeyd}} tepiya anciya.',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revizyon|$1 revizyon}} u {{PLURAL:$2|1 dosya|$2 dosya}} ameyê halê xo yê verıni',
'undeletedfiles' => '{{PLURAL:$1|1 dosya|$1 dosya}} tepiya anciyayi.',
'cannotundelete' => 'Besternayışo nêbeno:
$1',
'undeletedpage' => "'''$1 pel tepiya anciya'''

qey karê tepiya ardışi u qey karê hewn a kerdışê verıni bıewnê [[Special:Log/delete|qeydê hewn a kerdışi]].",
'undelete-header' => 'Peleyê ke veror de besterneyayê êna bıvinê: [[Special:Log/delete|qeydê esterneya]].',
'undelete-search-title' => 'Bıgeyre pelanê eserıtiyan',
'undelete-search-box' => 'bıgêr pelê hewn a biyayeyani',
'undelete-search-prefix' => 'pel ê ke pê ney destpêkenî, ramocın',
'undelete-search-submit' => 'Cı geyre',
'undelete-no-results' => 'Zerre arşîvê esterayîşî de peleyan match nibiyê.',
'undelete-filename-mismatch' => 'Vurnayîşê ke pê wextê puli ye $1î nieşkenî biyare: nameyê dosyayî match nibeno',
'undelete-bad-store-key' => 'Vurnayîşê ke pê wextê puli ye $1î nieşkenî biyare: verniyê esterayîşî de dosyayî vînî biya.',
'undelete-cleanup-error' => 'Eka dosyayê arşîvî "$1"î ke ho wedariyeno feqet yew ğelet biya.',
'undelete-missing-filearchive' => 'arşiwê IDyê yi dosyayi $1 tepiya niyeno çunke database de niyo.
belka cıwa ver hewn a biyo..',
'undelete-error' => 'Besternayışê peyd bıgi pela de xırabin vıcyê',
'undelete-error-short' => 'Eka dosyayê biyereno feqet yew ğelet biya: $1',
'undelete-error-long' => 'hewn a kerdışê na dosyayi wexta tepiya geriyenê xeta vıraziya:

$1',
'undelete-show-file-confirm' => '"<nowiki>$1</nowiki>" şıma emin î dosyaya revizyonê no $2 $3 tarixi bıvini?',
'undelete-show-file-submit' => 'E',
'undelete-revisionrow' => '$1 $2 ($3) $4 . . $5 $6 $7',

# Namespace form on various pages
'namespace' => 'Heruna naman:',
'invert' => 'Weçinıtışo peyserki',
'tooltip-invert' => 'nameyo ke nışan biyo (u nameyo elekeyın zi nışanyyayo se) vurnayışan  zerrekan nımtışi re ena dore tesdiqi nışan kerê',
'namespace_association' => 'Cayê nameyanê elaqedaran',
'tooltip-namespace_association' => 'Herunda canemiya elekeyın nışan kerdışi sero qıse kerdışi yana zerre dekerdışi rê ena dora tesdiqi nışan kerê',
'blanknamespace' => '(Ser)',

# Contributions
'contributions' => 'İştıraqê {{GENDER:$1|karber}}i',
'contributions-title' => 'Dekerdenê karber de $1',
'mycontris' => 'İştıraqi',
'contribsub2' => 'Qandê {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'Ena kriteriya de vurnayîş çini yo.',
'uctop' => '(weziyet)',
'month' => 'Aşm:',
'year' => 'Ser:',

'sp-contributions-newbies' => 'Tenya iştıraqanê karberanê neweyan bımocne',
'sp-contributions-newbies-sub' => 'Qe hesebê newe',
'sp-contributions-newbies-title' => 'Îştîrakê karberî ser hesabê neweyî',
'sp-contributions-blocklog' => 'Qeydê kılitkerdışi',
'sp-contributions-deleted' => 'iştırakê karberiê esterıtey',
'sp-contributions-uploads' => 'barkerdey',
'sp-contributions-logs' => 'qeydi',
'sp-contributions-talk' => 'werênayış',
'sp-contributions-userrights' => 'İdareyê heqanê karberan',
'sp-contributions-blocked-notice' => 'verniyê no/na karber/e geriyayo/a
qê referansi qeydê vernigrewtışi cêr de eşkera biyo:',
'sp-contributions-blocked-notice-anon' => 'Eno adresê IPi bloke biyo.
Cıkewtışo tewr peyêno ke bloke biyo, cêr seba referansi belikerdeyo:',
'sp-contributions-search' => 'Dekerdena cı geyrê',
'sp-contributions-username' => 'Adresa IPy ya zi nameyê karberi:',
'sp-contributions-toponly' => 'Tenya rewizyonanê tewr peyniyan bimocne',
'sp-contributions-submit' => 'Cı geyre',

# What links here
'whatlinkshere' => 'Gıreyê pele',
'whatlinkshere-title' => 'Per da "$1" rê perê ke gre danê',
'whatlinkshere-page' => 'Pele:',
'linkshere' => "Ena peleyan grey biya '''[[:$1]]''':",
'nolinkshere' => "Per da '''[[:$1]]''' rê pera ke gıre dana çıniya.",
'nolinkshere-ns' => "Ena cayê nameyî de yew pel zi '''[[:$1]]''' rê link nibeno.",
'isredirect' => 'pera hetenayışi',
'istemplate' => 'Açarnayene',
'isimage' => 'gıreyê dosya',
'whatlinkshere-prev' => '{{PLURAL:$1|veror|veror $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|verni|verni $1}}',
'whatlinkshere-links' => '← gırey',
'whatlinkshere-hideredirs' => 'Hetenayışê $1',
'whatlinkshere-hidetrans' => 'Açarnayışê $1',
'whatlinkshere-hidelinks' => 'Greyê $1',
'whatlinkshere-hideimages' => 'Gıreyê dosya $1',
'whatlinkshere-filters' => 'Avrêci',

# Block/unblock
'autoblockid' => 'Otomatik vındarnayış #$1',
'block' => 'Karberi vındarne',
'unblock' => 'Hesabê karberi akerê',
'blockip' => 'Karberi kılit ke',
'blockip-title' => 'Karberi kılit ke',
'blockip-legend' => 'Karber blok bike',
'blockiptext' => 'pê şuxulnayişê formê cêrıni, şıma eşkeni verniyê vurnayişkerdışê yew karberi ya zi yew IPyi bıgêrî. No têna qey verni-gırewtışê vandalizmiyo u gani şıma [[{{MediaWiki:Policy-url}}|qaydeyan]] re diqqet bıkeri. cêr de muheqqeq sebebê verni-grewtışi bınusi. (mesela: -nê- pelani de vandalizm kerdo).',
'ipadressorusername' => 'Adresa IPy ya zi nameyê karberi:',
'ipbexpiry' => 'Qedyayış:',
'ipbreason' => 'Sebeb:',
'ipbreasonotherlist' => 'Sebebê bini',
'ipbreason-dropdown' => '*sebebê verni-grewtışi yê pêroyi
** malumatê şaş têare kerdış
** Zerreyê pelan vetış
** keyepelê teberi re gırey eştış
** pelani re qıseyê tewşan(toşan) eştış
** Tehditwari hereket/Taciz
** yew ra ziyed hesaban xırab şuxulnayiş
** nameyê karberi yo ke meqbul niyo',
'ipb-hardblock' => 'KArberê ke ena IP ra dekewte de wa vurnayış nêkerê',
'ipbcreateaccount' => 'Hesab viraştişi blok bik',
'ipbemailban' => 'Ena karber rê destur medî  ke ay e-mail neşiravî',
'ipbenableautoblock' => 'verniyê IPadresa peyin ê no karberi u wexta ke vurnayişi kerd ê IPadresani otomotik bıger.',
'ipbsubmit' => 'Ena karber blok bike',
'ipbother' => 'Waxtê bini:',
'ipboptions' => '2 seat:2 hours,1 roc:1 day,3 roci:3 days,1 hefte:1 week,2 heftey:2 weeks,1 aşm:1 month,3 aşm:3 months,6 aşmi:6 months,1 ser:1 year,ebedi:infinite',
'ipbotheroption' => 'bini',
'ipbotherreason' => 'Sebebê bini:',
'ipbhidename' => 'Nameyê karberî listeyan u vurnayîşan ra binumne',
'ipbwatchuser' => 'Pela miniqaşe u pela ena karberî seyr bike',
'ipb-disableusertalk' => 'No karber wexto ke bloqedeyo wa pela da xodı vurnayış kerdışi rê izin medı',
'ipb-change-block' => 'Pê ena ayaran, karberî reyna bloke bike',
'ipb-confirm' => 'Bloke kerdışi tesdik ke',
'badipaddress' => 'Adresê IPî raşt niyo',
'blockipsuccesssub' => 'Blok biyo',
'blockipsuccesstext' => 'Verniya [[Special:Contributions/$1|$1]] gêriyaya.
<br />Qey çım ra viyarnayişê verni-grewtışi bewni [[Special:BlockList|Ê yê ke verniyê IP adresê cı gêriyaya]].',
'ipb-blockingself' => 'Şımayê kenê ke xo bloke kerê! Şıma qayılye xo bloke kerê?',
'ipb-confirmhideuser' => 'Wexto ke "karberi bınımnê" nışandeyo se şıma ye kenê karberi bloke kerê. No, Namey karberi lista pêron dı u dekewtışê rocekan dı aktiv bo.Şıma qayıli ney bıkerê?',
'ipb-edit-dropdown' => 'Sebebê blokî bivurne',
'ipb-unblock-addr' => '$1 a bik',
'ipb-unblock' => 'Yew adresê IPî ya zi nameyê karberî blok bike',
'ipb-blocklist' => 'Blokî ke hama estê ey bivîne',
'ipb-blocklist-contribs' => 'Ser $1 îştîrakî',
'unblockip' => 'Hesabê karberî a bike',
'unblockiptext' => 'Cıreştışê nuştışê IP ya zi karberio ke ver ra gêriyayo, seba peyser barkerdışi dey rê formê cêrêni bıgurenên.',
'ipusubmit' => 'Enê kılitkerdışi wedare',
'unblocked' => '[[User:$1|$1]] blok biyo',
'unblocked-range' => "Blokey $1'i wederya",
'unblocked-id' => 'Blokê $1î wedariyayo',
'blocklist' => 'Karberê kılitbiyayey',
'ipblocklist' => 'Karberê kılitbiyayey',
'ipblocklist-legend' => 'Yew karberê kılitbiyayey bıvêne',
'blocklist-userblocks' => 'Kılitkerdışê hesaban bınımne',
'blocklist-tempblocks' => 'Kılitkerdışan mıweqet bınımne',
'blocklist-addressblocks' => 'Tenya kılitkerdışanê IPy bınımne',
'blocklist-rangeblocks' => 'Kılitkerdışanê rêzkiyan bınımne',
'blocklist-timestamp' => 'İmzay demi',
'blocklist-target' => 'Menzil',
'blocklist-expiry' => 'Wahdey qedyayışi',
'blocklist-by' => 'hizmetdarê blokê',
'blocklist-params' => 'Parametreyê wedernayışi',
'blocklist-reason' => 'Sebeb',
'ipblocklist-submit' => 'Cı geyre',
'ipblocklist-localblock' => 'blokê mehelli',
'ipblocklist-otherblocks' => '{{PLURAL:$1|blokê|blokê}} bini',
'infiniteblock' => 'ebedî',
'expiringblock' => 'roca $1i saeta $2i de qediyena',
'anononlyblock' => 'teyna karbero anonim',
'noautoblockblock' => 'otoblok nihebitîyeno',
'createaccountblock' => 'Hesab viraştîş blok biyo',
'emailblock' => 'e-mail blok biyo',
'blocklist-nousertalk' => 'ti nieşken pele minaqaşe xo bivurne',
'ipblocklist-empty' => 'Lista kılitkerdışi venga.',
'ipblocklist-no-results' => 'Adresa IPya waştiye ya zi namey karberi kılit nêbiyo.',
'blocklink' => 'kılit ke',
'unblocklink' => 'a ke',
'change-blocklink' => 'kılitkerdışi bıvurne',
'contribslink' => 'iştıraqi',
'emaillink' => 'e-poste bırışe',
'autoblocker' => 'Şıma otomatikmen kılit biy, çıke adresa şımawa \'\'IP\'\'y terefê "[[User:$1|$1]]" gureniyena.
Sebebê kılit-biyayışê $1\'i: "$2"o',
'blocklogpage' => 'Qeydê bloqi',
'blocklog-showlog' => 'verniyê no/na karberi cıwa ver geriyayo/ya.',
'blocklog-showsuppresslog' => 'verniyê no/na karberi cıwa ver geriyayo/ya.',
'blocklogentry' => 'Karberê [[$1]] ke bloqe, bloqey cı hetana $2 $3 do bıramo.',
'reblock-logentry' => 'qey [[$1]]i tarixê qediyayişi $2 $3 pa ninan a eyarê ver-grewtışan vurna.',
'blocklogtext' => "No kuliyatê kılitkerdış u rakerdışê fealiyetê karberano.
Adresê IP'ya ke otomatikmen kılit biyê lista de çıniya.
Seba lista karberanê ke heta nıka kılit biyê [[Special:BlockList|lista kılitkerdışê IPy]] bıvinê.",
'unblocklogentry' => '$1 ake',
'block-log-flags-anononly' => 'karberê anomini tenya',
'block-log-flags-nocreate' => 'akerdışê hesabi racneyayo',
'block-log-flags-noautoblock' => 'Oto-wedariye terkneyayo',
'block-log-flags-noemail' => 'e-posta biya bloqe',
'block-log-flags-nousertalk' => 'Pela verênayişi ke xo nêşeno bıvurno',
'block-log-flags-angry-autoblock' => 'oto-wedariye amayen aktivo',
'block-log-flags-hiddenname' => 'nameyê karberi nımteyo',
'range_block_disabled' => 'Desturê administorî ke viraştişê blokê rangeyî kefiliyo.',
'ipb_expiry_invalid' => 'Wextê qediyayışi nêvêreno.',
'ipb_expiry_temp' => 'Kılitbiyayışê karberê nımıtey gani ebedi bo.',
'ipb_hide_invalid' => 'hesabê karberi pinani nêbeno; belka semedê zaf vurnayişi ra yo.',
'ipb_already_blocked' => '"$1" zaten blok biya',
'ipb-needreblock' => '$1 xora engel biyo. Tı wazenay eyaran bıvurnê?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Kılitkerdışo bin|Kılitkerdışê bini}}',
'unblock-hideuser' => 'NAmeyê karberi nımneyayo qandê coy şıma nêşenê bloqey cı wedarnê.',
'ipb_cant_unblock' => 'xeta: IDyê ver-grewtışi $1 nêesa/asa.
belka ver-grewtış wedariyayo.',
'ipb_blocked_as_range' => 'xeta: $1 verniyê IPadresi direk nêgeriyayo u ver-gırewtışi nêwedariyeno .
labele parçeya benateyê na $2 adresibi u ey ra ver-geryayo u şıma eşkeni no wedari.',
'ip_range_invalid' => 'Rêza IPi nêvêrena.',
'ip_range_toolarge' => 'Menzilan ke /$1 ra girdêrê inan rê izin nidano.',
'proxyblocker' => 'blokarê proxyi',
'proxyblockreason' => 'IPadresa şıma yew proxyo akerdeyo u ey ra verniyê ey geriya.',
'sorbs' => 'DNSBL',
'sorbsreason' => 'IP adresa şıma, hetê no {{SITENAME}} keyepeli ra  DNSBL de proxy hesibyayo u liste biyo.',
'sorbs_create_account_reason' => 'IP adresa şıma, hetê no translatewiki.net keyepeli ra DNSBL de proxy hesibyayo u liste biyo.

şıma neeşkeni hesab bıvırazi',
'cant-block-while-blocked' => 'Ê ye ke verniyê şıma gırewtî şıma nêeşkeni verniyê ninan bıgeri',
'cant-see-hidden-user' => 'karber o ke şıma gêreni verniyê ey bıgeri ca ra verniyê ey gırewteyo u pinani kerdeyo.',
'ipbblocked' => 'Ti nieşkena karberanê binan bloke bike ya zi a bike cunki ti bloke biya',
'ipbnounblockself' => 'Ti nieşkena xo a bike',

# Developer tools
'lockdb' => 'Database kilit bik',
'unlockdb' => 'Database a bik',
'lockdbtext' => 'qefelnayişê databaseyi: pelê pêro karberan, tercihê ninan uêb vındarneno.
eke şıma ıney gure keni u şıma xo ra emini, taahhud bıde wexta gure şıma qediya şıma database keni a.',
'unlockdbtext' => 'akerdışê qeflıkê databaseyi; pêro karberani re pelan keno a, imkanê vurnayişê tercih u listeya temaşakerdışi dan.
şıma raşta qayili no gure bıkeri u eke şıma qayili teyid bıkerê.',
'lockconfirm' => 'Ya, ez wazene database kilit bikeri.',
'unlockconfirm' => 'Ya, ez wazene database a bikeri.',
'lockbtn' => 'Database kilit bik',
'unlockbtn' => 'Database a bik',
'locknoconfirm' => 'Şıma qutiyê araşt kerdışi nêweçinê.',
'lockdbsuccesssub' => 'Database kilit biya',
'unlockdbsuccesssub' => 'Database a biya',
'lockdbsuccesstext' => 'database qefıliya.<br />
wexta mıqat/qayt kewtışi databaseyê şıma qediya u xo vir ra mekerê[[Special:UnlockDB|qeflıkê databaseyi akerê]].',
'unlockdbsuccesstext' => 'Database a biya.',
'lockfilenotwritable' => 'dosyaya qefılnayişê databaseyi ser ra çiyek nênusyena.',
'databasenotlocked' => 'Database a nibiya.',
'lockedbyandtime' => '({{GENDER:$1|$1}} ra $2 tepya $3 biyo)',

# Move page
'move-page' => '$1 Bere',
'move-page-legend' => 'Pele bere',
'movepagetext' => "Pe form ki ho bın de, tı eşkeno name yew pele bıvurni u tarixê pele hemi ya zi pyeran beri.
Ma nameyê kıhanyeri keno pele redireksiyoni ser nameyê newe.
Tı eşkeno pele redireksiyoni ki şıno nameyê originali bıvurni.
Eg tı nıwazeno, ma tı ra rica keni tı [[Special:DoubleRedirects|double]] ya zi [[Special:BrokenRedirects|broken redirects]] qontrol bıki.
Tı gani qontrol bıki eg gıreyan şıno peleyanê raşti.

Teme eka ser yew name de yew nuşte esti, sistemê ma '''nıeşkeno''' nuşte tı beri. Eka ser ena name de yew pele vengi esti, sistemê ma eşkeno nuşte tı beri.
Tı nıeşkeni name yew pele reyna bıvurni.

'''Teme!'''
Ena transfer ser peleyanê populari zaf muhumo;
Ma tu ra rica keni, tı en verni dı qontrol bıki u bışıravi.",
'movepagetext-noredirectfixer' => "Pe form ki ho bın de, tı eşkeno name yew pele bıvurni u tarixê pele hemi ya zi pyeran beri.
Ma nameyê kıhanyeri keno pele redireksiyoni ser nameyê newe.
Tı eşkeno pele redireksiyoni ki şıno nameyê originali bıvurni.
Eg tı nıwazeno, ma tı ra rica keni tı [[Special:DoubleRedirects|raçarnayışo dılet]] ya zi [[Special:BrokenRedirects|raçarnayışo xırab]]i qontrol bıke.
Tı gani qontrol bıki eg gıreyan şıno peleyanê raşti.

Teme eka ser yew name de yew nuşte esti, sistemê ma '''nıeşkeno''' nuşte tı beri. Eka ser ena name de yew pele vengi esti, sistemê ma eşkeno nuşte tı beri.
Tı nıeşkeni name yew pele reyna bıvurni.

'''İkaz!'''
Ena transfer ser peleyanê populari zaf muhumo;
Ma tu ra rica keni, tı en verni dı qontrol bıki u bışıravi.",
'movepagetalktext' => "Ma peleyê mınaqeşeyê ena pele otomatik beno, '''ma nıeşken ber, eg:'''
*Yew peleyê mınaqeşeyê ser ena name rona esto, ya zi
*Tı quti check nıkerd.

Oturse, tı gani peleyê mınaqeşeyê manually beri.",
'movearticle' => 'Pele bere:',
'moveuserpage-warning' => "'''Diqet:''' Ti eka yew pelê karberi beni. Diqet bike teyna pel beni feqat ena pele reyna nameyê newi \"nebeno''.",
'movenologin' => 'Şıma cıkewtış nêvıraşto',
'movenologintext' => 'qey vurnayişê nameyê peli şıma gani qeyd kerde u cıkewteyê [[Special:UserLogin|sistemi]] bıbiy.',
'movenotallowed' => 'desturê şıma çino, şıma pelan bıkırışi',
'movenotallowedfile' => 'desturê şıma çino, şıma pelan bıkırışi',
'cant-move-user-page' => 'desturê şıma çino, şıma pelanê karberani bıkırışi (bê pelê cerıni).',
'cant-move-to-user-page' => 'desturê şıma çino, şıma yew peli bıkırışi pelê yew karberi.',
'newtitle' => 'Nameyê newi:',
'move-watch' => 'Peler seyr ke',
'movepagebtn' => 'Pele bere',
'pagemovedsub' => 'Berdışi kerd temam',
'movepage-moved' => '\'\'\'"$1" berd "$2"\'\'\'',
'movepage-moved-redirect' => 'yew rayberdışi vıraziya',
'movepage-moved-noredirect' => 'yew rayberdışi çap bı',
'articleexists' => 'Ena nameyê pela database ma dı esta ya zi tı raşt nınuşt. .
Yewna name bınus.',
'cantmove-titleprotected' => 'şıma nêşkeni yew peli bıhewelnê tiya çunke pawıyeno',
'talkexists' => "'''Ma ena pele berd. Feqet pele mıneqeşe dı yew problem esto. Çınki ser name newe dı yew pele rona esto. Eq tı eşkeno, pele mıneqeşe manually beri.'''",
'movedto' => 'berd be',
'movetalk' => 'Pela werênayışiê elaqedare bere',
'move-subpages' => 'pelê bınini bıkırış($1 heta tiya)',
'move-talk-subpages' => 'pelê bınini yê pelê werê ameyeşi bıkırış ($1 heta tiya)',
'movepage-page-exists' => 'maddeya $1i ca ra esta u newe ra otomatikmen nênusyena.',
'movepage-page-moved' => 'pelê $1i kırışiya pelê $2i.',
'movepage-page-unmoved' => 'pelê $1i nêkırışiyeno sernameyê $2i.',
'movepage-max-pages' => 'tewr ziyed $1 {{PLURAL:$1|peli|peli}} kırışiya u hıni ziyedê ıney otomotikmen nêkırışiyeno.',
'movelogpage' => 'Qeydé berdışi',
'movelogpagetext' => 'nameyê liste ya ke cêr de yo, pelê vuriyayeyani mocneno',
'movesubpage' => '{{PLURAL:$1|Subpage|pelê bınıni}}',
'movesubpagetext' => '{{PLURAL:$1|pelê bınıni yê|pelê bınıni yê}} no $1 peli cer de yo.',
'movenosubpage' => 'pelê bınıni yê no peli çino.',
'movereason' => 'Sebeb:',
'revertmove' => 'peyser bia',
'delete_and_move' => 'Bestere û bere',
'delete_and_move_text' => '==gani hewn a bıbıo/bıesteriyo==

" no [[:$1]]" name de yew pel ca ra esto. şıma wazeni pê hewn a kerdışê ey peli vurnayişê nameyi bıkeri?',
'delete_and_move_confirm' => 'Eya, na pele bestere',
'delete_and_move_reason' => '"[[$1]]" qande nami re ca akerdışi re besteriyaye',
'selfmove' => 'name yo ke şıma wazeni bıbo, ın name û name yo ke ca ra esto eyni yê /zepê yê. vurnayiş mumkin niyo.',
'immobile-source-namespace' => '"$1" pelê cayi de nameyi nêkırışyenî',
'immobile-target-namespace' => 'peli nêkırışiyeni "$1" cayê nameyan',
'immobile-target-namespace-iw' => 'xetê benatê wikiyan, hedefê pelkırıştış niyo',
'immobile-source-page' => 'nameyê no peli nêvuriyeno',
'immobile-target-page' => 'sernameyê no hedefi re nêkırışiyeno',
'bad-target-model' => 'Hedefo ke waştiyayo zerreke cı babetna model karneno. Ke nêşeno $1 ra açarno $2.',
'imagenocrossnamespace' => 'Dosya, ca yo ke qey nameyê dosyayan nêbıbo nêkırışiyeno',
'nonfile-cannot-move-to-file' => 'Ekê dosya niyê, cade namande dosyaya nêahulneyênê',
'imagetypemismatch' => 'tipa dosyaya neweyi re pênêgıneno/nêgıneno pê',
'imageinvalidfilename' => 'nameyê dosyayi ya hedefi meqbul niyo.',
'fix-double-redirects' => 'rayberdış ê ke sernameyê orjinali re işaret keni rocane bıker.',
'move-leave-redirect' => 'pey de yew rayberdış roni',
'protectedpagemovewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, loge bivini:",
'semiprotectedpagemovewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, loge bivini:",
'move-over-sharedrepo' => '== Dosya esto ==
[[:$1]] enbar ma de esto. Eka ti wazeno ena dosyo ser ena sername de bero, yewna dosya sero nusiyeno.',
'file-exists-sharedrepo' => 'Ena sername zaten embar ma de esto.
Ma rica keno yewna sername binuse.',

# Export
'export' => 'Pela ateber dı',
'exporttext' => 'şıma yew pelê nişanebiyayeyi, nuşteyê taqımê pelani, pê pêşteyê XMLi eşkeni bıdi teberi.
wiki yo ke wikimedya xebıtneno, pê [[Special:Import|pelê zerre dayişê]] no wikiyi beno.

şıma eşkeni yew gırey bıerzi,
ma vaci: qey pelê "[[{{MediaWiki:Mainpage}}]]i " [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportall' => 'Pela Pêron ateberdı',
'exportcuronly' => 'têna revizyonê peyin bıger',
'exportnohistory' => "----
'''Not:''' pê no form teberdayişê verê (tarix) pelan battal biyo",
'exportlistauthors' => 'zerre de qandê her pela listey iştiraxkara esto',
'export-submit' => 'Teber de',
'export-addcattext' => 'Kategoriye ra pelan têare ke',
'export-addcat' => 'Têare ke',
'export-addnstext' => 'pelan cayê nameyan ra têare ker',
'export-addns' => 'têare ker',
'export-download' => 'yewna qaydeyi de qeydker',
'export-templates' => 'şablonan daxil ker',
'export-pagelinks' => 'behsê xorıniya pelê pêrabesteyani:',

# Namespace 8 related
'allmessages' => 'Mesacê sistemi',
'allmessagesname' => 'Name',
'allmessagesdefault' => 'Metnê mesacê hesabiyayey',
'allmessagescurrent' => 'Nuşteyê mesacê rocaney',
'allmessagestext' => 'na liste, listeya mesajê cayê nameyê wikimedya yo.
eke şıma qayili paşt bıdi mahalli kerdışê wikimedyayi, kerem kerê pelê [https://www.mediawiki.org/wiki/Localisation mahalli kerdışê wikimedyayi] u [//translatewiki.net translatewiki.net] ziyaret bıkerê.',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' qefelnaye yo u ey ra '''{{ns:special}}:Allmessages''' karkerdışi re akerde niyo.",
'allmessages-filter-legend' => 'Avrêc',
'allmessages-filter' => 'goreyê xususi kerdışi re filtre bıker',
'allmessages-filter-unmodified' => 'Nivurnaye',
'allmessages-filter-all' => 'Pêro',
'allmessages-filter-modified' => 'Vurnaye',
'allmessages-prefix' => 'pê prefiks filtre bıker',
'allmessages-language' => 'Zıwan:',
'allmessages-filter-submit' => 'Şo',

# Thumbnails
'thumbnail-more' => 'Gırd ke',
'filemissing' => 'Dosya biya vini',
'thumbnail_error' => 'Thumbnail niviraziya: $1',
'thumbnail_error_remote' => '$1 ra mesaco xırab: $2',
'djvu_page_error' => 'pelê DjVuyi bêşumulo',
'djvu_no_xml' => 'Qe DjVu nieşkenî XML fetch bikî',
'thumbnail-temp-create' => 'İdare dosyay resimiya nêvırazêna',
'thumbnail-dest-create' => 'Resimo werdiyo keyd nêbeno',
'thumbnail_invalid_params' => 'Parametreya thumbnailî raşt niyşê',
'thumbnail_dest_directory' => 'Nieşkenî direktorê destinasyonî virazî',
'thumbnail_image-type' => 'Tipê resimî kebul nibeno',
'thumbnail_gd-library' => 'Configurasyonê katalog ê GDî tam niyo:funksiyonê $1î vînî biyo',
'thumbnail_image-missing' => 'Dosya vînî biyo: $1',

# Special:Import
'import' => 'Peleyi import bik',
'importinterwiki' => 'Împortê transwîkî',
'import-interwiki-text' => 'qey kırıştışê zerreyi yew wiki u pel bıvıcinê.
tarixê revizyon u nameyê nuştoxi pawyene.
karê zerredayişê benateyê wikiyani[[Special:Log/import|zerreyê rocaneyê kırıştî de]] qeyd beno.',
'import-interwiki-source' => 'Çime wîkî/pel:',
'import-interwiki-history' => 'Qe eno pel, revizyonê tarixê hemî kopya bike',
'import-interwiki-templates' => 'Şablonê hemî dehil bike',
'import-interwiki-submit' => 'Azare de',
'import-interwiki-namespace' => 'Destinasyonê canameyî:',
'import-interwiki-rootpage' => 'Hedef pelaya reçi (opsiyonel):',
'import-upload-filename' => 'Nameyê dosyayi:',
'import-comment' => 'Vatış:',
'importtext' => 'Kerem ke dosyay, çımeyê wiki ra pê [[Special:Export|kırıştışê teberdayişi]] bıdê teber, Komputerê xo de qeyd kerê u bar kerê tiya.',
'importstart' => 'Pelan împort kenî',
'import-revision-count' => '$1 {{PLURAL:$1|revizyon|revizyon}}',
'importnopages' => 'Pel çino ke import bike',
'imported-log-entries' => ' $1 {{PLURAL:$1|logê dekerdişi|loganê dekerdişan}} ard.',
'importfailed' => 'Împort nebiy: <nowiki>$1</nowiki>',
'importunknownsource' => 'Çimeyê tip ê împortî nizanyano',
'importcantopen' => 'Nieşkenî dosyayê împortî a bike',
'importbadinterwiki' => 'Linkê înterwîkîyî nihebitiyeno',
'importnotext' => 'Veng o ya zi tede nuşte çini yo',
'importsuccess' => 'Împort qediya!',
'importhistoryconflict' => 'verê revizyon ê ke pêverdiyaye yê tiya de mewcud o (no pel, belka cıwa ver kırışiyayo zerreyi)',
'importnosources' => 'çımeyê kırıştışê zerredayişi nidiyo şınasnayişi u barbiyayişê verıni battal verdiyo.',
'importnofile' => 'Yew zi dosyayê împortî bar nibiyo.',
'importuploaderrorsize' => "barbiyayişê kırıştışê zerredayişi nibı.
gırdiyê dosyayi, gırdî yo ke musa'ade biyo ıney gırdıyî ra gırd o.",
'importuploaderrorpartial' => 'barbiyayişê kırıştışê zerredayişi nibı.
têna yew qısımê dosyayi ey bar bı',
'importuploaderrortemp' => 'barbiyayişê kırıştışê zerredayişi nibı.
dosyaya emaneti vindbiyo',
'import-parse-failure' => 'Împortê XML-parse nebiyo',
'import-noarticle' => 'Pel çino ke împort bike!',
'import-nonewrevisions' => 'Revizyonê hemi vernî de împort biyê.',
'xml-error-string' => '$1 çizgi de $2 col $3 (bit $4): $5',
'import-upload' => 'Dosyayê XML bar bike',
'import-token-mismatch' => "vindibiyayişê ma'lumatê hesabi. kerem kerê newe ra tesel/cereb bıkerê.",
'import-invalid-interwiki' => 'Ena wiki ra azere kerdış nêbeno.',
'import-error-edit' => 'Pela " $1 " qandê vurnayışi aya nêgêrêna çıkı cı rê icazet nêdeyayo.',
'import-error-create' => 'Pela " $1 " qandê vıraştışi aya nêabêna çıkı cı rê icazet nêdeyayo.',
'import-error-interwiki' => 'Pela " $1 " qandê name dayışi aya nêgêrêna çıkı namey cı (interwiki) sero cırê ca abıryayo.',
'import-error-special' => 'Pela " $1 " qandê vıraştışi aya nêgêrêna çıkı namay cı nameyo do xısusiyo u na pela rê no name nêgêrêno.',
'import-error-invalid' => 'Pela "$1" nêdebyê de çıkı namey cı çınyo.',
'import-error-unserialize' => 'Rewizyon perda $2 ra "$1" nihewdeyino/ Miyane raporer $3 ra $4 model deye ratneyi.',
'import-options-wrong' => '{{PLURAL:$2|Weçenego|Weçenego}} xerpiyaye: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Sernuştey ena pela reçey cı raverde niyo.',
'import-rootpage-nosubpage' => 'Qan de bınnaman reçe de "$1" re mısade nedano.',

# Import log
'importlogpage' => 'Defterê seyırio idxal',
'importlogpagetext' => 'wiki yo ke nişane biyo tera kırıştışê zerredayişi nêbeno.',
'import-logentry-upload' => 'dosyayê bar kerdişî ra [[$1]] împort biyo',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|çımraviyarnayış|çımraviyarnayışi}}',
'import-logentry-interwiki' => '$1 transwiki biyo',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revizyon|revizyonî}} $2 ra',

# JavaScriptTest
'javascripttest' => 'Cerebnayışê JavaScripti',
'javascripttest-title' => 'Testê $1 gurweyênê',
'javascripttest-pagetext-noframework' => 'Na pela testanê JavaScripta gurweynayışi re abıryaya.',
'javascripttest-pagetext-unknownframework' => 'Çerçeweyê "$1" cerbnayışi xırabo.',
'javascripttest-pagetext-frameworks' => 'Şıma ra reca xorê cêr ra test weçinê:$1',
'javascripttest-pagetext-skins' => 'Testa akarfinayışi rê verqayt:',
'javascripttest-qunit-intro' => 'Mediawiki.org dı [dokumanê $1] bıvinê.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit test suite',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Pelaya karberi',
'tooltip-pt-anonuserpage' => 'pelê karberê IPyi',
'tooltip-pt-mytalk' => 'Pela ya Qıse vatışi',
'tooltip-pt-anontalk' => 'vurnayiş ê ke no Ipadresi ra biyo muneqeşa bıker',
'tooltip-pt-preferences' => 'Tercihê to',
'tooltip-pt-watchlist' => 'Lista pelanê ke to gırewtê seyrkerdış',
'tooltip-pt-mycontris' => 'Yew lista iştıraqanê şıma',
'tooltip-pt-login' => 'Mayê şıma ronıştış akerdışi rê dawet keme; labelê ronıştış mecburi niyo',
'tooltip-pt-anonlogin' => 'Seba cıkewtışê şıma rê dewato; labelê, no zeruri niyo',
'tooltip-pt-logout' => 'Bıveciye',
'tooltip-ca-talk' => 'Zerrey pela sero werênayış',
'tooltip-ca-edit' => 'Tı şenay na pele bıvurnê.
Kerem ke, qeydkerdış ra ver gocega verqayti bıxebetne.',
'tooltip-ca-addsection' => 'Yew qısımo newe ake',
'tooltip-ca-viewsource' => 'Ena pele kılit biya.
Şıma şenê çımeyê aye bıvênê',
'tooltip-ca-history' => 'Versiyonê verênê ena pele',
'tooltip-ca-protect' => 'Ena pele kılit ke',
'tooltip-ca-unprotect' => 'Starkerdışe ena peler bıvurne',
'tooltip-ca-delete' => 'Ena perer besternê',
'tooltip-ca-undelete' => 'peli biyarê halê ver hewnakerdışi',
'tooltip-ca-move' => 'Ena pele bere',
'tooltip-ca-watch' => 'Ena pele lista xoya seyrkerdışi ke',
'tooltip-ca-unwatch' => 'Ena pele listeya seyir-kerdışi xo ra bıvec',
'tooltip-search' => 'Zerreyê {{SITENAME}} de cı geyre',
'tooltip-search-go' => 'Ebe nê namey tami şo yew pela ke esta',
'tooltip-search-fulltext' => 'Nê  metni peran dı cı geyre',
'tooltip-p-logo' => 'Pela seri bıvênên',
'tooltip-n-mainpage' => 'Şo pela seri',
'tooltip-n-mainpage-description' => 'Şo pela seri',
'tooltip-n-portal' => 'Heqa projey de, kes çı şeno bıkero, çıçiyo koti deyo',
'tooltip-n-currentevents' => 'Vurnayışanê peyênan de melumatê pey bıvêne',
'tooltip-n-recentchanges' => 'Wiki de lista vurnayışanê peyênan',
'tooltip-n-randompage' => 'Şırê pera ke raştameyê',
'tooltip-n-help' => 'Cayê doskerdışi',
'tooltip-t-whatlinkshere' => 'Lista pelanê wikiya pêroina ke tiya gırê bena',
'tooltip-t-recentchangeslinked' => 'Vurnayışê peyênê pelanê ke ena pela ra gırê biyê',
'tooltip-feed-rss' => 'RSS feed qe ena pele',
'tooltip-feed-atom' => 'Qe ena pele atom feed',
'tooltip-t-contributions' => 'İştirakanê ena karber bevin',
'tooltip-t-emailuser' => 'Ena karber ri yew email bışırav',
'tooltip-t-upload' => 'Dosya bar ke',
'tooltip-t-specialpages' => 'Yew lista pelanê xasanê pêroyinan',
'tooltip-t-print' => 'Nımuney çapkerdışiê ena pele',
'tooltip-t-permalink' => 'Gırêyo daimi be ena versiyonê pele',
'tooltip-ca-nstab-main' => 'Pela zerreki bımocne',
'tooltip-ca-nstab-user' => 'Pela karberi bıvin',
'tooltip-ca-nstab-media' => 'Pele Mediya bivinên',
'tooltip-ca-nstab-special' => 'Na yew pelê da xususiya, şıma nêşenê nae bıvurnê',
'tooltip-ca-nstab-project' => 'Pela procey bıvêne',
'tooltip-ca-nstab-image' => 'Pelay dosya bımocne',
'tooltip-ca-nstab-mediawiki' => 'Mesacê sistemi bivinên',
'tooltip-ca-nstab-template' => 'Şabloni bıvinê',
'tooltip-ca-nstab-help' => 'Peleyê yardimi bivinên',
'tooltip-ca-nstab-category' => 'Pele kategoriyan bevinin',
'tooltip-minoredit' => 'Eno vurnayışê qıçkeko',
'tooltip-save' => 'Vurnayışanê xo qeyd ke',
'tooltip-preview' => 'Vurnayışê xo bıvin. Verniyê qeyd kerdışi, vurnayışê xo ena pele dı control bık.',
'tooltip-diff' => 'Metni sero vurnayışan mocneno',
'tooltip-compareselectedversions' => 'Ena per de ferqê rewziyonan de dı weçinaya bıvinê',
'tooltip-watch' => 'Eno pele listey tıyo seyir-kerdişi ri dek',
'tooltip-watchlistedit-normal-submit' => 'Sernuşteya hewad',
'tooltip-watchlistedit-raw-submit' => 'Listeyê seyri newen ke',
'tooltip-recreate' => 'pel hewn a bışiyo zi tepiya biya',
'tooltip-upload' => 'Dest be barkerdışi ke',
'tooltip-rollback' => '"Peyser bia" be yew tık pela iştıraq(an)ê peyên|i(an) peyser ano.',
'tooltip-undo' => '"Undo" ena vurnayışê newi iptal kena u vurnayışê verni a kena.
Tı eşkeno yew sebeb bınus.',
'tooltip-preferences-save' => 'Terciha qeyd ke',
'tooltip-summary' => 'Yew xulasaya kilm binuse',

# Scripts
'common.js' => '/* Any JavaScript here will be loaded for all users on every page load. */',

# Metadata
'notacceptable' => "formatê ma'lumati no peşkeşwanê wikiyi nêweniyeno.",

# Attribution
'anonymous' => '{{PLURAL:$1|karberê|karberê}} anonimi yê keyepelê {{SITENAME}}i',
'siteuser' => 'karberê {{SITENAME}}i $1',
'anonuser' => 'karberê anonim o {{SITENAME}}i $1',
'lastmodifiedatby' => 'Ena pele tewr peyên roca $2, $1 by $3. de biya rocaniye',
'othercontribs' => 'xebatê $1 ıney geriyayo diqqeti/geriyayo nezer.',
'others' => 'bini',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|karberê ey|karberanê ey}} $1',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|karberê eyê|karberanê eyê}} anonimi $1',
'creditspage' => 'şınasnameyê peli',
'nocredits' => 'qey no peli hema/hona yew şınasnameyi mewcud niyo',

# Spam protection
'spamprotectiontitle' => 'filtreya spami yo pawıtışê verba-vındertışi',
'spamprotectiontext' => 'pel o ke şıma waşt qeyd bıkeri hetê filtreya spami ra blok bı. ihtimalo gırdek o teber-gıreyê listeya sabıqayi ra yo.',
'spamprotectionmatch' => 'nuşte yo ke rıcnayoxê spami herikneno: $1',
'spambot_username' => 'wikimedya spam-pakkerdışi',
'spam_reverting' => 'agêriyeno revizyon o ke tawayê $1 ıney piya çiniyo',
'spam_blanking' => 'Revizyonê gredê $1 vineyay, wa weng kero',
'spam_deleting' => 'Revizyonê gredê $1 vineyay, wa besterneyê',
'simpleantispam-label' => "Cerbnayışa anti-spami.
Ney '''Mefiyé de'''!",

# Info page
'pageinfo-title' => 'Heq tê "$1"\'i',
'pageinfo-not-current' => 'Qısur de mevêne, rewizyonanê verênan rê nê melumatan dayış mumkın niyo',
'pageinfo-header-basic' => 'Seron zanayış',
'pageinfo-header-edits' => 'Veréna timar kerdışi',
'pageinfo-header-restrictions' => 'Sıtarkerdışê pele',
'pageinfo-header-properties' => 'Xısusiyetê pele',
'pageinfo-display-title' => 'Sernuştey bımocne',
'pageinfo-default-sort' => 'Hesıbyaye mırfeyo kılm',
'pageinfo-length' => 'Derdeya pela (bayti heta)',
'pageinfo-article-id' => 'Kamiya pele',
'pageinfo-language' => 'Zıwanê zerreyê pele',
'pageinfo-content-model' => 'Modela zerreka perer',
'pageinfo-robot-policy' => 'Weziyetê motor de cıgeyrayışi',
'pageinfo-robot-index' => 'İndeksbiyayen',
'pageinfo-robot-noindex' => 'İndeksnêbiyayen',
'pageinfo-views' => 'Amarina mocnayışan',
'pageinfo-watchers' => 'Amariya pela serykeran',
'pageinfo-few-watchers' => '$1 ra tayê {{PLURAL:$1|seyrker|seyrkeri}}',
'pageinfo-redirects-name' => 'Hetenayışê na perer',
'pageinfo-redirects-value' => '$1',
'pageinfo-subpages-name' => 'Bınpelê na pela',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|hetenayış|hetenayışi}}; $3 {{PLURAL:$3|raykerdışt|raykerdışi}})',
'pageinfo-firstuser' => 'Pela vıraşter',
'pageinfo-firsttime' => 'Demê pela vıraştışi',
'pageinfo-lastuser' => 'Vurnayoğo peyên',
'pageinfo-lasttime' => 'Deme u vurnayışo peyên',
'pageinfo-edits' => 'Amarina vurnayışan pêro',
'pageinfo-authors' => 'Amarina nuştekaran pêro',
'pageinfo-recent-edits' => 'Amariya vurnayışan ($1 ra nata)',
'pageinfo-recent-authors' => 'Amarina nuştekaran pêro',
'pageinfo-magic-words' => '{{PLURAL:$1|Çekuya|Çekuyê}} ($1) sihırini',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Kategoriye|Kategoriyan}} ($1) bınımne',
'pageinfo-templates' => '{{PLURAL:$1|Şablono|Şablonê}} ke mocniyenê ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|1 Pele|$1 Pelan}} de bestiya pıra',
'pageinfo-toolboxlink' => 'Melumatê pele',
'pageinfo-redirectsto' => 'Beno hetê',
'pageinfo-redirectsto-info' => 'melumat',
'pageinfo-contentpage' => 'Zey jû pela zerreki hesebiyena',
'pageinfo-contentpage-yes' => 'Eya',
'pageinfo-protect-cascading' => 'Sıtarkerdey tiya cı ra yenê war',
'pageinfo-protect-cascading-yes' => 'Eya',
'pageinfo-protect-cascading-from' => 'Sıtarkerdey cı ra yenê war',
'pageinfo-category-info' => 'Şınasiya kategoriye',
'pageinfo-category-pages' => 'Amarê pelan',
'pageinfo-category-subcats' => 'Amarê bınkategoriyan',
'pageinfo-category-files' => 'Amarê dosyeyan',

# Skin names
'skinname-cologneblue' => 'Cologne Blue',
'skinname-monobook' => 'MonoBook',
'skinname-modern' => 'Modern',
'skinname-vector' => 'Vektor',

# Patrolling
'markaspatrolleddiff' => 'Nişan bıke ke dewriya biyo',
'markaspatrolledtext' => 'Ena pele nişan bike ke devriye biyo',
'markedaspatrolled' => 'Nişan biyo ke verni de devriye biyo',
'markedaspatrolledtext' => 'Versiyone weçinaye [[:$1]] nişan biyo ke devriye biyo',
'rcpatroldisabled' => 'Devriyeyê vurnayışê peyêni nihebitiyeno',
'rcpatroldisabledtext' => 'Devriyeyê vurnayışê peyêni inke kefilnaye biyo u nihebitiyeno',
'markedaspatrollederror' => 'Nişan nibeno ke devriye biyo',
'markedaspatrollederrortext' => 'Ti gani revizyon işaret bike ke Nişanê devriye biyo',
'markedaspatrollederror-noautopatrol' => 'Ti nieşkeno ke vurnayişê xo nişan bike ke devriye biyê.',
'markedaspatrollednotify' => 'Na vurnayışa dewriye deye $1 nışan biyo.',
'markedaspatrollederrornotify' => 'Nışan kerdışê dewriyey nêbı',

# Patrol log
'patrol-log-page' => 'Qeydé çımsernayoğan',
'patrol-log-header' => 'Ena listeyê logi revizyonê devriyeyi mocneno.',
'log-show-hide-patrol' => 'Qeydé Çımsernayoğan $1',

# Image deletion
'deletedrevision' => 'Veriyono kihan $1 wederna',
'filedeleteerror-short' => 'Wedarnayişê dosya de ğelati esto: $1',
'filedeleteerror-long' => 'Eka dosya wedarnayişi de ğeleti biyê:

$1',
'filedelete-missing' => 'Ena dosya "$1" nieşkeno biyo wedariye, çunki ena dosya çini yo.',
'filedelete-old-unregistered' => 'Ena dosya revizyoni yê weçinayi "$1" database ma de çini yo.',
'filedelete-current-unregistered' => 'Ena dosyayê weçinayi "$1" database ma de çini yo.',
'filedelete-archive-read-only' => 'Ena direktorê arşivi "$1" webserver de nieşkeno binusi.',

# Browsing diffs
'previousdiff' => '← Vurnayışê kıhanyer',
'nextdiff' => 'Vurnayışo peyên →',

# Media information
'mediawarning' => "'''Teme''': Na dosya de belkia kodê xırabıni estê.
Gurênayışê nae de, beno ke sistemê şıma zerar bıvêno.",
'imagemaxsize' => "Limitê ebat ê resimi:<br />''(qe pela deskripsiyonê dosyayan)''",
'thumbsize' => 'Ebadê Thumbnaili',
'widthheight' => '$1 - $2',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|pele|peli}}',
'file-info' => 'ebatê dosyayi: $1, MIME tip: $2',
'file-info-size' => '$1 × $2 pixelan, ebatê dosya: $3, MIME type: $4',
'file-info-size-pages' => '$1 × $2 pikse, dergeya dosyay: $3, MIME tipiya cı: $4, $5 {{PLURAL:$5|pela|pela}}',
'file-nohires' => 'Deha berz agozney cı çıniyo',
'svg-long-desc' => 'Dosyay SVG, zek vanê $1 × $2 piksela, ebatê dosya: $3',
'svg-long-desc-animated' => 'SVG dosya, nominalin $1 × $2 piksela, ebatê dosya: $3',
'svg-long-error' => "Nêmeqbul dosyaya SVG'i: $1",
'show-big-image' => 'Oricinal dosya',
'show-big-image-preview' => "Verqayd dergiya: $1'i.",
'show-big-image-other' => 'Zewmi{{PLURAL:$2|Vılêşnayış|Vılêşnayışê}}: $1.',
'show-big-image-size' => '$1 × $2 piksel',
'file-info-gif-looped' => 'viyariye biyo',
'file-info-gif-frames' => '$1 {{PLURAL:$1|çerçeve|çerçeveyi}}',
'file-info-png-looped' => 'atlama biyo',
'file-info-png-repeat' => '$1 {{PLURAL:$1|hew|hew}} kay biyê',
'file-info-png-frames' => '$1 {{PLURAL:$1|çerçeve|çerçeveyi}}',
'file-no-thumb-animation' => "'''Not: Dılet tekniko limit, gırd agozneya resm de qıckek de animasyoni miyan dı nêbo.'''",
'file-no-thumb-animation-gif' => "'''Not: Dılet tekniko limit, gırd agozneya resm de qıckek de  GIF imaci de animasyon do nêbo.'''",

# Special:NewFiles
'newimages' => 'Galeriya dosyayan dê newan',
'imagelisttext' => "Cêr de yew listeyê '''$1''' esto {{PLURAL:$1|dosya|dosyayi}} veçiniya $2.",
'newimages-summary' => 'Ena pela xasi dosyayi ke peni de bar biyayeyi mocnane.',
'newimages-legend' => 'Avrêc',
'newimages-label' => 'Nameyê dosya ( ya zi parçe ey)',
'showhidebots' => '(bota $1)',
'noimages' => 'Çik çini yo.',
'ilsubmit' => 'Cı geyre',
'bydate' => 'goreyê zemani',
'sp-newimages-showfrom' => '$1, sehat $2 ra tepya dosyayané newan bıasné',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2 × $3',
'seconds-abbrev' => '$1 san',
'minutes-abbrev' => '$1 deq',
'hours-abbrev' => '$1h',
'days-abbrev' => '$1d',
'seconds' => '{{PLURAL:$1|$1 saniya|$1 saniyeyan}}',
'minutes' => '{{PLURAL:$1|$1 deqa|$1 deqan}}',
'hours' => '{{PLURAL:$1|$1 saete|$1 saetan}}',
'days' => '{{PLURAL:$1|$1 roce|$1 rocan}}',
'weeks' => '{{PLURAL:$1|$1 hefte|$1 hefteyan}}',
'months' => '{{PLURAL:$1|aşme|$1 aşman}}',
'years' => '{{PLURAL:$1|$1 serre|$1 serran}}',
'ago' => 'Verê $1',
'just-now' => 'Hema newke',

# Human-readable timestamps
'hours-ago' => 'Verê $1 {{PLURAL:$1|saete|saetan}}',
'minutes-ago' => 'Verê $1 {{PLURAL:$1|deqa|deqan}}',
'seconds-ago' => 'Verê $1 {{PLURAL:$1|saniya|saniyeyan}}',
'monday-at' => 'Dışeme $1 de',
'tuesday-at' => 'Sêşeme $1 de',
'wednesday-at' => 'Çarşeme $1 de',
'thursday-at' => 'Pancşeme $1 de',
'friday-at' => 'Êne $1 de',
'saturday-at' => 'Şeme $1 de',
'sunday-at' => 'Kırê $1 de',
'yesterday-at' => 'Vızêri $1 de',

# Bad image list
'bad_image_list' => 'Şeklo umumi wınayo:

Tenya çiyo ke beno lista (rezê ke be * dest kenê cı) çıman ver de vêniyeno.
Yew rêze de gırêyo sıfteyın gani gırêyo de dosya xırabıne bo.
Na rêze de her gırêyo bin zey istisna vêniyeno, yanê pelê ke dosya beno ke sero rêzbiyaye asena.',

/*
Short names for language variants used for language conversion links.
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-mo' => 'mo',
'variantname-zh-sg' => 'sg',
'variantname-zh-my' => 'my',
'variantname-zh' => 'zh',

# Variants for Gan language
'variantname-gan-hans' => 'hans',
'variantname-gan-hant' => 'hant',
'variantname-gan' => 'gan',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr' => 'sr',

# Variants for Kazakh language
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk' => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku' => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg' => 'tg',

# Variants for Inuktitut language
'variantname-ike-cans' => 'ike-Cans',
'variantname-ike-latn' => 'ike-Latn',
'variantname-iu' => 'iu',

# Variants for Tachelhit language
'variantname-shi-tfng' => 'shi-Tfng',
'variantname-shi-latn' => 'shi-Latn',
'variantname-shi' => 'shi',

# Metadata
'metadata' => 'Melumato serên',
'metadata-help' => 'Ena dosya dı zafyer informasyoni esto. Belki ena dosya yew kamareyo dijital ya zi skaner ra vıraziyo.
Eg ena dosya, kondisyonê orcinali ra bıvuriya, belki detayanê hemi nıeseno.',
'metadata-expand' => 'Detayan bımocne',
'metadata-collapse' => 'melumati bınımne',
'metadata-fields' => 'Resımê meydanê metadataê ke na pele de benê lista, pela resımmocnaene de ke tabloê metadata gına waro, gureniyenê.
Ê bini zey sayekerdoğan nımiyenê.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',
'metadata-langitem' => "'''$2:''' $1",
'metadata-langitem-default' => '$1',

# Exif tags
'exif-imagewidth' => 'Herayey',
'exif-imagelength' => 'Dergi',
'exif-bitspersample' => 'yew parçe de biti',
'exif-compression' => 'Planê kompresyoni',
'exif-photometricinterpretation' => 'Compozisyonê pixeli',
'exif-orientation' => 'Oriyentasyon',
'exif-samplesperpixel' => 'teneyê parçeyi',
'exif-planarconfiguration' => 'Rezeyê datayi',
'exif-ycbcrsubsampling' => 'Subsampleyi ebatê Y heta C',
'exif-ycbcrpositioning' => 'pozisyonê Y u C',
'exif-xresolution' => 'Rezulasyanê veriniye',
'exif-yresolution' => 'Rezulasyanê derganiye',
'exif-stripoffsets' => 'cayê data yê resim',
'exif-rowsperstrip' => 'Yew reze de teneyê dizeyi',
'exif-stripbytecounts' => 'Yew compresyon de dizeyi',
'exif-jpeginterchangeformat' => 'JPEG SOI rê ayar bike',
'exif-jpeginterchangeformatlength' => 'Bitê data yê JPEG',
'exif-whitepoint' => 'noktayê sipe ye kromaticiti',
'exif-primarychromaticities' => 'Kromaticitiyê eveli',
'exif-ycbcrcoefficients' => 'Cayê rengi yê transformasyon metriksê koefişinti',
'exif-referenceblackwhite' => 'Çiftyê siya u sipe değerê referansi',
'exif-datetime' => 'Zeman u tarixê vurnayişê dosyayi',
'exif-imagedescription' => 'Serê resimi',
'exif-make' => 'Viraştoğê kamera',
'exif-model' => 'Modelê kamerayi',
'exif-software' => 'Software ke hebitiyeno',
'exif-artist' => 'Nuştoğ',
'exif-copyright' => 'Wahirê copyrighti',
'exif-exifversion' => 'Versiyonê Exif',
'exif-flashpixversion' => 'Versiyonê Flashpix rê biyo desteg',
'exif-colorspace' => 'Cayê rengi',
'exif-componentsconfiguration' => 'manayê qisimê hemi',
'exif-compressedbitsperpixel' => 'Modê komprasyonê resimi',
'exif-pixelydimension' => 'Herayeya resimi',
'exif-pixelxdimension' => 'Berzeya resimi',
'exif-usercomment' => 'Hulasayê karberi',
'exif-relatedsoundfile' => 'Derhekê dosya yê vengi',
'exif-datetimeoriginal' => 'Zeman u tarixê data varaziyayişi',
'exif-datetimedigitized' => 'Zeman u tarixê dicital kerdişi',
'exif-subsectime' => 'ZemanTarix saniyeyibini',
'exif-subsectimeoriginal' => 'ZemanTarixOricinal saniyeyibini',
'exif-subsectimedigitized' => 'ZemanTarixDicital saniyeyibini',
'exif-exposuretime' => 'Zemanê orta de vinderdişi',
'exif-exposuretime-format' => '$1 san ($2)',
'exif-fnumber' => 'F Amar',
'exif-fnumber-format' => 'f/$1',
'exif-exposureprogram' => 'Programê Orta de Vinderdişi',
'exif-spectralsensitivity' => 'Hesasiyetê spektrali',
'exif-isospeedratings' => 'ISO değerê piti',
'exif-shutterspeedvalue' => "Pêtiya Deklanşor dê APEX'i",
'exif-aperturevalue' => "Akerdina APEX'i",
'exif-brightnessvalue' => "Berqeya APEX'i",
'exif-exposurebiasvalue' => 'Orta de viderdişi',
'exif-maxaperturevalue' => 'Tewr zafeyê wareyê apertur',
'exif-subjectdistance' => 'Duriyê ey',
'exif-meteringmode' => 'Modê pemawitişi',
'exif-lightsource' => 'Çimeyê roşni',
'exif-flash' => 'Flaş',
'exif-focallength' => 'Deganiyê fokus ê lensi',
'exif-focallength-format' => '$1 mm',
'exif-subjectarea' => 'Wareyê ey',
'exif-flashenergy' => 'Kuvetê flaşi',
'exif-focalplanexresolution' => 'Focal plane X resolution',
'exif-focalplaneyresolution' => 'Focal plane Y resolution',
'exif-focalplaneresolutionunit' => 'Focal plane resolution unit',
'exif-subjectlocation' => 'cayê kerdoxi',
'exif-exposureindex' => 'rêzê (indexê) pozi',
'exif-sensingmethod' => 'metodê hiskerdışi',
'exif-filesource' => 'çimeyê dosyayi',
'exif-scenetype' => 'tipa sehneyi',
'exif-customrendered' => 'karê resmê xususiyi',
'exif-exposuremode' => 'poz kerdışi',
'exif-whitebalance' => 'Dengeyo Sipe',
'exif-digitalzoomratio' => 'dijital zoom',
'exif-focallengthin35mmfilm' => "filmê 35 mm'yın de dûriyê merkeziyi",
'exif-scenecapturetype' => 'tipa sehne gırewtışi',
'exif-gaincontrol' => 'kontrolê sehneyi',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Saturasyon',
'exif-sharpness' => 'Tucî',
'exif-devicesettingdescription' => "daşınasnayişê 'eyarê cihazi",
'exif-subjectdistancerange' => 'menzilê mesafeya kerdoxi',
'exif-imageuniqueid' => 'şınasnameyê resmê xususiyi',
'exif-gpsversionid' => 'revizyonê GPSyi',
'exif-gpslatituderef' => 'paralelê zıme û veroci',
'exif-gpslatitude' => 'Heralem',
'exif-gpslongituderef' => 'meridyenê rocvetış û rocawavi',
'exif-gpslongitude' => 'Lemen',
'exif-gpsaltituderef' => 'çımeyê berziyi',
'exif-gpsaltitude' => 'berzî',
'exif-gpstimestamp' => "Wextê GPSyi (se'eta atomiki)",
'exif-gpssatellites' => 'Qandê peymıtışi antenê ke vıstê kar',
'exif-gpsstatus' => 'cayê gırewtoxi',
'exif-gpsmeasuremode' => 'moda peymawıtışi',
'exif-gpsdop' => 'karê peymawıtışi',
'exif-gpsspeedref' => 'Uniteyê pitî',
'exif-gpsspeed' => 'pêtîyê receiveri',
'exif-gpstrackref' => 'Referansê ke ser hetiyê hereketi',
'exif-gpstrack' => 'hetiyê hereketi',
'exif-gpsimgdirectionref' => 'Referansê ke ser hetiyê resimi',
'exif-gpsimgdirection' => 'Hetiyê resimi',
'exif-gpsmapdatum' => 'Geodetic survey data used',
'exif-gpsdestlatituderef' => 'Reference for latitude of destination',
'exif-gpsdestlatitude' => 'Latitude destination',
'exif-gpsdestlongituderef' => 'Reference for longitude of destination',
'exif-gpsdestlongitude' => 'Longitude of destination',
'exif-gpsdestbearingref' => 'Reference for bearing of destination',
'exif-gpsdestbearing' => 'Bearing of destination',
'exif-gpsdestdistanceref' => 'Referanse ke ser duriyeyê cayê şiyayişi',
'exif-gpsdestdistance' => 'Duriyeyê cayê şiyayişi',
'exif-gpsprocessingmethod' => 'Name of GPS processing method',
'exif-gpsareainformation' => 'Nameyê wareyê GPSi',
'exif-gpsdatestamp' => 'Tarixê GPSi',
'exif-gpsdifferential' => 'GPS differential correction',
'exif-coordinate-format' => '$1° $2′ $3″ $4',
'exif-jpegfilecomment' => "Vatışê dosyada JPEG'i",
'exif-keywords' => 'Qesa kelimey',
'exif-worldregioncreated' => 'Resim dınya dı qanci mıntıqara gêriyayo',
'exif-countrycreated' => 'Resim qanci dewlet ra gêriyayo',
'exif-countrycodecreated' => 'Cayo ke resim ancıyayo kodê dewlet da cı',
'exif-provinceorstatecreated' => 'Cayê resim antışi dewlet yana wılayet',
'exif-citycreated' => 'Suka ke resim gêriyayao',
'exif-sublocationcreated' => 'Bın lokasyonê resimê suker da cı grot',
'exif-worldregiondest' => 'Wareyo ke mocneyêno',
'exif-countrydest' => 'Dewleta ke mocneyêna',
'exif-countrycodedest' => 'Kodê dewleto ke mocneyoêno',
'exif-provinceorstatedest' => 'Eyalet yana wılayeto ke mocneyêno',
'exif-citydest' => 'Sûka ke mocneyêna',
'exif-sublocationdest' => 'Mıntıqeya sûker mocnayış',
'exif-objectname' => 'Sernuşteyo qıckek',
'exif-specialinstructions' => 'Talimatê xısusi',
'exif-headline' => 'Sername',
'exif-credit' => 'Kredi/Destegdaren',
'exif-source' => 'Çıme',
'exif-editstatus' => 'Resmi vurnayışê weziyeti',
'exif-urgency' => 'Aciliyet',
'exif-fixtureidentifier' => 'Namey fiksturi',
'exif-locationdest' => 'Tarifê cay',
'exif-locationdestcode' => 'Lokasyon kodi vaciya',
'exif-objectcycle' => 'Qandê medyay deme u roce cı',
'exif-contact' => 'Zanışiya irtibati',
'exif-writer' => 'Nuştekar',
'exif-languagecode' => 'Zıwan',
'exif-iimversion' => 'Verqaydê IIM',
'exif-iimcategory' => 'Kategori',
'exif-iimsupplementalcategory' => 'Oleyê Kategoriyan',
'exif-datetimeexpires' => 'No peyra mekarênê',
'exif-datetimereleased' => 'Bıroşe',
'exif-originaltransmissionref' => 'Oricinal pusula da kodê açarnayışi',
'exif-identifier' => 'Şınasnayer',
'exif-lens' => 'Lensê karkerdışi',
'exif-serialnumber' => 'Seri nımreyê kamera',
'exif-cameraownername' => 'Wayırê kamera',
'exif-label' => 'Etiket',
'exif-datetimemetadata' => 'Malumatê metamalumati peyd timarya',
'exif-nickname' => 'Bêresmi namey cı',
'exif-rating' => 'Rey dayış (5i sera)',
'exif-rightscertificate' => 'Sertifikayê idariya heqan',
'exif-copyrighted' => 'Weziyetê telifi',
'exif-copyrightowner' => 'Wayırê Telifi',
'exif-usageterms' => 'Şertê karkerdışi',
'exif-webstatement' => 'Heqê telifiya miyandene',
'exif-originaldocumentid' => 'Xasiya ID ya dokuman de orcinali',
'exif-licenseurl' => 'Qandê Lisans de heqê telifiye URL',
'exif-morepermissionsurl' => 'Alternatif malumatê lisansi',
'exif-attributionurl' => 'No nuşte çı wext karyayo, şıma ra reca gre dekerê de',
'exif-preferredattributionname' => 'No nuşte çı wext karyayo, Şıma ra reca morkerê',
'exif-pngfilecomment' => "Vatışê dosyada PNG'i",
'exif-disclaimer' => 'Redê mesuliyeti',
'exif-contentwarning' => 'İkazê zerreki',
'exif-giffilecomment' => "vatena dosya da GIF'i",
'exif-intellectualgenre' => 'Babeta çêki',
'exif-subjectnewscode' => 'Kodê muhtewa',
'exif-scenecode' => 'IPTC kodê sahni',
'exif-event' => 'Weqaya ke nameycıyo ravreno',
'exif-organisationinimage' => 'Organizasyono ke ravêreno',
'exif-personinimage' => 'Merdumo ke nameycıyo ravêreno',
'exif-originalimageheight' => 'Veror de resim nêkırpnayışi dergeya cı',
'exif-originalimagewidth' => 'Veror de resim nêkırpnayışi herayeya cı',

# Make & model, can be wikified in order to link to the camera and model name
'exif-contact-value' => '$1

$2
<div class="adr">
$3

$4, $5, $6 $7
</div>
$8',
'exif-subjectnewscode-value' => '$2 ($1)',

# Exif attributes
'exif-compression-1' => 'Nêdegusneyayo',
'exif-compression-2' => 'CCITT Grube 3 1-ebadın kodkerdışê dergiya gurenayışê Huffmanio modifiyekerde',
'exif-compression-3' => 'CCITT Group 3 fax kodkerdış',
'exif-compression-4' => 'CCITT Group 4 fax kodkerdış',
'exif-compression-5' => 'LZW',
'exif-compression-6' => 'JPEG (verên)',
'exif-compression-7' => 'JPEG',
'exif-compression-8' => 'Deflate (Adobe)',
'exif-compression-32773' => 'PackBits (Macintosh RLE)',
'exif-compression-32946' => 'Deflate (PKZIP)',
'exif-compression-34712' => 'JPEG2000',

'exif-copyrighted-true' => 'Heqê telifiye',
'exif-copyrighted-false' => 'Telifiya waziyeta eyara',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Tarix nizanyano',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'kıştki-ser çarnayiş',
'exif-orientation-3' => '180° çariyayo',
'exif-orientation-4' => 'dergî-ser çarnayiş',
'exif-orientation-5' => '90° çariyayo (çepser) u dergî-ser çarnayiş',
'exif-orientation-6' => '90° CCW çariyayo (hetê saetê ra)',
'exif-orientation-7' => "90° çariyayo (hetê se'eti ra) u dergî-ser çarnayiş",
'exif-orientation-8' => '90° CW çariyayo (çepser)',

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-65535' => 'Kalibrasyon nêvıraziyayo',

'exif-componentsconfiguration-0' => 'çini yo',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'nêdiya daşınasnayişi',
'exif-exposureprogram-1' => 'Pê/bı dest',
'exif-exposureprogram-2' => 'Programo normal',
'exif-exposureprogram-3' => "'ewwıliyê kıfşi",
'exif-exposureprogram-4' => "'ewwıliyê denklanşori",
'exif-exposureprogram-5' => 'proğramo vıraştox',
'exif-exposureprogram-6' => 'proğramê hareketi (qey antışê sehneyê hereketıni)',
'exif-exposureprogram-7' => 'moda portreyi (zemin keno gerzawın, portre zi keno net u hema anceno)',
'exif-exposureprogram-8' => 'moda peyzaji (têna çi yo ke dûri re çım verdeno)',

'exif-subjectdistance-value' => '$1 metreyi',

'exif-meteringmode-0' => 'Nêzanayen',
'exif-meteringmode-1' => 'orta',
'exif-meteringmode-2' => 'gıraniyê merkeziyi ser',
'exif-meteringmode-3' => 'noqtayın',
'exif-meteringmode-4' => 'zaf noqtayın',
'exif-meteringmode-5' => 'Desenın/fesalın',
'exif-meteringmode-6' => 'qısmî',
'exif-meteringmode-255' => 'Bin',

'exif-lightsource-0' => 'Nêzanayen',
'exif-lightsource-1' => 'Roşnê Tici',
'exif-lightsource-2' => 'Florasant',
'exif-lightsource-3' => 'roşnê bêbızate',
'exif-lightsource-4' => 'Flaş',
'exif-lightsource-9' => 'saye/hewayo weşî',
'exif-lightsource-10' => 'hewra/hora',
'exif-lightsource-11' => 'Sersiyın',
'exif-lightsource-12' => 'Florasanê roşnê tici (D 5700 – 7100K)',
'exif-lightsource-13' => 'Florasanê sipe ye roci (N 4600 – 5400K)',
'exif-lightsource-14' => 'Florasanê sipe ye hewli (W 3900 – 4500K)',
'exif-lightsource-15' => 'Florasanê sipe (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Roşna standarde A',
'exif-lightsource-18' => 'Roşna standarde B',
'exif-lightsource-19' => 'Roşna standarde C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO volframê studyoyi',
'exif-lightsource-255' => 'Çimeyê roşni yê bini',

# Flash modes
'exif-flash-fired-0' => 'flash nêteqa/ta nêkewt',
'exif-flash-fired-1' => 'flash teqa/ta kewt',
'exif-flash-return-0' => 'moda roştê gêrayoxi qefelnaye yo',
'exif-flash-return-2' => 'roşto gêrayox çino',
'exif-flash-return-3' => 'roşto gêrayox tesbit bı/ca bı',
'exif-flash-mode-1' => 'flaşo mecburi teqa',
'exif-flash-mode-2' => 'flasho mecburi qefelnaye yo',
'exif-flash-mode-3' => 'moda otomatike',
'exif-flash-function-1' => 'Fonksiyonê flaşi çini yo',
'exif-flash-redeye-1' => 'modê çim-sur tay kerdişi',

'exif-focalplaneresolutionunit-2' => 'inchî',

'exif-sensingmethod-1' => 'daşinasnayişê ey çino',
'exif-sensingmethod-2' => 'Sensorê wareyê rengê yew-çipi',
'exif-sensingmethod-3' => 'Sensorê wareyê rengê di-çipi',
'exif-sensingmethod-4' => 'Sensorê wareyê rengê hirê-çipi',
'exif-sensingmethod-5' => 'sensora têrêz a ke rengın his kena',
'exif-sensingmethod-7' => 'Sensorê hirê-çizgi',
'exif-sensingmethod-8' => 'sensora aritmetik a ke rengın his kena',

'exif-filesource-3' => 'Dicital makinay kamera',

'exif-scenetype-1' => 'ca de fotoğraf ker',

'exif-customrendered-0' => 'Prosesê normali',
'exif-customrendered-1' => 'proseso xususi',

'exif-exposuremode-0' => 'pozkerdışê otomatiki',
'exif-exposuremode-1' => 'pozkerdışê manueli',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'balansê sıpi yo otomatiki',
'exif-whitebalance-1' => 'balansê sıpi yo manueli',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Manzara',
'exif-scenecapturetype-2' => 'Portre',
'exif-scenecapturetype-3' => 'şew-antış',

'exif-gaincontrol-0' => 'Çıniyo',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Nerm',
'exif-contrast-2' => 'Huşk',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'mırdiyo kêm',
'exif-saturation-2' => 'mırdiyo ziyed',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Nerm',
'exif-sharpness-2' => 'Huşk',

'exif-subjectdistancerange-0' => 'Nêzanayen',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Vinayişê nezdiyi',
'exif-subjectdistancerange-3' => 'Vinayişê duri',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Veriniya zımeyi',
'exif-gpslatitude-s' => 'Veriniya veroci',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'derganiya rocvetış',
'exif-gpslongitude-w' => 'Derganiya rocawan',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => 'Sewiye de roy ra $1 {{PLURAL:$1|metre|metre}} cordeyo',
'exif-gpsaltitude-below-sealevel' => 'Sewiye de roy ra $1 {{PLURAL:$1|metre|metre}} cêrdeyo',

'exif-gpsstatus-a' => 'peymawıtış dewam keno',
'exif-gpsstatus-v' => 'şuxuliyayişê peymawıtışi',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/s',
'exif-gpsspeed-m' => 'Mil/saat',
'exif-gpsspeed-n' => 'milê deryayi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometre',
'exif-gpsdestdistance-m' => 'Mil',
'exif-gpsdestdistance-n' => 'Milê roy',

'exif-gpsdop-excellent' => '($1) Weşo',
'exif-gpsdop-good' => '($1) rındo',
'exif-gpsdop-moderate' => '($1) ne rınd nezi aro',
'exif-gpsdop-fair' => '($1) idare keno',
'exif-gpsdop-poor' => '($1) neqim nê keno',

'exif-objectcycle-a' => 'Teq ê şıfaqi',
'exif-objectcycle-p' => 'Teq ê şani',
'exif-objectcycle-b' => 'Şew u roc',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'hetê raştê ey',
'exif-gpsdirection-m' => 'hetê manyetikê ey',

'exif-ycbcrpositioning-1' => 'Wertekerdış',
'exif-ycbcrpositioning-2' => 'Wayırê-site',

'exif-dc-contributor' => 'İştıraqkeri',
'exif-dc-coverage' => 'Heruna yana wextin grotışa medya',
'exif-dc-date' => 'Tarix(i)',
'exif-dc-publisher' => 'Hesrekar',
'exif-dc-relation' => 'Medyay cı',
'exif-dc-rights' => 'Heqi',
'exif-dc-source' => 'Medyay çımi',
'exif-dc-type' => 'Babeta medyay',

'exif-rating-rejected' => 'Red ke',

'exif-isospeedratings-overflow' => '65535 ra gırdo',

'exif-maxaperturevalue-value' => '$1 APEX (f/$2)',

'exif-iimcategory-ace' => 'Zagon, kultur u keyfiye',
'exif-iimcategory-clj' => 'Arey u huquq',
'exif-iimcategory-dis' => 'Weqey u Qezey',
'exif-iimcategory-fin' => 'Ekonomi u Kar',
'exif-iimcategory-edu' => 'Terbiyet',
'exif-iimcategory-evn' => 'Dorme',
'exif-iimcategory-hth' => 'Weşeyey',
'exif-iimcategory-hum' => 'Elekey merduman',
'exif-iimcategory-lab' => 'Gurweyayin',
'exif-iimcategory-lif' => 'Cıwiyayış u keyf kerdış',
'exif-iimcategory-pol' => 'Siyaset',
'exif-iimcategory-rel' => 'Din u iman kerdış',
'exif-iimcategory-sci' => 'Zanış u teknoloci',
'exif-iimcategory-soi' => 'Sosyal meseley',
'exif-iimcategory-spo' => 'Spor',
'exif-iimcategory-war' => 'Leci, pê şanayış u dışmeney',
'exif-iimcategory-wea' => 'Hewa',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => '($1) Kemiyo',
'exif-urgency-high' => '( $1 ) Vêşiyo',
'exif-urgency-other' => 'Sıftê  şınasiya karberi ($1)',

# External editor support
'edit-externally' => 'Ena dosya bıvurne pe yew programê harici',
'edit-externally-help' => '(Qe informasyonê zafyer ena bevinin [https://www.mediawiki.org/wiki/Manual:External_editors setup instructions])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'pêro',
'namespacesall' => 'pêro',
'monthsall' => 'pêro',
'limitall' => 'pêro',

# Email address confirmation
'confirmemail' => 'Adresê e-posta tesdiq ker',
'confirmemail_noemail' => 'Yew emaîlê tu raştîyê çin o ke [[Special:Preferences|tercihê karberî]] ayar bike.',
'confirmemail_text' => 'Qey gurweyayışê e-postayê wikiyi gani veror e-postayê şıma araşt bıbo.
Adresa şıma re qey erşawıtışê e-postayê araştin, butonê cêrıni pıploxnê.
E-posta yo ke erşawiyeno tede gıreyê kodê araşti esto, gıreyi pıploxne akerê u e-postayê xo araşt kerê.',
'confirmemail_pending' => 'Yew codê konfirmasyonî ma ti ra şiravt;
Eka ti newe hesabê xo viraşt, ti gani yew di dekika vindero u email xo kontrol bike, yani reyna yew hesab meviraz.',
'confirmemail_send' => 'Yew kodê konfirmasyonî email mina bişirave',
'confirmemail_sent' => 'Emailê konfirmasyonî şiravt',
'confirmemail_oncreate' => 'Yew codê konfirmasyonî ma ti ra şiravt;
Ena kod semed ci kewtîşî lazim niyo, feqat ti gani sistem rê eno kod bimocne ke ti opsiyonê emailî wîkî a bike.',
'confirmemail_sendfailed' => '{{SITENAME}} nieşkenî ti ra yew emailê konfirmasyonî bişiravî.
Rica keno ke adresê emailî xo kontrol bike.

Email şawitoğ eno reyna ard: $1',
'confirmemail_invalid' => 'Kodê konfirmasyonî raşt niyo.
Wextê kod ê konfirmasyonî viyerto.',
'confirmemail_needlogin' => ' $1 lazimo ke ti adresê emaîl ê xo konfirme bike.',
'confirmemail_success' => 'Email adresê tu konfirme biy.
Ti eşkeno [[Special:UserLogin|ci kewt]].',
'confirmemail_loggedin' => 'E-posta adresiya şıma hendana tesdiq biya.',
'confirmemail_error' => 'Konfirmasyon ni biy, yew ğelet esto.',
'confirmemail_subject' => '{{SITENAME}} konfirmasyonê adres ê emalî',
'confirmemail_body' => 'Brayo delal, mara ke şıma no IP-adresi ra,
keyepelê {{SITENAME}}i de pêno $2 e-postayi hesab kerda.

eke raşta no e-posta eyê şımayo şıma gani araşt bıkerî, qey araşt kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrêni bıtıkne.

$3

eke şıma hesab *nê akerdo*, qey terqnayışê araşt kerdışê adresa e-postayi gıreyê cêrıni pıploxnê:

$5

kodê araşti heta ıney tarixi $4 meqbulo.',
'confirmemail_body_changed' => 'Yew ten, muhtemelen şıma no IP-adresi $1 ra,
keyepelê {{SITENAME}}i de pê no $2 e-postayi hesab kerd a.

Eke raşta no e-posta eyê şıma yo şıma gani tesdiq bıkerî,
qey tesdiq kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrıni bıtıkne:

$3

eke şıma hesab *a nêkerdo*, qey ibtalê tesdiqkerdışê adresa e-postayi gıreyê cêrıni bıtıknê:

$5

kodê tesdiqi heta ıney tarixi $4 meqbul o.',
'confirmemail_body_set' => 'Jew ten, muhtemelen şıma no IP-adresi $1 ra,
keye pelê {{SITENAME}}i de pê no $2 e-postayi hesab kerda.

Eke raşta no e-posta eyê şıma yo şıma gani tesdiq bıkerî,
qey tesdiq kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrıni bıtıkne:

$3

eke şıma hesab *nêakerdo*, qey ibtalê tesdiq kerdışê adresa e-postayi gıreyê cêrêni bıtıknê:

$5

kodê tesdiqi heta ıney tarixi $4 meqbul o.',
'confirmemail_invalidated' => 'Konfermasyonê adres ê emaîlî iptal biy',
'invalidateemail' => 'confirmasyonê e-maili iptal bik',

# Scary transclusion
'scarytranscludedisabled' => '[Transcludê înterwîkîyî nihebityeno]',
'scarytranscludefailed' => '[Qe $1 fetch kerdişî nihebitiyeno]',
'scarytranscludefailed-httpstatus' => '[Qande $1 şablon nêşa bıgêriyo: HTTP $2]',
'scarytranscludetoolong' => '[Ena URL zaf dergo]',

# Delete conflict
'deletedwhileediting' => "'''Teme''': Ena pele  verniyê ti de eseteriyaya!",
'confirmrecreate' => "Karberê [[User:$1|$1]]î ([[User talk:$1|mesac]]), verniyê vurnayîşê ti ra ena pele wedarno, sebeb: ''$2''
Ma rica keno tesdiq bike ke ti raştî wazeno eno pel bivirazo.",
'confirmrecreate-noreason' => 'karbero [[User:$1|$1]] ([[User talk:$1|mesac]]) , dest pêkerdışiena pela sero vurnayışiya tepya ena pela besternê. Şıma qayıli ke ena pela fına vırazê se ena pela tesdiq kerê.',
'recreate' => 'Reyna viraz',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'Temam',
'confirm-purge-top' => 'Vervirê na pele bestere?',
'confirm-purge-bottom' => 'Purge kerdişê yew pel cacheyî estereno u revizyonê penîyî mucneno.',

# action=watch/unwatch
'confirm-watch-button' => 'TEMAM',
'confirm-watch-top' => 'Ena pele lista xoya seyrkerdışi ke',
'confirm-unwatch-button' => 'TEMAM',
'confirm-unwatch-top' => 'Ena pele lista xoya seyirkerdışi ra bıvece?',

# Separators for various lists, etc.
'semicolon-separator' => '&#32;',
'comma-separator' => ',&#32;',
'colon-separator' => ':&#32;',
'pipe-separator' => '&#32;|&#32;',
'word-separator' => '&#32;',
'ellipsis' => '...',
'percent' => '$1%',
'parentheses' => '($1)',
'brackets' => '[$1]',
'quotation-marks' => '"$1".',

# Multipage image navigation
'imgmultipageprev' => '← peleyê verin',
'imgmultipagenext' => 'pela badê cû →',
'imgmultigo' => 'Şo!',
'imgmultigoto' => 'Şo pela da $1',

# Table pager
'ascending_abbrev' => 'berz',
'descending_abbrev' => 'nızm',
'table_pager_next' => 'Pela peyêne',
'table_pager_prev' => 'Pela verêne',
'table_pager_first' => 'Pela jûyıne',
'table_pager_last' => 'Pela peyêne',
'table_pager_limit' => 'Jû pele de $1 unsuran bımocne',
'table_pager_limit_label' => 'Her pele ra xacetan',
'table_pager_limit_submit' => 'Şo',
'table_pager_empty' => 'Netice çini yo',

# Auto-summaries
'autosumm-blank' => 'Pele de her çi wederna',
'autosumm-replace' => "Maqale pê '$1' vuriya",
'autoredircomment' => 'Pele [[$1]] rê redirek biyo',
'autosumm-new' => "Pela vıraziyê, '$1' bıvinê",

# Size units
'size-bytes' => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',
'size-terabytes' => '$1 TB',
'size-petabytes' => '$1 PB',
'size-exabytes' => '$1 EB',
'size-zetabytes' => '$1 ZB',
'size-yottabytes' => '$1 YB',

# Bitrate units
'bitrate-bits' => '$1bps',
'bitrate-kilobits' => '$1kbps',
'bitrate-megabits' => '$1Mbps',
'bitrate-gigabits' => '$1Gbps',
'bitrate-terabits' => '$1Tbps',
'bitrate-petabits' => '$1Pbps',
'bitrate-exabits' => '$1Ebps',
'bitrate-zetabits' => '$1Zbps',
'bitrate-yottabits' => '$1Ybps',

# Live preview
'livepreview-loading' => 'Ho bar keni...',
'livepreview-ready' => 'Ho bar keni... Hezir o!',
'livepreview-failed' => 'Verqeyd nibiyo! Verqeydo normal deneme bike.',
'livepreview-error' => 'Nieşken giredayi biy: $1 "$2".
Verqeydo normal deneme bike.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Vurnayîşî ke {{PLURAL:$1|seniye|seniyeyî}} ra newiyerî belki inan nimucneno.',
'lag-warn-high' => 'Eka serverê databaseyî zaf hebitiyeno, ayra vurnayîşî ke {{PLURAL:$1|seniye|seniyeyî}} ra newiyerî belki inan nimucneno.',

# Watchlist editor
'watchlistedit-numitems' => 'Listeyê seyirkerdişi ti de {{PLURAL:$1|1 title|$1 titles}} esta, feqet pelayanê minaqeşeyan dahil niyê.',
'watchlistedit-noitems' => 'Listeyê seyr kerdişê tu de seroğ çin o.',
'watchlistedit-normal-title' => 'Listeyê seyirkerdişi bivurne',
'watchlistedit-normal-legend' => 'Listeyê seyr kerdişê tu de seroğ biwedarna.',
'watchlistedit-normal-explain' => 'Listeyê seyr kerdîşî ti de serogî cor de mucnayiyo.
Eka ti wazeno seroğ biwedarne, kuti ke kistê de, ay işaret bike u "{{int:Watchlistedit-normal-submit}}" klik bike.
Ti hem zi eşkeno [[Special:EditWatchlist/raw|edit the raw list]].',
'watchlistedit-normal-submit' => 'Seroğî biwedarnê',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 seroğ|$1 seroğî}} seyr kerdişê tu ra wedarno.',
'watchlistedit-raw-title' => 'Listeyê seyirkerdişi ye hami bivurne',
'watchlistedit-raw-legend' => 'Listeyê seyirkerdişi ye hami bivurne',
'watchlistedit-raw-explain' => 'Listeyê seyr kerdîşî ti de serogî cor de mucnayiyo u ti eşkeno pê dekerdiş u wedarnayîş liste bivurne.
Eka vurnayîşê ti qediyo, Listeyê Seyr Kerdişî Rocaniye Bike "{{int:Watchlistedit-raw-submit}}" klik bike.
Ti hem zi eşkeno [[Special:EditWatchlist|use the standard editor]].',
'watchlistedit-raw-titles' => 'Seroğî:',
'watchlistedit-raw-submit' => 'Listeyê seyri newen ke',
'watchlistedit-raw-done' => 'Listeyê tuyê seyrkerdişi rocaniye biyo',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 seroğ|$1 seroğî}} de kerd:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 seroğ|$1 seroği}} besteriyaye:',

# Watchlist editing tools
'watchlisttools-view' => 'vurnayışanê eleqadari bıvin',
'watchlisttools-edit' => 'Lista seyrkerdışi bıvênên u bıvurnên',
'watchlisttools-raw' => 'Listeyê seyr-kerdışi bıvin',

# Iranian month names
'iranian-calendar-m1' => 'Farvardin',
'iranian-calendar-m2' => 'Ordibeheşt',
'iranian-calendar-m3' => 'Xordad',
'iranian-calendar-m4' => 'Tir',
'iranian-calendar-m5' => 'Morded',
'iranian-calendar-m6' => 'Şahrivar',
'iranian-calendar-m7' => 'Mehr',
'iranian-calendar-m8' => 'Aban',
'iranian-calendar-m9' => 'Azar',
'iranian-calendar-m10' => 'Dey',
'iranian-calendar-m11' => 'Behman',
'iranian-calendar-m12' => 'Esfend',

# Hijri month names
'hijri-calendar-m1' => 'Muharram',
'hijri-calendar-m2' => 'Sefer',
'hijri-calendar-m3' => 'Rebiel ewwel',
'hijri-calendar-m4' => 'Rebiel sani',
'hijri-calendar-m5' => 'Cemaziel ewwel',
'hijri-calendar-m6' => 'Cemaziel tani',
'hijri-calendar-m7' => 'Receb',
'hijri-calendar-m8' => 'Şehban',
'hijri-calendar-m9' => 'Remezan',
'hijri-calendar-m10' => 'Şewwal',
'hijri-calendar-m11' => 'Zil Qade',
'hijri-calendar-m12' => 'Zil Hicce',

# Hebrew month names
'hebrew-calendar-m1' => 'Tişrei',
'hebrew-calendar-m2' => 'Çeşvan',
'hebrew-calendar-m3' => 'Kislev',
'hebrew-calendar-m4' => 'Tevet',
'hebrew-calendar-m5' => 'Şevat',
'hebrew-calendar-m6' => 'Adar',
'hebrew-calendar-m6a' => 'Adar I',
'hebrew-calendar-m6b' => 'Adar II',
'hebrew-calendar-m7' => 'Nisan',
'hebrew-calendar-m8' => 'Iyar',
'hebrew-calendar-m9' => 'Sivan',
'hebrew-calendar-m10' => 'Tamuz',
'hebrew-calendar-m11' => 'Av',
'hebrew-calendar-m12' => 'Elul',
'hebrew-calendar-m1-gen' => 'Tişrei',
'hebrew-calendar-m2-gen' => 'Çeşvan',
'hebrew-calendar-m3-gen' => 'Kislev',
'hebrew-calendar-m4-gen' => 'Tevet',
'hebrew-calendar-m5-gen' => 'Şevat',
'hebrew-calendar-m6-gen' => 'Adar',
'hebrew-calendar-m6a-gen' => 'Adar I',
'hebrew-calendar-m6b-gen' => 'Adar II',
'hebrew-calendar-m7-gen' => 'Nisan',
'hebrew-calendar-m8-gen' => 'Iyar',
'hebrew-calendar-m9-gen' => 'Sivan',
'hebrew-calendar-m10-gen' => 'Tamuz',
'hebrew-calendar-m11-gen' => 'Av',
'hebrew-calendar-m12-gen' => 'Elul',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|mesac]])',
'timezone-utc' => '[[UTC]]',

# Core parser functions
'unknown_extension_tag' => 'Etiketê ekstensiyon ê "$1"î nizanyeno',
'duplicate-defaultsort' => '\'\'\'Tembe:\'\'\' Hesıbyaye sırmey ratnayış de "$2" sırmey ratnayış de "$1"i nêhesıbneno.',

# Special:Version
'version' => 'Versiyon',
'version-extensions' => 'Ekstensiyonî ke ronaye',
'version-specialpages' => 'Pelanê xasiyan',
'version-parserhooks' => 'Çengelê Parserî',
'version-variables' => 'Vurnayeyî',
'version-antispam' => 'Spam vındarnayış',
'version-skins' => 'Cıldi',
'version-api' => 'API',
'version-other' => 'Bin',
'version-mediahandlers' => 'Kulbê medyayî',
'version-hooks' => 'Çengelî',
'version-parser-extensiontags' => 'Etiketê ekstensiyon ê parserî',
'version-parser-function-hooks' => 'Çengelê ekstensiyon ê parserî',
'version-hook-name' => 'Nameyê çengelî',
'version-hook-subscribedby' => 'Eza biyayoğ',
'version-version' => '(Versiyon $1)',
'version-svn-revision' => '(r$2)',
'version-license' => 'Lisans',
'version-poweredby-credits' => "Ena wiki, dezginda '''[https://www.mediawiki.org/ MediaWiki]''' ya piya vıraziyaya, heqê telifi © 2001-$1 $2.",
'version-poweredby-others' => 'Zewmi',
'version-poweredby-translators' => "Açernere translatewiki.net'i",
'version-credits-summary' => 'Ma qayılime ke [[Special:Version|MediaWiki]] rê ke kami destek dayo wa mayê vanime inan bışınasne.',
'version-license-info' => "MediaWiki xoseri jew nuştereno; MediaWiki'yer, weqfê xoseri nuşteren GNU lisansiya merdumi şene ke vıla kerê, bıvurnê u timar kerê.

Nuşterenê MediaWiki merdumi cı ra nahfat bivinê deye êyê mısade danê; feqet ke nêşeno BIROŞO yana XOSERİ VILA KERO qerantiya ney çına. bewni rê lisansta GNU'y.

enê programiya piya [{{SERVER}}{{SCRIPTPATH}}/COPYING jew kopyay lisans dê GNU] zi şımarê icab keno; narak lisansê şıma çıno se, Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA adresi ra yana [//www.gnu.org/licenses/old-licenses/gpl-2.0.html enê lisansi buwane].",
'version-software' => 'Softwareyê ronayi',
'version-software-product' => 'Mal',
'version-software-version' => 'Versiyon',
'version-entrypoints' => "heruna dekewtış de GRE'i",
'version-entrypoints-header-entrypoint' => 'Heruna dekewtışi',
'version-entrypoints-header-url' => 'GRE',
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath Article path]',
'version-entrypoints-scriptpath' => '[https://www.mediawiki.org/wiki/Manual:$wgScriptPath Script path]',

# Special:Redirect
'redirect' => "Hetenayışa dosyay, karberi yana  rewizyona ID'i",
'redirect-legend' => 'Hetenayışa dosya yana pela',
'redirect-submit' => 'Şo',
'redirect-lookup' => 'Bewni',
'redirect-value' => 'Erc:',
'redirect-user' => 'Kamiya Karberi:',
'redirect-revision' => 'Rewizyona pela',
'redirect-file' => 'Namey dosya',
'redirect-not-exists' => 'Erc nêvineyê',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Dosyayanê zey pêyan cı geyrê',
'fileduplicatesearch-summary' => 'Dosyanê çıftan bınê têmiyankewteyan de bıgeyre.',
'fileduplicatesearch-legend' => 'kopyayê ena dosya bigêre',
'fileduplicatesearch-filename' => 'Nameyê dosyayi',
'fileduplicatesearch-submit' => 'Cı geyre',
'fileduplicatesearch-info' => '$1 × $2 piksel<br />Ebatê dosyayî: $3<br />Tipê MIMEî: $4',
'fileduplicatesearch-result-1' => "Dosyayê ''$1î'' de hem-kopya çini yo.",
'fileduplicatesearch-result-n' => "Dosyayê ''$1î'' de {{PLURAL:$2|1 hem-kopya|$2 hem-kopyayî'}} esto.",
'fileduplicatesearch-noresults' => 'Ebe namey "$1" ra dosya nêdiyayê.',

# Special:SpecialPages
'specialpages' => 'Pelê xısusiy',
'specialpages-note-top' => 'Kıtabek',
'specialpages-note' => '*Normal pera bağsi.
* <span class="mw-specialpagerestricted">Peré bağseyé ke groté ver hafıza.</span>',
'specialpages-group-maintenance' => 'Raporê tepıştışi',
'specialpages-group-other' => 'Pelê xasiyê bini',
'specialpages-group-login' => 'Cı kewe / hesab vıraze',
'specialpages-group-changes' => 'Vurnayişê peni u logan',
'specialpages-group-media' => 'Raporê medya u bar kerdîşî',
'specialpages-group-users' => 'Karber u heqqî',
'specialpages-group-highuse' => 'Peleyê ke vêşi karênê',
'specialpages-group-pages' => 'Listeyê pelan',
'specialpages-group-pagetools' => 'Haletê pelan',
'specialpages-group-wiki' => 'Melumat u haceti',
'specialpages-group-redirects' => 'Pela xasîyê ke heteneyayê',
'specialpages-group-spam' => 'haletê spami',

# Special:BlankPage
'blankpage' => 'Pela venge',
'intentionallyblankpage' => 'Ena pel bi zanayişî weng mendo.',

# External image whitelist
'external_image_whitelist' => '  #no satır zey xo verde/raverde<pre>
#parçeyê ifadeya rêzbiyayeyani (têna zerreyê ıney de // ) u çıtayo/çiyo zi mende cêr de têare kerê.
#ney URL ya (hotlink) resmê teberi de hemcıta benî.
#Ê yê ke hemcıt (eşleşmek-hemçift) biyê zey resımi asenî, eqsê hal de zi zey gıreyê resmi aseno.
satır ê ke pê ney # # destpêkenê zey mışore/mıjore muamele vineno.
#herfa gırd û qıci ferq nêkeno

#parçeyê ifadeya rêzbiyayeyani bıerzê serê ney satıri. no satır zey xo verde/raverde </pre>',

# Special:Tags
'tags' => 'Etiketê vurnayîşê raştî',
'tag-filter' => 'Avrêcê [[Special:Tags|Etiketi]]:',
'tag-filter-submit' => 'Avrêc',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Etiket|Etiketi}}]]: $2)',
'tags-title' => 'Etiketan',
'tags-intro' => 'Eno pel de listeyê eyiketî este ke belki software pê ey edit kenî.',
'tags-tag' => 'Nameyê etiketi',
'tags-display-header' => 'Listeyê vurnayîşî de esayîş',
'tags-description-header' => 'Deskripsyonê manay ê hemî',
'tags-active-header' => 'Activ o?',
'tags-hitcount-header' => 'Vurnayîşî ke etiket biyê',
'tags-active-yes' => 'E',
'tags-active-no' => 'Nê',
'tags-edit' => 'bıvurne',
'tags-hitcount' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}}',

# Special:ComparePages
'comparepages' => 'Pela miqeyese ke',
'compare-selector' => 'Revizyonê pele miqayese bike',
'compare-page1' => 'Pele 1',
'compare-page2' => 'Pele 2',
'compare-rev1' => 'Revizyonê 1i',
'compare-rev2' => 'Revizyonê 2i',
'compare-submit' => 'Miqayese',
'compare-invalid-title' => 'Sernameyo ke şımayê vanê ravêrde niyo.',
'compare-title-not-exists' => 'Sernameyo ke şımayê vanê mewcud niyo.',
'compare-revision-not-exists' => 'Revizyono ke şımaye vanê mewcud niyo.',

# Database error messages
'dberr-header' => 'Ena Wiki de yew ğelet esta',
'dberr-problems' => 'Mayê muxulêm! Ena sita dı newke xırabiya teknik esta.',
'dberr-again' => 'Yew di dekika vinder u hin bar bike.',
'dberr-info' => '(Erzmelumati ra xızmetkari nêreseno: $1)',
'dberr-info-hidden' => '(Ardendé erz malumatiya gredayışo nébeno)',
'dberr-usegoogle' => 'Ti eşkeno hem zi ser Google de bigêre.',
'dberr-outofdate' => 'Ekê raten da ma deyê belki zi newen niyo qandê coy diqet kerê.',
'dberr-cachederror' => 'Pel ke ti wazeno yew kopyayê cacheyî ay esto, ay belki rocaniyeyo.',

# HTML forms
'htmlform-invalid-input' => 'Inputê ti de tayê ğeletî estê',
'htmlform-select-badoption' => 'Ena değer ke ti spesife kerd yew opsiyonê raştî ni yo.',
'htmlform-int-invalid' => 'Ena değer ke ti spesife kerd yew reqem ni yo.',
'htmlform-float-invalid' => 'Ena değer ke ti spesife kerd yew amar ni yo.',
'htmlform-int-toolow' => 'Ena değer ke ti spesife kerd maxsimumê $1î ra kilmyer o.',
'htmlform-int-toohigh' => 'Ena değer ke ti spesife kerd maxsimumê $1î ra zafyer o.',
'htmlform-required' => 'Ena deger lazim o',
'htmlform-submit' => 'Bişirav',
'htmlform-reset' => 'Vurnayişî reyna biyar',
'htmlform-selectorother-other' => 'Bin',
'htmlform-no' => 'Nê',
'htmlform-yes' => 'Eya',
'htmlform-chosen-placeholder' => 'Opsiyon weçine',

# SQLite database support
'sqlite-has-fts' => '$1 tam-metn destegê cı geyrayışiya piya',
'sqlite-no-fts' => '$1 tam-metn bê destegê cı geyrayışi',

# New logging system
'logentry-delete-delete' => '$1 pera $3 {{GENDER:$2|besternê}}',
'logentry-delete-restore' => '$1 pela $3 {{GENDER:$2|peyser arde}}',
'logentry-delete-event' => '$1 $3: $4 de asayışê {{PLURAL:$5|cıkerdışi|cıkerdışan}} {{GENDER:$2|vurna}}',
'logentry-delete-revision' => '$1 pela $3: $4 de asayışê {{PLURAL:$5|yew revizyoni|$5 revizyonan}} {{GENDER:$2|vurna}}',
'logentry-delete-event-legacy' => '$1 Asayışê {{GENDER:$2|vurnayışê}} $3 dekerde de',
'logentry-delete-revision-legacy' => '$1 pela $3 de asayışê revizyonan {{GENDER:$2|vurna}}',
'logentry-suppress-delete' => '$1 Pela $3 {{GENDER:$2|dewosiyayiye}}',
'logentry-suppress-event' => '$1 pela $3: $4 de dızdêni asayışê {{PLURAL:$5|yew weqeyo rocane|$5 weqeyê rocaney}} {{GENDER:$2|vurnay}}',
'logentry-suppress-revision' => '$1 pela $3: $4 de asayışê {{PLURAL:$5|yew revizyoni|$5 revizyonan}} dızdêni {{GENDER:$2|vurna}}',
'logentry-suppress-event-legacy' => '$3 asayışê cıyo xısusi $1 dızdêni {{GENDER:$2|vurna}}',
'logentry-suppress-revision-legacy' => '$3 asayışê cıyo xısusi $1 dızdêni {{GENDER:$2|vurna}}',
'revdelete-content-hid' => 'zerrek nımıteyo',
'revdelete-summary-hid' => 'xulasaya vurnayışi nımıtiya',
'revdelete-uname-hid' => 'nameyê karberi nımıteyo',
'revdelete-content-unhid' => 'zerrek nênımıteyo',
'revdelete-summary-unhid' => 'xulasaya vurnayışi nênımıtiya',
'revdelete-uname-unhid' => 'nameyê karberi nênımıteyo',
'revdelete-restricted' => 'vergırewtışê ke xızmekaran rê biye',
'revdelete-unrestricted' => 'vergırewtışê ke xızmekaran rê dariyê we',
'logentry-move-move' => '$1 pela $3 {{GENDER:$2|berd}} $4',
'logentry-move-move-noredirect' => 'Hetenayışi sera pela $3 ra $1 {{GENDER:$2|berd}} pela $4',
'logentry-move-move_redir' => 'Hetenayışi sera pela $3 ra $1 {{GENDER:$2|berd}} pela $4',
'logentry-move-move_redir-noredirect' => 'Hetenayışi sera pela $3 ra $1 {{GENDER:$2|berd}} pela $4',
'logentry-patrol-patrol' => '$1 versiyono $4 ke {{GENDER:$2|nişan biyo}} pela $3 ra qontrol kerd',
'logentry-patrol-patrol-auto' => 'Çımraviyarnayışê $4 pela $3 ke $1 otomatikman {{GENDER:$2|nişan biyo}} qontrol kerd',
'logentry-newusers-newusers' => 'Hesabê karberê $1 {{GENDER:$2|vıraziya}}',
'logentry-newusers-create' => 'Hesabê karberi $1 {{GENDER:$2|vıraziya}}',
'logentry-newusers-create2' => 'Hesabê karberi $1 terefê $3 ra {{GENDER:$2|vıraziya}}',
'logentry-newusers-byemail' => 'Karber $1 hesabe $3 {{GENDER:$2|virast}} u parola rist epostadaci',
'logentry-newusers-autocreate' => 'Hesabê karberi $1 otomatikmen {{GENDER:$2|vıraşt}}',
'logentry-rights-rights' => '$1 qandê $3 rê ezayiya grube $4 ra $5 {{GENDER:$2|vuriye}}',
'logentry-rights-rights-legacy' => '$1 qandê $3 rê ezayiya grube {{GENDER:$2|vuriye}}',
'logentry-rights-autopromote' => '$1 otomatikmen $4 ra $5 {{GENDER:$2|terfi bi}}',
'rightsnone' => '(çino)',

# Feedback
'feedback-bugornote' => 'Jew mersela teferruato teknik esta şıma reca malumatê şıma hazıro se [ $1  jew xırab rapor] bıvinê.Zewbi zi, formê cerê xo rê şenê karfiyê. Vatışê xo pela da "[ $3  $2 ]", namey karber dê xoya piya u wasteriya karfiye.',
'feedback-subject' => 'Mersel:',
'feedback-message' => 'Mesac:',
'feedback-cancel' => 'Bıterkne',
'feedback-submit' => 'Peyxeberdar Bırşe',
'feedback-adding' => 'Pela rê peyxeberdar defêno...',
'feedback-error1' => 'Xeta: API ra neticey ne vıcyay',
'feedback-error2' => 'Xeta: Timar kerdış nebı',
'feedback-error3' => 'Xeta: API ra cewab çıno',
'feedback-thanks' => 'Teşekkur kemê! Vatışê şıma pela da "[$2 $1]" esta.',
'feedback-close' => 'Biya star',
'feedback-bugcheck' => 'Harika! Sadece [xırabina ke $1 ] çınyayışê cı kontrol keno.',
'feedback-bugnew' => 'Mı qontrol ke. Xetaya newi xeber ke',

# Search suggestions
'searchsuggest-search' => 'Cı geyre',
'searchsuggest-containing' => 'Estên...',

# API errors
'api-error-badaccess-groups' => 'Ena wiki de dosya barkerdışi rê mısade nêdeyêno.',
'api-error-badtoken' => 'Xirabiya zerrek:Xırab resim.',
'api-error-copyuploaddisabled' => 'URL barkerdış ena waster dı qefılyayo.',
'api-error-duplicate' => 'Ena {{PLURAL:$1|ze ke [zey $2]|biya [zey dosya da $2]}} zeq wesiqa biya wendeyê.',
'api-error-duplicate-archive' => 'Ena {{PLURAL:$1|vurneyaya [$2 zey na dosya]| [zerrey cı zey $2 dosya]}} aseno,feqet {{PLURAL:$1|ena dosya|tewr veri}} besterneyaya.',
'api-error-duplicate-archive-popup-title' => 'Ena {{PLURAL:$1|Dosya besterneyaya|dosya}} xora  besterneyaya.',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|dosyaya|dosyaya}} dılet',
'api-error-empty-file' => 'Dosyaya ke şıma rışta venga.',
'api-error-emptypage' => 'Newi, pelaya veng vıraştışi rê mısade nêdeyêno.',
'api-error-fetchfileerror' => 'Xırabiya zerrek:Dosya grotış dı tay çi raşt nêşı.',
'api-error-fileexists-forbidden' => 'Jû dosya be nê nameyê "$1" ra xora esta, u naye sero nêşeno ke bınuşiyo.',
'api-error-fileexists-shared-forbidden' => 'Jû dosya be nameyê "$1" ra depoyê doyeyanê barekerdeyan de xora esta, u naye sero nêşeno ke bınuşiyo.',
'api-error-file-too-large' => 'Dosyaye ke şıma rışta zaf gırda.',
'api-error-filename-tooshort' => 'Namayê dosyayi zaf kilm a.',
'api-error-filetype-banned' => 'Tipê ena dosya qedexe biya.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|Dosya qebul ne vinena|dosya qebul ne vinena|Ena babeta dosya qebul ne vinena}}. Eke cırê izin deyayo se {{PLURAL:$3|Babatan dosyayan|babeta dosyayan}} de $2 bıvin.',
'api-error-filetype-missing' => 'Derganiya dosya kemiya',
'api-error-hookaborted' => 'Vurnayişê tu ke to cerbna pê yew çengal ra terkneya.',
'api-error-http' => 'Xırabiya zerreki:Wasteriya irtabet bırya.',
'api-error-illegal-filename' => 'Ena nameyê dosyayi kebul nibena.',
'api-error-internal-error' => 'Xırabiye zerrek:Na wikide barkerdış de şıma dı çıyê raşt nêşı.',
'api-error-invalid-file-key' => 'Xırabiye zerrek:İdari  depokerdışê dosya nêvineya.',
'api-error-missingparam' => 'Xırabiye zerrek:Parametre waştış dı xırabin',
'api-error-missingresult' => 'Xırabiya zerrek:Kopya kerdışê cı nêbı.',
'api-error-mustbeloggedin' => 'Dosya barkerdışi re cıkewtış icab keno.',
'api-error-mustbeposted' => 'Zırabiya zerrek:HTTP POST waştış icab keno',
'api-error-noimageinfo' => 'Barkerdışê dosya temamya lakin wasterira marê malumat nêdeyayo.',
'api-error-nomodule' => 'Xırabiya zerrek:Sazkerdışê modul dê barkerdışi nêvıraziyayo.',
'api-error-ok-but-empty' => 'Xırabiya zerrek:Wastero cıwan nêdano.',
'api-error-overwrite' => 'Ser yew dosyayê ke hama esta, ser ey qeyd nibena.',
'api-error-stashfailed' => 'Xırabiya zerrek:Wasteri idari dosyey kerdi vıni.',
'api-error-publishfailed' => 'Xetaya zerrey: Cıgeyrayoği nêşiya dosyaya rocaniye akero.',
'api-error-timeout' => 'Cıwab dayışê wasteri peyra mend.',
'api-error-unclassified' => 'Yew xeteyê nizanyeni biya.',
'api-error-unknown-code' => "$1'dı jew xeta vıciye",
'api-error-unknown-error' => 'Zerre xırabin:Dasoya barkerdış de tay çi raşt nêşı.',
'api-error-unknown-warning' => "$1'dı ikazo xırab:",
'api-error-unknownerror' => "$1'dı jew xeta vıciye",
'api-error-uploaddisabled' => 'BArkerdış ena wikide qefılneyayo',
'api-error-verification-error' => 'Dosya xırabiya yana derganiya cı xıraba.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|saniya|saniyey}}',
'duration-minutes' => '$1 {{PLURAL:$1|deqa|deqey}}',
'duration-hours' => '($1 {{PLURAL:$1|seate|seati}})',
'duration-days' => '($1 {{PLURAL:$1|roce|roci}})',
'duration-weeks' => '$1 {{PLURAL: $1|hefte|heftey}}',
'duration-years' => '$1 {{PLURAL:$1|serre|serri}}',
'duration-decades' => '$1 {{PLURAL:$1|dades|dadesi}}',
'duration-centuries' => '$1 {{PLURAL:$1|seserre|seserri}}',
'duration-millennia' => '$1 {{PLURAL:$1|milenyum|milenyumi}}',

# Image rotation
'rotate-comment' => 'Resim heta sehata $1 {{PLURAL:$1|derece|derecey}} bi cerx',

# Limit report
'limitreport-title' => 'Agoznaye malumata profili:',
'limitreport-cputime' => 'CPU dem karnayış',
'limitreport-cputime-value' => '$1 {{PLURAL:$1|saniye|saniyeyan}}',
'limitreport-walltime' => 'Raştay demdı bıkarn',
'limitreport-walltime-value' => '$1 {{PLURAL:$1|saniye|saniyeyan}}',
'limitreport-ppvisitednodes' => 'Amariya ziyaretda gozgıreya verkarkerdoği',
'limitreport-ppgeneratednodes' => 'Amariya vıraştışda gozgırandé vekarkerdoği',
'limitreport-postexpandincludesize' => 'Ebata herayina rışteri dahil a.',
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|bayt|bayti}}',
'limitreport-templateargumentsize' => 'Ebata hacetandi şablonan',
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|bayt|bayti}}',
'limitreport-expansiondepth' => 'Tewr veşi herayina dergbiyayışi',
'limitreport-expensivefunctioncount' => 'Amoriya fonksiyonde vay agozni',

# Special:ExpandTemplates
'expandtemplates' => 'şablonan hêra ker',
'expand_templates_intro' => 'Na pela xususi metın geno u şablonê ke tedeyê reyna reyna hêra keno.
U hem zi nê fonksiyonan hêra keno
<nowiki>{{</nowiki>#language:…}}</code>, u zey nê parametreyan
<nowiki>{{</nowiki>CURRENTDAY}}</code>
Eneri Medya wiki sera xo keno.',
'expand_templates_title' => 'Sernameyê weziyeti, misal qandê {{FULLPAGENAME}}.:',
'expand_templates_input' => 'sernameyê cıkewtışi:',
'expand_templates_output' => 'netice',
'expand_templates_xml_output' => 'XML vıraştış',
'expand_templates_ok' => 'temam',
'expand_templates_remove_comments' => 'Tefsiran wedare',
'expand_templates_remove_nowiki' => 'neticeyan de etiketê <nowiki> yan çap bıker',
'expand_templates_generate_xml' => 'Dara XML arêdayoği bımocne',
'expand_templates_preview' => 'Verqayt',

);
