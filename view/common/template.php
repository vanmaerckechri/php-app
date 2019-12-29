<?php 
require_once('header.php');
require_once('footer.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page['title'] ?></title>
</head>
<body>
    <?= $page['header'] ?>
    <?= $page['content'] ?>
    <?= $page['footer'] ?>
</body>
</html>