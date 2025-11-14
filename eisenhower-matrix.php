<?php
/**
 * Plugin Name: Eisenhower Matrix
 * Plugin URI: https://example.com/eisenhower-matrix
 * Description: Verwalte deine Aufgaben mit der Eisenhower-Matrix (dringend/wichtig)
 * Version: 1.0.0
 * Author: Dein Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: eisenhower-matrix
 */

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// Plugin-Konstanten
define('EM_VERSION', '1.0.0');
define('EM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Lade die einzelnen Klassen
require_once EM_PLUGIN_DIR . 'includes/class-i18n.php';
require_once EM_PLUGIN_DIR . 'includes/class-post-type.php';
require_once EM_PLUGIN_DIR . 'includes/class-taxonomies.php';
require_once EM_PLUGIN_DIR . 'includes/class-meta-boxes.php';
require_once EM_PLUGIN_DIR . 'includes/class-shortcode.php';
require_once EM_PLUGIN_DIR . 'includes/class-assets.php';
require_once EM_PLUGIN_DIR . 'includes/class-drag-drop.php';

/**
 * Hauptklasse des Plugins
 */
class Eisenhower_Matrix {
    
    private $post_type;
    private $taxonomies;
    private $meta_boxes;
    private $shortcode;
    private $assets;
    private $drag_drop;
    
    /**
     * Konstruktor - initialisiert das Plugin
     */
    public function __construct() {
        // Instanzen der einzelnen Klassen erstellen
        $this->post_type = new EM_Post_Type();
        $this->taxonomies = new EM_Taxonomies();
        $this->meta_boxes = new EM_Meta_Boxes();
        $this->shortcode = new EM_Shortcode();
        $this->assets = new EM_Assets();
        $this->drag_drop = new EM_Drag_Drop();
        
        // Initialisieren
        $this->init();
    }
    
    /**
     * Initialisiert alle Komponenten
     */
    private function init() {
        $this->post_type->init();
        $this->taxonomies->init();
        $this->meta_boxes->init();
        $this->shortcode->init();
        $this->assets->init();
        $this->drag_drop->init();
    }
}

// Plugin initialisieren
new Eisenhower_Matrix();