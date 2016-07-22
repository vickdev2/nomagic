<?php
//Functions start

get_stylesheet_uri() //for child theme 

get_template_directory()// for theme directory 


get_post_type() // return post type

wp_redirect($url.'/wp-admin/edit.php?post_type=team');// wp redirect 


$url=site_url()//site url

$user = wp_get_current_user();// current login  user detail


//Functions End

/* To Know Current User Role in Wordpress if Admin or any other role [Start]*/
	$user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
		
	}
	else{
		
	}
	
/* To Know Current User Role in Wordpress if Admin or any other role [End]*/	

/* To Remove AdminBar from front end for specific user [start]*/	
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false); //removes admin bar 
	}else{
		show_admin_bar(true); //shows admin bar
	}
}
/* To Remove AdminBar from front end for specific user [End]*/	



/* Filter to avoid error header is already sent while redirecting [Start]*/
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}

/* Filter to avoid error header is already sent while redirecting [End]*/

/* Preventing Post Publish for validation [Start]*/
add_action('admin_head-post.php','ep_publish_admin_hook');
add_action('admin_head-post-new.php','ep_publish_admin_hook');

function ep_publish_admin_hook(){
    global $post;
    if ( is_admin() && $post->post_type == 'team' ){//post type is team [custom posttype ]
        ?>
        <script language="javascript" type="text/javascript">
            (function($){
                jQuery(document).ready(function() {
					jQuery('#publish').click(function() {
						if (jQuery('#kit_rest_own_paypal_fld_inf').val() == "") {
							alert( "Please enter your paypal id." );
							jQuery('#kit_rest_own_paypal_fld_inf').focus();
							return false ;
						}
						return true ;
					});
                });
            })(jQuery);
        </script>
        <?php
    }
}
/* Preventing Post Publish for validation [End]*/


/* Excerpt Length hook [Start]*/
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/* Excerpt Length hook [End]*/


/* Excerpt Read More Hook [Start]*/
add_filter( 'excerpt_more', 'modify_read_more_link' );
function modify_read_more_link() {
return '
<a href="' . get_permalink() . '" data-hover="Read More"><button class="btn muD-button" type="button">Read more</button></a>';              
}

/* Excerpt Read More Hook [End]*/


/* Limit team post type  creation [Start]*/
add_action( 'admin_head-post-new.php', 'check_post_limit' );
function check_post_limit() {
  if( $_GET['post_type'] === 'team' ) {
    global $userdata;
    global $wpdb;
	
    $item_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'team' AND post_author = $userdata->ID" );
    if( $item_count >= 1 ) {
	$url=site_url();

	//wp_redirect($url.'/wp-admin/edit.php?post_type=team');
?>
<script language="javascript" type="text/javascript">
            (function($){
alert('You can add only one Reataurant.Click on Add Products to Add New Product.');
window.location.href = "<?php echo $url.'/wp-admin/edit.php?post_type=team';?>";
})(jQuery);
        </script>
	<?php


	}
	
  }
}
/* Limit team post type  creation [End]*/


/* Check if specific post created earlier [Start]*/
add_action( 'admin_head-post-new.php', 'check_product_post_limit' );
function check_product_post_limit() {
  if( $_GET['post_type'] === 'product' ) {
    global $userdata;
    global $wpdb;
	
    $item_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'team' AND post_author = $userdata->ID" );
	
    if( $item_count <= 0 ) {
	$url=site_url();
	wp_redirect($url.'/wp-admin/edit.php?post_type=team');
	exit();
	}
	
  }
}
/* Check if specific post created earlier [End]*/


/* Hides custom fields/screen options from post types[start]*/ 
add_action('admin_init','remove_custom_meta_boxes');
function remove_custom_meta_boxes() {
remove_meta_box('postcustom','post','normal');
remove_meta_box('postcustom','page','normal');
remove_meta_box('postcustom','product','normal');
remove_meta_box('postcustom','team','normal');
	$user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
		
	}
	else{
		remove_meta_box('revisionsdiv','team','normal');
		remove_meta_box('revisionsdiv','product','normal');
		
		
		remove_meta_box('the_champ_meta','team','normal');
		remove_meta_box('the_champ_meta','product','normal');
		
		remove_meta_box('commentsdiv','team','normal');
		remove_meta_box('commentsdiv','product','normal');
		remove_meta_box('commentstatusdiv','team','normal');
		remove_meta_box('commentstatusdiv','product','normal');
	}
}

/* Hides custom fields/screen options from post types[End]*/

/* Hide individual plugn update [Start]*/
function filter_plugin_updates1( $value ) {
    unset( $value->response['wp-members/wp-members.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates1' );

function filter_plugin_updates2( $value ) {
    unset( $value->response['rich-reviews/rich-reviews.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates2' );

function filter_plugin_updates3( $value ) {
    unset( $value->response['super-socializer/super_socializer.php'] );
    return $value;
}

/* Hide individual plugn update [End]*/


/* Redirect After post type updation [Start]*/

add_action( 'save_post', 'my_project_updated_send_email' );
function my_project_updated_send_email( $post_id ) {
	$post_status=get_post_status( $post_id ) ;
	$post_status;
	
	$user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
		
	}
	else{
		if(get_post_type()==='team'){
			if($post_status==='trash'){
				$url=site_url();
				wp_redirect($url.'/wp-admin/edit.php?post_type=team');
				exit();
			}else{
				$url=site_url();
				wp_redirect($url=site_url().'/?p=715');
			
		exit();
				
			}
			
		}
	}
}
/* Redirect After post type updation [End]*/

/* Ajax Example [start]*/
add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' );

function example_ajax_request() {

// The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {

	$cus_lat = $_REQUEST['cus_lat'];
	$cus_long = $_REQUEST['cus_long'];
	$cus_address=$_REQUEST['cus_address'];



		// Let's take the data that was sent and do something with it
		if (!empty($cus_lat) && !empty($cus_long)&& !empty($cus_address)) {
		session_start();
		// Set session variables
		$_SESSION["cus_lat_sess"] = $cus_lat;
		$_SESSION["cus_long_sess"] = $cus_long;
		$_SESSION["cus_address_sess"] = $cus_address;
		
		}

	}// Always die in functions echoing ajax content
}
// If you wanted to also use the function for non-logged in users (in a theme for example)
add_action( 'wp_ajax_nopriv_example_ajax_request', 'example_ajax_request' );

add_action( 'wp_enqueue_scripts', 'wp_proaject_thm_style_ajax' );
function wp_proaject_thm_style_ajax(){
session_start();
if(isset($_SESSION['cus_lat_sess']) && !empty($_SESSION['cus_lat_sess']) && isset($_SESSION['cus_long_sess']) && !empty($_SESSION['cus_long_sess'])&& isset($_SESSION['cus_address_sess']) && !empty($_SESSION['cus_address_sess'])) {

}
else{
?>
<script>
jQuery(document).ready(function(e) {
	
	initialize();
	
  var geocoder;
  var cus_address;
  var cus_lat;
  var cus_long;

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
	} 
//Get the latitude and the longitude;
function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
	cus_lat=lat;
	cus_long=lng;
    cuslatlong(lat, lng);

}

function cuslatlong(lat, lng){
		
	    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
		
      if (status == google.maps.GeocoderStatus.OK) {
      
	  if (results[0].formatted_address) {
         //formatted address
        cus_address=results[0].formatted_address;
		//find country name
			if(typeof(cus_address) !== "undefined" && cus_address !== null){
				 $.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: "POST",
					dataType: "json",
					data: {
					'action':'example_ajax_request',
					'cus_lat' : cus_lat,
					'cus_long' : cus_long,
					'cus_address': cus_address
					},

					success:function(data) {
					 window.location.reload();
					// This outputs the result of the ajax request
					//alert(data.resonse)
					},
					error: function(errorThrown){
					console.log(errorThrown);
					}
					});
			}

        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
}
function errorFunction(err){
    alert("Geocoder failed "+ err.message);
}

  function initialize() {
    geocoder = new google.maps.Geocoder();
	
	}

});
</script>
<?php }
?>

<?php
}
/* Ajax Example [End]*/


