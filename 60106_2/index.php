<?php
print_r($_POST);
var_dump($_POST);


// .:/Applications/MAMP/bin/php/php5.6.10/lib/php;
// .:/Applications/MAMP/htdocs/pear
// phpinfo();



// 大枠で、POSTに値が入っていたらの処理
// つまり、最初の読み込みでは飛ばされて、input.htmlへいく
if (!empty($_POST)){

	$form = $_POST;

	$error = array();

	if (empty($form['company'])) {
		$error['company']="必須";
	}

	if (empty($form['user_name'])) {
		$error['user_name']="必須";
	}

	if (empty($form['mail'])) {
		$error['mail']="必須";
	}

	if (empty($form['tel'])) {
		$error['tel']="必須";
	}
	else if (!preg_match('/^[0-9]+$/', $form['tel'])){
	$error['tel'] = "数字にしてください";
	}

	if (!empty($form['yubin'])) {
		if(!preg_match("/^\d{7}$/",$form['yubin'])){
			$error['yubin'] = "7桁の半角数字を入力して下さい";
		}
	}

	// エラーがあったら or 戻るボタンを押したら
	// もう一度inputへいきexitする
	if (!empty($error) || !empty($form['back'])) {
	include_once("input.html");
	exit;
	}

	// 上の文でエラーがなかったら or 送信ボタンを押してなかったら
	// confirmへいくのだ
	if (empty($form['send'])) {
		include_once("confirm.html");
		exit;
	}


echo "send";
exit;


} //if (!empty($_POST)){}



//一番最初の読み込み
include_once("input.html");


?>