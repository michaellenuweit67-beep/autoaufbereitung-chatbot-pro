<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Dashboard {

    public function render() {

        $services = get_option('acp_services', array());
        $extras   = get_option('acp_extras', array());
        $cats     = get_option('acp_categories', array());

        // Sicherheit
        $services = is_array($services) ? count($services) : 0;
        $extras   = is_array($extras) ? count($extras) : 0;
        $cats     = is_array($cats) ? count($cats) : 0;

        ?>

        <div class="wrap">

            <h1>?? Autoaufbereitung Chatbot Pro</h1>

            <p>Willkommen im Dashboard.</p>

            <div class="acp-dashboard">

                <div class="acp-card">
                    <h2>Kategorien</h2>
                    <strong><?php echo esc_html($cats); ?></strong>
                </div>

                <div class="acp-card">
                    <h2>Dienstleistungen</h2>
                    <strong><?php echo esc_html($services); ?></strong>
                </div>

                <div class="acp-card">
                    <h2>Zusatzleistungen</h2>
                    <strong><?php echo esc_html($extras); ?></strong>
                </div>

            </div>

        </div>

        <?php
    }

}

new ACP_Dashboard();