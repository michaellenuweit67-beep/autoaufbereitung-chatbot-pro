<?php
/*
Plugin Name: Autoaufbereitung Chatbot Pro
Description: Professionelles Anfrage-, Termin- und Kundenmanagement für Fahrzeugaufbereiter mit Preisrechner, Google-Kalender, WhatsApp, Bilder-Upload und Zusatzleistungen.
Version: 9.0.7
Author: LP4YOU
Author URI: https://www.lp4you.de
Text Domain: autoaufbereitung-chatbot-pro


*/

/**
 * Klassen laden
 */


require_once plugin_dir_path(__FILE__) . 'includes/class-loader.php';


require_once plugin_dir_path(__FILE__) . 'includes/class-install.php';

register_activation_hook(
    __FILE__,
    ['ACP_Install', 'install']
);