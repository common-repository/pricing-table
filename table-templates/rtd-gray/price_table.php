<?php
$data = get_post_meta($pid, 'pricing_table_opt', true);
$featured = get_post_meta($pid, 'pricing_table_opt_feature', true);
$feature_description = get_post_meta($pid, 'pricing_table_opt_feature_description', true);
$data_des = get_post_meta($pid, 'pricing_table_opt_description', true);
//print_r($data_des);

$feature_name = get_post_meta($pid, 'pricing_table_opt_feature_name', true);
$package_name = get_post_meta($pid, 'pricing_table_opt_package_name', true);

$kc = 0;
?>

<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/pricing-table/table-templates/rtd-gray/css/style.css">
<div style="clear: both;"></div>

<div id="wpdm-pricing-table" class="wppt-img-left" style="clear: both;display: block;">
    <?php
    $num_of_plans = count($data);
    $cnt = 0;
    foreach ($data as $key => $value) {
        $cnt++;
        $cnt == $num_of_plans ? ($styl = 'style="-moz-border-radius: 0 5px 5px 0; -webkit-border-radius: 0 5px 5px 0; border-radius: 0 5px 5px 0;"') : $styl = '';
        ?>
        <div class="plan" <?php echo $styl; ?><?php if ($featured == $package_name[$key]) { ?> id="most-popular"<?php } ?> >

            <h3><?php echo $package_name[$key]; ?><span><?php echo $value['Price']; ?></span></h3>
            <a class="signup" href="<?php echo $value['ButtonURL'] ?>"><?php echo $value['ButtonText'] ?></a>
            <ul>

                <?php
                foreach ($value as $key1 => $value1) {

                    if (strtolower($key1) != "buttonurl" && strtolower($key1) != "buttontext" && strtolower($key1) != "price") {
                        if (isset($data_des[$key], $data_des[$key][$key1]) && $data_des[$key][$key1] != '') {
                            $value1 = "<a class='wppttip' href='#' title='{$data_des[$key][$key1]}'>" . $value1 . "</a>";
                        }
                        if(isset($params['hide']) && $params['hide'] == 'FeatureName')
                            $ftr = '';
                        else
                            $ftr = strtolower($key1) != 'detail' ? $feature_name[$key1] : '';

                        echo "<li><b>" . $value1 . " <span class='feature-name'>" . $ftr . "</span></b></li>";
                    }
                }
                ?>

            </ul>
        </div >
<?php } ?>
    <div style="clear: both;"></div>

</div>

<script>
    jQuery(function() {
        var totalli = jQuery("#wpdm-pricing-table ul:first li").length;
        var maxh = 0;

        for (var i = 1; i <= totalli; i++) {
            jQuery("#wpdm-pricing-table ul li:nth-child(" + i + ")").each(function() {
                var h = jQuery(this).outerHeight();
                if (h > maxh)
                    maxh = h;

            });
            jQuery("#wpdm-pricing-table ul li:nth-child(" + i + ")").css('cssText', 'min-height:' + maxh + 'px !important');
            maxh = 0;
        }
    });
</script>

<!-- price table designed by red-team-design.com -->

