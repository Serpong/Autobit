-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 22-05-31 13:55
-- 서버 버전: 10.4.20-MariaDB
-- PHP 버전: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `autobit`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `coin`
--

CREATE TABLE `coin` (
  `idx_coin` int(11) NOT NULL,
  `name_coin` varchar(255) NOT NULL,
  `market` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `coin`
--

INSERT INTO `coin` (`idx_coin`, `name_coin`, `market`) VALUES
(1, '비트코인', 'KRW-BTC'),
(2, '이더리움', 'KRW-ETH');

-- --------------------------------------------------------

--
-- 테이블 구조 `coin_price`
--

CREATE TABLE `coin_price` (
  `idx_coin_setting` int(11) NOT NULL,
  `ts_price` datetime NOT NULL DEFAULT current_timestamp(),
  `opening_price` double NOT NULL,
  `low_price` double NOT NULL,
  `high_price` double NOT NULL,
  `trade_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `coin_setting`
--

CREATE TABLE `coin_setting` (
  `idx_coin_setting` int(11) NOT NULL,
  `idx_coin` int(11) NOT NULL,
  `unit` mediumint(8) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `coin_setting`
--

INSERT INTO `coin_setting` (`idx_coin_setting`, `idx_coin`, `unit`) VALUES
(1, 1, 1440),
(2, 1, 60),
(3, 1, 5),
(4, 2, 1440),
(5, 2, 240),
(6, 1, 240),
(7, 1, 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `member`
--

CREATE TABLE `member` (
  `idx_user` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_nick` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `member`
--

INSERT INTO `member` (`idx_user`, `user_id`, `user_pass`, `user_nick`) VALUES
(1, 'test', '$2y$10$IfnESfYOLbqfN2mKSFHrDuqnCTBqxR3CorFtLUQdvdpOf84n2TIii', 'OPENMIND');

-- --------------------------------------------------------

--
-- 테이블 구조 `strategy`
--

CREATE TABLE `strategy` (
  `idx_strategy` int(11) NOT NULL,
  `name_strategy` varchar(255) NOT NULL,
  `hashtag` varchar(255) NOT NULL DEFAULT '',
  `count_trade` double NOT NULL DEFAULT 0,
  `percent_profit` double NOT NULL DEFAULT 0,
  `percent_max_loss` double NOT NULL DEFAULT 0,
  `percent_win_rate` double NOT NULL DEFAULT 0,
  `idx_user` int(11) NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `strategy`
--

INSERT INTO `strategy` (`idx_strategy`, `name_strategy`, `hashtag`, `count_trade`, `percent_profit`, `percent_max_loss`, `percent_win_rate`, `idx_user`, `code`) VALUES
(1, 'MACD', '안정적투자,장투용', 0.918506800917053, 704.5152913858298, -7.863904633904051, 69.23076923076923, 1, '#단순 이동 평균\r\ndef SMA(data,period = 30, column = \'close\'):\r\n	return data[column].rolling(window= period).mean()\r\n\r\n#지수 이동 평균\r\ndef EMA(data, period = 20, column = \'close\'):\r\n	return data[column].ewm(span=period, adjust=False).mean()\r\n\r\n#MACD\r\ndef MACD(data, period_short = 12, period_long = 26, period_signal = 9, column=\'close\'):\r\n	#단기 지수 이평선 계산\r\n	ShortEMA = EMA(data, period_short, column=column)\r\n	#장기 지수 이평선 계산\r\n	LongEMA  = EMA(data, period_long, column=column)\r\n	#이동 평균 수렴/발산 계산\r\n	data[\'MACD\'] = ShortEMA - LongEMA\r\n	#신호선 계산\r\n	data[\'Signal_Line\'] = EMA(data, period_signal, column=\'MACD\')\r\n	return data\r\n\r\n\r\ndef make_strategy(prices):\r\n	count = len(prices)\r\n	if count == 1 : \r\n		return\r\n	macd = MACD(prices)\r\n	today_macd = macd.iloc[-1][\'MACD\']\r\n	yesterday_macd = macd.iloc[-2][\'MACD\']\r\n	today_signal = macd.iloc[-1][\'Signal_Line\']\r\n	yesterday_signal = macd.iloc[-2][\'Signal_Line\']\r\n	\r\n	if yesterday_macd > yesterday_signal and today_macd < today_signal :\r\n		return \'sell\'\r\n	elif yesterday_macd < yesterday_signal and today_macd > today_signal :\r\n		return \'buy\''),
(2, 'RSI', '', 0.3061689334307775, 13.417918417180715, -19.90543735224586, 75, 1, 'def RSI(data, period = 14, column = \'close\'):\r\n	delta  = data[column].diff(1)\r\n	delta = delta.dropna()\r\n\r\n	up = delta.copy() # delta 값 복사\r\n	down = delta.copy() # delta 값 복사 \r\n	up[up < 0] = 0\r\n	down[down > 0] = 0\r\n	data[\'up\'] = up\r\n	data[\'down\'] = down\r\n\r\n	AVG_Gain = SMA(data, period, column=\'up\')\r\n	AVG_Loss = abs(SMA(data, period, column=\'down\'))\r\n	RS = AVG_Gain / AVG_Loss\r\n\r\n	RSI = 100.0 -(100.0/(1.0 + RS))\r\n	data[\'RSI\'] = RSI\r\n	return data\r\n\r\ndef make_strategy(prices):\r\n	rsi= RSI(prices) \r\n	rsi = rsi.iloc[-1][\'RSI\']\r\n\r\n	if rsi > 70 : \r\n		return \'sell\'\r\n		\r\n	elif rsi < 30 :\r\n		return \'buy\''),
(3, '볼린저밴드', '', 0.2381313925555729, -16.8818431900351, -15.678242249325013, 33.33333333333333, 1, 'def MA20(data, period = 20, column=\'close\'):\r\n	\r\n	ma20 =  data[column].rolling(window= period).mean()\r\n	return ma20\r\n\r\ndef make_strategy(prices):\r\n	#Bollinger Bands 전략\r\n	ma20 = MA20(prices)\r\n\r\n	bol_upper = ma20 + 2 * prices[\'close\'].rolling(window= 20).std()\r\n	bol_down = ma20  - 2 * prices[\'close\'].rolling(window = 20).std()\r\n\r\n	bol_upper = bol_upper.iloc[-1]\r\n	bol_down = bol_down.iloc[-1]\r\n	prices = prices.iloc[-1][\'close\']\r\n\r\n	if prices > bol_upper:\r\n		return \'sell\'\r\n	elif prices < bol_down:\r\n		return \'buy\''),
(4, '슬로우 스토캐스틱', '', 0.23813139242125764, 11.273315475595723, -15.13398881819934, 66.66666666666666, 1, 'def get_stochastic_fast_k(close_price, low, high, n=5):\r\n	fast_k = ((close_price - low.rolling(n).min()) / (high.rolling(n).max() - low.rolling(n).min())) * 100\r\n	return fast_k\r\n\r\n# Slow %K = Fast %K의 m기간 이동평균(SMA)\r\ndef get_stochastic_slow_k(fast_k, n=3):\r\n	slow_k = fast_k.rolling(n).mean()\r\n	return slow_k\r\n\r\n# Slow %D = Slow %K의 t기간 이동평균(SMA)\r\ndef get_stochastic_slow_d(slow_k, n=3):\r\n	slow_d = slow_k.rolling(n).mean()\r\n	return slow_d\r\n\r\ndef make_strategy(prices):\r\n\r\n	prices[\'close_price\'] = prices.iloc[-1][\'close\']\r\n	close_price= (prices.iloc[-1][\'close\'])\r\n\r\n	prices[\'fast_k\'] = get_stochastic_fast_k(prices[\'close\'], prices[\'low\'], prices[\'high\'], 5)\r\n	prices[\'slow_k\'] = get_stochastic_slow_k(prices[\'fast_k\'], 3)\r\n	prices[\'slow_d\'] = get_stochastic_slow_d(prices[\'slow_k\'], 3)\r\n\r\n	fast_k = (prices.iloc[-1][\'fast_k\'])\r\n	t_slow_k = (prices.iloc[-1][\'slow_k\'])\r\n	t_slow_d = (prices.iloc[-1][\'slow_d\'])\r\n	\r\n	count = len(prices)\r\n	if count == 1 : \r\n		return\r\n	y_slow_k = (prices.iloc[-2][\'slow_k\'])\r\n	y_slow_d = (prices.iloc[-2][\'slow_d\'])\r\n\r\n	\r\n	if  fast_k <= 25 and y_slow_d > y_slow_k and t_slow_k > t_slow_d:\r\n		return \'buy\'\r\n	\r\n	elif fast_k >= 75 and y_slow_k > y_slow_d and t_slow_d > t_slow_k:\r\n		return \'sell\''),
(5, '홀수짝수', '', 17.621723019322094, 133.89121948157404, -33.09148264984228, 56.201550387596896, 1, 'def make_strategy(prices):\r\n	if len(prices) == 1:\r\n		return\r\n		\r\n	if round(prices.iloc[-1][\'time\'] / (1440*60)) % 2 == 0:\r\n		return \'buy\'\r\n	else:\r\n		return \'sell\''),
(6, 'MACD 보완', '', 0.8504692572646111, 683.3338870918386, -13.072276002620276, 66.66666666666666, 1, 'def make_strategy(prices):\r\n	count = len(prices)\r\n	if count == 1 : \r\n		return\r\n		\r\n	macd1 = MACD(prices)\r\n	today_macd1 = macd1.iloc[-1][\'MACD\']\r\n	yesterday_macd1 = macd1.iloc[-2][\'MACD\']\r\n	today_signal1 = macd1.iloc[-1][\'Signal_Line\']\r\n	yesterday_signal1 = macd1.iloc[-2][\'Signal_Line\']\r\n\r\n	macd2 = MACD(prices, 6, 13, 5)\r\n	today_macd2 = macd2.iloc[-1][\'MACD\']\r\n	yesterday_macd2 = macd2.iloc[-2][\'MACD\']\r\n	today_signal2 = macd2.iloc[-1][\'Signal_Line\']\r\n	yesterday_signal2 = macd2.iloc[-2][\'Signal_Line\']\r\n	\r\n	if yesterday_macd1 > yesterday_signal1 and today_macd1 < today_signal1 :\r\n		return \'sell\'\r\n	elif yesterday_macd2 < yesterday_signal2 and today_macd2 > today_signal2 :\r\n		return \'buy\'');

-- --------------------------------------------------------

--
-- 테이블 구조 `strategy_data`
--

CREATE TABLE `strategy_data` (
  `idx_strategy_data` int(11) NOT NULL,
  `idx_strategy_setting` int(11) NOT NULL,
  `ts_trade` timestamp NULL DEFAULT NULL,
  `is_buy` tinyint(1) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `strategy_setting`
--

CREATE TABLE `strategy_setting` (
  `idx_strategy_setting` int(11) NOT NULL,
  `idx_strategy` int(11) NOT NULL,
  `idx_coin` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `dt_last_check` datetime DEFAULT NULL,
  `last_flag` varchar(20) NOT NULL DEFAULT 'sell'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `coin`
--
ALTER TABLE `coin`
  ADD PRIMARY KEY (`idx_coin`);

--
-- 테이블의 인덱스 `coin_price`
--
ALTER TABLE `coin_price`
  ADD PRIMARY KEY (`idx_coin_setting`,`ts_price`);

--
-- 테이블의 인덱스 `coin_setting`
--
ALTER TABLE `coin_setting`
  ADD PRIMARY KEY (`idx_coin_setting`);

--
-- 테이블의 인덱스 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`idx_user`);

--
-- 테이블의 인덱스 `strategy`
--
ALTER TABLE `strategy`
  ADD PRIMARY KEY (`idx_strategy`);

--
-- 테이블의 인덱스 `strategy_data`
--
ALTER TABLE `strategy_data`
  ADD PRIMARY KEY (`idx_strategy_data`),
  ADD KEY `idx_strategy_setting` (`idx_strategy_setting`);

--
-- 테이블의 인덱스 `strategy_setting`
--
ALTER TABLE `strategy_setting`
  ADD PRIMARY KEY (`idx_strategy_setting`),
  ADD KEY `idx_strategy` (`idx_strategy`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `coin`
--
ALTER TABLE `coin`
  MODIFY `idx_coin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 테이블의 AUTO_INCREMENT `coin_setting`
--
ALTER TABLE `coin_setting`
  MODIFY `idx_coin_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 테이블의 AUTO_INCREMENT `member`
--
ALTER TABLE `member`
  MODIFY `idx_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 테이블의 AUTO_INCREMENT `strategy`
--
ALTER TABLE `strategy`
  MODIFY `idx_strategy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 테이블의 AUTO_INCREMENT `strategy_data`
--
ALTER TABLE `strategy_data`
  MODIFY `idx_strategy_data` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `strategy_setting`
--
ALTER TABLE `strategy_setting`
  MODIFY `idx_strategy_setting` int(11) NOT NULL AUTO_INCREMENT;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `strategy_data`
--
ALTER TABLE `strategy_data`
  ADD CONSTRAINT `strategy_data_ibfk_1` FOREIGN KEY (`idx_strategy_setting`) REFERENCES `strategy_setting` (`idx_strategy_setting`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 테이블의 제약사항 `strategy_setting`
--
ALTER TABLE `strategy_setting`
  ADD CONSTRAINT `strategy_setting_ibfk_1` FOREIGN KEY (`idx_strategy`) REFERENCES `strategy` (`idx_strategy`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
