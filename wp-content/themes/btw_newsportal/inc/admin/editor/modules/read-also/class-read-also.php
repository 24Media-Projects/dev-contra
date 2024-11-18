<?php 


class BTW_Editor_Module_Read_Also extends BTW_Editor_Module{

    protected $module_label = 'Σχετικό Άρθρο';

    protected $module_name = 'read-also';

    private $settings = [
        'post_type' => [ "'post'" ],
    ];

    public function __construct(){

        parent::__construct();

        $this->settings = apply_filters( 'btw/admin_editor/modules/read_also/settings', $this->settings );

        add_action( 'wp_ajax_read_also__preview', [ $this, 'read_also__preview' ] );
        add_action( 'wp_ajax_read_also__get_data', [ $this, 'read_also__get_data' ] );
        add_action( 'wp_ajax_read_also__get_post_tags', [ $this, 'read_also__get_post_tags' ] );

    }

    public function admin_editor_scripts(){

        wp_register_script( 'wp_editor_read_also_js', get_template_directory_uri() . '/inc/admin/editor/modules/read-also/assets/js/read-also.js', array( 'jquery' ), $this->script_version, false );
        wp_enqueue_script( 'wp_editor_read_also_js' );

    }



    /**
     * Read Also Preview
     * Returns the shortcode as html to editor.
     * Shortcode Params: posts
     */
    public function read_also__preview(){

        $post = get_post( ( int ) $_POST['post_id'] );
        $return = [];

        if( !$post ){
            $return['success'] = false;
            wp_send_json( $return );
        }

        $return['html'] = '';

        $atts = $_POST['atts']['named'];
        $posts = $atts['posts'];


        ob_start();
        echo do_shortcode( '[read_also_sc posts="' . $posts . '"]' );
        $return['html'] = ob_get_contents();
        ob_get_clean();

        $return['success'] = true;

        wp_send_json_success( $return );
    }


    /**
     *  Get Options for select in read also modal including filters: search and post tags
     *  Returns matching posts as options and selected posts
     */
    public function read_also__get_data(){

        $post = get_post( ( int ) $_POST['current_post_id'] );
        $return = [];

        if( !$post || !wp_verify_nonce( $_POST['nonce'], 'btw-wp-editor-nonce' ) ){
            $return['success'] = false;
            wp_send_json( $return );
        }

        $selected_posts = !empty( $_POST['selected_posts'] ) ? explode( ',', $_POST['selected_posts'] ) : [0];

        $return['choices'] = [];
        $return['values'] = [];

        global $wpdb;

        $posts_per_page = $_POST['posts_per_page'] ?? 15;

        $choices_args = array(
            'posts_per_page' => $posts_per_page,
            'post_type' => 'post',
        );

        $limit = $posts_per_page;
        $offset = ( $_POST['page'] - 1 ) * $limit;

        $search = !empty( $_POST['search'] ) ? ' AND p.post_title LIKE "%' . trim( sanitize_text_field( $_POST['search'] ) ) . '%"' : '';
        $date_sql_where = !empty( $_POST['date'] ) ? " AND p.post_date >= '" . $_POST['date'] . "-01-01'" : '';
        $tags_join = !empty( $_POST['tag'] ) ? ' INNER JOIN ' . $wpdb->term_relationships . ' as t ON t.object_id = p.ID ' : '';
        $tags_where = !empty( $_POST['tag'] ) ? ' AND t.term_taxonomy_id = "' . $_POST['tag'] . '"' : '';
        $sql_post_type_in = 'post_type IN(' . implode( ',', $this->settings['post_type'] ) . ')';

        $choices = $wpdb->get_results(
            "SELECT p.ID, p.post_title
             FROM $wpdb->posts as p $tags_join
             WHERE $sql_post_type_in AND p.post_status = 'publish' $search $tags_where $date_sql_where
             ORDER BY p.post_date DESC ,p.ID DESC  LIMIT {$limit} OFFSET {$offset}"
        );

        $return['sql'] = $wpdb->last_query;

        $values = get_posts([
            'post__in' => $selected_posts,
        ]);

        foreach( $choices as $choice ):
            $return['choices'][] = array(
                'ID'        => $choice->ID,
                'postTitle' => $choice->post_title,
            );
        endforeach;

        if( !$choices && $_POST['page'] == 1 ){
            $return['choices'][] = array(
                'ID' => -1,
                'postTitle' => 'Δεν βρέθηκαν αποτελέσματα.'
            );

        }elseif( !$choices ){
            $return['choices'] = [];
        }

        foreach( $values as $value ):
            $return['values'][] = array(
                'ID'        => $value->ID,
                'postTitle' => $value->post_title,
            );

        endforeach;

        $return['success'] = true;

        wp_send_json( $return );
    }

    /**
     * Get availiable post tags to filter by tag in modal
     */
    public function read_also__get_post_tags(){
        $return = [];
        $search_tag = !empty($_GET['search']) ? $_GET['search'] : null;

        $post = get_post((int) $_GET['current_post_id']);

        if (!$post || !wp_verify_nonce($_GET['nonce'], 'btw-wp-editor-nonce')) {
            $return[] = array(
                'id' => -1,
                'text' => '-1',
            );

            wp_send_json_error($return);
        }

        $args = array(
            'taxonomy' => 'post_tag',
            'number' => 100,
        );

        if ($search_tag) {
            $args['name__like'] = $search_tag;
        }

        $tags = get_terms($args);

        foreach ($tags as $tag) {
            $return[] = array(
                'id' => $tag->term_id,
                'text' => $tag->name,
            );
        }

        wp_send_json($return);
    }

}


 $btw_editor_module_read_also = new BTW_Editor_Module_Read_Also();