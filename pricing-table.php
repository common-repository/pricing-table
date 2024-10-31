<?php
/**
 * Plugin Name: Pricing Table
 * Plugin URI: https://www.wpdownloadmanager.com/download/wordpress-pricing-table-plugin/
 * Description: WordPress Plugin for creating colorful pricing tables.
 * Author: W3 Eden, Inc.
 * Version: 1.5.2
 * Author URI: https://www.wpdownloadmanager.com/
 */

include(dirname(__FILE__)."/modules/metabox.php");
include(dirname(__FILE__)."/modules/wppt-free-mce-button.php");

global $enque;
$enque = 0;

$plugindir = str_replace('\\','/',dirname(__FILE__));
define('WPPT_PLUGINDIR',$plugindir);

function wppt_custom_init(){
    $labels = array(
        'name' => _x('Pricing Tables', 'post type general name'),
        'singular_name' => _x('Pricing Table', 'post type singular name'),
        'add_new' => _x('Add New', 'pricing-table'),
        'add_new_item' => __('Add New Pricing Table'),
        'edit_item' => __('Edit Pricing Table'),
        'new_item' => __('New Pricing Table'),
        'all_items' => __('All Pricing Tables'),
        'view_item' => __('View Pricing Table'),
        'search_items' => __('Search Pricing Tables'),
        'not_found' =>  __('No Pricing Table found'),
        'not_found_in_trash' => __('No Pricing Tables found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Pricing Table'

    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-welcome-widgets-menus'
    );
    register_post_type('pricing-table',$args);
}


function wppt_preview_table($content){
    global $wp_query;
    if(get_post_type()!='pricing-table') return $content;
    $pid = get_the_ID();
    $template = isset($_REQUEST['template'])?$_REQUEST['template']:'rock';
    $responsive = isset($params['responsive'])?'responsive':'';
    $styles = array('epic'=>9,'mega'=>9,'lucid'=>10,'override'=>9);

    ob_start();
    ?>
    <form action="">
        <input type="hidden" name="pricing-table" value="<?php echo $wp_query->query_vars['pricing-table']; ?>">
        <b>Select Template</b><br/>
        <select name="template" class="button input" id="ptt">
            <option value="">Select Template</option>
            <?php
            $directory = ABSPATH."/wp-content/plugins/pricing-table/table-templates";
            $directory_list = opendir($directory);
            while (FALSE !== ($file = readdir($directory_list))){
                // if the filepointer is not the current directory
                // or the parent directory
                if($file != '.' && $file != '..'){
                    $sel = isset($_GET['template']) && $_GET['template'] === $file?'selected=selected':'';
                    $path = $directory.'/'.$file;
                    if(is_dir($path)){
                        echo "<option $sel value='".$file."'>".strtoupper($file)."</option>";
                    }
                }
            }
            ?>

        </select>
        <input type="submit" value="Preview"><br/>
        <?php if(isset($styles[$template]) && $styles[$template]>0){ ?>
            <b>Select Style</b><br/>
            <select onchange="jQuery('#ptts').attr('href','<?php echo plugins_url('pricing-table/table-templates/'.$template.'/style');?>'+this.value+'.css')">
                <option value="">Select Style</option>
                <?php for($dx = 1; $dx <= $styles[$template]; $dx++){ ?>
                    <option value="<?php echo $dx; ?>">Style <?php echo $dx; ?></option>

                <?php } ?>
            </select>
        <?php } ?>

    </form>

    <?php
    include("table-templates/{$template}/price_table.php");
    $data = ob_get_contents();
    ob_clean();
    $code[] = "[y]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/yes.png")."' />";
    $code[] = "[n]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/no.png")."' />";
    $code[] = "[na]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/na.png")."' />";
    $data = str_replace($code, $icons, $data);
    return $content.$data;
}

function wppt_table($params){
    $pid = $params['id'];
    if(isset($params['style']))
    $style = $params['style'];
    extract($params);

    $custom_css = get_post_meta($pid, '__wppt_css',true);

    $template = isset($params['template'])?$params['template']:'rock';
    $responsive = isset($params['responsive'])?'responsive':'';
    ob_start();
    if($custom_css != '') echo '<style>'.$custom_css.'</style>';
    include("table-templates/{$template}/price_table.php");
    $data = ob_get_contents();
    ob_clean();
    $shortcode = get_option("_wppt_shortcode",array());
    if(is_array($shortcode)){
        foreach($shortcode as $c){
            if(in_array(strtolower(end(explode('.',$c))),array('png','jpg','gif','jpeg')))
                $icons[] = "<img src='$c' />";
            else
                $icons[] = $c;
        }}

    $code=get_option("_wppt_code");
    $code = @array_values($code);
    $code[] = "[y]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/yes.png")."' />";
    $code[] = "[n]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/no.png")."' />";
    $code[] = "[na]";
    $icons[] = "<img src='".plugins_url("pricing-table/images/na.png")."' />";
    $data = str_replace($code, $icons,$data);
    return $data;
}


