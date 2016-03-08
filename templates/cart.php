<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 12:24
 */
session_start();
global $cartDivID, $shipping_page;
?>
<div id="<?php echo ($cartDivID) ? $cartDivID : 'preview-select-payment';?>">
    <div class="title">Order Summary</div>
    <div id="iperCartDetail" class="order-detail">
    </div>
    <a href="<?php echo get_permalink($shipping_option) ?>"class="btn btn-red1 btn-block linkbtn" >Place Order</a>
</div>

<script id="iper-cart-template" type="text/x-handlebars-template">

    <div data-product="{{product.db_id}}" class="containerIperProduct">
        <!-- PRODOTTO -->
        <div class="product">
            <span class="name"><strong>{{product.title}}</strong> <a href="<?php echo get_permalink(get_option("id_medical_home"));?>" title="Edit">Edit</a></span>
        </div>
        <div class="clearfix"></div>
        <hr>

        <!-- RATEPLAN -->
        <div class="payment-type">
            <div class="radio">
                <input type="radio" name="paymentType" id="paymentType" value={{product.plan.plan_wp_id}} checked>
                <label>{{product.plan.wp_sel_plan_title}} <div class="order-text-space"><span class="priceff">${{product.plan.plan_price}}{{{product.plan.time}}}</span></div></label>
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- UPSELLS -->
        <ul class="upsell">
            <li class="upsell"><strong>Options</strong></li>
            <div class="order-text-space">
                <span class="upsell">
                    {{#each product.plan.upsells}}
                        {{title}} ${{price}}<br>
                    {{/each}}
                </span>

            </div>
            <div class="clearfix"></div>
        </ul>
        <hr>

        <!-- ACCESSORIES -->
        <?php

            foreach($_SESSION["iper_cart"]["products"] as $single){
                $aProduct=$single;
            }

            $accessories=array();
            $arrAccessories=array();
            if(!empty($aProduct['plan']['accessories'])){
                foreach($aProduct['plan']['accessories'] as $key => $value){
                    foreach($value as $single){
                        $arrAccessories[]=$single;
                    }
                }
            }

        ?>
        <?php if($shipping_page == '0'){ ?>
            <strong>Accessories</strong>
            <ul class="productListCart">
                {{#each product.plan.accessories}}
                    {{#each this}}
                        <li><a  data-groupID="{{fkACCESSORY_GROUP}}" data-accID="{{idACCESSORY}}" href="#" class="iperBtRemoveAccessories"><i class="fa fa-times"></i></a> {{{title}}} {{price}}</li>
                    {{/each}}
                {{/each}}
            </ul>
            <hr>
        <?php } else if($shipping_page == '1'){

                    if(!empty($arrAccessories)){?>

                        <strong>Accessories</strong>
                        <ul class="productListCart">
                            {{#each product.plan.accessories}}
                            {{#each this}}
                            <li><a  data-groupID="{{fkACCESSORY_GROUP}}" data-accID="{{idACCESSORY}}" href="#" class="iperBtRemoveAccessories"><i class="fa fa-times"></i></a> {{{title}}} {{price}}</li>
                            {{/each}}
                            {{/each}}
                        </ul>
                        <hr>

                   <?php  }
        }?>


        <!-- TOTAL -->
        <ul class="total">
            <li class="total1"><strong>Total</strong> <div class="order-text-space"><span class="price">${{total}}</span></div><div class="clearfix"></div></li>
            <!--<li class="annualtotal"><strong>Total Annually</strong> <div class="order-text-space"><span class="price">${{total_annual}} </span></div><div class="clearfix"></div></li>-->
        </ul>

    </div>
</script>
<script type="text/javascript">

    if(!$ || $==null) $=jQuery.noConflict();

    $(function(){
        var initCart=jQuery.parseJSON("<?php echo addslashes(json_encode(iperGetCartJSONObject()));?>");

        for(var i in initCart){
            var single=initCart[i];
            iperRenderCartJS(single);
        }
    });

    function iperRenderCartJS(data){

        if(!$ || $==null) $=jQuery.noConflict();
        var source   = $("#iper-cart-template").html();
        var template = Handlebars.compile(source);
        var html    = template(data);
        $("#iperCartDetail").html(html);

        bindDeleteAccessory();
    }

    function deleteAccessoryClick(e){

        e.preventDefault();

        var groupID=$(this).attr("data-groupID");
        var accID=$(this).attr("data-accID");
        var product=$(this).closest(".containerIperProduct").attr("data-product");

        if(groupID && accID){
            $(e.currentTarget).closest("li").fadeOut();
            removeAccessoryFromCart(groupID,accID,product);
        }

    }

    function bindDeleteAccessory() {
        var permanent = $('.list-select').attr('data-permanent');

        if (permanent == '0') {
            $(".iperBtRemoveAccessories").unbind('click', deleteAccessoryClick).bind('click', deleteAccessoryClick);
        } else { $(".iperBtRemoveAccessories").html('');}
    }

</script>