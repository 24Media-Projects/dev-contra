<?php

/**
 * Available strings
 * @var string, $slot_id
 * @var string, $slot_name
 * @var string, $amp_targeting
 * @var array, $sizes
 * @var array, $container_class: default empty
 * @var string, $container_id: default empty
 */

global $post;
$post_display_options = get_field( 'btw__global_fields__display_options' ) ?: [];
$post_hide_ads = in_array( 'hide_ads', $post_display_options );

$container_id = !empty($container_id) ? 'id="' . $container_id . '"' : '';

$container_class = $container_class ?? [];

if (!in_array($slot_id, $container_class)) {
  $container_class[] = $slot_id;
}

/**
 * Check if post is set to not show ads
 */
if ( $post_hide_ads ) {
  return;
}

/**
$dimensions = explode( ',', $sizes );
$width = explode('x', $dimensions)['0'];
$height = explode('x', $dimensions)['1'];

**/

?>
<div <?php echo $container_id; ?> class="ads_element <?php echo implode( ' ', $container_class );?>">

    <div class="dfp-wrap has-amp-bg inner rectangle <?php echo $slot_id; ?>">
      <amp-ad 
        width=336 height=280
        type="doubleclick"
        data-slot="/4834629/contra.gr/<?php echo $slot_name;?>"
        data-multi-size="<?php echo $sizes;?>"
        data-multi-size-validation="false"
        layout="fixed"
        json='<?php echo $amp_targeting;?>'>
      </amp-ad>
    </div>
</div>


