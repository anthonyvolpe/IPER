<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 09:48
 */

global $_iper_product_meta,$post;
foreach($_iper_product_meta as $meta):

    $value=get_post_meta($post->ID, $meta['name'], true);

    if($meta['type']=='input-text'):
        include(sprintf("%s/res/input-text.php", dirname(__FILE__)));
    elseif($meta['type']=='input-textarea'):
        include(sprintf("%s/res/input-textarea.php", dirname(__FILE__)));
    endif;
    ?>

    <br>
    <br>

<?php endforeach; ?>
