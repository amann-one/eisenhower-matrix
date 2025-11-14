<?php
/**
 * Klasse für Assets
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Assets {
    
    public function init() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }
    
    public function enqueue_admin_assets($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'em-admin-css',
            EM_PLUGIN_URL . 'assets/admin.css',
            array(),
            EM_VERSION
        );
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'em-frontend-css',
            EM_PLUGIN_URL . 'assets/frontend.css',
            array(),
            EM_VERSION
        );
    }
}