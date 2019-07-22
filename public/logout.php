<?php setcookie('user', "", time(), '/');
header("Location:index.php?r=logout");
?>