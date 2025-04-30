<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for
 * the admin area functionality of the plugin.
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/admin
 */
class Educational_Expense_Calculator_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/educational-expense-calculator-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/educational-expense-calculator-admin.js', array('jquery'), $this->version, false);
        
        // Localize the script with data for AJAX calls
        wp_localize_script($this->plugin_name, 'eec_admin_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eec_admin_nonce'),
        ));
    }

    /**
     * Add options page to the admin menu
     *
     * @since    1.0.0
     */
    public function add_options_page() {
        add_options_page(
            'Educational Expense Calculator Settings',
            'Edu Expense Calculator',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_options_page')
        );
    }

    /**
     * Display the options page content
     *
     * @since    1.0.0
     */
    public function display_options_page() {
        include_once 'partials/educational-expense-calculator-admin-display.php';
    }

    /**
     * Register settings for the plugin
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register a setting for College Scorecard API key
        register_setting(
            'eec_options',
            'eec_college_scorecard_api_key',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            )
        );
        
        // Register a setting for Federal Student Aid API key
        register_setting(
            'eec_options',
            'eec_federal_student_aid_api_key',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            )
        );
        
        // Register a setting for cache duration (in seconds)
        register_setting(
            'eec_options',
            'eec_cache_duration',
            array(
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => 86400, // 24 hours
            )
        );
        
        // Register a setting for showing debug info
        register_setting(
            'eec_options',
            'eec_show_debug_info',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'default' => false,
            )
        );
        
        // Register a setting for calculator title
        register_setting(
            'eec_options',
            'eec_calculator_title',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 'Educational Expense Calculator',
            )
        );
        
        // Register a setting for calculator description
        register_setting(
            'eec_options',
            'eec_calculator_description',
            array(
                'type' => 'string',
                'sanitize_callback' => 'wp_kses_post',
                'default' => 'Calculate your educational expenses and determine loan/grant eligibility based on your school selection and income data.',
            )
        );
        
        // Add sections for the plugin settings
        add_settings_section(
            'eec_api_settings',
            'API Settings',
            array($this, 'api_settings_section_callback'),
            $this->plugin_name
        );
        
        add_settings_section(
            'eec_display_settings',
            'Display Settings',
            array($this, 'display_settings_section_callback'),
            $this->plugin_name
        );
        
        add_settings_section(
            'eec_cache_settings',
            'Cache Settings',
            array($this, 'cache_settings_section_callback'),
            $this->plugin_name
        );
        
        // Add fields for API settings
        add_settings_field(
            'eec_college_scorecard_api_key',
            'College Scorecard API Key',
            array($this, 'college_scorecard_api_key_callback'),
            $this->plugin_name,
            'eec_api_settings'
        );
        
        add_settings_field(
            'eec_federal_student_aid_api_key',
            'Federal Student Aid API Key',
            array($this, 'federal_student_aid_api_key_callback'),
            $this->plugin_name,
            'eec_api_settings'
        );
        
        // Add fields for display settings
        add_settings_field(
            'eec_calculator_title',
            'Calculator Title',
            array($this, 'calculator_title_callback'),
            $this->plugin_name,
            'eec_display_settings'
        );
        
        add_settings_field(
            'eec_calculator_description',
            'Calculator Description',
            array($this, 'calculator_description_callback'),
            $this->plugin_name,
            'eec_display_settings'
        );
        
        add_settings_field(
            'eec_show_debug_info',
            'Show Debug Information',
            array($this, 'show_debug_info_callback'),
            $this->plugin_name,
            'eec_display_settings'
        );
        
        // Add fields for cache settings
        add_settings_field(
            'eec_cache_duration',
            'Cache Duration (seconds)',
            array($this, 'cache_duration_callback'),
            $this->plugin_name,
            'eec_cache_settings'
        );
    }

    /**
     * Sanitize checkbox inputs
     *
     * @since    1.0.0
     * @param    mixed    $input    Input value to sanitize
     * @return   boolean            Sanitized value
     */
    public function sanitize_checkbox($input) {
        return (isset($input) && true == $input) ? true : false;
    }

    /**
     * API Settings section callback
     *
     * @since    1.0.0
     */
    public function api_settings_section_callback() {
        echo '<p>Enter your API keys for the services used by the Educational Expense Calculator.</p>';
    }

    /**
     * Display Settings section callback
     *
     * @since    1.0.0
     */
    public function display_settings_section_callback() {
        echo '<p>Customize how the calculator is displayed on your site.</p>';
    }

    /**
     * Cache Settings section callback
     *
     * @since    1.0.0
     */
    public function cache_settings_section_callback() {
        echo '<p>Configure caching to improve performance and reduce API calls.</p>';
    }

    /**
     * College Scorecard API Key field callback
     *
     * @since    1.0.0
     */
    public function college_scorecard_api_key_callback() {
        $api_key = get_option('eec_college_scorecard_api_key', '');
        ?>
        <input type="text" name="eec_college_scorecard_api_key" id="eec_college_scorecard_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
        <p class="description">
            Enter your College Scorecard API key. You can get a key from <a href="https://api.data.gov/signup/" target="_blank">https://api.data.gov/signup/</a>.
        </p>
        <?php
    }

    /**
     * Federal Student Aid API Key field callback
     *
     * @since    1.0.0
     */
    public function federal_student_aid_api_key_callback() {
        $api_key = get_option('eec_federal_student_aid_api_key', '');
        ?>
        <input type="text" name="eec_federal_student_aid_api_key" id="eec_federal_student_aid_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
        <p class="description">
            Enter your Federal Student Aid API key. You can get a key from <a href="https://api.data.gov/signup/" target="_blank">https://api.data.gov/signup/</a>.
        </p>
        <?php
    }

    /**
     * Calculator Title field callback
     *
     * @since    1.0.0
     */
    public function calculator_title_callback() {
        $title = get_option('eec_calculator_title', 'Educational Expense Calculator');
        ?>
        <input type="text" name="eec_calculator_title" id="eec_calculator_title" value="<?php echo esc_attr($title); ?>" class="regular-text">
        <p class="description">
            Enter the title to display above the calculator.
        </p>
        <?php
    }

    /**
     * Calculator Description field callback
     *
     * @since    1.0.0
     */
    public function calculator_description_callback() {
        $description = get_option('eec_calculator_description', 'Calculate your educational expenses and determine loan/grant eligibility based on your school selection and income data.');
        ?>
        <textarea name="eec_calculator_description" id="eec_calculator_description" rows="3" class="large-text"><?php echo esc_textarea($description); ?></textarea>
        <p class="description">
            Enter the description to display below the calculator title.
        </p>
        <?php
    }

    /**
     * Show Debug Info field callback
     *
     * @since    1.0.0
     */
    public function show_debug_info_callback() {
        $show_debug = get_option('eec_show_debug_info', false);
        ?>
        <input type="checkbox" name="eec_show_debug_info" id="eec_show_debug_info" value="1" <?php checked(1, $show_debug, true); ?>>
        <label for="eec_show_debug_info">
            Show debug information in the calculator (for administrators only)
        </label>
        <?php
    }

    /**
     * Cache Duration field callback
     *
     * @since    1.0.0
     */
    public function cache_duration_callback() {
        $duration = get_option('eec_cache_duration', 86400);
        ?>
        <input type="number" name="eec_cache_duration" id="eec_cache_duration" value="<?php echo esc_attr($duration); ?>" class="small-text" min="0">
        <p class="description">
            Enter the number of seconds to cache API responses. Use 0 to disable caching.<br>
            Common values: 3600 (1 hour), 86400 (1 day), 604800 (1 week)
        </p>
        <?php
    }

    /**
     * AJAX handler for clearing the plugin cache
     *
     * @since    1.0.0
     */
    public function clear_cache_ajax() {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'eec_admin_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Check user capability
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to perform this action.'));
        }
        
        // Get all transients with our prefix
        global $wpdb;
        $transients = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '%_transient_eec_%'"
        );
        
        $count = 0;
        if ($transients) {
            foreach ($transients as $transient) {
                $transient_name = str_replace('_transient_', '', $transient->option_name);
                delete_transient($transient_name);
                $count++;
            }
        }
        
        wp_send_json_success(array(
            'message' => sprintf('%d cache items cleared successfully.', $count),
        ));
    }
}
