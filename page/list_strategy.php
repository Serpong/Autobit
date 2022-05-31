<?php
	$strategy_rows = $db->query("SELECT * FROM strategy");
?>
<div class="wrapper_xs">
	<div id="list_strategy_wrap">
		<span class="item head">
			<div>전략명</div>
			<div>월 평균 매매</div>
			<div>최대 손실</div>
			<div>승률</div>
			<div>수익률</div>
		</span>
		<? while($strategy_data = $strategy_rows->fetch_assoc()): ?>
			<a href="/<?=$strategy_data['idx_strategy']?>" class="item">
				<div class="name"><?=$strategy_data['name_strategy']?></div>
				<div><?=stat_format($strategy_data['count_trade'], false)?>회</div>
				<div><?=stat_format($strategy_data['percent_max_loss'], false)?>%</div>
				<div><?=stat_format($strategy_data['percent_win_rate'], 50)?>%</div>
				<div><?=stat_format($strategy_data['percent_profit'])?>%</div>
			</a>
		<? endwhile ?>
	</div>
</div>