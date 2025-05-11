<?php
/**
 * Sydney functions and definitions
 *
 * @package Sydney
 */

if ( ! function_exists( 'sydney_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sydney_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Sydney, use a find and replace
	 * to change 'sydney' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sydney', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Content width
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1170; /* pixels */
	}

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('sydney-large-thumb', 1000);
	add_image_size('sydney-medium-thumb', 550, 400, true);
	add_image_size('sydney-small-thumb', 230);
	add_image_size('sydney-service-thumb', 350);
	add_image_size('sydney-mas-thumb', 480);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sydney' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'sydney_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	//Gutenberg align-wide support
	add_theme_support( 'align-wide' );

	//Enable template editing. Can't use theme.json right now because it disables wide/full alignments
	add_theme_support( 'block-templates' );

	//Forked Owl Carousel flag
	$forked_owl = get_theme_mod( 'forked_owl_carousel', false );
	if ( !$forked_owl ) {
		set_theme_mod( 'forked_owl_carousel', true );
	}	

	//Set the compare icon for YTIH button
	update_option( 'yith_woocompare_button_text', sydney_get_svg_icon( 'icon-compare', false ) );
}
endif; // sydney_setup
add_action( 'after_setup_theme', 'sydney_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sydney_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'sydney' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	//Footer widget areas
	for ( $i=1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => __( 'Footer ', 'sydney' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	//Register the front page widgets
	if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		register_widget( 'Sydney_List' );
		register_widget( 'Sydney_Services_Type_A' );
		register_widget( 'Sydney_Services_Type_B' );
		register_widget( 'Sydney_Facts' );
		register_widget( 'Sydney_Clients' );
		register_widget( 'Sydney_Testimonials' );
		register_widget( 'Sydney_Skills' );
		register_widget( 'Sydney_Action' );
		register_widget( 'Sydney_Video_Widget' );
		register_widget( 'Sydney_Social_Profile' );
		register_widget( 'Sydney_Employees' );
		register_widget( 'Sydney_Latest_News' );
		register_widget( 'Sydney_Portfolio' );
	}
	register_widget( 'Sydney_Contact_Info' );

}
add_action( 'widgets_init', 'sydney_widgets_init' );

/**
 * Load the front page widgets.
 */
if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
	require get_template_directory() . "/widgets/fp-list.php";
	require get_template_directory() . "/widgets/fp-services-type-a.php";
	require get_template_directory() . "/widgets/fp-services-type-b.php";
	require get_template_directory() . "/widgets/fp-facts.php";
	require get_template_directory() . "/widgets/fp-clients.php";
	require get_template_directory() . "/widgets/fp-testimonials.php";
	require get_template_directory() . "/widgets/fp-skills.php";
	require get_template_directory() . "/widgets/fp-call-to-action.php";
	require get_template_directory() . "/widgets/video-widget.php";
	require get_template_directory() . "/widgets/fp-social.php";
	require get_template_directory() . "/widgets/fp-employees.php";
	require get_template_directory() . "/widgets/fp-latest-news.php";
	require get_template_directory() . "/widgets/fp-portfolio.php";

	/**
	 * Page builder support
	 */
	require get_template_directory() . '/inc/so-page-builder.php';	
}
require get_template_directory() . "/widgets/contact-info.php";

/**
 * Enqueue scripts and styles.
 */
function sydney_admin_scripts() {
	wp_enqueue_script( 'sydney-admin-functions', get_template_directory_uri() . '/js/admin-functions.js', array('jquery'),'20211006', true );
	wp_localize_script( 'sydney-admin-functions', 'sydneyadm', array(
		'fontawesomeUpdate' => array(
			'confirmMessage' => __( 'Are you sure? Keep in mind this is a global change and you will need update your icons class names in all theme widgets and post types that use Font Awesome 4 icons.', 'sydney' ),
			'errorMessage' => __( 'It was not possible complete the request, please reload the page and try again.', 'sydney' )
		),
		'headerUpdate' => array(
			'confirmMessage' => __( 'Are you sure you want to upgrade your header?', 'sydney' ),
			'errorMessage' => __( 'It was not possible complete the request, please reload the page and try again.', 'sydney' )
		),
		'headerUpdateDimiss' => array(
			'confirmMessage' => __( 'Are you sure you want to dismiss this notice?', 'sydney' ),
			'errorMessage' => __( 'It was not possible complete the request, please reload the page and try again.', 'sydney' )
		),					
	) );
}
add_action( 'admin_enqueue_scripts', 'sydney_admin_scripts' );

