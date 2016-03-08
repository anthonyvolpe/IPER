<?php
session_start();
//Check required session data
global $wpdb;

$shipping_option = get_option('id_medical_shipping');
$medical_home_option = get_option('id_medical_home');

if(
    !$_SESSION
    || empty($_SESSION['iper_cart'])
    || empty($_SESSION['iper_cart']['products'])
){
  header("location: ".get_permalink( $medical_home_option )) ;
}

$aProduct=false;
foreach($_SESSION['iper_cart']['products'] as $single){
    $aProduct=$single;
}
    global $shipping_page;
    $shipping_page = '0';

/**
$rateplanID = $_COOKIE['sel_plan_id'];
$sel_product = $_COOKIE['sel_product'];
$sel_plan_and_price = $_COOKIE['sel_plan'];
$sel_plan_and_price=rtrim($sel_plan_and_price,',');
$sel_plan_and_price=explode(',',$sel_plan_and_price);

$sel_plan= $sel_plan_and_price[0];
$plan_price= $sel_plan_and_price[1];
$plan_price = str_replace('$', '', $plan_price);
 */

$rateplanID = intval($aProduct['plan']['plan_id']);
$sel_plan= $aProduct['plan']['plan_wp_id'];
$plan_price= $aProduct['plan']['plan_price'];

$aPlan=$aProduct['plan'];

/*$sel_upsell = $_COOKIE['sel_upsell'];
$sel_upsell=rtrim($sel_upsell,',');
$sel_upsell=explode(',',$sel_upsell);*/

$idsSelected=array();
$permanentAcc=array();
if(!empty($aPlan['accessories'])){
    foreach($aPlan['accessories'] as $key => $value ){
        foreach($value as $single){
            $idsSelected[]=$single["META_WP_ID"];
            if($single["permanent"]){
                $permanentAcc[]=$single["META_WP_ID"];
            }
        }
    }
}


/*
$accessories_cookier=$_COOKIE['iper_accessories_cart'];
if($accessories_cookier) {
    $accessories_cookier = json_decode(stripslashes($accessories_cookier));
    if(!empty($accessories_cookier)) {
        $idsSelected = array_map(function ($a) {
            return $a->ID;
        }, $accessories_cookier);
    }
}
*/

/* setCookie('iper_accessories_cart', '', time() + (3600), "/");*/
iper_hook_css();
get_header();
/*for($i=0; $i<count($sel_upsell)/2; $i++){
    $upsell_id[$i]= $sel_upsell[2*$i];
    $upsell_price[$i] = $sel_upsell[2*$i+1];
}*/

/*$args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'date',
    'order'            => 'ASC',
    'post_type'        => 'accessory'
);

$posts_array = get_posts( $args );*/

include_once('nav_bar.php');

/*$wp_sel_plan = get_post($sel_plan);
$wp_sel_plan_title = get_the_title($wp_sel_plan);*/

//chiamata database
$accessory_group_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_LIST(%d)", $rateplanID);
$groups_accessories = callDBStored($accessory_group_list_call);

/*if($wp_sel_plan_title == 'Annually'){
    $time = '<strong>/yr</strong>';
    $total_annual = $plan_price;
}

if($wp_sel_plan_title == 'Monthly'){
    $total_annual = $plan_price*12;
    $time = '<strong>/mo</strong>';
}

if($wp_sel_plan_title == 'Quarterly'){
    $total_annual = $plan_price*4;
    $time = '<strong>/4 mo</strong>';
}

if($wp_sel_plan_title == 'Semi-Annually'){
    $total_annual = ($plan_price)*2;
    $time = '<strong>/6 mo</strong>';
}*/
?>

<div class="select-container">
    <div class="container">
        <div class="row">
<!--<pre><?php
/*
        foreach($_SESSION['iper_cart']['products'] as $product){

            foreach($product['plan']['accessories'] as $accessory){

                var_dump($accessory);
            }
        }




    */?></pre>-->
            <div class="col-sm-8">
                <div class="title-page"><span>3 </span>Select Accessories</div>

                <?php
                foreach($groups_accessories as $group_accessories){

                    $group_ID = $group_accessories->idACCESSORY_GROUP;
                    $is_free = $group_accessories->IncludedWithOrder;
                    $accessory_single_selection = $group_accessories->LimitRestriction;

                    /*echo $is_free.$accessory_single_selection;*/
                    $permanent_='0';
                    if($is_free=='1' && $accessory_single_selection=='0'){
                        $permanent_='1';
                    }
                    ?>

                        <div class="select-subtitle"><h3><label><?php if($is_free != '0'){ echo 'INCLUDED'; }?><?php if(!$permanent_=='1'){ ?> Select <?php if($accessory_single_selection == '1'){ echo 'One'; }else{echo 'More';} ?> From The Following<?php }?></label></h3></div>
                            <div class="list-select" data-permanent="<?php echo $permanent_; ?>">


                                <div  data-accGroupID="<?php echo $group_ID ;?>" class="row" data-singleSelection="<?php echo $accessory_single_selection;?>">

                                    <?php
                                          $accessory_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_LIST(%d, %d)", $rateplanID, $group_ID);
                                          $accessories = callDBStored($accessory_list_call);
                                    ?>

                                    <?php foreach($accessories as $accessory){

                                        $accessory_wp_id = $accessory->META_WP_ID;
                                        $accessory_wp_post = get_post($accessory_wp_id);

                                        if($is_free=='0' && $accessory->Price){$accessory_price = $accessory->Price;}else{$accessory_price = '';}//verifico se l'accessorio è incluso nel rateplan o no

                                        $feat_image = wp_get_attachment_url(get_post_thumbnail_id($accessory_wp_post->ID));

                                        $accessory_wp_post->price = $accessory_price;
                                        ?>

                                        <div data-accID="<?php echo $accessory->idACCESSORY ;?>" class="card-select col-xs-6 col-sm-6 col-md-4 <?php echo (in_array($accessory_wp_id,$idsSelected)) ? ' active' : ''  ;?>" data-accGroup="<?php echo $group_ID;?>" data-product="<?php echo $aProduct['db_id'] ;?>" data-json='<?php echo json_encode($accessory);?>' data-accPrice="<?php echo $accessory_price; ?>">
                                            <!--<div class="accessory_price"><?php echo '$'.$accessory_price; ?></div><div class="card_title"><?php
                                                    echo get_the_title($accessory_wp_post); ?></div>-->
                                            <div class="card1" style="<?php if(isset($feat_image)&&(!empty($feat_image))){
                                                echo 'background: url('.$feat_image.'); background-size:cover;';}?>">
                                                <div class="checkbox"></div>
                                            </div>
                                            <a class="btn btn-grey1 btn-block">Select<?php echo (in_array($accessory_wp_id,$idsSelected)) ? "ed" : "";?></a>
                                        </div>

                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <div class="col-sm-4">
                    <?php include_once('cart.php');   ?>
                </div>
        </div>
    </div>
</div>


<?php
include($ABS_path . "/footer.php");
get_footer();
?>