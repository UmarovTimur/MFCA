<?php
/**
 * Archive: list of posts with cover, title and excerpt.
 *
 * @package Sydney
 */
?>
<main id="main" class="post-wrap" role="main">
	<h1 class="screen-reader-text"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>

	<?php mfca_post_types_menu(); ?>

	<?php if ( have_posts() ) : ?>

		<div class="posts-layout<?php echo mfca_is_video_category() ? ' posts-layout--video' : ''; ?>" <?php sydney_masonry_data(); ?>>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post__book">
						<a href="<?php the_permalink(); ?>" class="post__book-image">
							<?php the_post_thumbnail( mfca_is_video_category() ? 'medium_large' : 'post-thumbnail' ); ?>
						</a>
						<div class="post__book-body">
							<h3>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php the_excerpt(); ?>
						</div>
					</div>
				<?php else : ?>
					<div class="post__book post__book--text">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>

		<?php sydney_posts_navigation(); ?>

	<?php else : ?>

		<?php get_template_part( 'content', 'none' ); ?>

	<?php endif; ?>
</main><!-- #main -->
