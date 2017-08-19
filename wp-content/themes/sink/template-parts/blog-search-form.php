<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	global $form;
?>

<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<div class="input-field">
		<label for="blog-search"><?php esc_html_e( 'Search', 'sink' ) ?></label>
		<input class="form-control" id="blog-search" type="text" value="<?php esc_attr( get_search_query() ) ?>"
		       name="s"/>
		<button type="submit"><i class="fa fa-search"></i></button>
		<input type="hidden" value="post" name="post_type"/>
	</div>
</form>
