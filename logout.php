<?php
require_once 'includes/auth.php';

logout_user();

header('Location: index.php?logged_out=1');
exit;
?>


