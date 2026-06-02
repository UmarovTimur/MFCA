<?php
/**
 * Шаблон для главных категорий
 * Файл: part-templates/archive-main-category.php
 */

$current_category = get_queried_object();
$subcategories   = get_categories(
	array(
		'parent'     => $current_category->term_id,
		'hide_empty' => true,
	)
);

if ( $subcategories ) : ?>
	<main id="main" class="post-wrap" role="main">
		<div class="subcategories-sliders">
			<?php foreach ( $subcategories as $subcat ) : ?>
				<section class="subcategory-slider-section">
					<div class="subcategory-header">
						<h2>
							<a href="<?php echo esc_url( get_category_link( $subcat->term_id ) ); ?>">
								<?php echo esc_html( local_translate( $subcat->name, $current_category->slug ) ); ?>
							</a>
						</h2>
						<div class="subcategory-meta">
							<span class="post-count">
								<?php
								printf(
									esc_html( _n( '%s книга', '%s книг', $subcat->count, 'sydney' ) ),
									esc_html( number_format_i18n( $subcat->count ) )
								);
								?>
							</span>
							<a href="<?php echo esc_url( get_category_link( $subcat->term_id ) ); ?>" class="view-all">
								<?php esc_html_e( 'Смотреть все', 'sydney' ); ?>
							</a>
						</div>
					</div>

					<?php
					$books_query = new WP_Query(
						array(
							'cat'            => $subcat->term_id,
							'posts_per_page' => 12,
							'post_status'    => 'publish',
						)
					);
					?>

					<?php if ( $books_query->have_posts() ) : ?>
						<div class="books-slider" data-category="<?php echo esc_attr( local_translate( $subcat->slug, $current_category->slug ) ); ?>">
							<div class="books-container">
								<?php while ( $books_query->have_posts() ) : $books_query->the_post(); ?>
									<div class="book-card">
										<a href="<?php the_permalink(); ?>" class="book-link">
											<div class="book-cover">
												<?php if ( has_post_thumbnail() ) : ?>
													<?php the_post_thumbnail( 'medium', array( 'class' => 'book-image' ) ); ?>
												<?php else : ?>
													<div class="book-placeholder">
														<span><?php esc_html_e( 'Без обложки', 'sydney' ); ?></span>
													</div>
												<?php endif; ?>
											</div>
											<div class="book-info">
												<p class="book-title"><?php the_title(); ?></p>
												<?php $author = get_post_meta( get_the_ID(), 'book_author', true ); ?>
												<?php if ( $author ) : ?>
													<p class="book-author"><?php echo esc_html( $author ); ?></p>
												<?php endif; ?>
											</div>
										</a>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					<?php else : ?>
						<div class="no-books">
							<p><?php esc_html_e( 'В этой категории пока нет книг.', 'sydney' ); ?></p>
						</div>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</section>
			<?php endforeach; ?>
		</div>
	</main>
<?php endif; ?>
