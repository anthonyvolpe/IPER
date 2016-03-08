<?php
    // Global consts and vars.
    global $id_setting, $config;

    $home       =   get_option("id_medical_home");
    //$product    =   get_option("id_medical_product");
    $shipping   =   get_option("id_medical_shipping");
    $order      =   get_option("id_medical_order");
    $profile    =   get_option("id_medical_profile");
    $thank      =   get_option("id_medical_thank");
    $accessory  =   get_option("id_medical_accessories");
    $back_color =   get_option("id_background");

    $config = array();
    $config['id_medical_home']          = $home;
    //$config['id_medical_product']       = $product;
    $config['id_medical_shipping']      = $shipping;
    $config['id_medical_order']         = $order;
    $config['id_medical_profile']       = $profile;
    $config['id_medical_thank']         = $thank;
    $config['id_medical_accessories']   = $accessory;
    $config['id_background']            = $back_color;


    $id_setting = array(
        array( 'name' => 'API', 'option_name' => 'id_api_salesforce', 'type' => 'input', 'description' => 'Insert Link Api Salesforce',	'default' => ''),
        array( 'name' => 'Brand ID', 'option_name' => 'id_brand', 'type' => 'input', 'description' => 'Insert Brand ID',	'default' => ''),
        array( 'name' => 'Encrption KEY', 'option_name' => 'encrption_key', 'type' => 'input', 'description' => 'Encrption KEY',	'default' => ''),
        array( 'name' => 'Singnature KEY', 'option_name' => 'signature_key', 'type' => 'textarea', 'description' => 'Singnature KEY',	'default'
        => ''),

        array( 'name' => 'Call to Action', 'option_name' => 'title', 'type' => 'title', 'description' => '',	'default' => 'title'),
        array( 'name' => 'Call to Action Title ', 'option_name' => 'cta_title', 'type' => 'input', 'description' => 'Line 1',	'default' =>
            'Operators Are Standing By'),
        array( 'name' => 'Call to Action Tel ', 'option_name' => 'cta_tel', 'type' => 'input', 'description' => 'Telephone',	'default' => '1.800
        .800.2537'),
        array( 'name' => 'Call to Action Descr ', 'option_name' => 'cta_descr', 'type' => 'input', 'description' => 'Line 2',	'default' => 'CALL
        NOW TO ORDER!'),
        array( 'name' => 'Pages Template', 'option_name' => 'Pages Template', 'type' => 'title', 'description' => '',	'default' => 'title'),
        array( 'name' => 'Home', 'option_name' => 'id_medical_home', 'type' => 'select-page', 'description' => 'Select Home page',	'default' => ''),
        //array( 'name' => 'Product', 'option_name' => 'id_medical_product', 'type' => 'select-page', 'description' => 'Select product page',	'default' => ''),
        array( 'name' => 'Shipping', 'option_name' => 'id_medical_shipping', 'type' => 'select-page', 'description' => 'Select Shipping page',	'default' => ''),
        array( 'name' => 'Order Detail', 'option_name' => 'id_medical_order', 'type' => 'select-page', 'description' => 'Select Order page',	'default' => ''),
        array( 'name' => 'Profile Set up', 'option_name' => 'id_medical_profile', 'type' => 'select-page', 'description' => 'Select Profile page',	'default' => ''),
        array( 'name' => 'Thank', 'option_name' => 'id_medical_thank', 'type' => 'select-page', 'description' => 'Select Thank page',	'default' => ''),
        array( 'name' => 'Accessories', 'option_name' => 'id_medical_accessories', 'type' => 'select-page', 'description' => 'Select Select Accessories page',	'default' => ''),
        array( 'name' => 'Colors Selection', 'option_name' => 'Colors Selection', 'type' => 'title', 'description' => '',	'default' => 'title'),
        array( 'name' => 'Product Button Color', 'option_name' => 'id_product_color', 'type' => 'input', 'description' => 'Insert hexadecimal product button Color. <!--<span style="background-color:#da9333; color:#fff; padding:3px;" >Default:#da9333</span>-->',	'default' => '#da9333'),
        array( 'name' => 'Products background Color', 'option_name' => 'id_product_background_color', 'type' => 'input', 'description' => 'Insert hexadecimal products background Color. <!--<span style="background-color:#89d0f2; color:#fff; padding:3px;" >Default:#89d0f2</span>-->',	'default' => '#89d0f2'),
        array( 'name' => 'Objects in Evidence and Nav Color', 'option_name' => 'id_in_evidence_color', 'type' => 'input', 'description' => 'Insert hexadecimal Product in Evidence and Nav color, Modal window h1 and Upsell Price. <!--<span style="background-color:#0b2265; color:#fff; padding:3px;" >Default:#0b2265</span>-->',	'default' => '#0b2265'),
        array( 'name' => 'Accessories Button Color', 'option_name' => 'id_acc_btn_color', 'type' => 'input', 'description' => 'Insert hexadecimal accessories page button Color. <!--<span style="background-color:#a8a8a7; color:#fff; padding:3px;" >Default:#a8a8a7</span>-->',	'default' => '#a8a8a7'),
        array( 'name' => 'Title page, Place Order and Next Button Color', 'option_name' => 'id_next_btn_color', 'type' => 'input', 'description' => 'Insert hexadecimal Title page, Place Order and Next Button Color. <!--<span style="background-color:#9c3039; color:#fff; padding:3px;" >Default:#9c3039</span>-->',	'default' => '#9c3039')

    );

    add_filter('single_template','IperCptTemplate_single');
    add_filter('archive_template','IperCptTemplate_archive');

    remove_filter('the_content', 'wpautop');

    //route single-template
    if(!function_exists('IperCptTemplate_single')){

        function IperCptTemplate_single($single_template){

            global $post;
            $found = locate_template('single-product.php');
            if($post->post_type == 'product' && $found == ''){
                $single_template = dirname(__FILE__).'/templates/template-product.php';
            }

            return $single_template;
        }
    }
    //route archive-template
    if(!function_exists('IperCptTemplate_archive')){
        function IperCptTemplate_archive($template){
            if(is_post_type_archive('product')){
                $theme_files = array('archive-product.php');
                $exists_in_theme = locate_template($theme_files, false);
                if($exists_in_theme == ''){
                    return plugin_dir_path(__FILE__) . '/templates/template-medical-home.php';
                }
            }
            return $template;
        }
    }


    add_action('admin_menu', 'iper_menu_page');

    function iper_menu_page() {

        $themename = "Medical Alert Option";
        $shortname = "iper_medical_alert";
        $menu_slug="edit_posts";		//Capability

        add_object_page($themename, $themename, $menu_slug, $shortname,'id_main_admin');
    }

    function id_main_admin(){
        global $themename, $shortname,$id_setting;

        $themename = "Medical Alert Option";
        $save=false;
        if ( 'save' == $_REQUEST['action'] ) {
            $save=true;
            foreach($id_setting as $id_single) {
                update_option( $id_single['option_name'], $_POST[$id_single['option_name']] );
                $i++;
            }
        }

    if ( $save ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' Settings save..</strong></p></div>';
    ?>
    <div id="main_admin">
        <?php $admin_header = "<h1>".$themename." - Developed by <a href='http://www.iperdesign.com/it/'>Iperdesign</a></h1>";
        echo $admin_header;
        ?>
        <form method="post">
            <table>
                <?php
                    $i=0;
                    foreach($id_setting as $id_single) {?><tr><?php
                        switch($id_single['type'])
                        {
                            case 'input':
                            {
                            ?>
                                <td><?=$id_single['name'] ?></td>
                                <td><input type="text" name="<?=$id_single['option_name'];?>" id="<?=$id_single['option_name'];?>" value="<?php if ( get_option( $id_single['option_name'] ) != "") { echo stripslashes(get_option( $id_single['option_name'] 	)  ); } ?>" />
                                    <?php if(strpos($id_single['option_name'], 'color')){echo '<div style ="width:20px; height: 20px; margin-top: -25px;margin-left: 170px;position: absolute; background-color:'.get_option($id_single['option_name']).';" class="choosen_color"></div>';}?>
                                </td>

                                <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }
                            case 'title':
                            {
                                ?>
                                <td><h1><? echo $id_single['name'] ?></h1></td>
                                <?php
                                break;
                            }
                            case 'textarea':
                            {
                            ?>
                                <td><? echo $id_single['name'] ?></td>
                                <td><textarea name="<?=$id_single['option_name'];?>" id="<?=$id_single['option_name'];?>" ><?php if ( get_option( $id_single['option_name'] ) != "") { echo stripslashes(get_option( $id_single['option_name'] 	)  ); } ?></textarea></td>
                                <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }


                            case 'select-page':
                            {
                            ?>
                                <td><? echo $id_single['name']?></td>
                                <td><?php echo wp_dropdown_pages(array('echo' => 0,'name' => $id_single['option_name'], 'selected' => get_option($id_single['option_name']))); ?></td>
                                <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;

                            }
                            case 'select-category':
                            {
                            ?>
                                <td><?=$id_single['name']?></td>
                                        <td><?=wp_dropdown_categories(array('hide_empty'=>0,'echo' => 0,'name' => $id_single['option_name'], 'selected' => stripslashes(get_option($id_single['option_name']))));?></td>
                                        <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }

                            case 'select-product_tags':
                            {
                            ?>
                                <td><?=$id_single['name']?></td>
                                        <td><?=wp_dropdown_categories(array('hide_empty'=>0,'echo' => 0,'taxonomy' => 'product_tag','name' => $id_single['option_name'], 'selected' => stripslashes(get_option($id_single['option_name']))));?></td>
                                        <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }

                            case 'select-product_category':
                            {
                            ?>
                                <td><?=$id_single['name']?></td>
                                        <td><?=wp_dropdown_categories(array('hide_empty'=>0,'echo' => 0,'taxonomy' => 'wpsc_product_category','name' => $id_single['option_name'], 'selected' => stripslashes(get_option($id_single['option_name']))));?></td>
                                        <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }

                            case 'select-image':
                            {
                                ?>
                                <td><?=$id_single['name']?></td>
                                <td><?php images_dropdown($id_single['option_name'],$id_single['option_name'],stripslashes(get_option($id_single['option_name'])));?></td>
                                <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }

                            case 'select-pdf':
                            {
                                ?>
                                <td><?=$id_single['name']?></td>
                                <td><?php pdf_dropdown($id_single['option_name'],$id_single['option_name'],stripslashes(get_option($id_single['option_name'])));?></td>
                                <td><p class="description"><?=$id_single['description']?></p></td>
                            <?php
                                break;
                            }
                        }
                    $i++;?></tr><?php
                    }
                ?>
            </table>
        <div id="inputs">
            <input type="submit" value="save options" class="button-primary" />
            <input type="hidden" name="action" value="save" />
            </form>
        </div>
    </div>

    <?php include_once "admin-cron.php"; ?>
<?php }

    function theme_scripts_important() {
        wp_enqueue_style( 'bootstrap-select',        plugin_dir_url( __FILE__ ).'/templates/bootstrap/css/bootstrap-select.min.css', array(), false  );
        wp_enqueue_style( 'style',          plugin_dir_url( __FILE__ ).'/templates/style.css', false,filemtime( get_stylesheet_directory() . '/style.css' ), 'all');

        wp_enqueue_script( 'iperjs',        plugin_dir_url( __FILE__ ). '/templates/script/iperjs.js', array('jquery'), filemtime( get_stylesheet_directory() . '/js/iperjs.js' ), true );
        wp_enqueue_script( 'cart_functions',        plugin_dir_url( __FILE__ ). '/templates/script/cart_functions.js', array('jquery'), filemtime( get_stylesheet_directory() . '/js/cart_functions.js' ), true );
    }

    add_action( 'wp_enqueue_scripts', 'theme_scripts_important', 15 );

    function theme_scripts() {
        wp_enqueue_style( 'bootstrap',      plugin_dir_url( __FILE__ ).'templates/bootstrap/css/bootstrap.min.css', array(), false  );
        wp_enqueue_style( 'bootstrap-theme',   plugin_dir_url( __FILE__ ).'templates/bootstrap/css/bootstrap-theme.min.css', array(), false  );

        wp_enqueue_script( 'jquery',        plugin_dir_url( __FILE__ ). 'templates/script/jquery.min.js', array(), false, true );
        wp_enqueue_script( 'bootstrap-js',  plugin_dir_url( __FILE__ ). 'templates/bootstrap/js/bootstrap.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'bootstrap-select',        plugin_dir_url( __FILE__ ). '/templates/bootstrap/js/bootstrap-select.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'validator',           plugin_dir_url( __FILE__ ). 'templates/script/validator.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'sticky',      plugin_dir_url( __FILE__ ). 'templates/script/jquery.sticky.js', array('jquery'), false, true );
        wp_enqueue_script( 'handlebars',      plugin_dir_url( __FILE__ ). 'templates/script/handlebars-v4.0.5.js', array('jquery'), false, true );
    }

    add_action( 'wp_enqueue_scripts', 'theme_scripts' );

    // ADD ACTIVE BTN TO PRODUCT POST
    function publish_in_frontpage($post){

        $value = get_post_meta($post->ID, 'publish_in_frontpage', true);

        echo '<div class="misc-pub-section misc-pub-section-last"><span id="timestamp">'
            . '<label><input type="checkbox"' . (!empty($value) ? ' checked="checked" ' : null) . 'value="1" name="publish_in_frontpage" /> Featured</label>'
            .'</span></div>';
    }
    add_action( 'post_submitbox_misc_actions', 'publish_in_frontpage' );


    /*function add_testimonial($post){

        $value = get_post_meta($post->ID, 'testimonial', true);

        echo '<div class="misc-pub-section misc-pub-section-last"><span id="timestamp">'
            . '<label><input type="text" name="testimonial" /> Testimonial</label>'
            .'</span></div>';
    }
    add_action( 'post_submitbox_misc_actions', 'add_testimonial' );

    function save_testimonial_data($postid){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
        if ( !current_user_can( 'edit_page', $postid ) ) return false;
        if(empty($postid) || $_POST['post_type'] != 'product') return false;

        if($_POST['action'] == 'editpost'){
            delete_post_meta($postid, 'testimonial');
        }

        add_post_meta($postid, 'testimonial', $_POST['testimonial']);

        update_post_meta($postid, '_wp_page_template', 'templates/template-product.php');
    }
    add_action( 'save_post', 'save_testimonial_data');*/



    function save_postdata($postid){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
        if ( !current_user_can( 'edit_page', $postid ) ) return false;
        if(empty($postid) || $_POST['post_type'] != ('product' || 'rateplan')) return false;

        if($_POST['action'] == 'editpost'){
            delete_post_meta($postid, 'publish_in_frontpage');
        }

        add_post_meta($postid, 'publish_in_frontpage', $_POST['publish_in_frontpage']);

        update_post_meta($postid, '_wp_page_template', $_POST['page_template']);
    }
    add_action( 'save_post', 'save_postdata');

    function hex2rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;}



    function iper_set_style($btn_color, $card_color, $nav_active_color, $btn_accessories, $btn_place_order){

        ?>

        <style type="text/css">
            .btn-orange{background-color:<?php echo $btn_color; ?>;}
            .btn-orange:hover{color: <?php echo $nav_active_color; ?>;}

            /*.breadcrumb-navbar ul li{background-color:<?php echo $nav_active_color; ?>;}*/
            /*.breadcrumb-navbar ul li:hover{background-color:<?php echo $card_color; ?>;}
            .breadcrumb-navbar ul li:hover span{background-color:<?php echo $nav_active_color; ?>;}*/
            /*.breadcrumb-navbar ul li:last-child .item:before{border-color:transparent transparent transparent <?php echo $nav_active_color; ?>;}*/

            .list-product .card-container .card .content .list-accessories ul li:before{color:<?php echo $btn_color; ?>;}
            .breadcrumb-navbar ul li.active a{color:<?php echo $nav_active_color; ?>!important}
            /*.breadcrumb-navbar ul li a>span{background-color:<?php echo $card_color; ?>;}*/
            .list-product .card-container .card{background-color:<?php echo $card_color; ?>;}

            .list-product .card-container:hover .card,
            .list-product .card-container.active .card{background-color:<?php echo $nav_active_color; ?>;}

            #preview-select-payment .title{background-color:<?php echo $card_color; ?>;}
            #preview-order-payment .title{background-color:<?php echo $card_color; ?>;}

            .card-select:hover > a.btn, .card-select.active > a.btn{background-color:<?php echo $btn_color; ?>;}

            .btn-grey2{background-color:<?php echo $btn_accessories; ?>;}
            .card-select:hover .checkbox:before, .card-select.active .checkbox:before{color:<?php echo $btn_color; ?>;}
            .btn-grey1{background-color:<?php echo $btn_accessories; ?>;}
            .btn-grey1:hover{background-color:<?php echo $btn_color; ?>;}
            .btn-red1{background-color:<?php echo $btn_place_order; ?>;}
            .btn-red{background-color:<?php echo $btn_place_order; ?>;}
            .btn-grey{background-color:<?php echo $btn_accessories; ?>;}

            .title-page{ color:<?php echo $btn_place_order; ?>;}
            .title-page>span{background-color:<?php echo $btn_place_order; ?>;}

            .most-popular{background-color:<?php echo $btn_place_order; ?>!important;}
            .most-popular:after{border-color: transparent transparent transparent <?php echo $btn_place_order; ?>;}
            /*.#slider-home .item .call-out{background-color: rgba(13,13,13,0.5) url("images/arrow-slide-contact.png") no-repeat 80% top;!important*/

           #slider-home .item .disclaimer{background-color: <?php echo hex2rgba($nav_active_color, 0.9); ?>;}
            /*#slider-product .item .disclaimer{background-color: <?php echo hex2rgba($nav_active_color, 0.9); ?>;}*/

            .product-name{color:<?php echo $nav_active_color; ?>!important;  }
            .modal-body .detail-plan h1{color:<?php echo $nav_active_color; ?>}
            .modal-content .upsell_price{color:<?php echo $nav_active_color; ?>}
            #slider-product .item .call-out{<?php echo $btn_place_order; ?> url("images/arrow-slide-contact.png") no-repeat 80% top}

            @media (max-width: 992px) {
                button.close {
                    color: <?php echo $card_color; ?>;
                }
            }

        </style>

    <?php
    }

    if(!function_exists('callDBStored')){
        function callDBStored($preparedData,$single=false,$type=OBJECT)
        {

            $res    = false;
            $new_db = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
            if ($single) {
                $res = $new_db->get_row($preparedData, $type);
            } else {
                $res = $new_db->get_results($preparedData, $type);
            }

            if($new_db->last_errors!=""){
                die("ERROR ON SQL \n".$new_db->last_errors."\n SQL: ".$new_db->last_query);
            }

            unset($new_db);

            return $res;
        }
    }

    function call_the_style(){
        iper_set_style(get_option('id_product_color'), get_option('id_product_background_color'), get_option('id_in_evidence_color'), get_option('id_acc_btn_color'), get_option('id_next_btn_color'));
    }

    function iper_hook_css(){
        add_action('wp_head','call_the_style');
    }

    include_once("page-types.php");
    include_once("medical-shortcode.php");
    include_once("medical-metabox.php");
    
    // medical alert encryption classes
    include_once("encryption/message-content.php");
    include_once("encryption/message-content-signed.php");
    include_once("encryption/encrypted-content.php");
    include_once("encryption/secured-content.php");
?>