<?php

if ( !class_exists( 'Anthologize_Ajax_Handlers' ) ) :

    require_once('class-project-organizer.php');

class Anthologize_Ajax_Handlers {

    var $project_organizer = null;

    function anthologize_ajax_handlers() {
        add_action( 'wp_ajax_get_tags', array( $this, 'fetch_tags' ) );
        add_action( 'wp_ajax_get_cats', array( $this, 'fetch_cats' ) );
        add_action( 'wp_ajax_get_posts_by', array( $this, 'get_posts_by' ) );
        add_action( 'wp_ajax_place_item', array( $this, 'place_item' ) );
        add_action( 'wp_ajax_merge_items', array( $this, 'merge_items' ) );
    }

    function __construct() {
        $this->anthologize_ajax_handlers();
        $project_id = $_POST['project_id'];
        if ($this->project_organizer == null){
            $this->project_organizer = new Anthologize_Project_Organizer($project_id);
        }
    }

    function fetch_tags() {
        $tags = get_tags();

        $the_tags = '';
        foreach( $tags as $tag ) {
            $the_tags .= $tag->slug . ':' . $tag->name . ',';
        }

        if (strlen($the_tags) > 0) {
            $the_tags = substr($the_tags, 0, strlen($the_tags)-1);
        }

        print($the_tags);
        die();
    }

    function fetch_cats() {
        $cats = get_categories();

        $the_cats = '';
        foreach( $cats as $cat ) {
            $the_cats .= $cat->term_id . ':' . $cat->name . ',';
        }

        if (strlen($the_cats) > 0) {
            $the_cats = substr($the_cats, 0, strlen($the_cats)-1);
        }

        print($the_cats);
        die();
    }

    function get_posts_by() {
        $term = $_POST['term'];
        $tagorcat = $_POST['tagorcat'];

        // Blech
        $t_or_c = ( $tagorcat == 'tag' ) ? 'tag' : 'cat';

        $args = array(
            'post_type' => array('post', 'page', 'imported_items' ),
            $t_or_c => $term,
            'posts_per_page' => -1
        );

        query_posts( $args );

        $response = '';

        while ( have_posts() ) {
            the_post();
            $response .= get_the_ID() . ':' . get_the_title() . ',';
        }


        if (strlen($response) > 0) {
            $response = substr($response, 0, strlen($response)-1);
        }

        print($response);

        die();
    }

    function place_item() {
        $project_id = $_POST['project_id'];
        $post_id = $_POST['post_id'];
        $dest_part_id = $_POST['dest_id'];
        $dest_seq = stripslashes($_POST['dest_seq']);
        $dest_seq_array = json_decode($dest_seq, $assoc=true);
        if ( NULL === $dest_seq_array ) {
            header('HTTP/1.1 500 Internal Server Error');
            die();
        }

        if ('true' === $_POST['new_post']) {
            $new_item = true;
            $src_part_id = false;
            $src_seq_array = false;
        } else {
            $new_item = false;
            $src_part_id = $_POST['src_id'];
            $src_seq = stripslashes($_POST['src_seq']);
            $src_seq_array = json_decode($src_seq, $assoc=true);
            if ( NULL === $src_seq_array ) {
                header('HTTP/1.1 500 Internal Server Error');
                die();
            }
        }

        $insert_result = $this->project_organizer->insert_item($project_id, $post_id, $new_item, $dest_part_id, $src_part_id, $dest_seq_array, $src_seq_array);

        if (false === $insert_result) {
            header('HTTP/1.1 500 Internal Server Error');
            die();
        } else {
            print "{\"post_id\":\"$insert_result\"}";
        }

        die();
    }

    function merge_items() {
        $project_id = $_POST['project_id'];
        $post_id = $_POST['post_id'];

        if (is_array($_POST['child_post_ids'])) {
            $child_post_ids = $_POST['child_post_ids'];
        } else {
            $child_post_ids = Array($_POST['child_post_ids']);
        }

        $new_seq = stripslashes($_POST['new_seq']);

        $new_seq_array = json_decode($new_seq, $assoc=true);
        if ( NULL === $new_seq_array ) {
            header('HTTP/1.1 500 Internal Server Error');
            die();
        }

        $append_result = $this->project_organizer->append_children($post_id, $child_post_ids);

        if (false === $append_result) {
            header('HTTP/1.1 500 Internal Server Error');
            die();
        }

        $reseq_result = $this->project_organizer->rearrange_items($new_seq_array);

        // TODO: What to do? If the merge succeeded but the resort failed, ugh...
        /*if (false === $reseq_result) {
        }*/

        die();
    }

}

endif;

?>
