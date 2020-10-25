/************************************************************************
*									*
*		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
*									*
*		名称		: スタートアップ処理			*
*		ファイル名	: startup.s				*
*									*
************************************************************************/
*
	CHIP	68020
	SECT	startup
*
	XDEF	_main
*
	XREF	_power_on
*
_main
	MOVEA.L	#$FF080000,A7
	ORI.W	#$700,SR		: 割り込みマスク
*
	MOVEQ	#$8,D0			: キャッシュイネーブル
	MOVEC.L	D0,CACR
	MOVEQ	#$1,D0
	MOVEC.L	D0,CACR
*
	MOVEA.L	#$FF080000,A0		: ベクタベースアドレスセット
	MOVEC.L	A0,VBR
*
	JMP	_power_on		: パワーオン処理
*
	END	_main
