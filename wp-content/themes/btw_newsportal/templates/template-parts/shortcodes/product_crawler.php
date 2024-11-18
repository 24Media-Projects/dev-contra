<div class="product__container">
	<?php

  $product_url          = wp_unslash($args['url']);
  $product_name         = wp_unslash($args['name']);
  $product_desc         = wp_unslash($args['desc']);
  $product_img          = is_numeric($args['img']) ? wp_get_attachment_image_src($args['img'],'full')['0'] : wp_unslash($args['img']);
  $product_img_credits  = wp_unslash($args['credits']);
  $product_price        = wp_unslash($args['price']);
  $product_sale_price   = wp_unslash($args['sale_price']);
  $shop_name            = wp_unslash($args['shop_name']);
  $product_buy_link     = wp_unslash($args['buylink']);
	$product_has_buy_now_button = !empty($args['has_buy_now_button']) ? ( $args['has_buy_now_button'] == 'false' ? false : true ) : true;
  $product_disable_link = !empty($args['disable_link']) ? ( $args['disable_link'] == 'false' ? false : true ) : false;

  $product_img__alt     = is_numeric($args['img']) && get_post_meta( $args['img'], '_wp_attachment_image_alt', true ) ? get_post_meta( $args['img'], '_wp_attachment_image_alt', true ) : $product_name;
	?>

  <div class="product_item">

  	<figure class="product_item__thumbnail">
      <?php
      if ( !($product_disable_link) ) {
        if ( $product_buy_link ) {
      ?>
      <a target="_blank" href="<?php echo $product_buy_link; ?>" title="<?php echo esc_html($product_name);?>">
      <?php
        } else {
      ?>
      <a target="_blank" href="<?php echo $product_url; ?>" title="<?php echo esc_html($product_name);?>">
      <?php
        }
      ?>
			  <img src="<?php echo $product_img;?>" alt="<?php echo esc_html($product_img__alt);?>">
		  </a>
      <?php
      } else {
      ?>
      <img src="<?php echo $product_img;?>" alt="<?php echo esc_html($product_img__alt);?>">
      <?php
      } ?>
	  </figure>

		<div class="product_item__content">

      <div class="flex-item first">
        <h4 class="product_item__title">
          <?php
          if ( !($product_disable_link) ) {

            if ( $product_buy_link ) {
          ?>
          <a target="_blank" href="<?php  echo $product_buy_link; ?>" title="<?php echo esc_html($product_name);?>">
          <?php
            } else {
          ?>
          <a target="_blank" href="<?php  echo $product_url; ?>" title="<?php echo esc_html($product_name);?>">
          <?php
            }
          ?>
            <?php echo remove_punctuation($product_name);?>
          </a>

          <?php
          } else {
          ?>
          <span><?php echo $product_name;?></span>
          <?php
          }
          ?>
        </h4>

        <?php
        if ($product_price) {
          if ( $product_sale_price ) { ?>
        <div class="product_item__price on_sale">
          <span class="regular_price"><?php echo $product_price;?></span>
          <span class="sale_price">
            <em>Τιμή με έκπτωση: </em><?php echo $product_sale_price;?>
          </span>
        </div>
        <?php
          } else {
        ?>
        <div class="product_item__price">
          <span class="regular_price"><?php echo $product_price;?></span>
        </div>
        <?php
          }
        } ?>
      </div>

      <div class="product_item__desc"><?php echo $product_desc;?></div>
  	</div>
    <!-- .product_item__content closed -->


    <?php
    $footer_class = null;

    if( (!$product_disable_link && $product_has_buy_now_button && $product_buy_link) || $shop_name ) {

      if( (!$product_disable_link && $product_has_buy_now_button && $product_buy_link) && $shop_name ) {
        $footer_class = 'has_buy_button has_shop_name';
      } elseif ( !$product_disable_link && $product_has_buy_now_button && $product_buy_link ) {
        $footer_class = 'has_buy_button no_shop_name';
      } elseif ( $shop_name ) {
        $footer_class = 'has_shop_name no_buy_button';
      }
    ?>
    <div class="product_footer <?php echo $footer_class; ?>">


      <?php 
      if( !$product_disable_link && $product_has_buy_now_button ) {
        if( $product_buy_link ){
      ?>
      <a class="product_item__buy_button" target="_blank" href="<?php  echo $product_buy_link; ?>" title="<?php echo esc_html($product_name);?>">
      <?php
        } else {
      ?>
      <a class="product_item__buy_button" target="_blank" href="<?php  echo $product_url; ?>" title="<?php echo esc_html($product_name);?>">
      <?php
        }
      ?>
        Αγόρασέ το
      </a>
      <?php
      }
      ?>

      <?php if ( $shop_name ) { ?>
      <div class="product_item__shop_name"><?php echo $shop_name;?></div>
      <?php } ?>
    </div>
    <?php } ?>

  </div>
  <!-- .product_item closed -->

</div>
<!-- .product__container closed -->
