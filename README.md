# D4 Custom Post Type Generator #
_Generate CPTs, Taxonomies, and Permissions/Roles quickly_

# Custom Post Types #

### Basic Constructor ###

    $cpt = new d4\CPT\CPT( $post_type_names = array( 'Custom Post Type' ), $options = array() );
    // Any Additional Setter functions
    $cpt->create();

```$post_type_names``` holds an array of the Singular, Plural, Slug, and "database name" of the Custom Post Type. You only need to provide the Singular name for a valid constructor.

* If Plural is not set, the Singular Name will just have an "s" added to it.
* If Slug is not set, the Singular Name will be converted to lowercase with spaces replaced with hyphens.
* If Database Name is not set, the singular name will be converted to lowercase with spaces replaced with underscores.

        $post_type_names = array(
            'Custom Post Type', // Singluar
            'Custom Post Types', // Plural
            'custom-post-type', // Slug
            'custom_post_type', // "database_name"
        );

```$options``` holds any arguments for registering the Custom Post Type that I haven't bothered making a Getter/Setter for. Anything placed in here will override the defaults as well as anything that has been set with the Setter functions within the Class.

    $defaults = array(
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => array( 'title', 'editor', 'thumbnail', 'author', ),
        'taxonomies' => array(),
        'menu_position' => 5,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array(
            'slug' => <YOUR CPT SLUG>,
            'with_front' => false,
            'feeds' => false,
            'pages' => true,
        ),
        'capability_type' => 'post',
        'capabilities' => array(),
    );

    $options = array(
        'public' => false, // Overrides the default for public
    );

### Getters/Setters ###

#### General ####

* ```get_singular()``` and ```set_singular( String $singular )```
    * Overrides the Singular set in the Constructor
* ```get_plural()``` and ```set_plural( String $plural )```
    * Overrides the Plural set in the Constructor
* ```get_slug()``` and ```set_slug( String $slug )```
    * Overrides the Slug set in the Constructor
* ```get_db_name()``` and ```set_db_name( String $db_name )```
    * Overrides the Database Name set in the Constructor

#### [`register_post_type()`](https://codex.wordpress.org/Function_Reference/register_post_type) Arguments ####

* ```get_menu_icon()``` and ```set_menu_icon( String $menu_icon )```
    * ```set_menu_icon( String $menu_icon )``` assumes you are working with the built-in Dashicons
        * You do not need to specifically include "dashicons-", but it will work either way.
        * If you set it to "blank" or "" no Icon will be set. This is usefull if you are going to include Font-Awesome or another CSS Icon Library in the Backend and want to use CSS to display a Custom Icon.
* ```get_supports()``` and ```set_supports( Array $supports )```
    * ```set_supports( String $supports )``` will work as well. It will create an Array for you.
        * It will split the String on commas.
* ```get_menu_position()``` and ```set_menu_position( int $menu_position )```
    * Standard Menu Positions
        * null - below Comments
        * 5 - below Posts
        * 10 - below Media
        * 15 - below Links
        * 20 - below Pages
        * 25 - below comments
        * 60 - below first separator
        * 65 - below Plugins
        * 70 - below Users
        * 75 - below Tools
        * 80 - below Settings
        * 100 - below second separator
* ```get_taxonomies()``` and ```set_taxonomies( Array $taxonomies )```
    * ```set_taxonomies( String $taxonomies )``` will also work as well. It will create an Array for you.
       * It will split the String on commas.
* ```get_capability_type()``` and ```set_capability_type( Array $capability_type )```
    * ```set_capability_type( String $capability_type )``` will work as well. It will create an Array for you.
        * It will add an "s" at the end to make it plural.
* ```get_public()``` and ```set_public( boolean $is_public )```
    * Overrides the `$default` settings
* ```get_has_archive``` and ```set_has_archive( boolean $has_archive )```
    * Overrides the `$default` settings

# Custom Taxonomies #

Coming Soon...

# Custom User Roles #

### Basic Constructor ###

    $role = new d4\CPT\Role( $role_names = array( 'Custom Role' ) );
    $role->create();
    // Any Additional Setter functions

    $role->destroy(); // Destroys the Role.

***NOTE: Setter functions have to go after the create() method on Roles. This is because they must exist in the Database first before anything can be added to/removed from them.***
* The Constructor could theorhetically take a Capability Type and Permission Levels to add them at Role Creation, but it ends up making this Class less flexible. Especially since you have to explicitly add and remove them from the database instead of just deleting the code.

```$role_names``` holds an array of the Singular and "database name" of the Custom Post Type. You only need to provide the Singular name for a valid constructor.

* If Database Name is not set, the singular name will be converted to lowercase with spaces replaced with underscores.

        $role_names = array(
            'Custom Role', // Singluar
            'custom_role', // "database_name"
        );

### Getters/Setters ###

#### General ####

* ```get_role_display_name()``` and ```set_role_display_name( String $display_name )```
    * Overrides the Role's Display Name set in the Constructor
* ```get_role_name()``` and ```set_role_name( String $role_name )```
    * Overrides the Role;s Database Name set in the Constructor

#### Permission Generation ####

* ```get_capability_type()``` and ```set_capability_type( Array $capability_type )```
    * ```set_capability_type( String $capability_type )``` will work as well. It will create an Array for you.
        * It will add an "s" at the end to make it plural.
* ```get_permissions()``` and ```set_permissions( Array $permissions )```
    * Permissions are generated using the set Capability Type.
    * You can set all Permissions within a capability type:

            $role->set_capability_type( 'post' );
            $role->set_permissions( 'all' );
    * You can also be more granular:

            $role->set_capability_type( 'post' );
            $role->set_permissions( array(
                'read' => 'all',
                'edit' => 'private',
            ) );
        * Here are all the possible combinations:
            * read
                * all
                * own
            * edit
                * all
                * private
                * others
            * delete
                * all
                * private
                * others
* ```add_permissions( boolean $only_current_role )```
    * Once you've generated Permissions using ```set_permissions()```, this will add any queued up Permissions to the Role
    * If ```$only_current_role``` is set to ```true```, then this will also apply to the Administrator, Editor, and Author Roles.
* ```remove_permissions( boolean $only_current_role )```
    * This will remove any queued up Permissions genereated with ```set_permissions()``` from the role.
    * If ```$only_current_role``` is set to ```true```, then this will also apply to the Administrator, Editor, and Author Roles.
* ```clear_permissions_queue()```
    * This clears the queue of generated Permissions.
    * The ```set_permissions()``` Pushes to the end of an Array. This allows you to generate Permissions for multiple Capability Types and then add to/remove from a Role all at once. This method exists if you want to avoid that.
