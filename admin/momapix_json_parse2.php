<?php

    $image_load = WP_PLUGIN_URL . "/momapix-image-selector/images/loader.gif";
    $post_id = absint($_REQUEST['post_id']);
    
    echo '<p id="top_msg"><a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab=photo&momapage=1&type=wp_momapix_photo"> Last Photos</a> ';
    echo '||| <a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab=cerca&momapage=1&type=wp_momapix_photo">Search </a> </p>';
   
  
    if (!isset($_POST['submit']) && !isset($_GET['search_key']))
{
    ?>
  

     <form id="form" class="media-upload-form type-form validate" action="" method="POST" >
			
                
                <input type="text" size="30" name="search_key" value="" />
                
                <input type="hidden" name="post_edited" value="<?PHP echo $_GET['post_id']; ?>" />
                
                <input id="submit" type="submit" name="submit" class="button" value="Search" />
               
                <div id="loading2" style="display:none; position:relative; left:50%;"><img src="<?php echo $image_load ?>" alt="Loading..." /><br>Loading...</div>
            
            </form>

         <script type="text/javascript">
        (function (d)
            {
            d.getElementById('form').onsubmit = function () {
            d.getElementById('submit').style.display = 'block';
            d.getElementById('loading2').style.display = 'block';
                 };
            }(document));
        </script>
        
	<?php
}
    
  
    
    //$tabs = $_GET['tab'];
    //$tabs = 'cerca';
    
    $options = get_option( 'momapix_default_value' );

    $baseURL = $options['momapix_account'];
 


    //  momapix form for image searchable
    

  

        

        if (isset($_POST['submit']) && empty($_POST['search_key']))
            
            {
                echo'<p id="top_msg">please enter a key</p>';
                exit;
            }
         

            
            elseif (isset($_GET['search_key'])) {
            $key = $_GET['search_key'];
            $page = $_GET['momapage'];
            
            
            }
            
            elseif (isset($_POST['search_key'])) { 
            $key = $_POST['search_key'];
            $page	 = 1; 
            }
            
            else 
            {
                exit;
            }
            
      
        

 // Momapix Function to handle key search
               
function momapixKey2SearchUrl($string) {
		$string	= '{"request_array_searchbar":"'.$string.'"}';
	  	$hex 	= "";
	  	for ($i = 0; $i < strlen($string); $i++) {
	    	$hex .= (strlen(dechex(ord($string[$i]))) < 2) ?
	    	"0" . dechex(ord($string[$i])) : dechex(ord($string[$i]));
	  	}
	
	  	return "0x".strtoupper($hex);
	}
        
// end Momapix Function to handle key search    
     

        
        $surl	=	$baseURL."/rest/json/search/it/".$page."/".momapixKey2SearchUrl($key);
              
        $json	=	momapix_file_get_contents($surl);
        
        $results = 	json_decode($json, $depth = 1);
        
        
        
        $surl_next = $baseURL."/rest/json/search/it/".++$page."/".momapixKey2SearchUrl($key);
        
        $json_next	=	momapix_file_get_contents($surl_next);
                
        $results_next = 	json_decode($json_next, $depth = 1);
        
        

if (is_array($results['result']))
{
    

        echo '<p id="top_msg">';
        echo 'Results for '.$key;
        echo "</p>";
        echo '<p id="top_msg">pag. '.$_GET['momapage'].'</p>';
        
        
        
// prev button event
    
if ($_GET['momapage']>1){
 
    echo '<div id="momapix_image_results"><div  id="momapix_image_results_inside">';
    echo '<a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab=cerca&momapage='.($_GET['momapage']-1).'&type=wp_momapix_photo&search_key='.$key.'">';
    echo '<img id="img_result" src="'.WP_PLUGIN_URL .'/momapix-image-selector/images/prev_arrow.gif"><br>Click here to prev</a>';
    echo '</div>';
    echo '</div>';


}   
// prev button event end  



    
    foreach($results['result'] as $result)
    {

                    $image_moma_url_m = $baseURL."/Image".$result['id'].'.jpg';
                    $image_moma_url_s = $baseURL."/Image".$result['id'].'.png';
                    $image_moma_url_b = $baseURL."/Preview".$result['id'].'.jpg';
                    $image_moma = $result['id'].'.jpg';


         ?>


<!-- momapix_image_results -->

    <div id="momapix_image_results">

        <div id="momapix_image_results_inside">
                         
                
                <a href="#" onclick="return insert_picture('<?php echo $image_moma_url_m; ?>','<?php echo $post_id; ?>')" />
                <img id="img_result" src="<?php echo $image_moma_url_m; ?>"/>
                <br/>
                </a>
                <br>
                <?php echo $image_moma ?> <br>
            
                    <a href="#" onclick="return insert_picture('<?php echo $image_moma_url_s; ?>','<?php echo $post_id; ?>')" />
                    small</a> | 
                    
        
                    <a href="#" onclick="return insert_picture('<?php echo $image_moma_url_m; ?>','<?php echo $post_id; ?>')" />
                    med</a> | 
                    
        
                    <a href="#" onclick="return insert_picture('<?php echo $image_moma_url_b; ?>','<?php echo $post_id; ?>')" />
                    big
                    </a>
            
        </div>

    </div> 

<!-- momapix_image_results -->
    <?php 
        
    } 
               
    
 // next button search only if next page is not empty
    
  if (is_array($results_next['result'])) {
        
        echo '<div id="momapix_image_results"><div  id="momapix_image_results_inside">';
	echo '<a href="'. $_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab=cerca&type=wp_momapix_photo&momapage='.$page.'&search_key='.$key.'">';
        echo '<img id="img_result" src="'.WP_PLUGIN_URL .'/momapix-image-selector/images/next_arrow.gif"><br>Click here to next </a>';
        echo '</div>';
        echo '</div>';
   }
 
    
 // end next button search  
    
    
    
}

else {
    
    echo '<p id="top_msg">Sorry no results for '.$key.' <br>Try again</p>';
}

       
