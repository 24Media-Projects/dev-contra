<?php
// Template Name: Protoselida
get_header('protoselida');


$page_title     = get_the_title();
$page_link      = get_the_permalink();
?>



<div class="category__wrapper newspapares__wrapper" id="pagestart">
    <div class="category__header">
        <div class="group_header ">
            <h1 class="section__title">
                <a href="<?php echo $page_link; ?>" title="Όλα τα Πρωτοσέλιδα">
                    <?php echo remove_punctuation($page_title); ?>
                </a>
            </h1>
        </div>
    </div>
</div>

<div class="container mt-3 newspapares__main_wrapper">

    <div class="row" id="main">

        <!-- new class added -->
        <div class="col newspapares__main_col" id="contentcol">


            <div class="container-fluid">
                <div class="row">
                    <div class="col col-12">


                        <!-- new class added -->
                        <div class="row calendar_buttons">


                            <!-- class changed col-5 -> col-6 -->
                            <div class="col col-xxl-8 col-12 ps-0 me-0 pe-xxl-20 ">

                                <div class="container-fluid calendarcolumn">
                                    <div class="row align-items-center">

                                        <div class="col col-auto"><button aria-label="Previous" type="button" class="simplebutton border-start border-top border-bottom border-black r40 calendararrow" data-bind="click:onPrev"><svg class="icon_left">
                                                    <use xlink:href="#newspapers_arrow"></use>
                                                </svg></button></div>
                                        <div class="col text-center border-top border-bottom border-black calendartext">
                                            <div class="row align-items-center" style="height: 38px;"><span data-bind="text:filterTitleText()"></span>
                                            </div>
                                        </div>
                                        <div class="col text-center border-top border-bottom border-black border-start calendarsmalltext">
                                            <div class="row align-items-center" style="height: 38px;"><span data-bind="text:filterTitleSmallText()"></span>
                                            </div>
                                        </div>
                                        <div class="col col-auto "><button aria-label="Next" type="button" class="simplebutton border-top border-bottom border-black r40 calendararrow" data-bind="click:onNext, disable:isToday(), css: { fadisabled: isToday()}"><svg class="icon_right">
                                                    <use xlink:href="#newspapers_arrow"></use>
                                                </svg></button></div>
                                        <div class="col col-auto">
                                            <div class="btn-group">
                                                <button aria-label="Calendar" type="button" id="calendar" data-bs-toggle="dropdown" class="simpleborderedbutton r65_40"><svg>
                                                        <use xlink:href="#icon_calendar"></use>
                                                    </svg><svg>
                                                        <use xlink:href="#icon-close"></use>
                                                    </svg></button>
                                                <div class="dropdown-menu dropdown-menu-end p-0">
                                                    <div class="container-fluid" style="height: 328px;">
                                                        <div class="row">
                                                            <div class="col col-7" style="position: relative;">
                                                                <div class="w-100 calendarddbutton" id="calendarmonth"></div>
                                                                <svg class="icon_down">
                                                                    <use xlink:href="#newspapers_arrow"></use>
                                                                </svg>
                                                                <div id="monthsmenu">
                                                                    <ul id="monthslist" class="calendarlist border-end border-black" style="z-index: 2; width: calc(100% + 1px); height: 328px">
                                                                        <li>Ιανουάριος</li>
                                                                        <li>Φεβρουάριος</li>
                                                                        <li>Μάρτιος</li>
                                                                        <li>Απρίλιος</li>
                                                                        <li>Μάιος</li>
                                                                        <li>Ιούνιος</li>
                                                                        <li>Ιούλιος</li>
                                                                        <li>Αύγουστος</li>
                                                                        <li>Σεπτέμβριος</li>
                                                                        <li>Οκτώβριος</li>
                                                                        <li>Νοέμβριος</li>
                                                                        <li>Δεκέμβριος</li>
                                                                    </ul>
                                                                    <button id="closemonthsbutton" class="btn"><svg>
                                                                            <use xlink:href="#icon-close"></use>
                                                                        </svg></button>
                                                                </div>
                                                            </div>
                                                            <div class="col col-5" style="position: relative; border-left-style: solid; border-left-width: 1px; border-left-color: black;">
                                                                <div class="w-100 calendarddbutton" id="calendaryear"></div>
                                                                <svg class="icon_down">
                                                                    <use xlink:href="#newspapers_arrow"></use>
                                                                </svg>
                                                                <div id="yearsmenu">
                                                                    <ul id="yearslist" class="calendarlist border-start border-black" style="z-index: 2; transform: translate(-1px,0); width: calc(100% + 1px); height: 328px;"></ul>
                                                                    <button id="closeyearsbutton" class="btn"><svg>
                                                                            <use xlink:href="#icon-close"></use>
                                                                        </svg></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table class="table table-borderless calendar">
                                                            <thead>
                                                                <tr>
                                                                    <th>ΔΕΥ</th>
                                                                    <th>ΤΡΙ</th>
                                                                    <th>ΤΕΤ</th>
                                                                    <th>ΠΕΜ</th>
                                                                    <th>ΠΑΡ</th>
                                                                    <th>ΣΑΒ</th>
                                                                    <th>ΚΥΡ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="calendarbody"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- NEW: class changed: col-7 -> col-6 -->
                            <div class="col col-xxl-4 col-12">



                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col col-12">
                                            <div class="btn-group categorieslistpadding" style="width: 100%;">
                                                <button class="btn border border-black customdropdownbutton dropdown-toggle h40" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bind="text:nameForSelectedCategory()">
                                                </button>
                                                <svg class="icon_down">
                                                    <use xlink:href="#newspapers_arrow"></use>
                                                </svg>
                                                <ul class="dropdown-menu  w-100" data-bind="foreach:actualCategoriesListOrdering">
                                                    <li><a class="dropdown-item" href="#" data-bind="text:$parent.nameForCategory($data), click:$parent.selectCategory"></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="container-fluid">
                            <div>
                                <!-- ko if:zoomedPaper() == null -->
                                <div class="row" data-bind="foreach:data">
                                    <div class="m-0 p-0" data-bind="visible:$parent.categoryVisible($data)">

                                        <!-- NEW: class added -->
                                        <div class="mt-40 text-center group_title" data-bind="text:title.name"></div>


                                        <hr style="margin-bottom: 0;" />
                                        <div class="container-fluid">
                                            <div class="row text-center mt-4" data-bind="visible:newspapers.length == 0">
                                                <div class="col-12">
                                                    Δε βρέθηκαν πρωτοσέλιδα
                                                </div>
                                            </div>

                                            <div class="row" data-bind="foreach:newspapers">
                                                <div class="col col-auto thumbcell ">


                                                    <!-- NEW:  class added-->
                                                    <div class="newspaper_name">
                                                        <a href="#" data-bind="text:title, click:$parents[1].onItemSelected.bind($data,$index(),$parentContext.$index())"></a>
                                                    </div>




                                                    <div style=" position: relative;">
                                                        <div style="position: relative;">
                                                            <img class="imageThumb" data-bind="attr:{src:BASE_URL+imgThumbUrl}, click:$parents[1].onItemSelected.bind($data,$index(),$parentContext.$index())" />
                                                        </div>

                                                        <div class="divider divider-right"></div>
                                                        <div class="divider divider-left">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /ko -->
                            </div>
                            <div class="row">
                                <div class="container-fluid mt-40" data-bind="if:zoomedPaper() != null">
                                    <div class="row topzoomdate">

                                        <!-- NEW: class added -->
                                        <div class="col-12 text-center group_title date" data-bind="text:SelectedPaperDate()"></div>

                                    </div>
                                    <div class="row border-bottom border-black">
                                        <div class="col col-2">

                                            <!-- NEW: class added -->
                                            <span class="leftzoomdate group_title" data-bind="text:SelectedPaperDate(), click:zoomOut"></span>
                                        </div>
                                        <div class="col-8 text-center">

                                            <!-- NEW: class added -->
                                            <span class="group_title" data-bind="text:selectedPaperTitle()"></span>
                                        </div>
                                        <div class="col col-2 text-end">
                                            <div class="zoomnavright">
                                                <button type="button" class="simplebutton border-end  border-black r40 text-danger" data-bind="click:zoomPrev, css: {zoomarrowdisabled:zoomPrevDisabled()}"><svg class="icon_left">
                                                        <use xlink:href="#newspapers_arrow"></use>
                                                    </svg></button>
                                                <button type="button" class="simplebutton text-danger  r40" data-bind="click:zoomNext, css:{zoomarrowdisabled:zoomNextDisabled()}"><svg class="icon_right">
                                                        <use xlink:href="#newspapers_arrow"></use>
                                                    </svg></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-20">
                                        <div class="col col-12" style="position: relative;">
                                            <img style="width:100%" data-bind="attr:{src:SelectedPaperImage()}" />
                                            <button aria-label="Previous" type="button" style="left:-20px" class="simplebutton centeredsemitransparentbutton r40 text-danger" data-bind="click:zoomPrev, css: {zoomarrowdisabled:zoomPrevDisabled()}"><svg class="icon_left">
                                                    <use xlink:href="#newspapers_arrow"></use>
                                                </svg></button>
                                            <button aria-label="Next" type="button" style="right:-20px" class="simplebutton centeredsemitransparentbutton text-danger  r40" data-bind="click:zoomNext, css:{zoomarrowdisabled:zoomNextDisabled()}"><svg class="icon_right">
                                                    <use xlink:href="#newspapers_arrow"></use>
                                                </svg></button>
                                        </div>
                                    </div>

                                    <div class="row border-bottom border-black mt-4">
                                        <div class="col col-10"></div>
                                        <div class="col col-2 text-end">
                                            <div class="zoomnavright">
                                                <button type="button" class="simplebutton border-end  border-black r40 text-danger" data-bind="click:zoomPrevB, css: {zoomarrowdisabled:zoomPrevDisabled()}"><svg class="icon_left">
                                                        <use xlink:href="#newspapers_arrow"></use>
                                                    </svg></button>
                                                <button type="button" class="simplebutton text-danger  r40" data-bind="click:zoomNextB, css:{zoomarrowdisabled:zoomNextDisabled()}"><svg class="icon_right">
                                                        <use xlink:href="#newspapers_arrow"></use>
                                                    </svg></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-auto rightadvcolumnwithpadding newspapares__sidebar_col" id="advcol">
            <aside class="main_sidebar category__aside sidebar_column">
                <?php
                btw_get_template_part('template-parts/ads/dfp', [
                    'slot_id' => 'sidebar_a',
                ]);
                ?>
            </aside>
        </div>
    </div>

    <section class="taboola_posts_container">
        <div class="taboola_feed"></div>
    </section>  
  
</div>

<?php get_footer(); ?>