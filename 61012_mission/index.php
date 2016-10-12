<?php

//最初の処理。postの値が何もなければ、inputを読む
if (empty($_POST)) {
    include_once("input.html");
}

$form = $_POST;
$error = array();
