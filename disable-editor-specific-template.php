<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
Plugin Name: Disable Content Editor for Specific Template
Version: 2.0
Plugin URI: http://venugopalphp.wordpress.com
Description: This plugin useful for remove the total content editor for specfic page template.
Author: Venugopal
Author URI: http://venugopalphp.wordpress.com
*/

/* 
 * Include styles for table
 */
 add_action( 'admin_init', 'include_styles' );
 function include_styles() {         
$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)).'/css';
wp_enqueue_style( 'table', $pluginfolder.'/table.css' );
 }
add_action('admin_menu', 'disable_editor_plugin');


// Plugin Creation 
function disable_editor_plugin() {
	add_menu_page('Disable Editor', 'Disable Editor', 'read', 'disable-editor', 'diable_editor_function','',79);
}


// Calling Plugin Creation Function
function diable_editor_function(){
   include "selected_templates.php";
   
   // Delete File Name from database
    if(filter_var($_REQUEST['del'],FILTER_SANITIZE_STRING) != ""){
     $del_temp_name = sanitize_text_field($_REQUEST['del']);
    global $wpdb;
        	 $wpdb->query("DELETE  FROM ".$wpdb->prefix."disable_content_editor WHERE template_name = '$del_temp_name' ");
     
 }
            echo "<h1> Welcome to Disable Content Editor for Specific Page Template </h1>";
            $templates = get_page_templates();
             echo "<form method='post'  action=' '>Select Template File : <select name='template_names' required>";
             echo "<option value=''>Select Template File</option>";
             
             $i=1;

             foreach ( $templates as $template_name => $template_filename ) 
           {
              echo "<option value='$template_filename/$template_name'>"; echo $template_name; echo "</option>";
                 $i++;
            }
             echo "</select><input type='submit' name='names_submit' value='Submit'></form>";
             echo "<br><br>";
             
             
// Storing Template Names to database
    if(filter_var($_REQUEST['names_submit'],FILTER_SANITIZE_STRING) != ""){

         global $wpdb;

        $templatename = sanitize_text_field($_REQUEST['template_names']);
        /*template_name_php =  substr(strstr($templatename,'/'),1);

        if(empty($template_name_php))
        {
          $templatename_insert = $_REQUEST['template_names'];
        } else
        {
             $templatename = $_REQUEST['template_names'];
             $templatename_insert =  substr(strstr($templatename,'/'),1);
        }  */ 
      $select_temp->insert_tempname(sanitize_text_field($templatename));  
    }
        echo $select_temp->selected_templates();
 
}
 /**
 * Hide editor for specific page templates.
 *
 */
add_action( 'admin_init', 'hide_editor' );

function hide_editor() {
	// Get the Post ID.
	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	if( !isset( $post_id ) ) return;

	// Get the name of the Page Template file.
        global $wpdb;
        $tempsw = $wpdb->get_results("select * from ".$wpdb->prefix."disable_content_editor");
         foreach($tempsw as $tempsnamew){
             
            $filenames = $tempsnamew->filename;
                   
	$template_file = get_post_meta($post_id, '_wp_page_template', true);
    
        
    if($template_file == $filenames){ // edit the template name
       
    	remove_post_type_support('page', 'editor');
    }
    
         }
}






//Create table while activation

function create_plugin_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'disable_content_editor';
    $sql = "CREATE TABLE $table_name (
        id int(9) unsigned NOT NULL AUTO_INCREMENT,
           filename varchar(250),
            template_name varchar(250),
               PRIMARY KEY  (id)
        );";
 
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
 
register_activation_hook( __FILE__, 'create_plugin_database_table' );
?>