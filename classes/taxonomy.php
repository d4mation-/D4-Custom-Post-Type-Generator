<?php
/**
 * The Taxonomy class
 * Create a new object of this class to create a Taxonomy.
 *
 * @author Eric Defore <d4mation>
 */

namespace d4\CPT;

class Taxonomy {

	protected $_taxonomy_names = array(
		'singular'	=>	'Custom Taxonomy',
		'plural'	=>	'Custom Taxonomies',
		'slug'		=>	'custom_taxonomy',
		'slug'		=>	'custom-taxonomy',
		'db_name'	=>	'custom_taxonomy',
	);
	
	protected $_post_types = array( 'post' );
	
	protected $_options;
	
	function __construct( $taxonomy_names = array( 'Custom Taxonomy' ), $post_types = array( 'post' ), $options = array() ) {
		
		if ( ! is_array( $taxonomy_names ) ) {
			
			$taxonomy_names = array( $taxonomy_names );
			
		}
		
		if( isset( $taxonomy_names[0] ) ) {
			$this->_taxonomy_names['singular'] = $taxonomy_names[0];
		}
		else {
			// Constructor handles it.
		}
		
		if( isset( $taxonomy_names[1] ) ) {
			$this->_taxonomy_names['plural'] = $taxonomy_names[1];
		}
		else {
			$this->_taxonomy_names['plural'] = $this->_make_plural( $this->_taxonomy_names['singular'] );
		}
		
		if( isset( $taxonomy_names[2] ) ) {
			$this->_taxonomy_names['slug'] = $taxonomy_names[2];
		}
		else {
			$this->_taxonomy_names['slug'] = $this->_make_slug( $this->_taxonomy_names['singular'] );
		}
		
		if( isset( $taxonomy_names[3] ) ) {
			$this->_taxonomy_names['db_name'] = $taxonomy_names[3];
		}
		else {
			$this->_taxonomy_names['db_name'] = $this->_make_db_name( $this->_taxonomy_names['singular'] );
		}
		
		if ( ! is_array( $post_types ) ) {
			$this->_post_types = array( $post_types );
		}
		else{
			$this->_post_types = $post_types;
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
		
		return $this->_taxonomy_names['singular'];
		
	}
	
	public function set_singular( $singular ) {
		
		if ( empty( $singular ) || ! is_string( $singular ) ) {
			
			throw new \ErrorException( 'Taxonomy singular name needs to be defined' );
			
		}
		
		$this->_taxonomy_names['singular'] = $singular;
		
		return $this;
		
	}
	
	public function get_plural() {
		
		return $this->_taxonomy_names['plural'];
		
	}
	
	public function set_plural( $plural ) {
		
		if ( empty( $plural ) || ! is_string( $plural ) ) {
			
			throw new \ErrorException( 'Taxonomy plural name needs to be defined' );
			
		}
		
		$this->_taxonomy_names['plural'] = $plural;
		
		return $this;
		
	}
	
	public function get_slug() {
		
		return $this->_taxonomy_names['slug'];
		
	}
	
	public function set_slug( $slug ) {
		
		if ( empty( $slug ) || ! is_string( $slug ) ) {
			
			throw new \ErrorException( 'Taxonomy slug name needs to be defined' );
			
		}
		
		$this->_taxonomy_names['slug'] = $slug;
		
		return $this;
		
	}
	
	public function get_db_name() {
		
		return $this->_taxonomy_names['db_name'];
	}
	
	public function set_db_name( $db_name ) {
		
		if ( empty( $db_name ) || ! is_string( $db_name ) ) {
			
			throw new \ErrorException( 'Taxonomy database name needs to be defined' );
			
		}
		
		$this->_taxonomy_names['db_name'] = $db_name;
		
		return $this;
		
	}
	
	public function get_post_types() {
		
		return $this->_post_types;
		
	}
	
	public function set_post_types( $post_types ) {
		
		if ( empty( $post_types ) || is_int( $post_types ) ) {
			
			throw new \ErrorException( 'Taxonomy post type(s) needs to be defined' );
			
		}
		
		if ( is_array( $post_types ) ) {
			$this->_post_types = $post_types;
		}
		else{
			$this->_post_types = array( $post_types );
		}
		
	}
	
	public function register_taxonomy() {
		
		// Friendly post type names.
		$singular = $this->_taxonomy_names['singular'];
		$plural   = $this->_taxonomy_names['plural'];
		$slug     = $this->_taxonomy_names['slug'];
		$textdomain = $slug;

		// Default labels.
		$labels = array( 
			'name'							=>	sprintf( __( '%s', $textdomain ), $plural ),
			'singular_name'					=>	sprintf( __( '%s', $textdomain ), $singular ),
			'search_items'					=>	sprintf( __( 'Search %s', $textdomain ), $plural ),
			'popular_items'					=>	sprintf( __( 'Popular %s', $textdomain ), $plural ),
			'all_items'						=>	sprintf( __( 'All %s', $textdomain ), $plural ),
			'parent_item'					=>	sprintf( __( 'Parent %s', $textdomain ), $singular ),
			'parent_item_colon'				=>	sprintf( __( 'Parent %s:', $textdomain ), $singular ),
			'edit_item'						=>	sprintf( __( 'Edit %s', $textdomain ), $singular ),
			'update_item'					=>	sprintf( __( 'Update %s', $textdomain ), $singular ),
			'add_new_item'					=>	sprintf( __( 'Add New %s', $textdomain ), $singular ),
			'new_item_name'					=>	sprintf( __( 'New %s', $textdomain ), $singular ),
			'separate_items_with_commas'	=>	sprintf( __( 'Separate %s with commas', $textdomain ), $plural ),
			'add_or_remove_items'			=>	sprintf( __( 'Add or remove %s', $textdomain ), $plural ),
			'choose_from_most_used'			=>	sprintf( __( 'Choose from most used %s', $textdomain ), $plural ),
			'menu_name'						=>	sprintf( __( '%s', $textdomain ), $plural ),
		);
		
		// Default options.
		$defaults = array( 
			'labels' => $labels,
			'public' => false,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'hierarchical' => true,

			'rewrite' => array( 
				'slug' => $slug, 
				'with_front' => false,
				'feeds' => false,
				'pages' => true
			),
			'query_var' => true
		);

		// Merge user submitted options with defaults.
		$args = array_replace( $defaults, $this->_options );

		// Set the object options as full options passed.
		$this->_options = $args;

		// Check that the post type doesn't already exist.
        //if ( ! post_type_exists( $this->_taxonomy_names['db_name'] ) ) {

			// Register the post type.
			register_taxonomy( $this->_taxonomy_names['db_name'], $this->_post_types, $this->_options );
		//}
		
	}
	
	public function get_options() {
		
		return $this->_options; // Debugging only. No real need for this in production setting.
		
	}
	
	public function create() {
		
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		
	}

}

?>