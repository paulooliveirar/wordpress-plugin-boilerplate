<?php 
/**
 * Example Plugin
 *
 * @package           ExamplePlugin
 * @author            Paulo Oliveira Rodrigues
 * @copyright         2021 Paulo O. Rodrigues
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Example Plugin
 * Plugin URI:        https://github.com/paulooliveirar/wordpress-plugin-boilerplate
 * Description:       This plugin aims to help other programmers in creating their first wordpress plugin or just make their lives easier.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Paulo Oliveira Rodrigues
 * Author URI:        https://themeforest.net/user/paulooliveirar
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/paulooliveirar/wordpress-plugin-boilerplate
 * Text Domain:       example-plugin
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ABSPATH' ) ) {
    die( 'We\'re sorry, but you can not directly access this file.' );
}

if(WP_DEBUG == true){
    define( 'WP_DEBUG_POPUP', TRUE );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );
define( 'PLUGIN_NAME', 'Example Plugin' );
define( 'PLUGIN_PACKAGE', 'example-plugin' );
define( 'PLUGIN_PATH_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_SHORT_PATH', 'example-plugin.php' );

function example_plugin_activate() 
{
    require_once PLUGIN_PATH_DIR . 'includes/class-example-plugin-activator.php';
}

function example_plugin_deactivate() 
{
    require_once PLUGIN_PATH_DIR . 'includes/class-example-plugin-deactivator.php';
}

function example_plugin_uninstall()
{

}

/** 
 * 
 */
register_activation_hook( __FILE__, 'example_plugin_activate');

/** 
 * 
 */
//register_deactivation_hook(__FILE__, 'example_plugin_deactivate');

/** 
 * 
 */
register_uninstall_hook(__FILE__, 'example_plugin_uninstall');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-example-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function render_plugin(){
    if(is_admin()){ 
        require_once PLUGIN_PATH_DIR . 'includes/class-example-plugin.php';
        require_once PLUGIN_PATH_DIR . 'admin/template.php'; 
        require_once PLUGIN_PATH_DIR . 'includes/sql-querys.php';
        $plugin = new \ExamplePlugin\Includes\ExamplePlugin();
        $plugin->run();
        new \ExamplePlugin\Admin\Template();
        new \ExamplePlugin\Includes\SqlQuerys();
    }

    if(!is_admin()){
        //require_once PLUGIN_PATH_DIR . '/public/template.php';
    }
}

render_plugin();