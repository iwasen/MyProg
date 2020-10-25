#!/usr/bin/perl

use Getopt::Std;                     # オプション解析用
use DBI;
use DBD::Pg;

# 日付取得
$time = time;
$today = get_ymd($time);
$time -= 182 * 24 * 60 * 60;
$b6month = get_ymd($time);
($sec,$min,$hour,$mday,$mon,$year,$wday) = localtime(time);
$today_y = sprintf("%04d",$year+1900);

# dbサーバ名
getopts('d:',\%opts);
$db_host = $opts{'d'} || 'localhost';

$dbh_pg = DBI->conxxxt("dbi:Pg:dbname=melonpan;host=$db_host", "postgres", "");
die "Cannot conxxxt: $DBI::errstr\n" unless $dbh_pg;

# データ削除
#$sql = "truncate table T_RANKING_LIST";
#$dbh->do($sql);

$sql = "truncate table T_RANKING_LIST";
$dbh_pg->do($sql);

# ------------------------------------------------------------------------------

#$common_cond = " 
#	( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' ) 
#	and M.mag_pub_stop_flg <> '02' 
#	and M.publisher_id <> 0 
#	and M.mag_id = X.mag_id ";

$common_cond_pg = " 
	( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' ) 
	and M.mag_pub_stop_flg <> '02' 
	and M.publisher_id <> 0 ";

$base_cnt = 20;
$base_cnt2 = 50;

# 1 読者数Ranking
#$sql = "select M.mag_id, X.rankpoint  
#		  from M_MAGAZINE M,
#			   ( select mag_id, count(*) as rankpoint from T_MAILADDR group by mag_id ) X 
#		 where $common_cond 
#		 order by X.rankpoint desc";
$sql =
  "SELECT M.mag_id, X.rankpoint"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR group by mag_id ) X ON M.mag_id = X.mag_id"
 ." WHERE $common_cond_pg"
 ." order by X.rankpoint desc";
&write_data_from_sql($sql, "01", 50);

# 03 推薦文が多いRanking
#$sql = 
#  "select M.mag_id ,X.rankpoint"
# ."  from M_MAGAZINE M,"
# ."      (select mag_id, count(*) as rankpoint from T_RECOMEND "
# ."        where to_char(modify_dt, 'YYYYMMDD') > '$b6month' group by mag_id ) X "
# ."  where $common_cond and rankpoint <> '0' "
# ."  order by X.rankpoint desc";
$sql = 
  "select M.mag_id ,X.rankpoint"
 ."  from M_MAGAZINE M"
 ."  JOIN (select mag_id, count(*) as rankpoint from T_RECOMEND "
 ."         where to_char(modify_dt, 'YYYYMMDD') > '$b6month' group by mag_id ) X  ON M.mag_id = X.mag_id"
 ."  where $common_cond_pg and rankpoint <> '0' "
 ."  order by X.rankpoint desc";
&write_data_from_sql($sql, "03", 50);

# 04 女性が読んでるRanking
#$sql = "select M.mag_id, round( (X.rankpoint/C.melonpa)*100, 2 ) 
#		from M_MAGAZINE M,
#			( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			   group by mag_id ) C, 
#			( select mag_id, count(*) as rankpoint from T_MAILADDR C, M_MELONPA D
#			   where C.reader_id = D.reader_id and D.reader_sex = '2'
#			   group by mag_id ) X 
#		where $common_cond 
#		  and C.mag_id = X.mag_id 
#		  and melonpa >= $base_cnt 
#		order by ( X.rankpoint / C.melonpa ) desc";
$sql =
  "SELECT M.mag_id, round( float8(X.rankpoint * 100)/float8(C.melonpa), 2 )"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa   from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."          group by mag_id ) C ON M.mag_id = C.mag_id"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."          where D.reader_sex = '2' group by mag_id ) X  ON M.mag_id = X.mag_id "
 ." WHERE $common_cond_pg "
 ."   AND melonpa >= $base_cnt "
 ."	order by ( float8(X.rankpoint) / float8(C.melonpa) ) desc";
&write_data_from_sql($sql, "04", 50);

