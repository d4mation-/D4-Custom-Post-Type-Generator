<?php
/**
 * The CPT class
 * Create a new object of this class to create a CPT.
 *
 * @author Eric Defore <d4mation->
 */

namespace d4\CPT;

class CPT {

    protected $_post_type_names = array(
        'singular'	=>	'Custom Post Type',
        'plural'	=>	'Custom Post Types',
        'slug'		=>	'custom-post-type',
        'db_name'	=>	'custom_post_type',
    );

    protected $_menu_icon = null; // Defaults to Posts icon
    protected $_supports = array( 'title', 'editor', 'thumbnail', 'author', ); // Actual WordPress defaults are kind of dumb for this.
    protected $_taxonomies = array();
    protected $_menu_position = 5;
    protected $_is_public = true;
    protected $_has_archive = true;
    protected $_show_in_rest = true;
    protected $_capability_type = 'post';
    protected $_capabilities = array();

    protected $_options;

    function __construct( $post_type_names = array( 'Custom Post Type' ), $options = array() ) {

        if ( ! is_array( $post_type_names ) ) {

            $post_type_names = array( $post_type_names );

        }

        if( isset( $post_type_names[0] ) ) {
            $this->_post_type_names['singular'] = $post_type_names[0];
        }
        else {
            // Constructor handles it.
        }

        if( isset( $post_type_names[1] ) ) {
            $this->_post_type_names['plural'] = $post_type_names[1];
        }
        else {
            $this->_post_type_names['plural'] = $this->_make_plural( $this->_post_type_names['singular'] );
        }

        if( isset( $post_type_names[2] ) ) {
            $this->_post_type_names['slug'] = $post_type_names[2];
        }
        else {
            $this->_post_type_names['slug'] = $this->_make_slug( $this->_post_type_names['singular'] );
        }

        if( isset( $post_type_names[3] ) ) {
            $this->_post_type_names['db_name'] = $post_type_names[3];
        }
        else {
            $this->_post_type_names['db_name'] = $this->_make_db_name( $this->_post_type_names['singular'] );
        }

        if ( ! is_array( $options ) ) {
            $this->_options = array( $options );
        }
        else {
            $this->_options = $options;
        }

    }

    protected function _make_singular( $name ) {

        return str_replace( '_', ' ', str_replace( '-', ' ', ucwords( $name ) ) );

    }

    protected function _make_plural( $singular ) {

        return $singular . 's';

    }

    protected function _make_slug( $singular ) {

        return str_replace( ' ', '-', strtolower( $singular ) );

    }

    protected function _make_db_name( $singular ) {

        return str_replace( ' ', '_', strtolower( $singular ) );

    }

    public function get_singular() {

        return $this->_post_type_names['singular'];

    }

    public function set_singular( $singular ) {

        if ( empty( $singular ) || ! is_string( $singular ) ) {

            throw new \ErrorException( 'CPT singular name needs to be defined' );

        }

        $this->_post_type_names['singular'] = $singular;

        return $this;

    }

    public function get_plural() {

        return $this->_post_type_names['plural'];

    }

    public function set_plural( $plural ) {

        if ( empty( $plural ) || ! is_string( $plural ) ) {

            throw new \ErrorException( 'CPT plural name needs to be defined' );

        }

        $this->_post_type_names['plural'] = $plural;

        return $this;

    }

    public function get_slug() {

        return $this->_post_type_names['slug'];

    }

    public function set_slug( $slug ) {

        if ( empty( $slug ) || ! is_string( $slug ) ) {

            throw new \ErrorException( 'CPT slug name needs to be defined' );

        }

        $this->_post_type_names['slug'] = $this->_make_slug( $slug );

        return $this;

    }

    public function get_db_name() {

        return $this->_post_type_names['db_name'];
    }

    public function set_db_name( $db_name ) {

        if ( empty( $db_name ) || ! is_string( $db_name ) ) {

            throw new \ErrorException( 'CPT database name needs to be defined' );

        }

        $this->_post_type_names['db_name'] = $this->_make_db_name( $db_name );

        return $this;

    }

