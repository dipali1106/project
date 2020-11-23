<?php
   /*
   *Plugin Name: custom-movie-plugin 
   *Plugin URI:
   *description: >- To add custom post type movie and taxonomies genre
   *Version: 1.0
   *Author: dipali
   text-domain : movie-data
   *Tags: Custom Post-type, Custom Taxonomy
   */
   //exit if accessed directly
if(! defined('ABSPATH') ) exit;

class CustomMovieData{


	function __construct(){
		$this->create_post_type();	
	}	

	function create_post_type(){
		//Hook into the 'init' action
		add_action( 'init', array($this, 'movie_init') );
		//hook into the init action and call create_Types_nonhierarchical_taxonomy when it fires
		add_action( 'init', array($this, 'create_genres_taxonomy') );

	}
	
	// Our custom post type function Movie

	    function movie_init()
	    {
	    //Label part for GUI
		    $labels = array(
		    'name' => __('Movies','movie-data'),
		    'singular_name' => __('Movie','movie-data'),
		    'add_new' => __('Add New Movie','movie-data'),
		    'add_new_item' => __('Add New Movie','movie-data'),
		    'edit_item' =>  __('Edit Movie','movie-data'),
		    'new_item' =>  __('Add New Movie','movie-data'),
		    'view_item' =>  __('View Movie','movie-data'),
		    'search_items' =>  __('Search Movie','movie-data'),	    
		    'not_found' =>  __('No Movies found','movie-data'),
		    
		    'not_found_in_trash' =>  __('No Movies found in trash','movie-data')
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
		    'name' => _x( 'Genres', 'taxonomy general name','movie-data' ),
		    'singular_name' => _x( 'Genre', 'taxonomy singular name', 'movie-data' ),
		    'search_items' =>  __( 'Search Genres', 'movie-data'),
		    'popular_items' => __( 'Popular Genres','movie-data' ),
		    'all_items' => __( 'All Genres','movie-data' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Genre','movie-data' ), 
		    'update_item' => __( 'Update Genre', 'movie-data' ),
		    'add_new_item' => __( 'Add New Genre', 'movie-data' ),
		    'new_item_name' => __( 'New Genre Name', 'movie-data' ),
		    'separate_items_with_commas' => __( 'Separate Genres with commas','movie-data' ),
		    'add_or_remove_items' => __( 'Add or remove Genres' ),
		    'choose_from_most_used' => __( 'Choose from the most used Genres', 'movie-data' ),
		    'menu_name' => __( 'Genres', 'movie-data' ),
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

	//function for shortcode
	public static function movieAction($atts,$content=null){
		wp_register_script('script', plugin_dir_url( __FILE__ ) . 'asset/script.js' ,array(),true);
      	wp_enqueue_script('script');
		wp_enqueue_style( 'style-css', plugin_dir_url( __FILE__ ) .'asset/mystyle.css' );
  ?>

  	<div class="btn-group">
	  	<button class="button" onclick="showAdd()" ><?php _e('ADD MOVIES', 'movie-data') ?></button>
	  	
	  	<button class="button" onclick="showEdit()"><?php _e('EDIT MOVIES', 'movie-data') ?></button>
	  	
	  	<button class="button" onclick="showDelete()"><?php _e('Delete Movies', 'movie-data') ?></button>
	  	
	  </div>
  	

 
  <div class="add-movie" id="addMovie" style="display:none">
  	<form  action="" id="add-movie" method="post" >
	  	<div class="form-group">
	  		<label for="name"><?php _e('Movie Name:', 'movie-data') ?></label>
	  		<input type="text" class="form-control" placeholder="<?php _e('Enter Movie Name', 'movie-data') ?>" id="name" name="movie_name" required>
		</div>
	
	  	<div class="form-group">
		  <label for="description"><?php _e('Movie Description','movie-data') ?>:</label>
		  <textarea  class="form-control" placeholder="<?php _e('Enter Movie Description','movie-data') ?>" id="description" name="movie_desc" required></textarea>
	 	 </div>
  		<?php

  		global $wpdb;
   		$terms = get_terms( array(
    						'taxonomy' => 'genre',
    						'hide_empty' => false,
								) );

  		?>
  		<div class="form-group">
	  		<label for="genre"><?php _e('Select Genre' ,'movie-data') ?>:</label>
	  		<select name="genre" class="form-control" id="genre" required >
	  		<option value="" ><?php _e('--Select Genre--','movie-data') ?></option>
	  	<?php foreach($terms as $term){?>
	  		<option value="<?php echo $term->name;?>" ><?php echo $term->name; ?></option>
	  	<?php } ?>
	  		</select>	  	
 		 </div>	 
  		<button type="submit" value="submit" class="btn btn-primary"  name="save-btn"><?php _e('Submit','movie-data') ?></button>
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

  <?php
  global $wpdb;
  if(isset($_POST['save-btn']))
  {
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
	
	wp_set_object_terms($post_id,$genre,__('genre','movie-data')  );
	        
  }

?>
<div class="movie-title"  id="movies-title">
	<h2><?php _e('All Movies','movie-data') ?></h2>
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
                <table>
                 <tr>
                 <th width="130"> <?php _e('Movie Title','movie-data') ?> </th>
                 <td width="200"><h6 class="title"><?php the_title() ;?></h6></td></tr>
                 <tr><th width="130"><?php _e('Movie Description','movie-data') ?></th>
                 <td width="200"><h6 class="content"><?php the_content(); ?></h6></td></tr>
             	</table>
                
              </div>    
       <?php
            }
        } 
        else {
            // no Movies found
            ?>
          <p><?php _e('Sorry, no books found.','movie-data')  ?></p>
          <?php
            } ?>
    </div>
</div>

<?php
/* Restore original Post Data */
wp_reset_postdata();


  ?>
  <div class="deleteMovie" id="deleteMovie" style="display:none">
	<h2><?php _e('Delete Movies','movie-data') ?></h2>
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
                 <th width="130"><?php  _e('Movie Title','movie-data') ?> </th>
                 <td width="200"><h6 class="title"><?php the_title() ;?></h6></td></tr>
                 <tr><th width="130"><?php _e('Movie Description','movie-data') ?></th>
                 <td width="200"><h6 class="content"><?php the_content(); ?></h6></td></tr>
                 <tr><th width="130"><?php _e('Movie Author','movie-data') ?> </th>
                 <td width="200"><h6 class="content"><?php the_author(); ?></h6></td></tr>
             	</table>
             	<button type="submit" value="submit" name="btn-2"><?php _e('Delete','movie-data') ?></button>
                </form>
              </div>    
       <?php
	       
	       	if(isset($_POST['btn-2'])){
	       		
	       	echo "<script type='text/javascript'>
	        window.location=document.location.href;
	        </script>";

	         global $wpdb;
	       	$id=$_POST['id'];
	       	//echo $id;
	       	wp_trash_post($id);
	       	wp_delete_object_term_relationships($id, _e('genre','movie-data'));
	       	wp_reset_postdata();
	       
	      		 }
	 		  
            }
        } 
        else {
            // no movies found
            ?>
          <p><?php _e('Sorry, no movies found.','movie-data'); ?></p>
          <?php
            } 
          ?>
    </div>
</div>

<?php
/* Restore original Post Data */
wp_reset_postdata();

