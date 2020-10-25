/*	FILE DESCRIPTION
 * ----- %Z%%M% %I% %E% %U% -----

		FILE NAME	: d3item.h
		ROUTINE		: 項目一覧表示・選択
		REVISION	:
		REMARKS		:	88.04.19	S.Aizawa
*/

/*	項目テーブル定義	*/

typedef	struct	{
	short	int	flag;
	short	int	result;
	char		*ptr;
} D_CELL;

/*	動作モード	*/

#define	IT_MUL		0x01		/* 複数選択		*/
#define	IT_NUM		0x02		/* 番号指定		*/
#define	IT_ALL		0x04		/* 全指定／全解除	*/
#define	IT_CEN		0x08		/* センタリング 	*/
#define	IT_DSP		0x10		/* 表示のみ		*/
#define	IT_NSL		0x20		/* 選択無し（反転部無し）*/
