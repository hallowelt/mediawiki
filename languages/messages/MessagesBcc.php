<?php
/** Southern Balochi (جهلسری بلوچی)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'fa';

$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'حاص',
	NS_TALK             => 'گپ',
	NS_USER             => 'کاربر',
	NS_USER_TALK        => 'گپ_کاربر',
	NS_PROJECT_TALK     => 'گپ_$1',
	NS_FILE             => 'عکس',
	NS_FILE_TALK        => 'گپ_عکس',
	NS_MEDIAWIKI        => 'مدیاویکی',
	NS_MEDIAWIKI_TALK   => 'گپ_مدیاویکی',
	NS_TEMPLATE         => 'تمپلت',
	NS_TEMPLATE_TALK    => 'گپ_تمپلت',
	NS_HELP             => 'کمک',
	NS_HELP_TALK        => 'گپ_راهنما',
	NS_CATEGORY         => 'دسته',
	NS_CATEGORY_TALK    => 'گپ_دسته',
];

$namespaceAliases = [
	'مدیا' => NS_MEDIA,
	'ویژه' => NS_SPECIAL,
	'بحث' => NS_TALK,
	'کاربر' => NS_USER,
	'بحث_کاربر' => NS_USER_TALK,
	'بحث_$1' => NS_PROJECT_TALK,
	'تصویر' => NS_FILE,
	'بحث_تصویر' => NS_FILE_TALK,
	'مدیاویکی' => NS_MEDIAWIKI,
	'بحث_مدیاویکی' => NS_MEDIAWIKI_TALK,
	'الگو' => NS_TEMPLATE,
	'بحث_الگو' => NS_TEMPLATE_TALK,
	'راهنما' => NS_HELP,
	'بحث_راهنما' => NS_HELP_TALK,
	'رده' => NS_CATEGORY,
	'بحث_رده' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'کل_کوله_یان' ],
	'Allpages'                  => [ 'کل_صفحات' ],
	'Ancientpages'              => [ 'صفحات_قدیمی' ],
	'Blankpage'                 => [ 'صفحه_هالیک' ],
	'Block'                     => [ 'محدود_آی_پی' ],
	'BlockList'                 => [ 'لیست_محدوددیت_آی_پی' ],
	'Booksources'               => [ 'منابع_کتاب' ],
	'BrokenRedirects'           => [ 'پرشتگین_غیرمستقیم' ],
	'Categories'                => [ 'دستجات' ],
	'ChangePassword'            => [ 'تریتگ_رمز' ],
	'Confirmemail'              => [ 'تایید_ایمیل' ],
	'Contributions'             => [ 'مشارکتان' ],
	'CreateAccount'             => [ 'شرکتن_حساب' ],
	'Deadendpages'              => [ 'مرتگین_صفحات' ],
	'DoubleRedirects'           => [ 'دوبل_غیر_مستقیم' ],
	'Emailuser'                 => [ 'ایمیل_کاربر' ],
	'Export'                    => [ 'درگیزگ' ],
	'Fewestrevisions'           => [ 'کمترین_بازبینی' ],
	'FileDuplicateSearch'       => [ 'گردگ_کپی_فایل' ],
	'Filepath'                  => [ 'مسیر_فایل' ],
	'Import'                    => [ 'وارد' ],
	'Invalidateemail'           => [ 'نامعتبرین_ایمیل' ],
	'Listadmins'                => [ 'لیست_مدیران' ],
	'Listbots'                  => [ 'لیست_روباتان' ],
	'Listfiles'                 => [ 'لیست_عکس' ],
	'Listgrouprights'           => [ 'لیست_حقوق_گروه' ],
	'Listredirects'             => [ 'لیست_غیر_مستقیمان' ],
	'Listusers'                 => [ 'لیست_کاربر' ],
	'Lockdb'                    => [ 'کبلدب' ],
	'Log'                       => [ 'ورودان' ],
	'Lonelypages'               => [ 'صفحات_یتیم' ],
	'Longpages'                 => [ 'مزنین_صفحات' ],
	'MergeHistory'              => [ 'چندوبند_تاریح' ],
	'MIMEsearch'                => [ 'گردگ_میام' ],
	'Mostcategories'            => [ 'گیشترین_دستجات' ],
	'Mostimages'                => [ 'گیشترین_عکس' ],
	'Mostlinked'                => [ 'گیشتر_لینک_بوتت' ],
	'Mostlinkedcategories'      => [ 'دستجات_گیشتر_لینک_بوتگین' ],
	'Mostlinkedtemplates'       => [ 'تمپلتان_گیشتر_لینک_بوتگین' ],
	'Mostrevisions'             => [ 'گیشترین_بازبینی' ],
	'Movepage'                  => [ 'جاه_په_جاهی_صفحه' ],
	'Mycontributions'           => [ 'منی_مشارکت' ],
	'Mypage'                    => [ 'منی_صفحه' ],
	'Mytalk'                    => [ 'منی_گپ' ],
	'Newimages'                 => [ 'نوکین_عکسان' ],
	'Newpages'                  => [ 'نوکین_صفحات' ],
	'Preferences'               => [ 'ترجیحات' ],
	'Prefixindex'               => [ 'ایندکس_پیشوند' ],
	'Protectedpages'            => [ 'صفحات_محافظتی' ],
	'Protectedtitles'           => [ 'عناوین_محافظتی' ],
	'Randompage'                => [ 'صفحه_تصادفی' ],
	'Randomredirect'            => [ 'غیرمستقیم_تصادفی' ],
	'Recentchanges'             => [ 'نوکین_تغییرات' ],
	'Recentchangeslinked'       => [ 'نوکین_تغییرات_لینک' ],
	'Revisiondelete'            => [ 'حذف_بازبینی' ],
	'Search'                    => [ 'گردگ' ],
	'Shortpages'                => [ 'هوردین_صفحات' ],
	'Specialpages'              => [ 'حاصین_صفحات' ],
	'Statistics'                => [ 'آمار' ],
	'Uncategorizedcategories'   => [ 'دستجات_بی_دسته' ],
	'Uncategorizedimages'       => [ 'عکسان_بی_دسته' ],
	'Uncategorizedpages'        => [ 'صفحات_بی_دسته' ],
	'Uncategorizedtemplates'    => [ 'تمپلتان_بی_دسته' ],
	'Undelete'                  => [ 'حذف_نکتن' ],
	'Unlockdb'                  => [ 'کلب_نه_کتن_دب' ],
	'Unusedcategories'          => [ 'بی_استفاده_این_دسته' ],
	'Unusedimages'              => [ 'بی_استفاده_این_عکس' ],
	'Unusedtemplates'           => [ 'تمپلتان_بی_استفاده' ],
	'Unwatchedpages'            => [ 'نه_چارتگین_صفحه' ],
	'Upload'                    => [ 'آپلود' ],
	'Userlogin'                 => [ 'ورودکاربر' ],
	'Userlogout'                => [ 'دربیگ_کاربر' ],
	'Userrights'                => [ 'حقوق_کاربر' ],
	'Version'                   => [ 'نسخه' ],
	'Wantedcategories'          => [ 'لوٹتگین_دسته' ],
	'Wantedpages'               => [ 'لوٹتگین_صفحات' ],
	'Watchlist'                 => [ 'لیست_چارگ' ],
	'Whatlinkshere'             => [ 'ای_لینکی_ادان_هست' ],
	'Withoutinterwiki'          => [ 'بی_بین_ویکی' ],
];
