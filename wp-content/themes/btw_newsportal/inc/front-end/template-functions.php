<?php

// remove punctuation from greek uppercase letters
function remove_punctuation($string = null)
{
  if ($string === null) {
    return null;
  }

  $latin_check = '/[\x{0030}-\x{007f}]/u';
  if (preg_match($latin_check, $string)) {
    $string = strtoupper($string);
  }

  $letters = array('α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ', 'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω', 'é');
  $letters_accent = array('ά', 'έ', 'ή', 'ί', 'ό', 'ύ', 'ώ', 'é');
  $letters_upper_accent = array('Ά', 'Έ', 'Ή', 'Ί', 'Ό', 'Ύ', 'Ώ', 'É');
  $letters_upper_solvents = array('ϊ', 'ϋ', 'ΐ', 'ΰ');
  $letters_other = array('ς');

  $letters_to_uppercase = array('Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω', 'É');
  $letters_accent_to_uppercase = array('Α', 'Ε', 'Η', 'Ι', 'Ο', 'Υ', 'Ω', 'É');
  $letters_upper_accent_to_uppercase = array('Α', 'Ε', 'Η', 'Ι', 'Ο', 'Υ', 'Ω', 'É');
  $letters_upper_solvents_to_uppercase  = array('Ϊ', 'Ϋ', 'Ϊ', 'Ϋ');
  $letters_other_to_uppercase = array('Σ');

  $lowercase = array_merge($letters, $letters_accent, $letters_upper_accent, $letters_upper_solvents, $letters_other);
  $uppercase = array_merge($letters_to_uppercase, $letters_accent_to_uppercase, $letters_upper_accent_to_uppercase, $letters_upper_solvents_to_uppercase, $letters_other_to_uppercase);

  $uppecase_string = str_replace($lowercase, $uppercase, $string);

  return $uppecase_string;
}




// Clear title from special character '&'
function btw_clean_title($string)
{
  $string = str_replace('&', 'and', $string); // Removes special chars.
  $string = str_replace('#038;', '', $string);
  return $string;
}




/*
  Get post author data
  If btw__article_fields__hide_author: return false
  if btw__article_fields__byline: return byline
*/
function btw_get_post_author( $post = null, $suppress_filters = false ){

	if (!$post) {
		global $post;
	}

	$post_display_options = get_field( 'btw__global_fields__display_options', $post->ID ) ?: [];

	if( in_array( 'hide_author', $post_display_options )
		|| apply_filters( 'btw/post_author/hide_author', false, $post )
	){
		return false;
	}

	$author_id = $post->post_author;
	$author = new WP_User($author_id);

	$post_byline = get_field('btw__article_fields__byline', $post->ID);

	$archive_url = $post_byline
		? get_field('btw__article_fields__byline_url', $post->ID)
		: ( get_user_meta($author_id, 'user_has_archive', true) ? get_author_posts_url($author_id) : null );

	$author_display_name = $post_byline ?: $author->display_name;

	if( !$suppress_filters ){
		$author_display_name = apply_filters('btw/post_author/display_name', $author_display_name, $post);
	}

	return (object) array(
		'author_id' => $author_id,
		'display_name' => $author_display_name,
		'byline' => $post_byline,
		'archive_link' => array(
			'url' => $archive_url,
			'title' => esc_attr( "Δείτε όλα τα άρθρα από το χρήστη {$author_display_name}" ),
			'target_blank' => (bool)maybe_return_target_blank($archive_url),
		),
		'meta' => array(
			'avatar_id' => btw_get_author_avatar_id( $author_id, !empty( $post_byline ) ),
			'job_description' => get_user_meta($author_id, 'btw__user_fields__job_description', true),
			'bio' => get_user_meta($author_id, 'description', true),
		)
	);
}


function btw_get_author_avatar_id( $author_id, $force_default_avatar = false ){

	$default_author_avatar = get_field('btw__brand_fields__default_author_logo', 'option');
	$author_avatar = get_field('btw__global_fields__featured_image', 'user_' . $author_id);

	$avatar_id = $force_default_avatar || !$author_avatar
		? apply_filters( 'btw/default_author_avatar_id', $default_author_avatar['id'] ?? 0 )
		: apply_filters( 'btw/author/avatar_id', $author_avatar['id'] );

	return $avatar_id;

}

