<?php
    $shortcode = array();
    $code = array();
    $shortcode = get_option("_wppt_shortcode");
    $code = get_option("_wppt_code");

    if(!empty($shortcode))
        foreach ($shortcode as $key => $value) {
            $shortcode[$key] = htmlspecialchars($value);
        }
?>

<style type="text/css">
   .inm{
       padding-left: 10px;
       color: #008000;
       font-weight: bold;
   }
   .promo{
       overflow:hidden;
       margin:5px;
       background: #fafafa;
       border: 1px solid #ccc;
       display: block;
       float: left;
       -webkit-border-radius: 6px;
       -moz-border-radius: 6px;
       border-radius: 6px;
   }
    .promo img{
        padding: 0;
        margin: 0;
        vertical-align: bottom;
    }
    input[type="text"]{
        box-shadow: none;
    }
    .sc-note{
        padding:10px;
        margin-bottom: 10px;
        border-radius: 0px;
        background: #F1F1F1;
        font-size: 15px;
        line-height: 25px;
        border: 1px dashed #ccc;
    }
    #addnew {
        margin-top: 20px;
    }
</style>

<div class="wrap">
    <h2><?php _e('Shortcode Generator', 'wppt') ?></h2>
    <div style="clear: both;"></div>
    <div id="poststuff" style="width: 790px; float: left;overflow: hidden;">
    <form action="" method="post" id="wptb">
        <input type="hidden" name="action" value="wppt_save_shortcode">
        <div style="margin-top: 10px;"></div>
        <div class="postbox " id="fb-like-options">

        <div title="Click to toggle" class="handlediv"><br></div>
        <h3 class="hndle"><span>Custom Shortcodes</span></h3>
        <div class="inside">
            <table id="wp-shortcode-table" class="wp-list-table widefat fixed posts" cellpadding="4" width="100%" cellspacing="15">
                <thead>
                    <tr>
                        <th width="150"><b><?php _e('Shortcode', 'wppt') ?></b></th>
                        <th><b><?php _e('Shortcode Value', 'wppt') ?></b></th>
                        <th width="70"><b><?php _e('Action', 'wppt') ?></b></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(is_array($shortcode)){
                    $i=0;
                    foreach($shortcode as $key => $value){
                        $i++;
                     ?>
                    <tr id="<?php echo "row_".$i;?>">
                        <td valign="top"><input size="15" type="text" name="code[<?php echo $key;?>]" value="<?php echo $code[$key];?>" /></td>
                        <td align="left"><input size="60" style="max-width:100%" type="text" name="shortcode[<?php echo $key;?>]" value="<?php echo $value;?>" /></td>
                        <td><img style='padding:8px;' class='deleterow' rel='<?php echo "row_".$i;?>' title='<?php _e('Delete this shortcode','wppt'); ?>' src='<?php echo plugins_url();?>/pricing-table/images/delete.png' /></td>
                    </tr>
                     <?php
                    }
                }
                ?>

                </tbody>
            </table>
            <br/>

            <div class="sc-note">
                <img src="<?php echo plugins_url('pricing-table/images/edit-desc.gif')?>"> <b>Shortcode</b> will be replaced by <b>Shortcode Value</b> while rendering table at front-end. Use <b>Image URL</b> or <b>any text</b> for shortcode value.
            </div>
            <div class="sc-note">
                <img src="<?php echo plugins_url('pricing-table/images/edit-desc.gif')?>"> You can use shortcode to insert image into table. Just put the image url in <b>Shortcode Value</b> and define a <b>Shortcode</b> for it.
            </div>

            <input class="button" type="button" name="addnew" id="addnew" value="<?php _e('Add New Shortcode','wppt'); ?>">
            <br clear="all">
        </div>
        </div>

        <br clear="all" />
        <br clear="all" />
        <input type="submit" id="btn" class="button-primary" value="<?php _e('Save Shortcodes','wppt'); ?>">
        <span id="loading" style="display: none;"><img src="images/loading.gif" alt=""> Saving...</span>
    </form>
    </div>

    <div style="float: left;width: 350px;margin-left: 30px;margin-top: 18px;">
        <a href="https://wpeden.com/" class="promo" target="_blank"><img src="<?php echo plugins_url('pricing-table/images/club.jpg'); ?>" /></a>
        <a href="https://wpeden.com/minimax-wordpress-page-layout-builder-plugin/" class="promo" target="_blank"><img src="<?php echo plugins_url('pricing-table/images/minimax.jpg'); ?>" /></a>
        <div style="clear: both;"></div>
    </div>
</div>

<script>

    jQuery('#addnew').live("click",function(){
        var stcd="code_"+new Date().getTime();
         jQuery('#wp-shortcode-table tbody').append('<tr  id="'+stcd+'"class="'+stcd+'"><td valign="top"><input size="15" type="text" name="code['+stcd+']" value="" /></td><td align="left"><input size="60" style="max-width:100%" type="text" name="shortcode['+stcd+']" value="" /></td><td align=center><img style="padding:8px;" class="deleterow" rel="'+stcd+'" title="Delete this row" src="<?php echo plugins_url();?>/pricing-table/images/delete.png" /></td></tr>');
    });

    jQuery('.deleterow').live('click', function() {
    if(confirm("Are you sure you want to delete?")){
            jQuery("#"+jQuery(this).attr('rel')).slideUp(function(){jQuery(this).remove();});
    }
    });

    jQuery('#wptb').submit(function(){
           jQuery(this).ajaxSubmit({
               'url': ajaxurl,
               'beforeSubmit':function(){
                   jQuery('#loading').fadeIn();
               },
               'success':function(res){
                   jQuery('#loading').fadeOut();
               }
           });
      return false;
      });

</script>
