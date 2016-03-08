<?php
/*
Plugin Name: Iper Medical
Plugin URI: http://www.iperdesign.com
Description: Plugin for Medical Alert
Author: Iperdesign
Version: 1.0.0
Author URI: http://www.iperdesign.com
License: GPLv2 or later
Copyright 2016 otherplus (email: help@iperdesign.com)
*/

if (!defined('ABSPATH')) die("Access denied!");

include_once "IperSalseforceSync.php";


global $theme_url, $theme_path, $ABS_path;

$theme_url = get_bloginfo('wpurl') . '/wp-content/plugins/iper-medical/templates';
$theme_path = plugin_dir_path(__FILE__) . '/wp-content/plugins/iper-medical/templates';
$ABS_path = ABSPATH . '/wp-content/plugins/iper-medical/templates';

include_once('page-templater.php');
include_once('functions.php');

// define activate plugin
function iper_medical_activate(){

    include_once('hook.php');

    //include_once('init_db.php');

}
register_activation_hook(__FILE__,'iper_medical_activate');

// define deactivate plugin
function iper_medical_deactivate(){

}

register_deactivation_hook(__FILE__,'iper_medical_deactivate');


//[FP]
    function decimal_($number){
        $num = explode('.', $number);
        return $num[1];
    }

function iperGetCartJSONObject(){
    session_start();


    $cart=$_SESSION['iper_cart'];
    if(empty($cart) || empty($cart['products'])){
        return array();
    }

    $res=array_map(function($a){

        global $wpdb;

        $plan=$a['plan'];

        //$ratePlan=callDBStored($wpdb->prepare("call IPER_MA_RATEPLAN_GET(%d)",$plan['plan_id']),true);
        $wp_sel_plan_title=get_the_title($plan['plan_wp_id']);

        $time='';
        $total_annual=0;
        $plan_price=$plan['plan_price'];

        if($wp_sel_plan_title == 'Annually'){
            $time = '<strong>/yr</strong>';
            $total_annual = $plan_price;
        }

        if($wp_sel_plan_title == 'Monthly'){
            $total_annual = $plan_price*12;
            $time = '<strong>/mo</strong>';
        }

        if($wp_sel_plan_title == 'Quarterly'){
            $total_annual = $plan_price*4;
            $time = '<strong>/3 mo</strong>';
        }

        if($wp_sel_plan_title == 'Semi-Annually'){
            $total_annual = ($plan_price)*2;
            $time = '<strong>/6 mo</strong>';
        }

        $plan["wp_sel_plan_title"]=$wp_sel_plan_title;
        $plan["time"]=$time;

        $upsells=array();

        if(!empty($plan["upsells"])){
            foreach($plan["upsells"] as $single){

                if(strlen(decimal_($single['price']))=='1'){
                    $single['price'] .=0;}
                if(strlen(decimal_($single['price']))=='0'){
                    $single['price'] .=".00";}

                $upsells[]=array(
                    "ID"=>$single['db_id'],
                    "WP_ID"=>$single['wp_id'],
                    "price"=>$single['price'],
                    "title"=>get_the_title($single['wp_id'])
                );
            }
        }
        $plan["upsells"]=$upsells;

        $accessories=array();
        if(!empty($plan["accessories"])){
            foreach($plan["accessories"] as $key => $value ){

                $accGroupDB=callDBStored($wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_GET(%d)",$key),true);
                $IncludedWithOrder=intval($accGroupDB->IncludedWithOrder);  // se true price = 0 altrimenti aggiungi il prezzo dell'accesories al totale
                $accessories[$key]=array();
                foreach($value as $single){

                    if($IncludedWithOrder==1){
                        $single['Price']="Free";
                    }
                    $obj=$single;
                    $obj["title"]=get_the_title($single['META_WP_ID']);
                    $obj["price"]=$single['Price'];
                    $accessories[$key][]=$obj;
                }
            }
        }

        $plan["accessories"]=$accessories;

        $res=array(
            "db_id"=>$a['db_id'],
            "wp_id"=>$a['wp_id'],
            "title"=>get_the_title($a['wp_id']),
            "plan"=>$plan
        );

        return array("product"=>$res,"total"=>getCartTotal(),"total_annual"=>$total_annual);
    },$cart['products']);

    return $res;
}


function getCartTotal(){

    $total=0;
    global $wpdb;
    if(isset($_SESSION['iper_cart']) && isset($_SESSION['iper_cart']['products'])){

        foreach($_SESSION['iper_cart']['products'] as $product){

            $total+=floatval($product['plan']['plan_price']);

            if(!empty($product['plan']['upsells'])){
                foreach($product['plan']['upsells'] as $upsells){
                    //price
                    $total+=floatval($upsells['price']);

                    if(strlen(decimal_($total))=='1'){
                        $total .=0;}
                    if(strlen(decimal_($total))=='0'){
                        $total .=".00";}
                }
            }

            if(!empty($product['plan']['accessories'])){

                foreach($product['plan']['accessories'] as $key => $value){
                    //price
                    $accGroupDB=callDBStored($wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_GET(%d)",$key),true);
                    $IncludedWithOrder=intval($accGroupDB->IncludedWithOrder);  // se true price = 0 altrimenti aggiungi il prezzo dell'accesories al totale

                    //print_r($value);
                    //wp_die(json_encode($accGroupDB));

                    if($IncludedWithOrder=='0'){
                        foreach($value as $accessories ){
                            $total+=floatval($accessories['Price']);

                            if(strlen(decimal_($total))=='1'){
                                $total .=0;}
                            if(strlen(decimal_($total))=='0'){
                                $total .=".00";}


                        }
                    }
                }
            }
        }
    }

    return $total;
}

add_action('wp_ajax_iper_remove_accessory_to_cart','iper_remove_accessory_to_cart');
    add_action('wp_ajax_nopriv_iper_remove_accessory_to_cart','iper_remove_accessory_to_cart');
function iper_remove_accessory_to_cart(){
    session_start();

    if(!$_POST['data'] || !$_POST['data']){
        return ;
    }

    $accGroup=$_POST['data']['groupID'];
    $accessory=intval($_POST['data']['accID']);
    $product=intval($_POST['data']['product']);
    $plan=$_SESSION['iper_cart']['products'][$product]['plan'];

    if(!empty($plan['accessories'][$accGroup])){
        $arr=array();
        foreach($plan['accessories'][$accGroup] as $single){
            if($accessory!=intval($single['idACCESSORY'])){
                $arr[]=$single;
            }
        }
        $plan['accessories'][$accGroup]=$arr;
        $_SESSION['iper_cart']['products'][$_POST['data']['product']]['plan']=$plan;
    }

    //calcolate total
    $_SESSION['iper_cart']['total']=getCartTotal();
    $res=array("status"=>1,"cart"=>iperGetCartJSONObject());
    echo json_encode($res);
    wp_die();

}

add_action( 'wp_ajax_iper_remove_product_plan_accessories_to_cart', 'iper_remove_product_plan_accessories_to_cart' );
    add_action( 'wp_ajax_nopriv_iper_remove_product_plan_accessories_to_cart', 'iper_remove_product_plan_accessories_to_cart' );
function iper_remove_product_plan_accessories_to_cart(){
    session_start();

    if(!$_POST['data'] || !$_POST['data']['accessories']){
        return ;
    }

    $accGroup=intval($_POST['data']['accGroup']);
    $accessories=$_POST['data']['accessories'];
    $product=intval($_POST['data']['product']);
    $plan=$_SESSION['iper_cart']['products'][$product]['plan'];
    if(!empty($plan['accessories'][$accGroup])){
        $arr=array();
        foreach($plan['accessories'][$accGroup] as $single){
            if($accessories['idACCESSORY']!=$single['idACCESSORY']){
                $arr[]=$single;
            }
        }
        $plan['accessories'][$accGroup]=$arr;
        $_SESSION['iper_cart']['products'][$_POST['data']['product']]['plan']=$plan;
    }

    //calcolate total
    $_SESSION['iper_cart']['total']=getCartTotal();
    $res=array("status"=>1,"cart"=>iperGetCartJSONObject());
    echo json_encode($res);
    wp_die();
}

add_action( 'wp_ajax_iper_add_product_plan_accessories_to_cart', 'iper_add_product_plan_accessories_to_cart' );
    add_action( 'wp_ajax_nopriv_iper_add_product_plan_accessories_to_cart', 'iper_add_product_plan_accessories_to_cart' );
function iper_add_product_plan_accessories_to_cart(){
    session_start();

    /*$_SESSION['iper_cart']['products']['accessories']*/

    if(!$_POST['data'] || !$_POST['data']['accessories']){
        return ;
    }

    $accessories=$_POST['data']['accessories'];
    $accGroup=intval($_POST['data']['accGroup']);

    global $wpdb;

    $plan=$_SESSION['iper_cart']['products'][$_POST['data']['product']]['plan'];
    $accGroupDB=callDBStored($wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_GET(%d)",$accGroup,true));
    $LimitRestriction=intval($accGroupDB->LimitRestriction);    // se true solo un accessorio può essere selezionato, altrimenti aggiungi
    $IncludedWithOrder=intval($accGroupDB->IncludedWithOrder);  // se true price = 0 altrimenti aggiungi il prezzo dell'accesories al totale

    if($IncludedWithOrder){
        $accessories['Price']="Free";
        $accessories['price']="Free";
    }

    $found=false;
    if(!empty($plan['accessories'][$accGroup])){
        foreach($plan['accessories'][$accGroup] as $single){
            if($accessories['idACCESSORY']==$single['idACCESSORY']){
                $found=true;
                break ;
            }
        }
    }

    if(!$found){
        if($LimitRestriction==1){
            $plan['accessories'][$accGroup]=array($accessories);
        }else{
            $plan['accessories'][$accGroup][]=$accessories;
        }
    }

    $_SESSION['iper_cart']['products'][$_POST['data']['product']]['plan']=$plan;
    $_SESSION['iper_cart']['total']=getCartTotal();

    $res=array("status"=>1,"cart"=>iperGetCartJSONObject());
    echo json_encode($res);
    wp_die();
}

add_action( 'wp_ajax_iper_set_cookie_product_plan', 'iper_set_cookie_product_plan' );
    add_action( 'wp_ajax_nopriv_iper_set_cookie_product_plan', 'iper_set_cookie_product_plan' );
function iper_set_cookie_product_plan(){

    global $wpdb;

    session_start();
    if(!$_POST['data']){
        return ;
    }


    $accessory_group_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_LIST(%d)", $_POST['data']['plan_id']);
    $groups_accessories = callDBStored($accessory_group_list_call);

    $accessories=array();

    foreach($groups_accessories as $group_accessory){
        $group_ID = $group_accessory->idACCESSORY_GROUP;
        $group_INCLUDED = $group_accessory->IncludedWithOrder;
        $group_LIMIT = $group_accessory->LimitRestriction;

        if($group_LIMIT == '0' && $group_INCLUDED == '1'){

            $accessory_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_LIST(%d, %d)", $_POST['data']['plan_id'], $group_ID);
            $single = callDBStored($accessory_list_call,false,ARRAY_A);

            $single=array_map(function($a){
                $a['permanent']=true;
                return $a;
            },$single);

            /*$accessories = $accessories->idACCESSORY;*/
            $accessories[$group_ID] =$single;


        }
    }

    $product_id=intval($_POST['data']['product_id']);
    $plan_price=str_replace("$","",$_POST['data']['plan_price']);
    $plan_price=floatval($plan_price);

    if(strlen(decimal_($plan_price))=='1'){
        $plan_price .=0;}
    if(strlen(decimal_($plan_price))=='0'){
        $plan_price .=".00";}

    if(!isset($_SESSION['iper_cart'])){
        $_SESSION['iper_cart']=array();
    }

    $_SESSION['iper_cart']['products']=array();

    /**
    if(!$_SESSION['iper_cart']['products']){
        $_SESSION['iper_cart']['products']=array();
    }**/



    $_SESSION['iper_cart']['products'][$product_id]=array(
        "db_id"=>$product_id,
        "wp_id"=>intval($_POST['data']['product_wp_id']),
        "plan"=>array(
            "plan_id"=>intval($_POST['data']['plan_id']),
            "plan_wp_id"=>intval($_POST['data']['plan_wp_id']),
            "plan_price"=>$plan_price,
            "accessories"=>$accessories
        )
    );

    if(strlen(decimal_($plan_price))=='1'){
        $plan_price .=0;}
    if(strlen(decimal_($plan_price))=='0'){
        $plan_price .=".00";}

    $_SESSION['iper_cart']['total']=floatval($plan_price);

    echo json_encode($_SESSION['iper_cart']);
    wp_die();

}

    add_action( 'wp_ajax_iper_set_cookie_product_plan_upsell', 'iper_set_cookie_product_plan_upsell' );
    add_action( 'wp_ajax_nopriv_iper_set_cookie_product_plan_upsell', 'iper_set_cookie_product_plan_upsell' );
function iper_set_cookie_product_plan_upsell(){
    session_start();
    if(!$_POST['data']){
        return ;
    }

    $product_id=intval($_POST['data']['product']);
    $plan_id=intval($_POST['data']['plan']);

    if(
        !isset($_SESSION['iper_cart'])
        || !$_SESSION['iper_cart']['products']
        || ! $_SESSION['iper_cart']['products'][$product_id]
    ){
        return ;
    }



    $price=floatval($_POST['data']['upsellPrice']);

    if(strlen(decimal_($plan_price))=='1'){
        $plan_price .=0;}
    if(strlen(decimal_($plan_price))=='0'){
        $plan_price .=".00";}

    $upsellWPID=intval($_POST['data']['upsell']);
    $upsellID=intval($_POST['data']['upsellID']);

    if(!empty($_SESSION['iper_cart']['products'][$product_id]["plan"]["upsells"])){
        foreach($_SESSION['iper_cart']['products'][$product_id]["plan"]["upsells"] as &$single){
            if($single["db_id"]==$upsellID){
                unset($single);
            }
        }
    }

    $_SESSION['iper_cart']['products'][$product_id]["plan"]["upsells"][]=array(
        "db_id"=>$upsellID,
        "price"=>$price,
        "wp_id"=>$upsellWPID
    );


    $_SESSION['iper_cart']['total']=getCartTotal();
    //$_SESSION['iper_cart']['total']=$_SESSION['iper_cart']['total']+floatval($price);

    echo json_encode($_SESSION['iper_cart']);
    wp_die();
}
//END [FP]

add_action( 'wp_ajax_iper_add_to_cart', 'iper_add_to_cart' );
add_action( 'wp_ajax_iper_remove_to_cart', 'iper_remove_to_cart' );
add_action( 'wp_ajax_iper_set_cookie_plan', 'iper_set_cookie_plan' );
add_action( 'wp_ajax_iper_set_cookie_product', 'iper_set_cookie_product' );
add_action( 'wp_ajax_iper_set_cookie_upsell', 'iper_set_cookie_upsell' );

    add_action( 'wp_ajax_nopriv_iper_add_to_cart', 'iper_add_to_cart' );
    add_action( 'wp_ajax_nopriv_iper_remove_to_cart', 'iper_remove_to_cart' );
    add_action( 'wp_ajax_nopriv_iper_set_cookie_plan', 'iper_set_cookie_plan' );
    add_action( 'wp_ajax_nopriv_iper_set_cookie_product', 'iper_set_cookie_product' );
    add_action( 'wp_ajax_nopriv_iper_set_cookie_upsell', 'iper_set_cookie_upsell' );




    function iper_set_cookie_product(){

        $product =  $_POST['product'] ;
        $product_id =  $_POST['product_fk_id'] ;
        $cookie_name = "sel_product";
        $cookie_name2 = "sel_product_fk_id";
        $cookie_value = $product;
        $cookie_value2 = $product_id;
        setcookie($cookie_name, $cookie_value, time() + (3600), "/");
        setcookie($cookie_name2, $cookie_value2, time() + (3600), "/");


        echo json_encode($_COOKIE);
    }

    function iper_set_cookie_plan(){

        $plan =  $_POST['plan'] ;
        $plan2 =  $_POST['plan_id'] ;

        $cookie_name = "sel_plan";
        $cookie_name2 = "sel_plan_id";
        $cookie_value = $plan;
        $cookie_value2 = $plan2;
        setcookie($cookie_name, $cookie_value, time() + (3600), "/");
        setcookie($cookie_name2, $cookie_value2, time() + (3600), "/");

        echo json_encode($_COOKIE);
    }

    function iper_set_cookie_upsell(){

        $product =  $_POST['upsell'] ;
        $cookie_name = "sel_upsell";
        $curr_value='';
        if($product) {
            $curr_value = $_COOKIE[$cookie_name] . $product . ',';
        }

        setcookie($cookie_name, $curr_value , time() + (3600), "/");

        //$cookie_final_value = $_COOKIE["sel_upsell"].','.$cookie_value;
/*
        if($product){

            setcookie($cookie_name, $cookie_final_value , time() + (3600), "/");}else{  setcookie($cookie_name, '' , time() + (3600), "/");
        }*/

        echo json_encode($_COOKIE[$cookie_name]);
    }


function iper_add_to_cart() {

    $product =  $_POST['product'];


    $cookie_name = "iper_accessories_cart";
    $currentValue=$_COOKIE[$cookie_name];

    if(empty($currentValue)){
        $currentValue=array();
    }else{
        $currentValue=json_decode(stripslashes($currentValue));
    }

    if(!is_array($currentValue)){
        $currentValue=array();
    }

    $currentValue[]=$product;

    $cookie_value = json_encode($currentValue);

    setcookie($cookie_name, $cookie_value, time() + (3600), "/");
    setcookie($cookie_name2, $cookie_value2, time() + (3600), "/");

    echo json_encode(array(
        "status"=>1,
        "cart"=>array(
            "products"=> $currentValue,
            "price"=>13,
            "Shipping"=>"free"
            )
         ));

    wp_die(); // this is required to terminate immediately and return a proper response
}

function iper_remove_to_cart(){
    $product =  $_POST['product'] ;
    $appo="";
    $cookie_name = "iper_accessories_cart";
    $currentValue=$_COOKIE[$cookie_name];



    if($currentValue){
        $currentValue=json_decode(stripslashes($currentValue));
        $i=0;
        $found=false;
        while($i<count($currentValue) && !$found){
            $appo.=" - ".$i;
            if(intval($currentValue[$i]->ID)==intval($product["ID"])){
                $found=true;
            }else{
                $i++;
            }
        }

        if($found){
            unset($currentValue[$i]);
            $currentValue = array_values($currentValue);
        }
    }else{
        $currentValue=array();
    }

    $cookie_value=json_encode($currentValue);
    setcookie($cookie_name, $cookie_value, time() + (3600), "/");

    echo json_encode(array(
        "status"=>1,
        "eliminato"=>$i,
        "cart"=>array(
            "products"=> $currentValue,
            "tax"=>13,
            "Shipping"=>"free"
        )
    ));

    wp_die(); // this is required to terminate immediately and return a proper response
}

function iper_cron_loaded(){
    if ( empty( $GLOBALS['wp']->query_vars['iper_backend_route'] ) )
        return;

    // Allow for a plugin to insert a different class to handle requests.

    include_once(plugin_dir_path(__FILE__).'iper-cron.php');

    die();
}

function iper_cron_init(){

    iper_backend_register_rewrites();
    global $wp;
    $wp->add_query_var( 'iper_backend_route' );
}

function  iper_backend_register_rewrites(){


    $pre='iper_cron';
    $pre=str_replace("/","",$pre);

    add_rewrite_rule( '^' . $pre . '/?$','index.php?iper_backend_route=/','top' );
    add_rewrite_rule( '^' . $pre . '(.*)?','index.php?iper_backend_route=$matches[1]','top' );
}

    add_action( 'template_redirect',  'iper_cron_loaded', -100 );
    add_action( 'init', 'iper_cron_init' );

?>