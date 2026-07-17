<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Zusatzleistungen
 */

if (!defined('ABSPATH')) {
    exit;
}


class ACP_Extras {

    private $option_name = 'acp_extras';

    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings() {
        register_setting(
            'acp_extras_group',
            $this->option_name
        );
    }

    public function get_extras() {
        return get_option($this->option_name, array());
    }

    public function render() {

        $extras = $this->get_extras();
        ?>

        <div class="wrap">

            <h1>Zusatzleistungen</h1>

            <form method="post" action="options.php">

                <?php settings_fields('acp_extras_group'); ?>

                <table class="widefat striped">

                    <thead>
                        <tr>
                            <th>Aktiv</th>
                            <th>Name</th>
                            <th>Preis</th>
                            <th>Dauer (Min.)</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($extras as $i => $extra) : ?>

                        <tr>

                            <td>
                                <input type="checkbox"
                                    name="acp_extras[<?php echo $i; ?>][active]"
                                    value="1"
                                    <?php checked(!empty($extra['active'])); ?>>
                            </td>

                            <td>
                                <input class="regular-text"
                                    name="acp_extras[<?php echo $i; ?>][name]"
                                    value="<?php echo esc_attr($extra['name'] ?? ''); ?>">
                            </td>

                            <td>
                                <input type="number"
                                    step="0.01"
                                    name="acp_extras[<?php echo $i; ?>][price]"
                                    value="<?php echo esc_attr($extra['price'] ?? 0); ?>">
                            </td>

                            <td>
                                <input type="number"
                                    name="acp_extras[<?php echo $i; ?>][duration]"
                                    value="<?php echo esc_attr($extra['duration'] ?? 0); ?>">
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

                <p>
                    <button type="button"
                        class="button button-primary"
                        id="acp-add-extra">
                        + Zusatzleistung hinzufügen
                    </button>
                </p>

                <?php submit_button(); ?>

            </form>

        </div>

        <?php
    }

}

new ACP_Extras();