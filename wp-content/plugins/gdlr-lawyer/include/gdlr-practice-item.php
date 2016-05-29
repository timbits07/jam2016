<?php
	/*	
	*	Goodlayers Practice Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you create practice item
	*	---------------------------------------------------------------------
	*/
	
	// add action to check for practice item
	add_action('gdlr_print_item_selector', 'gdlr_check_practice_item', 10, 2);
	if( !function_exists('gdlr_check_practice_item') ){
		function gdlr_check_practice_item( $type, $settings = array() ){
			if($type == 'practice'){
				echo gdlr_print_practice_item( $settings );
			}
		}
	}
	
	// print practice item
	if( !function_exists('gdlr_print_practice_item') ){
		function gdlr_print_practice_item( $settings = array() ){

			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $gdlr_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $gdlr_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
				
			if( $settings['practice-layout'] == 'carousel' ){ 
				$settings['carousel'] = true;
			}
			$ret  = gdlr_get_item_title($settings);	
				
			$ret .= '<div class="practice-item-wrapper type-' . $settings['practice-style'] . ' gdlr-column-' . str_replace('/', '-', $settings['practice-size']) . '" ' . $item_id . $margin_style . ' data-ajax="' . AJAX_URL . '" >'; 

			// query posts section
			$args = array('post_type' => 'practice', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;

			if( !empty($settings['category']) ){
				$args['tax_query'] = array('relation' => 'OR');
				array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'practice_category', 'field'=>'slug'));			
			}			
			$query = new WP_Query( $args );

			// create the practice filter
			$settings['num-excerpt'] = empty($settings['num-excerpt'])? 0: $settings['num-excerpt'];
			$settings['practice-size'] = str_replace('1/', '', $settings['practice-size']);
			if( $settings['practice-filter'] == 'enable' ){
			
				// ajax infomation
				$ret .= '<div class="gdlr-ajax-info" data-num-fetch="' . $args['posts_per_page'] . '" data-num-excerpt="' . $settings['num-excerpt'] . '" ';
				$ret .= 'data-orderby="' . $args['orderby'] . '" data-order="' . $args['order'] . '" ';
				$ret .= 'data-thumbnail-size="' .  $settings['thumbnail-size'] . '" data-practice-style="' . $settings['practice-style'] . '" ';
				$ret .= 'data-practice-size="' . $settings['practice-size'] . '" data-practice-layout="' .  $settings['practice-layout'] . '" ';
				$ret .= 'data-ajax="' . admin_url('admin-ajax.php') . '" data-category="' . $settings['category'] . '" ></div>';
			
				// category filter
				if( empty($settings['category']) ){
					$parent = array('gdlr-all'=>__('All', 'gdlr-lawyer'));
					$settings['category-id'] = '';
				}else{
					$term = get_term_by('slug', $settings['category'], 'practice_category');
					$parent = array($settings['category']=>$term->name);
					$settings['category-id'] = $term->term_id;
				}
				
				$filters = $parent + gdlr_get_term_list('practice_category', $settings['category-id']);
				$filter_active = 'active';
				$ret .= '<div class="practice-item-filter">';
				foreach($filters as $filter_id => $filter){
					$filter_id = ($filter_id == 'gdlr-all')? '': $filter_id;

					$ret .= '<a class="' . $filter_active . '" href="#" ';
					$ret .= 'data-category="' . $filter_id . '" >' . $filter . '</a>';
					$filter_active = '';
				}
				$ret .= '<div class="practice-item-filter-gimmick" ></div>';
				$ret .= '</div>';
			}

			$ret .= '<div class="practice-item-holder">';
			if($settings['practice-style'] == 'widget-style'){	
				$ret .= gdlr_get_widget_practice($query, $settings['thumbnail-size']);
			
			}else if( $settings['practice-style'] == 'classic-style'){
				global $gdlr_excerpt_length; $gdlr_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'gdlr_set_excerpt_length');
				
				$ret .= gdlr_get_classic_practice($query, $settings['practice-size'], 
							$settings['thumbnail-size'], $settings['practice-layout'] );
							
				remove_filter('excerpt_length', 'gdlr_set_excerpt_length');
			}else if($settings['practice-style'] == 'modern-style'){	
				
				$ret .= gdlr_get_modern_practice($query, $settings['practice-size'], 
							$settings['thumbnail-size'], $settings['practice-layout'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// create pagination
			if($settings['practice-filter'] == 'enable' && $settings['pagination'] == 'enable'){
				$ret .= gdlr_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}else if($settings['pagination'] == 'enable'){
				$ret .= gdlr_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // practice-item-wrapper
			return $ret;
		}
	}
	
	// ajax function for practice filter / pagination
	add_action('wp_ajax_gdlr_get_practice_ajax', 'gdlr_get_practice_ajax');
	add_action('wp_ajax_nopriv_gdlr_get_practice_ajax', 'gdlr_get_practice_ajax');
	if( !function_exists('gdlr_get_practice_ajax') ){
		function gdlr_get_practice_ajax(){
			$settings = $_POST['args'];

			$args = array('post_type' => 'practice', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (empty($settings['paged']))? 1: $settings['paged'];
				
			if( !empty($settings['category']) ){
				$args['tax_query'] = array(
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'practice_category', 'field'=>'slug')
				);
			}			
			$query = new WP_Query( $args );
			

			$ret  = '<div class="practice-item-holder">';
			if( $settings['practice-style'] == 'classic-style'){
				global $gdlr_excerpt_length; $gdlr_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'gdlr_set_excerpt_length');
				
				$ret .= gdlr_get_classic_practice($query, $settings['practice-size'], 
							$settings['thumbnail-size'], $settings['practice-layout'] );
							
				remove_filter('excerpt_length', 'gdlr_set_excerpt_length');
			}else if($settings['practice-style'] == 'modern-style'){	
				
				$ret .= gdlr_get_modern_practice($query, $settings['practice-size'], 
							$settings['thumbnail-size'], $settings['practice-layout'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// pagination section
			$ret .= gdlr_get_ajax_pagination($query->max_num_pages, $args['paged']);
			die($ret);
		}
	}	

	// get practice thumbnail
	if( !function_exists('gdlr_get_practice_thumbnail') ){
		function gdlr_get_practice_thumbnail($size = 'full'){
			$ret  = '';
			
			$image_id = get_post_thumbnail_id();
			if( !empty($image_id) ){
				$ret .= '<div class="gdlr-practice-thumbnail">';
				$ret .= gdlr_get_image($image_id, $size, true);
				$ret .= '</div>';
			}		

			return $ret;
		}
	}	
	
	// print widget practice
	if( !function_exists('gdlr_get_widget_practice') ){
		function gdlr_get_widget_practice($query, $thumbnail_size){
			global $post;

			$ret  = '';
			while($query->have_posts()){ $query->the_post();	
				$ret .= '<div class="gdlr-item gdlr-widget-practice">';
				$ret .= gdlr_get_practice_thumbnail($thumbnail_size);
				
				$ret .= '<div class="gdlr-practice-item-content">';
				$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '<a class="practice-read-more" href="' . get_permalink() . '" >' . __('Learn More', 'gdlr-lawyer');
				$ret .= '<i class="fa fa-long-arrow-right icon-long-arrow-right" ></i></a>';
				$ret .= '</div>';
				
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // gdlr-item
			}
			wp_reset_postdata();
			
			return $ret;
		}
	}
	
	// print classic portfolio
	if( !function_exists('gdlr_get_classic_practice') ){
		function gdlr_get_classic_practice($query, $size, $thumbnail_size, $layout = 'fitRows'){
			if($layout == 'carousel'){ 
				return gdlr_get_classic_carousel_practice($query, $size, $thumbnail_size); 
			}		
		
			global $post;

			$current_size = 0;
			$ret  = '<div class="gdlr-isotope" data-type="practice" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . gdlr_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="gdlr-item gdlr-classic-practice">';
				$ret .= '<div class="gdlr-ux gdlr-classic-practice-ux">';
				$ret .= gdlr_get_practice_thumbnail($thumbnail_size);
				$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '<div class="practice-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>'; // gdlr-ux
				$ret .= '</div>'; // gdlr-item
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('gdlr_get_classic_carousel_practice') ){
		function gdlr_get_classic_carousel_practice($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="gdlr-practice-carousel-item gdlr-item" >';	
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="practice-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="gdlr-item gdlr-classic-practice">';

				$ret .= gdlr_get_practice_thumbnail($thumbnail_size);
				$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '<div class="practice-excerpt">' . get_the_excerpt() . '</div>';
				
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>';
			$ret .= '</div>';
			
			return $ret;
		}		
	}	
	
	// print modern practice
	if( !function_exists('gdlr_get_modern_practice') ){
		function gdlr_get_modern_practice($query, $size, $thumbnail_size, $layout = 'fitRows'){
			if($layout == 'carousel'){ 
				return gdlr_get_modern_carousel_practice($query, $size, $thumbnail_size); 
			}
			
			global $post;

			$current_size = 0;
			$ret  = '<div class="gdlr-isotope" data-type="practice" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . gdlr_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="gdlr-item gdlr-modern-practice">';
				$ret .= '<div class="gdlr-ux gdlr-modern-practice-ux">';
	
				$image_id = get_post_thumbnail_id();
				if( !empty($image_id) ){
					$ret .= '<div class="gdlr-practice-thumbnail">';
					$ret .= gdlr_get_image($image_id, $thumbnail_size);
					$ret .= '<div class="gdlr-practice-thumbnail-overlay"></div>';
					$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
					$ret .= '</div>';
				}else{
					$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				}
				
				$ret .= '</div>'; // gdlr-ux
				$ret .= '</div>'; // gdlr-item
				$ret .= '</div>'; // gdlr-column-class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('gdlr_get_modern_carousel_practice') ){
		function gdlr_get_modern_carousel_practice($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="gdlr-practice-carousel-item gdlr-item" >';		
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="practice-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="gdlr-item gdlr-modern-practice">';
				$image_id = get_post_thumbnail_id();
				if( !empty($image_id) ){
					$ret .= '<div class="gdlr-practice-thumbnail">';
					$ret .= gdlr_get_image($image_id, $thumbnail_size);
					$ret .= '<div class="gdlr-practice-thumbnail-overlay"></div>';
					$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
					$ret .= '</div>';
				}else{
					$ret .= '<h3 class="practice-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				}
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // gdlr-item
			
			return $ret;
		}		
	}
	
?>