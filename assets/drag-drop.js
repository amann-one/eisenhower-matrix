/**
 * Drag & Drop Funktionalität für Eisenhower Matrix
 */

(function($) {
    'use strict';
    
    // Warte bis DOM geladen ist
    $(document).ready(function() {
        initDragDrop();
    });
    
    /**
     * Initialisiert Drag & Drop
     */
    function initDragDrop() {
        const taskItems = document.querySelectorAll('.task-item');
        const quadrants = document.querySelectorAll('.quadrant');
        
        // Drag-Events für alle Aufgaben
        taskItems.forEach(item => {
            item.setAttribute('draggable', 'true');
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
        });
        
        // Drop-Events für alle Quadranten
        quadrants.forEach(quadrant => {
            quadrant.addEventListener('dragover', handleDragOver);
            quadrant.addEventListener('drop', handleDrop);
            quadrant.addEventListener('dragleave', handleDragLeave);
        });
    }
    
    /**
     * Wird beim Start des Draggings ausgeführt
     */
    function handleDragStart(e) {
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        
        // Speichere Task-ID
        const taskId = this.getAttribute('data-task-id');
        e.dataTransfer.setData('taskId', taskId);
    }
    
    /**
     * Wird beim Ende des Draggings ausgeführt
     */
    function handleDragEnd(e) {
        this.classList.remove('dragging');
        
        // Entferne alle Hover-Effekte
        document.querySelectorAll('.quadrant').forEach(q => {
            q.classList.remove('drag-over');
        });
    }
    
    /**
     * Wird ausgeführt wenn über einem Quadranten gehovert wird
     */
    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('drag-over');
        
        return false;
    }
    
    /**
     * Wird ausgeführt wenn Quadrant verlassen wird
     */
    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }
    
    /**
     * Wird beim Drop ausgeführt
     */
    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        e.preventDefault();
        this.classList.remove('drag-over');
        
        const taskId = e.dataTransfer.getData('taskId');
        const targetQuadrant = this.classList.contains('q1') ? 'q1' :
                              this.classList.contains('q2') ? 'q2' :
                              this.classList.contains('q3') ? 'q3' : 'q4';
        
        // Bestimme neue Wichtigkeit und Dringlichkeit
        const newTaxonomies = getQuadrantTaxonomies(targetQuadrant);
        
        // Sende AJAX-Request zum Aktualisieren
        updateTaskQuadrant(taskId, newTaxonomies, targetQuadrant);
        
        return false;
    }
    
    /**
     * Gibt Taxonomien für einen Quadranten zurück
     */
    function getQuadrantTaxonomies(quadrant) {
        const taxonomies = {
            'q1': { importance: emDragDrop.taxonomies.important, urgency: emDragDrop.taxonomies.urgent },
            'q2': { importance: emDragDrop.taxonomies.important, urgency: emDragDrop.taxonomies.notUrgent },
            'q3': { importance: emDragDrop.taxonomies.notImportant, urgency: emDragDrop.taxonomies.urgent },
            'q4': { importance: emDragDrop.taxonomies.notImportant, urgency: emDragDrop.taxonomies.notUrgent }
        };
        
        return taxonomies[quadrant];
    }
    
    /**
     * Aktualisiert Aufgabe per AJAX
     */
    function updateTaskQuadrant(taskId, taxonomies, targetQuadrant) {
        // Zeige Ladeanzeige
        showLoadingIndicator();
        
        $.ajax({
            url: emDragDrop.ajaxUrl,
            type: 'POST',
            data: {
                action: 'em_update_task_quadrant',
                nonce: emDragDrop.nonce,
                task_id: taskId,
                importance: taxonomies.importance,
                urgency: taxonomies.urgency
            },
            success: function(response) {
                if (response.success) {
                    // Erfolgsmeldung anzeigen
                    showNotification('Aufgabe erfolgreich verschoben!', 'success');
                    
                    // Seite nach kurzer Verzögerung neu laden
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
                    showNotification('Fehler beim Verschieben der Aufgabe.', 'error');
                }
            },
            error: function() {
                showNotification('Verbindungsfehler. Bitte versuche es erneut.', 'error');
            },
            complete: function() {
                hideLoadingIndicator();
            }
        });
    }
    
    /**
     * Zeigt Benachrichtigung an
     */
    function showNotification(message, type) {
        const notification = $('<div class="em-notification em-notification-' + type + '">' + message + '</div>');
        $('body').append(notification);
        
        // Einblenden
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        // Ausblenden nach 3 Sekunden
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    /**
     * Zeigt Ladeanzeige
     */
    function showLoadingIndicator() {
        if ($('.em-loading').length === 0) {
            $('body').append('<div class="em-loading"><div class="em-spinner"></div></div>');
        }
    }
    
    /**
     * Versteckt Ladeanzeige
     */
    function hideLoadingIndicator() {
        $('.em-loading').remove();
    }
    
})(jQuery);