/* Hides new button and logo Admin Dashboard [Start]*/
$user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
		
	}
	else{
	add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
	}
	
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );//removes wp logo from admin dashboard
	$wp_admin_bar->remove_node( 'new-content' );//removes new button  from admin dashboard
	$wp_admin_bar->remove_node( 'comments' );//removes comments menu from admin dashboard
	
}
/* Hides new button and logo Admin Dashboard [End]*/




/* Registering Menu [Start]*/
add_action( 'after_setup_theme', 'wp_proaject_thm_support' ); 
function wp_proaject_thm_support(){
	
	register_nav_menus( array(
		'wp_proaject_thm_food_sup_main_menu' => 'Food Supplier Main Menu'
	) );
	
	 
}
/* Registering Menu [End]*/




/* Hiding wocommerce product data [Start]*/
add_filter( 'product_type_selector', 'remove_product_types' );

function remove_product_types( $types ){
    unset( $types['grouped'] );
    unset( $types['external'] );
	unset( $types['variable'] );

    return $types;
}
/* Hiding wocommerce product data [End]*/

/* Hiding wocommerce product type option [Start]*/
add_filter( 'product_type_options', 'remove_product_type_options' );

function remove_product_type_options( $types ){
    unset( $types['virtual'] );
    
	unset( $types['downloadable'] );

    return $types;
}

/* Hiding wocommerce product type option [Start]*/

/* Hiding wocommerce view store link from adminbar [Start]*/
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
function remove_admin_bar_links() {
    global $wp_admin_bar;
    
    $wp_admin_bar->remove_menu('view-store'); // Remove the view store link
    
}    
/* Hiding wocommerce view store link from adminbar [End]*/


/* Change  wocommerce Review and Description position tab [Start]*/
add_filter( 'woocommerce_product_tabs', 'sb_woo_move_description_tab', 98);
function sb_woo_move_description_tab($tabs) {

$tabs['description']['priority'] = 15;

$tabs['reviews']['priority'] = 5;

return $tabs;
}
/* Change  wocommerce Review and Description position tab [End]*/

/* Registering Sidebar [Start]*/

add_action( 'widgets_init', 'wp_proaject_thm_widgets_init' );
function wp_proaject_thm_widgets_init(){
	
	register_sidebar( array(
		'name'          => 'KItchen Theme Social Register',
		'id'            => 'wppt_social_register_sec',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );
	
}
/* Registering Sidebar [End]*/





/* Customizing  Count View [Start] */ 
 $user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
	
	}
	else{
		
add_action( 'current_screen', function ( $current_screen ) {
		

	   if ($current_screen->id === 'edit-shop_order')
            add_filter( "views_{$current_screen->id}", 'list_table_views_filter' );
    }, 20);
	}

function list_table_views_filter( array $views ) {
    //error_log(print_r($view, true));
	$total=1;
	$publish=2;
	$draft=3;
	$pending=4;
    $views['all'] = preg_replace( '/\(.+\)/U', '', $views['all'] ); 
    $views['wc-processing'] = preg_replace( '/\(.+\)/U', '', $views['wc-processing'] ); 
    $views['wc-completed'] = preg_replace( '/\(.+\)/U', '', $views['wc-completed'] ); 
    $views['wc-cancelled'] = preg_replace( '/\(.+\)/U', '', $views['wc-cancelled'] );  
    $views['wc-refunded'] = preg_replace( '/\(.+\)/U', '', $views['wc-refunded'] ); 
    $views['wc-cancel-request'] = preg_replace( '/\(.+\)/U', '', $views['wc-cancel-request'] ); 
    
	
	return $views;
}
/* Customizing  Count View [End] */ 


/* Customizing  custom post type Count View [Start] */ 
 $user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
	
	}
	else{
add_action( 'current_screen', function ( $current_screen ) {
		

	   if ($current_screen->id === 'edit-team')
            add_filter( "views_{$current_screen->id}", 'list_table_team_views_filter' );
    }, 20);
	}


function list_table_team_views_filter( array $views ) {

	
	
        $views['all'] = preg_replace( '/\(.+\)/U', '', $views['all'] ); 
        $views['trash'] = preg_replace( '/\(.+\)/U', '', $views['trash'] ); 

        $views['publish'] = preg_replace( '/\(.+\)/U', '', $views['publish'] ); 
        $views['mine'] = preg_replace( '/\(.+\)/U', '', $views['mine'] ); 
        $views['future'] = preg_replace( '/\(.+\)/U', '', $views['future'] );
    
	return $views;
}
/* Customizing  custom post type Count View [End] */ 



//Remove dashboard widgets [Start]
function remove_dashboard_meta(){
        
         $user = wp_get_current_user();
		$cur_user_id=get_current_user_id();
	   
	   
		$roles_metadata = $user->roles;
		$role=$roles_metadata[0];
		if($role==='administrator'){
		
		}
		else{
			
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_published-posts', 'dashboard', 'normal' );
       // remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');


		}
        
} add_action( 'admin_init', 'remove_dashboard_meta' ); 

