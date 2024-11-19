<!DOCTYPE html>
<!--[if IE 8]>
    <html class="ie ie8 lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@200;700;800&display=swap" rel="stylesheet">
    

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php
    wp_head();
    do_action('btw_amp_styles');
    ?>

</head>

<?php

// LOGO
$logo = get_brand_logo();
$logo_url = $logo['url'];
$logo_alt = $logo['alt'];
?>

<body <?php body_class(); ?> id="global_body">
    <!-- INMOBI CMP -->
    <amp-geo layout="nodisplay">
        <script type="application/json">
            {
                "ISOCountryGroups": {
                    "usca": [
                        "preset-us-ca"
                    ]
                }
            }
        </script>
    </amp-geo>
    <amp-consent id="inmobi" layout="nodisplay">
        <script type="application/json">
            {
                "consentInstanceId": "inmobi",
                "checkConsentHref": "https://api.cmp.inmobi.com/amp/check-consent",
                "consentRequired": "remote",
                "promptUISrc": "https://cmp.inmobi.com/tcfv2/amp.html",
                "postPromptUI": "postPromptUI",
                "geoOverride": {
                    "usca": {
                        "consentRequired": "remote",
                        "promptUISrc": "https://cmp.inmobi.com/tcfv2/amp.html?usp",
                        "postPromptUI": null
                    }
                },
                "clientConfig": {
                    "coreConfig": {
                        "vendorPurposeLegitimateInterestIds": [
                            7,
                            8,
                            9,
                            2,
                            10,
                            11
                        ],
                        "isCoveredTransaction": false,
                        "ccpaViaUsp": false,
                        "consentLocations": [
                            "WORLDWIDE"
                        ],
                        "cmpVersion": "latest",
                        "totalVendors": 837,
                        "mspaSignalMode": "OPT_OUT",
                        "privacyMode": [
                            "GDPR",
                            "USP"
                        ],
                        "mspaJurisdiction": "STATE_AND_NATIONAL",
                        "hashCode": "skdoDnsMfEChLndwRQahVQ",
                        "publisherLogo": "https://www.contra.gr/img/app/9067384/0/o/0/0/contra.png?qc-size=167,50",
                        "publisherSpecialFeaturesIds": [
                            1,
                            2
                        ],
                        "uspVersion": 1,
                        "mspaAutoPopUp": true,
                        "publisherCountryCode": "GR",
                        "publisherPurposeIds": [
                            1,
                            2,
                            3,
                            4,
                            5,
                            6,
                            7,
                            8,
                            9,
                            10,
                            11
                        ],
                        "publisherSpecialPurposesIds": [
                            1,
                            2
                        ],
                        "gbcConfig": {
                            "applicablePurposes": [{
                                    "defaultValue": "GRANTED",
                                    "id": 1
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 2
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 3
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 4
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 5
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 6
                                },
                                {
                                    "defaultValue": "GRANTED",
                                    "id": 7
                                }
                            ],
                            "locations": [
                                "EEA"
                            ],
                            "enabled": true
                        },
                        "publisherFeaturesIds": [
                            1,
                            2,
                            3
                        ],
                        "stacks": [],
                        "mspaOptOutPurposeIds": [
                            1,
                            2,
                            3,
                            4
                        ],
                        "publisherLIRestrictionIds": [],
                        "inmobiAccountId": "ELvyd96EHn3ND",
                        "vendorSpecialPurposesIds": [
                            1,
                            2
                        ],
                        "initScreenBodyTextOption": 1,
                        "publisherConsentRestrictionIds": [],
                        "vendorPurposeIds": [
                            1,
                            2,
                            7,
                            8,
                            10,
                            11,
                            3,
                            5,
                            4,
                            6,
                            9
                        ],
                        "lang_": "el",
                        "defaultToggleValue": "off",
                        "initScreenRejectButtonShowing": true,
                        "initScreenCloseButtonShowing": false,
                        "publisherPurposeLegitimateInterestIds": [],
                        "suppressCcpaLinks": false,
                        "publisherName": "CONTRA STAGING - AMP",
                        "vendorSpecialFeaturesIds": [
                            2,
                            1
                        ],
                        "displayUi": "always",
                        "uspLspact": "N",
                        "googleEnabled": false,
                        "gdprEncodingMode": "TCF",
                        "vendorListUpdateFreq": 30,
                        "uspJurisdiction": [
                            "CA"
                        ],
                        "vendorFeaturesIds": [
                            1,
                            2,
                            3
                        ],
                        "gvlVersion": 3
                    },
                    "premiumProperties": {},
                    "premiumUiLabels": {
                        "uspDnsText": [
                            ""
                        ]
                    },
                    "coreUiLabels": {
                        "initScreenRejectButton": "ΔΙΑΦΩΝΩ",
                        "agreeButton": "ΣΥΜΦΩΝΩ"
                    },
                    "theme": {
                        "uxToogleActiveColor": "#888",
                        "uxSecondaryButtonTextColor": "#000",
                        "uxPrimaryButtonColor": "#000",
                        "uxSecondaryButtonColor": "#fff",
                        "uxFontColor": "#000",
                        "uxBackgroundColor": "#fff"
                    },
                    "tagVersion": "V3"
                }
            }
        </script>
        <div id="postPromptUI">
            <button role="button" on="tap:inmobi.prompt()">
                <svg style="height:20px">
                    <g fill="none">
                        <g fill="#FFF">
                            <path d="M16 10L15 9C15 9 15 8 15 8L16 7C16 7 16 6 16 6 16 5 15 4 14 3 14 2 13 2 13 3L12 3C12 3 11 3 11 2L11 1C11 1 10 0 10 0 9 0 7 0 6 0 6 0 5 1 5 1L5 2C5 3 4 3 4 3L3 3C3 2 2 2 2 3 1 4 0 5 0 6 0 6 0 7 0 7L1 8C1 8 1 9 1 9L0 10C0 10 0 11 0 11 0 12 1 13 2 14 2 15 3 15 3 14L4 14C4 14 5 14 5 15L5 16C5 16 6 17 6 17 7 17 9 17 10 17 10 17 11 16 11 16L11 15C11 14 12 14 12 14L13 14C13 15 14 15 14 14 15 13 16 12 16 11 16 11 16 10 16 10ZM13 13L12 13C11 13 11 13 9 14L9 16C9 16 7 16 7 16L7 14C5 14 5 13 4 13L3 13C2 13 1 12 1 11L3 10C2 9 2 8 3 7L1 6C1 5 2 4 3 4L4 4C5 4 5 3 7 3L7 1C7 1 9 1 9 1L9 3C11 3 11 4 12 4L13 4C14 4 15 5 15 6L13 7C14 8 14 9 13 10L15 11C15 12 14 13 13 13ZM8 5C6 5 5 7 5 9 5 10 6 12 8 12 10 12 11 10 11 9 11 7 10 5 8 5ZM8 11C7 11 6 10 6 9 6 7 7 6 8 6 9 6 10 7 10 9 10 10 9 11 8 11Z" />
                        </g>
                    </g>
                </svg>
                PRIVACY
            </button>
        </div>
    </amp-consent>
    <div class="inmobi-disclaimer">
        <button role="button" on="tap:inmobi.prompt()">
            <svg style="height:20px">
                <g fill="none">
                    <g fill="#FFF">
                        <path d="M16 10L15 9C15 9 15 8 15 8L16 7C16 7 16 6 16 6 16 5 15 4 14 3 14 2 13 2 13 3L12 3C12 3 11 3 11 2L11 1C11 1 10 0 10 0 9 0 7 0 6 0 6 0 5 1 5 1L5 2C5 3 4 3 4 3L3 3C3 2 2 2 2 3 1 4 0 5 0 6 0 6 0 7 0 7L1 8C1 8 1 9 1 9L0 10C0 10 0 11 0 11 0 12 1 13 2 14 2 15 3 15 3 14L4 14C4 14 5 14 5 15L5 16C5 16 6 17 6 17 7 17 9 17 10 17 10 17 11 16 11 16L11 15C11 14 12 14 12 14L13 14C13 15 14 15 14 14 15 13 16 12 16 11 16 11 16 10 16 10ZM13 13L12 13C11 13 11 13 9 14L9 16C9 16 7 16 7 16L7 14C5 14 5 13 4 13L3 13C2 13 1 12 1 11L3 10C2 9 2 8 3 7L1 6C1 5 2 4 3 4L4 4C5 4 5 3 7 3L7 1C7 1 9 1 9 1L9 3C11 3 11 4 12 4L13 4C14 4 15 5 15 6L13 7C14 8 14 9 13 10L15 11C15 12 14 13 13 13ZM8 5C6 5 5 7 5 9 5 10 6 12 8 12 10 12 11 10 11 9 11 7 10 5 8 5ZM8 11C7 11 6 10 6 9 6 7 7 6 8 6 9 6 10 7 10 9 10 10 9 11 8 11Z" />
                    </g>
                </g>
            </svg>
            PRIVACY
        </button>
    </div>
    <!--END INMOBI CMP -->


    <?php do_action('btw_amp_analytics'); ?>

    <?php get_template_part('templates/global_elements/contra-icons'); ?>

    <div class="global_wrapper">
        <?php

        btw_get_template('amp-templates/ads/dfp', [
            'slot_id'         => 'ros_prestitial',
            'slot_name'       => 'ros_prestitial',
            'container_class' => ['prestitial'],
            'sizes'           => '1x1',
        ]);

        if (btw_is_magazine()) {
            get_template_part('templates/global_elements/header-magazine');
        } else {
            get_template_part('templates/global_elements/header');
        }

        get_template_part('templates/global_elements/side_navigation');
