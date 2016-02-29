<?php
/**
 * 注文処理クラス
 *
 * @package    sample
 * @subpackage order
 * @version    2016/02/29 1.0
 * @author     yutaka.sudo
 */


// カレントの言語を日本語に設定する
mb_language("ja");

// 内部文字エンコードを設定する
mb_internal_encoding("UTF-8");


// config読み込み
require_once dirname(__FILE__) . "/config.php";

// フォーム情報読み込み
require_once dirname(__FILE__) . "/form_data.php";

// 関連クラス読み込み
require_once dirname(__FILE__) . "/Validate.class.php";


// sessionスタート
session_start();

// session初期化
if (! isset($_SESSION[Order::$sessPagePrefix])) {
    $_SESSION = array(Order::$sessPagePrefix => '');
}

/**
 * お問い合わせ用 controller兼modelクラス
 */
class Order
{
    /**
     * お問い合わせディレクトリ
     */
    const ORDER_DIR = '/order';

    /**
     * お問い合わせTOP
     */
    const ORDER_TOP = '/';

    /**
     * 注文確定までの入稿データ一時ディレクトリ
     */
    const ORDER_FILE_TMP_DIR = '/upload_tmp';

    /**
     * 注文確定時の入稿データディレクトリ
     */
    const ORDER_FILE_DIR = '/upload';

    /**
     * サポート側メールアドレス（宛先）
     */
    const SUPPORT_MAIL_TO = ADMIN_MAIL_TO;

    /**
     * サポート側メールアドレス（Cc）
     */
    const SUPPORT_MAIL_CC = '';

    /**
     * サポート側メールFROM(FROMに日本語を使用する場合は要改修)
     */
    const SUPPORT_MAIL_FROM = '"LIMEX" <sample@sample>';

    /**
     * ユーザ側メールFROM
     */
    const USER_MAIL_FROM = '"LIMEX" <sample@sample>';

    /**
     * upload status
     */
    const UP_SUCCESS = 0;
    const UP_ERR_SYSTEM_LIMIT = 1;
    const UP_ERR_MIME_TYPE = 2;
    const UP_ERR_MAX_SIXE = 3;
    const UP_ERR_TEMP_COPY = 4;
    const UP_ERR_FILES = 5;

    /**
     * upload msg
     */
    const UP_MSG_SUCCESS = '一時アップロード完了';
    const UP_ERR_MSG_COMMON = '一時アップロードに失敗しました。もう一度お願いします。';
    const UP_ERR_MSG_MIME = 'このファイル形式は許可されていません。';
    const UP_ERR_MSG_LIMIT = 'ファイル容量は::SIZE::MB未満でお願いします。';

    /**
     * メール送信時システムエラーメッセージ
     */
    const SYSTEM_ERR_MSG_01 = <<<EOF
            大変申しわけございませんが、時間をおきまして、再度お試しください。<br />
            何度も表示されるようでしたら、お手数ですが、管理者までお問い合わせください。
EOF;

    /**
     * メール送信時システムエラーメッセージ
     */
    const SYSTEM_ERR_MSG_02 = <<<EOF
            お手数ですが、もう一度始めからお願いします。
EOF;

    // session接頭語
    public static $sessPagePrefix = 'order';

    /**
     * フォーム要素
     * @var array
     */
    private $element_list = array(
        'meishi_name',
        'meishi_kana',
        'meishi_post1',
        'meishi_post2',
        'meishi_pref',
        'meishi_addr',
        'meishi_tel',
        'meishi_email',
        'meishi_data',
        'meishi_setnum',
        'meishi_colornum',
        'nouhin_name',
        'nouhin_kana',
        'nouhin_post1',
        'nouhin_post2',
        'nouhin_pref',
        'nouhin_addr',
        'nouhin_tel',
        'seikyu_name',
        'seikyu_kana',
        'seikyu_post1',
        'seikyu_post2',
        'seikyu_pref',
        'seikyu_addr',
        'seikyu_tel',
        );

    /**
     * 許可画像ファイルタイプ(exif_imagetypeに委任)
     */
    private static $allowImageFileType = array(
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG,
        IMAGETYPE_PSD,
    );

    /**
     * 画像以外(PDF)
     */
    private static $allowFileType = array(
        'application/pdf',
        'application/x-pdf',
        'application/acrobat',
        'applications/vnd.pdf',
        'text/pdf',
        'text/x-pdf',
    );

    /**
     * データファイルupload最大容量(MB)
     */
    public static $maxFileSize = 5;

    /**
     * トップページ定義用（メール内で使用）
     * @var string
     */
    private $web_index = '';

    /**
     * POSTデータ格納用
     * @var array
     */
    private $data = array();

    /**
     * 入力エラーメッセージ格納用
     * @var array
     */
    private $validate = array();

    /**
     * 注文番号
     * @var string
     */
    private $orderId;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // webroot定義
        $this->web_index = SSL . '://' . $_SERVER['HTTP_HOST'] . '/';

