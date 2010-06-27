<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Zpracování formuláře</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<style type="text/css">
	body {font:80%/1.3em "Segoe UI", sans-serif;}
	</style>
</head>

<body>
	<h2>První textarea</h2>
	<?php
	require_once __DIR__ . "/ForumTexy.php";
	$forumTexy = new ForumTexy;
	$text = get_magic_quotes_gpc() ? stripslashes($_POST["text"]) : $_POST["text"];
	echo $forumTexy->process($text);
	?>



	<h2>Druhá textarea</h2>
	<?php
	require_once __DIR__ . "/AdminTexy.php";
	$adminTexy = new AdminTexy;
	$text2 = get_magic_quotes_gpc() ? stripslashes($_POST["text2"]) : $_POST["text2"];
	echo $adminTexy->process($text2);
	?>
</body>
</html>