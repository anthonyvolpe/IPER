<?php
session_start();
setcookie('sel_upsell', '', time() + (3600), "/");
$args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'date',
    'order'            => 'ASC',
    'post_type'        => 'rateplan'
);
$posts_array = get_posts( $args );

if(!$posts_array || empty($post->ID)) {
    $home_url = "location:" . home_url();
    header('location:'.$home_url);
}

global $wpdb,$post;

$product_wp_id=$post->ID;
$product_db_id=callDBStored($wpdb->prepare("call IPER_MA_PRODUCT_GET_ID_BY_WPID(%d)",$product_wp_id),true);
$product_db_id=$product_db_id->idPRODUCT;

/*$cookie_name = "sel_product";
$cookie_value = get_the_title($post->ID);
setcookie($cookie_name, $cookie_value, time() + (3600), "/");*/

iper_hook_css(); //richiama la funzione per settare gli stili. vedi functions.php
get_header();

$feat_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
$home_id = get_option('id_medical_home');
include_once('nav_bar.php');


?>

<div class="container">

    <div class="title-page"><span>2</span> Select a Payment Plan</div>


        <div id="slider-product">
            <div class="" role="listbox">
                <div class="item active">
                    <div class="row no-pad">
                        <div class="p_img col-md-5">
                            <?php if(isset($feat_image)&&(!empty($feat_image)))
                                {
                                    echo '<img src="'.$feat_image.'" alt="slide">';
                                }?>

                            <a class="call-out" title="Slide name">
                                <!--<hr style="margin:14px 0;">-->
                                <span><?php echo get_option(cta_title); ?></span>
                                <span class="number"><?php echo get_option(cta_descr); ?> <?php echo get_option(cta_tel); ?></span>
                                <!--<span class="action"></span>-->
                                <hr>
                            </a>

                            <a class="disclaimer" title="Slide name">
                                <span class="virgo">"</span>
                                <div class="testimonial_"><span><?php echo get_post_meta($post->ID, 'iper_product_testimonial_description',true); ?></span></div>
                                <div class="testimonial_name"><span class="testimonial_name_s"><?php echo get_post_meta($post->ID, 'iper_product_testimonial_name',true); ?></span></div>
                            </a>
                        </div>

                        <div class="col-md-7 no-pad " style="padding-left:0;">
                             <div class="body_text_detail">
                            <?php
                                $active = get_post_meta($post->ID, 'publish_in_frontpage', true);
                                if($active == 1){ echo "<div class='most-popular'>Most Popular!</div>"; }
                            ?>


                            <div class="clearfix"></div>

                            <div class="product-name product-id" data-id="<?php echo $post->ID; ?>"><?php echo get_the_title(); ?></div>
                            <div class="clearfix"></div>

                            <?php $content=$post->post_content; $content=apply_filters('the_content',$content); ?>
                            <?php echo $content;?>
                            <!--<a class="call-out" href="<?php /*echo get_permalink($config['id_medical_product']); */?>" title="Slide name">
                                <small><?php /*echo get_option(cta_title); */?></small>
                                <span class="number"><?php /*echo get_option(cta_tel); */?></span>
                                <span class="action"><?php /*echo get_option(cta_descr); */?></span>
                            </a>-->

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="content-product">

            <?php echo get_post_meta($post->ID, 'iper_rateplan_selected',true); ?>

            <!--<h5>It's the Perfect Solution for Active Seniors</h5>
            <p>Now you can leave home and still get help 24/7 with Mobile Alert - visit the grandchildren, go out to lunch, or shop at your favorite stores with full confidence.</p>
            <h5>With Mobile Alert, you can just get up and Go</h5>
            <p>Now you can go out and participate in all of your favorite activities - taking walks, gardening, lunching with friends or traveling - and still be able to get help in the event of a sudden fall or medical emergency. Mobile Alert, the medical alarm service that uses GPS technology to follow you at home and on the go, ensures that professional help is your constant companion.</p>
            -->
            <div class="clearfix"></div>

        </div>




        <div class="list-product list-payment-type">


            <div class="row">

                <?php
                    $initialPlan="";
                    $initialPlanID="";
                    //la chiamata al database per i rateplan è fatta in nav_bar.php

                    foreach ($rate_plans as $rate_plan) {

                        //verifica link dopo upsell

                        $rateplanID = $rate_plan->idRATEPLAN;
                        //se gli accessori non ci sono ti mando direttamente alla medical shipping
                        $accessory_group_list_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_GROUP_LIST(%d)", $rateplanID);
                        $groups_accessories        = callDBStored($accessory_group_list_call);
                        $group_ID                  = $groups_accessories->idACCESSORY_GROUP;


                        if($groups_accessories) { //se non ci sono gruppi di accessori ti mando allo shipping. Se ci sono gruppi, solo se tutti i gruppi hanno is_free 1 e included 0 ti mando allo shipping
                            $counter = 0;
                            foreach ($groups_accessories as $group) {
                                $is_free = $group->IncludedWithOrder;
                                $accessory_single_selection = $group->LimitRestriction;
                                   if($is_free=='1' && $accessory_single_selection=='0'){
                                       $counter++;
                                   }
                                }
                            if($counter != count($groups_accessories)){//vedo se ci sono accessory
                                $link_upsell = get_permalink($config['id_medical_accessories']);
                            }else {
                                $link_upsell = get_permalink($config['id_medical_shipping']);
                            }

                        } else { $link_upsell = get_permalink($config['id_medical_shipping']); }




                        $args = array(
                            'posts_per_page'   => -1,
                            'orderby'          => 'date',
                            'order'            => 'ASC',
                            'post_type'        => 'upsell'
                        );

                        $posts_array = get_posts( $args );
                        $rateplanID = $rate_plan->idRATEPLAN;
                        $upsells_call = $wpdb->prepare("CALL IPER_MA_UPSELL_LIST(%d)", $rateplanID);
                        $upsells = callDBStored($upsells_call);

                      /* */?><!--<pre><?php /*var_dump($upsells); */?></pre>--><?php

                        $post = get_post($rate_plan->META_WP_ID);
                        /*$nomeRatePlan    = $rate_plan->AccessoryName;*/

                        $price = $rate_plan->Price;
                        $monthly_price = $rate_plan->MonthlyPrice;
                        $promotion_call = $wpdb->prepare("CALL IPER_MA_PROMOTION_LIST(%d)", $rateplanID);
                        $promotion = callDBStored($promotion_call);

                        if(!empty($promotion)) {
                            foreach ($promotion as $pro) {
                                $price = $pro->Price;
                            }
                        }


                  ?>


                        <div class= "col-sm-6 col-md-3 card-column">
                            <div class="card-container <?php $active = get_post_meta($post->ID, 'publish_in_frontpage', true); if($active == 1){ $initialPlan=$post->ID.','.$price; $initialPlanID = $upsells[0]->idUPSEL; echo "active";} ?>">
                                    <div class="card" style="height:auto !important">
                                        <div class="title"><?php echo $post->post_title ?></div>
                                        <div class="content">
                                            <div class="post_id"><?php echo $post->ID; ?></div>
                                            <div class="plan_id"><?php echo $rateplanID; ?></div>
                                            <div class="upsell_id"><?php echo $upsells[0]->idUPSEL; ?></div>
                                            <div class="price">$<?php echo $monthly_price; ?></div>

                                            <?php

                                                if(!empty($promotion)){
                                                    foreach ($promotion as $pro){
                                                        if($pro->Name=="12-for-11" || $pro->Name=="6-for-5" ){
                                                            echo '<div class="payment-type">1 Month Free</div>';
                                                        }

                                                    }
                                                }


                                            ?>



                                            <div class="list-accessories">

                                                    <?php
                                                        /*$accessory_call = $wpdb->prepare("CALL IPER_MA_ACCESSORY_LIST(%d,0)", $rateplanID);
                                                        $accessory = callDBStored($accessory_call);
                                                        if( !empty($accessory)){
                                                            foreach ($accessory as $acc){
                                                               $post_acc = get_post($acc->META_WP_ID);
                                                                echo "<li>".$post_acc->post_title."</li>";
                                                            }
                                                        }*/
                                                        $content = $post->post_content;
                                                        $content=apply_filters('the_content',$content);
                                                        echo $content;


                                                    ?>


                                        </div>
                                    </div>
                                 </div>
                                <a class="btn btn-orange btn-block plan_select iperModalPlan btIperSelectPlan" data-toggle="modal" data-target="#modalProduct<?php echo $upsells[0]->idUPSEL; ?>" data-plan="<?php echo $rate_plan->META_WP_ID.','.$price;?>" data-plan-id="<?php echo $rate_plan->idRATEPLAN;?>" data-product="<?php echo $product_wp_id;?>" data-product-id="<?php echo $product_db_id;?>">Add this Item</a>
                            </div>
                        </div>

                        <?php

                        /* setcookie('upsell' , $post->ID, time()+3600);*/

                                $i=-1;
                                $wp_upsell_id = '';

                                    foreach($upsells as $upsell){
                                        $i++;
                                        $wp_upsell = get_post($upsell->META_WP_ID);

                                        ?>

                                        <div class="modal fade" id="modalProduct<?php echo $upsells[$i]->idUPSEL; ?>" tabindex="-1" role="dialog" aria-labelledby="modalProductLabel">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <div class="row">

                                                            <div class="col-md-5  text-center">
                                                                <div class="detail-plan">

                                                                    <div class="text-plan">
                                                                        <p><?php echo $wp_upsell->post_content;?></p>
                                                                    </div>
                                                                    <h1><?php echo $wp_upsell->post_title; ?></h1>

                                                                    <?php

                                                                        if(strlen(decimal_($upsell->Price))=='1'){
                                                                            $upsell->Price .=0;}
                                                                        if(strlen(decimal_($upsell->Price))=='0'){
                                                                            $upsell->Price .=".00";}

                                                                    ?>

                                                                    <span class="upsell_price">$<?php echo $upsell->Price; ?></span>
                                                                    <!-- <button type="button" class="btn btn-grey" data-dismiss="modal">No Thanks</button>-->
                                                                    <div class="modal-btns">
                                                                        <?php
                                                                            if($upsells[$i+1]->idUPSEL){
                                                                            $next_link = '#modalProduct'.$upsells[$i+1]->idUPSEL;
                                                                            $link_accessory = '';
                                                                            } else {
                                                                                $next_link= '';
                                                                                $link_accessory = $link_upsell;
                                                                            }
                                                                        ?>
                                                                        <a href="<?php echo $link_accessory; ?>" type="button" class="btn
                                                                        btn-grey2" data-toggle="modal" data-target="<?php echo $next_link; ?>"
                                                                           <?php echo $next_link!=''?'data-dismiss="modal"':''; ?>>No Thanks</a>
                                                                            <a href="<?php echo $link_accessory; ?>" type="button" class="btn
                                                                            btn-red large-btn iperModalUpsell" data-toggle="modal" data-target="<?php echo $next_link; ?>" data-upsell ="<?php echo $wp_upsell->ID.','.$upsell->Price; ?>" data-product-id="<?php echo $product_db_id;?>" data-upsell-id="<?php echo $upsell->idUPSEL;?>" data-dismiss="modal">Add This Item</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-7 upsell_img" <?php
                                                                $feat_image_upsell = wp_get_attachment_url(get_post_thumbnail_id($wp_upsell->ID));
                                                                if(isset($feat_image_upsell)&&(!empty($feat_image_upsell)))
                                                                {
                                                                    echo 'style="background: url('.$feat_image_upsell.') no-repeat center / cover; height: 100%; position:absolute; right:0;"';
                                                                }

                                                            ?>></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

            <?php } ?>


           <!-- <div class="row">
            <div class="col-md-5 col-md-push-7"> <div class="plan_check_error"></div><a class="btn blue_btn btn-block plan_select plan_select_final iperModalPlan" id="btIperSelectPlan" data-toggle="modal" data-target="#modalProduct<?php /*if($initialPlanID){echo $initialPlanID;} */?>" data-plan='<?php /*echo $initialPlan ;*/?>' data-plan-id="<?php /*echo $initialPlanID;*/?>" data-product="<?php /*echo $product_wp_id;*/?>" data-product-id="<?php /*echo $product_db_id;*/?>">Add This Item</a></div>
            </div>-->
        </div>
    </div>

    <script>
        (function($) {
            var colHeight = 0;
            $(".card-column .card-container .card").each(function( index ) {

                if($( this ).height() > colHeight){
                    colHeight=$( this ).height();
                }
            }).each(function( index ) {

                if(colHeight!=0){
                    $( this ).height(colHeight);
                }

            });

            function scroll(){
                window.scrollTo(0, $(".anchor_me").offset().top-90);
                console.log('scroll');
            }

            $(window).load(function(){
                scroll();
                console.log('document ready');
                console.log($(".anchor_me").offset().top);
            });

        })(jQuery);
    </script>

<?php
include($ABS_path . "/footer.php");
get_footer();
?>
   <!-- <a name="payment_plan_anchor"></a>-->