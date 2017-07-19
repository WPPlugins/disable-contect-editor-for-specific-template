<?php 
 class Select_templates
 {
     public  function insert_tempname($tempname){
          global $wpdb;
          
         // Checking Template file name in database  
          
          
            $filename = dirname($tempname);
          
		  $tem_url = $tempname;
           $tokens = explode('/', $tem_url);
           $template_name = $tokens[sizeof($tokens)-1];
         
          
  $name_check = "SELECT id FROM ".$wpdb->prefix."disable_content_editor WHERE filename = '$filename' ";
  $no_name_php = $wpdb->get_var($name_check); 
  
  if(empty($no_name_php) && $no_name_php == ""):
      
     /* Template File name not there in database
      * Inserting New Template Names in to database
      */
      //echo $template_name_php;
   
    $insert_tempname =  "INSERT INTO ".$wpdb->prefix."disable_content_editor (filename, template_name) VALUES('$filename','$template_name')";
    $wpdb->query($insert_tempname);
    
    endif;
     }
      /* List of Selected template names
      * 
      */
     public function selected_templates()
     {
          global $wpdb;
          echo "<table class='temp_css' border='0'>";
         $temps = $wpdb->get_results("select * from ".$wpdb->prefix."disable_content_editor");
         
         $i=1;
         if(!empty($temps))
         echo "<tr><th>Sr no</th><th>Template Name</th><th>File Name</th><th>Action</th></tr>";
         foreach($temps as $tempsname){
                   
         echo "<tr><td>". $i."</td><td>".$tempsname->template_name."</td><td>".$tempsname->filename."</td>";
       ?>
 <td><a onclick="return confirm('Are you sure want to delete this record?');" href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'&del='.$tempsname->template_name; ?>">Delete</a></td>

     <?php    
$i++;
         }
         
         echo "</table>";
     }
     
     
     
     
 }
 
 
 
 
 
 
 $select_temp = new Select_templates();