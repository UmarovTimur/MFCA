<?php
/**
 * Шаблон для главных категорий
 * Файл: part-templates/archive-main-category.php
 */

?>
<style>
	.subcategories-sliders {
	}
	.subcategory-slider-section {
	}
	.subcategory-header {
		margin-bottom: 15px;
	}
	.subcategory-meta {
	}
	.post-count {
	}
	.view-all {
	}
	.books-slider {
		display: flex;
		overflow-x: auto;
	}
	.books-slider::-webkit-scrollbar {
		display: none;
	}
	.books-container {
		display: flex;
		column-gap: 20px;
	}
	.book-card {
	
		width: 200px;

	
	}
	.book-link {
	}
	.book-cover {
		position: relative;
		width:100%;
		aspect-ratio: 2 / 3;
	}
	.book-cover img {
		width: 100%;
		height: 100%;
		position: absolute;
		top:0;
		left:0;
		object-fit: cover;	
	}
	.book-placeholder {
	}
	.book-info {
	}
	.book-title {
	}
	.book-author {
	}
	.no-books {
	}
</style>
<?php

$current_category = get_queried_object();
    $subcategories = get_categories(array(
        'parent' => $current_category->term_id,
        'hide_empty' => true
    ));
    
    if ($subcategories) : ?>
        <div class="subcategories-sliders">
            <?php foreach ($subcategories as $subcat) : ?>
                <div class="subcategory-slider-section">
                    <div class="subcategory-header">
                        <h2>
                            <a style="color: black;" href="<?php echo get_category_link($subcat->term_id); ?>">
                                <?php echo local_translate($subcat->name, $current_category->slug); ?>
                            </a>
                        </h2>
                        <div class="subcategory-meta">
                            <span class="post-count"><?php echo $subcat->count; ?> <?php echo _n('книга', 'книг', $subcat->count); ?></span>
                            <a href="<?php echo get_category_link($subcat->term_id); ?>" class="view-all">Смотреть все</a>
                        </div>
                    </div>

                    <!-- Слайдер книг -->
                    <?php
                    $books_query = new WP_Query(array(
                        'cat' => $subcat->term_id,
                        'posts_per_page' => 12,
                        'post_status' => 'publish'
                    ));
    

                    if ($books_query->have_posts()) : ?>
                        <div class="books-slider" data-category="<?php echo local_translate($subcat->slug, $current_slug); ?>">
                            <div class="books-container">
                                <?php while ($books_query->have_posts()) : $books_query->the_post(); ?>
                                    <div class="book-card">
                                        <a href="<?php the_permalink(); ?>" class="book-link">
                                            <div class="book-cover">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('medium', array('class' => 'book-image')); ?>
                                                <?php else : ?>
                                                    <div class="book-placeholder">
                                                        <span>Без обложки</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="book-info">
                                                <p class="book-title"><?php the_title(); ?></p>
                                                <?php 
                                                // Получаем автора (можно использовать custom field или мета)
                                                $author = get_post_meta(get_the_ID(), 'book_author', true);
                                                if ($author) : ?>
                                                    <p class="book-author"><?php echo esc_html($author); ?></p>
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
                    <?php endif; 
                    wp_reset_postdata(); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>