
<?php

session_start();

if(!$_SESSION['iper_cart'] || empty($_SESSION["iper_cart"]["products"]) /*|| !isset($_POST) || empty($_POST)*/){
    wp_safe_redirect(get_bloginfo('siteurl'));
    die();
}

iper_hook_css();
get_header();
include_once('nav_bar.php');
global $wpdb;

$orderID=md5("iperdesign|".microtime());

$total=getCartTotal();

$_SESSION["md_profile"]=array(
    "first_name"=>$_POST["firstName_billing"],
    "last_name"=>$_POST["lastName_billing"],
);

/**
 * @param $AccessoryID
 * @param $Quantity
 * @return array
 */
function createAccessory($AccessoryID,$Quantity){


    $Accessory= array(
         /*"AccessoryName" => null
        ,"AccessoryCode" => null*/
        "AccessoryID" => $AccessoryID
        /*,"Price" => null*/
        ,"Quantity" => $Quantity
        /*,"IsAvailable" => null*/
    );



    return $Accessory;
}

/**
 * @param $PaymentType
 * @param $CardholderName
 * @param $CardType
 * @param $CardNumber
 * @param $CVV
 * @param $AccountHolderName
 * @param $AccountType
 * @param $AccountNumber
 * @param $RoutingNumber
 * @return array
 */

function createPaymentInformation($PaymentType,$CardholderName,$CardType,$CardNumber,$CVV,$AccountHolderName=NULL,$AccountType=NULL,$AccountNumber=NULL,
                                  $RoutingNumber=NULL){

    if($CardType=="American" ){
        $CardType = "American Express";
    }
    if($CardType=="Master" ){
        $CardType = "MasterCard";
    }


    $PaymentInformation = array(

         "PaymentType" => $PaymentType
        ,"CardholderName" => $CardholderName
        ,"CardType" => $CardType
        ,"CardNumber" => $CardNumber
        /*,"CVV" => $CVV*/
        /*,"AccountHolderName" => $AccountHolderName
        ,"AccountType" => $AccountType
        ,"AccountNumber" => $AccountNumber
        ,"RoutingNumber" => $RoutingNumber*/

    );
    return $PaymentInformation;
}

$objPaymentInformation=createPaymentInformation("Credit Card",$_POST["creditcard_name"], $_POST["creditcard_type"], $_POST["creditcard_number"], $_POST["creditcard_cvv"]);

/**
 * @param $ProductID
 * @param $Quantity
 * @param $RatePlanID
 * @param $PromotionID
 * @param $Accessories
 * @return array
 */
function createOrderProduct($ProductID,$Quantity,$RatePlanID,$PromotionID=null,$Accessories=null){

    $OrderProduct= array(

        "ProductID" => $ProductID
    ,   "Quantity" => $Quantity
    ,   "RatePlanID" => $RatePlanID
    /*,   "PromotionID" => $PromotionID*/
    ,   "Accessories" => $Accessories

    );

    if(is_null($Accessories)){
        unset($OrderProduct["Accessories"]);
    }

    return $OrderProduct;
}

$aProduct=false;
foreach($_SESSION["iper_cart"]["products"] as $single){
    $aProduct=$single;
}



$accessories=NULL;
$arrAccessories=array();
if(!empty($aProduct['plan']['accessories'])){
    $accessories=array();
    foreach($aProduct['plan']['accessories'] as $key => $value){
        foreach($value as $single){
            $accessories[]=createAccessory($single["AccessoryID"],1);
            $arrAccessories[]=$single;
        }
    }
}

$aRatePlan=callDBStored($wpdb->prepare("call IPER_MA_RATEPLAN_GET(%d)",$aProduct['plan']['plan_id']),true);
$dbProduct=callDBStored($wpdb->prepare("call IPER_MA_PRODUCT_GET(%d)",$aProduct['db_id']),true);

$orderProducts=array(
    createOrderProduct($dbProduct->ProductID,1,$aRatePlan->RatePlanID,NULL,$accessories)
);

$arrUpsells=array();

if(!empty($aProduct['plan']['upsells'])){
    foreach($aProduct['plan']['upsells'] as $single){

        $aUpsell=callDBStored($wpdb->prepare("call IPER_MA_UPSELL_GET(%d)",$single['db_id']),true);
        $arrUpsells[]=$aUpsell;
        $orderProducts[]=createOrderProduct($aUpsell->ProductID,1,$aRatePlan->RatePlanID);
    }
}

