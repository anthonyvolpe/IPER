<?php


    global $post, $config, $index_medical;
    $index_medical=1;
?>


<nav class="navbar breadcrumb-navbar">
    <div class="container_">
        <ul class="list-inline">

            <?php




                $product_wp_id=$post->ID;
                $product_db_id=callDBStored($wpdb->prepare("call IPER_MA_PRODUCT_GET_ID_BY_WPID(%d)",$product_wp_id),true);
                $product_db_id=$product_db_id->idPRODUCT;

                $rateplan_call = $wpdb->prepare("CALL IPER_MA_RATEPLAN_LIST(%d)", $product_db_id);
                $rate_plans = callDBStored($rateplan_call);
                $they_exist = 0;

                foreach ($rate_plans as $rate_plan) {

                    $rateplanID = $rate_plan->idRATEPLAN;
                    $accessory_group_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_LIST(%d)", $rateplanID);
                    $groups_accessories = callDBStored($accessory_group_list_call);

                    if($groups_accessories) {
                        $counter = 0;
                        $they_exist++;

                        foreach ($groups_accessories as $group) {
                            $is_free = $group->IncludedWithOrder;
                            $accessory_single_selection = $group->LimitRestriction;

                            if($is_free=='1' && $accessory_single_selection=='0'){
                                $counter++; }

                            if($counter != count($groups_accessories)){
                                     $they_exist='1';
                            }else {  $they_exist='0';}
                        }

                    } else { $they_exist='0';}
                }

                $they_exist='0';


                if($post->ID==$config['id_medical_home']){
                    echo get_nav_bredcrumb(1, $they_exist);
                }
                if($post->post_type=='product'){
                    echo get_nav_bredcrumb(2, $they_exist);
                }
                if($post->ID==$config['id_medical_accessories']){
                    echo get_nav_bredcrumb(3, $they_exist);
                }
                if($post->ID==$config['id_medical_shipping']){
                    echo get_nav_bredcrumb(4, $they_exist);
                }
                if($post->ID==$config['id_medical_order']){
                    echo get_nav_bredcrumb(5, $they_exist);
                }
                if($post->ID==$config['id_medical_profile']){
                    echo get_nav_bredcrumb(6, $they_exist);
                }
                if($post->ID==$config['id_medical_thank']){
                    echo get_nav_bredcrumb(7, $they_exist);
                }



                function get_nav_bredcrumb($ord, $acc){

                         $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";




                    function link_active($link){
                        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($actual_link == $link){
                        return 'active';}
                    }


                    $aProduct=false;
                    foreach($_SESSION['iper_cart']['products'] as $single){
                        $aProduct=$single;
                    }

                    global $indice;

                    if($acc>0){
                        $accessory_exist='1';
                        $link_previous_shipping = get_permalink(get_option('id_medical_accessories'));
                        $link_after_plan=array( get_permalink(get_option('id_medical_accessories')), 'Medical Accessories');
                    } else{
                        $accessory_exist='0';
                        $link_previous_shipping = get_permalink($aProduct["wp_id"]);
                    $link_after_plan=array(get_permalink(get_option('id_medical_shipping')), 'Shipping & Billing');
                   }

                    $html = '<li class="'.link_active(get_the_permalink(get_option("id_medical_home"))).'">
                                <span class="item"><a href="'.get_the_permalink(get_option("id_medical_home")).'" title="Select Product"><span>1</span> Products</a></span>
                            </li>';
                    if($ord==1){ }elseif($ord>=2) {
                                            if ($ord>=2) {

                                                if($ord==2){
                                                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                                } else {$actual_link = get_permalink($aProduct["wp_id"]);}

                                                $back = '<li class="go-back">
                                                 <span class="item"><a href="'.get_the_permalink(get_option("id_medical_home")).'" title="go back"> Go Back</a></span>
                                                 </li>';
                                                $html .= '<li class="nav_ '.link_active($actual_link).'">
                                                         <a href="'.$actual_link.'" title="Payment Plan"><span>2</span> Payment Plan</a>
                                                        </li>';
                                                $next_link = '<li class="next_nav_">
                                             <span class="item"><span>3</span> '.$link_after_plan[1].'</span>
                                             </li>';
                                            }

                                            if ($ord>=3 && $accessory_exist=='1') {
                                                $back = '<li class="go-back">
                                                 <a href="'.get_permalink($aProduct["wp_id"]).'" title="go back">Go Back</a>
                                                 </li>';
                                                $html .= '
                                                    <li class="next_nav_ '.link_active(get_permalink(get_option('id_medical_accessories'))).'">
                                                         <a href="'.get_permalink(get_option('id_medical_accessories')).'" title="Select Medical Accessories"><span>3</span> Medical Accessories</a>
                                                    </li>';

                                                $next_link = '<li >
                                                                 <span class="item"><span>4</span> Shipping & Billing</span>
                                                            </li>';

                                            }
                                            if ($ord>=4) {
                                                $indice=3+$accessory_exist;

                                                $back = '<li class="go-back">
                                                 <a href="'.$link_previous_shipping.'" title="go back">Go Back</a>
                                                 </li>';

                                                $html .= '
                                                    <li class="'.link_active(get_permalink(get_option('id_medical_shipping'))).'">
                                                         <a href="'.get_permalink(get_option('id_medical_shipping')).'" title="Select Products"><span>'.$indice.'</span> Shipping & Billing</a>
                                                    </li>';

                                                $next_link = '<li class="next_nav_">
                                                                 <span class="item"><span>'.($indice+1).'</span> Your Order</span>
                                                            </li>';

                                            }
                                            if ($ord>=5) {
                                                $indice=4+$accessory_exist;
                                                $back = '<li class="go-back">
                                                 <a href="'.get_permalink(get_option('id_medical_shipping')).'" title="go back">Go Back</a>
                                                 </li>';

                                                $html .= '
                                                    <li class="'.link_active(get_permalink(get_option('id_medical_order'))).'">
                                                         <a href="'.get_permalink(get_option('id_medical_order')).'" title="Select Products"><span>'.$indice.'</span> Your Order</a>
                                                    </li>';

                                                $next_link = '<li class="next_nav_">
                                                                 <span class="item"><span>'.($indice+1).'</span> Profile Set Up</span>
                                                            </li>';

                                            }
                                            if ($ord>=6) {
                                                $indice=5+$accessory_exist;
                                                $back = '<li class="go-back">
                                                 <a href="'.get_permalink(get_option('id_medical_order')).'" title="go back">Go Back</a>
                                                 </li>';

                                                $html .= '
                                                    <li class="'.link_active(get_permalink(get_option('id_medical_profile'))).'">
                                                         <span class="item"><a href="'.get_permalink(get_option('id_medical_profile')).'" title="Select Products"><span>'.$indice.'</span> Profile Set Up</a></span>
                                                    </li>';

                                               /* $next_link = '<li>
                                                                 <span class="item"><span>7</span> Thank You</span>
                                                            </li>';*/
                                                $next_link = '<li class="next_nav_">
                                                                 <span class="item"><span>'.($indice+1).'</span> Profile Completed</span>
                                                            </li>';

                                            }
                                            if ($ord==7) {
                                                $back = '<li class="go-back">
                                                 <a href="'.get_permalink(get_option('id_medical_profile')).'" title="go back">Go Back</a>
                                                 </li>';

                                               /* $html .= '
                                                    <li>
                                                         <a href="'.get_permalink($config['id_medical_thank']).'" title="Select Products"><span>7</span> Thank You</a>
                                                    </li>';*/
                                                $html .= ' <li class="'.link_active(get_permalink(get_option('id_medical_thank'))).'">
                                                         <span class="item"><a href="'.get_permalink(get_option('id_medical_thank')).'" title="Profile Complete"><span>'.$indice.'</span> Profile Completed</a></span>
                                                    </li>';

                                                $next_link = '';

                                            }
                    }

                   $html .= $next_link;
                    $final_res = $back.$html;

                    return $final_res;

                }

             ?>


            </li>
        </ul>
    </div>
</nav>