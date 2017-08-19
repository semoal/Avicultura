<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( function_exists( 'woocommerce_mini_cart' ) ) :

		global $woocommerce;
		?>
		<div id="mini-cart" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<?php if ( hippo_option( 'minicart-title-show', FALSE, TRUE ) ) : ?>
							<h4 class="modal-title"><?php echo esc_html( hippo_option( 'mini-cart-title', FALSE, esc_html__( 'Cart', 'sink' ) ) ); ?></h4>
						<?php endif; ?>
						<button type="button" class="close" data-dismiss="modal"
						        aria-label="<?php esc_html_e( 'Close', 'sink' ) ?>"><span
								aria-hidden="true">&times;</span></button>
					</div>

					<div class="modal-body woocommerce mini-cart-details cart-details">

						<div class="mini-cart-contents widget_shopping_cart_content">
							<?php woocommerce_mini_cart() ?>
						</div>
					</div>
				</div>
			</div>
			<!-- .modal-dialog -->
		</div> <!-- .modal -->
	<?php endif;