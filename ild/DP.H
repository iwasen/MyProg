/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: ダイナミック・パラメータ		*
 *		ファイル名	: dp.h					*
 *									*
 ************************************************************************/

typedef	struct	{
	short	PX04A_0;
	short	PY04A_0;
	short	PX04A_1;
	short	PY04A_1;
	ushort	L04A_0;
} IFF_GATE_A;

typedef	struct	{
	short	PX04B_0;
	short	PY04B_0;
	short	L04B_0;
	short	L04B_1;
} IFF_GATE_B;

typedef	struct	{
	short	PX04C_0;
	short	PY04C_0;
	short	PX04C_1;
	short	PY04C_1;
	short	PX04C_2;
	short	PY04C_2;
	short	PX04C_3;
	short	PY04C_3;
	ushort	L04C_0;
} IFF_GATE_C;

typedef	struct	{
	short	PX100;
	short	PY100;
} OBST;

typedef	struct	{
	short	PX50;
	short	PY50;
	ushort	L50;
	ushort	D50;
	ushort	C05;
} ZUKEI0;

typedef	struct	{
	short	PX51;
	short	PY51;
	ushort	LX51;
	ushort	LY51;
} ZUKEI1;

typedef	struct	{
	short	PX52_0;
	short	PY52_0;
	short	PX52_1;
	short	PY52_1;
	short	PX52_2;
	short	PY52_2;
	short	PX52_3;
	short	PY52_3;
	short	PX52_4;
	short	PY52_4;
} ZUKEI2;

typedef	struct	{
	short	PX53;
	short	PY53;
	ushort	L53_0;
	ushort	L53_1;
} ZUKEI3;

typedef	struct	{
	ZUKEI0	zukei0[18];
	ZUKEI1	zukei1[18];
	ZUKEI3	zukei3[18];
	ZUKEI2	zukei2[18];
	ushort	A191[4];
	ushort	A181[6];
	ushort	C001;
	ushort	A171[3];
	ushort	A161[5];
	ushort	A151[3];
	ushort	A121[4];
	ushort	A122[4];
	ushort	A111_A[4];
	ushort	A111_B[4];
	ushort	A101[4];
	ushort	A102[4];
	ushort	A103[7];
	ushort	A141[9];
	ushort	C140;
	ushort	A142[9];
	ushort	C141;
	ushort	A143[9];
	ushort	C142;
	ushort	A144[9];
	ushort	C143;
	ushort	A145[9];
	ushort	C144;
	ushort	A131[9];
	ushort	C130;
	ushort	A132[9];
	ushort	C131;
	ushort	A133[9];
	ushort	C132;
	ushort	LX20A;
	ushort	LX20B;
	ushort	LX21A;
	ushort	LX21B;
	ushort	LY22A;
	ushort	LY22B;
	ushort	LX28;
	ushort	LY28;
	short	PX090;
	short	PY090;
	short	PX091;
	short	PY091;
	IFF_GATE_B	IFF_gate_b[3];
	short	PX030;
	short	PY030;
	short	PX031;
	short	PY031;
	ushort	LX031;
	ushort	LX032;
	ushort	LY033;
	ushort	LY034;
	ushort	LY035;
	ushort	LY036;
	ushort	LX037;
	ushort	LX038;
	short	PX02;
	short	PY02;
	short	PX01;
	short	PY01;
	OBST	obst[100];
	ushort	LY22C;
	ushort	LX21C;
	IFF_GATE_A	IFF_gate_a[3];
	ushort	LY26;
	ushort	LX25A;
	ushort	LX25D;
	ushort	LX23;
	ushort	LY23;
	ushort	R07;
	ushort	LX07;
	ushort	R06;
	ushort	R08;
	ushort	LX08;
	short	PX05;
	short	PY05;
	IFF_GATE_C	IFF_gate_c[3];
	ushort	L40A;
	ushort	D40A0;
	ushort	D40A1;
	ushort	D40A2;
	ushort	D40A3;
	ushort	D40A4;
	short	PX40B;
	short	PY40B;
	ushort	L41A;
	ushort	D41A0;
	ushort	D41A1;
	ushort	D41A2;
	ushort	D41A3;
	ushort	D41A4;
	short	PX41B;
	short	PY41B;
	ushort	A431[7];
	ushort	A432[6];
	ushort	A433[6];
} DP;
