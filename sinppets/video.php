global $post;
?>
<script>
    // получение url страницы
	let urlForEachPageSection = new URL(`${document.location.href}`);
    if (document.getElementById('id-for-each-of-post') == null) {
            console.error('Error - 101 [id-for-each-of-post]');
    } else if (!urlForEachPageSection.searchParams.get('section')) {
		urlForEachPageSection.searchParams.set('section', `${document.getElementById('id-for-each-of-post').innerHTML}`);
		document.location.href = urlForEachPageSection.href;
	}
</script>
<style>
    .entry-title,#id-for-each-of-post {
    	display:none;
    }
    /* h3 {
        font-size: 32px;
    } */
    .post-content__vidoe {
        /* display: flex; */
        /* flex-wrap:wrap; */
    }
    .post__video-image {
        flex: 0 0 400px;
        height: 100%;
    }
    @media (min-width:992px) {
        .post__video {
            display: flex;
            align-items: center;
        }
        .post__video-body {
            padding-left: 20px;
        }
    }
    .post__video-title {
        font-size: 21px;
        font-weight: bold;
        color:var(--sydney-headings-color);
        margin-top:8px;
        margin-bottom: 5px;
    }
    .post__video-text {
        font-size: 14px;
    }
</style>

<?php
	$postslist = get_posts( array( 'posts_per_page' => "200", 'cat'=>$_GET['section'] ) );
?>

<div class="post-content__vidoe">
    <?php foreach ( $postslist as $post ) {
        setup_postdata($post);?>
        <div class="post__video">
            <div class="post__video-image">
                <?php the_content(); ?>
            </div>
            <div class="post__video-body">
                <div class="post__video-title">
                    <?php the_title(); ?>
                </div>
                <div class="post__video-text">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
    
    	<div style="width:100%; height: 1px; background-color:rgba(0,0,0,.2)" ></div>
      <?php
    }
    wp_reset_postdata();  ?>
</div>