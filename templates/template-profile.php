<?php session_start(); iper_hook_css(); get_header(); include_once('nav_bar.php'); global $indice;//richiama la funzione per settare gli stili. vedi functions.php?>

    <!--<nav class="navbar breadcrumb-navbar">
        <div class="container_">
            <ul class="list-inline">
                <li class="go-back">
                    <a href="<?php /*echo get_permalink($config['id_medical_order']); */?>" title="go back">Go Back</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_home']); */?>" title="Select Products"><span>1</span> Select Products</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_product']); */?>" title="Select Products"><span>2</span> Payment Plan</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_shipping']); */?>" title="Select Products"><span>3</span> Shipping & Billing</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_order']); */?>" title="Thank You"><span>4</span> Thank You</a>
                </li>
                <li>
                    <span class="item"><span>5</span> Profile Set Up</span>
                </li>
            </ul>
        </div>
    </nav>-->
<?php

    /*$us_state_states = array(
        'St'=>'State',
        'AL'=>'ALABAMA',
        'AK'=>'ALASKA',
        'AS'=>'AMERICAN SAMOA',
        'AZ'=>'ARIZONA',
        'AR'=>'ARKANSAS',
        'CA'=>'CALIFORNIA',
        'CO'=>'COLORADO',
        'CT'=>'CONNECTICUT',
        'DE'=>'DELAWARE',
        'DC'=>'DISTRICT OF COLUMBIA',
        'FM'=>'FEDERATED STATES OF MICRONESIA',
        'FL'=>'FLORIDA',
        'GA'=>'GEORGIA',
        'GU'=>'GUAM GU',
        'HI'=>'HAWAII',
        'ID'=>'IDAHO',
        'IL'=>'ILLINOIS',
        'IN'=>'INDIANA',
        'IA'=>'IOWA',
        'KS'=>'KANSAS',
        'KY'=>'KENTUCKY',
        'LA'=>'LOUISIANA',
        'ME'=>'MAINE',
        'MH'=>'MARSHALL ISLANDS',
        'MD'=>'MARYLAND',
        'MA'=>'MASSACHUSETTS',
        'MI'=>'MICHIGAN',
        'MN'=>'MINNESOTA',
        'MS'=>'MISSISSIPPI',
        'MO'=>'MISSOURI',
        'MT'=>'MONTANA',
        'NE'=>'NEBRASKA',
        'NV'=>'NEVADA',
        'NH'=>'NEW HAMPSHIRE',
        'NJ'=>'NEW JERSEY',
        'NM'=>'NEW MEXICO',
        'NY'=>'NEW YORK',
        'NC'=>'NORTH CAROLINA',
        'ND'=>'NORTH DAKOTA',
        'MP'=>'NORTHERN MARIANA ISLANDS',
        'OH'=>'OHIO',
        'OK'=>'OKLAHOMA',
        'OR'=>'OREGON',
        'PW'=>'PALAU',
        'PA'=>'PENNSYLVANIA',
        'PR'=>'PUERTO RICO',
        'RI'=>'RHODE ISLAND',
        'SC'=>'SOUTH CAROLINA',
        'SD'=>'SOUTH DAKOTA',
        'TN'=>'TENNESSEE',
        'TX'=>'TEXAS',
        'UT'=>'UTAH',
        'VT'=>'VERMONT',
        'VI'=>'VIRGIN ISLANDS',
        'VA'=>'VIRGINIA',
        'WA'=>'WASHINGTON',
        'WV'=>'WEST VIRGINIA',
        'WI'=>'WISCONSIN',
        'WY'=>'WYOMING',
        'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
        'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
        'AP'=>'ARMED FORCES PACIFIC'
    );

    $canadian_states_up = array(
        "Pr"=>'Province',
        "BC" => "BRITISH COLUMBIA",
        "ON" => "ONTARIO",
        "NL" => "NEWFOUNDLAND and LABRADOR",
        "NS" => "NOVA SCOTIA",
        "PE" => "PRINCE EDWARD ISLAND",
        "NB" => "NEW BRUNSWICK",
        "QC" => "QUEBEC",
        "MB" => "MANITOBA",
        "SK" => "SASKATCHEWAN",
        "AB" => "ALBERTA",
        "NT" => "NORTHWEST TERRITORIES",
        "NU" => "NUNAVUT",
        "YT" => "YUKON TERRITORY"
    );*/

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

    $objProfile =$_SESSION['shipping_info'];
    $hasData=is_array($_SESSION["md_profile"]);
