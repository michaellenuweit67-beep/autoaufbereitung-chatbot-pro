<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Category Model
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Category_Model {

    /**
     * Tabellenname
     */
    private $table;

    /**
     * Konstruktor
     */
    public function __construct() {

        global $wpdb;

        $this->table = $wpdb->prefix . 'acp_categories';

    }

    /**
     * Alle Kategorien
     */
    public function findAll() {

        global $wpdb;

        return $wpdb->get_results(
            "SELECT * FROM {$this->table} ORDER BY sort_order ASC, name ASC",
            ARRAY_A
        );

    }

    /**
     * Eine Kategorie
     */
    public function find($id) {

        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id=%d",
                $id
            ),
            ARRAY_A
        );

    }

    /**
     * Neue Kategorie
     */
    public function insert($data) {

        global $wpdb;

        return $wpdb->insert(
            $this->table,
            array(
                'name'       => sanitize_text_field($data['name']),
                'sort_order' => intval($data['sort_order']),
                'active'     => !empty($data['active']) ? 1 : 0
            ),
            array(
                '%s',
                '%d',
                '%d'
            )
        );

    }

    /**
     * Kategorie ändern
     */
    public function update($id, $data) {

        global $wpdb;

        return $wpdb->update(
            $this->table,
            array(
                'name'       => sanitize_text_field($data['name']),
                'sort_order' => intval($data['sort_order']),
                'active'     => !empty($data['active']) ? 1 : 0
            ),
            array(
                'id' => intval($id)
            ),
            array(
                '%s',
                '%d',
                '%d'
            ),
            array(
                '%d'
            )
        );

    }

    /**
     * Kategorie löschen
     */
    public function delete($id) {

        global $wpdb;

        return $wpdb->delete(
            $this->table,
            array(
                'id' => intval($id)
            ),
            array(
                '%d'
            )
        );

    }

    /**
     * Anzahl
     */
    public function count() {

        global $wpdb;

        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$this->table}"
        );

    }

}