<?php

//大枠の処理 POSTに値がなければ、input.htmlへいく。
if (!empty($_POST)){

    exit;

} //if (!empty($_POST))


//一番最初の読み込み
include_once("input.html");