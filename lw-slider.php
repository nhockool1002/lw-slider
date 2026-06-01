<?php
/**
 * Plugin Name:       LWSlider - Lightweight BS5 Slider
 * Plugin URI:        https://codecanyon.net/user/nhutnguyen
 * Description:       A lightweight, fast, and highly customizable Bootstrap 5 slider. Supports Touch Swipe, Animate.css, Video, and unique 3D effects.
 * Version:           0.9.00
 * Author:            Nhut Nguyen
 * Author URI:        mailto:nhut.nguyenminh.it@gmail.com
 * Text Domain:       lw-slider
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// 1. Define Constants (Prefix: VSNN_2612)
define( 'VSNN_2612_VERSION', '0.9.00' );
define( 'VSNN_2612_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VSNN_2612_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VSNN_2612_TEXT_DOMAIN', 'lw-slider' );

// 2. Autoload Classes
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