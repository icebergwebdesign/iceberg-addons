<?php
/**
 * Template for the notes panel.
 *
 * @package    IcebergAddOns
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div id="iceberg-notes-panel" class="iceberg-notes-panel wp-admin-styling">
    <button id="iceberg-notes-close" class="iceberg-notes-close-button">&times;</button>
    
    <div class="iceberg-notes-content">
        <h2><?php _e('Add a Note', 'iceberg-addons'); ?></h2>
        
        <form id="iceberg-notes-form">
            <div class="form-group">
                <label for="iceberg-notes-title">
                    <?php _e('Title', 'iceberg-addons'); ?>
                </label>
                <input type="text" 
                       id="iceberg-notes-title" 
                       name="title" 
                       class="iceberg-notes-input" 
                       placeholder="<?php esc_attr_e('Enter note title...', 'iceberg-addons'); ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="iceberg-notes-content">
                    <?php _e('Content', 'iceberg-addons'); ?>
                </label>
                <?php
                // Initialize WordPress editor
                wp_editor(
                    '', // Initial content
                    'iceberg-notes-content',
                    array(
                        'textarea_name' => 'content',
                        'media_buttons' => false,
                        'textarea_rows' => 8,
                        'teeny' => true,
                        'quicktags' => true,
                        'tinymce' => array(
                            'toolbar1' => 'bold,italic,bullist,numlist,link',
                            'toolbar2' => '',
                            'toolbar3' => '',
                        ),
                    )
                );
                ?>
            </div>

            <div class="form-actions">
                <button type="submit" 
                        id="iceberg-notes-add" 
                        class="iceberg-notes-add-button">
                    <?php _e('Add Note', 'iceberg-addons'); ?>
                </button>
            </div>
        </form>

        <div class="iceberg-notes-list-header">
            <h3><?php _e('Notes', 'iceberg-addons'); ?></h3>
        </div>

        <div id="iceberg-notes-list" class="iceberg-notes-list">
            <!-- Notes will be dynamically loaded here -->
            <div class="iceberg-notes-loading">
                <?php _e('Loading notes...', 'iceberg-addons'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Template for individual note -->
<script type="text/template" id="iceberg-note-template">
    <div class="iceberg-note" data-note-id="{{id}}">
        <div class="iceberg-note-header">
            <span class="iceberg-note-title">{{title}}</span>
            <span class="iceberg-note-timestamp">{{timestamp}}</span>
        </div>
        
        <div class="iceberg-note-body">
            <div class="iceberg-note-content">
                {{content}}
            </div>
            
            <div class="iceberg-note-meta">
                <span class="iceberg-note-author">{{author}}</span>
                <div class="iceberg-note-actions">
                    <a href="#" class="iceberg-note-edit" data-note-id="{{id}}">
                        <?php _e('Edit', 'iceberg-addons'); ?>
                    </a> |
                    <a href="#" class="iceberg-note-delete" data-note-id="{{id}}">
                        <?php _e('Delete', 'iceberg-addons'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</script>

<!-- Template for note edit form -->
<script type="text/template" id="iceberg-note-edit-template">
    <div class="iceberg-note-edit-form">
        <input type="text" 
               class="iceberg-note-edit-title" 
               value="{{title}}" 
               placeholder="<?php esc_attr_e('Enter note title...', 'iceberg-addons'); ?>">
        
        <div class="iceberg-note-edit-content-wrapper">
            {{editor}}
        </div>
        
        <div class="iceberg-note-edit-actions">
            <button type="button" class="iceberg-note-save">
                <?php _e('Save', 'iceberg-addons'); ?>
            </button>
            <button type="button" class="iceberg-note-cancel">
                <?php _e('Cancel', 'iceberg-addons'); ?>
            </button>
        </div>
    </div>
</script>