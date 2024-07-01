<?php


if(!class_exists('RentRightCpt')){
    class RentRightCpt{
        public function register(){
            add_action('init', [$this, 'custom_post_type']);

            add_action('add_meta_boxes', [$this, 'add_meta_box_property']);
            add_action('save_post',[$this,'save_metabox'],10,2);

        }

        public function add_meta_box_property(){
            add_meta_box(
                'rentright_settings',
                'Property Settings',
                [$this, 'metabox_property_html'],
                'property',
                'normal',
                'default',
            );
        }

        public function save_metabox($post_id,$post){

            if(!isset($_POST['_rentright']) || !wp_verify_nonce($_POST['_rentright'], 'rentrightfields')){
                return $post_id;
            }

            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
                return $post_id;
            }

            if($post->post_type != 'property'){
                return $post_id;
            }

            $post_type = get_post_type_object($post->post_type);
            if(!current_user_can($post_type->cap->edit_post,$post_id)){
                return $post_id;
            }
            
            if(is_null($_POST['rentright_price'])){
                delete_post_meta($post_id,'rentright_price');
            } else {
                update_post_meta($post_id,'rentright_price', sanitize_text_field(intval($_POST['rentright_price'])));
            }

            if(is_null($_POST['rentright_period'])){
                delete_post_meta($post_id,'rentright_period');
            } else {
                update_post_meta($post_id,'rentright_period', sanitize_text_field($_POST['rentright_period']));
            }

            if(is_null($_POST['rentright_type'])){
                delete_post_meta($post_id,'rentright_type');
            } else {
                update_post_meta($post_id,'rentright_type', sanitize_text_field($_POST['rentright_type']));
            }

            if(is_null($_POST['rentright_agent'])){
                delete_post_meta($post_id,'rentright_agent');
            } else {
                update_post_meta($post_id,'rentright_agent', sanitize_text_field($_POST['rentright_agent']));
            }

            return $post_id;
        }


        public function metabox_property_html($post){
            $price = get_post_meta($post->ID, 'rentright_price', true);
            $period = get_post_meta($post->ID, 'rentright_period', true);
            $type = get_post_meta($post->ID, 'rentright_type', true);
            $agent_meta = get_post_meta($post->ID, 'rentright_agent', true);

            wp_nonce_field('rentrightfields','_rentright');

            echo '
            <p>
                <label for="rentright_price">'.esc_html__('Price','rentright').'</label>
                <input type="number" id="rentright_price" name="rentright_price" value="'.esc_attr($price).'">
            </p>

            <p>
                <label for="rentright_period">'.esc_html__('Period','rentright').'</label>
                <input type="text" id="rentright_period" name="rentright_period" value="'.esc_attr($period).'">
            </p>

            <p>
                <label for="rentright_type">'.esc_html__('Type','rentright').'</label>
                <select id="rentright_type" name="rentright_type">
                    <option value="">Select Type</option>
                    <option value="sale" '.selected('sale',$type,false).'>'.esc_html__('For Sale','rentright').'</option>
                    <option value="rent" '.selected('rent',$type,false).'>'.esc_html__('For Rent','rentright').'</option>
                    <option value="sold" '.selected('sold',$type,false).'>'.esc_html__('Sold','rentright').'</option>
                </select>
            </p>
            ';

            $agents = get_posts(array('post_type'=>'agent','numberposts'=>-1));
            
            if($agents){
                echo '<p>
                <label for="rentright_agent">'.esc_html__('Agents','rentright').'</label>
                <select id="rentright_agent" name="rentright_agent">
                    <option value="">'.esc_html__('Select Agent','rentright').'</option>';

                foreach($agents as $agent){ ?>
                    <option value="<?php echo esc_html($agent->ID); ?>" <?php if($agent->ID == $agent_meta){echo 'selected'; } ?>><?php echo esc_html($agent->post_title) ?></option>
                <?php }

                echo '</select>
                </p>';
            }
        }

        public function custom_post_type(){
            register_post_type('property', 
            array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'properties'),
                'label' => 'Property',
                'supports' => array('title', 'editor', 'thumbnail'),
            ));
            register_post_type('agent', 
            array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'properties'),
                'label' => 'Agents',
                'supports' => array('title', 'editor', 'thumbnail'),
                'show_in_rest' => true,
            ));

            $labels = array(
                'name'              => esc_html_x( 'Locations', 'taxonomy general name', 'rentright' ),
                'singular_name'     => esc_html_x( 'Location', 'taxonomy singular name', 'rentright' ),
                'search_items'      => esc_html__( 'Search Locations', 'rentright' ),
                'all_items'         => esc_html__( 'All Locations', 'rentright' ),
                'parent_item'       => esc_html__( 'Parent Location', 'rentright' ),
                'parent_item_colon' => esc_html__( 'Parent Location:', 'rentright' ),
                'edit_item'         => esc_html__( 'Edit Location', 'rentright' ),
                'update_item'       => esc_html__( 'Update Location', 'rentright' ),
                'add_new_item'      => esc_html__( 'Add New Location', 'rentright' ),
                'new_item_name'     => esc_html__( 'New Location Name', 'rentright' ),
                'menu_name'         => esc_html__( 'Location', 'rentright' ),
            );

            $args = array(
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'properties/location'),
                'labels' => $labels,
            );

            register_taxonomy('location', 'property', $args);

            unset($args);
            unset($labels);

            $labels = array(
                'name'              => esc_html_x( 'Types', 'taxonomy general name', 'rentright' ),
                'singular_name'     => esc_html_x( 'Type', 'taxonomy singular name', 'rentright' ),
                'search_items'      => esc_html__( 'Search Types', 'rentright' ),
                'all_items'         => esc_html__( 'All Typens', 'rentright' ),
                'parent_item'       => esc_html__( 'Parent Type', 'rentright' ),
                'parent_item_colon' => esc_html__( 'Parent Type:', 'rentright' ),
                'edit_item'         => esc_html__( 'Edit Type', 'rentright' ),
                'update_item'       => esc_html__( 'Update Type', 'rentright' ),
                'add_new_item'      => esc_html__( 'Add New Type', 'rentright' ),
                'new_item_name'     => esc_html__( 'New Type Name', 'rentright' ),
                'menu_name'         => esc_html__( 'Type', 'rentright' ),
            );
            $args = array(
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug'=>'properties/type'),
                'labels' => $labels,
            );

            register_taxonomy('property-type','property',$args);
        }
    }
}

if(class_exists('RentRightCpt')){
    $rentRightCpt = new RentRightCpt();
    $rentRightCpt->register();
}