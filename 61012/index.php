<?php

//大枠の処理 POSTに値がなければ、input.htmlへいく。
if (!empty($_POST)){

    $form = $_POST;
    $error = array();

    if (empty($form['company'])) {
        $error['company']="必須";
    }

    if (empty($form['tel'])) {
        $error['tel']="必須";
    }

    if (empty($form['mail'])) {
        $error['mail']="必須";
    }


    // エラーがあったら
    // もう一度inputへいきexitする
    if (!empty($error)) {
        include_once("input.html");
        exit;
    }
    //エラーがなければ確認画面へ遷移
    include_once("confirm.html");
    exit;

} //if (!empty($_POST))


//一番最初の読み込み
include_once("input.html");