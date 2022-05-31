<?php
	if($is_logged_in)
		alert("이미 로그인 되어있습니다.");

	if(isset($_POST['user_id'], $_POST['user_pass'])){
		$user_id = addslashes(trim($_POST['user_id']));
		$user_pass = addslashes(trim($_POST['user_pass']));

		$member_rows = $db->query("SELECT idx_user, user_pass FROM MEMBER WHERE user_id='{$user_id}'");
		if($member_rows->num_rows == 0)
			alert("아이디나 패스워드가 틀렸습니다.");
		$member_data = $member_rows->fetch_assoc();

		if(!password_verify($user_pass, $member_data['user_pass']))
			alert("아이디나 패스워드가 틀렸습니다.");

		$_SESSION['idx_me'] = $member_data['idx_user'];
		alert("로그인이 완료되었습니다.", '/');

	}
?>
<div class="wrapper_xs">
	<form class="y_form" method="POST">
		<h2>AutoBit 로그인</h2>
		<label>
			<p>아이디</p>
			<input type="text" name="user_id" placeholder="아이디" required>
		</label>
		<label>
			<p>비밀번호</p>
			<input type="password" name="user_pass" placeholder="비밀번호" required>
		</label>
		<button type="submit" class="btn_submit">로그인</button>
	</form>
</div>