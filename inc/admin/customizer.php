<?php

$files = array(
    'customizer-fields/sortable.php',
    'customizer-fields/activate-layout-settings.php',
    'customizer-fields/activated-sortable.php',
    'customizer-fields/color-scheme.php',
    'customizer-fields/menu-dropdown.php',
    'customizer-fields/checkbox-group.php',
    'customizer/identity.php',
    'customizer/header.php',
    'customizer/template-settings.php',
    'customizer/frontpage.php',
    'customizer/widgetized.php',
    'customizer/footer.php',
    'customizer/404.php',
);

foreach($files as $file)
    require_once $file;




/**
 * Remove some default settings from the customizer
 * @param  object $wp_customize
 */
function benjamin_remove_default($wp_customize){
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('header_image');
    $wp_customize->remove_section('background_image');
    $wp_customize->remove_section('custom_css');

    // $wp_customize->register_control_type( 'Benjamin_Activate_Layout_Custom_Control' );
    // $wp_customize->register_control_type( 'Benjamin_Activated_Sortable_Custom_Control' );
    // $wp_customize->register_control_type( 'Benjamin_Checkbox_Group_Control' );
    // $wp_customize->register_control_type( 'Benjamin_Menu_Dropdown_Custom_Control' );
    // $wp_customize->register_control_type( 'Benjamin_Sortable_Custom_Control' );
}
add_action('customize_register', 'benjamin_remove_default');