    public function get_menu_icon() {

        if ( $this->_menu_icon == null ) {

            return 'dashicons-admin-post';

        }

        return $this->_menu_icon;

    }

    public function set_menu_icon( $icon ) {

        if ( empty( $icon ) || ! is_string( $icon ) ) {

            throw new \ErrorException( 'CPT menu icon needs to be defined' );

        }

        if ( strpos( $icon, 'dashicons-' ) === false ) {

            $this->_menu_icon = 'dashicons-' . $icon; // In case "dashicons-" isn't explicitly defined

        }
        else if ( $icon == 'blank' || $icon == '' ) {

            $this->_menu_icon = ''; // Allow a truly blank Icon

        }
        else {

            $this->_menu_icon = $icon;

        }

        return $this;

    }

    public function get_supports() {

        return $this->_supports;

    }

    public function set_supports ( $supports ) {

        if ( empty( $supports ) ) {

            throw new \ErrorException( 'CPT supported options needs to be defined' );

        }

        if ( is_array( $supports ) ) {

            $this->_supports = $supports;

        }
        else {
            $this->_supports = explode( ',', trim( $supports ) );
        }

        return $this;

    }

    public function get_menu_position() {

        return $this->_menu_position;

    }

    public function set_menu_position( $menu_position ) {

        if ( ( empty( $menu_position ) ) || ( ! is_int( $menu_position ) ) || ( ! is_null( $menu_position ) ) ) {

            throw new \ErrorException( 'CPT menu position needs to be defined' );

        }

        $this->_menu_position = $menu_position;

        return $this;

    }

    public function get_taxonomies() {

        return $this->_taxonomies;

    }

    public function set_taxonomies( $taxonomies ) {

        if ( empty( $taxonomies ) ) {

            throw new \ErrorException( 'CPT taxonomies need to be defined' );

        }

        if ( is_array( $taxonomies ) ) {

            $this->_taxonomies = $taxonomies;

        }
        else {
            $this->_taxonomies = explode( ',', trim( $taxonomies ) );
        }

        return $this;

    }

    public function get_public() {

        return $this->_is_public;

    }

    public function set_public( $boolean ) {

        if ( empty( $boolean ) || is_bool( $boolean ) ) {

            throw new \ErrorException( 'CPT public value needs to be defined' );

        }

        $this->_is_public = $boolean;

        return $this;

    }

    public function get_has_archive() {

        return $this->_has_archive;

    }

    public function set_has_archivel( $boolean ) {

        if ( empty( $boolean ) || is_bool( $boolean ) ) {

            throw new \ErrorException( 'CPT has archive value needs to be defined' );

        }

        $this->_has_archive = $boolean;

        return $this;

    }

    public function get_show_in_rest() {

        return $this->_show_in_rest;

    }

    public function set_show_in_rest( $boolean ) {

        if ( empty( $boolean ) || is_bool( $boolean ) ) {

            throw new \ErrorException( 'CPT show in rest value needs to be defined' );

        }

        $this->_show_in_rest = $boolean;

        return $this;

    }

    public function get_capability_type() {

        return $this->_capability_type;

    }

    public function set_capability_type( $capability_type ) {

        if ( empty( $capability_type ) ) {

            throw new \ErrorException( 'CPT capability type needs to be defined' );

        }

        if ( is_array( $capability_type ) ) {
            $this->_capability_type = $capability_type;
        }
        else {
            $this->_capability_type = array( $capability_type, $this->_make_plural( $capability_type) );
        }

        $this->_set_capabilities( $capability_type );

        return $this;

    }

    public function get_capabilities() {

        return $this->_capabilities;

    }

    public function set_capabilities() {

        throw new \ErrorException( 'set_capabilities() should not be directly accessed. Use set_capability_type() to generate the Capabilities based on your Singular and Plural Capability Types.' );

    }

