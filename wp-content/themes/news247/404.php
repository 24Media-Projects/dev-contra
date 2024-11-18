<?php

get_header();
?>

<div class="error_page">
    <h1 class="error_title">404</h1>
    <p class="error_msg">Η σελίδα αυτή δε βρέθηκε</p>

    <img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/404.svg" alt="Η σελίδα αυτή δε βρέθηκε"
        class="error_img">
</div>

<?php
get_footer();