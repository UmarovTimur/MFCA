<?php
/**
 * Default archive template partial.
 *
 * @package Sydney
 */
?>

<main id="main" class="post-wrap" role="main">
	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</header>

		<div class="posts-layout" <?php sydney_masonry_data(); ?>>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>
		</div>

		<?php sydney_posts_navigation(); ?>
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>
</main><!-- #main -->

