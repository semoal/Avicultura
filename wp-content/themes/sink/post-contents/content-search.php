<?php
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-wrapper' ); ?>>

    <header class="entry-header">
        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

        <div class="post-author">
            <span class="author">
                <?php esc_html_e( 'By', 'sink' ); ?><?php printf( '<a class="url fn n" href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) ) ?>
            </span>
        </div>

        <div class="entry-meta">
            <?php hippo_posted_on() ?>
        </div> <!-- .entry-meta -->
    </header> <!-- .entry-header -->
    
    <?php
        hippo_post_thumbnail();
    ?>

    <div class="entry-content">
        <?php
            the_content( '<span class="btn btn-primary readmore">' . esc_html__( 'Read More', 'sink' ) . '</span>' );
            hippo_link_pages();
        ?>
    </div> <!-- .entry-content -->
</article><!-- #post-## -->