function wppt_columns_struct( $columns ) {
    $column_shorcode = array( 'shortcode' => 'Short-Code' );
    $columns = array_slice( $columns, 0, 2, true ) + $column_shorcode + array_slice( $columns, 2, NULL, true );
    return $columns;
}

function wppt_column_obj( $column ) {
    global $post;
    if($post->post_type == 'pricing-table'){
        switch ( $column ) {
            case 'shortcode':
            echo "<input type=text readonly=readonly value='[ahm-pricing-table id={$post->ID}]' size=25 style=\"box-shadow:none;border:1px solid #ddd;text-align:Center;font-family:'Courier New';font-size:10pt;pading:5px 10px;\" onclick='this.select()' />";
            break;
        }
    }
}

function wppt_help(){
    include(dirname(__FILE__)."/tpls/help.php");
}

function wppt_shortcodes(){
    include(dirname(__FILE__)."/tpls/short-codes.php");
}
function wppt_save_shortcode(){
    $sc = array();
    $sc = $_POST['shortcode'];
    foreach ($sc as $key=>$value) {
        $sc[$key] = stripslashes($value);
    }
    $c = array();
    $c = $_POST['code'];
    foreach ($c as $key=>$value) {
        $c[$key] = stripslashes($value);
    }

    update_option('_wppt_shortcode', $sc);
    update_option('_wppt_code', $c);
}

function wppt_menu(){
    add_submenu_page('edit.php?post_type=pricing-table', 'Shortcodes', 'Shortcodes', 'administrator', 'short-codes', 'wppt_shortcodes');
    add_submenu_page('edit.php?post_type=pricing-table', 'Help', 'Help', 'administrator', 'help', 'wppt_help');
}

if(is_admin()){
    add_action("admin_menu","wppt_menu");
}

function wppt_admin_enqueue_scripts( $hook ){

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

        if ( 'pricing-table' === $post->post_type ) {

            wp_enqueue_script("jquery");
            wp_enqueue_script("jquery-form");
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style('wp-jquery-ui-dialog');
            wp_enqueue_script('wppt-dragtable', plugins_url('pricing-table/js/admin/dragtable.js'));
            wp_enqueue_script('wppt-tablednd', plugins_url('pricing-table/js/admin/jquery.tablednd_0_5.js'));
            wp_enqueue_script('wppt-tiptipjs', plugins_url() . '/pricing-table/js/site/jquery.tipTip.minified.js', array('jquery'));

            wp_enqueue_style('wppt-admin', plugins_url('pricing-table/css/admin/wppt.admin.css'));
            wp_enqueue_style('tiptipcss', plugins_url() . '/pricing-table/css/site/tipTip.css');
        }
    }
}

function admin_tiptip_init(){
    global $post;
    $screen = get_current_screen();

    if(is_object($post) && $post->post_type === 'pricing-table' && $screen->id == 'pricing-table'){
    ?>
        <script>
            jQuery(function(){
                jQuery(".feature-desc-edit").tipTip({defaultPosition:'top'});
                jQuery(".featured-package").tipTip({defaultPosition:'top'});
                jQuery(".deletecol").tipTip({defaultPosition:'top'});
                jQuery(".featured-package-edit").tipTip({defaultPosition:'top'});
                jQuery(".deleterow").tipTip({defaultPosition:'top'});
                jQuery(".feature-edit").tipTip({defaultPosition:'top'});
                jQuery(".rdndHandler").tipTip({defaultPosition:'top'});
                jQuery(".dragPricingCol").tipTip({defaultPosition:'top'});
                jQuery(".wpptimport").tipTip({
                    content: "You can import previouly exported table, no need to build same table twice.",
                    defaultPosition: 'top'
                });
            });
        </script>
    <?php
    }
}
add_action('admin_footer','admin_tiptip_init');

