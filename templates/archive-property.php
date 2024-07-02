<?php
get_header(); ?>

<?php $rentRight_Template->get_template_part('partials/filter');; ?>

<div class="wrapper archive_property">
    <?php 



    if(!empty($_POST['submit'])){
        
        $args = array(
            'post_type'=>'property',
            'posts_per_page' => -1,
            'meta_query' => array('relation'=>'AND'),
            'tax_query' => array('relation'=>'AND'),
        );

        if(isset($_POST['rentright_type']) && $_POST['rentright_type'] !=''){
            array_push($args['meta_query'],array(
                'key' => 'rentright_type',
                'value' => esc_attr($_POST['rentright_type']),
            ));
        }

        if(isset($_POST['rentright_price']) && $_POST['rentright_price'] !=''){
            array_push($args['meta_query'],array(
                'key' => 'rentright_price',
                'value' => esc_attr($_POST['rentright_price']),
                'type' => 'numeric',
                'compare' => '<=',
            ));
        }

        if(isset($_POST['rentright_agent']) && $_POST['rentright_agent'] !=''){
            array_push($args['meta_query'],array(
                'key' => 'rentright_agent',
                'value' => esc_attr($_POST['rentright_agent']),
            ));
        }

        if(isset($_POST['rentright_property-type']) && $_POST['rentright_property-type'] != ''){
            array_push($args['tax_query'],array(
                'taxonomy' => 'property-type',
                'terms' => $_POST['rentright_property-type'],
            ));
        }

        if(isset($_POST['rentright_location']) && $_POST['rentright_location'] != ''){
            array_push($args['tax_query'],array(
                'taxonomy' => 'location',
                'terms' => $_POST['rentright_location'],
            ));
        }

        $properties = new WP_Query($args);

        if ( $properties->have_posts() ) {

            // Load posts loop.
            while ( $properties->have_posts() ) {
                $properties->the_post(); 
            
                $rentRight_Template->get_template_part('partials/content');
            
            }
        } else {
            echo '<p>'.esc_html__('No Properties','rentright').'</p>';
        }

    } else {

        if ( have_posts() ) {

            // Load posts loop.
            while ( have_posts() ) {
                the_post(); 
            
                $rentRight_Template->get_template_part('partials/content');
            
            }
        
        //Pagination
        posts_nav_link();

        
        } else {
            echo '<p>'.esc_html__('No Properties','rentright').'</p>';
        }
    }
    ?>
</div>

<?php
get_footer();