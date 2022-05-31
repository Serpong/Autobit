<?php
	if(!$is_logged_in)
		alert("로그인이 필요한 페이지입니다.", '/login');
?>

<?php if ($sub_page == 'main'): ?>
	<?
		$strategy_rows = $db->query("SELECT * FROM strategy WHERE idx_user='{$_SESSION['idx_me']}' ");
	?>
	<div class="wrapper_xs">
		<div id="list_strategy_wrap">
			<span class="item head">
				<div>전략명</div>
				<div>수정</div>
				<div>삭제</div>
			</span>
			<? while($strategy_data = $strategy_rows->fetch_assoc()): ?>
				<div class="item">
					<div class="name"><a href="/<?=$strategy_data['idx_strategy']?>"><?=$strategy_data['name_strategy']?></a></div>
					<div><a href="/manage_strategy/edit/<?=$strategy_data['idx_strategy']?>">수정</a></div>
					<div><a href="/manage_strategy/delete/<?=$strategy_data['idx_strategy']?>">삭제</a></div>
				</div>
			<? endwhile ?>

			<div class="item btn_new_strategy">
				<a href="/manage_strategy/new">전략 추가</a>
			</div>
		</div>
	</div>
<?php elseif($sub_page == 'delete'): ?>
	<?
		if(!isset($_GET['idx']))
			alert("비정상적인 주소입니다.");

		$idx_strategy = addslashes($_GET['idx']);
		$strategy_rows = $db->query("SELECT * FROM strategy where idx_strategy='{$idx_strategy}' and idx_user='{$_SESSION['idx_me']}' limit 1");
		if($strategy_rows->num_rows == 0)
			alert("존재하지 않는 전략입니다.");

		$db->query("DELETE FROM strategy where idx_strategy='{$idx_strategy}' and idx_user='{$_SESSION['idx_me']}' limit 1");
		alert("삭제가 완료되었습니다.")
	?>
<?php elseif($sub_page == 'edit' || $sub_page == 'new'): ?>
	<?
		if(isset($_GET['idx'])){

			$idx_strategy = addslashes($_GET['idx']);
			$strategy_rows = $db->query("SELECT * FROM strategy where idx_strategy='{$idx_strategy}' and idx_user='{$_SESSION['idx_me']}' limit 1");
			if($strategy_rows->num_rows == 0)
				alert("존재하지 않는 전략입니다.");
			$strategy_data = $strategy_rows->fetch_assoc();

			$name_strategy = hs($strategy_data['name_strategy']);
			$hashtag = hs($strategy_data['hashtag']);
			$code = hs($strategy_data['code']);
			$url_action = "/manage_strategy/action/{$idx_strategy}";
		}
		else
		{
			$name_strategy = "";
			$hashtag = "";
			$code = "def make_strategy(prices):\n\n\t# 테스트 전략\n\tfrom random import random\n\t\n\trand = random()\n\tif rand < 0.2:\n\t\treturn 'buy'\n\telif(rand < 0.4):\n\t\treturn 'sell'";
			$url_action = "/manage_strategy/action";
		}

	?>
	<div class="wrapper_s">
		<form class="y_form" action="<?=$url_action?>" method="POST">
			<h2>전략 <?=($sub_page=='edit'?"수정":"등록")?></h2>
			<label>
				<p>전략명</p>
				<input type="text" name="name_strategy" placeholder="전략명" value="<?=$name_strategy?>" required>
			</label>
			<label>
				<p>해시태그 (쉼표로 구분)</p>
				<input type="text" name="hashtag" placeholder="해시태그" value="<?=$hashtag?>">
			</label>
			<label>
				<p>전략 코드</p>
				<textarea type="text" class="editor" name="code" placeholder="Python 코드"><?=$code?></textarea>
			</label>
			<button type="submit" class="btn_submit"><?=($sub_page=='edit'?"수정":"등록")?></button>
		</form>
	</div>
	<script>
		var editor = CodeMirror.fromTextArea($(".editor").get(0), {
			lineNumbers: true,
			indentUnit:4,
			indentWithTabs:true,
			mode: "python"
		});
	</script>
<?php elseif($sub_page == 'action'): ?>
	<?

		$name_strategy = addslashes($_POST['name_strategy']);
		$hashtag = addslashes($_POST['hashtag']);
		$code = addslashes($_POST['code']);

		if(isset($_GET['idx']))
		{
			$idx_strategy = addslashes($_GET['idx']);
			$db->query("
				UPDATE strategy SET
				name_strategy='{$name_strategy}',
				hashtag='{$hashtag}',
				code='{$code}'
				WHERE idx_strategy='{$idx_strategy}' and idx_user='{$_SESSION['idx_me']}'
			");
			alert("수정이 완료되었습니다.", '/manage_strategy');
		}
		else
		{
			$db->query("
				INSERT strategy set
				name_strategy='{$name_strategy}',
				hashtag='{$hashtag}',
				code='{$code}',
				idx_user='{$_SESSION['idx_me']}'
			");
			alert("등록이 완료되었습니다.", '/manage_strategy');
		}
	?>
<?php else: ?>
	<div class="wrapper">비정상적인 주소입니다.</div>
<?php endif ?>