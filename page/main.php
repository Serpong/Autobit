<?
	if(!defined('AFTER_CONFIG'))
		exit('no config');

	$high_profit_rows = $db->query("SELECT * FROM strategy order by percent_profit desc limit 4");
	$low_loss_rows = $db->query("SELECT * FROM strategy order by percent_max_loss desc limit 4");

?>

<div id="main_wrap" class="wrapper">

	<h3>
		수익률 상위 전략
		<div class="select_wrap">
			<span>비트코인</span>
			<span>2020.01.01~</span>
		</div>
	</h3>
	<div class="card_wrap">
		<? while($ITEM = $high_profit_rows->fetch_assoc()): ?>
			<div class="item">
				<a href="/1" class="name_item ellipsis_line1" title="<?=hs($ITEM['name_strategy'])?>"><?=hs($ITEM['name_strategy'])?></a>
				<div class="hashtag_wrap">
					<?
						$hashtags = explode(',', $ITEM['hashtag']);
						foreach ($hashtags as $value){
							if($value == '') continue;
							?><a href="#">#<?=$value?></a><?php
						}
					?>
				</div>
				<ul class="stat_wrap ac">
					<li class="stat1">
						<div class="title">월 평균 매매 횟수</div>
						<div class="data"><?=stat_format($ITEM['count_trade'], false)?>회</div>
					</li>
					<li class="stat2">
						<div class="title">기간 내 최대 손실</div>
						<div class="data"><?=stat_format($ITEM['percent_max_loss'], false)?>%</div>
					</li>
					<li class="stat3">
						<div class="title">기간 내 수익률</div>
						<div class="data"><?=stat_format($ITEM['percent_profit'])?>%</div>
					</li>
				</ul>
			</div>
		<? endwhile ?>
	</div>

	<h3>최저 손실 전략</h3>
	<div class="card_wrap">
		<? while($ITEM = $low_loss_rows->fetch_assoc()): ?>
			<div class="item">
				<a href="/1" class="name_item ellipsis_line1" title="<?=hs($ITEM['name_strategy'])?>"><?=hs($ITEM['name_strategy'])?></a>
				<div class="hashtag_wrap">
					<?
						$hashtags = explode(',', $ITEM['hashtag']);
						foreach ($hashtags as $value){
							if($value == '') continue;
							?><a href="#">#<?=$value?></a><?php
						}
					?>
				</div>
				<ul class="stat_wrap ac">
					<li class="stat1">
						<div class="title">월 평균 매매 횟수</div>
						<div class="data"><?=stat_format($ITEM['count_trade'], false)?>회</div>
					</li>
					<li class="stat2">
						<div class="title">기간 내 최대 손실</div>
						<div class="data"><?=stat_format($ITEM['percent_max_loss'], false)?>%</div>
					</li>
					<li class="stat3">
						<div class="title">기간 내 수익률</div>
						<div class="data"><?=stat_format($ITEM['percent_profit'])?>%</div>
					</li>
				</ul>
			</div>
		<? endwhile ?>
	</div>
</div>