/**
 * @param $Name
 * @param $Phone
 * @param $Street1
 * @param $Street2
 * @param $City
 * @param $State
 * @param $PostalCode
 * @param $Country
 * @return array
 */
function createAddress($Name,$Phone,$Street1,$Street2,$City,$State,$PostalCode,$Country){



    $Address = array(

        "Name" => $Name
    ,   "Phone" => $Phone
    ,   "Street1" => $Street1
    ,   "Street2" => $Street2
    ,   "City" => $City
    ,   "State" => $State
    ,   "PostalCode" => $PostalCode
    ,   "Country" => $Country

    );


    return $Address;
}



    function fix_dash($num){
        $number = $num;
        $number = str_replace("-","",$number); //se ci sono dash li rimuovo
        $number = str_split($number); //trasformo in array
        $number1 = array_slice($number, 0, 3);
        array_push($number1, '-');
        $number2 = array_slice($number, 3, 3);
        array_push($number2, '-');
        $number3 = array_slice($number, 6, 4);
        $number = array_merge($number1, $number2, $number3);
        $number = implode('', $number);
        return $number;
    }

    $phone_shipping = $_POST["phone_shipping"];
    $phone_billing = $_POST["phone_billing"];

    $phone_shipping = fix_dash($phone_shipping);
    $phone_billing = fix_dash($phone_billing);



$objAddressShipping=createAddress(rtrim($_POST["firstName_shipping"])."%".rtrim($_POST["lastName_shipping"]),$phone_shipping,
    $_POST["address1_shipping"],
    $_POST["address2_shipping"],$_POST["city_shipping"],$_POST["state_shipping"],$_POST["zip_shipping"],$_POST["country_shipping"]);
$objAddressBilling=createAddress(rtrim($_POST["firstName_billing"])."%".rtrim($_POST["lastName_billing"]),$phone_billing,$_POST["address1_billing"],
    $_POST["address2_billing"],$_POST["city_billing"],$_POST["state_billing"],$_POST["zip_billing"],$_POST["country_billing"]);


/**
 * @param $CustomerFirstName
 * @param $CustomerLastName
 * @param $CustomerEmail
 * @param $CustomerPhoneNumber
 * @param $DiscountCode
 * @param $MarketingCampaign
 * @param $ShippingID
 * @param $PaymentInformation
 * @param $ShippingInformation
 * @param $BillingInformation
 * @param $OrderProducts
 * @return array
 */


function createOrder($CustomerFirstName,$CustomerLastName,$CustomerEmail,$CustomerPhoneNumber,$DiscountCode,$MarketingCampaign,$ShippingID,
                     $PaymentInformation,$ShippingInformation,$BillingInformation,$OrderProducts){
    $Order = array(

        "CustomerFirstName" => $CustomerFirstName
    ,   "CustomerLastName" => $CustomerLastName
    ,   "CustomerEmail" => $CustomerEmail
    ,   "CustomerPhoneNumber" => $CustomerPhoneNumber
    /*,   "DiscountCode" => $DiscountCode
    ,   "MarketingCampaign" => $MarketingCampaign*/
    ,   "ShippingID" => $ShippingID
    ,   "PaymentInformation" => $PaymentInformation
    ,   "ShippingInformation" => $ShippingInformation
    ,   "BillingInformation" => $BillingInformation
    ,   "OrderProducts" => $OrderProducts
    );
    return  $Order;
}

$objOrder=createOrder($_POST["firstName_billing"], $_POST["lastName_billing"] , $_POST["email_billing"], $phone_billing, NULL, NULL, $_POST["shippingType"], $objPaymentInformation, $objAddressShipping,$objAddressBilling,$orderProducts);






$idShipping=$_POST["shippingType"];
$shippingPrice=0;

    $rateplan_call = $wpdb->prepare("CALL IPER_MA_RATEPLAN_LIST(%d)", $aProduct ['db_id']);
    $rate_plans = callDBStored($rateplan_call);

    foreach($rate_plans as $plan){
        if($plan->idRATEPLAN==$aProduct['plan']['plan_id']){
            $plan_term = $plan->Term;
        }
    }

$list=callDBStored("CALL IPER_MA_SHIPPING_LIST()");
    if(!empty($list)){
        foreach($list as $single){
            if($single->ShippingID==$idShipping){
                $shippingPrice=floatval($single->Price);
            }
    }

}
    function decimal($number){
        $num = explode('.', $number);
        return $num[1];
    }

    if(strlen(decimal($shippingPrice))=='1'){
        $shippingPrice .=0;}
    if(strlen(decimal($shippingPrice))=='0'){
        $shippingPrice .=".00";}

