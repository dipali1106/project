<?php
   /*
   *Plugin Name: custom-movie-plugin
   *Plugin URI:
   *description: >- To add custom post type movie and taxonomies genre
   *Version: 1.0
   *Author: dipali
   *Tags: Custom Post-type, Custom Taxonomy
 
   */
   //exit if accessed directly
if(! defined('ABSPATH') ) exit;

class CustomMovieData{
	function __construct(){
		//Hook into the 'init' action
		add_action( 'init', array($this, 'movie_init') );
		//hook into the init action and call create_Types_nonhierarchical_taxonomy when it fires
		add_action( 'init', array($this, 'create_genres_taxonomy', 0) );
	}

	function activate(){
		//generete a custom post type
		$this->movie_init();
		//flush rewrite rules
		flush_rewrite_rules();
	}

	function deactivate(){
		//flush rewrite rules
		flush_rewrite_rules();
	}

	function uninstall()
	{
		//delete custom post type
		
		//delete all the plugin data
		
	}

	// Our custom post type function Book

	   function movie_init()
	    {
	    //Label part for GUI
		    $labels = array(
		    'name' => esc_html__('Movies', 'themedomain' ),
		    'singular_name' => esc_html__('Movie ',
		    'themedomain' ),
		    'add_new' => esc_html__('Add New Movie', 'themedomain'),
		    'add_new_item' => esc_html__('Add New Movie',
		    'themedomain' ),
		    'edit_item' => esc_html__('Edit Movie',
		    'themedomain' ),
		    'new_item' => esc_html__('Add New Movie',
		    'themedomain' ),
		    'view_item' => esc_html__('View Movie', 'themedomain' ),
		    'search_items' => esc_html__('Search Movie',
		    'themedomain' ),
		    'not_found' => esc_html__('No Movies found',
		    'themedomain' ),
		    'not_found_in_trash' => esc_html__('No Movies
		    found in trash', 'themedomain' )
		    );

		// Set other options for Custom Post Type
		    $args = array(
		    'labels' => $labels,
		    'public' => true,
		    'show_ui' => true,
		    'menu_icon' => 'dashicons-paperclip',
		    'show_in_menu'=>true,
		    'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'author', 'custom-fields', 'revisions'
		    ),

		    'hierarchical' => false,
		    'rewrite' => array( 'slug' => sanitize_title(
		    'Movie' ), 'with_front' => false ),
		    'menu_position' => 4,
		    'has_archive' => true
		    );
		     // Registering Custom Post Type Movie
		    register_post_type( 'movie', $args );
		}

	function create_genres_taxonomy() {
	 
		// Labels part for the GUI
	 
	  $labels = array(
	    'name' => _x( 'Genres', 'taxonomy general name' ),
	    'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
	    'search_items' =>  __( 'Search Genres' ),
	    'popular_items' => __( 'Popular Genres' ),
	    'all_items' => __( 'All Genres' ),
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => __( 'Edit Genre' ), 
	    'update_item' => __( 'Update Genre' ),
	    'add_new_item' => __( 'Add New Genre' ),
	    'new_item_name' => __( 'New Genre Name' ),
	    'separate_items_with_commas' => __( 'Separate Genres with commas' ),
	    'add_or_remove_items' => __( 'Add or remove Genres' ),
	    'choose_from_most_used' => __( 'Choose from the most used Genres' ),
	    'menu_name' => __( 'Genres' ),
	  ); 
	 
		// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('genre','movie',array(
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'show_in_rest' => true,
	    'show_admin_column' => true,
	    'update_count_callback' => '_update_post_term_count',
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'genre' ),
	  ));
	}


}


if (class_exists('CustomMovieData')){
	$customMovieData= new CustomMovieData();
}

//activation
register_activation_hook(__FILE__, array($customMovieData, 'activate') );

//deactivation
register_deactivation_hook(__FILE__, array($customMovieData, 'deactivate') );






 
function create_genres_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Genres', 'taxonomy general name' ),
    'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Genres' ),
    'popular_items' => __( 'Popular Genres' ),
    'all_items' => __( 'All Genres' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Genre' ), 
    'update_item' => __( 'Update Genre' ),
    'add_new_item' => __( 'Add New Genre' ),
    'new_item_name' => __( 'New Genre Name' ),
    'separate_items_with_commas' => __( 'Separate Genres with commas' ),
    'add_or_remove_items' => __( 'Add or remove Genres' ),
    'choose_from_most_used' => __( 'Choose from the most used Genres' ),
    'menu_name' => __( 'Genres' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('genre','movie',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'genre' ),
  ));
}

add_shortcode('movie-button',function($atts,$content=null){
  ?>
  <div class="container">
  	<button class="button">ADD MOVIES</button>
  	<button class="button">EDIT MOVIES</button>
  	<button class="button">DELETE MOVIES</button>
  </div>
  <div class="container add-movie">
  	<form  action="">
  	<div class="form-group">
  <label for="name">Movie Name:</label>
  <input type="text" class="form-control" placeholder="Enter Movie Name" id="name" name="movie_name">
</div>
<div class="form-group">
  <label for="description">Movie Description:</label>
  <input type="text" class="form-control" placeholder="Enter Movie Description" id="description" name="movie_desc">
  </div>
  
  <button type="submit" class="btn btn-primary" name="save-btn">Submit</button>
</form>
</div>
  <?php
});
?>