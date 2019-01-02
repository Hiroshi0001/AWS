<?php
/**** 項目表示・非表示 ****/

/**
 * ユーザー一覧の名前を表示名に変更します。(列の内部名)
 */
function display_name_users_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $k => $v ) {
		if ( 'name' == $k ) $new_columns['display_name'] = $v;
		else $new_columns[$k] = $v;
	}
	return $new_columns;
}
add_filter( 'manage_users_columns', 'display_name_users_column' );
/**
 * ユーザー一覧の名前を表示名に変更します。(値)
 */
function display_name_users_custom_column( $output, $column_name, $user_id ) {
	if ( 'display_name' == $column_name ) {
		$user = get_userdata($user_id);
		return $user->display_name;
	}
}
add_filter( 'manage_users_custom_column', 'display_name_users_custom_column', 10, 3 );
/**
 * ユーザー一覧の名前のソートを元のものと同じにします。
 */
function display_name_users_sortable_column( $columns ) {
	$columns['display_name'] = 'name';
	return $columns;
}
add_filter( 'manage_users_sortable_columns', 'display_name_users_sortable_column' );
/**
 * ユーザー編集(ユーザー新規追加、プロフィール含む)の名姓の項目を姓名の順にします。
 */
function lastfirst_name() {
	?><script>
		jQuery(function($){
			$('#last_name').closest('tr').after($('#first_name').closest('tr'));
		});
	</script><?php
}
add_action( 'admin_footer-user-new.php', 'lastfirst_name' );
add_action( 'admin_footer-user-edit.php', 'lastfirst_name' );
add_action( 'admin_footer-profile.php', 'lastfirst_name' );



/**
 * 管理バーの項目を削除します。
 */
function remove_bar_menus( $wp_admin_bar ) {
    $wp_admin_bar->remove_menu( 'wp-logo' );      // ロゴ
//    $wp_admin_bar->remove_menu( 'site-name' );    // サイト名
//    $wp_admin_bar->remove_menu( 'view-site' );    // サイト名 -> サイトを表示
//    $wp_admin_bar->remove_menu( 'dashboard' );    // サイト名 -> ダッシュボード (公開側)
//    $wp_admin_bar->remove_menu( 'themes' );       // サイト名 -> テーマ (公開側)
    $wp_admin_bar->remove_menu( 'customize' );    // サイト名 -> カスタマイズ (公開側)
    $wp_admin_bar->remove_menu( 'comments' );     // コメント
    $wp_admin_bar->remove_menu( 'updates' );      // 更新
//    $wp_admin_bar->remove_menu( 'view' );         // 投稿を表示
    $wp_admin_bar->remove_menu( 'new-content' );  // 新規
    $wp_admin_bar->remove_menu( 'new-post' );     // 新規 -> 投稿
    $wp_admin_bar->remove_menu( 'new-media' );    // 新規 -> メディア
    $wp_admin_bar->remove_menu( 'new-link' );     // 新規 -> リンク
    $wp_admin_bar->remove_menu( 'new-page' );     // 新規 -> 固定ページ
    $wp_admin_bar->remove_menu( 'new-user' );     // 新規 -> ユーザー
//    $wp_admin_bar->remove_menu( 'my-account' );   // マイアカウント
//    $wp_admin_bar->remove_menu( 'user-info' );    // マイアカウント -> プロフィール
//    $wp_admin_bar->remove_menu( 'edit-profile' ); // マイアカウント -> プロフィール編集
//    $wp_admin_bar->remove_menu( 'logout' );       // マイアカウント -> ログアウト
    $wp_admin_bar->remove_menu( 'search' );       // 検索 (公開側)
}
add_action('admin_bar_menu', 'remove_bar_menus', 201);
/**
 * 「WordPressへようこそ！」の部分を独自の内容に変更します。
 */
function my_welcome_panel() { ?>
	<div class="welcome-panel-content">
		<h2>any dance association</h2>
		<p>会員管理システム</p>
	</div>
<?php }
add_action( 'welcome_panel', 'my_welcome_panel' );
remove_action( 'welcome_panel', 'wp_welcome_panel' );
/**
 * ダッシュボードウィジェットを削除します。
 */
function remove_dashboard_widget() {
	if ( ! current_user_can( 'administrator' ) ) {
	 	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // 概要
//	 	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // アクティビティ
	 	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // クイックドラフト
	 	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPressニュース
 	}
} 
add_action('wp_dashboard_setup', 'remove_dashboard_widget' );
/**
 * 管理画面の「Wordpressのご利用ありがとうございます。」の文言を削除します。
 */
add_filter('admin_footer_text', '__return_empty_string');
/**
 * 管理画面下部のバージョン番号を削除します。
 */
function remove_footer_version() {
	remove_filter( 'update_footer', 'core_update_footer' ); 
}
add_action( 'admin_menu', 'remove_footer_version' );

