<?php
/**
 * Plugin Name: Epin Game Services
 * Plugin URI: https://turkeymediaworks.com/epin-game-service/
 * Description: Epin game, product item service entegrations.
 * Version: 0.0.1
 * Developer: Turkey Media Works
 * Developer URI: https://turkeymediaworks.com/
 * 
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/*
* Load the all defines.
*/

define( 'EPIN_VERSION', '0.0.1' );

/* Menu */
function epin_admin_menu_option()
{
  add_menu_page('Epin Games', 'Epin Games', 'manage_options', 'epin-games', 'epin_scripts_page', 'dashicons-games', 200);
}

add_action('admin_menu', 'epin_admin_menu_option');

 /* Content */
 function epin_scripts_page()
{
   echo '<div class="epin_wrapper">';
   epin_form();
   echo "</div>";
}

/* Service Keys Form */
function epin_form()
{
    if(array_key_exists('submit_scripts_update', $_POST)){
        update_option('epin_active', (isset($_POST['epin_active']) ? $_POST['epin_active'] : "false"));
        update_option('epin_base_url', $_POST['epin_base_url']);
        update_option('epin_authorization', $_POST['epin_authorization']);
        update_option('epin_api_name', $_POST['epin_api_name']);
        update_option('epin_api_key', $_POST['epin_api_key']);
        update_option('epin_last_update', time());
        echo "<div class=\"success\">Kaydedildi.</div>";
    }

    $epin_active = get_option('epin_active', 'none');
    $epin_base_url = get_option('epin_base_url', 'none');
    $epin_authorization = get_option('epin_authorization', 'none');
    $epin_api_name = get_option('epin_api_name', 'none');
    $epin_api_key = get_option('epin_api_key', 'none');
    $epin_last_update = get_option('epin_last_update', 'none');

    $content = '<form action="" method="post">';
    $content .= '<label for="epin_active">Durumu: </label><input type="checkbox" id="epin_active" name="epin_active" value="true" '.($epin_active == "true" ? "checked" : "").' class="tgl tgl-light" />';
    $content .= '<br><br>';
    $content .= '<label for="epin_base_url">Base URL</label><input type="text" id="epin_base_url" name="epin_base_url" value="'.$epin_base_url.'" />';
    $content .= '<br><br>';
    $content .= '<label for="epin_authorization">Authorization</label><input type="text" id="epin_authorization" name="epin_authorization" value="'.$epin_authorization.'" />';
    $content .= '<br><br>';
    $content .= '<label for="epin_api_name">API Name</label><input type="text" id="epin_api_name" name="epin_api_name" value="'.$epin_api_name.'" />';
    $content .= '<br><br>';
    $content .= '<label for="epin_api_key">API Key</label><input type="text" id="epin_api_key" name="epin_api_key" value="'.$epin_api_key.'" />';
    $content .= '<br><br>';
    $content .= '<button type="submit" id="save" name="submit_scripts_update">Kaydet</button>';
    $content .= '<br><br>';
    $content .= '<span>Son Güncelleme: '.$epin_last_update.'</span>';
    $content .= "</form>";

    echo $content;
}

function epin_shortcode_function( $atts ){
  return 'Epin Games';
}
add_shortcode( 'epin', 'epin_shortcode_function' );

function epin_script_adding_function() {
  wp_enqueue_script( 'epin-js', plugin_dir_url( __FILE__ ) . 'build/app.js' );
}
add_action( 'wp_enqueue_scripts', 'epin_script_adding_function' );


/* Panel Styles */
wp_enqueue_style( 'styles', plugin_dir_url( __FILE__ ) . 'styles/app.css', [], wp_get_theme()->get( 'Version' ), 'all' );