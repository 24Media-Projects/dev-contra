<!-- // BEGIN SOCIAL SECTION -->
<tr id="social_section">
  <td align="center" style="padding:35px 0 15px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable">
      <tbody>
        <tr>
          <td style="font: normal normal normal 10px/12px Arial;letter-spacing: 0px;color: #000000;text-align:center;padding:0 0 15px;">FOLLOW NEWS 24/7</td>
        </tr>
        <tr>
          <td align="center">
            <table border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <?php
                  if (get_field('btw__brand_fields__facebook', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__facebook', 'option'); ?>" style="display: inline-block; vertical-align: middle;margin-right: 6px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-facebook.png" alt="Ακολουθήστε μας στο Facebook" style="display: block;max-width: 100%;height: auto;" />
                      </a>
                    </td>
                  <?php }

                  if (get_field('btw__brand_fields__twitter', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__twitter', 'option'); ?>" style="display: inline-block; vertical-align: middle;margin-right: 6px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-twitter.png" alt="Ακολουθήστε μας στο Twitter" style="display: block;max-width: 100%;height: auto;"  />
                      </a>
                    </td>
                  <?php }

                  if (get_field('btw__brand_fields__instagram', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__instagram', 'option'); ?>" style="display: inline-block; vertical-align: middle;margin-right: 6px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-instagram.png" alt="Ακολουθήστε μας στο Instagram" style="display: block;max-width: 100%;height: auto;" />
                      </a>
                    </td>
                  <?php }

                  if (get_field('btw__brand_fields__linkedin', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__linkedin', 'option'); ?>" style="display: inline-block; vertical-align: middle;margin-right: 6px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-linkedin.png" alt="Ακολουθήστε μας στο Linkedin" style="display: block;max-width: 100%;height: auto;"  />
                      </a>
                    </td>
                  <?php }

                  if (get_field('btw__brand_fields__youtube', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__youtube', 'option'); ?>" style="display: inline-block; vertical-align: middle;margin-right: 6px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-youtube.png" alt="Ακολουθήστε μας στο Youtube" style="display: block;max-width: 100%;height: auto;"  />
                      </a>
                    </td>
                  <?php }

                  if (get_field('btw__brand_fields__tiktok', 'option')) { ?>
                    <td align="center">
                      <a target="_blank" href="<?php echo get_field('btw__brand_fields__tiktok', 'option'); ?>" style="display: inline-block; vertical-align: middle;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-tiktok.png" alt="Ακολουθήστε μας στο Tiktok" style="display: block;max-width: 100%;height: auto;"  />
                      </a>
                    </td>
                  <?php }
                  ?>

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
<!-- END SOCIAL SECTION // -->