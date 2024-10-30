<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
</head>
<body>
	<header>
		<h1>Error Page</h1>
	</header>
	
	<p><?php echo esc_html($err); ?></p>
</body>

</html>