//Remove dashboard widgets [End]

/* // code to remove custom post type sub menu (wocommerce also) for wocommerce can use user role aedit plugin
function remove_menu_from_cpt() {
  global $submenu;
  $post_type = 'product';
  $tax_slug = 'product_cat';
  if (isset($submenu['edit.php?post_type='.$post_type])) {
    foreach ($submenu['edit.php?post_type='.$post_type] as $k => $sub) {
      if (false !== strpos($sub[2],$tax_slug)) {
        unset($submenu['edit.php?post_type='.$post_type][$k]);
      }
    }
  }
}
add_action('admin_menu','remove_menu_from_cpt');
*/




// adding author widget box to product editing page
function wpse_74054_add_author_woocommerce() {
    add_post_type_support( 'product', 'author' );
	
}
add_action('init', 'wpse_74054_add_author_woocommerce', 999 );



/* Removing revisions and comments divs from posts and screen options [Start]*/
add_action( 'add_meta_boxes' , 'remove_metaboxes', 50 );
function remove_metaboxes() {
       $user = wp_get_current_user();
		$cur_user_id=get_current_user_id();
	   
	   
		$roles_metadata = $user->roles;
		$role=$roles_metadata[0];
		if($role==='administrator'){
		
		}
		else{
			
		remove_meta_box( 'commentsdiv' , 'product' , 'normal' );
		remove_meta_box( 'revisionsdiv' , 'product' , 'normal' );


		}
     
   
}
/* Removing revisions and comments divs from posts and screen options [End]*/

/* Removing wocommerce product post tabs [Start]*/
add_filter('woocommerce_product_data_tabs', 'remove_linked_products', 10, 1);
function remove_linked_products($tabs){
    unset($tabs['linked_product']);
	unset($tabs['inventory']);
	unset($tabs['shipping']);
	unset($tabs['attribute']);
	unset($tabs['advanced']);
	return($tabs);
}
/* Removing wocommerce product post tabs [End]*/


/*wocommerce login redirect [Start]*/
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );
function wc_custom_user_redirect( $redirect, $user ) {
	// Get the first of all the roles assigned to the user
	$role = $user->roles[0];
	
	
	$dashboard = admin_url();
	$myaccount = get_permalink( wc_get_page_id( 'myaccount' ) );
	if($role==='administrator'){
			$url=$dashboard;
	}
	if($role==='shop_manager'){
			$url=$dashboard;
	}
	if($role==='customer'){
			$url=$myaccount;
	}
	return $url;
}

/*wocommerce login redirect [End]*/

/*wordpress  loout redirect [Start]*/
add_action("wp_logout", "my_logout");
function my_logout(){
    

		$user = wp_get_current_user();
		$roles_metadata = $user->roles;
		$role=$roles_metadata[0];
		if($role==='administrator'){
		
		}else{
			wp_redirect(home_url());
			die();
		}
    
    
}
/*wordpress  loout redirect [End]*/


/* code to make custom field required [Start]*/
add_action('admin_head-post.php','kit_team_publish_admin_hook');
add_action('admin_head-post-new.php','kit_team_publish_admin_hook');

function kit_team_publish_admin_hook(){
    global $post;
    if ( is_admin() && $post->post_type == 'team' ){
        ?>
        <script language="javascript" type="text/javascript">
            (function($){
                jQuery(document).ready(function() {
					jQuery('#publish').click(function() {
						if (jQuery('#kit_rest_own_street_address_inf').val() == "") {
							alert( "Please enter your Street Address." );
							jQuery('#kit_rest_own_street_address_inf').focus();
							return false ;
						}
                                                if (jQuery('#kit_rest_own_postcode_address_inf').val() == "") {
							alert( "Please enter your Post Code." );
							jQuery('#kit_rest_own_postcode_address_inf').focus();
							return false ;
						}
                                                if (jQuery('#kit_rest_own_state_address_inf').val() == "") {
							alert( "Please enter your State Address" );
							jQuery('#kit_rest_own_state_address_inf').focus();
							return false ;
						}
                                                if (jQuery('#wpmtp_contact').val() == "") {
							alert( "Please enter your Contact Number" );
							jQuery('#wpmtp_contact').focus();
							return false ;
						}
                                                if (jQuery('#wpmtp_email').val() == "") {
							alert( "Please enter your Email Address" );
							jQuery('#wpmtp_email').focus();
							return false ;
						}
                                                

						return true ;
					});
                });
            })(jQuery);
        </script>
        <?php
    }
}
/* code to make custom field required [End]*/


/* Add a widget to the dashboard  [Start]*/
function example_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'Dashboard Widget',         // Title.
                 'example_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function example_dashboard_widget_function() {

	// Display whatever it is you want to show.
echo "Dashboard Restaurant Widget";
echo '<br>';
$url=site_url().'/wp-admin/edit.php?post_type=team';
echo '<a href="'.$url.'">View Restaurant</a>';
}

/* Add a widget to the dashboard  [End]*/

//removing  screen option from dashboard
add_filter('screen_options_show_screen', '__return_false');

//removing  help option from dashboard
add_action('admin_head', 'mytheme_remove_help_tabs');
function mytheme_remove_help_tabs() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}


/* Showing login user post in dashboard [Start]*/
function posts_for_current_author($query) {

	if($query->is_admin) {

		global $user_ID;
		$query->set('author',  $user_ID);
	}
	return $query;
}

add_action( 'current_screen', function ( $current_screen ) {

       $user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
	
	}
	else{
		if ($current_screen->id === 'edit-team'||$current_screen->id === 'edit-product')
           add_filter('pre_get_posts', 'posts_for_current_author');
	}
	   
    }, 20);

/* Showing login user post in dashboard [End]*/


