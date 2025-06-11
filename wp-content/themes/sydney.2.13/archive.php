<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sydney
 */

get_header();

$layout 		= sydney_blog_layout();
$sidebar_pos 	= sydney_sidebar_position();

$main_categories = ['az','kz','ka','kg','ce','ru','tj','tk','uz','ug'];
$category = get_queried_object();
?>

	<!-- <?php do_action('sydney_before_content'); ?> -->

    <style>
        .page-wrap {
            padding-bottom: 0px !important;
        }
        .content-wrapper {
            flex: 1 1 auto;
            padding-bottom: 20px;
        }
    </style>
	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?><?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
	
    <?php

        $current_category = get_queried_object();
        $current_slug = $current_category->slug;
        
        // Проверяем, является ли текущая категория главной
        $is_main_category = in_array($current_slug, $main_categories);
        
        // Проверяем, является ли родительская категория главной (для подкатегорий)
        $parent_is_main = false;
        if ($current_category->parent != 0) {
            $parent_category = get_category($current_category->parent);
            $parent_is_main = in_array($parent_category->slug, $main_categories);
        }
        
        if ($is_main_category) {
            // Подключаем шаблон для ГЛАВНЫХ категорий
            get_template_part('part-templates/archive', 'main-category');
            
        } elseif ($parent_is_main) {
            // Подключаем шаблон для ПОДКАТЕГОРИЙ
            get_template_part('part-templates/archive', 'subcategory');
            
        } else {
            // Обычный шаблон для остальных категорий
            get_template_part('part-templates/archive', 'default');
        }

    ?>
   

	<!-- <?php do_action('sydney_after_content'); ?> -->

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
