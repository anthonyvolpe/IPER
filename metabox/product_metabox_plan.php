<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 09:48
 */

    global $_iper_product_meta_plan, $post, $wpdb;

    $product_db_id_call = $wpdb->prepare("CALL  IPER_MA_PRODUCT_GET_ID_BY_WPID(%d)", $post->ID);
    $product_db_id = callDBStored($product_db_id_call);

    $id= $product_db_id[0]->idPRODUCT;
    $product_rateplans_call = $wpdb->prepare("CALL  IPER_MA_RATEPLAN_LIST(%d)", $id);
    $product_rateplans = callDBStored($product_rateplans_call);


    foreach($_iper_product_meta_plan as $meta):

    $value=get_post_meta($post->ID, $meta['name'], true);

    if($meta['type']=='input-text'):
    include(sprintf("%s/res/input-textarea.php", dirname(__FILE__)));
    endif;
    ?>

    <br>
    <br>

<?php endforeach; ?>