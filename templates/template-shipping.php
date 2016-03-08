<?php
    session_start();

    iper_hook_css(); get_header(); global $indice;

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'ASC',
            'post_type'        => 'shipping'
        );
        $posts_array = get_posts( $args );
        include_once('nav_bar.php');

?>
<!--<pre><?php /*var_dump($_SESSION['iper_cart']); */?></pre>-->
    <div class="container_">

        <form id="form-payment" data-toggle="validator" action="<?php echo get_permalink($config['id_medical_order']); ?>" method="post" role="form" data-delay="500">

            <div class="row">
                <div id="iperColCartSummary" class="col-sm-4 col-sm-push-8">

                    <?php
                    global $cartDivID;
                    global $shipping_page;
                    $shipping_page = '1';
                    $cartDivID="preview-order-payment";
                    include_once "cart.php";
                    ?>
                </div>

                <div id="iperColShippingSummary" class="col-sm-8 col-sm-pull-4">

                    <div class="title-page"><span><?php echo $indice;?></span> Shipping Address</div>

                    <div class="container-form-payment">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="firstName_shipping" name="firstName_shipping" placeholder="First Name*" value="<?php if(isset($_POST['firstName_shipping'])){ echo $_POST['firstName_shipping'];} ?>" data-error="Please insert your First Name" required><?php $_POST['firstName_shipping']; ?>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="lastName_shipping" name="lastName_shipping" placeholder="Last Name*" data-error="Please insert your Last Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <label>Country*</label>
                        <div class="row">

                            <?php

                                $us_state_states = array(
                                    'st'=>'State*',
                                    'AL'=>'AL',
                                    'AK'=>'AK',
                                    'AZ'=>'AZ',
                                    'AR'=>'AR',
                                    'CA'=>'CA',
                                    'CO'=>'CO',
                                    'CT'=>'CT',
                                    'DE'=>'DE',
                                    'FL'=>'FL',
                                    'GA'=>'GA',
                                    'HI'=>'HI',
                                    'ID'=>'ID',
                                    'IL'=>'IL',
                                    'IN'=>'IN',
                                    'IA'=>'IA',
                                    'KS'=>'KS',
                                    'KY'=>'KY',
                                    'LA'=>'LA',
                                    'ME'=>'ME',
                                    'MD'=>'MD',
                                    'MA'=>'MA',
                                    'MI'=>'MI',
                                    'MN'=>'MN',
                                    'MS'=>'MS',
                                    'MO'=>'MO',
                                    'MT'=>'MT',
                                    'NE'=>'NE',
                                    'NV'=>'NV',
                                    'NH'=>'NH',
                                    'NJ'=>'NJ',
                                    'NM'=>'NM',
                                    'NY'=>'NY',
                                    'NC'=>'NC',
                                    'ND'=>'ND',
                                    'OH'=>'OH',
                                    'OK'=>'OK',
                                    'OR'=>'OR',
                                    'PA'=>'PA',
                                    'PR'=>'PR',
                                    'RI'=>'RI',
                                    'SC'=>'SC',
                                    'SD'=>'SD',
                                    'TN'=>'TN',
                                    'TX'=>'TX',
                                    'UT'=>'UT',
                                    'VT'=>'VT',
                                    'VA'=>'VA',
                                    'WA'=>'WA',
                                    'WV'=>'WV',
                                    'WI'=>'WI',
                                    'WY'=>'WY'
                                );

                                $canadian_states_up = array(
                                    "Pr"=>'Province*',
                                    "AB" => "AB",
                                    "BC" => "BC",
                                    "MB" => "MB",
                                    "NB" => "NB",
                                    "NL" => "NL",
                                    "NS" => "NS",
                                    "NT" => "NT",
                                    "NU" => "NU",
                                    "ON" => "ON",
                                    "PE" => "PE",
                                    "QC" => "QC",
                                    "SK" => "SK",
                                    "YT" => "YT"
                                );



                            ?>

                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <select id="country_shipping" name="country_shipping" class="form-control selectpicker show-tick"  data-error="Please select a country"  title="Country*" required>
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>

                                    </select>

                                    <script type="text/javascript">

                                        (function($) {



                                            var initWidth=$('#iperColCartSummary').width();
                                            $('#preview-order-payment').css({ position: 'absolute', top: 0, left: 'auto', width: ''+initWidth+'px' });
                                            var stickyTop = $('#preview-order-payment').offset()?$('#preview-order-payment').offset().top-60:0; // returns number


                                            var canScroll=false;
                                            if($(window).width()>768){
                                                canScroll=true;
                                            } else {

                                            canScroll=false;
                                             $('#preview-order-payment').css({ position: 'relative', top: 0, left: 'auto', width: 'auto' });
                                             }

                                                $( window ).resize(function() {
                                                if($(window).width()>768){
                                                canScroll=true;

                                                 }else{
                                                 if(canScroll){
                                                    $('#preview-order-payment').css({ position: 'relative', top: 0, left: 'auto', width: 'auto' });
                                                 }
                                                 canScroll=false;
                                                 }
                                                });


                                                $(window).scroll(function(){ // scroll event

                                                    if(!canScroll) return;

                                                    if(($(window).scrollTop())>700){

                                                        $("#preview-order-payment").css("transition","none");}



                                                    var windowTop = $(window).scrollTop(); // returns number
                                                    var screenHeight=$('#preview-order-payment').height();
                                                    var offsetFooter=$("#colophon").offset().top;

                                                    var buttonTop=$("#place_order").offset().top;

                                                    var left=$("#iperColShippingSummary").width()+$("#iperColShippingSummary").offset().left+45;

                                                    if (stickyTop < windowTop) {

                                                        if((windowTop+screenHeight)>=buttonTop){
                                                            var parentRow=$('#preview-order-payment').closest("div.row").offset().top;
                                                            var calc=(buttonTop+$("#place_order").height()-screenHeight-30-parentRow);

                                                            $('#preview-order-payment').css({ position: 'absolute', top: calc +'px', left:'auto' });
                                                        }else{
                                                            $('#preview-order-payment').css({ position: 'fixed', top: '30px', left: ''+left+'px' });
                                                        }
                                                    } else {
                                                        $('#preview-order-payment').css({ position: 'absolute', top: 0, left: 'auto' });
                                                    }
                                                /* else {   $('#preview-order-payment').css({ position: 'absolute', top: 0, left: 'auto', width: 'auto' });  }*/

                                                });








                                        })(jQuery);

                                        jQuery(function(){

                                            jQuery("#preview-order-payment a.btn").click(function(e){
                                                e.preventDefault();
                                                jQuery("#form-payment").submit();
                                            });

                                        /*function getPlace(place){

                                            //ajax
                                            var selected_place = {
                                                'action': 'iper_change_states',
                                                'place': place
                                            };
                                            console.log(selected_place);

                                            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                                            jQuery.post(ajax_object.ajax_url, selected_place, function(response) {});
                                        }

                                        jQuery("#country_shipping").on('changed.bs.select', function (e) {
                                            var selected = jQuery('#country_shipping').val();
                                            getPlace(selected);
                                            console.log(selected);
                                        });*/


                                        var arrUS= jQuery.parseJSON('<?php echo addslashes(json_encode($us_state_states));?>');
                                        var arrCanada=jQuery.parseJSON('<?php echo addslashes(json_encode($canadian_states_up));?>');
                                        var oldState="US";

                                            jQuery('#country_shipping').on('changed.bs.select', function (e) {

                                                var value=jQuery('#country_shipping').val();
                                                if(value=='Canada' && oldState!="Canada") {

                                                    jQuery('#state_shipping').html("");
                                                    for(var i in arrCanada){
                                                        jQuery('#state_shipping').append("<option value='"+i+"'>"+arrCanada[i]+"</option>");
                                                        jQuery('#state_shipping').selectpicker('refresh');

                                                    }

                                                }else if(value=="United States" && oldState!="United States"){
                                                    jQuery('#state_shipping').html("");
                                                    for(var i in arrUS){
                                                        jQuery('#state_shipping').append("<option value='"+i+"'>"+arrUS[i]+"</option>");
                                                        jQuery('#state_shipping').selectpicker('refresh');

                                                    }
                                                }

                                                oldState=value;
                                            });

                                            jQuery('#country_billing').on('changed.bs.select', function (e) {

                                                var value=jQuery('#country_billing').val();
                                                if(value=='Canada' && oldState!="Canada") {

                                                    jQuery('#state_billing').html("");
                                                    for(var i in arrCanada){
                                                        jQuery('#state_billing').append("<option value='"+i+"'>"+arrCanada[i]+"</option>");
                                                        jQuery('#state_billing').selectpicker('refresh');

                                                    }

                                                }else if(value=="United States" && oldState!="United States"){
                                                    jQuery('#state_billing').html("");
                                                    for(var i in arrUS){
                                                        jQuery('#state_billing').append("<option value='"+i+"'>"+arrUS[i]+"</option>");
                                                        jQuery('#state_billing').selectpicker('refresh');

                                                    }
                                                }

                                                oldState=value;
                                            });

                                            function checkStateIsValid(e, id, value1, value2, value3, value4){
                                                var parent=jQuery(id).closest('.form-group');
                                                parent.removeClass('has-error');

                                                jQuery(".help-block.with-errors",parent).html("");
                                                 console.log('checkStateIsValid');


                                                if(jQuery(id).val()==value1 || jQuery(id).val()==value2 || jQuery(id).val()==value3 || jQuery(id).val()==value4){

                                                    parent.addClass('has-error');
                                                     //jQuery('.state').toggleClass('has-error');
                                                     console.log('value');
                                                     console.log( jQuery(id).closest('.form-group'));

                                                     var errText=jQuery(id).attr("data-error");
                                                     jQuery(".help-block.with-errors",parent).html(errText);

                                                     e.preventDefault();
                                                }
                                            }

                                                jQuery('#form-payment').validator().on('submit', function (e) {
                                                      if (e.isDefaultPrevented()) {
                                                        // handle the invalid form...
                                                        checkStateIsValid(e, '#state_shipping', 'State*', 'Province*', 'St', 'Pr');
                                                        checkStateIsValid(e, '#state_billing', 'State*', 'Province*', 'St', 'Pr');

                                                      } else {
                                                        // everything looks good!
                                                       checkStateIsValid(e, '#state_shipping', 'State*', 'Province*', 'St', 'Pr');
                                                        checkStateIsValid(e, '#state_billing', 'State*', 'Province*', 'St', 'Pr');

                                                      }
                                                    });



                                                    function luhnCheckFast(luhn)
                                                            {
                                                                var ca, sum = 0, mul = 0;
                                                                var len = luhn.length;
                                                                while (len--)
                                                                {
                                                                    ca = parseInt(luhn.charAt(len),10) << mul;
                                                                    sum += ca - (ca>9)*9; // sum += ca - (-(ca>9))|9
                                                                    // 1 <--> 0 toggle.
                                                                    mul ^= 1; // mul = 1 - mul;
                                                                };
                                                                return (sum%10 === 0) && (sum > 0);
                                                            };

                                                     function getCreditCardType(accountNumber)
                                                                            {

                                                                              //start without knowing the credit card type
                                                                              var result = "unknown";

                                                                              //first check for MasterCard
                                                                              if (/^5[1-5]/.test(accountNumber))
                                                                              {
                                                                                result = "MasterCard";
                                                                              }

                                                                              //then check for Visa
                                                                              else if (/^4/.test(accountNumber))
                                                                              {
                                                                                result = "Visa";
                                                                              }

                                                                              //then check for AmEx
                                                                              else if (/^3[47]/.test(accountNumber))
                                                                              {
                                                                                result = "American Express";
                                                                              }

                                                                              else if (/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/.test(accountNumber))
                                                                              {
                                                                                result = "Discover";
                                                                              }

                                                                              return result;
                                                                            }







                                                    function check_cardnumber_IsValid(e, id){
                                                    console.log('check state');
                                                    var parent=jQuery(id).closest('.form-group');
                                                    var gliphycon=jQuery('.glyphicon_search');
                                                    console.log(getCreditCardType(jQuery('#creditcard_number').val().replace(/-/g,"")));
                                                    console.log(jQuery(id).val().replace(/-/g,""));


                                                        if(luhnCheckFast(jQuery(id).val().replace(/-/g,"")) && (getCreditCardType(jQuery('#creditcard_number').val().replace(/-/g,""))==jQuery('#creditcard_type').val())){

                                                                     console.log('lhun true');



                                                                     parent.removeClass('has-error');
                                                                     parent.addClass('has-success');
                                                                     gliphycon.addClass('glyphicon-ok');
                                                                     gliphycon.removeClass('glyphicon-remove');
                                                                    /*parent.removeClass('has-error');
                                                                    e.preventDefault();
                                                                    jQuery(".help-block.with-errors",parent).html('');*/
                                                         } else {
                                                                    console.log('lhun false');
                                                                    gliphycon.removeClass('glyphicon-ok');
                                                                    gliphycon.addClass('glyphicon-remove');
                                                                    parent.removeClass('has-success');
                                                                    parent.addClass('has-error');

                                                                    var errText=jQuery(id).attr("data-error");
                                                                    jQuery(".help-block.with-errors",parent).html(errText);
                                                                    /*parent.removeClass('has-success');
                                                                    gliphycon.removeClass('glyphicon-ok');*/
                                                                    /*parent.addClass('has-error');
                                                                    var errText=jQuery(id).attr("data-error");
                                                                    jQuery(".help-block.with-errors",parent).html(errText);parent.addClass('has-error');
                                                                    var errText=jQuery(id).attr("data-error");
                                                                    jQuery(".help-block.with-errors",parent).html(errText);*/
                                                                 }
                                                            }

                                                            jQuery('#form-payment').validator().on('submit', function (e) {
                                                                                 console.log("submit");
                                                                          if (e.isDefaultPrevented()) {
                                                                            // handle the invalid form...
                                                                            check_cardnumber_IsValid(e, '#creditcard_number');
                                                                          } else {
                                                                            // everything looks good!
                                                                           check_cardnumber_IsValid(e, '#creditcard_number');
                                                                            }
                                                                 });

                                                            jQuery('#creditcard_number').validator().on('keyup', function (e) {
                                                                                 console.log("submit");
                                                                          if (e.isDefaultPrevented()) {
                                                                            // handle the invalid form...
                                                                            check_cardnumber_IsValid(e, '#creditcard_number');
                                                                          } else {
                                                                            // everything looks good!
                                                                           check_cardnumber_IsValid(e, '#creditcard_number');
                                                                            }
                                                                 });

                                                             jQuery('#creditcard_type').validator().on('change', function (e) {
                                                                                 console.log("submit");
                                                                          if (e.isDefaultPrevented()) {
                                                                            // handle the invalid form...
                                                                            check_cardnumber_IsValid(e, '#creditcard_number');
                                                                          } else {
                                                                            // everything looks good!
                                                                           check_cardnumber_IsValid(e, '#creditcard_number');
                                                                            }
                                                                 });

                                                             /*jQuery('#creditcard_number').on('keyup', function () {
                                                            var i=0;
                                                                     for(i=4; i<20; i+4){
                                                                    console.log(i);
                                                                      }

                                                            });*/



                                                            /*jQuery("#creditcard_number").mask("9999-9999-9999-99?99");*/




                                                                /*jQuery('#creditcard_name').validator().on('keyup', function (e) {
                                                                      if (e.isDefaultPrevented()) {
                                                                        // handle the invalid form...
                                                                        checkStateIsValid(e, '#creditcard_name');

                                                                      } else {
                                                                        // everything looks good!
                                                                       checkStateIsValid(e, '#creditcard_name');

                                                                 }*/

                                                                 jQuery('#form-payment').on('submit', function(){
                                                                 var parent=jQuery('#payment_terms').closest('.form-group');
                                                                 if(!jQuery('input[name=payment_terms]').is(':checked')){
                                                                         var errText=jQuery('#payment_terms').attr("data-error");
                                                                         jQuery(".help-block.with-errors",parent).html(errText);
                                                                      } else {
                                                                         jQuery(".help-block.with-errors",parent).html('');
                                                                    }
                                                                 });

                                                                 jQuery('#payment_terms').on('change', function(){

                                                                 var parent=jQuery(this).closest('.form-group');
                                                                 if(jQuery('input[name=payment_terms]').is(':checked')){

                                                                     jQuery(".help-block.with-errors",parent).html('');
                                                                     }
                                                                 });

                                                                 $id_form = jQuery('#form-payment');
                                                                 $id_form.validator().on('submit', function (e) {
                                                                 $div_error = $id_form.find('.has-error').first();
                                                                 $div_error_offset = $div_error.offset().top;
                                                                 $view_height= jQuery( window ).height();
                                                                 window.scrollTo(0, $div_error_offset-$view_height/2);});






                                            });

                                    </script>

                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address1_shipping" name="address1_shipping" placeholder="Address Line 1*" data-error="Enter your Address" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address2_shipping" name="address2_shipping" placeholder="Address Line 2" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            </div>

                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="city_shipping" name="city_shipping" placeholder="City*" data-error="Enter your city" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <select id="state_shipping" name="state_shipping" class="form-control selectpicker show-tick"  data-error="Select a State or a Province" title="" required>

                                        <!--<option value="State" disabled selected>state</option>-->
                                        <?php
                                        $i=0;
                                            foreach($us_state_states as $key => $value){
                                                $i++;
                                                if($i=0){$disabled='disabled selected';}
                                                echo "<option value='".$value."'>".$value."</option>";}
                                         ?>

                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="zip_shipping" name="zip_shipping" data-error="Please enter your Zip Code" pattern="[0-9]{5}" placeholder="Zip*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            </div>

                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="email"   class="form-control" id="email_shipping" name="email_shipping" placeholder="Email*" data-error="Please provide a valid Email Address"  required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="phone_shipping" name="phone_shipping" pattern="[0-9/-]{9,12}" placeholder="Phone*: 123-456-7890" data-error="Please provide a valid Phone Number" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-xs-12"><small><i>* Please note that we cannot ship to PO Boxes</i></small></div>
                        </div>
                        <label>Shipping Options*</label>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">


                                    <?php
                                        global $wpdb;
                                        $chiamata1="CALL IPER_MA_SHIPPING_LIST()";
                                        $shipping=callDBStored($chiamata1);
   /* print_r($shipping);*/

                                        function cmp($a, $b)
                                        {
                                            if ($a["prezzo"] == $b["prezzo"]) {
                                                return 0;
                                            }
                                            return ($a["prezzo"] < $b["prezzo"]) ? -1 : 1;
                                        }

                                        if(!empty($shipping)){

                                            function decimal($number){
                                                $num = explode('.', $number);
                                                return $num[1];
                                            }

                                            $i=0;
                                            $c=0;

                                            $cart_session = $_SESSION["iper_cart"]["products"];
                                            foreach($cart_session as $ses){
                                                $product_id = $ses['db_id'];
                                                $plan_id = $ses['plan']['plan_id'];

                                            }

                                            $rateplan_call = $wpdb->prepare("CALL IPER_MA_RATEPLAN_LIST(%d)", $product_id);
                                            $rate_plans = callDBStored($rateplan_call);
                                            foreach($rate_plans as $plan){
                                                if($plan->idRATEPLAN==$plan_id){
                                                    $plan_term = $plan->Term;
                                                }
                                            }

                                            foreach($shipping as $ship){


                                                 $post_ship = get_post($ship->META_WP_ID);


                                                $price =$ship->Price;
                                                 if(strlen(decimal($price))=='1'){

                                                    $price .=0;}
                                                if(strlen(decimal($price))=='0'){

                                                    $price .=".00";}

                                                /*$shipping_method[$post_ship->post_title] = $price;*/
                                                /*print_r($ship->ShippingID);*/

                                                if ($plan_term!='Annually'){
                                                    if ($ship->Price!="0"){
                                                        $shipping_method[$c]['id'] = $ship->ShippingID;
                                                        $shipping_method[$c]['title'] = $post_ship->post_title;
                                                        $shipping_method[$c]['prezzo'] = $price;

                                                        $c++;
                                                    }
                                                }else{

                                                    $shipping_method[$c]['id'] = $ship->ShippingID;
                                                    $shipping_method[$c]['title'] = $post_ship->post_title;
                                                    $shipping_method[$c]['prezzo'] = $price;

                                                    $c++;

                                                }
                                            }





                                            usort($shipping_method,"cmp");

                                            $count = 0;
                                            foreach($shipping_method as $k => $j){

                                                //if( $j['prezzo'] == '0.00' ){$j['prezzo']="";}
                                                ?>

                                                <div class="radio">
                                                    <input type="radio" name="shippingType" id="shippingType_<?php echo $k;?>" value="<?php echo $j['id'];?>" <?php if($i==0){ ?> checked="checked" <?php } ;?> >
                                                    <label for="shippingType_<?php echo $k;?>"><?php echo $j['title'];?> (<?php echo $j['prezzo'];?>)</label>
                                                </div>
                                                <?php
                                                $count++;
                                                $i++;
                                            }

                                        }


                                        /*$posts_array = array_reverse($posts_array);
                                        foreach($posts_array as $key => $post){
                                        if($post->post_title=="Ground Shipping"){$x = 'checked="checked"';} else {$x='';}

                                        $html = '
                                        <div class="radio">
                                        <input type="radio" name="shippingType" id="shippingType'.$key.'" value="'.$post->post_title.'" data-price="$9.99" '.$x.'>
                                        <label for="shippingType'.$key.'"><span class="name">'.$post->post_title.'</span> <span class="price">$9.99</span></label>
                                        </div>';

                                        echo $html;

                                    }*/ ?>



                                    <!--<div class="radio">
                                        <input type="radio" name="shippingType" id="shippingType2" value="2 Day Shipping" data-price="$19.99">
                                        <label for="shippingType2"><span class="name">2 Day Shipping</span> <span class="price">$19.99</span></label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="shippingType" id="shippingType3" value="Overnight Shipping" data-price="$39.99">
                                        <label for="shippingType3"><span class="name">Overnight Shipping</span> <span class="price">$39.99</span></label>
                                    </div>-->

                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="container-form-payment billing-detail">
                        <div class="title-page">Billing Address</div>
                        <div class="checkbox">
                                            <input id="billing_sameas_shipping" name="billing_sameas_shipping" type="checkbox">
                                            <label for="billing_sameas_shipping">Billing Address is the same as Shipping Address</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="firstName_billing" name="firstName_billing" data-error="Please enter your First Name" placeholder="First Name*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="lastName_billing" name="lastName_billing" data-error="Please enter your Last Name" placeholder="Last Name*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has feedback">
                                    <select id="country_billing" name="country_billing" class="form-control selectpicker show-tick"  title="Country*" data-error="Please enter your Country" required>
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>
                                        <div class="help-block with-errors"></div>
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address1_billing" name="address1_billing" placeholder="Address Line 1*" data-error ="Please enter your Address" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address2_billing" name="address2_billing" placeholder="Address Line 2" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>

                             </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="city_billing" name="city_billing" placeholder="City*" data-error ="Please enter your city" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <select id="state_billing" name="state_billing" class="form-control selectpicker show-tick" title="" data-error="Select a State or a Province" required>
                                        <?php
                                            foreach($us_state_states as $key => $value){
                                                echo "<option value='".$value."'>".$value."</option>";}
                                        ?>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="zip_billing" name="zip_billing" placeholder="Zip*" pattern="[0-9]{5}" data-error ="Please enter your Zip Code" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="phone_billing" name="phone_billing" placeholder="Phone*:123-456-7890" pattern="[0-9/-]{9,12}" data-error ="Please provide a valid Phone Number" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" id="email_billing" name="email_billing" placeholder="Email*" data-error="Please provide a valid Email Address" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>


                    <div class="title-page"> Payment Information</div>
                        </div>


                    <div class="container-form-payment">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="radio has-feedback">
                                    <input type="radio" name="creditCardOption" id="creditCardOption" value="credit_card"  checked>
                                    <label for="creditCardOption">Credit Card</label>


                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select id="creditcard_type" name="creditcard_type" class="form-control selectpicker show-tick"  title="Discover*" required>
                                        <option value="Visa">Visa</option>
                                        <option value="MasterCard">Master Card</option>
                                        <option value="American Express">American Express</option>
                                        <option value="Discover">Discover</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control"   id="creditcard_number" name="creditcard_number" placeholder="Credit Card Number*: xxxx-xxxx-xxxx-xxxx" pattern="[0-9/-]{13,23}" data-error="Please provide a valid credit card number" required>
                                    <span class="glyphicon_search glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                          <div class="distance2">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="creditcard_name" name="creditcard_name" placeholder="Name On Card*" data-error ="Please enter your Credit Card Name" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                                <div class="col-sm-6 col-md-3 distance">
                                <label class="exp_date">Expiration Date</label>
                                    <div class="form-group">
                                        <select id="creditcard_month" name="creditcard_month" class="form-control selectpicker show-tick" title="Month*" required>
                                <?php for($i=1;$i<13;$i++): ?>
                                    <?php if($i>=date('n')){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php }?>
                                <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <select id="creditcard_year" name="creditcard_year" class="form-control selectpicker show-tick"  title="Year*" required>
                                                                                    <?php
                                            $cY=date("Y");
                                        ?>
                                        <?php for($i=$cY-1;$i<$cY+10;$i++): ?>
                                <?php if($i>=date('Y')){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php } ?>
                                        <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                           </div>


                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                            <?php

                                $terms_and_condition=get_post(454);
                                $terms_and_condition_body=$terms_and_condition->post_content;
                                ?>

                                <!--<div class="form-group has-feedback">
                                    <div class="checkbox">
                                        <input type="checkbox" id="payment_terms" name="payment_terms" required>
                                        <label for="payment_terms">I agree with the <a href="#terms_text" data-toggle="collapse">Terms and Conditions</a></label>
                                    </div>
                                </div>
                                <div id="terms_text" class="collapse">
                                    <button type="button" class="close2" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <span><?php /*echo $terms_and_condition->post_title; */?></span>
                                    <div class="space2"><?php /*echo $terms_and_condition_body;*/?></div>
                                </div>-->

                                <div class="form-group has-feedback">
                                    <div class="checkbox">
                                    <input type="checkbox" class="form-control" id="payment_terms" name="payment_terms" data-error="You need to agree to the Terms and Conditions before placing your order" required>
                                    <label for="payment_terms">I agree with the <a href="#demo" data-toggle="collapse" >Terms and Conditions </a></label>
                                    <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div id="demo" class="collapse">
                                   <!-- <span><?php /*echo $terms_and_condition->post_title; */?></span>-->
                                    <div class="space2 terms_text"><?php echo $terms_and_condition_body;?></div>
                                </div>

                            </div>
                        </div>

                        <button id="place_order" class="btn btn-red btn-big btn-block">Place Order</button>
                    </div>
                                        </div>


                </div>
                <!--<div class="col-sm-4">
                    <div id="preview-order-payment">
                        <div class="title">Order Summary</div>
                        <div class="order-detail">
                            <div class="product">
                                <span class="name"><strong>In Home</strong> (Landline)  <a href="<?php /*echo get_permalink($config['id_medical_product']); */?>" title="Edit">Edit</a></span> <span class="price">$29.95/mo</span>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="payment-type">
                                <div class="radio">
                                    <input type="radio" name="paymentType" id="paymentType" value="annual" checked>
                                    <label for="creditCardOption">Annual Plan <span class="price">$29.95/mo</span></label>
                                    <div class="clearfix"></div>
                                </div>
                                <ul>
                                    <li><strong>Accessory 1</strong> <span class="price">Included</span><div class="clearfix"></div></li>
                                    <li><strong>Accessory 2</strong> <span class="price">Included</span><div class="clearfix"></div></li>
                                </ul>
                            </div>
                            <hr>
                            <ul class="shipping-tax">
                                <li class="tax"><strong>Tax</strong> <span class="price">$0.00</span><div class="clearfix"></div></li>
                                <li class="shipping"><strong>Shipping</strong> <span class="price">$9.90</span><div class="clearfix"></div></li>
                            </ul>
                            <hr>
                            <ul class="total">
                                <li class="tax"><strong>Total</strong> <span class="price">$XXX.XX</span><div class="clearfix"></div></li>
                            </ul>
                        </div>
                        <button class="btn btn-red btn-block">Place Order</button>
                    </div>
                </div>-->
            </div>

        </form>
    </div>

<?php include($ABS_path . "/footer.php");
get_footer(); ?>