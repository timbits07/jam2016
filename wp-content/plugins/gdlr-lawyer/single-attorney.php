<?php 
	get_header(); 
	
	while( have_posts() ){ the_post();
?>
<div class="gdlr-content">

	<?php 
		global $gdlr_sidebar, $theme_option, $gdlr_post_option, $gdlr_is_ajax;
		
		$gdlr_sidebar = array(
			'type'=>'no-sidebar',
			'left-sidebar'=>'', 
			'right-sidebar'=>''
		); 				
		$gdlr_sidebar = gdlr_get_sidebar_class($gdlr_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container gdlr-class-<?php echo $gdlr_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $gdlr_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $gdlr_sidebar['center']; ?> columns">
					<div class="gdlr-item gdlr-item-start-content">
						<div id="attorney-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="gdlr-attorney-info-wrapper">
								<?php echo gdlr_get_attorney_thumbnail($theme_option['attorney-thumbnail-size']); ?>
								<div class="gdlr-attorney-info-head">
									<h3 class="gdlr-attorney-info-title"><?php the_title(); ?></h3>
									<div class="gdlr-attorney-info-position"><?php echo gdlr_text_filter($gdlr_post_option['position']); ?></div>
								</div>
								
								<?php if( !empty($gdlr_post_option['email']) || !empty($gdlr_post_option['telephone']) ){ ?>
								<div class="gdlr-attorney-info-contact">
								<?php
									echo '<h3 class="gdlr-attorney-info-contact-title">' . __('Contacts', 'gdlr-lawyer') . '</h3>';
									
									if( !empty($gdlr_post_option['telephone']) ){
										echo '<div class="gdlr-attorney-info-contact-item" >';
										echo '<i class="fa fa-phone icon-phone"></i><span>' . $gdlr_post_option['telephone'] . '</span>';
										echo '</div>';
									}
									
									if( !empty($gdlr_post_option['email']) ){
										echo '<div class="gdlr-attorney-info-contact-item" >';
										echo '<i class="fa fa-envelope icon-envelope"></i><span>' . $gdlr_post_option['email'] . '</span>';
										echo '</div>';
									}
								?>
									<div class="gdlr-attorney-info-social">
										<?php echo gdlr_text_filter($gdlr_post_option['social']); ?>
									</div>
								</div>
								<?php } ?>
								

							</div>
							<div class="gdlr-attorney-content">
								<?php the_content(); ?>	
								<div class="clear"></div>
							</div>	
							<div class="clear"></div>
						</div><!-- #attorney -->
						
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