<?php
	/*	
	*	Goodlayers Attorney Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you create attorney item
	*	---------------------------------------------------------------------
	*/
	
	// add action to check for attorney item
	add_action('gdlr_print_item_selector', 'gdlr_check_attorney_item', 10, 2);
	if( !function_exists('gdlr_check_attorney_item') ){
		function gdlr_check_attorney_item( $type, $settings = array() ){
			if($type == 'attorney'){
				echo gdlr_print_attorney_item( $settings );
			}
		}
	}
	
	// print attorney item
	if( !function_exists('gdlr_print_attorney_item') ){
		function gdlr_print_attorney_item( $settings = array() ){

			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $gdlr_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $gdlr_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
		
			$ret  = '<div class="attorney-item-wrapper" ' . $item_id . $margin_style . ' >'; 
			
			if( $settings['attorney-layout'] == 'carousel' ){
				$settings['carousel'] = true;
			}
			$ret .= gdlr_get_item_title($settings);	
			
			// query posts section
			$args = array('post_type' => 'attorney', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;

			if( !empty($settings['category']) ){
				$args['tax_query'] = array('relation' => 'OR');
				array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'attorney_category', 'field'=>'slug'));
			}			
			$query = new WP_Query( $args );

			// create the attorney filter
			$settings['num-excerpt'] = empty($settings['num-excerpt'])? 0: $settings['num-excerpt'];
			$settings['attorney-size'] = str_replace('1/', '', $settings['attorney-size']);
			
			$ret .= '<div class="attorney-item-holder">';

			global $gdlr_excerpt_length, $gdlr_excerpt_text; 
			$gdlr_excerpt_text = __('Read Profile', 'gdlr-lawyer');
			$gdlr_excerpt_length = $settings['num-excerpt'];
			add_filter('excerpt_length', 'gdlr_set_excerpt_length');
				
			$ret .= gdlr_get_attorney_item($query, $settings['attorney-size'], 
						$settings['thumbnail-size'], $settings['attorney-layout'] );
							
			remove_filter('excerpt_length', 'gdlr_set_excerpt_length');
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// create pagination
			if($settings['pagination'] == 'enable'){
				$ret .= gdlr_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // portfolio-item-wrapper
			return $ret;
		}
	}

	// get attorney thumbnail
	if( !function_exists('gdlr_get_attorney_thumbnail') ){
		function gdlr_get_attorney_thumbnail($size = 'full'){
			$ret  = '';
			
			$image_id = get_post_thumbnail_id();
			if( !empty($image_id) ){
				$ret .= '<div class="gdlr-attorney-thumbnail">';
				$ret .= gdlr_get_image($image_id, $size, true);
				$ret .= '</div>';
			}		

			return $ret;
		}
	}	
	
	// print classic portfolio
	if( !function_exists('gdlr_get_attorney_item') ){
		function gdlr_get_attorney_item($query, $size, $thumbnail_size, $layout = 'fitRows'){
			if($layout == 'carousel'){ 
				return gdlr_get_carousel_attorney_item($query, $size, $thumbnail_size); 
			}		
		
			global $post;

			$current_size = 0;
			$ret  = '<div class="gdlr-isotope" data-type="attorney" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . gdlr_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="gdlr-item gdlr-attorney-item">';
				
				$attorney_option = json_decode(gdlr_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= gdlr_get_attorney_thumbnail($thumbnail_size);
				$ret .= '<div class="attorney-content-wrapper" >';
				$ret .= '<h3 class="attorney-title gdlr-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '<div class="attorney-position">' . $attorney_option['position'] . '</div>';
				$ret .= '<div class="attorney-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>'; // content-wrapper
				
				$ret .= '</div>'; // gdlr-item
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('gdlr_get_carousel_attorney_item') ){
		function gdlr_get_carousel_attorney_item($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="gdlr-attorney-carousel-item gdlr-item" >';	
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="attorney-item-holder" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="gdlr-item gdlr-attorney-item">';

				$attorney_option = json_decode(gdlr_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= gdlr_get_attorney_thumbnail($thumbnail_size);
				$ret .= '<div class="attorney-content-wrapper" >';
				$ret .= '<h3 class="attorney-title gdlr-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '<div class="attorney-position">' . $attorney_option['position'] . '</div>';
				$ret .= '<div class="attorney-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>'; // content-wrapper
				
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>';
			$ret .= '</div>';
			
			return $ret;
		}		
	}	
	
?>