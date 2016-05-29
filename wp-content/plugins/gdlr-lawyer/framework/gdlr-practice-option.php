<?php
	/*	
	*	Goodlayers Practice Option file
	*	---------------------------------------------------------------------
	*	This file creates all practice options and attached to the theme
	*	---------------------------------------------------------------------
	*/
	
	// add action to create practice post type
	add_action( 'init', 'gdlr_create_practice' );
	if( !function_exists('gdlr_create_practice') ){
		function gdlr_create_practice() {
			global $theme_option;
			
			if( !empty($theme_option['practice-slug']) ){
				$practice_slug = $theme_option['practice-slug'];
				$practice_category_slug = $theme_option['practice-category-slug'];
			}else{
				$practice_slug = 'practice';
				$practice_category_slug = 'practice_category';
			}
			
			register_post_type( 'practice',
				array(
					'labels' => array(
						'name'               => __('Practices', 'gdlr-lawyer'),
						'singular_name'      => __('Practice', 'gdlr-lawyer'),
						'add_new'            => __('Add New', 'gdlr-lawyer'),
						'add_new_item'       => __('Add New Practice', 'gdlr-lawyer'),
						'edit_item'          => __('Edit Practice', 'gdlr-lawyer'),
						'new_item'           => __('New Practice', 'gdlr-lawyer'),
						'all_items'          => __('All Practices', 'gdlr-lawyer'),
						'view_item'          => __('View Practice', 'gdlr-lawyer'),
						'search_items'       => __('Search Practice', 'gdlr-lawyer'),
						'not_found'          => __('No practices found', 'gdlr-lawyer'),
						'not_found_in_trash' => __('No practices found in Trash', 'gdlr-lawyer'),
						'parent_item_colon'  => '',
						'menu_name'          => __('Practices', 'gdlr-lawyer')
					),
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => $practice_slug  ),
					'capability_type'    => 'post',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => 5,
					'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
				)
			);
			
			// create practice categories
			register_taxonomy(
				'practice_category', array("practice"), array(
					'hierarchical' => true,
					'show_admin_column' => true,
					'label' => __('Practice Categories', 'gdlr-lawyer'), 
					'singular_label' => __('Practice Category', 'gdlr-lawyer'), 
					'rewrite' => array( 'slug' => $practice_category_slug  )));
			register_taxonomy_for_object_type('practice_category', 'practice');

			// add filter to style single template
			if( defined('WP_THEME_KEY') && WP_THEME_KEY == 'goodlayers' ){
				add_filter('single_template', 'gdlr_register_practice_template');
			}
		}
	}

	if( !function_exists('gdlr_register_practice_template') ){
		function gdlr_register_practice_template($single_template) {
			global $post;

			if ($post->post_type == 'practice') {
				$single_template = dirname(dirname( __FILE__ )) . '/single-practice.php';
			}
			return $single_template;	
		}
	}
	
	// add a practice option
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_practice_options'); }
	if( !function_exists('gdlr_create_practice_options') ){
	
		function gdlr_create_practice_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('practice'),
					'meta_title' => __('Goodlayers Practice Option', 'gdlr-lawyer'),
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
								'sidebar' => array(
									'type' => 'radioimage',
									'options' => array(
										'default-sidebar'=>GDLR_PATH . '/include/images/default-sidebar-2.png',
										'no-sidebar'=>GDLR_PATH . '/include/images/no-sidebar-2.png',
										'both-sidebar'=>GDLR_PATH . '/include/images/both-sidebar-2.png', 
										'right-sidebar'=>GDLR_PATH . '/include/images/right-sidebar-2.png',
										'left-sidebar'=>GDLR_PATH . '/include/images/left-sidebar-2.png'
									),
									'default' => 'default-sidebar'
								),	
								'left-sidebar' => array(
									'title' => __('Left Sidebar' , 'gdlr-lawyer'),
									'type' => 'combobox',
									'options' => $gdlr_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper left-sidebar-wrapper both-sidebar-wrapper'
								),
								'right-sidebar' => array(
									'title' => __('Right Sidebar' , 'gdlr-lawyer'),
									'type' => 'combobox',
									'options' => $gdlr_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper right-sidebar-wrapper both-sidebar-wrapper'
								),						
						)
					),
					
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-lawyer'),
						'options' => array(
							'page-title' => array(
								'title' => __('Page Title' , 'gdlr-lawyer'),
								'type' => 'text'
							),
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-lawyer'),
								'type' => 'textarea'
							),							
							'header-background' => array(
								'title' => __('Header Background Image' , 'gdlr-lawyer'),
								'button' => __('Upload', 'gdlr-lawyer'),
								'type' => 'upload',
							),	
							'pdf-link' => array(
								'title' => __('PDF Download Link' , 'gdlr-lawyer'),
								'type' => 'text',
							),							
						)
					),

				)
			);
			
		}
	}	
	
	// add practice in page builder area
	add_filter('gdlr_page_builder_option', 'gdlr_register_practice_item');
	if( !function_exists('gdlr_register_practice_item') ){
		function gdlr_register_practice_item( $page_builder = array() ){
			global $gdlr_spaces;
		
			$page_builder['content-item']['options']['practice'] = array(
				'title'=> __('Practice', 'gdlr-lawyer'), 
				'type'=>'item',
				'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-lawyer'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('practice_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-lawyer')
					),					
					'practice-style'=> array(
						'title'=> __('Practice Style' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'widget-style' => __('Widget Style', 'gdlr-lawyer'),
							'classic-style' => __('Classic Style', 'gdlr-lawyer'),
							'modern-style' => __('Modern Style', 'gdlr-lawyer'),
						),
					),	
					'practice-size'=> array(
						'title'=> __('Practice Size' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'1/4'=>'1/4',
							'1/3'=>'1/3',
							'1/2'=>'1/2',
							'1/1'=>'1/1'
						),
						'default'=>'1/3',
						'wrapper-class'=>'practice-style-wrapper classic-style-wrapper modern-style-wrapper'
					),
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-lawyer'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of practices you want to pull out.', 'gdlr-lawyer')
					),	
					'num-excerpt'=> array(
						'title'=> __('Num Excerpt' ,'gdlr-lawyer'),
						'type'=> 'text',	
						'default'=> '20',
						'wrapper-class'=>'practice-style-wrapper classic-style-wrapper'
					),					
					'practice-layout'=> array(
						'title'=> __('Practice Layout Order' ,'gdlr-lawyer'),
						'type'=> 'combobox',
						'options'=> array(
							'fitRows' =>  __('FitRows ( Order items by row )', 'gdlr-lawyer'),
							'masonry' => __('Masonry ( Order items by spaces )', 'gdlr-lawyer'),
							'carousel' => __('Carousel ( Only For Grid And Modern Style )', 'gdlr-lawyer'),
						),
						'wrapper-class'=>'practice-style-wrapper classic-style-wrapper modern-style-wrapper',
						'description'=> __('You can see an example of these two layout here', 'gdlr-lawyer') . 
							'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
					),
					'practice-filter'=> array(
						'title'=> __('Enable Practice filter' ,'gdlr-lawyer'),
						'type'=> 'checkbox',
						'default'=> 'disable',
						'wrapper-class'=>'practice-style-wrapper classic-style-wrapper modern-style-wrapper',
						'description'=> __('*** You have to select only 1 ( or none ) category when enable this option. This option cannot works with carousel function.','gdlr-lawyer')
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