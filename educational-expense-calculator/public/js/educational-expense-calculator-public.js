/**
 * Public JavaScript for the Educational Expense Calculator plugin.
 *
 * Handles all the client-side functionality for the calculator including:
 * - School search and selection
 * - Expense calculation
 * - Eligibility determination
 * - Results display
 *
 * @since      1.0.0
 */
(function($) {
    'use strict';

    // Store application state
    const state = {
        currentStep: 1,
        selectedSchool: null,
        expenseData: null,
        eligibilityData: null
    };

    /**
     * Initialize the calculator
     */
    function initCalculator() {
        setupEventListeners();
        setupStepNavigation();
    }

    /**
     * Set up all event listeners
     */
    function setupEventListeners() {
        // School search
        $('#eec-search-button').on('click', handleSchoolSearch);
        $('#eec-school-search').on('keyup', function(e) {
            if (e.key === 'Enter') {
                handleSchoolSearch();
            }
        });
        
        // Step navigation buttons
        $('.eec-next-button').on('click', goToNextStep);
        $('.eec-back-button').on('click', goToPreviousStep);
        
        // Step indicator clicks
        $('.eec-step').on('click', function() {
            // Only allow going to steps that have been completed
            const step = parseInt($(this).data('step'));
            if (step < state.currentStep) {
                goToStep(step);
            }
        });
        
        // Expense calculation triggers
        $('#eec-residency, #eec-housing').on('change', calculateExpenses);
        
        // Start over button
        $('#eec-start-over').on('click', resetCalculator);
        
        // Print results button
        $('#eec-print-results').on('click', printResults);
    }

    /**
     * Set up step navigation
     */
    function setupStepNavigation() {
        // Show current step
        goToStep(1);
    }

    /**
     * Navigate to a specific step
     * 
     * @param {number} step - Step number to navigate to
     */
    function goToStep(step) {
        // Hide all steps
        $('.eec-form-step').removeClass('active');
        
        // Show the requested step
        $(`#eec-step-${step}`).addClass('active');
        
        // Update step indicators
        $('.eec-step').removeClass('active completed');
        for (let i = 1; i <= 4; i++) {
            if (i < step) {
                $(`.eec-step[data-step="${i}"]`).addClass('completed');
            } else if (i === step) {
                $(`.eec-step[data-step="${i}"]`).addClass('active');
            }
        }
        
        // Update current step in state
        state.currentStep = step;
    }

    /**
     * Go to the next step
     */
    function goToNextStep() {
        // Validate current step before proceeding
        if (validateCurrentStep()) {
            const nextStep = state.currentStep + 1;
            if (nextStep <= 4) {
                // For step 2, calculate expenses when entering
                if (nextStep === 2) {
                    calculateExpenses();
                }
                
                // For step 4, calculate eligibility when entering
                if (nextStep === 4) {
                    calculateEligibility();
                }
                
                goToStep(nextStep);
            }
        }
    }

    /**
     * Go to the previous step
     */
    function goToPreviousStep() {
        const prevStep = state.currentStep - 1;
        if (prevStep >= 1) {
            goToStep(prevStep);
        }
    }

    /**
     * Validate the current step
     * 
     * @returns {boolean} - Whether the current step is valid
     */
    function validateCurrentStep() {
        switch (state.currentStep) {
            case 1:
                // School selection
                if (!state.selectedSchool) {
                    showError('Please select a school to continue.');
                    return false;
                }
                return true;
                
            case 2:
                // Expense calculation - always valid
                return true;
                
            case 3:
                // Eligibility information
                const income = $('#eec-income').val();
                if (!income || isNaN(income) || parseFloat(income) < 0) {
                    showError('Please enter a valid income amount.');
                    return false;
                }
                return true;
                
            default:
                return true;
        }
    }

    /**
     * Handle school search
     */
    function handleSchoolSearch() {
        const query = $('#eec-school-search').val().trim();
        
        // Validate query length
        if (query.length < 3) {
            showError('Please enter at least 3 characters to search.');
            return;
        }
        
        // Show loading state
        $('#eec-search-results').html('<div class="eec-loading-spinner"></div><p>Searching schools...</p>');
        
        // Clear any errors
        $('#eec-search-error').empty();
        
        // Make AJAX request
        $.ajax({
            url: eec_vars.ajax_url,
            type: 'GET',
            data: {
                action: 'get_schools',
                query: query,
                nonce: eec_vars.nonce
            },
            success: function(response) {
                if (response.success) {
                    displaySchoolResults(response.data);
                } else {
                    showError(response.data.message || 'An error occurred while searching for schools.');
                    $('#eec-search-results').empty();
                }
            },
            error: function() {
                showError('Failed to connect to the server. Please try again.');
                $('#eec-search-results').empty();
            }
        });
    }

    /**
     * Display school search results
     * 
     * @param {object} data - School search results data
     */
    function displaySchoolResults(data) {
        const $results = $('#eec-search-results');
        
        if (!data.schools || data.schools.length === 0) {
            $results.html('<p>No schools found matching your search.</p>');
            return;
        }
        
        let html = '<ul class="eec-school-list">';
        
        data.schools.forEach(function(school) {
            html += `
                <li class="eec-school-item" data-school-id="${school.id}">
                    <div class="eec-school-name">${school.name}</div>
                    <div class="eec-school-location">${school.city}, ${school.state}</div>
                </li>
            `;
        });
        
        html += '</ul>';
        
        // Add pagination info if available
        if (data.metadata) {
            html += `<div class="eec-pagination-info">Showing ${data.schools.length} of ${data.metadata.total} results</div>`;
        }
        
        $results.html(html);
        
        // Add click event for school selection
        $('.eec-school-item').on('click', function() {
            const schoolId = $(this).data('school-id');
            const schoolName = $(this).find('.eec-school-name').text();
            const schoolLocation = $(this).find('.eec-school-location').text();
            
            selectSchool(schoolId, schoolName, schoolLocation);
        });
    }

    /**
     * Select a school
     * 
     * @param {string} id - School ID
     * @param {string} name - School name
     * @param {string} location - School location
     */
    function selectSchool(id, name, location) {
        // Update state
        state.selectedSchool = {
            id: id,
            name: name,
            location: location
        };
        
        // Display selected school
        const html = `
            <div class="eec-school-card">
                <h3>${name}</h3>
                <p>${location}</p>
                <button type="button" class="eec-button eec-button-small eec-change-school">Change School</button>
            </div>
        `;
        
        $('#eec-selected-school').html(html);
        
        // Clear search results
        $('#eec-search-results').empty();
        $('#eec-school-search').val('');
        
        // Enable next button
        $('#eec-step-1-next').prop('disabled', false);
        
        // Add event listener for change school button
        $('.eec-change-school').on('click', function() {
            // Clear selected school
            state.selectedSchool = null;
            $('#eec-selected-school').empty();
            
            // Disable next button
            $('#eec-step-1-next').prop('disabled', true);
        });
    }

    /**
     * Calculate expenses based on selected school and options
     */
    function calculateExpenses() {
        if (!state.selectedSchool) {
            return;
        }
        
        // Show loading state
        $('#eec-expense-loading').show();
        $('#eec-expense-results').empty();
        
        // Get form values
        const residency = $('#eec-residency').val();
        const housing = $('#eec-housing').val();
        
        // Make AJAX request
        $.ajax({
            url: eec_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'calculate_expenses',
                school_id: state.selectedSchool.id,
                residency: residency,
                housing: housing,
                nonce: eec_vars.nonce
            },
            success: function(response) {
                $('#eec-expense-loading').hide();
                
                if (response.success) {
                    state.expenseData = response.data;
                    displayExpenseResults(response.data);
                } else {
                    showError(response.data.message || 'An error occurred while calculating expenses.');
                }
            },
            error: function() {
                $('#eec-expense-loading').hide();
                showError('Failed to connect to the server. Please try again.');
            }
        });
    }

    /**
     * Display expense calculation results
     * 
     * @param {object} data - Expense calculation data
     */
    function displayExpenseResults(data) {
        const expenses = data.expenses;
        
        // Format currency 
        const formatCurrency = (amount) => {
            return '$' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };
        
        let html = `
            <h3>Annual Expenses for ${data.school_name}</h3>
            <p class="eec-academic-year">Academic Year: ${data.academic_year}</p>
            
            <div class="eec-expense-breakdown">
                <div class="eec-expense-item">
                    <div class="eec-expense-label">Tuition and Fees</div>
                    <div class="eec-expense-amount">${formatCurrency(expenses.tuition)}</div>
                </div>
                <div class="eec-expense-item">
                    <div class="eec-expense-label">Room and Board</div>
                    <div class="eec-expense-amount">${formatCurrency(expenses.room_and_board)}</div>
                </div>
                <div class="eec-expense-item">
                    <div class="eec-expense-label">Books and Supplies</div>
                    <div class="eec-expense-amount">${formatCurrency(expenses.books)}</div>
                </div>
                <div class="eec-expense-item">
                    <div class="eec-expense-label">Other Expenses</div>
                    <div class="eec-expense-amount">${formatCurrency(expenses.other)}</div>
                </div>
                <div class="eec-expense-item eec-expense-total">
                    <div class="eec-expense-label">Total Cost of Attendance</div>
                    <div class="eec-expense-amount">${formatCurrency(expenses.total)}</div>
                </div>
            </div>
        `;
        
        $('#eec-expense-results').html(html);
    }

    /**
     * Calculate financial aid eligibility
     */
    function calculateEligibility() {
        if (!state.expenseData) {
            showError('Please calculate expenses first.');
            return;
        }
        
        // Show loading state
        $('#eec-results-container').empty();
        $('#eec-results-container').html(`
            <div class="eec-loading-spinner"></div>
            <p>Calculating financial aid eligibility...</p>
        `);
        
        // Get form values
        const params = {
            income: $('#eec-income').val(),
            household_size: $('#eec-household-size').val(),
            dependency_status: $('#eec-dependency-status').val(),
            academic_level: $('#eec-academic-level').val(),
            degree_level: $('#eec-degree-level').val(),
            school_cost: state.expenseData.expenses.total,
            planning_to_teach: $('#eec-planning-to-teach').is(':checked') ? 'true' : 'false',
            parent_died_in_service: $('#eec-parent-died-in-service').is(':checked') ? 'true' : 'false', 
            is_parent: $('#eec-is-parent').is(':checked') ? 'true' : 'false',
            nonce: eec_vars.nonce
        };
        
        // Debug info
        if ($('#eec-debug-output').length) {
            $('#eec-debug-output').html(`<pre>${JSON.stringify(params, null, 2)}</pre>`);
        }
        
        // Make AJAX request
        $.ajax({
            url: eec_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'check_eligibility',
                ...params
            },
            success: function(response) {
                if (response.success) {
                    state.eligibilityData = response.data;
                    displayResults();
                } else {
                    $('#eec-results-container').html(`
                        <div class="eec-error-message">
                            ${response.data.message || 'An error occurred while calculating eligibility.'}
                        </div>
                    `);
                }
            },
            error: function() {
                $('#eec-results-container').html(`
                    <div class="eec-error-message">
                        Failed to connect to the server. Please try again.
                    </div>
                `);
            }
        });
    }

    /**
     * Display final results
     */
    function displayResults() {
        if (!state.selectedSchool || !state.expenseData || !state.eligibilityData) {
            return;
        }
        
        const expenseData = state.expenseData;
        const eligibilityData = state.eligibilityData;
        
        // Format currency 
        const formatCurrency = (amount) => {
            if (amount === null || amount === undefined) return 'N/A';
            return '$' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };
        
        // Format percentage
        const formatPercent = (decimal) => {
            if (decimal === null || decimal === undefined) return 'N/A';
            return (decimal * 100).toFixed(1) + '%';
        };
        
        let html = `
            <div class="eec-results-header">
                <h3>Financial Aid Summary for ${expenseData.school_name}</h3>
                <p class="eec-academic-year">Academic Year: ${expenseData.academic_year}</p>
            </div>
            
            <div class="eec-results-summary">
                <div class="eec-summary-item">
                    <div class="eec-summary-label">Expected Family Contribution (EFC)</div>
                    <div class="eec-summary-value">${formatCurrency(eligibilityData.efc)}</div>
                </div>
                <div class="eec-summary-item">
                    <div class="eec-summary-label">Total Cost of Attendance</div>
                    <div class="eec-summary-value">${formatCurrency(eligibilityData.cost_of_attendance)}</div>
                </div>
            </div>
            
            <div class="eec-results-sections">
                <div class="eec-results-section">
                    <h4>Grant Eligibility</h4>
                    ${renderGrantsSection(eligibilityData.grants)}
                </div>
                
                <div class="eec-results-section">
                    <h4>Loan Eligibility</h4>
                    ${renderLoansSection(eligibilityData.loans)}
                </div>
                
                <div class="eec-results-section">
                    <h4>Financial Aid Summary</h4>
                    <div class="eec-results-table">
                        <div class="eec-results-row">
                            <div class="eec-results-cell">Total Grants</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(eligibilityData.summary.total_grants)}</div>
                        </div>
                        <div class="eec-results-row">
                            <div class="eec-results-cell">Total Loans</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(eligibilityData.summary.total_loans)}</div>
                        </div>
                        <div class="eec-results-row">
                            <div class="eec-results-cell">Total Financial Aid</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(eligibilityData.summary.total_aid)}</div>
                        </div>
                        <div class="eec-results-row eec-results-highlight">
                            <div class="eec-results-cell">Remaining Cost (Out of Pocket)</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(eligibilityData.summary.remaining_cost)}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="eec-results-notes">
                <h4>Important Notes</h4>
                <ul>
                    <li>These are estimated figures based on the information you provided and federal aid guidelines.</li>
                    <li>Actual financial aid amounts may vary based on your specific circumstances and the school's policies.</li>
                    <li>Contact the financial aid office at ${expenseData.school_name} for more detailed information.</li>
                    <li>Remember to complete the Free Application for Federal Student Aid (FAFSA) to apply for federal aid.</li>
                </ul>
            </div>
        `;
        
        $('#eec-results-container').html(html);
    }

    /**
     * Render the grants section of the results
     * 
     * @param {object} grants - Grants data
     * @returns {string} - HTML for grants section
     */
    function renderGrantsSection(grants) {
        if (!grants || Object.keys(grants).length === 0) {
            return '<p>Based on the information provided, you may not be eligible for federal grants.</p>';
        }
        
        let html = '<div class="eec-results-table">';
        html += `
            <div class="eec-results-row eec-results-header-row">
                <div class="eec-results-cell">Grant Program</div>
                <div class="eec-results-cell">Estimated Amount</div>
            </div>
        `;
        
        for (const grantId in grants) {
            const grant = grants[grantId];
            html += `
                <div class="eec-results-row">
                    <div class="eec-results-cell">${grant.name}</div>
                    <div class="eec-results-cell eec-results-value">$${grant.amount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}</div>
                </div>
            `;
        }
        
        html += '</div>';
        return html;
    }

    /**
     * Render the loans section of the results
     * 
     * @param {object} loans - Loans data
     * @returns {string} - HTML for loans section
     */
    function renderLoansSection(loans) {
        if (!loans || Object.keys(loans).length === 0) {
            return '<p>Based on the information provided, you may not be eligible for federal student loans.</p>';
        }
        
        let html = '<div class="eec-results-table">';
        html += `
            <div class="eec-results-row eec-results-header-row">
                <div class="eec-results-cell">Loan Program</div>
                <div class="eec-results-cell">Maximum Amount</div>
                <div class="eec-results-cell">Interest Rate</div>
                <div class="eec-results-cell">Origination Fee</div>
            </div>
        `;
        
        for (const loanId in loans) {
            const loan = loans[loanId];
            html += `
                <div class="eec-results-row">
                    <div class="eec-results-cell">${loan.name}</div>
                    <div class="eec-results-cell eec-results-value">$${loan.amount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}</div>
                    <div class="eec-results-cell">${loan.interest_rate}</div>
                    <div class="eec-results-cell">${loan.origination_fee}</div>
                </div>
            `;
        }
        
        html += '</div>';
        return html;
    }

    /**
     * Reset the calculator to the initial state
     */
    function resetCalculator() {
        // Reset state
        state.currentStep = 1;
        state.selectedSchool = null;
        state.expenseData = null;
        state.eligibilityData = null;
        
        // Clear form fields
        $('#eec-school-search').val('');
        $('#eec-search-results').empty();
        $('#eec-selected-school').empty();
        $('#eec-expense-results').empty();
        $('#eec-income').val('');
        $('#eec-planning-to-teach').prop('checked', false);
        $('#eec-parent-died-in-service').prop('checked', false);
        $('#eec-is-parent').prop('checked', false);
        
        // Reset selects to defaults
        $('#eec-residency').val('in_state');
        $('#eec-housing').val('on_campus');
        $('#eec-household-size').val('1');
        $('#eec-dependency-status').val('dependent');
        $('#eec-academic-level').val('first_year');
        $('#eec-degree-level').val('undergraduate');
        
        // Disable next button
        $('#eec-step-1-next').prop('disabled', true);
        
        // Go back to step 1
        goToStep(1);
    }

    /**
     * Print the results
     */
    function printResults() {
        window.print();
    }

    /**
     * Show an error message
     * 
     * @param {string} message - Error message to display
     */
    function showError(message) {
        $('#eec-search-error').html(`<div class="eec-error-message">${message}</div>`);
    }

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        initCalculator();
    });

})(jQuery);
