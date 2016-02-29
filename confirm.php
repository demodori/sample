<?php
/**
 * 注文ページ確認画面
 *
 * @package    sample
 * @subpackage order
 * @version    2016/02/29 1.0
 * @author     yutaka.sudo
 */

// お問い合わせクラスロード
require_once __DIR__ . "/include/Order.class.php";

// 直アクセスはエラー（トップへ）
if (empty($_POST) || !Order::getSession('contentsToken')) {
    Order::doSystemErr();
}

// 確認処理
$order = new Order();
$order->confirm();

// トークン生成
$token = sha1(uniqid(mt_rand(), true));
Order::setSession('token', $token);

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
    <div class="wrapper cf cofirm">
        <h3>ご注文</h3>
        <form action="order.php" method="post" name="form_order" id="form">
        <!-- 名刺情報 -->
            <div class="head">
                <div class="pt_ssss pb_ssss">入力内容をご確認し、よろしければ「この内容で申し込む」ボタンを押してください。</div>
                <div class="order_title">名刺情報</div>
            </div>
            <dl class="cf">
                <dt>お名前</dt>
                <dd>
                    <?php echo $order->escape('meishi_name'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな</dt>
                <dd>
                    <?php echo $order->escape('meishi_kana'); ?>
                </dd>
            </dl>
            <dl class="cf">
             <dt>住所</dt>
                <dd>
                    <p class="mb_sssssss"><?php echo $order->escape('meishi_post1'); ?> - <?php echo $order->escape('meishi_post1'); ?></p>
                    <?php echo $prefectures[$order->getData('meishi_pref')] . $order->escape('meishi_addr'); ?>
                </dd>
            </dl>
            <dl class="cf">
                 <dt>電話番号</dt>
                <dd>
                    <?php echo $order->escape('meishi_tel'); ?>
                </dd>
               </dl> 
            <dl class="cf">
                <dt>メールアドレス</dt>
                <dd>
                    <?php echo $order->escape('meishi_email'); ?>
                </dd>
            </dl>
            <dl class="cf">
             <dt>入稿データ</dt>
                <dd>
                    <?php echo $order->escape('meishi_data'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>セット数<br />（100枚 / 1セット）</dt>
                <dd>
                    <?php echo $setNumList[$order->getData('meishi_setnum')]; ?> セット
                </dd>
            </dl>
            <dl class="cf">
                <dt>色数</dt>
                <dd>
                    <?php echo $colorNumList[$order->getData('meishi_colornum')]; ?> 色
                </dd>
            </dl>

            <div class="head mb_sss">
                <div class="order_title">納品先</div>
            </div>
        <?php if (Order::getSession('post_data.nouhin_name')) : ?>
            <!-- 納品先 -->
            <dl class="cf">
                <dt>お名前</dt>
                <dd>
                    <?php echo $order->escape('nouhin_name'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな</dt>
                <dd>
                    <?php echo $order->escape('nouhin_kana'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>住所</dt>
                <dd>
                    <p class="mb_sssssss"><?php echo $order->escape('nouhin_post1'); ?> - <?php echo $order->escape('nouhin_post2'); ?></p>
                    <?php echo $prefectures[$order->getData('nouhin_pref')] . $order->escape('nouhin_addr'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>電話番号</dt>
                <dd>
                    <?php echo $order->escape('nouhin_tel'); ?>
                </dd>
            </dl>
        <?php else : ?>
            <dl class="cf">
                <dd>名刺情報と同じ</dd>
            </dl>
        <?php endif; ?>

            <div class="head mb_sss">
                <div class="order_title">請求先</div>
            </div>
        <?php if (Order::getSession('post_data.seikyu_name')) : ?>
            <!-- 請求先 -->
            <dl class="cf">
                <dt>お名前</dt>
                <dd>
                    <?php echo $order->escape('seikyu_name'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>ふりがな</dt>
                <dd>
                    <?php echo $order->escape('seikyu_kana'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>住所</dt>
                <dd>
                    <p class="mb_sssssss"><?php echo $order->escape('seikyu_post1'); ?> - <?php echo $order->escape('seikyu_post1'); ?></p>
                    <?php echo $prefectures[$order->getData('seikyu_pref')] . $order->escape('seikyu_addr'); ?>
                </dd>
            </dl>
            <dl class="cf">
                <dt>電話番号</dt>
                <dd class="mb_sss">
                    <?php echo $order->escape('seikyu_tel'); ?>
                </dd>
            </dl>
        <?php else : ?>
            <dl class="cf">
                <dd>名刺情報と同じ</dd>
            </dl>
        <?php endif; ?>

            <dl class="cf">
                <dd style="text-align: center;">
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <input class="btn" type="button" value="戻る" onclick="window.location.href = './?status=back';" />
                    <input type="image" name="btn_confirm" src="img/button_confirm.png"/>
                </dd>
            </dl>
        </form>
    </div>
</section><!-- /#body -->

<footer>
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