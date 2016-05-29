<?php
	/*	
	*	Goodlayers Attorney Option file
	*	---------------------------------------------------------------------
	*	This file creates all attorney options and attached to the theme
	*	---------------------------------------------------------------------
	*/
	
	// add action to create attorney post type
	add_action( 'init', 'gdlr_create_attorney' );
	if( !function_exists('gdlr_create_attorney') ){
		function gdlr_create_attorney() {
			global $theme_option;
			
			if( !empty($theme_option['attorney-slug']) ){
				$attorney_slug = $theme_option['attorney-slug'];
				$attorney_category_slug = $theme_option['attorney-category-slug'];
			}else{
				$attorney_slug = 'attorney';
				$attorney_category_slug = 'attorney_category';
			}
			
			register_post_type( 'attorney',
				array(
					'labels' => array(
						'name'               => __('Attorneys', 'gdlr-lawyer'),
						'singular_name'      => __('Attorney', 'gdlr-lawyer'),
						'add_new'            => __('Add New', 'gdlr-lawyer'),
						'add_new_item'       => __('Add New Attorney', 'gdlr-lawyer'),
						'edit_item'          => __('Edit Attorney', 'gdlr-lawyer'),
						'new_item'           => __('New Attorney', 'gdlr-lawyer'),
						'all_items'          => __('All Attorneys', 'gdlr-lawyer'),
						'view_item'          => __('View Attorney', 'gdlr-lawyer'),
						'search_items'       => __('Search Attorney', 'gdlr-lawyer'),
						'not_found'          => __('No attorneys found', 'gdlr-lawyer'),
						'not_found_in_trash' => __('No attorneys found in Trash', 'gdlr-lawyer'),
						'parent_item_colon'  => '',
						'menu_name'          => __('Attorneys', 'gdlr-lawyer')
					),
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => $attorney_slug  ),
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 5,
					'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
				)
			);
			
			// create attorney categories
			register_taxonomy(
				'attorney_category', array("attorney"), array(
					'hierarchical' => true,
					'show_admin_column' => true,
					'label' => __('Attorney Categories', 'gdlr-lawyer'), 
					'singular_label' => __('Attorney Category', 'gdlr-lawyer'), 
					'rewrite' => array( 'slug' => $attorney_category_slug  )));
			register_taxonomy_for_object_type('attorney_category', 'attorney');

			// add filter to style single template
			if( defined('WP_THEME_KEY') && WP_THEME_KEY == 'goodlayers' ){
				add_filter('single_template', 'gdlr_register_attorney_template');
			}
		}
	}

	if( !function_exists('gdlr_register_attorney_template') ){
		function gdlr_register_attorney_template($single_template) {
			global $post;

			if ($post->post_type == 'attorney') {
				$single_template = dirname(dirname( __FILE__ )) . '/single-attorney.php';
			}
			return $single_template;	
		}
	}
	
	// add a attorney option
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_attorney_options'); }
	if( !function_exists('gdlr_create_attorney_options') ){
	
		function gdlr_create_attorney_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('attorney'),
					'meta_title' => __('Goodlayers Aattorney Option', 'gdlr-lawyer'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => __('Page Layout', 'gdlr-lawyer'),
						'options' => array(
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-lawyer'),
								'type' => 'textarea'
							),							
							'header-background' => array(
								'title' => __('Header Background Image' , 'gdlr-lawyer'),
								'button' => __('Upload', 'gdlr-lawyer'),
								'type' => 'upload',
							),						
						)
					),
					
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-lawyer'),
						'options' => array(
							'position' => array(
								'title' => __('Position' , 'gdlr-lawyer'),
								'type' => 'text'
							),	
							'telephone' => array(
								'title' => __('Telephone' , 'gdlr-lawyer'),
								'type' => 'text'
							),	
							'email' => array(
								'title' => __('Email' , 'gdlr-lawyer'),
								'type' => 'text'
							),		
							'social' => array(
								'title' => __('Social ( Shortcode )' , 'gdlr-lawyer'),
								'type' => 'textarea'
							),						
						)
					),

				)
			);
			
		}
	}	
	
	// add attorney in page builder area
	add_filter('gdlr_page_builder_option', 'gdlr_register_attorney_item');
	if( !function_exists('gdlr_register_attorney_item') ){
		function gdlr_register_attorney_item( $page_builder = array() ){
			global $gdlr_spaces;
		
			$page_builder['content-item']['options']['attorney'] = array(
				'title'=> __('Attorney', 'gdlr-lawyer'), 
				'type'=>'item',
				'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-lawyer'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('attorney_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-lawyer')
					),			
					'attorney-size'=> array(
						'title'=> __('Attorney Size' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'1/4'=>'1/4',
							'1/3'=>'1/3',
							'1/2'=>'1/2',
							'1/1'=>'1/1'
						),
						'default'=>'1/3'
					),
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-lawyer'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of attorney you want to pull out.', 'gdlr-lawyer')
					),	
					'num-excerpt'=> array(
						'title'=> __('Num Excerpt' ,'gdlr-lawyer'),
						'type'=> 'text',	
						'default'=> '20'
					),					
					'attorney-layout'=> array(
						'title'=> __('Attorney Layout Order' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'fitRows' =>  __('FitRows ( Order items by row )', 'gdlr-lawyer'),
							'masonry' => __('Masonry ( Order items by spaces )', 'gdlr-lawyer'),
							'carousel' => __('Carousel ( Only For Grid And Modern Style )', 'gdlr-lawyer'),
						),
						'description'=> __('You can see an example of these two layout here', 'gdlr-lawyer') . 
							'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
					),					
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> gdlr_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-lawyer')
					),	
					'orderby'=> array(
						'title'=> __('Order By' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'date' => __('Publish Date', 'gdlr-lawyer'), 
							'title' => __('Title', 'gdlr-lawyer'), 
							'rand' => __('Random', 'gdlr-lawyer'), 
						)
					),
					'order'=> array(
						'title'=> __('Order' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'desc'=>__('Descending Order', 'gdlr-lawyer'), 
							'asc'=> __('Ascending Order', 'gdlr-lawyer'), 
						)
					),			
					'pagination'=> array(
						'title'=> __('Enable Pagination' ,'gdlr-lawyer'),
						'type'=> 'checkbox'
					),					
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-lawyer'),
						'type' => 'text',
						'default' => $gdlr_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-lawyer')
					),				
				))
			);
			return $page_builder;
		}
	}
	
?>