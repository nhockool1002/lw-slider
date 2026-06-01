<?php
/**
 * Plugin Name:       LWSlider - Lightweight BS5 Slider
 * Plugin URI:        https://codecanyon.net/user/nhutnguyen
 * Description:       A lightweight, fast, and highly customizable Bootstrap 5 slider. Supports Touch Swipe, Animate.css, Video, and unique 3D effects.
 * Version:           0.0.1e
 * Author:            Nhut Nguyen
 * Author URI:        mailto:nhut.nguyenminh.it@gmail.com
 * Text Domain:       lw-slider
 * Domain Path:       /languages
 * Update URI:        https://github.com/nhockool1002/lw-slider
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// 1. Define Constants (Prefix: VSNN_2612)
define( 'VSNN_2612_VERSION', '0.0.1e' );
define( 'VSNN_2612_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VSNN_2612_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VSNN_2612_TEXT_DOMAIN', 'lw-slider' );
define( 'VSNN_2612_SLUG', 'lw-slider' );
define( 'VSNN_2612_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'VSNN_2612_GITHUB_REPO', 'nhockool1002/lw-slider' );
define( 'VSNN_2612_GITHUB_URL', 'https://github.com/' . VSNN_2612_GITHUB_REPO );
define( 'VSNN_2612_GITHUB_API', 'https://api.github.com/repos/' . VSNN_2612_GITHUB_REPO . '/releases/latest' );
define( 'VSNN_2612_GITHUB_CACHE_KEY', 'vsnn_2612_github_release' );

// 2. Autoload Classes
require_once VSNN_2612_PLUGIN_DIR . 'class-vsnn-2612-filters.php';
require_once VSNN_2612_PLUGIN_DIR . 'admin/class-vsnn-2612-admin.php';
require_once VSNN_2612_PLUGIN_DIR . 'public/class-vsnn-2612-public.php';

// 3. Initialize Plugin
function vsnn_2612_init() {
    // Load Admin Logic
    if ( is_admin() ) {
        $plugin_admin = new VSNN_2612_Admin();
        $plugin_admin->run();
    }

    // Load Public Logic
    $plugin_public = new VSNN_2612_Public();
    $plugin_public->run();
}
add_action( 'plugins_loaded', 'vsnn_2612_init' );

// 4. Load Text Domain (i18n)
function vsnn_2612_load_textdomain() {
    load_plugin_textdomain( 'lw-slider', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'vsnn_2612_load_textdomain' );

/**
 * 5. GitHub Auto Update
 *
 * Uses the latest GitHub Release tag as the available plugin version.
 * Create release tags higher than VSNN_2612_VERSION to show updates in WP Admin.
 */
function vsnn_2612_normalize_github_version( $tag_name ) {
    return preg_replace( '/^v/i', '', trim( (string) $tag_name ) );
}

function vsnn_2612_normalize_version_for_compare( $version ) {
    $version = strtolower( vsnn_2612_normalize_github_version( $version ) );

    if ( preg_match( '/^(\d+(?:\.\d+)*)([a-z])$/', $version, $matches ) ) {
        return $matches[1] . '.' . ( ord( $matches[2] ) - 96 );
    }

    return $version;
}

function vsnn_2612_is_newer_version( $remote_version, $current_version ) {
    return version_compare(
        vsnn_2612_normalize_version_for_compare( $remote_version ),
        vsnn_2612_normalize_version_for_compare( $current_version ),
        '>'
    );
}

function vsnn_2612_get_github_release( $force_refresh = false ) {
    if ( $force_refresh ) {
        delete_site_transient( VSNN_2612_GITHUB_CACHE_KEY );
    }

    $cached = get_site_transient( VSNN_2612_GITHUB_CACHE_KEY );

    if ( false !== $cached && ! $force_refresh ) {
        return $cached;
    }

    $response = wp_remote_get(
        VSNN_2612_GITHUB_API,
        array(
            'timeout' => 10,
            'headers' => array(
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'LWSlider-WordPress-Updater',
            ),
        )
    );

    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        return false;
    }

    $release = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( empty( $release['tag_name'] ) ) {
        return false;
    }

    $release['version'] = vsnn_2612_normalize_github_version( $release['tag_name'] );
    $release['package'] = ! empty( $release['zipball_url'] ) ? $release['zipball_url'] : '';

    if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
        foreach ( $release['assets'] as $asset ) {
            if ( ! empty( $asset['browser_download_url'] ) && preg_match( '/\.zip$/i', $asset['browser_download_url'] ) ) {
                $release['package'] = $asset['browser_download_url'];
                break;
            }
        }
    }

    if ( empty( $release['package'] ) ) {
        return false;
    }

    set_site_transient( VSNN_2612_GITHUB_CACHE_KEY, $release, 15 * MINUTE_IN_SECONDS );

    return $release;
}