# 05 学生が読んでるRanking
#$sql = "select M.mag_id, round( (X.rankpoint/C.melonpa)*100, 2 ) 
#		from M_MAGAZINE M,
#			( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			   group by mag_id
#			) C, 
#			( select mag_id, count(*) as rankpoint from T_MAILADDR C, M_MELONPA D 
#			   where C.reader_id = D.reader_id and D.READER_OCCUP = '08001'
#			   group by mag_id ) X 
#		where $common_cond 
#		  and C.mag_id = X.mag_id 
#		  and melonpa >= $base_cnt 
#		order by ( X.rankpoint / C.melonpa ) desc";
$sql =
  "SELECT M.mag_id, round( float8(X.rankpoint*100)/float8(C.melonpa), 2 )"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa   from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."		     group by mag_id ) C ON M.mag_id = C.mag_id"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR C join M_MELONPA D "
 ."             on C.reader_id = D.reader_id and D.READER_OCCUP = '08001' group by mag_id ) X ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg" 
 ."   AND melonpa >= $base_cnt"
 ."	ORDER BY ( float8(X.rankpoint) / float8(C.melonpa) ) desc";
&write_data_from_sql($sql, "05", 50);

# 06 ビジネスマンが読んでるRanking
#$sql = "select M.mag_id, round( (X.rankpoint/C.melonpa)*100, 2) 
#		from M_MAGAZINE M,
#		    ( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			   group by mag_id ) C, 
#			( select mag_id, count(*) as rankpoint from T_MAILADDR C, M_MELONPA D
#			   where C.reader_id = D.reader_id
#			     and D.READER_OCCUP = '02001'
#			   group by mag_id ) X 
#		where $common_cond 
#		  and C.mag_id = X.mag_id 
#		  and melonpa >= $base_cnt 
#		order by ( X.rankpoint / C.melonpa ) desc";
$sql =
  "SELECT M.mag_id, round( float8(X.rankpoint*100)/float8(C.melonpa), 2 )"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa   from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."		     group by mag_id ) C ON M.mag_id = C.mag_id"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR C join M_MELONPA D "
 ."             on C.reader_id = D.reader_id and D.READER_OCCUP = '02001' group by mag_id ) X ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg" 
 ."   AND melonpa >= $base_cnt"
 ."	ORDER BY ( float8(X.rankpoint) / float8(C.melonpa) ) desc";
&write_data_from_sql($sql, "06", 50);

# 07 読者の平均年齢が低いRanking
#$sql = "select M.mag_id, round(X.rankpoint, 2) 
#		from M_MAGAZINE M,
#			( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			   group by mag_id ) C, 
#			( select mag_id, avg(Y.YMD) as rankpoint 
#			  from ( SELECT L.MAG_ID, ( '$today_y' - to_char(K.READER_BIRTH, 'YYYY') ) as YMD  
#					   FROM M_MELONPA K, T_MAILADDR L 
#					   WHERE L.READER_ID <> 0 and  L.READER_ID = K.READER_ID(+) ) Y
#			 group by mag_id ) X 
#		where $common_cond 
#		  and M.mag_id = C.mag_id 
#		  and C.melonpa >= $base_cnt 
#		order by X.rankpoint";
$sql_sub =
  "SELECT L.MAG_ID, ( $today_y - float8(to_char(K.READER_BIRTH, 'YYYY')) ) as YMD "
 ."	 FROM M_MELONPA K LEFT JOIN T_MAILADDR L "
 ."	   ON L.READER_ID <> 0 and  L.READER_ID = K.READER_ID";
 
$sql =
  "SELECT M.mag_id, round(X.rankpoint, 2) "
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."          group by mag_id ) C ON M.mag_id = C.mag_id " 
 ."	 JOIN ( select mag_id, avg(Y.YMD) as rankpoint from ( $sql_sub ) Y"
 ."		     group by mag_id ) X ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg "
 ."	  and C.melonpa >= $base_cnt "
 ."	order by X.rankpoint";
&write_data_from_sql($sql, "07", 50);

# 08 読者の平均年齢が高いRanking
#$sql = "select M.mag_id, round(X.rankpoint, 2) 
#		from M_MAGAZINE M,
#			( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			   group by mag_id ) C, 
#			( select mag_id, avg(Y.YMD) as rankpoint 
#			  from ( SELECT L.MAG_ID, ( '$today_y' - to_char(K.READER_BIRTH, 'YYYY') ) as YMD  
#					   FROM M_MELONPA K, T_MAILADDR L 
#					   WHERE L.READER_ID <> 0 and  L.READER_ID = K.READER_ID(+) ) Y
#			 group by mag_id ) X 
#		where $common_cond 
#		  and M.mag_id = C.mag_id 
#		  and C.melonpa >= $base_cnt 
#		order by X.rankpoint desc";
$sql_sub =
  "SELECT L.MAG_ID, ( $today_y - float8(to_char(K.READER_BIRTH, 'YYYY')) ) as YMD "
 ."	 FROM M_MELONPA K LEFT JOIN T_MAILADDR L "
 ."	   ON L.READER_ID <> 0 and  L.READER_ID = K.READER_ID";
 
