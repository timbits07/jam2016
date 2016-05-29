<?php 
	get_header(); 
	
	while( have_posts() ){ the_post();
?>
<div class="gdlr-content">

	<?php 
		global $gdlr_sidebar, $theme_option, $gdlr_post_option, $gdlr_is_ajax;
		
		if( empty($gdlr_post_option['sidebar']) || $gdlr_post_option['sidebar'] == 'default-sidebar' ){
			$gdlr_sidebar = array(
				'type'=>$theme_option['practice-sidebar-template'],
				'left-sidebar'=>$theme_option['practice-sidebar-left'], 
				'right-sidebar'=>$theme_option['practice-sidebar-right']
			); 
		}else{
			$gdlr_sidebar = array(
				'type'=>$gdlr_post_option['sidebar'],
				'left-sidebar'=>$gdlr_post_option['left-sidebar'], 
				'right-sidebar'=>$gdlr_post_option['right-sidebar']
			); 				
		}
		$gdlr_sidebar = gdlr_get_sidebar_class($gdlr_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container gdlr-class-<?php echo $gdlr_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $gdlr_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $gdlr_sidebar['center']; ?> columns">
					<div class="gdlr-item gdlr-item-start-content">
						<div id="practice-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php 
								echo gdlr_get_practice_thumbnail($theme_option['practice-thumbnail-size']);
							?>
							<div class="gdlr-practice-head">
								<?php
									if( !empty($gdlr_post_option['pdf-link']) ){
										echo '<a class="gdlr-pdf-download" href="' . $gdlr_post_option['pdf-link'] . '" target="_blank" >';
										_e('Download PDF', 'gdlr-lawyer');
										echo '<i class="fa fa-file-pdf-o icon-file-text-alt" ></i>';
										echo '</a>';
									}
								?>
								<h1 class="gdlr-practice-title" ><?php the_title(); ?></h1>
								<div class="clear"></div>
							</div>
							<div class="gdlr-practice-content">
								<?php the_content(); ?>	
								<div class="clear"></div>
							</div>	
						</div><!-- #practice -->
						
						<div class="clear"></div>		
					</div>
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- gdlr-content -->
<?php
	}
	
	get_footer(); 
?>