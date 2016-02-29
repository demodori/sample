<?php
/**
 * お問い合わせユーザ用メールテンプレート
 */

// 件名
$user_subject = '[LIMEX] ご注文確定';

$user_body = <<<EOF
{$this->getData('meishi_name')} 様

この度は名刺をご注文いただきまして、ありがとうございます。
下記ご注文を承りました。

担当者より改めてご連絡させていただきますので、今しばらくお待ちください。

[ご注文番号]
{$indent}{$this->orderId}

[お名前]
{$indent}{$this->getData('meishi_name')}（{$this->getData('meishi_kana')}）

[ご住所]
{$indent}〒{$this->getData('meishi_post1')}-{$this->getData('meishi_post2')}
{$indent}{$this->getData('meishi_prefecture')}{$this->getData('meishi_addr')}

[メールアドレス]
{$indent}{$this->getData('meishi_email')}

[お電話]
{$indent}{$this->getData('meishi_tel')}

[ご注文内容]
{$indent}セット数：{$this->getData('meishi_setnum')} セット
{$indent}色数　　：{$this->getData('meishi_colornum')} 色

EOF;

// 納品先指定
if ($this->getData('nouhin_name')) {
	$user_body .= <<<EOF
----------------------------------------------------------
◆納品先
----------------------------------------------------------
[お名前(ふりがな)]
{$indent}{$this->getData('nouhin_name')}（{$this->getData('nouhin_kana')}）

[住所]
{$indent}〒{$this->getData('nouhin_post1')}-{$this->getData('nouhin_post2')}
{$indent}{$this->getData('nouhin_prefecture')}{$this->getData('nouhin_addr')}

[電話番号]
{$indent}{$this->getData('nouhin_tel')}

EOF;
}

// 請求先指定
if ($this->getData('seikyu_name')) {
	$user_body .= <<<EOF
----------------------------------------------------------
◆請求先
----------------------------------------------------------
[お名前(ふりがな)]
{$indent}{$this->getData('seikyu_name')}（{$this->getData('seikyu_kana')}）

[住所]
{$indent}〒{$this->getData('seikyu_post1')}-{$this->getData('seikyu_post2')}
{$indent}{$this->getData('seikyu_prefecture')}{$this->getData('seikyu_addr')}

[電話番号]
{$indent}{$this->getData('seikyu_tel')}

EOF;
}

$user_body .= <<<EOF
──────────────────────────────────────────────────
□ このメール配信について
──────────────────────────────────────────────────
このメールは、配信専用システムよりお送りしております。
直接ご返信いただいてもお答えいたしかねますのでご了承ください。
お問い合わせは、お手数ですが下記連絡先までお願いいたします。

──────────────────────────────────────────────────
□お問い合わせ先
──────────────────────────────────────────────────
LIMEX
東京都荒川区東日暮里1丁目1番1号
日暮里駅前test1F
TEL: 03-xxxx-xxxx（担当: テストさん）
MAIL: xxxx@xxxxx.com

========================================================

オーダーメイド名刺
{$this->web_index}

========================================================
EOF;
