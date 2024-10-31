<?php
function wppt_add_custom_box() {
    add_meta_box(   'pricing-table-feature-options',
        __( 'Pricing Table Builder '
            . '<a class="button wpptimport" '
            . 'style="margin-left:15px;border-radius:15px;vertical-align:middle;border:1px solid #0490B3;color:#0490B3;">'
            . '<span>Import Table</span></a> ', 'wppt' ),
        'wppt_individual_features',
        'pricing-table',
        'normal',
        'core'
    );
}

function wppt_individual_features( $post ) {
    include(WPPT_PLUGINDIR."/tpls/metabox-feature-options.php");
}

function wppt_save_pricing_table( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( !current_user_can( 'edit_post', $post_id ) ) return;
    if(get_post_type()!='pricing-table') return;

    //print_r($_POST['features_description']);
    //exit;

    if(isset($_POST['features']))
        update_post_meta($post_id,'pricing_table_for_post',$_POST['features']);

    if(isset($_POST['features'])){
        update_post_meta($post_id,'pricing_table_opt',$_POST['features']);
        update_post_meta($post_id,'pricing_table_opt_description',$_POST['features_description']);
        update_post_meta($post_id,'pricing_table_opt_feature',$_POST['featured']);
        update_post_meta($post_id,'pricing_table_opt_feature_name',$_POST['feature_name']);
        update_post_meta($post_id,'pricing_table_opt_feature_description',$_POST['feature_description']);
        update_post_meta($post_id,'pricing_table_opt_package_name',$_POST['package_name']);


    update_post_meta($post_id,'alt_feature',$_POST['alt_feature']);
    update_post_meta($post_id,'alt_price',$_POST['alt_price']);
    update_post_meta($post_id,'alt_detail',$_POST['alt_detail']);

    update_post_meta($post_id,'__wppt_returnurl',$_POST['__wppt_returnurl']);
    update_post_meta($post_id,'__wppt_cancelurl',$_POST['__wppt_cancelurl']);
    update_post_meta($post_id,'__wppt_currency_code',$_POST['__wppt_currency_code']);
    update_post_meta($post_id,'__wppt_business',$_POST['__wppt_business']);

    update_post_meta( $post_id,'__wppt_css', stripcslashes($_POST['__wppt_css']) );
    }
}

function wppt_info_metabox_html($post){
    ?>
    <style>
        .wppt-btn {
            border-radius: 3px;
            display: inline-block;
            font-size: 13px;
            font-weight: bold;
            line-height: 15px;
            padding: 7px 10px;
            text-decoration: none;
        }
        .wppt-btn-info {
            background-color: #e8f7ed;
            border: 1px solid #30b661;
            color: #1ba84e;
            display: block;
            text-align: center;
        }
        .wppt-mb-p{
            font-size: 14px;
        }
    </style>
    <div class="wppt-info">
        <p class="wppt-mb-p">Need Help? We are happy to answer any question you might have.</p>
        <div style="margin: 20px 0px;">
            <a class="wppt-btn wppt-btn-info" target="_blank" href="https://www.wpdownloadmanager.com/support/forum/download-manager-free/">Support Forum</a>
        </div>
        <p class="wppt-mb-p">If you like WordPress Pricing Table please leave us a <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/pricing-table?rate=5#postform" style="color:#E6B800;text-decoration: none;">★★★★★</a> rating. <b>THANKS</b> in advance!</p>
        <p class="wppt-mb-p">Get Pro Version for Unlimited Table Templates and More Options.</p>
        <div style="margin: 20px 0px;">
            <a class="wppt-btn wppt-btn-info" target="_blank" href="https://www.wpdownloadmanager.com/download/wordpress-pricing-table-plugin/">Upgrade To Premium</a>
        </div>
    </div>

    <?php
}

function wppt_shortcode_metabox_html(){
    global $post;
    ?>
    <div class="wppt-info">
        <p class="wppt-mb-p">Place this shortcode in a page to show the current pricing table. You can also use TinyMCE editor button to insert shortcode.</p>
    </div>
    <?php

    echo "<input type=text readonly=readonly value='[ahm-pricing-table id={$post->ID}]' style=\"box-shadow:none;border:2px solid #ddd;text-align:Center;font-family:'Courier New';font-size:14px;width: 100%;\" onclick='this.select()' />";
}

function wppt_info_metabox() {

    add_meta_box( 'pricing-table-shortcode-metabox',
        'Pricing Table Shortcode',
        'wppt_shortcode_metabox_html',
        'pricing-table',
        'side',
        'high'
    );

    add_meta_box( 'pricing-table-info-metabox',
        'Pricing Table Quick Links',
        'wppt_info_metabox_html',
        'pricing-table',
        'side',
        'default'
    );

}
add_action( 'add_meta_boxes', 'wppt_info_metabox');

add_action( 'add_meta_boxes', 'wppt_add_custom_box');
add_action( 'save_post', 'wppt_save_pricing_table' );
