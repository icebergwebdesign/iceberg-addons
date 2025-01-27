/**
 * Block Editor integration for Iceberg Notes
 */
(function(wp) {
    if (!wp || !wp.plugins || !wp.editPost || !wp.element) {
        return;
    }

    const { registerPlugin } = wp.plugins;
    const { PluginSidebarMoreMenuItem } = wp.editPost;
    const { createElement } = wp.element;

    /**
     * Register the notes plugin in the block editor
     */
    registerPlugin('iceberg-notes-editor', {
        render: function() {
            return createElement(
                PluginSidebarMoreMenuItem,
                {
                    icon: createElement('svg', {
                        width: 24,
                        height: 24,
                        viewBox: '0 0 24 24',
                        xmlns: 'http://www.w3.org/2000/svg'
                    },
                    createElement('path', {
                        d: 'M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 9h-2V5h2v6zm0 4h-2v-2h2v2z',
                        fill: 'currentColor'
                    })),
                    onClick: function() {
                        // Trigger the notes panel toggle
                        const panel = document.getElementById('iceberg-notes-panel');
                        if (panel) {
                            panel.classList.toggle('open');
                            
                            // If panel is opened, trigger notes reload
                            if (panel.classList.contains('open')) {
                                jQuery(document).trigger('iceberg-notes-panel-opened');
                            }
                        }
                    }
                },
                'Notes'
            );
        }
    });

    /**
     * Handle editor initialization
     */
    wp.domReady(function() {
        // Adjust panel position when Gutenberg sidebar is open
        const adjustPanelPosition = function() {
            const panel = document.getElementById('iceberg-notes-panel');
            const sidebar = document.querySelector('.interface-interface-skeleton__sidebar');
            
            if (panel && sidebar) {
                const sidebarWidth = sidebar.offsetWidth;
                panel.style.right = sidebar.classList.contains('is-open') ? 
                    sidebarWidth + 'px' : '0';
            }
        };

        // Watch for sidebar changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.target.classList.contains('is-open')) {
                    adjustPanelPosition();
                }
            });
        });

        // Start observing the sidebar
        const sidebar = document.querySelector('.interface-interface-skeleton__sidebar');
        if (sidebar) {
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        }

        // Initial position adjustment
        adjustPanelPosition();
    });

})(window.wp);