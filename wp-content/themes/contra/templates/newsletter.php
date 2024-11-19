<?php // Template Name: NEWSLETTER
get_header();
$section_title = get_the_title();
?>

<div class="legal__wrapper">
    <main class="legal_main newsletter_main">
        <h1 class="section__title"><?php echo remove_punctuation($section_title); ?></h1>
        <div class="paragraph">
            <?php the_content(); ?>
        </div>

        <div class="subscription_form__area">

            <!-- Begin Mailchimp Signup Form -->

            <div id="mc_embed_signup">
                <form
                    action="https://contra.us1.list-manage.com/subscribe/post?u=b89623a4e29393181e6283770&id=11a0ec29ff&v_id=165&f_id=00eec2e1f0"
                    method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
                    target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <!-- <h2>Subscribe</h2> -->
                        <!-- <div class="indicates-required">
							<span class="asterisk">*</span> indicates required
						</div>
-->
                        <div class="mc-field-group">
                            <!-- <label  class="uppercase_label" for="mce-EMAIL">
                                ΣΥΜΠΛΗΡΩΣΤΕ ΤΟ EMAIL ΣΑΣ <span class="asterisk">*</span>
                            </label> -->
                            <input placeholder="Συμπλήρωσε το email σου" type="email" value="" name="EMAIL"
                                class="required email" id="mce-EMAIL">
                        </div>


                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>

                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text" name="b_b89623a4e29393181e6283770_5e0319a029" tabindex="-1" value="">
                        </div>
                        <div class="clear">
                            <input type="submit" value="ΕΓΓΡΑΦΗ" name="subscribe" id="mc-embedded-subscribe"
                                class="button">
                        </div>


                        <div id="mergeRow-gdpr" class="mc-field-group content__gdpr" style="padding-bottom: 0;">

                            <fieldset class="mc_fieldset gdprRequired mc-field-group" name="interestgroup_field"
                                style="padding-bottom: 0;">
                                <label class="lowercase_label checkbox subfield" for="gdpr_61">
                                    <input type="checkbox" id="gdpr_61" name="gdpr[61]" value="Y" class="av-checkbox ">
                                    <label for="gdpr_25">
                                            <svg>
                                                <use xlink:href="#icon-ckeck"></use>
                                            </svg>
                                        </label>
                                    <span>Επιθυμώ να λαμβάνω newsletters από το Contra</span>
                                </label>
                            </fieldset>



                        </div>
                      


                        <div class="form_paragraph">
                            <div class="content__gdpr">
                                <label class="lowercase_label">contra.gr</label>
                                <p>
                                    To website, Contra της εταιρείας «24MEDIA ΑΕ ψηφιακών εφαρμογών»
                                    κατόπιν δική σας επιθυμίας, σας αποστέλλει ενημερωτικά newsletters.
                                    Σύμφωνα με τον Γενικό Κανονισμό Προστασίας Δεδομένων ( GDPR)
                                    δίνει τη δυνατότητα στον καθένα να ελέγχει καλύτερα τα προσωπικά
                                    του δεδομένα.
                                    Για να σας στέλνουμε ειδησεογραφικά newsletters θα θέλαμε τη
                                    συγκατάθεσή σας.
                                </p>
                                <p>
                                    <a href="/oroi-xrisis/">Η εταιρεία μας δηλώνει ότι τηρεί τις διατάξεις του Γενικού
                                        Κανονισμού
                                        Προστασίας Δεδομένων ( GDPR) και σας ενημερώνει ότι το προσωπικό
                                        σας δεδομένο (email) που μας έχετε εμπιστευτεί</a>
                                </p>
                            </div>

                            <div class="content__gdprLegal">
                                <p>
                                    We use Mailchimp as our marketing platform. By clicking below to
                                    subscribe, you acknowledge that your information will be transferred
                                    to Mailchimp for processing.
                                    <a href="https://mailchimp.com/legal/" target="_blank">
                                        Learn more about Mailchimp's privacy practices here.
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'>
            </script>

            <script type='text/javascript'>
            (function($) {
                window.fnames = new Array();
                window.ftypes = new Array();
                fnames[0] = 'EMAIL';
                ftypes[0] = 'email';
                /*
                 * Translated default messages for the $ validation plugin.
                 * Locale: EL
                 */
                $.extend($.validator.messages, {
                    required: "Αυτό το πεδίο είναι υποχρεωτικό.",
                    remote: "Παρακαλώ διορθώστε αυτό το πεδίο.",
                    email: "Παρακαλώ εισάγετε μια έγκυρη διεύθυνση email.",
                    url: "Παρακαλώ εισάγετε ένα έγκυρο URL.",
                    date: "Παρακαλώ εισάγετε μια έγκυρη ημερομηνία.",
                    dateISO: "Παρακαλώ εισάγετε μια έγκυρη ημερομηνία (ISO).",
                    number: "Παρακαλώ εισάγετε έναν έγκυρο αριθμό.",
                    digits: "Παρακαλώ εισάγετε μόνο αριθμητικά ψηφία.",
                    creditcard: "Παρακαλώ εισάγετε έναν έγκυρο αριθμό πιστωτικής κάρτας.",
                    equalTo: "Παρακαλώ εισάγετε την ίδια τιμή ξανά.",
                    accept: "Παρακαλώ εισάγετε μια τιμή με έγκυρη επέκταση αρχείου.",
                    maxlength: $.validator.format("Παρακαλώ εισάγετε μέχρι και {0} χαρακτήρες."),
                    minlength: $.validator.format("Παρακαλώ εισάγετε τουλάχιστον {0} χαρακτήρες."),
                    rangelength: $.validator.format(
                        "Παρακαλώ εισάγετε μια τιμή με μήκος μεταξύ {0} και {1} χαρακτήρων."),
                    range: $.validator.format("Παρακαλώ εισάγετε μια τιμή μεταξύ {0} και {1}."),
                    max: $.validator.format("Παρακαλώ εισάγετε μια τιμή μικρότερη ή ίση του {0}."),
                    min: $.validator.format("Παρακαλώ εισάγετε μια τιμή μεγαλύτερη ή ίση του {0}.")
                });
            }(jQuery));
            var $mcj = jQuery.noConflict(true);
            </script>
            <!--End mc_embed_signup-->





        </div>

    </main>
</div>

<?php get_footer(); ?>