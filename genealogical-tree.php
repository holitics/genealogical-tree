<?php

/**
 * Genealogical Tree - Pro Plugin
 *
 * This is the main plugin file, which is responsible for loading the plugin,
 * defining activation and deactivation hooks, and including dependencies.
 *
 * @link              https://wordpress.org/plugins/genealogical-tree
 * @since             1.0.0
 * @package           Genealogical_Tree
 *
 * @wordpress-plugin
 * Plugin Name:       Genealogical Tree
 * Plugin URI:        https://wordpress.org/plugins/genealogical-tree
 * Description:       The ultimate solution for creating and displaying family trees and family history on WordPress.
 * Version:           2.2.4
 * Author:            ak devs
 * Author URI:        https://github.com/akdevsfr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       genealogical-tree
 * Domain Path:       /languages
 * @fs_premium_only:   /admin/genealogical-tree-handel-import.php, /admin/genealogical-tree-handel-import-csv.php, /admin/genealogical-tree-handel-export.php, /includes/php-gedcom/, /admin/partials/genealogical-tree-meta-tree-settings-premium.php, /includes/class-genealogical-tree-api.php
 */
// Abort if this file is called directly.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Autoload dependencies using Composer.
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';
/**
 * Freemius SDK Initialization and Setup.
 *
 * Initializes the Freemius SDK for managing plugin subscriptions and licensing.
 *
 * @return mixed The Freemius SDK instance.
 */
if ( function_exists( 'gt_fs' ) ) {
    gt_fs()->set_basename( false, __FILE__ );
} else {
    // if gt_fs not defined.
    if ( !function_exists( 'gt_fs' ) ) {
        function gt_fs() {
            global $gt_fs;
            if ( !isset( $gt_fs ) ) {
                // Enable multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_3592_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_3592_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $gt_fs = fs_dynamic_init( array(
                    'id'             => '3592',
                    'slug'           => 'genealogical-tree',
                    'premium_slug'   => 'genealogical-tree-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e7259dba96b5463b7e746506d5e2c',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => true,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                    'menu'           => array(
                        'slug'       => 'genealogical-tree',
                        'first-path' => '/edit-tags.php?taxonomy=gt-family-group&post_type=gt-member',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $gt_fs;
        }

        // Initialize the Freemius SDK.
        gt_fs();
        // Trigger an action to signal that the SDK has been loaded.
        do_action( 'gt_fs_loaded' );
    }
    /**
     * Define plugin constants.
     */
    define( 'GENEALOGICAL_TREE_VERSION', '2.2.4' );
    define( 'GENEALOGICAL_TREE_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'GENEALOGICAL_TREE_DIR_PATH', plugin_dir_path( __FILE__ ) );
    /**
     * Activation callback function.
     *
     * This function is triggered on plugin activation.
     * It performs any setup tasks required for the plugin to function correctly.
     *
     * @since 1.0.0
     */
    function activate_genealogical_tree() {
        \Zqe\Genealogical_Tree_Activator::activate();
    }

    /**
     * Deactivation callback function.
     *
     * This function is triggered on plugin deactivation.
     * It cleans up any resources or settings used by the plugin.
     *
     * @since 1.0.0
     */
    function deactivate_genealogical_tree() {
        \Zqe\Genealogical_Tree_Deactivator::deactivate();
    }

    // Register the activation and deactivation hooks.
    register_activation_hook( __FILE__, 'activate_genealogical_tree' );
    register_deactivation_hook( __FILE__, 'deactivate_genealogical_tree' );
    /**
     * Initializes and runs the plugin.
     *
     * This function is responsible for kicking off the plugin's functionality.
     * It instantiates the main plugin class and triggers its execution.
     *
     * @since 1.0.0
     */
    function run_genealogical_tree() {
        $plugin = new \Zqe\Genealogical_Tree();
        $plugin->run();
    }

    // Hook the plugin initialization to the 'plugins_loaded' action.
    add_action( 'plugins_loaded', 'run_genealogical_tree', 5 );
}