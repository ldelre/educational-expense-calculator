<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */
class Educational_Expense_Calculator_Activator {

    /**
     * Initializes the plugin upon activation.
     *
     * - Sets up default options for the plugin
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Initialize default plugin settings
        $default_options = array(
            'college_scorecard_api_key' => '',
            'federal_student_aid_api_key' => '',
            'cache_duration' => 86400, // 24 hours in seconds
            'show_debug_info' => 0,
        );

        // Only add the options if they don't already exist
        foreach ($default_options as $option_name => $default_value) {
            add_option('eec_' . $option_name, $default_value);
        }

        // Create cache directory if it doesn't exist
        $upload_dir = wp_upload_dir();
        $cache_dir = $upload_dir['basedir'] . '/eec-cache';
        
        if (!file_exists($cache_dir)) {
            wp_mkdir_p($cache_dir);
            
            // Create an index.php file in the cache directory to prevent directory listing
            $index_file = $cache_dir . '/index.php';
            file_put_contents($index_file, '<?php // Silence is golden');
        }

        // Flush rewrite rules to ensure any custom endpoints work
        flush_rewrite_rules();
    }
}