?>
   <!-- <pre><?php /*print_r($objProfile); */?></pre>-->

    <script>
        function submitform()
                {
                var form = document.getElementById('form-payment');

                  document.form.submit();

                }

        var shippingInfo=jQuery.parseJSON("<?php echo addslashes(json_encode($objProfile));?>");
        var arrUS= jQuery.parseJSON('<?php echo addslashes(json_encode($us_state_states));?>');
        var arrCanada=jQuery.parseJSON('<?php echo addslashes(json_encode($canadian_states_up));?>');
        var oldState="US";

        jQuery(function(){




         /*jQuery('#state_shipping').on('changed.bs.select', function (e) {

         if(jQuery('#state_shipping').val()=="State"){
        jQuery('#state_shipping').closest('.form-group').addClass('has-error');
        console.log('hereeee');

                     }
             });


        jQuery('#complete_profile').on('click', function(){

            if(jQuery('#state_shipping').val()=="State"){

                jQuery('#state_shipping').closest('.form-group').addClass('has-error');
                 //jQuery('.state').toggleClass('has-error');
                 console.log('eccoci');
                 console.log( jQuery('#state_shipping').closest('.form-group'));
                 *//*jQuery('.help-block', '.state').html('<ul class="unstyled"><li>ciao</li></ul>');*//*
             }
        });*/

        function checkStateIsValid(e, id, value1, value2, value3, value4){
            var parent=jQuery(id).closest('.form-group');
            parent.removeClass('has-error');

            jQuery(".help-block.with-errors",parent).html("");


            if(jQuery(id).val()==value1 || jQuery(id).val()==value2 || jQuery(id).val()==value3 || jQuery(id).val()==value4){

                parent.addClass('has-error');
                 //jQuery('.state').toggleClass('has-error');

                 var errText=jQuery(id).attr("data-error");
                 jQuery(".help-block.with-errors",parent).html(errText);

                 e.preventDefault();
            }
        }

        function checkRelationship(){

        var parent=jQuery('#relationship1').closest('.form-group');
        parent.removeClass('has-error');
        jQuery(".help-block.with-errors",parent).html("");

        if(jQuery('#relationship1').val()=='rel'){
        var errText=jQuery('#relationship1').attr("data-error");
        jQuery(".help-block.with-errors",parent).html(errText);
         parent.addClass('has-error');
        }


        }

            jQuery('#form-payment').validator().on('submit', function (e) {
                  if (e.isDefaultPrevented()) {
                    // handle the invalid form...
                    checkStateIsValid(e, '#state_shipping', 'State*', 'Province*', 'St', 'Pr');
                    checkRelationship();

                  } else {
                    // everything looks good!
                   checkStateIsValid(e, '#state_shipping', 'State*', 'Province*', 'St', 'Pr');
                   checkRelationship();

                  }
                })

            jQuery("#billing_sameas_shipping").change(function(){
                if(jQuery(this).prop("checked")){
                    /*jQuery("#firstName_shipping").val(shippingInfo.Name);
                    jQuery("#lastName_shipping").val(shippingInfo.LastName);*/
                    jQuery("#country_shipping").val(shippingInfo.Country);
                    jQuery("#address1_shipping").val(shippingInfo.Street1);
                    jQuery("#address2_shipping").val(shippingInfo.Street2);
                    jQuery("#city_shipping").val(shippingInfo.City);
                    jQuery("#state_shipping").val(shippingInfo.State);
                    jQuery("#zip_shipping").val(shippingInfo.PostalCode);
                    jQuery("#email_shipping").val(shippingInfo.Mail);
                    jQuery("#phone_shipping").val(shippingInfo.Phone);


                    /*jQuery('#state_shipping').html("");
                    jQuery('#state_shipping option').each(function(){
                             var opt=jQuery(this).clone();
                            jQuery('#state_shipping').append(opt);


                      });*/
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


                    jQuery('#country_shipping').selectpicker('refresh');
                    jQuery('#state_shipping').selectpicker('refresh');

                    jQuery('#country_shipping').selectpicker('val',shippingInfo.Country);
                   jQuery('#state_shipping').selectpicker('val',shippingInfo.State);

                   jQuery('#country_shipping').selectpicker('refresh');
                    jQuery('#state_shipping').selectpicker('refresh');
                      }
            });


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

                    jQuery('input[name=another_person]').on('change', function() {
                       if(jQuery('input[name=another_person]:checked', '#form-payment').val()=='Yes'){
                          jQuery('.another_persone_use_the_system').toggleClass('hide_another_persone_use_the_system');
                 } else { jQuery('.another_persone_use_the_system').toggleClass('hide_another_persone_use_the_system');
                    }
                  });

                  var id_form = jQuery('#form-payment');
                  id_form.validator().on('submit', function (e) {
                 var div_error = id_form.find('.has-error').first();
                 var div_error_offset = div_error.offset().top;
                 var view_height= jQuery( window ).height();
                 window.scrollTo(0, div_error_offset-view_height/2);});


        });



    </script>

    <div class="container_">

        <form id="form-payment" data-toggle="validator" action="<?php echo get_permalink($config['id_medical_thank']); ?>" method="post" role="form">

            <div class="row">
                <div class="col-sm-8">

                    <div class="title-page"><span><?php echo $indice; ?></span> Set Up Your Profile</div>


                    <div class="container-form-payment">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="firstName_shipping" name="firstName_shipping" placeholder="First Name*" data-error="Please enter your First Name" required >
                                    <!--value="--><?php /*echo ($hasData) ? $_SESSION["md_profile"]["first_name"] : "";*/?>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="lastName_shipping" name="lastName_shipping" placeholder="Last Name*" data-error="Please enter your Second Name" required >
                                    <!--value="--><?php /*echo ($hasData) ? $_SESSION["md_profile"]["last_name"] : "";*/?>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12"><label>Date of Birth*</label></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select name="profile_month" id="Month" class="form-control selectpicker show-tick"  title="Month*" >
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="Day" name="profile_day" class="form-control selectpicker show-tick"  title="Day*" >
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="year" name="profile_year" class="form-control selectpicker show-tick" title="Year*" >
                                        <?php $cY=date("Y"); ?>
                                        <?php for($i=$cY-1;$i>$cY-132;$i--): ?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <label>Address where the device will be used*</label>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input id="billing_sameas_shipping" name="billing_sameas_shipping" type="checkbox" <?php if(isset($_POST['billing_sameas_shipping'])){echo 'checked';} ?>>
                                        <label for="billing_sameas_shipping">Same as shipping address</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <label>Country*</label>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select id="country_shipping" name="country_shipping" class="form-control selectpicker show-tick" title="Country*" required>
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address1_shipping" name="address1_shipping" placeholder="Address Line 1*" data-error="Insert the Address where the device will be used" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address2_shipping" name="address2_shipping" placeholder="Address Line 2" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="cross_street" name="cross_street" placeholder="Cross Street" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="city_shipping" name="city_shipping" placeholder="City*" data-error="Enter the city where the device will be used" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group state has-feedback">
                                    <select id="state_shipping" name="state_shipping" class="form-control selectpicker show-tick"  data-error="Select a State or Province"  title="" required>
                                       <?php
                                            $i=0;
                                    foreach($us_state_states as $key => $value){
                                        echo "<option value='".$value."'>".$value."</option>";}
                                        ?>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="zip_shipping" name="zip_shipping" placeholder="Zip*" pattern="[0-9]{5}" data-error="Please enter your Zip Code"required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" id="email_shipping" name="email_shipping" placeholder="Email*" data-error="Please provide a valid Email Address" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="phone_shipping" name="phone_shipping" pattern="[0-9/-]{9,13}" placeholder="Phone*:123-456-7890" data-error="Please provide a valid Phone Number" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <label>Will Anyone Else Use This System*</label>
                        <div class="row ">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <div class="radio">
                                            <input type="radio" name="another_person" id="another_person_yes"  value="Yes" >
                                            <label for="another_person_yes"><span class="name yes">Yes</span> </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="radio">
                                            <input type="radio" name="another_person" id="another_person_no"  value="No" checked="checked">
                                            <label for="another_person_no"><span class="name no">No</span> </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>




                    <div class="container-form-payment billing-detail">
                      <div class="another_persone_use_the_system hide_another_persone_use_the_system">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="anotherperson_firstname_text" name="anotherperson_firstname" placeholder="First Name*" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="anotherperson_lastname_text" name="anotherperson_lastname" placeholder="Last Name*" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="col-xs-12"><label>Date of Birth*</label></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="Month" name="another_person_month" class="form-control selectpicker show-tick" data-live-search="true" title="Month*" >
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="Day" name="another_person_day" class="form-control selectpicker show-tick" data-live-search="true" title="Day*" >

                                        <?php /*for($i=1; $i<=31; $i++){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        } */?>


                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="Year" name="another_person_year" class="form-control selectpicker show-tick" data-live-search="true" title="Year*" >
                                            <?php
