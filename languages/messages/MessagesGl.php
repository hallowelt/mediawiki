<?php
/** Galician (galego)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'pt';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Conversa',
	NS_USER             => 'Usuario',
	NS_USER_TALK        => 'Conversa_usuario',
	NS_PROJECT_TALK     => 'Conversa_$1',
	NS_FILE             => 'Ficheiro',
	NS_FILE_TALK        => 'Conversa_ficheiro',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Conversa_MediaWiki',
	NS_TEMPLATE         => 'Modelo',
	NS_TEMPLATE_TALK    => 'Conversa_modelo',
	NS_HELP             => 'Axuda',
	NS_HELP_TALK        => 'Conversa_axuda',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Conversa_categoría',
];

$namespaceAliases = [
	'Conversa_Usuario' => NS_USER_TALK,
	'Imaxe' => NS_FILE,
	'Conversa_Imaxe' => NS_FILE_TALK,
	'Conversa_Modelo' => NS_TEMPLATE_TALK,
	'Conversa_Axuda' => NS_HELP_TALK,
	'Conversa_Categoría' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Usuario', 'female' => 'Usuaria' ],
	NS_USER_TALK => [ 'male' => 'Conversa_usuario', 'female' => 'Conversa_usuaria' ],
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j \d\e F \d\e Y',
	'dmy both' => 'j \d\e F \d\e Y "ás" H:i',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Usuarios_activos' ],
	'Allmessages'               => [ 'Todas_as_mensaxes' ],
	'AllMyUploads'              => [ 'Todas_as_miñas_subidas', 'Todas_as_miñas_cargas', 'Todos_os_meus_ficheiros' ],
	'Allpages'                  => [ 'Todas_as_páxinas' ],
	'Ancientpages'              => [ 'Páxinas_máis_antigas' ],
	'Badtitle'                  => [ 'Título_incorrecto' ],
	'Blankpage'                 => [ 'Baleirar_a_páxina' ],
	'Block'                     => [ 'Bloquear', 'Bloquear_o_enderezo_IP', 'Bloquear_o_usuario' ],
	'BlockList'                 => [ 'Lista_de_bloqueos', 'Lista_dos_bloqueos_a_enderezos_IP' ],
	'Booksources'               => [ 'Fontes_bibliográficas' ],
	'BrokenRedirects'           => [ 'Redireccións_rotas' ],
	'Categories'                => [ 'Categorías' ],
	'ChangeEmail'               => [ 'Cambiar_o_correo_electrónico', 'Cambiar_correo_electrónico' ],
	'ChangePassword'            => [ 'Cambiar_o_contrasinal', 'Cambiar_contrasinal' ],
	'ComparePages'              => [ 'Comparar_as_páxinas' ],
	'Confirmemail'              => [ 'Confirmar_o_correo_electrónico', 'Confirmar_correo_electrónico' ],
	'Contributions'             => [ 'Contribucións' ],
	'CreateAccount'             => [ 'Crear_unha_conta' ],
	'Deadendpages'              => [ 'Páxinas_mortas' ],
	'DeletedContributions'      => [ 'Contribucións_borradas' ],
	'DoubleRedirects'           => [ 'Redireccións_dobres' ],
	'EditWatchlist'             => [ 'Editar_a_lista_de_vixilancia' ],
	'Emailuser'                 => [ 'Enviar_correo_electrónico', 'Correo_electrónico', 'Enviar_correo_electrónico_usuario', 'Enviar_correo_electrónico_usuaria' ],
	'ExpandTemplates'           => [ 'Expandir_os_modelos' ],
	'Export'                    => [ 'Exportar' ],
	'Fewestrevisions'           => [ 'Páxinas_con_menos_revisións' ],
	'FileDuplicateSearch'       => [ 'Procura_de_ficheiros_duplicados', 'Busca_de_ficheiros_duplicados', 'Busca_de_arquivos_duplicados' ],
	'Filepath'                  => [ 'Ruta_do_ficheiro' ],
	'Import'                    => [ 'Importar' ],
	'Invalidateemail'           => [ 'Invalidar_o_enderezo_de_correo_electrónico', 'Invalidar_correo_electrónico', 'Invalidar_enderezo_de_correo_electrónico', 'Invalidar_enderezo_correo_electrónico' ],
	'JavaScriptTest'            => [ 'Proba_do_JavaScript' ],
	'LinkSearch'                => [ 'Buscar_ligazóns_web' ],
	'Listadmins'                => [ 'Lista_de_administradores' ],
	'Listbots'                  => [ 'Lista_de_bots' ],
	'Listfiles'                 => [ 'Lista_de_imaxes' ],
	'Listgrouprights'           => [ 'Lista_de_dereitos_segundo_o_grupo' ],
	'Listredirects'             => [ 'Lista_de_redireccións' ],
	'Listusers'                 => [ 'Lista_de_usuarios' ],
	'Lockdb'                    => [ 'Pechar_a_base_de_datos' ],
	'Log'                       => [ 'Rexistros' ],
	'Lonelypages'               => [ 'Páxinas_orfas' ],
	'Longpages'                 => [ 'Páxinas_longas' ],
	'MergeHistory'              => [ 'Fusionar_os_historiais', 'Fusionar_historiais' ],
	'MIMEsearch'                => [ 'Procura_MIME' ],
	'Mostcategories'            => [ 'Páxinas_con_máis_categorías' ],
	'Mostimages'                => [ 'Ficheiros_máis_ligados', 'Arquivos_máis_ligados' ],
	'Mostinterwikis'            => [ 'Páxinas_con_máis_interwikis' ],
	'Mostlinked'                => [ 'Páxinas_máis_ligadas' ],
	'Mostlinkedcategories'      => [ 'Categorías_máis_ligadas' ],
	'Mostlinkedtemplates'       => [ 'Modelos_máis_ligados' ],
	'Mostrevisions'             => [ 'Páxinas_con_máis_revisións' ],
	'Movepage'                  => [ 'Mover_a_páxina', 'Mover_páxina', 'Mover_o_artigo', 'Mover_artigo' ],
	'Mycontributions'           => [ 'As_miñas_contribucións', 'Miñas_contribucións' ],
	'Mypage'                    => [ 'A_miña_páxina_de_usuario', 'A_miña_páxina', 'Miña_páxina_de_usuario', 'Miña_páxina' ],
	'Mytalk'                    => [ 'A_miña_conversa', 'Miña_conversa' ],
	'Myuploads'                 => [ 'As_miñas_subidas' ],
	'Newimages'                 => [ 'Imaxes_novas' ],
	'Newpages'                  => [ 'Páxinas_novas' ],
	'PagesWithProp'             => [ 'Páxinas_con_propiedades' ],
	'PasswordReset'             => [ 'Restablecer_o_contrasinal', 'Restablecer_contrasinal' ],
	'PermanentLink'             => [ 'Ligazón_permanente' ],
	'Preferences'               => [ 'Preferencias' ],
	'Prefixindex'               => [ 'Índice_de_prefixos' ],
	'Protectedpages'            => [ 'Páxinas_protexidas' ],
	'Protectedtitles'           => [ 'Títulos_protexidos' ],
	'RandomInCategory'          => [ 'Aleatoria_na_categoría', 'Aleatorio_na_categoría' ],
	'Randompage'                => [ 'Ao_chou', 'Páxina_aleatoria', 'Aleatoria', 'Aleatorio' ],
	'Randomredirect'            => [ 'Redirección_aleatoria' ],
	'Recentchanges'             => [ 'Cambios_recentes' ],
	'Recentchangeslinked'       => [ 'Cambios_relacionados' ],
	'Redirect'                  => [ 'Redirección' ],
	'ResetTokens'               => [ 'Restablecer_os_pases' ],
	'Revisiondelete'            => [ 'Revisións_borradas' ],
	'Search'                    => [ 'Procurar', 'Buscar' ],
	'Shortpages'                => [ 'Páxinas_curtas' ],
	'Specialpages'              => [ 'Páxinas_especiais' ],
	'Statistics'                => [ 'Estatísticas', 'Estadísticas' ],
	'Tags'                      => [ 'Etiquetas' ],
	'Unblock'                   => [ 'Desbloquear' ],
	'Uncategorizedcategories'   => [ 'Categorías_sen_categoría', 'Categorías_non_categorizadas' ],
	'Uncategorizedimages'       => [ 'Imaxes_sen_categoría' ],
	'Uncategorizedpages'        => [ 'Páxinas_sen_categoría' ],
	'Uncategorizedtemplates'    => [ 'Modelos_sen_categoría' ],
	'Undelete'                  => [ 'Restaurar' ],
	'Unlockdb'                  => [ 'Abrir_a_base_de_datos' ],
	'Unusedcategories'          => [ 'Categorías_sen_uso', 'Categorías_non_utilizadas' ],
	'Unusedimages'              => [ 'Ficheiros_sen_uso', 'Imaxes_sen_uso', 'Ficheiros_non_usados', 'Imaxes_non_usadas' ],
	'Unusedtemplates'           => [ 'Modelos_non_usados', 'Modelos_sen_uso' ],
	'Unwatchedpages'            => [ 'Páxinas_sen_vixiar', 'Páxinas_non_vixiadas' ],
	'Upload'                    => [ 'Subir', 'Cargar' ],
	'UploadStash'               => [ 'Ficheiros_agochados', 'Arquivos_agochados', 'Subidas_agochadas' ],
	'Userlogin'                 => [ 'Iniciar_sesión', 'Iniciar_a_sesión', 'Acceder_ao_sistema', 'Acceder_ó_sistema' ],
	'Userlogout'                => [ 'Saír_ao_anonimato' ],
	'Userrights'                => [ 'Dereitos_de_usuario' ],
	'Version'                   => [ 'Versión' ],
	'Wantedcategories'          => [ 'Categorías_requiridas' ],
	'Wantedfiles'               => [ 'Ficheiros_requiridos' ],
	'Wantedpages'               => [ 'Páxinas_requiridas', 'Ligazóns_rotas' ],
	'Wantedtemplates'           => [ 'Modelos_requiridos' ],
	'Watchlist'                 => [ 'Lista_de_vixilancia' ],
	'Whatlinkshere'             => [ 'Páxinas_que_ligan_con_esta', 'O_que_liga_aquí' ],
	'Withoutinterwiki'          => [ 'Sen_interwiki', 'Sen_interwikis' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#REDIRECCIÓN', '#REDIRECIONAMENTO', '#REDIRECT' ],
	'notoc'                     => [ '0', '__SENÍNDICE__', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__SENGALERÍA__', '__SEMGALERIA__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__FORZAROÍNDICE__', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__ÍNDICE__', '__TDC__', '__SUMÁRIO__', '__SUMARIO__', '__TOC__' ],
	'noeditsection'             => [ '0', '__SECCIÓNSNONEDITABLES__', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__NAOEDITARSECAO__', '__SEMEDITARSECAO__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MESACTUAL', 'MESACTUAL2', 'MESATUAL', 'MESATUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MESACTUAL1', 'MESATUAL1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'NOMEDOMESACTUAL', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ],
	'currentmonthabbrev'        => [ '1', 'ABREVIATURADOMESACTUAL', 'MESATUALABREV', 'MESATUALABREVIADO', 'ABREVIATURADOMESATUAL', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'DÍAACTUAL', 'DIAATUAL', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DÍAACTUAL2', 'DIAATUAL2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NOMEDODÍAACTUAL', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'ANOACTUAL', 'ANOATUAL', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'DATAEHORAACTUAIS', 'HORARIOATUAL', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'HORAACTUAL', 'HORAATUAL', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MESLOCAL', 'MESLOCAL2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'MESLOCAL1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ],
	'localmonthabbrev'          => [ '1', 'ABREVIATURADOMESLOCAL', 'MESLOCALABREV', 'MESLOCALABREVIADO', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'DÍALOCAL', 'DIALOCAL', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DÍALOCAL2', 'DIALOCAL2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'NOMEDODÍALOCAL', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ANOLOCAL', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'DATAEHORALOCAIS', 'HORARIOLOCAL', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'HORALOCAL', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'NÚMERODEPÁXINAS', 'NUMERODEPAGINAS', 'NÚMERODEPÁGINAS', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'NÚMERODEARTIGOS', 'NUMERODEARTIGOS', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'NÚMERODEFICHEIROS', 'NUMERODEARQUIVOS', 'NÚMERODEARQUIVOS', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NÚMERODEUSUARIOS', 'NUMERODEUSUARIOS', 'NÚMERODEUSUÁRIOS', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'NÚMERODEUSUARIOSACTIVOS', 'NUMERODEUSUARIOSATIVOS', 'NÚMERODEUSUÁRIOSATIVOS', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'NÚMERODEEDICIÓNS', 'NUMERODEEDICOES', 'NÚMERODEEDIÇÕES', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'NOMEDAPÁXINA', 'NOMEDAPAGINA', 'NOMEDAPÁGINA', 'PAGENAME' ],
	'namespace'                 => [ '1', 'ESPAZODENOMES', 'DOMINIO', 'DOMÍNIO', 'ESPACONOMINAL', 'ESPAÇONOMINAL', 'NAMESPACE' ],
	'namespacenumber'           => [ '1', 'NÚMERODOESPAZODENOMES', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', 'ESPAZODECONVERSA', 'PAGINADEDISCUSSAO', 'PÁGINADEDISCUSSÃO', 'TALKSPACE' ],
	'subjectspace'              => [ '1', 'ESPAZODECONTIDO', 'PAGINADECONTEUDO', 'PAGINADECONTEÚDO', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'fullpagename'              => [ '1', 'NOMECOMPLETODAPÁXINA', 'NOMECOMPLETODAPAGINA', 'NOMECOMPLETODAPÁGINA', 'FULLPAGENAME' ],
	'subpagename'               => [ '1', 'NOMEDASUBPÁXINA', 'NOMEDASUBPAGINA', 'NOMEDASUBPÁGINA', 'SUBPAGENAME' ],
	'rootpagename'              => [ '1', 'NOMEDAPÁXINARAÍZ', 'ROOTPAGENAME' ],
	'basepagename'              => [ '1', 'NOMEDAPÁXINABASE', 'NOMEDAPAGINABASE', 'NOMEDAPÁGINABASE', 'BASEPAGENAME' ],
	'talkpagename'              => [ '1', 'NOMEDAPÁXINADECONVERSA', 'NOMEDAPAGINADEDISCUSSAO', 'NOMEDAPÁGINADEDISCUSSÃO', 'TALKPAGENAME' ],
	'subjectpagename'           => [ '1', 'NOMEDAPÁXINADECONTIDO', 'NOMEDAPAGINADECONTEUDO', 'NOMEDAPÁGINADECONTEÚDO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'img_thumbnail'             => [ '1', 'miniatura', 'miniaturadaimagem', 'miniaturadaimaxe', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'miniatura=$1', 'miniaturadaimaxe=$1', 'miniaturadaimagem=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'dereita', 'direita', 'right' ],
	'img_left'                  => [ '1', 'esquerda', 'left' ],
	'img_none'                  => [ '1', 'ningún', 'nenhum', 'none' ],
	'img_center'                => [ '1', 'centro', 'center', 'centre' ],
	'img_framed'                => [ '1', 'conmarco', 'marco', 'conbordo', 'commoldura', 'comborda', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'senmarco', 'senbordo', 'semmoldura', 'semborda', 'frameless' ],
	'img_page'                  => [ '1', 'páxina=$1', 'páxina_$1', 'página=$1', 'página_$1', 'página $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'arribaádereita', 'arribaádereita=$1', 'arribaádereita_$1', 'superiordireito', 'superiordireito=$1', 'superiordireito_$1', 'superiordireito $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'borda', 'bordo', 'border' ],
	'img_baseline'              => [ '1', 'liñadebase', 'linhadebase', 'baseline' ],
	'img_top'                   => [ '1', 'arriba', 'acima', 'top' ],
	'img_text_top'              => [ '1', 'texto-arriba', 'text-top' ],
	'img_middle'                => [ '1', 'medio', 'meio', 'middle' ],
	'img_bottom'                => [ '1', 'abaixo', 'bottom' ],
	'img_text_bottom'           => [ '1', 'texto-abaixo', 'text-bottom' ],
	'img_link'                  => [ '1', 'ligazón=$1', 'ligação=$1', 'link=$1' ],
	'img_class'                 => [ '1', 'clase=$1', 'class=$1' ],
	'sitename'                  => [ '1', 'NOMEDOSITIO', 'NOMEDOSITE', 'NOMEDOSÍTIO', 'SITENAME' ],
	'localurl'                  => [ '0', 'URLLOCAL:', 'LOCALURL:' ],
	'articlepath'               => [ '0', 'RUTADOARTIGO', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', 'IDDAPÁXINA', 'PAGEID' ],
	'server'                    => [ '0', 'SERVIDOR', 'SERVER' ],
	'servername'                => [ '0', 'NOMEDOSERVIDOR', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'RUTADAESCRITURA', 'CAMINHODOSCRIPT', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'RUTADOESTILO', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'GRAMÁTICA:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'SEXO:', 'GENERO', 'GÊNERO', 'GENDER:' ],
	'currentweek'               => [ '1', 'SEMANAACTUAL', 'SEMANAATUAL', 'CURRENTWEEK' ],
	'localweek'                 => [ '1', 'SEMANALOCAL', 'LOCALWEEK' ],
	'revisionid'                => [ '1', 'IDDAREVISIÓN', 'IDDAREVISAO', 'IDDAREVISÃO', 'REVISIONID' ],
	'revisionday'               => [ '1', 'DÍADAREVISIÓN', 'DIADAREVISAO', 'DIADAREVISÃO', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DÍADAREVISIÓN2', 'DIADAREVISAO2', 'DIADAREVISÃO2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'MESDAREVISIÓN', 'MESDAREVISAO', 'MÊSDAREVISÃO', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'MESDAREVISIÓN1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'ANODAREVISIÓN', 'ANODAREVISAO', 'ANODAREVISÃO', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'DATAEHORADAREVISIÓN', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'USUARIODAREVISIÓN', 'USUARIODAREVISAO', 'USUÁRIODAREVISÃO', 'REVISIONUSER' ],
	'fullurl'                   => [ '0', 'URLCOMPLETO:', 'FULLURL:' ],
	'canonicalurl'              => [ '0', 'URLCANÓNICO:', 'CANONICALURL:' ],
	'lcfirst'                   => [ '0', 'PRIMEIRAMINÚSCULA:', 'PRIMEIRAMINUSCULA:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'PRIMEIRAMAIÚSCULA:', 'PRIMEIRAMAIUSCULA:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'MINÚSCULA:', 'MINUSCULA', 'MINÚSCULA', 'MINUSCULAS', 'MINÚSCULAS', 'LC:' ],
	'uc'                        => [ '0', 'MAIÚSCULA:', 'MAIUSCULA', 'MAIÚSCULA', 'MAIUSCULAS', 'MAIÚSCULAS', 'UC:' ],
	'raw'                       => [ '0', 'ENBRUTO:', 'RAW:' ],
	'displaytitle'              => [ '1', 'AMOSAROTÍTULO', 'MOSTRAROTÍTULO', 'EXIBETITULO', 'EXIBETÍTULO', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__LIGAZÓNDANOVASECCIÓN__', '__LINKDENOVASECAO__', '__LINKDENOVASEÇÃO__', '__LIGACAODENOVASECAO__', '__LIGAÇÃODENOVASEÇÃO__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'VERSIÓNACTUAL', 'REVISAOATUAL', 'REVISÃOATUAL', 'CURRENTVERSION' ],
	'language'                  => [ '0', '#LINGUA:', '#IDIOMA:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'LINGUADOCONTIDO', 'IDIOMADOCONTIDO', 'IDIOMADOCONTEUDO', 'IDIOMADOCONTEÚDO', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'PÁXINASNOESPAZODENOMES:', 'PAGINASNOESPACONOMINAL', 'PÁGINASNOESPAÇONOMINAL', 'PAGINASNODOMINIO', 'PÁGINASNODOMÍNIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'NÚMERODEADMINISTRADORES', 'NUMERODEADMINISTRADORES', 'NUMBEROFADMINS' ],
	'special'                   => [ '0', 'especial', 'special' ],
	'defaultsort'               => [ '1', 'ORDENAR:', 'ORDENACAOPADRAO', 'ORDENAÇÃOPADRÃO', 'ORDEMPADRAO', 'ORDEMPADRÃO', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'tag'                       => [ '0', 'etiqueta', 'tag' ],
	'hiddencat'                 => [ '1', '__CATEGORÍAOCULTA__', '__CATEGORIAOCULTA__', '__CATOCULTA__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'PÁXINASNACATEGORÍA', 'PAXINASNACATEGORIA', 'PAGINASNACATEGORIA', 'PÁGINASNACATEGORIA', 'PAGINASNACAT', 'PÁGINASNACAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'TAMAÑODAPÁXINA', 'TAMAÑODAPAXINA', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEXAR__', '__INDEX__' ],
	'noindex'                   => [ '1', '__NONINDEXAR__', '__NAOINDEXAR__', '__NÃOINDEXAR__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'NÚMEROENGRUPO', 'NUMEROENGRUPO', 'NUMERONOGRUPO', 'NÚMERONOGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__REDIRECCIÓNESTÁTICA__', '__REDIRECCIONESTATICA__', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'NIVELDEPROTECCIÓN', 'NIVELDEPROTECCION', 'NIVELDEPROTECAO', 'NÍVELDEPROTEÇÃO', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'formatodadata', 'formateardata', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', 'RUTA', 'PATH' ],
	'url_query'                 => [ '0', 'PESCUDA', 'BUSCA', 'QUERY' ],
	'pagesincategory_all'       => [ '0', 'todos', 'all' ],
	'pagesincategory_pages'     => [ '0', 'páxinas', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'subcategorías', 'subcats' ],
	'pagesincategory_files'     => [ '0', 'ficheiros', 'files' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
