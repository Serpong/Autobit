<?
	if(!defined('AFTER_CONFIG'))
		exit('no config');

	
	if(isset($_GET['unit']))
		$unit = addslashes($_GET['unit']);
	else
		$unit = 1440;
	
	if(isset($_GET['market']))
		$market = addslashes($_GET['market']);
	else
		$market = "KRW-BTC";

	$min_start_ts = strtotime(get_min_dt($unit));

	if(isset($_GET['date_start']) && strtotime($_GET['date_start']) > $min_start_ts)
		$date_start = addslashes($_GET['date_start']);
	else
		$date_start = date("Y-m-d", $min_start_ts);


	$idx_strategy = addslashes($_GET['idx_strategy']);
	$strategy_rows = $db->query("SELECT * FROM strategy WHERE idx_strategy='{$idx_strategy}' limit 1;");

	if($strategy_rows->num_rows == 0)
		alert("존재하지 않는 전략 입니다.");
	$strategy_data = $strategy_rows->fetch_assoc();

?>
<script type="text/javascript">
	const ws = new WebSocket("ws://localhost:3000");

	var markers = [];
	var is_new = true;
	var close = {};
	var result_profit = 100;
	var bnh_profit = 100;
	var buy_price = 0;
	var last_i = 0;


	var market			= "<?=$market?>";
	var unit			= <?=$unit?>;
	var idx_strategy	= <?=$idx_strategy?>;
	var date_start		= "<?=$date_start?>";
	var my_name			= "<?=($is_logged_in?$ME['user_nick']:('비회원'.substr(md5(rand()),0,5)) )?>";

	function template_recent_signal(i, data){
		var result = '';
		var data_profit_el = (data['profit']>=0?"+":'')+round2(data['profit']);
		var result_profit_el = round2(result_profit - 100);
		data['dt'] = new Date(data['time']*1000).format("Y-m-d H:i");
		if(data['is_buy']){
			result+="<tr class='buy'>";
			result+="<td>#"+i+"</td>";
			result+="<td class='red'>매수</td>";
			result+="<td>"+data['dt']+"</td>";
			result+="<td>"+comma(data['price'])+"원</td>";
			result+="<td>-</td>";
			result+="<td></td>";
			result+="</tr>";
		}else{
			result+="<tr class='sell'>";
			result+="<td rowspan='2'>#"+i+"</td>";
			result+="<td class='blue'>매도</td>";
			result+="<td>"+data['dt']+"</td>";
			result+="<td>"+comma(data['price'])+"원</td>";
			result+="<td rowspan='2' class='"+(data['profit']>=0?'red':"blue")+"'>"+data_profit_el+"%</td>";
			result+="<td rowspan='2' class='"+(result_profit_el>=0?'red':"blue")+"'>"+result_profit_el+"%</td>";
			result+="</tr>";
		}
		return result;
	}

	function view_strategy(market, unit, idx_strategy, date_start){
		is_new = true;
		markers = [];
		close = {};
		result_profit = 100;
		bnh_profit = 100;
		buy_price = 0;
		last_i = 0;
		ws.send(JSON.stringify({action:"view_strategy", fetch_info:{market:market,unit:unit,idx_strategy:idx_strategy,date_start:date_start}}));
	}
	function get_closes(prices){
		var result = {};
		for (var i = 0; i < prices.length; i++) {
			result[prices[i]['time']] = prices[i]['close'];
		}
		return result;
	}


	ws.onmessage = function(recv){
		var data = JSON.parse(recv.data);

		if(data.mode == 'update_chart'){
			close = Object.assign({}, close, get_closes(data["list_price"])); //가져와서 merge

			//가격 업데이트
			if(is_new)
			{
				chart_data.setData(data["list_price"]);

				is_new = false;
				//chart.timeScale().fitContent();
			}
			else
			{
				for (var i = 0; i < data["list_price"].length; i++) {
					chart_data.update(data["list_price"][i]);
				}
			}


			if(data['list_strategy_data'].length){
				var html_recent_signal = '';
				for (var i = 0; i < data['list_strategy_data'].length; i++) {
					const current_data = data['list_strategy_data'][i];

					//마커추가
					const is_buy = current_data['is_buy']=='1';

					if(buy_price == 0 && !is_buy) // 시작이 매도면 패스
						continue;

					var trade_number = Math.floor((last_i+i)/2)+1;

					markers.push({
						time: 		current_data['time'],
						position: 	is_buy?'aboveBar':'belowBar',
						color: 		is_buy?'#d24f45':'#1261c4',
						shape: 		is_buy?'arrowDown':'arrowUp',
						text: 		(is_buy?'매수':'매도')+"#"+trade_number,
					});


					//내역 추가
					current_data['price'] = close[current_data['time']];

					if(is_buy){
						buy_price = current_data['price'];
					}else{
						current_data['profit'] = get_profit(buy_price, current_data['price']);

						result_profit *= (current_data['profit']+100)/100
					}
					html_recent_signal = template_recent_signal(trade_number, current_data) + html_recent_signal;
				}
				last_i += data['list_strategy_data'].length;

				chart_data.setMarkers(markers);
				$('.recent_signal_wrap tbody').prepend(html_recent_signal);

				//바이앤홀드
				if(markers.length)
				{
					var first_price = close[markers[0]['time']];
					var last_price = close[Object.keys(close)[Object.keys(close).length - 1]];
					bnh_profit = get_profit(first_price, last_price);
				}
				$('#table_result_sub .data_times').text(Math.floor(last_i/2)+"회");
				$('#table_result_sub .data_bnh_profit').text(comma(round2(bnh_profit))+"%");
				$('#table_result_sub .data_result_profit').text(comma(round2(result_profit-100))+"%");
			}
		}
		else if(data.mode == 'recv_chat'){
			var tmp_html = $("<div><span>"+data.chat_data['name']+":</span></div>");
			tmp_html.append(data.chat_data['cont']);
			$('#view_strategy_chat').append(tmp_html);
			$('#view_strategy_chat').get(0).scrollTop = 999999;
		}
	}
	ws.onopen = function(){
		setTimeout(function(){
			view_strategy(market, unit, idx_strategy, date_start);
		}, 300); // 임시
	}

