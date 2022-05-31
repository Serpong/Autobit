<?php
	if($is_logged_in)
		alert("이미 로그인 되어있습니다.");

	if(isset($_POST['user_id'], $_POST['user_pass'], $_POST['user_pass_re'], $_POST['user_nick'])){
		$user_id = addslashes(trim($_POST['user_id']));
		$user_pass = addslashes(trim($_POST['user_pass']));
		$user_pass_re = addslashes(trim($_POST['user_pass_re']));
		$user_pass_hash = password_hash($user_pass, PASSWORD_DEFAULT);
		$user_nick = addslashes(trim($_POST['user_nick']));

		if($user_pass != $user_pass_re)
			alert("비밀번호 확인이 틀렸습니다.");

		$member_rows = $db->query("SELECT * FROM MEMBER WHERE user_id='{$user_id}'");
		if($member_rows->num_rows)
			alert("이미 존재하는 아이디입니다.");

		$db->query("INSERT INTO MEMBER(user_id, user_pass, user_nick) values('{$user_id}','{$user_pass_hash}','{$user_nick}')");
		db_chk();
		$_SESSION['idx_me'] = $db->insert_id;
		alert("회원가입이 완료되었습니다.", '/');

	}
?>

<div class="wrapper_xs">
	<form class="y_form" method="POST">
		<h2>AutoBit 회원가입</h2>
		<label>
			<p>아이디</p>
			<input type="text" name="user_id" placeholder="아이디" required>
		</label>
		<label>
			<p>비밀번호</p>
			<input type="password" name="user_pass" placeholder="비밀번호" required>
		</label>
		<label>
			<p>비밀번호 확인</p>
			<input type="password" name="user_pass_re" placeholder="비밀번호 확인" required>
		</label>
		<label>
			<p>닉네임(특수문자 사용 불가)</p>
			<input type="text" name="user_nick" placeholder="닉네임" pattern="[a-zA-Z0-9_가-힣]+" required>
		</label>
		<button type="submit" class="btn_submit">회원가입</button>
	</form>
</div>