        // ページ読み込み時イニシャル処理
        foreach ($this->element_list as $element) {
            // 初期化
            $this->setData($element, "");

            // trimし格納（確認画面、処理ページ）
            if (isset($_POST[$element])) {
                //$this->setData($element, trim($_POST[$element]));
                self::setSession('post_data', array($element => trim($_POST[$element])));

            // POST以外の時で、SESSIONにデータがあれば格納（入力画面への戻り時）
            } elseif (self::getSession('post_data.' . $element) !== false) {
                $this->setData($element, self::getSession('post_data.' . $element));
            }
        }
    }

    /**
     * 確認画面コントロール
     * @return void
     */
    public function confirm()
    {
        // formデータ
        //$formData['subject'] = $this->subject_list;
        $formData = null;
        //$_SESSION[self::$sessPagePrefix]['errmsg'] = null;

        // 入力値チェック
        $Valid = new Validate($formData);
        if (! $Valid->start($this->getSession('post_data'))) {
            // メッセージを取得し、入力画面読み込み
            $this->setSession('errmsg', $Valid->getMessage(), true);
            Order::location(self::ORDER_DIR . self::ORDER_TOP . '?status=err');
            exit;
        }
        $this->setSession('errmsg', array(), true);
    }

    /**
     * 問い合わせデータ送信（管理者とユーザ）
     * @return array 結果(code 0:成功、1:入稿データコピー失敗、2:メール送信失敗)
     */
    public function order()
    {
        $status['code'] = 0;
        $this->orderId = $this->createOrderId();

        // 入稿データ一時ファイルを確定
        $dirname = date('Ymd');
        $filename = self::getSession('post_data.file_id') . '_' . self::getSession('post_data.meishi_data');
        $basedir = $_SERVER['DOCUMENT_ROOT'] . self::ORDER_DIR;
        $source =  $basedir . self::ORDER_FILE_TMP_DIR . '/' . $filename;
        if (! file_exists($basedir . self::ORDER_FILE_DIR . '/' . $dirname)) {
            mkdir($basedir . self::ORDER_FILE_DIR . '/' . $dirname, 0775);
        }
        $dest = $basedir . self::ORDER_FILE_DIR . '/' . $dirname . '/' . $filename;

        if (copy($source, $dest)) {
            // 完了メール送信
            $data['filepath'] = $dest;
            list($admin_mailres, $user_mailres) = $this->sendmail($data);
            if (! ($admin_mailres && $user_mailres)) {
                $status['code'] = 2;    // メール送信エラー
            }
        } else {
            $status['code'] = 1;    // データファイルコピー失敗
        }

        return $status;
    }

    /**
     * 注文番号生成
     * @return [type] [description]
     */
    private function createOrderId()
    {
        return date('ymdHi') . sprintf('%06d', rand(10000, 999999));
    }

    /**
     * 注文者と管理者へメール送信
     * @param  array $data 注文データ
     * @return array 送信結果
     */
    private function sendmail($data)
    {
        // メール整形用
        $indent = '　';

        // 管理者側
        $boundary = '__BOUNDARY__'.md5(rand());
        $admin_header  = "Content-Type: multipart/mixed;boundary=\"{$boundary}\"\n";
        $admin_header .= 'From: ' . self::SUPPORT_MAIL_FROM . "\n";
        $admin_header .= 'Reply-To: ' . self::SUPPORT_MAIL_FROM . "\n";
        $admin_header .= 'Cc: ' . self::SUPPORT_MAIL_CC . "\n";
        $admin_header .= "\n";

        // 添付
        $fp = fopen($data['filepath'], 'r');
        $attachFile = fread($fp, filesize($data['filepath']));
        fclose($fp);

        include(__DIR__ . '/mail_template_admin.php');    // 件名、本文
        $body  = "--{$boundary}\n";
        $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
        $body .= "\n";
        $admin_body  = $body . $admin_body;
        $admin_body .= "--{$boundary}\n";
        $admin_body .= "Content-Type: application/octet-stream; name=\"{$this->getData('meishi_data')}\"\n";
        $admin_body .= "Content-Transfer-Encoding: base64\n";
        $admin_body .= "Content-Disposition: attachment; filename=\"{$this->getData('meishi_data')}\"\n";
        $admin_body .= "\n";
        $admin_body .= chunk_split(base64_encode($attachFile)) . "\n";
        $admin_body .= "--{$boundary}--\n";

        $admin_result = mb_send_mail(self::SUPPORT_MAIL_TO, $admin_subject, $admin_body, $admin_header);


        // ユーザ側
        $user_header   = 'From: ' . self::USER_MAIL_FROM . "\n";
        $user_header  .= 'Reply-To: ' . self::USER_MAIL_FROM . "\n";
        include(__DIR__ . '/mail_template_client.php');    // 件名、本文

        $user_result = mb_send_mail($this->getData('meishi_email'), $user_subject, $user_body, $user_header);

        return array($admin_result, $user_result);
    }

    /**
     * データファイルが許可されているファイルタイプかを判定
     * @param string $file ファイルパス
     * @return boolean true=許可ファイル、false=未許可ファイル
     */
    public static function checkFileType($file)
    {
        $type = @exif_imagetype($file);
        if (in_array($type, self::$allowImageFileType)) {
            return true;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file);
        finfo_close($finfo);
        if (in_array($mimeType, self::$allowFileType)) {
            return true;
        }

        return false;
    }

    /**
     * 注文番号取得
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * POST値を格納
     * ※SESSION利用可によりSESSIONへも格納
     * @param string $element 要素名
     * @param string $value   POST値
     */
    public function setData($element, $value)
    {
        $this->data[$element] = $value;
    }

    /**
     * POST値取得
     * @param  string $element 要素名
     * @return mix    ポスト値
     */
    public function getData($element)
    {
        //return $this->data[$element];
        return self::getSession('post_data.' . $element);
    }

    /**
     * html要素を無効化しつつ呼び出し
     * @param  $element 要素名
     * @return string 無効化後の文字列
     */
    public function escape($element)
    {
        return htmlspecialchars($this->getData($element), ENT_QUOTES);
    }

    /**
     * 要素別エラーメッセージ取得
     * @param  string $element 要素名(指定なしで全部)
     * @return string エラーメッセージ
     */
    public function getErrMessage($element = '')
    {
        if (empty($element)) {
            return self::getSession('errmsg');
        } else {
            return self::getSession('errmsg.' . $element);
        }
    }

    /**
     * エラーメッセージの有無
     * @return boolean 有無
     */
    public function hasErrMessage($element = null)
    {
        $search = 'errmsg';
        if (! $element) {
            $search .= '.' . $element;
        }
        $retval = self::getSession($search);
        return !empty($retval);
    }

    /**
     * 件名リスト取得
     * @return 件名リスト
     */
    public function getSubjectList()
    {
        return $this->subject_list;
    }

    /**
     * セレクトボックスの値取得
     * @param  string $element 要素名
     * @return string セレクトボックスの値
     */
    public function getSelectValue($element)
    {
        $variable_name = $element . "_list";
        return $this->{$variable_name}[$this->getData($element)];
    }

    /**
     * データを保有しているか
     * @return boolean
     */
    public function issetData()
    {
        $buff = (implode('', $this->data));
        return (empty($buff)) ? false : true;
    }

    /**
     * システムエラー処理。メッセージ付きで問い合わせトップへ
     * @return void
     */
    public static function doSystemErr()
    {
        self::setSession('errmsg', array('system' => self::SYSTEM_ERR_MSG_02));
        self::location(self::ORDER_DIR . self::ORDER_TOP . '?status=err', true);
    }

    /**
     * session変数への代入(追加)
     * @param string $key 配列キー
     * @param mix    $val 値
     */
    public static function setSession($key, $val, $override = false)
    {
        if (is_array(self::getSession($key))) {
            if ($override) {
                $_SESSION[self::$sessPagePrefix][$key] = $val;
            } else {
                $_SESSION[self::$sessPagePrefix][$key] = array_merge($_SESSION[self::$sessPagePrefix][$key], $val);
            }
        } else {
            $_SESSION[self::$sessPagePrefix][$key] = $val;
        }
    }

    /**
     * session変数呼び出し
     * @param string $key 配列キー(.区切りで子階層)
     */
    public static function getSession($fullkey, $session = array())
    {
        if (! isset($_SESSION[self::$sessPagePrefix])) {
            return false;
        }

        if (empty($session)) {
            $session = $_SESSION[self::$sessPagePrefix];
        }

        $pos = strpos($fullkey, '.');
        $key = substr($fullkey, 0, $pos);

        if ($pos !== false) {
            if (isset($session[$key])) {
                return self::getSession(substr($fullkey, $pos + 1), $session[$key]);
            } else {
                return false;
            }
        } else {
            if (isset($session[$fullkey])) {
                return $session[$fullkey];
            } else {
                return false;
            }
        }
    }

    /**
     * 注文ページのsession削除
     * @return void
     */
    public static function deleteSession()
    {
        unset($_SESSION[self::$sessPagePrefix]);
    }

    /**
     * リダイレクト（同一ドメイン内のみ）（常時SSL対応15/10/8）
     * @param string  $path     ドキュメントルート以下のパス
     * @param boolean $ssl_flg  true:HTTP, false=HTTPS
     */
    public static function location($path, $ssl_flg = false)
    {
        $scheme = ($ssl_flg) ? SSL : 'http';
        header('Location: ' . $scheme . '://'. $_SERVER['SERVER_NAME'] . $path);
        exit;
    }
}
