<?php
/**
 * The College Scorecard API integration.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes/api
 */

/**
 * The College Scorecard API integration.
 *
 * This class handles all interaction with the College Scorecard API
 * to retrieve school information including costs, programs, etc.
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes/api
 */
class College_Scorecard_API {

    /**
     * The base URL for the College Scorecard API.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $api_base_url    The base URL for API requests.
     */
    private $api_base_url = 'https://api.data.gov/ed/collegescorecard/v1/';

    /**
     * The API key for the College Scorecard API.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $api_key    The API key for authentication.
     */
    private $api_key;

    /**
     * Duration in seconds to cache API responses.
     *
     * @since    1.0.0
     * @access   private
     * @var      int    $cache_duration    Cache duration in seconds.
     */
    private $cache_duration;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->api_key = get_option('eec_college_scorecard_api_key', '');
        $this->cache_duration = get_option('eec_cache_duration', 86400); // Default 24 hours
    }

    /**
     * Search for schools based on query parameters.
     *
     * @since    1.0.0
     * @param    array    $params    Array of search parameters.
     * @return   array               Array of schools matching the search criteria.
     */
    public function search_schools($params = array()) {
        // Check if we have a cached result for this query
        $cache_key = 'eec_schools_' . md5(serialize($params));
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        // Default parameters
        $default_params = array(
            'api_key' => $this->api_key,
            'per_page' => 100,
            'fields' => 'id,school.name,school.city,school.state,school.zip,latest.cost.tuition.in_state,latest.cost.tuition.out_of_state,school.school_url,school.price_calculator_url,latest.cost.avg_net_price.overall,latest.cost.attendance.academic_year,latest.aid.pell_grant_rate,latest.aid.loan_rate'
        );
        
        $query_params = wp_parse_args($params, $default_params);
        
        // Make the API request
        $response = wp_remote_get(add_query_arg($query_params, $this->api_base_url . 'schools'));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message(),
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array(
                'success' => false,
                'message' => 'Error decoding API response: ' . json_last_error_msg(),
            );
        }
        
        if (!isset($data['results']) || !is_array($data['results'])) {
            return array(
                'success' => false,
                'message' => 'Invalid API response format',
            );
        }
        
        $schools = array();
        
        foreach ($data['results'] as $school) {
            $schools[] = array(
                'id' => $school['id'],
                'name' => $school['school.name'],
                'city' => $school['school.city'],
                'state' => $school['school.state'],
                'zip' => $school['school.zip'],
                'tuition_in_state' => isset($school['latest.cost.tuition.in_state']) ? $school['latest.cost.tuition.in_state'] : null,
                'tuition_out_of_state' => isset($school['latest.cost.tuition.out_of_state']) ? $school['latest.cost.tuition.out_of_state'] : null,
                'website' => isset($school['school.school_url']) ? $school['school.school_url'] : null,
                'price_calculator_url' => isset($school['school.price_calculator_url']) ? $school['school.price_calculator_url'] : null,
                'avg_net_price' => isset($school['latest.cost.avg_net_price.overall']) ? $school['latest.cost.avg_net_price.overall'] : null,
                'total_cost' => isset($school['latest.cost.attendance.academic_year']) ? $school['latest.cost.attendance.academic_year'] : null,
                'pell_grant_rate' => isset($school['latest.aid.pell_grant_rate']) ? $school['latest.aid.pell_grant_rate'] : null,
                'loan_rate' => isset($school['latest.aid.loan_rate']) ? $school['latest.aid.loan_rate'] : null,
            );
        }
        
        $result = array(
            'success' => true,
            'metadata' => array(
                'total' => $data['metadata']['total'],
                'page' => $data['metadata']['page'],
                'per_page' => $data['metadata']['per_page'],
            ),
            'schools' => $schools,
        );
        
        // Cache the result
        set_transient($cache_key, $result, $this->cache_duration);
        
        return $result;
    }

    /**
     * Get detailed information for a specific school.
     *
     * @since    1.0.0
     * @param    string    $school_id    The school ID to retrieve.
     * @return   array                   School details.
     */
    public function get_school_details($school_id) {
        if (empty($school_id)) {
            return array(
                'success' => false,
                'message' => 'School ID is required',
            );
        }

        // Check if we have a cached result for this school
        $cache_key = 'eec_school_' . $school_id;
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        // Parameters for detailed school information
        $params = array(
            'api_key' => $this->api_key,
            'fields' => 'id,school.name,school.city,school.state,school.zip,school.school_url,school.price_calculator_url,latest.cost.tuition.in_state,latest.cost.tuition.out_of_state,latest.cost.avg_net_price.overall,latest.cost.attendance.academic_year,latest.cost.attendance.program_year,latest.cost.roomboard.oncampus,latest.cost.roomboard.offcampus,latest.cost.books,latest.cost.other,latest.aid.pell_grant_rate,latest.aid.loan_rate,latest.aid.median_debt.completers.overall,latest.programs.cip_4_digit'
        );
        
        // Make the API request
        $response = wp_remote_get(add_query_arg($params, $this->api_base_url . 'schools/' . urlencode($school_id)));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message(),
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array(
                'success' => false,
                'message' => 'Error decoding API response: ' . json_last_error_msg(),
            );
        }
        
        if (!isset($data['results']) || !is_array($data['results']) || count($data['results']) === 0) {
            return array(
                'success' => false,
                'message' => 'School not found or invalid API response format',
            );
        }
        
        $school = $data['results'][0];
        
        // Parse programs if available
        $programs = array();
        if (isset($school['latest.programs.cip_4_digit']) && is_array($school['latest.programs.cip_4_digit'])) {
            foreach ($school['latest.programs.cip_4_digit'] as $program) {
                $programs[] = array(
                    'code' => $program['code'],
                    'title' => $program['title'],
                    'credential' => isset($program['credential']['title']) ? $program['credential']['title'] : '',
                );
            }
        }
        
        $result = array(
            'success' => true,
            'school' => array(
                'id' => $school['id'],
                'name' => $school['school.name'],
                'city' => $school['school.city'],
                'state' => $school['school.state'],
                'zip' => $school['school.zip'],
                'website' => isset($school['school.school_url']) ? $school['school.school_url'] : null,
                'price_calculator_url' => isset($school['school.price_calculator_url']) ? $school['school.price_calculator_url'] : null,
                'costs' => array(
                    'tuition_in_state' => isset($school['latest.cost.tuition.in_state']) ? $school['latest.cost.tuition.in_state'] : null,
                    'tuition_out_of_state' => isset($school['latest.cost.tuition.out_of_state']) ? $school['latest.cost.tuition.out_of_state'] : null,
                    'avg_net_price' => isset($school['latest.cost.avg_net_price.overall']) ? $school['latest.cost.avg_net_price.overall'] : null,
                    'total_academic_year' => isset($school['latest.cost.attendance.academic_year']) ? $school['latest.cost.attendance.academic_year'] : null,
                    'total_program_year' => isset($school['latest.cost.attendance.program_year']) ? $school['latest.cost.attendance.program_year'] : null,
                    'room_board_oncampus' => isset($school['latest.cost.roomboard.oncampus']) ? $school['latest.cost.roomboard.oncampus'] : null,
                    'room_board_offcampus' => isset($school['latest.cost.roomboard.offcampus']) ? $school['latest.cost.roomboard.offcampus'] : null,
                    'books' => isset($school['latest.cost.books']) ? $school['latest.cost.books'] : null,
                    'other' => isset($school['latest.cost.other']) ? $school['latest.cost.other'] : null,
                ),
                'aid' => array(
                    'pell_grant_rate' => isset($school['latest.aid.pell_grant_rate']) ? $school['latest.aid.pell_grant_rate'] : null,
                    'loan_rate' => isset($school['latest.aid.loan_rate']) ? $school['latest.aid.loan_rate'] : null,
                    'median_debt' => isset($school['latest.aid.median_debt.completers.overall']) ? $school['latest.aid.median_debt.completers.overall'] : null,
                ),
                'programs' => $programs,
            ),
        );
        
        // Cache the result
        set_transient($cache_key, $result, $this->cache_duration);
        
        return $result;
    }

    /**
     * Search for programs/fields of study.
     *
     * @since    1.0.0
     * @param    string    $query    The search query for programs.
     * @return   array               Array of matching programs.
     */
    public function search_programs($query) {
        // Check if we have a cached result for this query
        $cache_key = 'eec_programs_' . md5($query);
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        $params = array(
            'api_key' => $this->api_key,
            'fields' => 'id,school.name,latest.programs.cip_4_digit',
            'per_page' => 50,
        );
        
        // Make the API request
        $response = wp_remote_get(add_query_arg($params, $this->api_base_url . 'schools'));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message(),
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array(
                'success' => false,
                'message' => 'Error decoding API response: ' . json_last_error_msg(),
            );
        }
        
        if (!isset($data['results']) || !is_array($data['results'])) {
            return array(
                'success' => false,
                'message' => 'Invalid API response format',
            );
        }
        
        $programs = array();
        $seen_programs = array();
        
        foreach ($data['results'] as $school) {
            if (!isset($school['latest.programs.cip_4_digit']) || !is_array($school['latest.programs.cip_4_digit'])) {
                continue;
            }
            
            foreach ($school['latest.programs.cip_4_digit'] as $program) {
                if (!isset($program['title'])) {
                    continue;
                }
                
                // Skip if we've already seen this program
                if (isset($seen_programs[$program['code']])) {
                    continue;
                }
                
                // If there's a search query, check if it matches the program title
                if (!empty($query) && stripos($program['title'], $query) === false) {
                    continue;
                }
                
                $programs[] = array(
                    'code' => $program['code'],
                    'title' => $program['title'],
                    'credential' => isset($program['credential']['title']) ? $program['credential']['title'] : '',
                );
                
                $seen_programs[$program['code']] = true;
            }
        }
        
        $result = array(
            'success' => true,
            'programs' => $programs,
        );
        
        // Cache the result
        set_transient($cache_key, $result, $this->cache_duration);
        
        return $result;
    }
}
