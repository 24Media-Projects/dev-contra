<?php
extract(btw_get_hp_group_fields());

global $hp_groups_slots;

$slot = array_shift($hp_groups_slots);

?>

<div class="home_wrapper zodiac_signs__wrapper">
    <section class="zodiac_signs" id="<?php echo $section_id; ?>">

		<?php echo btw_get_impression_url($impression_url); ?>

        <div class="section_container">

            <?php
            btw_get_template_part('template-parts/group_header', [
                'section_title' => $section_title,
                'section_title_url' => $section_title_url,
                'related_links' => [['link_text' => 'Από την Άση Μπήλιου www.asibiliou.gr', 'link_url' => 'https://www.asibiliou.gr']],
                'heading' => 'h2',
            ]);
            ?>

            <div class="article_container">
                <?php

                foreach (get_field('btw__group_fields__hp__template__zodiac_signs__first_post_selection', $group_id) ?: [] as $row) :

                    $atf_post = new BTW_Atf_Post([
                        'item' => $row,
                        'primary_term' => $primary_term,
                        'image_srcsets' => array(
                            array(
                                'image_size'   => 'medium_horizontal',
                                'media_query'  => '(max-width: 1023px )',
                            ),
                            array(
                                'image_size'   => 'large_square',
                                'media_query'  => '(max-width: 767px )',
                                'mobile'       => true,
                            ),
                            array(
                                'image_size'  => 'small_horizontal',
                                'default'     => true,
                            ),
                        ),
                        'render_attrs' => [
                            'extra_class' => 'overlay_mobile',
                        ],
                    ]);

                    $atf_post->render();

                endforeach;

                foreach (get_field('btw__group_fields__hp__template__zodiac_signs__second_post_selection', $group_id) ?: [] as $row) :

                    $atf_post = new BTW_Atf_Post([
                        'item' => $row,
                        'primary_term' => $primary_term,
                        'image_srcsets' => array(
                            array(
                                'image_size'   => 'medium_horizontal',
                                'media_query'  => '(max-width: 767px )',
                                'mobile'       => true,
                            ),
                            array(
                                'image_size'  => 'medium_horizontal',
                                'default'     => true,
                            ),
                        ),
                        'render_attrs' => [],
                    ]);

                    $atf_post->render();

                endforeach;
                ?>

                <div class="horoscope_container">
                    <div class="post__content">
                        <h3 class="post__title">
                            Τα ζώδια σήμερα
                        </h3>
                        <div class="post__category">
                            <h4 class="caption date">
                                <?php echo $today = date("d.m.Y"); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="signs_container">
                        <div class="horoscope_sign">
                            <a title="ΚΡΙΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%ce%ba%cf%81%ce%b9%cf%8c%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-aries" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΚΡΙΟΣ</h4>
                            </a>
                        </div>

                        <div class="horoscope_sign">
                            <a title="ΤΑΥΡΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%cf%84%ce%b1%cf%8d%cf%81%ce%bf%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-taurus" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΤΑΥΡΟΣ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΔΙΔΥΜΟΙ" target="_blank" href="https://asibiliou.gr/prediction/%ce%b4%ce%af%ce%b4%cf%85%ce%bc%ce%bf%ce%b9-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-gemini" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΔΙΔΥΜΟΙ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΚΑΡΚΙΝΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%ce%ba%ce%b1%cf%81%ce%ba%ce%af%ce%bd%ce%bf%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-cancer" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΚΑΡΚΙΝΟΣ</h4>
                            </a>
                        </div>

                        <div class="horoscope_sign">
                            <a title="ΛΕΩΝ" target="_blank" href="https://asibiliou.gr/prediction/%ce%bb%ce%ad%cf%89%ce%bd-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-leo" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΛΕΩΝ</h4>
                            </a>
                        </div>

                        <div class="horoscope_sign">
                            <a title="ΠΑΡΘΕΝΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%cf%80%ce%b1%cf%81%ce%b8%ce%ad%ce%bd%ce%bf%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-virgo" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΠΑΡΘΕΝΟΣ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΖΥΓΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%ce%b6%cf%85%ce%b3%cf%8c%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-libra" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΖΥΓΟΣ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΣΚΟΡΠΙΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%cf%83%ce%ba%ce%bf%cf%81%cf%80%ce%b9%cf%8c%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-scorpio" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΣΚΟΡΠΙΟΣ</h4>
                            </a>
                        </div>

                        <div class="horoscope_sign">
                            <a title="ΤΟΞΟΤΗΣ" target="_blank" href="https://asibiliou.gr/prediction/%cf%84%ce%bf%ce%be%cf%8c%cf%84%ce%b7%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-sagittarius" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΤΟΞΟΤΗΣ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΤΟΞΟΤΗΣ" target="_blank" href="https://asibiliou.gr/prediction/%ce%b1%ce%b9%ce%b3%cf%8c%ce%ba%ce%b5%cf%81%cf%89%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-capricorn" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΑΙΓΟΚΕΡΩΣ</h4>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΥΔΡΟΧΟΟΣ" target="_blank" href="https://asibiliou.gr/prediction/%cf%85%ce%b4%cf%81%ce%bf%cf%87%cf%8c%ce%bf%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-aquarius" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <div class="horoscope_title">ΥΔΡΟΧΟΟΣ</div>
                            </a>
                        </div>
                        <div class="horoscope_sign">
                            <a title="ΙΧΘΥΕΣ" target="_blank" href="https://asibiliou.gr/prediction/%ce%b9%cf%87%ce%b8%cf%8d%ce%b5%cf%82-%ce%b7%ce%bc%ce%b5%cf%81%ce%b7%cf%83%ce%b9%ce%b1/">
                                <figure class="horoscope_icon">
                                    <svg width="60" height="60">
                                        <use xlink:href="#horoscope-pisces" class="horoscope_sign_alias"></use>
                                    </svg>
                                </figure>
                                <h4 class="horoscope_title">ΙΧΘΥΕΣ</h4>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <aside class="section_sidebar">
        <?php
            btw_get_template_part('template-parts/ads/dfp', [
                'slot_id' => $slot['1'],
            ]);
        ?>
        </aside>
    </section>

</div>