/* Enquing css and  js [Start]*/
add_action( 'wp_enqueue_scripts', 'wp_proaject_thm_style' );
function wp_proaject_thm_style(){
	
	wp_enqueue_style( 'bootstrap-min-css', get_stylesheet_directory_uri().'/css/bootstrap.min.css' );

	wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri().'/css/bootstrap.css' );

        wp_enqueue_style( 'prettyPhoto', get_stylesheet_directory_uri().'/css/prettyPhoto.css' );

	wp_enqueue_style( 'font-awesome-min', get_stylesheet_directory_uri().'/css/font-awesome.min.css' );
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/css/font-awesome.css' );

	wp_enqueue_style( 'custom-stylesheet', get_stylesheet_directory_uri().'/css/style.css' );
	
	
	//menu
	wp_enqueue_style( 'accesspress-basic-superfish-css', get_template_directory_uri() . '/css/superfish.css');
	wp_enqueue_style( 'accesspress-basic-lato-font', '//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' );
	wp_enqueue_style( 'accesspress-basic-style', get_stylesheet_uri() );
	wp_enqueue_style( 'accesspress-basic-responsive-css', get_template_directory_uri() . '/css/responsive.css');
	
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/fawesome/css/font-awesome.css' );
	wp_enqueue_script( 'accesspress-basic-superfish', get_template_directory_uri() . '/js/superfish.js', array('jquery','hoverIntent'));
	wp_enqueue_script( 'accesspress-basic-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20120206', true );
	wp_enqueue_script('accesspress-basic-custom-js', get_template_directory_uri().'/js/custom.js', array('jquery'));
	
	
	
	
}
add_action( 'wp_enqueue_scripts', 'wp_proaject_thm_scripts' );
function wp_proaject_thm_scripts(){
	wp_deregister_script( 'jquery' ); 
	wp_register_script( 'jquery',get_stylesheet_directory_uri() .'/js/jquery-2.2.1.min.js',false,false, true );
	
	wp_enqueue_script('jquery');

	wp_enqueue_script( 'bootstrap-min-js', get_stylesheet_directory_uri() .'/js/bootstrap.min.js', array('jquery'),false,true );

	wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() .'/js/bootstrap.js', array('jquery'),false,true );

	wp_enqueue_script( 'jquery-prettyPhoto-js', get_stylesheet_directory_uri() .'/js/jquery.prettyPhoto.js', array('jquery'),false,true );
	
	
	
}


add_action( 'admin_enqueue_scripts', 'kit_theme_admin_scripts' );// for admin section

function kit_theme_admin_scripts(){
wp_enqueue_script('kit-cus-admin-script', get_template_directory_uri().'/js/kit-cus-admin-script.js',false,true );
}

/* Enquing css and  js [End]*/