$sql =
  "SELECT M.mag_id, round(X.rankpoint, 2) "
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."          group by mag_id ) C ON M.mag_id = C.mag_id " 
 ."	 JOIN ( select mag_id, avg(Y.YMD) as rankpoint from ( $sql_sub ) Y"
 ."		     group by mag_id ) X ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg "
 ."	  and C.melonpa >= $base_cnt "
 ."	order by X.rankpoint desc";
&write_data_from_sql($sql, "08", 50);

# 09 専門職が読んでるRanking";
#$sql = "select M.mag_id, round( (X.rankpoint/C.melonpa)*100, 2 ) 
#		from M_MAGAZINE M,
#		    ( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			group by mag_id ) C, 
#			( select mag_id, count(*) as rankpoint from T_MAILADDR C, M_MELONPA D
#		   	  where C.reader_id = D.reader_id
#		     	and ( D.READER_OCCUP = '06001' or D.READER_OCCUP = '07001' ) 
#			group by mag_id ) X 
#		where $common_cond 
#	  	and melonpa >= $base_cnt 
#	  	and C.mag_id = X.mag_id 
#	  order by ( X.rankpoint / C.melonpa ) desc";
$sql =
  "SELECT M.mag_id, round( float8(X.rankpoint*100)/float8(C.melonpa), 2 )"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa   from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."		     group by mag_id ) C ON M.mag_id = C.mag_id"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR C join M_MELONPA D "
 ."             on C.reader_id = D.reader_id and D.READER_OCCUP in ('06001','07001') group by mag_id ) X "
 ."    ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg" 
 ."   AND melonpa >= $base_cnt"
 ."	ORDER BY ( float8(X.rankpoint) / float8(C.melonpa) ) desc";
&write_data_from_sql($sql, "09", 50);

# 10 主婦・主夫が読んでるRanking
#$sql = "select M.mag_id, round( (X.rankpoint/C.melonpa)*100, 2 ) 
#		from M_MAGAZINE M,
#		    ( select mag_id, count(*) as melonpa from T_MAILADDR C, M_MELONPA D where C.reader_id = D.reader_id
#			group by mag_id ) C, 
#			( select mag_id, count(*) as rankpoint from T_MAILADDR C, M_MELONPA D
#			   where C.reader_id = D.reader_id
#			     and D.READER_OCCUP = '09001' 
#			   group by mag_id ) X 
#		where $common_cond 
#		  and melonpa >= $base_cnt 
#		  and C.mag_id = X.mag_id 
#		order by ( X.rankpoint / C.melonpa ) desc";
$sql =
  "SELECT M.mag_id, round( float8(X.rankpoint*100)/float8(C.melonpa), 2 )"
 ."  FROM M_MAGAZINE M"
 ."  JOIN ( select mag_id, count(*) as melonpa   from T_MAILADDR C join M_MELONPA D using(reader_id)"
 ."		     group by mag_id ) C ON M.mag_id = C.mag_id"
 ."  JOIN ( select mag_id, count(*) as rankpoint from T_MAILADDR C join M_MELONPA D "
 ."             on C.reader_id = D.reader_id and D.READER_OCCUP = '09001' group by mag_id ) X ON M.mag_id = X.mag_id"
 ."	WHERE $common_cond_pg" 
 ."   AND melonpa >= $base_cnt"
 ."	ORDER BY ( float8(X.rankpoint) / float8(C.melonpa) ) desc";
&write_data_from_sql($sql, "10", 50);

