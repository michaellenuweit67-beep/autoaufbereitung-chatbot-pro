<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Assets laden
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Assets {

    public function __construct() {

        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));

    }

    /**
     * Backend
     */
    public function admin_assets() {

        wp_enqueue_style(
            'acp-admin',
            plugin_dir_url(__FILE__) . '../assets/css/admin.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'acp-admin',
            plugin_dir_url(__FILE__) . '../assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

    }

    /**
     * Frontend
     */
    public function frontend_assets() {

        wp_enqueue_style(
            'acp-frontend',
            plugin_dir_url(__FILE__) . '../assets/css/frontend.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'acp-frontend',
            plugin_dir_url(__FILE__) . '../assets/js/frontend.js',
            array('jquery'),
            '1.0.0',
            true
        );

    }

}

new ACP_Assets();