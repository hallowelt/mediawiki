<?php
/** Finnish (suomi)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Toiminnot',
	NS_TALK             => 'Keskustelu',
	NS_USER             => 'Käyttäjä',
	NS_USER_TALK        => 'Keskustelu_käyttäjästä',
	NS_PROJECT_TALK     => 'Keskustelu_{{GRAMMAR:elative|$1}}',
	NS_FILE             => 'Tiedosto',
	NS_FILE_TALK        => 'Keskustelu_tiedostosta',
	NS_MEDIAWIKI        => 'Järjestelmäviesti',
	NS_MEDIAWIKI_TALK   => 'Keskustelu_järjestelmäviestistä',
	NS_TEMPLATE         => 'Malline',
	NS_TEMPLATE_TALK    => 'Keskustelu_mallineesta',
	NS_HELP             => 'Ohje',
	NS_HELP_TALK        => 'Keskustelu_ohjeesta',
	NS_CATEGORY         => 'Luokka',
	NS_CATEGORY_TALK    => 'Keskustelu_luokasta',
];

$namespaceAliases = [
	'Kuva' => NS_FILE,
	'Keskustelu_kuvasta' => NS_FILE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Aktiiviset_käyttäjät' ],
	'Allmessages'               => [ 'Järjestelmäviestit' ],
	'AllMyUploads'              => [ 'Kaikki_tallennukseni' ],
	'Allpages'                  => [ 'Kaikki_sivut' ],
	'Ancientpages'              => [ 'Kuolleet_sivut' ],
	'Badtitle'                  => [ 'Kelpaamaton_otsikko' ],
	'Blankpage'                 => [ 'Tyhjä_sivu' ],
	'Block'                     => [ 'Estä' ],
	'BlockList'                 => [ 'Muokkausestot' ],
	'Booksources'               => [ 'Kirjalähteet' ],
	'BotPasswords'              => [ 'Bottisalasanat' ],
	'BrokenRedirects'           => [ 'Virheelliset_ohjaukset', 'Virheelliset_uudelleenohjaukset' ],
	'Categories'                => [ 'Luokat' ],
	'ChangeEmail'               => [ 'Muuta_sähköpostiosoite' ],
	'ChangePassword'            => [ 'Muuta_salasana', 'Alusta_salasana' ],
	'ComparePages'              => [ 'Vertaa_sivuja' ],
	'Confirmemail'              => [ 'Varmista_sähköpostiosoite' ],
	'Contributions'             => [ 'Muokkaukset' ],
	'CreateAccount'             => [ 'Luo_tunnus' ],
	'Deadendpages'              => [ 'Linkittömät_sivut' ],
	'DeletedContributions'      => [ 'Poistetut_muokkaukset' ],
	'Diff'                      => [ 'Ero' ],
	'DoubleRedirects'           => [ 'Kaksinkertaiset_ohjaukset', 'Kaksinkertaiset_uudelleenohjaukset' ],
	'EditPage'                  => [ 'Muokkaa_sivua' ],
	'EditWatchlist'             => [ 'Muokkaa_tarkkailulistaa' ],
	'Emailuser'                 => [ 'Lähetä_sähköpostia' ],
	'ExpandTemplates'           => [ 'Mallineiden_laajennus' ],
	'Export'                    => [ 'Vie_sivuja' ],
	'Fewestrevisions'           => [ 'Vähiten_muokatut_sivut' ],
	'FileDuplicateSearch'       => [ 'Kaksoiskappaleiden_haku' ],
	'Filepath'                  => [ 'Tiedostopolku' ],
	'Import'                    => [ 'Tuo_sivuja' ],
	'Invalidateemail'           => [ 'Hylkää_sähköpostiosoite' ],
	'JavaScriptTest'            => [ 'JavaScriptTesti' ],
	'LinkSearch'                => [ 'Linkkihaku' ],
	'Listadmins'                => [ 'Ylläpitäjät' ],
	'Listbots'                  => [ 'Botit' ],
	'ListDuplicatedFiles'       => [ 'Listaa_tuplatiedostot' ],
	'Listfiles'                 => [ 'Tiedostoluettelo' ],
	'Listgrouprights'           => [ 'Käyttäjäryhmien_oikeudet' ],
	'Listredirects'             => [ 'Ohjaukset', 'Ohjaussivut', 'Uudelleenohjaukset' ],
	'Listusers'                 => [ 'Käyttäjät' ],
	'Lockdb'                    => [ 'Lukitse_tietokanta' ],
	'Log'                       => [ 'Loki', 'Lokit' ],
	'Lonelypages'               => [ 'Yksinäiset_sivut' ],
	'Longpages'                 => [ 'Pitkät_sivut' ],
	'MediaStatistics'           => [ 'Median_tiedot' ],
	'MergeHistory'              => [ 'Liitä_muutoshistoria' ],
	'MIMEsearch'                => [ 'MIME-haku' ],
	'Mostcategories'            => [ 'Luokitelluimmat_sivut' ],
	'Mostimages'                => [ 'Viitatuimmat_tiedostot' ],
	'Mostinterwikis'            => [ 'Eniten_kielilinkkejä' ],
	'Mostlinked'                => [ 'Viitatuimmat_sivut' ],
	'Mostlinkedcategories'      => [ 'Viitatuimmat_luokat' ],
	'Mostlinkedtemplates'       => [ 'Viitatuimmat_mallineet' ],
	'Mostrevisions'             => [ 'Muokatuimmat_sivut' ],
	'Movepage'                  => [ 'Siirrä_sivu' ],
	'Mycontributions'           => [ 'Omat_muokkaukset' ],
	'MyLanguage'                => [ 'Oma_kieli' ],
	'Mypage'                    => [ 'Oma_sivu' ],
	'Mytalk'                    => [ 'Oma_keskustelu' ],
	'Myuploads'                 => [ 'Omat_tiedostot' ],
	'Newimages'                 => [ 'Uudet_tiedostot', 'Uudet_kuvat' ],
	'Newpages'                  => [ 'Uudet_sivut' ],
	'NewSection'                => [ 'Uusi_osio' ],
	'PageHistory'               => [ 'Sivun_historia' ],
	'PageInfo'                  => [ 'Sivun_tiedot' ],
	'PageLanguage'              => [ 'Sivun_kieli' ],
	'PasswordPolicies'          => [ 'Salasanakäytännöt' ],
	'PasswordReset'             => [ 'Unohtuneen_salasanan_vaihto' ],
	'PermanentLink'             => [ 'Ikilinkki' ],
	'Preferences'               => [ 'Asetukset' ],
	'Prefixindex'               => [ 'Etuliiteluettelo' ],
	'Protectedpages'            => [ 'Suojatut_sivut' ],
	'Protectedtitles'           => [ 'Suojatut_sivunimet' ],
	'RandomInCategory'          => [ 'Satunnainen_kohde_luokasta' ],
	'Randompage'                => [ 'Satunnainen_sivu' ],
	'Randomredirect'            => [ 'Satunnainen_ohjaus', 'Satunnainen_uudelleenohjaus' ],
	'Randomrootpage'            => [ 'Satunnainen_juurisivu' ],
	'Recentchanges'             => [ 'Tuoreet_muutokset' ],
	'Recentchangeslinked'       => [ 'Linkitetyt_muutokset' ],
	'Redirect'                  => [ 'Ohjaus' ],
	'Renameuser'                => [ 'Käyttäjätunnusten_nimeäminen' ],
	'Revisiondelete'            => [ 'Poista_versio' ],
	'Search'                    => [ 'Haku' ],
	'Shortpages'                => [ 'Lyhyet_sivut' ],
	'Specialpages'              => [ 'Toimintosivut' ],
	'Statistics'                => [ 'Tilastot' ],
	'Tags'                      => [ 'Merkkaukset' ],
	'TrackingCategories'        => [ 'Tarkkailuluokat' ],
	'Unblock'                   => [ 'Poista_esto' ],
	'Uncategorizedcategories'   => [ 'Luokittelemattomat_luokat' ],
	'Uncategorizedimages'       => [ 'Luokittelemattomat_tiedostot' ],
	'Uncategorizedpages'        => [ 'Luokittelemattomat_sivut' ],
	'Uncategorizedtemplates'    => [ 'Luokittelemattomat_mallineet' ],
	'Undelete'                  => [ 'Palauta' ],
	'Unlockdb'                  => [ 'Avaa_tietokanta' ],
	'Unusedcategories'          => [ 'Käyttämättömät_luokat' ],
	'Unusedimages'              => [ 'Käyttämättömät_tiedostot' ],
	'Unusedtemplates'           => [ 'Käyttämättömät_mallineet' ],
	'Unwatchedpages'            => [ 'Tarkkailemattomat_sivut' ],
	'Upload'                    => [ 'Tallenna', 'Lisää_tiedosto' ],
	'Userlogin'                 => [ 'Kirjaudu_sisään' ],
	'Userlogout'                => [ 'Kirjaudu_ulos' ],
	'Userrights'                => [ 'Käyttöoikeudet' ],
	'Version'                   => [ 'Versio' ],
	'Wantedcategories'          => [ 'Halutut_luokat' ],
	'Wantedfiles'               => [ 'Halutut_tiedostot' ],
	'Wantedpages'               => [ 'Halutut_sivut' ],
	'Wantedtemplates'           => [ 'Halutut_mallineet' ],
	'Watchlist'                 => [ 'Tarkkailulista' ],
	'Whatlinkshere'             => [ 'Tänne_viittaavat_sivut' ],
	'Withoutinterwiki'          => [ 'Kielilinkittömät_sivut' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'KANTASIVUNIMI', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'KANTASIVUNIMIE', 'BASEPAGENAMEE' ],
	'currentday'                => [ '1', 'KULUVAPÄIVÄ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'KULUVAPÄIVÄ2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'KULUVAPÄIVÄNIMI', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'KULUVAVIIKONPÄIVÄ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'KULUVATUNTI', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'KULUVAKUU', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'KULUVAKUUNIMILYHYT', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'KULUVAKUUNIMI', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'KULUVAKUUNIMIGEN', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'KULUVAAIKA', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'KULUVAAIKALEIMA', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'NYKYINENVERSIO', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'KULUVAVIIKKO', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'KULUVAVUOSI', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'AAKKOSTUS:', 'OLETUSAAKKOSTUS:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'NÄKYVÄOTSIKKO', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'TIEDOSTOPOLKU:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__SISLUETTPAKOTUS__', '__FORCETOC__' ],
	'formatnum'                 => [ '0', 'MUOTOILELUKU', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'KOKOSIVUNIMI', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'KOKOSIVUNIMIE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'TÄYSIOSOITE:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'TÄYSIOSOITEE:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'SUKUPUOLI:', 'GENDER:' ],
	'grammar'                   => [ '0', 'TAIVUTUS:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__PIILOLUOKKA__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'perustaso', 'baseline' ],
	'img_border'                => [ '1', 'reunus', 'border' ],
	'img_bottom'                => [ '1', 'alas', 'alhaalla', 'bottom' ],
	'img_center'                => [ '1', 'keski', 'keskitetty', 'center', 'centre' ],
	'img_framed'                => [ '1', 'kehys', 'kehystetty', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'kehyksetön', 'frameless' ],
	'img_left'                  => [ '1', 'vasen', 'left' ],
	'img_link'                  => [ '1', 'linkki=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'pienoiskuva=$1', 'pienois=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'keskellä', 'middle' ],
	'img_none'                  => [ '1', 'tyhjä', 'none' ],
	'img_page'                  => [ '1', 'sivu=$1', 'sivu_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'oikea', 'right' ],
	'img_sub'                   => [ '1', 'alaindeksi', 'sub' ],
	'img_super'                 => [ '1', 'yläindeksi', 'super', 'sup' ],
	'img_thumbnail'             => [ '1', 'pienoiskuva', 'pienois', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'ylös', 'ylhäällä', 'top' ],
	'img_upright'               => [ '1', 'pysty', 'pysty=$1', 'pysty_$1', 'yläoikea', 'yläoikea=$1', 'yläoikea_$1', 'upright', 'upright=$1', 'upright $1' ],
	'language'                  => [ '0', '#KIELI:', '#LANGUAGE:' ],
	'localday'                  => [ '1', 'PAIKALLINENPÄIVÄ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'PAIKALLINENPÄIVÄ2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'PAIKALLINENPÄIVÄNIMI', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'PAIKALLINENVIIKONPÄIVÄ', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'PAIKALLINENTUNTI', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'PAIKALLINENKUU', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthabbrev'          => [ '1', 'PAIKALLINENKUUNIMILYHYT', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'PAIKALLINENKUUNIMI', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'PAIKALLINENKUUNIMIGEN', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'PAIKALLINENAIKA', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'PAIKALLINENAIKALEIMA', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'PAIKALLINENOSOITE:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'PAIKALLINENOSOITEE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'PAIKALLINENVIIKKO', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'PAIKALLINENVUOSI', 'LOCALYEAR' ],
	'namespace'                 => [ '1', 'NIMIAVARUUS', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NIMIAVARUUSE', 'NAMESPACEE' ],
	'noeditsection'             => [ '0', '__EIOSIOMUOKKAUSTA__', '__NOEDITSECTION__' ],
	'noindex'                   => [ '1', '__HAKUKONEKIELTO__', '__NOINDEX__' ],
	'notoc'                     => [ '0', '__EISISLUETT__', '__NOTOC__' ],
	'ns'                        => [ '0', 'NA:', 'NS:' ],
	'numberofadmins'            => [ '1', 'YLLÄPITÄJÄMÄÄRÄ', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'ARTIKKELIMÄÄRÄ', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'MUOKKAUSMÄÄRÄ', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'TIEDOSTOMÄÄRÄ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'SIVUMÄÄRÄ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'KÄYTTÄJÄMÄÄRÄ', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'SIVUNIMI', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIVUNIMIE', 'PAGENAMEE' ],
	'pagesize'                  => [ '1', 'SIVUKOKO', 'PAGESIZE' ],
	'plural'                    => [ '0', 'MONIKKO:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'SUOJAUSTASO', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#OHJAUS', '#UUDELLEENOHJAUS', '#REDIRECT' ],
	'revisionday'               => [ '1', 'VERSIOPÄIVÄ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'VERSIOPÄIVÄ2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'VERSIOID', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'VERSIOKUUKAUSI', 'REVISIONMONTH' ],
	'revisiontimestamp'         => [ '1', 'VERSIOAIKALEIMA', 'REVISIONTIMESTAMP' ],
	'revisionyear'              => [ '1', 'VERSIOVUOSI', 'REVISIONYEAR' ],
	'scriptpath'                => [ '0', 'SKRIPTIPOLKU', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'PALVELIN', 'SERVER' ],
	'servername'                => [ '0', 'PALVELINNIMI', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'SIVUSTONIMI', 'SITENAME' ],
	'subjectpagename'           => [ '1', 'AIHESIVUNIMI', 'ARTIKKELISIVUNIMI', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'AIHESIVUNIMIE', 'ARTIKKELISIVUNIMIE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'AIHEAVARUUS', 'ARTIKKELIAVARUUS', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'AIHEAVARUUSE', 'ARTIKKELIAVARUUSE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'ALASIVUNIMI', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ALASIVUNIMIE', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'VASTINE:', 'SUBST:' ],
	'talkpagename'              => [ '1', 'KESKUSTELUSIVUNIMI', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'KESKUSTELUSIVUNIMIE', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'KESKUSTELUAVARUUS', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'KESKUSTELUAVARUUSE', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__SISÄLLYSLUETTELO__', '__TOC__' ],
];

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];

$datePreferences = [
	'default',
	'fi normal',
	'fi seconds',
	'fi numeric',
	'ISO 8601',
];

$defaultDateFormat = 'fi normal';

$dateFormats = [
	'fi normal time' => 'H.i',
	'fi normal date' => 'j. F"ta" Y',
	'fi normal both' => 'j. F"ta" Y "kello" H.i',

	'fi seconds time' => 'H:i:s',
	'fi seconds date' => 'j. F"ta" Y',
	'fi seconds both' => 'j. F"ta" Y "kello" H:i:s',

	'fi numeric time' => 'H.i',
	'fi numeric date' => 'j.n.Y',
	'fi numeric both' => 'j.n.Y "kello" H.i',
];

$datePreferenceMigrationMap = [
	'default',
	'fi normal',
	'fi seconds',
	'fi numeric',
];

$bookstoreList = [
	'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
	'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
	'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
	'Tampereen seudun kirjastot'    => 'http://kirjasto.tampere.fi/Piki?formid=fullt&typ0=6&dat0=$1'
];

$linkTrail = '/^([a-zäö]+)(.*)$/sDu';
