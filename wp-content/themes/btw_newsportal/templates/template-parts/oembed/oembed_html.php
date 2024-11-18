<?php

/*

add stop_video, stop_video_<provider> as class names on stop button

Avalilable strings:
$video_html: contains the iframe of the video returned by BTW_Embed
$video_ref
$provider_name
$embeded_html

*/

extract( $args );
 ?>


 <div class="featured_video_overlay">

   <?php echo $embeded_html; ?>

   <div class="video_frame">
     <?php echo $video_html;?>
   </div>

 </div>
