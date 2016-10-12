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


    // エラーがあったら or 戻るボタンを押したら
    // もう一度inputへいきexitする
    if (!empty($error) || !empty($form['back']) ) {
        include_once("input.html");
        exit;
    }
    //上記if分で、エラーがなければ or 送信ボタンを押してなかったら
    //確認画面へ遷移
    if (empty($form['send'])) {
        include_once("confirm.html");
        exit;
    }

    //確認画面から送信ボタンを押したら、正常終了の証
    echo "send";
    exit;

} //if (!empty($_POST))


//一番最初の読み込み
include_once("input.html");