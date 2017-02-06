<?php
/**
 * Changelog
 */

$activello = wp_get_theme( 'activello' );

?>
<div class="featured-section changelog">
	

	<?php
	WP_Filesystem();
	global $wp_filesystem;
	$activello_changelog       = $wp_filesystem->get_contents( get_template_directory() . '/changelog.txt' );
	$activello_changelog_lines = explode( PHP_EOL, $activello_changelog );
	foreach ( $activello_changelog_lines as $activello_changelog_line ) {
		if ( substr( $activello_changelog_line, 0, 3 ) === "###" ) {
			echo '<h4>' . substr( $activello_changelog_line, 3 ) . '</h4>';
		} else {
			echo $activello_changelog_line, '<br/>';
		}


	}

	echo '<hr />';


	?>

</div>