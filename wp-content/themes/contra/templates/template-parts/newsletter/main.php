<?php
global $post;
setup_postdata($post);

$ad       = get_field('btw__newsletter_fields__ad');
$ad_image = $ad['image'];
$ad_link  = $ad['link'];


$fpost                  = get_field('btw__newsletter_fields__featured_post');
$newsletter_posts       = get_field('btw__newsletter_fields__posts');
$opinion_post           = get_field('btw__newsletter_fields__opinion');
$newsletter_more_posts  = get_field('btw__newsletter_fields__more_posts');
$fend_post              = get_field('btw__newsletter_fields__featured_end_post');


get_template_part("templates/template-parts/newsletter/header");

  
  if ( !empty($fpost) ):
    foreach ($fpost as $featured_post):
      setup_postdata($featured_post);

      $fimage     = get_field('btw__newsletter_fields__featured_post_image');
      $size           = 'large-square';
      // var_dump( $fend_image);


      if ( $fimage ) {
        $fimage_url = $fimage['sizes'][$size];
        $fimage_alt = $fimage['alt'];
      } else {
        $fimage_url = get_the_post_thumbnail_url($featured_post->ID,'large-square');
        $fimage_alt = get_the_title($featured_post->ID);

        if ( empty($fimage_url) ) {
          $fimage_url = get_template_directory_uri() . '/assets/img/default_post_main_image.jpg';
        }
      }

      $categories         = wp_get_post_terms($featured_post->ID, 'category');
      $categories_count   = count($categories);

      if ( $categories_count > 1 ) {
        foreach($categories as $term) {
          if( get_post_meta($featured_post->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
            $primary_cat    = $term;
            $primary_cat_id = $term->term_id;
          }
        }

        if ( empty($primary_cat) ) {
          $primary_cat    = $categories[0];
          $primary_cat_id = $categories[0]->term_id;
        }
      } else {
        $primary_cat      = $categories[0];
        $primary_cat_id   = $categories[0]->term_id;
      }
      
      $category_id                = $primary_cat_id;
      $category_name              = $primary_cat->name;
      $category_permalink         = get_category_link($primary_cat_id);
      $category_priority_over_tag = get_field('btw__categore_fields__priority_over_tag', 'category_' . $category_id);


      $tags = get_the_tags($featured_post->ID);
      if ( $tags ) {
        $tag      = $tags[0];
        $tag_id   = $tag->term_id;
        $tag_name = $tag->name;
        $tag_link = get_category_link($tag_id);
      }


      $byline                 = get_field('btw__article_fields__byline', $featured_post->ID); 
      // author
      $post_author            = get_the_author_meta($featured_post->ID);
      $post__author_id        = get_the_author_meta('ID');
      $post__author_name      = get_the_author_meta('display_name');
      $post__author_permalink = get_author_posts_url( $post__author_id );
  ?>

  <!-- // BEGIN FEATURED POST -->
  <tr id="summary">
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>
          <tr>
            
            <td align="left" style="padding: 60px 0 0;">
              <!--[if mso]>
                <center>
              <![endif]-->
              <a href="<?php echo get_the_permalink($featured_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin-bottom: 30px;">
                <img src="<?php echo $fimage_url; ?>" style="width: 100%; max-width:100%;"/>
              </a>

              <div class="fpost_content" style="padding: 0 6.68%;">

                <?php if ( $tags || $categories ) { ?>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td bgcolor="#ffcccc" style="padding: 6px 10px; margin-bottom: 15px;">
                        <?php if ( $tags && $category_priority_over_tag !=1 ) { ?>
                        <a class="caption" href="<?php echo $tag_link;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($tag_name); ?>
                          </span>
                        </a>
                        <?php } else { ?>
                        <a class="caption" href="<?php echo $category_permalink;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($category_name); ?>
                          </span>
                        </a>
                        <?php }?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                <h2 class="large_title" style="font-family:Arial,sans-serif; font-size: 32px; line-height: 42px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin-top: 11px; margin-bottom:15px;">
                  <a href="<?php echo get_the_permalink($featured_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                    <?php echo get_the_title($featured_post->ID); ?>
                  </a>
                </h2>

                <?php if ( $byline ) { ?>
                <div class="post__author" style="color: #888888; font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; margin: 0;">
                  <?php echo remove_punctuation($byline); ?>
                </div>
                <?php 
                  } else {
                    if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
                ?>
                <div class="post__author" style="color: #888888;font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px;; letter-spacing: 0.1em; margin: 0;">
                  <?php echo remove_punctuation($post__author_name); ?>
                </div>
                <?php 
                    } else { 
                ?>
                <div class="post__author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 0;">
                  <a href="<?php echo $post__author_permalink; ?>" style="color: #888888; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #ffcccc;">
                    <?php echo remove_punctuation($post__author_name); ?>
                  </a>
                </div>
                <?php 
                    }
                  }
                ?>
              </div>
             
  
              <!--[if mso]>
                </center>
              <![endif]-->
            </td>

          </tr>
        </tbody>
        <!-- testt  -->
      </table>
    </td>
  </tr>
  <?php 
    endforeach;
    wp_reset_postdata();
  endif;
  ?>
  <!-- END FEATURED POST // -->





  <!-- // BEGIN POSTS -->
  <?php
  if ( !empty($newsletter_posts) ): 

  ?>
  <tr id="top_news">
    <td align="center" style="padding: 0;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>

          <?php
          $c = 0;
          foreach ($newsletter_posts as $newsletter_post):
            setup_postdata($newsletter_post);

            $c++;


            $pimage_url = get_the_post_thumbnail_url($newsletter_post->ID,'medium_large');
            $pimage_alt = get_the_title($newsletter_post->ID);

            if ( empty($pimage_url) ) {
              $pimage_url = get_template_directory_uri() . '/assets/img/default_post_main_image.jpg';
            }

            $tags = get_the_tags($newsletter_post->ID);
            if ( $tags ) {
              $tag      = $tags[0];
              $tag_id   = $tag->term_id;
              $tag_name = $tag->name;
              $tag_link = get_category_link($tag_id);
            }

            $categories         = wp_get_post_terms($newsletter_post->ID, 'category');
            $categories_count   = count($categories);

            if ( $categories_count > 1 ) {
              foreach($categories as $term) {
                if( get_post_meta($newsletter_post->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
                  $primary_cat    = $term;
                  $primary_cat_id = $term->term_id;
                }
              }

              if ( empty($primary_cat) ) {
                $primary_cat    = $categories[0];
                $primary_cat_id = $categories[0]->term_id;
              }
            } else {
              $primary_cat      = $categories[0];
              $primary_cat_id   = $categories[0]->term_id;
            }
            
            $category_id                = $primary_cat_id;
            $category_name              = $primary_cat->name;
            $category_permalink         = get_category_link($primary_cat_id);
            $category_priority_over_tag = get_field('btw__categore_fields__priority_over_tag', 'category_' . $category_id);

            $byline                 = get_field('btw__article_fields__byline', $newsletter_post->ID); 
            // author
            $post_author            = get_the_author_meta($newsletter_post->ID);
            $post__author_id        = get_the_author_meta('ID');
            $post__author_name      = get_the_author_meta('display_name');
            $post__author_permalink = get_author_posts_url( $post__author_id );

            if ( $c == 1 ) { 
          ?>
          <tr class="article">
            <td align="left" style="padding: 60px 0 0;">

              <div class="bgcontainer" style="background:url(<?php echo get_template_directory_uri(); ?>/assets/img/newsletter/pinkbg.jpg) no-repeat scroll top center white; background-size: 100% auto; padding: 6.68% 6.68% 0;">
              
                <a href="<?php echo get_the_permalink($newsletter_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin-bottom: 30px;">
                  <img src="<?php echo $pimage_url; ?>" style="width: 100%; max-width:100%;"/>
                </a>

                <div class="fpost_content" style="padding: 0;">

                  <?php if ( !empty($tags) || !empty($categories) ) { ?>
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td bgcolor="#ffcccc" style="padding: 6px 10px; margin-bottom: 15px;">
                          <?php if ( !empty($tags) && $category_priority_over_tag !=1 ) { ?>
                          <a class="caption" href="<?php echo $tag_link;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                            <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                              <?php echo remove_punctuation($tag_name); ?>
                            </span>
                          </a>
                          <?php } else { ?>
                          <a class="caption" href="<?php echo $category_permalink;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                            <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                              <?php echo remove_punctuation($category_name); ?>
                            </span>
                          </a>
                          <?php }?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <?php } ?>

                  <h2 class="small_title" style="font-family:Arial,sans-serif; font-size: 26px; line-height: 32px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin-top: 14px; margin-bottom:19px;">
                    <a href="<?php echo get_the_permalink($newsletter_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                      <?php echo get_the_title($newsletter_post->ID); ?>
                    </a>
                  </h2>

                  <?php if ( $byline ) { ?>
                  <div class="post__author" style="color: #888888; font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; margin: 0;">
                    <?php echo remove_punctuation($byline); ?>
                  </div>
                  <?php 
                    } else {
                      if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
                  ?>
                  <div class="post__author" style="color: #888888;font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px;; letter-spacing: 0.1em; margin: 0;">
                    <?php echo remove_punctuation($post__author_name); ?>
                  </div>
                  <?php 
                      } else { 
                  ?>
                  <div class="post__author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 0;">
                    <a href="<?php echo $post__author_permalink; ?>" style="color: #888888; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #ffcccc;">
                      <?php echo remove_punctuation($post__author_name); ?>
                    </a>
                  </div>
                  <?php 
                      }
                    }
                  ?>
                </div>
              </div>
              <!-- .bgcontainer closed -->
            </td>
          </tr>

          <?php 
            } else {
          ?>
          <tr class="article">
            <td align="left" style="padding: 60px 5% 0;">
              <a href="<?php echo get_the_permalink($newsletter_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin-bottom: 30px;">
                <img src="<?php echo $pimage_url; ?>" style="width: 100%; max-width:100%;"/>
              </a>

              <div class="fpost_content" style="padding: 0;">

                <?php if ( !empty($tags) || !empty($categories) ) { ?>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td bgcolor="#ffcccc" style="padding: 6px 10px; margin-bottom: 15px;">

                        <?php if ( !empty($tags) && $category_priority_over_tag !=1 ) { ?>
                        <a class="caption" href="<?php echo $tag_link;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($tag_name); ?>
                          </span>
                        </a>
                        <?php } else { ?>
                        <a class="caption" href="<?php echo $category_permalink;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($category_name); ?>
                          </span>
                        </a>
                        <?php }?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                <h2 class="small_title" style="font-family:Arial,sans-serif; font-size: 26px; line-height: 32px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin-top: 14px; margin-bottom:19px;">
                  <a href="<?php echo get_the_permalink($newsletter_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                    <?php echo get_the_title($newsletter_post->ID); ?>
                  </a>
                </h2>

                <?php if ( $byline ) { ?>
                <div class="post__author" style="color: #888888; font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; margin: 0;">
                  <?php echo remove_punctuation($byline); ?>
                </div>
                <?php 
                  } else {
                    if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
                ?>
                <div class="post__author" style="color: #888888;font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px;; letter-spacing: 0.1em;  margin: 0;">
                  <?php echo remove_punctuation($post__author_name); ?>
                </div>
                <?php 
                    } else { 
                ?>
                <div class="post__author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 0;">
                  <a href="<?php echo $post__author_permalink; ?>" style="color: #888888; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #ffcccc;">
                    <?php echo remove_punctuation($post__author_name); ?>
                  </a>
                </div>
                <?php 
                    }
                  }
                ?>
              </div>
            </td>
          </tr>
          <?php 
            }
          endforeach;
          wp_reset_postdata();
          ?>
        </tbody>
      </table>
    </td>
  </tr>
  <!-- END POSTS // -->
  <?php endif; ?>








  <?php 
  if ( !empty($opinion_post) ):
    foreach ($opinion_post as $opost):
      setup_postdata($opost);

      $byline                 = get_field('btw__article_fields__byline', $featured_post->ID); 
      // author
      $post_author            = get_the_author_meta($featured_post->ID);
      $post__author_id        = get_the_author_meta('ID');
      $post__author_name      = get_the_author_meta('display_name');
      $post__author_permalink = get_author_posts_url( $post__author_id );
  ?>
  <!-- // BEGIN OPINION POST -->
  <tr id="opinion">
    <td align="center" style="padding: 60px 0 0;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>
          <tr>
            
            <td align="center" bgcolor="#ffcccc" style="padding: 11% 6.68% 9.4%;">
              <div class="section_title" style="padding-bottom: 23px;">
                <img src="<?php echo get_template_directory_uri();?>/assets/img/newsletter/opinion.png" width="270" style="display:block; margin-left:auto; margin-right:auto; max-width:80%;">
              </div>

              <h2 class="large_title" style="font-family:Arial,sans-serif; font-size: 32px; line-height: 42px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin:0;">
                  <a href="<?php echo get_the_permalink($opost->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                    <?php echo get_the_title($opost->ID); ?>
                  </a>
              </h2>

              <?php if ( $byline ) { ?>
              <div class="opinion_author" style="color: #000000; font-family:Arial,sans-serif; font-weight: normal; font-size: 15px; line-height: 19px; letter-spacing: 0.1em; margin: 26px 0 0;">
                <?php echo remove_punctuation($byline); ?>
              </div>
              <?php 
                } else {
                  if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
              ?>
              <div class="opinion_author" style="color: #000000;font-family:Arial,sans-serif; font-weight: normal; font-size: 15px; line-height: 19px;; letter-spacing: 0.1em; margin: 26px 0 0;">
                <?php echo remove_punctuation($post__author_name); ?>
              </div>
              <?php 
                  } else { 
              ?>
              <div class="opinion_author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 15px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 26px 0 0;">
                <a href="<?php echo $post__author_permalink; ?>" style="color: #000000; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #000000; margin:0;">
                  <?php echo remove_punctuation($post__author_name); ?>
                </a>
              </div>
              <?php 
                  }
                }
              ?>

            </td>

          </tr>
        </tbody>
        <!-- testt  -->
      </table>
    </td>
  </tr>
  <?php 
    endforeach;
    wp_reset_postdata();
  endif;
  ?>
  <!-- END OPINION POST // -->


  <?php if($ad_image):?>
  <tr>
    <td align="center" colspan="4" style="padding: 60px 0 0;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>
          <tr>
            <td class="banner_area" align="center" collspan="2" valign="center" style="min-width: 100%;">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                  <tr>
                    <td style="padding: 40px 0; border-top: 1px solid #dddddd; border-bottom: 1px solid #dddddd;">
                      <?php if ($ad_link) { ?>
                      <a target="_bank" href="<?php echo $ad_link;?>" style="outline:none; border:none; display: block;">
                      <?php } ?>
                        <img src="<?php echo $ad_image['url'];?>" style="max-width:100%; display: block; margin: auto;"/>

                      <?php if ($ad_link) { ?>
                      </a>
                      <?php } ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
  <?php endif; ?>






  <!-- // BEGIN MORE POSTS -->
  <?php
  if ( !empty($newsletter_more_posts) ): 

    $more_posts__section_title = get_field('btw__newsletter_fields__more_posts_title');

  ?>
  <tr id="top_news">
    <td align="center" style="padding: 0;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>
          <?php if ( $more_posts__section_title ) { ?>
          <tr>
            <td align="center">
              <h3 style="padding-top: 53px; font-family:'Arial Black', Arial, sans-serif; font-weight:bold; font-size: 28px; line-height: 34px; mso-line-height-rule:exactly;  letter-spacing: 0.25em; margin: 0 0 -35px;">
                <?php echo remove_punctuation($more_posts__section_title); ?>
              </h3>
            </td>
          </tr>
          <?php }

    


          $c = 0;
          foreach ($newsletter_more_posts as $mpost):
            setup_postdata($mpost);

            $c++;


            $mimage_url = get_the_post_thumbnail_url($mpost->ID,'medium_large');
            $mimage_alt = get_the_title($mpost->ID);

            if ( empty($mimage_url) ) {
              $mimage_url = get_template_directory_uri() . '/assets/img/default_post_main_image.jpg';
            }

            $tags = get_the_tags($mpost->ID);
            if ( $tags ) {
              $tag      = $tags[0];
              $tag_id   = $tag->term_id;
              $tag_name = $tag->name;
              $tag_link = get_category_link($tag_id);
            }

            $categories         = wp_get_post_terms($mpost->ID, 'category');
            $categories_count   = count($categories);

            if ( $categories_count > 1 ) {
              foreach($categories as $term) {
                if( get_post_meta($mpost->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
                  $primary_cat    = $term;
                  $primary_cat_id = $term->term_id;
                }
              }

              if ( empty($primary_cat) ) {
                $primary_cat    = $categories[0];
                $primary_cat_id = $categories[0]->term_id;
              }
            } else {
              $primary_cat      = $categories[0];
              $primary_cat_id   = $categories[0]->term_id;
            }
            
            $category_id                = $primary_cat_id;
            $category_name              = $primary_cat->name;
            $category_permalink         = get_category_link($primary_cat_id);
            $category_priority_over_tag = get_field('btw__categore_fields__priority_over_tag', 'category_' . $category_id);


            $byline                 = get_field('btw__article_fields__byline', $mpost->ID); 
            // author
            $post_author            = get_the_author_meta($mpost->ID);
            $post__author_id        = get_the_author_meta('ID');
            $post__author_name      = get_the_author_meta('display_name');
            $post__author_permalink = get_author_posts_url( $post__author_id );
          ?>
          <tr class="article">
            <td align="left" style="padding: 60px 6.68% 0;">
              <a href="<?php echo get_the_permalink($mpost->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin-bottom: 30px;">
                <img src="<?php echo $mimage_url; ?>" style="width: 100%; max-width:100%;"/>
              </a>

              <div class="fpost_content" style="padding: 0;">

                <?php if ( !empty($tags) || !empty($categories) ) { ?>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td bgcolor="#ffcccc" style="padding: 6px 10px; margin-bottom: 15px;">

                        <?php if ( !empty($tags) && $category_priority_over_tag !=1 ) { ?>
                        <a class="caption" href="<?php echo $tag_link;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($tag_name); ?>
                          </span>
                        </a>
                        <?php } else { ?>
                        <a class="caption" href="<?php echo $category_permalink;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($category_name); ?>
                          </span>
                        </a>
                        <?php }?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                <h2 class="small_title" style="font-family:Arial,sans-serif; font-size: 26px; line-height: 32px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin-top: 14px; margin-bottom:19px;">
                  <a href="<?php echo get_the_permalink($mpost->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                    <?php echo get_the_title($mpost->ID); ?>
                  </a>
                </h2>

                <?php if ( $byline ) { ?>
                <div class="post__author" style="color: #888888; font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; margin: 0;">
                  <?php echo remove_punctuation($byline); ?>
                </div>
                <?php 
                  } else {
                    if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
                ?>
                <div class="post__author" style="color: #888888;font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px;; letter-spacing: 0.1em;  margin: 0;">
                  <?php echo remove_punctuation($post__author_name); ?>
                </div>
                <?php 
                    } else { 
                ?>
                <div class="post__author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 0;">
                  <a href="<?php echo $post__author_permalink; ?>" style="color: #888888; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #ffcccc;">
                    <?php echo remove_punctuation($post__author_name); ?>
                  </a>
                </div>
                <?php 
                    }
                  }
                ?>
              </div>
            </td>
          </tr>
          <?php 
          endforeach;
          wp_reset_postdata();
          ?>
        </tbody>
      </table>
    </td>
  </tr>
  <!-- END MORE POSTS // -->
  <?php endif; ?>




  <?php 
  if ( !empty($fend_post) ):
    foreach ($fend_post as $featured_end_post):
      setup_postdata($featured_end_post);

      $fend_image     = get_field('btw__newsletter_fields__featured_end_post_image');
      $size           = 'large-square';
      // var_dump( $fend_image);


      if ( $fend_image ) {
        $fend_image_url = $fend_image['sizes'][$size];
        $fend_image_alt = $fend_image['alt'];
      } else {
        $fend_image_url = get_the_post_thumbnail_url($featured_end_post->ID,'large-square');
        $fend_image_alt = get_the_title($featured_end_post->ID);

        if ( empty($fend_image_url) ) {
          $fend_image_url = get_template_directory_uri() . '/assets/img/default_post_main_image.jpg';
        }
      }

      $tags = get_the_tags($featured_end_post->ID);
      if ( $tags ) {
        $tag      = $tags[0];
        $tag_id   = $tag->term_id;
        $tag_name = $tag->name;
        $tag_link = get_category_link($tag_id);
      }

      $categories         = wp_get_post_terms($featured_end_post->ID, 'category');
      $categories_count   = count($categories);

      if ( $categories_count > 1 ) {
        foreach($categories as $term) {
          if( get_post_meta($featured_end_post->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
            $primary_cat    = $term;
            $primary_cat_id = $term->term_id;
          }
        }

        if ( empty($primary_cat) ) {
          $primary_cat    = $categories[0];
          $primary_cat_id = $categories[0]->term_id;
        }
      } else {
        $primary_cat      = $categories[0];
        $primary_cat_id   = $categories[0]->term_id;
      }
      
      $category_id                = $primary_cat_id;
      $category_name              = $primary_cat->name;
      $category_permalink         = get_category_link($primary_cat_id);
      $category_priority_over_tag = get_field('btw__categore_fields__priority_over_tag', 'category_' . $category_id);


      $byline                 = get_field('btw__article_fields__byline', $featured_end_post->ID); 
      // author
      $post_author            = get_the_author_meta($featured_end_post->ID);
      $post__author_id        = get_the_author_meta('ID');
      $post__author_name      = get_the_author_meta('display_name');
      $post__author_permalink = get_author_posts_url( $post__author_id );
  ?>

  <!-- // BEGIN FEATURED END POST -->
  <tr id="footer_posts">
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
        <tbody>
          <tr>
            
            <td align="left" style="padding: 60px 0 0;">
              <!--[if mso]>
                <center>
              <![endif]-->
              <a href="<?php echo get_the_permalink($featured_end_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin-bottom: 30px;">
                <img src="<?php echo $fend_image_url; ?>" style="width: 100%; max-width:100%;"/>
              </a>

              <div class="fpost_content" style="padding: 0 6.68%;">

                <?php if ( $tags || $categories ) { ?>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td bgcolor="#ffcccc" style="padding: 6px 10px; margin-bottom: 15px;">
                        <?php if ( $tags && $category_priority_over_tag !=1 ) { ?>
                        <a class="caption" href="<?php echo $tag_link;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($tag_name); ?>
                          </span>
                        </a>
                        <?php } else { ?>
                        <a class="caption" href="<?php echo $category_permalink;?>" style="display: inline-block; color:#000; outline:none; mso-line-height-rule:exactly; font-weight: bold; font-family: 'Arial Black', Arial, sans-serif; font-size:12px; line-height:12px; letter-spacing: 0.25em; text-decoration:none !important; text-decoration:none;">
                          <span style="text-decoration:none !important; text-decoration:none; font-family: inherit; font-weight: inherit;">
                            <?php echo remove_punctuation($category_name); ?>
                          </span>
                        </a>
                        <?php }?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <?php } ?>

                <h2 class="large_title" style="font-family:Arial,sans-serif; font-size: 32px; line-height: 42px; font-weight: normal; mso-line-height-rule:exactly; color:#000000; outline:none; border:none; display:block; margin-top: 11px; margin-bottom:15px;">
                  <a href="<?php echo get_the_permalink($featured_end_post->ID);?>" onkeyup="" style="text-decoration: none; outline:none; border:none; display:block; margin: 0;">
                    <?php echo get_the_title($featured_end_post->ID); ?>
                  </a>
                </h2>

                <?php if ( $byline ) { ?>
                <div class="post__author" style="color: #888888; font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; margin: 0;">
                  <?php echo remove_punctuation($byline); ?>
                </div>
                <?php 
                  } else {
                    if ( $post__author_name == 'Ladylike' || $post__author_name == 'ladulike' || $post__author_name == 'ldladmin' ) { 
                ?>
                <div class="post__author" style="color: #888888;font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px;; letter-spacing: 0.1em;  margin: 0;">
                  <?php echo remove_punctuation($post__author_name); ?>
                </div>
                <?php 
                    } else { 
                ?>
                <div class="post__author" style="font-family:Arial,sans-serif; font-weight: normal; font-size: 11px; line-height: 19px; letter-spacing: 0.1em; color: #000; margin: 0;">
                  <a href="<?php echo $post__author_permalink; ?>" style="color: #888888; text-decoration: none; outline:none; border:none; display:inline-block; line-height:inherit; font-size: inherit; border-bottom: 1px solid #ffcccc;">
                    <?php echo remove_punctuation($post__author_name); ?>
                  </a>
                </div>
                <?php 
                    }
                  }
                ?>
              </div>
             
  
              <!--[if mso]>
                </center>
              <![endif]-->
            </td>

          </tr>
        </tbody>
        <!-- testt  -->
      </table>
    </td>
  </tr>
  <?php 
    endforeach;
    wp_reset_postdata();
  endif;
  ?>
  <!-- END FEATURED END POST // -->







