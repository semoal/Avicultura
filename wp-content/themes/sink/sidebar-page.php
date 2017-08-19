<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    $layout = hippo_option( 'page-layout', FALSE, 'sidebar-right' );
    if ( $layout == 'sidebar-right' and is_active_sidebar( 'hippo-page-sidebar' ) ) :
        ?>
        <div class="col-md-3 col-sm-4 right-sidebar">
            <div class="primary-sidebar widget-area" role="complementary">
                <?php dynamic_sidebar( 'hippo-page-sidebar' ); ?>
            </div>
        </div>
        <?php
    elseif ( $layout == 'sidebar-left' and is_active_sidebar( 'hippo-page-sidebar' ) ) :
        ?>
        <div class="col-md-3 col-md-pull-9 col-sm-4 col-sm-pull-8 left-sidebar">
            <div class="primary-sidebar widget-area" role="complementary">
                <?php dynamic_sidebar( 'hippo-page-sidebar' ); ?>
            </div>
        </div>
    <?php endif;