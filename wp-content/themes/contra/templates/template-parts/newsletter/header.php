<?php
$logo     = get_field('btw__brand_fields__logo_newsletter', 'option');

$newsletter_title     = get_field('btw__newsletter_fields__title');
$newsletter_intro     = get_the_content();



$date_published       = get_the_date('l j F Y');
?>
<!-- // BEGIN HEADER -->
<tr id="templateHeader">
  <td align="center" style="font-family:Arial,sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable">
      <tbody>

        <tr>
          <td align="center" colspan="4" style="padding: 48px 0 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody>
                <tr>
                  <td class="logo_area" colspan="2" align="center" valign="top" style="padding: 0 0 13px;">
                    <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" style="outline:none; border:none; color:inherit; text-align: center;">
                      <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" style="display: block;width: 100%;height: auto;margin: 0 auto;">
                    </a>
                  </td>
                </tr>

                <tr>
                  <td colspan="2" align="center" valign="top">
                    <?php if ($newsletter_title) { ?>
                      <h1 style="display:none; font-family:'Arial Black', Arial, sans-serif; font-weight:bold; font-size: 15px; line-height: 15px; mso-line-height-rule:exactly;  letter-spacing: 0.25em; margin: 0;">
                        <?php echo remove_punctuation($newsletter_title); ?>
                      </h1>
                    <?php } ?>

                    <p style="font: normal normal normal 17px/19px Georgia;letter-spacing: 0px;color: #000000;margin:0">
                      <?php echo $date_published; ?>
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>


        <?php if (!empty($newsletter_intro) || !empty($hightlight_text_desc)) { ?>
          <tr>
            <td align="center" colspan="4" style="padding: 50px 6.68% 52px; border-bottom: 1px solid #dddddd;">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                  <?php if (!empty($newsletter_intro)) { ?>
                    <tr>
                      <td colspan="2" align="left" valign="top">
                        <div class="newsletter_intro" style="font-family: Arial, sans-serif; font-weight:normal; font-size: 18px; line-height: 30px; margin: 0;">
                          <?php the_content(); ?>
                        </div>
                      </td>
                    </tr>
                  <?php }

                  /*
                if ( !empty($hightlight_text_desc) ) {
                ?>
                <tr>
                  <td colspan="2" align="left" valign="top" style="border: 15px solid #ffcccc; padding: 17px 25px;">
                    <?php if ( $hightlight_text_title ) { ?>
                    <div class="hightlight_text_title" style="font-family: 'Arial Black', Arial, sans-serif; font-weight:bold; font-size: 18px; line-height: 30px; letter-spacing: 0.25em; margin: 0;">
                      <?php echo remove_punctuation($hightlight_text_title); ?>
                    </div>
                    <?php } ?>

                    <div class="hightlight_text" style="font-family: Arial, sans-serif; font-weight:normal; font-size: 18px; line-height: 30px; margin: 0;">
                      <?php echo $hightlight_text_desc; ?>
                    </div>
                  </td>
                </tr>
                <?php
                }
                */

                  /*
                if ( !empty($editor_signature) ) { 
                ?>
                <tr>
                  <td colspan="2" align="right" valign="top" style="padding-top: 41px;">
                    <div class="editor_signature" style="font-family: Arial, sans-serif; font-weight:normal; font-size: 18px; line-height: 30px; margin: 0;">
                      <?php echo $editor_signature; ?>
                    </div>
                  </td>
                </tr>
                <?php 
                }
                */
                  ?>
                </tbody>
              </table>
          </tr>

        <?php } ?>
      </tbody>
    </table>
  </td>
</tr>
<!-- END HEADER // -->