<?php

    global $meta_post;
    $meta_post = array(
       array(
           'Medical Select Product', //Nome template
           'id_medical_home', //Option name
           'template-medical-home.php' //template path
       ),array(
           'Medical Order',
           'id_medical_order',
           'template-order.php'
       ),array(
           'Medical Profile',
           'id_medical_profile',
            'template-profile.php'
       ),array(
           'Medical Shipping',
           'id_medical_shipping',
            'template-shipping.php'
       ),array(
           'Medical Thank',
           'id_medical_thank',
           'template-thank.php'
       ),array(
           'Medical Select Accessories',
           'id_medical_accessories',
           'template-medical-accessories.php'
       )
    );

    foreach($meta_post as $page_template) {

        $medical_page_ID = get_page_by_title( $page_template[0] );

        if ($medical_page_ID) {
            //if page exist update only page template
            update_post_meta($medical_page_ID->ID, '_wp_page_template', 'templates/'.$page_template[2]);
            update_option($page_template[1], $medical_page_ID->ID);

        } else {
            //if page NOT exist create page with template page
            $medical_page = array(
                'post_type'   => 'page',
                'post_title'  => $page_template[0],
                'post_status' => 'publish',
                'post_author' => 1
            );

            $page_id = wp_insert_post($medical_page);
            update_post_meta($page_id, '_wp_page_template', 'templates/'.$page_template[2]);
            update_option($page_template[1], $page_id);
        }
    }

?>