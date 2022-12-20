<!--=======================  audio   =========================-->

global $post;
?>

<script>
    // Запрос Постов для страницы
    // получение url страницы
	let urlForEachPageSection = new URL(`${document.location.href}`);
	if (!urlForEachPageSection.searchParams.get('section')) {
		urlForEachPageSection.searchParams.set('section', `${document.getElementById('id-for-each-of-post').innerHTML}`);
		document.location.href = urlForEachPageSection.href;
	} else {
        alert('Некорректно указан ID рубрики в редакторе страниц - Код ошибки 101.');
    }
</script>
<style>
/* Скрытие "Заголовка страницы" "блока для указания ID рубрики" */
.entry-title,#id-for-each-of-post {
	display:none;
}
</style>



<?php
	$postslist = get_posts( array( 'posts_per_page' => "200", 'cat'=>$_GET['section'] ) );
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