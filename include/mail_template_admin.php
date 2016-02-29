<?php
/**
 * お問い合わせ管理者用メールテンプレート
 */

// 件名
$admin_subject = '[LIMEX] 注文確定(admin)';

// 本文
$admin_body = <<<EOF
以下の注文が入りましたので、対応をお願いします。

[注文番号]
{$indent}{$this->orderId}

----------------------------------------------------------
◆名刺情報
----------------------------------------------------------
[お名前(ふりがな)]
{$indent}{$this->getData('meishi_name')}({$this->getData('meishi_kana')})

[住所]
{$indent}〒{$this->getData('meishi_post1')}-{$this->getData('meishi_post2')}
{$indent}{$this->getData('meishi_prefecture')}{$this->getData('meishi_addr')}

[電話番号]
{$indent}{$this->getData('meishi_tel')}

[メールアドレス]
{$indent}{$this->getData('meishi_email')}

[セット数]
{$indent}{$this->getData('meishi_setnum')} セット

[色数]
{$indent}{$this->getData('meishi_colornum')} 色

EOF;

// 納品先指定
if ($this->getData('nouhin_name')) {
	$admin_body .= <<<EOF
----------------------------------------------------------
◆納品先
----------------------------------------------------------
[お名前(ふりがな)]
{$indent}{$this->getData('nouhin_name')}({$this->getData('nouhin_kana')})

[住所]
{$indent}〒{$this->getData('nouhin_post1')}-{$this->getData('nouhin_post2')}
{$indent}{$this->getData('nouhin_prefecture')}{$this->getData('nouhin_addr')}

[電話番号]
{$indent}{$this->getData('nouhin_tel')}

EOF;
}

// 請求先指定
if ($this->getData('seikyu_name')) {
	$admin_body .= <<<EOF
----------------------------------------------------------
◆請求先
----------------------------------------------------------
[お名前(ふりがな)]
{$indent}{$this->getData('seikyu_name')}({$this->getData('seikyu_kana')})

[住所]
{$indent}〒{$this->getData('seikyu_post1')}-{$this->getData('seikyu_post2')}
{$indent}{$this->getData('seikyu_prefecture')}{$this->getData('seikyu_addr')}

[電話番号]
{$indent}{$this->getData('seikyu_tel')}

EOF;
}
