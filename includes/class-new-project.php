<?php

if ( !class_exists( 'Anthologize_New_Project' ) ) :

class Anthologize_New_Project {

	function save_project () {

        $post_data = array();
        $post_data['post_title'] = $_POST['post_title'];
        $post_data['post_type'] = 'projects';
        $post_data['post_status'] = 'publish'; // Should come up with a way to manage drafts vs. publishing.

        $new_anthologize_meta = $_POST['anthologize_meta'];
       // print_r($_POST); die();

        // If we're editing an existing project.
        if ( !empty($_POST['project_id'])) {

            $post_data['ID'] = $_POST['project_id'];
		    wp_update_post($post_data);

		    if ( is_null($new_anthologize_meta) ) {

		        delete_post_meta($post_data['ID'],'anthologize_meta');

		    } else {

		        update_post_meta($post_data['ID'],'anthologize_meta',$new_anthologize_meta);

		    }

		} else { // Otherwise, we're creating a new project

            $new_post = wp_insert_post($post_data);
            update_post_meta($new_post, 'anthologize_meta', $new_anthologize_meta );

		}

		wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=anthologize' );
	}

	function display() {

	    if ( isset($_POST['save_project']) ) {
            $project_id = $this->save_project();
        }

	    $project = get_post(@$_GET['project_id'] );
	    $meta = get_post_meta( $project->ID, 'anthologize_meta', TRUE );

	?>
		<div class="wrap anthologize">

		<div id="anthologize-logo"><img src="<?php echo WP_PLUGIN_URL . '/anthologize/images/anthologize-logo.gif' ?>" /></div>
            <?php if ( $project ): ?>
			<h2><?php _e( 'Edit Project', 'anthologize' ) ?></h2>
            <?php else: ?>
            <h2><?php _e( 'Add New Project', 'anthologize' ) ?></h2>
    		<?php endif; ?>
            <form action="<?php echo get_bloginfo( 'wpurl' ) ?>/wp-admin/admin.php?page=anthologize/includes/class-new-project.php&noheader=true" method="post">
                <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="post_title"><?php _e( 'Project Title', 'anthologize' ) ?></label></th>
                    <td><input type="text" name="post_title" value="<?php echo $project->post_title; ?>"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="anthologize_meta[subtitle]"><?php _e( 'Subtitle', 'anthologize' ) ?></label>
                    <td><input type="text" name="anthologize_meta[subtitle]" value="<?php if( !empty($meta['subtitle']) ) echo $meta['subtitle']; ?>" /></td>
                </tr>

            	<tr valign="top">
            	    <th scope="row"><label><?php _e( 'Author Name <span>(optional)</span>', 'anthologize' ) ?></label></th>
            	    <td><textarea name="anthologize_meta[author_name]" rows="5" cols="50"><?php if( !empty($meta['author_name']) ) echo $meta['author_name']; ?></textarea></td>
            	</tr>


            </table>


       	   <div class="anthologize-button"><input type="submit" name="save_project" value="Save Project"></div>
            <input type="hidden" name="project_id" value="<?php echo $project->ID ?>">
            </form>

		</div>
		<?php

	}
}

endif;

function item_meta_redirect($location) {
    $location = 'admin.php?page=anthologize';
    echo $location; exit;
    return $location;
}

add_filter('redirect_post_location', 'item_meta_redirect');

$new_project = new Anthologize_New_Project();
$new_project->display();

?>