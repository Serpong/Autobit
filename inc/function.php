<?
	if(!defined('AFTER_CONFIG')) exit('no config');


	//db function
	function db_chk()
	{
		global $db;
		if(mysqli_errno($db))
			alert('db error');
	}

	// json functions
	$json_return_arr = array();
	$json_return_arr['msg'] = 'success';
	$json_return_arr['error'] = false;
	$json_return_arr['data'] = null;
	function json_return(){
		global $json_return_arr;
		header('Content-Type: application/json');
		echo json_encode($json_return_arr);
		exit();
	}
	function json_error($msg="error", $error = 1)
	{
		global $json_return_arr;
		$json_return_arr['msg'] = $msg;
		$json_return_arr['error'] = $error;
		json_return();
	}
	function json_success($msg="success", $error = 0)
	{
		global $json_return_arr;
		$json_return_arr['msg'] = $msg;
		$json_return_arr['error'] = $error;
		json_return();
	}


	//이동관련 function
	function hs($str)
	{
		return htmlspecialchars($str, ENT_QUOTES);
	}
	function redirect($location){
		echo "<script>location.href='".$location."'</script>";
		exit();	
	}
	function redirect_back(){
		echo "<script>history.back();</script>";
		exit();
	}
	function alert($msg, $location = 'none'){
		echo "<script>alert('".hs($msg)."');</script>";
		if($location == 'none')
			redirect_back();
		else
			redirect($location);
	}

	function template_y_select($name, $arr_data, $active_key = '', $default_key = ''){
		if(isset($arr_data[$active_key]))
			$active_key = $active_key;
		elseif(isset($arr_data[$default_key]))				//default_key 문자열
			$active_key = $default_key;
		elseif(isset(array_keys($arr_data)[$default_key]))	//default_key 숫자
			$active_key = array_keys($arr_data)[$default_key];
		else
			$active_key = array_keys($arr_data)[0];
		

		ob_start();
		?>
		<div class="y_select">
			<input type="hidden" name="<?=$name?>" value="<?=$active_key?>">
			<div class="data_wrap">
				<span class="data"><?=$arr_data[$active_key]?></span>
				<ul class="y_option">
					<?php foreach ($arr_data as $key => $value): ?>
						<li data-value="<?=$key?>" class='<?=($active_key==$key)?'active':''?>'><?=$value?></li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<?
		return ob_get_clean();
	}


	function chk_page_active($str){
		global $page;

		if($page == $str)
			return 'active';
		else
			return '';
	}


	function get_min_dt($unit){
		return date("Y-m-d H:i:s", time() - $unit*60*1000);
	}


	function stat_format($num, $diamiter = 0){
		$result = number_format($num, 2);
		if($diamiter === false)
			$color = '';
		else
		{
			if($num > $diamiter)
				$color = 'red';
			elseif($num == $diamiter)
				$color = 'gray';
			else
				$color = 'blue';
		}

		return "<span class='{$color}'>{$result}</span>";
	}

?>