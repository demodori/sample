<?php
/**
 * お問い合わせ入力値チェック
 *
 * @package    sample
 * @subpackage Order
 * @version    2016/02/29 1.0
 * @author     yutaka.sudo
 */


/**
 * Validateコンポーネントクラス
 */
class Validate
{
    /**
     * エラーエッセージ
     * @var array
     */
    private $message = array();

    /**
     * フォーム要素
     * @var array
     */
    private $formData = array();

    /**
     * コンストラクター
     * @param array $formData フォーム要素
     */
    public function __construct($formData)
    {
        $this->formData = $formData;
    }

    /**
     * 入力値チェック
     * NGの場合は入力画面を読み込む
     * @return boolean true:エラーなし, false:エラー
     */
    public function start($data)
    {
        $result = true;

        // 名刺情報：名前
        $element = 'meishi_name';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：お名前は必須項目です。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '名刺情報：お名前は30文字以内でお願いします。');
            $result = false;
        }

        // 名刺情報：ふりがな
        $element = 'meishi_kana';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：ふりがなは必須項目です。');
            $result = false;
        } elseif (! $this->check_hiragana($data[$element])) {
            $this->setMessage($element, '名刺情報：ふりがなはひらがなでお願いします。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '名刺情報：ふりがなは30文字以内でお願いします。');
            $result = false;
        }

        // 名刺情報：郵便番号
        $element = 'meishi_post1';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：郵便番号（左）は必須項目です。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 3, 3) || ! is_numeric($data[$element])) {
            $this->setMessage($element, '名刺情報：郵便番号（左）は半角数字3桁でお願いします。');
            $result = false;
        }
        $element = 'meishi_post2';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：郵便番号（右）は必須項目です。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 4, 4) || ! is_numeric($data[$element])) {
            $this->setMessage($element, '名刺情報：郵便番号（右）は半角数字4桁でお願いします。');
            $result = false;
        }
        // 都道府県
        global $prefectures;
        $element = 'meishi_pref';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：都道府県は必須項目です。');
            $result = false;
        } elseif (! in_array($data[$element], array_keys($prefectures))) {
            $this->setMessage($element, '名刺情報：都道府県に誤りがあります。');
            $result = false;
        }
        // 都道府県以降の住所
        $element = 'meishi_addr';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：住所(都道府県以降)は必須項目です。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 100)) {
            $this->setMessage($element, '名刺情報：住所(都道府県以降)は100文字以内でお願いします。');
            $result = false;
        }

        // 名刺情報：電話
        $element = 'meishi_tel';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：電話番号は必須項目です。');
            $result = false;
        } elseif (!$this->check_tel($data[$element])) {
            $this->setMessage($element, '名刺情報：電話番号は数字と「-」(ハイフン)で13桁以内でお願いします。');
            $result = false;
        }

        // メール
        $element = 'meishi_email';
        if (empty($data[$element])) {
            $this->setMessage($element, 'メールアドレスは必須項目です。');
            $result = false;
        } elseif (!$this->check_email($data[$element])) {
            $this->setMessage($element, 'メールアドレスの形式に誤りがあります。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 255)) {
            $this->setMessage($element, 'メールアドレスは255文字以内でお願いします。');
            $result = false;
        }

        // 入稿データ
        $element = 'meishi_data';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：入稿データは必須項目です。');
            $result = false;
        }

        // セット数
        global $setNumList;
        $element = 'meishi_setnum';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：セット数は必須項目です。');
            $result = false;
        } elseif (! in_array($data[$element], array_keys($setNumList))) {
            $this->setMessage($element, '名刺情報：セット数に誤りがあります。');
            $result = false;
        }

        // 色数
        global $colorNumList;
        $element = 'meishi_colornum';
        if (empty($data[$element])) {
            $this->setMessage($element, '名刺情報：色数は必須項目です。');
            $result = false;
        } elseif (! in_array($data[$element], array_keys($colorNumList))) {
            $this->setMessage($element, '名刺情報：色数に誤りがあります。');
            $result = false;
        }

        //----------------------------------------------------------//
        //                          納品先                          //
        //----------------------------------------------------------//
        //どれか１つでも入力されていたら全て必須チェックをかける
        $requireFlg = false;
        $elements = array('name', 'kana', 'post1', 'post2', 'pref', 'addr', 'tel');
        $buff = '';
        foreach ($elements as $value) {
            $buff .= $data['nouhin_' . $value];
        }
        if (! empty($buff)) {
            $requireFlg = true;
        }

        // 納品先：名前
        $element = 'nouhin_name';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：お名前は必須項目です。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '納品先：お名前は30文字以内でお願いします。');
            $result = false;
        }

        // 納品先：ふりがな
        $element = 'nouhin_kana';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：ふりがなは必須項目です。');
            $result = false;
        } elseif (! $this->check_hiragana($data[$element])) {
            $this->setMessage($element, '納品先：ふりがなはひらがなでお願いします。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '納品先：ふりがなは30文字以内でお願いします。');
            $result = false;
        }

        // 納品先：郵便番号
        $element = 'nouhin_post1';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：郵便番号（左）は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' && (! $this->check_length($data[$element], 3, 3) || ! is_numeric($data[$element]))) {
            $this->setMessage($element, '納品先：郵便番号（左）は半角数字3桁でお願いします。');
            $result = false;
        }
        $element = 'nouhin_post2';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：郵便番号（右）は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' && (! $this->check_length($data[$element], 4, 4) || ! is_numeric($data[$element]))) {
            $this->setMessage($element, '納品先：郵便番号（右）は半角数字4桁でお願いします。');
            $result = false;
        }
        // 都道府県
        global $prefectures;
        $element = 'nouhin_pref';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：都道府県は必須項目です。');
            $result = false;
        } elseif (! in_array($data[$element], array_keys($prefectures))) {
            $this->setMessage($element, '納品先：都道府県に誤りがあります。');
            $result = false;
        }
        // 都道府県以降の住所
        $element = 'nouhin_addr';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：住所(都道府県以降)は必須項目です。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 100)) {
            $this->setMessage($element, '納品先：住所(都道府県以降)は100文字以内でお願いします。');
            $result = false;
        }

        // 納品先：電話
        $element = 'nouhin_tel';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '納品先：電話番号は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' &&!$this->check_tel($data[$element])) {
            $this->setMessage($element, '納品先：電話番号は数字と「-」(ハイフン)で13桁以内でお願いします。');
            $result = false;
        }

        //----------------------------------------------------------//
        //                          請求先                          //
        //----------------------------------------------------------//
        //どれか１つでも入力されていたら全て必須チェックをかける
        $requireFlg = false;
        $elements = array('name', 'kana', 'post1', 'post2', 'pref', 'addr', 'tel');
        $buff = '';
        foreach ($elements as $value) {
            $buff .= $data['seikyu_' . $value];
        }
        if (! empty($buff)) {
            $requireFlg = true;
        }

        // 請求先：名前
        $element = 'seikyu_name';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：お名前は必須項目です。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '請求先：お名前は30文字以内でお願いします。');
            $result = false;
        }

        // 請求先：ふりがな
        $element = 'seikyu_kana';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：ふりがなは必須項目です。');
            $result = false;
        } elseif (! $this->check_hiragana($data[$element])) {
            $this->setMessage($element, '請求先：ふりがなはひらがなでお願いします。');
            $result = false;
        } elseif (! $this->check_length($data[$element], 0, 30)) {
            $this->setMessage($element, '請求先：ふりがなは30文字以内でお願いします。');
            $result = false;
        }

        // 請求先：郵便番号
        $element = 'seikyu_post1';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：郵便番号（左）は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' && (! $this->check_length($data[$element], 3, 3) || ! is_numeric($data[$element]))) {
            $this->setMessage($element, '請求先：郵便番号（左）は半角数字3桁でお願いします。');
            $result = false;
        }
        $element = 'seikyu_post2';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：郵便番号（右）は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' && (! $this->check_length($data[$element], 4, 4) || ! is_numeric($data[$element]))) {
            $this->setMessage($element, '請求先：郵便番号（右）は半角数字4桁でお願いします。');
            $result = false;
        }
        // 都道府県
        global $prefectures;
        $element = 'seikyu_pref';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：都道府県は必須項目です。');
            $result = false;
        } elseif (! in_array($data[$element], array_keys($prefectures))) {
            $this->setMessage($element, '請求先：都道府県に誤りがあります。');
            $result = false;
        }
        // 都道府県以降の住所
        $element = 'seikyu_addr';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：住所(都道府県以降)は必須項目です。');
            $result = false;
        } elseif (!$this->check_length($data[$element], 0, 100)) {
            $this->setMessage($element, '請求先：住所(都道府県以降)は100文字以内でお願いします。');
            $result = false;
        }

        // 請求先：電話
        $element = 'seikyu_tel';
        if ($requireFlg && empty($data[$element])) {
            $this->setMessage($element, '請求先：電話番号は必須項目です。');
            $result = false;
        } elseif ($data[$element]!= '' &&!$this->check_tel($data[$element])) {
            $this->setMessage($element, '請求先：電話番号は数字と「-」(ハイフン)で13桁以内でお願いします。');
            $result = false;
        }


        return $result;
    }

    /**
     * 要素別エラーメッセージ格納
     * @param string $element 要素名
     * @param string $value エラーメッセージ
     */
    private function setMessage($element, $message)
    {
        // なければ初期化
        if (!isset($this->message[$element])) {
            $this->message[$element] = "";
        }
        $this->message[$element] .= $message . "<br />";
    }

    /**
     * 要素別エラーメッセージ格納
     * @param  string $element 要素名
     * @return エラーメッセージ（要素指定時は要素別）
     */
    public function getMessage($element = null)
    {
        return (! $element)?$this->message:$this->message[$element];
    }


    ///////////////////////////////////////////////////////////////////////////
    /**
     * 文字列の長さチェック
     *
     * @param  string $str        チェック文字列
     * @param  int    $min_length 最小値
     * @param  int    $max_length 最大値
     * @return bool   true=OK, false=NG
     */
    private function check_length($str, $min_length, $max_length)
    {
        $length = mb_strlen($str, 'UTF8');
        return  ($length >= $min_length && $max_length >= $length)?true:false;
    }

    /**
     * E-mailの妥当性チェック
     *
     * @param  string $email メールアドレス
     * @return bool   true=OK, false=NG
     */
    private function check_email($email)
    {
        $pattern = '/^[a-zA-Z0-9_¥.¥-]+?@[A-Za-z0-9_¥.¥-]+$/';
        return preg_match($pattern, $email)?true:false;
    }

    /**
     * 電話番号の妥当性チェック
     *
     * @param  string $tel 電話
     * @return bool   true=OK, false=NG
     */
    private function check_tel($tel)
    {
        $pattern = '/^[0-9¥-]{1,13}$/';
        return preg_match($pattern, $tel)?true:false;
    }

    /**
     * 入力文字列の平仮名チェック+全角空白
     *
     * @param  string $tel 電話
     * @return bool   true=OK, false=NG
     */
    private function check_hiragana($str)
    {
        return preg_match("/^[　\sぁ-んー]*$/u", $str);
    }
}
