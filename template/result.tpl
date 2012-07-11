<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="./css/main.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript">
		function onTweet(elm){
			var url = location.href;
			var text = 'これはテストです。';
			elm.href = 'http://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURI(text);
		}
	</script>
	
</head>
<body>
	<div>
		<div>SketDanceロゴジェネレーター</div>
		<img src="./image.php?first=<?=$first?>&last=<?=$last?>"/> 
		<div>説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文</div>
		
		<div>
			<div id="custom-tweet-button">
				<a href="#" target="_blank" onclick="onTweet(this)">Tweet</a>
			</div>		
			<a id="mailto" href="#">メールで送る</a>
			<a href="./">やり直す</a>
		</div>
		
		<div>
			<a href="termofuse.html">利用規約</a> | <a href="privacy.html">プライバシーポリシー</a>
		</div>
	</div>
	<script type="text/javascript">
		var e = document.getElementById('mailto');
		var url = 'http://sketdance.logo-gene.com/image.php?first=<?=$first?>&last=<?=$last?>&type=m';
		e.href = 'mailto:?body=' + encodeURIComponent(url)
	</script>
</body>
</html>
