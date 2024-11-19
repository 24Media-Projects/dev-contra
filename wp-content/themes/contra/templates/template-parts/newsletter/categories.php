<?php 
$newsletter__footer_cats = get_field('btw__newsletter_fields__terms', 'option');

if ( have_rows('btw__newsletter_fields__terms', 'option') ) {
?>

<!-- // BEGIN CATEGORIES -->
<tr id="categories_section">
	<td align="center" style="padding: 60px 0 0;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 600px;">
      		<tbody>
        		<tr>
        			<?php 
        			while ( have_rows('btw__newsletter_fields__terms', 'option') ):
        				the_row();

        				$footer_cat_name = get_sub_field('cat_title');
        				$footer_cat_link = get_sub_field('cat_link');

        			?>
        			<td align="center" style="padding: 25px 0px; border-top: 1px solid #dddddd; border-bottom: 1px solid #dddddd;">
                    	<a target="_blank" href="<?php echo $footer_cat_link;?>" style="font-family: 'Arial Black', Aria, sans-serif; font-weight: bold; font-size: 12px; line-height: 14px; color: #000000; letter-spacing: 0.1em; display: inline-block; vertical-align: middle;">
                      		<?php echo remove_punctuation($footer_cat_name);?>
                    	</a>
                  	</td>
                  	<?php 
                  	endwhile;
                  	?>
        		</tr>
        	</tbody>
        </table>
	</td>
</tr>
<!-- // END CATEGORIES -->

<?php 
}
?>