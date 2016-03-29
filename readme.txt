1.)http://www.sitepoint.com/git-for-beginners/

$ git config --global user.name 'youR name'


$ git config --global user.email 'youremailid.com'

$ git init 

$ git status

$ git add 

$ git add -all

$ git commit -m 'message'


$ git remote add origin https://github.com/----- [for first time]


$ git push -u origin master
<<<<<<< HEAD

================================================

2.)adding  wordpress theme ustomize options

http://themefoundation.com/wordpress-theme-customizer/
=======
>>>>>>> d12b3ea553563e06a224d8a74beadc6cde652d4e


https://premium.wpmudev.org/blog/creating-custom-controls-wordpress-theme-customizer/

puul command --> git pull origin master

=============================================
3.)create custom post type and tcustom texanomy in wordpress
==========================================================
IMPORTANT NOTE---> if custom post type page not found error , you may change and save permalink in setting option wordpress.
function custom_post_type() {


	
	// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'services', 'Post Type General Name'),
		'singular_name'       => _x( 'services', 'Post Type Singular Name'),
		'menu_name'           => __( 'Our Services' ),
		'parent_item_colon'   => __( 'Our Services'),
		'all_items'           => __( 'All Our Services'),
		'view_item'           => __( 'View Our Service'),
		'add_new_item'        => __( 'Add New Our Service' ),
		'add_new'             => __( 'Add New' ),
		'edit_item'           => __( 'Edit Our Service'),
		'update_item'         => __( 'Update Our Service'),
		'search_items'        => __( 'Search Our Service'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash'),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Our Services'),
		'description'         => __( 'Our Services Description' ),
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
	register_post_type( 'services', $args );



}
add_action( 'init', 'custom_post_type', 0 );
add_action( 'init', 'create_our_services_texanomies', 0 );


// create two taxonomies, genres and writers for the post type "book"
function create_our_services_texanomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'servies-category', 'taxonomy general name' ),
		'singular_name'     => _x( 'servies-category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Services Category' ),
		'all_items'         => __( 'All Services Category' ),
		'parent_item'       => __( 'Parent Services Category' ),
		'parent_item_colon' => __( 'Parent Services Category:' ),
		'edit_item'         => __( 'Edit Services Category' ),
		'update_item'       => __( 'Update Services Category' ),
		'add_new_item'      => __( 'Add New Services Category' ),
		'new_item_name'     => __( 'New Services Category Name' ),
		'menu_name'         => __( 'Services Category' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'servies-category' ),
	);

	register_taxonomy( 'servies-category', array( 'services' ), $args );
	

}
============================================================================
4.) customize wpordpress post comment list
http://blog.josemcastaneda.com/2013/05/29/custom-comment/

=============================================================