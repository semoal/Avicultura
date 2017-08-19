<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( post_password_required() ) {
		return;
	}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				printf( esc_html( _nx( 'One comment', '%s comments', get_comments_number(), 'comments title', 'sink' ) ),
				        number_format_i18n( get_comments_number() ) );
			?>
		</h3>

		<ul class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'       => 'li',
						'short_ping'  => TRUE,
						'avatar_size' => 80,
						'callback'    => 'hippo_comments_list'
					) );
			?>
		</ul><!-- .comment-list -->
		<?php
	endif; // have_comments() ?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav class="navigation comment-navigation" role="navigation">
			<h3 class="screen-reader-text section-heading"><?php esc_html_e( 'Comment navigation', 'sink' ); ?></h3>
			<ul class="pager comment-navigation">
				<li class="previous"><?php previous_comments_link( '<i class="fa fa-angle-double-left"></i> ' . esc_html__( 'Older Comments', 'sink' ) ); ?></li>
				<li class="next"><?php next_comments_link( esc_html__( 'Newer Comments', 'sink' ) . '<i class="fa fa-angle-double-right"></i>' ); ?></li>
			</ul>
		</nav><!-- .comment-navigation -->
	<?php endif;  // Check for comment navigation   ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<div class="alert alert-warning no-comments"><?php esc_html_e( 'Comments are closed.', 'sink' ); ?></div>
	<?php else :
		hippo_comment_form();
	endif;
	?>
</div><!-- /#comments -->