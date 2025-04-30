<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/admin/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="eec-admin-header">
        <h2>Educational Expense Calculator</h2>
        <p>This plugin helps students calculate educational expenses and determine loan/grant eligibility based on school selection and income data.</p>
    </div>
    
    <div class="eec-admin-main">
        <div class="eec-admin-settings">
            <form method="post" action="options.php">
                <?php
                settings_fields('eec_options');
                do_settings_sections($this->plugin_name);
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        
        <div class="eec-admin-sidebar">
            <div class="eec-admin-box">
                <h3>Getting Started</h3>
                <ol>
                    <li>Enter your API keys in the settings above.</li>
                    <li>Configure the display options for the calculator.</li>
                    <li>Add the calculator to any page or post using the shortcode:</li>
                </ol>
                <div class="eec-admin-shortcode">
                    <code>[educational_expense_calculator]</code>
                    <button class="button button-secondary eec-copy-shortcode" data-clipboard-text="[educational_expense_calculator]">Copy</button>
                </div>
                <p>You can also use the shortcode with options:</p>
                <div class="eec-admin-shortcode">
                    <code>[educational_expense_calculator title="College Cost Calculator" description="Plan your education budget"]</code>
                </div>
            </div>
            
            <div class="eec-admin-box">
                <h3>Cache Management</h3>
                <p>API responses are cached to improve performance. You can clear the cache if you need to refresh the data.</p>
                <button id="eec-clear-cache" class="button button-secondary">Clear Cache</button>
                <div id="eec-cache-status"></div>
            </div>
            
            <div class="eec-admin-box">
                <h3>Support</h3>
                <p>If you need help with this plugin or have feature requests, please contact us:</p>
                <ul>
                    <li><a href="https://example.com/support" target="_blank">Support Website</a></li>
                    <li><a href="mailto:support@example.com">Email Support</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
