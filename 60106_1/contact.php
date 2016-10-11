<?php
// このファイルは河村さんからもらった参考ファイル
include('Mail.php');
include('Mail/mime.php');
include_once("define.php");

$form = $error = array();

$err_require = '入力必須項目です';
$err_select  = '選択必須項目です';
$err_format  = '正しく入力してください';
$err_number  = '数値を入力してください';

$contact_type = array(
    'サービスに関するお問い合わせ',
    'お見積りについて',
    '採用への応募',
);

/* input */
if (empty($_POST)) {
    include_once('./contact.html');
    exit;
}

/* confirm */

// trim
foreach ($_POST as $k => $v) {
    $form[$k] = trim($v);
}

// check
if (empty($form['company'])) {
    $error['company'] = $err_require;
}

if (empty($form['person'])) {
    $error['person'] = $err_require;
}

if (empty($form['mail'])) {
    $error['mail'] = $err_require;

} else if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $form['mail'])) {
    $error['mail'] = $err_format;
}

if (empty($form['tel'])) {
    $error['tel'] = $err_require;

} else if (!is_number($form['tel'])) {
    $error['tel'] = $err_format;
}

if (!empty($form['zip'])) {
    if ((strlen($form['zip']) !== 7) || !is_number($form['zip'])) {
        $error['zip'] = $err_format;
    }
}

if (!empty($error) || !empty($form['back'])) {
    include_once('./contact.html');
    exit;
}

if (empty($form['send'])) {
    include_once('./contact_confirm.html');
    exit;
}

/* complete */

// mail
send($form);

header("Location: http://◯◯.co.jp/contact_complete.html");
exit;

/* function */

function e($data) {
    echo $data;
}

function h($data) {
    return htmlspecialchars($data);
}

function is_number($num) {
    return preg_match('/^[0-9]+$/', $num);
}

function pr($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function send($data) {
    global $contact_type;
    global $tel;

    $zip     = !empty($data['zip']) ? $data['zip'] : '未記入';
    $address = !empty($data['address']) ? $data['address'] : '未記入';
    $content = !empty($data['content']) ? $data['content'] : '未記入';

$body = <<< EOF
{$data['company']}
{$data['person']}　様

株式会社◯◯へのお問い合わせ、誠にありがとうございます。
下記、お問い合わせを承りました。

==================================================
お問合内容： {$contact_type[$data['contact_type']]}
御社名　　： {$data['company']}
氏名　　　： {$data['person']}
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
    $hdrs = $mime->headers(array('From' => $from, 'To' => $to, 'Subject' => $subject, 'Bcc' => $from));

    $mail =& Mail::factory('mail');

    if (!$mail->send($to, $hdrs, $text)) {
        return false;
    }

    return true;
}
