<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if(get_the_post_thumbnail(get_the_ID(),'large')) {
        echo get_the_post_thumbnail(get_the_ID(),'large');
    } ?>

    <?php 

    //Price
    $price = esc_html(get_post_meta(get_the_ID(),'rentright_price', true));
    
    //Location
    $ale_location = '';
    $locations = get_the_terms(get_the_ID(),'location');

    foreach($locations as $location){
        $ale_location.= " ".$location->name;
    }

    //Agents
    $agent_id = get_post_meta(get_the_ID(),'rentright_agent', true);
    $agent = get_post($agent_id);
    
    ?>

    <h2><?php the_title(); ?></h2>
    <div class="description"><?php the_excerpt(); ?></div>
    <div class="property_info">
        <span class="location"><?php esc_html_e('Location:','rentright'); 
        
        echo esc_html($ale_location);

        ?> </span>
        <span class="type"><?php esc_html_e('Type:','rentright'); 
        $types = get_the_terms(get_the_ID(),'property-type');

        foreach($types as $type){
            echo " ".esc_html($type->name);
        }
        ?> </span>

        <span class="price"><?php esc_html_e('Price:','rentright'); echo ' '.$price;?> </span>

        <span class="offer"><?php esc_html_e('Offer:','rentright'); echo ' '.esc_html(get_post_meta(get_the_ID(),'rentright_type', true));?> </span>
        <span class="agent"><?php esc_html_e('Agent:','rentright'); 
        
        echo " ".esc_html($agent->post_title);
        ?> </span>
    </div>
    <a href="<?php the_permalink(); ?>">Open This Property</a>
</article>