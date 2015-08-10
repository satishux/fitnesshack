<?php
if (isset($_POST['action'])) {  
      switch ($_POST['action']) {  
        case 'version':  
          echo '4.02';  
          break;  
        case 'info':  
          $obj = new stdClass();  
          $obj->slug = 'wprobot.php';  
          $obj->plugin_name = 'wprobot.php';  
          $obj->new_version = '4.02';  
          $obj->requires = '3.0';  
          $obj->tested = '3.8';  
          $obj->downloaded = 1372843;  
          $obj->last_updated = '2013-12-02';  
          $obj->sections = array(  
            'description' => 'WP Robot 4 - The most popular and powerful autoblogging plugin for WordPress weblogs. See http://wprobot.net/ for details.',  
            'changelog' => 'Please see the update thread in our support forum.'  
          );  
          $obj->download_link = 'http://wprobot.net/rpl/updaterXXX.php?email=' . $_POST['email'];  
          echo serialize($obj);  
        case 'license':  
          echo 'false';  
          break;  
      }  
    } else {  
	
	
	echo "ERROR";
		$lemail = $_GET["email"];
		
		// search DB for user
		
		
		
        //header('Cache-Control: public');  
       // header('Content-Description: File Transfer');  
        //header('Content-Type: application/zip');  
        //readfile('update.zip');  
    }
?>	