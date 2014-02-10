<?php


//$baseURL	=	"http://demo.momapix.com/develop";  //Questo va preso dal setting del plugin
    $options = get_option( 'momapix_default_value' ); // Preso dal setting del plugin
    $baseURL = $options['momapix_account'];
    
    $tabs = $_GET['tab'];

    //if (isset($_GET['tab'])) { $tabs = $_GET['tab']; $page = 1; }
  
    
    if (isset($_GET['momapage'])) { $page = $_GET['momapage']; }
    else {$page	 = 1;}
    
    $post_id = absint($_REQUEST['post_id']);
    $url 	= 	$baseURL."/rest/json/archive/it/".$page."/DFL";
    $url_foto =         $baseURL."/rest/json/event/it/".$page."/";
    $image_btn = WP_PLUGIN_URL . "/momapix-image-selector/images/moma_logo_wp.gif";
    $idEvent = "";
    
// check if is set or if is correct url setting momapix menu wp    
/*    
if(@momapix_file_get_contents($url)===FALSE){
    echo '<div id="wrong_url" style="margin-left:18px; margin-top:14px">Please check syntax or insert in the Wordpress left menu settings<br/><<<<<<<<<<br/><img src=" '.$image_btn.'"/> Momapix &nbsp; &nbsp; website\'s url  and try again<br/><<<<<<<<<</div>';
}
*/

    
// if selected specific event display specific images part of that event 
   
if (isset($_GET['id_event']))
{  
  $event_title = ($_GET['event_title']);
  
  $idEvent =   $_GET['id_event'];
  
  $url_foto_event = $url_foto.$idEvent ;   
  
  $json	=	momapix_file_get_contents($url_foto_event);        
 
  $results = 	json_decode($json, $depth = 1);


 



// back to page
  

echo '<p id="top_msg"> <a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.$page.'&type=wp_momapix_photo">Back to Events</a> <br></p> ';
echo '<p id="no_images">'.$event_title.'</p>';
echo '<p id="no_images">pag. '.$page.'</p>';


// prev button event
/*    
if ($page>1){
 

echo '<br><a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.($_GET['momapage']-1).'&type=wp_momapix_photo&event_title='.$event_title.'&id_event='.$idEvent.'">Click here to prev <<<</a>'; 


} 
*/
// prev button event end

    foreach($results['itemsInEvent'] as $result)
		{
                    $image_moma_url = $baseURL."/Image".$result['id'].'.jpg';
                    $image_moma = $result['id'].'.jpg';
                    ?>
 
<div id="momapix_image_results_event">
    <div id="momapix_image_results_inside_event">
        
		    <a href="#" onclick="return insert_picture('<?php echo $image_moma_url; ?>','<?php echo $post_id; ?>')" />
                    <img id="img_result" src="<?php echo $baseURL.'/Image'.$result['id']?>.jpg"/>
                    </a>
                    <?php echo "<p title=\"".$result['title']." - ".$result['caption']."\">".substr($result['caption'],0,100)."</p>"; ?>
                    
    </div>
</div> 

<!-- momapix_image_event_results end -->
                  
                 <?php   
                 }

 // next button event only if next page is not empty
                 
   
    if (count($results['itemsInEvent'])==$results['requestItemsPerPage'] && (!isset($_GET['momapage'])))
    {
               
		
    echo '<a href="'. $_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.++$page.'&type=wp_momapix_photo&event_title='.$result['title'].'&id_event='.$result['id_event'].'">Click here to next</a>';
 
    
    }
   
    elseif (isset($_GET['momapage']) && count($results['itemsInEvent'])==$results['requestItemsPerPage'])
    {
 
      echo '<a href="'. $_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.++$page.'&type=wp_momapix_photo&event_title='.$result['title'].'&id_event='.$result['id_event'].'">Click here to next >>></a>';
     
          
    } 
    
 // end next button event  
       
   
} 
   

else {

 echo '<p id="top_msg">pag. '.$_GET['momapage'].'</p>';
 
// prev button archive
    
if ($page>1){

    echo '<div id="momapix_image_results"><div  id="momapix_image_results_inside">';
    echo '<br><a href="'.$_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.($_GET['momapage']-1).'&type=wp_momapix_photo"><img id="img_result" src="'.WP_PLUGIN_URL .'/momapix-image-selector/images/prev_arrow.gif"><br>Click here to prev</a>'; 
    echo '</div>';
    echo '</div>';

}   
// prev button archive end

    $json	=	momapix_file_get_contents($url);
   
    $results = 	json_decode($json, $depth = 1);
            
		
		foreach($results['eventsInArchive'] as $result)
		{
                    $image_moma_url = $baseURL."/Image".$result['id'].'.jpg';
                    $image_moma = $result['id'].'.jpg';
                    ?>

<div id="momapix_image_results">
    <div  id="momapix_image_results_inside">
                    
		    
                    
                    <a href="<?php echo $_SERVER["PHP_SELF"];?>?post_id=<?php echo $post_id;?>&tab=<?php echo $tabs; ?>&type=wp_momapix_photo&event_title=<?php echo $result['title'];?>&id_event=<?php echo $result['id_event'];?>">
                        <img id="img_result" src="<?php echo $baseURL.'/Image'.$result['idcover']?>.jpg"/>
                    </a>
                    
                    <?php 
                    echo "<p title=\"".$result['title']." - ".$result['caption']."\">".substr($result['title'],0,100)."</p>"; 
                    ?>
                    
      </div>
        <?php

    ?>
    </div> 
<!-- momapix_image_results -->
   
                    
                 <?php   
                 }
    
 // next button archive only if next page is not empty
    
                 
   
    if (count($results['eventsInArchive'])==$results['requestItemsPerPage'] && (!isset($_GET['momapage'])))
    {
               
    	
    echo '<div id="momapix_image_results"><div  id="momapix_image_results_inside">';
    echo '<a href="'. $_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.++$page.'&type=wp_momapix_photo"><img id="img_result" src="'.WP_PLUGIN_URL .'/momapix-image-selector/images/next_arrow.gif"><br>Click here to next</a>';
    echo '</div>';
    echo '</div>';
    }
   
    elseif (isset($_GET['momapage']) && count($results['eventsInArchive'])==$results['requestItemsPerPage'])
    {
    	
    echo '<div id="momapix_image_results"><div  id="momapix_image_results_inside">';
    echo '<a href="'. $_SERVER['PHP_SELF'].'?post_id='.$post_id.'&tab='.$tabs.'&momapage='.++$page.'&type=wp_momapix_photo"><img id="img_result" src="'.WP_PLUGIN_URL .'/momapix-image-selector/images/next_arrow.gif"><br>Click here to next</a>';
    echo '</div>';
    echo '</div>';  
    } 
    
 // end next button archive  
       
        
  }