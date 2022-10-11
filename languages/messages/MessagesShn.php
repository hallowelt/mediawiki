<?php
/** Shan (ၵႂၢမ်းတႆးလူင်)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'သိုဝ်ႇၶၢဝ်ႇ',
	NS_SPECIAL          => 'ၶိုၵ်ႉတွၼ်း',
	NS_TALK             => 'ဢုပ်ႇၵုမ်',
	NS_USER             => 'ၽူႈၸႂ်ႉတိုဝ်း',
	NS_USER_TALK        => 'ဢုပ်ႇၵုမ်_ၽူႈၸႂ်ႉတိုဝ်း',
	NS_PROJECT_TALK     => 'ဢုပ်ႇၵုမ်_$1',
	NS_FILE             => 'ၾၢႆႇ',
	NS_FILE_TALK        => 'ဢုပ်ႇၵုမ်_ၾၢႆႇ',
	NS_MEDIAWIKI        => 'မီႇတီႇယႃႇဝီႇၶီႇ',
	NS_MEDIAWIKI_TALK   => 'ဢုပ်ႇၵုမ်_မီႇတီႇယႃႇဝီႇၶီႇ',
	NS_TEMPLATE         => 'ထႅမ်းပလဵတ်ႉ',
	NS_TEMPLATE_TALK    => 'ဢုပ်ႇၵုမ်_ထႅမ်းပလဵတ်ႉ',
	NS_HELP             => 'လွင်ႈၸွႆႈထႅမ်',
	NS_HELP_TALK        => 'ဢုပ်ႇၵုမ်_လွင်ႈၸွႆႈထႅမ်',
	NS_CATEGORY         => 'ပိူင်ထၢၼ်ႈ',
	NS_CATEGORY_TALK    => 'ဢုပ်ႇၵုမ်_ပိူင်ထၢၼ်ႈ',
];

$namespaceAliases = [
	'သိုဝ်ႇၶၢဝ်ႇဝီႇၶီႇ' => NS_MEDIAWIKI,
	'ဢုပ်ႇၵုမ်_သိုဝ်ႇၶၢဝ်ႇဝီႇၶီႇ' => NS_MEDIAWIKI_TALK,
	'ထႅမ်းပလဵၵ်ႉ' => NS_TEMPLATE,
	'ဢုပ်ႇၵုမ်_ထႅမ်းပလဵၵ်ႉ' => NS_TEMPLATE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'ၽူႈၸႂ်ႉတိုဝ်းတူင်ႉတိုၼ်ႇ' ],
	'Allmessages'               => [ 'ၶေႃႈၶၢဝ်ႇတင်းသဵင်ႈ' ],
	'AllMyUploads'              => [ 'လွင်ႈလူတ်ႇၶိုၼ်ႈၵဝ်ၶႃႈတင်းသဵင်ႈ', 'ၾၢႆႇၶႃႇတင်းသဵင်ႈ' ],
	'Allpages'                  => [ 'ၼႃႈလိၵ်ႈတင်းသဵင်ႈ' ],
	'Ancientpages'              => [ 'ၼႃႈလိၵ်ႈၵဝ်ႇမွၼ်ႇ' ],
	'ApiHelp'                   => [ 'ၵၼ်ၸွႆႈထႅမ်ဢေႇၽီႇဢၢႆႇ' ],
	'ApiSandbox'                => [ 'ဢေႇၽီႇဢၢႆႇသႅၼ်ႉပွၵ်ႉ' ],
	'AutoblockList'             => [ 'သဵၼ်ႈမၢႆႁႄႉတတ်းႁင်းတူဝ်', 'သဵၼ်ႈမၢႆႁႄႉတတ်းႁင်းတူဝ်' ],
	'Badtitle'                  => [ 'ႁူဝ်ၶေႃႈႁၢႆႉၸႃႉ' ],
	'Blankpage'                 => [ 'ၼႃႈလိၵ်ႈပဝ်ႇ' ],
	'Block'                     => [ 'ႁႄႉတတ်း', 'ႁႄႉတတ်းဢၢႆႇၽီႇ', 'ႁႄႉတတ်းၽူႈၸႂ်ႉတိုဝ်း' ],
	'BlockList'                 => [ 'သဵၼ်ႈမၢႆႁႄႉတတ်း', 'သဵၼ်ႈမၢႆႁႄႉတတ်း', 'သဵၼ်ႈမၢႆႁႄႉတတ်းဢၢႆႇၽီႇ' ],
	'Booksources'               => [ 'ငဝ်ႈငႃႇပပ်ႉ' ],
	'BotPasswords'              => [ 'ၶေႃႈလပ်ႉပွတ်ႉ' ],
	'BrokenRedirects'           => [ 'လွင်ႈပိၼ်ႇၸီႉဢၼ်ၵွႆဝႆႉ' ],
	'Categories'                => [ 'ပိူင်ထၢၼ်ႈ' ],
	'ChangeContentModel'        => [ 'လႅၵ်ႈလၢႆႈမေႃႇတႄႇလမ်းၼႂ်း' ],
	'ChangeCredentials'         => [ 'လႅၵ်ႈလၢႆႈလၵ်းထၢၼ်' ],
	'ChangeEmail'               => [ 'လႅၵ်ႈလၢႆႈဢီးမေးလ်' ],
	'ChangePassword'            => [ 'လႅၵ်ႈလၢႆႈၶေႃႈလပ်ႉ', 'တင်ႈၶိုၼ်းၶေႃႈလပ်ႉ', 'တင်ႈၶိုၼ်းၶေႃႈလပ်ႉ' ],
	'ComparePages'              => [ 'ၼိူင်းတူၺ်းၼႃႈလိၵ်ႈ' ],
	'Confirmemail'              => [ 'ၼႄႉၼွၼ်းဢီးမေးလ်' ],
	'Contributions'             => [ 'လွင်ႈၶဝ်ႈႁူမ်ႈ', 'ၶဝ်ႈႁူမ်ႈ' ],
	'CreateAccount'             => [ 'ၵေႃႇသၢင်ႈဢၶွင်ႉ' ],
	'Deadendpages'              => [ 'ၼႃႈလိၵ်ႈသုတ်းႁၢမ်း' ],
	'DeletedContributions'      => [ 'လွင်ႈၶဝ်ႈႁူမ်ႈၸိူဝ်းမွတ်ႇဝႆႉ' ],
	'DoubleRedirects'           => [ 'ၶေႃႈပိၼ်ႇၸီႉသွင်ၵမ်း' ],
	'EditTags'                  => [ 'မႄးတတ်းတိတ်းၸပ်း' ],
	'EditWatchlist'             => [ 'မႄးထတ်းသဵၼ်ႈမၢႆတူၺ်း' ],
	'Emailuser'                 => [ 'ၽူႈၸႂ်ႉတိုဝ်းဢီးမေးလ်', 'ဢီးမေးလ်' ],
	'ExpandTemplates'           => [ 'ၵီႈပိုတ်ႇထႅမ်းပလဵၵ်ႉ' ],
	'Export'                    => [ 'သူင်ႇဢွၵ်ႇ' ],
	'Fewestrevisions'           => [ 'လွင်ႈမႄးၶိုၼ်းဢၼ်ႇဢေႇသုတ်း' ],
	'FileDuplicateSearch'       => [ 'သွၵ်ႈႁႃၾၢႆႇထပ်းၵၼ်' ],
	'Filepath'                  => [ 'သၢႆတၢင်းၾၢႆႇ' ],
	'GoToInterwiki'             => [ 'ၵႂႃႇၸူးဢိၼ်ႇထႃႇဝီႇၶီႇ' ],
	'Import'                    => [ 'သူင်ႇၶဝ်ႈ' ],
	'Invalidateemail'           => [ 'ဢီးမေးလ်ဢမ်ႇၼႄႉၼွၼ်း' ],
	'JavaScriptTest'            => [ 'လွင်ႈၸၢမ်းၵျႃႇပႃးၸၶရိပ်ႉ' ],
	'LinkAccounts'              => [ 'ႁဵင်းၵွင်ႉဢၶွင်ႉ' ],
	'LinkSearch'                => [ 'သွၵ်ႈႁႃႁဵင်းၵွင်ႉ' ],
	'Listadmins'                => [ 'သဵၼ်ႈမၢႆၽူႈၵုမ်းၵၢၼ်' ],
	'Listbots'                  => [ 'သဵၼ်ႈမၢႆပွတ်ႉ' ],
	'ListDuplicatedFiles'       => [ 'သဵၼ်ႈမၢႆၾၢႆႇထပ်းၵၼ်', 'သဵၼ်ႈမၢႆၾၢႆႇထပ်းၵၼ်' ],
	'Listfiles'                 => [ 'သဵၼ်ႈမၢႆၾၢႆႇ', 'သဵၼ်ႈမၢႆၾၢႆႇ', 'သဵၼ်ႈမၢႆၶႅပ်းႁၢင်ႈ' ],
	'Listgrants'                => [ 'သဵၼ်ႈမၢႆၶႂၢင်းပၼ်' ],
	'Listgrouprights'           => [ 'သဵၼ်ႈမၢႆသုၼ်ႇၸုမ်း', 'ၽူႈၸႂ်ႉတိုဝ်းသုၼ်ႇၸုမ်း' ],
	'Listredirects'             => [ 'သဵၼ်ႈမၢႆတူဝ်ပိၼ်ႇၸီႉ' ],
	'Listusers'                 => [ 'သဵၼ်ႈမၢႆၽူႈၸႂ်ႉတိုဝ်း', 'သဵၼ်ႈမၢႆၽူႈၸႂ်ႉတိုဝ်း' ],
	'Lockdb'                    => [ 'ၶတ်းယွင်ၶေႃႈမုၼ်း' ],
	'Log'                       => [ 'သၢႆမၢႆ', 'သၢႆမၢႆ' ],
	'Lonelypages'               => [ 'ၼႃႈလိၵ်ႈတူဝ်လဵဝ်', 'ၼႃႈလိၵ်ႈၽၢၼ်ပေႃႈ' ],
	'Longpages'                 => [ 'ၼႃႈလိၵ်ႈယၢဝ်း' ],
	'MediaStatistics'           => [ 'သဵၼ်ႈသၢႆမၢႆသိုဝ်ႇၶၢဝ်ႇ' ],
	'MergeHistory'              => [ 'ပိုၼ်းလေႃး' ],
	'MIMEsearch'                => [ 'သွၵ်ႈႁႃဢႅမ်ႇဢၢႆႇဢႅမ်ႇဢီး' ],
	'Mostcategories'            => [ 'ပူင်ထၢၼ်ႈၵမ်ႉၼမ်' ],
	'Mostimages'                => [ 'ၾၢႆႇၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်', 'ၾၢႆႇၵမ်ႉၼမ်', 'ၶႅပ်းႁၢင်ႈၵမ်ႉၼမ်' ],
	'Mostinterwikis'            => [ 'ဢိၼ်ႇထႃႇဝီႇၶီႇၵမ်ႉၼမ်' ],
	'Mostlinked'                => [ 'ၼႃႈလိၵ်ႈၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်', 'ၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်' ],
	'Mostlinkedcategories'      => [ 'ပိူင်ထၢၼ်ႈၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်', 'ပိူင်ထၢၼ်ႈၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်' ],
	'Mostlinkedtemplates'       => [ 'ၼႃႈလိၵ်ႈၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်', 'ထႅမ်းပလဵၵ်ႉၸိူဝ်းၵွင်ႉဝႆႉၵမ်ႉၼမ်', 'ထႅမ်းပလဵၵ်ႉၸိူဝ်းၸႂ်ႉဝႆႉၵမ်ႉၼမ်' ],
	'Mostrevisions'             => [ 'လွင်ႈမႄးၶိုၼ်းၵမ်ႉၼမ်' ],
	'Movepage'                  => [ 'ၶၢႆႉၼႃႈလိၵ်ႈ' ],
	'Mycontributions'           => [ 'လွင်ႈၶဝ်ႈႁူမ်ႈၵဝ်ၶႃႈ' ],
	'MyLanguage'                => [ 'ၽႃႇသႃႇၵႂၢမ်းၵဝ်ၶႃႈ' ],
	'Mypage'                    => [ 'ၼႃႈလိၵ်ႈၵဝ်ၶႃႈ' ],
	'Mytalk'                    => [ 'လွင်ႈဢုပ်ႇၵုမ်ၵဝ်ၶႃႈ' ],
	'Myuploads'                 => [ 'လွင်ႈလူတ်ႇၶိုၼ်ႈၵဝ်ၶႃႈ', 'ၾၢႆႇၵဝ်ၶႃႈ' ],
	'Newimages'                 => [ 'ၾၢႆႇဢၼ်မႂ်ႇ', 'ၶႅပ်းႁၢင်ႈဢၼ်မႂ်ႇ' ],
	'Newpages'                  => [ 'ၼႃႈလိၵ်ႈဢၼ်မႂ်ႇ' ],
	'PageData'                  => [ 'ၶေႃႈမုၼ်းၼႃႈလိၵ်ႈ' ],
	'PageLanguage'              => [ 'ၽႃႇသႃႇၵႂၢမ်းၼႃႈလိၵ်ႈ' ],
	'PagesWithProp'             => [ 'ၼႃႈလိၵ်ႈတင်းၶူဝ်းၶွင်', 'ၼႃႈလိၵ်ႈတင်းၶူဝ်းၶွင်', 'ၼႃႈလိၵ်ႈတင်းၶူဝ်းၶွင်', 'ၼႃႈလိၵ်ႈတင်းၶူဝ်းၶွင်' ],
	'PasswordPolicies'          => [ 'ပေႃႇလၸီႇၶေႃႈလပ်ႉ' ],
	'PasswordReset'             => [ 'တင်ႈၶိုၼ်းၶေႃႈလပ်ႉ' ],
	'PermanentLink'             => [ 'ႁဵင်းၵွင်ႉမၼ်ႈၵႅၼ်ႇ', 'ႁဵင်းၵွင်ႉမၼ်ႈၵႅၼ်ႇ' ],
	'Preferences'               => [ 'ငဝ်ႈၵုမ်းၵၢၼ်' ],
	'Prefixindex'               => [ 'တူဝ်ၶပ်ႉဝႆႉၼႃႈ' ],
	'Protectedpages'            => [ 'ၼႃႈလိၵ်ႈၸိူဝ်းႁႄႉၵင်ႈဝႆႉ' ],
	'Protectedtitles'           => [ 'ႁူဝ်ၶေႃႈၸိူဝ်းႁႄႉၵင်ႈဝႆႉ' ],
	'RandomInCategory'          => [ 'ပိူင်ထၢၼ်ႈတီႈၵမ်ႉသၢင်ႇတေႃႇ' ],
	'Randompage'                => [ 'ၵမ်ႉသၢင်ႇတေႃႇ', 'ၼႃႈလိၵ်ႈၵမ်ႉသၢင်ႇတေႃႇ' ],
	'Randomredirect'            => [ 'တူဝ်ပိၼ်ႇၸီႉၵမ်ႉသၢင်ႇတေႃႇ' ],
	'Randomrootpage'            => [ 'ၼႃႈလိၵ်ႈငဝ်ႈငႃႇၵမ်ႉသၢင်ႇတေႃႇ' ],
	'Recentchanges'             => [ 'လွင်ႈလႅၵ်ႈလၢႆႈမႂ်ႇမႂ်ႇ' ],
	'Recentchangeslinked'       => [ 'လွင်ႈလႅၵ်ႈလၢႆႈမႂ်ႇမႂ်ႇၸိူဝ်းၵွင်ႉဝႆႉ', 'လွင်ႈလႅၵ်ႈလၢႆႈၸိူဝ်းၵွင်ႉၵၢႆႇ' ],
	'Redirect'                  => [ 'ပိၼ်ႇၸီႉ' ],
	'RemoveCredentials'         => [ 'ထွၼ်ပႅတ်ႈလၵ်းထၢၼ်' ],
	'ResetTokens'               => [ 'တင်ႈၶိုၼ်းမၢႆၶပ်ႉ' ],
	'Revisiondelete'            => [ 'မွတ်ႇပႅတ်ႈလွင်ႈမႄးၶိုၼ်း' ],
	'RunJobs'                   => [ 'ႁဵတ်းၵၢၼ်' ],
	'Search'                    => [ 'သွၵ်ႈႁႃ' ],
	'Shortpages'                => [ 'ၼႃႈလိၵ်ႈဢၼ်ပွတ်း' ],
	'Specialpages'              => [ 'ၼႃႈလိၵ်ႈၶိုၵ်ႉတွၼ်း' ],
	'Statistics'                => [ 'သဵၼ်ႈသၢႆမၢႆ', 'သဵၼ်ႈသၢႆမၢႆ' ],
	'Tags'                      => [ 'တိတ်းၸပ်း' ],
	'TrackingCategories'        => [ 'ၸွမ်းႁွႆးပိူင်ထၢၼ်ႈ' ],
	'Unblock'                   => [ 'ပိုတ်ႇသေႃး' ],
	'Uncategorizedcategories'   => [ 'ပိူင်ထၢၼ်ႈလေႃးလႄး' ],
	'Uncategorizedimages'       => [ 'ၾၢႆႇလေႃးလႄး', 'ၶႅပ်းႁၢင်ႈလေႃးလႄး' ],
	'Uncategorizedpages'        => [ 'ၼႃႈလိၵ်ႈလေႃးလႄး' ],
	'Uncategorizedtemplates'    => [ 'ထႅမ်းပလဵၵ်ႉလေႃးလႄး' ],
	'Undelete'                  => [ 'ၶိုၼ်းဢမ်ႇမွတ်ႇ' ],
	'UnlinkAccounts'            => [ 'ၶိုၼ်းဢမ်ႇၵွင်ႉဢၶွင်ႉ' ],
	'Unlockdb'                  => [ 'ၶိုၼ်းဢမ်ႇၶတ်းယွင်ၶေႃႈမုၼ်း' ],
	'Unusedcategories'          => [ 'ပိူင်ထၢၼ်ႈဢၼ်ဢမ်ႇၸႂ်ႉဝႆႉ' ],
	'Unusedimages'              => [ 'ၾၢႆႇဢၼ်ဢမ်ႇၸႂ်ႉဝႆႉ', 'ၶႅပ်းႁၢင်ႈဢၼ်ဢမ်ႇၸႂ်ႉဝႆႉ' ],
	'Unusedtemplates'           => [ 'ထႅမ်းပလဵၵ်ႉဢၼ်ဢမ်ႇၸႂ်ႉဝႆႉ' ],
	'Unwatchedpages'            => [ 'ၼႃႈလိၵ်ႈဢၼ်ဢမ်ႇတူၺ်းဝႆႉ' ],
	'Upload'                    => [ 'လူတ်ႇၶိုၼ်ႈ' ],
	'UploadStash'               => [ 'လူတ်ႇၶိုၼ်ႈတီႈသိမ်းသူင်ႇ' ],
	'Userlogin'                 => [ 'လွၵ်ႉဢိၼ်ႇၽူႈၸႂ်ႉတိုဝ်း', 'လွၵ်ႉဢိၼ်ႇ' ],
	'Userlogout'                => [ 'လွၵ်ႉဢွၵ်ႉၽူႈၸႂ်ႉတိုဝ်း', 'လွၵ်ႉဢွၵ်ႉ' ],
	'Userrights'                => [ 'သုၼ်ႇၽူႈၸႂ်ႉတိုဝ်း', 'သုၼ်ႇၽူႈၸႂ်ႉတိုဝ်း', 'ႁဵတ်းပွတ်ႉ' ],
	'Version'                   => [ 'ဝႃးသျိၼ်း' ],
	'Wantedcategories'          => [ 'ပိူင်ထၢၼ်ႈဢၼ်ၶႂ်ႈလႆႈ' ],
	'Wantedfiles'               => [ 'ၾၢႆႇဢၼ်ၶႂ်ႈလႆႈ' ],
	'Wantedpages'               => [ 'ၼႃႈလိၵ်ႈဢၼ်ၶႂ်ႈလႆႈ', 'ႁဵင်းၵွင်ႉၸိူဝ်းလူႉဝႆႉ' ],
	'Wantedtemplates'           => [ 'ထႅမ်းပလဵၵ်ႉဢၼ်ၶႂ်ႈလႆႈ' ],
	'Watchlist'                 => [ 'သဵၼ်ႈမၢႆတႃႇတူၺ်း' ],
	'Whatlinkshere'             => [ 'ႁဵင်းၵွင်ႉသင်တီႈၼႆႈ' ],
	'Withoutinterwiki'          => [ 'ဢမ်ႇမီးဢိၼ်ႇထႃႇဝီႇၶီႇ' ],
];