/*                                                $cY=date("Y");
                                            */?>
                                            <?php /*for($i=$cY-1;$i>$cY-132;$i--): */?>
                                                <option value="<?php /*echo $i;*/?>"><?php /*echo $i;*/?></option>
                                                <?php /*endfor; */?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group has-feedback">
                                    <input type="tel" class="form-control" id="phone_billing" name="another_person_phone" placeholder="Phone" pattern="[0-9]{9,11}">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>-->
                      </div>
                        <!--<div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select id="country_billing" class="form-control selectpicker show-tick" data-live-search="true" title="Country*" required>
                                        <option value="United States">United States</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Messico">Messico</option>
                                    </select>
                                </div>
                            </div>
                        </div>-->
                     <!--   <div class="row">-->
                            <!--<div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address1_billing" placeholder="Address Line 1*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="address2_billing" placeholder="Address Line 2*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>-->
                            <!--<div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="city_billing" placeholder="City*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="state_billing" class="form-control selectpicker show-tick" data-live-search="true" title="State*" required>
                                        <option value="Philadelphia">Philadelphia</option>
                                        <option value="New York">New York</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="zip_billing" placeholder="Zip*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" id="email_billing" placeholder="Email*" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            -->
                        <!--</div>-->

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-subtitle1">Emergency Contact Information</div>
                            </div>

                                <div class="form-subtitle2 pad-left"><label>Primary Contact</label></div>


                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                        <input type="text" class="form-control" id="fname_emergency_contact" name="fname_emergency_contact" data-error="Please insert the emergency contact's First Name" placeholder="First Name*" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group has-feedback">

                                        <input type="text" class="form-control" id="sname_emergency_contact" name="sname_emergency_contact" data-error="Please insert the emergency contact's Last Name" placeholder="Last Name*" required>
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        <div class="help-block with-errors"></div>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                    <div>
                                        <select id="relationship1" name="1_emergencycontact_relationship" class="form-control selectpicker show-tick" data-error="Please select the emergency contact Relationship" title="Relationship" required>
                                        <option value="rel">Relationship</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Caretaker">Caretaker</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Ex-Husband">Ex-Husband</option>
                                        <option value="Ex-Wife">Ex-Wife</option>
                                        <option value="Father">Father</option>
                                        <option value="Friend">Friend</option>
                                        <option value="Granddaughter">Granddaughter</option>
                                        <option value="Grandfather">Grandfather</option>
                                        <option value="Grandmother">Grandmother</option>
                                        <option value="Grandparent">Grandparent</option>
                                        <option value="Grandson">Husband</option>
                                        <option value="Husband">Grandson</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Niece">Niece</option>
                                        <option value="Neighbor">Neighbor</option>
                                        <option value="Nephew">Nephew</option>
                                        <option value="Nurse">Nurse</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Son">Son</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Other">Other</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="phone_first_emergency_contact" name="phone_first_emergency_contact" pattern="[0-9/-]{9,13}" placeholder="Phone1*:123-456-7890" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <!--<div class="col-sm-6">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="second_phone_first_emergency_contact" name="s_phone_first_emergency_contact" pattern="[0-9]{9,11}" placeholder="Phone2">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>-->
                        </div>


                        <div class="row">
                            <div class="form-subtitle2 pad-left"><label>Secondary Contact</label></div>
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">

                                        <input type="text" class="form-control" id="fname_second_emergency_contact" name="fname_second_emergency_contact" placeholder="First Name">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                </div>
                            </div>
                            <div class="col-sm-4">

                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" id="sname_second_emergency_contact" name="sname_second_emergency_contact" placeholder="Last Name">
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div>
                                        <select id="relationship2" name="2_emergencycontact_relationship" class="form-control selectpicker show-tick" title="Relationship">
                                        <option value="">Relationship</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Caretaker">Caretaker</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Ex-Husband">Ex-Husband</option>
                                        <option value="Ex-Wife">Ex-Wife</option>
                                        <option value="Father">Father</option>
                                        <option value="Friend">Friend</option>
                                        <option value="Granddaughter">Granddaughter</option>
                                        <option value="Grandfather">Grandfather</option>
                                        <option value="Grandmother">Grandmother</option>
                                        <option value="Grandparent">Grandparent</option>
                                        <option value="Grandson">Grandson</option>
                                         <option value="Husband">Husband</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Niece">Niece</option>
                                        <option value="Neighbor">Neighbor</option>
                                        <option value="Nephew">Nephew</option>
                                        <option value="Nurse">Nurse</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Son">Son</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-8">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="phone_second_emergency_contact" name="phone_second_emergency_contact" pattern="[0-9/-]{9,13}" placeholder="Phone:123-456-7890" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row <?php if($_POST["fname_third_emergency_contact"]==""){echo 'hide_emergency_contact';}?> emergency_contact">
                            <div class="form-subtitle2 pad-left"><label>Third Contact</label></div>
                            <div class="col-sm-4">
                                <div class="form-group has-feedback">
                                    <div class="form-subtitle2">
                                        <input type="text" class="form-control" id="fname_third_emergency_contact" name="fname_third_emergency_contact" placeholder="First Name" >
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-subtitle2">
                                    <div class="form-group has-feedback">
                                        <input type="text" class="form-control" id="sname_third_emergency_contact" name="sname_third_emergency_contact" placeholder="Last Name" >
                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-subtitle2">
                                        <select id="relationship3" name="3_emergencycontact_relationship" class="form-control selectpicker show-tick"  title="Relationship" >
                                        <option value="">Relationship</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Caretaker">Caretaker</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Ex-Husband">Ex-Husband</option>
                                        <option value="Ex-Wife">Ex-Wife</option>
                                        <option value="Father">Father</option>
                                        <option value="Friend">Friend</option>
                                        <option value="Granddaughter">Granddaughter</option>
                                        <option value="Grandfather">Grandfather</option>
                                        <option value="Grandmother">Grandmother</option>
                                        <option value="Grandparent">Grandparent</option>
                                        <option value="Grandson">Grandson</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Niece">Niece</option>
                                        <option value="Neighbor">Neighbor</option>
                                        <option value="Nephew">Nephew</option>
                                        <option value="Nurse">Nurse</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Son">Son</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-8">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" id="phone_third_emergency_contact" name="phone_third_emergency_contact" pattern="[0-9/-]{9,13}" placeholder="Phone:123-456-7890" >
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>


                            <div id="_add_contact">

                            </div>

                        <div class="row">
                            <div class="col-xs-12"><label><a id="_a_add_contact" class="blue_big" >+Add Another Emergency Contact</a></label></div>
                            <div class="col-md-8">
                                <div class=" btn-to-continue">
                                <button id="complete_profile"  class="btn btn-red btn_ship">Complete Profile Set Up</button>
                                  <!--  <a href="<?php /*echo get_permalink($config['id_medical_thank']); */?>" class="btn btn-red btn_ship">Complete Profile Set Up</a>-->
                                </div>
                            </div>
                        </div>

                        </div>

                    </div>
                </div>
            </div>


        </form>
    </div>


<script type="javascript">
jQuery(function(){
    jQuery("#complete_profile").click(function(){$("form-payment").submit();});
});
</script>

<?php include($ABS_path . "/footer.php");
get_footer(); ?>