/**
 * バージョンアップ通知を非表示にします。
 */
function update_nag_hide() {
	if ( ! current_user_can( 'administrator' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
add_action( 'admin_init', 'update_nag_hide' );

function custom_admin_menu() {
	remove_menu_page( 'index.php' ); // ダッシュボード
}
add_action( 'admin_menu', 'custom_admin_menu', 1000 );
function my_admin_style() {

global $current_user;

		$heredocs = <<< EOD
<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js"></script>
<script>
jQuery(function($){

	$(".swpm-members-nav-tab-wrapper").before("<p>表示例 Active:有効 Inactive:無効 Expired:期限切れ</p>");
	
	/*
	
	/* spwm 会員 */
	$(".toplevel_page_simple_wp_membership table.wp-list-table tbody tr").each(function(o){
		$(this).find("td").eq(2).before($(this).find("td").eq(3));
	});
	$("#swpm-create-user > table > tbody > tr:nth-child(7)")
		.before($("#swpm-create-user > table > tbody > tr:nth-child(8)"));
	$("#swpm-edit-user > table > tbody > tr:nth-child(8)")
		.before($("#swpm-edit-user > table > tbody > tr:nth-child(9)"));
	$("input[name=company_name]").parent().parent().find("th").html("メール送信の可否");

});
</script>
<style>
EOD;

/* 編集者以下の権限で非表示 */
if ( $current_user->ID != "1"  ) {
$heredocs .= <<< EOD
#menu-posts-event > ul > li:nth-child(9),
#menu-posts-event > ul > li:nth-child(10),
#toplevel_page_fullstripe-settings,
#menu-posts,
#menu-comments,
#menu-pages,

#menu-posts-event > ul > li:nth-child(3),
#toplevel_page_simple_wp_membership > ul > li:nth-child(3),
#toplevel_page_simple_wp_membership > ul > li:nth-child(4),
#toplevel_page_simple_wp_membership > ul > li:nth-child(5),
#toplevel_page_simple_wp_membership > ul > li:nth-child(6),
EOD;
}
if ( ! current_user_can( 'editor' ) ) {
$heredocs .= <<< EOD
EOD;
if ( ! current_user_can( 'author' ) ) {
$heredocs .= <<< EOD
#dbem-bookings-table > thead > tr > th:nth-child(6),
#dbem-bookings-table > tbody > tr > td:nth-child(6),
EOD;
}
}
	echo $heredocs."
#commentsdiv,
#commentstatusdiv,
#postimagediv,
#postexcerpt,
#slugdiv,
#advanced-sortables,

.event-rsvp-options-tickets h4,
.ticket-name,
.ticket-description,
.ticket-price,
.ticket-spaces,
.ticket-dates-from,
.ticket-dates-to,
.ticket-type,
.ticket-options,

#em-bookings-table-settings-trigger,

#dbem-bookings-table > thead > tr > th:nth-child(3),
#dbem-bookings-table > thead > tr > th:nth-child(5),
#dbem-bookings-table > tbody > tr > td:nth-child(3),
#dbem-bookings-table > tbody > tr > td:nth-child(5),

.em-duration-range,
.em-range-description,

.em-location-data-state,
.em-location-data-postcode,
.em-location-data-region,

#swpm-edit-user > table > tbody > tr:nth-child(12),
#swpm-edit-user > table > tbody > tr:nth-child(13),
#swpm-edit-user > table > tbody > tr:nth-child(14),
#swpm-edit-user > table > tbody > tr:nth-child(15),
#swpm-edit-user > table > tbody > tr:nth-child(16),


#swpm-create-user > table > tbody > tr:nth-child(11),
#swpm-create-user > table > tbody > tr:nth-child(12),
#swpm-create-user > table > tbody > tr:nth-child(13),
#swpm-create-user > table > tbody > tr:nth-child(14),
#swpm-create-user > table > tbody > tr:nth-child(15),


.swpm-admin-menu-wrap div.error#message
{
	display: none;
}
</style>" . PHP_EOL;
}
add_action('admin_print_styles', 'my_admin_style');
function remove_menus () {
	global $current_user;
	if ( $current_user->ID != 1) {
	    remove_menu_page( 'index.php' );                  // ダッシュボード
	//    remove_menu_page( 'edit.php' );                   // 投稿
	    remove_menu_page( 'upload.php' );                 // メディア
	    remove_menu_page( 'edit.php?post_type=page' );    // 固定ページ
	    remove_menu_page( 'edit-comments.php' );          // コメント
	    remove_menu_page( 'themes.php' );                 // 外観
	    remove_menu_page( 'plugins.php' );                // プラグイン
//	    remove_menu_page( 'users.php' );                  // ユーザー
	    remove_menu_page( 'tools.php' );                  // ツール
	    remove_menu_page( 'options-general.php' );  
	}
}
add_action('admin_menu', 'remove_menus');

