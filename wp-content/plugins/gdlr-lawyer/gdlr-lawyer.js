(function($){
	"use strict";

	// get practice using ajax
	function gdlr_practice_ajax(prac_holder, ajax_info, category, paged){

		var args = new Object();
		args['num-fetch'] = ajax_info.attr('data-num-fetch');
		args['num-excerpt'] = ajax_info.attr('data-num-excerpt');
		args['order'] = ajax_info.attr('data-order');
		args['orderby'] = ajax_info.attr('data-orderby');
		args['thumbnail-size'] = ajax_info.attr('data-thumbnail-size');
		args['practice-style'] = ajax_info.attr('data-practice-style');
		args['practice-size'] = ajax_info.attr('data-practice-size');
		args['practice-layout'] = ajax_info.attr('data-practice-layout');
		args['category'] = (category)? category: ajax_info.attr('data-category');
		args['paged'] = (paged)? paged: 1;

		// hide the un-used elements
		var animate_complete = false;
		prac_holder.slideUp(500, function(){
			animate_complete = true;
		});
		prac_holder.siblings('.gdlr-pagination').slideUp(500, function(){
			$(this).remove();
		});
		
		var now_loading = $('<div class="gdlr-now-loading"></div>');
		now_loading.insertBefore(prac_holder);
		now_loading.slideDown();
		
		// call ajax to get practice item
		$.ajax({
			type: 'POST',
			url: ajax_info.attr('data-ajax'),
			data: {'action': 'gdlr_get_practice_ajax', 'args': args},
			error: function(a, b, c){ console.log(a, b, c); },
			success: function(data){
				now_loading.css('background-image','none').slideUp(function(){ $(this).remove(); });	
			
				var prac_item = $(data).hide();
				if( animate_complete ){
					gdlr_bind_practice_item(prac_holder, prac_item);
				}else{
					setTimeout(function() {
						gdlr_bind_practice_item(prac_holder, prac_item);
					}, 500);
				}	
			}
		});			
	}
	
	function gdlr_bind_practice_item(prac_holder, prac_item){
		if( prac_holder ){
			prac_holder.replaceWith(prac_item);
		}
		prac_item.slideDown();
		
		// bind events
		prac_item.each(function(){
			if( $(this).hasClass('gdlr-pagination') ){
				$(this).children().gdlr_bind_practice_pagination();
			}
		});	

		prac_item.find('img').load(function(){ $(window).trigger('resize'); });		
	}
	
	$.fn.gdlr_bind_practice_pagination = function(){
		$(this).click(function(){
			if($(this).hasClass('current')) return;
			var port_holder = $(this).parent('.gdlr-pagination').siblings('.practice-item-holder');
			var ajax_info = $(this).parent('.gdlr-pagination').siblings('.gdlr-ajax-info');
			
			var category = $(this).parent('.gdlr-pagination').siblings('.practice-item-filter');
			if( category ){
				category = category.children('.active').attr('data-category');
			}

			gdlr_practice_ajax(port_holder, ajax_info, category, $(this).attr('data-paged'));
			return false;
		});		
	}	
	
	$(document).ready(function(){
		
		// practice item
		$('.practice-item-holder').on({
			mouseenter: function(){
				$(this).find('img').transition({ scale: 1.1, duration: 200 });
			},
			mouseleave: function(){
				$(this).find('img').transition({ scale: 1, duration: 200 });
			}
		}, '.gdlr-classic-practice .gdlr-practice-thumbnail, .gdlr-modern-practice .gdlr-practice-thumbnail');
	
		// single page
		$('body.single-practice').on({
			mouseenter: function(){
				$(this).find('img').transition({ scale: 1.1, duration: 200 });
			},
			mouseleave: function(){
				$(this).find('img').transition({ scale: 1, duration: 200 });
			}
		}, '.gdlr-practice-thumbnail');
	
		// script for calling ajax practice when selecting category
		$('.practice-item-filter a').click(function(){
			if($(this).hasClass('active')) return false;
			$(this).addClass('active').siblings().removeClass('active');
		
			var prac_holder = $(this).parent('.practice-item-filter').siblings('.practice-item-holder');
			var ajax_info = $(this).parent('.practice-item-filter').siblings('.gdlr-ajax-info');

			gdlr_practice_ajax(prac_holder, ajax_info, $(this).attr('data-category'));
			return false;
		});
		$('.practice-item-wrapper .gdlr-pagination.gdlr-ajax .page-numbers').gdlr_bind_practice_pagination();
		
	});
	
	$(window).load(function(){
		// filter gimmick
		var gimmick_width = 0;
		var gimmick_left = 0;
		var gimmick_top = 0;
		
		$('.practice-item-filter').each(function(){
			var active = $(this).children('.active');
			
			$(this).children('.practice-item-filter-gimmick').css({
				width: active.width(),
				left: active.position().left + 15,
				top: active.position().top + 21
			});
			
			gimmick_width = active.width();
			gimmick_left = active.position().left + 15; 
			gimmick_top = active.position().top + 21;
		});
		$('.practice-item-filter a').hover(function(){
			var active = $(this);
			
			$(this).siblings('.practice-item-filter-gimmick').animate({
				width: active.width(),
				left: active.position().left + 15,
				top: active.position().top + 21
			}, { duration: 200, queue: false });
		}, function(){
			$(this).siblings('.practice-item-filter-gimmick').animate({
				width: gimmick_width,
				left: gimmick_left,
				top: gimmick_top
			}, { duration: 200, queue: false });
		});		
		$('.practice-item-filter a').click(function(){
			gimmick_width = $(this).width();
			gimmick_left = $(this).position().left + 15; 
		});
	});	
})(jQuery);