/*
  Post author html, based on btw_get_post_author
  display_avatar: include avatar on html, default false
*/
function btw_get_post_author_html( $post = null, $display_avatar = false, $suppress_filters = false, $image_srcsets = [], $lazyload = true ){

	if( !$post ){
		global $post;
	}

	$post_author_data = btw_get_post_author($post, $suppress_filters);

	if( !$post_author_data ) return false;

	ob_start();

	$anchor_tag_target = !empty($post_author_data->archive_link['target']) ? 'target="_blank"' : '';
	$open_anchor_tag = $post_author_data->archive_link['url'] ? "<a {$anchor_tag_target} href=\"{$post_author_data->archive_link['url']}\" title=\"{$post_author_data->archive_link['title']}\">" : '';
	$close_anchor_tag = $post_author_data->archive_link['url'] ? '</a>' : '';


	?>

	<?php echo $open_anchor_tag; ?>

	<?php if ($display_avatar && !empty($post_author_data->meta['avatar_id'])) : ?>

        <div class="post__author_avatar">
			<?php echo btw_get_attachment_html(
				attachment_id: $post_author_data->meta['avatar_id'],
				image_srcsets: $image_srcsets,
				forced_alt_text: $post_author_data->archive_link['title'],
				lazyload: $lazyload
			); ?>
        </div>

	<?php endif; ?>

    <span class="post__author">
    <?php echo $post_author_data->display_name; ?>
  </span>

	<?php echo $close_anchor_tag; ?>

	<?php
	$author_html = ob_get_clean();

	echo apply_filters( 'btw/post_author/author_html', $author_html, $post );
}


function btw_return_post_author_html( $post = null, $display_avatar = false ){
  ob_start();
  $post_author_html = btw_get_post_author_html( $post, $display_avatar );
  return ob_get_clean();
}

/*
  Add attachment credits,  add class to attachment landscape / portrait
  See wpdb get_var function
  get_field acf  function
  wp_get_attachment_image_src wp function
  wp_get_attachment_caption wp function
  get_post_meta wp function
  for more details
*/
function get_content_images( $content ){

  global $wpdb, $post, $btw_global_settings;

  $btw_theme_settings = $btw_global_settings->get_theme_settings();

  preg_match_all("/<img[^>]+>?/", $content, $inline_attachments);

  if (!$inline_attachments) return $content;

  foreach ($inline_attachments['0'] as $key => $inline_attachment) {

    // get attachment id from <img>, id / class
    if( !preg_match( '/id="([^"]+?)"/', $inline_attachment, $attachment_id_from_img_id )
      && !preg_match( '/wp-image-(\d+)\s?/', $inline_attachment, $attachment_id_from_img_class )
    ){
      continue;
    }

    
    $attachment_id = $attachment_id_from_img_id['1'] ?? ( $attachment_id_from_img_class['1'] ?? null );

    if (!$attachment_id) continue;

    $attachment_data = wp_get_attachment_image_src($attachment_id, 'full');

    if( $attachment_data === false ){
      continue;
    }

    $attachment_view_type = $attachment_data['1'] > $attachment_data['2']
      ? 'landscape'
      : ( $attachment_data['1'] == $attachment_data['2'] ? 'square' : 'portrait' );

    $attachment_obj = get_post($attachment_id);
    $attachments_credits = btw_attachment_credits_html($attachment_obj);
    $attachment_alt_text = esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true));

    $container_classes = apply_filters('btw/post_content/inline_images/container_classes', "attachment-container wp-caption {$attachment_view_type}", $inline_attachment, $post);

    // amp 
    $amp_image_final_html =
      '<figure class="' . $container_classes . '">
        <amp-img
          alt="' . $attachment_alt_text . '"
          src="' . $attachment_data['0'] . '"
          width="' . $attachment_data['1'] . '"
          height="' . $attachment_data['2'] . '"
          layout="responsive" lightbox>
        </amp-img>
        ' . $attachments_credits . '
      </figure>';

      
    // default
    $image_html = apply_filters( 'btw/post_content/inline_images/inline_image_html', $inline_attachments['0'][$key] . $attachments_credits, $post);

    $image_final_html =
      "<div class=\"{$container_classes}\">
          {$image_html}
      </div>";

    $content = str_replace( $inline_attachments['0'][$key], ( !btw_is_amp_endpoint() ?  $image_final_html : $amp_image_final_html ), $content );
  }

  return $content;
}





