<?php
/**
 * Autoaufbereitung Chatbot Pro
 * UI-Komponenten
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_UI {

    /**
     * Seitenkopf
     */
    public static function page_header($title, $subtitle = '') {

        echo '<div class="acp-page-header">';
        echo '<h1>' . esc_html($title) . '</h1>';

        if (!empty($subtitle)) {
            echo '<p>' . esc_html($subtitle) . '</p>';
        }

        echo '</div>';
    }

    /**
     * Kartenbeginn
     */
    public static function card_start($title = '') {

        echo '<div class="acp-card">';

        if ($title) {
            echo '<h2>' . esc_html($title) . '</h2>';
        }
    }

    /**
     * Kartenende
     */
    public static function card_end() {

        echo '</div>';

    }

    /**
     * Primärer Button
     */
    public static function button($text, $class = 'button button-primary') {

        echo '<button type="submit" class="' . esc_attr($class) . '">';
        echo esc_html($text);
        echo '</button>';

    }

    /**
     * Abschnitt
     */
    public static function section($title) {

        echo '<div class="acp-section">';
        echo '<h3>' . esc_html($title) . '</h3>';

    }

    public static function section_end() {

        echo '</div>';

    }

}