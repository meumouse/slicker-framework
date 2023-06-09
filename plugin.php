<?php

namespace Slicker;

use Slicker\Core\Modules_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class plugin
 */
class Slicker {

    /**
     * @var Slicker
     */
    private static $_instance;

    /**
     * @var Modules_Manager
     */
    public $modules_manager;

    private static $classes_aliases;

    public static function get_classes_aliases() {
        if ( ! self::$classes_aliases ) {
            return self::init_classes_aliases();
        }

        return self::$classes_aliases;
    }

    private static function init_classes_aliases() {
        $classes_aliases = [];

        return $classes_aliases;
    }

    /**
     * Throw error on object clone
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @since 1.0.0
     * @return void
     */
    public function __clone() {
        // Cloning instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Algo deu errado.', 'slicker-framework' ), '1.0.0' );
    }

    /**
     * Disable unserializing of the class
     *
     * @since 1.0.0
     * @return void
     */
    public function __wakeup() {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Algo deu errado.', 'slicker-framework' ), '1.0.0' );
    }

    /**
     * @return Slicker
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private static function normalize_class_name( $string, $delimiter = ' ' ) {
        return str_replace( ' ', $delimiter, ucwords( str_replace( $delimiter, ' ', $string ) ) );
    }

    public function autoload( $class ) {
        if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
            return;
        }

        $classes_aliases = self::get_classes_aliases();

        $has_class_alias = isset( $classes_aliases[ $class ] );

        // Backward Compatibility: Save old class name for set an alias after the new class is loaded
        if ( $has_class_alias ) {
            $class_alias_name = $classes_aliases[ $class ];
            $class_to_load = $class_alias_name;
        } else {
            $class_to_load = $class;
        }

        if ( ! class_exists( $class_to_load ) ) {
            $filename = strtolower(
                preg_replace(
                    [ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                    [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                    $class_to_load
                )
            );

            $filename = SLICKER_ABSPATH . $filename . '.php';

            if ( is_readable( $filename ) ) {
                include( $filename );
            }
        }

        if ( $has_class_alias && class_exists( $class_alias_name ) ) {
            class_alias( $class_alias_name, $class );
        }
    }

    public function on_init() {
        $this->modules_manager  = new Modules_Manager();

        /**
         * Slicker Framework init.
         *
         * Fires on Slicker Framework init, after Elementor has finished loading but
         * before any headers are sent.
         *
         * @since 1.0.0
         */
        do_action( 'slicker_framework/init' );
    }

    private function setup_hooks() {
        add_action( 'plugins_loaded', [ $this, 'on_init' ] );
    }

    /**
     * Slicker constructor.
     */
    private function __construct() {
        spl_autoload_register( [ $this, 'autoload' ] );

        $this->setup_hooks();
    }

    final public static function get_title() {
        return esc_html__( 'Slicker Framework', 'slicker-framework' );
    }
}

Slicker::instance();