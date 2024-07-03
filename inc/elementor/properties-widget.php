<?php

class Elementor_Properties_Widget extends \Elementor\Widget_Base {

    protected $rentRight_Template;

    protected $rentLocations = array(''=>'Select Smth');
	
	public function get_name() {
		return 'rentright';
	}


	public function get_title() {
		return esc_html__( 'Properties List', 'rentright' );
	}


	public function get_icon() {
		return 'fa fa-code';
	}


	public function get_categories() {
		return [ 'rentright' ];
	}


	protected function _register_controls() {


        $temp_locations = get_terms('location');

        foreach($temp_locations as $location){
            $this->rentLocations[$location->term_id] = $location->name;
        }

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'rentright' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'count',
			[
				'label' => esc_html__( 'Posts Count', 'rentright' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
			]
		);

        $this->add_control(
			'offer',
			[
				'label' => esc_html__( 'Offer', 'rentright' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
                    '' => 'Select smth',
					'sale'  => esc_html__( 'For sale', 'rentright' ),
					'rent' => esc_html__( 'For Rent', 'rentright' ),
					'sold' => esc_html__( 'Sold', 'rentright' ),
				],
			]
		);

        $this->add_control(
			'location',
			[
				'label' => esc_html__( 'Location', 'rentright' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->rentLocations,
			]
		);

		$this->end_controls_section();

	}


	protected function render() {

		$settings = $this->get_settings_for_display();

        $args = array(
            'post_type' => 'property',
            'posts_per_page' => $settings['count'],
            'meta_query' => array('relation'=>'AND'),
            'tax_query' => array('relation'=>'AND'),
        );

        if(isset($settings['offer']) && $settings['offer'] != '' ){
            array_push($args['meta_query'],array(
                'key' => 'rentright_type',
                'value' => esc_attr($settings['offer']),
            ));
        }

        if(isset($settings['location']) && $settings['location'] != ''){
            array_push($args['tax_query'],array(
                'taxonomy' => 'location',
                'terms' => $settings['location'],
            ));
        }

        $properties = new WP_Query($args);


        $this->rentRight_Template = new RentRight_Template_Loader();

        if ( $properties->have_posts() ) {
            echo '<div class="wrapper archive_property">';
            while ( $properties->have_posts() ) {
                $properties->the_post(); 
            
                $this->rentRight_Template->get_template_part('partials/content');
            
            }
            echo '</div>';
        }
        wp_reset_postdata();
		

	}

}