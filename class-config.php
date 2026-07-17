<?php
/**
 * Autoaufbereitung Chatbot Pro
 * Zentrale Konfiguration
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACP_Config
{
    /**
     * Plugin
     */
    public const PLUGIN_NAME = 'Autoaufbereitung Chatbot Pro';

    public const VERSION = '10.0.1';

    public const DB_VERSION = '1.0.0';

    public const AUTHOR = 'LP4YOU';

    public const COMPANY = 'LP4YOU';

    /**
     * Datenbanktabellen
     */
    public const TABLE_COMPANY      = 'acp_company';

    public const TABLE_CATEGORIES   = 'acp_categories';

    public const TABLE_SERVICES     = 'acp_services';

    public const TABLE_EXTRAS       = 'acp_extras';

    public const TABLE_CUSTOMERS    = 'acp_customers';

    public const TABLE_VEHICLES     = 'acp_vehicles';

    public const TABLE_REQUESTS     = 'acp_requests';

    public const TABLE_APPOINTMENTS = 'acp_appointments';

    public const TABLE_IMAGES       = 'acp_images';

    public const TABLE_SETTINGS     = 'acp_settings';

    public const TABLE_LICENSES     = 'acp_licenses';

    public const TABLE_LOGS         = 'acp_logs';

    /**
     * Uploads
     */
    public const MAX_UPLOAD_IMAGES = 10;

    public const MAX_UPLOAD_SIZE = 10485760; // 10 MB

    /**
     * Bilder
     */
    public const ALLOWED_IMAGE_TYPES = array(

        'jpg',
        'jpeg',
        'png',
        'webp'

    );

    /**
     * Fahrzeugklassen
     */
    public const VEHICLE_CLASSES = array(

        'kleinwagen'   => 'Kleinwagen',

        'kompakt'      => 'Kompaktklasse',

        'mittelklasse' => 'Mittelklasse',

        'oberklasse'   => 'Oberklasse',

        'suv'          => 'SUV',

        'van'          => 'Van'

    );

    /**
     * Status
     */
    public const STATUS_ACTIVE = 1;

    public const STATUS_INACTIVE = 0;

}