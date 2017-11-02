<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="utf-8">
	<title>石勝エクステリア「都市緑化技術で新しい造園を創造」</title>
	<meta name="description" content="石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
	<meta property="og:type" content="website">
	<meta property="og:title" content="石勝エクステリア「都市緑化技術で新しい造園を創造」">
	<meta property="og:url" content="http://www.ishikatsu.co.jp/">
	<meta property="og:description" content="石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
{{include_html file="common_include.html"}}
	<script src="share/slick/slick.min.js"></script>
	<link rel="stylesheet" href="share/slick/slick.css">
	<link rel="stylesheet" href="share/slick/slick-theme.css">
	<script>
		$(document).ready(function(){
			$('.carousel').slick({
				dots: true,
				dotsClass: 'carousel__dots',
				autoplay: true,
				fade: true,

			});
		});
	</script>
{{include_html file="gtm01.html"}}
</head>
<body>
{{include_html file="gtm02.html"}}
	<div class="wrapper" id="wrapperTop">
{{include_html file="header.html"}}
		<div class="topMain">
			<ul class="carousel">
				<li class="carousel__item"><img src="share/images/carousel02.png" class="carousel__image" alt="みどりとともに 新しい時代の「造園」を描くリーディングカンパニーへ"></li>
				<li class="carousel__item"><img src="share/images/carousel03.png" class="carousel__image" alt="技術紹介 石勝エクステリアが培ってきた独自の都市緑化技術"></li>
				<li class="carousel__item"><img src="share/images/carousel04.png" class="carousel__image" alt="管理運営 お客さまや地域の皆様の心が豊かになる環境創造をめざして"></li>
				<li class="carousel__item"><img src="share/images/carousel05.png" class="carousel__image" alt="実績紹介 都市開発から住宅、リゾート、公共施設まで、石勝エクステリアの美しい景観づくり"></li>
			</ul>
			<section class="section section--news">
				<h2 class="section__title section__title--news">
					お知らせ<br><span class="section__subTitle">News</span>
				</h2>
				<ul class="newsList">
{{foreach from=$open_news_list item=item}}
					<li class="newsList__item">
						<p class="newsList__Timetext"><time class="newsList__date" datetime="{{$item.disp_date|date_format:'Y-m-d'}}">{{$item.disp_date|date_format:'Y年m月d日'}}</time></p>
						<p class="newsList__Linktext">{{include file="parts/news_link.tpl" item=$item}}{{$item.title}}</a></p>
					</li>
{{/foreach}}
				</ul>
				<a href="#TODO" class="newsDetail">お知らせ一覧へ</a>
			</section>
			<section class="section section--about">
				<h2 class="section__title">
					石勝エクステリアについて<br><span class="section__subTitle">About Us</span>
				</h2>
				<ul class="aboutList aboutList--top">
					<li class="aboutList__item"><a href="company/message/" class="aboutList__link aboutList__link--01"><span class="aboutList__text">トップメッセージ</span></a></li>
					<li class="aboutList__item"><a href="company/philosophy/" class="aboutList__link aboutList__link--02"><span class="aboutList__text">企業理念</span></a></li>
					<li class="aboutList__item"><a href="company/service/" class="aboutList__link aboutList__link--03"><span class="aboutList__text">事業紹介</span></a></li>
					<li class="aboutList__item"><a href="works/awards/" class="aboutList__link aboutList__link--04"><span class="aboutList__text">受賞作品</span></a></li>
				</ul>
			</section>
			<section class="section section--approach">
				<h2 class="section__title">
					石勝エクステリアの取り組み<br><span class="section__subTitle">Our Approach</span>
				</h2>
				<ul class="bannerCircle bannerCircle--top">
					<li class="bannerCircle__item"><a href="attempt/" class="bannerCircle__link bannerCircle__link--01"><span class="bannerCircle__text bannerCircle__text--01">海外での<br>景観創造</span></a></li>
					<li class="bannerCircle__item"><a href="attempt/hr/" class="bannerCircle__link bannerCircle__link--02"><span class="bannerCircle__text">人材育成</span></a></li>
					<li class="bannerCircle__item"><a href="attempt/csr/" class="bannerCircle__link bannerCircle__link--03"><span class="bannerCircle__text">CSR</span></a></li>
					<li class="bannerCircle__item"><a href="attempt/reconstruction/" class="bannerCircle__link bannerCircle__link--04"><span class="bannerCircle__text">復興支援活動</span></a></li>
				</ul>
			</section>
			<div class="background02">
				<section class="section section--information">
					<h2 class="section__title">
						インフォメーション<br><span class="section__subTitle">Information</span>
					</h2>
					<ul class="bannerCol2 bannerCol2--top">
						<li class="bannerCol2__item"><a href="technology/download/" class="bannerCol2__link bannerCol2__link--01"><span class="bannerCol2__text bannerCol2__text--01">カタログダウンロード</span></a></li>
						<li class="bannerCol2__item"><a href="partners/" class="bannerCol2__link bannerCol2__link--02"><span class="bannerCol2__text">ビジネスパートナーのみなさま</span></a></li>
					</ul>
				</section>
				<section class="section section--link">
					<h2 class="section__title">
						関連リンク<br><span class="section__subTitle">Links</span>
					</h2>
					<ul class="linkList">
						<li class="linkList__item"><a href="http://www.tokyu-fudosan-hd.co.jp/ir/mgtpolicy/plan/" target="_blank" class="linkList__link linkList__link--01">東急不動産ホールディングス中期経営計画2017-2020</a></li>
						<li class="linkList__item"><a href="http://www.tokyu-fudosan-hd.co.jp/" target="_blank" class="linkList__link linkList__link--02">東急不動産ホールディングス</a></li>
						<li class="linkList__item"><a href="company/group/" class="linkList__link linkList__link--03">株式会社石勝グリーンメンテナンス</a></li>
						<li class="linkList__item"><a href="http://www.iei-kouen.jp/yamoto/" target="_blank" class="linkList__link linkList__link--04">谷本公園ナイタ照明付き全面人工芝球場</a></li>
						<li class="linkList__item"><a href="http://www.iei-kouen.jp/kawasakishiryokuka/" target="_blank" class="linkList__link linkList__link--05">川崎市緑化センター</a></li>
					</ul>
				</section>
			</div>
		</div>
{{include_html file="footer.html"}}
	</div>
</body>
</html>