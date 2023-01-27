
<!--===================================== Вывод записией(книг) -->
global $post;
?>
<script>
    // Запрос Постов для страницы
    // получение url страницы
	let urlForEachPageSection = new URL(`${document.location.href}`);
    if (document.getElementById('id-for-each-of-post') == null) {
        alert('Некорректно указан ID рубрики в редакторе страниц - Код ошибки 101.');
    }else if (!urlForEachPageSection.searchParams.get('section')) {
		urlForEachPageSection.searchParams.set('section', `${document.getElementById('id-for-each-of-post').innerHTML}`);
		document.location.href = urlForEachPageSection.href;
	}
</script>
<style>
.entry-title,#id-for-each-of-post {
	display:none;
}
</style>

<?php
	$postslist = get_posts( array( 'posts_per_page' => "200", 'cat'=>$_GET['section'] ) );
?>
	    <style>
        h3 {
            font-size: 32px;
        }
        .post__book {
            display: flex;
            /* column-gap: 25px; */
        }
        .post__book-image  {
            flex: 1 0 auto;
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
                align-items:
            }
        }
    </style>
<?php

foreach ( $postslist as $post ){
  setup_postdata($post);?>

    <div class="post__book">
        <div class="post__book-image">
            <?php the_post_thumbnail(); ?>
        </div>
        <div class="post__book-body">
            <?php the_content(); ?>
        </div>
    </div>
	  
	  <div style="width:100%; height: 1px; background-color:rgba(0,0,0,.2)" ></div>
  <?php
}
wp_reset_postdata();  ?>
<!--================================================-->

<?php the_date(); ?> - выводит дату новости 
<?php the_title(); ?> - выводит заголовок новости
<?php the_excerpt(); ?> - выводит краткое описание 
<?php the_post_thumbnail(); ?> - выводит превью новости - картинку 

<!--================================================-->
