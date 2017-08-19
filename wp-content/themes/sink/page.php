<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	get_header(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class( 'page-content default-page' ); ?>>
		<div class="row">
			<?php
				$layout     = hippo_option( 'page-layout', FALSE, 'right-sidebar' );
				$grid_class = 'col-md-12';

				if ( $layout == 'right-sidebar' ) :
					$grid_class = ( is_active_sidebar( 'hippo-page-sidebar' ) )
						? 'col-md-9 col-sm-8'
						: $grid_class;

				elseif ( $layout == 'left-sidebar' ) :
					$grid_class = ( is_active_sidebar( 'hippo-page-sidebar' ) )
						? 'col-md-9 col-md-push-3 col-sm-8 col-sm-push-4'
						: $grid_class;
				endif;
			?>
			<div class="<?php echo esc_attr( $grid_class ); ?>">
				<div class="entry-content">
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'post-contents/content', 'page' ); ?>

						<?php if ( hippo_option( 'page-comment', FALSE, comments_open() ) ) : ?>
							<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>

						<?php endif; // hippo_option( 'page-comment', FALSE, TRUE ) ?>

					<?php endwhile; // end of the loop. ?>
				</div>
				<!-- .entry-content -->
			</div>
			<!-- .col-* -->
			<?php get_sidebar( 'page' ); ?>
		</div>
		<!-- .row -->
	</div> <!-- #post-# -->
<?php get_footer(); ?>