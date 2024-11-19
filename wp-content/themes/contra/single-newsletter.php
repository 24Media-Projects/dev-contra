<?php
ob_start();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?php the_title(); ?></title>
</head>

<body style="margin:0;padding:0;font-family:'Open Sans',Arial,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#F4F4F4;">
  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="margin:0;padding:0;width:100%!important;height:100%!important;mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;">
    <tbody>
      <tr>
        <td align="center" valign="top" style="border-collapse:collapse;">

			<?php btw_get_post_impressions_url(null, true); ?>

          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:100%;max-width:640px;">
            <tbody>
              <!-- BEGIN PREHEADER \\ -->
              <!-- <tr id="templatePreheader">
          <td align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="wrapperTable" style="max-width: 800px;">
              <tbody>
                <tr>
                  <td align="center" colspan="4" style="">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tbody>
                        <tr bgcolor="#F8F8F8">
                          <td align="center" style="padding:10px 26px;">
                            <a href="<?php the_permalink(); ?>" target="_blank" style="text-decoration: none; display: inline-block;  font-size:12px;font-family:'Open Sans',Arial,sans-serif;">
                              Δείτε αυτό το email στο browser.
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr> -->
              <!-- END PREHEADER \\ -->
              <?php
              get_template_part("templates/template-parts/newsletter/header");

              ?>
              <tr>
                <td>
                  <table bgcolor="#ffffff" align="center" border="0" cellpadding="0" cellspacing="0">
                    <?php get_template_part("templates/template-parts/newsletter/featured_post");
                    get_template_part("templates/template-parts/newsletter/posts");
                    get_template_part("templates/template-parts/newsletter/opinion");
                    get_template_part("templates/template-parts/newsletter/ad");
                    get_template_part("templates/template-parts/newsletter/overlay_post");
                    get_template_part("templates/template-parts/newsletter/bg_color_img_post");
                    get_template_part("templates/template-parts/newsletter/bg_color_post");
                    get_template_part("templates/template-parts/newsletter/img_post");
                    get_template_part("templates/template-parts/newsletter/more_posts");
                    ?>
                  </table>
                </td>
              </tr>


              <?php
              get_template_part("templates/template-parts/newsletter/social");
              get_template_part("templates/template-parts/newsletter/footer");
              ?>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>


  <style type="text/css">
    /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
    body {
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: none;
      font-family: Arial, sans-serif;
    }

    img {
      border: 0 none;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
    }

    a img {
      border: 0 none;
    }

    .imageFix {
      display: block;
    }

    table,
    td {
      border-collapse: collapse;
    }

    #bodyTable {
      height: 100% !important;
      margin: 0;
      padding: 0;
      width: 100% !important;
    }


    a {
      color: inherit;
      border: none;
      outline: none;
      text-decoration: none;
    }

    #social_section a:hover,
    #templateFooterMessage a:hover {
      opacity: 0.8;
    }

    .bgcontainer>a {
      position: relative;
      display: block;
    }

    .bgcontainer>a:after {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      content: "";
      visibility: hidden;
      background-color: #fff;
      opacity: 0;
    }

    .bgcontainer>a:hover {
      opacity: 1;
    }

    .bgcontainer>a:hover:after {
      opacity: 1;
    }

    table td {
      border-collapse: collapse;
    }

    p {
      margin: 0;
    }

    #bodyTable {
      height: 100% !important;
      margin: 0;
      padding: 0;
      width: 100% !important;
    }

    .newsletter_intro p {
      margin: 0 0 30px;
    }

    .newsletter_intro a {
      border-bottom: 2px solid #ffcccc;
    }

    .newsletter_intro a:hover {
      color: #ffcccc;
      opacity: 1;
    }

    .newsletter_intro h2,
    .newsletter_intro h3 {
      font-size: 1.3em;
      line-height: 1.23em;
      letter-spacing: 0.02em;
      font-weight: normal;
    }

    @media only screen and (max-width: 900px) {
      .wrapperTable {
        width: 100% !important;
      }
    }


    @media only screen and (max-width: 470px) {

      .newsletter_intro,
      .hightlight_text,
      .editor_signature {
        font-size: 16px !important;
        line-height: 26px !important;
      }

      .newsletter_intro p {
        margin-bottom: 16px !important;
      }

      .small_title {
        font-size: 20px !important;
        line-height: 27px !important;
      }

      .large_title {
        font-size: 26px !important;
        line-height: 32px !important;
      }

      .caption {
        font-size: 10px !important;
        letter-spacing: 0.15em !important;
      }

      .post__author {
        font-size: 10px !important;
        line-height: 17px !important;
      }

      .opinion_author {
        font-size: 13px !important;
      }
    }
  </style>

  <!--[if mso]>
    <style type="text/css">
    body{
      font-family: Arial, sans-serif;
    }
    </style>
  <![endif]-->


</body>

</html>

<?php

$email_html = ob_get_clean();

echo $email_html;

if (isset($_GET['email'])) {
  wp_mail($_GET['email'], get_the_title(), $email_html, array('Content-Type: text/html; charset=UTF-8'));
}
