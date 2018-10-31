<?php

require_once('../../../private/initialize.php');

require_login();

if (!isset($_GET['id']))
{
    redirect_to(url_for('/staff/subjects/index.php'));
}
// always have default values
$id = $_GET['id'];

if (is_post_request()){

    $subject = [];
    $subject['id'] = $id;
    $subject['menu_name'] = $_POST['menu_name'] ?? '';
    $subject['position'] = $_POST['position'] ?? '';
    $subject['visible'] = $_POST['visible'] ?? '';

    $result = update_subject($subject);
    if ($result === true)
    {
        $_SESSION['message'] = "The subject was updated successfully.";
        redirect_to(url_for('staff/subjects/show.php?id=' . $id));
    } else {
        $errors = $result;
        //var_dump($errors);
    }


}else {
    $subject = find_subject_by_id($id);
}

//count all subjects
$subject_set = find_all_subjects();
$subject_count = mysqli_num_rows($subject_set);
mysqli_free_result($subject_set);

$page_title = 'Edit Subject';
include (SHARED_PATH . '/staff_header.php');
?>
    <div id="content">
        <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>
        <div class="subject new">
            <h1>Edit subject</h1>
            <?php echo display_errors($errors); ?>
            <form action="<?php echo url_for('staff/subjects/edit.php?id=' . h(u($id)))?>" method="post">
                <div>
                    <label for="menu-name">Menu Name</label>
                    <input type="text") name="menu_name" value="<?php echo h($subject['menu_name']); ?>">
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
                    <input type="checkbox" name="visible" value="1"<?php if ($subject['visible'] == "1") { echo " checked";} ?>>
                </div>
                <div id="operations">
                    <input type="submit" name="submit" value="Edit Subject">
                </div>
            </form>
        </div>
    </div>


<?php
include (SHARED_PATH . '/staff_footer.php');