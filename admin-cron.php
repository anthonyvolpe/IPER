<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 18:31
 */
?>
<br><br><br>
<div>
    <h1>Sync with SalesForce</h1>
    <button id="btSendCron" class="button button-primary">Sync now</button> <span style="line-height: 28px; margin-left: 15px;" id="cronMSG"></span><br><br>
    <textarea id="iper_print_debug_cron" rows="10" style="width: 80%;"></textarea>
</div>
<script type="text/javascript">
    jQuery(function(){
        jQuery("#btSendCron").click(function(e){
            jQuery("#cronMSG").html("Waiting, sync in progress...");
            jQuery("#btSendCron").attr("disabled","disabled");
            e.preventDefault();
            jQuery.get("<?php echo get_bloginfo('wpurl');?>/iper_cron",{},function(data){
                jQuery("#iper_print_debug_cron").html(data);
                jQuery("#btSendCron").removeAttr("disabled");
                jQuery("#cronMSG").html("");
            });
        });
    });
</script>