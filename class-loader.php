<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Klassen-Loader
 *
 * Version: 1.0.0
 * Entwickler: LP4YOU
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Loader {

    /**
     * Plugin-Version
     */
    private string $version = '1.0.0';

    /**
     * Konstruktor
     */
    public function __construct() {
        $this->load_classes();
    }

    /**
     * Alle Plugin-Klassen laden
     */
    private function load_classes() {

        $base = plugin_dir_path(__FILE__);




        $classes = array(

    'class-config.php',
    'class-install.php',
    'class-assets.php',
    'class-dashboard.php',
    'class-admin.php',
    'class-uni.php',
    'class-category-model.php',
    'class-service-model.php',
    'class-categories.php',
    'class-services.php',
    'class-extras.php',
    'class-calendar.php',
    'class-requests.php',
    'class-settings.php',
    'class-system.php'

);
    

        foreach ($classes as $file) {

            $path = $base . $file;

            if (file_exists($path)) {
                require_once $path;
            }

        }

    }

    /**
     * Plugin-Version
     */
    public function get_version() {
        return $this->version;
    }

}

new ACP_Loader();
new ACP_Services();
new ACP_Admin();
new ACP_Categories();
new ACP_Services();
new ACP_Extras();