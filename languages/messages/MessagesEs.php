<?php
/** Spanish (español)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'Medio',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Discusión',
	NS_USER             => 'Usuario',
	NS_USER_TALK        => 'Usuario_discusión',
	NS_PROJECT_TALK     => '$1_discusión',
	NS_FILE             => 'Archivo',
	NS_FILE_TALK        => 'Archivo_discusión',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_discusión',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Plantilla_discusión',
	NS_HELP             => 'Ayuda',
	NS_HELP_TALK        => 'Ayuda_discusión',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Categoría_discusión',
];

$namespaceAliases = [
	'Imagen' => NS_FILE,
	'Imagen_discusión' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Usuario', 'female' => 'Usuaria' ],
	NS_USER_TALK => [ 'male' => 'Usuario_discusión', 'female' => 'Usuaria_discusión' ],
]; // T113499

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'UsuariosActivos' ],
	'Allmessages'               => [ 'TodosLosMensajes', 'Todos_los_mensajes' ],
	'AllMyUploads'              => [ 'TodasMisSubidas', 'Todas_mis_subidas', 'TodosMisArchivos', 'Todos_mis_archivos' ],
	'Allpages'                  => [ 'Todas', 'Todas_las_páginas' ],
	'Ancientpages'              => [ 'PáginasAntiguas', 'Páginas_antiguas' ],
	'ApiHelp'                   => [ 'AyudaAPI', 'Ayuda_de_la_API' ],
	'ApiSandbox'                => [ 'Zona_de_pruebas_de_la_API' ],
	'AutoblockList'             => [ 'Lista_de_bloqueos_automáticos' ],
	'Badtitle'                  => [ 'Título_incorrecto' ],
	'Blankpage'                 => [ 'PáginaEnBlanco', 'Blanquear_página', 'BlanquearPágina', 'Página_en_blanco' ],
	'Block'                     => [ 'Bloquear' ],
	'BlockList'                 => [ 'UsuariosBloqueados', 'Lista_de_usuarios_bloqueados' ],
	'Booksources'               => [ 'FuentesDeLibros', 'Fuentes_de_libros' ],
	'BrokenRedirects'           => [ 'RedireccionesRotas', 'Redirecciones_rotas' ],
	'Categories'                => [ 'Categorías' ],
	'ChangeEmail'               => [ 'Cambiar_correo_electrónico', 'CambiarEmail', 'CambiarCorreo' ],
	'ChangePassword'            => [ 'Cambiar_contraseña', 'CambiarContraseña', 'ResetearContraseña', 'Resetear_contraseña' ],
	'ComparePages'              => [ 'Comparar_páginas', 'CompararPáginas' ],
	'Confirmemail'              => [ 'Confirmar_correo_electrónico', 'ConfirmarEmail' ],
	'Contributions'             => [ 'Contribuciones' ],
	'CreateAccount'             => [ 'Crear_una_cuenta', 'CrearCuenta' ],
	'Deadendpages'              => [ 'PáginasSinSalida', 'Páginas_sin_salida' ],
	'DeletedContributions'      => [ 'ContribucionesBorradas', 'Contribuciones_borradas', 'Contribuciones_Borradas' ],
	'DoubleRedirects'           => [ 'RedireccionesDobles', 'Redirecciones_dobles' ],
	'EditWatchlist'             => [ 'EditarSeguimiento' ],
	'Emailuser'                 => [ 'Enviar_correo_electrónico', 'MandarEmailUsuario' ],
	'ExpandTemplates'           => [ 'Sustituir_plantillas', 'Sustituidor_de_plantillas', 'Expandir_plantillas' ],
	'Export'                    => [ 'Exportar' ],
	'Fewestrevisions'           => [ 'MenosEdiciones', 'Menos_ediciones' ],
	'FileDuplicateSearch'       => [ 'BuscarArchivosDuplicados', 'Buscar_archivos_duplicados' ],
	'Filepath'                  => [ 'RutaDeArchivo', 'Ruta_de_archivo' ],
	'Import'                    => [ 'Importar' ],
	'Invalidateemail'           => [ 'Invalidar_correo_electrónico', 'InvalidarEmail' ],
	'JavaScriptTest'            => [ 'PruebaJavaScript' ],
	'LinkSearch'                => [ 'BúsquedaDeEnlaces', 'Búsqueda_de_enlaces' ],
	'Listadmins'                => [ 'ListaDeAdministradores', 'Lista_de_administradores' ],
	'Listbots'                  => [ 'ListaDeBots', 'Lista_de_bots' ],
	'Listfiles'                 => [ 'ListaImágenes', 'Lista_de_imágenes' ],
	'Listgrouprights'           => [ 'ListaDerechosGrupos', 'Derechos_de_grupos_de_usuarios' ],
	'Listredirects'             => [ 'TodasLasRedirecciones', 'Todas_las_redirecciones' ],
	'Listusers'                 => [ 'ListaUsuarios', 'Lista_de_usuarios' ],
	'Lockdb'                    => [ 'BloquearBasedeDatos', 'Bloquear_base_de_datos' ],
	'Log'                       => [ 'Registro' ],
	'Lonelypages'               => [ 'PáginasHuérfanas', 'Páginas_huérfanas' ],
	'Longpages'                 => [ 'PáginasLargas', 'Páginas_largas' ],
	'MergeHistory'              => [ 'FusionarHistorial', 'Fusionar_historial' ],
	'MIMEsearch'                => [ 'BuscarPorMIME', 'Buscar_por_MIME' ],
	'Mostcategories'            => [ 'MásCategorizadas', 'Más_categorizadas' ],
	'Mostimages'                => [ 'MásImágenes', 'Con_más_imágenes' ],
	'Mostinterwikis'            => [ 'MásInterwikis' ],
	'Mostlinked'                => [ 'MásEnlazados', 'Más_enlazados', 'MásEnlazadas' ],
	'Mostlinkedcategories'      => [ 'CategoríasMásUsadas', 'Categorías_más_usadas' ],
	'Mostlinkedtemplates'       => [ 'PlantillasMásUsadas', 'Plantillas_más_usadas' ],
	'Mostrevisions'             => [ 'MásEdiciones', 'Más_ediciones' ],
	'Movepage'                  => [ 'MoverPágina', 'Mover_página' ],
	'Mycontributions'           => [ 'MisContribuciones', 'Mis_contribuciones' ],
	'MyLanguage'                => [ 'MiIdioma', 'Mi_idioma' ],
	'Mypage'                    => [ 'MiPágina', 'Mi_página' ],
	'Mytalk'                    => [ 'MiDiscusión', 'Mi_discusión' ],
	'Myuploads'                 => [ 'MisArchivosSubidos', 'Mis_archivos_subidos' ],
	'Newimages'                 => [ 'NuevasImágenes', 'Nuevas_imágenes' ],
	'Newpages'                  => [ 'PáginasNuevas', 'Páginas_nuevas' ],
	'PasswordPolicies'          => [ 'Política_de_contraseñas' ],
	'PasswordReset'             => [ 'RestablecerContraseña', 'Restablecer_contraseña' ],
	'PermanentLink'             => [ 'EnlacePermanente', 'Enlace_permanente' ],
	'Preferences'               => [ 'Preferencias' ],
	'Prefixindex'               => [ 'PáginasPorPrefijo', 'Páginas_por_prefijo' ],
	'Protectedpages'            => [ 'PáginasProtegidas', 'Páginas_protegidas' ],
	'Protectedtitles'           => [ 'TítulosProtegidos', 'Títulos_protegidos' ],
	'RandomInCategory'          => [ 'Aleatorio_en_categoría', 'Aleatoria_en_categoría' ],
	'Randompage'                => [ 'Aleatoria', 'Aleatorio', 'Página_aleatoria' ],
	'Randomredirect'            => [ 'RedirecciónAleatoria', 'Redirección_aleatoria' ],
	'Recentchanges'             => [ 'CambiosRecientes', 'Cambios_recientes' ],
	'Recentchangeslinked'       => [ 'CambiosEnEnlazadas', 'Cambios_en_enlazadas' ],
	'Redirect'                  => [ 'Redirigir' ],
	'ResetTokens'               => [ 'ReestablecerClaves' ],
	'Revisiondelete'            => [ 'BorrarRevisión', 'Borrar_revisión' ],
	'Search'                    => [ 'Buscar' ],
	'Shortpages'                => [ 'PáginasCortas', 'Páginas_cortas' ],
	'Specialpages'              => [ 'PáginasEspeciales', 'Páginas_especiales' ],
	'Statistics'                => [ 'Estadísticas' ],
	'Tags'                      => [ 'Etiquetas' ],
	'TrackingCategories'        => [ 'CategoríasDeSeguimiento', 'Categorías_de_seguimiento' ],
	'Unblock'                   => [ 'Desbloquear' ],
	'Uncategorizedcategories'   => [ 'CategoríasSinCategorizar', 'Categorías_sin_categorizar' ],
	'Uncategorizedimages'       => [ 'ImágenesSinCategorizar', 'Imágenes_sin_categorizar' ],
	'Uncategorizedpages'        => [ 'PáginasSinCategorizar', 'Páginas_sin_categorizar' ],
	'Uncategorizedtemplates'    => [ 'PlantillasSinCategorizar', 'Plantillas_sin_categorizar' ],
	'Undelete'                  => [ 'Restaurar' ],
	'Unlockdb'                  => [ 'DesbloquearBasedeDatos', 'Desbloquear_base_de_datos' ],
	'Unusedcategories'          => [ 'CategoríasSinUso', 'Categorías_sin_uso' ],
	'Unusedimages'              => [ 'ImágenesSinUso', 'Imágenes_sin_uso' ],
	'Unusedtemplates'           => [ 'PlantillasSinUso', 'Plantillas_sin_uso' ],
	'Unwatchedpages'            => [ 'PáginasSinVigilar', 'Páginas_sin_vigilar' ],
	'Upload'                    => [ 'SubirArchivo', 'Subir_archivo' ],
	'UploadStash'               => [ 'ArchivosEscondidos', 'FicherosEscondidos' ],
	'Userlogin'                 => [ 'Entrar', 'Entrada_del_usuario' ],
	'Userlogout'                => [ 'Salida_del_usuario', 'Salir' ],
	'Userrights'                => [ 'PermisosUsuarios', 'Permisos_de_usuarios' ],
	'Version'                   => [ 'Versión' ],
	'Wantedcategories'          => [ 'CategoríasRequeridas', 'Categorías_requeridas' ],
	'Wantedfiles'               => [ 'ArchivosRequeridos', 'Archivos_requeridos' ],
	'Wantedpages'               => [ 'PáginasRequeridas', 'Páginas_requeridas' ],
	'Wantedtemplates'           => [ 'PlantillasRequeridas', 'Plantillas_requeridas' ],
	'Watchlist'                 => [ 'Seguimiento', 'Lista_de_seguimiento' ],
	'Whatlinkshere'             => [ 'LoQueEnlazaAquí', 'Lo_que_enlaza_aquí' ],
	'Withoutinterwiki'          => [ 'SinInterwikis', 'Sin_interwikis' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'articlepath'               => [ '0', 'RUTAARTÍCULO', 'RUTAARTICULO', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'NOMBREDEPAGINABASE', 'NOMBREDEPÁGINABASE', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'NOMBREDEPAGINABASEC', 'NOMBREDEPÁGINABASEC', 'BASEPAGENAMEE' ],
	'canonicalurl'              => [ '0', 'URLCANONICA:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'URLCANONICAC:', 'CANONICALURLE:' ],
	'contentlanguage'           => [ '1', 'IDIOMADELCONTENIDO', 'IDIOMADELCONT', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'DÍAACTUAL', 'DIAACTUAL', 'DÍA_ACTUAL', 'DIA_ACTUAL', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DÍAACTUAL2', 'DIAACTUAL2', 'DÍA_ACTUAL2', 'DIA_ACTUAL2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NOMBREDÍAACTUAL', 'NOMBREDIAACTUAL', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'DDSACTUAL', 'DIADESEMANAACTUAL', 'DÍADESEMANAACTUAL', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'HORAACTUAL', 'HORA_ACTUAL', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MESACTUAL', 'MESACTUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MESACTUAL1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'MESACTUALABREVIADO', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'MESACTUALCOMPLETO', 'NOMBREMESACTUAL', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'MESACTUALGENITIVO', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'HORA_MINUTOS_ACTUAL', 'HORAMINUTOSACTUAL', 'TIEMPOACTUAL', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'MARCADEHORAACTUAL', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'VERSIONACTUAL', 'VERSIÓNACTUAL', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'SEMANAACTUAL', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'AÑOACTUAL', 'AÑO_ACTUAL', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'ORDENAR:', 'CLAVEDEORDENPREDETERMINADO:', 'ORDENDECATEGORIAPREDETERMINADO:', 'ORDENDECATEGORÍAPREDETERMINADO:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noreplace'     => [ '0', 'noreemplazar', 'noreplace' ],
	'displaytitle'              => [ '1', 'MOSTRARTÍTULO', 'MOSTRARTITULO', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'RUTAARCHIVO:', 'RUTARCHIVO:', 'RUTADEARCHIVO:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__FORZAR_TDC__', '__FORZARTDC__', '__FORZARTOC__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'formatodefecha', 'formatearfecha', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'FORMATONÚMERO', 'FORMATONUMERO', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'NOMBRECOMPLETODEPÁGINA', 'NOMBRECOMPLETODEPAGINA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'NOMBRECOMPLETODEPAGINAC', 'NOMBRECOMPLETODEPÁGINAC', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLCOMPLETA:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLCOMPLETAC:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'GÉNERO:', 'GENERO:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMATICA:', 'GRAMÁTICA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__CATEGORÍAOCULTA__', '__HIDDENCAT__' ],
	'img_border'                => [ '1', 'borde', 'border' ],
	'img_bottom'                => [ '1', 'abajo', 'bottom' ],
	'img_center'                => [ '1', 'centro', 'centrado', 'centrada', 'centrar', 'center', 'centre' ],
	'img_framed'                => [ '1', 'marco', 'enmarcado', 'enmarcada', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'sinmarco', 'sin_enmarcar', 'sinenmarcar', 'frameless' ],
	'img_lang'                  => [ '1', 'idioma=$1', 'lang=$1' ],
	'img_left'                  => [ '1', 'izquierda', 'izda', 'izq', 'left' ],
	'img_link'                  => [ '1', 'vínculo=$1', 'vinculo=$1', 'enlace=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'miniaturadeimagen=$1', 'miniatura=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'medio', 'middle' ],
	'img_none'                  => [ '1', 'no', 'ninguna', 'ninguno', 'nada', 'none' ],
	'img_page'                  => [ '1', 'pagina=$1', 'página=$1', 'pagina_$1', 'página_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'derecha', 'der', 'dcha', 'right' ],
	'img_thumbnail'             => [ '1', 'miniaturadeimagen', 'miniatura', 'mini', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'arriba', 'top' ],
	'index'                     => [ '1', '__INDEXAR__', '__INDEX__' ],
	'language'                  => [ '0', '#IDIOMA:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'MINUS:', 'MINÚS:', 'LC:' ],
	'lcfirst'                   => [ '0', 'PRIMEROMINUS:', 'PRIMEROMINÚS:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'DÍALOCAL', 'DIALOCAL', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DIALOCAL2', 'DÍALOCAL2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'NOMBREDIALOCAL', 'NOMBREDÍALOCAL', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'DDSLOCAL', 'DIADESEMANALOCAL', 'DÍADESEMANALOCAL', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'HORALOCAL', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'MESLOCAL', 'MESLOCAL2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'MESLOCAL1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'MESLOCALABREVIADO', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'MESLOCALCOMPLETO', 'NOMBREMESLOCAL', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'MESLOCALGENITIVO', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'HORAMINUTOSLOCAL', 'TIEMPOLOCAL', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'MARCADEHORALOCAL', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'URLLOCAL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'URLLOCALC:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'SEMANALOCAL', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'AÑOLOCAL', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MSJ:', 'MSG:' ],
	'namespace'                 => [ '1', 'ESPACIODENOMBRE', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ESPACIODENOMBREC', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'NÚMERODELESPACIO', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__VINCULARANUEVASECCION__', '__ENLACECREARSECCIÓN__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__NOCONVERTIRCONTENIDO__', '__NOCC___', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__NO_EDITAR_SECCIÓN__', '__NOEDITARSECCIÓN__', '__NOEDITARSECCION__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__SIN_GALERÍA__', '__NOGALERÍA__', '__NOGALERIA__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__NOINDEXAR__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__NOVINCULARANUEVASECCION__', '__SINENLACECREARSECCIÓN__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__NOCONVERTIRTITULO__', '__NOCONVERTIRTÍTULO__', '__NOCT___', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__SIN_TDC__', '__NOTDC__', '__NOTOC__' ],
	'ns'                        => [ '0', 'EN:', 'NS:' ],
	'numberingroup'             => [ '1', 'NÚMEROENGRUPO', 'NUMEROENGRUPO', 'NUMENGRUPO', 'NÚMENGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'NÚMERODEUSUARIOSACTIVOS', 'NUMERODEUSUARIOSACTIVOS', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'NÚMEROADMINIISTRADORES', 'NÚMEROADMINS', 'NUMEROADMINS', 'NUMEROADMINISTRADORES', 'NUMERODEADMINISTRADORES', 'NUMERODEADMINS', 'NÚMERODEADMINISTRADORES', 'NÚMERODEADMINS', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NÚMERODEARTÍCULOS', 'NUMERODEARTICULOS', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NÚMERODEEDICIONES', 'NUMERODEEDICIONES', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NÚMERODEARCHIVOS', 'NUMERODEARCHIVOS', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NÚMERODEPÁGINAS', 'NUMERODEPAGINAS', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NÚMERODEUSUARIOS', 'NUMERODEUSUARIOS', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'RELLENARIZQUIERDA', 'RELLENARIZQ', 'PADLEFT' ],
	'padright'                  => [ '0', 'RELLENARDERECHA', 'RELLENARDER', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'IDDEPÁGINA', 'IDPÁGINA', 'IDDEPAGINA', 'IDPAGINA', 'PAGEID' ],
	'pagename'                  => [ '1', 'NOMBREDEPAGINA', 'NOMBREDEPÁGINA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'NOMBREDEPAGINAC', 'NOMBREDEPÁGINAC', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'PÁGINASENCATEGORÍA', 'PÁGINASENCAT', 'PAGSENCAT', 'PAGINASENCATEGORIA', 'PAGINASENCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'todo', 'all' ],
	'pagesincategory_files'     => [ '0', 'archivos', 'files' ],
	'pagesincategory_pages'     => [ '0', 'páginas', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'subcategorías', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'PÁGINASENESPACIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'TAMAÑOPÁGINA', 'TAMAÑODEPÁGINA', 'TAMAÑOPAGINA', 'TAMAÑODEPAGINA', 'PAGESIZE' ],
	'protectionlevel'           => [ '1', 'NIVELDEPROTECCIÓN', 'NIVELDEPROTECCION', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'SINFORMATO:', 'SINPUNTOS:', 'RAW:' ],
	'rawsuffix'                 => [ '1', 'SF', 'R' ],
	'redirect'                  => [ '0', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ],
	'revisionday'               => [ '1', 'DIADEREVISION', 'DIAREVISION', 'DÍADEREVISIÓN', 'DÍAREVISIÓN', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DIADEREVISION2', 'DIAREVISION2', 'DÍADEREVISIÓN2', 'DÍAREVISIÓN2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'IDDEREVISION', 'IDREVISION', 'IDDEREVISIÓN', 'IDREVISIÓN', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'MESDEREVISION', 'MESDEREVISIÓN', 'MESREVISION', 'MESREVISIÓN', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'MESDEREVISION1', 'MESDEREVISIÓN1', 'MESREVISION1', 'MESREVISIÓN1', 'REVISIONMONTH1' ],
	'revisionsize'              => [ '1', 'TAMAÑODEREVISIÓN', 'TAMAÑODEREVISION', 'REVISIONSIZE' ],
	'revisiontimestamp'         => [ '1', 'MARCADEHORADEREVISION', 'MARCADEHORADEREVISIÓN', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'USUARIODEREVISION', 'USUARIODEREVISIÓN', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'AÑODEREVISION', 'AÑODEREVISIÓN', 'AÑOREVISION', 'AÑOREVISIÓN', 'REVISIONYEAR' ],
	'rootpagename'              => [ '1', 'NOMBREDEPAGINARAIZ', 'NOMBREDEPÁGINARAÍZ', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', 'NOMBREDEPAGINARAIZC', 'NOMBREDEPÁGINARAÍZC', 'ROOTPAGENAMEE' ],
	'scriptpath'                => [ '0', 'RUTASCRIPT', 'RUTADESCRIPT', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'SERVIDOR', 'SERVER' ],
	'servername'                => [ '0', 'NOMBRESERVIDOR', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'NOMBREDELSITIO', 'SITENAME' ],
	'special'                   => [ '0', 'especial', 'special' ],
	'speciale'                  => [ '0', 'especialc', 'speciale' ],
	'staticredirect'            => [ '1', '__REDIRECCIÓNESTÁTICA__', '__REDIRECCIONESTATICA__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'RUTAESTILO', 'RUTADEESTILO', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'NOMBREDEPAGINADETEMA', 'NOMBREDEPÁGINADETEMA', 'NOMBREDEPÁGINADEASUNTO', 'NOMBREDEPAGINADEASUNTO', 'NOMBREDEPAGINADEARTICULO', 'NOMBREDEPÁGINADEARTÍCULO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'NOMBREDEPAGINADETEMAC', 'NOMBREDEPÁGINADETEMAC', 'NOMBREDEPÁGINADEASUNTOC', 'NOMBREDEPAGINADEASUNTOC', 'NOMBREDEPAGINADEARTICULOC', 'NOMBREDEPÁGINADEARTÍCULOC', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'ESPACIODEASUNTO', 'ESPACIODETEMA', 'ESPACIODEARTÍCULO', 'ESPACIODEARTICULO', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ESPACIODETEMAC', 'ESPACIODEASUNTOC', 'ESPACIODEARTICULOC', 'ESPACIODEARTÍCULOC', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'NOMBREDESUBPAGINA', 'NOMBREDESUBPÁGINA', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'NOMBREDESUBPAGINAC', 'NOMBREDESUBPÁGINAC', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'SUST:', 'FIJAR:', 'SUBST:' ],
	'tag'                       => [ '0', 'etiqueta', 'tag' ],
	'talkpagename'              => [ '1', 'NOMBREDEPÁGINADEDISCUSIÓN', 'NOMBREDEPAGINADEDISCUSION', 'NOMBREDEPAGINADISCUSION', 'NOMBREDEPÁGINADISCUSIÓN', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'NOMBREDEPÁGINADEDISCUSIÓNC', 'NOMBREDEPAGINADEDISCUSIONC', 'NOMBREDEPAGINADISCUSIONC', 'NOMBREDEPÁGINADISCUSIÓNC', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'ESPACIODEDISCUSION', 'ESPACIODEDISCUSIÓN', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ESPACIODEDISCUSIONC', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__TDC__', '__TOC__' ],
	'uc'                        => [ '0', 'MAYUS:', 'MAYÚS:', 'UC:' ],
	'ucfirst'                   => [ '0', 'PRIMEROMAYUS:', 'PRIMEROMAYÚS:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'CODIFICARURL:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'RUTA', 'PATH' ],
	'url_query'                 => [ '0', 'BÚSQUEDA', 'QUERY' ],
];

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i j M Y',
];

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];
$minimumGroupingDigits = 2;

$linkTrail = '/^([a-záéíóúñ]+)(.*)$/sDu';
