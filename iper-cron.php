<?php
/**
 * Created by PhpStorm.
 * User: linus
 * Date: 03/02/16
 * Time: 17:15
 */

    /*if(!function_exists('callDBStored')){
        function callDBStored($preparedData,$single=false,$type=OBJECT)
        {

            $res    = false;
            $new_db = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
            if ($single) {
                $res = $new_db->get_row($preparedData, $type);
            } else {
                $res = $new_db->get_results($preparedData, $type);
            }

            unset($new_db);

            return $res;

        }
    }*/


    function createMessage($stato,$type,$Message){
        $obj = array(
            "time"=>time(),
            "stato"=>$stato,
            "type"=>  $type,
            "message"=>$Message
        );
        return $obj;
    }

    /************* chiamata request ****************/
    $log    = "";
    $crypto = new SecuredContent();


    /*$api_uri =  get_option(id_api_salesforce);*/
    /*$api_uri = 'https://lilsand2-connectamerica.cs23.force.com/RestServices/';*/ // this variable should be configurable
    $api_rest="services/apexrest/GetProductCatalog";


    $api_uri =  get_option(id_api_salesforce); // this variable should be configurable
    $api_rest="GetProductCatalog";


    $api_param="?brand=";
    $api_brand=get_option(id_brand);
    //$log_Array = array();

    //array_push($log_Array,createMessage(1,"info","inizio sync"));


    $is_post = false;//$_SERVER['REQUEST_METHOD'] === 'POST';
    $url         = $api_uri .$api_rest . $api_param . $api_brand; //$api_uri . (($is_post) ? $_POST['meta']['uri'] : $_GET['meta']['uri']);

    $request = curl_init($url);

    curl_setopt($request, CURLOPT_CUSTOMREQUEST, $is_post ? 'POST' : 'GET');
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    if ($is_post) {
        $data = json_encode($crypto->encode_content(json_encode($_POST['content'])));
        
        curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
    }
    else {
        $auth_header = json_encode($crypto->encode_content(urldecode($url)));

        curl_setopt($request, CURLOPT_HTTPHEADER, array('CA-Authorization: ' . $auth_header));
    }

    //array_push($log_Array,createMessage(1,"info","chiamata"));


    global $wpdb;
    $chiamta1=$wpdb->prepare("CALL IPER_MA_REQUEST_SET(%d,%s,%s,%s,%s,%d)",
        0,
        $url,
        '',
        '',
        '',
        -1
    );
    //array_push($log_Array,createMessage(1,"Chiamata",$chiamta1));

    $rs=callDBStored($chiamta1,true);

    array_push($log_Array,createMessage(1,"Risultato",$rs));

    $result = curl_exec($request);

    curl_close($request);

    $result = json_decode($result);
    $result = (OBJECT)json_decode($crypto->decode_content($result));

    $chiamta1=$wpdb->prepare("CALL IPER_MA_REQUEST_SET(%d,%s,%s,%s,%s,%d)",
        $rs->identity,
        $url,
        '',
        $result->ResponseHeader->ResponseId,
        json_encode($result),
        -1
    );
    //array_push($log_Array,createMessage(1,"Chiamata",$chiamta1));

    $rs=callDBStored($chiamta1,true);

    //array_push($log_Array,createMessage(1,"Risultato",$rs));

    $RequestID = $rs->identity;
    $ResponseId = $result->ResponseHeader->ResponseId;

    $Brand = $result->ResponseBody->BrandOfferings;

    if (!empty($Brand) && count($Brand)==1){

        $BrandID = $result->ResponseBody->BrandOfferings[0]->BrandID;
        $product = $result->ResponseBody->BrandOfferings[0]->Products;

        $log = $log."INIZIO INSERIMENTO PRODOTTI".PHP_EOL;


        if (!empty($product)){

            //array_push($log_Array,createMessage(1,"info","Inizio Prodotti". count($product)));


            $log = $log."PRODOTTI " . count($product) . PHP_EOL;
            foreach ($product as $single){


                /************* inserimento prodotto ****************/

                $ProductName=$single->ProductName;
                $ProductID=$single->ProductID;
                $ProductCode=$single->ProductCode;
                $IsAvailable=$single->IsAvailable?1:0;

                $log = $log."prodotto:".$ProductID .PHP_EOL;

                $call=$wpdb->prepare("CALL IPER_MA_PRODUCT_SET(%d,%s,%s,%s,%s,%s,%d,%d)",
                    0,
                    $BrandID,
                    $ProductName,
                    $ProductID,
                    $ProductCode,
                    $IsAvailable,
                    0,
                    $RequestID
                );

                $log = $log."chiamata:".$call .PHP_EOL;
                $rs=callDBStored($call,true);

                $idProduct = $rs->identity;

                if($rs->status == 2){

                    $typepost = array(
                        'post_title'    => $ProductName,
                        'post_status'   => 'publish',                 //Default status draft
                        'post_author'   => 1,
                        'post_type'     => 'product'
                    );
                    //creating post with arguments above and assign post id to $def_post_id
                    $def_post_id = wp_insert_post($typepost);

                    $call=$wpdb->prepare("CALL IPER_MA_PRODUCT_SET(%d,%s,%s,%s,%s,%s,%d,%d)",
                        $idProduct,
                        $BrandID,
                        $ProductName,
                        $ProductID,
                        $ProductCode,
                        $IsAvailable,
                        $def_post_id,
                        $RequestID
                    );

                    $rs=callDBStored($call,true);

                    $log = $log."Inserimento prodotto:".$ProductID. " id:".$idProduct.PHP_EOL;

                }elseif ($rs->status == 1){

                    $log = $log."Modifica prodotto:".$ProductID. " id:".$idProduct.PHP_EOL;

                }else{

                    $log = $log."Error prodotto:".$ProductID. PHP_EOL;

                }


                /************* inserimento RatePlans ****************/
                $rateplan = $single->RatePlans;
                if (!empty($rateplan)){
                    $log = $log."RATEPLAN " . count($rateplan) . PHP_EOL;
                    foreach ($rateplan as $rate) {


                        $Term=$rate->Term;
                        $RatePlanID=$rate->RatePlanID;
                        $Price=$rate->Price;
                        $OrderType=$rate->OrderType;
                        $MonthlyPrice  =$rate->MonthlyPrice;

                        $log = $log."rateplan:".$RatePlanID .PHP_EOL;

                        $call=$wpdb->prepare("CALL IPER_MA_RATEPLAN_SET(%d,%d,%s,%s,%s,%s,%s,%d,%d)",
                            0,
                            $idProduct,
                            $Term,
                            $RatePlanID,
                            $Price,
                            $OrderType,
                            $MonthlyPrice,
                            0,
                            $RequestID
                        );

                        $log = $log."chiamata:".$call .PHP_EOL;
                        $rs=callDBStored($call,true);

                        $idRateplan = $rs->identity;


                        if($rs->status == 2){

                            $typepost = array(
                                'post_title'    => $Term,
                                'post_status'   => 'publish',                 //Default status draft
                                'post_author'   => 1,
                                'post_type'     => 'rateplan'
                            );
                            //creating post with arguments above and assign post id to $def_post_id
                            $def_post_id = wp_insert_post($typepost);

                            $call=$wpdb->prepare("CALL IPER_MA_RATEPLAN_SET(%d,%d,%s,%s,%s,%s,%s,%d,%d)",
                                $idRateplan,
                                $idProduct,
                                $Term,
                                $RatePlanID,
                                $Price,
                                $OrderType,
                                $MonthlyPrice,
                                $def_post_id,
                                $RequestID
                            );

                            $rs=callDBStored($call,true);

                            $log = $log."Inserimento rateplan:".$RatePlanID. " id:".$idRateplan.PHP_EOL;

                        }elseif ($rs->status == 1){

                            $log = $log."Modifica rateplan:".$RatePlanID. " id:".$idRateplan.PHP_EOL;

                        }else{

                            $log = $log."Error rateplan:".$RatePlanID. PHP_EOL;

                        }

                        /************* inserimento Upsell ****************/
                        $UpsellOptions = $rate->UpsellOptions; // da chiedere la struttura

                        if(!empty($UpsellOptions)) {
                            $log = $log."UpsellOptions " . count($UpsellOptions) . PHP_EOL;

                            foreach($UpsellOptions as $upsell){

                                $ShowBeforeAccessories = $upsell->ShowBeforeAccessories?1:0;;
                                $ProductName= $upsell->ProductName;
                                $ProductID= $upsell->ProductID;
                                $Price= $upsell->Price;


                                $log = $log."UpsellOptions:".$ProductID .PHP_EOL;



                                $call=$wpdb->prepare("CALL IPER_MA_UPSELL_SET(%d,%d,%s,%s,%s,%s,%d,%d)",
                                    0,
                                    $idRateplan,
                                    $ProductName,
                                    $ProductID,
                                    $Price,
                                    $ShowBeforeAccessories,
                                    0,
                                    $RequestID
                                );

                                $log = $log."chiamata:".$call .PHP_EOL;
                                $rs=callDBStored($call,true);

                                $idUpsell = $rs->identity;


                                if($rs->status == 2){

                                    $typepost = array(
                                        'post_title'    => $ProductName,
                                        'post_status'   => 'publish',                 //Default status draft
                                        'post_author'   => 1,
                                        'post_type'     => 'upsell'
                                    );
                                    //creating post with arguments above and assign post id to $def_post_id
                                    $def_post_id = wp_insert_post($typepost);

                                    $call=$wpdb->prepare("CALL IPER_MA_UPSELL_SET(%d,%d,%s,%s,%s,%s,%d,%d)",
                                        $idUpsell,
                                        $idRateplan,
                                        $ProductName,
                                        $ProductID,
                                        $Price,
                                        $ShowBeforeAccessories,
                                        $def_post_id,
                                        $RequestID
                                    );

                                    $rs=callDBStored($call,true);

                                    $log = $log."Inserimento UpsellOptions:".$ProductID. " id:".$idUpsell.PHP_EOL;

                                }elseif ($rs->status == 1){

                                    $log = $log."Modifica UpsellOptions:".$ProductID. " id:".$idUpsell.PHP_EOL;

                                }else{

                                    $log = $log."Error UpsellOptions:".$ProductID. PHP_EOL;

                                }




                            }

                        }





                            /************* inserimento AccessoryGroups ****************/
                        $AccessoryGroups = $rate->AccessoryGroups;
                        if(!empty($AccessoryGroups)){
                            $log = $log."AccessoryGroups " . count($AccessoryGroups) . PHP_EOL;

                            foreach ($AccessoryGroups as $group){

                                $LimitRestriction=$group->LimitRestriction?1:0;;
                                $IncludedWithOrder=$group->IncludedWithOrder?1:0;;
                                $Description=$group->Description;
                                $AccessoryGroupID=$group->AccessoryGroupID;


                                $log = $log."AccessoryGroups:".$AccessoryGroupID .PHP_EOL;

                                $call=$wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_SET(%d,%d,%s,%s,%s,%s,%d,%d)",
                                    0,
                                    $idRateplan,
                                    $LimitRestriction,
                                    $IncludedWithOrder,
                                    $Description,
                                    $AccessoryGroupID,
                                    0,
                                    $RequestID
                                );

                                $log = $log."chiamata:".$call .PHP_EOL;
                                $rs=callDBStored($call,true);

                                $idAccessoryGroup = $rs->identity;

                                $log = $log."Inserimento AccessoryGroups:".$AccessoryGroupID. " id:".$idAccessoryGroup.PHP_EOL;


                                /************* inserimento Accessories ****************/
                                $Accessory = $group->Accessories;
                                if(!empty($Accessory)){
                                    $log = $log."Accessory " . count($Accessory) . PHP_EOL;
                                    foreach ($Accessory as $single_Accessory){


                                        $Quantity = $single_Accessory->Quantity;
                                        $Price= $single_Accessory->Price;
                                        $IsAvailable= $single_Accessory->IsAvailable?1:0;
                                        $AccessoryName= $single_Accessory->AccessoryName;
                                        $AccessoryID= $single_Accessory->AccessoryID;
                                        $AccessoryCode= $single_Accessory->AccessoryCode;

                                        $log = $log."Accessories:".AccessoryID .PHP_EOL;

                                        $call=$wpdb->prepare("CALL IPER_MA_ACCESSORY_SET(%d,%d,%s,%s,%s,%s,%s,%s,%d,%d)",
                                            $idAccessoryGroup,
                                            0,
                                            $Quantity,
                                            $Price,
                                            $IsAvailable,
                                            $AccessoryName,
                                            $AccessoryID,
                                            $AccessoryCode,
                                            0,
                                            $RequestID
                                        );

                                        $log = $log."chiamata:".$call .PHP_EOL;
                                        $rs=callDBStored($call,true);


                                        $idAccessory = $rs->identity;
                                        $log = $log."Inserimento Accessories:".$AccessoryID. " id:".$idAccessory." Status".$rs->status.PHP_EOL;

                                        if($rs->status == 2){

                                            $typepost = array(
                                                'post_title'    => $AccessoryName,
                                                'post_status'   => 'publish',                 //Default status draft
                                                'post_author'   => 1,
                                                'post_type'     => 'accessory' //controllare
                                            );
                                            //creating post with arguments above and assign post id to $def_post_id
                                            $def_post_id = wp_insert_post($typepost);

                                            $call=$wpdb->prepare("CALL IPER_MA_ACCESSORY_SET(%d,%d,%s,%s,%s,%s,%s,%s,%d,%d)",
                                                $idAccessoryGroup,
                                                $idAccessory,
                                                $Quantity,
                                                $Price,
                                                $IsAvailable,
                                                $AccessoryName,
                                                $AccessoryID,
                                                $AccessoryCode,
                                                $def_post_id,
                                                $RequestID
                                            );


                                            $rs=callDBStored($call,true);

                                            $log = $log."Inserimento Accessories:".$AccessoryID. " id:".$idAccessory.PHP_EOL;

                                        }elseif ($rs->status == 1){

                                            $log = $log."Modifica Accessories:".$AccessoryID. " id:".$idAccessory.PHP_EOL;

                                        }else{

                                            $log = $log."Error Accessories:".$ProductID. PHP_EOL;

                                        }


                                    }//ACCESSORY FOR

                                }//ACCESSORY IF



                            }//GROUP ACC FOR

                        }//GROUP ACC IF

                        /************* inserimento AvailablePromotions ****************/

                        $log = $log."INSERIMENTO PROMOTION <br>";

                        $AvailablePromotions = $rate->AvailablePromotions;
                        $log = $log."INSERIMENTO PROMOTION ".count($AvailablePromotions).PHP_EOL;

                        if(!empty($AvailablePromotions)){
                            foreach ($AvailablePromotions as $promotion) {
                                $PromotionID = $promotion->PromotionID;
                                $Price       = $promotion->Price;
                                $Name        = $promotion->Name;

                                $call = $wpdb->prepare("CALL IPER_MA_PROMOTION_SET(%d,%d,%s,%s,%s,%d,%d)",
                                    0,
                                    $idRateplan,
                                    $PromotionID,
                                    $Price,
                                    $Name,
                                    0,
                                    $RequestID
                                );
                                $log  = $log . "chiamata:" . $call . PHP_EOL;

                                $rs = callDBStored($call, true);


                                $idAvailablePromotions = $rs->identity;

                                $log = $log . "Inserimento AvailablePromotions:" . $PromotionID . " id:" . $idAvailablePromotions . PHP_EOL;
                            }


                        }//PROMOTION IF



                    }//RATEPLAN FOR
                }//RATEPLAN IF


                $Bundles = $single->Bundles;
                if(!empty($Bundles)){
                    $BundleProducts = $Bundles->BundleProducts;
                    if (!empty($BundleProducts)){
                        foreach ($BundleProducts as $bundle){
                            $Name = $bundle->Name;
                            $ID = $bundle->ID;

                             $call=$wpdb->prepare("CALL IPER_MA_BUNDLE_SET(%d,%d,%s,%s,%d,%d)",
                                                               0,
                                                               $idProduct,
                                                               $ID,
                                                               $Name,
                                                               0,
                                                               $RequestID
                                                       );
                            $log = $log."chiamata:".$call .PHP_EOL;

                              $rs=callDBStored($call,true);


                            $idBundleProducts = $rs->identity;

                            $log = $log."Inserimento BundleProducts:".$ID. " id:".$idBundleProducts.PHP_EOL;

                        }//BundleProducts for
                    }//BundleProductsif
                }//bundles if


            }//PRODUCT FOR
        }//PRODUCT IF



        /************* inserimento Shippings ****************/
        $Shippings = $result->ResponseBody->BrandOfferings[0]->Shippings;

        $log = $log."INIZIO INSERIMENTO SHIPPING <br>";

        if(!empty($Shippings)){

            $log = $log."SHIPPING " . count($Shippings) . PHP_EOL;

            foreach ($Shippings as $single){

                $ShippingMethod = $single->ShippingMethod;
                $ShippingID = $single->ShippingID;
                $ShippingCarrier = $single->ShippingCarrier;
                $Price = $single->Price;

                 $call=$wpdb->prepare("CALL IPER_MA_SHIPPING_SET(%d,%s,%s,%s,%s,%s,%d,%d)",
                                        0,
                                        $BrandID,
                                        $ShippingMethod,
                                        $ShippingID,
                                        $ShippingCarrier,
                                        $Price,
                                        0,
                                        $RequestID
                                );
                $log = $log."chiamata:".$call .PHP_EOL;

                    $rs=callDBStored($call,true);

                $idShipping = $rs->identity;


                if($rs->status == 2){

                    $typepost = array(
                        'post_title'    => $ShippingMethod,
                        'post_status'   => 'publish',                 //Default status draft
                        'post_author'   => 1,
                        'post_type'     => 'shipping' //controllare
                    );
                    //creating post with arguments above and assign post id to $def_post_id
                    $def_post_id = wp_insert_post($typepost);

                    $call=$wpdb->prepare("CALL IPER_MA_SHIPPING_SET(%d,%s,%s,%s,%s,%s,%d,%d)",
                        $idShipping,
                        $BrandID,
                        $ShippingMethod,
                        $ShippingID,
                        $ShippingCarrier,
                        $Price,
                        $def_post_id,
                        $RequestID
                    );
                    $log = $log."chiamata:".$call .PHP_EOL;


                    $rs=callDBStored($call,true);

                    $log = $log."Inserimento Shipping:".$ShippingID. " id:".$idShipping.PHP_EOL;

                }elseif ($rs->status == 1){

                    $log = $log."Modifica Shipping:".$ShippingID. " id:".$idShipping.PHP_EOL;

                }else{

                    $log = $log."Error Shipping:".$ShippingID. PHP_EOL;

                }



            }//Shipping for

        }//shipping if


        $call="CALL IPER_MA_CLEAN_WP_POST";


        $rs=callDBStored($call,true);

        $log = $log."Pulizia POST";



        $call=$wpdb->prepare("CALL IPER_MA_REQUEST_SET_LOG(%d,%s)",
            $RequestID,
            $log
        );

        $rs=callDBStored($call,true);

        $log = $log."Inserimento LOG id:".$RequestID.PHP_EOL;

    }//brand




