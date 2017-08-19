<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
<!-- Modal Search Form -->
<i class="icon-close zmdi zmdi-close zmdi-hc-fw"></i>

<div class="header-search-form" itemscope itemtype="http://schema.org/WebSite">
	<meta itemprop="url" content="<?php echo esc_url( home_url( '/' ) ); ?>"/>

	<form itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction" method="get"
	      class="woocommerce-product-search"
	      action="<?php echo esc_url( home_url( '/' ) ); ?>">

		<div class="form-row">
			<div class="input-field">
				<?php if ( class_exists( 'WooCommerce' ) ): ?>
					<label for="topSearch"><?php esc_html_e( 'Search product', 'sink' ) ?></label>
				<?php else: ?>
					<label for="topSearch"><?php esc_html_e( 'Search post', 'sink' ) ?></label>
				<?php endif; ?>
				<input required itemprop="query-input" tabindex="-1" autofocus="" type="search" id="topSearch"
				       class="form-control"
				       value="<?php echo get_search_query(); ?>" name="s"/>
				<button class="button" type="submit"><i class="zmdi zmdi-search zmdi-hc-fw"></i></button>
				<?php if ( class_exists( 'WooCommerce' ) ): ?>
					<meta itemprop="target"
					      content="<?php echo esc_url( home_url( '/' ) ); ?>?s={s}&amp;post_type=product"/>
					<input type="hidden" name="post_type" value="product"/>
				<?php else: ?>
					<meta itemprop="target"
					      content="<?php echo esc_url( home_url( '/' ) ); ?>?s={s}&amp;post_type=post"/>
					<input type="hidden" name="post_type" value="post"/>
				<?php endif; ?>
			</div>
		</div>
	</form>
</div>
