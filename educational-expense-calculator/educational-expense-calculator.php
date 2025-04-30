<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Educational_Expense_Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Educational Expense Calculator
 * Plugin URI:        https://example.com/educational-expense-calculator-uri/
 * Description:       A plugin that helps students calculate educational expenses and determine loan/grant eligibility based on school selection and income data.
 * Version:           1.0.0
 * Author:            Your Name
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       educational-expense-calculator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('EDUCATIONAL_EXPENSE_CALCULATOR_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 */
function activate_educational_expense_calculator() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-educational-expense-calculator-activator.php';
    Educational_Expense_Calculator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_educational_expense_calculator() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-educational-expense-calculator-deactivator.php';
    Educational_Expense_Calculator_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_educational_expense_calculator');
register_deactivation_hook(__FILE__, 'deactivate_educational_expense_calculator');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-educational-expense-calculator.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_educational_expense_calculator() {
    $plugin = new Educational_Expense_Calculator();
    $plugin->run();
}

run_educational_expense_calculator();
