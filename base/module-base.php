<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

namespace Slicker\Base;
use Slicker;

abstract class Module_Base {

    /**
     * Module instance.
     *
     * Holds the module instance.
     *
     * @access protected
     *
     * @var Module
     */
    protected static $_instances = [];

    /**
     * Instance.
     *
     * Ensures only one instance of the module class is loaded or can be loaded.
     *
     * @access public
     * @static
     *
     * @return Module An instance of the class.
     */
    public static function instance() {
        $class_name = static::class_name();

        if ( empty( static::$_instances[ $class_name ] ) ) {
            static::$_instances[ $class_name ] = new static();
        }

        return static::$_instances[ $class_name ];
    }

    /**
     * @access public
     * @static
     */
    public static function is_active() {
        return true;
    }

    /**
     * Class name.
     *
     * Retrieve the name of the class.
     *
     * @access public
     * @static
     */
    public static function class_name() {
        return get_called_class();
    }

    /**
     * Clone.
     *
     * Disable class cloning and throw an error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object. Therefore, we don't want the object to be cloned.
     *
     * @access public
     */
    public function __clone() {
        // Cloning instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Algo deu errado.', 'slicker-framework' ), '1.0.0' );
    }

    /**
     * Wakeup.
     *
     * Disable unserializing of the class.
     *
     * @access public
     */
    public function __wakeup() {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Algo deu errado.', 'slicker-framework' ), '1.0.0' );
    }
}