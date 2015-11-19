<?php
$global_options = (array) get_option( 'mc4wp_form', array() );

// find all form posts
$posts = get_posts(
	array(
		'post_type' => 'mc4wp-form',
		'post_status' => 'publish',
		'numberposts' => -1
	)
);

$css_map = array(
	'default'       => 'form-basic',
	'custom'        => 'styles-builder',
	'light'         => 'form-theme-light',
	'dark'          => 'form-theme-dark',
	'red'           => 'form-theme-red',
	'green'         => 'form-theme-green',
	'blue'          => 'form-theme-blue',
	'custom-color'  => 'form-theme-custom-color'
);

foreach( $posts as $post ) {

	// get form options from post meta directly
	$options = (array) get_post_meta( $post->ID, '_mc4wp_settings', true );

	// store all global options in scoped form settings
	// do this BEFORE changing css key, so we take that as well.
	foreach( $global_options as $key => $value ) {
		if( strlen( $value ) > 0 && ( ! isset( $options[ $key ] ) || strlen( $options[ $key ] ) == 0 ) ) {
			$options[ $key ] = $value;
		}
	}

	// update "css" option value
	if( isset( $options['css'] ) && isset( $css_map[ $options['css'] ] ) ) {
		$options['css'] = $css_map[ $options['css'] ];
	}

	update_post_meta( $post->ID, '_mc4wp_settings', $options );
}

// delete old options
delete_option( 'mc4wp_form' );