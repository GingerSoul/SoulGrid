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

if(!class_exists('Soul_Grid') && class_exists('FLBuilder')){
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
            require(SOUL_GRID_PATH . 'classes/soul_grid_add_bb_settings.php');
        }
    }
    
    new Soul_Grid;
}


























?>
