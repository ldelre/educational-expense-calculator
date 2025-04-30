<?php
/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes
 */
class Educational_Expense_Calculator {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Educational_Expense_Calculator_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('EDUCATIONAL_EXPENSE_CALCULATOR_VERSION')) {
            $this->version = EDUCATIONAL_EXPENSE_CALCULATOR_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'educational-expense-calculator';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-educational-expense-calculator-loader.php';

        /**
         * The class responsible for API integration with College Scorecard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/api/class-college-scorecard-api.php';

        /**
         * The class responsible for API integration with Federal Student Aid.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/api/class-federal-student-aid-api.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-educational-expense-calculator-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-educational-expense-calculator-public.php';

        $this->loader = new Educational_Expense_Calculator_Loader();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Educational_Expense_Calculator_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Educational_Expense_Calculator_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_shortcode('educational_expense_calculator', $plugin_public, 'display_calculator');
        
        // AJAX handlers for the calculator
        $this->loader->add_action('wp_ajax_get_schools', $plugin_public, 'get_schools_ajax');
        $this->loader->add_action('wp_ajax_nopriv_get_schools', $plugin_public, 'get_schools_ajax');
        
        $this->loader->add_action('wp_ajax_calculate_expenses', $plugin_public, 'calculate_expenses_ajax');
        $this->loader->add_action('wp_ajax_nopriv_calculate_expenses', $plugin_public, 'calculate_expenses_ajax');
        
        $this->loader->add_action('wp_ajax_check_eligibility', $plugin_public, 'check_eligibility_ajax');
        $this->loader->add_action('wp_ajax_nopriv_check_eligibility', $plugin_public, 'check_eligibility_ajax');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
