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
?>

	<?php do_action('sydney_before_content'); ?>

	<div id="primary" class="content-area <?php echo esc_attr( $sidebar_pos ); ?><?php echo esc_attr( apply_filters( 'sydney_content_area_class', 'col-md-9' ) ); ?>">
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					// the_archive_title( '<h1 class="archive-title">', '</h1>' );
					// the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
                <div class="post-header _container">
                    <a href="../book/" id="_link-book" class="post-header__item">
                        <div class="post-header__link ">
                            <div class="post-header__img">
                                <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/Book-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Book
                            </div>
                        </div>
                    </a>
                    <a href="../audio/" id="_link-audi" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                            <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/audio-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Audio
                            </div>
                        </div>
                    </a>
                    <a href="../video/" id="_link-vide" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                                <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/video-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Video
                            </div>
                        </div>
                    </a>
                    <a href="../story/" id="_link-stor" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                                <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/svidet-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Story
                            </div>
                        </div>
                    </a>
                    <style>	
                        .post-header {
                            display: flex;
                            padding: 4px 4px;
                            background-color: #00102E;
                            margin-bottom:27px;
                        }

                        .post-header__item {
                            padding: 10px 20px;
                            transition: all .3s ease 0s;
                        }
                        .post-header__item._active {
                            background-color: rgba(255, 255, 255, 0.6) !important;  
                        }
                        .post-header__item:hover {
                            background-color: rgba(255, 255, 255, 0.3);
                        }
                        .post-header__link {
                            display: flex;
                            align-items: center;
                            color: white;
                        }
                        .post-header__img {
                            height: 40px;
                            margin-right: 10px;
                        }
                        .post-header__img img{
                            max-width: 100%;
                            max-height: 100%;
                        }
                        .post-header__text {
                            color:white !important;
                        }
                        @media screen and (max-width:767.98px) {
                            .post-header {
                                margin-top: -15px;
                                margin-bottom:15px;
                                
                            }
                            .post-header__item {
                                flex: 0 1 25%;
                                padding: 10px 10px;
                            }
                            .post-header__img {
                                margin-right: 0;
                            }
                            .post-header__link {
                                flex-direction: column;
                            }
                        }
                    </style>
                    <script>
                    // активации на нужном видео записей
                    let variable = document.location.pathname.toString().slice(-5,-1);
                    document.getElementById(`_link-${variable}`).classList.add('_active');
                    // создании сыллок
                    let langNow = document.location.pathname.toString().slice(-8,-6);
                    let postHeaderItem = [...document.querySelectorAll('.post-header__item')];
                    let linksForPostHeaderItems = ['-book','-audio','-video','-story'];
                    for (let j = 0; j < postHeaderItem.length; j++) {
                        const el = postHeaderItem[j];
                        el.setAttribute('href',`../${langNow + linksForPostHeaderItems[j]}`);
                    }
                    </script>
                </div>
			</header><!-- .page-header -->
            
            <script>
                const langCategory = ['az','kz'];
                let urlLangCategory = document.location.pathname.toString();
                console.log(urlLangCategory);
                console.log(urlLangCategory[9][-1]);
                for (let i = 0; i <script langCategory.length; i++) {
                    if (urlLangCategory == langCategory) {
                        console.log('Redirect!');
                        break;
                    }
                }
            </script>
            <style>
                h3 {
                    font-size: 32px;
                }
                .post__book {
                    display: flex;
                    margin-bottom: 25px;
                    /* column-gap: 25px; */
                }
                .post__book-image  {
                    flex: 1 0 auto;
                    text-align: center;
                }
                .post__book-image img {
                    max-width: 250px;
                    height: auto;
                    margin-right: 30px;
                }
                .post__book-body {
                    flex: 1 1 auto;
                }
                @media (max-width:768px) {
                    .post__book {
                        flex-direction:column;
                        margin-bottom: 15px;
                    }
                    .post__book-image img {
                        margin-right: 0px;
                    }
                }
            </style>
			<div class="posts-layout container">
				<div class="" <?php sydney_masonry_data(); ?>>
					<?php while ( have_posts() ) : the_post(); ?>

						<!-- get_template_part( 'content', get_post_format() ); -->
                        
						<div class="post__book">
                            <a href="<?php the_permalink() ?>" class="post__book-image">
                                <?php the_post_thumbnail(); ?>
                            </a>
                            <div class="post__book-body">
                                <p><?php the_content(); ?></p>
                            </div>
                        </div>

					<?php endwhile; ?>
				</div>
			</div>
			
			<?php sydney_posts_navigation(); ?>	

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
