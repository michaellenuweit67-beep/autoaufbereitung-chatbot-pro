<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Service Model
 *
 * Datenbankzugriff für Dienstleistungen
 *
 * @package ACP
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Service_Model
{
    /**
     * Tabellenname
     *
     * @var string
     */
    private $table;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        global $wpdb;

        $this->table = $wpdb->prefix . 'acp_services';
    }

    /**
     * Alle Dienstleistungen laden
     *
     * @return array
     */
    public function findAll()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT *
             FROM {$this->table}
             ORDER BY category_id ASC, name ASC",
            ARRAY_A
        );
    }

    /**
     * Eine Dienstleistung laden
     *
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE id = %d",
                absint($id)
            ),
            ARRAY_A
        );
    }

    /**
     * Dienstleistungen einer Kategorie
     *
     * @param int $categoryId
     * @return array
     */
    public function findByCategory($categoryId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE category_id = %d
                 ORDER BY name ASC",
                absint($categoryId)
            ),
            ARRAY_A
        );
    }

    /**
     * Alle aktiven Dienstleistungen
     *
     * @return array
     */
    public function findActive()
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT *
             FROM {$this->table}
             WHERE active = 1
             ORDER BY category_id ASC, name ASC",
            ARRAY_A
        );
    }

   /**
 * Neue Dienstleistung anlegen
 *
 * @param array $data
 * @return int|false
 */
public function insert($data)
{
    global $wpdb;

    $result = $wpdb->insert(
        $this->table,
        array(
            'category_id'       => absint($data['category_id']),
            'name'              => sanitize_text_field($data['name']),
            'short_description' => sanitize_text_field($data['short_description']),
            'description'       => wp_kses_post($data['description']),
            'price_small'       => (float) $data['price_small'],
            'price_compact'     => (float) $data['price_compact'],
            'price_medium'      => (float) $data['price_medium'],
            'price_large'       => (float) $data['price_large'],
            'price_suv'         => (float) $data['price_suv'],
            'price_van'         => (float) $data['price_van'],
            'duration'          => absint($data['duration']),
            'duration_unit'     => sanitize_text_field($data['duration_unit']),
            'image_mode'        => sanitize_text_field($data['image_mode']),
            'image_limit'       => absint($data['image_limit']),
            'booking_mode'      => sanitize_text_field($data['booking_mode']),
            'active'            => !empty($data['active']) ? 1 : 0
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%f',
            '%f',
            '%f',
            '%f',
            '%f',
            '%f',
            '%d',
            '%s',
            '%s',
            '%d',
            '%s',
            '%d'
        )
    );

    if ($result === false) {

        wp_die(
            '<h2>SQL-Fehler beim Speichern</h2>' .
            '<p><strong>Fehlermeldung:</strong></p>' .
            '<pre>' . esc_html($wpdb->last_error) . '</pre>' .
            '<p><strong>Letzte SQL-Abfrage:</strong></p>' .
            '<pre>' . esc_html($wpdb->last_query) . '</pre>'
        );

    }

    return (int) $wpdb->insert_id;
}
    /**
     * Dienstleistung aktualisieren
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        global $wpdb;

        $result = $wpdb->update(
            $this->table,
            array(
                'category_id'       => absint($data['category_id']),
                'name'              => sanitize_text_field($data['name']),
                'short_description' => sanitize_text_field($data['short_description']),
                'description'       => wp_kses_post($data['description']),
                'price_small'       => (float) $data['price_small'],
                'price_compact'     => (float) $data['price_compact'],
                'price_medium'      => (float) $data['price_medium'],
                'price_large'       => (float) $data['price_large'],
                'price_suv'         => (float) $data['price_suv'],
                'price_van'         => (float) $data['price_van'],
                'duration'          => absint($data['duration']),
                'duration_unit'     => sanitize_text_field($data['duration_unit']),
                'image_mode'        => sanitize_text_field($data['image_mode']),
                'image_limit'       => absint($data['image_limit']),
                'booking_mode'      => sanitize_text_field($data['booking_mode']),
                'active'            => !empty($data['active']) ? 1 : 0
            ),
            array(
                'id' => absint($id)
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%d',
                '%s',
                '%s',
                '%d',
                '%s',
                '%d'
            ),
            array(
                '%d'
            )
        );

        return ($result !== false);
    }

    /**
     * Dienstleistung löschen
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        global $wpdb;

        $result = $wpdb->delete(
            $this->table,
            array(
                'id' => absint($id)
            ),
            array(
                '%d'
            )
        );

        return ($result !== false);
    }

    /**
     * Anzahl aller Dienstleistungen
     *
     * @return int
     */
    public function count()
    {
        global $wpdb;

        return (int) $wpdb->get_var(
            "SELECT COUNT(*)
             FROM {$this->table}"
        );
    }

    /**
     * Prüft, ob eine Dienstleistung existiert
     *
     * @param int $id
     * @return bool
     */
    public function exists($id)
    {
        global $wpdb;

        return (bool) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*)
                 FROM {$this->table}
                 WHERE id = %d",
                absint($id)
            )
        );
    }

    /**
     * Anzahl der Dienstleistungen einer Kategorie
     *
     * @param int $categoryId
     * @return int
     */
    public function countByCategory($categoryId)
    {
        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*)
                 FROM {$this->table}
                 WHERE category_id = %d",
                absint($categoryId)
            )
        );
    }

}