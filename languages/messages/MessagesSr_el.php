<?php
/** Serbian (Latin script) (srpski (latinica))
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 *
 * @author FriedrickMILBarbarossa
 * @author Geitost
 * @author Kaganer
 * @author Liangent
 * @author Meno25
 * @author Michaello
 * @author Milicevic01
 * @author Rancher
 * @author Red Baron
 * @author Reedy
 * @author Slaven Kosanovic
 * @author TheStefan12345
 * @author Жељко Тодоровић
 * @author Михајло Анђелковић
 * @author לערי ריינהארט
 */

$fallback = 'sr-latn, sr';

$namespaceNames = [
	NS_MEDIA            => 'Medij',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	NS_PROJECT_TALK     => 'Razgovor_o_$1',
	NS_FILE             => 'Datoteka',
	NS_FILE_TALK        => 'Razgovor_o_datoteci',
	NS_MEDIAWIKI        => 'Medijaviki',
	NS_MEDIAWIKI_TALK   => 'Razgovor_o_Medijavikiju',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
];

# Aliases to cyrillic namespaces
$namespaceAliases = [
	"Медија"                  => NS_MEDIA,
	"Посебно"                 => NS_SPECIAL,
	"Разговор"                => NS_TALK,
	"Корисник"                => NS_USER,
	"Разговор_са_корисником"  => NS_USER_TALK,
	"Разговор_о_$1"           => NS_PROJECT_TALK,
	"Слика"                   => NS_FILE,
	"Разговор_о_слици"        => NS_FILE_TALK,
	"МедијаВики"              => NS_MEDIAWIKI,
	"Разговор_о_МедијаВикију" => NS_MEDIAWIKI_TALK,
	'Шаблон'                  => NS_TEMPLATE,
	'Разговор_о_шаблону'      => NS_TEMPLATE_TALK,
	'Помоћ'                   => NS_HELP,
	'Разговор_о_помоћи'       => NS_HELP_TALK,
	'Категорија'              => NS_CATEGORY,
	'Разговор_о_категорији'   => NS_CATEGORY_TALK,

	'Medija'                  => NS_MEDIA,
	'Slika'                   => NS_FILE,
	'Razgovor_o_slici'        => NS_FILE_TALK,
];