/**
 * @param $RequestHeader
 * @param $RequestBody
 * @param $ResumeID
 * @return array
 */


/**
 * @return array
 */
function createRequestHeader(){

    $RequestHeader =  array(

        "RequestID" =>"Request_".microtime()

    );
    return $RequestHeader;

}

function createOrderRequest($order,$request){

    return array(
        "RequestHeader"=>$request,
        "RequestBody"=>$order
    );
}

    function createProfile($FirstName, $LastName, $Country_shipping, $Street1, $Street2, $City, $State, $PostalCode, $Mail, $Phone){

        $Profile = array(
        "Name" => $FirstName
    ,   "LastName" =>  $LastName
    ,   "Country" =>  $Country_shipping
    ,   "Street1" => $Street1
    ,   "Street2" => $Street2
    ,   "City" => $City
    ,   "State" => $State
    ,   "PostalCode" => $PostalCode
    ,   "Mail" => $Mail
    ,   "Phone" => $Phone
        );

        return $Profile;
    }

    $objProfile=createProfile($_POST["firstName_shipping"], $_POST["lastName_shipping"], $_POST["country_shipping"], $_POST["address1_shipping"],$_POST["address2_shipping"], $_POST["city_shipping"],$_POST["state_shipping"],$_POST["zip_shipping"], $_POST['email_shipping'], $phone_shipping );

    $_SESSION['shipping_info']=$objProfile;

    $req=createOrderRequest($objOrder,createRequestHeader());
    $sf=new IperSalseforceSync(IperSalseforceSync::kACTION_ORDER_CREATE,$req);
    $res=json_decode(json_decode($sf->sendRequest()));

    /*if(!isset($res->ResponseBody) || !$res->ResponseBody || $res->ResponseBody =''){
        $res=json_decode(json_decode($sf->sendRequest()));
    }*/
    if(isset($res->ResponseBody) && $res->ResponseBody !=''){
        $res_text =  'Success';
    }
    if(!isset($res->ResponseBody) || $res->ResponseBody ==''){
        $res_text = 'Failed';
    }

    $_SESSION['Opportunity_ID_Profile_Page'] = $res->ResponseBody;

    if($_SERVER["REMOTE_ADDR"]=='79.3.196.80'):
?>

