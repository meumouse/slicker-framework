<?php

namespace Slicker\Core;

use Slicker\Base\Module_Base;
use Slicker\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Modules_Manager {
    /**
     * @var Module_Base[]
     */
    private $modules = [];

    public function __construct() {
        $modules = [];

        foreach ( $modules as $module_name ) {
            $class_name = str_replace( '-', ' ', $module_name );
            $class_name = str_replace( ' ', '', ucwords( $class_name ) );
            $class_name = '\Slicker\Modules\\' . $class_name . '\Module';

            /**
             * @var Mudule_Base $class_name
             */
            if ( $class_name::is_active() ) {
                $this->modules[ $module_name ] = $class_name::instance();
            }
        }
    }

    /**
     * @param string $module_name
     *
     * @return Module_Base|Module_Base[]
     */
    public function get_modules( $module_name ) {
        if ( $module_name ) {
            if ( isset( $this->modules[ $module_name ] ) ) {
                return $this->modules[ $module_name ];
            }

            return null;
        }

        return $this->modules;
    }
}