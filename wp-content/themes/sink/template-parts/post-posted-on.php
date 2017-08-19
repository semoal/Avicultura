<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	global $hide_list;

?>
<ul class="list-inline">
	<li>
		<span><?php the_time( 'j F, Y' ) ?></span>
	</li>

	<li>
                    <span class="post-author">
                        <?php esc_html_e( 'By', 'sink' ); ?><?php printf( '<a class="url fn n" href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) ) ?>
                    </span>
	</li>

	<?php if ( get_the_category_list() and ! isset( $hide_list[ 'category' ] ) ) { ?>
		<li>
                        <span class="post-category">
                            <?php esc_html_e( 'In', 'sink' ); ?>
                            <?php echo get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'sink' ) );
                            ?>
                        </span>
		</li>
	<?php } ?>

	<?php echo edit_post_link( '<i class="fa fa-pencil"></i>' . esc_html__( 'Edit Post', 'sink' ), '<li class="edit-link">', '</li>' ) ?>
</ul>