echo $log;







    /*
    if($_GET['v']=='idee'){

        die();
        global $wpdb,$sitepress;

        $sql="SELECT * FROM italiacamp.IMPORT_IDEE";
        $idee=$wpdb->get_results($sql);

        foreach($idee as $single){

            $titolo=$single->TITOLO;
            $abs=$single->ABSTRACT;
            $user=$single->ID_DB_USER;
            $origID=$single->ID;

            $it_post = array(
                'post_title'    => $titolo,
                'post_content'  => $abs,
                'post_status'   => 'publish',                 //Default status draft
                'post_author'   => $user,
                'post_type'     => 'iper-cpt-idee'
            );
            //creating post with arguments above and assign post id to $def_post_id
            $def_post_id = wp_insert_post($it_post);
            $def_trid = $sitepress->get_element_trid($def_post_id,'post_'.'iper-cpt-idee');
            $def_code='it'; //Default post to italian
            $id='en';   //Second language english
            //Create en post
            $en_post = array(
                'post_title'    => $titolo,
                'post_content'  => $abs,
                'post_status'   => 'publish',                 //Default status draft
                'post_author'   => $user,
                'post_type'     => 'iper-cpt-idee'
            );
            //creating post with arguments above and assign post id to $ru_post_id
            $en_post_id = wp_insert_post($en_post);

            //change language and trid of second post to match russian and default post trid
            $sitepress->set_element_language_details($en_post_id, 'post_'.'iper-cpt-idee', $def_trid, $id,$def_code);

            //inser on tb idee
            $ideaID=$wpdb->insert(
                'iper_wp_icIDEE',
                array(
                    'icUSER_idUSER'=>$user,
                    'PROGETTO_NOME'=>$titolo,
                    'PROGETTO_ABSTRACT'=>$abs,
                    'IS_APPROVED'=>'si',
                    'STATO'=>'publish',
                    'fkWP_POST'=>$def_post_id,
                    'VERSIONE'=>1,
                    'META_DATA_CREAZIONE'=>date('Y-m-d H:i:s'),
                    'META_DATA_ULTIMA_MODIFICA'=>date('Y-m-d H:i:s')
                )
            );

            $ideaID=$wpdb->insert_id;

            if($ideaID){
                //update
                $wpdb->update(
                    'IMPORT_IDEE',
                    array(
                        'ID_DB_IDEA'=>$wpdb->insert_id
                    ),
                    array('ID'=>$origID)
                );

                echo "<br>Aggiunta idea $titolo";
            }
            //ID_DB_IDEA
        }
    }

*/





?>
