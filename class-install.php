<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Installation
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Install
{
    /**
     * Plugin installieren
     */
    public static function install()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        /*
        |--------------------------------------------------------------------------
        | Tabellen
        |--------------------------------------------------------------------------
        */

        $company    = $wpdb->prefix . ACP_Config::TABLE_COMPANY;
        $categories = $wpdb->prefix . ACP_Config::TABLE_CATEGORIES;
        $services   = $wpdb->prefix . ACP_Config::TABLE_SERVICES;
        $extras     = $wpdb->prefix . ACP_Config::TABLE_EXTRAS;
        $settings   = $wpdb->prefix . ACP_Config::TABLE_SETTINGS;
        $logs       = $wpdb->prefix . ACP_Config::TABLE_LOGS;

        /*
        |--------------------------------------------------------------------------
        | Firma
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$company} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            company_name VARCHAR(200) NOT NULL,

            owner VARCHAR(150) NULL,

            street VARCHAR(200) NULL,

            zip VARCHAR(20) NULL,

            city VARCHAR(100) NULL,

            country VARCHAR(100) NULL,

            phone VARCHAR(50) NULL,

            mobile VARCHAR(50) NULL,

            whatsapp VARCHAR(50) NULL,

            email VARCHAR(150) NULL,

            website VARCHAR(200) NULL,

            logo VARCHAR(255) NULL,

            primary_color VARCHAR(20) DEFAULT '#2271b1',

            secondary_color VARCHAR(20) DEFAULT '#135e96',

            opening_hours LONGTEXT NULL,

            active TINYINT(1) DEFAULT 1,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            updated_at DATETIME NULL,

            PRIMARY KEY (id)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Kategorien
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$categories} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(150) NOT NULL,

            sort_order INT DEFAULT 0,

            active TINYINT(1) DEFAULT 1,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            updated_at DATETIME NULL,

            PRIMARY KEY (id)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Dienstleistungen
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$services} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            category_id BIGINT UNSIGNED NOT NULL,

            name VARCHAR(200) NOT NULL,

            short_description TEXT NULL,

            description LONGTEXT NULL,

            price_small DECIMAL(10,2) DEFAULT 0,

            price_compact DECIMAL(10,2) DEFAULT 0,

            price_medium DECIMAL(10,2) DEFAULT 0,

            price_large DECIMAL(10,2) DEFAULT 0,

            price_suv DECIMAL(10,2) DEFAULT 0,

            price_van DECIMAL(10,2) DEFAULT 0,

            duration INT DEFAULT 0,

            duration_unit VARCHAR(20) DEFAULT 'minutes',

            image_mode VARCHAR(20) DEFAULT 'optional',

            image_limit INT DEFAULT 5,

            booking_mode VARCHAR(20) DEFAULT 'online',

            active TINYINT(1) DEFAULT 1,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            updated_at DATETIME NULL,

            PRIMARY KEY (id)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Zusatzleistungen
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$extras} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            name VARCHAR(150) NOT NULL,

            price DECIMAL(10,2) DEFAULT 0,

            duration INT DEFAULT 0,

            active TINYINT(1) DEFAULT 1,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            updated_at DATETIME NULL,

            PRIMARY KEY (id)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Einstellungen
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$settings} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            setting_key VARCHAR(150) NOT NULL,

            setting_value LONGTEXT NULL,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            updated_at DATETIME NULL,

            PRIMARY KEY (id),

            UNIQUE KEY setting_key (setting_key)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Systemprotokoll
        |--------------------------------------------------------------------------
        */

        dbDelta("CREATE TABLE {$logs} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            level VARCHAR(20) DEFAULT 'info',

            module VARCHAR(100) NULL,

            message TEXT NULL,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            PRIMARY KEY (id)

        ) {$charset};");

        /*
        |--------------------------------------------------------------------------
        | Datenbankversion speichern
        |--------------------------------------------------------------------------
        */

        update_option(
            'acp_db_version',
            ACP_Config::DB_VERSION
        );
    }
}