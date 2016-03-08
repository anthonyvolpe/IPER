<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 09:45
 */
global $post;
    error_reporting(E_ALL);

const PRODUCT_POST_TYPE = "product";

global $_iper_product_meta;
global $_iper_product_meta_plan;

$_iper_product_meta  = array(
    array('name'=>'iper_product_testimonial_name','type'=>'input-text','title'=>'Testimonial Name'),
    array('name'=>'iper_product_testimonial_description','type'=>'input-textarea','title'=>'Testimonial Description'),
    array('name'=>'iper_product_testimonial_learnmore_link','type'=>'input-text','title'=>'Learn More Link')
);
$_iper_product_meta_plan  = array(
        array('name'=>'iper_rateplan_selected','type'=>'input-text','title'=>'Featured Rateplan')
    );

add_action('add_meta_boxes', 'add_meta_boxes_iper_product');

    function add_meta_boxes_iper_product(){

        add_meta_box(
            sprintf('iper_plugin_%s_section', PRODUCT_POST_TYPE),
            'Set Products Options',
            'add_inner_iper_meta_boxes',
            PRODUCT_POST_TYPE
        );

        add_meta_box(
            sprintf('iper_plugin_%s_section_plan', PRODUCT_POST_TYPE),
            'Set Select Rateplan Text',
            'add_inner_iper_meta_boxes_plan',
            PRODUCT_POST_TYPE
        );

    }



function add_inner_iper_meta_boxes(){
    include(sprintf("%s/metabox/%s_metabox.php", dirname(__FILE__), PRODUCT_POST_TYPE));
}
function add_inner_iper_meta_boxes_plan(){
    include(sprintf("%s/metabox/%s_metabox_plan.php", dirname(__FILE__), PRODUCT_POST_TYPE));
}


add_action('save_post', 'save_iper_product_post');
function save_iper_product_post($post_id){
    global $_iper_product_meta;
    global $_iper_product_meta_plan;
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

    if(isset($_POST['post_type']) && $_POST['post_type'] == PRODUCT_POST_TYPE && current_user_can('edit_post', $post_id)) {
        foreach($_iper_product_meta as $field){
            update_post_meta($post_id, $field['name'], $_POST[$field['name']]);
        }
        foreach($_iper_product_meta_plan as $field){
            update_post_meta($post_id, $field['name'], $_POST[$field['name']]);
        }
    }
}

?>