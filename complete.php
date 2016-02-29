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

<style type="text/css">
<!--
.orderid {
  color: red;
  font-size: large;
  margin-top: 20px;
  margin-bottom: 20px;
}
-->
</style>
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
<section id="body" style="margin-bottom: 50px;">
    <div class="pageTtl">
        <h2>ORDER</h2>
    </div>
    ご注文、誠にありがとうございました。<br />
  <?php if(isset($_GET['order_id'])) : ?>
    <div class="orderid">
      ご注文番号：<?php echo $_GET['order_id']; ?>
    </div>
  <?php endif; ?>
    ※ご注文受領のお知らせメールを自動で配信します。メールが届かない場合は「<a href="">お問い合わせ</a>」よりご連絡ください。<br />
    <br />
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