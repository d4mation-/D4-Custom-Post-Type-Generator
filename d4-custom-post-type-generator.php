<?php
/*
Plugin Name: D4 Custom Post Type Generator
Description: Generate CPTs, Taxonomies, and Permissions/Roles quickly.
Version: 0.1
Author: Eric Defore
License: GPL
*/

define( 'D4_CPT_ROOT', __DIR__ );
define( 'D4_CPT_VERSION', '0.1' );

function d4_cpt_generator_loader() {
    /*
	 * Load autoloader
	 */
    require_once D4_CPT_ROOT . '/autoload.php';
}

d4_cpt_generator_loader();

?>