$datePreferenceMigrationMap = [
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Aktivni_korisnici', 'AktivniKorisnici' ],
	'Allmessages'               => [ 'Sve_poruke', 'SvePoruke' ],
	'AllMyUploads'              => [ 'Sva_moja_otpremanja', 'SvaMojaOtpremanja', 'SveMojeDatoteke' ],
	'Allpages'                  => [ 'Sve_stranice', 'SveStranice' ],
	'Ancientpages'              => [ 'Najstarije_stranice', 'NajstarijeStranice', 'NajstarijiČlanci' ],
	'ApiHelp'                   => [ 'Pomoć_oko_API-ja', 'API_pomoć' ],
	'ApiSandbox'                => [ 'Pesak_API-ja', 'API_pesak' ],
	'AutoblockList'             => [ 'Spisak_autoblokada', 'SpisakAutoblokova', 'Autoblokade', 'Autoblokovi' ],
	'Badtitle'                  => [ 'Neispravan_naslov', 'Loš_naslov', 'LošNaslov' ],
	'Blankpage'                 => [ 'Prazna_stranica', 'PraznaStranica' ],
	'Block'                     => [ 'Blokiraj', 'BlokirajIP', 'BlokirajKorisnika' ],
	'BlockList'                 => [ 'Spisak_blokada', 'SpisakBlokada', 'SpisakBlokiranih', 'PopisBlokiranih' ],
	'Booksources'               => [ 'Štampani_izvori', 'ŠtampaniIzvori', 'KnjiževniIzvori' ],
	'BotPasswords'              => [ 'Lozinke_botova', 'LozinkeBotova' ],
	'BrokenRedirects'           => [ 'Pokvarena_preusmerenja', 'Neispravna_preusmerenja', 'PokvarenaPreusmerenja' ],
	'Categories'                => [ 'Kategorije' ],
	'ChangeContentModel'        => [ 'Promeni_sadržinski_model', 'PromeniModelSadržaja', 'IzmeniModelSadržaja' ],
	'ChangeCredentials'         => [ 'Promeni_akreditive', 'PromeniAkreditive' ],
	'ChangeEmail'               => [ 'Promeni_imejl', 'PromeniImejl', 'PromeniImejlAdresu' ],
	'ChangePassword'            => [ 'Promeni_lozinku', 'PromeniLozinku' ],
	'ComparePages'              => [ 'Uporedi_stranice', 'UporediStranice' ],
	'Confirmemail'              => [ 'Potvrdi_imejl', 'PotvrdiImejl', 'PotvrdiE-poštu', 'Potvrda_e-pošte' ],
	'Contributions'             => [ 'Doprinosi', 'Prilozi' ],
	'CreateAccount'             => [ 'Otvori_nalog', 'OtvoriNalog' ],
	'Deadendpages'              => [ 'Ćorsokaci', 'StraniceKojeNeVodeNikuda', 'SlepeStranice' ],
	'DeletedContributions'      => [ 'Izbrisani_doprinosi', 'ObrisaniDoprinosi' ],
	'Diff'                      => [ 'Razlike' ],
	'DoubleRedirects'           => [ 'Dvostruka_preusmerenja', 'DvostrukaPreusmerenja' ],
	'EditPage'                  => [ 'Uredi_stranicu', 'UrediStranicu', 'Uredi' ],
	'EditTags'                  => [ 'Uredi_oznake', 'UrediOznake' ],
	'EditWatchlist'             => [ 'Uredi_spisak_nadgledanja', 'UrediSpisakNadgledanja' ],
	'Emailuser'                 => [ 'Pošalji_imejl_korisniku', 'PošaljiImejlKorisniku' ],
	'ExpandTemplates'           => [ 'Proširi_šablone', 'ProširiŠablone' ],
	'Export'                    => [ 'Izvezi' ],
	'Fewestrevisions'           => [ 'Najmanje_izmena', 'NajmanjeIzmena', 'ČlanciSaNajmanjeRevizija' ],
	'FileDuplicateSearch'       => [ 'Pretraga_duplikata_datoteka', 'PretragaDuplikataDatoteka' ],
	'Filepath'                  => [ 'Putanja_do_datoteke', 'Putanja_datoteke', 'PutanjaDatoteke' ],
	'GoToInterwiki'             => [ 'Poseti_međuviki', 'PosetiMeđuviki' ],
	'Import'                    => [ 'Uvezi' ],
	'Invalidateemail'           => [ 'Otkaži_potvrdu_imejla', 'PoništiImejl' ],
	'JavaScriptTest'            => [ 'Testiranje_JavaScript-a', 'Testiranje_JavaScripta', 'TestiranjeJavaskripta' ],
	'LinkAccounts'              => [ 'Poveži_naloge', 'PovežiNaloge' ],
	'LinkSearch'                => [ 'Pretraga_veza', 'PretragaVeza' ],
	'Listadmins'                => [ 'Spisak_administratora', 'SpisakAdministratora', 'PopisAdministratora' ],
	'Listbots'                  => [ 'Spisak_botova', 'SpisakBotova', 'PopisBotova' ],
	'ListDuplicatedFiles'       => [ 'Spisak_dupliranih_datoteka', 'SpisakDuplikata' ],
	'Listfiles'                 => [ 'Spisak_datoteka', 'SpisakDatoteka', 'SpisakSlika' ],
	'Listgrants'                => [ 'Spisak_dozvola', 'SpisakDozvola' ],
	'Listgrouprights'           => [ 'Spisak_prava_korisničkih_grupa', 'Spisak_korisničkih_prava', 'SpisakKorisničkihPrava' ],
	'Listredirects'             => [ 'Spisak_preusmerenja', 'SpisakPreusmerenja' ],
	'Listusers'                 => [ 'Spisak_korisnika', 'SpisakKorisnika', 'Korisnički_spisak', 'KorisničkiSpisak' ],
	'Lockdb'                    => [ 'Zaključaj_bazu_podataka', 'ZaključajBazuPodataka', 'Zaključaj_bazu', 'ZaključajBazu' ],
	'Log'                       => [ 'Dnevnik', 'Izveštaj', 'Izveštaji' ],
	'Lonelypages'               => [ 'Siročići' ],
	'Longpages'                 => [ 'Duge_stranice', 'DugačkeStranice', 'DugačkeStrane' ],
	'MediaStatistics'           => [ 'Statistika_datoteka', 'StatistikeMedija' ],
	'MergeHistory'              => [ 'Objedini_istoriju', 'Spoji_istoriju', 'SpojiIstoriju' ],
	'MIMEsearch'                => [ 'Pretraga_po_MIME-u', 'MIME_pretraga' ],
	'Mostcategories'            => [ 'Najviše_kategorija', 'NajvišeKategorija', 'ČlanciSaNajvišeKategorija' ],
	'Mostimages'                => [ 'Najpovezanije_datoteke', 'NajpovezanijeDatoteke', 'NajpovezanijeSlike' ],
	'Mostinterwikis'            => [ 'Najviše_međuvikija', 'NajvišeMeđuvikija' ],
	'Mostlinked'                => [ 'Najpovezanije_stranice', 'NajpovezanijeStranice', 'NajpovezanijeStrane' ],
	'Mostlinkedcategories'      => [ 'Najpovezanije_kategorije', 'NajpovezanijeKategorije' ],
	'Mostlinkedtemplates'       => [ 'Najpovezaniji_šabloni', 'NajpovezanijiŠabloni' ],
	'Mostrevisions'             => [ 'Najviše_izmena', 'NajvišeIzmena', 'NajvišeRevizija', 'ČlanciSaNajvišeRevizija' ],
	'Movepage'                  => [ 'Premesti_stranicu', 'PremestiStranicu', 'Premesti', 'Preusmeri' ],
	'Mycontributions'           => [ 'Moji_doprinosi', 'MojiDoprinosi', 'Moji_prilozi' ],
	'MyLanguage'                => [ 'Moj_jezik', 'MojJezik' ],
	'Mypage'                    => [ 'Moja_stranica', 'MojaStranica' ],
	'Mytalk'                    => [ 'Moj_razgovor', 'MojRazgovor' ],
	'Myuploads'                 => [ 'Moja_otpremanja', 'MojaOtpremanja', 'Moja_slanja' ],
	'Newimages'                 => [ 'Nove_datoteke', 'NoveDatoteke', 'NoviFajlovi', 'NoveSlike' ],
	'Newpages'                  => [ 'Nove_stranice', 'NoveStranice', 'NoveStrane' ],
	'NewSection'                => [ 'Novi_odeljak', 'NoviOdeljak' ],
	'PageData'                  => [ 'Podaci_stranice', 'PodaciStranice' ],
	'PageHistory'               => [ 'Istorija_stranice', 'IstorijaStranice', 'Istorija' ],
	'PageLanguage'              => [ 'Jezik_stranice', 'JezikStranice' ],
	'PagesWithProp'             => [ 'Stranice_sa_svojstvom', 'StraniceSaSvojstvom' ],
	'PasswordPolicies'          => [ 'Pravila_o_lozinkama', 'PravilaZaLozinke' ],
	'PasswordReset'             => [ 'Resetovanje_lozinke', 'ResetovanjeLozinke' ],
	'PermanentLink'             => [ 'Trajna_veza', 'TrajnaVeza', 'Privremena_veza' ],
	'Preferences'               => [ 'Podešavanja', 'Postavke' ],
	'Prefixindex'               => [ 'Stranice_s_prefiksom', 'StraniceSaPrefiksom' ],
	'Protectedpages'            => [ 'Zaštićene_stranice', 'ZaštićeneStranice' ],
	'Protectedtitles'           => [ 'Zaštićeni_naslovi', 'ZaštićeniNaslovi' ],
	'Purge'                     => [ 'Osveži' ],
	'RandomInCategory'          => [ 'Nasumična_stranica_u_kategoriji', 'Slučajna_stranica_u_kategoriji', 'Slučajna_strana_u_kategoriji' ],
	'Randompage'                => [ 'Nasumična_stranica', 'SlučajnaStranica', 'SlučajnaStrana' ],
	'Randomredirect'            => [ 'Nasumično_preusmerenje', 'SlučajnoPreusmerenje' ],
	'Randomrootpage'            => [ 'Nasumična_korenska_stranica', 'SlučajnaOsnovnaStranica', 'SlučajnaOsnovnaStrana' ],
	'Recentchanges'             => [ 'Skorašnje_izmene', 'SkorašnjeIzmene' ],
	'Recentchangeslinked'       => [ 'Povezane_izmene', 'SrodneIzmene' ],
	'Redirect'                  => [ 'Preusmerenje' ],
	'RemoveCredentials'         => [ 'Ukloni_akreditive', 'UkloniAkreditive' ],
	'ResetTokens'               => [ 'Resetuj_tokene', 'ResetujŽetone' ],
	'Revisiondelete'            => [ 'Brisanje_izmene', 'BrisanjeIzmene', 'UklanjanjeIzmene' ],
	'RunJobs'                   => [ 'Izvrši_poslove', 'IzvršiPoslove' ],
	'Search'                    => [ 'Pretraži' ],
	'Shortpages'                => [ 'Kratke_stranice', 'KratkeStranice', 'KratkiČlanci' ],
	'Specialpages'              => [ 'Posebne_stranice', 'PosebneStranice', 'PosebneStrane', 'Specijalne_stranice', 'SpecijalneStrane' ],
	'Statistics'                => [ 'Statistika', 'Statistike' ],
	'Tags'                      => [ 'Oznake' ],
	'TrackingCategories'        => [ 'Kategorije_za_praćenje', 'KategorijeZaPraćenje' ],
	'Unblock'                   => [ 'Deblokiraj' ],
	'Uncategorizedcategories'   => [ 'Nekategorisane_kategorije', 'NekategorisaneKategorije', 'KategorijeBezKategorija' ],
	'Uncategorizedimages'       => [ 'Nekategorisane_datoteke', 'NekategorisaneDatoteke', 'SlikeBezKategorija' ],
	'Uncategorizedpages'        => [ 'Nekategorisane_stranice', 'NekategorisaneStranice', 'Članci_bez_kategorija', 'ČlanciBezKategorija' ],
	'Uncategorizedtemplates'    => [ 'Nekategorisani_šabloni', 'NekategorisaniŠabloni', 'ŠabloniBezKategorija' ],
	'Undelete'                  => [ 'Vrati' ],
	'UnlinkAccounts'            => [ 'Odveži_naloge', 'OdvežiNaloge', 'UkloniPovezivanjeNaloga' ],
	'Unlockdb'                  => [ 'Otključaj_bazu_podataka', 'OtključajBazuPodataka', 'Otključaj_bazu', 'OtključajBazu' ],
	'Unusedcategories'          => [ 'Neupotrebljene_kategorije', 'NeiskorišćeneKategorije' ],
	'Unusedimages'              => [ 'Neupotrebljene_datoteke', 'NeiskorišćeneDatoteke', 'NeiskorišćeneSlike' ],
	'Unusedtemplates'           => [ 'Neupotrebljeni_šabloni', 'NeiskorišćeniŠabloni' ],
	'Unwatchedpages'            => [ 'Nenadgledane_stranice', 'NenadgledaneStranice' ],
	'Upload'                    => [ 'Otpremi', 'Pošalji' ],
	'UploadStash'               => [ 'Skladište' ],
	'Userlogin'                 => [ 'Korisnička_prijava', 'KorisničkaPrijava' ],
	'Userlogout'                => [ 'Korisnička_odjava', 'KorisničkaOdjava' ],
	'Userrights'                => [ 'Korisnička_prava', 'KorisničkaPrava' ],
	'Version'                   => [ 'Verzija', 'Izdanje' ],
	'Wantedcategories'          => [ 'Tražene_kategorije', 'TraženeKategorije' ],
	'Wantedfiles'               => [ 'Tražene_datoteke', 'TraženeDatoteke', 'TraženeSlike' ],
	'Wantedpages'               => [ 'Tražene_stranice', 'TraženeStranice', 'TraženeStrane' ],
	'Wantedtemplates'           => [ 'Traženi_šabloni', 'TraženiŠabloni' ],
	'Watchlist'                 => [ 'Spisak_nadgledanja', 'SpisakNadgledanja' ],
	'Whatlinkshere'             => [ 'Šta_vodi_dovde', 'ŠtaVodiOvde', 'Šta_je_povezano_ovde' ],
	'Withoutinterwiki'          => [ 'Bez_međuvikija', 'BezMeđuvikija' ],
];

