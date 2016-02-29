<?php
/**
 * 注文ページトップ
 *
 * @package    sample
 * @subpackage order
 * @version    2016/02/29 1.0
 * @author     yutaka.sudo
 */

// 注文model兼controller
require_once __DIR__ . "/include/Order.class.php";

$order = new Order();

// 初回アクセス時初期化
if (! isset($_GET['status'])) {
    Order::deleteSession();
}

// 完了後のブラウザバック用トークン
$token = sha1(uniqid(mt_rand(), true));
Order::setSession('contentsToken', $token);

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<!--[if lt IE 9]>
<script type="text/javascript" src="../../../js/ie/html5shiv.js"></script>
<![endif]-->
<title>注文</title>
<meta name="keywords" content="sample, 注文">
<meta name="description" content="これは自作の注文サンプルプログラムです。">

<meta property="og:title" content="サンプル" />
<meta property="og:url" content="http://www.yahoo.co.jp/" />
<meta property="og:description" content="OGPテスト。" />
<meta property="og:image" content="http://k.yimg.jp/images/top/sp2/cmn/logo-ns-131205.png" />

<meta name="viewport" content="width=1080px, maximum-scale=1.0, user-scalable=yes">

<link type="text/css" rel="stylesheet" href="../css/style.css" />
<link type="text/css" rel="stylesheet" href="./css/style.css" />

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="./js/order.js"></script>
<LINK REL="SHORTCUT ICON" HREF="../img/favicon.ico">

</head>
<body class="order">
<header>
    <div class="wrapper">
        <h1 class="logo"><a href="../"><img src="../img/logo_header.png" height="44" width="192" alt="LIMEX"></a></h1>
        <ul id="gNav">
            <li class="nav1"><a href="../about/">ABOUT</a></li>
            <li class="nav2"><a href="../mission/">MISSION</a></li>
            <li class="nav3"><a href="../factory/">FACTORY</a></li>
            <li class="nav4"><a href="../company/">COMPANY</a></li>
            <li class="nav5"><a href="../news/">NEWS</a></li>
            <li class="nav6"><a href="https://tb-m.com/form/contact_lime-x/contact/">CONTACT</a></li>
        </ul>
    </div>
