/**
 * Notes functionality for Iceberg AddOns
 */
(function($) {
    'use strict';

    const IcebergNotes = {
        init: function() {
            this.panel = $('#iceberg-notes-panel');
            this.form = $('#iceberg-notes-form');
            this.list = $('#iceberg-notes-list');
            this.editor = null;

            // Initialize TinyMCE if available
            if (typeof tinyMCE !== 'undefined') {
                this.editor = tinyMCE.get('iceberg-notes-content');
            }

            this.bindEvents();
            this.initBlockEditor();
        },

        bindEvents: function() {
            // Toggle panel
            $(document).on('click', '.iceberg-notes-icon', this.togglePanel.bind(this));
            $(document).on('click', '#iceberg-notes-close', this.closePanel.bind(this));

            // Form submission
            this.form.on('submit', this.handleSubmit.bind(this));

            // Note actions
            this.list.on('click', '.iceberg-note-header', this.toggleNoteContent);
            this.list.on('click', '.iceberg-note-edit', this.startEditing.bind(this));
            this.list.on('click', '.iceberg-note-delete', this.deleteNote.bind(this));
            this.list.on('click', '.iceberg-note-save', this.saveEdit.bind(this));
            this.list.on('click', '.iceberg-note-cancel', this.cancelEdit.bind(this));
        },

        togglePanel: function(e) {
            e.preventDefault();
            this.panel.toggleClass('open');
            if (this.panel.hasClass('open')) {
                this.loadNotes();
            }
        },

        closePanel: function() {
            this.panel.removeClass('open');
        },

        handleSubmit: function(e) {
            e.preventDefault();
            const title = $('#iceberg-notes-title').val();
            let content = '';

            // Get content from TinyMCE if available, otherwise from textarea
            if (this.editor && !this.editor.isHidden()) {
                content = this.editor.getContent();
            } else {
                content = $('#iceberg-notes-content').val();
            }

            this.addNote(title, content);
        },

        addNote: function(title, content) {
            if (!title || !content) return;

            $.ajax({
                url: icebergNotes.ajax_url,
                type: 'POST',
                data: {
                    action: 'iceberg_add_note',
                    nonce: icebergNotes.nonce,
                    title: title,
                    content: content
                },
                success: (response) => {
                    if (response.success) {
                        // Clear form
                        $('#iceberg-notes-title').val('');
                        if (this.editor) {
                            this.editor.setContent('');
                        } else {
                            $('#iceberg-notes-content').val('');
                        }
                        this.loadNotes();
                    } else {
                        alert(icebergNotes.strings.saveError);
                    }
                },
                error: () => alert(icebergNotes.strings.saveError)
            });
        },

        loadNotes: function() {
            this.list.html('<div class="iceberg-notes-loading">Loading notes...</div>');

            $.ajax({
                url: icebergNotes.ajax_url,
                type: 'POST',
                data: {
                    action: 'iceberg_fetch_notes',
                    nonce: icebergNotes.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderNotes(response.data);
                    } else {
                        this.list.html('<div class="iceberg-notes-error">' + icebergNotes.strings.loadError + '</div>');
                    }
                },
                error: () => {
                    this.list.html('<div class="iceberg-notes-error">' + icebergNotes.strings.loadError + '</div>');
                }
            });
        },

        renderNotes: function(notes) {
            if (!notes.length) {
                this.list.html('<div class="iceberg-notes-empty">No notes found</div>');
                return;
            }

            const template = $('#iceberg-note-template').html();
            const notesHtml = notes.map(note => {
                return template
                    .replace(/\{\{id\}\}/g, note.id)
                    .replace(/\{\{title\}\}/g, note.title)
                    .replace(/\{\{content\}\}/g, note.content)
                    .replace(/\{\{timestamp\}\}/g, note.timestamp)
                    .replace(/\{\{author\}\}/g, note.author);
            }).join('');

            this.list.html(notesHtml);
        },

        toggleNoteContent: function(e) {
            const noteBody = $(this).siblings('.iceberg-note-body');
            noteBody.slideToggle(200);
            $(this).closest('.iceberg-note').toggleClass('expanded');
        },

        startEditing: function(e) {
            e.preventDefault();
            const note = $(e.target).closest('.iceberg-note');
            const noteId = note.data('note-id');
            const title = note.find('.iceberg-note-title').text();
            const content = note.find('.iceberg-note-content').html();

            const template = $('#iceberg-note-edit-template').html();
            const editorId = 'iceberg-note-edit-' + noteId;
            const editHtml = template
                .replace(/\{\{title\}\}/g, title)
                .replace(/\{\{editor\}\}/g, '<textarea id="' + editorId + '">' + content + '</textarea>');

            note.addClass('editing').find('.iceberg-note-body').html(editHtml);

            // Initialize TinyMCE for edit
            wp.editor.initialize(editorId, {
                tinymce: {
                    toolbar1: 'bold,italic,bullist,numlist,link',
                    toolbar2: '',
                    toolbar3: '',
                },
                quicktags: true,
                mediaButtons: false,
            });
        },

        saveEdit: function(e) {
            e.preventDefault();
            const note = $(e.target).closest('.iceberg-note');
            const noteId = note.data('note-id');
            const title = note.find('.iceberg-note-edit-title').val();
            const editorId = 'iceberg-note-edit-' + noteId;
            const content = wp.editor.getContent(editorId);

            $.ajax({
                url: icebergNotes.ajax_url,
                type: 'POST',
                data: {
                    action: 'iceberg_edit_note',
                    nonce: icebergNotes.nonce,
                    note_id: noteId,
                    title: title,
                    content: content
                },
                success: (response) => {
                    if (response.success) {
                        wp.editor.remove(editorId);
                        this.loadNotes();
                    } else {
                        alert(icebergNotes.strings.saveError);
                    }
                },
                error: () => alert(icebergNotes.strings.saveError)
            });
        },

        cancelEdit: function(e) {
            e.preventDefault();
            const note = $(e.target).closest('.iceberg-note');
            const noteId = note.data('note-id');
            const editorId = 'iceberg-note-edit-' + noteId;
            
            wp.editor.remove(editorId);
            this.loadNotes();
        },

        deleteNote: function(e) {
            e.preventDefault();
            if (!confirm(icebergNotes.strings.confirmDelete)) return;

            const note = $(e.target).closest('.iceberg-note');
            const noteId = note.data('note-id');

            $.ajax({
                url: icebergNotes.ajax_url,
                type: 'POST',
                data: {
                    action: 'iceberg_delete_note',
                    nonce: icebergNotes.nonce,
                    note_id: noteId
                },
                success: (response) => {
                    if (response.success) {
                        note.slideUp(200, () => {
                            note.remove();
                            if (!this.list.children('.iceberg-note').length) {
                                this.loadNotes();
                            }
                        });
                    } else {
                        alert(icebergNotes.strings.deleteError);
                    }
                },
                error: () => alert(icebergNotes.strings.deleteError)
            });
        },

        initBlockEditor: function() {
            // Add button to Gutenberg editor if available
            if (typeof wp !== 'undefined' && wp.plugins && wp.editPost) {
                const { registerPlugin } = wp.plugins;
                const { PluginSidebarMoreMenuItem } = wp.editPost;
                const { createElement } = wp.element;

                registerPlugin('iceberg-notes', {
                    render: () => createElement(
                        PluginSidebarMoreMenuItem,
                        {
                            icon: 'admin-comments',
                            onClick: () => this.togglePanel({ preventDefault: () => {} })
                        },
                        'Notes'
                    )
                });
            }
        }
    };

    // Initialize on document ready
    $(document).ready(() => IcebergNotes.init());

})(jQuery);