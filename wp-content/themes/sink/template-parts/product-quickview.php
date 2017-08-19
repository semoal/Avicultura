<?php
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $product;
?>
<div id="quick-view-<?php the_id(); ?>" class="modal fade product-quick-view product">
    <div class="modal-dialog">
        <div class="modal-content">
            <a href="javascript:;" class="close" data-dismiss="modal">&times;</a>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="image-wrap">
                            <div class="images">
                                <?php
                                    echo $product->get_image( 'shop_catalog', array( 'class' => 'img-responsive', 'alt' => esc_attr( $product->get_title() ) ) );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="summary entry-summary">
                            <?php do_action( 'woocommerce_single_product_summary' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- .product-quick-view -->
