<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$host_name = DB_HOST;
$database = DB_NAME;
$user_name = DB_USER;
$password = DB_PASSWORD;

$link = mysqli_connect($host_name, $user_name, $password, $database);
if (mysqli_connect_errno()) {
    die('<p>Failed to connect to MySQL: '.esc_html(mysqli_error($link)).'</p>');
} else {
    //echo '<p>Connection to MySQL server successfully established.</p >';
}
?>