/*
  Get oembed html data of featured post type video
  Returns: video html
  See BTW_Embed class
      and posts with featured videos ( post type video )
*/

if (!function_exists('btw_get_post_video')) {
  function btw_get_post_video($args = array())
  {

    global $post;

    extract($args);

    $oembed_video = new BTW_Embed();

    return $oembed_video->get_oembed_video_html($video_url, array(
      'embeded_html' => $embeded_html ?? '',
    ));
  }
}



/*
  Get which third party embed scripts matches post content embed_codes
  Returns unique array with third party embed scripts
  See
    inc/front-end/template-actions.php
    btw_return_template_part above function
    for more details
*/

function get_embed_scripts($post)
{

  $post_content = [];
  $post_content[] = $post->post_content;

  $embed_scripts = [];
  foreach ($post_content as $content) {
    if (!preg_match_all('/\[embed_code_sc\s+provider="([^"]+)"?[^]]+\]/', $content, $providers)) continue;

    foreach ($providers['1'] as $provider) :

      if ($provider == '24media_player') continue;

      $script = btw_return_template_part( 'global_elements/third_party_scripts/embeds/' . $provider ) ?? '';

      $embed_scripts[] = trim( $script );
    endforeach;
  }

  return array_values(array_unique(array_filter($embed_scripts)));
}






/*
  Brand logo
  return array with
  url, alt text
*/
function get_brand_logo()
{

  global $btw_global_settings;

  $logo = get_field('btw__brand_fields__logo', 'option') ?: [];

  $fallback_logo = $btw_global_settings->get_default_logo();

  return array_merge($fallback_logo, $logo);
}

/*
  Get post primary category
  If post has multi categories, use post meta _yoast_wpseo_primary_category
  If post has one categoery, use the first category
  If post has no categories, use default category
  return object with
  term_id, name, slug, term_link, priority_over_tag
*/

function btw_get_post_primary_category($post = null)
{

  if (!$post) {
    global $post;
  }

  $primary_category_id = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
  if (!$primary_category_id || !get_term($primary_category_id, 'category')) {
    $post_categories_ids = wp_get_post_categories($post->ID, array('fields' => 'ids'));
    $primary_category_id = !empty($post_categories_ids['0']) ? $post_categories_ids['0'] : get_option('default_category');
  }

  $primary_category = get_term($primary_category_id, 'category');

  return (object) array(
    'term_id'           => $primary_category->term_id,
    'name'              => $primary_category->name,
    'slug'              => $primary_category->slug,
    'parent'            => $primary_category->parent,
    'taxonomy'          => 'category',
    'term_link'         => get_term_link($primary_category, 'category'),
    'priority_over_tag' => !empty(get_field('btw__category_fields__priority_over_tag', 'category_' . $primary_category->term_id)),
  );
}


function btw_get_post_primary_tag($post = null)
{

  if (!$post) {
    global $post;
  }

  $post_tags = get_the_terms($post->ID, 'post_tag');

  if (!$post_tags) return btw_get_post_primary_category($post);

  // better get the first tag with array_shift, because first key may not be 0
  $primary_tag = array_shift($post_tags);

  return (object) array(
    'term_id' => $primary_tag->term_id,
    'name' => $primary_tag->name,
    'taxonomy' => 'post_tag',
    'term_link' => get_term_link($primary_tag)
  );
}


