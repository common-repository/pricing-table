<?php
add_filter('mce_external_plugins', "wppt_tinyplugin_register");
add_filter('mce_buttons', 'wppt_tinyplugin_add_button', 0);

function wppt_tinyplugin_add_button($buttons) {
    array_push($buttons, "separator", "wppt_tinyplugin");
    return $buttons;
}

function wppt_tinyplugin_register($plugin_array) {
    $url = plugins_url("/pricing-table/js/ext/editor_plugin.js");
    $plugin_array['wppt_tinyplugin'] = $url;
    return $plugin_array;
}

function wppt_free_tinymce() {
    global $wpdb;
    if (!isset($_GET['wppt_action']) || $_GET['wppt_action'] != 'wppt_tinymce_button')
        return false;
    ?>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
            <title>Pricing Table &#187; Insert Table</title>
            <style>
                *{font-family: Tahoma !important; font-size: 9pt; letter-spacing: 1px;}
                select,input{padding:5px;font-size: 9pt !important;font-family: Tahoma !important; letter-spacing: 1px;margin:5px;}
                .button{
                    background: #7abcff; /* old browsers */
                    background: -moz-linear-gradient(top, #7abcff 0%, #60abf8 44%, #4096ee 100%); /* firefox */
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7abcff), color-stop(44%,#60abf8), color-stop(100%,#4096ee)); /* webkit */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7abcff', endColorstr='#4096ee',GradientType=0 ); /* ie */
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                    border:1px solid #FFF;
                    color: #FFF;
                }
                .input{
                    width: 340px;   
                    background: #EDEDED; /* old browsers */
                    background: -moz-linear-gradient(top, #EDEDED 24%, #fefefe 81%); /* firefox */
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(24%,#EDEDED), color-stop(81%,#fefefe)); /* webkit */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#EDEDED', endColorstr='#fefefe',GradientType=0 ); /* ie */
                    border:1px solid #aaa; 
                    color: #000;
                }
                .button-primary{cursor: pointer;}
                fieldset{padding: 10px;}
                select{clear: both;display: block;}
                small{margin-left: 6px;}
                .clrpkr{ border-radius:4px;width: 340px;}
                #rock_styles{margin-top: 10px;padding-left: 6px;}
            </style> 
        </head>
        <body><br>
            <fieldset>
                <legend>Embed Pricing Table</legend>
                <small>Select Table</small>
                <select class="button input" id="fl">
                    <?php
                    query_posts('post_type=pricing-table&posts_per_page=1000');

                    while (have_posts()) {
                        the_post();
                        ?>
                        <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
                        <?php
                    }
                    ?>
                </select>

                <small>Select Template</small>
                <select class="button input" id="ptt"> 
                    <?php
                    $directory = "../wp-content/plugins/pricing-table/table-templates";

                    $directory_list = opendir($directory);
                    while (FALSE !== ($file = readdir($directory_list))) {
                        // if the filepointer is not the current directory
                        // or the parent directory
                        if ($file != '.' && $file != '..') {
                            // we build the new path to scan
                            $path = $directory . '/' . $file;
                            if (is_dir($path)) {
                                $style = $file == 'rock' ? "style='font-weight:bold;background: #ECD7F7;'" : "";
                                $file_label = ucwords(str_replace("-", " ", $file));
                                echo "<option value='" . $file . "' $style>" . $file_label . "</option>";
                            }
                        }
                    }
                    ?> 
                </select>  
                <div id="rock_styles" class="tplopts" style="display: none;">
                    <div><em>Options are available in pro only!</em></div>
                    <div style="width: 180px;float: left">
                        <strong>Color</strong><br>
                        <input type="radio" value="default" disabled="disabled" title="Available in pro only" name="color" class="pptc"> Default<br>
                        <input type="radio" value="skin-blue" disabled="disabled" title="Available in pro only" name="color" class="pptc"> Blue<br>
                        <input type="radio" value="skin-dark-blue" disabled="disabled" title="Available in pro only" name="color" class="pptc"> Dark Blue<br>
                        <input type="radio" value="skin-coffee" disabled="disabled" title="Available in pro only" name="color" class="pptc"> Coffee<br>
                        <input type="radio" value="skin-green" disabled="disabled" title="Available in pro only" name="color" class="pptc"> Green<br>
                        &nbsp;<br>
                    </div>
                    <div style="width: 180px;float: left">
                        <b>Skins &amp; Styles</b><br/>
                        <input type="checkbox" value="flat" disabled="disabled" title="Available in pro only" class="pptc"> Flat<br>
                        <input type="checkbox" value="singular-head" disabled="disabled" title="Available in pro only" class="pptc"> Similar Header<br>
                        <input type="checkbox" value="hover-effect" disabled="disabled" title="Available in pro only" class="pptc"> Hover Effect<br>
                        <input type="checkbox" value="long-shadow" disabled="disabled" title="Available in pro only" class="pptc"> Long Shadow<br>
                        <input type="checkbox" value="skin-smooth" disabled="disabled" title="Available in pro only" class="pptc"> Smooth<br>
                        <input type="checkbox" value="arrowed" disabled="disabled" title="Available in pro only" class="pptc"> Arrowed<br>
                    </div><div style="width: 180px;float: left">
                        <strong>Style</strong><br>
                        <input type="radio" value="style-1" name="style" disabled="disabled" title="Available in pro only" class="pptc"> Style 1<br>
                        <input type="radio" value="style-2" name="style" disabled="disabled" title="Available in pro only" class="pptc"> Style 2<br>
                        <input type="radio" value="style-3" name="style" disabled="disabled" title="Available in pro only" class="pptc"> Style 3<br>
                        <input type="radio" value="style-2 style-3" disabled="disabled" title="Available in pro only" name="style" class="pptc"> Style 4<br>
                    </div><div style="clear: both"></div>

                    <div style="margin:8px;padding: 10px;border: 1px solid #eeeeee">
                        <label><input type="checkbox" id="paypal" value="1" disabled="disabled" title="Available in pro only"> Use PayPal</label><br/>

                    </div>

                </div>

                <div id="override_styles" class="tplopts" style="display: none;">
                    <small>Style:</small>
                    <select class="button input" id="mccolor">
                        <option value="1">--Select Style--</option>
                        <option value="1">Style 1 (Black)</option>
                        <option value="2" disabled="disabled">Style 2 (Orange)</option>
                        <option value="3" disabled="disabled">Style 3 (Red)</option>
                        <option value="4" disabled="disabled">Style 4 (Gray-Red)</option>
                        <option value="5" disabled="disabled">Style 5 (Mixed Colors)</option>
                        <option value="6" disabled="disabled">Style 6 (Red-Orange)</option>
                        <option value="7" disabled="disabled">Style 7 (Mixed Colors)</option>
                        <option value="8" disabled="disabled">Style 8 (Yellow-Red)</option>
                        <option value="9" disabled="disabled">Style 9 (Green-StepUp)</option>
                        <option value="10" disabled="disabled">Style 10 (Blue-StepUp)</option>
                        <option value="11" disabled="disabled">Style 11 (Orange-StepUp)</option>
                        <option value="12" disabled="disabled">Style 12 (Mixed Colors)</option>
                    </select>
                </div>

                <div id="smooth_styles" class="tplopts" style="display: none;">
                    <input type="checkbox" id="hfn" value="1"> Hide Feature Name<br><br>
                </div>

                <div id="rtd_styles" class="tplopts" style="display: none;">
                    <input type="checkbox" id="rtd-hfn" value="1"> Hide Feature Name<br><br>
                </div>                

                <input type="submit" id="addtopost" class="button button-primary" name="addtopost" value="Insert into post" />
            </fieldset>   <br>

            <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
            <script src="<?php echo home_url('/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>
            <script>
                jQuery.noConflict();
                function script_init() {

                    switch (jQuery('#ptt').val()) {
                        case "override":
                            jQuery('.tplopts').slideUp();
                            jQuery('#override_styles').slideDown();
                            break;
                        case "rock":
                            jQuery('.tplopts').slideUp();
                            jQuery('#rock_styles').slideDown();
                            break;
                        case "smooth":
                            jQuery('.tplopts').slideUp();
                            jQuery('#smooth_styles').slideDown();
                            break;
                        case "rtd-gray":
                            jQuery('.tplopts').slideUp();
                            jQuery('#rtd_styles').slideDown();
                            break;                            
                        default:
                            jQuery('.tplopts').slideUp();
                            break;
                    }
                }
                window.onload = script_init();
                jQuery(function () {
                    jQuery('#ptt').change(function () {
                        script_init();
                    });
                });

                var scc = '';
                jQuery('.pptc').on('click', function () {
                    scc = '';
                    jQuery('.pptc').each(function () {
                        if (this.checked)
                            scc += jQuery(this).val() + " ";
                    });
                });

                jQuery('#addtopost').click(function () {
                    var win = window.dialogArguments || opener || parent || top;
                    var hide = '';

                    if (jQuery('#hfn').is(':checked')) {
                        hide = "FeatureName";
                    }
                    if (jQuery('#rtd-hfn').is(':checked')) {
                        hide = "FeatureName";
                    }
                    if (hide !== '')
                        hide = 'hide="' + hide + '"';

                    win.send_to_editor('[ahm-pricing-table id=' + jQuery('#fl').val() + ' template=' + jQuery('#ptt').val() + ' ' + hide + ']');
                    tinyMCEPopup.close();
                    return false;
                });
            </script>

        </body>    
    </html>

    <?php
    die();
}

add_action('init', 'wppt_free_tinymce');

