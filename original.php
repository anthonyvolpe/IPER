<div class="product-name product-id" data-id="<?php echo $post->ID; ?>"><i class="fa fa-wifi" ></i> <?php echo get_the_title(); ?></div>
<div class="clearfix"></div>

<div id="slider-product">
    <div class="" role="listbox">
        <div class="item active">
            <div class="row no-pad">
                <div class="p_img col-md-7">
                    <?php if(isset($feat_image)&&(!empty($feat_image)))
                    {
                        echo '<img src="'.$feat_image.'" alt="slide">';
                    }?>
                </div>

                <div class="col-md-5 no-pad" style="padding-left:0;">
                    <a class="disclaimer" title="Slide name">
                        <div class="testimonial_"><span><?php echo get_post_meta($post->ID, 'iper_product_testimonial_description',true); ?></span></div>
                        <div class="testimonial_name"><span class="testimonial_name_s"><?php echo get_post_meta($post->ID, 'iper_product_testimonial_name',true); ?></span></div>
                    </a>
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

<div class="content-product">


    <?php $content=$post->post_content; $content=apply_filters('the_content',$content); ?>
    <?php echo $content;?>

    <!--<h5>It's the Perfect Solution for Active Seniors</h5>
    <p>Now you can leave home and still get help 24/7 with Mobile Alert - visit the grandchildren, go out to lunch, or shop at your favorite stores with full confidence.</p>
    <h5>With Mobile Alert, you can just get up and Go</h5>
    <p>Now you can go out and participate in all of your favorite activities - taking walks, gardening, lunching with friends or traveling - and still be able to get help in the event of a sudden fall or medical emergency. Mobile Alert, the medical alarm service that uses GPS technology to follow you at home and on the go, ensures that professional help is your constant companion.</p>
    -->

    <div class="clearfix"></div>


</div>