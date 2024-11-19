<?php

/*

Avalilable strings:
$amp_html: contains the iframe of the video returned by BTW_EMBED
$video_ref
$provider_name
$embeded_html

*/

extract( $args );
 ?>


 <div class="featured_video_overlay">

   <?php echo $embeded_html; ?>

   <div class="video_frame">
     <?php echo $amp_html;?>
   </div>

 </div>
