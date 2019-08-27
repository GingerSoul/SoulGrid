<?php
/**
 * Plugin Name: Soul Grid
 * Plugin URI:	...
 * Description: ...
 * Author:		Soul Plugins
 * Author URI:	URI: https://soulplugins.co
 * Version:		1.0
 * Text Domain: soul-grid
 * Domain Path: /assets/lang/
 */

if(!class_exists('Soul_Grid')){
    class Soul_Grid{

        public function __construct(){
            self::load_constants();
            self::load_classes();
        }

        public static function load_constants(){
            define('SOUL_GRID_PLUGIN_VERSION', '1.0');
            define('SOUL_GRID_URL_PATH', trailingslashit(plugin_dir_url(__FILE__)));
            define('SOUL_GRID_PATH', trailingslashit(plugin_dir_path(__FILE__)));
        }

        public static function load_classes(){
            // if beaver builder is active, setup our BB editor integrations
            if(class_exists('FLBuilder')){
                require(SOUL_GRID_PATH . 'classes/soul_grid_add_bb_settings.php');
            }
        }
    }

    add_action('plugins_loaded', function(){
        new Soul_Grid;
    }, 999);
}


























?>
