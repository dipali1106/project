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
		$this->create_post_type();	
	}
	

	function register(){
			add_action('wp_enqueue_scripts',array($this, 'enqueue'));

			add_action('admin-menu', array($this,'add_admin_pages'));
		}

	function enqueue(){
		wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',array('jquery'),true);
		wp_enqueue_style('mystyle', plugin_dir_url( __FILE__ ) . '/asset/mystyle.css' ,array(),true);
        //wp_enqueue_style('mystyle');
	}

	protected function create_post_type(){
		//Hook into the 'init' action
		add_action( 'init', array($this, 'movie_init') );
		//hook into the init action and call create_Types_nonhierarchical_taxonomy when it fires
		add_action( 'init', array($this, 'create_genres_taxonomy') );

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
	$customMovieData->register();

}

//activation
require_once plugin_dir_path(__FILE__).'inc/custom-activate.php';
register_activation_hook(__FILE__, array('CustomActivate', 'activate') );

//deactivation
require_once plugin_dir_path(__FILE__).'inc/custom-deactivate.php';
register_deactivation_hook(__FILE__, array('CustomDeactivate', 'deactivate') );



add_shortcode('movie-button',function($atts,$content=null){
  ?>
  <div class="container" >
  	<div class="btn-group">
	  	<button class="button" value="button" >ADD MOVIES</button>
	  	<button class="button">EDIT MOVIES</button>
	  	<button class="button">DELETE MOVIES</button>
  	</div>
  </div>
  <div class="container add-movie">
  	<form  action="" id="add-movie"  >
	  	<div class="form-group">
	  		<label for="name">Movie Name:</label>
	  		<input type="text" class="form-control" placeholder="Enter Movie Name" id="name" name="movie_name">
		</div>
	
	  	<div class="form-group">
		  <label for="description">Movie Description:</label>
		  <input type="text" class="form-control" placeholder="Enter Movie Description" id="description" name="movie_desc">
	 	 </div>
  	<?php

  		global $wpdb;
   		$posts = $wpdb->get_results( "SELECT $wpdb->term_taxonomy.term_taxonomy_id, $wpdb->terms.name 
   			FROM $wpdb->terms INNER JOIN $wpdb->term_taxonomy 
   			ON $wpdb->terms.term_id= $wpdb->term_taxonomy.term_id WHERE taxonomy='genre' ");
   		//echo $posts[1]->term_id;

  		?>
  		<div class="form-group">
	  		<label for="genre">Select Genre:</label>
	  		<select name="genre" class="form-control" id="genre">
	  		<option value="" >Select Genre</option>
	  	<?php foreach($posts as $post){?>
	  		<option value="<?php echo $post->term_taxonomy_id;?>" ><?php echo $post->name; ?></option>
	  	<?php } ?>
	  		</select>	  	
 		 </div>	 
  		<button type="submit" value="submit" class="btn btn-primary"  name="save-btn">Submit</button>
	</form>
</div>
<style>
	.form-group{
		margin-top: 10px;
	}
	.form-control{
		width: 90%;
	}
</style>
<script>
	
function displayFm(){
      $("#add-movie").toggle('show');
    }
   </script>
 
  <?php
  global $wpdb;
  if(isset($_POST['submit'])){
  	$name=$_POST['movie_name'];
    $movie_desc=$_POST['movie_desc'];
    $genre=$_POST['genre'];

  // Create post object
 	$user_id = get_current_user_id();
 //	echo $user_id;

// Insert the post into the database
	$post_id = wp_insert_post(array (
		'post_author'=>$user_id,
   'post_type' => 'movie',
   'post_title' => $name,
   'post_content' => $movie_desc,
   'post_status' => 'publish',
   'comment_status' => '',   // if you prefer
   'ping_status' => '',      // if you prefer
));
//$query="Insert into $wpdb->posts ('post_author','post_type','post_title', 'post_content', 'post_status' , 'comment_status') values($user_id, 'movie', '$name','$movie_desc', 'publish','')";
//$rs=mysql_query($query);
                        
}

//display
?>
<div class="container" >
	<h2>Movie Block Appearance</h2>
	<div id="gridbox" class="grid">
        <?php  $args=array(
              'post_type'=> 'movie',
            'posts_per_page' => 6,
            'status'  => 'published');
            $query=new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) 
        {
            while ( $query->have_posts() )
             {
                $query->the_post();?>

                <div class="grid-item">
                <a href="<?php the_permalink(); ?>">
                 <h5 class="title"><?php the_title() ;?></h5>
                </a>
              </div>    
       <?php
            }
        } 
        else {
            // no Books found
            ?><h1>Sorry...</h1>
          <p><?php _e('Sorry, no books found.'); ?></p>
          <?php
            } ?>
    </div>
</div>

<?php
/* Restore original Post Data */
wp_reset_postdata();

});
