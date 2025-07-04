<?php
/*
Plugin Name: Update Test
Description: プラグインの動作テスト用の最小構成。
Version: 1.0.22
Author: kutsu123
Update URI: https://m-g-n.github.io/update_test/update.json
*/

namespace hogehoge;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // 直アクセス防止
}

define('UPDATE_TEST_MAIN_LOADED', true);
define('UPDATE_TEST_PLUGIN_PATH', __FILE__);
define('UPDATE_TEST_PLUGIN_BASENAME', plugin_basename(UPDATE_TEST_PLUGIN_PATH));

require_once __DIR__ . '/autoupdate.php';



if ( ! class_exists( '\hogehoge\Update_Test_Plugin' ) ) {
    class Update_Test_Plugin {
        public static function activate() {
            // 必要ならここに初期化コードを書く
        }
        public static function deactivate() {
            // 必要ならここにクリーンアップコードを書く
        }
        public static function admin_notice() {
            echo '<p>Update Test プラグインが有効です。</p>';
        }
    }

    register_activation_hook( __FILE__, array( '\hogehoge\Update_Test_Plugin', 'activate' ) );
    register_deactivation_hook( __FILE__, array( '\hogehoge\Update_Test_Plugin', 'deactivate' ) );
    add_action( 'admin_notices', array( '\hogehoge\Update_Test_Plugin', 'admin_notice' ) );
}
