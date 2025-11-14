<?php
/**
 * Klasse für Internationalisierung
 * 
 * Datei: includes/class-i18n.php
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_i18n {
    
    /**
     * Initialisiert die Hooks
     */
    public function init() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    /**
     * Lädt die Übersetzungsdateien
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'eisenhower-matrix',
            false,
            dirname(plugin_basename(EM_PLUGIN_DIR)) . '/languages/'
        );
    }
}