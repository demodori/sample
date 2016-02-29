<?php
/**
 * お問い合わせ送信処理ページ
 * 中間ファイルの為、htmkはありません。
 *
 * @package    limex
 * @subpackage order
 * @version    2015/10/15 1.0
 * @author     yutaka.sudo
 */

// お問い合わせクラスロード
require_once __DIR__ . "/include/Order.class.php";

// 会員情報も渡す
$order = new Order();

// 事前チェック
if (empty($_POST) || !$order->issetData() || Order::getSession('token') !== $_POST['token']) {
    Order::doSystemErr();
}

// トークン削除
unset($_SESSION[Order::$sessPagePrefix]['token']);

// 送信
$status = $order->order();


// 成功時完了画面へ
if ($status['code'] === 0) {
    // sessionと一時ファイルの削除
    $tmpdir = $_SERVER['DOCUMENT_ROOT'] . Order::ORDER_DIR . Order::ORDER_FILE_TMP_DIR;
    $updir = $_SERVER['DOCUMENT_ROOT'] . Order::ORDER_DIR . Order::ORDER_FILE_DIR;
    unlink($tmpdir . '/' . Order::getSession('post_data.file_id') . '_' . Order::getSession('post_data.meishi_data'));
    exec('find '. $tmpdir . ' -mtime +3 -exec rm -f {} \;');
    exec('find '. $updir . ' -mtime +7 -exec rm -rf {} \;');
    Order::deleteSession();
    Order::location(Order::ORDER_DIR . '/complete.php?order_id=' . $order->getOrderId());
} else {
    Order::setSession('errmsg', array('system' => Order::SYSTEM_ERR_MSG_01 . '(' . $status['code']. ')'));
    Order::location(Order::ORDER_DIR . Order::ORDER_TOP . '?status=err', true);
}

