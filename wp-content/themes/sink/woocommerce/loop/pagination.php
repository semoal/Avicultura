<?php
	/**
	 * Pagination - Show numbered pagination for catalog pages
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see           https://docs.woothemes.com/document/template-structure/
	 * @author        WooThemes
	 * @package       WooCommerce/Templates
	 * @version       2.2.2
	 */


	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) :
		return;
	endif;
?>
<div class="woocommerce-pagination">
	<?php

		$product_page_items = paginate_links( apply_filters( 'woocommerce_pagination_args', array(
			'base'      => esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, FALSE ) ) ) ),
			'format'    => '',
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $wp_query->max_num_pages,
			'prev_text' => '<i class="fa fa-long-arrow-left"></i> ' . esc_html__( 'Prev', 'sink' ),
			'next_text' => esc_html__( 'Next', 'sink' ) . '<i class="fa fa-long-arrow-right"></i>',
			'type'      => 'array',
			'end_size'  => 3,
			'mid_size'  => 3
		) ) );


		$pagination = '<ul class="pagination page-numbers"><li>';
		$pagination .= join( "</li><li>", (array) $product_page_items );
		$pagination .= "</li></ul>";

		echo $pagination;
	?>
</div> <!-- .woocommerce-pagination -->