</script>
<script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>

<div id="view_strategy_wrap">
	<div class="top_wrap wrapper_l">
		<div class="left box">
			<div class="info_wrap">
				<div class="name_wrap">
					<div class="item_name"><?=$strategy_data['name_strategy']?></div>
					<div class="hashtag_wrap">
						<?php
							$hashtags = explode(',', $strategy_data['hashtag']);
							foreach ($hashtags as $value){
								if($value == '') continue;
								?><a href="#">#<?=$value?></a><?php
							}
						?>
					</div>
				</div>
			</div>

			<div id="view_strategy_chart"></div>
		</div>
		<script type="text/javascript">
			$(function(){
				$('.y_select .data').click(function(){
					if($(this).parents('.y_select').hasClass('active')){
						$('.y_select').removeClass('active');
						$(this).parents('.y_select').removeClass('active');
					}else{
						$('.y_select').removeClass('active');
						$(this).parents('.y_select').addClass('active');
					}
				});
				$('.y_select .y_option>li').click(function(){
					var el_y_select = $(this).parents('.y_select');

					el_y_select.find('li').removeClass('active');
					$(this).addClass('active');
					el_y_select.find('.data').text($(this).text());
					el_y_select.find('input[type=hidden]').val($(this).data('value'));
					el_y_select.removeClass('active');
				});
				$('body').click(function(e){
					if($(e.target).parents('.y_select').length == 0)
						$('.y_select').removeClass('active');
				});
			});
		</script>
		<div class="right box">
			<div class="right_dashed">전략 설정</div>
			<form>
				<div class="row">
					<span class="title">코인명</span>
					<?=template_y_select("market", $list_coins, isset($_GET['market'])?$_GET['market']:'', 0)?>
				</div>
				<div class="row">
					<span class="title">매매단위</span>
					<?=template_y_select("unit", array("1"=>"1분", "5"=>"5분", "60"=>"1시간", "240"=>"4시간", "1440"=>"1일", ), isset($_GET['unit'])?$_GET['unit']:'', '1440')?>
				</div>
				<div class="row">
					<span class="title">시작일</span>
					<span><label class="y_input"><input type="text" data-toggle="datepicker" name="date_start" value="<?=$date_start?>"></label></span>
				</div>
				<div class="row">
					<span class="title"></span>
					<span><button type="submit" class="y_button">전략실행</span></button>
				</div>
			</form>
			<div class="right_dashed">전략 결과</div>
			<table id="table_result_sub">
				<tbody>
					<tr>
						<th>매매 횟수</th>
						<td><span class="data_times"></span></td>
					</tr>
					<tr>
						<th>바이앤홀드 수익</th>
						<td><span class="data_bnh_profit"></span></td>
					</tr>
					<tr>
						<th>전략 수익</th>
						<td><span class="data_result_profit"></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="bottom_wrap wrapper_l">
		<div class="left box">
			<button class="btn_tab active" data-tab-idx='1'>최근 거래</button>
			<button class="btn_tab" data-tab-idx='2' style="display:none;">상세결과</button>
			<div class="tab_wrap">
				<div class="active" data-tab='1'>
					<table class="table_recent_signal" style="width:calc(100% - 18px)">
						<thead>
							<tr>
								<th>거래 #</th>
								<th>신호</th>
								<th>날짜</th>
								<th>가격</th>
								<th>수익률</th>
								<th>누적 수익률</th>
							</tr>
						</thead>
					</table>
					<div class="recent_signal_wrap">
						<table class="table_recent_signal">
							<tbody></tbody>
						</table>
					</div>
				</div>
				<div data-tab='2'>
					상세결과입니다.
				</div>
			</div>
		</div>
		<div class="right box">
			<!-- 인기 전략 -->
			실시간 채팅
			<div id="view_strategy_chat">
				<div class="hint">채팅방에 연결되었습니다.</div>
			</div>
			<input id="view_strategy_chat_input" type="text" placeholder="메시지를 입력해주세요.">
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.btn_tab').click(function(){
		$(this).siblings().removeClass('active');
		$(this).addClass('active');

		var tab = $("[data-tab="+$(this).data('tab-idx')+"]");
		tab.siblings().hide();
		tab.show();
	});
