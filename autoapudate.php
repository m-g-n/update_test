<?php
/*
Plugin Name: Auto Apudate
Description: 自動アップデート用のサンプルプラグイン。
Version: 1.0.1
Author: Your Name
*/

namespace hogehoge;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // 直アクセス防止
}

class Auto_Apudate_Plugin {
    /**
     * GitHub Pages上のJSONファイルを使った自動アップデート対応
     */
    public static function github_pages_updater( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }
        $plugin_slug = plugin_basename( __FILE__ );
        // ここを実際のGitHub PagesのURLに変更してください
        $json_url = 'https://yourname.github.io/autoapudate/update.json';
        $response = wp_remote_get( $json_url );
        if ( is_wp_error( $response ) ) {
            return $transient;
        }
        $data = json_decode( wp_remote_retrieve_body( $response ) );
        if ( ! isset( $data->version ) || ! isset( $data->package ) ) {
            return $transient;
        }
        $new_version = $data->version;
        $current_version = get_plugin_data( __FILE__ )['Version'];
        if ( version_compare( $current_version, $new_version, '>=' ) ) {
            return $transient;
        }
        $transient->response[ $plugin_slug ] = (object) [
            'slug'        => dirname( $plugin_slug ),
            'plugin'      => $plugin_slug,
            'new_version' => $new_version,
            'url'         => isset( $data->url ) ? $data->url : '',
            'package'     => $data->package,
        ];
        return $transient;
    }
}

remove_filter( 'site_transient_update_plugins', [ __NAMESPACE__ . '\\Auto_Apudate_Plugin', 'github_updater' ] );
add_filter( 'site_transient_update_plugins', [ __NAMESPACE__ . '\\Auto_Apudate_Plugin', 'github_pages_updater' ] );
