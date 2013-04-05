<?php

//----------------------------------------------------
//
// 子テーマに書いて行くのめんどいので
// ここに書いて読み込みできたらいい感じかな
//
//----------------------------------------------------


/**
 * ログイン画面のロゴ画像を変更。
 */
function custom_login_logo() {
    echo '<style type="text/css">h1 a { background: url('.get_bloginfo('template_directory').'/images/logo-login.png) 50% 50% no-repeat !important; }</style>';
}
add_action('login_head', 'custom_login_logo');


/**
 * バージョン更新を非表示にする
 * バージョンチェックの通信をさせない
 */
//第二引数にセット。
add_filter('pre_site_transient_update_core', '__return_zero');
remove_action('wp_version_check', 'wp_version_check');
remove_action('admin_init', '_maybe_update_core');


/**
 * 管理画面フッター関連。
 */
add_filter('admin_footer_text', function(){return 'ご利用ありがとうございます。';});
add_filter('update_footer', function(){return '';}, 20);


/**
 * 管理バーのメニュー非表示。
 * @param unknown $wp_admin_bar
 */
function remove_admin_bar_menu($wp_admin_bar) {
    $wp_admin_bar->remove_menu('wp-logo');     // WordPressシンボルマーク
    $wp_admin_bar->remove_menu('comments');    // コメント
    $wp_admin_bar->remove_menu('new-content'); // 新規
    $wp_admin_bar->remove_menu('new-post');    // 新規 -> 投稿
    $wp_admin_bar->remove_menu('new-media');   // 新規 -> メディア
    $wp_admin_bar->remove_menu('new-link');    // 新規 -> リンク
    $wp_admin_bar->remove_menu('new-page');    // 新規 -> 固定ページ
    $wp_admin_bar->remove_menu('new-user');    // 新規 -> ユーザー
    $wp_admin_bar->remove_menu('updates');     // 更新
}
add_action('admin_bar_menu', 'remove_admin_bar_menu', 100);


/**
 * 管理バーのヘルプメニューを非表示にする
 */
function my_admin_head() {
    echo '<style type="text/css">#contextual-help-link-wrap{display:none;}</style>';
}
add_action('admin_head', 'my_admin_head');


/**
 * プロフィールからいならい項目をなくす。
 */
function hide_profile_fields( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);
    return $contactmethods;
}
add_filter('user_contactmethods','hide_profile_fields');


/**
 * サイドバーのいらない項目非表示。
 */
if (!current_user_can('edit_users')) {
    function remove_menus () {
        global $menu;
        $restricted = array(
                __('メディア'),
                __('ツール'),
                __('お問い合わせ')
        );
        end ($menu);
        while (prev($menu)){
            $value = explode(' ',$menu[key($menu)][0]);
            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
                unset($menu[key($menu)]);
            }
        }
    }
    add_action('admin_menu', 'remove_menus');
}


/**
 * ダッシュボード
 */
function example_dashboard_widget_function() {
    echo "お知らせを表示します。";
}
function example_add_dashboard_widgets() {
    wp_add_dashboard_widget('example_dashboard_widget', 'CLUB-A-TRUSTからのお知らせ', 'example_dashboard_widget_function');
    global $wp_meta_boxes;
    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
    $example_widget_backup = array('example_dashboard_widget' => $normal_dashboard['example_dashboard_widget']);
    unset($normal_dashboard['example_dashboard_widget']);
    $sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );

function my_dashbord() {
    global $wp_meta_boxes;
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
}
add_action('wp_dashboard_setup', 'my_dashbord');

// ようこそパネル。
function hide_welcome_panel() {
    $user_id = get_current_user_id();
    if ( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) ) {
        update_user_meta( $user_id, 'show_welcome_panel', 0 );
    }
}
add_action( 'load-index.php', 'hide_welcome_panel');

