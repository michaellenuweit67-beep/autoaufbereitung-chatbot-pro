<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Kategorien Verwaltung
 *
 * @package ACP
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Categories
{

    /**
     * Category Model
     *
     * @var ACP_Category_Model
     */
    private $model;

    /**
     * Menü-Slug
     *
     * @var string
     */
    private $menu_slug = 'acp-categories';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->model = new ACP_Category_Model();

        add_action(
            'admin_menu',
            array($this, 'registerMenu')
        );

        add_action(
            'admin_post_acp_save_category',
            array($this, 'saveCategory')
        );

        add_action(
            'admin_post_acp_delete_category',
            array($this, 'deleteCategory')
        );
    }


								
/**
 * Kompatibilität für Admin-Aufruf
 *
 * @return void
 */
public function render()
{
    $this->renderPage();
}





    /**
     * Admin-Menü registrieren
     *
     * @return void
     */
    public function registerMenu()
    {

        add_submenu_page(

            'acp-dashboard',

            __('Kategorien', 'autoaufbereitung-chatbot-pro'),

            __('Kategorien', 'autoaufbereitung-chatbot-pro'),

            'manage_options',

            $this->menu_slug,

            array($this, 'renderPage')

        );

    }

    /**
     * Hauptseite
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

        }

    }

    /**
     * Kategorienliste
     *
     * @return void
     */
    private function renderList()
    {

        $categories = $this->model->findAll();

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline">

                <?php esc_html_e(
                    'Kategorien',
                    'autoaufbereitung-chatbot-pro'
                ); ?>

            </h1>

            <a href="<?php echo esc_url(

                admin_url(
                    'admin.php?page=' .
                    $this->menu_slug .
                    '&action=new'
                )

            ); ?>"
               class="page-title-action">

                <?php esc_html_e(
                    'Neue Kategorie',
                    'autoaufbereitung-chatbot-pro'
                ); ?>

            </a>

            <hr class="wp-header-end">

            <?php

            if (empty($categories)) {

                ?>

                <div class="notice notice-info">

                    <p>

                        <?php esc_html_e(
                            'Es wurden noch keine Kategorien angelegt.',
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

                    <th width="70">ID</th>

                    <th>Name</th>

                    <th width="120">Sortierung</th>

                    <th width="100">Aktiv</th>

                    <th width="180">Aktionen</th>

                </tr>

                </thead>

                <tbody>

                                <?php foreach ($categories as $category) : ?>

                    <tr>

                        <td>

                            <?php echo (int) $category['id']; ?>

                        </td>

                        <td>

                            <strong>

                                <?php echo esc_html($category['name']); ?>

                            </strong>

                        </td>

                        <td>

                            <?php echo (int) $category['sort_order']; ?>

                        </td>

                        <td>

                            <?php if ((int) $category['active'] === 1) : ?>

                                <span style="color:#008a20;font-weight:bold;">

                                    <?php esc_html_e(
                                        'Ja',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </span>

                            <?php else : ?>

                                <span style="color:#d63638;font-weight:bold;">

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
                                        (int) $category['id']
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
                                            'admin-post.php?action=acp_delete_category&id=' .
                                            (int) $category['id']
                                        ),

                                        'acp_delete_category'

                                    )

                                ); ?>"

                                onclick="return confirm(
                                    '<?php echo esc_js(
                                        __('Kategorie wirklich löschen?', 'autoaufbereitung-chatbot-pro')
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
     * Formular
     *
     * @param int $id
     * @return void
     */
    private function renderForm($id = 0)
    {

        $category = array(

            'id'         => 0,
            'name'       => '',
            'sort_order' => 0,
            'active'     => 1

        );

        if ($id > 0) {

            $result = $this->model->find($id);

            if (!empty($result)) {

                $category = $result;

            }

        }

        ?>

        <div class="wrap">

            <h1>

                <?php

                echo $id > 0
                    ? esc_html__('Kategorie bearbeiten', 'autoaufbereitung-chatbot-pro')
                    : esc_html__('Neue Kategorie', 'autoaufbereitung-chatbot-pro');

                ?>

            </h1>

            <form
                method="post"
                action="<?php echo esc_url(
                    admin_url('admin-post.php')
                ); ?>">

                <?php wp_nonce_field('acp_save_category'); ?>

                <input
                    type="hidden"
                    name="action"
                    value="acp_save_category">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo (int) $category['id']; ?>">

                <table class="form-table">

                    <tbody>

                        <tr>

                            <th scope="row">

                                <label for="name">

                                    <?php esc_html_e(
                                        'Kategoriename',
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
                                    maxlength="150"
                                    required
                                    value="<?php echo esc_attr($category['name']); ?>">

                                <p class="description">

                                    <?php esc_html_e(
                                        'Beispiel: Innenreinigung, Außenreinigung oder Keramikversiegelung.',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </p>

                            </td>

                        </tr>

                        <tr>

                            <th scope="row">

                                <label for="sort_order">

                                    <?php esc_html_e(
                                        'Sortierung',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </th>

                            <td>

                                <input
                                    type="number"
                                    id="sort_order"
                                    name="sort_order"
                                    class="small-text"
                                    min="0"
                                    step="1"
                                    value="<?php echo (int) $category['sort_order']; ?>">

                                <p class="description">

                                    <?php esc_html_e(
                                        'Niedrigere Werte werden zuerst angezeigt.',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </p>

                            </td>

                        </tr>

                        <tr>

                            <th scope="row">

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
                                            (int) $category['active'],
                                            1
                                        ); ?>>

                                    <?php esc_html_e(
                                        'Kategorie aktivieren',
                                        'autoaufbereitung-chatbot-pro'
                                    ); ?>

                                </label>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <?php submit_button(

                    $id > 0
                        ? __('Kategorie aktualisieren', 'autoaufbereitung-chatbot-pro')
                        : __('Kategorie speichern', 'autoaufbereitung-chatbot-pro')

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
     * Kategorie speichern
     *
     * @return void
     */
    public function saveCategory()
    {

        if (!current_user_can('manage_options')) {

            wp_die(
                __('Keine Berechtigung.', 'autoaufbereitung-chatbot-pro')
            );

        }

        check_admin_referer('acp_save_category');

        $id = isset($_POST['id'])
            ? absint($_POST['id'])
            : 0;

        $data = array(

            'name' => isset($_POST['name'])
                ? sanitize_text_field(wp_unslash($_POST['name']))
                : '',

            'sort_order' => isset($_POST['sort_order'])
                ? absint($_POST['sort_order'])
                : 0,

            'active' => !empty($_POST['active']) ? 1 : 0

        );

        if ($id > 0) {

            $this->model->update(
                $id,
                $data
            );

            $message = 'updated';

        } else {

            $this->model->insert(
                $data
            );

            $message = 'created';

        }

        wp_safe_redirect(

            add_query_arg(

                array(

                    'page'    => $this->menu_slug,
                    'message' => $message

                ),

                admin_url('admin.php')

            )

        );

        exit;

    }

    /**
     * Kategorie löschen
     *
     * @return void
     */
    public function deleteCategory()
    {

        if (!current_user_can('manage_options')) {

            wp_die(
                __('Keine Berechtigung.', 'autoaufbereitung-chatbot-pro')
            );

        }

        check_admin_referer('acp_delete_category');

        $id = isset($_GET['id'])
            ? absint($_GET['id'])
            : 0;

        if ($id > 0) {

            $this->model->delete($id);

        }

        wp_safe_redirect(

            add_query_arg(

                array(

                    'page'    => $this->menu_slug,
                    'message' => 'deleted'

                ),

                admin_url('admin.php')

            )

        );

        exit;

    }

}

new ACP_Categories();