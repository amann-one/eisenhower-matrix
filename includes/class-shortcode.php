<?php
/**
 * Klasse für Shortcode
 */

if (!defined('ABSPATH')) {
    exit;
}

class EM_Shortcode {
    
    public function init() {
        add_shortcode('eisenhower_matrix', array($this, 'render_matrix'));
    }
    
    public function render_matrix($atts) {
        ob_start();
        
        $tasks = $this->get_all_tasks();
        $quadrants = $this->sort_tasks_into_quadrants($tasks);
        $this->display_matrix($quadrants);
        
        return ob_get_clean();
    }
    
    private function get_all_tasks() {
        return get_posts(array(
            'post_type' => 'em_task',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
    }
    
    private function sort_tasks_into_quadrants($tasks) {
        $quadrants = array(
            'q1' => array(),
            'q2' => array(),
            'q3' => array(),
            'q4' => array()
        );
        
        foreach ($tasks as $task) {
            $importance = wp_get_post_terms($task->ID, 'em_importance', array('fields' => 'names'));
            $urgency = wp_get_post_terms($task->ID, 'em_urgency', array('fields' => 'names'));
            
            $is_important = in_array(__('Wichtig', 'eisenhower-matrix'), $importance);
            $is_urgent = in_array(__('Dringend', 'eisenhower-matrix'), $urgency);
            
            if ($is_important && $is_urgent) {
                $quadrants['q1'][] = $task;
            } elseif ($is_important && !$is_urgent) {
                $quadrants['q2'][] = $task;
            } elseif (!$is_important && $is_urgent) {
                $quadrants['q3'][] = $task;
            } else {
                $quadrants['q4'][] = $task;
            }
        }
        
        return $quadrants;
    }
    
    private function display_matrix($quadrants) {
        ?>
        <div class="eisenhower-matrix">
            <div class="matrix-grid">
                <div class="quadrant q1">
                    <h3><?php esc_html_e('Sofort erledigen', 'eisenhower-matrix'); ?></h3>
                    <p class="quadrant-label"><?php esc_html_e('Wichtig & Dringend', 'eisenhower-matrix'); ?></p>
                    <?php $this->render_task_list($quadrants['q1']); ?>
                </div>
                
                <div class="quadrant q2">
                    <h3><?php esc_html_e('Planen', 'eisenhower-matrix'); ?></h3>
                    <p class="quadrant-label"><?php esc_html_e('Wichtig & Nicht dringend', 'eisenhower-matrix'); ?></p>
                    <?php $this->render_task_list($quadrants['q2']); ?>
                </div>
                
                <div class="quadrant q3">
                    <h3><?php esc_html_e('Delegieren', 'eisenhower-matrix'); ?></h3>
                    <p class="quadrant-label"><?php esc_html_e('Nicht wichtig & Dringend', 'eisenhower-matrix'); ?></p>
                    <?php $this->render_task_list($quadrants['q3']); ?>
                </div>
                
                <div class="quadrant q4">
                    <h3><?php esc_html_e('Eliminieren', 'eisenhower-matrix'); ?></h3>
                    <p class="quadrant-label"><?php esc_html_e('Nicht wichtig & Nicht dringend', 'eisenhower-matrix'); ?></p>
                    <?php $this->render_task_list($quadrants['q4']); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function render_task_list($tasks) {
        if (empty($tasks)) {
            echo '<p class="no-tasks">' . esc_html__('Keine Aufgaben', 'eisenhower-matrix') . '</p>';
            return;
        }
        
        echo '<ul class="task-list">';
        foreach ($tasks as $task) {
            $this->render_task_item($task);
        }
        echo '</ul>';
    }
    
    private function render_task_item($task) {
        $deadline = get_post_meta($task->ID, '_em_deadline', true);
        $status = get_post_meta($task->ID, '_em_status', true);
        
        echo '<li class="task-item status-' . esc_attr($status) . '" data-task-id="' . esc_attr($task->ID) . '">';
        echo '<strong>' . esc_html($task->post_title) . '</strong>';
        
        if ($deadline) {
            echo '<br><span class="deadline">📅 ' . esc_html(date('d.m.Y', strtotime($deadline))) . '</span>';
        }
        
        echo '</li>';
    }
}