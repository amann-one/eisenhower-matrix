<?php
/**
 * Klasse für Drag & Drop Funktionalität
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Drag_Drop {
    
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_em_update_task_quadrant', array($this, 'ajax_update_task_quadrant'));
        add_action('wp_ajax_nopriv_em_update_task_quadrant', array($this, 'ajax_update_task_quadrant'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'em-drag-drop',
            EM_PLUGIN_URL . 'assets/drag-drop.js',
            array('jquery'),
            EM_VERSION,
            true
        );
        
        wp_localize_script('em-drag-drop', 'emDragDrop', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('em_drag_drop_nonce'),
            'messages' => array(
                'success' => __('Aufgabe erfolgreich verschoben!', 'eisenhower-matrix'),
                'error' => __('Fehler beim Verschieben der Aufgabe.', 'eisenhower-matrix'),
                'connectionError' => __('Verbindungsfehler. Bitte versuche es erneut.', 'eisenhower-matrix')
            ),
            'taxonomies' => array(
                'important' => __('Wichtig', 'eisenhower-matrix'),
                'notImportant' => __('Nicht wichtig', 'eisenhower-matrix'),
                'urgent' => __('Dringend', 'eisenhower-matrix'),
                'notUrgent' => __('Nicht dringend', 'eisenhower-matrix')
            )
        ));
        
        wp_enqueue_style(
            'em-drag-drop-css',
            EM_PLUGIN_URL . 'assets/drag-drop.css',
            array(),
            EM_VERSION
        );
    }
    
    public function ajax_update_task_quadrant() {
        check_ajax_referer('em_drag_drop_nonce', 'nonce');
        
        $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
        $importance = isset($_POST['importance']) ? sanitize_text_field($_POST['importance']) : '';
        $urgency = isset($_POST['urgency']) ? sanitize_text_field($_POST['urgency']) : '';
        
        if (!$task_id || !$importance || !$urgency) {
            wp_send_json_error(array('message' => __('Ungültige Parameter', 'eisenhower-matrix')));
        }
        
        if (!current_user_can('edit_post', $task_id)) {
            wp_send_json_error(array('message' => __('Keine Berechtigung', 'eisenhower-matrix')));
        }
        
        $importance_updated = $this->update_taxonomy($task_id, 'em_importance', $importance);
        $urgency_updated = $this->update_taxonomy($task_id, 'em_urgency', $urgency);
        
        if ($importance_updated && $urgency_updated) {
            wp_send_json_success(array(
                'message' => __('Aufgabe erfolgreich aktualisiert', 'eisenhower-matrix'),
                'task_id' => $task_id
            ));
        } else {
            wp_send_json_error(array('message' => __('Fehler beim Aktualisieren', 'eisenhower-matrix')));
        }
    }
    
    private function update_taxonomy($task_id, $taxonomy, $term_name) {
        $term = get_term_by('name', $term_name, $taxonomy);
        
        if (!$term) {
            return false;
        }
        
        $result = wp_set_object_terms($task_id, array($term->term_id), $taxonomy, false);
        
        return !is_wp_error($result);
    }
}