function btw_get_post_primary_term($post = null, $taxonomy = '')
{

  if (!$post) {
    global $post;
  }

  $primary_category = btw_get_post_primary_category($post);

  if ($primary_category->slug == 'live-blog') {
    return $primary_category;
  }

  // if user sets a taxonomy, return this as primary term
  if ($taxonomy) {
    return $taxonomy == 'category' ? btw_get_post_primary_category($post) : btw_get_post_primary_tag($post);
  }

  if (is_tag()) {
    return btw_get_post_primary_category($post);
  }

  $primary_tag = btw_get_post_primary_tag($post);

  if (is_category()) {
    return $primary_tag ?: $primary_category;
  }

  return $primary_category->priority_over_tag || !$primary_tag  ? $primary_category : $primary_tag;
}



/*
  Sharing tools html
*/

if (!function_exists('btw_sharing_tools')) {

  function btw_sharing_tools($post = null, $container_class = '')
  {

    if (!$post) {
      global $post;
    }

    $sharing_title    = get_the_title($post);
    $sharing_url      = get_the_permalink($post);
    $sharing_sitename = get_bloginfo('description');
    $post__lead       = get_field('btw__global_fields__lead', $post->ID, false, false);
    $sharing_summary  = $post__lead ? strip_tags($post__lead) :  strip_tags(apply_filters('the_excerpt', $post->post_excerpt));
    $site_url         = site_url();
    $facebook_app_id  = get_field('btw__brand_fields__facebook_app_id', 'option');

    // Filter to change the label of the provider. Could be string / html...
    $sharing_providers = apply_filters(
      'btw/sharing_tools/providers',
      array(
        'facebook'  => 'FACEBOOK',
        'twitter'   => 'TWITTER',
        'messenger' => 'MESSENGER',
        'whatsapp'  => 'WHATSAPP',
        'linkedin'  => 'LINKEDIN',
        'email'     => 'EMAIL',
      )
    );

  ?>

    <ul class="sharing_tools <?php echo $container_class; ?>">

      <?php if (in_array('facebook', array_keys($sharing_providers))) : ?>

        <li class="social facebook">
          <a aria-label="Facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $sharing_url; ?>&title=<?php echo $sharing_title; ?>&description=<?php echo $sharing_summary; ?>" target="_blank">
            <?php echo $sharing_providers['facebook']; ?>
          </a>
        </li>

      <?php endif; ?>

      <?php if (in_array('twitter', array_keys($sharing_providers))) : ?>

        <li class="social twitter">
          <a aria-label="Twitter" href="http://twitter.com/intent/tweet?text=<?php echo $sharing_title; ?>+<?php echo $sharing_url; ?>" target="_blank">
            <?php echo $sharing_providers['twitter']; ?>
          </a>
        </li>

      <?php endif; ?>

      <?php if (in_array('linkedin', array_keys($sharing_providers))) : ?>

        <li class="social linkedin">
          <a aria-label="Linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $sharing_url; ?>" target="_blank">
            <?php echo $sharing_providers['linkedin']; ?>
          </a>
        </li>

      <?php endif; ?>
      

      <?php if (in_array('messenger', array_keys($sharing_providers))) : ?>

        <li class="social messenger">
          <a aria-label="Messenger" class="messenger__desktop" href="http://www.facebook.com/dialog/send?app_id=<?php echo $facebook_app_id; ?>&link=<?php echo $sharing_url; ?>&amp;redirect_uri=<?php echo esc_url($site_url); ?>" target="_blank">
            <?php echo $sharing_providers['messenger']; ?>
          </a>
          <a aria-label="Messenger" class="messenger__mobile" href="fb-messenger://share/?link=<?php echo $sharing_url; ?>&app_id=<?php echo $facebook_app_id; ?>" target="_blank">
            <?php echo $sharing_providers['messenger']; ?>
          </a>
        </li>

      <?php endif; ?>

      <!-- <?php if (in_array('whatsapp', array_keys($sharing_providers))) : ?>

      <li class="social whatsapp">
        <a aria-label="Whatsapp" href="https://api.whatsapp.com/send?text=<?php echo $sharing_url; ?>" data-action="share/whatsapp/share" target="_blank">
          <?php echo $sharing_providers['whatsapp']; ?>
        </a>
      </li>

      <?php endif; ?> -->


      <!-- <?php if (in_array('email', array_keys($sharing_providers))) : ?>

        <li class="social email">
          <a aria-label="Email" href="mailto:?subject=<?php echo $sharing_title; ?>&amp;body=<?php echo $sharing_url; ?>" target="_blank" title="Share by Email">
          <?php echo $sharing_providers['email']; ?>
          </a>
        </li>

      <?php endif; ?> -->

    </ul>

  <?php }
}

