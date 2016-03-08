<?php
global $wpdb;
if($accessories_cookier && !empty($accessories_cookier)){
    $obj=array("products"=>$accessories_cookier);
    ?>
    <script type="text/javascript">
        window.cart=jQuery.parseJSON("<?php echo addslashes(json_encode($obj));?>");
    </script>
<?php
}

$cart=$_SESSION['iper_cart'];

foreach($cart['products'] as $product):

    $ratePlan=callDBStored($wpdb->prepare("call IPER_MA_RATEPLAN_GET(%d)",$product['plan']['plan_id']),true);
    $plan_price=$product['plan']['plan_price'];
    $wp_sel_plan_title=get_the_title($product['plan']['plan_wp_id']);

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
        $time = '<strong>/4 mo</strong>';
    }

    if($wp_sel_plan_title == 'Semi-Annually'){
        $total_annual = ($plan_price)*2;
        $time = '<strong>/6 mo</strong>';
    }
?>
    <div id="preview-select-payment">
    <div class="title">Order Summary</div>
    <div class="order-detail">
        <?php if(isset($sel_product)){  //mostro i dettagli del carrello solo se è stato selezionato un prodotto?>

            <!--                       PRODOTTO SELEZIONATO-->
            <div class="product">
                <span class="name"><strong><?php echo  get_the_title($product['wp_id']); ?></strong> <a href="<?php echo get_permalink( $medical_home_option );?>" title="Edit">Edit</a></span>
            </div>
            <div class="clearfix"></div>
            <hr>

            <!--                       rateplan SELEZIONATO-->
            <div class="payment-type">
                <div class="radio">
<!--                    <input type="radio" name="paymentType" id="paymentType" value="<?php /*get_the_title($sel_plan); */?>" checked>-->
                    <input type="radio" name="paymentType" id="paymentType" value="<?php echo $product['plan']['plan_wp_id']; ?>" checked>
                    <label><?php echo $wp_sel_plan_title;?> <div class="order-text-space"><span class="priceff">$<?php echo $plan_price.$time;?></span></div></label>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--                       upsell SELEZIONATO-->

            <?php if($sel_upsell[0] != ''){ ?>
                <ul class="upsell">
                    <li class="upsell"><strong>Upsell</strong></li>
                    <div class="order-text-space">
                                        <span class="upsell">
                                            <?php for($i=0; $i<count($upsell_id); $i++){ $upsell_sum_price += $upsell_price[$i]; echo get_the_title(get_post($upsell_id[$i])).' '.$upsell_price[$i].'<br>';}?>
                                        </span>

                    </div>
                    <div class="clearfix"></div>

                </ul>
                <hr>
            <?php } ?>

            <!--                       accessories SELEZIONATi-->
            <ul class="productListCart">
                Select Accessories
            </ul>
            <hr>




            <ul class="shipping-tax">
                <li class="tax"><strong>Tax</strong><div class="order-text-space"><span class="price">0</span></div><div class="clearfix"></div></li>
                <!-- <li class="shipping"><strong>Shipping</strong><div class="order-text-space"><span class="price">TBD</span></div><div class="clearfix"></div></li>-->
            </ul>
            <hr>


            <ul class="total">
                <li class="total1"><strong>Total</strong> <div class="order-text-space"><span class="price"><?php

                            echo $price_period+$upsell_sum_price;

                            ?></span></div><div class="clearfix"></div></li>
                <li class="annualtotal"><strong>Total Annually</strong> <div class="order-text-space"><span class="price">$<?php echo $total_annual;?> </span></div><div class="clearfix"></div></li>
            </ul>

        <?php }else{echo '<a style="color:black;" href="'.get_permalink($medical_home_option).'" title="Edit">Seleziona un prodotto</a>';} ?>
    </div>

    <a href="<?php echo get_permalink($shipping_option) ?>"class="btn btn-red1 btn-block">Next</a>


</div>
<?php endforeach; ?>