//Registering Custom Post Type
add_action( 'init', 'custom_post_type', 0 );
function custom_post_type() {


	
	// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'wppt_banner_slider', 'Post Type General Name'),
		'singular_name'       => _x( 'wppt_banner_slider', 'Post Type Singular Name'),
		'menu_name'           => __( 'Banner Slider' ),
		'parent_item_colon'   => __( 'Banner Slider'),
		'all_items'           => __( 'All Banner Sliders'),
		'view_item'           => __( 'View Banner Sliders'),
		'add_new_item'        => __( 'Add New Banner Slider' ),
		'add_new'             => __( 'Add New ' ),
		'edit_item'           => __( 'Edit Banner Slider'),
		'update_item'         => __( 'Update Banner Slider'),
		'search_items'        => __( 'Search Banner Slider'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash'),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Banner Slider'),
		'description'         => __( 'Banner Slider Description' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( '' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'wppt_banner_slider', $args );
	
	// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'wppt_spark_feature', 'Post Type General Name'),
		'singular_name'       => _x( 'wppt_spark_feature', 'Post Type Singular Name'),
		'menu_name'           => __( 'Spark Feature' ),
		'parent_item_colon'   => __( 'Spark Feature'),
		'all_items'           => __( 'All Spark Features'),
		'view_item'           => __( 'View Spark Features'),
		'add_new_item'        => __( 'Add Spark Feature' ),
		'add_new'             => __( 'Add New ' ),
		'edit_item'           => __( 'Edit Spark Feature'),
		'update_item'         => __( 'Update Spark Feature'),
		'search_items'        => __( 'Search Spark Feature'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash'),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Spark Feature'),
		'description'         => __( 'Spark Feature Description' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( '' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	//register_post_type( 'wppt_spark_feature', $args );



}

/* Banner Slider post type custom field Start*/
add_action( 'add_meta_boxes', 'wppt_banner_slider_cd_meta_box_add' );
function wppt_banner_slider_cd_meta_box_add()
{
	$types = array('wppt_banner_slider');
    add_meta_box( 'wppt_b_sldr-custom-box', 'custom fields', 'wppt_banner_slider_cd_meta_box_cb', $types, 'normal', 'high' );
}

function wppt_banner_slider_cd_meta_box_cb($post)
{
$values = get_post_custom( $post->ID );
$wppt_b_sldr_btn_txt = isset( $values['wppt_b_sldr_btn_txt'] ) ? esc_attr( $values['wppt_b_sldr_btn_txt'][0] ) : '';
$wppt_b_sldr_btn_link= isset( $values['wppt_b_sldr_btn_link'] ) ? esc_attr( $values['wppt_b_sldr_btn_link'][0] ) : '';
$wppt_b_sldr_active_cls= isset( $values['wppt_b_sldr_active_cls'] ) ? esc_attr( $values['wppt_b_sldr_active_cls'][0] ) : '';
//echo $text;
//echo $selected;
//die;
wp_nonce_field( 'wppt_b_sldr_my_meta_box_nonce', 'wppt_b_sldr_meta_box_nonce' );
    ?>
    <table>
	<tr>
	<td><label for="wppt_b_sldr_btn_txt">Button Text</label></td>
	<td><input type="text" name="wppt_b_sldr_btn_txt" id="wppt_b_sldr_btn_txt" value="<?php echo $wppt_b_sldr_btn_txt; ?>" /></td>
	</tr>
	<tr>
	<td><label for="wppt_b_sldr_btn_link">Button Link</label></td>
	<td><input type="text" name="wppt_b_sldr_btn_link" id="wppt_b_sldr_btn_link" value="<?php echo $wppt_b_sldr_btn_link; ?>" /></td>
	</tr>
	<tr>
	<td><label for="wppt_b_sldr_active_cls">Apply Active Class <span style='color:red;'>[Note: use only for first slide]</span></label></td>
	<td><input type="text" name="wppt_b_sldr_active_cls" id="wppt_b_sldr_active_cls" value="<?php echo $wppt_b_sldr_active_cls; ?>" /></td>
	</tr>
	</table>
    <?php    
}

add_action( 'save_post', 'wppt_b_sldr_cd_meta_box_save' );
function wppt_b_sldr_cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['wppt_b_sldr_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wppt_b_sldr_meta_box_nonce'], 'wppt_b_sldr_my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['wppt_b_sldr_btn_txt'] ) ) //product post code
        update_post_meta( $post_id, 'wppt_b_sldr_btn_txt', wp_kses( $_POST['wppt_b_sldr_btn_txt']) );
		    
	if( isset( $_POST['wppt_b_sldr_btn_link'] ) ) //product post code
        update_post_meta( $post_id, 'wppt_b_sldr_btn_link', wp_kses( $_POST['wppt_b_sldr_btn_link']) );
		
	if( isset( $_POST['wppt_b_sldr_active_cls'] ) ) //product post code
        update_post_meta( $post_id, 'wppt_b_sldr_active_cls', wp_kses( $_POST['wppt_b_sldr_active_cls']) );


    
}
/* Banner Slider post type custom field End*/



//wpmem plugin shortcodes 

/* Redirection when new user registers*/
add_action( 'wpmem_register_redirect', 'my_reg_redirect' );
function my_reg_redirect() {
	// NOTE: this is an action hook that uses wp_redirect.
	// wp_redirect must always be followed by exit();
	$url=site_url().'/?p=699';
	wp_redirect($url);
	exit();
}

/* Redirection when existing user  login [From front end Login]*/
add_filter( 'wpmem_login_redirect', 'my_login_redirect', 10, 2 );
function my_login_redirect( $redirect_to, $user_id ) {
	$user = wp_get_current_user();
	$roles_metadata = $user->roles;
	$role=$roles_metadata[0];
	
	
	if($role==='administrator'){
	$url=site_url().'/?p=715';
	return $url=site_url().'/wp-admin';
	}
	else{
		$url=site_url().'/?p=715';
		return $url=site_url().'/wp-admin/post-new.php?post_type=product';
	}
	// return the url that the login should redirect to
	
}/* Date Time post meta [cuatom field] in product post type */
add_filter( 'wpmem_register_heading', 'my_heading' );

function my_heading( $heading )
{
	/**
	 * The original heading comes in with
	 * the optional $heading parameter.
	 * You can filter it or change it.
 	 */
	
	$heading = 'New Members Registration';
	
	return $heading;
}

add_filter( 'wpmem_login_failed', 'my_login_failed_msg' );

function my_login_failed_msg( $str )
{
	?>
	  <script>
	  $('.kit-soc-reg-form').css("display", "none");
	  
	  $('#myModal1').addClass("in");
	  $('#myModal1').attr("aria-hidden","true");
	  $('#myModal1').css("display", "block");
	  $(".lgn-fail-cls-btn").click(function(){
			$('#myModal1').css("display", "none");
			$('#myModal1').attr("aria-hidden","false");
			$('#myModal1').removeClass("in");
		});
	  
	  </script>
	<?php
	return $str;
	
}

//end


add_filter( 'rwmb_meta_boxes', 'your_prefix_register_meta_boxes' );
function your_prefix_register_meta_boxes( $meta_boxes )
{
	/**
	 * prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	$prefix = 'your_prefix_';

	// 1st meta box


	// 2nd meta box
	$meta_boxes[] = array(
		'title' => __( 'SET TIME', 'your-prefix' ),

		'fields' => array(
			
			
			// DATETIME
			array(
				'name'       => __( 'Food will expire on', 'your-prefix' ),
				'id'         => $prefix . 'datetime',
				'type'       => 'datetime',

				// jQuery datetime picker options.
				// For date options, see here http://api.jqueryui.com/datepicker
				// For time options, see here http://trentrichardson.com/examples/timepicker/
				'js_options' => array(
					'stepMinute'     => 15,
					'showTimepicker' => true,
				),
			),
			
		),
	);

	return $meta_boxes;
}
/*Custom fields on team post types start */
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
	$types = array('team');
    add_meta_box( 'res-owner-paypal-id', 'Restaurant Owner Address & PayPal Id Details', 'cd_meta_box_cb', $types, 'normal', 'high' );
}

function cd_meta_box_cb($post)
{
$values = get_post_custom( $post->ID );
$res_owner_add_info = isset( $values['kit_restaurant_add_info'] ) ? esc_attr( $values['kit_restaurant_add_info'][0] ) : '';
$res_owner_paypal_info = isset( $values['kit_rest_own_paypal_fld_inf'] ) ? esc_attr( $values['kit_rest_own_paypal_fld_inf'][0] ) : '';

$kit_rest_own_street_address_inf=isset( $values['kit_rest_own_street_address_inf'] ) ? esc_attr( $values['kit_rest_own_street_address_inf'][0] ) : '';
$kit_rest_own_state_address_inf=isset( $values['kit_rest_own_state_address_inf'] ) ? esc_attr( $values['kit_rest_own_state_address_inf'][0] ) : '';
$kit_rest_own_postcode_address_inf=isset( $values['kit_rest_own_postcode_address_inf'] ) ? esc_attr( $values['kit_rest_own_postcode_address_inf'][0] ) : '';

wp_nonce_field( 'kit_res_my_meta_box_nonce', 'kit_res_meta_box_nonce' );
    ?>
    <table>
	<tr>
	<td><label for="kit_rest_own_street_address_inf">Street Address *</label></td>
	<td><input type="text" class='kit-add-cus-req-str' name="kit_rest_own_street_address_inf" id="kit_rest_own_street_address_inf" value="<?php echo $kit_rest_own_street_address_inf; ?>" /></td>
	</tr>
	
	<tr>
	<td><label for="kit_rest_own_state_address_inf">State / Territory *</label></td>
	<td><input type="text" class='kit-add-cus-req-state' name="kit_rest_own_state_address_inf" id="kit_rest_own_state_address_inf" value="<?php echo $kit_rest_own_state_address_inf; ?>" /></td>
	</tr>
	
	<tr>
	<td><label for="kit_rest_own_postcode_address_inf">Post Code *</label></td>
	<td><input type="text" class='kit-add-cus-req-post' name="kit_rest_own_postcode_address_inf" id="kit_rest_own_postcode_address_inf" value="<?php echo $kit_rest_own_postcode_address_inf; ?>" /></td>
	</tr>
	
	<tr>
	<td><label for="restaurant_owner_paypal_id"><strong>Restaurant Owner Paypal ID *</strong></label></td>
	<td><input type="text" name="kit_rest_own_paypal_fld_inf" id="kit_rest_own_paypal_fld_inf" value="<?php echo $res_owner_paypal_info; ?>" /></td>
	</tr>
	</table>
    <?php    
}
add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['kit_res_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['kit_res_meta_box_nonce'], 'kit_res_my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['kit_rest_own_street_address_inf'] ) ) //street
        update_post_meta( $post_id, 'kit_rest_own_street_address_inf', wp_kses( $_POST['kit_rest_own_street_address_inf']) );
	
	if( isset( $_POST['kit_rest_own_state_address_inf'] ) ) //state
        update_post_meta( $post_id, 'kit_rest_own_state_address_inf', wp_kses( $_POST['kit_rest_own_state_address_inf']) );
         
	
	if( isset( $_POST['kit_rest_own_postcode_address_inf'] ) ) //post code
        update_post_meta( $post_id, 'kit_rest_own_postcode_address_inf', wp_kses( $_POST['kit_rest_own_postcode_address_inf']) );
         
	
	if( isset( $_POST['kit_restaurant_add_info'] ) ) 
        update_post_meta( $post_id, 'kit_restaurant_add_info', wp_kses( $_POST['kit_restaurant_add_info']) );
         
    if( isset( $_POST['kit_rest_own_paypal_fld_inf'] ) ) //paypal id
        update_post_meta( $post_id, 'kit_rest_own_paypal_fld_inf', esc_attr( $_POST['kit_rest_own_paypal_fld_inf'] ) );
    
}
/*Custom fields on team post types end */

