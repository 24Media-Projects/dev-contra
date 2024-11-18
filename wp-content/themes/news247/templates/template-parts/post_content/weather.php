<?php


$accuweather_url = get_field('btw__group_fields__hp__template__latest_stories__weather__accuweather_url');

$accuweather_forcast_posts = get_option('accuweather_forcast_posts');

if (!$accuweather_forcast_posts) {
    return;
}

$current_conditions = array_shift($accuweather_forcast_posts);

?>

<article class="article weather_widget square">

    <div class="weather_location">
        <span class="caption s-font-bold">Καιρός</span>
        <h4 class="caption s-font">Αθήνα</h4>
    </div>

    <div class="weather_container">

        <div class="weather__current_conditions">
            <div class="weather__temperature">
                <p><?php echo $current_conditions['temperature']; ?>&#8451;</p>
                <figure class="weather__icon">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/weather-icons/<?php echo $current_conditions['icon_id']; ?>.svg" alt="weather icon" width="100" height="100" />
                </figure>
            </div>

        </div>

        <div class="weather__forcast">

            <?php foreach ($accuweather_forcast_posts as $accuweather_forcast_post) :
                $foracst_time = new DateTime($accuweather_forcast_post['datetime']);
            ?>

                <div class="weather__forcast_item">
                    <p class="weather__date_time caption s-font"><?php echo $foracst_time->format('H:i'); ?></p>
                    <div class="weather__temperature">
                        <p><?php echo $accuweather_forcast_post['temperature']; ?>&#8451;</p>
                        <figure class="weather__icon">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/weather-icons/<?php echo $accuweather_forcast_post['icon_id']; ?>.svg" alt="weather icon" width="30" height="30" />
                        </figure>
                    </div>
                    <p class="weather__real_feel_temeprature">RealFeel&copy; <?php echo $accuweather_forcast_post['real_feel_temperature']; ?>&#176;</p>
                </div>

            <?php endforeach; ?>

        </div>

        <div class="weather__view_more">
            <figure class="weather__view_more--accuweather_logo">
                <a target="_blank" title="Περισσότερα" href="<?php echo $accuweather_url; ?>">
                    <svg>
                        <use xlink:href="#accuweather-logo"></use>
                    </svg>
                </a>
            </figure>
            <a class="caption s-font" target="_blank" title="Περισσότερα" href="<?php echo $accuweather_url; ?>">
                <span>Περισσότερα</span>
                <svg>
                    <use xlink:href="#icon-arrow-dropdown-menu"></use>
                </svg>
            </a>
        </div>

    </div>

</article>