function wppt_enqueue_scripts(){
    global $enque;

    if($enque == 1){
        wp_enqueue_script('jquery');
        wp_enqueue_script('wppt-icon', plugins_url('pricing-table/js/site/icon.js'));
        wp_enqueue_script('tiptipjs', plugins_url().'/pricing-table/js/site/jquery.tipTip.minified.js',array('jquery'));
    }
    wp_enqueue_style('tiptipcss', plugins_url().'/pricing-table/css/site/common.css');
}


function wppt_tiptip_init(){
    global $enque;

    if($enque == 1){
        ?>
        <script>
            jQuery(function(){
                jQuery(".wppttip").tipTip({defaultPosition:'bottom'});
            });
        </script>
    <?php
    }
}

function wppt_baseurl(){
    global $enque;

    if($enque == 1){
        ?>
        <script>
            var wppt_url = "<?php echo plugins_url('/pricing-table/'); ?>";
        </script>
    <?php
    }
}

function wppt_detect_shortcode(){
    global $post, $enque;

    $pattern = get_shortcode_regex();
    if(!is_object($post) || is_admin()) return;

    if( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'ahm-pricing-table', $matches[2] ) ){
        $enque = 1;
    }
}

function wppt_post_row_actions($actions, $post){
    if($post->post_type=='pricing-table'){
        $actions['clone'] = "<a style='color:#2D873F' href='".admin_url()."?task=wpptclone&clone={$post->ID}'>Clone</a>";
        $actions['export'] = "<a style='color:#2D873F' href='".admin_url()."?task=wpptexport&export={$post->ID}'>Export Table</a>";
    }
    return $actions;
}

function wppt_clone(){
    if(!isset($_GET['task']) || $_GET['task']!='wpptclone'||!is_admin()) return false;
    $pid = $_GET['clone'];
    $data = get_post_meta($pid, 'pricing_table_opt',true);
    $featured=  get_post_meta($pid, 'pricing_table_opt_feature',true);
    $feature_description =  get_post_meta($pid, 'pricing_table_opt_feature_description',true);
    $data_des = get_post_meta($pid, 'pricing_table_opt_description',true);
    $feature_name=  get_post_meta($pid, 'pricing_table_opt_feature_name',true);
    $package_name=  get_post_meta($pid, 'pricing_table_opt_package_name',true);
    $npid = wp_insert_post(array("post_title"=>'New Pricing Table','post_status'=>'draft','post_type'=>'pricing-table'));

    update_post_meta($npid,'pricing_table_for_post',$featured);
    update_post_meta($npid,'pricing_table_opt',$data);
    update_post_meta($npid,'pricing_table_opt_description',$data_des);
    update_post_meta($npid,'pricing_table_opt_feature',$featured);
    update_post_meta($npid,'pricing_table_opt_feature_name',$feature_name);
    update_post_meta($npid,'pricing_table_opt_feature_description',$feature_description);
    update_post_meta($npid,'pricing_table_opt_package_name',$package_name);
    header("location: post.php?post={$npid}&action=edit");
    die();

}

function wppt_export(){
    if( !isset($_GET['task']) || $_GET['task'] != 'wpptexport' || !is_admin()) return false;
    $pid = $_GET['export'];

    $pt_export['pricing_table_opt'] = get_post_meta($pid, 'pricing_table_opt',true);
    $pt_export['pricing_table_opt_feature'] =  get_post_meta($pid, 'pricing_table_opt_feature',true);
    $pt_export['pricing_table_opt_feature_description'] =  get_post_meta($pid, 'pricing_table_opt_feature_description',true);
    $pt_export['pricing_table_opt_description'] = get_post_meta($pid, 'pricing_table_opt_description',true);
    $pt_export['pricing_table_opt_feature_name'] =  get_post_meta($pid, 'pricing_table_opt_feature_name',true);
    $pt_export['pricing_table_opt_package_name'] =  get_post_meta($pid, 'pricing_table_opt_package_name',true);


    $data = serialize($pt_export);
    header("Content-Description: File Transfer");
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=\"pricing-table-{$pid}.txt\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . strlen($data));
    echo $data;
    die();
}

function wppt_import_form(){
    ?>
    <div style="padding: 10px;">
        <a class="ui-button ui-widget ui-state-default ui-corner-all" href="<?php echo admin_url('/?import_wppt_sample=1'); ?>">Import Sample Table</a>
        <p>OR</p>
        <form action="" enctype="multipart/form-data" method="post">
            <p style="border: 1px solid #0490b3;padding: 5px 8px;background: #fafafa;color: #0490b3;border-radius: 2px;">
                Import previously exported table that you have created somewhere else.
            </p>
            <p>Upload Import File:</p>
            <input type="file" name="importtable"> <br><br>
            <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all" value="Upload and Import">
        </form>
    </div>
    <?php
    die();
}

