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

// エラーがある　or  戻るボタン押してたら  もう一度inputへ
if (!empty($error)  || !empty($form['back']) ) {
    include_once("input.html");
    exit;
}

//入力画面からエラーがない or 確認画面で送信ボタンを押してなかったら
//確認画面へ遷移
if (empty($form['send'])) {
    include_once("confirm.html");
    exit;
}

//確認画面から送信ボタンを押したら完了画面へ
include_once("complete.html");
exit;

