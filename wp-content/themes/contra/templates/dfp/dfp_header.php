<?php

if ( btw_hide_ads() ) { ?>
    <script>
        window.googletag = window.googletag || {};

        window.googletag.cmd = window.googletag.cmd || [];
    </script>
    <?php
    return;
}



global $hp_groups_slots;


$set_dfp_targeting = new BTW_DFP_TARGETING();
$set_dfp_targeting = $set_dfp_targeting->init();

?>


<script async="async" src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>


<script>
    var pageType = '<?php echo btw_get_dfp_page_template();?>',
        gptAdSlots = [],
        mappingSizes = [];

    var getSlots = function(mappingSizes) {

        var gptAdSlots = [];
        
       if( pageType != 'home' ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/ros_prestitial', [1, 1], 'ros_prestitial')
                    .defineSizeMapping( mappingSizes['mapping1'] )
                    .addService(googletag.pubads()));

       }

        if( [ 'home', 'single_magazine', 'magazine_category', 'magazine_subcategory' ].indexOf( pageType ) === -1 ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/ros_970x250a', [[970, 250], [1, 1]], 'under_menu')
                        .defineSizeMapping( mappingSizes['mapping2'] )
                        .addService(googletag.pubads()));

            gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/ROS_300x250a_Sidebar', [[300, 600], [300, 250]], 'sidebar_a')
                        .defineSizeMapping( pageType == 'live_blog' ? mappingSizes['mapping3'] : mappingSizes['mapping15'] )
                        .addService(googletag.pubads()));
       }


       if( [ 'archive', 'search', 'podcast_subcategory' ].indexOf( pageType ) !== -1 ){
            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/section_inline', [[300, 250], [728, 90], [336, 280], [660, 100], [468, 60]], 'term_inline')
                        .defineSizeMapping( mappingSizes['mapping4'] )
                        .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/section_inline', [[300, 250], [728, 90], [336, 280], [660, 100], [468, 60]], 'term_inline_a')
                        .defineSizeMapping( mappingSizes['mapping4'] )
                        .addService(googletag.pubads()));
       }


       if( pageType == 'single_post' ){

            gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/ros_inline_a', [[1, 1], [300, 250], [336, 280], [300, 600]], 'article_inline')
                        .defineSizeMapping( mappingSizes['mapping5'] )
                        .addService(googletag.pubads()));

            gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/ros_inline_b', [[300, 250], [336, 280], [300, 600]], 'article_inline_b')
                        .defineSizeMapping( mappingSizes['mapping18'] )
                        .addService(googletag.pubads()) );


            // gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/ros_article_end', [[300, 250], [336, 280], [728, 90], [300, 600]], 'article_end')
            //             .defineSizeMapping( mappingSizes['mapping6'] )
            //             .addService(googletag.pubads()));
            
       }

        if( pageType == 'home' ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/hp_prestitial', [1, 1], 'hp_prestitial')
                        .defineSizeMapping( mappingSizes['mapping1'] )
                        .addService(googletag.pubads()));


            gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/hp_300x250a', [[300, 250], [336, 280], [300, 600]], 'latest_stories')
                        .defineSizeMapping( mappingSizes['mapping8'] )
                        .addService(googletag.pubads()));

             gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/hp_300x250a', [[300, 250], [336, 280], [300, 600]], 'popular_articles')
                        .defineSizeMapping( mappingSizes['mapping9'] )
                        .addService(googletag.pubads()));


             gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/hp_970x250a', [[1, 1], [300, 250], [300, 600], [970, 250], [336, 280], [728, 90]], 'hp_billboard_a')
                        .defineSizeMapping( mappingSizes['mapping10'] )
                        .addService(googletag.pubads()));

             gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/hp_970x250b', [[728, 90], [970, 250]], 'hp_billboard_b')
                        .defineSizeMapping( mappingSizes['mapping12'] )
                        .addService(googletag.pubads()));


            <?php
            /**
             * $hp_groups_slots is defined on homepage template
             * each hp_groups_slot contains an array with 2 elements
             * the first is the slot name and the second  is the slot id
             * 
             * @see homepage.php
             * @see template-functions.php, btw_get_hp_group_slots function
             */
            foreach( $hp_groups_slots ?? [] as $hp_group_slot ):?>

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/<?php echo $hp_group_slot['0'];?>', [[300, 250], [336, 280], [728, 90], [300, 600]], '<?php echo $hp_group_slot['1'];?>')
                        .defineSizeMapping( mappingSizes['mapping11'] )
                        .addService(googletag.pubads()));

            <?php endforeach; ?>


        }

        if( pageType == 'single_magazine' ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_article_billboard', [[300,250], [336,280], [728,90],  [970, 250]], 'magazine_article_above_taboola')
                .defineSizeMapping( mappingSizes['mapping14'] )
                .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_sidebar_300x250', [[300, 250], [300, 600]], 'magazine_article_sidebar')
                .defineSizeMapping( mappingSizes['mapping3'] )
                .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_article_inlineA', [[1, 1], [300, 250], [300, 600], [336, 280]], 'article_inline')
                .defineSizeMapping( mappingSizes['mapping5'] )
                .addService(googletag.pubads()));

            gptAdSlots.push( googletag.defineSlot('/4834629/contra.gr/SundayEdition_article_inlineB', [[300, 250], [336, 280], [300, 600]], 'article_inline_b')
                        .defineSizeMapping( mappingSizes['mapping17'] )
                        .addService(googletag.pubads()) );

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_articleEnd', [[336, 280], [728, 90], [300, 600], [300, 250]], 'magazine_article_end')
                    .defineSizeMapping( mappingSizes['mapping6'] )
                    .addService(googletag.pubads()));

        }

        if( pageType == 'magazine_subcategory' ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_970x250a', [970, 250], 'magazine_category_billboard')
                    .defineSizeMapping( mappingSizes['mapping13'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_970x250b', [[970, 250], [728, 90]], 'magazine_category_billboard_b')
                    .defineSizeMapping( mappingSizes['mapping12'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_inline', [[336, 280], [728, 90], [300, 250], [660, 100], [468, 60]], 'magazine_category_inline')
                    .defineSizeMapping( mappingSizes['mapping4'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_inline', [[336, 280], [728, 90], [300, 250], [660, 100], [468, 60]], 'magazine_category_inline_a')
                    .defineSizeMapping( mappingSizes['mapping4'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_inline', [[336, 280], [728, 90], [300, 250], [660, 100], [468, 60]], 'magazine_category_inline_b')
                    .defineSizeMapping( mappingSizes['mapping4'] )
                    .addService(googletag.pubads()));

        }

        if( pageType == 'magazine_category' ){

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_hp_Billboard', [[970, 250],[300,250],[336,280],[660,100],[728,90],[468,60],[300,250],[336,280],[300,600]], 'magazine_home_billboard')
                    .defineSizeMapping( mappingSizes['mapping16'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_inline', [[336, 280], [728, 90], [300, 250], [660, 100], [468, 60]], 'magazine_category_inline_a')
                    .defineSizeMapping( mappingSizes['mapping4'] )
                    .addService(googletag.pubads()));

            gptAdSlots.push(googletag.defineSlot('/4834629/contra.gr/SundayEdition_Category_inline', [[336, 280], [728, 90], [300, 250], [660, 100], [468, 60]], 'magazine_category_inline_b')
                    .defineSizeMapping( mappingSizes['mapping4'] )
                    .addService(googletag.pubads()));

        }





        return gptAdSlots;

    }


    window.googletag = window.googletag || {};

    window.googletag.cmd = window.googletag.cmd || [];

    googletag.cmd.push(function() {
        var mappingSizes = [];

     // prestitial
      var mapping1 = googletag.sizeMapping()
                              .addSize( [0, 0], [1,1] )
                              .build();
      // ros_970x250a
      var mapping2 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [1024, 0], [ [970,250] ] )
                              .addSize( [1350, 0], [ [970,250],[1, 1] ] )
                              .build();

      // ROS_300x250a_Sidebar
      // SundayEdition_sidebar_300x250
      var mapping3 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [1024, 0], [ [300, 250], [300,600] ] )
                              .build();

      // section_inline
      // SundayEdition_Category_inline
      var mapping4 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [336,280]] )
                              .addSize( [768, 0], [ [300, 250], [336,280],[660,110],[728,90],[468,60] ] )
                              .addSize( [1024, 0], [] )
                              .build();


      // ros_inline_a
      // SundayEdition_article_inlineA
      var mapping5 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [1,1], [300,600], [336,280]] )
                              .addSize( [768, 0], [ [300, 250], [1,1], [336,280] ] )
                              .build();

      // ros_article_end
      // SundayEdition_articleEnd
      var mapping6 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250],[300,600],[336,280]] )
                              .addSize( [768, 0], [ [300, 250], [336,280],[728,90] ] )
                              .addSize( [1024, 0], [ [300, 250], [336,280] ] )
                              .build();

      // ros_970x250b
      var mapping7 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [336,280],[300,600]] )
                              .addSize( [768, 0], [ [300, 250], [336,280],[728,90],[468,60] ] )
                              .addSize( [1024, 0], [ [970, 250],[300, 250], [336,280], [728,90] ] )
                              .build();


      // HP_300x250a - - desktop + mobile
      var mapping8 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [300,600] ] )
                              .addSize( [768, 0], [] )
                              .addSize( [1024, 0], [ [300, 250], [336,280] ] )
                              .addSize( [1350, 0], [ [300, 250], [300,600] ] )
                              .build();

      // HP_300x250a - tablet
      var mapping9 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [768, 0], [ [300, 250], [300,600] ] )
                              .addSize( [1024, 0], [] )
                              .build();

      // HP_970x250a 
      var mapping10 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [300,600]] )
                              .addSize( [768, 0], [ [728,90], [300,250], [336,280] ] )
                              .addSize( [1024, 0], [ [970, 250], [728,90] ] )
                              .addSize( [1350, 0], [ [970, 250], [1,1] ] )
                              .build();

      // HP_300x250b
      // HP_300x250c
      // HP_300x250d
      // HP_300x250e
      // hp_300x250f
      var mapping11 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [300,600], [336,280]] )
                              .addSize( [768, 0], [ [300,250], [300,600], [336,280], [728,90] ] )
                              .addSize( [1024, 0], [ [300, 250], [336,280] ] )
                              .addSize( [1350, 0], [ [300, 250], [300,600] ] )
                              .build();

      // HP_970x250b
      // SundayEdition_Category_970x250b
      var mapping12 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [1024, 0], [ [970, 250], [728,90] ] )
                              .addSize( [1350, 0], [ [970, 250] ] )
                              .build();


        // SundayEdition_Category_970x250a
      var mapping13 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [1024, 0], [ [970, 250] ] )
                              .build();

      // SundayEdition_article_billboard
      var mapping14 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [[300,250], [336,280]] )
                              .addSize( [768, 0], [[300,250], [336,280], [728,90]] )
                              .addSize( [1024, 0], [ [970, 250] ] )
                              .build();


      // SundayEdition_article_billboard
      var mapping15 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [1350, 0], [ [300,250],[300,600] ] )
                              .build();

      // SundayEdition_hp_Billboard
      var mapping16 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [336,280]] )
                              .addSize( [768, 0], [ [300, 250], [336,280],[660,110],[728,90],[468,60] ] )
                              .addSize( [1024, 0], [[970,250]] )
                              .build();

       // SundayEdition_article_inlineB
      var mapping17 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [300,600], [336,280]] )
                              .build();

                              
        // ros_inline_b
      var mapping18 = googletag.sizeMapping()
                              .addSize( [0, 0], [] )
                              .addSize( [320, 0], [ [300, 250], [300,600], [336,280], [1,1] ] )
                              .build();


                  

        mappingSizes['mapping1'] = mapping1;
        mappingSizes['mapping2'] = mapping2;
        mappingSizes['mapping3'] = mapping3;
        mappingSizes['mapping4'] = mapping4;
        mappingSizes['mapping5'] = mapping5;
        mappingSizes['mapping6'] = mapping6;
        mappingSizes['mapping7'] = mapping7;
        mappingSizes['mapping8'] = mapping8;
        mappingSizes['mapping9'] = mapping9;
        mappingSizes['mapping10'] = mapping10;
        mappingSizes['mapping11'] = mapping11;
        mappingSizes['mapping12'] = mapping12;
        mappingSizes['mapping13'] = mapping13;
        mappingSizes['mapping14'] = mapping14;
        mappingSizes['mapping15'] = mapping15;
        mappingSizes['mapping16'] = mapping16;
        mappingSizes['mapping17'] = mapping17;
        mappingSizes['mapping18'] = mapping18;

        gptAdSlots = getSlots(mappingSizes);

        // PPID init
        var ppid = localStorage.__PPID;

        googletag.pubads().disableInitialLoad();
        googletag.pubads().enableSingleRequest();

        <?php echo $set_dfp_targeting; ?>

        if (ppid) {
          googletag.pubads().setPublisherProvidedId(ppid);
        }

        googletag.enableServices();

    });

    
</script>