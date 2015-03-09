<?php
/**
 * Class and Trait autoloader for this plugin
 *
 * @author Eric Defore <d4mation>
 */

/*
 * Register resource autoloader
 */
spl_autoload_register( 'd4_cpt_generator_autoloader' );

/**
 * The function that makes on-demand autoloading of files for this plugin
 * possible. It is registered with spl_autoload_register() and must not be
 * called directly.
 *
 * @param string $resource Fully qualified name of the resource that is to be loaded
 * @return void
 */
function d4_cpt_generator_autoloader( $resource = '' ) {
	$namespace_root = 'd4\CPT';

	$resource = trim( $resource, '\\' );

	if ( empty( $resource ) || strpos( $resource, '\\' ) === false || strpos( $resource, $namespace_root ) !== 0 ) {
		//not our namespace, bail out
		return;
	}

	$path = str_replace(
				'_',
				'-',
				implode(
					'/',
					array_slice(	//remove the namespace root and grab the actual resource
						explode( '\\', $resource ),
						2
					)
				)
			);

	$path = sprintf( '%s/classes/%s.php', untrailingslashit( D4_CPT_ROOT ), strtolower( $path ) );

	if ( file_exists( $path ) ) {
		require_once $path;
	}
}


//EOF