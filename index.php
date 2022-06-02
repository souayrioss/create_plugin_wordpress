<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">


<?php

/**
 * Plugin Name: contact us plugin
 */


add_shortcode('saida', function () {
    send_email();
    Form();
});

function Form()
{
 ?>
  <div class="justify-content-center w-50">
    <form action=" <?= esc_url($_SERVER['REQUEST_URI']) ?> " method="post">
        <p>
            Name : <br />
            <input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value=" <?= isset($_POST["cf-name"]) ? esc_attr($_POST["cf-name"]) : '' ?>" size="40" require />
        </p>
        <p>
            Email : <br />
            <input type="email" name="cf-email" value="<?= isset($_POST["cf-email"]) ? esc_attr($_POST["cf-email"]) : '' ?>" size="40" require/>
        </p>
        <p>
            Subject : <br />
            <input type="text" name="cf-subject" pattern=".+" value="<?= isset($_POST["cf-subject"]) ? esc_attr($_POST["cf-subject"]) : '' ?> " size="40" require/>
        </p>
        <p>
            Message : <br />
            <textarea rows="10" cols="35" name="cf-message" require><?= isset($_POST["cf-message"]) ? esc_attr($_POST["cf-message"]) : '' ?> </textarea>
        </p>
        <p>
            <input type="submit" name="cf-submitted" value="Send" class="w-100 align-center"/>
        </p>
    </form>
  </div>
    <?php
}
function send_email()
{
    if (isset($_POST['cf-submitted'])) {
        $name    = $_POST["cf-name"];
        $email   = $_POST["cf-email"];
        $subject = $_POST["cf-subject"];
        $message = $_POST["cf-message"];
        InsertData($email, $name, $message, $subject); ?>
        <div class="alert alert-success fw-bold w-50">
            Thanks for contacting me, expect a response soon. 
        </div>
    <?php
    }
}

add_action('admin_menu', function () {
    add_menu_page('Messages','contact form','edit_posts','contact_form','callback_fct','dashicons-media-spreadsheet');  
    add_submenu_page( 'contact_form','Child Menu','Child Menu','manage_options','Custom_sub_menu','list_emails');
});

function callback_fct(){  
    echo '<div class="d-flex justify-content-center">';
    echo '<h1>Hello</h1>';
    echo '</div>';
}

function list_emails()
{
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM `wp_plugin` ;");
    ?>
    <div class="d-flex justify-content-center align-item-center">
      <h1>Emails</h1>
    </div>  
    <table class="table">
        <?php if (count($results) < 1) { ?>
            <div class="d-flex justify-content-center align-item-center">
                <div class="alert alert-warning w-50 fw-bold text-center">
                        Mailbox is empty!
                </div>
            </div>
        <?php } else { ?>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Message</th>
            </tr>
        <?php }
        foreach ($results as $entry) { ?>
            <tr>
                <td><?= $entry->email ?></td>
                <td><?= $entry->name ?></td>
                <td><?= $entry->subject ?></td>
                <td><?= $entry->message ?></td>
            </tr>
        <?php } ?>

    </table>
 <?php
}


add_action('activate_contact_plugin/index.php', function () {
    global $wpdb;
    $wpdb->query("CREATE TABLE IF NOT EXISTS `wp_plugin` ( `id` INT NOT NULL AUTO_INCREMENT , `email` TEXT NOT NULL , `name` TEXT NOT NULL , `subject` TEXT NOT NULL , `message` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
});

add_action('deactivate_contact_plugin/index.php', function () {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS `wp_plugin`;");
});

function InsertData($email, $name, $message, $subject)
{
    global $wpdb;
    $wpdb->query("INSERT INTO `wp_plugin` (`id`, `email`, `name`, `subject`, `message`) VALUES (NULL, '{$email}', '{$name}', '{$subject}', '{$subject}');");
}