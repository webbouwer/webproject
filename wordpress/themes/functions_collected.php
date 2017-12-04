<?php
  /*
   * This is a custom functions.php code tweaked with custom theme options and functions.
   * Note: most code comes from the Imagazine theme and needs theme-specific adjustments before you use it in your theme 
   */ 
   
	// WP core functions setup
	// require get_template_directory() . '/options.php'; // regular options functions for a theme options page
	// require get_template_directory() . '/customizer.php'; // regular customizer functions
	// require get_template_directory() . '...php'; 	// more custom functions

	// Register and load some widgets
	function imagazine_load_widgets() {
		//register_widget( 'NAME_OF_THE_CUSTOM_WIDGET' ); // load a custom widget
	}
	add_action( 'widgets_init', 'imagazine_load_widgets' );

	/*
	 * Return of the Links Manager
	 */
	add_filter( 'pre_option_link_manager_enabled', '__return_true' ); // now you can add and order links inside wordpress

	/*
	 * Register Theme Support
	 */
	function imagazine_setup_theme_global() {
		add_theme_support( 'post-thumbnails' ); // use thumbnails
    // register specific image sizes
		add_image_size( 'big-thumb', 320, 9999 );
		add_image_size( 'medium', 480, 9999 );
		add_image_size( 'normal', 960, 9999 );
		add_image_size( 'slide', 1800, 640, array( 'center', 'center' ) );
    // add wp options
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );
	}
	add_action( 'after_setup_theme', 'imagazine_setup_theme_global' );

	/*
	 * Register menu's
   * Setup menu positions
	 */
	function imagazine_setup_register_menus() {
		register_nav_menus(
			array(
			'uppermenu' => __( 'Upper menu' , 'imagazine' ),
			'topmenu' => __( 'Top menu' , 'imagazine' ),
			'sidemenu' => __( 'Side menu' , 'imagazine' ),
			'footermenu' => __( 'Footer menu' , 'imagazine' )
			)
		);
	}
	add_action( 'init', 'imagazine_setup_register_menus' );

	/* JQuery init */
	function imagazine_frontend_jquery() {
		wp_enqueue_script('jquery');
	}
	add_action( 'init', 'imagazine_frontend_jquery' );


	/* Sidebars / Widgets 
   * Regular sidebars setup
   */
	function imagazine_setup_widgets_init() { 
		if (function_exists('register_sidebar')) {
			
			// the top widgets
			register_sidebar(array(
				'name' => 'Top widgets',
				'id'   => 'topwidgets',
				'description'   => 'This is for top widget(s) above on top of the topbar.',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '<div class="clr"></div></div></div>',
				'before_title'  => '<div class="widget-titlebox"><h3>',
				'after_title'   => '</h3></div><div class="widget-contentbox">'
			));

			// the content sidebar widget
			register_sidebar(array(
				'name' => 'Content sidebar 1 (Widgets Default)',
				'id'   => 'sidebar',
				'description'   => 'This is a standard wordpress sidebar widgetized area.',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '<div class="clr"></div></div></div>',
				'before_title'  => '<div class="widget-titlebox"><h3>',
				'after_title'   => '</h3></div><div class="widget-contentbox">'
			));

			// the footer content
			register_sidebar(array(
				'name' => 'Footer Content Widgets',
				'id'   => 'footercontent',
				'description'   => 'Footer widgetized content',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '<div class="clr"></div></div></div>',
				'before_title'  => '<div class="widget-titlebox"><h3>',
				'after_title'   => '</h3></div><div class="widget-contentbox">'
			));
      
      // ..

		}
	}
	add_action( 'widgets_init', 'imagazine_setup_widgets_init' );

	/*
	 * Check active widgets
   * function to check if widgets are available
	 */
	function is_sidebar_active( $sidebar_id ){
		$the_sidebars = wp_get_sidebars_widgets();
		if( !isset( $the_sidebars[$sidebar_id] ) )
			return false;
		else
			return count( $the_sidebars[$sidebar_id] );
	}

	/*
	 * Widget empty title content wrapper fix
   * HTML adjustment for widget wrapper elements when title is missing
	 */
	function check_sidebar_params( $params ) {
		global $wp_registered_widgets;
		$settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
		$settings = $settings_getter->get_settings();
		$settings = $settings[ $params[1]['number'] ];
		if ( $params[0][ 'after_widget' ] == '<div class="clr"></div></div></div>' && isset( $settings[ 'title' ] ) &&  empty( $settings[ 'title' ] ) ){
			$params[0][ 'before_widget' ] .= '<div class="widget-contentbox">';
		}
		return $params;
	}
	add_filter( 'dynamic_sidebar_params', 'check_sidebar_params' );

	/* Widget shortcode */
	// Enable the use of shortcodes in text widgets.
	add_filter( 'widget_text', 'do_shortcode' );

	/* Execute PHP in the default text-widget	*/
	function php_execute($html){
	if(strpos($html,"<"."?php")!==false){ ob_start(); eval("?".">".$html);
	$html=ob_get_contents();
	ob_end_clean();
	}
	return $html;
	}
	add_filter('widget_text','php_execute',100);

  /*
   * css file listing
   */
  function get_theme_cssfilelist(){
  $dir =  get_template_directory(); //dirname( __DIR__ );
  $arr = [];
  $c = 0;
  // Open a directory, and read its contents
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){

      $ext = pathinfo($file, PATHINFO_EXTENSION);
        if( $ext == 'css' && $file != 'style.css'){
          $arr[$file] = $file;
        }
        $arr++;
      }
      closedir($dh);
    }
  }
  return $arr;
  }

	/*
	 * Editor styles (wysiwyg styling) 
	 */
	function imagazine_editor_styles() {
		add_editor_style( 'style.css' );
		//add_editor_style( get_theme_mod('onepiece_identity_stylelayout_stylesheet', 'default.css') );
	}
	add_action( 'admin_init', 'imagazine_editor_styles' );

	/* Enqueue scripts and styles */
	function imagazine_theme_scripts() {
		wp_enqueue_style( 'basic-stylesheet', get_stylesheet_uri() );
		//wp_enqueue_script( 'theme-responsive', get_template_directory_uri()
		// . '/assets/responsive.js', array(), '1.0.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'imagazine_theme_scripts' );

	/*
	 * WP CUSTOM VARS LOCATED
	 * Javascript with customizer variables
	 */
	// http://wordpress.stackexchange.com/questions/57386/how-do-i-force-wp-enqueue-scripts-to-load-at-the-end-of-head
	
  function imagazine_global_js() {

		// Register the script(s)
		//wp_register_script( 'custom_global_js', get_template_directory_uri().'/assets/global.js', 99, '1.0', false);
		//wp_register_script( 'custom_login_js', get_template_directory_uri().'/assets/userlogin.js', 99, '1.0', false);
    
		// Get the global data list.
		global $wp_global_data; // a global var with cyustomizer and meta datasee below

		// Localize the global data list for the script
		wp_localize_script( 'custom_global_js', 'site_data', $wp_global_data );
    
		// localize the script with specific data.
		//$color_array = array( 'color1' => get_theme_mod('color1'), 'color2' => '#000099' );
		//wp_localize_script( 'custom_global_js', 'object_name', $color_array );

		// The script can be enqueued now or later.
		//wp_enqueue_script( 'custom_global_js');
		//wp_enqueue_script( 'custom_login_js');
	}

	add_action('wp_enqueue_scripts', 'imagazine_global_js');



	/* Theme global functions */
	function check_image_orientation($pid){
		// check oriëntation
			$orient = 'square';
			$image = wp_get_attachment_image_src( get_post_thumbnail_id($pid), '');
			$image_w = $image[1];
			$image_h = $image[2];

			if ($image_w > $image_h) {
				$orient = 'landscape';
			}elseif ($image_w == $image_h) {
				$orient = 'square';
			}else {
				$orient = 'portrait';
			}
			return $orient;
	}

	/*
	 * Time
	 */
	function wp_time_ago( $t ) {
		// https://codex.wordpress.org/Function_Reference/human_time_diff
		//get_the_time( 'U' )
		printf( _x( '%s '.__('geleden','imagazine'), '%s = human-readable time difference', 'imagazine' ), human_time_diff( $t, current_time( 'timestamp' ) ) );
	}

	/*
	 * Adjust excerpt num words max
	 */
	function the_excerpt_length( $words = null, $links = true ) {
		global $_the_excerpt_length_filter;
		if( isset($words) ) {
			$_the_excerpt_length_filter = $words;
		}
		add_filter( 'excerpt_length', '_the_excerpt_length_filter' );
		if( $links == false){
			echo preg_replace('/(?i)<a([^>]+)>(.+?)<\/a>/','', get_the_excerpt() );
		}else{
			the_excerpt();
		}
		remove_filter( 'excerpt_length', '_the_excerpt_length_filter' );
		// reset the global
		$_the_excerpt_length_filter = null;
	}
  
  
  // return excerpt
	function _the_excerpt_length_filter( $default ) {
		global $_the_excerpt_length_filter;
		if( isset($_the_excerpt_length_filter) ) {
			return $_the_excerpt_length_filter;
		}
		return $default;
	}
	// the_excerpt_length( 25 );


	/* Search highlighting */
	function search_title_highlight() {
		$title = get_the_title();
		$keys = implode('|', explode(' ', get_search_query()));
		$title = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $title);
		echo $title;
	}
	function search_excerpt_highlight($excerpt_length = 20) {
	$excerpt = the_excerpt_length( $excerpt_length ); //get_the_excerpt();
	$keys = implode('|', explode(' ', get_search_query()));
	$excerpt = preg_replace('/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $excerpt);
	echo $excerpt;
	}

  /*
   * Exclude specific categories from the loop
   */
  add_action( 'pre_get_posts', 'exclude_specific_cats' );
  function exclude_specific_cats( $wp_query ) {   
      if( !is_admin() && is_main_query() && is_home() ){
      $exclude_cats = '-'.str_replace(",",",-", get_theme_mod('onepiece_content_exclude_categories') );
          $wp_query->set( 'cat', $exclude_cats ); // ! '-1' not allowed = buggy in WP Multisitesq
      }
  }

  /**
	 * Keep category select list in hiëarchy
	 * source http://wordpress.stackexchange.com/questions/61922/add-post-screen-keep-category-structure
	 */
	function imagazine_wp_terms_checklist_args( $args, $post_id ) {
	   $args[ 'checked_ontop' ] = false;
	   return $args;
	}
	add_filter( 'wp_terms_checklist_args', 'imagazine_wp_terms_checklist_args', 1, 2 );


	/* CATEGORY LIST - for metaboxes / customizer functions */
	function get_categories_select(){
		$get_cats = get_categories();
			$results;
			$count = count($get_cats);
			for ($i=0; $i < $count; $i++) {
				if (isset($get_cats[$i]))
					$results[$get_cats[$i]->slug] = $get_cats[$i]->name;
				else
					$count++;
			}
		return $results;
	}



	/*
	 * Register global variables (options/customizer)
	 */
	$wp_global_data = array(); // special var $wp_global_data
	$wp_global_data['customizer'] = json_encode(get_theme_mods());

  // check post by slug
  add_action('wp_ajax_get_post_id_by_slug', 'get_post_id_by_slug'); // ajax.php
  add_action('wp_ajax_nopriv_get_post_id_by_slug', 'get_post_id_by_slug');
  function get_post_id_by_slug(){
      global $wpdb;
      $args = array(
        'name'        => $_POST['post_slug'],
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
      );
      $dapost = get_posts($args);
      if ($dapost) {
        $dapost[0]->meta = get_post_meta( $dapost[0]->ID );
        $dapost[0]->category = wp_get_post_categories($dapost[0]->ID);
        $dapost[0]->tags = wp_get_post_terms( $dapost[0]->ID, 'post_tag', array("fields" => "slugs"));

        $response = $dapost[0];

      } else {
        $response = '';
      }
      echo json_encode($response, JSON_PRETTY_PRINT);
      wp_die();
  }

	/* Libraries */
  
	// include webicon
	function imagazine_load_webicons(){
	wp_enqueue_script('jquery-webicon', '//cdn.rawgit.com/icons8/bower-webicon/v0.10.7/jquery-webicon.min.js');
	}
	add_action( 'wp_print_scripts', 'imagazine_load_webicons' );

	// include googlefonts
  function google_fonts() {
		$query_args = array(
			'family' => get_theme_mod("imagazine_global_styles_mainfont", "Lato|Martel"),
			'subset' => get_theme_mod("imagazine_global_styles_subsetfont", "latin,latin-ext"),
		);
		wp_enqueue_style( 'google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
		// wp_register_style
  }
  add_action('wp_enqueue_scripts', 'google_fonts');

  /* or get it from the customizer
    function load_fonts() {
		wp_register_style( 'google_fonts', 'https://fonts.googleapis.com/css?family='.get_theme_mod("imagazine_global_styles_mainfont", "Lato|Martel") );
        wp_enqueue_style( 'google_fonts');
    }

    add_action('wp_print_styles', 'load_fonts');
  */








	/* Plugin output add-ons */

  // include responsivevoice tekst-to-speech button (if plugin enabled)
	function imagazine_add_responsive_voice_button(){

		if( function_exists( 'RV_add_voicebox' ) ) { // Responsive voice text used

			$var_lang_voice = array('nl' => 'Dutch Female','en-GB' => 'UK English Female');
			$lang = get_bloginfo("language"); //get_locale();

			$button = '[responsivevoice_button voice="'.$var_lang_voice[$lang].'" buttontext="'.__( "Lees voor", "imagazine").'"]';
			echo do_shortcode($button);

		/* Array translation needed..
		// https://github.com/wp-plugins/responsivevoice-text-to-speech/blob/master/responsivevoice-text-to-speech.php
		UK English Female, UK English Male, US English Female, Spanish Female, French Female, Deutsch Female, Italian Female, Greek Female, Hungarian Female, Turkish Female, Russian Female, Dutch Female, Swedish Female, Norwegian Female, Japanese Female, Korean Female, Chinese Female, Hindi Female, Serbian Male, Croatian Male, Bosnian Male, Romanian Male, Catalan Male, Australian Female, Finnish Female, Afrikaans Male, Albanian Male, Arabic Male, Armenian Male, Czech Female, Danish Female, Esperanto Male, Hatian Creole Female, Icelandic Male, Indonesian Female, Latin Female, Latvian Male, Macedonian Male, Moldavian Male, Montenegrin Male, Polish Female, Brazilian Portuguese Female, Portuguese Female, Serbo-Croatian Male, Slovak Female, Spanish Latin American Female, Swahili Male, Tamil Male, Thai Female, Vietnamese Male, Welsh Male
		*/

		// repsonsive voice custom button icon
		if( function_exists( 'imagazine_load_webicons' ) ) {
			echo '<a href="#" id="ttsbutton" title="Listen">';
			echo '<span class="buttonicon"><webicon icon="foundation:hearing-aid" /></span>';
			echo '<span class="buttontext">'.__( "Lees voor", "imagazine").'</span></a>';
			echo '
			<script>
			jQuery(function ($) { $(document).ready( function(){
			/* Replace text to speech button */
			$("#ttsbutton").on( "click", function(){
				//alert("De tekst wordt nu voorgelezen");
				$( ".optionmenu button.responsivevoice-button" ).trigger( "click" );
				return false;
			});
			});});
			</script>';

		}

		}

	}

  /* Performance */

/* Remove Emoji junk by Christine Cooper
 * Found on http://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
 */
function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' ); // filter to remove TinyMCE emojis
}
add_action( 'init', 'disable_wp_emojicons' );
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
/*
 * control (remove) gravatar
 */
function bp_remove_gravatar ($image, $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir) {
	$default = get_stylesheet_directory_uri() .'/images/avatar.png';
	if( $image && strpos( $image, "gravatar.com" ) ){ 
		return '<img src="' . $default . '" alt="avatar" class="avatar" ' . $html_width . $html_height . ' />';
	} else {
		return $image;
	}
}
add_filter('bp_core_fetch_avatar', 'bp_remove_gravatar', 1, 9 );
function remove_gravatar ($avatar, $id_or_email, $size, $default, $alt) {
	$default = get_stylesheet_directory_uri() .'/images/avatar.png';
	return "<img alt='{$alt}' src='{$default}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
}
add_filter('get_avatar', 'remove_gravatar', 1, 5);
function bp_remove_signup_gravatar ($image) {
	$default = get_stylesheet_directory_uri() .'/images/avatar.png';
	if( $image && strpos( $image, "gravatar.com" ) ){ 
		return '<img src="' . $default . '" alt="avatar" class="avatar" width="60" height="60" />';
	} else {
		return $image;
	}
}
add_filter('bp_get_signup_avatar', 'bp_remove_signup_gravatar', 1, 1 );

?>
