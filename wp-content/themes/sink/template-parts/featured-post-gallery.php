<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $gallery_items, $gallery_image_size;

?>

<div class="carousel slide blog-carousel" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php $increment = 0;
            foreach ( $gallery_items as $gallery_item_id ) :
                $image_url = wp_get_attachment_image_src( $gallery_item_id, $gallery_image_size );
                ?>
                <div class="item <?php echo ( $increment < 1 ) ? 'active' : '' ?>">
                    <img class="img-responsive wp-post-image" src="<?php echo esc_url( $image_url[ 0 ] ) ?>" alt="<?php echo trim( strip_tags( get_post_meta( $gallery_item_id, '_wp_attachment_image_alt', TRUE ) ) ) ?>"/>
                </div>
                <?php $increment ++;
            endforeach; ?>
    </div>
    <div class="carousel-control-wrapper">
        <!-- Controls -->
        <a class="left carousel-control" href=".blog-carousel" data-slide="prev">
            <i class="zmdi zmdi-chevron-up"></i>
        </a>

        <a class="right carousel-control" href=".blog-carousel" data-slide="next">
            <i class="zmdi zmdi-chevron-down"></i>
        </a>
    </div>
</div> <!-- .blog-carousel -->