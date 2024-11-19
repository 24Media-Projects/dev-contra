<?php
/*
  All actions related to front end
  See add_action function for more details
*/



/*
  Include Embed scripts on front-end.
  This actions is called on footer to include third party scripts needed to run embeds in content
  See inc/front-end/template-functions.php get_embed_scripts theme function for more details
*/
add_action('wp_footer', function () {

	if (is_single()) {

		global $post;
		$embed_scripts = get_embed_scripts($post);
		echo implode("\n", $embed_scripts);
	}
}, 99);






add_action('wp_head', function () { ?>

	<script>
		var btwRefreshSlot = function(adSlots) {
			if (typeof gptAdSlots === 'undefined') return;

			var slotNames = gptAdSlots.filter(function(gptAdSlot) {
				return adSlots.indexOf(gptAdSlot.getSlotId().getDomId()) !== -1;
			});

			if (!slotNames.length) return;

			googletag.cmd.push(function() {
				googletag.pubads().refresh(slotNames);
			})
		}
	</script>

	<?php });



add_action('wp_footer', function () {

	global $btw_video_scripts_args;

	BTW_Embed::set_video_variables();

	if (!empty($btw_video_scripts_args['vimeo'])) { ?>

		<script src="https://player.vimeo.com/api/player.js"></script>

	<?php } ?>

	<?php if (!empty($btw_video_scripts_args['glomex'])) { ?>

		<script src="https://player.glomex.com/integration/1/glomex-player.js"></script>

	<?php }
}, 1);


add_action('wp_footer', function () {

	global $btw_video_scripts_args;

	if (!empty($btw_video_scripts_args['facebook'])) { ?>

		<div id="fb-root"></div>
		<script async defer src="https://connect.facebook.net/el_GR/sdk.js#xfbml=1&version=v9.0"></script>

	<?php } ?>

	<?php if (!empty($btw_video_scripts_args['youtube'])) { ?>

		<script src="https://www.youtube.com/iframe_api"></script>

<?php }
}, 999);
