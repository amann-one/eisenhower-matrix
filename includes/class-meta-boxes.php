<?php
/**
 * Klasse für Meta-Boxen
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Meta_Boxes {
    
    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_task_meta'));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'em_task_details',
            __('Aufgaben-Details', 'eisenhower-matrix'),
            array($this, 'render_meta_box'),
            'em_task',
            'side',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        wp_nonce_field('em_save_task', 'em_task_nonce');
        
        $deadline = get_post_meta($post->ID, '_em_deadline', true);
        $status = get_post_meta($post->ID, '_em_status', true);
        ?>
        <p>
            <label for="em_deadline"><strong><?php esc_html_e('Deadline:', 'eisenhower-matrix'); ?></strong></label><br>
            <input type="date" id="em_deadline" name="em_deadline" value="<?php echo esc_attr($deadline); ?>" style="width: 100%;">
        </p>
        <p>
            <label for="em_status"><strong><?php esc_html_e('Status:', 'eisenhower-matrix'); ?></strong></label><br>
            <select id="em_status" name="em_status" style="width: 100%;">
                <option value="offen" <?php selected($status, 'offen'); ?>><?php esc_html_e('Offen', 'eisenhower-matrix'); ?></option>
                <option value="in_arbeit" <?php selected($status, 'in_arbeit'); ?>><?php esc_html_e('In Arbeit', 'eisenhower-matrix'); ?></option>
                <option value="erledigt" <?php selected($status, 'erledigt'); ?>><?php esc_html_e('Erledigt', 'eisenhower-matrix'); ?></option>
            </select>
        </p>
        <?php
    }
    
    public function save_task_meta($post_id) {
        if (!isset($_POST['em_task_nonce']) || !wp_verify_nonce($_POST['em_task_nonce'], 'em_save_task')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (isset($_POST['em_deadline'])) {
            update_post_meta($post_id, '_em_deadline', sanitize_text_field($_POST['em_deadline']));
        }
        
        if (isset($_POST['em_status'])) {
            update_post_meta($post_id, '_em_status', sanitize_text_field($_POST['em_status']));
        }
    }
}