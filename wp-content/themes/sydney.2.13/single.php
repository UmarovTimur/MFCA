<?php
/**
 * The template for displaying all single posts.
 *
 * @package Sydney
 */

get_header(); ?>

    <script>
        // 	breadcrumbs__link
        const categories = ["book", "audio", "video", "story"];
        document.addEventListener("DOMContentLoaded", (e) => {
        let nodeLinks = document.querySelectorAll(".breadcrumbs__link");
        nodeLinks.forEach((el) => {
            let text = el.querySelector("span").textContent;
            if (categories.includes(text)) {
            el.querySelector("span").textContent =
                text[0].toUpperCase() + text.slice(1);
            }
        });
        });
    </script> 

    <style>
        @media screen and (min-width:922px) {
		    .content-wrapper {
			    width:100%;
	        }
        }
        #main {
            max-width: 1170px;
            margin: 0 auto;
            /* padding-top:15px; */
        }

        .single .entry-header, .single .entry-footer {
            flex: 0 0 100%;
            margin:0;
        }
        .entry-header .entry-meta {
            margin:0;
        } 

        .single .entry-content {
            flex:0 1 calc(100% - 440px);
        }
        .entry-content h3, .entry-content h2, .entry-content h1, .entry-content h4{
            margin-top:0.5em !important;
        }
        .content-inner {
            padding-top:15px;
        }
        .single .entry-thumb {
            margin-bottom:0px;
        }
        @media (min-width:992.98px) {
            .content-inner {
                display:flex;
                flex-wrap:wrap;
                padding-top:30px;
            }
            #main {
                /* padding-top:30px; */
            }
            .single .entry-thumb {
                flex: 0 0 420px;
                margin-right:20px;
            }
            
        }


        .content-area .post-wrap, .contact-form-wrap {
            padding-right: 0px;
        }
        @media (max-width: 1024px) {
            .container {
                width: 100% !important;
            }
            h3 {
                font-size: 32px;
            }
        }
        .entry-title {
            display:none;
        }


    </style>


    <style>
        .custom-slider {
            display: flex;
            overflow-x: auto;
            gap: 16px;
            padding: 10px 0;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            cursor: grab;
        }
        .custom-slider:active {
            cursor: grabbing;
        }
        .slider-card {
            flex: 0 0 auto;
            width: 150px;
            scroll-snap-align: start;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.3s;
        }
        .slider-card:hover {
            opacity: 0.8;
        }
        .slider-thumb {
            aspect-ratio: 2 / 3;
            overflow: hidden;
            border-radius: 8px;
        }
        .slider-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 8px;
        }
        .slider-title {
            margin-top: 6px;
            font-size: 0.95rem;
            font-weight: 500;
        }

    </style>
    
    <style>

        .custom-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }

        .grid-card {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.3s ease;
        }

        .grid-card:hover {
            opacity: 0.8;
        }

        .grid-thumb {
            aspect-ratio: 2 / 3;
            overflow: hidden;
        }

        .grid-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .grid-title {
            margin-top: 6px;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.3;
            font-weight: bold;
        }
    </style>


	<?php $sidebar_pos 	= sydney_sidebar_position(); ?>

	<?php if (get_theme_mod('fullwidth_single')) { //Check if the post needs to be full width
		$width = 'fullwidth';
	} else {
		$width = 'col-md-9';
	} ?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?> <?php echo esc_attr( apply_filters( 'sydney_content_area_class', $width ) ); ?>">

		<?php sydney_yoast_seo_breadcrumbs(); ?>

		<main id="main" class="post-wrap" role="main">

        <?php post_types_menu_header() ?>

        <?php if ( function_exists( 'dimox_breadcrumbs' ) ) dimox_breadcrumbs(); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

            <?php
                $categories = get_the_category();
                $main_cat = null;
                foreach ($categories as $cat) {
                    if ($cat->category_parent == 0) {
                        $main_cat = $cat;
                        break;
                    }
                }
                
                if ( $main_cat ) {
                    $posts = get_posts( array(
                        'category__in' => array( $main_cat->term_id ),
                        'post__not_in' => array( get_the_ID() ), // исключаем текущий пост!
                        'posts_per_page' => 10,
                    ) );
                
                    echo render_post_grid( $posts );
                }
            ?>
            



		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
