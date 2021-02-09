<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?php \Component\AssetsBasic::getCss() ?>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h4>Header</h4>
<p>Вы вошли как <?= Component\App::$user->name ?></p>
<?php if(Component\App::$user->id > 0): ?>
<a href="/logout"><button>Выйти</button></a>
<?php else: ?>
<a href="/login"><button>Вход</button></a>
<?php endif; ?>
<br>