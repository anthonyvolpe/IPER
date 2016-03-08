<?php
//call_the_style();
session_start();
//[FP]
iper_hook_css();
get_header();

  /*  foreach($id_setting as $id_single) {

        print_r($id_single);
    }*///richiama la funzione per settare gli stili. vedi functions.php

/*    $idPRODUCT=1;

    $chiamta1=$wpdb->prepare("CALL IPER_MA_RATEPLAN_LIST(%d)",
        $idPRODUCT
    );

    $rateplans=callDBStored($chiamta1);

    if(!empty($rateplans)){
        foreach($rateplans as $single){

            $single->META_WP_ID;
            get_post($single->META_WP_ID);

        }

    }*/

?>

<div class="container_">
   <!-- <div id="slider-home" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <a href="#" title="Slide name"><img src="<?php echo $theme_url; ?>/images/slider1.png" alt="slide"></a>
                <a class="disclaimer" href="<?php echo get_permalink($config['id_medical_product']); ?>" title="Slide name">
                    <span>"Lorem ipsum dolor sit amet, consectetur adipiscing elit.”</span>
                </a>
                <a class="call-out" href="<?php echo get_permalink($config['id_medical_product']); ?>" title="Slide name">
                    <small><?php echo get_option(cta_title); ?></small>
                    <span class="number"><?php echo get_option(cta_tel); ?></span>
                    <span class="action"><?php echo get_option(cta_descr); ?></span>
                </a>
            </div>
            <div class="item">
                <a href="#" title="Slide name"><img src="<?php echo $theme_url; ?>/images/slider1.png" alt="slide"></a>
                <a class="disclaimer" href="<?php echo get_permalink($config['id_medical_product']); ?>" title="Slide name">
                    <span>"2Lorem ipsum dolor sit amet, consectetur adipiscing elit.”</span>
                </a>
                <a class="call-out" href="<?php echo get_permalink($config['id_medical_product']); ?>" title="Slide name">
                    <small>Operators Are Standing By</small>
                    <span class="number">1.800.800.2537</span>
                    <span class="action">CALL NOW TO ORDER!</span>
                </a>
            </div>
        </div>

    </div>



    <div class="title-list med-home">Select Your Product</div>
    <hr>
    </div>-->

    <?php include_once('nav_bar.php'); ?>

    <div class="row">
        <div id="home-content" class="col-xs-12">

        <?php echo $post->post_content;?>

        </div>
    </div>



    <?php echo do_shortcode('[product_medical_list]'); ?>

</div>

<?php include($ABS_path . "/footer.php");
get_footer(); ?>