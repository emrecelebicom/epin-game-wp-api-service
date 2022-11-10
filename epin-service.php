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
define( 'EPIN__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once EPIN__PLUGIN_DIR . 'includes/class.config.php';
require_once EPIN__PLUGIN_DIR . 'includes/class.message.php';
require_once EPIN__PLUGIN_DIR . 'includes/class.view.php';
require_once EPIN__PLUGIN_DIR . 'includes/class.plugin.php';
require_once EPIN__PLUGIN_DIR . 'includes/class.service.php';

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
   get();
   echo "</div>";
}

/* Service Keys Form */
function get()
{
  
    $service = new Service();
    $view = new View();
    $plugin = new Plugin();

    //Plugin tanımlamalarının kaydedilmesi
    if(array_key_exists('submit_scripts_update', $_POST)){
        $plugin->save();
    }

    //Servis verilerinin çekilmesi
    if(array_key_exists('update_datas', $_POST)){
      $service->save();
    }

    //Plugin View
    $view->get('epin-form');
}

function epin_shortcode_function( $atts ){
  $service = new Service();
  
  if(get_option('epin_active', 'false') == 'true'){

    $time_around = ((time() - get_option('epin_last_update')) / 60) / 60;

    /*
    Önce Aradaki farkı bulduk.
    Daha sonra 60 bölerek dakika farkını bulduk.
    Daha sonra da tekrar 60 a bölerek saati bulduk.
    */

    if(get_option('epin_items_time_name') == "day"){
      if($time_around > (get_option('epin_items_time') * 24)){
        $service->save();
      }
    }else{
      if($time_around > get_option('epin_items_time')){
        $service->save();
      }
    }

  }
}
add_shortcode( 'epin', 'epin_shortcode_function' );

function epin_script_adding_function() {
  wp_enqueue_script( 'epin-js', plugin_dir_url( __FILE__ ) . 'build/app.js' );
}
add_action( 'wp_enqueue_scripts', 'epin_script_adding_function' );


/* Panel Styles */
wp_enqueue_style( 'app-style', plugin_dir_url( __FILE__ ) . 'styles/app.css', [], wp_get_theme()->get( 'Version' ), 'all' );
wp_enqueue_script( 'app-script', plugin_dir_url( __FILE__ ) . 'scripts/app.js', [], wp_get_theme()->get( 'Version' ), 'all' );