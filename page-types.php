<?php
/**
 * Created by PhpStorm.
 * User: linus
 * Date: 28/01/16
 * Time: 11:02
 */


    function custom_post_type(){

        $posts_type = array(
            array(
                'Product',
                'Products',
                true,
                array('title', 'editor', 'thumbnail', 'revisions', 'excerpt')
            ),
            array(
                'RatePlan',
                'RatePlans',
                true,
                array('title', 'editor', 'thumbnail', 'revisions')
            ),
            array(
                'UpSell',
                'UpSells',
                true,
                array('title', 'editor', 'thumbnail', 'revisions')
            ),
            array(
                'Accessory',
                'Accessories',
                true,
                array('title', 'editor', 'thumbnail', 'revisions')
            ),
            array(
                'Shipping',
                'Shippings',
                false,
                array('title', 'editor', 'thumbnail', 'revisions')
            ),
        );


        foreach ($posts_type as $type) {

            $slug   = strtolower($type[0]);
            $labels = array(
                'name'               => _x($type[0], 'Post Type ' . $type[0], 'iper'),
                'singular_name'      => _x($type[0], 'Post Type ' . $type[0], 'iper'),
                'menu_name'          => __($type[1], 'iper'),
                'parent_item_colon'  => __('Parent ' . $type[1], 'iper'),
                'all_items'          => __('All ' . $type[1], 'iper'),
                'view_item'          => __('View ' . $type[1], 'iper'),
                'add_new_item'       => __('Add New ' . $type[0], 'iper'),
                'add_new'            => __('Add New', 'iper'),
                'edit_item'          => __('Edit ' . $type[0], 'iper'),
                'update_item'        => __('Update ' . $type[0], 'iper'),
                'search_items'       => __('Search ' . $type[1], 'iper'),
                'not_found'          => __('Not Found', 'iper'),
                'not_found_in_trash' => __('Not found in Trash', 'iper'),
            );


            $args = array(
                'label'               => __($type[0], ''),
                'description'         => __($type[0], ''),
                'labels'              => $labels,
                'supports'            => $type[3],
                'taxonomies'          => array(),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 5,
                'can_export'          => true,
                'has_archive'         => $type[2],
                'rewrite'             => array('slug' => $slug),
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
            );

            register_post_type($type[0], $args);
        }
    }
    add_action( 'init', 'custom_post_type', 0 );

/*add_filter( 'manage_rateplan_posts_columns', 'set_custom_edit_rateplan_columns' );
add_action( 'manage_rateplan_posts_custom_column' , 'custom_rateplan_column', 9, 2 );

function set_custom_edit_rateplan_columns($columns) {
    $columns['rateplan_product'] = __( 'Prodotto', 'your_text_domain' );

    return $columns;
}

function custom_rateplan_column( $column, $post_id ) {
    switch ( $column ) {

        case 'custom_rateplan_column' :
            echo "prodotto";
            break;
    }
}*/