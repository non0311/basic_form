<?php

//最初の処理。postの値が何もなければ、inputを読む
if (empty($_POST)) {
    include_once("input.html");
}

$form = $_POST;
$error = array();

if (empty($form['tanka'])) {
    $error['tanka']="必須";
}

if (empty($form['int'])) {
    $error['int']="必須";
}

if (empty($form['date'])) {
    $error['date']="必須";
}


