<?php
$data = get_post_meta($pid, 'pricing_table_opt',true);
$featured = get_post_meta($pid, 'pricing_table_opt_feature',true);
$feature_description = get_post_meta($pid, 'pricing_table_opt_feature_description',true);
$data_des = get_post_meta($pid, 'pricing_table_opt_description',true);
$feature_name = get_post_meta($pid, 'pricing_table_opt_feature_name',true);
$package_name = get_post_meta($pid, 'pricing_table_opt_package_name',true);
$pt = get_post($pid);
$alt_feature = get_post_meta($pid, 'alt_feature',true);
$alt_price = get_post_meta($pid, 'alt_price',true);
$alt_detail = get_post_meta($pid, 'alt_detail',true);
?>

<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro|Roboto|Open+Sans+Condensed:700,300,300italic' rel='stylesheet'>
<link href="<?php echo plugins_url('pricing-table/css/site/bootstrap-grid.css');?>" rel="stylesheet">
<link href="<?php echo plugins_url('pricing-table/table-templates/override/style.css');?>" rel="stylesheet">

<div class="w3eden">

	<div class="container-fluid top50">
    	<div class="row pricing-table" id="wpdm-pricing-table1">
        	<div class="col-md-2">
            	<ul class="pricing-title prc-title-list">
                    <li class="title-hader-row-1"></li>
                    <li class="title-hader-bar"></li>
                    <li class="title-hader-row-2">
                        <h1><?php $pt = explode(" ",$pt->post_title); echo $pt[0]."<br/><span>{$pt[1]}</span>";?></h1>
                    </li>

                    <?php
                    foreach($feature_name as $k=>$value1){

                        if(strtolower($value1) != "buttonurl" && strtolower($value1) != "buttontext" && strtolower($value1) != "detail"){
                            if(strtolower($value1) == "price"){

                            }else if(strtolower($value1) == "detail"){
                                if(!empty($alt_detail))echo "<li class='title-row {$ns}'><p>".$alt_detail."</p></li>";
                                else echo "<li class='title-row {$ns}'><p>".$value1."</p></li>";
                            }else  {
                                if($feature_description[$k] == '')
                                    echo "<li  class='title-row {$ns}'><p>".$value1."</p></li>";
                                else
                                    echo "<li class='title-row {$ns}' title='{$feature_description[$k]}'><p>".$value1."</p></li>";
                            }
                        }
                        $ns = !isset($ns) ? 'title-row-gray' : $ns;
                    }
                    ?>
                </ul>
            </div>

            <div class="col-md-10">
            	<div class="row">
                    <?php
                    $count = 0;
                    $total = count($data);
                    $currency = isset($currency) ? $currency : '';
                    foreach($data as $key => $value){
                        $rs = '';
                        $count++;
                        if($count == 1)
                            $col_id = "top-li-first";
                        elseif($count < $total)
                            $col_id = "";
                        else $col_id = "top-li-last";
                    ?>
                        <div class="col-md-<?php echo intval(12/count($package_name)); ?>">
                            <div class="free<?php if($package_name[$key] == $featured) echo ' active'; ?>">
                                <ul class="pricing-title">
                                    <li class="title-hader-row-1 free-hader-row-1 <?php echo $col_id;?>">
                                        <?php if($package_name[$key] == $featured)
                                            echo '<div class="ribbon">'; ?> <h5><?php echo $package_name[$key];?></h5>
                                    </li>
                                    <li class="free-hader-bar"></li>
                                    <li class="title-hader-row-2 free-row-2">
                                        <h1><?php echo $currency.$value['Price'];?></h1>
                                        <p><?php echo $value['Detail'];?></p>
                                    </li>
                                    <?php
                                    foreach($value as $key1 => $value1){

                                        if( strtolower($key1) != "buttonurl" && strtolower($key1) != "buttontext" && strtolower($key1) != "price" && strtolower($key1) != "detail"){

                                            if($data_des[$key][$key1] == '')
                                                echo "<li class='title-row {$rs} '><p>".$value1."&nbsp;</p></li>";
                                            else
                                                echo "<li class='wppttip title-row {$rs} ' title='{$data_des[$key][$key1]}'><p>".$value1."&nbsp;</p></li>";
                                        }

                                        $rs = $rs == '' ? 'free-row-gray' : '';
                                    } ?>
                                    <li class="title-row free-row-a footer-row"> <a href="<?php echo $value['ButtonURL']?>" class="btn btn-lg btn-primary"><?php echo $value['ButtonText']?></a></li>
                                </ul>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
