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
        <header class="page-header">
				<?php
					// the_archive_title( '<h1 class="archive-title">', '</h1>' );
					// the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
                <div class="post-header _container">
                    <a id="_link-book" class="post-header__item">
                        <div class="post-header__link ">
                            <div class="post-header__img">
                                <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/Book-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Book
                            </div>
                        </div>
                    </a>
                    <a id="_link-udio" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                            <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/audio-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Audio
                            </div>
                        </div>
                    </a>
                    <a id="_link-ideo" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                                <img src="../../wp-content/themes/sydney.2.13/images/main-rubrik-in-lang/video-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Video
                            </div>
                        </div>
                    </a>
                    <a id="_link-tory" class="post-header__item">
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
						// получение url страницы
						let menuItemsObjectCategory = [...document.querySelectorAll('.menu-item-object-category')];
						let variableForHref = document.location.pathname.toString().slice(-5,-1);
						for (let i = 0;i<menuItemsObjectCategory.length;i++) {
							let therefs = [...menuItemsObjectCategory[i].getElementsByTagName('a')];
							if (variableForHref == 'book') {
								therefs[0].href = therefs[0].href.toString().slice(0,-1) + "-book/";
							}
							if (variableForHref == 'udio') {
								therefs[0].href = therefs[0].href.toString().slice(0,-1) + "-audio/";
							}
							if (variableForHref == 'ideo') {
								therefs[0].href = therefs[0].href.toString().slice(0,-1) + "-video/";
							}
							if (variableForHref == 'tory') {
								therefs[0].href = therefs[0].href.toString().slice(0,-1) + "-story/";
							}
						}
						
						
						
						
                        // активации на нужном типе записей
                        let variable = document.location.pathname.toString().slice(-5,-1);
                        document.getElementById(`_link-${variable}`).classList.add('_active');
                        // создании сыллок
                        let langArray = ['az','kz','ka','kg','ce','ru','tj','tk','uz','ug'];
                        let linksForPostHeaderItems = ['-book','-audio','-video','-story'];
                        let langNow = document.location.pathname.toString().replace('/category/','').slice(0,2);
                        // получение языка
                        for (let i = 0; i < linksForPostHeaderItems.length; i++) {
                            const element = linksForPostHeaderItems[i];
                            langNow = langNow.replace(element,'');
                        }
                        // создание ссылок на основе языка
                        let postHeaderItem = [...document.querySelectorAll('.post-header__item')];
                        for (let j = 0; j < postHeaderItem.length; j++) {
                            const el = postHeaderItem[j];
                            el.setAttribute('href',`../${langNow + linksForPostHeaderItems[j]}`);
                        }
                    </script>
                </div>
			</header>
            <!-- .page-header -->
		<?php if ( have_posts() ) : ?>
            <style>
                h3 a {
                    font-size: 28px;
                    color:var(--sydney-headings-color);
                }
                h3 a:hover {
                    opacity: 0.8;
                }
                @media screen and (max-width:550px) {
                    .posts-layout {
                        padding: 0 15px;
                    }
                }
                .post__book {
                    display: flex;
                    margin-bottom: 25px;
                    /* column-gap: 25px; */
                }
                .post__book-image {
                    flex: 1 0 auto;
                    text-align: center;
                    position: relative;
                    margin-right: 30px;

                }
                .post__book-image::before {
                    content:"";
                    position: absolute;
                    top:0;
                    left:0;
                    width: 100%;
                    height: 100%;
                    opacity:0;
                    background-color:#fff;
                    transition: all .3s ease 0s;
                }
                .post__book-image:hover::before {
                        opacity: .3;
                }
                .post__book-image img {
                    max-width: 170px;
                    height: auto;
                }
                .post__book-body {
                    flex: 1 1 auto;
                }
                @media (max-width:550px) {
                    .post__book {
                        flex-direction:column;
                        margin-bottom: 15px;
                    }
                    .post__book-image {
                        margin-right: 0px;
                    }
		    .post__book-image img {
                    	max-width: 300px;
	            }
                }
            </style>
			<div class="posts-layout">
				<div class="" <?php sydney_masonry_data(); ?>>
					<?php while ( have_posts() ) : the_post(); ?>

						<!-- get_template_part( 'content', get_post_format() ); -->
                        
						<div class="post__book">
                            <a href="<?php the_permalink() ?>" class="post__book-image">
                                <?php the_post_thumbnail(); ?>
                            </a>
                            <div class="post__book-body">
                                <h3>
                                    <a href="<?php the_permalink() ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>

					<?php endwhile; ?>
				</div>
			</div>
			
			<?php sydney_posts_navigation(); ?>	

		<?php else : ?>
            <style>
                #main {
                    padding-top: 25px;
                }
            </style>
			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php do_action('sydney_after_content'); ?>

<?php do_action( 'sydney_get_sidebar' ); ?>
<?php get_footer(); ?>