    protected function _set_capabilities( $capability_type ) {

        if ( ! is_array( $capability_type ) ) {
            $capability_type = array( $capability_type, $this->_make_plural( $capability_type) );
        }

        $capabilities = array( // Custom Post Type holds ALL capabilities. Roles are given individual capabilities.

            // Singular
            'edit_post'	=>	'edit_' . $capability_type[0],
            'read_post'	=>	'read_' . $capability_type[0],
            'delete_post'	=>	'delete_' . $capability_type[0],
            // Plural
            'edit_posts'	=>	'edit_' . $capability_type[1],
            'edit_others_posts'	=>	'edit_' . $capability_type[1],
            'publish_posts'	=>	'publish_' . $capability_type[1],
            'read_private_posts'	=>	'read_private_' . $capability_type[1],
            'delete_posts'	=>	'delete_' . $capability_type[1],
            'delete_private_posts'	=>	'delete_private_' . $capability_type[1],
            'delete_published_posts'	=>	'delete_published_' . $capability_type[1],
            'delete_others_posts'	=>	'delete_others_' . $capability_type[1],
            'edit_private_posts'	=>	'edit_private_' . $capability_type[1],
            'edit_published_posts'	=>	'edit_published_' . $capability_type[1],

        );

        $this->_capabilities = $capabilities;

        return $this;

    }

    public function register_post_type() {

        // Friendly post type names.
        $singular = $this->_post_type_names['singular'];
        $plural   = $this->_post_type_names['plural'];
        $slug     = $this->_post_type_names['slug'];
        $textdomain = $slug;

        // Default labels.
        $labels = array(
            'name'               => sprintf( __( '%s', $textdomain ), $plural ),
            'singular_name'      => sprintf( __( '%s', $textdomain ), $singular ),
            'menu_name'          => sprintf( __( '%s', $textdomain ), $plural ),
            'all_items'          => sprintf( __( '%s', $textdomain ), $plural ),
            'add_new'            => __( 'Add New', $textdomain ),
            'add_new_item'       => sprintf( __( 'Add New %s', $textdomain ), $singular ),
            'edit_item'          => sprintf( __( 'Edit %s', $textdomain ), $singular ),
            'new_item'           => sprintf( __( 'New %s', $textdomain ), $singular ),
            'view_item'          => sprintf( __( 'View %s', $textdomain ), $singular ),
            'search_items'       => sprintf( __( 'Search %s', $textdomain ), $plural ),
            'not_found'          => sprintf( __( 'No %s found', $textdomain ), $plural ),
            'not_found_in_trash' => sprintf( __( 'No %s found in Trash', $textdomain ), $plural ),
            'parent_item_colon'  => sprintf( __( 'Parent %s:', $textdomain ), $singular ),
        );

        // Default options.
        $defaults = array(
            'labels' => $labels,
            'menu_icon' => $this->_menu_icon,
            'supports' => $this->_supports,
            'taxonomies' => $this->_taxonomies,
            'menu_position' => $this->_menu_position,
            'public' => $this->_is_public,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => $this->_has_archive,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array(
                'slug' => $slug,
                'with_front' => false,
                'feeds' => false,
                'pages' => true,
            ),
            'capability_type' => $this->_capability_type,
            'capabilities' => $this->_capabilities,
        );

        // Merge user submitted options with defaults.
        $args = array_replace( $defaults, $this->_options );

        // Set the object options as full options passed.
        $this->_options = $args;

        // Check that the post type doesn't already exist.
        if ( ! post_type_exists( $this->_post_type_names['db_name'] ) ) {

            // Register the post type.
            register_post_type( $this->_post_type_names['db_name'], $this->_options );
        }

    }

    public function get_options() {

        return $this->_options; // Debugging only. No real need for this in production setting.

    }

    public function create() {

        add_action( 'init', array( $this, 'register_post_type' ) );

    }


}

?>
