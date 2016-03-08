<?php
function product_medical_list_shortcode(){



    /*$args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'date',
        'order'            => 'ASC',
        'post_type'        => 'product'
    );
    $posts_array = get_posts( $args );*/



    $html = '<div class="list-product">
                <div class="row">';


    $chiamata1="CALL IPER_MA_PRODUCT_LIST()";
    $products=callDBStored($chiamata1);


    global $wpdb;


    /*if(!empty($rateplans)){
        foreach($rateplans as $single){

            $single->META_WP_ID;
            get_post($single->META_WP_ID);

        }

    }*/



    if(!empty($products)){

        //calcola larghezza colonne
        $p_number = count($products);
        ( $p_number & 1 ) ? $num='dispari' : $num='pari' ;
        if($num='dispari'){ $col_size = 'col-md-4';} else { $col_size = 'col-md-3'; }


        foreach($products as $product){


            $post = get_post($product->META_WP_ID);
            $idRatePlan = $product->idRATEPLAN;

            //per ogni prodotto prendo il contenuto di wordpress del rateplan più economico
            $most_economic_rateplan = get_post($product->RATEPLAN_META_WP_ID);
            $most_economic_rateplan_content = $most_economic_rateplan->post_content;

            $most_economic_rateplan_content=apply_filters('the_content',$most_economic_rateplan_content);


            //stampo gli accessori del rateplan piu economico associato al prodotto
           /* $accessory_call=$wpdb->prepare("CALL IPER_MA_ACCESSORY_LIST(%d , %d)", $idRatePlan , 0);
            $rp_accessories=callDBStored($accessory_call);
                if(!empty($rp_accessories)) {
                    foreach ($rp_accessories as $accessory) {
                        $nomeAccessorio    = $accessory->AccessoryName;
                        $li_nomeAccessorio = '<li>' . $nomeAccessorio . '</li>';
                    }
                }*/

            $active = get_post_meta($post->ID, 'publish_in_frontpage', true);
            if($active == 1){ $class = 'active'; }else{ $class = ''; }
             $excerpt=$post->post_excerpt;
             $excerpt=apply_filters('the_content',$excerpt);

            if(get_post_meta($post->ID, 'iper_product_testimonial_learnmore_link',true) != ''){
                $learnmore_link = get_post_meta($post->ID, 'iper_product_testimonial_learnmore_link',true);
            } else { $learnmore_link = get_permalink($post->ID);}

            /*<div class="price">$'.$product->Price.'</div>
                                <div class="payment-type">with '.get_post_meta($post->ID, 'Rateplan_selected',true).' plan</div>
                                <div class="list-accessories"></div>*/
            $html .= '
             <div class=" col-sm-6 '.$col_size.' card-column">
             <div class="card-container '.$class.'">
                <div class="card">
                    <div class="title">'.$post->post_title.'</div>
                            <div class="content">
                                <a href="'.$learnmore_link.'" title="Learn More"><span>Learn More</span></a>
                                    '.$excerpt.'
                    </div>
                </div>
                <a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="btn btn-orange btn-block iperModalProduct" data-product="'.$post->ID.'" data-base-product-id="'.$product->fkPRODUCT.'">Select</a>
             </div>
            </div>';


        }
    }


   /* foreach($posts_array as $post){

        $active = get_post_meta($post->ID, 'publish_in_frontpage', true);
        if($active == 1){ $class = 'active'; }else{ $class = ''; }

        $html .= '
             <div class=" col-sm-6 col-md-3 card-column">
             <div class="card-container '.$class.'">
                <div class="card">
                    <div class="title">'.$post->post_title.'</div>
                            <div class="content">
                                <a href="" title="Learn More"><span>Learn More</span></a>
                                <div class="price">$29.95</div>
                                <div class="payment-type">with monthly plan</div>
                                <div class="list-accessories">
                                    <ul>
                                        <li>Pendant*</li>
                                        <li>Bracelet*</li>
                                    </ul>
                                    *Colors may vary
                            </div>
                    </div>
                </div>
                <a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="btn btn-orange btn-block iperModalProduct" data-product="'.$post->ID.'">Select</a>
             </div>
            </div>';


    }*/

    $html .='

            <script >
        (function($) {
                        var colHeight = 0;
                        $(".card-column .card-container .card").each(function( index ) {

                             if($( this ).height() > colHeight){
                                colHeight=$( this ).height();
                             }
                        }).each(function( index ) {

                             if(colHeight!=0){
                                $( this ).height(colHeight)
                             }

                        });

        })(jQuery);
            </script>


            </div>
        </div>';


    return $html;
}
    add_shortcode('product_medical_list', 'product_medical_list_shortcode');



    function half_page_shortcode($atts, $content = null) {
        return '<div class="col-xs-12 col-md-6">'.$content."</div>";
    }

    add_shortcode('half_page', 'half_page_shortcode');


    function container_shortcode($atts, $content = null) {
        return '<div class="p_body_container">'.do_shortcode($content)."</div>";
    }

    add_shortcode('container', 'container_shortcode');

    function large_col_text_shortcode($atts, $content = null) {
        return '<div class="p_body col-md-7 col-md-pull-5">'.$content."</div>";
    }

    add_shortcode('large_col_text', 'large_col_text_shortcode');

    function small_col_img_shortcode($atts, $content = null) {
        return '<div class="p_img2 col-md-5 col-md-push-7">'.$content."</div>";
    }

    add_shortcode('small_col_img', 'small_col_img_shortcode');

    function add_bullet_shortcode($atts, $content = null) {
        return '<li class="list-accessories">'.$content.'</li>';
    }

    add_shortcode('bullet', 'add_bullet_shortcode');

    function add_bullets_shortcode($atts, $content = null) {
        return '<div class="list-accessories"><div class="just_wrap"><ul>'.do_shortcode($content).'</ul></div></div>';
    }

    add_shortcode('bullets', 'add_bullets_shortcode');

    function add_plan_price_shortcode($atts, $content = null) {
        return '<div class="price">'.$content.'</div>';
    }

    add_shortcode('plan_price', 'add_plan_price_shortcode');

    function add_plan_paymentype_shortcode($atts, $content = null) {
        return '<div class="payment-type">'.$content.'</div>';
    }

    add_shortcode('payment_plan', 'add_plan_paymentype_shortcode');





   /* <div class="price">$'.$product->Price.'</div>
                                <div class="payment-type">with '.get_post_meta($post->ID, 'Rateplan_selected',true).' plan</div>
                                <div class="list-accessories">
                                    <ul>
'.$most_economic_rateplan_content.'
                                    </ul>
                                    *Colors may vary
</div>*/



?>