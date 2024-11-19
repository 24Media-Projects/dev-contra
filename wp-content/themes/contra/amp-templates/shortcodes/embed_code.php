<?php
  global $contra_amp;

  $provider = $args['provider'];
  $embed_code = $args['embed_code'];

  $embed_code_html = $contra_amp->get_embed_code_html($provider,$embed_code);

?>


<div class="embed_code__container provider_<?php echo $provider;?>">
<div class="embed_code_item">

  <div class="embed_code_item__content">

    <?php echo $embed_code_html;?>

  </div>
  <!-- .embed_code_item__content closed -->

</div>
<!-- .embed_code_item closed -->

</div>
<!-- .embed_code__container closed -->
