<?php
/*
Template Name: Home page
*/

get_header(); ?>

	<div id="primary" class="fp-content-area">
		<main id="main" class="site-main" role="main">
			<div class="entry-content">
				<div class="mfca-main container">
					<div class="mfca-main__row">
						<?php foreach ( mfca_languages() as $code => $name ) : ?>
							<?php if ( ! $name ) { continue; } ?>
							<a href="<?php echo esc_url( home_url( "/c/{$code}/" ) ); ?>" class="mfca-main__item">
								<div class="mfca-main__image">
									<img src="<?php echo esc_url( get_theme_file_uri( "images/flags/{$code}.png" ) ); ?>" alt="">
								</div>
								<div class="mfca-main__text"><?php echo esc_html( $name ); ?></div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div><!-- .entry-content -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
