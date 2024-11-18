<?php

$current_year = date('Y');

?>

<div tabindex="1" id="read-also-modal" class="read-also-modal btw-editor-modal">
    <div class="btw-editor-modal__inner">
        <div class="btw-editor-modal__close">
            X
        </div>
        <h5 class="btw-editor-modal__title">

        </h5>
        <form class="read-also-modal__form">

            <div class="read-also-modal__form--header">
                <p class="label">ΕΠΙΛΕΞΤΕ ΑΡΘΡΟ</p>
                <select name="read-also-posts__date" class="read-also-posts__date" data-default-value="<?php echo $current_year; ?>">
                    <option value="">Όλα τα άρθρα</option>

                    <?php for ($i = $current_year; $i >= $current_year - 5; $i--) : ?>
                        <option value="<?php echo $i; ?>" <?php selected($i, $current_year); ?>>Άρθρα από το <?php echo $i; ?> και μετά</option>
                    <?php endfor; ?>

                </select>
            </div>

            <div class="read-also-items-container clear">

                <div class="filters">
                    <input type="text" class="read-also-posts__search" />
                    <select name="read-also-posts__tags" class="read-also-posts__tags"></select>

                </div>

                <div class="read-also__choices">

                    <ul></ul>

                </div>

                <div class="read-also__values">

                    <ul class="ui-sortable"></ul>

                </div>
            </div>

            <button class="read-also-modal__btn button btn">ΕΙΣΑΓΩΓΗ ΑΡΘΡΟΥ</button>

        </form>
    </div>
</div>