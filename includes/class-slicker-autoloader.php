<?php

/**
 * Slicker Autoloader
 *
 * @package MeuMouse.com
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Autoloader class.
 */
class Slicker_Framework_Autoloader {

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct() {
        if ( function_exists( '__autoload' ) ) {
            spl_autoload_register( '__autoload' );
        }

        spl_autoload_register( array( $this, 'autoload' ) );

        $this->include_path = untrailingslashit( plugin_dir_path( SLICKER_FRAMEWORK_FILE ) ) . '/includes/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class Class name.
     * @return string
     */
    private function get_file_name_from_class( $class ) {
        return 'class-' . str_replace( '_', '-', $class ) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param  string $path File path.
     * @return bool Successful or not.
     */
    private function load_file( $path ) {
        if ( $path && is_readable( $path ) ) {
            include_once $path;
            return true;
        }
        return false;
    }

    /**
     * Auto-load WC classes on demand to reduce memory consumption.
     *
     * @param string $class Class name.
     */
    public function autoload( $class ) {
        $class = strtolower( $class );

        if ( 0 !== strpos( $class, 'slicker_' ) ) {
            return;
        }

        $file = $this->get_file_name_from_class( $class );
        $path = '';

        if ( 0 === strpos( $class, 'slicker_meta_box' ) ) {
            $path = $this->include_path . 'admin/meta-boxes/';
        } 

        if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
            $this->load_file( $this->include_path . $file );
        }
    }
}

new Slicker_Framework_Autoloader();