<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    $css_classes = array( 'blog-post-wrapper', 'blog-post-list' );
    
    if ( hippo_has_post_thumbnail() ) :
        $css_classes[] = 'hippo-column-vertical-align';
    endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( join( ' ', $css_classes ) ); ?>>

    <div class="row">
        <?php
            
            if ( hippo_has_post_thumbnail() ) :
                
                global $hippo_blog_list_post_count;
                
                $push_pull_class_thumbnail = ( $hippo_blog_list_post_count % 2 == 0 ) ? 'col-md-push-4' : '';
                $push_pull_class_contents  = ( $hippo_blog_list_post_count % 2 == 0 ) ? 'col-md-pull-8 z-blog-content-left' : '';
                
                ?>
                <div class="col-md-8 <?php echo esc_attr( $push_pull_class_thumbnail ) ?>">
                    <?php hippo_post_thumbnail(); ?>
                </div>
            <?php endif; ?>
        
        <?php if ( hippo_has_post_thumbnail() ) : ?>
        <div class="col-md-4 <?php echo esc_attr( $push_pull_class_contents ) ?>">
            <?php else : ?>
            <div class="col-md-12">
                <?php endif; ?>

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
                    </div>
                    <!-- .entry-content -->
                </div> <!-- .content-wrapper -->
                
                <?php if ( hippo_has_post_thumbnail() ) : ?>
            </div> <!-- .col-md-4 -->
            <?php else : ?>
        </div> <!-- .col-md-12 -->
    <?php endif; ?>
    </div> <!-- .row -->
</article><!-- #post-## -->