<?php
/**
 * お問い合わせ用configファイル
 * 開発と本番で値が異なるものを列挙。
 *
 * @package    sample
 * @subpackage Order
 * @version    2016/02/29 1.0
 * @author     yutaka.sudo
 */

// エラーレポーティング設定
ini_set('error_reporting', E_ALL & ~E_STRICT);

// SSL
define('SSL', 'http');

// 宛先メールアドレス
define('ADMIN_MAIL_TO', 'sudo@nagisalabs.org');
?>