<?php
$logo     = get_field('btw__brand_fields__logo_newsletter_footer', 'option');

switch_to_locale('en_US');
$date_publsihed = get_the_date('D F j H:i:s T Y');
switch_to_locale('el');

?>
<!-- // BEGIN FOOTER // -->

<tr id="templateFooterMessage">
  <td align="center" style="padding: 0 0 40px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable">
      <tbody>
        <tr>
          <td style="font: normal normal normal 10px/12px Arial;letter-spacing: 0px;color: #000000;text-align:center;padding:0 0 5px;">SITE ΤΟΥ ΟΜΙΛΟΥ 24 MEDIA<br> © <?php echo $date_publsihed; ?> CONTRA.<br> ALL RIGHTS RESERVED</td>
        </tr>
        <tr>
          <td class="logo_area" align="center">
            <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" style="outline:none; border:none; color:inherit; text-align: center;">
              <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" style="display: block; margin-left: auto; margin-right: auto;max-width: 100%;height: auto;">
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
<!-- END FOOTER // -->