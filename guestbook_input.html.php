<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html>
<title>Contact</title>
<body>
<?php if (isset($error_message)) {
    echo '<p>' . esc_html($error_message) . '</p>';
}?>
	<form method="post" action="">
		<p><label for="first">First name:</label><input id="first" type="text" name="first_name" value="<?php if (isset($_POST['first_name'])) {echo esc_html($_POST['first_name']);}?>"></p>
		<p><label for="last">Last name:</label><input id="last"type="text" name="last_name" value="<?php if (isset($_POST['last_name'])) {echo esc_html($_POST['last_name']);}?>"></p>
		<p><label for="location">Location:</label><input id="location" type="text" name="input_location" value="<?php if (isset($_POST['input_location'])) {echo esc_html($_POST['input_location']);}?>"></p>
		<p><label for="email">E-mail:</label><input id="email" type="text" name="input_email" value="<?php if (isset($_POST['input_email'])) {echo esc_html($_POST['input_email']);}?>"></p>
		<p><label for="comments">Comments:</label><textarea id="comments" name="input_comments" rows="4" cols="60"><?php if (isset($_POST['input_comments'])) {echo esc_textarea($_POST['input_comments']) ;}?></textarea></p>
		<br>
		<p><input type="submit"></p>
		<br>
	</form>
</body>
</html>