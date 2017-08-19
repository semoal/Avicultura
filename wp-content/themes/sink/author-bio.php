<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

?>
<div class="about-author clearfix">
	<div class="media">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="pull-left">
			<?php
				echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'hippo_author_bio_avatar_size', 150 ) );
			?>
		</a>

		<div class="media-body">
			<div class="author-info media-heading">
				<h3><?php echo get_the_author(); ?></h3>

				<div class="author-links">
					<a class="author-post"
					   href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
						<i class="zmdi zmdi-sort-amount-desc zmdi-hc-fw"></i> <?php esc_html_e( 'Post', 'sink' ); ?>
					</a>
					<?php if ( get_the_author_meta( 'user_url' ) ) : ?>
						<a class="author-web" href="<?php echo esc_url( get_the_author_meta( 'user_url' ) ); ?>"
						   target="_blank">
							<i class="zmdi zmdi-globe zmdi-hc-fw"></i> <?php esc_html_e( 'Web', 'sink' ); ?>
						</a>
					<?php endif ?>

					<ul class="social-link circle icon-grey list-inline">
						<?php
							$facebook_profile = trim( get_the_author_meta( 'facebook_profile' ) );
							if ( $facebook_profile && ! empty( $facebook_profile ) ) :
								echo '<li class="facebook"><a href="' . esc_url( $facebook_profile ) . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
							endif;

							$twitter_profile = trim( get_the_author_meta( 'twitter_profile' ) );
							if ( $twitter_profile && ! empty( $twitter_profile ) ) :
								echo '<li class="twitter"><a href="' . esc_url( $twitter_profile ) . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
							endif;

							$google_profile = trim( get_the_author_meta( 'google_profile' ) );
							if ( $google_profile && ! empty( $google_profile ) ) {
								echo '<li class="google"><a href="' . esc_url( $google_profile ) . '" rel="author" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
							}

							$linkedin_profile = trim( get_the_author_meta( 'linkedin_profile' ) );
							if ( $linkedin_profile && ! empty( $linkedin_profile ) ) {
								echo '<li class="linkedin"><a href="' . esc_url( $linkedin_profile ) . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
							}

							$github_profile = trim( get_the_author_meta( 'github_profile' ) );
							if ( $github_profile && ! empty( $github_profile ) ) {
								echo '<li class="linkedin"><a href="' . esc_url( $github_profile ) . '" target="_blank"><i class="fa fa-github"></i></a></li>';
							}
						?>
					</ul>
					<!-- .social-link -->
				</div>
				<!-- /.author-links -->
				<p><?php echo esc_html( get_the_author_meta( 'description' ) ) ?></p>
			</div>
		</div>
		<!-- /.media-body -->
	</div>
	<!-- /.media -->
</div> <!-- /.about-author -->