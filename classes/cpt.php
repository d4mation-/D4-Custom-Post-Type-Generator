<?php
/**
 * The CPT class
 * Create a new object of this class to create a CPT.
 *
 * @author Eric Defore <d4mation>
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
	protected $_supports = array( 'title', 'editor', 'thumbnail' );
	protected $_menu_position = 5;
	protected $_capability_type = 'post';
	protected $_capabilities;
	
	protected $_options;
	
	function __construct( $post_type_names = array( 'Custom Post Type' ), Array $options = array() ) {
		
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
	
	protected function _make_singular( $name = null ) {
		
		if ( ! isset( $name ) ) {
			
			$name = $this->_post_type_names;
			
		}
		
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
		
		$this->_post_type_names['slug'] = $slug;
		
		return $this;
		
	}
	
	public function get_db_name() {
		
		return $this->_post_type_names['db_name'];
	}
	
	public function set_db_name( $db_name ) {
		
		if ( empty( $db_name ) || ! is_string( $db_name ) ) {
			
			throw new \ErrorException( 'CPT database name needs to be defined' );
			
		}
		
		$this->_post_type_names['db_name'] = $db_name;
		
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
			$this->_supports = array( $supports );
		}
		
		return $this;
		
	}
	
	public function get_menu_position() {
		
		return $this->_menu_position;
		
	}
	
	public function set_menu_position( $menu_position ) {
		
		if ( empty( $menu_position ) || ! is_int( $menu_position ) ) {
			
			throw new \ErrorException( 'CPT menu position needs to be defined' );
			
		}
		
		$this->_menu_position = $menu_position;
		
		return $this;
		
	}
	
	public function get_capability_type () {
		
		return $this->_capability_type;
		
	}
	
	public function set_capability_type( $capability_type ) {
		
		if ( empty( $capability_type ) ) {
			
			throw new \ErrorException( 'CPT capability type needs to be defined' );
			
		}
		
		if ( is_array( $capability_type ) ) {
			
			$this->_supports = $capability_type;
			
		}
		else {
			$this->_supports = array( $capability_type, $this->_make_plural( $capability_type) );
		}
		
		return $this;
		
	}
	
	public function register_post_type() {
		
		// Friendly post type names.
		$plural   = $this->_post_type_names['plural'];
		$singular = $this->_post_type_names['plural'];
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
			'parent_item_colon'  => sprintf( __( 'Parent %s:', $textdomain ), $singular )
		);

		// Default options.
		$defaults = array(
			'labels' => $labels,
			'menu_icon' => $this->_menu_icon,
			'supports' => $this->_supports,
			'menu_position' => $this->_menu_position,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => false,
				'feeds' => false,
				'pages' => true,
			),
			'capability_type' => $this->_capability_type,
		);

		// Merge user submitted options with defaults.
		$args = array_replace_recursive( $defaults, $this->_options );

		// Set the object options as full options passed.
		$this->_options = $args;

		// Check that the post type doesn't already exist.
        if ( ! post_type_exists( $this->_post_type_names['db_name'] ) ) {

			// Register the post type.
			register_post_type( $this->_post_type_names['db_name'], $this->_options );
		}
		
	}
	
	public function create() {
		
		add_action( 'init', array( $this, 'register_post_type' ) );
		
	}
	

}
 
?>