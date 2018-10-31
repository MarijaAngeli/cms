<?php

//is_blank('abcd')
// * validate data presence
// *  uses trim() so empty spaces don't count
// * uses === to avoid false positives
// * better than empty() which considers "0" to be empty
function is_blank($value)
{
    return !isset($value) || trim($value) === '';
}

// has_presence('abcd')
// * validate data presence
// * reverse of is_blank()
// * I prefer validation names with "has"
function has_presence($value)
{
    return !is_blank($value);
}

// has length_greater_then('abcd',3)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_greater_then($value, $min)
{
    $length = strlen($value);
    return $length > $min;
}

// has length_less_then('abcd',5)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_less_then($value, $max)
{
    $length = strlen($value);
    return $length < $max;
}

// has length_exactly('abcd',4)
// * validate string length
// * spaces count towards length
// * use trim() if spaces should not count
function has_length_exactly($value, $exact)
{
    $length = strlen($value);
    return $length == $exact;
}

// has length('abcd',['min' => 3, 'max' => 5])
// * validate string length
// * combines functions _greater_then, _less_then, _exactly
// * spaces count towards length
// * use trim() if spaces should not count
function has_length($value, $options)
{
    if (isset($options['min']) && !has_length_greater_then($value, $options['min']-1))
    {
        return false;
    } elseif (isset($options['max']) && !has_length_less_then($value, $options['max']+1))
    {
        return false;
    }elseif (isset($options['exact']) && !has_length_exactly($value, $options['exact']))
    {
        return false;
    } else {
        return true;
    }
}

// has_inclusion_of(5, [1,3,5,7,9])
// * validate inclusion in a set
function has_inclusion($value, $set)
{
    return in_array($value, $set);
}

// has_exclusion_of(5, [1,3,5,7,9])
// * validate exclusion in a set
function has_exclusion($value, $set)
{
    return !in_array($value, $set);
}

//has_string('nobody@nowhere.com', '.com')
// * validate inclusion of character(s)
// * strpos returns string start position or false
// * uses !== to prevent position 0 from beong considered false
// * strpos is faster then preg_match()
function has_string($value, $required_string)
{
    return strpos($value, $required_string) !== false;
}

// has_valid_email_format('nobody@nowhere.com')
// * validate correct format for email addresses
// * format: [chars]@[chars].[2+ letters]
// * preg_metch is helpful, uses a regular expression
// return 1 for a match, 0 for no match
function has_valid_email_format($value)
{
    $emil_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
    return preg_match($emil_regex, $value) === 1;
}

function has_unique_page_menu_name($menu_name, $current_id = "0")
{
    global $db;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE menu_name='" . db_escape($db,$menu_name) . "' ";
    $sql .= "AND id != '" . db_escape($db,$current_id) . "'";

    $page_set = mysqli_query($db, $sql);
    $page_count = mysqli_num_rows($page_set);
    mysqli_free_result($page_set);

    return $page_count === 0;
}
// has unique username(johndoepublic)
// validates uniqnuess of admins.username
// for new record provide only the username
// for existing records, provide current ID as second argument
// has unique username('johndoepublic, 4)
function has_unique_username($username, $current_id = "0")
{
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $result = mysqli_query($db, $sql);
    $admin_count = mysqli_num_rows($result);
    mysqli_free_result($result);
    return $admin_count === 0;
}
