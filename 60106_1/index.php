<?php
include('Mail.php');
include('Mail/mime.php');
//print_r($_REQUEST);
print_r($_POST);

if (!empty($_POST)) {
	$form = $_POST;

	$error = array();
	if (empty($form['company'])) {
		$error['company'] = "必須";
	}

	if (empty($form['user_name'])) {
		$error['user_name'] = "必須";
	}


	if (empty($form['mail'])) {
		$error['mail'] = "必須";
	}


	if (empty($form['tel'])) {
		$error['tel'] = "必須";
	}
	else if (!preg_match('/^[0-9]+$/', $form['tel'])){
		$error['tel'] = "数字にしてください";
	}


	if (!empty($form['yubin'])) {
		if(!preg_match("/^\d{7}$/",$form['yubin'])){
			$error['yubin'] = "7桁の半角数字を入力して下さい";
		}
	}

	//print_r($error);
	// エラーがあったら　or　戻るボタンを押したら　もう一度inputへ
	if (!empty($error) || !empty($form['back'])) {
		include_once("input.html");
		exit;
	}

	// 確認画面
	if (empty($form['send'])) {
		include_once("confirm.html");
		exit;
	}

	// メール送信
	if (send($form) === false) {
		echo "mail error";
		exit;
	}

	// 完了画面
	header("Location: http://54.238.248.46/sample_form/complete.html");
	exit;
}

include_once("input.html");


function send($data) {
    global $contact_type;
    global $tel;

    $zip     = !empty($data['yubin']) ? $data['yubin'] : '未記入';
    $address = !empty($data['stay']) ? $data['stay'] : '未記入';
    $content = !empty($data['contact']) ? $data['contact'] : '未記入';
//お問合内容： {$contact_type[$data['contact_type']]}
$body = <<< EOF
{$data['company']}
{$data['user_name']}　様

株式会社◯◯へのお問い合わせ、誠にありがとうございます。
下記、お問い合わせを承りました。

==================================================

御社名　　： {$data['company']}
氏名　　　： {$data['user_name']}
メール　　： {$data['mail']}
電話番号　： {$data['tel']}
郵便番号　： {$zip}
住所　　　： {$address}
お問合詳細：
{$content}
==================================================

後ほど担当からご連絡させていただきます。

尚、2～3日しても、連絡が無い場合は
何かの手違いの可能性もございますので、お手数ではございますが、
以下の電話番号・またはメールアドレス宛にご連絡頂けます様
よろしくお願い申し上げます。


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
株式会社◯◯
HP： http://◯◯.co.jp
Mail： info@◯◯.co.jp
Tel： {$tel}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
EOF;
//echo $body;

    $from    = 'info@◯◯.co.jp'; 
    $to      = $data['mail'];
    $subject = '【◯◯】お問い合わせを承りました';
    $params  = array('text_charset' => 'iso-2022-jp');

    // encode
    $subject = mb_encode_mimeheader($subject, 'ISO-2022-JP');
    $body    = mb_convert_encoding($body, 'JIS');

    $mime = new Mail_mime("\n");
    $mime->setTXTBody($body);

    $text = $mime->get($params);
    
    // BCCで$fromへも送信する
    $hdrs = $mime->headers(array('From' => $from, 'To' => $to, 'Subject' => $subject));

    $mail =& Mail::factory('mail');

//echo $to;exit;

    if (!$mail->send($to, $hdrs, $text)) {
        return false;
    }

    return true;
}


?>
