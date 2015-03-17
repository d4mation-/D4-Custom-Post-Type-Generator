<?php
/**
 * The Role class
 * Create a new object of this class to create a Role Object.
 * Role Objects can be created without Creating a Role, so they can be used to edit existing Capabilities.
 *
 * Role/Permission creation is persistent, so you can/should delete the code you write after running it. 
 * Deleting the code will not remove the Roles/Permissions. Explicitly Deleting/Removing them will.
 *
 * @author Eric Defore <d4mation>
 */

namespace d4\CPT;

class Role {
	
	protected $_role_name = 'custom_role';
	protected $_role_display_name = 'Custom Role';
	protected $_capability_type = 'post';
	protected $_permissions = 'all';
	protected $_capabilities = array( 'read' ); // Without the "read" capability the user doesn't have a backend at all.
	
	function __construct( $role_name = 'custom_role', $role_display_name = 'Custom Role', $capability_type = 'post', $permissions = array( 'all' ) ) {
		
		if ( strpos( $role_name, ' ' ) === false ) {
			$this->_role_name = $role_name;
		}
		else{
			$this->_role_name = str_replace( '-', '_', $this->_make_db_name( $role_name ) );
		}
		
		$this->_role_display_name = $role_display_name;
		
		if ( ! is_array( $capability_type ) ) {
			$this->_capability_type = array( $capability_type, $this->_make_plural( $capability_type ) );
		}
		else {
			$this->_capability_type = $capability_type;
		}
		
		
	
	}
	
	protected function _is_associative_array( $array ) {
		// Because there's no good way to check this in PHP
		return ( bool )count( array_filter( array_keys( $array ), 'is_string' ) );
	}
	
	protected function _make_plural( $singular ) {
		
		return $singular . 's';
		
	}
	
	protected function _make_db_name( $singular ) {
		
		return str_replace( ' ', '_', strtolower( $singular ) );
		
	}
	
	public function get_role_name() {
		
		return $this->_role_name;
		
	}
	
	public function set_role_name( $role_name ) {
		
		if ( empty( $role_name ) || ! is_string( $role_name ) ) {
			
			throw new \ErrorException( 'Role name needs to be defined' );
			
		}
		
		$this->_role_name = $this->_make_db_name( $role_name );
		
		return $this;
		
	}
	
	public function get_role_display_name() {
		
		return $this->_role_display_name;
		
	}
	
	public function set_role_display_name( $role_display_name ) {
		
		if ( empty( $role_display_name ) || ! is_string( $role_display_name ) ) {
			
			throw new \ErrorException( 'Role display name needs to be defined' );
			
		}
		
		$this->_role_display_name = $role_display_name;
		
		return $this;
		
	}
	
	public function get_capability_type() {
		
		return $this->_capability_type;
		
	}
	
	public function set_capability_type( $capability_type ) {
		
		if ( empty( $capability_type ) ) {
			
			throw new \ErrorException( 'Role capability type needs to be defined' );
			
		}
		
		if ( ! is_array( $capability_type ) ) {
			
			$capability_type = array( $capability_type, $this->_make_plural( $capability_type ) );
		
		}
		
		$this->_capability_type = $capability_type;
		
		return $this;
		
	}
	
	public function get_permissions() {
		
		return $this->_permissions;
		
	}
	
	public function set_permissions( $permissions ) {
		
		if ( ! is_array($permissions) ) {
			if ( strtolower( $permissions ) == 'all' ) {
				
				$this->_permissions = 'all';
				
				$this->_set_capabilities( $this->_capability_type, $this->_permissions ); // Send "All" as a String
				
				return $this; // Exit execution here
				
			}
		}
		
		if ( empty( $permissions ) || ! is_array( $permissions ) || ! $this->_is_associative_array( $permissions ) ) {
			
			throw new \ErrorException( 'Role permissions type needs to be properly defined' );
			
		}
		
		$this->_permissions = $permissions;
		
		// Generates WP Permissions
		$this->_set_capabilities( $this->_capability_type, $this->_permissions ); // Send Associative Array
		
		return $this;
		
	}
	
	public function get_capabilities() {
		
		return $this->_capabilities;
		
	}
	
	public function set_capabilities() {
		
		throw new \ErrorException( 'set_capabilities() should not be directly accessed. Use set_permissions() to generate the Capabilities based on your Singular and Plural Capability Types.' );
		
	}
	
	protected function _set_capabilities( $capability_type, $permissions ) {
		
		if ( ! is_array( $capability_type ) ) {
			$capability_type = array( $capability_type, $this->_make_plural( $capability_type) );
		}
		
		if ( ! is_array($permissions) ) {
			if ( strtolower( $permissions ) == 'all' ) {
				
				$this->_set_capabilities_parts( $capability_type, $permissions, $permissions ); // Send 'all' as both parameters
				
				return $this;
				
			}
		}
		else{
			
			foreach ( $permissions as $type => $permission ) {
				
				$this->_set_capabilities_parts( $capability_type, $type, $permission );
				
			}
			
		}
		
	}
	