/* Product post type custom field Start*/
add_action( 'add_meta_boxes', 'product_cd_meta_box_add' );
function product_cd_meta_box_add()
{
	$types = array('product');
    add_meta_box( 'res-product-custom-box', 'Restaurant Post Code', 'product_cd_meta_box_cb', $types, 'normal', 'high' );
}

function product_cd_meta_box_cb($post)
{
$values = get_post_custom( $post->ID );
$res_pro_post_code_info = isset( $values['post_code'] ) ? esc_attr( $values['post_code'][0] ) : '';
//echo $text;
//echo $selected;
//die;
wp_nonce_field( 'kit_resproduct_my_meta_box_nonce', 'kit_resproduct_meta_box_nonce' );
    ?>
    <table>
	<tr>
	<td><label for="post_code">Post Code</label></td>
	<td><input type="text" name="post_code" id="post_code" value="<?php echo $res_pro_post_code_info; ?>" /></td>
	</tr>
	
	
	</table>
    <?php    
}

add_action( 'save_post', 'product_cd_meta_box_save' );
function product_cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['kit_resproduct_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['kit_resproduct_meta_box_nonce'], 'kit_resproduct_my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['post_code'] ) ) //product post code
        update_post_meta( $post_id, 'post_code', wp_kses( $_POST['post_code']) );

    
}
/* Product post type custom field End*/

function sample_admin_notice__success() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
    </div>
    <?php
}/* Redirect After Team post type updation START*/

   add_action( 'pre_get_posts', 'wpcf_filter_author_posts' );
   function wpcf_filter_author_posts( $query ){
	   
	   $post_type=$query->query['post_type'];
	   $user = wp_get_current_user();
	   $cur_user_id=get_current_user_id();
	   
	   
		$roles_metadata = $user->roles;
		$role=$roles_metadata[0];
		if($role==='administrator'){
		
		}
		else{
			
			if($post_type==='shop_order'){
				$query->set( 'meta_key', 'kit_author_id_single_order_item' );
				$query->set( 'meta_value', $cur_user_id );
			}


		}
	  
   
	 
   }
   

/* Adding theme footer link options start*/
add_action( 'customize_register' , 'wp_proaject_thm_options' );
function wp_proaject_thm_options( $wp_customize ) {
	
	
	//Footer layer-2 Starts
	$wp_customize->add_section( 'wppt_footl2_cnt_opt_sec' , array(
	  'title' => 'Footer  Options',
	 
	) );
	$wp_customize->add_setting( 'wppt_footl2_copy_cont_opt_stng',
	array(
		'default' =>'',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	)
	);
	$wp_customize->add_control(
    'wppt_footl2_copy_cont_opt_ctrl',
    array(
        'label' => 'Copy Right content',
		'section'  => 'wppt_footl2_cnt_opt_sec',
		'settings' => 'wppt_footl2_copy_cont_opt_stng',
        'type' => 'text',
		
    )
	);
	
	
	$wp_customize->add_setting( 'wppt_footl2_fblnk_opt_stng',
	array(
		'default' =>'',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	)
	);
	$wp_customize->add_control(
    'wppt_footl2_fblnk_opt_ctrl',
    array(
        'label' => 'Facebook Link',
		'section'  => 'wppt_footl2_cnt_opt_sec',
		'settings' => 'wppt_footl2_fblnk_opt_stng',
        'type' => 'text',
		
    )
	);
	
	$wp_customize->add_setting( 'wppt_footl2_twlnk_opt_stng',
	array(
		'default' =>'',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	)
	);
	$wp_customize->add_control(
    'wppt_footl2_twlnk_opt_ctrl',
    array(
        'label' => 'Twitter Link',
		'section'  => 'wppt_footl2_cnt_opt_sec',
		'settings' => 'wppt_footl2_twlnk_opt_stng',
        'type' => 'text',
		
    )
	);
	
	$wp_customize->add_setting( 'wppt_footl2_gplnk_opt_stng',
	array(
		'default' =>'',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	)
	);
	$wp_customize->add_control(
    'wppt_footl2_gplnk_opt_ctrl',
    array(
        'label' => 'Google+ Link',
		'section'  => 'wppt_footl2_cnt_opt_sec',
		'settings' => 'wppt_footl2_gplnk_opt_stng',
        'type' => 'text',
		
    )
	);
	$wp_customize->add_setting( 'wppt_footl2_linkedlnk_opt_stng',
	array(
		'default' =>'',
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage'
	)
	);
	$wp_customize->add_control(
    'wppt_footl2_linkedlnk_opt_ctrl',
    array(
        'label' => 'LinkedIn Link',
		'section'  => 'wppt_footl2_cnt_opt_sec',
		'settings' => 'wppt_footl2_linkedlnk_opt_stng',
        'type' => 'text',
		
    )
	);
}/* Adding theme footer link options end*/

function sv_conditional_email_recipient(recipient, $order ) {
			$order_id=$order->id;
			 global $wpdb;

			$_order_id_sql="SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id=".$order_id;
			$posts_order_item_id = $wpdb->get_results($_order_id_sql);
			$order_item_id=$posts_order_item_id[0]->order_item_id;
			
			
			$_order_pro_id_sql="SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id=".$order_item_id." AND meta_key='_product_id'";
			$posts_product_id = $wpdb->get_results($_order_pro_id_sql);
			$order_product_id=$posts_product_id[0]->meta_value;
			
			
			$product_seller_id_sql="SELECT post_author FROM wp_posts WHERE ID=".$order_product_id;
			$posts_product_seller_id = $wpdb->get_results($product_seller_id_sql);
			$product_seller_id=$posts_product_seller_id[0]->post_author;
			
		
			
			$pro_seller_team_sql="SELECT ID FROM wp_posts WHERE post_status = 'publish' AND post_type = 'team' AND post_author =".$product_seller_id;
			$pro_seller_team_id = $wpdb->get_results($pro_seller_team_sql);
			//echo '<pre>';print_r($pro_seller_team_id);echo '</pre>';
			$pro_seller_team_id=$pro_seller_team_id[0]->ID;
		
			
			
			//$meta=get_post_meta($post->ID);
			
			$res_own_email=get_post_meta ($pro_seller_team_id, '_email', true );
			
							
	// Bail on WC settings pages since the order object isn't yet set yet
	// Not sure why this is even a thing, but shikata ga nai
	return $recipient .= ','.$res_own_email;
}
add_filter( 'woocommerce_email_recipient_new_order', 'sv_conditional_email_recipient', 10, 2 );

