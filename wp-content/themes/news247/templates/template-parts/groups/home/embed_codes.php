<?php

// we want this only in hp group
if( isset($group_id) ){
	extract(btw_get_hp_group_fields());
}

$embed_codes = get_field('btw__group_fields__hp__template__embed_codes__embed_codes');

if (!$embed_codes) return;

?>



<!-- Remove the following styles after elections finish  -->
<style>
    @media screen and (min-width:768px) {
        .embed_codes_section .container iframe {
            height: 760px;
        }
    }

    @media screen and (min-width:1024px) {
        .embed_codes_section .container iframe {
            height: 1250px;
        }
    }
</style>

<div class="home_wrapper embed_codes__wrapper">
    <section id="<?php echo $section_id; ?>" class="embed_codes_section">

        <?php ob_start(); ?>
        <div class="dropdown_container">
            <button class="selected-option_container" onclick="window.toggleOptions()">
                <span class="selected-option caption s-font"><?php echo $embed_codes[0]['label']; ?></span>
                <svg>
                    <use xlink:href="#icon-arrow-dropdown-menu"></use>
                </svg>
            </button>
            <ul class="dropdown-options">
                <?php foreach ($embed_codes as $key => $embed_code) : ?>
                    <li class="caption s-font" onclick="window.selectOption(this, <?php echo $key; ?>)"><?php echo $embed_code['label']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        $after_related_terms = ob_get_clean();

        btw_get_template_part('template-parts/group_header', [
            'section_title' => $section_title,
            'section_title_url' => $section_title_url ?? '',
            'heading' => $heading ?? 'h2',
            'after_related_terms' => $after_related_terms,
        ]);
        ?>


        <div class="container">
            <?php foreach ($embed_codes as $key => $embed_code) : ?>
                <div data-index="<?php echo $key; ?>" class="<?php if ($key == 0) echo 'active'; ?>">
                    <?php echo $embed_code['embed_code']; ?>
                </div>
            <?php endforeach; ?>
        </div>


    </section>
</div>