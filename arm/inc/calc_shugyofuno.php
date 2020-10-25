<?
/******************************************************
* system  : xxx リスク診断
* title   : 就業不能計算処理
* content : リスク診断に於ける就業不能関連の計算を行う
* version : 1.0
* author  : aizawa
*******************************************************/

// ■リスク診断／就業不能データ計算
function calc_sf_data() {
	global $g_encode, $g_const, $g_sheets;

	$n = '就業不能';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// 本人
	$c['C68'] = $s['基本']['ER3'] + $s['基本']['EV3'];
	$c['C69'] = $s['基本']['ER4'] + $s['基本']['EV4'];
	$c['C70'] = $s['基本']['ER5'] + $s['基本']['EV5'];
	$c['C71'] = VB_SUM($n, 'C68:C70');
	$c['D68'] = $s['基本']['ET3'];
	$c['D69'] = $s['基本']['ET4'];
	$c['D70'] = $s['基本']['ET5'];
	$c['D71'] = VB_SUM($n, 'D68:D70');
	$c['E68'] = $s['基本']['EU3'];
	$c['E69'] = $s['基本']['EU4'];
	$c['E70'] = $s['基本']['EU5'];
	$c['E71'] = VB_SUM($n, 'E68:E70');
	$c['F68'] = $s['基本']['ES3'];
	$c['F69'] = $s['基本']['ES4'];
	$c['F70'] = $s['基本']['ES5'];
	$c['F71'] = VB_SUM($n, 'F68:F70');
	$c['G68'] = VB_SUM($n, 'C68:F68');
	$c['G69'] = VB_SUM($n, 'C69:F69');
	$c['G70'] = VB_SUM($n, 'C70:F70');
	$c['G71'] = VB_SUM($n, 'G68:G70');
	$c['F75'] = 0;
	$c['K45'] = $s['基本']['C49'];
	$c['B47'] = $s['基本']['FL3'];
	$c['B48'] = $s['基本']['FL4'];
	$c['B49'] = $s['基本']['FL5'];
	$c['B50'] = VB_SUM($n, 'B47:B49');
	$c['C47'] = $c['G68'];
	$c['C48'] = $c['G69'];
	$c['C49'] = $c['G70'];
	$c['C50'] = VB_SUM($n, 'C47:C49');
	$c['D47'] = $s['基本']['FP3'];
	$c['D48'] = 0;
	$c['D49'] = 0;
	$c['D50'] = VB_SUM($n, 'D47:D49');
	$c['E47'] = 0;
	$c['E48'] = $s['基本']['FN4'] + $s['基本']['FO4'];
	$c['E49'] = $s['基本']['FN5'] + $s['基本']['FO5'];
	$c['E50'] = VB_SUM($n, 'E47:E49');
	$c['F47'] = $s['基本']['FQ3'];
	$c['F48'] = $s['基本']['FR4'];
	$c['F49'] = 0;
	$c['F50'] = VB_SUM($n, 'F47:F49');
	$c['G47'] = $s['基本']['FV3'];
	$c['G48'] = $s['基本']['FV4'];
	$c['G49'] = $s['基本']['FV5'];
	$c['G50'] = VB_SUM($n, 'G47:G49');
	$c['H47'] = $s['基本']['FM3'];
	$c['H48'] = $s['基本']['FM4'];
	$c['H49'] = $s['基本']['FM5'];
	$c['H50'] = VB_SUM($n, 'H47:H49');
	$c['J47'] = $s['生保']['I129'];
	$c['J48'] = $s['生保']['I130'];
	$c['J49'] = $s['生保']['I131'];
	$c['J50'] = VB_SUM($n, 'J47:J49');
	$c['K47'] = MAX(0, MIN($c['K45'], $c['C47'] - VB_SUM($n, 'D47:J47')));
	$c['K48'] = MAX(0, MIN($c['K45'] - VB_SUM($n, 'K47:K47'), $c['C48'] - VB_SUM($n, 'D48:J48')));
	$c['K49'] = MAX(0, MIN($c['K45'] - VB_SUM($n, 'K47:K48'), $c['C49'] - VB_SUM($n, 'D49:J49')));
	$c['K50'] = VB_SUM($n, 'K47:K49');
	$c['L47'] = VB_SUM($n, 'D47:K47');
	$c['L48'] = VB_SUM($n, 'D48:K48');
	$c['L49'] = VB_SUM($n, 'D49:K49');
	$c['L50'] = VB_SUM($n, 'D50:K50');
	$c['M47'] = VB_SUM($n, 'D47:K47') / $c['C47'];
	$c['M48'] = VB_SUM($n, 'D48:K48') / $c['C48'];
	$c['M49'] = VB_SUM($n, 'D49:K49') / $c['C49'];
	$c['N47'] = $c['B47'] * 3 / (VB_SUM($n, 'B47:B48') * 3 + $c['B49']) * (1 - $c['F75']);
	$c['N48'] = $c['B48'] * 3 / (VB_SUM($n, 'B47:B48') * 3 + $c['B49']) * (1 - $c['F75']);
	$c['N49'] = $c['B49'] / (VB_SUM($n, 'B47:B48') * 3 + $c['B49']) * (1 - $c['F75']);
	$c['C4'] = $c['B47'];
	$c['C5'] = $c['C68'];
	$c['C6'] = $c['D68'];
	$c['C7'] = $c['E68'];
	$c['C8'] = $c['F68'];
	$c['C9'] = VB_SUM($n, 'C5:C8');
	$c['C11'] = $c['D47'] + $c['E47'];
	$c['C12'] = $c['F47'] + $c['G47'];
	$c['C13'] = $c['H47'] + $c['K47'];
	$c['C14'] = $c['J47'];
	$c['C15'] = VB_SUM($n, 'C11:C14');
	$c['C16'] = VB_IF($c['C9'] > 0, $c['C15'] / $c['C9'], 0);
	$c['E4'] = $c['B48'];
	$c['E5'] = $c['C69'];
	$c['E6'] = $c['D69'];
	$c['E7'] = $c['E69'];
	$c['E8'] = $c['F69'];
	$c['E9'] = $c['G69'];
	$c['E11'] = $c['D48'] + $c['E48'];
	$c['E12'] = $c['F48'] + $c['G48'];
	$c['E13'] = $c['H48'] + $c['K48'];
	$c['E14'] = $c['J48'];
	$c['E15'] = VB_SUM($n, 'E11:E14');
	$c['E16'] = VB_IF($c['E9'] > 0, $c['E15'] / $c['E9'], 0);
	$c['G4'] = $c['B49'];
	$c['G5'] = $c['C70'];
	$c['G6'] = $c['D70'];
	$c['G7'] = $c['E70'];
	$c['G8'] = $c['F70'];
	$c['G9'] = $c['G70'];
	$c['G11'] = $c['D49'] + $c['E49'];
	$c['G12'] = $c['F49'] + $c['G49'];
	$c['G13'] = $c['H49'] + $c['K49'];
	$c['G14'] = $c['J49'];
	$c['G15'] = VB_SUM($n, 'G11:G14');
	$c['G16'] = VB_IF($c['G9'] > 0, $c['G15'] / $c['G9'], 0);
	$c['J4'] = $c['C4'] + $c['E4'] + $c['G4'];
	$c['J5'] = $c['C71'];
	$c['J6'] = $c['D71'];
	$c['J7'] = $c['E71'];
	$c['J8'] = $c['F71'];
	$c['J9'] = $c['G71'];
	$c['J11'] = $c['C11'] + $c['E11'] + $c['G11'];
	$c['J12'] = $c['C12'] + $c['E12'] + $c['G12'];
	$c['J13'] = $c['C13'] + $c['E13'] + $c['G13'];
	$c['J14'] = $c['C14'] + $c['E14'] + $c['G14'];
	$c['J15'] = $c['C15'] + $c['E15'] + $c['G15'];
	$c['J16'] = VB_IF($c['J9'] > 0, $c['J15'] / $c['J9'], 0);

	$c['B36'] = $c['C47'];
	$c['B37'] = $c['C48'];
	$c['B38'] = $c['C49'];
	$c['C36'] = VB_IF($c['C47'] > 0, VB_SUM($n, 'D47:E47') / $c['C47'] * 100, 0);
	$c['C37'] = VB_IF($c['C48'] > 0, VB_SUM($n, 'D48:E48') / $c['C48'] * 100, 0);
	$c['C38'] = VB_IF($c['C49'] > 0, VB_SUM($n, 'D49:E49') / $c['C49'] * 100, 0);
	$c['D36'] = VB_IF($c['C47'] > 0, VB_SUM($n, 'F47:G47') / $c['C47'] * 100, 0);
	$c['D37'] = VB_IF($c['C48'] > 0, VB_SUM($n, 'F48:G48') / $c['C48'] * 100, 0);
	$c['D38'] = VB_IF($c['C49'] > 0, VB_SUM($n, 'F49:G49') / $c['C49'] * 100, 0);
	$c['E36'] = VB_IF($c['C47'] > 0, ($c['H47'] + $c['K47']) / $c['C47'] * 100, 0);
	$c['E37'] = VB_IF($c['C48'] > 0, ($c['H48'] + $c['K48']) / $c['C48'] * 100, 0);
	$c['E38'] = VB_IF($c['C49'] > 0, ($c['H49'] + $c['K49']) / $c['C49'] * 100, 0);
	$c['F36'] = VB_IF($c['C47'] > 0, $c['J47'] / $c['C47'] * 100, 0);
	$c['F37'] = VB_IF($c['C48'] > 0, $c['J48'] / $c['C48'] * 100, 0);
	$c['F38'] = VB_IF($c['C49'] > 0, $c['J49'] / $c['C49'] * 100, 0);
	$c['C62'] = 100;
	$c['C63'] = 100;
	$c['C64'] = 100;
	$c['C65'] = 100;
	$c['D62'] = $c['C36'];
	$c['D63'] = $c['C37'];
	$c['D64'] = $c['C38'];
	$c['E62'] = $c['D36'];
	$c['E63'] = $c['D37'];
	$c['E64'] = $c['D38'];
	$c['F62'] = $c['E36'];
	$c['F63'] = $c['E37'];
	$c['F64'] = $c['E38'];
	$c['G62'] = $c['F36'];
	$c['G63'] = $c['F37'];
	$c['G64'] = $c['F38'];
	$c['H62'] = $c['N47'];
	$c['H63'] = $c['N48'];
	$c['H64'] = $c['N49'];
	$c['I62'] = MIN(100, VB_SUM($n, 'D62:G62'));
	$c['I63'] = MIN(100, VB_SUM($n, 'D63:G63'));
	$c['I64'] = MIN(100, VB_SUM($n, 'D64:G64'));
	$c['D65'] = ($c['H62'] * $c['D62']) + ($c['H63'] * $c['D63']) + ($c['H64'] * $c['D64']);
	$c['E65'] = ($c['H62'] * $c['E62']) + ($c['H63'] * $c['E63']) + ($c['H64'] * $c['E64']);
	$c['F65'] = ($c['H62'] * $c['F62']) + ($c['H63'] * $c['F63']) + ($c['H64'] * $c['F64']);
	$c['G65'] = ($c['H62'] * $c['G62']) + ($c['H63'] * $c['G63']) + ($c['H64'] * $c['G64']);
	$c['I65'] = ($c['H62'] * $c['I62']) + ($c['H63'] * $c['I63']) + ($c['H64'] * $c['I64']);
	$c['C39'] = $c['D65'];
	$c['D39'] = $c['E65'];
	$c['E39'] = $c['F65'];
	$c['F39'] = $c['G65'];
	$c['G36'] = $c['I62'];
	$c['G37'] = $c['I63'];
	$c['G38'] = $c['I64'];
	$c['G39'] = $c['I65'];

	// 配偶者
	$c['R68'] = $s['基本']['EX3'] + $s['基本']['FB3'];
	$c['R69'] = $s['基本']['EX4'] + $s['基本']['FB4'];
	$c['R70'] = $s['基本']['EX5'] + $s['基本']['FB5'];
	$c['R71'] = VB_SUM($n, 'R68:R70');
	$c['S68'] = $s['基本']['EY3'];
	$c['S69'] = $s['基本']['EY4'];
	$c['S70'] = $s['基本']['EY5'];
	$c['S71'] = VB_SUM($n, 'S68:S70');
	$c['T68'] = $s['基本']['EZ3'];
	$c['T69'] = $s['基本']['EZ4'];
	$c['T70'] = $s['基本']['EZ5'];
	$c['T71'] = VB_SUM($n, 'T68:T70');
	$c['U68'] = $s['基本']['FA3'];
	$c['U69'] = $s['基本']['FA4'];
	$c['U70'] = $s['基本']['FA5'];
	$c['U71'] = VB_SUM($n, 'U68:U70');
	$c['V68'] = VB_SUM($n, 'R68:U68');
	$c['V69'] = VB_SUM($n, 'R69:U69');
	$c['V70'] = VB_SUM($n, 'R70:U70');
	$c['V71'] = VB_SUM($n, 'R71:U71');
	$c['U78'] = 0;
	$c['W45'] = $s['基本']['C49'];
	$c['Q47'] = $s['基本']['FL8'];
	$c['Q48'] = $s['基本']['FL10'];
	$c['Q49'] = VB_SUM($n, 'Q47:Q48');
	$c['R47'] = $c['V68'];
	$c['R48'] = $c['V70'];
	$c['R49'] = VB_SUM($n, 'R47:R48');
	$c['S47'] = $s['基本']['FP8'];
	$c['S48'] = 0;
	$c['S49'] = VB_SUM($n, 'S47:S48');
	$c['T47'] = 0;
	$c['T48'] = $s['基本']['FN10'] + $s['基本']['FO10'];
	$c['T49'] = VB_SUM($n, 'T47:T48');
	$c['U47'] = $s['基本']['FM8'];
	$c['U48'] = $s['基本']['FM10'];
	$c['U49'] = VB_SUM($n, 'U47:U48');
	$c['V47'] = $s['生保']['I254'];
	$c['V48'] = $s['生保']['I256'];
	$c['V49'] = VB_SUM($n, 'V47:V48');
	$c['W47'] = MAX(MIN($c['W45'], $c['R47'] - VB_SUM($n, 'S47:V47')), 0);
	$c['W48'] = MAX(0, MIN($c['W45'] - VB_SUM($n, 'W47:W47'), $c['R48'] - VB_SUM($n, 'S48:V48')));
	$c['W49'] = VB_SUM($n, 'W47:W48');
	$c['Y47'] = VB_SUM($n, 'S47:W47');
	$c['Y48'] = VB_SUM($n, 'S48:W48');
	$c['Y49'] = VB_SUM($n, 'S49:W49');
	$c['Z47'] = VB_SUM($n, 'S47:W47') / $c['R47'];
	$c['Z48'] = VB_SUM($n, 'S48:W48') / $c['R48'];
	$c['AA47'] = $c['Q47'] * 3 / (VB_SUM($n, 'Q47:Q47') * 3 + $c['Q48']) * (1 - $c['U78']);
	$c['AA48'] = $c['Q48'] / (VB_SUM($n, 'Q47:Q47') * 3 + $c['Q48']) * (1 - $c['U78']);
	$c['R4'] = $c['Q47'];
	$c['R5'] = $c['R68'];
	$c['R6'] = $c['T68'];
	$c['R7'] = $c['U68'];
	$c['R8'] = $c['S68'];
	$c['R9'] = $c['V68'];
	$c['R11'] = $c['S47'] + $c['T47'];
	$c['R12'] = 0;
	$c['R13'] = $c['U47'] + $c['W47'];
	$c['R14'] = $c['V47'];
	$c['R15'] = VB_SUM($n, 'R11:R14');
	$c['R16'] = VB_IF($c['R9'] > 0, $c['R15'] / $c['R9'], 0);
	$c['T4'] = 0;
	$c['T5'] = 0;
	$c['T6'] = 0;
	$c['T7'] = 0;
	$c['T8'] = 0;
	$c['T9'] = 0;
	$c['T11'] = 0;
	$c['T12'] = 0;
	$c['T13'] = 0;
	$c['T14'] = 0;
	$c['T15'] = 0;
	$c['V4'] = $c['Q48'];
	$c['V5'] = $c['R70'];
	$c['V6'] = $c['T70'];
	$c['V7'] = $c['U70'];
	$c['V8'] = $c['S70'];
	$c['V9'] = $c['V70'];
	$c['V11'] = $c['S48'] + $c['T48'];
	$c['V12'] = 0;
	$c['V13'] = $c['U48'] + $c['W48'];
	$c['V14'] = $c['V48'];
	$c['V15'] = VB_SUM($n, 'V11:V14');
	$c['V16'] = VB_IF($c['V9'] > 0, $c['V15'] / $c['V9'], 0);
	$c['Y4'] = $c['R4'] + $c['T4'] + $c['V4'];
	$c['Y5'] = $c['R71'];
	$c['Y6'] = $c['T71'];
	$c['Y7'] = $c['U71'];
	$c['Y8'] = $c['S71'];
	$c['Y9'] = $c['V71'];
	$c['Y11'] = $c['R11'] + $c['T11'] + $c['V11'];
	$c['Y12'] = 0;
	$c['Y13'] = $c['R13'] + $c['T13'] + $c['V13'];
	$c['Y14'] = $c['R14'] + $c['T14'] + $c['V14'];
	$c['Y15'] = $c['R15'] + $c['T15'] + $c['V15'];
	$c['Y16'] = VB_IF($c['Y9'] > 0, $c['Y15'] / $c['Y9'], 0);

	$c['Q36'] = $c['R47'];
	$c['Q37'] = $c['R48'];
	$c['R36'] = VB_IF($c['R47'] > 0, VB_SUM($n, 'S47:T47') / $c['R47'] * 100, 0);
	$c['R37'] = VB_IF($c['R48'] > 0, VB_SUM($n, 'S48:T48') / $c['R48'] * 100, 0);
	$c['S36'] = 0;
	$c['S37'] = 0;
	$c['T36'] = VB_IF($c['R47'] > 0, ($c['U47'] + $c['W47']) / $c['R47'] * 100, 0);
	$c['T37'] = VB_IF($c['R48'] > 0, ($c['U48'] + $c['W48']) / $c['R48'] * 100, 0);
	$c['U36'] = VB_IF($c['R47'] > 0, $c['V47'] / $c['R47'] * 100, 0);
	$c['U37'] = VB_IF($c['R47'] > 0, $c['V48'] / $c['R48'] * 100, 0);
	$c['R62'] = 100;
	$c['R63'] = 100;
	$c['R64'] = 100;
	$c['S62'] = $c['R36'];
	$c['S63'] = $c['R37'];
	$c['T62'] = $c['S36'];
	$c['T63'] = $c['S37'];
	$c['U62'] = $c['T36'];
	$c['U63'] = $c['T37'];
	$c['V62'] = $c['U36'];
	$c['V63'] = $c['U37'];
	$c['W62'] = $c['AA47'];
	$c['W63'] = $c['AA48'];
	$c['X62'] = MIN(100, VB_SUM($n, 'S62:V62'));
	$c['X63'] = MIN(100, VB_SUM($n, 'S63:V63'));
	$c['S64'] = ($c['W62'] * $c['S62']) + ($c['W63'] * $c['S63']);
	$c['T64'] = ($c['W62'] * $c['T62']) + ($c['W63'] * $c['T63']);
	$c['U64'] = ($c['W62'] * $c['U62']) + ($c['W63'] * $c['U63']);
	$c['V64'] = ($c['W62'] * $c['V62']) + ($c['W63'] * $c['V63']);
	$c['X64'] = ($c['W62'] * $c['X62']) + ($c['W63'] * $c['X63']);
	$c['R38'] = $c['S64'];
	$c['S38'] = $c['T64'];
	$c['T38'] = $c['U64'];
	$c['U38'] = $c['V64'];
	$c['V36'] = $c['X62'];
	$c['V37'] = $c['X63'];
	$c['V38'] = $c['X64'];
}
?>