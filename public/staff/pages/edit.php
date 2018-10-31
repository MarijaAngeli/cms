<?php

require_once ('../../../private/initialize.php');

require_login();

if (!isset($_GET['id']))
{
    redirect_to(url_for('/staff/pages/index.php'));
}
$id = $_GET['id'];

    if (is_post_request())
    {
        $page = [];
        $page['id'] = $id;
        $page['menu_name'] = $_POST['menu_name'] ?? '';
        $page['position'] = $_POST['position'] ?? '';
        $page['visible'] = $_POST['visible'] ?? '';
        $page['subject_id'] = $_POST['subject_id'] ?? '';
        $page['content'] = $_POST['content'] ?? '';

        $result = update_page($page);
        if ($result === true)
        {
            $_SESSION['message'] = "The page was updated successfully.";
            redirect_to(url_for('/staff/pages/show.php?id=' . $id));
        }else {
            $errors = $result;
        }

    } else {
        $page = find_page_by_id($id);
    }

$page_count = count_pages_by_subject_id($page['subject_id']);


    $page_title = 'Edit Page';
    include (SHARED_PATH . '/staff_header.php');
    ?>
    <div id="content">
        <a class="back-link" href="<?php echo url_for('/staff/subjects/show.php?id=' . h(u($page['subject_id']))); ?>">
            &laquo; Back to Subject Page
        </a>
        <div class="page edit">
            <h1>Edit page</h1>

            <?php echo display_errors($errors); ?>
            <form action="<?php echo url_for('staff/pages/edit.php?id=' . h(u($id)))?>" method="post">
                <div>
                    <label for="menu-name">Menu Name</label>
                    <input type="text" name="menu_name" value="<?php echo h($page['menu_name']); ?>">
                </div>
                <div>
                    <label for="position">Position</label>
                    <select name="position">
                        <?php
                            for ($i=1; $i<=$page_count; $i++)
                            {
                                echo "<option value=\"{$i}\"";
                                if ($page['position'] == $i)
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
                    <input type="checkbox" name="visible" value="1"<?php if ($page['visible'] == "1") { echo " checked";} ?>>
                </div>
                <div>
                    <label for="subject">Subject</label>
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
                        ?>
                    </select>
                </div>
                <div>
                    <dt>Content</dt>
                    <dd>
                        <textarea name="content" cols="60" rows="10"><?php echo h($page['content']) ?></textarea>
                    </dd>
                </div>
                <div id="operations">
                    <input type="submit" name="submit" value="Edit Subject">
                </div>
            </form>
        </div>
    </div>


<?php
include (SHARED_PATH . '/staff_footer.php');
