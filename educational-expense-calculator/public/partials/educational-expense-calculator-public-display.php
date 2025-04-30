<?php
/**
 * Provide a public-facing view for the calculator
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Educational_Expense_Calculator
 * @subpackage Educational_Expense_Calculator/public/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="eec-calculator-container">
    <div class="eec-calculator-header">
        <h2 class="eec-calculator-title"><?php echo esc_html($atts['title']); ?></h2>
        <div class="eec-calculator-description"><?php echo wp_kses_post($atts['description']); ?></div>
    </div>
    
    <div class="eec-calculator-body">
        <!-- Step Navigation -->
        <div class="eec-steps">
            <div class="eec-step active" data-step="1">
                <span class="eec-step-number">1</span>
                <span class="eec-step-label">School</span>
            </div>
            <div class="eec-step" data-step="2">
                <span class="eec-step-number">2</span>
                <span class="eec-step-label">Expenses</span>
            </div>
            <div class="eec-step" data-step="3">
                <span class="eec-step-number">3</span>
                <span class="eec-step-label">Eligibility</span>
            </div>
            <div class="eec-step" data-step="4">
                <span class="eec-step-number">4</span>
                <span class="eec-step-label">Results</span>
            </div>
        </div>
        
        <!-- Step 1: School Selection -->
        <div class="eec-form-step active" id="eec-step-1">
            <div class="eec-form-group">
                <label for="eec-school-search">Search for your school:</label>
                <div class="eec-search-container">
                    <input type="text" id="eec-school-search" class="eec-school-search" placeholder="Enter school name (at least 3 characters)">
                    <button type="button" class="eec-search-button" id="eec-search-button">
                        <span class="eec-search-icon">🔍</span>
                    </button>
                </div>
                <div class="eec-search-results" id="eec-search-results"></div>
                <div class="eec-search-error" id="eec-search-error"></div>
            </div>
            
            <div class="eec-selected-school" id="eec-selected-school"></div>
            
            <div class="eec-form-actions">
                <button type="button" class="eec-button eec-button-primary eec-next-button" id="eec-step-1-next" disabled>Next: Expenses</button>
            </div>
        </div>
        
        <!-- Step 2: Expense Calculation -->
        <div class="eec-form-step" id="eec-step-2">
            <div class="eec-form-group">
                <label for="eec-residency">Select your residency status:</label>
                <select id="eec-residency" class="eec-form-control">
                    <option value="in_state">In-State</option>
                    <option value="out_of_state">Out-of-State</option>
                </select>
            </div>
            
            <div class="eec-form-group">
                <label for="eec-housing">Select your housing plan:</label>
                <select id="eec-housing" class="eec-form-control">
                    <option value="on_campus">On-Campus</option>
                    <option value="off_campus">Off-Campus</option>
                </select>
            </div>
            
            <div class="eec-expense-loading" id="eec-expense-loading">
                <div class="eec-loading-spinner"></div>
                <p>Calculating expenses...</p>
            </div>
            
            <div class="eec-expense-results" id="eec-expense-results"></div>
            
            <div class="eec-form-actions">
                <button type="button" class="eec-button eec-button-secondary eec-back-button">Back</button>
                <button type="button" class="eec-button eec-button-primary eec-next-button" id="eec-step-2-next">Next: Eligibility</button>
            </div>
        </div>
        
        <!-- Step 3: Eligibility Information -->
        <div class="eec-form-step" id="eec-step-3">
            <div class="eec-form-group">
                <label for="eec-income">Annual Household Income ($):</label>
                <input type="number" id="eec-income" class="eec-form-control" placeholder="Enter your annual household income" min="0">
            </div>
            
            <div class="eec-form-group">
                <label for="eec-household-size">Household Size:</label>
                <select id="eec-household-size" class="eec-form-control">
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i === 1 ? 'person' : 'people'; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="eec-form-group">
                <label for="eec-dependency-status">Dependency Status:</label>
                <select id="eec-dependency-status" class="eec-form-control">
                    <option value="dependent">Dependent Student</option>
                    <option value="independent">Independent Student</option>
                </select>
                <div class="eec-form-help">
                    <p>
                        <strong>Dependent Student:</strong> If you're under 24, not married, have no dependents, not a veteran, and not in foster care.
                    </p>
                    <p>
                        <strong>Independent Student:</strong> If you're 24 or older, married, have dependents, a veteran, or in foster care.
                    </p>
                </div>
            </div>
            
            <div class="eec-form-group">
                <label for="eec-academic-level">Academic Year Level:</label>
                <select id="eec-academic-level" class="eec-form-control">
                    <option value="first_year">First Year</option>
                    <option value="second_year">Second Year</option>
                    <option value="third_year_and_beyond">Third Year and Beyond</option>
                </select>
            </div>
            
            <div class="eec-form-group">
                <label for="eec-degree-level">Degree Level:</label>
                <select id="eec-degree-level" class="eec-form-control">
                    <option value="undergraduate">Undergraduate</option>
                    <option value="graduate">Graduate</option>
                </select>
            </div>
            
            <div class="eec-form-group">
                <label>Additional Qualifications:</label>
                <div class="eec-checkbox-group">
                    <div class="eec-checkbox">
                        <input type="checkbox" id="eec-planning-to-teach" name="eec-planning-to-teach">
                        <label for="eec-planning-to-teach">Planning to become a teacher in a high-need field</label>
                    </div>
                    <div class="eec-checkbox">
                        <input type="checkbox" id="eec-parent-died-in-service" name="eec-parent-died-in-service">
                        <label for="eec-parent-died-in-service">Parent/guardian died while serving in military after 9/11</label>
                    </div>
                    <div class="eec-checkbox">
                        <input type="checkbox" id="eec-is-parent" name="eec-is-parent">
                        <label for="eec-is-parent">I am a parent of an undergraduate student (for PLUS loans)</label>
                    </div>
                </div>
            </div>
            
            <div class="eec-eligibility-loading" id="eec-eligibility-loading">
                <div class="eec-loading-spinner"></div>
                <p>Calculating eligibility...</p>
            </div>
            
            <div class="eec-form-actions">
                <button type="button" class="eec-button eec-button-secondary eec-back-button">Back</button>
                <button type="button" class="eec-button eec-button-primary eec-next-button" id="eec-step-3-next">Calculate Results</button>
            </div>
        </div>
        
        <!-- Step 4: Results -->
        <div class="eec-form-step" id="eec-step-4">
            <div class="eec-results-container" id="eec-results-container"></div>
            
            <div class="eec-form-actions">
                <button type="button" class="eec-button eec-button-secondary eec-back-button">Back</button>
                <button type="button" class="eec-button eec-button-secondary" id="eec-start-over">Start Over</button>
                <button type="button" class="eec-button eec-button-primary" id="eec-print-results">Print Results</button>
            </div>
        </div>
    </div>
    
    <div class="eec-calculator-footer">
        <p class="eec-disclaimer">
            Disclaimer: This calculator provides estimates based on the information you provide and current federal financial aid programs.
            Actual costs and aid eligibility may vary. Please contact your school's financial aid office for more specific information.
        </p>
        
        <?php if (current_user_can('manage_options') && get_option('eec_show_debug_info', false)) : ?>
        <div class="eec-debug-info">
            <h4>Debug Information (Admin Only)</h4>
            <div id="eec-debug-output"></div>
        </div>
        <?php endif; ?>
    </div>
</div>
