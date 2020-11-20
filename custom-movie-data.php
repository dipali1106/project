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
			add_action('wp_enqueue_script',array($this, 'enqueue'));
		}

	function enqueue(){
		wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',array('jquery'),true);
		wp_enqueue_style('mystyle', plugin_dir_url( __FILE__ ) . '/asset/mystyle.css' ,array(),true);
        //wp_enqueue_style('mystyle');
	}

	 function create_post_type(){
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


add_shortcode('movie-button',function($atts,$content=null){
	wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) .'/asset/mystyle.css' );
  ?>
  <div class="container" >
  	<div class="btn-group">
	  	<button class="button" value="button" >ADD MOVIES</button>
	  	
	  	<button class="button">EDIT MOVIES</button>
	  	
	  	<button class="button">DELETE MOVIES</button>
	  	
	  </div>
  	</div>
  </div>
  <div class="container add-movie">
  	<form  action="" id="add-movie" method="post" >
	  	<div class="form-group">
	  		<label for="name">Movie Name:</label>
	  		<input type="text" class="form-control" placeholder="Enter Movie Name" id="name" name="movie_name">
		</div>
	
	  	<div class="form-group">
		  <label for="description">Movie Description:</label>
		  <textarea  class="form-control" placeholder="Enter Movie Description" id="description" name="movie_desc"></textarea>
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
	  		<option value="" >--Select Genre--</option>
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
		margin-bottom: 5px;
	}
	.form-control{
		width: 100%;
	}
</style>
<script>
/*	
function displayFm(){
      $("#add-movie").toggle('show');
    }*/
   </script>
 
  <?php
  global $wpdb;
  if(isset($_POST['save-btn'])){
  	$name=$_POST['movie_name'];
    $movie_desc=$_POST['movie_desc'];
    $genre=$_POST['genre'];

  // Create post object
 	$user_id = get_current_user_id();


// Insert the post into the database
	$post_id = wp_insert_post(array (
	'post_author'=>$user_id,
   'post_type' => 'movie',
   'post_title' => $name,
   'post_content' => $movie_desc,
   'post_status' => 'publish',
   'comment_status' => '',   // if you prefer
   'ping_status' => 'open',      // if you prefer
));
	//echo $post_id;
	$query=$wpdb->insert('wp_term_relationships', 
    array(
      'object_id'          => $post_id,
      'term_taxonomy_id'       => $genre
    ),
    array(
      '%d',
      '%d'
    ) 
  ); 
                        
}?>
<div class="container" >
	<h2>All Movies</h2>
	<div id="gridbox" class="grid">
        <?php  $args=array(
              'post_type'=> 'movie',
            'posts_per_page' => 10,
            'status'  => 'published');
            $query=new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) 
        {
            while ( $query->have_posts() )
             {
                $query->the_post();?>

                <div class="grid-item">
                <ul>
                 <li><h5 class="title"><?php the_title() ;?></h5>
                 <h6 class="content"><?php the_content() ;?></h6></li></ul>
                
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

//display



});
?>

<?php 

add_shortcode('movie-delete-button',function($atts,$content=null){
	wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) .'/asset/mystyle.css' );
  ?>
  <div class="container" >
	<h2>Movie Block Appearance</h2>
	<div id="gridbox" class="grid">
        <?php  $args=array(
              'post_type'=> 'movie',
            'posts_per_page' => 10,
            'status'  => 'published');
            $query=new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) 
        {
            while ( $query->have_posts() )
             {
                $query->the_post();?>

                <div class="grid-item">
                	<form method="post" action="">
                <table>
                	<input type="hidden" name="id" value="<?php the_ID() ?>" >
                 <tr>
                 <th width="130"> Movie Title </th>
                 <td width="200"><h6 class="title"><?php the_title() ;?></h6></td></tr>
                 <tr><th width="130">Movie Description</th>
                 <td width="200"><h6 class="content"><?php the_content(); ?></h6></td></tr>
                 <tr><th width="130">Movie Author</th>
                 <td width="200"><h6 class="content"><?php the_author(); ?></h6></td></tr>
             	</table>
             	<button type="submit" value="submit" name="btn">Delete</button>
                </form>
              </div>    
       <?php
	       
	       	if(isset($_POST['btn'])){
	       	echo "<script type='text/javascript'>
	        window.location=document.location.href;
	        </script>";

	         global $wpdb;
	       	$id=$_POST['id'];
	       	//echo $id;
	       	wp_delete_post($id,$force_delete = false);
	       	wp_reset_data();
	       
	      		 }
	 		  
            }
        } 
        else {
            // no movies found
            ?><h1>Sorry...</h1>
          <p><?php _e('Sorry, no movies found.'); ?></p>
          <?php
            } 
          ?>
    </div>
</div>

<?php
/* Restore original Post Data */
wp_reset_postdata();


});?>