/*
  Post featured image html
  Featured image / default image
  Returns object with
  id, url, alt text, html class, credits_html

  size: attachment size, default full
  attachment_id: attachment ( featured image id )
  default_image_name: fallback default image name

*/

function btw_get_post_featured_image($size = 'full', $post = null, $attachment_id = null){

  if (!$post) {
    global $post;
  }

  $post_title = get_the_title($post);
  $attachment_id = $attachment_id ?: get_post_thumbnail_id($post);
  $attachment_obj = get_post($attachment_id);

  $default_image = apply_filters('btw/post/default_featured_image', get_field( 'btw__brand_fields__default_image', 'option' ), $post );
  $default_image_url = $size == 'full' ? $default_image['url'] : $default_image['sizes'][ $size ];

  if (!$attachment_id || $attachment_obj === null || $attachment_obj->post_type != 'attachment') {

    return (object) array(
      'id'           => $default_image['ID'],
      'url'          => $default_image_url,
      'alt'          => esc_attr('default image'),
      'class'        => '',
      'credits_html' => '',
    );
  }

  $attachment_src         = wp_get_attachment_image_src($attachment_obj->ID, $size, true);
  $attachment_url         = $attachment_src[0];
  $attachment_alt         = get_post_meta($attachment_obj->ID, '_wp_attachment_image_alt', true) ?: $post_title;
  $attachment_class       = $attachment_src[1] < 1080 ? 'small_image' : '';

  return (object) array(
    'id'           => $attachment_id,
    'url'          => $attachment_url,
    'alt'          => esc_attr($attachment_alt),
    'class'        => $attachment_class,
    'credits_html' => btw_attachment_credits_html($attachment_obj),
  );
}


/**
 * Get post featured image html.
 * @param int, $attachment_id
 * @param array, $image_srcsets
 * @param string, $forced_alt_text
 * @param bool, $lazyload
 */
function btw_get_attachment_html( $attachment_id, $image_srcsets = [], $forced_alt_text = '', $lazyload = true ){

	$attachment_alt = $forced_alt_text ?: get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$esc_attachment_alt = esc_attr($attachment_alt );

	// set default image srcsets, if not set
	if( !$image_srcsets ){
		$image_srcsets = array(
			array(
				'image_size' => 'full',
				'default'    => true,
			)
		);
	}

	/**
	 * Default image srcset is the one that has a default key set
	 * or the first array defined on the $image_srcsets
	 **/
	$default_srcset = array_filter( $image_srcsets, function( $image_srcset ){
		return !empty( $image_srcset['default'] );
	});

	$default_srcset = $default_srcset ? array_shift($default_srcset) : array_shift($image_srcsets);

	/**
	 * @note Attachment html is different for amp
	 */
	if (!btw_is_amp_endpoint()) {

		$default_attachment_image_src = wp_get_attachment_image_src( $attachment_id, $default_srcset['image_size'] );

		$img_class = $lazyload ? 'class="lazyload"' : '';
		$img_src = "src=\"{$default_attachment_image_src['0']}\"";
		$img_src = $lazyload ? 'data-' . $img_src : $img_src;

		//first img is the default image
		$attachment_html[] = "<img decoding=\"async\" loading=\"lazy\" {$img_class} {$img_src} alt=\"{$esc_attachment_alt}\" />";

		/**
		 * Get attachment picture sources
		 */
		foreach( $image_srcsets as $image_srcset ){

			// media_query key is required
			if( empty( $image_srcset['media_query'] ) ){
				continue;
			}

			$attachment_image_src = wp_get_attachment_image_src( $attachment_id, $image_srcset['image_size'] );
			$attachment_image_src = $attachment_image_src[0];

			$attachment_html[] = "<source media=\"{$image_srcset['media_query']}\" srcset=\"{$attachment_image_src}\" />";
		}

		$picture_html = '<picture>' . implode("\n", array_reverse($attachment_html)) . '</picture>';

	} else {

		$filtered_srcsets = array_filter( $image_srcsets, function( $image_srcset ){
			return isset( $image_srcset['mobile'] ) && $image_srcset['mobile'] === true;
		});

		$mobile_size = array_shift( $filtered_srcsets );


		$mobile_size = $mobile_size['image_size'] ?? $default_srcset['image_size'];

		$amp_attachment_image_src = wp_get_attachment_image_src( $attachment_id, $mobile_size );
		$amp_attachment_image_src = $amp_attachment_image_src['0'];

		$picture_html = "<img src=\"{$amp_attachment_image_src}\" alt=\"{$esc_attachment_alt}\" />";
	}

	return $picture_html;
}


