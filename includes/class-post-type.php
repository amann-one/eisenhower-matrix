<?php
/**
 * Klasse für den Custom Post Type
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Post_Type {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name' => __('Aufgaben', 'eisenhower-matrix'),
            'singular_name' => __('Aufgabe', 'eisenhower-matrix'),
            'add_new' => __('Neue Aufgabe', 'eisenhower-matrix'),
            'add_new_item' => __('Neue Aufgabe hinzufügen', 'eisenhower-matrix'),
            'edit_item' => __('Aufgabe bearbeiten', 'eisenhower-matrix'),
            'new_item' => __('Neue Aufgabe', 'eisenhower-matrix'),
            'view_item' => __('Aufgabe ansehen', 'eisenhower-matrix'),
            'search_items' => __('Aufgaben suchen', 'eisenhower-matrix'),
            'not_found' => __('Keine Aufgaben gefunden', 'eisenhower-matrix'),
            'not_found_in_trash' => __('Keine Aufgaben im Papierkorb', 'eisenhower-matrix')
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-grid-view',
            'supports' => array('title', 'editor', 'author'),
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'aufgaben')
        );
        
        register_post_type('em_task', $args);
    }
}
