<?php

/**
 * Autoaufbereitung Chatbot Pro
 * Admin-Menü
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'register_menu'));
    }

    /**
     * Backend-Menü
     */
    public function register_menu() {

        add_menu_page(
            'Autoaufbereitung Chatbot Pro',
            'Autoaufbereitung Chatbot',
            'manage_options',
            'autoaufbereitung-chatbot-pro',
            array($this, 'dashboard'),
            'dashicons-format-chat',
            58
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'autoaufbereitung-chatbot-pro',
            array($this, 'dashboard')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Kategorien',
            'Kategorien',
            'manage_options',
            'acp-categories',
            array($this, 'categories')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Dienstleistungen',
            'Dienstleistungen',
            'manage_options',
            'acp-services',
            array($this, 'services')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Zusatzleistungen',
            'Zusatzleistungen',
            'manage_options',
            'acp-extras',
            array($this, 'extras')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Kalender',
            'Kalender',
            'manage_options',
            'acp-calendar',
            array($this, 'calendar')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Anfragen',
            'Anfragen',
            'manage_options',
            'acp-requests',
            array($this, 'requests')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'Einstellungen',
            'Einstellungen',
            'manage_options',
            'acp-settings',
            array($this, 'settings')
        );

        add_submenu_page(
            'autoaufbereitung-chatbot-pro',
            'System',
            'System',
            'manage_options',
            'acp-system',
            array($this, 'system')
        );
    }

    public function dashboard() {
        (new ACP_Dashboard())->render();
    }

    public function categories() {
        (new ACP_Categories())->render();
    }

    public function services() {
        (new ACP_Services())->render();
    }

    public function extras() {
        (new ACP_Extras())->render();
    }

    public function calendar() {
        echo '<div class="wrap"><h1>Kalender</h1><p>Modul folgt.</p></div>';
    }

    public function requests() {
        echo '<div class="wrap"><h1>Kundenanfragen</h1><p>Modul folgt.</p></div>';
    }

    public function settings() {
        echo '<div class="wrap"><h1>Einstellungen</h1><p>Modul folgt.</p></div>';
    }

    public function system() {
        echo '<div class="wrap"><h1>System</h1><p>Modul folgt.</p></div>';
    }
}

new ACP_Admin();