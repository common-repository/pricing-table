<?php
$data = get_post_meta($pid, 'pricing_table_opt', true);
$featured = get_post_meta($pid, 'pricing_table_opt_feature', true);
$feature_description = get_post_meta($pid, 'pricing_table_opt_feature_description', true);
$data_des = get_post_meta($pid, 'pricing_table_opt_description', true);
$feature_name = get_post_meta($pid, 'pricing_table_opt_feature_name', true);
$package_name = get_post_meta($pid, 'pricing_table_opt_package_name', true);
$currency = isset($currency) ? $currency : '$';
?>
<link rel="stylesheet" href="<?php echo plugins_url(); ?>/pricing-table/table-templates/bold/css/style.css">
<div id="wpdm-pricing-table">
    <?php
    $plans = count($data);
    foreach ($data as $key => $value) {
        ?>
        <div class="wppt-plan <?php if ($featured == $package_name[$key]) echo 'featured-column'; ?>" style="width: <?php echo (100/$plans - 1); ?>%">
            <div class="wppt-plan-name <?php if ($featured == $package_name[$key]) echo 'featured-plan'; ?>">
                <h3><?php echo $package_name[$key]; ?></h3>
            </div>
            <div class="wppt-plan-details">

                <div class="wppt-plan-header">
                    <p class="wppt-plan-price"><?php echo $currency; ?><?php echo $value['Price']; ?></p>
                    <p class="wppt-plan-period"><?php echo $value['Detail'];?></p>
                </div>
                <div class="wppt-plan-features">
                    <ul>
                        <?php
                        foreach ($value as $fet_key => $fet_val):
                            if (strtolower($fet_key) != "buttonurl" && strtolower($fet_key) != "buttontext" && strtolower($fet_key) != "price" && strtolower($fet_key) != "detail" ) {

                                if ($data_des[$key][$fet_key] != '') {
                                    $fet_val = "<a class='wppttip' href='#' title='{$data_des[$key][$fet_key]}'>" . $fet_val . "</a>";
                                }
                                else{
                                    $fet_val = "<span class='wppt-feature-val'>" . $fet_val . "</span>";
                                }
                                if(isset($params['hide']) && $params['hide'] == 'FeatureName')
                                    $ftr = '';
                                else
                                    $ftr = strtolower($fet_key) != 'detail' ? $feature_name[$fet_key] : '';

                                echo "<li>" . $fet_val . " <span class='wppt-feature-name'>" . $ftr . "</span></li>";
                            }
                        endforeach;
                        ?>
                    </ul>

                </div>

                <div class="wppt-plan-button">
                    <a href="<?php echo $value['ButtonURL'] ?>" class="wppt-plan-button-link">
                        <?php echo $value['ButtonText'] ?>
                    </a>
                </div>
            </div>
        </div>
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
