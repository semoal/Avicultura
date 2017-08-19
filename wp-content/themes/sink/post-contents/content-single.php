<?php
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-wrapper' ); ?>>
        <header class="entry-header">
            <!-- <h2 class="entry-title"><?php //the_title(); ?></h2> -->
            <div class="entry-meta">
                <?php hippo_posted_on() ?>
            </div>
            <!-- .entry-meta -->
        </header>
        <!-- .entry-header -->
        
        <?php
            hippo_post_thumbnail();
        ?>

        <div class="entry-content">
            <?php
                the_content( '<span class="btn btn-default btn-primary readmore">' . esc_html__( 'Read More', 'sink' ) . '</span>' );
                hippo_link_pages();
            ?>
        </div>
        <!-- .entry-content -->
    </article> <!-- #post-## -->
    <div class="clearfix"></div>
<?php
    
    if ( get_the_tag_list( '', ' ' ) ) :
        ?>
        <div class="tagcloud">
            <span><?php esc_html_e( 'Taged In: ', 'sink' ); ?></span>
            <?php echo get_the_tag_list( '', ' ' ); ?>
        </div>
    <?php endif; // End if  ?>

<?php if ( hippo_option( 'show-blog-share-button', FALSE, TRUE ) ) : ?>
    <div class="hippo-share-button">
        <div class="social">
            <span><?php esc_html_e( 'Share: ', 'sink' ); ?></span>
            <ul class="list-inline">
                
                <?php if ( hippo_option( 'blog-share-button', 'facebook', FALSE ) ) : ?>
                    <!--Facebook-->
                    <li>
                        <a class="btn-social facebook"
                           href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>"
                           title="<?php esc_html_e( 'Share this post on Facebook!', 'sink' ); ?>" target="_blank"><i
                                    class="zmdi zmdi-facebook"></i></a>
                    </li>
                <?php endif; ?>
                
                <?php if ( hippo_option( 'blog-share-button', 'twitter', FALSE ) ) : ?>
                    <!--Twitter-->
                    <li>
                        <a class="btn-social twitter"
                           href="http://twitter.com/home?status=Reading:<?php the_permalink(); ?>"
                           title="<?php esc_html_e( 'Share this post on Twitter!', 'sink' ); ?>" target="_blank"><i
                                    class="zmdi zmdi-twitter"></i></a>
                    </li>
                <?php endif; ?>
                
                <?php if ( hippo_option( 'blog-share-button', 'google', FALSE ) ) : ?>
                    <!--Google Plus-->
                    <li>
                        <a class="btn-social google-plus"
                           href="https://plus.google.com/share?url=<?php the_permalink(); ?>"
                           title="<?php esc_html_e( 'Share this post on Google+!', 'sink' ); ?>" target="_blank"><i
                                    class="zmdi zmdi-google-plus"></i></a>
                    </li>
                <?php endif; ?>
                
                <?php if ( hippo_option( 'blog-share-button', 'linkedin', FALSE ) ) : ?>
                    <!--Linkedin-->
                    <li>
                        <a class="btn-social linkedin"
                           href="http://www.linkedin.com/shareArticle?mini=true&amp;title=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>"
                           title="<?php esc_html_e( 'Share this post on Linkedin!', 'sink' ); ?>" target="_blank"><i
                                    class="zmdi zmdi-linkedin-box"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php
    
    if ( is_single() ) :
        
        if ( get_the_author_meta( 'description' ) ) :
            get_template_part( 'author-bio' );
        endif;
        
        if ( hippo_option( 'post-navigation', FALSE, TRUE ) ) :
            hippo_post_navigation();
        endif;
    endif;