<?php
setcookie('user', "", time(), '/');
setcookie('logged', "", time(), '/');
header("Location:index.php?r=logout");
?>