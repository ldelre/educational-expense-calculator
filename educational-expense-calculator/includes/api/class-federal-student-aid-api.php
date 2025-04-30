<?php
/**
 * The Federal Student Aid API integration.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes/api
 */

/**
 * The Federal Student Aid API integration.
 *
 * This class handles all interaction with the Federal Student Aid API
 * to retrieve loan and grant eligibility information.
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/includes/api
 */
class Federal_Student_Aid_API {

    /**
     * The base URL for the Federal Student Aid API.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $api_base_url    The base URL for API requests.
     */
    private $api_base_url = 'https://api.data.gov/ed/studentaid/v1/';

    /**
     * The API key for the Federal Student Aid API.
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
        $this->api_key = get_option('eec_federal_student_aid_api_key', '');
        $this->cache_duration = get_option('eec_cache_duration', 86400); // Default 24 hours
    }

    /**
     * Get loan program information.
     *
     * @since    1.0.0
     * @return   array    Array of loan programs and eligibility requirements.
     */
    public function get_loan_programs() {
        // Check if we have a cached result
        $cache_key = 'eec_loan_programs';
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        // Note: Since we don't have direct access to a Federal Student Aid API,
        // we're implementing a simulated response with accurate information.
        // In a real-world scenario, you would make the API call here.
        
        // Simulated loan programs data based on real Federal Student Aid information
        $loan_programs = array(
            array(
                'id' => 'direct_subsidized',
                'name' => 'Direct Subsidized Loans',
                'description' => 'Direct Subsidized Loans are available to undergraduate students with financial need. The U.S. Department of Education pays the interest while you\'re in school at least half-time, for the first six months after you leave school (grace period), and during deferment periods.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => true,
                    'enrollment_requirement' => 'at least half-time',
                    'degree_level' => 'undergraduate',
                ),
                'interest_rate' => '4.99%', // Rate for 2022-2023
                'annual_limits' => array(
                    'first_year_dependent' => 3500,
                    'first_year_independent' => 3500,
                    'second_year_dependent' => 4500,
                    'second_year_independent' => 4500,
                    'third_year_and_beyond_dependent' => 5500,
                    'third_year_and_beyond_independent' => 5500,
                ),
                'aggregate_limits' => array(
                    'dependent' => 23000,
                    'independent' => 23000,
                ),
                'origination_fee' => '1.057%',
            ),
            array(
                'id' => 'direct_unsubsidized',
                'name' => 'Direct Unsubsidized Loans',
                'description' => 'Direct Unsubsidized Loans are available to undergraduate and graduate students; there is no requirement to demonstrate financial need. Interest accrues from the time the loan is disbursed, and you are responsible for paying all interest.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => false,
                    'enrollment_requirement' => 'at least half-time',
                    'degree_level' => 'undergraduate and graduate',
                ),
                'interest_rate' => array(
                    'undergraduate' => '4.99%',
                    'graduate' => '6.54%',
                ),
                'annual_limits' => array(
                    'first_year_dependent' => 5500,
                    'first_year_independent' => 9500,
                    'second_year_dependent' => 6500,
                    'second_year_independent' => 10500,
                    'third_year_and_beyond_dependent' => 7500,
                    'third_year_and_beyond_independent' => 12500,
                    'graduate' => 20500,
                ),
                'aggregate_limits' => array(
                    'dependent_undergraduate' => 31000,
                    'independent_undergraduate' => 57500,
                    'graduate' => 138500,
                ),
                'origination_fee' => '1.057%',
            ),
            array(
                'id' => 'direct_plus',
                'name' => 'Direct PLUS Loans',
                'description' => 'Direct PLUS Loans are available to graduate or professional students and parents of dependent undergraduate students. Credit check required; borrower must not have adverse credit history.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => false,
                    'enrollment_requirement' => 'at least half-time',
                    'degree_level' => 'graduate or parent of undergraduate',
                    'credit_check_required' => true,
                ),
                'interest_rate' => '7.54%',
                'annual_limits' => 'up to cost of attendance minus other financial aid received',
                'aggregate_limits' => 'none',
                'origination_fee' => '4.228%',
            ),
        );
        
        $result = array(
            'success' => true,
            'loan_programs' => $loan_programs,
        );
        
        // Cache the result
        set_transient($cache_key, $result, $this->cache_duration);
        
        return $result;
    }

    /**
     * Get grant program information.
     *
     * @since    1.0.0
     * @return   array    Array of grant programs and eligibility requirements.
     */
    public function get_grant_programs() {
        // Check if we have a cached result
        $cache_key = 'eec_grant_programs';
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        // Note: Since we don't have direct access to a Federal Student Aid API,
        // we're implementing a simulated response with accurate information.
        // In a real-world scenario, you would make the API call here.
        
        // Simulated grant programs data based on real Federal Student Aid information
        $grant_programs = array(
            array(
                'id' => 'pell_grant',
                'name' => 'Federal Pell Grant',
                'description' => 'Federal Pell Grants are usually awarded only to undergraduate students who display exceptional financial need and have not earned a bachelor\'s, graduate, or professional degree.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => true,
                    'degree_level' => 'undergraduate',
                    'prior_degree_restrictions' => 'no bachelor\'s or graduate degree',
                ),
                'maximum_award' => 6895, // For 2022-2023 award year
                'application' => 'FAFSA required',
            ),
            array(
                'id' => 'fseog',
                'name' => 'Federal Supplemental Educational Opportunity Grant (FSEOG)',
                'description' => 'The FSEOG program provides need-based grants to low-income undergraduate students to promote access to postsecondary education. Priority is given to students who receive Federal Pell Grants.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => true,
                    'degree_level' => 'undergraduate',
                    'priority' => 'Pell Grant recipients',
                ),
                'award_range' => array(
                    'min' => 100,
                    'max' => 4000,
                ),
                'application' => 'FAFSA required',
            ),
            array(
                'id' => 'teach_grant',
                'name' => 'Teacher Education Assistance for College and Higher Education (TEACH) Grant',
                'description' => 'The TEACH Grant Program provides grants to students who are completing or plan to complete coursework needed to begin a career in teaching.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => false,
                    'academic_requirement' => 'maintain 3.25 GPA',
                    'service_agreement' => 'teach in high-need field at school serving low-income students for 4 years',
                ),
                'maximum_award' => 4000,
                'application' => 'FAFSA required, TEACH Grant Agreement to Serve',
            ),
            array(
                'id' => 'iraq_afghanistan_service_grant',
                'name' => 'Iraq and Afghanistan Service Grant',
                'description' => 'The Iraq and Afghanistan Service Grant is available to students whose parent or guardian was a member of the U.S. armed forces and died as a result of military service performed in Iraq or Afghanistan after the events of 9/11.',
                'eligibility' => array(
                    'must_demonstrate_financial_need' => false,
                    'parent_service_requirement' => 'parent/guardian died in military service in Iraq or Afghanistan after 9/11',
                    'age_requirement' => 'under 24 years old or enrolled in college at time of parent\'s death',
                    'pell_eligibility' => 'not eligible for Pell Grant based on EFC',
                ),
                'maximum_award' => 6124.79, // For 2022-2023 award year
                'application' => 'FAFSA required',
            ),
        );
        
        $result = array(
            'success' => true,
            'grant_programs' => $grant_programs,
        );
        
        // Cache the result
        set_transient($cache_key, $result, $this->cache_duration);
        
        return $result;
    }

    /**
     * Calculate eligibility for financial aid based on provided parameters.
     *
     * @since    1.0.0
     * @param    array    $params    Parameters including income, dependency status, etc.
     * @return   array               Financial aid eligibility information.
     */
    public function calculate_eligibility($params) {
        // Required parameters validation
        if (!isset($params['income']) || !is_numeric($params['income'])) {
            return array(
                'success' => false,
                'message' => 'Valid income information is required.',
            );
        }
        
        if (!isset($params['dependency_status']) || !in_array($params['dependency_status'], array('dependent', 'independent'))) {
            return array(
                'success' => false,
                'message' => 'Valid dependency status is required.',
            );
        }
        
        if (!isset($params['school_cost']) || !is_numeric($params['school_cost'])) {
            return array(
                'success' => false,
                'message' => 'Valid school cost information is required.',
            );
        }
        
        // Extract parameters
        $income = (float) $params['income'];
        $dependency_status = $params['dependency_status'];
        $school_cost = (float) $params['school_cost'];
        $academic_level = isset($params['academic_level']) ? $params['academic_level'] : 'first_year';
        $degree_level = isset($params['degree_level']) ? $params['degree_level'] : 'undergraduate';
        $household_size = isset($params['household_size']) ? intval($params['household_size']) : 1;
        
        // Get loan and grant programs
        $loan_programs_data = $this->get_loan_programs();
        $grant_programs_data = $this->get_grant_programs();
        
        if (!$loan_programs_data['success'] || !$grant_programs_data['success']) {
            return array(
                'success' => false,
                'message' => 'Error retrieving financial aid programs.',
            );
        }
        
        $loan_programs = $loan_programs_data['loan_programs'];
        $grant_programs = $grant_programs_data['grant_programs'];
        
        // Calculate estimated Expected Family Contribution (EFC)
        // This is a simplified formula for demonstration purposes
        $efc = $this->calculate_estimated_efc($income, $dependency_status, $household_size);
        
        // Calculate estimated Pell Grant award
        $pell_grant_award = $this->calculate_pell_grant($efc);
        
        // Determine loan eligibility based on academic level and dependency status
        $loan_eligibility = array();
        
        foreach ($loan_programs as $loan) {
            // Skip if degree level doesn't match
            if ($loan['id'] === 'direct_subsidized' && $degree_level !== 'undergraduate') {
                continue;
            }
            
            // Skip Direct PLUS for undergraduates (student borrowers)
            if ($loan['id'] === 'direct_plus' && $degree_level === 'undergraduate' && !isset($params['is_parent'])) {
                continue;
            }
            
            $max_amount = 0;
            
            if ($loan['id'] === 'direct_plus') {
                // PLUS loans can cover up to the cost of attendance minus other aid
                $max_amount = $school_cost - $pell_grant_award;
                
                if (isset($loan_eligibility['direct_subsidized']['amount'])) {
                    $max_amount -= $loan_eligibility['direct_subsidized']['amount'];
                }
                
                if (isset($loan_eligibility['direct_unsubsidized']['amount'])) {
                    $max_amount -= $loan_eligibility['direct_unsubsidized']['amount'];
                }
            } else {
                // For subsidized and unsubsidized loans, get the appropriate annual limit
                $annual_limit_key = $academic_level . '_' . $dependency_status;
                
                if (isset($loan['annual_limits'][$annual_limit_key])) {
                    $max_amount = $loan['annual_limits'][$annual_limit_key];
                } elseif ($degree_level === 'graduate' && isset($loan['annual_limits']['graduate'])) {
                    $max_amount = $loan['annual_limits']['graduate'];
                }
                
                // For subsidized loans, the amount is also limited by the financial need
                if ($loan['id'] === 'direct_subsidized') {
                    $financial_need = $school_cost - $efc;
                    $max_amount = min($max_amount, $financial_need);
                    
                    // If no financial need, not eligible for subsidized loans
                    if ($financial_need <= 0) {
                        $max_amount = 0;
                    }
                }
            }
            
            // Only include if eligible for some amount
            if ($max_amount > 0) {
                $loan_eligibility[$loan['id']] = array(
                    'name' => $loan['name'],
                    'amount' => $max_amount,
                    'interest_rate' => is_array($loan['interest_rate']) ? $loan['interest_rate'][$degree_level] : $loan['interest_rate'],
                    'origination_fee' => $loan['origination_fee'],
                );
            }
        }
        
        // Determine grant eligibility
        $grant_eligibility = array();
        
        foreach ($grant_programs as $grant) {
            // Default to not eligible
            $eligible = false;
            $amount = 0;
            
            switch ($grant['id']) {
                case 'pell_grant':
                    if ($degree_level === 'undergraduate' && $pell_grant_award > 0) {
                        $eligible = true;
                        $amount = $pell_grant_award;
                    }
                    break;
                    
                case 'fseog':
                    // Priority to Pell Grant recipients with exceptional need
                    if ($degree_level === 'undergraduate' && $pell_grant_award > 0 && $efc < 1000) {
                        $eligible = true;
                        // Simplified determination - in reality this varies by school
                        $amount = 1000;
                    }
                    break;
                    
                case 'teach_grant':
                    // Simplified eligibility check
                    if (isset($params['planning_to_teach']) && $params['planning_to_teach'] === true) {
                        $eligible = true;
                        $amount = $grant['maximum_award'];
                    }
                    break;
                    
                case 'iraq_afghanistan_service_grant':
                    // Simplified eligibility check
                    if (isset($params['parent_died_in_service']) && $params['parent_died_in_service'] === true) {
                        $eligible = true;
                        $amount = $grant['maximum_award'];
                    }
                    break;
            }
            
            if ($eligible) {
                $grant_eligibility[$grant['id']] = array(
                    'name' => $grant['name'],
                    'amount' => $amount,
                );
            }
        }
        
        // Calculate total aid and remaining cost
        $total_grants = 0;
        foreach ($grant_eligibility as $grant) {
            $total_grants += $grant['amount'];
        }
        
        $total_loans = 0;
        foreach ($loan_eligibility as $loan) {
            $total_loans += $loan['amount'];
        }
        
        $total_aid = $total_grants + $total_loans;
        $remaining_cost = max(0, $school_cost - $total_aid);
        
        // Final eligibility result
        $result = array(
            'success' => true,
            'efc' => $efc,
            'cost_of_attendance' => $school_cost,
            'grants' => $grant_eligibility,
            'loans' => $loan_eligibility,
            'summary' => array(
                'total_grants' => $total_grants,
                'total_loans' => $total_loans,
                'total_aid' => $total_aid,
                'remaining_cost' => $remaining_cost,
            ),
        );
        
        return $result;
    }

    /**
     * Calculate an estimated Expected Family Contribution (EFC).
     *
     * @since    1.0.0
     * @param    float     $income              Annual household income.
     * @param    string    $dependency_status   Dependency status ('dependent' or 'independent').
     * @param    int       $household_size      Number of people in household.
     * @return   float                          Estimated EFC.
     */
    private function calculate_estimated_efc($income, $dependency_status, $household_size) {
        // This is a simplified formula for demonstration purposes
        // In reality, the EFC formula is much more complex and considers many factors
        
        // Simplified income protection allowance based on household size
        $income_protection = array(
            1 => 10840,
            2 => 17380,
            3 => 21620,
            4 => 26680,
            5 => 31480,
            6 => 36880,
        );
        
        $protection_amount = isset($income_protection[$household_size]) ? 
            $income_protection[$household_size] : 
            $income_protection[6] + (($household_size - 6) * 4160);
        
        // Available income after protection
        $available_income = max(0, $income - $protection_amount);
        
        // Contribution rate varies by dependency status
        $contribution_rate = ($dependency_status === 'dependent') ? 0.50 : 0.70;
        
        // Calculate EFC
        $efc = $available_income * $contribution_rate;
        
        // EFC is always rounded to whole dollar amount
        return round($efc);
    }

    /**
     * Calculate Pell Grant amount based on EFC.
     *
     * @since    1.0.0
     * @param    float    $efc    Expected Family Contribution.
     * @return   float            Estimated Pell Grant amount.
     */
    private function calculate_pell_grant($efc) {
        // Maximum Pell Grant for 2022-2023 award year
        $max_pell = 6895;
        
        // EFC cutoff for Pell Grant eligibility in 2022-2023
        $efc_cutoff = 6206;
        
        if ($efc > $efc_cutoff) {
            return 0;
        }
        
        // Simple linear reduction based on EFC
        $pell_amount = $max_pell - $efc;
        
        // Minimum Pell Grant is $650 (if eligible)
        return max(650, $pell_amount);
    }
}
