<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 09:52
 */


?>
<label for="<?php echo $meta['name']?>"><?php _e($meta['title'])?></label>
<select id="<?php echo $meta['name'];?>" name="<?php echo $meta['name'];?>" class="form-control selectpicker show-tick" data-live-search="true" title="<?php print_r($product_rateplans); ?>">
    <?php
                foreach($product_rateplans as $product_rateplan){
                    echo "<option value='".$product_rateplan->Term  ."'>".$product_rateplan->Term."</option>";}
            ?>
</select>
<label for="Selected Plan"><strong><?php echo ' '.get_post_meta($post->ID, 'Rateplan_selected',true); ?></strong></label>