</header>
<section id="body">
    <div class="pageTtl">
        <h2>ORDER</h2>
    </div>
    <div class="note"><span class="caution">※は必須項目です。</span></div>
    <div class="wrapper cf input">
        <h3>ご注文</h3>
        <form action="confirm.php" method="post" name="form_order" id="form" enctype="multipart/form-data">
        <?php // エラーメッセージ ?>
        <?php if ($order->hasErrMessage('system')) : ?>
            <div class="errmsg"><?php echo $order->getErrMessage('system'); ?></div>
        <?php endif; ?>
        <!-- 名刺情報 -->
            <div class="head">
                <div class="order_title">名刺情報</div>
                <div class="pt_ssss pb_ssss">以下情報をご入力ください。</div>
            </div>
            <dl class="cf">
                <dt>お名前<span class="caution">※</span></dt>
                <dd>
                    <input type="text" class="input_001" id="meishi_name" name="meishi_name" value="<?php echo $order->escape('meishi_name'); ?>" placeholder="例：名刺　太郎" />
                    <?php if (Order::getSession('errmsg.meishi_name')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_name')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな<span class="caution">※</span></dt>
                <dd>
                    <input type="text" class="input_001" id="meishi_kana" name="meishi_kana" value="<?php echo $order->escape('meishi_kana'); ?>" placeholder="例：めいし　たろう" />
                    <?php if (Order::getSession('errmsg.meishi_kana')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_kana')); ?>
                </dd>
            </dl>
            <dl class="cf">
             <dt>住所<span class="caution">※</span></dt>
                <dd>
                    <p class="mb_sssss"><input type="text" class="order_post form_small" maxlength="3" id="meishi_post1" name="meishi_post1" value="<?php echo $order->escape('meishi_post1'); ?>" size="3" /> -
                    <input type="text" class="order_post form_small" maxlength="4" id="meishi_post2" name="meishi_post2" value="<?php echo $order->escape('meishi_post2'); ?>" size="4" /></p>
                    <select class="mb_ssss" id="meishi_pref" name="meishi_pref">
                    <?php
                    foreach ($prefectures as $key => $value) :
                        printf('<option value="%d"%s>%s</option>', $key, ($order->getData('meishi_pref') == $key)?'selected':'', $value);
                    endforeach
                    ?>
                    </select><br />
                    <input type="text" class="input_001" id="meishi_addr" name="meishi_addr" value="<?php echo $order->escape('meishi_addr'); ?>" placeholder="例：渋谷区千駄ヶ谷１−１−１　テストビル5F" />
                    <?php if (Order::getSession('errmsg.meishi_post1')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_post1')); ?>
                    <?php if (Order::getSession('errmsg.meishi_post2')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_post2')); ?>
                    <?php if (Order::getSession('errmsg.meishi_pref')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_pref')); ?>
                    <?php if (Order::getSession('errmsg.meishi_addr')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_addr')); ?>
                </dd>
            </dl>
            <dl class="cf">
                 <dt>電話番号<span class="caution">※</span></dt>
                <dd>
                    <input type="text" class="input_001" id="meishi_tel" name="meishi_tel" value="<?php echo $order->escape('meishi_tel'); ?>" placeholder="例：03-0000-0000" />
                    <?php if (Order::getSession('errmsg.meishi_tel')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_tel')); ?>
                </dd>
               </dl> 
            <dl class="cf">
                <dt>メールアドレス<span class="caution">※</span></dt>
                <dd>
                    <input type="text" class="input_001" id="meishi_email" name="meishi_email" value="<?php echo $order->escape('meishi_email'); ?>" placeholder="例：test@test.com" />
                    <?php if (Order::getSession('errmsg.meishi_email')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_email')); ?>
                </dd>
            </dl>
            <dl class="cf">
             <dt>入稿データ<span class="caution">※</span></dt>
                <dd>
                <?php if (! $meishi_data = Order::getSession('post_data.meishi_data')) : ?>
                    <input type="file" id="meishi_data" name="meishi_data" value="アップロード" />
                    <div id="upedfile"><?php echo $meishi_data; ?><input type="button" name="clearfile" class="btn filebtn" id="clearfile" value="ファイル選択" onclick="$('#meishi_data').click();"></div>
                    <?php if (Order::getSession('errmsg.meishi_data')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_data')); ?>
                    <div id="result"></div>
                <?php else : ?>
                    <input type="file" id="meishi_data" name="meishi_data" value="アップロード" />
                    <div id="upedfile"><div id="filename" class="pb_ssss"><?php echo $meishi_data; ?></div><input type="button" name="clearfile"  class="btn filebtn" id="clearfile" value="選び直す" onclick="$('#meishi_data').click();"></div>
                    <div id="result"></div>
                <?php endif; ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>セット数<span class="caution">※</span><br />（100枚 / 1セット）</dt>
                <dd>
                    <select id="meishi_setnum" name="meishi_setnum">
                    <?php
                    foreach ($setNumList as $key => $value) :
                        printf('<option value="%d"%s>%s</option>', $key, ($order->getData('meishi_setnum') == $key)?'selected':'', $value);
                    endforeach
                    ?>
                    </select>
                    <?php if (Order::getSession('errmsg.meishi_setnum')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_setnum')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>色数<span class="caution">※</span></dt>
                <dd>
                    <select id="meishi_colornum" name="meishi_colornum">
                    <?php
                    foreach ($colorNumList as $key => $value) :
                        printf('<option value="%d"%s>%s</option>', $key, ($order->getData('meishi_colornum') == $key)?'selected':'', $value);
                    endforeach
                    ?>
                    </select>
                    <?php if (Order::getSession('errmsg.meishi_colornum')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.meishi_colornum')); ?>
                </dd>
            </dl>
            <!-- 納品先 -->
            <div class="head mb_sss">
                <div class="order_title">※納品先が違う場合</div>
            </div>

            <dl class="cf">
                <dt>お名前</dt>
                <dd>
                    <input type="text" class="input_001" id="nouhin_name" name="nouhin_name" value="<?php echo $order->escape('nouhin_name'); ?>" placeholder="例：名刺　太郎" />
                    <?php if (Order::getSession('errmsg.nouhin_name')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_name')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな</dt>
                <dd>
                    <input type="text" class="input_001" id="nouhin_kana" name="nouhin_kana" value="<?php echo $order->escape('nouhin_kana'); ?>" placeholder="例：めいし　たろう" />
                    <?php if (Order::getSession('errmsg.nouhin_kana')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_kana')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>住所</dt>
                <dd>
                    <p class="mb_sssss"><input type="text" class="order_post form_small" maxlength="3" id="nouhin_post1" name="nouhin_post1" value="<?php echo $order->escape('nouhin_post1'); ?>" size="3" /> -
                    <input type="text" class="order_post form_small" maxlength="4" id="nouhin_post2" name="nouhin_post2" value="<?php echo $order->escape('nouhin_post2'); ?>" size="4" /></p>
                    <select  class="mb_ssss" id="nouhin_pref" name="nouhin_pref">
                    <?php
                    foreach ($prefectures as $key => $value) :
                        printf('<option value="%d"%s>%s</option>', $key, ($order->getData('nouhin_pref') == $key)?'selected':'', $value);
                    endforeach
                    ?>
                    </select><br />
                    <input type="text" class="input_001" id="nouhin_addr" name="nouhin_addr" value="<?php echo $order->escape('nouhin_addr'); ?>" placeholder="例：渋谷区千駄ヶ谷１−１−１　テストビル5F" />
                    <?php if (Order::getSession('errmsg.nouhin_post1')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_post1')); ?>
                    <?php if (Order::getSession('errmsg.nouhin_post2')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_post2')); ?>
                    <?php if (Order::getSession('errmsg.nouhin_pref')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_pref')); ?>
                    <?php if (Order::getSession('errmsg.nouhin_addr')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_addr')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>電話番号</dt>
                <dd>
                    <input type="text" class="input_001" id="nouhin_tel" name="nouhin_tel" value="<?php echo $order->escape('nouhin_tel'); ?>" placeholder="例：03-0000-0000" />
                    <?php if (Order::getSession('errmsg.nouhin_tel')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.nouhin_tel')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <!-- 請求先 -->
                <div class="head mb_sss">
                    <div class="order_title">※請求先が違う場合</div>
                </div>

                <dt>お名前</dt>
                <dd>
                    <input type="text" class="input_001" id="seikyu_name" name="seikyu_name" value="<?php echo $order->escape('seikyu_name'); ?>" placeholder="例：名刺　太郎" />
                    <?php if (Order::getSession('errmsg.seikyu_name')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_name')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな</dt>
                <dd>
                    <input type="text" class="input_001" id="seikyu_kana" name="seikyu_kana" value="<?php echo $order->escape('seikyu_kana'); ?>" placeholder="例：めいし　たろう" />
                    <?php if (Order::getSession('errmsg.seikyu_kana')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_kana')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>住所</dt>
                <dd>
                    <p class="mb_sssss"><input type="text" class="order_post form_small" maxlength="3" id="seikyu_post1" name="seikyu_post1" value="<?php echo $order->escape('seikyu_post1'); ?>" size="3" /> -
                    <input type="text" class="order_post form_small" maxlength="4" id="seikyu_post2" name="seikyu_post2" value="<?php echo $order->escape('seikyu_post2'); ?>" size="4" /></p>
                    <select class="mb_ssss" id="seikyu_pref" name="seikyu_pref">
                    <?php
                    foreach ($prefectures as $key => $value) :
                        printf('<option value="%d"%s>%s</option>', $key, ($order->getData('seikyu_pref') == $key)?'selected':'', $value);
                    endforeach;
                    ?>
                    </select><br />
                    <input type="text" class="input_001" id="seikyu_addr" name="seikyu_addr" value="<?php echo $order->escape('seikyu_addr'); ?>" placeholder="例：渋谷区千駄ヶ谷１−１−１　テストビル5F" />
                    <?php if (Order::getSession('errmsg.seikyu_post1')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_post1')); ?>
                    <?php if (Order::getSession('errmsg.seikyu_post2')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_post2')); ?>
                    <?php if (Order::getSession('errmsg.seikyu_pref')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_pref')); ?>
                    <?php if (Order::getSession('errmsg.seikyu_addr')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_addr')); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>電話番号</dt>
                <dd class="mb_sss">
                    <input type="text" class="input_001" id="seikyu_tel" name="seikyu_tel" value="<?php echo $order->escape('seikyu_tel'); ?>" placeholder="例：03-0000-0000" />
                    <?php if (Order::getSession('errmsg.seikyu_tel')) printf('<div class="errmsg">%s</div>', Order::getSession('errmsg.seikyu_tel')); ?>
                </dd>
                <dd style="text-align: center;"><input type="image" name="btn_confirm" src="img/button_input.png"/></dd>
            </dl>
        </form>
    </div>
</section><!-- /#body -->

<footer style="clear:both;">
    <ul class="cf01">
    <li><a href="https://twitter.com/share?url=http://tb-m.com/&amp;text=%e6%ac%a1%e4%b8%96%e4%bb%a3%e7%b4%a0%e6%9d%90LIMEX%20%e6%a0%aa%e5%bc%8f%e4%bc%9a%e7%a4%beTBM" target="_blank" class="icon01">twitter</a></li>
    <li><a href="https://www.facebook.com/sharer.php?u=http://tb-m.com/" target="_blank" class="icon02">facebook</a></li>
    <li><a href="https://www.youtube.com/channel/UCIvfa-J0VGeLl71Aeg6sWRg" target="_blank" class="icon03">youtube</a></li>
    </ul>
    <p class="copyright">Copyright TBM Co.,Ltd All Rights Reserved</p>
</footer>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46676692-1', 'auto');
  ga('send', 'pageview');
</script>
<!-- アクセス解析 -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16758707-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- // アクセス解析 -->

</body>
</html>