<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
<div class="row">
	<div class="blog-pagination">
		<?php if ( get_next_posts_link() ) : ?>
			<div class="col-xs-6">
				<div class="prev">
					<?php next_posts_link( '<i class="zmdi zmdi-long-arrow-left"></i>' . esc_html__( 'Older Entries', 'sink' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
			<div class="col-xs-6 pull-right">
				<div class="next">
					<?php previous_posts_link( esc_html__( 'Newer Entries', 'sink' ) . '<i class="zmdi zmdi-long-arrow-right"></i>' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<!-- .blog-pagination -->
</div> <!-- .row -->