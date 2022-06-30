<?php
/** Malagasy (Malagasy)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 *
 * @author Alno
 * @author Hoo
 * @author Jagwar
 * @author The Evil IP address
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'fr';

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'ANARANAFOTOPEJY', 'ANARAMPOTOPEJY', 'NOMBASEDEPAGE', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ANARANAFOTOPEJYE', 'ANARAMPOTOPEJYE', 'NOMBASEDEPAGEX', 'BASEPAGENAMEE' ],
	'currentday'                => [ '1', 'ANDRO', 'JOURACTUEL', 'JOUR1ACTUEL', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ANDRO2', 'JOUR2ACTUEL', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ANARANANDRO', 'ANARANAANDRO', 'NOMJOURACTUEL', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ALAHADY', 'JDSACTUEL', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ORA', 'HEUREACTUELLE', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'VOLANA', 'MOISACTUEL', 'MOIS2ACTUEL', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'VOLANA1', 'MOIS1ACTUEL', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'ANARAMBOLANAFOHY', 'ANARANAVOLANAFOHY', 'ABREVMOISACTUEL', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'ANARAMBOLANA', 'NOMMOISACTUEL', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ANARAMBOLANAGEN', 'ANARANAVOLANA', 'CURRENTMONTHNAME', 'NOMGENMOISACTUEL', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'LERA', 'HORAIREACTUEL', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'HERINANDRO', 'SEMAINEACTUELLE', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'TAONA', 'ANNEEACTUELLE', 'CURRENTYEAR' ],
	'displaytitle'              => [ '1', 'ASEHOLOHATENY', 'AFFICHERTITRE', 'DISPLAYTITLE' ],
	'forcetoc'                  => [ '0', '__TEREONYLAHATRA__', '__FORCERSOMMAIRE__', '__FORCERTDM__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'ANARAMPEJYFENO', 'ANARANAPEJYFENO', 'NOMPAGECOMPLET', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ANARAMPEJYFENOX', 'ANARANAPEJYFENOX', 'NOMPAGECOMPLETX', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLREHETRA:', 'URLCOMPLETE:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLREHETRAX:', 'URLCOMPLETEX:', 'FULLURLE:' ],
	'img_border'                => [ '1', 'sisiny', 'bordure', 'border' ],
	'img_bottom'                => [ '1', 'ambany', 'bas', 'bottom' ],
	'img_center'                => [ '1', 'centré', 'ampivoany', 'anivony', 'center', 'centre' ],
	'img_left'                  => [ '1', 'ankavia', 'gauche', 'left' ],
	'img_middle'                => [ '1', 'anivo', 'milieu', 'middle' ],
	'img_none'                  => [ '1', 'tsymisy', 'néant', 'neant', 'none' ],
	'img_page'                  => [ '1', 'pejy $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'ankavanana', 'droite', 'right' ],
	'img_top'                   => [ '1', 'ambony', 'haut', 'top' ],
	'localday'                  => [ '1', 'ANDROANTOERANA', 'JOURLOCAL', 'JOUR1LOCAL', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ANDROANTOERANA2', 'JOUR2LOCAL', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ANARANANDROANTOERANA', 'NOMJOURLOCAL', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'ALAHADYANTOERANA', 'JDSLOCAL', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'ORAANTOERANA', 'HEURELOCALE', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'VOLANAANTOERANA', 'MOISLOCAL', 'MOIS2LOCAL', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'VOLANAANTOERANA1', 'MOIS1LOCAL', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'ANARAMBOLANAANTOERANAFOHY', 'ABREVMOISLOCAL', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'ANARAMBOLANAANTOERANA', 'NOMMOISLOCAL', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'ANARAMBOLANAANTOERANAGEN', 'NOMGENMOISLOCAL', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'LERAANTOERANA', 'HORAIRELOCAL', 'LOCALTIME' ],
	'localweek'                 => [ '1', 'HERINANDROANTOERANA', 'SEMAINELOCALE', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'TAONAANTOERANA', 'ANNEELOCALE', 'LOCALYEAR' ],
	'namespace'                 => [ '1', 'ANARANTSEHATRA', 'ANARANASEHATRA', 'ESPACENOMMAGE', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ANARANTSEHATRAX', 'ANARANASEHATRAX', 'ESPACENOMMAGEX', 'NAMESPACEE' ],
	'noeditsection'             => [ '0', '__TSYAZOOVAINA__', '__SECTIONNONEDITABLE__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__TSYASIANAGALLERY__', '__AUCUNEGALERIE__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__TSYASIANALAHATRA__', '__AUCUNSOMMAIRE__', '__AUCUNETDM__', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'ISAMPIKAMBANAMANOVA', 'NOMBREUTILISATEURSACTIFS', 'NUMBEROFACTIVEUSERS' ],
	'numberofarticles'          => [ '1', 'ISALAHATSORATRA', 'NOMBREARTICLES', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'ISAFANOVANA', 'NOMBREMODIFS', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'ISARAKITRA', 'NOMBREFICHIERS', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'ISAPEJY', 'NOMBREPAGES', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'ISAMPIKAMBANA', 'NOMBREUTILISATEURS', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'ANARAMPEJY', 'ANARANAPEJY', 'NOMPAGE', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ANARAMPEJYX', 'ANARANAPEJYX', 'NOMPAGEX', 'PAGENAMEE' ],
	'redirect'                  => [ '0', '#FIHODINANA', '#REDIRECTION', '#REDIRECT' ],
	'subjectspace'              => [ '1', 'TOERANALAHATSORATRA', 'ESPACESUJET', 'ESPACEARTICLE', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'TOERANNYLAHATSORATRA', 'ESPACESUJETX', 'ESPACEARTICLEX', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'ANARANAZANAPEJY', 'ANARANJANAPEJY', 'NOMSOUSPAGE', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ANARANJANAPEJYX', 'ANARANAZANAPEJYX', 'NOMSOUSPAGEX', 'SUBPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ANARAMPEJINDRESAKA', 'ANARANAPEJINDRESAKA', 'NOMPAGEDISCUSSION', 'TALKPAGENAME' ],
	'talkspace'                 => [ '1', 'PEJINDRESAKA', 'PEJYRESAKA', 'DINIKA', 'ESPACEDISCUSSION', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'PEJINDRESAKAX', 'PEJYRESAKAX', 'DINIKAX', 'ESPACEDISCUSSIONX', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__LAHATRA__', '__LAHAT__', '__SOMMAIRE__', '__TDM__', '__TOC__' ],
];

$namespaceNames = [
	NS_MEDIA            => 'Rakitra',
	NS_SPECIAL          => 'Manokana',
	NS_TALK             => 'Dinika',
	NS_USER             => 'Mpikambana',
	NS_USER_TALK        => 'Dinika_amin\'ny_mpikambana',
	NS_PROJECT_TALK     => 'Dinika_amin\'ny_$1',
	NS_FILE             => 'Sary',
	NS_FILE_TALK        => 'Dinika_amin\'ny_sary',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dinika_amin\'ny_MediaWiki',
	NS_TEMPLATE         => 'Endrika',
	NS_TEMPLATE_TALK    => 'Dinika_amin\'ny_endrika',
	NS_HELP             => 'Fanoroana',
	NS_HELP_TALK        => 'Dinika_amin\'ny_fanoroana',
	NS_CATEGORY         => 'Sokajy',
	NS_CATEGORY_TALK    => 'Dinika_amin\'ny_sokajy',
];

$namespaceAliases = [
	'Média' => NS_MEDIA,
	'Discuter' => NS_TALK,
	'Utilisateur' => NS_USER,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_$1' => NS_PROJECT_TALK,
	'Discussion_Image' => NS_FILE_TALK,
	'Discussion_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Modèle' => NS_TEMPLATE,
	'Discussion_Modèle' => NS_TEMPLATE_TALK,
	'Aide' => NS_HELP,
	'Discussion_Aide' => NS_HELP_TALK,
	'Fanampiana' => NS_HELP,
	'Dinika_amin\'ny_fanampiana' => NS_HELP_TALK,
	'Catégorie' => NS_CATEGORY,
	'Discussion_Catégorie' => NS_CATEGORY_TALK,
];

// Remove French aliases
$namespaceGenderAliases = [];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Mpikambana_mavitrika', 'MpikambanaMavitrika' ],
	'Allmessages'               => [ 'Hafatra_rehetra', 'HafatraRehetra' ],
	'AllMyUploads'              => [ 'Fanondranana_rehetra', 'FanondrananaRehetra' ],
	'Allpages'                  => [ 'Pejy_rehetra', 'PejyRehetra' ],
	'Ancientpages'              => [ 'Pejy_antitra', 'PejyAntitra' ],
	'Badtitle'                  => [ 'LohatenyDiso', 'Lohateny_diso' ],
	'Blankpage'                 => [ 'Pejy_fotsy', 'PejyFotsy' ],
	'Block'                     => [ 'Hanakana', 'Fanakanana', 'Sakana' ],
	'BlockList'                 => [ 'Lisitry_ny_sakana', 'Lisi-tsakana' ],
	'Booksources'               => [ 'Boky_loharano', 'BokyLoharano', 'BokyLoharanon-torohay' ],
	'BrokenRedirects'           => [ 'Fihodinana_tapaka', 'Fihodinana_vaky', 'FihodinanaTapaka', 'FihodinanaVaky' ],
	'Categories'                => [ 'Sokajy' ],
	'ChangeEmail'               => [ 'HanovaNyMailaka', 'Hanova_ny_mailaka' ],
	'ChangePassword'            => [ 'Hiova_tenimiafina', 'HiovaTenimiafina', 'Fiovantenimiafina' ],
	'ComparePages'              => [ 'Fampitaham-pejy', 'Fampitahampejy', 'HampitahaPejy', 'Hampitaha_pejy' ],
	'Confirmemail'              => [ 'FanamarinanaAdiresyMailaka', 'Fanamarinana_adiresy_mailaka' ],
	'Contributions'             => [ 'Fandraisan\'anjara', 'Fandraisananjara' ],
	'CreateAccount'             => [ 'Hamorona_kaonty', 'HamoronaKaonty' ],
	'Deadendpages'              => [ 'Pejy_tsy_misy_rohy', 'Pejy_tsy_mandrohy' ],
	'DeletedContributions'      => [ 'Fandraisan\'anjara_voafafa', 'FandraisananjaraVoafafa' ],
	'Diff'                      => [ 'Fahasmhf.' ],
	'DoubleRedirects'           => [ 'Fihodinana_miroa' ],
	'EditWatchlist'             => [ 'Hanova_ny_pejy_arahana', 'HanovaPejyArahana' ],
	'Emailuser'                 => [ 'Handefa_mailaka', 'HandefaMailaka' ],
	'ExpandTemplates'           => [ 'Hamelatra_endrika', 'HamelatraEndrika' ],
	'Export'                    => [ 'Hanondrana_pejy', 'HanondranaPejy' ],
	'Fewestrevisions'           => [ 'Pejy_vitsy_mpanova_indrindra' ],
	'FileDuplicateSearch'       => [ 'Fikarohan-drakitra_miroa' ],
	'Filepath'                  => [ 'Lalan-drakitra' ],
	'Import'                    => [ 'Hampidi-pejy' ],
	'Invalidateemail'           => [ 'Hampandiso_ny_mailaka', 'HampandisoMailaka' ],
	'JavaScriptTest'            => [ 'AndranaJavaScript' ],
	'LinkSearch'                => [ 'Fikarohan-drohy' ],
	'Listadmins'                => [ 'Lisitry_ny_mpandrindra', 'LisitraMpandrindra' ],
	'Listbots'                  => [ 'Lisitry_ny_rôbô', 'LisitraRôbô', 'LisitraRobo' ],
	'Listfiles'                 => [ 'Lisitry_ny_rakitra', 'LisitraRakitra' ],
	'Listgrouprights'           => [ 'Lisitry_ny_satam-pikambana' ],
	'Listredirects'             => [ 'Lisitry_ny_fihodinana' ],
	'Listusers'                 => [ 'Lisitran\'ny_mpikambana' ],
	'Lockdb'                    => [ 'Hanidy_ny_database' ],
	'Log'                       => [ 'Laogy' ],
	'Lonelypages'               => [ 'Pejy_manirery' ],
	'Longpages'                 => [ 'Pejy_lavabe' ],
	'MergeHistory'              => [ 'Hampiaraka_ny_tantaram-pejy' ],
	'MIMEsearch'                => [ 'Fikarohana_MIME' ],
	'Mostcategories'            => [ 'Pejy_be_sokajy_indrindra' ],
	'Mostimages'                => [ 'Rakitra_voarohy_indrindra' ],
	'Mostlinked'                => [ 'Pejy_voarohy_indrindra' ],
	'Mostlinkedcategories'      => [ 'Sokajy_voarohy_indrindra' ],
	'Mostlinkedtemplates'       => [ 'Endrika_voarohy_indrindra' ],
	'Mostrevisions'             => [ 'Pejy_be_mpanova_indrindra' ],
	'Movepage'                  => [ 'Hanova_anaram-pejy' ],
	'Mycontributions'           => [ 'Fandraisan\'anjarako' ],
	'Mypage'                    => [ 'Pejiko' ],
	'Mytalk'                    => [ 'Pejin-dresako' ],
	'Myuploads'                 => [ 'Fanondranako' ],
	'Newimages'                 => [ 'Sary_vaovao' ],
	'Newpages'                  => [ 'Pejy_vaovao' ],
	'PageLanguage'              => [ 'Fitenim-pejy', 'Fitenimpejy' ],
	'PagesWithProp'             => [ 'Pejy_misy_tondro', 'PejyMisyTondro' ],
	'PasswordReset'             => [ 'Famerenan-tenimiafina', 'Famerenantenimiafina' ],
	'PermanentLink'             => [ 'Rohy_maharitra', 'RohyMaharitra' ],
	'Preferences'               => [ 'Safidy' ],
	'Prefixindex'               => [ 'Index' ],
	'Protectedpages'            => [ 'Pejy_voaaro' ],
	'Protectedtitles'           => [ 'Lohateny_voaaro' ],
	'RandomInCategory'          => [ 'Pejy_kisendra_anaty_sokajy_iray', 'PejyKisendraAnatySokajy' ],
	'Randompage'                => [ 'Kisendra' ],
	'Randomredirect'            => [ 'Fihodinana_kisendra', 'FihodinanaKisendra' ],
	'Recentchanges'             => [ 'Fiovana_farany', 'FiovanaFarany' ],
	'Recentchangeslinked'       => [ 'Fanarahana_ny_rohy', 'FanarahanaRohy' ],
	'Redirect'                  => [ 'Fihodinana' ],
	'ResetTokens'               => [ 'HamerinaToken' ],
	'Revisiondelete'            => [ 'Versiona_voafafa', 'VersionaVoafafa' ],
	'RunJobs'                   => [ 'Runjob' ],
	'Search'                    => [ 'Fikarohana', 'Karoka' ],
	'Shortpages'                => [ 'Pejy_fohy', 'PejyFohy' ],
	'Specialpages'              => [ 'Pejy_manokana', 'PejyManokana' ],
	'Statistics'                => [ 'Statistika' ],
	'Tags'                      => [ 'Balizy' ],
	'TrackingCategories'        => [ 'Sokajy_fanarahana', 'SokajyFanarahana' ],
	'Unblock'                   => [ 'Hanala_ny_fanakanana' ],
	'Uncategorizedcategories'   => [ 'Sokajy_tsy_misy_sokajy' ],
	'Uncategorizedimages'       => [ 'Sary_tsy_misy_sokajy', 'Rakitra_tsy_misy_sokajy' ],
	'Uncategorizedpages'        => [ 'Pejy_tsy_misy_sokajy' ],
	'Uncategorizedtemplates'    => [ 'Endrika_tsy_misy_sokajy' ],
	'Undelete'                  => [ 'Hamerina' ],
	'Unlockdb'                  => [ 'Hanala_ny_hidin\'ny_database' ],
	'Unusedcategories'          => [ 'Sokajy_tsy_miasa' ],
	'Unusedimages'              => [ 'Rakitra_tsy_miasa', 'Sary_tsy_miasa' ],
	'Unusedtemplates'           => [ 'Endrika_tsy_misy_mpampiasa' ],
	'Unwatchedpages'            => [ 'Pejy_tsy_misy_mpanaraka' ],
	'Upload'                    => [ 'Hanafatra' ],
	'UploadStash'               => [ 'Fanondranana_stash' ],
	'Userlogin'                 => [ 'Fidirana' ],
	'Userlogout'                => [ 'Fialàna' ],
	'Userrights'                => [ 'Fahefana' ],
	'Version'                   => [ 'Santiôna' ],
	'Wantedcategories'          => [ 'Sokajy_tadiavina' ],
	'Wantedfiles'               => [ 'Rakitra_tadiavina' ],
	'Wantedpages'               => [ 'Pejy_tadiavina' ],
	'Wantedtemplates'           => [ 'Endrika_tadiavina' ],
	'Watchlist'                 => [ 'Lisitry_ny_pejy_arahana' ],
	'Whatlinkshere'             => [ 'Pejy_mirohy' ],
	'Withoutinterwiki'          => [ 'Tsy_misy_interwiki' ],
];
