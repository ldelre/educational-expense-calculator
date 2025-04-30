/**
 * Admin JavaScript for the Educational Expense Calculator plugin.
 *
 * @since      1.0.0
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize tooltips
        $('.eec-tooltip').on('click', function(e) {
            e.preventDefault();
        });

        // Copy shortcode to clipboard
        $('.eec-copy-shortcode').on('click', function() {
            const shortcodeText = $(this).data('clipboard-text');
            const tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(shortcodeText).select();
            document.execCommand('copy');
            tempInput.remove();
            
            // Show copied message
            const originalText = $(this).text();
            $(this).text('Copied!');
            setTimeout(() => {
                $(this).text(originalText);
            }, 1500);
        });

        // Clear cache button
        $('#eec-clear-cache').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $status = $('#eec-cache-status');
            
            // Disable button during processing
            $button.prop('disabled', true).text('Clearing...');
            $status.html('<p>Processing...</p>');
            
            // Send AJAX request to clear cache
            $.ajax({
                url: eec_admin_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'eec_clear_cache',
                    nonce: eec_admin_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $status.html('<p class="notice notice-success">' + response.data.message + '</p>');
                    } else {
                        $status.html('<p class="notice notice-error">Error: ' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    $status.html('<p class="notice notice-error">An error occurred while clearing the cache.</p>');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Clear Cache');
                }
            });
        });

        // Toggle API key visibility
        $('.eec-toggle-api-key').on('click', function(e) {
            e.preventDefault();
            
            const $input = $($(this).data('target'));
            const currentType = $input.attr('type');
            
            if (currentType === 'password') {
                $input.attr('type', 'text');
                $(this).text('Hide');
            } else {
                $input.attr('type', 'password');
                $(this).text('Show');
            }
        });
    });

})(jQuery);
