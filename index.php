<?
	include_once($_SERVER['DOCUMENT_ROOT'] . "/inc/config.php");

	if(!defined('AFTER_CONFIG'))
		exit('no config');
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=yes">

	<title>AutoBit</title>

	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">
	
	<script src="/js/jquery.min.js"></script>
	<script src="/js/script.js"></script>

	<link rel="stylesheet" href="/css/reset.css">
	<link rel="stylesheet" href="/css/style.css">

	<link rel="stylesheet" type="text/css" href="/css/datepicker.min.css">
	<script type="text/javascript" src="/js/datepicker.min.js"></script>

	<!-- 에디터 -->
	<link rel="stylesheet" href="/assets/codemirror/codemirror.css">
	<script src="/assets/codemirror/codemirror.js"></script>
	<script src="/assets/codemirror/python.js"></script>

</head>
<body>
	<header>
		<ul class="wrapper_l ac">
			<li><a href="/"><img src="/img/logo_black.png" class="logo" alt="로고"></a></li>
			<li><a href="/list_strategy" class="<?=chk_page_active('list_strategy')?>">전략 목록</a></li>
			<li><a href="/manage_strategy" class="<?=chk_page_active('manage_strategy')?>">전략 관리</a></li>
			<?php if ($is_logged_in): ?>
				<li class="right"><a href="/logout">로그아웃</a></li>
			<?php else: ?>
				<li class="right"><a href="/register">회원가입</a></li>
				<li class="right"><a href="/login">로그인</a></li>
			<?php endif ?>
		</ul>
	</header>

	<? include_once($_SERVER['DOCUMENT_ROOT'] . "/page/{$page}.php") ?>
	
	<footer>
		<div class="wrapper_l">
			Copyright © 2021 AutoBit. All rights reserved.
		</div>
	</footer>
</body>
</html>