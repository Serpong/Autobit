<?
	session_start();
	header("Content-Type:text/html;charset=utf-8");


	define("AFTER_CONFIG", 1);

	include $_SERVER['DOCUMENT_ROOT'] . "/inc/dbconfig.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/inc/function.php";


	$db = mysqli_connect($db_host, $db_id, $db_pass, $db_db);
	$db->query("SET NAMES utf8");
	db_chk();


	//variables
	$allowed_pages = array(
		'main',
		'login',
		'logout',
		'register',
		'manage_strategy',
		'list_strategy',
		'view_strategy',
		'new_strategy',
	);

	$list_coins = array();//array("KRW-BTC"=>"비트코인", "KRW-ETH"=>"이더리움", )
	$coin_rows = $db->query("SELECT * FROM coin");
	while ($coin_data = $coin_rows->fetch_assoc()) {
		$list_coins[$coin_data['market']] = $coin_data['name_coin'];
	}

	//가공
	/* page setting */
	if(isset($_GET['page']) && in_array($_GET['page'], $allowed_pages))
		$page = $_GET['page'];
	else
		$page = $allowed_pages[0];

	if(isset($_GET['sub_page']))
		$sub_page = addslashes($_GET['sub_page']);
	else
		$sub_page = "main";


	if(isset($_SESSION['idx_me'])){
		if(!is_numeric($_SESSION['idx_me']))
			$_SESSION['idx_me'] = null;
		else
		{
			$ME_rows = $db->query("SELECT idx_user, user_nick, user_id FROM member WHERE idx_user='{$_SESSION['idx_me']}' limit 1");
			db_chk();
			if($ME_rows->num_rows == 0)
				$_SESSION['idx_me'] = null;
			else
				$ME = $ME_rows->fetch_assoc();
		}
	}

	if(isset($_SESSION['idx_me']) && !empty($_SESSION['idx_me']))
		$is_logged_in = true;
	else
		$is_logged_in = false;
	
?>