function wppt_import(){
    if(is_admin()&&isset($_FILES['importtable']) && is_uploaded_file($_FILES['importtable']['tmp_name'])){
    if(isset($_GET['post']))
        $post_id = $_GET['post'];
    else
        $post_id = wp_insert_post(array("post_title"=>'Imported Table','post_status'=>'draft','post_type'=>'pricing-table'));

    $data = file_get_contents($_FILES['importtable']['tmp_name']);
    @unlink($_FILES['importtable']['tmp_name']);
    $pt_import = unserialize($data);

    update_post_meta($post_id, "pricing_table_opt",$pt_import['pricing_table_opt']);
    update_post_meta($post_id, "pricing_table_opt_feature",$pt_import['pricing_table_opt_feature']);
    update_post_meta($post_id, "pricing_table_opt_feature_description",$pt_import['pricing_table_opt_feature_description']);
    update_post_meta($post_id, "pricing_table_opt_description",$pt_import['pricing_table_opt_description']);
    update_post_meta($post_id, "pricing_table_opt_feature_name",$pt_import['pricing_table_opt_feature_name']);
    update_post_meta($post_id,"pricing_table_opt_package_name",$pt_import['pricing_table_opt_package_name']);

    header("location: post.php?post={$post_id}&action=edit");
    die();
    }
}

function wppt_first_table(){

    $demo_table_dir = WPPT_PLUGINDIR.'/demo-table.txt';

    if(is_admin() && isset($_GET['import_wppt_sample']) && current_user_can('edit_posts') ){

        $post_id = wp_insert_post(array("post_title"=>'Imported Table','post_status'=>'draft','post_type'=>'pricing-table'));
        $data = file_get_contents($demo_table_dir);
        $pt_import = unserialize($data);

        update_post_meta($post_id, "pricing_table_opt",$pt_import['pricing_table_opt']);
        update_post_meta($post_id, "pricing_table_opt_feature",$pt_import['pricing_table_opt_feature']);
        update_post_meta($post_id, "pricing_table_opt_feature_description",$pt_import['pricing_table_opt_feature_description']);
        update_post_meta($post_id, "pricing_table_opt_description",$pt_import['pricing_table_opt_description']);
        update_post_meta($post_id, "pricing_table_opt_feature_name",$pt_import['pricing_table_opt_feature_name']);
        update_post_meta($post_id,"pricing_table_opt_package_name",$pt_import['pricing_table_opt_package_name']);

        header("location: post.php?post={$post_id}&action=edit");
        die();
    }
}
add_action('init', 'wppt_first_table');

add_action('plugins_loaded', 'wppt_load_textdomain');

function wppt_load_textdomain() {
  load_plugin_textdomain( 'wppt', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

function wppt_import_js(){
    ?>
    <script>
        jQuery(function() {
            jQuery('.wpptimport').on('click',function(){
                jQuery( "#imdialog").dialog();
                jQuery( "#imdialog" ).dialog( 'option', 'title','Import Pricing Table');
                jQuery( "#imdialog" ).dialog( 'option', 'width',540);
                jQuery( "#imdialog" ).dialog( "open" ).load("admin-ajax.php?action=wpptimport&modal=1&width=370&height=300");
                return false;
            });
        });
    </script>
    <?php
}

add_shortcode('ahm-pricing-table','wppt_table');

add_action('admin_head', 'wppt_import_js');
add_action( 'wp', 'wppt_detect_shortcode' );
add_action('wp_head', 'wppt_baseurl');
add_action('wp_footer', 'wppt_tiptip_init');
add_action('init', 'wppt_clone');
add_action('init', 'wppt_export');
add_action('init', 'wppt_import');
add_action('init', 'wppt_custom_init');

add_filter('post_row_actions', 'wppt_post_row_actions',10, 2);
add_filter('the_content','wppt_preview_table');
add_filter('manage_edit-pricing-table_columns', 'wppt_columns_struct', 10, 1);
add_action('manage_posts_custom_column', 'wppt_column_obj', 10, 1);
add_action('wp_ajax_wppt_save_shortcode', 'wppt_save_shortcode');
add_action('wp_ajax_wpptimport', 'wppt_import_form');
add_action('wp_enqueue_scripts', 'wppt_enqueue_scripts');
add_action('admin_enqueue_scripts', 'wppt_admin_enqueue_scripts');
