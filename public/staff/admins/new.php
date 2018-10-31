<?php

require_once ('../../../private/initialize.php');

require_login();

if (is_post_request())
{
    //handle form values sent by new.php
    $admin = [];
    $admin['first_name'] = $_POST['first_name'] ?? '';
    $admin['last_name'] = $_POST['last_name'] ?? '';
    $admin['email'] = $_POST['email'] ?? '';
    $admin['username'] = $_POST['username'] ?? '';
    $admin['password'] = $_POST['password'] ?? '';
    $admin['confirm_password'] = $_POST['confirm_password'] ?? '';

    $result = insert_admin($admin);
    if ($result === true)
    {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The admin was created successfully.";
        redirect_to(url_for('/staff/admins/show.php?id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {
    // display blank form
    $admin = [];
    $admin['first_name'] = '';
    $admin['last_name'] = '';
    $admin['email'] = '';
    $admin['username'] = '';
    $admin['password'] = '';
    $admin['confirm_password'] = '';
}

$page_title = 'Create Admin';
include (SHARED_PATH . '/staff_header.php');
?>
    <div id="content">
        <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>
        <div class="admin new">
            <h1>Create Admin</h1>

            <?php echo display_errors($errors) ?>

            <form action="<?php echo url_for('/staff/admins/new.php')?>" method="post">
                <div>
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" value="<?php echo h($admin['first_name'])?>">
                </div>
                <div>
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo h($admin['last_name'])?>">
                </div>
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?php echo h($admin['username'])?>">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?php echo h($admin['email'])?>">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" value="">
                </div>
                <div>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" value="">
                </div>
                <p>
                    Password should be at least 12 characters and include at least one uppercase letter, lowercase letter, number, and symbol.
                </p>
                <br />
                <div id="operations">
                    <input type="submit" name="submit" value="Create Admin">
                </div>
            </form>
        </div>
    </div>


<?php
include (SHARED_PATH . '/staff_footer.php');