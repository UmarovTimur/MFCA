<?php
/**
 * Archive of a language category: a slider of posts for each subcategory.
 *
 * @package Sydney
 */

$current_category = get_queried_object();
$subcategories    = get_categories(
	array(
		'parent'     => $current_category->term_id,
		'hide_empty' => true,
	)
);

if ( $subcategories ) : ?>
	<div class="subcategories-sliders">
		<?php foreach ( $subcategories as $subcat ) : ?>
			<?php $subcat_link = esc_url( get_category_link( $subcat->term_id ) ); ?>
			<div class="subcategory-slider-section">
				<div class="subcategory-header">
					<h2>
						<a href="<?php echo $subcat_link; ?>">
							<?php echo esc_html( mfca_translate( $subcat->name, $current_category->slug ) ); ?>
						</a>
					</h2>
					<div class="subcategory-meta">
						<span class="post-count"><?php echo (int) $subcat->count; ?> <?php echo esc_html( _n( 'книга', 'книг', $subcat->count ) ); ?></span>
						<a href="<?php echo $subcat_link; ?>" class="view-all">Смотреть все</a>
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
					<div class="books-slider" data-category="<?php echo esc_attr( mfca_translate( $subcat->slug, $current_category->slug ) ); ?>">
						<div class="books-container">
							<?php while ( $books_query->have_posts() ) : $books_query->the_post(); ?>
								<div class="book-card">
									<a href="<?php the_permalink(); ?>" class="book-link">
										<div class="book-cover">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php the_post_thumbnail( 'medium', array( 'class' => 'book-image' ) ); ?>
											<?php else : ?>
												<div class="book-placeholder"><span>Без обложки</span></div>
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
						<p>В этой категории пока нет книг.</p>
					</div>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