add_filter( 'woocommerce_email_recipient_customer_completed_order', 'your_email_recipient_filter_function', 10, 2);

function your_email_recipient_filter_function($recipient, $object) {
	$admin_email_id=get_option( 'admin_email', true ); 
    $recipient = $recipient . ','.$admin_email_id;
    return $recipient;
}
add_filter( 'woocommerce_email_recipient_cancelled_order', 'customer_cancelled_order_email_recipient_filter_function', 10, 2);

function customer_cancelled_order_email_recipient_filter_function($recipient, $order) {
			$order_id=$order->id;
			 global $wpdb;

			$_order_id_sql="SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id=".$order_id;
			$posts_order_item_id = $wpdb->get_results($_order_id_sql);
			$order_item_id=$posts_order_item_id[0]->order_item_id;
			
			
			$_order_pro_id_sql="SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id=".$order_item_id." AND meta_key='_product_id'";
			$posts_product_id = $wpdb->get_results($_order_pro_id_sql);
			$order_product_id=$posts_product_id[0]->meta_value;
			
			
			$product_seller_id_sql="SELECT post_author FROM wp_posts WHERE ID=".$order_product_id;
			$posts_product_seller_id = $wpdb->get_results($product_seller_id_sql);
			$product_seller_id=$posts_product_seller_id[0]->post_author;
			
		
			
			$pro_seller_team_sql="SELECT ID FROM wp_posts WHERE post_status = 'publish' AND post_type = 'team' AND post_author =".$product_seller_id;
			$pro_seller_team_id = $wpdb->get_results($pro_seller_team_sql);
			//echo '<pre>';print_r($pro_seller_team_id);echo '</pre>';
			$pro_seller_team_id=$pro_seller_team_id[0]->ID;
			
			$res_own_email=get_post_meta ($pro_seller_team_id, '_email', true );
			
	
			$recipient = $recipient . ','.$res_own_email;
    return $recipient;
}
add_action( 'woocommerce_order_status_refunded', 'mysite_refunded');
function mysite_refunded(){
	add_filter( 'woocommerce_email_headers', 'add_bcc_to_wc_admin_new_order', 10, 3 );
}

function add_bcc_to_wc_admin_new_order( $headers = '', $id = '', $wc_email = array() ) {
    $admin_email_id=get_option( 'admin_email', true ); 
	
        $headers .= "Bcc: ". $admin_email_id; 
    
    return $headers;
}

/* Disqus Comment System for product //start */
function disqus_embed($disqus_shortname) {
    global $post;
    wp_enqueue_script('disqus_embed','https://'.$disqus_shortname.'.disqus.com/embed.js');
    echo '<div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = "'.$disqus_shortname.'";
        var disqus_title = "'.$post->post_title.'";
        var disqus_url = "'.get_permalink($post->ID).'";
        var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
    </script>';
}

add_action('the_post', 'sb_remove_woocommerce_disqus', 10, 2 );
remove_action('pre_comment_on_post', 'dsq_pre_comment_on_post');

function sb_remove_woocommerce_disqus( $post, $query ) {
global $post, $wp_query;

if ($query->is_main_query() && $post->post_type == 'product') { 
    remove_filter('comments_template', 'dsq_comments_template');
}
}
/* Disqus Comment System for product //end */
/* customizing review form start*/
add_filter('comment_form_default_fields', 'custom_fields');
function custom_fields($fields) {



    $fields[ 'health_spoil_status' ] = '
<p><br><b>Did your health got spoiled within 24 hours after eating this food? If yes Tick Box, No Tick Box<b></p>
 <p class="comment-form-health_spoil_status">
 
 '.
      '<label id="fdan_label" for="health_spoil_status">' . __( 'YES' ) . '</label>'.
      '<input id="#fdan" name="health_spoil_status" type="checkbox" value="0" />
</p>
  <p class="comment-form-health_spoil_status">'.
      '<label id="fok_label" for="health_spoil_status">' . __( 'NO' ) . '</label>'.
      '<input id="#fok" name="health_spoil_status" type="checkbox" value="0" checked />
</p>';

  return $fields;
}

// Save the comment meta data along with comment

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
  if ( ( isset( $_POST['health_spoil_status'] ) ) && ( $_POST['health_spoil_status'] != '') )
  $health_spoil_status = wp_filter_nohtml_kses($_POST['health_spoil_status']);
  add_comment_meta( $comment_id, 'health_spoil_status', $health_spoil_status );

 
}

// Add the filter to check whether the comment meta data has been filled
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
  if ( ! isset( $_POST['health_spoil_status'] ) )
  wp_die( __( 'Error: You did not add health status.' ) );
  return $commentdata;
}

// Add the comment meta (saved earlier) to the comment text
// You can also output the comment meta values directly to the comments template  

add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

  

  if( $commenttitle = get_comment_meta( get_comment_ID(), 'health_spoil_status', true ) ) {
	  if($commenttitle==1){
		  $ul=site_url();
		  $danger_img='<b>Marked </b><image src="'.$ul.'/wp-content/uploads/2016/05/danger.png"  width="30px"></image>';
	  }
		  
	  else{
				  $danger_img='';
	  }
    $commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
    $text = $danger_img.'<br>'. $text;
  } 

return $text;
}


// Add an edit option to comment editing screen  

add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'health_spoil_status', __( 'Comment Metadata - Extend Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}

function extend_comment_meta_box ( $comment ) {
    $health_spoil_status = get_comment_meta( $comment->comment_ID, 'health_spoil_status', true );
 
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="health_spoil_status"><?php _e( 'health_spoil_status' ); ?></label>
        <input type="text" name="health_spoil_status" value="<?php echo esc_attr( $health_spoil_status ); ?>" class="widefat" />
    </p>
    
    </p>
    <?php
}


// Update comment meta data from comment editing screen 

add_action( 'edit_comment', 'extend_comment_edit_metafields' );

function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

  if ( ( isset( $_POST['health_spoil_status'] ) ) && ( $_POST['health_spoil_status'] != '') ) :
  $health_spoil_status = wp_filter_nohtml_kses($_POST['health_spoil_status']);
  update_comment_meta( $comment_id, 'health_spoil_status', $health_spoil_status);
  else :
  delete_comment_meta( $comment_id, 'health_spoil_status');
  endif;


}
// Add fields after default fields above the comment box, always visible