<?php endif; ?>

    <div class="container_">
        <span class="_result"><?php echo $res_text; ?></span>
        <button class="tmp_btn" data-target="#responses" data-toggle="collapse" data-responses = "<?php echo $res_text; ?>">See the Response</button>
        <div id="responses" class="collapse">
            <div class="req">
                <h2>Request</h2>
                <?php echo json_encode($req); ?>
            </div>
            <div class="res">
                <h2>Response</h2>
                <?php echo json_encode($res); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <hr>
                <div class="header-thank">
                    <h1 class="big-title-page">Order Details:</h1>
                    <p>Ordered On: <?php echo date("F jS, Y") ;?> | Order# <span class="order_id"><?php echo $orderID;?></span><!--  <a href="#" title="Print page"><i class="fa fa-print"></i></a></p> -->
                </div>
                <hr>
            </div>
        </div>
        <div id="iperOderDetailFinalSummary" class="row detail-order-row">
           <div class="row">
            <div class="col-sm-4">
                <h2 class="title-page">Shipping Address</h2>

                <p><strong>Name: </strong><?php echo str_replace( "%"," ",$objAddressShipping['Name']); ?></p>
                <p><strong>Address 1: </strong><?php echo $objAddressShipping['Street1']; ?></p>
                <p><strong>Address 2: </strong><?php echo $objAddressShipping['Street2']; ?></p>
                <p><strong>City: </strong><?php echo $objAddressShipping["City"]; ?></p>
                <p><strong>State: </strong><?php echo $objAddressShipping["State"];?></p>
                <p><strong>Postal Code: </strong><?php echo $objAddressShipping['PostalCode']; ?></p>
                <p><strong>Phone: </strong><?php echo $objAddressShipping['Phone']; ?></p>
                <p><strong>Email: </strong><?php echo $objOrder['CustomerEmail']; ?></p>
            </div>
            <div class="col-sm-4">
                <h2 class="title-page">Payment Details</h2>
                <p><?php echo $objPaymentInformation["CardType"]; ?> ***<?php echo substr($objPaymentInformation["CardNumber"],strlen($objPaymentInformation["CardNumber"])-4,4);?></p>
            </div>

               <?php
               if(get_the_title($aRatePlan->META_WP_ID) == 'Annually'){
               $time = '<strong>/yr</strong>';
               $total_annual = $plan_price;
               }

               if(get_the_title($aRatePlan->META_WP_ID) == 'Monthly'){
               $total_annual = $plan_price*12;
               $time = '<strong>/mo</strong>';
               }

               if(get_the_title($aRatePlan->META_WP_ID) == 'Quarterly'){
               $total_annual = $plan_price*4;
               $time = '<strong>/3 mo</strong>';
               }

               if(get_the_title($aRatePlan->META_WP_ID) == 'Semi-Annually'){
               $total_annual = ($plan_price)*2;
               $time = '<strong>/6 mo</strong>';
               }?>

            <div class="col-sm-4">
                <h2 class="title-page">Order Summary</h2>
                <div class="bottom-space">
                    <?php
                        $rateplan_price =  $aRatePlan->Price;
                        if(strlen(decimal($rateplan_price))=='1'){
                            $rateplan_price .=0;}
                        if(strlen(decimal($rateplan_price))=='0'){

                            $rateplan_price .=".00";}
                    ?>
                    <p><strong><?php echo get_the_title($dbProduct->META_WP_ID); ?></strong></p>
                    <p><?php echo get_the_title($aRatePlan->META_WP_ID); ?><span class="price pull-right">$<?php echo $rateplan_price.$time; ?></span></p>
                </div>

                <?php if(!empty($arrUpsells)){ ?>
                <p><strong>Options</strong></p>
                 <div class="bottom-space">

                        <?php foreach($arrUpsells as $single): ?>

                            <p><?php echo get_the_title($single->META_WP_ID); ?>
                                <span class="price_o pull-right">$<?php

                                        if(strlen(decimal($single->Price))=='1'){
                                            $single->Price .=0;}
                                        if(strlen(decimal($single->Price))=='0'){
                                            $single->Price .=".00";}

                                        echo $single->Price;?>

                                </span></p>
                        <?php endforeach; ?>

                    <!--<p>Extras:</p>

                    <?php /*if(!empty($arrAccessories)){ */?>
                        <?php /*foreach($arrAccessories as $single): */?>
                            <p><?php /*echo get_the_title($single['META_WP_ID']); */?>
                                <span class="price pull-right">$<?php /*echo $single['Price'] ;*/?></span></p>
                        <?php /*endforeach; */?>
                    --><?php /*} */?>
                </div>
                <?php } ?>

                <p>Item(s) Subtotal:<span class="price_o pull-right">$<?php echo $total;?></span></p>
                <p>Shipping & Handling<span class="price_o pull-right">$<?php echo $shippingPrice;?></span></p>
                <p><strong>Grand Total:</strong><span class="price_o pull-right">$<?php echo $total+$shippingPrice;?></span></p>
                 </div>
              </div>
            </br>
            <div class="text-center"><a href="<?php echo get_permalink($config['id_medical_profile']); ?>" title="Click to Continue" class="btn btn-custom btn_to_continue">Click to Continue</a></div>
            </br></br>

        </div>


    </div>
    <script>
        (function($) {

            $res = $('.tmp_btn');
            $( document ).ready(function(){

                if($res.attr('data-responses')=='Failed'){
                    console.log('ciao');
                    $btn_next = $('.btn_to_continue');
                    $btn_next.removeAttr('href');
                    $btn_next.css('background-color','grey');

                }
            });


        var stickyTop = $('#preview-order-payment').offset()?$('#preview-order-payment').offset().top-60:0; // returns number


        if($(window).width()>768){

            $(window).scroll(function(){ // scroll event

                if(($(window).scrollTop())>700){
                    $("#preview-order-payment").css("transition","none");
                }


                var windowTop = $(window).scrollTop(); // returns number
                var screenHeight=$('#preview-order-payment').height();
                var offsetFooter=$("#colophon").offset().top;

                var buttonTop=$("#place_order").offset().top;

                if (stickyTop < windowTop) {


                    if((windowTop+screenHeight)>=buttonTop){


                        var parentRow=$('#preview-order-payment').closest("div.row").offset().top;
                        var calc=(buttonTop+$("#place_order").height()-screenHeight-30-parentRow);

                        $('#preview-order-payment').css({ position: 'absolute', top: calc +'px' });
                    }else{

                        $('#preview-order-payment').css({ position: 'fixed', top: '30px' });
                    }

                }
                else {
                    $('#preview-order-payment').css({ position: 'absolute', top: 0 });
                }

            });}


        })(jQuery);

    </script>
<?php include($ABS_path . "/footer.php");
    get_footer(); ?>