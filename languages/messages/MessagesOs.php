<?php
/** Ossetic (Ирон)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author Amire80
 * @author Bouron
 * @author HalanTul
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Сæрмагонд',
	NS_TALK             => 'Тæрхон',
	NS_USER             => 'Архайæг',
	NS_USER_TALK        => 'Архайæджы_ныхас',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_тæрхон',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлы_тæрхон',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-йы_тæрхон',
	NS_TEMPLATE         => 'Хуызæг',
	NS_TEMPLATE_TALK    => 'Хуызæджы_тæрхон',
	NS_HELP             => 'Æххуыс',
	NS_HELP_TALK        => 'Æххуысы_тæрхон',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорийы_тæрхон',
];

$namespaceAliases = [
	'Дискусси'                    => NS_TALK,
	'Архайæджы_дискусси'          => NS_USER_TALK,
	'Дискусси_$1'                 => NS_PROJECT_TALK,
	'Ныв'                         => NS_FILE,
	'Нывы_тæрхон'                 => NS_FILE_TALK,
	'Нывы_тыххæй_дискусси'        => NS_FILE_TALK,
	'Дискусси_MediaWiki'          => NS_MEDIAWIKI_TALK,
	'Тæрхон_MediaWiki'            => NS_MEDIAWIKI_TALK,
	'Шаблон'                      => NS_TEMPLATE,
	'Шаблоны_тæрхон'              => NS_TEMPLATE_TALK,
	'Шаблоны_тыххæй_дискусси'     => NS_TEMPLATE_TALK,
	'Æххуысы_тыххæй_дискусси'     => NS_HELP_TALK,
	'Категорийы_тыххæй_дискусси'  => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'АктивонАрхайджытæ' ],
	'Allmessages'               => [ 'ФыстæджытæИууылдæр' ],
	'Allpages'                  => [ 'ФæрстæИууылдæр' ],
	'Ancientpages'              => [ 'ЗæрондФæрстæ' ],
	'Badtitle'                  => [ 'Æвзæрном' ],
	'Blankpage'                 => [ 'АфтидФарс' ],
	'Block'                     => [ 'Блок' ],
	'BlockList'                 => [ 'Блокты_Номхыгъд' ],
	'Booksources'               => [ 'ЧингуытыРавзæрæнтæ' ],
	'BrokenRedirects'           => [ 'ЦъæлРарвыстытæ' ],
	'Categories'                => [ 'Категоритæ' ],
	'ChangeEmail'               => [ 'EmailИвын' ],
	'ChangePassword'            => [ 'ПарольИвын' ],
	'ComparePages'              => [ 'ФæрстæАбарын' ],
	'Confirmemail'              => [ 'EmailБæлвырдКæнын' ],
	'Contributions'             => [ 'Бавæрд' ],
	'CreateAccount'             => [ 'АккаунтСкæнын' ],
	'Deadendpages'              => [ 'ХæдбарФæрстæ' ],
	'DeletedContributions'      => [ 'ХафтБавæрд' ],
	'DoubleRedirects'           => [ 'ДывæрÆрвыстытæ' ],
	'EditWatchlist'             => [ 'ЦæстдардИвын' ],
	'Emailuser'                 => [ 'АрхайæгмæEmail' ],
	'Export'                    => [ 'Экспорт' ],
	'Fewestrevisions'           => [ 'ЦъусдæрФæлтæртæ' ],
	'FileDuplicateSearch'       => [ 'ФайлыДубликатАгурын' ],
	'Filepath'                  => [ 'ФайлмæФæт' ],
	'Import'                    => [ 'Импорт' ],
	'Invalidateemail'           => [ 'EmailРабæлвырдКæнын' ],
	'JavaScriptTest'            => [ 'JavaScriptТест' ],
	'LinkSearch'                => [ 'ÆрвитæнАгурын' ],
	'Listadmins'                => [ 'РадгæстыНомхыгъд' ],
	'Listbots'                  => [ 'БоттыНомхыгъд' ],
	'Listfiles'                 => [ 'НывтыНомхыгъд' ],
	'Listgrouprights'           => [ 'АрхайджытыБартыНомхыгъд' ],
	'Listredirects'             => [ 'ÆрвыстытыНомхыгъд' ],
	'Listusers'                 => [ 'АрхайджытыНомхыгъд' ],
	'Lockdb'                    => [ 'РДСæхгæнын' ],
	'Log'                       => [ 'Логтæ' ],
	'Lonelypages'               => [ 'ИунæгФæрстæ' ],
	'Longpages'                 => [ 'ДаргъФæрстæ' ],
	'MergeHistory'              => [ 'ИсторитæБаиуКæнын' ],
	'MIMEsearch'                => [ 'MIMEАгурын' ],
	'Mostcategories'            => [ 'ФылдæрКатегоритæ' ],
	'Mostimages'                => [ 'ÆппæтыАрхайдФайлтæ' ],
	'Mostinterwikis'            => [ 'ФылдæрИнтервикитæ' ],
	'Mostlinked'                => [ 'ФылдæрБастФæрстæ' ],
	'Mostlinkedcategories'      => [ 'ФылдæрБастКатегоритæ' ],
	'Mostlinkedtemplates'       => [ 'ФылдæрБастХуызæгтæ' ],
	'Mostrevisions'             => [ 'ФылдæрФæлтæртæ' ],
	'Movepage'                  => [ 'ФарсХæссын' ],
	'Mycontributions'           => [ 'МæБавæрд' ],
	'Mypage'                    => [ 'МæФарс' ],
	'Mytalk'                    => [ 'МæНыхас' ],
	'Myuploads'                 => [ 'МæБавгæд' ],
	'Newimages'                 => [ 'НогФайлтæ' ],
	'Newpages'                  => [ 'НогФæрстæ' ],
	'PasswordReset'             => [ 'ПарольНогКæнын' ],
	'PermanentLink'             => [ 'УдгасÆрвитæн' ],
	'Preferences'               => [ 'Уагæвæрдтæ' ],
	'Prefixindex'               => [ 'РазæфтуантыИндекс' ],
	'Protectedpages'            => [ 'ÆхгæдФæрстæ' ],
	'Protectedtitles'           => [ 'ÆхгæдНæмттæ' ],
	'Randompage'                => [ 'ÆрхаугæФарс' ],
	'Randomredirect'            => [ 'ÆрхаугæРарвыст' ],
	'Recentchanges'             => [ 'ФæстагИвдтытæ' ],
	'Recentchangeslinked'       => [ 'БастИвдтытæ' ],
	'Revisiondelete'            => [ 'ИвдХафын' ],
	'Search'                    => [ 'Агурын' ],
	'Shortpages'                => [ 'ЦыбырФæрстæ' ],
	'Specialpages'              => [ 'СæрмагондФæрстæ' ],
	'Statistics'                => [ 'Статистикæ' ],
	'Tags'                      => [ 'Тегтæ' ],
	'Unblock'                   => [ 'РаблокКæнын' ],
	'Uncategorizedcategories'   => [ 'ÆнæКатегориКатегоритæ' ],
	'Uncategorizedimages'       => [ 'ÆнæКатегориФайлтæ' ],
	'Uncategorizedpages'        => [ 'ÆнæКатегориФæрстæ' ],
	'Uncategorizedtemplates'    => [ 'ÆнæКатегориХуызæгтæ' ],
	'Undelete'                  => [ 'Рацаразын' ],
	'Unlockdb'                  => [ 'РДРаблокКæнын' ],
	'Unusedcategories'          => [ 'ÆнæАрхайдКатегоритæ' ],
	'Unusedimages'              => [ 'ÆнæАрхайдФайлтæ' ],
	'Unusedtemplates'           => [ 'ÆнæАрхайдХуызæгтæ' ],
	'Unwatchedpages'            => [ 'ÆнæЦæстдардФæрстæ' ],
	'Upload'                    => [ 'Æвгæнын' ],
	'Userlogin'                 => [ 'Бахизын' ],
	'Userlogout'                => [ 'Рахизын' ],
	'Userrights'                => [ 'АрхайæджыБартæ' ],
	'Version'                   => [ 'Фæлтæр' ],
	'Wantedcategories'          => [ 'ХъæугæКатегоритæ' ],
	'Wantedfiles'               => [ 'ХъæугæФайлтæ' ],
	'Wantedpages'               => [ 'ХъæугæФæрстæ' ],
	'Wantedtemplates'           => [ 'ХъæугæХуызæгтæ' ],
	'Watchlist'                 => [ 'Цæстдард' ],
	'Whatlinkshere'             => [ 'АрдæмЦыÆрвиты' ],
	'Withoutinterwiki'          => [ 'ÆнæИнтервики' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'АБОН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'АБОН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'АБОНЫБОНЫНОМ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'НЫРЫСАХАТ', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'АЦЫМÆЙ', 'АЦЫМÆЙ2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'АЦЫМÆЙ1', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'АЦЫМÆЙЫНОМЦЫБ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_АБР', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'АЦЫМÆЙЫНОМ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'АЦЫМÆЙЫНОМГУЫР', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'НЫРЫРÆСТÆГ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ],
	'currentyear'               => [ '1', 'АЦЫАЗ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__СÆРТИМÆ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ],
	'img_center'                => [ '1', 'астæу', 'центр', 'center', 'centre' ],
	'img_left'                  => [ '1', 'галиу', 'слева', 'left' ],
	'img_manualthumb'           => [ '1', 'къаддæргонд=$1', 'къаддæр=$1', 'мини=$1', 'миниатюра=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'æнæ', 'без', 'none' ],
	'img_right'                 => [ '1', 'рахиз', 'справа', 'right' ],
	'img_thumbnail'             => [ '1', 'мини', 'къаддæргонд', 'къаддæр', 'миниатюра', 'thumb', 'thumbnail' ],
	'noeditsection'             => [ '0', '__ÆНÆХАЙИВЫНÆЙ__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__ÆНÆГАЛЕРЕЙ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__ÆНÆСÆР__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'numberofarticles'          => [ '1', 'УАЦТЫНЫМÆЦ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ],
	'numberofpages'             => [ '1', 'ФÆРСТЫНЫМÆЦ', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ],
	'pagename'                  => [ '1', 'ФАРСЫНОМ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ],
	'redirect'                  => [ '0', '#ÆРВИТÆН', '#ÆРВЫСТ', '#РАРВЫСТ', '#перенаправление', '#перенапр', '#REDIRECT' ],
	'toc'                       => [ '0', '__СÆРТÆ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ],
];

$linkTrail = '/^((?:[a-z]|а|æ|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|“|»)+)(.*)$/sDu';
$fallback8bitEncoding = 'windows-1251';
