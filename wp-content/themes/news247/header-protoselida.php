<!DOCTYPE html>
<!--[if IE 8]>
    <html class="ie ie8 lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@200;700;800&display=swap" rel="stylesheet">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <!--[if lt IE 9]>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.js"></script>
        <![endif]-->

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-KMFBL5VB');
    </script>
    <!-- End Google Tag Manager -->

    <?php get_template_part('templates/dfp/dfp_header'); ?>

    <script>
        <?php
        global $wp_query;

        $newspaper_category = $wp_query->query_vars['newspaper_category'] ?? 'null';
        $newspaper_date = !empty($wp_query->query_vars['newspaper_date'])
            ? DateTimeImmutable::createFromFormat('dmY', $wp_query->query_vars['newspaper_date'])->format('Ymd')
            : date('Ymd');
        $newspaper_paper = !empty($wp_query->query_vars['newspaper_paper'])
            ? str_replace('_', '-', $wp_query->query_vars['newspaper_paper'])
            : 'null';

        ?>


        var newspapersSettings = {
            category: <?php echo $newspaper_category == 'null' ? 'null' : "'{$newspaper_category}'"; ?>,
            date: '<?php echo $newspaper_date; ?>',
            paper: <?php echo $newspaper_paper == 'null' ? 'null' : "'{$newspaper_paper}'"; ?>,
        }
    </script>

    <?php wp_head(); ?>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!--<script src="https://kit.fontawesome.com/1d33ad81fa.js" crossorigin="anonymous"></script>-->
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.9/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/isoWeek.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/el.js"></script>


    <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/protoselida/custom.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script type='text/javascript' src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/front-end/protoselida/common.js?v=<?php echo time(); ?>"></script>

    <!-- NEWS247 STYLES - DO NOT REMOVE -->
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/protoselida/style-newspapers.css?v=<?php echo time(); ?>" rel="stylesheet">

    <script type="text/javascript">
        (function($) {

            var calendar;

            $(document).ready(function() {

                addEventListener("popstate", (event) => {
                    if (event.state == null)
                        newspapersSettings = {
                            category: null,
                            date: null,
                            paper: null
                        }
                    else
                        newspapersSettings = event.state;
                    initPage();

                });

                const calendarDD = document.getElementById('calendar');
                calendarDD.addEventListener('show.bs.dropdown', event => {
                    calendar.hideInnerElements();
                })

                dayjs.locale('el');
                dayjs.extend(window.dayjs_plugin_isoWeek);
                calendar = new MyCalendar();

                initPage();
                ko.applyBindings(m, document.getElementById("main"));

            })

            function initPage() {
                m.zoomedPaper(null);
                var datejs = (newspapersSettings.date == null) ? dayjs() : dayjs(newspapersSettings.date, "YYYYMMDD");
                calendar.init(datejs);
                m.date(datejs);
            }

            var MyCalendar = function() {
                var self = this;
                this.displayMonth = null;
                this.displayYear = null;
                this.currentDate = null;
                this.startyear = 2020;

                this.init = function(initialDate) {

                    $('#monthsmenu').hide();
                    $('#yearsmenu').hide();
                    $('#calendarmonth').on("click", function(event) {
                        event.stopPropagation();
                        $('#monthsmenu').show();
                    });
                    $("#closemonthsbutton").on("click", function(event) {
                        event.stopPropagation();
                        $('#monthsmenu').hide();
                    });
                    $('#monthslist li ').on('click', function(event) {
                        event.stopPropagation();
                        self.setMonth($(this).index());
                        $('#monthsmenu').hide();
                    });

                    var lastyear = dayjs().year();
                    for (var i = self.startyear; i <= lastyear; i++) {
                        $("#yearslist").append('<li>' + i + '</li>');
                    }
                    $('#calendaryear').on("click", function(event) {
                        event.stopPropagation();
                        $('#yearsmenu').show();
                    });
                    $("#closeyearsbutton").on("click", function(event) {
                        event.stopPropagation();
                        $('#yearsmenu').hide();
                    });
                    $('#yearslist li ').on('click', function(event) {
                        event.stopPropagation();
                        self.setYear($(this).index() + self.startyear);
                        $('#yearsmenu').hide();
                    });

                    self.prepareCurrentDate(initialDate);
                }

                this.hideInnerElements = function() {
                    $('#monthsmenu').hide();
                    $('#yearsmenu').hide();
                }

                this.setYear = function(y) {
                    self.displayYear = y;
                    self.UpdateGrid();
                }

                this.setMonth = function(m) {
                    self.displayMonth = m;
                    self.UpdateGrid();
                }

                this.prepareCurrentDate = function(d) {
                    self.currentDate = d;
                    self.displayMonth = d.month();
                    self.displayYear = d.year();
                    self.UpdateGrid();
                }

                this.UpdateGrid = function() {
                    var griddate = dayjs(new Date(self.displayYear, self.displayMonth, 1));

                    $('#calendarmonth').text(griddate.format("MMMM"));
                    $('#calendaryear').text(griddate.format("YYYY"));

                    $("#calendarbody").empty();
                    var element = document.getElementById("calendarbody");
                    element.innerHTML = "";

                    $('#calendarbody').append("<tr></tr>")
                    var row = $('#calendarbody tr:last')
                    for (var i = 0; i < griddate.isoWeekday() - 1; i++) {
                        row.append("<td></td>");
                    }

                    var today = dayjs();
                    while (griddate.month() == self.displayMonth) {
                        if (griddate.isoWeekday() == 1 && griddate.date() != 1) {
                            $('#calendarbody').append("<tr></tr>")
                            row = $('#calendarbody tr:last')
                        }
                        var cell = $("<div>" + griddate.date() + "</div>");
                        var tcell = $("<td></td>");
                        tcell.append(cell);
                        row.append(tcell);
                        if (griddate.isAfter(today, 'day'))
                            tcell.addClass("calendardisabled");
                        else {
                            tcell.addClass("calendaractive");
                            if (griddate.isSame(m.date(), 'day'))
                                cell.addClass("calendarselected");
                            if (griddate.isSame(today, 'day'))
                                cell.addClass("calendartoday");
                            cell.on("click", function(event) {
                                m.changeDate(dayjs(new Date(self.displayYear, self.displayMonth, Number($(this).text()))));
                                //m.Unzoom();
                            });
                        }
                        griddate = griddate.add(1, 'day');
                    }
                }
            }


            var MainModel = function(dat) {
                var self = this;
                this.date = ko.observable();
                this.category = ko.observable(0)
                this.zoomedPaper = ko.observable(null);
                this.zoomedPaperId = ko.observable(null);
                this.data = ko.observableArray();
                this.categoriesListOrdering = [0, 4, 5, 6, 8, 9, 11, 10, 12, 3];
                this.selectedIndexPath = ko.observable(null);
                this.actualCategoriesListOrdering = ko.observableArray();

                this.initCategory = function() {
                    if (newspapersSettings.category != null) {
                        var ct = self.data().find(c => categoriesDict[c.id].slug == newspapersSettings.category);
                        self.category(ct.id);
                    } else
                        self.category(0);
                }

                this.addZoomHistory = function(ip) {
                    if (ip != null) {
                        var state = {
                            category: categoriesDict[self.data()[ip.group].id].slug,
                            date: self.date().format("YYYYMMDD"),
                            paper: self.data()[ip.group].newspapers[ip.paper].slug.replaceAll("_", "-")
                        }
                        history.pushState(state, "", "/protoselida/" + state.category + "/" + state.paper + "/date/" + self.date().format("DDMMYYYY"));
                    }
                }

                this.addUnzoomedHistory = function() {
                    if (self.category() == 0) {
                        var state = {
                            category: null,
                            date: self.date().format("YYYYMMDD"),
                            paper: null
                        }
                        history.pushState(state, "", "/protoselida/date/" + self.date().format("DDMMYYYY"));
                    } else {
                        var state = {
                            category: categoriesDict[self.category()].slug,
                            date: self.date().format("YYYYMMDD"),
                            paper: null
                        }
                        history.pushState(state, "", "/protoselida/" + state.category + "/date/" + self.date().format("DDMMYYYY"));
                    }
                }



                this.Unzoom = function() {
                    self.zoomedPaper(null);
                    self.selectedIndexPath(null);
                    self.addUnzoomedHistory();

                }

                this.changeDate = function(d) {
                    //self.category(0);
                    //newspapersSettings.category = null

                    self.date(d);
                    if (self.zoomedPaper() == null) {
                        self.addUnzoomedHistory();
                    } else {
                        var ip = self.selectedIndexPath();
                        self.addZoomHistory(ip)
                    }
                }



                this.date.subscribe(function(v) {
                    self.LoadGeneralData();
                    calendar.prepareCurrentDate(v)

                })


                this.LoadGeneralData = function() {
                    $.getJSON("https://protoselida.24media.gr/public/json/widget?widgetId=3&date=" + self.date().format("YYYYMMDD"), function(d) {
                        var processeddata = [];
                        d.widgetGroups.forEach(wg => {
                            var group = {
                                type: "GROUP",
                                id: wg.group.id,
                                title: categoriesDict[wg.group.id],
                                slug: wg.group.name,
                                newspapers: []
                            }
                            wg.group.newspapers.forEach(p => {
                                var pap = {
                                    id: p.id,
                                    title: p.title,
                                    imgThumbUrl: p.imgThumbUrl,
                                    imgUrl: p.imgUrl,
                                    slug: p.name,
                                    date: dayjs(p.lastUpdated, "YYYY-MM-DD"),
                                    next: dayjs(p.nextPubDate),
                                    prev: dayjs(p.previousPubDate),
                                    name: p.name
                                };
                                group.newspapers.push(pap);
                            })
                            processeddata.push(group);
                        });
                        if (self.date().day() != 0) {
                            var kyriakatikes = processeddata.findIndex((e) => e.id == 3);
                            if (kyriakatikes != -1) {
                                k = processeddata.splice(kyriakatikes, 1);
                                processeddata.push(k[0]);
                            }
                        }
                        self.originaldata = processeddata;
                        self.data(self.originaldata);

                        var categoriesexistance = {};
                        processeddata.forEach(function(d) {
                            categoriesexistance[d.id] = d.newspapers.length > 0;
                        })
                        var tempArray = [0];
                        self.categoriesListOrdering.forEach(function(cId) {
                            if (categoriesexistance[cId]) tempArray.push(cId);
                        });
                        if (self.date().day() == 0 && tempArray[tempArray.length - 1] == 3) {
                            tempArray.splice(tempArray.length - 1, 1);
                            tempArray.splice(1, 0, 3);
                        }
                        self.actualCategoriesListOrdering(tempArray);


                        self.initCategory();
                        if (self.zoomedPaper() != null) {
                            var groupindex = self.selectedIndexPath()["group"];
                            var paperindex = self.data()[groupindex].newspapers.findIndex(p => p.id == self.zoomedPaper().id);
                            if (paperindex != -1) {
                                self.selectedIndexPath({
                                    "group": groupindex,
                                    "paper": paperindex
                                });
                                self.zoomedPaper(self.data()[groupindex].newspapers[paperindex]);
                                self.addZoomHistory();
                            } else {
                                self.zoomedPaper(null);
                                self.selectedIndexPath(null);
                            }
                        } else {
                            if (newspapersSettings.paper != null && self.category() != 0) {
                                var groupindex = self.data().findIndex(c => c.id == self.category());
                                var paperindex = self.data()[groupindex].newspapers.findIndex(p => p.slug == newspapersSettings.paper.replaceAll("-", "_"));
                                self.selectedIndexPath({
                                    "group": groupindex,
                                    "paper": paperindex
                                });
                                self.zoomedPaper(self.data()[groupindex].newspapers[paperindex]);
                            }
                        }

                    })
                }

                this.categoryVisible = function(c) {
                    if (self.category() == 0 && c.newspapers.length > 0)
                        return true;
                    return self.category() == c.id;
                }



                this.paperById = function(id) {
                    var res = null;
                    self.originaldata.forEach(function(group) {
                        group.newspapers.forEach(function(paper) {
                            if (paper.id == id) {
                                res = paper;
                            }
                        })
                    })
                    return res;
                }

                this.getType = function() {
                    if (self.data().size > 0)
                        return self.data()[0].type;
                    else
                        return "GROUP";
                }

                this.onItemSelected = function(paperindex, groupindex, p) {
                    self.selectCategory(self.data()[groupindex].id, false);
                    self.selectedIndexPath({
                        "group": groupindex,
                        "paper": paperindex
                    });
                    self.zoomedPaper(p);
                    self.addZoomHistory(self.selectedIndexPath());
                    self.scrollToNewsStart();
                }





                //********* Zoom ***********//
                this.selectedPaperTitle = function() {
                    var p = self.zoomedPaper();
                    return (p == null) ? "" : p.title;
                }

                this.SelectedPaperDate = function() {
                    var p = self.zoomedPaper();
                    if (p == null)
                        return "";
                    return p.date.format("DD.MM.YYYY");
                }

                this.SelectedPaperImage = function() {
                    var p = self.zoomedPaper();
                    if (p == null)
                        return "";
                    return BASE_URL + p.imgUrl;
                }

                this.zoomNextB = function() {
                    self.zoomNext();
                    self.scrollToNewsStart();
                }

                this.zoomPrevB = function() {
                    self.zoomPrev();
                    self.scrollToNewsStart();
                }

                this.scrollToNewsStart = function() {
                    const yOffset = -60;
                    const element = document.getElementById("pagestart");
                    const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({
                        top: y,
                        behavior: 'smooth'
                    });
                }

                this.zoomNext = function() {
                    var ip = self.selectedIndexPath();
                    var group = self.data()[ip.group];
                    if (ip.paper < group.newspapers.length - 1) {
                        ip.paper = ip.paper + 1
                    } else {
                        if (ip.group < self.data().length - 1) {
                            ip.paper = 0;
                            ip.group = ip.group + 1;
                            self.selectCategory(self.data()[ip.group].id, false);
                        }
                    }
                    self.zoomedPaper(self.data()[ip.group].newspapers[ip.paper]);
                    self.selectedIndexPath(ip);
                    self.addZoomHistory(ip);
                }

                this.zoomPrevDisabled = function() {
                    var ip = self.selectedIndexPath();
                    return ip.group == 0 && ip.paper == 0;
                }

                this.zoomNextDisabled = function() {
                    var ip = self.selectedIndexPath();
                    return ip.group == self.data().length - 1 && ip.paper == self.data()[ip.group].newspapers.length - 1;
                }

                this.zoomPrev = function() {
                    var ip = self.selectedIndexPath();
                    var group = self.data()[ip.group];
                    if (ip.paper > 0)
                        ip.paper = ip.paper - 1;
                    else
                    if (ip.group > 0) {
                        var prevgroup = self.data()[ip.group - 1];
                        ip.group = ip.group - 1;
                        ip.paper = prevgroup.newspapers.length - 1;
                        self.selectCategory(self.data()[ip.group].id, false);
                    }
                    self.zoomedPaper(self.data()[ip.group].newspapers[ip.paper]);
                    self.selectedIndexPath(ip);
                    self.addZoomHistory(ip);
                }

                this.fetchPaper = function(date, id) {
                    var formateddate = date.format("YYYYMMDD");
                    $.getJSON("https://protoselida.24media.gr/public/json/newspaper?id=" + id + "&date=" + formateddate, function(p) {

                        var day = dayjs(p.datePublished, "YYYY-MM-DD");
                        var pap = {
                            id: p.id,
                            title: p.title,
                            imgThumbUrl: p.imgThumbUrl,
                            imgUrl: p.imgUrl,
                            date: dayjs(p.lastUpdated, "YYYY-MM-DD"),
                            next: dayjs(p.nextPubDate),
                            prev: dayjs(p.previousPubDate),
                            name: p.name
                        };
                        self.zoomedPaper(pap);

                    })
                }

                //************ filter **************//
                this.filterTitleText = function() {
                    if (self.date() == null)
                        return "";
                    else
                        return dateStringTogenMonth(self.date().format("dddd D MMMM YYYY"));

                }

                this.filterTitleSmallText = function() {
                    if (self.date() == null)
                        return "";
                    return self.date().format('DD.MM.YYYY');
                }

                this.onPrev = function() {
                    if (self.zoomedPaper() == null) {
                        var prevday = self.date().subtract(1, 'day');
                        self.changeDate(prevday);
                    } else {
                        self.changeDate(self.zoomedPaper().prev);
                    }
                    //self.Unzoom();
                }

                this.onNext = function() {
                    if (self.zoomedPaper() == null) {
                        var nextday = self.date().add(1, 'day');
                        self.changeDate(nextday);
                    } else {
                        self.changeDate(self.zoomedPaper().next);
                    }
                    //self.Unzoom();
                }

                this.isToday = function() {
                    return self.date().isSame(dayjs(), 'day');
                }

                this.nameForCategory = function(id) {
                    return categoriesDict[id].name
                }

                this.nameForSelectedCategory = function() {
                    return self.nameForCategory(self.category());
                }

                this.categoryInfoForCategoryId = function(id) {
                    return self.data().find(c => c.id == id);
                }




                this.selectCategory = function(data, inform) {
                    if (typeof inform == "undefined")
                        inform = true;
                    self.category(data)
                    if (inform)
                        self.Unzoom();

                }

                this.zoomOut = function() {
                    self.category(0);
                    self.Unzoom();
                }

            }

            var m = new MainModel();
        })(jQuery);
    </script>

</head>


<body <?php body_class(); ?> itemscope>

    <?php get_template_part('templates/global_elements/news247-icons'); ?>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KMFBL5VB" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="global_wrapper">
        <?php

        btw_get_template_part('template-parts/ads/dfp', [
            'slot_id' => 'ros_prestitial',
            'container_class' => ['prestitial'],
        ]);

        get_template_part('templates/global_elements/header');

        get_template_part('templates/global_elements/side_navigation'); ?>

        <div class="latest_news_header">
            <?php
            btw_get_template_part('global_elements/homepage__eidiseis', [
                'img_size' => 'small_landscape',
            ]);
            ?>
            <div class="group_button">
                <a href="/roi-eidiseon" class="button more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ" type="button">
                    <span class="s-font-bold">ΠΕΡΙΣΣΟΤΕΡΑ</span>
                </a>
            </div>
        </div>