<?php
/**
 * Klasse für Taxonomien
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Taxonomies {
    
    public function init() {
        add_action('init', array($this, 'register_taxonomies'));
    }
    
    public function register_taxonomies() {
        register_taxonomy('em_importance', 'em_task', array(
            'label' => __('Wichtigkeit', 'eisenhower-matrix'),
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wichtigkeit')
        ));
        
        register_taxonomy('em_urgency', 'em_task', array(
            'label' => __('Dringlichkeit', 'eisenhower-matrix'),
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'dringlichkeit')
        ));
        
        $this->create_default_terms();
    }
    
    private function create_default_terms() {
        if (!term_exists(__('Wichtig', 'eisenhower-matrix'), 'em_importance')) {
            wp_insert_term(__('Wichtig', 'eisenhower-matrix'), 'em_importance');
            wp_insert_term(__('Nicht wichtig', 'eisenhower-matrix'), 'em_importance');
        }
        
        if (!term_exists(__('Dringend', 'eisenhower-matrix'), 'em_urgency')) {
            wp_insert_term(__('Dringend', 'eisenhower-matrix'), 'em_urgency');
            wp_insert_term(__('Nicht dringend', 'eisenhower-matrix'), 'em_urgency');
        }
    }
}