/*
  Attachment credits html
*/
function btw_attachment_credits_html($attachment_obj)
{

  $image_credits = [];

  if ($attachment_obj->post_excerpt) {
    $image_credits['wp_caption'] = $attachment_obj->post_excerpt;
  }

  $acf_credit_fields = apply_filters('btw/attachment_credits/acf_fields', array(
    'credits'      => 'btw__attachment_fields__credits',
    'photographer' => 'btw__attachment_fields__photographer',
  ));

  foreach( $acf_credit_fields as $key => $acf_credit_field){

    $credit = get_field($acf_credit_field, $attachment_obj->ID);

    if (!empty($credit) ){
      $image_credits[$key] = $credit;
    }
  }

  if (!$image_credits) return null;

  ob_start(); ?>

  <div class="image__credits">
    <div class="inner">
      <?php foreach ($image_credits as $key => $value) : ?>
        <span class="credit_item <?php echo $key; ?>">
          <?php echo apply_filters('btw/attachment_credits/credit_value', $value, $key); ?>
        </span>
      <?php endforeach; ?>
    </div>
  </div>

<?php

  return ob_get_clean();
}


/*
  AMP: Attachment credits html
*/
function btw_amp_attachment_credits_html($attachment_obj)
{

  $image_credits = [];

  if ($attachment_obj->post_excerpt) {
    $image_credits[] = $attachment_obj->post_excerpt;
  }

  foreach (['btw__attachment_fields__credits', 'btw__attachment_fields__photographer'] as $acf_credit_field) {
    $credit = get_field($acf_credit_field, $attachment_obj->ID);
    if ($credit) {
      $image_credits[] = $credit;
    }
  }

  if (!$image_credits) return null;

  ob_start(); ?>

  <figcaption class="image">
    <div class="inner">
      <span class="credit_item"><?php echo implode("</span>\n<span class=\"credit_item\">", $image_credits); ?></span>
    </div>
  </figcaption>

<?php

  return ob_get_clean();
}

/**
 * Define the btw_image_sizes, if not exists.
 * Need it for the initial theme setup
 */
if (!function_exists('btw_image_sizes')) {
  function btw_get_intermediate_image_sizes()
  {
    return [];
  }
}


/* Get attachment available sizes urls
   If attachment not exists, return empty array
*/
function btw_get_attachment_sizes($attachment_id)
{

  if ($attachment_id === -1) return [];

  $available_sizes = btw_get_intermediate_image_sizes();

  $return = array(
    'full' => wp_get_attachment_url($attachment_id),
  );

  foreach ($available_sizes as $size) {

    $src = wp_get_attachment_image_src($attachment_id, $size);
    $return[$size] = $src['0'];
  }

  return $return;
}



function btw_get_post_impressions_url($post = null, $inline_style = false)
{

  if (!$post) {
    global $post;
  }

  $impressions_url = get_field('btw__article_fields__impressions_url', $post);
  $timestamp = strtotime('now');

  if (!$impressions_url) return false;
?>

  <img class="imp_url" <?php if($inline_style) echo 'width="1" height="1"';?> src="<?php echo $impressions_url . '[' . $timestamp . ']'; ?>" alt="post impression url" <?php if($inline_style) echo 'style="opacity: 0; width: 1px; height: 1px; display: block;"';?> />

  <?php

}


