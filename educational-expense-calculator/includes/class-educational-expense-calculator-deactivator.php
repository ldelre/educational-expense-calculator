<?php
/**
 * Fired during plugin deactivation.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */
class Educational_Expense_Calculator_Deactivator {

    /**
     * Cleans up plugin data upon deactivation.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Clear any scheduled tasks
        wp_clear_scheduled_hook('eec_clear_cache');
        
        // Flush rewrite rules to clean up any customizations
        flush_rewrite_rules();
        
        // Note: We're not deleting options here because we want to preserve
        // user settings in case they reactivate the plugin later.
        // Options will be properly cleaned up by WordPress uninstall hook.
    }
}