$datePreferences = [
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
];

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = [
	/*
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
	 */

	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',
	'hh:mm d.m.yyyy date'       => 'j.n.Y',
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',
	'h:mm d.m.yyyy date'        => 'j.n.Y',
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',

	'hh:mm d. month y. both'    => 'H:i, j. F Y.',
	'hh:mm d month y both'      => 'H:i, j F Y',
	'hh:mm dd.mm.yyyy both'     => 'H:i, d.m.Y',
	'hh:mm d.m.yyyy both'       => 'H:i, j.n.Y',
	'hh:mm d. mon y. both'      => 'H:i, j. M Y.',
	'hh:mm d mon y both'        => 'H:i, j M Y',
	'h:mm d. month y. both'     => 'G:i, j. F Y.',
	'h:mm d month y both'       => 'G:i, j F Y',
	'h:mm dd.mm.yyyy both'      => 'G:i, d.m.Y',
	'h:mm d.m.yyyy both'        => 'G:i, j.n.Y',
	'h:mm d. mon y. both'       => 'G:i, j. M Y.',
	'h:mm d mon y both'         => 'G:i, j M Y',
];

/* NOT USED IN STABLE VERSION */
/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'KODIRANJEVEZE', 'KODIRANJE_VEZE', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'PUTANJAČLANKA', 'PUTANJA_ČLANKA', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'IMEOSNOVE', 'IME_OSNOVE', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'IMENAOSNOVA', 'IMENA_OSNOVA', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'JEZIKSADRŽAJA', 'JEZIK_SADRŽAJA', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'TRENUTNIDAN', 'TEKUĆIDAN', 'TEKUĆI_DAN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'TEKUĆIDAN2', 'TEKUĆI_DAN_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'TRENUTNIDANIME', 'IMETEKUĆEGDANA', 'IME_TEKUĆEG_DANA', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'TRENUTNIDOV', 'TEKUĆIDUN', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'TEKUĆISAT', 'TEKUĆI_SAT', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'TRENUTNIMESEC', 'TRENUTNI_MESEC', 'TEKUĆIMESEC', 'TEKUĆI_MESEC', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'TRENUTNIMESEC1', 'TRENUTNI_MESEC1', 'TEKUĆIMESEC1', 'TEKUĆI_MESEC1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'TRENUTNIMESECSKR', 'TEKUĆIMESECSKR', 'TEKUĆI_MESEC_SKR', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'TRENUTNIMESECIME', 'IMETEKUĆEGMESECA', 'IME_TEKUĆEG_MESECA', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'TRENUTNIMESECGEN', 'TEKUĆIMESECGEN', 'TEKUĆI_MESEC_GEN', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'TRENUTNOVREME', 'TEKUĆEVREME', 'TEKUĆE_VREME', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'TEKUĆIOTISAKVREMENA', 'TEKUĆI_OTISAK_VREMENA', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'TEKUĆEIZDANJE', 'TEKUĆE_IZDANJE', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'TRENUTNANEDELJA', 'TRENUTNA_NEDELJA', 'TEKUĆANEDELJA', 'TEKUĆA_NEDELJA', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'TRENUTNAGODINA', 'TEKUĆAGODINA', 'TEKUĆA_GODINA', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'PODRAZUMEVANOSORTIRANJE:', 'PODRAZUMEVANIKLJUČZASORTIRANJE:', 'PODRAZMEVANOSORTIRANJEKATEGORIJE:', 'DEFAULTSORT:' ],
	'defaultsort_noerror'       => [ '0', 'bez_greške', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', 'bez_zamene', 'noreplace' ],
	'directionmark'             => [ '1', 'SMEROZNAKE', 'SMER   _OZNAKE', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'NAZIVPRIKAZA', 'NAZIV_PRIKAZA', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'PUTANJADATOTEKE', 'PUTANJA_DATOTEKE', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__FORSIRANISADRŽAJ__', '__FORSIRANI_SADRŽAJ__', '__PRIMORANISADRŽAJ__', '__PRIMORANI_SADRŽAJ__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'formatdatuma', 'format_datuma', 'formatdate', 'dateformat' ],
	'fullpagename'              => [ '1', 'PUNOIMESTRANICE', 'PUNOIMESTRANE', 'PUNO_IME_STRANICE', 'PUNO_IME_STRANE', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'PUNAIMENASTRANICA', 'PUNAIMENASTRANA', 'PUNA_IMENA_STRANICA', 'PUNA_IMENA_STRANA', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'PUNURL:', 'CELAADRESA', 'CELA_ADRESA', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'PUNURLE:', 'CELEADRESE', 'CELE_ADRESE', 'FULLURLE:' ],
	'gender'                    => [ '0', 'ROD:', 'LICE:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMATIKA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__SAKRIVENAKAT__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'osnova', 'baseline' ],
	'img_border'                => [ '1', 'ivica', 'border' ],
	'img_bottom'                => [ '1', 'dno', 'bottom' ],
	'img_center'                => [ '1', 'centar', 'c', 'center', 'centre' ],
	'img_framed'                => [ '1', 'okvir', 'ram', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', 'bezokvira', 'bez_okvira', 'bezrama', 'bez_rama', 'frameless' ],
	'img_left'                  => [ '1', 'levo', 'l', 'left' ],
	'img_link'                  => [ '1', 'veza=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'mini=$1', 'umanjeno=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'sredina', 'middle' ],
	'img_none'                  => [ '1', 'bez', 'n', 'none' ],
	'img_page'                  => [ '1', 'stranica=$1', 'strana=$1', 'stranica_$1', 'strana_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'desno', 'd', 'right' ],
	'img_sub'                   => [ '1', 'pod', 'sub' ],
	'img_text_bottom'           => [ '1', 'sredinateksta', 'sredina_teksta', 'text-bottom' ],
	'img_text_top'              => [ '1', 'vrhteksta', 'vrh_teksta', 'text-top' ],
	'img_thumbnail'             => [ '1', 'mini', 'umanjeno', 'thumbnail', 'thumb' ],
	'img_top'                   => [ '1', 'vrh', 'top' ],
	'img_upright'               => [ '1', 'uspravno', 'uspravno=$1', 'uspravno_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1piskel', '$1p', '$1px' ],
	'index'                     => [ '1', '__INDEKS__', '__INDEX__' ],
	'language'                  => [ '0', '#JEZIK:', '#LANGUAGE:' ],
	'lcfirst'                   => [ '0', 'LCPRVI:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'LOKALNIDAN', 'LOKALNI_DAN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALNIDAN2', 'LOKALNI_DAN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'IMELOKALNOGDANA', 'IME_LOKALNOG_DANA', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'LOKALNIDUN', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'LOKALNISAT', 'LOKALNI_SAT', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'LOKALNIMESEC', 'LOKALNI_MESEC', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKALNIMESEC2', 'LOKALNI_MESEC2', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'LOKALNIMESECSKR', 'LOKALNI_MESEC_SKR', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'IMELOKALNOGMESECA', 'IME_LOKALNOG_MESECA', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'LOKALNIMESECGEN', 'LOKALNI_MESEC_GEN', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'LOKALNOVREME', 'LOKALNO_VREME', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'OTISAKVREMENA', 'OTISAK_VREMENA', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'LOKALNAADRESA:', 'LOKALNA_ADRESA:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALNEADRESE:', 'LOKALNE_ADRESE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKALNANEDELJA', 'LOKALNA_NEDELJA', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'LOKALNAGODINA', 'LOKALNA_GODINA', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'POR:', 'MSG:' ],
	'msgnw'                     => [ '0', 'NVPOR:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'IMENSKIPROSTOR', 'IMENSKI_PROSTOR', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'IMENSKIPROSTORI', 'IMENSKI_PROSTORI', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'BROJIMENSKOGPROSTORA', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__NOVAVEZAODELJKA__', '__NOVA_VEZA_ODELJKA__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__BEZCC__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__BEZIZMENA__', '__BEZ_IZMENA__', '__BEZIZMJENA__', '__BEZ_IZMJENA__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__BEZGALERIJE__', '__BEZ_GALERIJE__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__BEZINDEKSA__', '__BEZ_INDEKSA__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__BEZNOVEVEZEODELJKA__', '__BEZ_NOVE_VEZE_ODELJKA__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__BEZKN__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__BEZSADRŽAJA__', '__BEZ_SADRŽAJA__', '__NOTOC__' ],
	'ns'                        => [ '0', 'IP:', 'NS:' ],
	'numberingroup'             => [ '1', 'BROJUGRUPI', 'BROJ_U_GRUPI', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'BROJAKTIVNIHKORISNIKA', 'BROJ_AKTIVNIH_KORISNIKA', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'BROJADMINA', 'BROJ_ADMINA', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'BROJČLANAKA', 'BROJ_ČLANAKA', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'BROJIZMENA', 'BROJ_IZMENA', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'BROJDATOTEKA', 'BROJ_DATOTEKA', 'BROJFAJLOVA', 'BROJ_FAJLOVA', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'BROJSTRANICA', 'BROJ_STRANICA', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'BROJKORISNIKA', 'BROJ_KORISNIKA', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'PORAVNAJULEVO', 'PORAVNAJ_ULEVO', 'PADLEFT' ],
	'padright'                  => [ '0', 'PORAVNAJUDESNO', 'PORAVNAJ_UDESNO', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'IMESTRANICE', 'IME_STRANICE', 'STRANICA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'IMENASTRANICA', 'IMENA_STRANICA', 'STRANICE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'STRANICAUKATEGORIJI', 'STRANAUKATEGORIJI', 'STRANICA_U_KATEGORIJI', 'STRANA_U_KATEGORIJI', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'STRANICAUIMENSKOMPROSTORU', 'STRANAUIMENSKOMPROSTORU', 'STRANICA_U_IMENSKOM_PROSTORU', 'STRANA_U_IMENSKOM_PROSTORU', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'VELIČINASTRANICE', 'VELIČINASTRANE', 'VELIČINA_STRANICE', 'VELIČINA_STRANE', 'PAGESIZE' ],
	'plural'                    => [ '0', 'MNOŽINA:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'NIVOZAŠTITE', 'NIVO_ZAŠTITE', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'ČIST:', 'RAW:' ],
	'redirect'                  => [ '0', '#Preusmeri', '#preusmeri', '#PREUSMERI', '#Preusmjeri', '#preusmjeri', '#PREUSMJERI', '#redirect', '#REDIRECT' ],
	'revisionday'               => [ '1', 'DANIZMENE', 'DAN_IZMENE', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DANIZMENE2', 'DAN_IZMENE2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'IDREVIZIJE', 'ID_REVIZIJE', 'IB_IZMENE', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'MESECIZMENE', 'MESEC_IZMENE', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'MESECIZMENE1', 'MESEC_IZMENE1', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'VREMEIZMENE', 'VREME_IZMENE', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'KORISNIKIZMENE', 'KORISNIK_IZMENE', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'GODINAIZMENE', 'GODINA_IZMENE', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'BEZBEDNAZAMENA', 'BEZBEDNA_ZAMENA', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'SKRIPTA', 'SKRIPT', 'SCRIPTPATH' ],
	'servername'                => [ '0', 'IMESERVERA', 'IME_SERVERA', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'IMESAJTA', 'SITENAME' ],
	'special'                   => [ '0', 'posebno', 'special' ],
	'staticredirect'            => [ '1', '__STATIČKOPREUSMERENJE__', 'STATIČKO_PREUSMERENJE', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'PUTANJASTILA', 'PUTANJA_STILA', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'IMEČLANKA', 'IME_ČLANKA', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'IMENAČLANAKA', 'IMENA_ČLANAKA', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'IMENSKIPROSTORČLANKA', 'IMENSKI_PROSTOR_ČLANKA', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'IMENSKIPROSTORČLANAKA', 'IMENSKI_PROSTOR_ČLANAKA', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'IMEPODSTRANICE', 'IMEPODSTRANE', 'IME_PODSTRANICE', 'IME_PODSTRANE', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'IMENAPODSTRANICA', 'IMENAPODSTRANA', 'IMENA_PODSTRANICA', 'IMENA_PODSTRANA', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'ZAMENI:', 'ZAMENA:', 'SUBST:' ],
	'tag'                       => [ '0', 'oznaka', 'tag' ],
	'talkpagename'              => [ '1', 'IMERAZGOVORA', 'IME_RAZGOVORA', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'IMENARAZGOVORA', 'IMENA_RAZGOVORA', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'RAZGOVOR', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'RAZGOVORI', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__SADRŽAJ__', '__TOC__' ],
	'ucfirst'                   => [ '0', 'UCPRVI:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'KODIRANJEADRESE', 'KODIRANJE_ADRESE', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'PUTANJA', 'PATH' ],
	'url_query'                 => [ '0', 'REDOSLED', 'QUERY' ],
	'url_wiki'                  => [ '0', 'VIKI', 'WIKI' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