</script>


<script type="text/javascript">
	const chart = LightweightCharts.createChart($('#view_strategy_chart').get(0), {
		width: 910, height: 300,
		timeScale: {
			timeVisible: true,
			borderColor: '#D1D4DC',
			tickMarkType:3,
			rightOffset:1,
			barSpacing:10,
	        /*tickMarkFormatter: (time, tickMarkType, locale) => {
	            console.log(time, tickMarkType, locale);
	            const year = LightweightCharts.isBusinessDay(time) ? time.year : new Date(time * 1000).getUTCFullYear();
	            return String(year);
	        },*/
		},
		crosshair: {
			mode: LightweightCharts.CrosshairMode.Normal,
		},
		rightPriceScale: {
			borderColor: '#D1D4DC',
			mode: LightweightCharts.PriceScaleMode.Logarithmic,
		},
		layout: {
			backgroundColor: '#ffffff',
			textColor: '#000',
		},
		grid: {
			horzLines: {
				color: '#F0F3FA',
			},
			vertLines: {
				color: '#F0F3FA',
			},
		},
		localization: {
			locale: 'ko-KR',
      		dateFormat: 'yyyy-MM-dd',
      		/*timeFormat: '',*/
		},
	});
	
	var chart_data = chart.addCandlestickSeries({
		upColor: '#d24f45',
		downColor: '#1261c4',
		wickUpColor: '#d24f45',
		wickDownColor: '#1261c4',
		borderVisible: false,
	});
	
</script>