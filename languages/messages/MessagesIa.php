<?php
/** Interlingua (interlingua)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 */

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];

$namespaceNames = [
	NS_MEDIA            => 'Multimedia',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Usator',
	NS_USER_TALK        => 'Discussion_Usator',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Discussion_File',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Patrono',
	NS_TEMPLATE_TALK    => 'Discussion_Patrono',
	NS_HELP             => 'Adjuta',
	NS_HELP_TALK        => 'Discussion_Adjuta',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussion_Categoria',
];

$namespaceAliases = [
	'Imagine' => NS_FILE,
	'Discussion_Imagine' => NS_FILE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Usatores_active' ],
	'Allmessages'               => [ 'Tote_le_messages' ],
	'Allpages'                  => [ 'Tote_le_paginas' ],
	'Ancientpages'              => [ 'Paginas_ancian' ],
	'Badtitle'                  => [ 'Titulo_invalide' ],
	'Blankpage'                 => [ 'Pagina_vacue' ],
	'Block'                     => [ 'Blocar', 'Blocar_IP', 'Blocar_usator' ],
	'BlockList'                 => [ 'Lista_de_blocadas', 'Lista_de_blocadas_IP' ],
	'Booksources'               => [ 'Fontes_de_libros' ],
	'BrokenRedirects'           => [ 'Redirectiones_rupte' ],
	'Categories'                => [ 'Categorias' ],
	'ChangeEmail'               => [ 'Cambiar_e-mail' ],
	'ChangePassword'            => [ 'Cambiar_contrasigno' ],
	'ComparePages'              => [ 'Comparar_paginas' ],
	'Confirmemail'              => [ 'Confirmar_e-mail' ],
	'Contributions'             => [ 'Contributiones' ],
	'CreateAccount'             => [ 'Crear_conto' ],
	'Deadendpages'              => [ 'Paginas_sin_exito' ],
	'DeletedContributions'      => [ 'Contributiones_delite' ],
	'DoubleRedirects'           => [ 'Redirectiones_duple' ],
	'EditWatchlist'             => [ 'Modificar_observatorio' ],
	'Emailuser'                 => [ 'Inviar_e-mail_a_un_usator' ],
	'ExpandTemplates'           => [ 'Expander_patronos' ],
	'Export'                    => [ 'Exportar' ],
	'Fewestrevisions'           => [ 'Le_minus_versiones' ],
	'FileDuplicateSearch'       => [ 'Recerca_de_files_duplice' ],
	'Filepath'                  => [ 'Cammino_al_file' ],
	'Import'                    => [ 'Importar' ],
	'Invalidateemail'           => [ 'Invalidar_e-mail' ],
	'LinkSearch'                => [ 'Recerca_de_ligamines' ],
	'Listadmins'                => [ 'Lista_de_administratores' ],
	'Listbots'                  => [ 'Lista_de_robots' ],
	'Listfiles'                 => [ 'Lista_de_files', 'Lista_de_imagines' ],
	'Listgrouprights'           => [ 'Lista_del_derectos_de_gruppos' ],
	'Listredirects'             => [ 'Lista_de_redirectiones' ],
	'Listusers'                 => [ 'Lista_de_usatores' ],
	'Lockdb'                    => [ 'Blocar_BDD' ],
	'Log'                       => [ 'Registro', 'Registros' ],
	'Lonelypages'               => [ 'Paginas_orphanate' ],
	'Longpages'                 => [ 'Paginas_longe' ],
	'MergeHistory'              => [ 'Fusionar_historia' ],
	'MIMEsearch'                => [ 'Recerca_MIME' ],
	'Mostcategories'            => [ 'Le_plus_categorias' ],
	'Mostimages'                => [ 'Files_le_plus_ligate', 'Le_plus_files', 'Le_plus_imagines' ],
	'Mostlinked'                => [ 'Paginas_le_plus_ligate', 'Le_plus_ligate' ],
	'Mostlinkedcategories'      => [ 'Categorias_le_plus_ligate', 'Categorias_le_plus_usate' ],
	'Mostlinkedtemplates'       => [ 'Patronos_le_plus_ligate', 'Patronos_le_plus_usate' ],
	'Mostrevisions'             => [ 'Le_plus_versiones' ],
	'Movepage'                  => [ 'Renominar_pagina' ],
	'Mycontributions'           => [ 'Mi_contributiones' ],
	'MyLanguage'                => [ 'Mi_lingua' ],
	'Mypage'                    => [ 'Mi_pagina' ],
	'Mytalk'                    => [ 'Mi_discussion' ],
	'Myuploads'                 => [ 'Mi_files_incargate' ],
	'Newimages'                 => [ 'Nove_files', 'Nove_imagines' ],
	'Newpages'                  => [ 'Paginas_nove', 'Nove_paginas' ],
	'PasswordReset'             => [ 'Reinitialisar_contrasigno' ],
	'PermanentLink'             => [ 'Ligamine_permanente' ],
	'Preferences'               => [ 'Preferentias' ],
	'Prefixindex'               => [ 'Indice_de_prefixos' ],
	'Protectedpages'            => [ 'Paginas_protegite' ],
	'Protectedtitles'           => [ 'Titulos_protegite' ],
	'RandomInCategory'          => [ 'Aleatori_in_categoria' ],
	'Randompage'                => [ 'Aleatori', 'Pagina_aleatori' ],
	'Randomredirect'            => [ 'Redirection_aleatori' ],
	'Recentchanges'             => [ 'Modificationes_recente' ],
	'Recentchangeslinked'       => [ 'Modificationes_recente_ligate', 'Modificationes_connexe' ],
	'Revisiondelete'            => [ 'Deletion_de_versiones' ],
	'Search'                    => [ 'Recerca' ],
	'Shortpages'                => [ 'Paginas_curte' ],
	'Specialpages'              => [ 'Paginas_special' ],
	'Statistics'                => [ 'Statisticas' ],
	'Tags'                      => [ 'Etiquettas' ],
	'Unblock'                   => [ 'Disblocar' ],
	'Uncategorizedcategories'   => [ 'Categorias_non_categorisate' ],
	'Uncategorizedimages'       => [ 'Files_non_categorisate', 'Imagines_non_categorisate' ],
	'Uncategorizedpages'        => [ 'Paginas_non_categorisate' ],
	'Uncategorizedtemplates'    => [ 'Patronos_non_categorisate' ],
	'Undelete'                  => [ 'Restaurar' ],
	'Unlockdb'                  => [ 'Disblocar_BDD' ],
	'Unusedcategories'          => [ 'Categorias_non_usate' ],
	'Unusedimages'              => [ 'Files_non_usate', 'Imagines_non_usate' ],
	'Unusedtemplates'           => [ 'Patronos_non_usate' ],
	'Unwatchedpages'            => [ 'Paginas_non_observate' ],
	'Upload'                    => [ 'Incargar', 'Cargar' ],
	'UploadStash'               => [ 'Pila_de_files_incargate' ],
	'Userlogin'                 => [ 'Aperir_session', 'Identificar' ],
	'Userlogout'                => [ 'Clauder_session', 'Disconnecter' ],
	'Userrights'                => [ 'Derectos_de_usatores' ],
	'Wantedcategories'          => [ 'Categorias_desirate' ],
	'Wantedfiles'               => [ 'Files_desirate' ],
	'Wantedpages'               => [ 'Paginas_desirate', 'Ligamines_rupte' ],
	'Wantedtemplates'           => [ 'Patronos_desirate' ],
	'Watchlist'                 => [ 'Observatorio' ],
	'Whatlinkshere'             => [ 'Referentias_a_iste_pagina' ],
	'Withoutinterwiki'          => [ 'Sin_interwiki' ],
];
