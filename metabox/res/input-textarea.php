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
<label for="<?php echo $meta['name']?>"><?php _e($meta['title'])?></label><br>
<textarea name="<?php echo $meta['name']?>" id="<?php echo $meta['name']?>" cols="50" rows="7" style="max-width: 95%"><?php echo $value?></textarea>