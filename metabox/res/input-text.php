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
<input style="width: 300px;" type="text" name="<?php echo $meta['name']?>" id="<?php echo $meta['name']?>" value="<?php echo $value?>">