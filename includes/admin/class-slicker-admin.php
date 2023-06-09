<?php
/**
 * Slicker Framework Admin
 *
 * @category Admin
 * @package MeuMouse.com
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slicker_Framework_Admin class
 */
class Slicker_Framework_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'admin_init', array( $this, 'buffer' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

    }

    /**
     * Output buffering allows admin screens to make redirects later on.
     */
    public function buffer() {
        ob_start();
    }

    /**
     * Include any classes we need within admin.
     */
    public function includes() {
        // include files for running in admin
    }

    /**
     * Enqueue admin styles
     * 
     * @since 1.0.0
     */
    public function admin_styles() {
        $version = Slicker_Framework()->version;
        wp_register_style( 'slicker-admin-styles', plugins_url( '/assets/css/admin.css', SLICKER_FRAMEWORK_FILE ), array(), $version );
        wp_enqueue_style( 'slicker-admin-styles' );

    }

    /**
     * Enqueue admin scripts
     * 
     * @since 1.0.0
     */
    public function admin_scripts() {
        $version = Slicker_Framework()->version;
        wp_register_script( 'slicker-admin-scripts', plugins_url( '/assets/js/admin.js', SLICKER_FRAMEWORK_FILE ), array( 'jquery' ), $version );
        wp_enqueue_script( 'slicker-admin-scripts' );
    }

    /**
     * Include admin files conditionally
     */
    public function conditional_includes() {
        if ( ! $screen = get_current_screen() ) {
            return;
        }
    }
}

return new Slicker_Framework_Admin();