<?php 
//For Editing Movie
add_shortcode('movie-edit-button',function($atts,$content=null){
	wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) .'/asset/mystyle.css' );
  ?>
  <div class="container" >
	<h2>Movie Block Appearance</h2>
	<div id="gridbox" class="grid">
        <?php  $args=array(
            'post_type'=> 'movie',
            'posts_per_page' => 10,
            'status'  => 'published');
            $query=new WP_Query($args);

        // The Loop
        if ( $query->have_posts() ) 
        {
            while ( $query->have_posts() )
             {
                $query->the_post();
                $id=get_the_ID();
                global $wpdb;
   			$genre = $wpdb->get_results("SELECT term_taxonomy_id, $wpdb->terms.name  FROM $wpdb->term_relationships  INNER JOIN $wpdb->terms 
   				ON $wpdb->term_relationships.term_taxonomy_id=$wpdb->terms.term_id WHERE $wpdb->term_relationships.object_id = $id");
   			
   				?>

                <div class="grid-item">
                	<form method="post" action="">
                <table>
                	<input type="hidden" name="id" value="<?php the_ID() ?>" >
                 <tr>
                 <th width="130"> Movie Title </th>
                 <td width="200"><h6 class="title"><?php the_title() ;?></h6>
                 	<input type="hidden" name="title" value="<?php the_title() ?>" ></td></tr>
                 <tr><th width="130">Movie Description</th>
                 <td width="200"><h6 class="content"><?php the_content(); ?></h6>
                 	<input type="hidden" name="content" value="<?php the_content(); ?>" ></td></tr>
                 <tr><th width="130">Movie Author</th>
                 <td width="200"><h6 class="author"><?php the_author(); ?></h6></td></tr>
                 <tr><th width="130">Movie Genre</th>
                 <td width="200"><h6 class="genre"><?php echo $genre[0]->name; ?></h6>
                 	<input type="hidden" name="movie_genre" value="<?php echo $genre[0]->term_taxonomy_id; ?>" ></td></tr>
             	</table>
             	<button type="submit" value="submit" name="btn">Edit</button>
                </form>
              </div>    
       <?php
	       
	       if(isset($_POST['btn'])){
	       		
	       	add_action('to_open_form', 'open_editing_form');
	       	if (!function_exists('open_editing_form'))
	       	 {
	       		function open_editing_form()

	       		{
	       			$id=$_POST['id'];
	       			$title=$_POST['title'];
	       			$genre=$_POST['movie_genre'];
	       			$content=wp_strip_all_tags($_POST['content']);
	       			
	       			
	       			?>
	       		<div class="container edit-movie">
  					
  				<form  action="" id="edit-movie" method="post" >
			  	<div class="form-group">
			  		<label for="name">Movie Name:</label>
			  		<input type="hidden" id="post_id" name="id" value="<?php echo $id ?>">
			  		<input type="text" class="form-control"  id="name" name="update_name" value="<?php echo $title ?>">
			  		
				</div>
		
				  <div class="form-group">
					 <label for="description">Movie Description:</label>
					 <input type="text" class="form-control"  id="description" name="update_desc" value="<?php echo $content; ?>">
				 	</div>
		  		<?php

		  		global $wpdb;
		   		$posts = $wpdb->get_results( "SELECT $wpdb->term_taxonomy.term_taxonomy_id, $wpdb->terms.name 
		   			FROM $wpdb->terms INNER JOIN $wpdb->term_taxonomy 
		   			ON $wpdb->terms.term_id= $wpdb->term_taxonomy.term_id WHERE taxonomy='genre' ");		   		
		  		?>
		  		<div class="form-group">
			  		<label for="genre">Select Genre:</label>
			  		<select name="update_genre" class="form-control" id="genre">
			  			<option value="" >--Select Genre--</option>
			  		<?php foreach($posts as $post){?>
			  		<option value="<?php echo $post->term_taxonomy_id;?>"
			  		 <?php if($post->term_taxonomy_id==$genre) {echo"selected";} ?> ><?php 
			  		echo $post->name; ?></option>
			  	<?php } ?>
			  		</select>	  	
		 		 </div>	 
  					<button type="submit" value="submit" class="btn btn-primary"  name="save-button">Submit</button>
			</form>
			</div>
	       			<?php
	       			global $wpdb;
          if(isset($_POST['save-button'])){
          	echo "<script type='text/javascript'>
	        window.location=document.location.href;
	        </script>";
	          	$id=$_POST['id'];
	            $name=$_POST['update_name'];
	            $movie_desc=$_POST['update_desc'];
	            $genre=$_POST['update_genre'];
	            echo $name;
	            $query=$wpdb->update('wp_term_relationships', 
			        array(
			          'term_taxonomy_id'      => $genre
			        ),
			        array(
			          'object_id'   =>$id)    
			        );  
	             $my_post = array(
			      'ID'           => $id,
			      //'post_author'=>$user_id,
			      'post_title'   => $name,
			      'post_content' => $movie_desc,
			      );  
			      wp_update_post($my_post);

	                      
            }
				    
	       	  }
	        }
	 		  
          }
        } 
    }
        else {
            // no movies found
            ?><h1>Sorry...</h1>
          <p><?php _e('Sorry, no movies found.'); ?></p>
          <?php
            } 
          ?>
    </div>
</div>
<?php
/* Restore original Post Data */
wp_reset_postdata();
do_action('to_open_form', 'open_editing_form');
	

});
?>