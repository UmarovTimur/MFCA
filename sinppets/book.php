global $post;
?>
<script>
	let urlForEachPageSection = new URL(`${document.location.href}`);
	console.log(urlForEachPageSection.searchParams.get('section'));
	if (!urlForEachPageSection.searchParams.get('section')) {
		urlForEachPageSection.searchParams.set('section', `${document.getElementById('id-for-each-of-post').innerHTML}`);
		document.location.href = urlForEachPageSection.href;
	}
</script>
<style>
    .entry-title,#id-for-each-of-post {
    	display:none;
    }
    h3 {
        font-size: 32px;
    }
    .post__book {
        display: flex;
        column-gap: 25px;
    }
    .post__book-image  {
        flex: 0 0 250px;
    }
    .post__book-body {
        flex: 1 1 auto;
    }
	@media screen and (min-width:767px) {
		.post__book-body h3, .post__book-body h2 {
			margin-top:10px !important;	
		}
	}
	@media screen and (max-width:768px) {
		.post__book-body h3, .post__book-body h2 {
			margin-top:0.5em !important;	
		}
		.post__book {
			flex-direction: column;
			align-items:center;
		}
	}
	@media screen and (max-width:500px) {
	}
</style>

<?php
	$postslist = get_posts( array( 'posts_per_page' => "200", 'cat'=>$_GET['section'] ) );
?>
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
