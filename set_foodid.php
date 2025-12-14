<?php
session_start();

if (isset($_POST['foodid'])) {
    $_SESSION['foodid'] = $_POST['foodid'];
    echo "Food ID set in session.";
} else {
    echo "Food ID not set.";
}
?>