function vsnn_2612_get_update_data( $release ) {
    return (object) array(
        'id'            => VSNN_2612_GITHUB_URL,
        'slug'          => VSNN_2612_SLUG,
        'plugin'        => VSNN_2612_PLUGIN_BASENAME,
        'new_version'   => $release['version'],
        'url'           => VSNN_2612_GITHUB_URL,
        'package'       => $release['package'],
        'icons'         => array(),
        'banners'       => array(),
        'banners_rtl'   => array(),
        'tested'        => '',
        'requires_php'  => '',
        'compatibility' => new stdClass(),
    );
}

function vsnn_2612_check_github_update( $transient ) {
    if ( empty( $transient->checked ) || empty( $transient->checked[ VSNN_2612_PLUGIN_BASENAME ] ) ) {
        return $transient;
    }

    $force_refresh = ! empty( $_GET['force-check'] ) || ! empty( $_GET['vsnn_2612_check_update'] );
    $release = vsnn_2612_get_github_release( $force_refresh );

    if ( ! $release || empty( $release['version'] ) ) {
        return $transient;
    }

    $update_data = vsnn_2612_get_update_data( $release );

    if ( vsnn_2612_is_newer_version( $release['version'], $transient->checked[ VSNN_2612_PLUGIN_BASENAME ] ) ) {
        $transient->response[ VSNN_2612_PLUGIN_BASENAME ] = $update_data;
    } else {
        $transient->no_update[ VSNN_2612_PLUGIN_BASENAME ] = $update_data;
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'vsnn_2612_check_github_update' );

function vsnn_2612_clear_update_cache() {
    if ( empty( $_GET['vsnn_2612_check_update'] ) || empty( $_GET['_wpnonce'] ) ) {
        return;
    }

    if ( ! current_user_can( 'update_plugins' ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'vsnn_2612_check_update' ) ) {
        return;
    }

    delete_site_transient( VSNN_2612_GITHUB_CACHE_KEY );
    delete_site_transient( 'update_plugins' );

    wp_safe_redirect( remove_query_arg( array( 'vsnn_2612_check_update', '_wpnonce' ) ) );
    exit;
}
add_action( 'admin_init', 'vsnn_2612_clear_update_cache' );

function vsnn_2612_add_update_check_link( $links ) {
    if ( ! current_user_can( 'update_plugins' ) ) {
        return $links;
    }

    $url = wp_nonce_url(
        add_query_arg( 'vsnn_2612_check_update', '1', admin_url( 'plugins.php' ) ),
        'vsnn_2612_check_update'
    );

    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Check for updates', 'lw-slider' ) . '</a>';

    return $links;
}
add_filter( 'plugin_action_links_' . VSNN_2612_PLUGIN_BASENAME, 'vsnn_2612_add_update_check_link' );

function vsnn_2612_github_plugin_info( $result, $action, $args ) {
    if ( 'plugin_information' !== $action || empty( $args->slug ) || VSNN_2612_SLUG !== $args->slug ) {
        return $result;
    }

    $release = vsnn_2612_get_github_release();

    if ( ! $release ) {
        return $result;
    }

    $description = ! empty( $release['body'] ) ? wp_kses_post( wpautop( esc_html( $release['body'] ) ) ) : 'Release notes are available on GitHub.';

    return (object) array(
        'name'          => 'LWSlider - Lightweight BS5 Slider',
        'slug'          => VSNN_2612_SLUG,
        'version'       => $release['version'],
        'author'        => '<a href="mailto:nhut.nguyenminh.it@gmail.com">Nhut Nguyen</a>',
        'homepage'      => VSNN_2612_GITHUB_URL,
        'download_link' => $release['package'],
        'sections'      => array(
            'description' => 'A lightweight, fast, and highly customizable Bootstrap 5 slider.',
            'changelog'   => $description,
        ),
    );
}
add_filter( 'plugins_api', 'vsnn_2612_github_plugin_info', 10, 3 );

function vsnn_2612_fix_github_zip_folder_name( $source, $remote_source, $upgrader, $hook_extra = null ) {
    if ( empty( $hook_extra['plugin'] ) || VSNN_2612_PLUGIN_BASENAME !== $hook_extra['plugin'] ) {
        return $source;
    }

    global $wp_filesystem;

    if ( ! $wp_filesystem ) {
        return $source;
    }

    $corrected_source = trailingslashit( $remote_source ) . VSNN_2612_SLUG;

    if ( untrailingslashit( $source ) === untrailingslashit( $corrected_source ) ) {
        return $source;
    }

    if ( $wp_filesystem->exists( $corrected_source ) ) {
        $wp_filesystem->delete( $corrected_source, true );
    }

    if ( $wp_filesystem->move( $source, $corrected_source, true ) ) {
        return trailingslashit( $corrected_source );
    }

    return $source;
}
add_filter( 'upgrader_source_selection', 'vsnn_2612_fix_github_zip_folder_name', 10, 4 );



// --- THÊM VÀO CUỐI FILE lw-slider.php ---

/**
 * 1. Enqueue Scripts cho Editor (Gutenberg & Classic)
 */
function vsnn_2612_enqueue_editor_scripts() {
    // Chỉ load khi đang ở trang edit post/page
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->base, array( 'post', 'page' ) ) ) {
        return;
    }

    // Load file JS xử lý editor
    wp_enqueue_script( 
        'lw-slider-editor-js', 
        VSNN_2612_PLUGIN_URL . 'admin/js/lw-slider-editor.js', 
        array( 'jquery', 'wp-blocks', 'wp-element', 'wp-rich-text', 'wp-editor' ), // Dependencies cho Gutenberg
        VSNN_2612_VERSION, 
        true 
    );
}
add_action( 'admin_enqueue_scripts', 'vsnn_2612_enqueue_editor_scripts' );

