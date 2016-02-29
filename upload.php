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

// 対象エレメント名
$elementName = 'meishi_data';

$code = '';
$msg = '';
$maxFileSize = Order::$maxFileSize * 1024 * 1024 * 1024;

// php.iniのpost_max_size, upload_max_filesizeを超えた場合
if (empty($_POST) && empty($_GET) && empty($_FILES)) {
    $code = Order::UP_ERR_SYSTEM_LIMIT;
    $msg = str_replace('::SIZE::', Order::$maxFileSize, Order::UP_ERR_MSG_LIMIT);
}

if ($_FILES && ($_FILES[$elementName]['name']) !== '') {
    if ($_FILES[$elementName]['error'] === 0) {
        // 許可MIME-TYPEチェック
        if (! Order::checkFileType($_FILES[$elementName]['tmp_name'])) {
            $code = Order::UP_ERR_MIME_TYPE;
            $msg = Order::UP_ERR_MSG_MIME;
        // MAX SIZE
        } elseif (filesize($_FILES[$elementName]['tmp_name']) > $maxFileSize) {
            $code = Order::UP_ERR_MAX_SIXE;
            $msg = str_replace('::SIZE::', Order::$maxFileSize, Order::UP_ERR_MSG_LIMIT);
        } else {
            // コピー先ファイル名
            $fileId = basename($_FILES[$elementName]['tmp_name']);
            $fileName = $_FILES[$elementName]['name'];

            // 注文確定まで一時ディレクトリにコピー
            if (copy($_FILES[$elementName]['tmp_name'], __DIR__ . Order::ORDER_FILE_TMP_DIR . '/' . $fileId . '_' . $fileName)) {
                // upload完了。ファイルIDとファイル名保管
                Order::setSession('post_data', array(
                    'file_id' => $fileId,
                    $elementName => $fileName,
                ));
                $code = Order::UP_SUCCESS;
                $msg = Order::UP_MSG_SUCCESS;
                $status['filename'] = $fileName;
            // コピー失敗
            } else {
                $code = Order::UP_ERR_TEMP_COPY;
                $msg = Order::UP_ERR_MSG_COMMON . '(' . Order::UP_ERR_TEMP_COPY . ')';
            }
        }
    // FILES error
    } else {
        $code = Order::UP_ERR_FILES;
        $msg = Order::UP_ERR_MSG_COMMON . '(' . Order::UP_ERR_FILES . ')';
    }
}

// json返却
$status['code'] = $code;
$status['msg'] = $msg;

header('Content-type: text/html');
echo json_encode($status);
