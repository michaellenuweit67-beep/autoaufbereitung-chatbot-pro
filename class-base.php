class ACP_Base {

    protected function get_option($key, $default = array()) {
        return get_option($key, $default);
    }

    protected function update_option($key, $value) {
        return update_option($key, $value);
    }

    protected function sanitize_text($value) {
        return sanitize_text_field($value);
    }

    protected function sanitize_float($value) {
        return floatval($value);
    }

    protected function sanitize_int($value) {
        return absint($value);
    }

}