/**
 * 2. Đăng ký nút cho TinyMCE (Classic Editor)
 */
function vsnn_2612_add_tinymce_button() {
    // Kiểm tra quyền
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    // Kiểm tra Rich Editing có bật không
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'vsnn_2612_add_tinymce_plugin' );
        add_filter( 'mce_buttons', 'vsnn_2612_register_tinymce_button' );
    }
}
add_action( 'admin_head', 'vsnn_2612_add_tinymce_button' );

function vsnn_2612_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['lw_slider_button'] = VSNN_2612_PLUGIN_URL . 'admin/js/lw-slider-editor.js';
    return $plugin_array;
}

function vsnn_2612_register_tinymce_button( $buttons ) {
    array_push( $buttons, 'lw_slider_btn' );
    return $buttons;
}

/**
 * 3. AJAX Handler: Lấy danh sách Slider
 */
function vsnn_2612_ajax_get_sliders() {
    // Check permission (optional but recommended)
    // if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error();

    $args = array(
        'post_type'      => 'lw_slider',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC'
    );

    $sliders = get_posts( $args );
    $data = array();

    foreach ( $sliders as $s ) {
        $data[] = array(
            'ID'         => $s->ID,
            'post_title' => $s->post_title ? $s->post_title : '(No Title - ID: ' . $s->ID . ')'
        );
    }

    wp_send_json_success( $data );
}
add_action( 'wp_ajax_lw_get_sliders_list', 'vsnn_2612_ajax_get_sliders' );

/**
 * 4. Fix Icon hiển thị trên TinyMCE Toolbar
 */
function vsnn_2612_add_admin_editor_styles() {
    // Chỉ load khi user có quyền
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    ?>
    <style>
        /* Target class mà chúng ta đã đặt tên trong file JS: .mce-i-[tên-icon] */
        .mce-i-lw-slider-toolbar-icon:before {
            content: "\f233"; /* Mã unicode của dashicons-images-alt2 */
            font-family: dashicons;
            display: inline-block;
            -webkit-font-smoothing: antialiased;
            font-size: 20px; /* Kích thước chuẩn */
            vertical-align: top;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'vsnn_2612_add_admin_editor_styles' );