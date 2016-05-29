<?php
/**
 * The default template for displaying standard post format
 */
	global $gdlr_post_settings; 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="gdlr-standard-style">
		<div class="blog-date-wrapper">
			<span class="blog-date-day gdlr-title-font"><?php echo get_the_time('j'); ?></span>
			<span class="blog-date-month"><?php echo get_the_time('M'); ?></span>
		</div>
		<header class="post-header">
			<div class="gdlr-blog-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></div>

			<?php 
				if( $gdlr_post_settings['excerpt'] != 0 ){
					echo '<div class="gdlr-blog-excerpt">' . get_the_excerpt() . '</div>';
				}
			?>		
			<div class="clear"></div>
		</header><!-- entry-header -->
		<div class="clear"></div>
	</div>
</article><!-- #post -->