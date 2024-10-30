<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Plugin Name: Simple Contact Form
Plugin URI: https://github.com/cmiller734/contact-plugin
description: A non-bloated contact form built using PHP, SQL, HTML, and CSS
Version: 1.0
Author: Caleb Miller
Author URI: http://calebmillerweb.com
License: GPL2
 */


function simple_cf_sanitize_comments($string) { 
    $newlined_comments = nl2br($string);
    $newlined_comments_no_newlines_carriage = str_replace(array('\r\n', '\r', '\n'), '', $newlined_comments);
    return $newlined_comments_no_newlines_carriage;
}

function simple_cf_init()
{
    include('db.php');
    wp_register_style ( 'contact-plugin', plugins_url ( 'style.css', __FILE__ ) );
    wp_enqueue_style('contact-plugin');
    
    if (array_key_exists('first_name', $_REQUEST) ||
        array_key_exists('first_name', $_REQUEST) ||
        array_key_exists('input_location', $_REQUEST) ||
        array_key_exists('input_email', $_REQUEST) ||
        array_key_exists('input_comments', $_REQUEST)) //if any input has EVER been put in any of the forms
    {
        $error_message = nl2br("Please correct the following errors:\n");        
        $sanitized_first = sanitize_text_field($_REQUEST['first_name']);
        $sanitized_last = sanitize_text_field($_REQUEST['last_name']);
        $sanitized_location = sanitize_text_field($_REQUEST['input_location']);
        $sanitized_email = sanitize_email($_REQUEST['input_email']);
        $sanitized_comments = sanitize_textarea_field($_REQUEST['input_comments']);

        if (!empty($sanitized_first) && !ctype_space($sanitized_first) &&
            !empty($sanitized_last) && !ctype_space($sanitized_last) &&
            !empty($sanitized_location) && !ctype_space($sanitized_location) && 
            !empty($sanitized_email) && !ctype_space($sanitized_email))
        {
            $validation_met = true;
            if (strlen( $sanitized_first) < 2) 
            {
                $error_message .= "First name must consist of at least 2 characters!";
                $validation_met = false;
            }
            if (strlen($sanitized_last) < 2) 
            {
                $error_message .= "Last name must consist of at least 2 characters!";
                $validation_met = false;
            }
            if (strlen($sanitized_location) < 2)
            {
                $error_message .= "Your location must consist of at least 2 characters!";
                $validation_met = false;
            }
            if (!strpos($sanitized_email, "@") && $sanitized_email != "@") 
            {
                $error_message .= "E-mail address must contain an @ sign!";
                $validation_met = false;
            }

            if ($validation_met == false) 
            {
                include 'guestbook_input.html.php';
            } else
            {

                //Insertion into the SQL table
                $use_string = "USE " . DB_NAME . ";";
                if (!mysqli_query($link, $use_string)) {
                    $err = 'Unable to use database: ' . mysqli_error($link);
                    include 'errmsg.html.php';
                    exit();
                }
                
                $create_string = "CREATE TABLE IF NOT EXISTS contacts_table(FirstName varchar(255), LastName varchar(255), Location varchar(255), Email varchar(320), Comments varchar(255) )";
                if (!mysqli_query($link, $create_string)) {
                    $err = 'Unable to create table: ' . mysqli_error($link);
                    include 'errmsg.html.php';
                    exit();
                }

                $stmt = mysqli_prepare($link, "INSERT INTO contacts_table VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'sssss', $sanitized_first, $sanitized_last, $sanitized_location, $sanitized_email, $sanitized_comments);
                   
                  
                if (!mysqli_stmt_execute($stmt)) {
                    $err = 'Unable to update active field in members table: ' . mysqli_error($link);
                    include 'errmsg.html.php';
                    exit();
                }

                $email_to = get_bloginfo('admin_email');
                $email_subject = "New Form Submission From ";
                
                if (get_bloginfo('name')) {
                    $email_subject .= get_bloginfo('name');
                } else {
                    $email_subject .= "Your WP Site";
                }

                $email_message .= "First Name: ". $sanitized_first ."\n";
                $email_message .= "Last Name: ".$sanitized_last."\n";
                $email_message .= "Location: ".$sanitized_location."\n";
                $email_message .= "Email: ".$sanitized_email."\n";
                $email_message .= "Comments: ".$sanitized_comments."\n";

                $headers = 'From: '.$sanitized_email."\r\n".
                'Reply-To: '.$sanitized_email."\r\n" .
                'X-Mailer: PHP/' . phpversion();
                wp_mail($email_to, $email_subject, $email_message, $headers); 

                $final_message = "Thanks for contacting me. I'll get back to you shortly!";
                include 'guestbook_results.html.php';
            }
        } else {
            if (empty($sanitized_first) || ctype_space($sanitized_first)) //VALIDATION
            {
                $error_message .= nl2br("You must provide a name!\n");
            }
            if (empty($sanitized_last) || ctype_space($sanitized_last)) //VALIDATION
            {
                $error_message .= nl2br("You must provide a name!\n");
            }
            if (empty($sanitized_location) || ctype_space($sanitized_location)) //VALIDATION
            {
                $error_message .= nl2br("You must provide a location!\n");
            } 
            if (empty($sanitized_email) || ctype_space($sanitized_email)) //VALIDATION
            {
                $error_message .= nl2br("You must provide a valid e-mail address!\n");
            }
            include 'guestbook_input.html.php';
        }
    }
    else {
        include 'guestbook_input.html.php';
    }
}

add_shortcode( 'my_contact_form', 'simple_cf_init' );

?>