	protected function _set_capabilities_parts( $capability_type, $type, $permission ) {
		
		$capabilities = $this->_capabilities;
		
		if ( strtolower( $type ) == 'all' ) {
			
			$all_capabilities = array( 
				// Singular
				'edit_' . $capability_type[0],
				'read_' . $capability_type[0], 
				'delete_' . $capability_type[0],
				// Plural
				'edit_' . $capability_type[1], 
				'edit_others_' . $capability_type[1], 
				'publish_' . $capability_type[1], 
				'read_private_' . $capability_type[1], 
				'delete_' . $capability_type[1], 
				'delete_private_' . $capability_type[1], 
				'delete_published_' . $capability_type[1], 
				'delete_others_' . $capability_type[1], 
				'edit_private_' . $capability_type[1], 
				'edit_published_' . $capability_type[1], 
			);
			
			foreach ( $all_capabilities as $capability ) {
				array_push( $capabilities, $capability );
			}
			
		}
		
		if ( strtolower( $type ) == 'read' ) {
			
			if ( strtolower( $permission ) == 'all' ) {
				
				$read_capabilities = array(
					// Singular
					'read_' . $capability_type[0],
					// Plural
					'read_private_' . $capability_type[1],
				);
				
				foreach ( $read_capabilities as $capability ) {
					array_push( $capabilities, $capability );
				}
				
			}
			else{
				
				// No Private Posts
				array_push( $capabilities, 'read_' . $capability_type[0] );
				
			}
			
		}
		
		if ( strtolower( $type ) == 'edit' ) {
			
			// General catch-all permissions
			$edit_capabilities = array(
				// Singular
				'edit_' . $capability_type[0],
				// Plural
				'edit_' . $capability_type[1],
				'edit_published_' . $capability_type[1], 					
			);
			
			foreach ( $edit_capabilities as $capability ) {
				array_push( $capabilities, $capability );
			}
			
			if ( strtolower( $permission ) == 'all' ) {
				
				$edit_capabilities = array( 
					// Singular
					// Plural
					'edit_others_' . $capability_type[1], 
					'edit_private_' . $capability_type[1], 
				);
				
				foreach ( $edit_capabilities as $capability ) {
					array_push( $capabilities, $capability );
				}
				
			}
			else if ( strtolower( $permission ) == 'private' ) {
				
				array_push( $capabilities, 'edit_private_' . $capability_type[1] );
				
			}
			else if ( strtolower( $permission ) == 'others' ) {
				
				array_push( $capabilities, 'edit_others_' . $capability_type[1] );
				
			}
			
		}
		
		if ( strtolower( $type ) == 'delete' ) {
			
			// General catch-all permissions
			$delete_capabilities = array(
				// Singular
				'delete_' . $capability_type[0],
				// Plural
				'delete_' . $capability_type[1], 
				'delete_published_' . $capability_type[1], 			
			);
			
			foreach ( $delete_capabilities as $capability ) {
				array_push( $capabilities, $capability );
			}
			
			if ( strtolower( $permission ) == 'all' ) {
				
				$delete_capabilities = array( 
					// Singular
					// Plural
					'delete_private_' . $capability_type[1], 
					'delete_others_' . $capability_type[1], 
				);
				
				foreach ( $delete_capabilities as $capability ) {
					array_push( $capabilities, $capability );
				}
				
			}
			else if ( strtolower( $permission ) == 'private' ) {
				
				array_push( $capabilities, 'delete_private_' . $capability_type[1] );
				
			}
			else if ( strtolower( $permission ) == 'others' ) {
				
				array_push( $capabilities, 'edit_others_' . $capability_type[1] );
				
			}
			
		}
		
		$this->_capabilities = $capabilities;
		
	}
	
	public function add_permissions( $only_current_role = false ) {
		
		$capabilities = $this->_capabilities;
		
		if ( $only_current_role === true ) {
			
			$role = get_role( $this->_role_name );
			
			foreach ( $capabilities as $capability ) {
				
				$role->add_cap( $capability );
				
			}
			
		}
		else{
			
			$roles = array( $this->_role_name, 'administrator', 'editor', 'author' );
			
			foreach ( $roles as $the_role ) {
				foreach ( $capabilities as $capability ) {
					
					$role = get_role( $the_role );
					$role->add_cap( $capability );
					
				}
			}
			
		}
		
	}
	
	public function remove_permissions( $only_current_role = false ) {
		
		$capabilities = $this->_capabilities;
		
		if ( $only_current_role === true ) {
			
			$role = get_role( $this->_role_name );
			
			foreach ( $capabilities as $capability ) {
				
				$role->remove_cap( $capability );
				
			}
			
		}
		else{
			
			$roles = array( $this->_role_name, 'administrator', 'editor', 'author' );
			
			unset( $capabilities[0] ); // Just to be safe, don't remove the 'read' capability.
			
			foreach ( $roles as $the_role ) {
				foreach ( $capabilities as $capability ) {
					
					$role = get_role( $the_role );
					$role->remove_cap( $capability );
					
				}
			}
			
		}
		
	}
	
	public function add_role() {
		
		add_role( $this->_role_name, $this->_role_display_name, $this->_capabilities );
		
	}
	
	public function remove_role() {
		
		remove_role( $this->_role_name );
		
	}
	
	public function get_options() {
		
		return $this->_options; // Debugging only. No real need for this in production setting.
		
	}
	
	public function create() {
		
		add_action( 'init', array( $this, 'add_role' ) );
		
	}
	
	public function destroy() {
		
		add_action( 'init', array( $this, 'remove_role' ) );
		
	}

}

?>