  ?>
  <div class="editMovie" id="editMovie" style="display:none">
	<h2><?php _e('Edit Movies', 'movie-data') ?></h2>
	<div id="gridbox" class="grid">
        <?php  $args=array(
            'post_type'=> 'movie',
            'posts_per_page' => 10,
            'status'  => 'published');
            $query=new WP_Query($args);
        
        if ( $query->have_posts() ) 
        {
        	// The Loop
           while ( $query->have_posts() )
           {
                $query->the_post();
                $id=get_the_ID();
                global $wpdb;
                
                $term=wp_get_object_terms($id, 'genre');
   				//print_r($term);
   			
   				?>

                <div class="grid-item">
                <form method="post" action="">
                	<table>
                	<input type="hidden" name="id" value="<?php the_ID() ?>" >
	                <tr>
	                <th width="130"> <?php _e('Movie Title', 'movie-data') ?> </th>
	                <td width="200"><h6 class="title"><?php the_title() ;?></h6>
	                 	<input type="hidden" name="title" value="<?php the_title() ?>" ></td></tr>
	                <tr><th width="130"><?php _e('Movie Description', 'movie-data') ?></th>
	                <td width="200"><h6 class="content"><?php the_content(); ?></h6>
	                 	<input type="hidden" name="content" value="<?php the_content(); ?>" ></td></tr>
	                <tr><th width="130"><?php _e('Movie Author', 'movie-data') ?></th>
	                <td width="200"><h6 class="author"><?php the_author(); ?></h6></td></tr>
	                <tr><th width="130"><?php _e('Movie Genre', 'movie-data') ?></th>
	                <td width="200"><h6 class="genre"><?php echo $term[0]->name; ?></h6>
	                 	<input type="hidden" name="movie_genre" value="<?php echo $term[0]->term_taxonomy_id; ?>" >
	                </td></tr>
	             	</table>
	             	<button type="submit" value="submit" name="btn"><?php _e('Edit', 'movie-data') ?></button>
                </form>
              	</div>    
       		<?php
	       
		    if(isset($_POST['btn']))
		    {
		       		
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
	       		  	<div class="edit-movie-form" id="edit-movie-form">
  					
  					<form  action="" id="edit-movie" method="post" >
				  		<div class="form-group">
					  		<label for="name"><?php _e('Movie Name:', 'movie-data') ?></label>
					  		<input type="hidden" id="post_id" name="id" value="<?php echo $id ?>">
					  		<input type="text" class="form-control"  id="name" name="update_name" value="<?php echo $title ?>">
				  		
						</div>
		
				  		<div class="form-group">
							 <label for="description"><?php _e('Movie Description:', 'movie-data') ?></label>
							 <input type="text" class="form-control"  id="description" name="update_desc" value="<?php echo $content; ?>">
				 		</div>

			  		 <?php
			  		 global $wpdb;
			  		 $terms = get_terms( array(
    						'taxonomy' => 'genre',
    						'hide_empty' => false,
								) );
			   		 	   		
			  		 ?>
				  		<div class="form-group">
					  		<label for="genre"><?php _e('Select Genre:', 'movie-data') ?></label>
					  		<select name="update_genre" class="form-control" id="genre" >
					  			<option value="" ><?php _e('--Select Genre--', 'movie-data') ?></option>
					  			<?php 
					  			foreach($terms as $term)
					  			{	?>
						  			<option value="<?php echo $term->term_taxonomy_id;?>"
						  		 	<?php if($term->term_taxonomy_id==$genre) {echo"selected";} ?> ><?php 
						  			echo $term->name; ?></option>
					  			<?php 
					  			} 	?>
					  		</select>	  	
			 		 	</div>	 
  						<button type="submit" value="submit" class="btn btn-primary"  name="save-button">
  							<?php _e('Submit', 'movie-data') ?>
  						</button>
					</form>
				  	</div>
	       			<?php
	       			global $wpdb;
		          	if(isset($_POST['save-button']))
		          	 {		          		

		          		echo "<script type='text/javascript'>
			        	window.location=document.location.href;
			        	</script>";
			          	$id=$_POST['id'];
			            $name=$_POST['update_name'];
			            $movie_desc=$_POST['update_desc'];
			            $genre=$_POST['update_genre'];
			            
			            
					        wp_set_object_terms($id,$genre,_e('genre','movie-data') );
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
            ?>
          	<p><?php _e('Sorry, no movies found.','movie-data'); ?></p>
          	<?php
            } 
          ?>
    </div>
</div>
<?php
/* Restore original Post Data */
wp_reset_postdata();
do_action('to_open_form', 'open_editing_form');
	

}


}


if (class_exists('CustomMovieData')){
	$customMovieData= new CustomMovieData();

}
add_shortcode('movie-button',array('CustomMovieData','movieAction'));
?>