# 11 解除率が低いRanking
#$sql = "select M.mag_id, NVL(round( (X.rankpoint * 100) / (X.rankpoint + C.cnt) ,2) ,0) 
#		from M_MAGAZINE M,
#			(select mag_id, count(*) as cnt from T_MAILADDR group by mag_id ) C, 
#			(select mag_id, NVL(count(*),0) as rankpoint
#			   from T_DELREASON
#			  where trim(reason) <> '間違えて登録した'
#			  group by mag_id) X
#		where M.mag_pub_status_flg = '03' 
#		  and M.mag_pub_stop_flg <> '02' 
#		  and M.publisher_id <> 0 
#		  and M.mag_id = X.mag_id(+) 
#		  and M.mag_id = C.mag_id 
#		  and C.cnt >= $base_cnt2 
#		order by  NVL( ( X.rankpoint / (X.rankpoint + C.cnt) ), 0)";
$sql=
  "SELECT M.mag_id, coalesce(round( float8(X.rankpoint * 100) / float8(X.rankpoint + C.cnt) ,2), 0) "
 ."  FROM M_MAGAZINE M"
 ."       JOIN (select mag_id, count(*) as cnt from T_MAILADDR group by mag_id ) C ON M.MAG_ID = C.MAG_ID"
 ."  LEFT JOIN (select mag_id, coalesce(count(*),0) as rankpoint from T_DELREASON "
 ."              where trim(reason) <> '間違えて登録した' group by mag_id) X ON M.MAG_ID = X.MAG_ID"
 ." WHERE M.mag_pub_status_flg = '03' "
 ."   AND M.mag_pub_stop_flg <> '02' "
 ."   AND M.publisher_id <> 0 "
 ."   AND C.cnt >= 50 "
 ." ORDER BY coalesce( ( float8(X.rankpoint) / float8(X.rankpoint + C.cnt) ), 0)";
&write_data_from_sql($sql, "11", 50);

# 12 おすすめRanking
#$sql = "select M.mag_id, X.rankpoint  
#		  from M_MAGAZINE M, (select mag_id, count(*) as rankpoint from T_OSUSUME_LIST group by mag_id) X 
#		 where $common_cond 
#		 order by X.rankpoint desc, M.last_send_dt desc";
$sql = 
  "SELECT M.mag_id, X.rankpoint"
 ."  FROM M_MAGAZINE M"
 ."  JOIN (select mag_id, count(*) as rankpoint from T_OSUSUME_LIST group by mag_id) X ON M.MAG_ID = X.MAG_ID"
 ." WHERE $common_cond_pg"
 ." ORDER BY X.rankpoint desc, M.last_send_dt DESC";
&write_data_from_sql($sql, "12", 999999);

# ------------------------------------------------------------------------------

$dbh_pg->disconxxxt;
#$dbh->disconxxxt;


#=== YYYY/MM/DD形式に日付を変換 ===
sub get_ymd {
	($sec, $min, $hour, $mday, $mon, $year) = localtime(@_[0]);
	return sprintf("%04d%02d%02d", $year + 1900, $mon + 1, $mday);
}

#=== SQLからデータを取得しテーブルに保存 ===
sub write_data_from_sql_ora {
	local($sql, $rank_id, $max_cnt) = @_;

	$sth_sel = $dbh->prepare($sql);
	$sth_sel->execute;

	$sql_ins = "insert into t_ranking_list values(?,?,?,?)";
	$sth_ins = $dbh->prepare($sql_ins);

	$cnt=1;
	$ranking=1;
	$r_point_mae=0;
	while ( ($mag_id, $r_point ) = $sth_sel->fetchrow_array ) {

		if ( $r_point_mae != $r_point ){
			$r_point_mae = $r_point;
			$ranking = $cnt;
		}

		$sth_ins->execute($rank_id, $mag_id, $ranking, $r_point);
		$cnt++ ;
		if ( $cnt > $max_cnt ) {last;}
	}
	$sth_ins->finish;
	$sth_sel->finish;
}

#=== SQLからデータを取得しテーブルに保存(postgres) ===
sub write_data_from_sql {
	local($sql, $rank_id, $max_cnt) = @_;

	$sth_sel = $dbh_pg->prepare($sql);
	$sth_sel->execute;

	$sql_ins = "insert into t_ranking_list values(?,?,?,?)";
	$sth_ins = $dbh_pg->prepare($sql_ins);

	$cnt=1;
	$ranking=1;
	$r_point_mae=0;
	while ( ($mag_id, $r_point ) = $sth_sel->fetchrow_array ) {

		if ( $r_point_mae != $r_point ){
			$r_point_mae = $r_point;
			$ranking = $cnt;
		}

#DEBUG	print "$rank_id\n$mag_id\n$ranking\n$r_point\n";
		$sth_ins->execute($rank_id, $mag_id, $ranking, $r_point);
		$cnt++ ;
		if ( $cnt > $max_cnt ) {last;}
	}
	$sth_ins->finish;
	$sth_sel->finish;
}
