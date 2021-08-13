<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= config("app.name").(isset($title) ? ' - '.$title : ''); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-default.min.css">
    <link href="/fa/css/all.css" rel="stylesheet"> <!--load all styles -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php
if (can('view.menu')):
include("_menu.php");

if (hasFlash()):?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php
                include("_messages.php"); ?>
            </div>
        </div>
    </div>
<?php
endif; ?>
<?php
endif;

