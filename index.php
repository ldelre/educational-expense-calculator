<?php
/**
 * Educational Expense Calculator Plugin Demo Page
 * 
 * This file serves as a demonstration of the Educational Expense Calculator plugin.
 * In a real WordPress environment, the plugin would be installed and activated
 * through the WordPress admin interface.
 */

// Set headers to prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Expense Calculator Plugin Demo</title>
    <!-- Include plugin CSS -->
    <link rel="stylesheet" href="educational-expense-calculator/public/css/educational-expense-calculator-public.css">
    <!-- Additional demo page styling -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .demo-header {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .demo-header h1 {
            color: #0073aa;
            margin-top: 0;
        }
        
        .demo-info {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .demo-note {
            background-color: #e5f5fa;
            border-left: 4px solid #00a0d2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .demo-footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .api-keys-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
        
        .api-key-field {
            margin-bottom: 10px;
        }
        
        .api-key-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .api-key-field input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .api-key-submit {
            background-color: #0073aa;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .api-key-submit:hover {
            background-color: #005f8b;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <div class="demo-header">
            <h1>Educational Expense Calculator Plugin Demo</h1>
            <p>This is a demonstration of the Educational Expense Calculator WordPress plugin, which helps students calculate educational expenses and determine loan/grant eligibility based on school selection and income data.</p>
        </div>
        
        <div class="demo-info">
            <h2>About This Plugin</h2>
            <p>The Educational Expense Calculator is a comprehensive WordPress plugin designed to help students and families plan for the costs of higher education. It provides a user-friendly interface for searching schools, calculating expenses, and determining eligibility for federal financial aid programs.</p>
            
            <h3>Key Features:</h3>
            <ul>
                <li>Search and select from a database of U.S. colleges and universities</li>
                <li>Calculate tuition, room and board, and other expenses</li>
                <li>Determine eligibility for federal student loans and grants</li>
                <li>Responsive design that works on all devices</li>
                <li>Customizable settings for administrators</li>
            </ul>
            
            <div class="demo-note">
                <strong>Note:</strong> In a real WordPress environment, this plugin would be installed and activated through the WordPress admin interface. This demo page shows how the plugin would appear when embedded in a WordPress page using the <code>[educational_expense_calculator]</code> shortcode.
            </div>
            
            <div class="api-keys-section">
                <h3>API Configuration</h3>
                <p>To use the calculator with real data, you would need to configure the following API keys in the WordPress admin settings:</p>
                
                <form id="api-keys-form">
                    <div class="api-key-field">
                        <label for="college-scorecard-api-key">College Scorecard API Key:</label>
                        <input type="text" id="college-scorecard-api-key" name="college-scorecard-api-key" placeholder="Enter your College Scorecard API key">
                    </div>
                    
                    <div class="api-key-field">
                        <label for="federal-student-aid-api-key">Federal Student Aid API Key:</label>
                        <input type="text" id="federal-student-aid-api-key" name="federal-student-aid-api-key" placeholder="Enter your Federal Student Aid API key">
                    </div>
                    
                    <button type="button" class="api-key-submit" id="save-api-keys">Save API Keys</button>
                </form>
                
                <p><small>Get your API keys from <a href="https://api.data.gov/signup/" target="_blank">https://api.data.gov/signup/</a></small></p>
            </div>
        </div>
        
        <!-- Display the calculator as it would appear in WordPress -->
        <div class="eec-calculator-container">
            <div class="eec-calculator-header">
                <h2 class="eec-calculator-title">Educational Expense Calculator</h2>
                <div class="eec-calculator-description">Calculate your educational expenses and determine loan/grant eligibility based on your school selection and income data.</div>
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
            </div>
        </div>
        
        <div class="demo-footer">
            <p>This is a demonstration of the Educational Expense Calculator WordPress Plugin.</p>
            <p>&copy; 2025 Educational Expense Calculator. All rights reserved.</p>
        </div>
    </div>
    
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Include plugin JS -->
    <script>
        // This is a simplified version of the plugin JS for demo purposes
        (function($) {
            'use strict';
            
            // Variables to simulate AJAX responses
            const simulatedSchools = [
                { id: '001', name: 'Harvard University', city: 'Cambridge', state: 'MA' },
                { id: '002', name: 'Stanford University', city: 'Stanford', state: 'CA' },
                { id: '003', name: 'Massachusetts Institute of Technology', city: 'Cambridge', state: 'MA' },
                { id: '004', name: 'University of California, Berkeley', city: 'Berkeley', state: 'CA' },
                { id: '005', name: 'University of Michigan', city: 'Ann Arbor', state: 'MI' }
            ];
            
            const simulatedExpenses = {
                school_name: '',
                academic_year: '2024-2025',
                expenses: {
                    tuition: 45000,
                    room_and_board: 15000,
                    books: 1200,
                    other: 2500,
                    total: 63700
                }
            };
            
            const simulatedEligibility = {
                efc: 12000,
                need: 51700,
                grants: {
                    pell_grant: { name: 'Federal Pell Grant', amount: 6895, eligibility: 'Eligible' },
                    fseog: { name: 'Federal Supplemental Educational Opportunity Grant', amount: 2000, eligibility: 'Eligible' }
                },
                loans: {
                    direct_subsidized: { name: 'Direct Subsidized Loans', amount: 3500, eligibility: 'Eligible' },
                    direct_unsubsidized: { name: 'Direct Unsubsidized Loans', amount: 2000, eligibility: 'Eligible' }
                }
            };
            
            // Store application state
            const state = {
                currentStep: 1,
                selectedSchool: null,
                expenseData: null,
                eligibilityData: null
            };
            
            // Initialize the calculator
            function initCalculator() {
                setupEventListeners();
                hideLoading();
                
                // Hide the loading spinner initially
                $('#eec-expense-loading').hide();
                $('#eec-eligibility-loading').hide();
            }
            
            // Set up all event listeners
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
                $('#eec-print-results').on('click', function() {
                    window.print();
                });
                
                // API Keys form
                $('#save-api-keys').on('click', function() {
                    alert('API keys saved successfully! (In a real WordPress environment, these would be saved to the database.)');
                });
            }
            
            // Navigate to a specific step
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
            
            // Go to the next step
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
            
            // Go to the previous step
            function goToPreviousStep() {
                const prevStep = state.currentStep - 1;
                if (prevStep >= 1) {
                    goToStep(prevStep);
                }
            }
            
            // Validate the current step
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
            
            // Handle school search
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
                
                // Simulate AJAX request (in the real plugin this would call the WordPress AJAX endpoint)
                setTimeout(function() {
                    // Filter schools by query
                    const results = simulatedSchools.filter(school => 
                        school.name.toLowerCase().includes(query.toLowerCase()) ||
                        school.city.toLowerCase().includes(query.toLowerCase()) ||
                        school.state.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    displaySchoolResults(results);
                }, 1000);
            }
            
            // Display school search results
            function displaySchoolResults(schools) {
                const $results = $('#eec-search-results');
                
                if (!schools || schools.length === 0) {
                    $results.html('<p>No schools found matching your search.</p>');
                    return;
                }
                
                let html = '<ul class="eec-school-list">';
                
                schools.forEach(function(school) {
                    html += `
                        <li class="eec-school-item" data-school-id="${school.id}">
                            <div class="eec-school-name">${school.name}</div>
                            <div class="eec-school-location">${school.city}, ${school.state}</div>
                        </li>
                    `;
                });
                
                html += '</ul>';
                
                // Add pagination info
                html += `<div class="eec-pagination-info">Showing ${schools.length} results</div>`;
                
                $results.html(html);
                
                // Add click event for school selection
                $('.eec-school-item').on('click', function() {
                    const schoolId = $(this).data('school-id');
                    const schoolName = $(this).find('.eec-school-name').text();
                    const schoolLocation = $(this).find('.eec-school-location').text();
                    
                    selectSchool(schoolId, schoolName, schoolLocation);
                });
            }
            
            // Select a school
            function selectSchool(id, name, location) {
                // Update state
                state.selectedSchool = {
                    id: id,
                    name: name,
                    location: location
                };
                
                // Update simulated data
                simulatedExpenses.school_name = name;
                
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
            
            // Calculate expenses
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
                
                // Simulate expense calculation (adjust values based on selections)
                if (residency === 'out_of_state') {
                    simulatedExpenses.expenses.tuition = 55000;
                    simulatedExpenses.expenses.total = 73700;
                } else {
                    simulatedExpenses.expenses.tuition = 45000;
                    simulatedExpenses.expenses.total = 63700;
                }
                
                if (housing === 'off_campus') {
                    simulatedExpenses.expenses.room_and_board = 12000;
                    simulatedExpenses.expenses.total -= 3000;
                } else {
                    simulatedExpenses.expenses.room_and_board = 15000;
                }
                
                // Simulate AJAX delay
                setTimeout(function() {
                    $('#eec-expense-loading').hide();
                    state.expenseData = simulatedExpenses;
                    displayExpenseResults(simulatedExpenses);
                }, 1000);
            }
            
            // Display expense calculation results
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
            
            // Calculate eligibility
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
                const income = parseFloat($('#eec-income').val());
                const householdSize = parseInt($('#eec-household-size').val());
                const dependencyStatus = $('#eec-dependency-status').val();
                const academicLevel = $('#eec-academic-level').val();
                const degreeLevel = $('#eec-degree-level').val();
                
                // Adjust eligibility based on inputs
                
                // Adjust EFC based on income and household size
                simulatedEligibility.efc = Math.round(income * 0.22 - (householdSize * 1500));
                if (simulatedEligibility.efc < 0) simulatedEligibility.efc = 0;
                
                // Calculate need
                simulatedEligibility.need = state.expenseData.expenses.total - simulatedEligibility.efc;
                
                // Adjust Pell Grant based on EFC and degree level
                if (degreeLevel === 'graduate') {
                    simulatedEligibility.grants.pell_grant.amount = 0;
                    simulatedEligibility.grants.pell_grant.eligibility = 'Not Eligible (Graduate Student)';
                } else if (simulatedEligibility.efc > 6000) {
                    simulatedEligibility.grants.pell_grant.amount = 0;
                    simulatedEligibility.grants.pell_grant.eligibility = 'Not Eligible (EFC too high)';
                } else if (simulatedEligibility.efc > 3000) {
                    simulatedEligibility.grants.pell_grant.amount = 3000;
                }
                
                // Adjust loan amounts based on academic level and dependency status
                if (academicLevel === 'first_year') {
                    if (dependencyStatus === 'dependent') {
                        simulatedEligibility.loans.direct_subsidized.amount = 3500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 2000;
                    } else {
                        simulatedEligibility.loans.direct_subsidized.amount = 3500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 6000;
                    }
                } else if (academicLevel === 'second_year') {
                    if (dependencyStatus === 'dependent') {
                        simulatedEligibility.loans.direct_subsidized.amount = 4500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 2000;
                    } else {
                        simulatedEligibility.loans.direct_subsidized.amount = 4500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 6000;
                    }
                } else {
                    if (dependencyStatus === 'dependent') {
                        simulatedEligibility.loans.direct_subsidized.amount = 5500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 2000;
                    } else {
                        simulatedEligibility.loans.direct_subsidized.amount = 5500;
                        simulatedEligibility.loans.direct_unsubsidized.amount = 7000;
                    }
                }
                
                // Simulate API delay
                setTimeout(function() {
                    state.eligibilityData = simulatedEligibility;
                    displayResults();
                }, 1500);
            }
            
            // Display results
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
                
                const totals = {
                    grants: 0,
                    loans: 0
                };
                
                // Calculate totals
                Object.values(eligibilityData.grants).forEach(grant => {
                    if (grant.amount > 0) totals.grants += grant.amount;
                });
                
                Object.values(eligibilityData.loans).forEach(loan => {
                    if (loan.amount > 0) totals.loans += loan.amount;
                });
                
                const totalAid = totals.grants + totals.loans;
                const remainingCost = Math.max(0, expenseData.expenses.total - totalAid);
                
                let html = `
                    <div class="eec-results-header">
                        <h3>Financial Aid Summary for ${expenseData.school_name}</h3>
                        <p class="eec-academic-year">Academic Year: ${expenseData.academic_year}</p>
                    </div>
                    
                    <div class="eec-results-summary">
                        <div class="eec-summary-item">
                            <div class="eec-summary-label">Total Cost of Attendance</div>
                            <div class="eec-summary-value">${formatCurrency(expenseData.expenses.total)}</div>
                        </div>
                        <div class="eec-summary-item">
                            <div class="eec-summary-label">Expected Family Contribution</div>
                            <div class="eec-summary-value">${formatCurrency(eligibilityData.efc)}</div>
                        </div>
                        <div class="eec-summary-item">
                            <div class="eec-summary-label">Financial Need</div>
                            <div class="eec-summary-value">${formatCurrency(eligibilityData.need)}</div>
                        </div>
                        <div class="eec-summary-item">
                            <div class="eec-summary-label">Total Financial Aid</div>
                            <div class="eec-summary-value">${formatCurrency(totalAid)}</div>
                        </div>
                    </div>
                    
                    <div class="eec-results-section">
                        <h4>Grants (Do Not Need to be Repaid)</h4>
                        <div class="eec-results-table">
                            <div class="eec-results-row eec-results-header-row">
                                <div class="eec-results-cell">Grant Program</div>
                                <div class="eec-results-cell">Eligibility</div>
                                <div class="eec-results-cell eec-results-value">Amount</div>
                            </div>
                `;
                
                // Add grants
                Object.values(eligibilityData.grants).forEach(grant => {
                    html += `
                        <div class="eec-results-row">
                            <div class="eec-results-cell">${grant.name}</div>
                            <div class="eec-results-cell">${grant.eligibility}</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(grant.amount)}</div>
                        </div>
                    `;
                });
                
                html += `
                        <div class="eec-results-row eec-results-highlight">
                            <div class="eec-results-cell">Total Grants</div>
                            <div class="eec-results-cell"></div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(totals.grants)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="eec-results-section">
                    <h4>Loans (Must Be Repaid With Interest)</h4>
                    <div class="eec-results-table">
                        <div class="eec-results-row eec-results-header-row">
                            <div class="eec-results-cell">Loan Program</div>
                            <div class="eec-results-cell">Eligibility</div>
                            <div class="eec-results-cell eec-results-value">Amount</div>
                        </div>
                `;
                
                // Add loans
                Object.values(eligibilityData.loans).forEach(loan => {
                    html += `
                        <div class="eec-results-row">
                            <div class="eec-results-cell">${loan.name}</div>
                            <div class="eec-results-cell">${loan.eligibility}</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(loan.amount)}</div>
                        </div>
                    `;
                });
                
                html += `
                        <div class="eec-results-row eec-results-highlight">
                            <div class="eec-results-cell">Total Loans</div>
                            <div class="eec-results-cell"></div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(totals.loans)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="eec-results-section">
                    <h4>Summary</h4>
                    <div class="eec-results-table">
                        <div class="eec-results-row">
                            <div class="eec-results-cell">Total Cost of Attendance</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(expenseData.expenses.total)}</div>
                        </div>
                        <div class="eec-results-row">
                            <div class="eec-results-cell">Total Financial Aid</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(totalAid)}</div>
                        </div>
                        <div class="eec-results-row eec-results-highlight">
                            <div class="eec-results-cell">Remaining Cost</div>
                            <div class="eec-results-cell eec-results-value">${formatCurrency(remainingCost)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="eec-results-notes">
                    <h4>Next Steps:</h4>
                    <ul>
                        <li>Contact the financial aid office at ${expenseData.school_name} for specific information.</li>
                        <li>Complete the Free Application for Federal Student Aid (FAFSA) at <a href="https://studentaid.gov/h/apply-for-aid/fafsa" target="_blank">studentaid.gov</a>.</li>
                        <li>Research additional scholarships and grants that may be available to you.</li>
                        <li>Consider work-study opportunities at your school to help offset costs.</li>
                    </ul>
                </div>
                `;
                
                $('#eec-results-container').html(html);
            }
            
            // Show an error message
            function showError(message) {
                $('#eec-search-error').html(`<div class="eec-error-message">${message}</div>`);
            }
            
            // Hide loading spinners
            function hideLoading() {
                $('#eec-expense-loading').hide();
                $('#eec-eligibility-loading').hide();
            }
            
            // Reset the calculator
            function resetCalculator() {
                // Reset state
                state.currentStep = 1;
                state.selectedSchool = null;
                state.expenseData = null;
                state.eligibilityData = null;
                
                // Reset form fields
                $('#eec-school-search').val('');
                $('#eec-selected-school').empty();
                $('#eec-search-results').empty();
                $('#eec-search-error').empty();
                $('#eec-income').val('');
                $('#eec-residency').val('in_state');
                $('#eec-housing').val('on_campus');
                $('#eec-household-size').val('1');
                $('#eec-dependency-status').val('dependent');
                $('#eec-academic-level').val('first_year');
                $('#eec-degree-level').val('undergraduate');
                $('#eec-planning-to-teach').prop('checked', false);
                $('#eec-parent-died-in-service').prop('checked', false);
                $('#eec-is-parent').prop('checked', false);
                
                // Reset results
                $('#eec-expense-results').empty();
                $('#eec-results-container').empty();
                
                // Disable next button
                $('#eec-step-1-next').prop('disabled', true);
                
                // Go to step 1
                goToStep(1);
            }
            
            // Initialize when document is ready
            $(document).ready(function() {
                initCalculator();
            });
            
        })(jQuery);
    </script>
</body>
</html>