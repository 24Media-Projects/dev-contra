<div tabindex="2" id="product-crawler-modal" class="product-crawler-modal btw-editor-modal">
  <div class="btw-editor-modal__inner">
    <div class="btw-editor-modal__close">
      X
    </div>
    <h5 class="btw-editor-modal__title">

    </h5>
    <form class="product-crawler-modal__form" autocomplete="off">


      <div class="product__field">
        <label for="product-url">URL</label>
        <input type="url" name="product-url" class="product__fields--url" id="product-url" required />
        <button type="button" name="product__url-button" class="product__url-button button btn">Ανίχνευση</button>
        <span class="product-crawler__crawl_error product-crawler__error_msg hide">Δεν βρέθηκε κάτι,μπορείτε να τα συμπληρώσετε εσείς.</span>
      </div>

      <!-- <div class="product__field">
          <label for="product-url">URL Προιόντος</label>
          <input type="text" name="product-url" id="product-url" class="product__fields--url"  />
        </div> -->
      <div class="product__fields-container hide">

        <div class="product__field">
          <label for="product-name">Επωνυμία eshop</label>
          <input type="text" name="product-shop-name" id="product-shop-name" class="product__fields--shop-name" required />
          <button class="reset_value hide" data-reset="shop_name">Επαναφορά</button>
        </div>


        <div class="product__field">
          <label for="product-name">Όνομα Προιόντος</label>
          <input type="text" name="product-name" id="product-name" class="product__fields--name" required />
          <button class="reset_value hide" data-reset="name">Επαναφορά</button>
        </div>

        <div class="product__field">
          <label for="product-desc">Περιγραφή</label>
          <input type="text" name="product-desc" id="product-desc" class="product__fields--desc" required />
          <button class="reset_value hide" data-reset="desc">Επαναφορά</button>
        </div>

        <div class="product__field">
          <label for="product-img">Φωτογραφία</label>

          <div class="inner">
            <div class="product__fields--img-preview">

            </div>
            <div class="field-actions">
              <button class="wp-media-img button btn">Επιλογή από βιβλιοθήκη πολυμέσων</button>
              <button class="reset_value hide" data-reset="img">Επαναφορά</button>
            </div>
          </div>
          <input type="hidden" name="product-img" id="product-img" class="product__fields--img" required />
          <span class="product-crawler__img_missing product-crawler__error_msg hide">Επιλέξτε φωτογραφία.</span>
        </div>

        <div class="product__field hide">
          <label for="product-img-credits">Credits Φωτογραφίας</label>
          <input type="text" name="product-img-credits" id="product-img-credits" class="product__fields--img-credits" />
        </div>

        <div class="product__field">
          <label for="product-price">Τιμή</label>
          <input type="text" name="product-price" id="product-price" class="product__fields--price" />
          <button class="reset_value hide" data-reset="price">Επαναφορά</button>
        </div>

        <div class="product__field">
          <label for="product-price">Τιμή με Έκπτωση</label>
          <input type="text" name="product-sale-price" id="product-sale-price" class="product__fields--sale-price" />
          <button class="reset_value hide" data-reset="price">Επαναφορά</button>
        </div>


        <div class="product__field">
          <label for="product-buylink">
            Link Προϊόντος
            <small>Αν αφήσετε το πεδίο κενό θα χρησιμοποιηθεί το crawl url.</small>
          </label>
          <input type="text" name="product-buylink" id="product-buylink" class="product__fields--buylink" />
        </div>

        <div class="product__field">
          <label for="product-buy-now-checkbox">
            Ενεργοποίηση Buy Now Button
            <small>Αν θέλετε να ενεργοποίησετε το buy now button.</small>
          </label>
          <input type="checkbox" name="product-buy-now-checkbox" id="product-buy-now-checkbox" class="product__fields--buy-now-checkbox" value="true" checked />
          <input type="hidden" name="product-buy-now-button" id="product-buy-now-button" class="product__fields--buy-now-button" value="true" data-default-value="true" />

        </div>

        <div class="product__field">
          <label for="product-disable-link-checkbox">
            Απενεργοποίηση Link
            <small>Επιλέγοντας αυτό το πεδίο το προϊόν δε θα έχει link προς το κατάστημα.</small>
          </label>
          <input type="checkbox" name="product-disable-link-checkbox" id="product-disable-link-checkbox" class="product__fields--disable-link-checkbox" value="true" />
          <input type="hidden" name="product-disable-link-button" id="product-disable-link-button" class="product__fields--disable-link-button" value="false" data-default-value="false" />

        </div>

      </div>

      <button type="submit" class="product-crawler-modal__btn button btn hide">Εισαγωγή Προιόντος</button>

    </form>
  </div>
</div>