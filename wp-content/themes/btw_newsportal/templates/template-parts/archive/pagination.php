<nav class="navigation pagination" role="navigation" aria-label="<?php echo $pagination_args['aria_label'];?>">
    <h2 class="screen-reader-text"><?php echo $pagination_args['screen_reader_text'];?></h2>
    <ul class="nav-links">
        <li><?php echo implode( "</li><li>", $links );?></li>
  	</ul>
</nav>