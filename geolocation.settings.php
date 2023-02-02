<?php

function languages_init()
{
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages/'; /* Relative to WP_PLUGIN_DIR */
    load_plugin_textdomain('geolocation', 'false', $plugin_rel_path);
}

function getSiteLang()
{
    $language = substr(get_locale(), 0, 2);
    return $language;
}

function register_settings()
{
    register_setting('geolocation-settings-group', 'geolocation_map_width');
    register_setting('geolocation-settings-group', 'geolocation_map_height');
    register_setting('geolocation-settings-group', 'geolocation_default_zoom');
    register_setting('geolocation-settings-group', 'geolocation_map_position');
    register_setting('geolocation-settings-group', 'geolocation_map_display');
    register_setting('geolocation-settings-group', 'geolocation_wp_pin');
    register_setting('geolocation-settings-group', 'geolocation_google_maps_api_key');
    register_setting('geolocation-settings-group', 'geolocation_updateAddresses');
    register_setting('geolocation-settings-group', 'geolocation_map_width_page');
    register_setting('geolocation-settings-group', 'geolocation_map_height_page');
    register_setting('geolocation-settings-group', 'geolocation_provider');
    register_setting('geolocation-settings-group', 'geolocation_shortcode');
    register_setting('geolocation-settings-group', 'geolocation_osm_use_proxy');
    register_setting('geolocation-settings-group', 'geolocation_osm_tiles_url');
    register_setting('geolocation-settings-group', 'geolocation_osm_leaflet_js_url');
    register_setting('geolocation-settings-group', 'geolocation_osm_leaflet_css_url');
    register_setting('geolocation-settings-group', 'geolocation_osm_nominatim_url');
}

function unregister_settings()
{
    unregister_setting('geolocation-settings-group', 'geolocation_map_width');
    unregister_setting('geolocation-settings-group', 'geolocation_map_height');
    unregister_setting('geolocation-settings-group', 'geolocation_default_zoom');
    unregister_setting('geolocation-settings-group', 'geolocation_map_position');
    unregister_setting('geolocation-settings-group', 'geolocation_map_display');
    unregister_setting('geolocation-settings-group', 'geolocation_wp_pin');
    unregister_setting('geolocation-settings-group', 'geolocation_google_maps_api_key');
    unregister_setting('geolocation-settings-group', 'geolocation_updateAddresses');
    unregister_setting('geolocation-settings-group', 'geolocation_map_width_page');
    unregister_setting('geolocation-settings-group', 'geolocation_map_height_page');
    unregister_setting('geolocation-settings-group', 'geolocation_provider');
    unregister_setting('geolocation-settings-group', 'geolocation_shortcode');
    unregister_setting('geolocation-settings-group', 'geolocation_osm_use_proxy');
    unregister_setting('geolocation-settings-group', 'geolocation_osm_tiles_url');
    unregister_setting('geolocation-settings-group', 'geolocation_osm_leaflet_js_url');
    unregister_setting('geolocation-settings-group', 'geolocation_osm_leaflet_css_url');
    unregister_setting('geolocation-settings-group', 'geolocation_osm_nominatim_url');
}

function default_setting($name,$value)
{
    if (!get_option($name)) {
      update_option($name, $value);
    }
}

function default_settings()
{
    default_setting('geolocation_map_width', '450');
    default_setting('geolocation_map_height', '200');
    default_setting('geolocation_default_zoom', '16');
    default_setting('geolocation_map_position', 'after');
    default_setting('geolocation_map_display', 'link');
    update_option('geolocation_updateAddresses', false);
    default_setting('geolocation_map_width_page', '600');
    default_setting('geolocation_map_height_page', '300');
    default_setting('geolocation_provider', 'osm');
    default_setting('geolocation_shortcode', '[geolocation]');
    default_setting('geolocation_osm_use_proxy', false);
    default_setting('geolocation_osm_tiles_url', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png');
    default_setting('geolocation_osm_leaflet_js_url', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js');
    default_setting('geolocation_osm_leaflet_css_url', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.css');
    default_setting('geolocation_osm_nominatim_url', 'https://nominatim.openstreetmap.org/');
}

function delete_settings()
{
    delete_option('geolocation_map_width');
    delete_option('geolocation_map_height');
    delete_option('geolocation_default_zoom');
    delete_option('geolocation_map_position');
    delete_option('geolocation_map_display');
    delete_option('geolocation_updateAddresses');
    delete_option('geolocation_map_width_page');
    delete_option('geolocation_map_height_page');
    delete_option('geolocation_provider');
    delete_option('geolocation_shortcode');
    delete_option('geolocation_osm_use_proxy');
    delete_option('geolocation_osm_tiles_url');
    delete_option('geolocation_osm_leaflet_js_url');
    delete_option('geolocation_osm_leaflet_css_url');
    delete_option('geolocation_osm_nominatim_url');
}

function delete_addresses()
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $post_query = new WP_Query($args);
    if ($post_query->have_posts()) {
        while ($post_query->have_posts()) {
            $post_query->the_post();
        $post = get_post();
            delete_post_meta($post->ID, 'geo_address');
        }
    }
}

function activate()
{
    register_settings();
    default_settings();
}

function uninstall()
{
    unregister_settings();
    delete_settings();
    delete_addresses();
}


function add_settings()
{
    if (is_admin()) { // admin actions
        require_once(GEOLOCATION__PLUGIN_DIR.'geolocation.settings.page.php');
        add_options_page(__('Geolocation Plugin Settings', 'geolocation'), 'Geolocation', 'administrator', 'geolocation.php', 'geolocation_settings_page');
        add_action('admin_init', 'register_settings');
    }
}
