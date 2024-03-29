<?php


if(!class_exists('Soul_Grid_Add_BB_Settings')){
    class Soul_Grid_Add_BB_Settings{
        
        public function __construct(){
            self::load_hooks();
        }
        
        public static function load_hooks(){
            // enqueue the scripts and styles at the right times
            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_soul_grid_scripts_and_styles'));
            add_action('fl_before_sortable_enqueue', array(__CLASS__, 'enqueue_soul_grid_editor_scripts_and_styles'));

            // add the SoulGrid settings to each available BB type's editor form
            add_filter('fl_builder_register_settings_form', array(__CLASS__, 'add_soul_grid_settings_to_bb_forms'), 99, 2);

            // add our SoulGrid classes to any elements that have them
            add_filter('fl_builder_column_custom_class', array(__CLASS__, 'filter_classes'), 10, 2);
            add_filter('fl_builder_row_custom_class', array(__CLASS__, 'filter_classes'), 10, 2);
            add_filter('fl_builder_module_custom_class', array(__CLASS__, 'filter_classes'), 10, 2);
            
        }

        public static function enqueue_soul_grid_scripts_and_styles(){
            if(!wp_script_is( 'soul-grid-bb-styling', 'enqueued')){
                wp_enqueue_style( 'soul-grid-bb-styling', SOUL_GRID_URL_PATH . 'assets/css/soul-grid-styles.css' );
            }    
        }
        
        public static function enqueue_soul_grid_editor_scripts_and_styles(){
            
            if(!wp_script_is( 'soul-grid-bb-settings', 'enqueued')){
                wp_enqueue_script( 'soul-grid-bb-settings', SOUL_GRID_URL_PATH . 'assets/js/frontend.js', array('jquery') );
            }

            if(!wp_script_is( 'soul-grid-bb-styling', 'enqueued')){
                wp_enqueue_style( 'soul-grid-bb-styling', SOUL_GRID_URL_PATH . 'assets/css/soul-grid-styles.css' );
            }
        }
        
        /**
         * Adds the SoulGrid inputs to Beaver Builder Column, Row and Module editors
         **/
        public static function add_soul_grid_settings_to_bb_forms($form, $id){
            
            if(empty($form) || is_admin()){
                return $form;
            }
            
            if('col' === $id){
                $form = self::add_soul_grid_settings_to_columns($form, $id);
            }elseif('row' === $id){
                $form = self::add_soul_grid_settings_to_rows($form, $id);
            }elseif('module_advanced' === $id){
                $form = self::add_soul_grid_settings_to_modules($form, $id);
            }
            
            return $form;
        }

        /**
         * Adds the SoulGrid column & gutter controls to the Advanced tab for Columns in the Beaver Builder Live Editor
         **/
        public static function add_soul_grid_settings_to_columns($form, $id){
            global $global_settings;

            // if the global settings haven't been set yet, get them
            if(empty($global_settings)){
                $global_settings = FLBuilderModel::get_global_settings();
            }

            if(isset($form['tabs']) && isset($form['tabs']['advanced']) && !isset($form['tabs']['advanced']['sections']['soul_grid_columns'])){
                $form['tabs']['advanced']['sections']['soul_grid_columns'] = array(
                    'title'  => __( 'Soul Grid Columns', 'soul-grid' ),
                    'fields' => array(
                        'soul_grid_column_gutter_dropdown'  => array(
                            'type'    => 'select',
                            'label'      => __( 'Soul Grid Columns & Gutters', 'soul-grid' ),
                            'options' => array(
                                ''         => __( 'Don\'t use SoulGrid on this item', 'soul-grid' ),
                                'sg-1c-0g' => __( '1 Column, 0 Gutters', 'soul-grid' ),
                                'sg-1c-1g' => __( '1 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-1g' => __( '2 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-2g' => __( '2 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-2g' => __( '3 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-3g' => __( '3 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-3g' => __( '4 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-4g' => __( '4 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-4g' => __( '5 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-5g' => __( '5 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-5g' => __( '6 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-6g' => __( '6 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-6g' => __( '7 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-7g' => __( '7 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-7g' => __( '8 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-8g' => __( '8 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-8g' => __( '9 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-9g' => __( '9 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-9g' => __( '10 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-10g' => __( '10 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-10g' => __( '11 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-11g' => __( '11 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-11g' => __( '12 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-12g' => __( '12 Column, 12 Gutters', 'soul-grid' ),
                            ),
                            'preview' => array(
                                'type' => 'none',
                            ),
                            'responsive' => array(
                                'default_unit' => array(
                                    'default'    => empty( $global_settings->column_soul_grid_columns_and_gutters_unit ) ? 12 : $global_settings->column_soul_grid_columns_and_gutters_unit,
                                    'medium'     => empty( $global_settings->column_soul_grid_columns_and_gutters_medium_unit ) ? 12 : $global_settings->column_soul_grid_columns_and_gutters_medium_unit,
                                    'responsive' => empty( $global_settings->column_soul_grid_columns_and_gutters_responsive_unit ) ? 12 : $global_settings->column_soul_grid_columns_and_gutters_responsive_unit,
                                ),
                                'placeholder'  => array(
                                    'default'    => empty( $global_settings->column_soul_grid_columns_and_gutters ) ? '0' : $global_settings->column_soul_grid_columns_and_gutters,
                                    'medium'     => empty( $global_settings->column_soul_grid_columns_and_gutters_medium ) ? '0' : $global_settings->column_soul_grid_columns_and_gutters_medium,
                                    'responsive' => empty( $global_settings->column_soul_grid_columns_and_gutters_responsive ) ? '0' : $global_settings->column_soul_grid_columns_and_gutters_responsive,
                                ),
                            ),
                        ),
                    ),
                );
            }

            return $form;
        }

        /**
         * Adds the SoulGrid column & gutter controls to the Advanced tab for Rows in the Beaver Builder Live Editor
         **/
        public static function add_soul_grid_settings_to_rows($form, $id){
            global $global_settings;

            // if the global settings haven't been set yet, get them
            if(empty($global_settings)){
                $global_settings = FLBuilderModel::get_global_settings();
            }

            if(isset($form['tabs']) && isset($form['tabs']['advanced']) && !isset($form['tabs']['advanced']['sections']['soul_grid_columns'])){
                $form['tabs']['advanced']['sections']['soul_grid_columns'] = array(
                    'title'  => __( 'Soul Grid Columns', 'soul-grid' ),
                    'fields' => array(
                        'soul_grid_column_gutter_dropdown'  => array(
                            'type'    => 'select',
                            'label'      => __( 'Soul Grid Columns & Gutters', 'soul-grid' ),
                            'options' => array(
                                ''         => __( 'Don\'t use SoulGrid on this item', 'soul-grid' ),
                                'sg-1c-0g' => __( '1 Column, 0 Gutters', 'soul-grid' ),
                                'sg-1c-1g' => __( '1 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-1g' => __( '2 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-2g' => __( '2 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-2g' => __( '3 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-3g' => __( '3 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-3g' => __( '4 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-4g' => __( '4 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-4g' => __( '5 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-5g' => __( '5 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-5g' => __( '6 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-6g' => __( '6 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-6g' => __( '7 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-7g' => __( '7 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-7g' => __( '8 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-8g' => __( '8 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-8g' => __( '9 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-9g' => __( '9 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-9g' => __( '10 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-10g' => __( '10 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-10g' => __( '11 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-11g' => __( '11 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-11g' => __( '12 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-12g' => __( '12 Column, 12 Gutters', 'soul-grid' ),
                            ),
                            'preview' => array(
                                'type' => 'none',
                            ),
                            'responsive' => array(
                                'default_unit' => array(
                                    'default'    => empty( $global_settings->row_soul_grid_columns_and_gutters_unit ) ? 12 : $global_settings->row_soul_grid_columns_and_gutters_unit,
                                    'medium'     => empty( $global_settings->row_soul_grid_columns_and_gutters_medium_unit ) ? 12 : $global_settings->row_soul_grid_columns_and_gutters_medium_unit,
                                    'responsive' => empty( $global_settings->row_soul_grid_columns_and_gutters_responsive_unit ) ? 12 : $global_settings->row_soul_grid_columns_and_gutters_responsive_unit,
                                ),
                                'placeholder'  => array(
                                    'default'    => empty( $global_settings->row_soul_grid_columns_and_gutters ) ? '0' : $global_settings->row_soul_grid_columns_and_gutters,
                                    'medium'     => empty( $global_settings->row_soul_grid_columns_and_gutters_medium ) ? '0' : $global_settings->row_soul_grid_columns_and_gutters_medium,
                                    'responsive' => empty( $global_settings->row_soul_grid_columns_and_gutters_responsive ) ? '0' : $global_settings->row_soul_grid_columns_and_gutters_responsive,
                                ),
                            ),
                        ),
                    ),
                );
            }

            return $form;
        }

        /**
         * Adds the SoulGrid column & gutter controls to the Advanced tab for Modules in the Beaver Builder Live Editor
         **/
        public static function add_soul_grid_settings_to_modules($form, $id){
            global $global_settings;

            // if the global settings haven't been set yet, get them
            if(empty($global_settings)){
                $global_settings = FLBuilderModel::get_global_settings();
            }

            if(isset($form['sections']) && !isset($form['sections']['soul_grid_columns'])){
                $form['sections']['soul_grid_columns'] = array(
                    'title'  => __( 'Soul Grid Columns', 'soul-grid' ),
                    'fields' => array(
                        'soul_grid_column_gutter_dropdown'  => array(
                            'type'    => 'select',
                            'label'      => __( 'Soul Grid Columns & Gutters', 'soul-grid' ),
                            'options' => array(
                                ''         => __( 'Don\'t use SoulGrid on this item', 'soul-grid' ),
                                'sg-1c-0g' => __( '1 Column, 0 Gutters', 'soul-grid' ),
                                'sg-1c-1g' => __( '1 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-1g' => __( '2 Column, 1 Gutters', 'soul-grid' ),
                                'sg-2c-2g' => __( '2 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-2g' => __( '3 Column, 2 Gutters', 'soul-grid' ),
                                'sg-3c-3g' => __( '3 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-3g' => __( '4 Column, 3 Gutters', 'soul-grid' ),
                                'sg-4c-4g' => __( '4 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-4g' => __( '5 Column, 4 Gutters', 'soul-grid' ),
                                'sg-5c-5g' => __( '5 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-5g' => __( '6 Column, 5 Gutters', 'soul-grid' ),
                                'sg-6c-6g' => __( '6 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-6g' => __( '7 Column, 6 Gutters', 'soul-grid' ),
                                'sg-7c-7g' => __( '7 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-7g' => __( '8 Column, 7 Gutters', 'soul-grid' ),
                                'sg-8c-8g' => __( '8 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-8g' => __( '9 Column, 8 Gutters', 'soul-grid' ),
                                'sg-9c-9g' => __( '9 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-9g' => __( '10 Column, 9 Gutters', 'soul-grid' ),
                                'sg-10c-10g' => __( '10 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-10g' => __( '11 Column, 10 Gutters', 'soul-grid' ),
                                'sg-11c-11g' => __( '11 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-11g' => __( '12 Column, 11 Gutters', 'soul-grid' ),
                                'sg-12c-12g' => __( '12 Column, 12 Gutters', 'soul-grid' ),
                            ),
                            'preview' => array(
                                'type' => 'none',
                            ),
                            'responsive' => array(
                                'default_unit' => array(
                                    'default'    => empty( $global_settings->module_soul_grid_columns_and_gutters_unit ) ? 12 : $global_settings->module_soul_grid_columns_and_gutters_unit,
                                    'medium'     => empty( $global_settings->module_soul_grid_columns_and_gutters_medium_unit ) ? 12 : $global_settings->module_soul_grid_columns_and_gutters_medium_unit,
                                    'responsive' => empty( $global_settings->module_soul_grid_columns_and_gutters_responsive_unit ) ? 12 : $global_settings->module_soul_grid_columns_and_gutters_responsive_unit,
                                ),
                                'placeholder'  => array(
                                    'default'    => empty( $global_settings->module_soul_grid_columns_and_gutters ) ? '0' : $global_settings->module_soul_grid_columns_and_gutters,
                                    'medium'     => empty( $global_settings->module_soul_grid_columns_and_gutters_medium ) ? '0' : $global_settings->module_soul_grid_columns_and_gutters_medium,
                                    'responsive' => empty( $global_settings->module_soul_grid_columns_and_gutters_responsive ) ? '0' : $global_settings->module_soul_grid_columns_and_gutters_responsive,
                                ),
                            ),
                        ),
                    ),
                );
            }

            return $form;
        }


        /**
         * Applies the SoulGrid column & grid classes to elements based on the user's input
         **/
        public static function filter_classes($classes, $node){

            if(isset($node->settings->soul_grid_column_gutter_dropdown) && !empty($node->settings->soul_grid_column_gutter_dropdown)){
                $classes .= ( ' ' . esc_attr( $node->settings->soul_grid_column_gutter_dropdown ) );
            }
            
            if(isset($node->settings->soul_grid_column_gutter_dropdown_medium) && !empty($node->settings->soul_grid_column_gutter_dropdown_medium)){
                $classes .= ( ' ' . esc_attr( $node->settings->soul_grid_column_gutter_dropdown_medium ) . '_medium' );
            }

            if(isset($node->settings->soul_grid_column_gutter_dropdown_responsive) && !empty($node->settings->soul_grid_column_gutter_dropdown_responsive)){
                $classes .= ( ' ' . esc_attr( $node->settings->soul_grid_column_gutter_dropdown_responsive ) . '_responsive' );
            }

            return $classes;
        }
    }

    new Soul_Grid_Add_BB_Settings;
}




?>
