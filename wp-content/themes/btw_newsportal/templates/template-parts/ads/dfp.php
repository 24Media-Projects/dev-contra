<?php

/**
 *  Available strings
 * @param string, $slot_id: dfp slot id
 * @param array, $container_class: default empty
 * @param string,  $container_id: default empty
 * @param bool, $auto_refresh: slot auto refresh functionality
 */

if( btw_hide_ads() ) return;

$container_id = !empty($container_id) ? 'id="' . $container_id . '"' : '';

$container_class = $container_class ?? [];

if( !in_array( $slot_id, $container_class ) ){
  $container_class[] = $slot_id;
}

?>

<div <?php echo $container_id; ?> class="ads_element <?php echo implode( ' ', $container_class );?>">
  <div class="dfp-wrap inner rectangle <?php echo $slot_id; ?>">
    <div id="<?php echo $slot_id; ?>">
      <script>
        googletag.cmd.push(function() {
          googletag.display('<?php echo $slot_id; ?>');
        });
      </script>

      <?php if (!empty($auto_refresh)) : ?>

        <script>
          googletag.cmd.push(function() {
            setInterval(btwRefreshSlot.bind(null, ['<?php echo $slot_id; ?>']), <?php echo $auto_refresh; ?>);
          });
        </script>

      <?php endif; ?>

    </div>
  </div>
</div>