/**
 * Use the modern header in new installs
 */
function sydney_set_modern_header_flag() {
	update_option( 'sydney-update-header', true );

	//Disable old content position code
	update_option( 'sydney_woo_content_pos_disable', true );

	//Disable single product sidebar
	set_theme_mod( 'swc_sidebar_products', true );

	//Disable shop archive sidebar
	set_theme_mod( 'shop_archive_sidebar', 'no-sidebar' );	
}
add_action( 'after_switch_theme', 'sydney_set_modern_header_flag' );

/**
 * Elementor editor scripts
 */
function sydney_elementor_editor_scripts() {
	wp_enqueue_script( 'sydney-elementor-editor', get_template_directory_uri() . '/js/elementor.js', array( 'jquery' ), '20200504', true );
}
add_action('elementor/frontend/after_register_scripts', 'sydney_elementor_editor_scripts');

/**
 * Enqueue scripts and styles.
 */
function sydney_scripts() {

	$is_amp = sydney_is_amp();

	wp_enqueue_style( 'sydney-google-fonts', esc_url( sydney_google_fonts_url() ), array(), null );

	wp_enqueue_style( 'sydney-ie9', get_template_directory_uri() . '/css/ie9.css', array( 'sydney-style' ) );
	wp_style_add_data( 'sydney-ie9', 'conditional', 'lte IE 9' );

	if ( !$is_amp ) {
		wp_enqueue_script( 'sydney-functions', get_template_directory_uri() . '/js/functions.min.js', array(), '20220628', true );
		
		//Enqueue hero slider script only if the slider is in use
		$slider_home = get_theme_mod('front_header_type','nothing');
		$slider_site = get_theme_mod('site_header_type');
		if ( ( $slider_home == 'slider' && is_front_page() ) || ( $slider_site == 'slider' && !is_front_page() ) ) {
			wp_enqueue_script( 'sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'),'', true );
			wp_enqueue_script( 'sydney-hero-slider', get_template_directory_uri() . '/js/hero-slider.js', array('jquery'),'', true );
			wp_enqueue_style( 'sydney-hero-slider', get_template_directory_uri() . '/css/components/hero-slider.min.css', array(), '20220824' );
		}
	}

	if ( class_exists( 'Elementor\Plugin' ) ) {
		wp_enqueue_script( 'sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'),'', true );		

		wp_enqueue_style( 'sydney-elementor', get_template_directory_uri() . '/css/components/elementor.min.css', array(), '20220824' );
	}

	if ( defined( 'SITEORIGIN_PANELS_VERSION' )	) {

		wp_enqueue_style( 'sydney-siteorigin', get_template_directory_uri() . '/css/components/siteorigin.min.css', array(), '20220824' );

		wp_enqueue_script( 'sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'),'', true );

		wp_enqueue_script( 'sydney-so-legacy-scripts', get_template_directory_uri() . '/js/so-legacy.js', array('jquery'),'', true );

		wp_enqueue_script( 'sydney-so-legacy-main', get_template_directory_uri() . '/js/so-legacy-main.min.js', array('jquery'),'', true );

		if( get_option( 'sydney-fontawesome-v5' ) ) {
			wp_enqueue_style( 'sydney-font-awesome-v5', get_template_directory_uri() . '/fonts/font-awesome-v5/all.min.css' );
		} else {
			wp_enqueue_style( 'sydney-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );
		}
	}

	if ( is_singular() && ( comments_open() || '0' != get_comments_number() ) ) {
		wp_enqueue_style( 'sydney-comments', get_template_directory_uri() . '/css/components/comments.min.css', array(), '20220824' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_enqueue_style( 'sydney-style-min', get_template_directory_uri() . '/css/styles.min.css', '', '20220919' );

	wp_enqueue_style( 'sydney-style', get_stylesheet_uri(), '', '20220919' );
}
add_action( 'wp_enqueue_scripts', 'sydney_scripts' );

/**
 * Disable Elementor globals on theme activation
 */
function sydney_disable_elementor_globals () {
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_onboarded', true );
}
add_action('after_switch_theme', 'sydney_disable_elementor_globals');

/**
 * Enqueue Bootstrap
 */
function sydney_enqueue_bootstrap() {
	wp_enqueue_style( 'sydney-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true );
}
add_action( 'wp_enqueue_scripts', 'sydney_enqueue_bootstrap', 9 );

/**
 * Elementor editor scripts
 */

/**
 * Change the excerpt length
 */
function sydney_excerpt_length( $length ) {

  $excerpt = get_theme_mod('exc_lenght', 22 );
  return $excerpt;

}
add_filter( 'excerpt_length', 'sydney_excerpt_length', 999 );

/**
 * Blog layout
 */
function sydney_blog_layout() {
	$layout = get_theme_mod( 'blog_layout', 'layout2' );
	return $layout;
}

/**
 * Menu fallback
 */
function sydney_menu_fallback() {
	if ( current_user_can('edit_theme_options') ) {
		echo '<a class="menu-fallback" href="' . admin_url('nav-menus.php') . '">' . __( 'Create your menu here', 'sydney' ) . '</a>';
	}
}

/**
 * Header image overlay
 */
function sydney_header_overlay() {
	$overlay = get_theme_mod( 'hide_overlay', 0);
	if ( !$overlay ) {
		echo '<div class="overlay"></div>';
	}
}

/**
 * Header video
 */
function sydney_header_video() {

	if ( !function_exists('the_custom_header_markup') ) {
		return;
	}

	$front_header_type 	= get_theme_mod( 'front_header_type' );
	$site_header_type 	= get_theme_mod( 'site_header_type' );

	if ( ( get_theme_mod('front_header_type') == 'core-video' && is_front_page() || get_theme_mod('site_header_type') == 'core-video' && !is_front_page() ) ) {
		the_custom_header_markup();
	}
}

/**
 * Preloader
 * Hook into 'wp_body_open' to ensure compatibility with 
 * header/footer builder plugins
 */
function sydney_preloader() {

	$preloader = get_theme_mod( 'enable_preloader', 1 );

	if ( sydney_is_amp() || !$preloader ) {
		return;
	}

	?>
	<div class="preloader">
	    <div class="spinner">
	        <div class="pre-bounce1"></div>
	        <div class="pre-bounce2"></div>
	    </div>
	</div>
	<?php
}
add_action('wp_body_open', 'sydney_preloader');
add_action('elementor/theme/before_do_header', 'sydney_preloader'); // Elementor Pro Header Builder

/**
 * Header clone
 */
function sydney_header_clone() {

	$front_header_type 	= get_theme_mod('front_header_type','nothing');
	$site_header_type 	= get_theme_mod('site_header_type');

	if ( class_exists( 'Woocommerce' ) ) {

		if ( is_shop() ) {
			$shop_thumb = get_the_post_thumbnail_url( get_option( 'woocommerce_shop_page_id' ) );

			if ( $shop_thumb ) {
				return;
			}
		} elseif ( is_product_category() ) {
			global $wp_query;
			$cat 				= $wp_query->get_queried_object();
			$thumbnail_id 		= get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$shop_archive_thumb	= wp_get_attachment_url( $thumbnail_id );
			
			if ( $shop_archive_thumb ) {
				return;
			}
		}
	}

	if ( ( $front_header_type == 'nothing' && is_front_page() ) || ( $site_header_type == 'nothing' && !is_front_page() ) ) {
		echo '<div class="header-clone"></div>';
	}
}
add_action('sydney_before_header', 'sydney_header_clone');


// == Redirecy from /category/ to /c/
add_action('template_redirect', function () {
    if (strpos($_SERVER['REQUEST_URI'], '/category/') === 0) {
        $new_url = str_replace('/category/', '/c/', $_SERVER['REQUEST_URI']);
        wp_redirect($new_url, 301);
        exit;
    }
});


/**
 * Get image alt
 */
function sydney_get_image_alt( $image ) {
    global $wpdb;

    if( empty( $image ) ) {
        return false;
    }

    $attachment  = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid=%s;", strtolower( $image ) ) );
    $id   = ( ! empty( $attachment ) ) ? $attachment[0] : 0;

    $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

    return $alt;
}

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * from TwentyTwenty
 * 
 * @link https://git.io/vWdr2
 */
function sydney_skip_link_focus_fix() {

	if ( sydney_is_amp() ) { 
		return;
	}
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'sydney_skip_link_focus_fix' );

/**
 * Get SVG code for specific theme icon
 */
function sydney_get_svg_icon( $icon, $echo = false ) {
	$svg_code = wp_kses( //From TwentTwenty. Keeps only allowed tags and attributes
		Sydney_SVG_Icons::get_svg_icon( $icon ),
		array(
			'svg'     => array(
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
				'fill'        => true,
			),
			'path'    => array(
				'fill'      => true,
				'fill-rule' => true,
				'd'         => true,
				'transform' => true,
				'stroke'	=> true,
				'stroke-width' => true,
				'stroke-linejoin' => true
			),
			'polygon' => array(
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			),
			'rect'    => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'transform' => true
			),
		)
	);	

	if ( $echo != false ) {
		echo $svg_code; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $svg_code;
	}
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Page metabox
 */
require get_template_directory() . '/inc/classes/class-sydney-page-metabox.php';

/**
 * Posts archive
 */
require get_template_directory() . '/inc/classes/class-sydney-posts-archive.php';

/**
 * Display conditions
 */
require get_template_directory() . '/inc/display-conditions.php';

/**
 * Header
 */
require get_template_directory() . '/inc/classes/class-sydney-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Slider
 */
require get_template_directory() . '/inc/slider.php';

/**
 * Styles
 */
require get_template_directory() . '/inc/styles.php';

/**
 * Woocommerce basic integration
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * WPML
 */
if ( class_exists( 'SitePress' ) ) {
	require get_template_directory() . '/inc/integrations/wpml/class-sydney-wpml.php';
}

/**
 * LifterLMS
 */
if ( class_exists( 'LifterLMS' ) ) {
	require get_template_directory() . '/inc/integrations/lifter/class-sydney-lifterlms.php';
}

/**
 * Learndash
 */
if ( class_exists( 'SFWD_LMS' ) ) {
	require get_template_directory() . '/inc/integrations/learndash/class-sydney-learndash.php';
}

/**
 * Learnpress
 */
if ( class_exists( 'LearnPress' ) ) {
	require get_template_directory() . '/inc/integrations/learnpress/class-sydney-learnpress.php';
}

/**
 * Max Mega Menu
 */
if ( function_exists('max_mega_menu_is_enabled') ) {
	require get_template_directory() . '/inc/integrations/class-sydney-maxmegamenu.php';
}

/**
 * AMP
 */
require get_template_directory() . '/inc/integrations/class-sydney-amp.php';

/**
 * Upsell
 */
require get_template_directory() . '/inc/customizer/upsell/class-customize.php';

/**
 * Gutenberg
 */
require get_template_directory() . '/inc/editor.php';

/**
 * Fonts
 */
require get_template_directory() . '/inc/fonts.php';

/**
 * SVG codes
 */
require get_template_directory() . '/inc/classes/class-sydney-svg-icons.php';

/**
 * Review notice
 */
require get_template_directory() . '/inc/notices/class-sydney-review.php';

/**
 * Schema
 */
require get_template_directory() . '/inc/schema.php';

/**
 * Theme dashboard.
 */
require get_template_directory() . '/theme-dashboard/class-theme-dashboard.php';

/**
 * Theme update migration functions
 */
require get_template_directory() . '/inc/theme-update.php';

/**
 * Theme dashboard settings.
 */
require get_template_directory() . '/inc/theme-dashboard-settings.php';

/**
 * Premium modules
 */
require get_template_directory() . '/inc/classes/class-sydney-modules.php';

/*
 * Enable fontawesome 5 on first time theme activation
 * Check if the old theme is sydney to avoid enable the fa5 automatic and break icons
 * Since this hook also run on theme updates
 */
function sydney_enable_fontawesome_latest_version( $old_theme_name ) {
	$old_theme_name = strtolower( $old_theme_name );
	if( !get_option( 'sydney-fontawesome-v5' ) && strpos( $old_theme_name, 'sydney' ) === FALSE ) {
		update_option( 'sydney-fontawesome-v5', true );
	}
}
add_action('after_switch_theme', 'sydney_enable_fontawesome_latest_version');

/**
 * Autoload
 */
require_once get_parent_theme_file_path( 'vendor/autoload.php' );

/**
 * Sydney Toolbox and fontawesome update notice
 */
if ( defined( 'SITEORIGIN_PANELS_VERSION' ) && ( isset($pagenow) && $pagenow == 'themes.php' ) && isset( $_GET['page'] ) && $_GET['page'] == 'theme-dashboard' ) {
	function sydney_toolbox_fa_update_admin_notice(){
		$all_plugins    = get_plugins();
		$active_plugins = get_option( 'active_plugins' );
		$theme_version  = wp_get_theme( 'sydney' )->Version;

		// Check if Sydney Toolbox plugin is active
		if( ! in_array( 'sydney-toolbox/sydney-toolbox.php', $active_plugins ) ) {
			return;
		}

		if( version_compare( $all_plugins['sydney-toolbox/sydney-toolbox.php']['Version'], '1.16', '>=' ) ) {
			if( !get_option( 'sydney-fontawesome-v5' ) ) { ?> 
				<div class="notice notice-success thd-theme-dashboard-notice-success is-dismissible">
					<p>
						<strong><?php esc_html_e( 'Sydney Font Awesome Update: ', 'sydney'); ?></strong> <?php esc_html_e( 'Your website is currently running the version 4. Click in the below button to update to version 5.', 'sydney' ); ?>
						<br>
						<strong><?php esc_html_e( 'Important: ', 'sydney'); ?></strong> <?php esc_html_e( 'This is a global change. That means this change will affect all website icons and you will need update the icons class names in all theme widgets and post types that use Font Awesome 4 icons. For example: "fa-android" to "fab fa-android".', 'sydney' ); ?>
					</p>
					<a href="#" class="button sydney-update-fontawesome" data-nonce="<?php echo esc_attr( wp_create_nonce( 'sydney-fa-updt-nonce' ) ); ?>" style="margin-bottom: 9px;"><?php esc_html_e( 'Update to v5', 'sydney' ); ?></a>
					<br>
				</div>
			<?php
			}
			return;
		} ?>

		<div class="notice notice-success thd-theme-dashboard-notice-success is-dismissible">
			<p>
				<?php echo wp_kses_post( sprintf( __( '<strong>Optional:</strong> Now <strong>Sydney</strong> is compatible with Font Awesome 5. For it is needed the latest version of <strong>Sydney Toolbox</strong> plugin. You can update the plugin <a href="%s">here</a>.', 'sydney' ), admin_url( 'plugins.php' ) ) ); ?><br>
				<strong><?php esc_html_e( 'Important: ', 'sydney'); ?></strong> <?php esc_html_e( 'This is a global change. That means this change will affect all website icons and you will need update the icons class names in all theme widgets and post types that use Font Awesome 4 icons. For example: "fa-android" to "fab fa-android".', 'sydney' ); ?>
			</p>
		</div>
<?php
	}
	add_action('admin_notices', 'sydney_toolbox_fa_update_admin_notice');
}



/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2019.03.03
 * лицензия: MIT
*/
function dimox_breadcrumbs() {

	/* === ОПЦИИ === */
	$text['home']     = 'Главная'; // текст ссылки "Главная"
	$text['category'] = '%s'; // текст для страницы рубрики
	$text['search']   = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
	$text['tag']      = 'Записи с тегом "%s"'; // текст для страницы тега
	$text['author']   = 'Статьи автора %s'; // текст для страницы автора
	$text['404']      = 'Ошибка 404'; // текст для страницы 404
	$text['page']     = 'Страница %s'; // текст 'Страница N'
	$text['cpage']    = 'Страница комментариев %s'; // текст 'Страница комментариев N'

	$wrap_before    = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
	$wrap_after     = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
	$sep            = '<span class="breadcrumbs__separator"> › </span>'; // разделитель между "крошками"
	$before         = '<span class="breadcrumbs__current">'; // тег перед текущей "крошкой"
	$after          = '</span>'; // тег после текущей "крошки"

	$show_on_home   = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
	$show_home_link = 0; // 1 - показывать ссылку "Главная", 0 - не показывать
	$show_current   = 1; // 1 - показывать название текущей страницы, 0 - не показывать
	$show_last_sep  = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
	/* === КОНЕЦ ОПЦИЙ === */

	global $post;
	$home_url       = home_url('/');
	$link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
	$link          .= '<meta itemprop="position" content="%3$s" />';
	$link          .= '</span>';
	$parent_id      = ( $post ) ? $post->post_parent : '';
	$home_link      = sprintf( $link, $home_url, $text['home'], 1 );

	if ( is_home() || is_front_page() ) {

		if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;

	} else {

		$position = 0;

		echo $wrap_before;

		if ( $show_home_link ) {
			$position += 1;
			echo $home_link;
		}

		if ( is_category() ) {
			$parents = get_ancestors( get_query_var('cat'), 'category' );
			foreach ( array_reverse( $parents ) as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$cat = get_query_var('cat');
				echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_search() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $show_home_link ) echo $sep;
				echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['search'], get_search_query() ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_year() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_time('Y') . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_month() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_day() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
			$position += 1;
			echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$position += 1;
				$post_type = get_post_type_object( get_post_type() );
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
				if ( $show_current ) echo $sep . $before . get_the_title() . $after;
				elseif ( $show_last_sep ) echo $sep;
			} else {
				$cat = get_the_category(); $catID = $cat[0]->cat_ID;
				$parents = get_ancestors( $catID, 'category' );
				$parents = array_reverse( $parents );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position += 1;
					if ( $position > 1 ) echo $sep;
					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}
				if ( get_query_var( 'cpage' ) ) {
					$position += 1;
					echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
					echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
				} else {
					if ( $show_current ) echo $sep . $before . get_the_title() . $after;
					elseif ( $show_last_sep ) echo $sep;
				}
			}

		} elseif ( is_post_type_archive() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && !in_array($post_type->name, array('book', 'audio', 'story', 'video')) && $show_current ) echo $sep;
				if ( $show_current && !in_array($post_type->name, array('book', 'audio', 'story', 'video')) ) echo $before . $post_type->label . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}
		} elseif ( is_attachment() ) {
			$parent = get_post( $parent_id );
			$cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
			$parents = get_ancestors( $catID, 'category' );
			$parents = array_reverse( $parents );
			$parents[] = $catID;
			foreach ( $parents as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			$position += 1;
			echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_page() && ! $parent_id ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_title() . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_page() && $parent_id ) {
			$parents = get_post_ancestors( get_the_ID() );
			foreach ( array_reverse( $parents ) as $pageID ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
			}
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_tag() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$tagID = get_query_var( 'tag_id' );
				echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_author() ) {
			$author = get_userdata( get_query_var( 'author' ) );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_404() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . $text['404'] . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( has_post_format() && ! is_singular() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			echo get_post_format_string( get_post_format() );
		}

		echo $wrap_after;

	}
} // end of dimox_breadcrumbs()


