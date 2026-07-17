<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Dienstleistungen Verwaltung
 *
 * @package ACP
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Services
{

    /**
     * Service Model
     *
     * @var ACP_Service_Model
     */
    private $model;

    /**
     * Category Model
     *
     * @var ACP_Category_Model
     */
    private $categoryModel;

    /**
     * Menü-Slug
     *
     * @var string
     */
    private $menu_slug = 'acp-services';

    /**
     * Konstruktor
     */
    public function __construct()
    {

        $this->model = new ACP_Service_Model();

        $this->categoryModel = new ACP_Category_Model();

        add_action(
            'admin_post_acp_save_service',
            array($this, 'saveService')
        );

        add_action(
            'admin_post_acp_delete_service',
            array($this, 'deleteService')
        );

    }

    /**
     * Kompatibilität für class-admin.php
     *
     * @return void
     */
    public function render()
    {

        $this->renderPage();

    }

    /**
     * Routing
     *
     * @return void
     */
    public function renderPage()
    {

        if (!current_user_can('manage_options')) {

            wp_die(
                __('Keine Berechtigung.', 'autoaufbereitung-chatbot-pro')
            );

        }

        $action = isset($_GET['action'])
            ? sanitize_key($_GET['action'])
            : 'list';

        switch ($action) {

            case 'new':

                $this->renderForm();

                break;

            case 'edit':

                $this->renderForm(
                    absint($_GET['id'] ?? 0)
                );

                break;

            default:

                $this->renderList();

                break;

        }

    }

    /**
     * Übersicht aller Dienstleistungen
     *
     * @return void
     */
    private function renderList()
    {

        $services = $this->model->findAll();

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline">

                <?php esc_html_e(
                    'Dienstleistungen',
                    'autoaufbereitung-chatbot-pro'
                ); ?>

            </h1>

            <a
                href="<?php echo esc_url(

                    admin_url(
                        'admin.php?page=' .
                        $this->menu_slug .
                        '&action=new'
                    )

                ); ?>"
                class="page-title-action">

                <?php esc_html_e(
                    'Neue Dienstleistung',
                    'autoaufbereitung-chatbot-pro'
                ); ?>

            </a>

            <hr class="wp-header-end">

            <?php

            if (empty($services)) {

                ?>

                <div class="notice notice-info">

                    <p>

                        <?php esc_html_e(
                            'Es wurden noch keine Dienstleistungen angelegt.',
                            'autoaufbereitung-chatbot-pro'
                        ); ?>

                    </p>

                </div>

                <?php

                return;

            }

            ?>

            <table class="widefat striped">

                <thead>

                <tr>

                    <th width="60">ID</th>

                    <th>Dienstleistung</th>

                    <th>Kategorie</th>

                    <th width="100">Aktiv</th>

                    <th width="180">Aktionen</th>

                </tr>

                </thead>

                <tbody>

                                <?php foreach ($services as $service) : ?>

                    <?php

                    $category = $this->categoryModel->find(
                        $service['category_id']
                    );

                    ?>

                    <tr>

                        <td>

                            <?php echo (int) $service['id']; ?>

                        </td>

                        <td>

                            <strong>

                                <?php echo esc_html(
                                    $service['name']
                                ); ?>

                            </strong>

                            <?php if (!empty($service['short_description'])) : ?>

                                <br>

                                <small style="color:#666;">

                                    <?php echo esc_html(
                                        $service['short_description']
                                    ); ?>

                                </small>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php

                            echo !empty($category['name'])
                                ? esc_html($category['name'])
                                : '—';

                            ?>

                        </td>

                        <td>

                            <?php if ((int) $service['active'] === 1) : ?>

                                <span
                                    style="color:#008a20;font-weight:bold;">

                                    <?php esc_html_e(
                                        'Ja',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </span>

                            <?php else : ?>

                                <span
                                    style="color:#d63638;font-weight:bold;">

                                    <?php esc_html_e(
                                        'Nein',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a
                                class="button button-small"
                                href="<?php echo esc_url(

                                    admin_url(
                                        'admin.php?page=' .
                                        $this->menu_slug .
                                        '&action=edit&id=' .
                                        (int) $service['id']
                                    )

                                ); ?>">

                                <?php esc_html_e(
                                    'Bearbeiten',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </a>

                            <a
                                class="button button-small button-link-delete"
                                href="<?php echo esc_url(

                                    wp_nonce_url(

                                        admin_url(
                                            'admin-post.php?action=acp_delete_service&id=' .
                                            (int) $service['id']
                                        ),

                                        'acp_delete_service'

                                    )

                                ); ?>"

                                onclick="return confirm(
                                '<?php echo esc_js(
                                    __('Dienstleistung wirklich löschen?', 'autoaufbereitung-chatbot-pro')
                                ); ?>'
                                );">

                                <?php esc_html_e(
                                    'Löschen',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <?php

    }

    /**
     * Formular anzeigen
     *
     * @param int $id
     * @return void
     */
    private function renderForm($id = 0)
    {

        $categories = $this->categoryModel->findAll();

        $service = array(

            'id' => 0,

            'category_id' => 0,

            'name' => '',

            'short_description' => '',

            'description' => '',

            'price_small' => 0,

            'price_compact' => 0,

            'price_medium' => 0,

            'price_large' => 0,

            'price_suv' => 0,

            'price_van' => 0,

            'duration' => 0,

            'duration_unit' => 'minutes',

            'image_mode' => 'optional',

            'image_limit' => 5,

            'booking_mode' => 'online',

            'active' => 1

        );

        if ($id > 0) {

            $result = $this->model->find($id);

            if (!empty($result)) {

                $service = $result;

            }

        }

        ?>

        <div class="wrap">

            <h1>

                <?php

                echo $id > 0
                    ? esc_html__('Dienstleistung bearbeiten', 'autoaufbereitung-chatbot-pro')
                    : esc_html__('Neue Dienstleistung', 'autoaufbereitung-chatbot-pro');

                ?>

            </h1>

            <form
                method="post"
                action="<?php echo esc_url(
                    admin_url('admin-post.php')
                ); ?>">

                <?php wp_nonce_field('acp_save_service'); ?>

                <input
                    type="hidden"
                    name="action"
                    value="acp_save_service">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo (int) $service['id']; ?>">

                <table class="form-table">

                    <tbody>

                        <tr>

                            <th scope="row">

                                <label for="category_id">

                                    <?php esc_html_e(
                                        'Kategorie',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </th>

                            <td>

                                <select
                                    id="category_id"
                                    name="category_id"
                                    required>

                                    <option value="">

                                        <?php esc_html_e(
                                            'Bitte auswählen',
                                            'autoaufbereitung-chatbot-pro'
                                        ); ?>

                                    </option>

                                    <?php foreach ($categories as $category) : ?>

                                        <option
                                            value="<?php echo (int) $category['id']; ?>"
                                            <?php selected(
                                                $service['category_id'],
                                                $category['id']
                                            ); ?>>

                                            <?php echo esc_html(
                                                $category['name']
                                            ); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </td>

                        </tr>

                        <tr>

                            <th scope="row">

                                <label for="name">

                                    <?php esc_html_e(
                                        'Dienstleistung',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </th>

                            <td>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="regular-text"
                                    maxlength="200"
                                    required
                                    value="<?php echo esc_attr($service['name']); ?>">

                            </td>

                        </tr>

                        <tr>

                            <th scope="row">

                                <label for="short_description">

                                    <?php esc_html_e(
                                        'Kurzbeschreibung',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </th>

                            <td>

                                <input
                                    type="text"
                                    id="short_description"
                                    name="short_description"
                                    class="large-text"
                                    maxlength="255"
                                    value="<?php echo esc_attr($service['short_description']); ?>">

                            </td>

                        </tr>

                        <tr>

                            <th scope="row">

                                <label for="description">

                                    <?php esc_html_e(
                                        'Beschreibung',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </th>

                            <td>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="8"
                                    class="large-text"><?php

                                    echo esc_textarea(
                                        $service['description']
                                    );

                                ?></textarea>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <h2>

                    <?php esc_html_e(
                        'Preise nach Fahrzeugklasse',
                        'autoaufbereitung-chatbot-pro'
                    ); ?>

                </h2>

                <table class="form-table">

                    <tbody>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Kleinwagen',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_small"
                                    min="0"
                                    step="0.01"
                                    value="<?php echo esc_attr($service['price_small']); ?>">

                                €

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Kompaktklasse',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_compact"
                                    min="0"
                                    step="0.01"
                                                                        value="<?php echo esc_attr($service['price_compact']); ?>">

                                €

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Mittelklasse',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_medium"
                                    min="0"
                                    step="0.01"
                                    value="<?php echo esc_attr($service['price_medium']); ?>">

                                €

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Oberklasse',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_large"
                                    min="0"
                                    step="0.01"
                                    value="<?php echo esc_attr($service['price_large']); ?>">

                                €

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'SUV',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_suv"
                                    min="0"
                                    step="0.01"
                                    value="<?php echo esc_attr($service['price_suv']); ?>">

                                €

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Van',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="price_van"
                                    min="0"
                                    step="0.01"
                                    value="<?php echo esc_attr($service['price_van']); ?>">

                                €

                            </td>

                        </tr>

                    </tbody>

                </table>

                <h2>

                    <?php esc_html_e(
                        'Weitere Einstellungen',
                        'autoaufbereitung-chatbot-pro'
                    ); ?>

                </h2>

                <table class="form-table">

                    <tbody>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Dauer',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="duration"
                                    min="0"
                                    step="1"
                                    value="<?php echo (int) $service['duration']; ?>">

                                <select name="duration_unit">

                                    <option
                                        value="minutes"
                                        <?php selected(
                                            $service['duration_unit'],
                                            'minutes'
                                        ); ?>>

                                        Minuten

                                    </option>

                                    <option
                                        value="hours"
                                        <?php selected(
                                            $service['duration_unit'],
                                            'hours'
                                        ); ?>>

                                        Stunden

                                    </option>

                                </select>

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Bild-Upload',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <select name="image_mode">

                                    <option
                                        value="optional"
                                        <?php selected(
                                            $service['image_mode'],
                                            'optional'
                                        ); ?>>

                                        Optional

                                    </option>

                                    <option
                                        value="required"
                                        <?php selected(
                                            $service['image_mode'],
                                            'required'
                                        ); ?>>

                                        Erforderlich

                                    </option>

                                    <option
                                        value="disabled"
                                        <?php selected(
                                            $service['image_mode'],
                                            'disabled'
                                        ); ?>>

                                        Deaktiviert

                                    </option>

                                </select>

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Maximale Bilder',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    name="image_limit"
                                    min="1"
                                    max="20"
                                    value="<?php echo (int) $service['image_limit']; ?>">

                            </td>

                                                <tr>

                            <th>

                                <?php esc_html_e(
                                    'Buchungsmodus',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <select name="booking_mode">

                                    <option
                                        value="online"
                                        <?php selected(
                                            $service['booking_mode'],
                                            'online'
                                        ); ?>>

                                        Online buchbar

                                    </option>

                                    <option
                                        value="request"
                                        <?php selected(
                                            $service['booking_mode'],
                                            'request'
                                        ); ?>>

                                        Nur Anfrage

                                    </option>

                                    <option
                                        value="disabled"
                                        <?php selected(
                                            $service['booking_mode'],
                                            'disabled'
                                        ); ?>>

                                        Deaktiviert

                                    </option>

                                </select>

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <?php esc_html_e(
                                    'Aktiv',
                                    'autoaufbereitung-chatbot-pro'
                                ); ?>

                            </th>

                            <td>

                                <label>

                                    <input
                                        type="checkbox"
                                        name="active"
                                        value="1"
                                        <?php checked(
                                            (int) $service['active'],
                                            1
                                        ); ?>>

                                    <?php esc_html_e(
                                        'Dienstleistung aktiv',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <?php submit_button(

                    $id > 0
                        ? __('Dienstleistung aktualisieren', 'autoaufbereitung-chatbot-pro')
                        : __('Dienstleistung speichern', 'autoaufbereitung-chatbot-pro')

                ); ?>

                <a
                    href="<?php echo esc_url(
                        admin_url(
                            'admin.php?page=' .
                            $this->menu_slug
                        )
                    ); ?>"
                    class="button">

                    <?php esc_html_e(
                        'Abbrechen',
                        'autoaufbereitung-chatbot-pro'
                    ); ?>

                </a>

            </form>

        </div>

        <?php

    }

    /**
 * Dienstleistung speichern
 *
 * @return void
 */
public function saveService()
{
    wp_die('saveService wurde aufgerufen');

    if (!current_user_can('manage_options')) {

        wp_die(
            __('Keine Berechtigung.', 'autoaufbereitung-chatbot-pro')
        );

    }

    check_admin_referer('acp_save_service');

    $id = isset($_POST['id']) ? absint($_POST['id']) : 0;

    $data = array(

        'category_id' => absint($_POST['category_id']),

        'name' => sanitize_text_field(
            wp_unslash($_POST['name'])
        ),

        'short_description' => sanitize_text_field(
            wp_unslash($_POST['short_description'])
        ),

        'description' => wp_kses_post(
            wp_unslash($_POST['description'])
        ),

        'price_small' => (float) $_POST['price_small'],
        'price_compact' => (float) $_POST['price_compact'],
        'price_medium' => (float) $_POST['price_medium'],
        'price_large' => (float) $_POST['price_large'],
        'price_suv' => (float) $_POST['price_suv'],
        'price_van' => (float) $_POST['price_van'],

        'duration' => absint($_POST['duration']),

        'duration_unit' => sanitize_text_field(
            wp_unslash($_POST['duration_unit'])
        ),

        'image_mode' => sanitize_text_field(
            wp_unslash($_POST['image_mode'])
        ),

        'image_limit' => absint($_POST['image_limit']),

        'booking_mode' => sanitize_text_field(
            wp_unslash($_POST['booking_mode'])
        ),

        'active' => !empty($_POST['active']) ? 1 : 0

    );

    if ($id > 0) {

        $this->model->update($id, $data);

        $message = 'updated';

    } else {

        $this->model->insert($data);

        $message = 'created';

    }

    wp_safe_redirect(

        add_query_arg(

            array(

                'page' => $this->menu_slug,

                'message' => $message

            ),

            admin_url('admin.php')

        )

    );

    exit;
}



 // <-- Diese Klammer fehlt bei dir

/**
 * Dienstleistung löschen
 *
 * @return void
 */

public function deleteService()
{
    if (!current_user_can('manage_options')) {

        wp_die(
            __('Keine Berechtigung.', 'autoaufbereitung-chatbot-pro')
        );

    }

    check_admin_referer('acp_delete_service');

    $id = isset($_GET['id'])
        ? absint($_GET['id'])
        : 0;

    if ($id > 0) {

        $this->model->delete($id);

    }

    wp_safe_redirect(

        add_query_arg(

            array(

                'page' => $this->menu_slug,

                'message' => 'deleted'

            ),

            admin_url('admin.php')

        )

    );

    exit;
}
}