<?php

require_once ('../../../private/initialize.php');

require_login();

if (is_post_request())
{
    //handle form values sent by new.php
    $page = [];
    $page['menu_name'] = $_POST['menu_name'] ?? '';
    $page['position'] = $_POST['position'] ?? '';
    $page['visible'] = $_POST['visible'] ?? '';
    $page['subject_id'] = $_POST['subject_id'] ?? '';
    $page['content'] = $_POST['content'] ?? '';

    $result = insert_page($page);
    if ($result === true)
    {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The page was created successfully.";
        redirect_to(url_for('/staff/pages/show.php?id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {
    $page = [];
    $page['menu_name'] = '';
    $page['position'] = '';
    $page['visible'] = '';
    $page['subject_id'] = $_GET['subject_id'] ?? '1';
    $page['content'] = '';
}

$page_count = count_pages_by_subject_id($page['subject_id']) + 1;

$page_title = 'Create Pages';
include (SHARED_PATH . '/staff_header.php');
?>
<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/subjects/show.php?id=' . h(u($page['subject_id']))); ?>">
        &laquo; Back to Subject Page
    </a>
    <div class="subject new">
        <h1>Create page</h1>

        <?php echo display_errors($errors) ?>

        <form action="<?php echo url_for('/staff/pages/new.php')?>" method="post">
            <div>
                <label for="menu-name">Menu Name</label>
                <input type="text" name="menu_name" value="<?php echo h($page['menu_name'])?>">
            </div>
            <div>
                <label for="position">Position</label>
                <select name="position">
                    <?php
                        for ($i=1; $i<=$page_count; $i++) {
                            echo "<option value=\"{$i}\"";

                            if ($page['position'] == $i) {
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
            <div>
                <label for="subject_id">Subject</label>
                <select name="subject_id">
                    <?php
                    $subject_set = find_all_subjects();
                    while ($subject = mysqli_fetch_assoc($subject_set))
                    {
                        echo "<option value=\"" . h($subject['id']) . "\"";
                        if ($page['subject_id'] == $subject['id'])
                        {
                            echo " selected";
                        }
                        echo ">" . h($subject['menu_name']) . "</option>";
                    }
                    mysqli_free_result($subject_set);
                    ?>
                </select>
            </div>
            <br>
            <div>
                <dt>Content</dt>
                <textarea name="content" rows="10" cols="100"></textarea>
            </div>
            <div id="operations">
                <input type="submit" name="submit" value="Create Page">
            </div>
        </form>
    </div>
</div>


<?php
include (SHARED_PATH . '/staff_footer.php');