function add_post_formats() {
    add_theme_support( 'post-formats', array( 'audio','video', 'aside', 'image') );
}

add_action( 'after_setup_theme', 'add_post_formats', 20 );


function add_fifu_meta_to_rest() {
    register_post_meta('post', 'fifu_image_url', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'add_fifu_meta_to_rest');

function post_types_menu_header() {
	?>
	<header class="page-header">
				<?php
					// the_archive_title( '<h1 class="archive-title">', '</h1>' );
					// the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
                <div class="post-header _container">
                    <a id="_link-book" class="post-header__item">
                        <div class="post-header__link ">
                            <div class="post-header__img">
                                <img src="https://mfca.uzlatin.com/wp-content/uploads/2023/03/Book-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Book
                            </div>
                        </div>
                    </a>
                    <a id="_link-udio" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                            <img src="https://mfca.uzlatin.com/wp-content/uploads/2023/03/audio-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Audio
                            </div>
                        </div>
                    </a>
                    <a id="_link-ideo" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                                <img src="https://mfca.uzlatin.com/wp-content/uploads/2023/03/video-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Video
                            </div>
                        </div>
                    </a>
                    <!-- <a id="_link-tory" class="post-header__item">
                        <div  class="post-header__link">
                            <div class="post-header__img">
                                <img src="https://mfca.uzlatin.com/wp-content/uploads/2023/03/svidet-main-during-mfca-3475982.png" alt="">
                            </div>
                            <div class="post-header__text">
                                Story
                            </div>
                        </div>
                    </a> -->
                    <style>	
                        .post-header {
                            display: flex;
                            padding: 4px 4px;
                            background-color: #00102E;
                            margin-bottom:30px;
							margin-top: 30px;
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
                                margin-top: 15px;
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
					
					<?php 
					if ( is_single() ) {
						?>
						<script>
							// https://mfca.site/uz/1132/ => https://mfca.site/c/uz/uz-book/
							let langNow = getPathSegment(document.location, 0);
	                        let postHeaderItem = [...document.querySelectorAll('.post-header__item')];
							const linksForPostHeaderItems = ['-book', '-audio', '-video', '-story'];


							console.log(langNow);
							let preffixLink = window.location.origin + `/c/${langNow}/${langNow}` ;

							for (let j = 0; j < postHeaderItem.length; j++) {
								const el = postHeaderItem[j];
								el.setAttribute('href',preffixLink + linksForPostHeaderItems[j]);
							}



							function getPathSegment(url, pos) {
								const u = new URL(url);
								const path = u.pathname.split('/').filter(segment => segment.length > 0);
								return path[pos] || null;
							} 

						</script>
						<?php
					} else {
						?>
						<script>
						// https://mfca.site/c/uz/uz-book/ => https://mfca.site/c/uz/uz-audio/
						// https://mfca.site/c/uz-book/page/2/ => https://mfca.site/c/uz/uz-audio/
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
                        let variable = document.location.pathname.toString().replace(/page\/\d+\//g, '').slice(-5,-1);
                        document.getElementById(`_link-${variable}`).classList.add('_active');
                        // создании сыллок
                        let langArray = ['az','kz','ka','kg','ce','ru','tj','tk','uz','ug'];
                        let linksForPostHeaderItems = ['-book','-audio','-video','-story'];
                        let langNow = document.location.pathname.toString().replace('/c/','').slice(0,2);
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
						<?php
					}
					?>

					
                    
                </div>
			</header>
	<?php
}