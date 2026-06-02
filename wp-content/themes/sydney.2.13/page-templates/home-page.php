<?php
/*
Template Name: Home page
*/

get_header(); ?>

<?php
$languages = array(
	array( 'slug' => 'az', 'label' => 'Azərbaycan' ),
	array( 'slug' => 'kz', 'label' => 'Казах' ),
	array( 'slug' => 'ka', 'label' => 'Қорақалпоқ' ),
	array( 'slug' => 'kg', 'label' => 'Кыргыз' ),
	array( 'slug' => 'ru', 'label' => 'Русский' ),
	array( 'slug' => 'tj', 'label' => 'Тоҷик' ),
	array( 'slug' => 'tk', 'label' => 'Türkmen' ),
	array( 'slug' => 'uz', 'label' => 'Ўзбек' ),
);
?>

	<div id="primary" class="fp-content-area">
		<main id="main" class="site-main" role="main">
			<div class="entry-content">
				<div class="mfca-main container">
					<div class="mfca-main__row">
						<?php foreach ( $languages as $language ) : ?>
							<a href="<?php echo esc_url( home_url( '/c/' . $language['slug'] . '/' ) ); ?>" class="mfca-main__item">
								<div class="mfca-main__image">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/images/flags/' . $language['slug'] . '.png' ); ?>" alt="">
								</div>
								<div class="mfca-main__text">
									<?php echo esc_html( $language['label'] ); ?>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div><!-- .entry-content -->
		</main><!-- #main -->
	</div><!-- #primary --> 

<?php get_footer(); ?>
