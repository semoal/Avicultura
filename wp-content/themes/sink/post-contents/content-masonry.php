<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    $css_classes = array( 'blog-post-wrapper', 'blog-post-masonry' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( join( ' ', $css_classes ) ); ?>>
    
    <?php
        hippo_post_thumbnail();
    ?>

    <div class="content-wrapper">
        <div class="entry-header">
            <div class="entry-meta">
                <?php hippo_posted_on() ?>
            </div>
            <!-- .entry-meta -->
            
            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        </div>
        <!-- .entry-header -->
        <div class="entry-content">
            <?php
                the_content( '<span class="readmore">' . esc_html__( 'Continue', 'sink' ) . '</span>' );
                hippo_link_pages();
            ?>
        </div> <!-- .entry-content -->
    </div> <!-- .content-wrapper -->
</article><!-- #post-## -->