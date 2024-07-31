<?php
/** Quechua (Runa Simi)
 *
 * @file
 * @ingroup Languages
 *
 * @author AlimanRuna
 * @author Diego Grez
 * @author Kaganer
 * @author Omnipaedista
 * @author Reedy
 * @author The Evil IP address
 * @author לערי ריינהארט
 */

$fallback = 'qug, es';

$namespaceNames = [
	NS_MEDIA            => 'Midya',
	NS_SPECIAL          => 'Sapaq',
	NS_TALK             => 'Rimanakuy',
	NS_USER             => 'Ruraq',
	NS_USER_TALK        => 'Ruraq_rimanakuy',
	NS_PROJECT_TALK     => '$1_rimanakuy',
	NS_FILE             => 'Rikcha',
	NS_FILE_TALK        => 'Rikcha_rimanakuy',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_rimanakuy',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Plantilla_rimanakuy',
	NS_HELP             => 'Yanapa',
	NS_HELP_TALK        => 'Yanapa_rimanakuy',
	NS_CATEGORY         => 'Katiguriya',
	NS_CATEGORY_TALK    => 'Katiguriya_rimanakuy',
];

// Remove Spanish gender aliases (T39090)
$namespaceGenderAliases = [];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'KuchiRuraqkuna' ],
	'Allmessages'               => [ 'TukuyWillaykuna' ],
	'AllMyUploads'              => [ 'TukuyChurkusqaykuna' ],
	'Allpages'                  => [ 'TukuyPanqakuna' ],
	'Ancientpages'              => [ 'MawkaPanqa' ],
	'Badtitle'                  => [ 'ManaAllinSuti' ],
	'Blankpage'                 => [ 'PanqataChusaqchay' ],
	'Block'                     => [ 'Harkay', 'RuraqtaHarkay', 'IPHarkay' ],
	'BlockList'                 => [ 'HarkasqaRuraq', 'HarkasqaIP', 'HarkasqaRuraqkuna' ],
	'Booksources'               => [ 'PukyuLiwru' ],
	'BrokenRedirects'           => [ 'PantaPusapuna', 'PitisqaPusapuna' ],
	'Categories'                => [ 'Katiguriyakuna' ],
	'ChangeEmail'               => [ 'EChaskitaHukchay' ],
	'ChangePassword'            => [ 'YaykunaRimataKutichiy', 'YaykunaRimataHukchay' ],
	'ComparePages'              => [ 'PanqakunataWakinchay' ],
	'Confirmemail'              => [ 'EChaskitaTakyachiy' ],
	'Contributions'             => [ 'Rurasqakuna', 'Llamkapusqakuna' ],
	'CreateAccount'             => [ 'RakiqunaKamariy' ],
	'Deadendpages'              => [ 'Lluqsinannaq' ],
	'DeletedContributions'      => [ 'QullusqaRurasqa', 'QullusqaLlamkapusqa' ],
	'Diff'                      => [ 'WakinKay' ],
	'DoubleRedirects'           => [ 'IskayllaPusapuna' ],
	'EditWatchlist'             => [ 'WatiqasqakunataLlamkapuy' ],
	'Emailuser'                 => [ 'EChaskitaManakuy' ],
	'ExpandTemplates'           => [ 'PlantillakunataHuknachay' ],
	'Export'                    => [ 'HawamanQuy' ],
	'Fewestrevisions'           => [ 'AsllaLlamkapusqa', 'AsllaKutiLlamkapusqa' ],
	'FileDuplicateSearch'       => [ 'IskayllaWillaniqitaMaskay' ],
	'Filepath'                  => [ 'WillaniqiNan' ],
	'Import'                    => [ 'HawamantaChaskiy' ],
	'Invalidateemail'           => [ 'EChaskitaManaallinchay' ],
	'LinkSearch'                => [ 'TinkitaMaskay', 'TinkikunataMaskay' ],
	'Listadmins'                => [ 'Kamachiqkuna' ],
	'Listbots'                  => [ 'RuranaAntachakuna' ],
	'Listfiles'                 => [ 'RikchaSutisuyu' ],
	'Listgrouprights'           => [ 'HunuHayni', 'HunupHaynin', 'RuraqkunapHayninkuna' ],
	'Listredirects'             => [ 'Pusapunakuna', 'TukuyPusapuna' ],
	'Listusers'                 => [ 'Ruraqkuna', 'RuraqSutisuyu' ],
	'Lockdb'                    => [ 'WillaniqintintaHarkay' ],
	'Log'                       => [ 'Hallcha', 'Hallchasqa' ],
	'Lonelypages'               => [ 'WakchaPanqa' ],
	'Longpages'                 => [ 'HatunPanqa' ],
	'MergeHistory'              => [ 'WinayKawsaytaHunuy' ],
	'MIMEsearch'                => [ 'MIMEkamaMaskay' ],
	'Mostcategories'            => [ 'Katiguriyasapa' ],
	'Mostimages'                => [ 'TinkimuqsapaRikcha' ],
	'Mostlinked'                => [ 'Tinkimuqsapa', 'LliwmantaAswanTinkimuqniyuq' ],
	'Mostlinkedcategories'      => [ 'TinkimuqsapaKatiguriya', 'AnchaLlamkachisqa', 'AchkaKutiLlamkachisqa' ],
	'Mostlinkedtemplates'       => [ 'TinkimuqsapaPlantilla' ],
	'Mostrevisions'             => [ 'AnchaLlamkapusqa', 'AchkaKutiLlamkapusqa' ],
	'Movepage'                  => [ 'PanqataAstay' ],
	'Mycontributions'           => [ 'Rurasqaykuna', 'Llamkapusqaykuna' ],
	'MyLanguage'                => [ 'Rimayniy' ],
	'Mypage'                    => [ 'Panqay', 'NuqapPanqay' ],
	'Mytalk'                    => [ 'Rimachinay', 'RimanakuyPanqay', 'NuqapRimachinay', 'NuqapRimanakuyPanqay' ],
	'Myuploads'                 => [ 'Churkusqaykuna' ],
	'Newimages'                 => [ 'MusuqRikcha', 'MusuqRikchakuna' ],
	'Newpages'                  => [ 'MusuqPanqa' ],
	'PasswordReset'             => [ 'YaykunaRimaKutichina' ],
	'PermanentLink'             => [ 'KakuqTinki' ],
	'Preferences'               => [ 'Allinkachina', 'Allinkachinakuna' ],
	'Prefixindex'               => [ 'QallarinaKaskaSutisuyu' ],
	'Protectedpages'            => [ 'AmachasqaPanqa' ],
	'Protectedtitles'           => [ 'AmachasqaSuti' ],
	'RandomInCategory'          => [ 'KatiguriyapiKikinmanta' ],
	'Randompage'                => [ 'MayninpiPanqa' ],
	'Randomredirect'            => [ 'KikinmantaPusapuna' ],
	'Recentchanges'             => [ 'NaqhaHukchasqa' ],
	'Recentchangeslinked'       => [ 'HukchasqaTinkimuq' ],
	'Redirect'                  => [ 'Pusapuna', 'Pusapuy' ],
	'ResetTokens'               => [ 'LlawikunataKutichiy' ],
	'Revisiondelete'            => [ 'MusuqchasqaQulluy' ],
	'Search'                    => [ 'Maskay' ],
	'Shortpages'                => [ 'UchuyPanqa' ],
	'Specialpages'              => [ 'SapaqPanqa', 'SapaqPanqakuna' ],
	'Statistics'                => [ 'Ranuy', 'Kanchachani' ],
	'Tags'                      => [ 'Unanchachakuna' ],
	'Unblock'                   => [ 'AmanaHarkaychu' ],
	'Uncategorizedcategories'   => [ 'KatiguriyannaqKatiguriya' ],
	'Uncategorizedimages'       => [ 'KatiguriyannaqRikcha' ],
	'Uncategorizedpages'        => [ 'KatiguriyannaqPanqa' ],
	'Uncategorizedtemplates'    => [ 'KatiguriyannaqPlantilla' ],
	'Undelete'                  => [ 'QullusqataPaqarichiy' ],
	'Unlockdb'                  => [ 'WillaniqintintaPaskay' ],
	'Unusedcategories'          => [ 'ChusaqKatiguriya', 'ManaLlamkachisqaKatiguriya' ],
	'Unusedimages'              => [ 'ManaLlamkachisqaRikcha' ],
	'Unusedtemplates'           => [ 'ManaLlamkachisqaPlantilla' ],
	'Unwatchedpages'            => [ 'ManaWatiqasqa' ],
	'Upload'                    => [ 'Churkuy' ],
	'UploadStash'               => [ 'PakasqaWillaniqikuna' ],
	'Userlogin'                 => [ 'RuraqYaykuy' ],
	'Userlogout'                => [ 'RuraqLluqsiy' ],
	'Userrights'                => [ 'RuraqpaHaynin' ],
	'Version'                   => [ 'Musuqchasqa' ],
	'Wantedcategories'          => [ 'MunasqaKatiguriya', 'MunakusqaKatiguriya', 'MuchusqaKatiguriya' ],
	'Wantedfiles'               => [ 'MunasqaWillaniqi', 'MunakusqaWillaniqi', 'MuchusqaWillaniqi' ],
	'Wantedpages'               => [ 'MunasqaPanqa', 'MunakusqaPanqa', 'MuchusqaPanqa' ],
	'Wantedtemplates'           => [ 'MunasqaPlantilla', 'MunakusqaPlantilla', 'MuchusqaPlantilla' ],
	'Watchlist'                 => [ 'Watiqasqa', 'Watiqasqakuna' ],
	'Whatlinkshere'             => [ 'KaymanTinkimuq' ],
	'Withoutinterwiki'          => [ 'Interwikinnaq', 'Wikipurannaq' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'WATANALLAWICHAY', 'ANCHORENCODE' ],
	'basepagename'              => [ '1', 'TIKSIPANQASUTI', 'NOMBREDEPAGINABASE', 'NOMBREDEPÁGINABASE', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'TIKSIPANQASUTIE', 'NOMBREDEPAGINABASEC', 'NOMBREDEPÁGINABASEC', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'SAMIQRIMAY', 'IDIOMADELCONTENIDO', 'IDIOMADELCONT', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'KUNANPUNCHAW', 'DÍAACTUAL', 'DIAACTUAL', 'DÍA_ACTUAL', 'DIA_ACTUAL', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'KUNANPUNCHAW2', 'DÍAACTUAL2', 'DIAACTUAL2', 'DÍA_ACTUAL2', 'DIA_ACTUAL2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'KUNANPUNCHAWSUTI', 'NOMBREDÍAACTUAL', 'NOMBREDIAACTUAL', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'KUNANSIMANAPUNCHAW', 'DDSACTUAL', 'DIADESEMANAACTUAL', 'DÍADESEMANAACTUAL', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'KUNANURA', 'HORAACTUAL', 'HORA_ACTUAL', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'KUNANKILLA', 'MESACTUAL', 'MES_ACTUAL', 'MESACTUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'KUNANKILLAPISI', 'ABREVIACIONNOMBREMESACTUAL', 'ABREVIACIÓNNOMBREMESACTUAL', 'MESACTUALABREVIADO', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'KUNANKILLASUTI', 'NOMBREMESACTUAL', 'NOMBRE_MES_ACTUAL', 'MESACTUALCOMPLETO', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'KUNANKILLASUTIP', 'GENERADORNOMBREMESACTUAL', 'MESACTUALGENITIVO', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'KUNANPACHA', 'HORAACTUAL', 'HORA_ACTUAL', 'HORA_MINUTOS_ACTUAL', 'HORAMINUTOSACTUAL', 'TIEMPOACTUAL', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'KUNANPACHAQILLPA', 'MARCADEHORAACTUAL', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'KUNANMUSUQCHASQA', 'REVISIÓNACTUAL', 'VERSIONACTUAL', 'VERSIÓNACTUAL', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'KUNANSIMANA', 'SEMANAACTUAL', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'KUNANWATA', 'AÑOACTUAL', 'AÑO_ACTUAL', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'ALLINCHAY:', 'SIQINCHAY:', 'ORDENAR:', 'ORDENPREDETERMINADO:', 'CLAVEDEORDENPREDETERMINADO:', 'ORDENDECATEGORIAPREDETERMINADO:', 'ORDENDECATEGORÍAPREDETERMINADO:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'directionmark'             => [ '1', 'PURIRIYSANANCHA', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'SUTITARIKUCHIY', 'MOSTRARTÍTULO', 'MOSTRARTITULO', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'WILLAÑIQIÑAN', 'WILLANIQINAN', 'RUTAARCHIVO', 'RUTARCHIVO', 'RUTAARCHIVO:', 'RUTARCHIVO:', 'RUTADEARCHIVO:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__YUYARINATAATIPACHIY__', '__FORZARTDC__', '__FORZARTOC__', '__FORZAR_TDC__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'punchawrikchakuy', 'formatodefecha', 'formatearfecha', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'YUPAYRIKCHAKUY', 'FORMATONÚMERO', 'FORMATONUMERO', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'HUNTAPANQASUTI', 'NOMBREDEPÁGINACOMPLETA', 'NOMBREDEPAGINACOMPLETA', 'NOMBREDEPÁGINAENTERA', 'NOMBREDEPAGINAENTERA', 'NOMBRECOMPLETODEPÁGINA', 'NOMBRECOMPLETODEPAGINA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'HUNTAPANQASUTIE', 'NOMBRECOMPLETODEPAGINAC', 'NOMBRECOMPLETODEPÁGINAC', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'HUNTAURL:', 'URLCOMPLETA:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'HUNTAURLE:', 'URLCOMPLETAC:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'QHARIWARMI:', 'QARIWARMI:', 'GÉNERO:', 'GENERO:', 'GENDER:' ],
	'grammar'                   => [ '0', 'SIMIKAMACHIY:', 'GRAMATICA:', 'GRAMÁTICA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__PAKASQAKATIGURIYA__', '__CATEGORÍAOCULTA__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'wak=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', 'tiksisiqi', 'baseline' ],
	'img_border'                => [ '1', 'saywa', 'borde', 'border' ],
	'img_bottom'                => [ '1', 'sikipi', 'bottom' ],
	'img_center'                => [ '1', 'chawpi', 'centro', 'centrado', 'centrada', 'centrar', 'center', 'centre' ],
	'img_framed'                => [ '1', 'inchuyuq', 'inchu', 'marco', 'enmarcado', 'enmarcada', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'inchunnaq', 'sinmarco', 'sin_embarcar', 'sinenmarcar', 'sin_enmarcar', 'frameless' ],
	'img_left'                  => [ '1', 'lluqi', 'ichuq', 'izquierda', 'izda', 'izq', 'left' ],
	'img_link'                  => [ '1', 'tinki=$1', 'vínculo=$1', 'vinculo=$1', 'enlace=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'rikchacha=$1', 'miniaturadeimagen=$1', 'miniatura=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'ukhupi', 'middle' ],
	'img_none'                  => [ '1', 'manaima', 'mana', 'ninguna', 'nada', 'no', 'ninguno', 'none' ],
	'img_page'                  => [ '1', 'panqa=$1', 'pagina=$1', 'página=$1', 'pagina_$1', 'página_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'paña', 'alliq', 'derecha', 'dcha', 'der', 'right' ],
	'img_sub'                   => [ '1', 'uran', 'sub' ],
	'img_super'                 => [ '1', 'hanan', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'qillqasiki', 'text-bottom' ],
	'img_text_top'              => [ '1', 'qillqahawa', 'text-top' ],
	'img_thumbnail'             => [ '1', 'rikchacha', 'miniaturadeimagen', 'mini', 'miniatura', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'hawa', 'top' ],
	'img_upright'               => [ '1', 'sayaq', 'sayaq=$1', 'upright', 'upright=$1', 'upright $1' ],
	'index'                     => [ '1', '__UNANCHAY__', '__INDEXAR__', '__INDEX__' ],
	'int'                       => [ '0', 'WILLAY:', 'INT:' ],
	'language'                  => [ '0', '#RIMAY', '#IDIOMA', '#LANGUAGE' ],
	'lc'                        => [ '0', 'UCHUY:', 'MINUS:', 'MINÚS:', 'LC:' ],
	'lcfirst'                   => [ '0', 'UCHUYÑAWPAQ:', 'UCHUYNAWPAQ:', 'PRIMEROMINUS;', 'PRIMEROMINÚS:', 'PRIMEROMINUS:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'KAYPIPUNCHAW', 'DÍALOCAL', 'DIALOCAL', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'KAYPIPUNCHAW2', 'DIALOCAL2', 'DÍALOCAL2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'KAYPIPUNCHAWSUTI', 'NOMBREDIALOCAL', 'NOMBREDÍALOCAL', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'KAYLLASIMANAPUNCHAW', 'DDSLOCAL', 'DIADESEMANALOCAL', 'DÍADESEMANALOCAL', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'KAYPIURA', 'HORALOCAL', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'KAYPIKILLA', 'MESLOCAL', 'MESLOCAL2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthabbrev'          => [ '1', 'KAYPIKILLAPISI', 'ABREVIACIONMESLOCAL', 'MESLOCALABREVIADO', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'KAYPIKILLASUTI', 'NOMBREMESLOCAL', 'MESLOCALCOMPLETO', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'KAYPIKILLASUTIP', 'GENERADORNOMBREMESLOCAL', 'MESLOCALGENITIVO', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'KAYPIPACHA', 'HORALOCAL', 'HORAMINUTOSLOCAL', 'TIEMPOLOCAL', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'KAYLLAPACHAQILLPA', 'MARCADEHORALOCAL', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'KAYLLAURL:', 'URLLOCAL', 'URLLOCAL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'KAYLLAURLE:', 'URLLOCALC:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'KAYLLASIMANA', 'SEMANALOCAL', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'KAYPIWATA', 'AÑOLOCAL', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'WILLA:', 'MSJ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'WILLAMUSUQ:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'SUTIKITI', 'ESPACIODENOMBRE', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'SUTIKITIE', 'ESPACIODENOMBREC', 'NAMESPACEE' ],
	'nocontentconvert'          => [ '0', '__AMASAMIQTAHUKCHAYCHU__', '__NOCONVERTIRCONTENIDO__', '__NOCC___', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__AMARAKITAHUKCHAYCHU__', '__NOEDITARSECCIÓN__', '__NOEDITARSECCION__', '__NO_EDITAR_SECCIÓN__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__RIKCHASUYUNNAQ__', '__NOGALERÍA__', '__NOGALERIA__', '__SIN_GALERÍA__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__AMAUNANCHAYCHU__', '__NOINDEXAR__', '__NOINDEX__' ],
	'notitleconvert'            => [ '0', '__AMASUTITAHUKCHAYCHU__', '__NOCONVERTIRTITULO__', '__NOCONVERTIRTÍTULO__', '__NOCT___', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__YUYARINANNAQ__', '__NOTDC__', '__SIN_TDC__', '__NOTOC__' ],
	'ns'                        => [ '0', 'SKITI:', 'EN:', 'NS:' ],
	'numberingroup'             => [ '1', 'HUÑUPIYUPAY', 'HUNUPIYUPAY', 'NÚMEROENGRUPO', 'NUMEROENGRUPO', 'NUMENGRUPO', 'NÚMENGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'HAYKARURACHKAQ', 'NÚMERODEUSUARIOSACTIVOS', 'NUMERODEUSUARIOSACTIVOS', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'HAYKAKAMACHIQ', 'NÚMEROADMINISITRADORES', 'NÚMEROADMINS', 'NUMEROADMINS', 'NUMEROADMINISTRADORES', 'NUMERODEADMINISTRADORES', 'NUMERODEADMINS', 'NÚMERODEADMINISTRADORES', 'NÚMERODEADMINS', 'NÚMEROADMINIISTRADORES', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'HAYKAQILLQA', 'NÚMERODEARTÍCULOS', 'NUMERODEARTICULOS', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'HAYKALLAMKAPUSQA', 'NÚMERODEEDICIONES', 'NUMERODEEDICIONES', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'HAYKAWILLANIQI', 'NÚMERODEARCHIVOS', 'NUMERODEARCHIVOS', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'HAYKAPANQA', 'NÚMERODEPÁGINAS', 'NUMERODEPAGINAS', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'HAYKARURAQ', 'NÚMERODEUSUARIOS', 'NUMERODEUSUARIOS', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'PADLLUQI', 'PADICHUQ', 'PADLEFT' ],
	'padright'                  => [ '0', 'PADPAÑA', 'PADALLIQ', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'PANQASUTI', 'NOMBREDEPAGINA', 'NOMBREDEPÁGINA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'PANQASUTIE', 'NOMBREDEPAGINAC', 'NOMBREDEPÁGINAC', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'KATIGURIYAPIPANQAKUNA', 'PÁGINASENCATEGORÍA', 'PÁGINASENCAT', 'PAGSENCAT', 'PAGINASENCATEGORIA', 'PAGINASENCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'SUTIKITIPIPANQAKUNA:', 'PÁGINASENESPACIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'PANQACHHIKAN', 'PANQACHIKAN', 'PANQACHIKA', 'TAMAÑOPÁGINA', 'TAMAÑODEPÁGINA', 'TAMAÑOPAGINA', 'TAMAÑODEPAGINA', 'PAGESIZE' ],
	'plural'                    => [ '0', 'ACHKA:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'HAYKAAMACHAY', 'IMASINCHIAMACHAY', 'NIVELDEPROTECCIÓN', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'CHAWA:', 'SINFORMATO', 'SINPUNTOS', 'SINFORMATO:', 'SINPUNTOS:', 'RAW:' ],
	'redirect'                  => [ '0', '#PUSAPUNA', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ],
	'revisionday'               => [ '1', 'MUSUQCHASQAPUNCHAW', 'DIADEREVISION', 'DIAREVISION', 'DÍADEREVISIÓN', 'DÍAREVISIÓN', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'MUSUQCHASQAPUNCHAW2', 'DIADEREVISION2', 'DIAREVISION2', 'DÍADEREVISIÓN2', 'DÍAREVISIÓN2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'MUSUQCHASQAID', 'IDDEREVISION', 'IDREVISION', 'IDDEREVISIÓN', 'IDREVISIÓN', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'MUSUQCHASQAKILLA', 'MESDEREVISION', 'MESDEREVISIÓN', 'MESREVISION', 'MESREVISIÓN', 'REVISIONMONTH' ],
	'revisiontimestamp'         => [ '1', 'MUSUQCHASQAPACHAQILLPA', 'MARCADEHORADEREVISION', 'MARCADEHORADEREVISIÓN', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'MUSUQCHASQARURAQ', 'USUARIODEREVISION', 'USUARIODEREVISIÓN', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'MUSUQCHASQAWATA', 'AÑODEREVISION', 'AÑODEREVISIÓN', 'AÑOREVISION', 'AÑOREVISIÓN', 'REVISIONYEAR' ],
	'scriptpath'                => [ '0', 'QILLQAÑAN', 'QILLQANAN', 'RUTASCRIPT', 'RUTADESCRIPT', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'SIRWIQ', 'SERVIDOR', 'SERVER' ],
	'servername'                => [ '0', 'SIRWIQSUTI', 'NOMBRESERVIDOR', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'TIYAYSUTI', 'NOMBREDESITIO', 'NOMBREDELSITIO', 'SITENAME' ],
	'special'                   => [ '0', 'sapaq', 'especial', 'special' ],
	'staticredirect'            => [ '1', '__TIYAQLLAPUSAPUNA__', '__REDIRECCIONESTATICA__', '__REDIRECCIÓNESTÁTICA__', '__STATICREDIRECT__' ],
	'subjectpagename'           => [ '1', 'QILLQAPANQASUTI', 'NOMBREDEPAGINADETEMA', 'NOMBREDEPÁGINADETEMA', 'NOMBREDEPÁGINADEASUNTO', 'NOMBREDEPAGINADEASUNTO', 'NOMBREDEPAGINADEARTICULO', 'NOMBREDEPÁGINADEARTÍCULO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'QILLQAPANQASUTIE', 'NOMBREDEPAGINADETEMAC', 'NOMBREDEPÁGINADETEMAC', 'NOMBREDEPÁGINADEASUNTOC', 'NOMBREDEPAGINADEASUNTOC', 'NOMBREDEPAGINADEARTICULOC', 'NOMBREDEPÁGINADEARTÍCULOC', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'QILLQAKITI', 'ESPACIODEASUNTO', 'ESPACIODETEMA', 'ESPACIODEARTÍCULO', 'ESPACIODEARTICULO', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'QILLQAKITIE', 'ESPACIODETEMAC', 'ESPACIODEASUNTOC', 'ESPACIODEARTICULOC', 'ESPACIODEARTÍCULOC', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'URINPANQASUTI', 'NOMBREDESUBPAGINA', 'NOMBREDESUBPÁGINA', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'URINPANQASUTIE', 'NOMBREDESUBPAGINAC', 'NOMBREDESUBPÁGINAC', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'WAKCHAY:', 'SUST:', 'FIJAR:', 'SUBST:' ],
	'tag'                       => [ '0', 'unanchacha', 'UNANCHACHA', 'etiqueta', 'ETIQUETA', 'tag' ],
	'talkpagename'              => [ '1', 'RIMANAKUYPANQASUTI', 'NOMBREDEPÁGINADEDISCUSIÓN', 'NOMBREDEPAGINADEDISCUSION', 'NOMBREDEPAGINADISCUSION', 'NOMBREDEPÁGINADISCUSIÓN', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'RIMANAKUYPANQASUTIE', 'NOMBREDEPÁGINADEDISCUSIÓNC', 'NOMBREDEPAGINADEDISCUSIONC', 'NOMBREDEPAGINADISCUSIONC', 'NOMBREDEPÁGINADISCUSIÓNC', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'RIMANAKUYKITI', 'RIMAYKITI', 'ESPACIODEDISCUSION', 'ESPACIODEDISCUSIÓN', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'RIMANAKUYKITIE', 'RIMAYKITIE', 'ESPACIODEDISCUSIONC', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__YUYARINA__', '__TDC__', '__TOC__' ],
	'uc'                        => [ '0', 'HATUN:', 'MAYUS:', 'MAYÚS:', 'UC:' ],
	'ucfirst'                   => [ '0', 'HATUNÑAWPAQ:', 'HATUNNAWPAQ:', 'PRIMEROMAYUS;', 'PRIMEROMAYÚS:', 'PRIMEROMAYUS:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'URLLLAWICHAY', 'URL-LLAWICHAY', 'CODIFICAR', 'CODIFICARURL:', 'URLENCODE:' ],
];
