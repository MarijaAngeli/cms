<?php

require_once('../../../private/initialize.php');

require_login();

if (is_post_request()){

    $subject = [];
    $subject['menu_name'] = $_POST['menu_name'] ?? '';
    $subject['position'] = $_POST['position'] ?? '';
    $subject['visible'] = $_POST['visible'] ?? '';

    $result = insert_subject($subject);
    if ($result === true)
    {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The subject was created successfully.";
        redirect_to(url_for('/staff/subjects/show.php?id=' . $new_id));
    } else {
        $errors = $result;
    }

} else
    {
        //display the blank form
    }

//count all subjects
$subject_set = find_all_subjects();
$subject_count = mysqli_num_rows($subject_set) + 1;
mysqli_free_result($subject_set);

$subject = [];
$subject['position'] = $subject_count;

$page_title = 'Create Subject';
include (SHARED_PATH . '/staff_header.php');
?>
<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>
    <div class="subject new">
        <h1>Create subject</h1>

        <?php echo display_errors($errors);?>

        <form action="<?php echo url_for('/staff/subjects/new.php')?>" method="post">
            <div>
                <label for="menu-name">Menu Name</label>
                <input type="text" name="menu_name" value="">
            </div>
            <div>
                <label for="position">Position</label>
                    <select name="position">
                        <?php
                        for ($i=1; $i<=$subject_count; $i++)
                        {
                            echo "<option value=\"{$i}\"";
                            if ($subject['position'] == $i)
                            {
                                echo " selected";
                            }
                            echo ">{$i}</option>";
                        }
                        ?>
                    </select>
            </div>
            <div>
                <label for="visible">Visible</label>
                <input type="hidden" name="visible" value="0">
                <input type="checkbox" name="visible" value="1">
            </div>
            <div id="operations">
                <input type="submit" name="submit" value="Create Subject">
            </div>
        </form>
    </div>
</div>


<?php
include (SHARED_PATH . '/staff_footer.php');