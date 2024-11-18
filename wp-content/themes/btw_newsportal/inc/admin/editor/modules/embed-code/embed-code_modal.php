<?php

  $providers = apply_filters( 'btw/editor_modules/embed_code/admin/providers', array(
      '' => 'Διαλέξτε Provider',
      'youtube' => 'Youtube',
      'vimeo' => 'Vimeo',
      'instagram' => 'Instagram',
      'facebook' => 'Facebook',
      'twitter' => 'Twitter',
      'tumblr' => 'Tumblr',
      'giphy' => 'Giphy',
      'mix-cloud' => 'Mix Cloud',
      'reddit' => 'Reddit',
      'sound-cloud' => 'Sound Cloud',
      'pinterest' => 'Pinterest',
      'getty-images' => 'Getty Images',
      'google-maps' => 'Google Maps',
      'streamable' => 'Streamable (supports Spotify)',
      'spotify' => 'spotify',
      'mls-soccer' => 'MLS Soccer',
      'typeform' => 'Typeform',
      'google-form' => 'Google Form',
      'glomex' => 'Glomex',
      'ew' => 'Entertainment Weekly',
      'daily-mail' => 'Daily Mail',
      'dailymotion' => 'Daily Motion',
      'playbuzz' => 'Playbuzz',
      'promo-simple' => 'Promo Simple',
      'tiktok' => 'TikTok',
      'apester-media' => 'Apester Media',
      'dev_provider' => 'Dev Provider',
    ));

?>

<div tabindex="3" id="embed-code-modal" class="embed-code-modal btw-editor-modal">
  <div class="btw-editor-modal__inner">
    <div class="btw-editor-modal__close">
      X
    </div>
    <h5 class="btw-editor-modal__title">

    </h5>

    <form class="embed-code-modal__form">
      <div class="embed-code__field">
        <select name="embed-code__field--provider" class="embed-code__field--provider" required>
        <?php foreach( $providers as $value =>  $label ) :?>
          <option value="<?php echo $value;?>"><?php echo $label;?></option>
        <?php endforeach; ?>
        </select>
      </div>
      <div class="embed-code__field">
        <label for="embed-code__code">Εισαγωγή Embed Code</label>
        <textarea id="embed-code__code" name="embed-code__code"  class="embed-code__field--code" required ></textarea>
      </div>

      <!-- <div class="embed-code__field">
        <label for="embed-code__code">Μήκος Embed Container ( σε px )</label>
        <input type="number" id="embed-code__container-width" name="embed-code__container-width"  class="embed-code__field--container-width" min="0"/>
      </div>
      <div class="embed-code__field">
        <label for="embed-code__code">Ύψος Embed Container ( σε px )</label>
        <input type="number" id="embed-code__container-height" name="embed-code__container-height"  class="embed-code__field--container-height" min="0"/>
      </div> -->

      <button class="embed-code-modal__btn button btn">Εισαγωγή</button>

    </form>
  </div>
</div>
