<?php
/*
 * 自動更新
 * @package update_test
 * @license MIT
*/

namespace hogehoge;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // 直アクセス防止
}

class AutoUpdatePlugin {
    private $plugin_data;
    private $api_url;
    private $plugin_slug;
    private $version;

    public function __construct() {
        $basename = plugin_basename(UPDATE_TEST_PLUGIN_PATH);
        $plugin_data = get_plugin_data(UPDATE_TEST_PLUGIN_PATH);

        $this->plugin_slug = dirname( $basename );
        $this->version = $plugin_data['Version'];
        $this->api_url = $plugin_data['UpdateURI'];

        add_filter('site_transient_update_plugins', [$this, 'check_for_plugin_update']);
    }

    public function check_for_plugin_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        // Check cache
        $cache_key = 'update_test_plugin';
        $api_response = get_site_transient($cache_key);

        if ($api_response === false) {
            // Only request API if cache does not exist
            $response = wp_remote_get($this->api_url);
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $api_response = json_decode(wp_remote_retrieve_body($response), true);
                // Save cache for 24 hours
                set_site_transient($cache_key, $api_response, 24 * HOUR_IN_SECONDS);
            }
        }

        // Check update information if API response is valid
        if ($api_response) {
            if (version_compare($this->version, $api_response['version'], '<')) {
                $plugin_data = [
                    'slug'        => $this->plugin_slug,
                    'new_version' => $api_response['version'],
                    'package'     => $api_response['package'],
                ];
                $transient->response[$this->plugin_slug . '/' . $this->plugin_slug . '.php'] = (object) $plugin_data;
            }
        }
        return $transient;
    }
}

new AutoUpdatePlugin();