add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {
  echo '
  <p><br><b>Did your health got spoiled within 24 hours after eating this food? If yes Tick Box, No Tick Box<b></p>
  <p class="comment-form-health_spoil_status">
  
  '.
      '<label id="fdan_label" for="health_spoil_status">' . __( 'YES' ) . '</label>'.
      '<input id="fdan" name="health_spoil_status" type="checkbox" value="0" />
</p>
  <p class="comment-form-health_spoil_status">'.
      '<label id="fok_label" for="health_spoil_status">' . __( 'NO' ) . '</label>'.
      '<input id="fok" name="health_spoil_status" type="checkbox" value="0" checked />
</p>';

  
 }
 
add_action( 'comment_post', 'wti_save_comment_data' );

function wti_save_comment_data( $comment_id ) {
   $comment_ID= $comment_id;
	global $wpdb;
	
	
	
	
	$kit_sql_comment="SELECT meta_value	FROM wp_commentmeta WHERE meta_key='health_spoil_status' AND comment_id ='".$comment_ID."'";
	$kit_posts_comment = $wpdb->get_results($kit_sql_comment);
	$health_status=$kit_posts_comment[0]->meta_value;
	
	if($health_status==1){
	
	$kit_sql_comment_author="SELECT user_id	FROM wp_comments WHERE comment_ID='".$comment_ID."'";
	$kit_comment_author_data = $wpdb->get_results($kit_sql_comment_author);
	$kit_comment_author_id=$kit_comment_author_data[0]->user_id;
	
	$kit_sql_comment_author_details="SELECT * FROM wp_usermeta WHERE user_id='".$kit_comment_author_id."'";
	$kit_comment_author_meta_data = $wpdb->get_results($kit_sql_comment_author_details);
	
	
	foreach($kit_comment_author_meta_data as $kit_comment_author_meta_data_details){
		
		
		if($kit_comment_author_meta_data_details->meta_key=='billing_first_name'){
				$kit_comment_author_bill_f_name=$kit_comment_author_meta_data_details->meta_value;
		}
		if($kit_comment_author_meta_data_details->meta_key=='billing_last_name'){
				$kit_comment_author_bill_l_name=$kit_comment_author_meta_data_details->meta_value;
		}
		if($kit_comment_author_meta_data_details->meta_key=='billing_email'){
				$kit_comment_author_bill_email=$kit_comment_author_meta_data_details->meta_value;
		}
		if($kit_comment_author_meta_data_details->meta_key=='billing_phone'){
				$kit_comment_author_bill_phone=$kit_comment_author_meta_data_details->meta_value;
		}
		
	}
	// comment author details
	 $kit_comment_author_name=$kit_comment_author_bill_f_name.' '.$kit_comment_author_bill_l_name;
	
	 $kit_comment_author_bill_email=$kit_comment_author_bill_email;
	
	 $kit_comment_author_bill_phone=$kit_comment_author_bill_phone;
	
	
	
	$kit_sql_post_id="SELECT comment_post_id FROM wp_comments WHERE comment_id ='".$comment_ID."'";
	$kit_post_id_comment = $wpdb->get_results($kit_sql_post_id);
	$kit_post_id_comment=$kit_post_id_comment[0]->comment_post_id;
	
	$kit_sql_post_author_id="SELECT post_author FROM wp_posts WHERE ID ='".$kit_post_id_comment."'";
	$kit_post_author_id_data = $wpdb->get_results($kit_sql_post_author_id);
	
	$kit_post_author_id=$kit_post_author_id_data[0]->post_author;
	
	
	$pro_seller_team_sql="SELECT ID, post_title FROM wp_posts WHERE post_status = 'publish' AND post_type = 'team' AND post_author =".$kit_post_author_id;
	$pro_seller_team_id_data = $wpdb->get_results($pro_seller_team_sql);
	$pro_seller_team_id=$pro_seller_team_id_data[0]->ID;
	$kit_post_author_team_title=$pro_seller_team_id_data[0]->post_title;
	
	
	//Product seller details
	$res_own_contact=get_post_meta ($pro_seller_team_id, '_contact', true );
	$res_own_email=get_post_meta ($pro_seller_team_id, '_email', true );
	$res_own_website=get_post_meta ($pro_seller_team_id, '_website', true );
	
	
	$res_own_street=get_post_meta ($pro_seller_team_id, 'kit_rest_own_street_address_inf', true );
	$res_own_state=get_post_meta ($pro_seller_team_id, 'kit_rest_own_state_address_inf', true );
	$res_own_postcode=get_post_meta ($pro_seller_team_id, 'kit_rest_own_postcode_address_inf', true );
	
	$res_own_address=$res_own_street.' '.$res_own_state. ' '.$res_own_postcode;
	
		
	}
	$admin_email_id=get_option( 'admin_email', true ); 
	$to = $admin_email_id;
	$subject = "Danger food Comment";
	$url=site_url();
	
	$c=$url.'/wp-admin/post.php?post='.$kit_post_id_comment.'&action=edit';
	
	$content = $c;

	$status = wp_mail($to, $subject, $content);
	if($status){
		echo 'mail sent';
	}else{
		echo 'mail not sent';
	}
	
	// Use the comment id and add your logic
	//echo $comment_id;
	//die;
	
}

function myplugin_comment_columns( $columns )
{
	$columns['my_custom_column'] = __( 'Health Spoiled Status' );
	return $columns;
}
add_filter( 'manage_edit-comments_columns', 'myplugin_comment_columns' );

function myplugin_comment_column( $column, $comment_ID )
{	
	//echo $comment_ID;
	global $wpdb;

	$kit_sql_comment="SELECT meta_value	 FROM wp_commentmeta WHERE meta_key='health_spoil_status' AND comment_id ='".$comment_ID."'";

	$kit_posts_comment = $wpdb->get_results($kit_sql_comment);
	echo $kit_posts_comment[0]->meta_value;
	

	/*if ( 'my_custom_column' == $column ) {
		if ( $meta = get_comment_meta( $comment_ID, $column , true ) ) {
			echo $meta;
		}
	}
	*/
}

add_filter( 'manage_comments_custom_column', 'myplugin_comment_column', 10, 2 );

