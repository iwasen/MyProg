<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:エコ診断計算用定数
'******************************************************/

// kWhからMcalへの換算
define('KWH_MCAL', 0.86);

// 季節ごとの日数
define('DAYS_WINTER', 117);
define('DAYS_SUMMER', 65);
define('DAYS_SP_AUT', 183);

// 季節ごとの水温[℃]
define('TMP_WINTER', 7);
define('TMP_SUMMER', 24);
define('TMP_SP_AUT', 15);

// 機器効率
define('EFF_GAS', 0.8);
define('EFF_ELE', 1.0);
define('EFF_OIL', 0.8);

// 平均入浴時間[分]
define('NYUUYOKU_JIKAN', 20);

// 湯温低下率
define('TEIKA_FUTAARI', 0.7);
define('TEIKA_FUTANASHI', 2.4);

// 年間冷暖房日数
define('DAYS_REIBOU', 77);
define('DAYS_DANBOU', 107);

// 晴天、雨天の日数
define('DAYS_FAIR', 275);
define('DAYS_RAIN', 90);

// 平日と休日の日数
define('DAYS_WEEKDAY', 240);
define('DAYS_HOLIDAY', 125);

// 三角コーナーの容量[kg]
define('SINK_CORNER', 0.7);
?>