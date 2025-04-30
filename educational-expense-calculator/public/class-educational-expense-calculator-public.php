<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for
 * the public-facing side of the site.
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/public
 */
class Educational_Expense_Calculator_Public {

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
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/educational-expense-calculator-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/educational-expense-calculator-public.js', array('jquery'), $this->version, false);
        
        // Localize the script with data for AJAX calls
        wp_localize_script($this->plugin_name, 'eec_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eec_nonce'),
        ));
    }

    /**
     * Render the calculator via shortcode
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            HTML output for the calculator.
     */
    public function display_calculator($atts) {
        // Extract shortcode attributes
        $atts = shortcode_atts(array(
            'title' => get_option('eec_calculator_title', 'Educational Expense Calculator'),
            'description' => get_option('eec_calculator_description', 'Calculate your educational expenses and determine loan/grant eligibility based on your school selection and income data.'),
        ), $atts, 'educational_expense_calculator');
        
        // Start output buffering
        ob_start();
        
        // Include the calculator template
        include plugin_dir_path(__FILE__) . 'partials/educational-expense-calculator-public-display.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * AJAX handler for school search
     *
     * @since    1.0.0
     */
    public function get_schools_ajax() {
        // Check nonce for security
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'eec_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Get search query
        $query = isset($_GET['query']) ? sanitize_text_field($_GET['query']) : '';
        
        if (empty($query) || strlen($query) < 3) {
            wp_send_json_error(array('message' => 'Please enter at least 3 characters for search.'));
        }
        
        // Initialize College Scorecard API
        $college_api = new College_Scorecard_API();
        
        // Set up search parameters
        $params = array(
            'school.name' => $query,
            'per_page' => 10,
            'sort' => 'school.name:asc',
        );
        
        // Perform the search
        $results = $college_api->search_schools($params);
        
        if (!$results['success']) {
            wp_send_json_error(array('message' => $results['message']));
        }
        
        // Send back the results
        wp_send_json_success($results);
    }

    /**
     * AJAX handler for expense calculation
     *
     * @since    1.0.0
     */
    public function calculate_expenses_ajax() {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'eec_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Get school ID
        $school_id = isset($_POST['school_id']) ? sanitize_text_field($_POST['school_id']) : '';
        
        if (empty($school_id)) {
            wp_send_json_error(array('message' => 'Please select a school.'));
        }
        
        // Get additional parameters
        $residency = isset($_POST['residency']) ? sanitize_text_field($_POST['residency']) : 'in_state';
        $housing = isset($_POST['housing']) ? sanitize_text_field($_POST['housing']) : 'on_campus';
        
        // Initialize College Scorecard API
        $college_api = new College_Scorecard_API();
        
        // Get detailed school information
        $school_details = $college_api->get_school_details($school_id);
        
        if (!$school_details['success']) {
            wp_send_json_error(array('message' => $school_details['message']));
        }
        
        $school = $school_details['school'];
        
        // Calculate expenses based on parameters
        $tuition = ($residency === 'in_state') ? 
            $school['costs']['tuition_in_state'] : 
            $school['costs']['tuition_out_of_state'];
        
        $room_and_board = ($housing === 'on_campus') ? 
            $school['costs']['room_board_oncampus'] : 
            $school['costs']['room_board_offcampus'];
        
        // Books and supplies
        $books = $school['costs']['books'];
        
        // Other expenses
        $other = $school['costs']['other'];
        
        // Calculate total
        $total = $tuition + $room_and_board + $books + $other;
        
        // Prepare the response
        $response = array(
            'school_name' => $school['name'],
            'expenses' => array(
                'tuition' => $tuition,
                'room_and_board' => $room_and_board,
                'books' => $books,
                'other' => $other,
                'total' => $total,
            ),
            'academic_year' => date('Y') . '-' . (date('Y') + 1),
        );
        
        wp_send_json_success($response);
    }

    /**
     * AJAX handler for eligibility check
     *
     * @since    1.0.0
     */
    public function check_eligibility_ajax() {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'eec_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Validate required parameters
        $required_fields = array('income', 'dependency_status', 'academic_level', 'degree_level', 'school_cost');
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                wp_send_json_error(array('message' => 'All fields are required for eligibility check.'));
            }
        }
        
        // Get parameters and sanitize
        $params = array(
            'income' => (float) sanitize_text_field($_POST['income']),
            'dependency_status' => sanitize_text_field($_POST['dependency_status']),
            'academic_level' => sanitize_text_field($_POST['academic_level']),
            'degree_level' => sanitize_text_field($_POST['degree_level']),
            'school_cost' => (float) sanitize_text_field($_POST['school_cost']),
        );
        
        // Optional parameters
        if (isset($_POST['household_size'])) {
            $params['household_size'] = intval(sanitize_text_field($_POST['household_size']));
        }
        
        if (isset($_POST['planning_to_teach'])) {
            $params['planning_to_teach'] = ($_POST['planning_to_teach'] === 'true');
        }
        
        if (isset($_POST['parent_died_in_service'])) {
            $params['parent_died_in_service'] = ($_POST['parent_died_in_service'] === 'true');
        }
        
        if (isset($_POST['is_parent'])) {
            $params['is_parent'] = ($_POST['is_parent'] === 'true');
        }
        
        // Initialize Federal Student Aid API
        $fsa_api = new Federal_Student_Aid_API();
        
        // Calculate eligibility
        $eligibility = $fsa_api->calculate_eligibility($params);
        
        if (!$eligibility['success']) {
            wp_send_json_error(array('message' => $eligibility['message']));
        }
        
        // Send back the results
        wp_send_json_success($eligibility);
    }
}