function btw_get_social_media_follow_us_html($icon_color = 'black', $container = 'ul', $args = [])
{

  $platforms = [
    'facebook',
    'instagram',
    'twitter',
    'youtube',
  ];

  ob_start();

  foreach ($platforms as $platform) {

    $url = get_field("btw__brand_fields__{$platform}", 'option');

    if (!$url) continue;
  ?>

    <li class="<?php echo $platform; ?>">
      <a title="Ακολουθήστε μας στο <?php echo $platform; ?>" href="<?php echo $url; ?>" target="_blank">
        <?php echo strtoupper($platform); ?>
      </a>
    </li>

  <?php }

  if (!empty($args['include_email'])) {

    $brand_email = get_field('btw__brand_fields__email', 'option');

  ?>

    <li class="email">
      <a title="Στείλε μας email" href="mailto:<?php echo $brand_email; ?>">
        EMAIL
      </a>
    </li>

  <?php  }

  $return_html = ob_get_clean();

  echo $container ? sprintf('<%1$s class="social_media">%2$s</%1$s>', $container, $return_html) : $return_html;
}


/*
  Custom post pagination based on get_the_posts_pagination function
*/

function btw_get_the_posts_pagination($wp_query = null, $args = array())
{

  if (!$wp_query) {
    global $wp_query;
  }

  if ($wp_query->max_num_pages <= 1) return '';

  $current_page = get_query_var('paged', 0);

  $pagination_args = array_merge(array(
    'screen_reader_text' => 'Posts navigation',
    'aria_label'         => 'Posts',
    'class'              => 'pagination',
    'type'               => 'array',
  ), $args);

  $pagination_args['total'] = $wp_query->max_num_pages;

  // Set up paginated links.
  $links = paginate_links($pagination_args);

  if (!$links) return '';

  if ($current_page === 0) {
    array_unshift($links, '<span class="prev prev-disabled">' . $pagination_args['prev_text'] . '</span>');
  }

  if ($current_page == $wp_query->max_num_pages) {
    $links[] =  '<span class="next next-disabled">' . $pagination_args['next_text'] . '</span>';
  }

  $navigation = btw_return_template_part('template-parts/archive/pagination', [
    'links' => $links,
    'pagination_args' => $pagination_args,
  ]);

  return $navigation;
}

/**
 * Alias function of btw_get_impressions_url.
 *
 * @deprecated
 */
function btw_get_impression_url($impressions_url){
	btw_get_impressions_url($impressions_url);
}

function btw_get_impressions_url($impressions_url, $inline_style = false)
{

	if (!$impressions_url) return false;

	$timestamp = strtotime('now');

	?>

    <img class="imp_url" <?php if($inline_style) echo 'width="1" height="1"';?> src="<?php echo $impressions_url . '[' . $timestamp . ']'; ?>" alt="impression url" <?php if($inline_style) echo 'style="opacity: 0; width: 1px; height: 1px; display: block;"';?> />

	<?php

}


function btw_get_groups_by_group_type( $group_type ){

    $group_args = array(
      'post_type'         => 'group',
      'post_status'       => 'publish',
      'orderby'           => 'menu_order',
      'order'             => 'ASC',
      'meta_key'          => 'btw__group_fields__group_type',
      'meta_value'        => $group_type,
      'posts_per_page'    => -1,
      'suppress_filters'   => false,
    );

    $groups = new WP_Query( $group_args );

    if( is_wp_error( $groups ) ){
      return [];
    }

    return $groups;

}



function maybe_print_target_blank($url)
{
	if( !$url ) return ' ';

	$is_external = !str_starts_with( $url, site_url() ) && !str_starts_with($url, '#');
	echo $is_external ? ' target="_blank" ' : ' ';
}

function maybe_return_target_blank($url)
{
	ob_start();
	maybe_print_target_blank($url);
	return ob_get_clean();
}

function maybe_print_anchor_opening_tag($url, $attrs = []){
	$str = ' ';
	foreach($attrs as $k => $v){
		$str .= $k . '="' . $v . '" ';
	}
	$str .= maybe_return_target_blank($url);
	echo !empty($url) ? '<a href="' . $url . '"' . $str . '>' : '';
}

function maybe_print_anchor_closing_tag($url){
	echo !empty($url) ? '</a>' : '';
}
