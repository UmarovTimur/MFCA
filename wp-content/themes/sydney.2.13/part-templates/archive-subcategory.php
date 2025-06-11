<?php
/**
 * Шаблон для подкатегорий
 * Файл: part-template/archive-subcategory.php
 */

$current_category = get_queried_object();
$parent_category = get_category($current_category->parent);
?>

<main id="main" class="post-wrap" role="main">
                    <?php post_types_menu_header() ?>
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
                                flex: 1 1 100%;
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
                        <div class="posts-layout" <?php sydney_masonry_data(); ?>>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    
                                    <?php if (has_post_thumbnail()) {
                                    ?>
                                        <div class="post__book">
                                        <a href="<?php the_permalink() ?>" class="post__book-image">
                                            <?php the_post_thumbnail(); ?>
                                        </a>
                                        <div class="post__book-body">
                                            <h3>
                                                <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                                            </h3>
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div style="display:block;" class="post__book">
                                            <?php the_content();?>
                                        </div>
                                    
                                    <?php
                                    }
                                    endwhile; ?>
                        </div>
                        
                        <?php sydney_posts_navigation(); ?>	

                    <?php else : ?>

                        <?php get_template_part( 'content', 'none' ); ?>

                    <?php endif; ?>
                </main><!-- #main -->