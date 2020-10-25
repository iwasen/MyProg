#include	"epddt.h" 

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

	char	diaxis[]={"    "};
	char	dioutn[]={"                   "};
	char	dioutf[]=
	{"                                                           "};
	char	dioutm[]={" "};

	char	daxis[5];
	char	doutn[21];
	char	dslcn[21];
	char	doutf[5][61];
	char	doutm[2];
	int	elemn[5];

	char	ddoutf[5][61];

	char	dfile[65];

	int	dfrmpg03[] = {	1,
				lisf,
				10, 80,
				 0,  0,
				 2,  0,  11,  79,
				10,  0,
				 0,  3
			   };


	int	dfmspg03[] = {	2,
				eftb|posa,1,0,
				nend    ,posr|colr, 0,yell,strg|sfix,20,
				 end    ,posa|colr, 5,yell,strg|sfix,26
			    };

	int	ds02pg03[] = {	7,
				(int)"属性削除",
				(int)" 挿入上 ",
				(int)" 挿入下 ",
				(int)"属性読込",
				(int)" 前  頁 ",
				(int)" 後  頁 ",
				(int)"属性選択"
			    };
	int	df02fp03[] = {	1,
				eftb|posa,0,0,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&lixxxl,(int)"%-4d"
			    };
	int	df02cp03[] = {	1,
				eftb|nposa,
				nend|keyw,posr|colr,1,whit,
				strg|idir,(int)doutn
			    };
	int	dd02pg03[] = {	idir|strg,
				21,
				(int)doutn
			    };
	int	dc02pg03[] = {	1,
				maxs,
				dirc|int4,
				21
			    };
	int	dm02pg03[] = {	1,
				eftb|nposa,
				 end,posa|colr,0,whit,
				strg|idir,
				(int)"属性を入力してください。"
			    };
	int	dq02pg03[] = {	vtbl|inpt|listf,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds02pg03,
				(int)df02fp03,
				(int)df02cp03,
				(int)dd02pg03,
				(int)dc02pg03,
				(int)dm02pg03
			    };

	int	dq03pg03[] = {	vtbl|inpt|lixxx,
				slbl|fmtc|dest|chck|cmnt,
				(int)ds02pg03,
				(int)df02cp03,
				(int)dd02pg03,
				(int)dc02pg03,
				(int)dm02pg03
			    };

	int	df04cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutn
			    };
	int	dq04pg03[] = {	vtbl|inpt|liste,
				slbl|fmtc|dest|chck|cmnt,
				(int)ds02pg03,
				(int)df04cp03,
				(int)dd02pg03,
				(int)dc02pg03,
				(int)dm02pg03
			    };

	int	ds05pg03[] = {	14,
				(int)" 論理値 ",
				(int)" 整数値 ",
				(int)" 実数値 ",
				(int)" 文字列 ",
				0,0,
				(int)"更新終了",
				(int)" クリア ",
				0,0,
				0,
				0,0,
				(int)"更新終了"
			    };
	int	df05fp03[] = {	5,
				eftb|posa,12,0,
				nend,posr|colr,8,gree,
				strg|sfix,27,
				nend,posr|colr,0,yell,
				strg|idir,(int)":",
				 end,posr|colr,0,yell,
				strg|idir,(int)dslcn,
				nend|keyw,posr|colr,10,cyan,
				strg|sfix,28,
				nend,posr|colr,0,yell,
				strg|idir,(int)":"
			    };
	int	df05cp03[] = {	1,
				eftb|nposa,
				 end,posr|colr,0,whit,
				strg|idir,(int)doutm
			    };
	int	dd05pg03[] = {	dirc|int4,
				0
			    };
	int	dc05pg03[] = {	1,
				cmpi,
				dirc|int4,
				5,
				DS_N,DS_L,DS_I,DS_R,DS_C
			    };
	int	dm05pg03[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"モードを入力してください。"
			    };
	int	dq05pg03[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds05pg03,
				(int)df05fp03,
				(int)df05cp03,
				(int)dd05pg03,
				(int)dc05pg03,
				(int)dm05pg03
			    };

	int	ds06pg03[] = {	7,
				(int)"属性削除",
				(int)" 挿入上 ",
				(int)" 挿入下 ",
				0,
				(int)" 前  頁 ",
				(int)" 後  頁 ",
				(int)"更新終了"
			    };
	int	df06fp03[] = {	1,
				eftb|posa,14,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[0],(int)"%-4d"
			    };
	int	df06cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[0]
			    };
	int	dd06pg03[] = {	idir|strg,
				61,
				(int)ddoutf[0]
			    };
	int	dc06pg03[] = {	1,
				maxs,
				dirc|int4,
				61
			    };
	int	dm06pg03[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"属性値を入力してください。"
			    };
	int	dq06pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg03,
				(int)df06fp03,
				(int)df06cp03,
				(int)dd06pg03,
				(int)dc06pg03,
				(int)dm06pg03
			    };

	int	df07fp03[] = {	1,
				eftb|posa,15,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[1],(int)"%-4d"
			    };
	int	df07cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[1]
			    };
	int	dd07pg03[] = {	idir|strg,
				61,
				(int)ddoutf[1]
			    };
	int	dq07pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg03,
				(int)df07fp03,
				(int)df07cp03,
				(int)dd07pg03,
				(int)dc06pg03,
				(int)dm06pg03
			    };

	int	df08fp03[] = {	1,
				eftb|posa,16,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[2],(int)"%-4d"
			    };
	int	df08cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[2]
			    };
	int	dd08pg03[] = {	idir|strg,
				61,
				(int)ddoutf[2]
			    };
	int	dq08pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg03,
				(int)df08fp03,
				(int)df08cp03,
				(int)dd08pg03,
				(int)dc06pg03,
				(int)dm06pg03
			    };

	int	df09fp03[] = {	1,
				eftb|posa,17,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[3],(int)"%-4d"
			    };
	int	df09cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[3]
			    };
	int	dd09pg03[] = {	idir|strg,
				61,
				(int)ddoutf[3]
			    };
	int	dq09pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg03,
				(int)df09fp03,
				(int)df09cp03,
				(int)dd09pg03,
				(int)dc06pg03,
				(int)dm06pg03
			    };

	int	df10fp03[] = {	1,
				eftb|posa,18,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[4],(int)"%-4d"
			    };
	int	df10cp03[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[4]
			    };
	int	dd10pg03[] = {	idir|strg,
				61,
				(int)ddoutf[4]
			    };
	int	dq10pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg03,
				(int)df10fp03,
				(int)df10cp03,
				(int)dd10pg03,
				(int)dc06pg03,
				(int)dm06pg03
			    };

	int	dd11pg03[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc11pg03[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm11pg03[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"読み込み元を指定してください。"
			    };
	int	ds11pg03[] = {	7,
				(int)"シス索引",
				(int)"LAデータ",
				(int)"ファイル",
				0,
				0,
				0,
				0
			    };
	int	df11fp03[] = {	0,
				eftb|posa,0,0
			    };
	int	df11cp03[] = {	0,
				eftb|nposa
			    };
	int	dq11pg03[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds11pg03,
				(int)df11fp03,
				(int)df11cp03,
				(int)dd11pg03,
				(int)dc11pg03,
				(int)dm11pg03
			    };

	int	dd12pg03[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc12pg03[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm12pg03[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"ＬＡデータセット名を入力してください。"
			    };
	int	ds12pg03[] = {	7,
				0,
				0,
				0,
				0,
				0,
				0,
				0
			    };
	int	df12fp03[] = {	0,
				eftb|posa,0,0
			    };
	int	df12cp03[] = {	0,
				eftb|nposa
			    };
	int	dq12pg03[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds12pg03,
				(int)df12fp03,
				(int)df12cp03,
				(int)dd12pg03,
				(int)dc12pg03,
				(int)dm12pg03
			    };

	int	dd13pg03[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc13pg03[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm13pg03[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"説明部ファイル名を入力してください。"
			    };
	int	ds13pg03[] = {	7,
				0,
				0,
				0,
				0,
				0,
				0,
				0
			    };
	int	df13fp03[] = {	0,
				eftb|posa,0,0
			    };
	int	df13cp03[] = {	0,
				eftb|nposa
			    };
	int	dq13pg03[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds13pg03,
				(int)df13fp03,
				(int)df13cp03,
				(int)dd13pg03,
				(int)dc13pg03,
				(int)dm13pg03
			    };

	extern  int  dfint03(),dfque03(),dftrm03(),dflst03();
	extern  int  dfk0003(),dfk0103(),dfk0203(),dfk0303(),
                     dfk0403(),dfk0503(),dfk0603();
	extern	int  dfquit03(), dfexit03();

	int	d_pg03[] = {	ncond,
				expd|fram|sttl|fmsg|inte|quee|trme|lste,
				inte|quee|trme,
				f00|f01|f02|f03|f04|f05|f06|f16|f17,
				(int)dfrmpg03,
				(int)"＜　説明部　＞",
				(int)dfmspg03,
				(int)dfint03,
				(int)dfque03,
				(int)dftrm03,
				(int)dflst03,
				(int)dfint03,
				(int)dfque03,
				(int)dftrm03,
				(int)dfk0003,
				(int)dfk0103,
				(int)dfk0203,
				(int)dfk0303,
				(int)dfk0403,
				(int)dfk0503,
				(int)dfk0603,
				(int)dfquit03,
				(int)dfexit03,
				12,
				(int)dq02pg03,
				(int)dq03pg03,
				(int)dq04pg03,
				(int)dq05pg03,
				(int)dq06pg03,
				(int)dq07pg03,
				(int)dq08pg03,
				(int)dq09pg03,
				(int)dq10pg03,
				(int)dq11pg03,
				(int)dq12pg03,
				(int)dq13pg03
			  };


	int	dfrmpg04[] = {	1,
				lisf,
				10, 80,
				 0,  0,
				 2,  0,  11,  79,
				10,  0,
				 0,  3
			   };


	int	dfmspg04[] = {	2,
				eftb|posa,1,0,
				nend    ,posr|colr, 0,yell,strg|sfix,20,
				 end    ,posa|colr, 5,yell,strg|sfix,26
			    };

	int	ds01pg04[] = {	14,
				(int)"  Axi1  ",
				(int)"  Axi2  ",
				(int)"  Axi3  ",
				0,0,0,0,
				(int)" 軸削除 ",
				0,0,
				(int)" 軸読込 ",
				0,0,0
			    };
	int	df01fp04[] = {	2,
				eftb|posa,0,0,
				nend|keyw,colr,cyan,
				strg|sfix,25,
				nend,colr,yell,
				strg|idir,(int)":"
			    };
	int	df01cp04[] = {	1,
				eftb|nposa,
				 end,posr|colr,0,whit,
				strg|idir,(int)daxis
			    };
	int	dd01pg04[] = {	dirc|int4,
				0
			    };
	int	dc01pg04[] = {	1,
				cmpi,
				dirc|int4,
 				3,
				1,2,3
			    };
	int	dm01pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
			(int)"設定（更新）すべき軸ラベル名を選択してください。"
			    };
	int	dq01pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds01pg04,
				(int)df01fp04,
				(int)df01cp04,
				(int)dd01pg04,
				(int)dc01pg04,
				(int)dm01pg04
			    };


	int	ds02pg04[] = {	7,
				(int)"属性削除",
				(int)" 挿入上 ",
				(int)" 挿入下 ",
				0,
				(int)" 前  頁 ",
				(int)" 後  頁 ",
				(int)"属性選択"
			    };
	int	df02fp04[] = {	1,
				eftb|posa,0,0,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&lixxxl,(int)"%-4d"
			    };
	int	df02cp04[] = {	1,
				eftb|nposa,
				nend|keyw,posr|colr,1,whit,
				strg|idir,(int)doutn
			    };
	int	dd02pg04[] = {	idir|strg,
				21,
				(int)doutn
			    };
	int	dc02pg04[] = {	1,
				maxs,
				dirc|int4,
				21
			    };
	int	dm02pg04[] = {	1,
				eftb|nposa,
				 end,posa|colr,0,whit,
				strg|idir,
				(int)"属性を入力してください。"
			    };
	int	dq02pg04[] = {	vtbl|inpt|listf,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds02pg04,
				(int)df02fp04,
				(int)df02cp04,
				(int)dd02pg04,
				(int)dc02pg04,
				(int)dm02pg04
			    };

	int	dq03pg04[] = {	vtbl|inpt|lixxx,
				slbl|fmtc|dest|chck|cmnt,
				(int)ds02pg04,
				(int)df02cp04,
				(int)dd02pg04,
				(int)dc02pg04,
				(int)dm02pg04
			    };

	int	df04cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutn
			    };
	int	dq04pg04[] = {	vtbl|inpt|liste,
				slbl|fmtc|dest|chck|cmnt,
				(int)ds02pg04,
				(int)df04cp04,
				(int)dd02pg04,
				(int)dc02pg04,
				(int)dm02pg04
			    };

	int	ds05pg04[] = {	14,
				(int)" 論理値 ",
				(int)" 整数値 ",
				(int)" 実数値 ",
				(int)" 文字列 ",
				0,0,
				(int)"更新終了",
				(int)" クリア ",
				0,0,
				0,
				0,0,
				(int)"更新終了"
			    };
	int	df05fp04[] = {	5,
				eftb|posa,12,0,
				nend,posr|colr,8,gree,
				strg|sfix,27,
				nend,posr|colr,0,yell,
				strg|idir,(int)":",
				 end,posr|colr,0,yell,
				strg|idir,(int)dslcn,
				nend|keyw,posr|colr,10,cyan,
				strg|sfix,28,
				nend,posr|colr,0,yell,
				strg|idir,(int)":"
			    };
	int	df05cp04[] = {	1,
				eftb|nposa,
				 end,posr|colr,0,whit,
				strg|idir,(int)doutm
			    };
	int	dd05pg04[] = {	dirc|int4,
				0
			    };
	int	dc05pg04[] = {	1,
				cmpi,
				dirc|int4,
				5,
				DS_N,DS_L,DS_I,DS_R,DS_C
			    };
	int	dm05pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"モードを入力してください。"
			    };
	int	dq05pg04[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds05pg04,
				(int)df05fp04,
				(int)df05cp04,
				(int)dd05pg04,
				(int)dc05pg04,
				(int)dm05pg04
			    };

	int	ds06pg04[] = {	7,
				(int)"属性削除",
				(int)" 挿入上 ",
				(int)" 挿入下 ",
				0,
				(int)" 前  頁 ",
				(int)" 後  頁 ",
				(int)"更新終了"
			    };
	int	df06fp04[] = {	1,
				eftb|posa,14,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[0],(int)"%-4d"
			    };
	int	df06cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[0]
			    };
	int	dd06pg04[] = {	idir|strg,
				61,
				(int)ddoutf[0]
			    };
	int	dc06pg04[] = {	1,
				maxs,
				dirc|int4,
				61
			    };
	int	dm06pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"属性値を入力してください。"
			    };
	int	dq06pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg04,
				(int)df06fp04,
				(int)df06cp04,
				(int)dd06pg04,
				(int)dc06pg04,
				(int)dm06pg04
			    };

	int	df07fp04[] = {	1,
				eftb|posa,15,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[1],(int)"%-4d"
			    };
	int	df07cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[1]
			    };
	int	dd07pg04[] = {	idir|strg,
				61,
				(int)ddoutf[1]
			    };
	int	dq07pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg04,
				(int)df07fp04,
				(int)df07cp04,
				(int)dd07pg04,
				(int)dc06pg04,
				(int)dm06pg04
			    };

	int	df08fp04[] = {	1,
				eftb|posa,16,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[2],(int)"%-4d"
			    };
	int	df08cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[2]
			    };
	int	dd08pg04[] = {	idir|strg,
				61,
				(int)ddoutf[2]
			    };
	int	dq08pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg04,
				(int)df08fp04,
				(int)df08cp04,
				(int)dd08pg04,
				(int)dc06pg04,
				(int)dm06pg04
			    };

	int	df09fp04[] = {	1,
				eftb|posa,17,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[3],(int)"%-4d"
			    };
	int	df09cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[3]
			    };
	int	dd09pg04[] = {	idir|strg,
				61,
				(int)ddoutf[3]
			    };
	int	dq09pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg04,
				(int)df09fp04,
				(int)df09cp04,
				(int)dd09pg04,
				(int)dc06pg04,
				(int)dm06pg04
			    };

	int	df10fp04[] = {	1,
				eftb|posa,18,12,
				nend,posr|colr,0,red,
				int4|idir|dgt,(int)&elemn[4],(int)"%-4d"
			    };
	int	df10cp04[] = {	1,
				eftb|nposa,
				 end|keyw,posr|colr,1,whit,
				strg|idir,(int)doutf[4]
			    };
	int	dd10pg04[] = {	idir|strg,
				61,
				(int)ddoutf[4]
			    };
	int	dq10pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds06pg04,
				(int)df10fp04,
				(int)df10cp04,
				(int)dd10pg04,
				(int)dc06pg04,
				(int)dm06pg04
			    };

	int	dd11pg04[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc11pg04[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm11pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"読み込み元を指定してください。"
			    };
	int	ds11pg04[] = {	7,
				0,
				(int)"LAデータ",
				(int)"ファイル",
				0,
				0,
				0,
				0
			    };
	int	df11fp04[] = {	0,
				eftb|posa,0,0
			    };
	int	df11cp04[] = {	0,
				eftb|nposa
			    };
	int	dq11pg04[] = {	vtbl|inpt,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds11pg04,
				(int)df11fp04,
				(int)df11cp04,
				(int)dd11pg04,
				(int)dc11pg04,
				(int)dm11pg04
			    };

	int	dd12pg04[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc12pg04[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm12pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"ＬＡデータセット名を入力してください。"
			    };
	int	ds12pg04[] = {	7,
				0,
				0,
				0,
				0,
				0,
				0,
				0
			    };
	int	df12fp04[] = {	0,
				eftb|posa,0,0
			    };
	int	df12cp04[] = {	0,
				eftb|nposa
			    };
	int	dq12pg04[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds12pg04,
				(int)df12fp04,
				(int)df12cp04,
				(int)dd12pg04,
				(int)dc12pg04,
				(int)dm12pg04
			    };

	int	dd13pg04[] = {	idir|strg,
				65,
				(int)dfile
			    };
	int	dc13pg04[] = {	1,
				maxs,
				dirc|int4,
				65
			    };
	int	dm13pg04[] = {	1,
				eftb|nposa,
				 end,colr,whit,
				strg|idir,
				(int)"ラベル部ファイル名を入力してください。"
			    };
	int	ds13pg04[] = {	7,
				0,
				0,
				0,
				0,
				0,
				0,
				0
			    };
	int	df13fp04[] = {	0,
				eftb|posa,0,0
			    };
	int	df13cp04[] = {	0,
				eftb|nposa
			    };
	int	dq13pg04[] = {	vtbl|inpt|ok,
				slbl|fmtf|fmtc|dest|chck|cmnt,
				(int)ds13pg04,
				(int)df13fp04,
				(int)df13cp04,
				(int)dd13pg04,
				(int)dc13pg04,
				(int)dm13pg04
			    };

	extern  int  dfint04(),dfque04(),dftrm04(),dflst04();
	extern  int  dfk0004(),dfk0104(),dfk0204(),dfk0304(),
                     dfk0404(),dfk0504(),dfk0604();
	extern	int  dfquit04(), dfexit04();

	int	d_pg04[] = {	ncond,
				expd|fram|sttl|fmsg|inte|quee|trme|lste,
				inte|quee|trme,
				f00|f01|f02|f03|f04|f05|f06|f16|f17,
				(int)dfrmpg04,
				(int)"＜　ラベル部　＞",
				(int)dfmspg04,
				(int)dfint04,
				(int)dfque04,
				(int)dftrm04,
				(int)dflst04,
				(int)dfint04,
				(int)dfque04,
				(int)dftrm04,
				(int)dfk0004,
				(int)dfk0104,
				(int)dfk0204,
				(int)dfk0304,
				(int)dfk0404,
				(int)dfk0504,
				(int)dfk0604,
				(int)dfquit04,
				(int)dfexit04,
				13,
				(int)dq01pg04,
				(int)dq02pg04,
				(int)dq03pg04,
				(int)dq04pg04,
				(int)dq05pg04,
				(int)dq06pg04,
				(int)dq07pg04,
				(int)dq08pg04,
				(int)dq09pg04,
				(int)dq10pg04,
				(int)dq11pg04,
				(int)dq12pg04,
				(int)dq13pg04
			  };

	int	d_msgt[] = {
				0,0,0,0,0,0,0,0,0,0,
				0,0,0,0,0,0,0,0,0,0,
				(int)"番号",
				0,0,0,0,
				(int)"軸ラベル名",
				(int)"属性",
				(int)"対象属性",
				(int)"モード",
				0,0,
				0,0,0,0,0,0,0,0,0
			   };

	extern  int  i_indx[];

	int	d_ch1[] = {
				msg|titl,
				f00|f01|f02|f03|f04|f05|f06,
				(int)d_msgt,
				0,
				0,0,0,0,0,0,0,
				1,
				(int) d_pg03
			   };
