<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link href="./css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div>
		<div>ロゴジェネレーター</div>
		<div>説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文</div>
		
		<div class="error"><?= $error ?></div>
		
		<form action="generate.php" method="GET">
			<div>
				<input type="text" name="first" value="" size="10"/>
			</div>
			<div>
				<input type="text" name="last" value="" size="10"/>
			</div>
			<div>
				<input type="submit" value="決定"/>
			</div>
			
		</form>
		<a href="http://yahoo.co.jp">公式サイトへのリンク</a>
		
		<div>
			<a href="termofuse.html">利用規約</a> | <a href="privacy.html">プライバシーポリシー</a>
		</div>
		
	</div>
</body>
</html>
