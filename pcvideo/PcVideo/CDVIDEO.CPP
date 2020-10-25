/************************************************************************
 *
 *			ÇoÇbÅ|ÇuÇhÇcÇdÇn
 *
 *		ñºèÃ		: âÊëúà≥èkèàóù
 *		ÉtÉ@ÉCÉãñº	: cdvideo.cpp
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "pcvideo.h"

//#undef	LPCINFO
//#undef	LPDINFO

#define	MAXJSAMPLE	255
#define CENTERJSAMPLE	128

#define DCTSIZE		    8
#define DCTSIZE2	    64

#define SCALEBITS	16
#define CBCR_OFFSET	((INT32) CENTERJSAMPLE << SCALEBITS)
#define ONE_HALF	((INT32) 1 << (SCALEBITS-1))
#define FIX(x)		((INT32) ((x) * (1L<<SCALEBITS) + 0.5))

#define R_Y_OFF		0
#define G_Y_OFF		(1*(MAXJSAMPLE+1))
#define B_Y_OFF		(2*(MAXJSAMPLE+1))
#define R_CB_OFF	(3*(MAXJSAMPLE+1))
#define G_CB_OFF	(4*(MAXJSAMPLE+1))
#define B_CB_OFF	(5*(MAXJSAMPLE+1))
#define R_CR_OFF	B_CB_OFF
#define G_CR_OFF	(6*(MAXJSAMPLE+1))
#define B_CR_OFF	(7*(MAXJSAMPLE+1))
#define TABLE_SIZE	(8*(MAXJSAMPLE+1))
#define CONST_BITS 14

#define ONE	((INT32) 1)
#define RIGHT_SHIFT(x,shft)	((x) >> (shft))
#define MULTIPLY16V16(var1,var2)  ((var1) * (var2))
#define DESCALE(x,n)  RIGHT_SHIFT((x) + (ONE << ((n)-1)), n)
#define MULTIPLY(var,const)  ((DCTELEM) RIGHT_SHIFT((var) * (const), 8))
#define rightbits(n, x) ((x) & ((1U << (n)) - 1U))
#define DEQUANTIZE(coef,quantval)  (((IFAST_MULT_TYPE) (coef)) * (quantval))
#define IDESCALE(x,n)  ((int) RIGHT_SHIFT(x, n))
#define IFAST_SCALE_BITS  2
#define PASS1_BITS  2
#define RANGE_MASK  (MAXJSAMPLE * 4 + 3)

#define FIX_1_082392200  ((INT32)  277)
#define FIX_1_414213562  ((INT32)  362)
#define FIX_1_847759065  ((INT32)  473)
#define FIX_2_613125930  ((INT32)  669)
#define FIX_0_382683433  ((INT32)   98)
#define FIX_0_541196100  ((INT32)  139)
#define FIX_0_707106781  ((INT32)  181)
#define FIX_1_306562965  ((INT32)  334)

#define HUFF_LOOKAHEAD	8
#define MIN_GET_BITS  25

#define	LO_QUALITY1	2
#define	LO_QUALITY2	4

#define check_bit_buffer(nbits) \
	{ if (dinfo->bits_left < (nbits))  \
	    FillBitBuffer(dinfo, nbits);  \
	      }

#define get_bits(nbits) \
	(((int) (dinfo->get_buffer >> \
	(dinfo->bits_left -= (nbits)))) & ((1<<(nbits))-1))

#define peek_bits(nbits) \
	(((int) (dinfo->get_buffer >> \
	(dinfo->bits_left -  (nbits)))) & ((1<<(nbits))-1))

#define drop_bits(nbits) \
	(dinfo->bits_left -= (nbits))

#define huff_DECODE(result,htbl,donelabel) \
{ if (dinfo->bits_left < HUFF_LOOKAHEAD) {  \
    FillBitBuffer(dinfo, 0);  \
    if (dinfo->bits_left < HUFF_LOOKAHEAD) {  \
      result = SlowDECODE(dinfo, htbl, 1);  \
      goto donelabel;  \
    }  \
  }  \
  { int nb, look;  \
    look = peek_bits(HUFF_LOOKAHEAD);  \
    if ((nb = htbl->look_nbits[look]) != 0) {  \
      drop_bits(nb);  \
      result = htbl->look_sym[look];  \
    } else {  \
      result = SlowDECODE(dinfo, htbl, HUFF_LOOKAHEAD+1);  \
    }  \
  }  \
donelabel:;  \
}

#define huff_EXTEND(x,s)  ((x) < extend_test[s] ? (x) + extend_offset[s] : (x))

typedef	unsigned char	UINT8;
typedef	short	INT16;
typedef	unsigned short	UINT16;
//typedef	long	INT32;
typedef	unsigned char	JSAMPLE;
typedef	JSAMPLE	*JSAMPROW;
typedef int	DCTELEM;
typedef	short	JCOEF;
typedef JCOEF	*JCOEFPTR;
typedef int	IFAST_MULT_TYPE;
typedef unsigned char JOCTET;

typedef	JSAMPLE	YCCTAB[][DCTSIZE2];

typedef struct {
	UINT8 *bits;
	UINT8 *huffval;
} JHUFF_TBL;

typedef struct {
	unsigned int ehufco[256];
	char ehufsi[256];
} C_DERIVED_TBL, *LPC_DERIVED_TBL;

typedef struct {
	INT32	mincode[17];
	INT32	maxcode[18];
	int	valptr[17];
	JHUFF_TBL *pub;
	int	look_nbits[1<<HUFF_LOOKAHEAD];
	UINT8	look_sym[1<<HUFF_LOOKAHEAD];
} D_DERIVED_TBL, *LPD_DERIVED_TBL;

static const int ZIG[DCTSIZE2] = {
	 0,  1,  5,  6, 14, 15, 27, 28,
	 2,  4,  7, 13, 16, 26, 29, 42,
	 3,  8, 12, 17, 25, 30, 41, 43,
	 9, 11, 18, 24, 31, 40, 44, 53,
	10, 19, 23, 32, 39, 45, 52, 54,
	20, 22, 33, 38, 46, 51, 55, 60,
	21, 34, 37, 47, 50, 56, 59, 61,
	35, 36, 48, 49, 57, 58, 62, 63
};

static const int ZAG[DCTSIZE2+16] = {
	 0,  1,  8, 16,  9,  2,  3, 10,
	17, 24, 32, 25, 18, 11,  4,  5,
	12, 19, 26, 33, 40, 48, 41, 34,
	27, 20, 13,  6,  7, 14, 21, 28,
	35, 42, 49, 56, 57, 50, 43, 36,
	29, 22, 15, 23, 30, 37, 44, 51,
	58, 59, 52, 45, 38, 31, 39, 46,
	53, 60, 61, 54, 47, 55, 62, 63,
	 0,  0,  0,  0,  0,  0,  0,  0,
	 0,  0,  0,  0,  0,  0,  0,  0
};

static const UINT8 bits_dc_luminance[17] =
	{ 0, 0, 1, 5, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0 };
static const UINT8 val_dc_luminance[] =
	{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 };
static const UINT8 bits_dc_chrominance[17] =
	{ 0, 0, 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0 };
static const UINT8 val_dc_chrominance[] =
	{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 };
static const UINT8 bits_ac_luminance[17] =
	{ 0, 0, 2, 1, 3, 3, 2, 4, 3, 5, 5, 4, 4, 0, 0, 1, 0x7d };
static const UINT8 val_ac_luminance[] = {
	0x01, 0x02, 0x03, 0x00, 0x04, 0x11, 0x05, 0x12,
	0x21, 0x31, 0x41, 0x06, 0x13, 0x51, 0x61, 0x07,
	0x22, 0x71, 0x14, 0x32, 0x81, 0x91, 0xa1, 0x08,
	0x23, 0x42, 0xb1, 0xc1, 0x15, 0x52, 0xd1, 0xf0,
	0x24, 0x33, 0x62, 0x72, 0x82, 0x09, 0x0a, 0x16,
	0x17, 0x18, 0x19, 0x1a, 0x25, 0x26, 0x27, 0x28,
	0x29, 0x2a, 0x34, 0x35, 0x36, 0x37, 0x38, 0x39,
	0x3a, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48, 0x49,
	0x4a, 0x53, 0x54, 0x55, 0x56, 0x57, 0x58, 0x59,
	0x5a, 0x63, 0x64, 0x65, 0x66, 0x67, 0x68, 0x69,
	0x6a, 0x73, 0x74, 0x75, 0x76, 0x77, 0x78, 0x79,
	0x7a, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89,
	0x8a, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98,
	0x99, 0x9a, 0xa2, 0xa3, 0xa4, 0xa5, 0xa6, 0xa7,
	0xa8, 0xa9, 0xaa, 0xb2, 0xb3, 0xb4, 0xb5, 0xb6,
	0xb7, 0xb8, 0xb9, 0xba, 0xc2, 0xc3, 0xc4, 0xc5,
	0xc6, 0xc7, 0xc8, 0xc9, 0xca, 0xd2, 0xd3, 0xd4,
	0xd5, 0xd6, 0xd7, 0xd8, 0xd9, 0xda, 0xe1, 0xe2,
	0xe3, 0xe4, 0xe5, 0xe6, 0xe7, 0xe8, 0xe9, 0xea,
	0xf1, 0xf2, 0xf3, 0xf4, 0xf5, 0xf6, 0xf7, 0xf8,
	0xf9, 0xfa
};
static const UINT8 bits_ac_chrominance[17] =
	{ 0, 0, 2, 1, 2, 4, 4, 3, 4, 7, 5, 4, 4, 0, 1, 2, 0x77 };
static const UINT8 val_ac_chrominance[] = {
	0x00, 0x01, 0x02, 0x03, 0x11, 0x04, 0x05, 0x21,
	0x31, 0x06, 0x12, 0x41, 0x51, 0x07, 0x61, 0x71,
	0x13, 0x22, 0x32, 0x81, 0x08, 0x14, 0x42, 0x91,
	0xa1, 0xb1, 0xc1, 0x09, 0x23, 0x33, 0x52, 0xf0,
	0x15, 0x62, 0x72, 0xd1, 0x0a, 0x16, 0x24, 0x34,
	0xe1, 0x25, 0xf1, 0x17, 0x18, 0x19, 0x1a, 0x26,
	0x27, 0x28, 0x29, 0x2a, 0x35, 0x36, 0x37, 0x38,
	0x39, 0x3a, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48,
	0x49, 0x4a, 0x53, 0x54, 0x55, 0x56, 0x57, 0x58,
	0x59, 0x5a, 0x63, 0x64, 0x65, 0x66, 0x67, 0x68,
	0x69, 0x6a, 0x73, 0x74, 0x75, 0x76, 0x77, 0x78,
	0x79, 0x7a, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87,
	0x88, 0x89, 0x8a, 0x92, 0x93, 0x94, 0x95, 0x96,
	0x97, 0x98, 0x99, 0x9a, 0xa2, 0xa3, 0xa4, 0xa5,
	0xa6, 0xa7, 0xa8, 0xa9, 0xaa, 0xb2, 0xb3, 0xb4,
	0xb5, 0xb6, 0xb7, 0xb8, 0xb9, 0xba, 0xc2, 0xc3,
	0xc4, 0xc5, 0xc6, 0xc7, 0xc8, 0xc9, 0xca, 0xd2,
	0xd3, 0xd4, 0xd5, 0xd6, 0xd7, 0xd8, 0xd9, 0xda,
	0xe2, 0xe3, 0xe4, 0xe5, 0xe6, 0xe7, 0xe8, 0xe9,
	0xea, 0xf2, 0xf3, 0xf4, 0xf5, 0xf6, 0xf7, 0xf8,
	0xf9, 0xfa
};
static const unsigned int std_luminance_quant_tbl[DCTSIZE2] = {
	 16,  11,  12,  14,  12,  10,  16,  14,
	 13,  14,  18,  17,  16,  19,  24,  40,
	 26,  24,  22,  22,  24,  49,  35,  37,
	 29,  40,  58,  51,  61,  60,  57,  51,
	 56,  55,  64,  72,  92,  78,  64,  68,
	 87,  69,  55,  56,  80, 109,  81,  87,
	 95,  98, 103, 104, 103,  62,  77, 113,
	121, 112, 100, 120,  92, 101, 103,  99
};

static const unsigned int std_chrominance_quant_tbl[DCTSIZE2] = {
	17,  18,  18,  24,  21,  24,  47,  26,
	26,  47,  99,  66,  56,  66,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99,
	99,  99,  99,  99,  99,  99,  99,  99
};
static const INT16 aanscales[DCTSIZE2] = {
	16384, 22725, 21407, 19266, 16384, 12873,  8867,  4520,
	22725, 31521, 29692, 26722, 22725, 17855, 12299,  6270,
	21407, 29692, 27969, 25172, 21407, 16819, 11585,  5906,
	19266, 26722, 25172, 22654, 19266, 15137, 10426,  5315,
	16384, 22725, 21407, 19266, 16384, 12873,  8867,  4520,
	12873, 17855, 16819, 15137, 12873, 10114,  6967,  3552,
	 8867, 12299, 11585, 10426,  8867,  6967,  4799,  2446,
	 4520,  6270,  5906,  5315,  4520,  3552,  2446,  1247
};

static const int extend_test[16] = {
	0, 0x0001, 0x0002, 0x0004, 0x0008, 0x0010, 0x0020, 0x0040, 0x0080,
	0x0100, 0x0200, 0x0400, 0x0800, 0x1000, 0x2000, 0x4000
};

static const int extend_offset[16] = {
	0, ((-1)<<1) + 1, ((-1)<<2) + 1, ((-1)<<3) + 1, ((-1)<<4) + 1,
	((-1)<<5) + 1, ((-1)<<6) + 1, ((-1)<<7) + 1, ((-1)<<8) + 1,
	((-1)<<9) + 1, ((-1)<<10) + 1, ((-1)<<11) + 1, ((-1)<<12) + 1,
	((-1)<<13) + 1, ((-1)<<14) + 1, ((-1)<<15) + 1
};

static	INT32	*rgb_ycc_tab;
static	JSAMPLE	yccBuf[3][8*8];
static	JCOEF	MCUBuf[3][DCTSIZE2];
static	JHUFF_TBL	hTable[4] = {
	{(UINT8 *)bits_dc_luminance, (UINT8 *)val_dc_luminance},
	{(UINT8 *)bits_dc_chrominance, (UINT8 *)val_dc_chrominance},
	{(UINT8 *)bits_ac_luminance, (UINT8 *)val_ac_luminance},
	{(UINT8 *)bits_ac_chrominance, (UINT8 *)val_ac_chrominance}
};
static	LPC_DERIVED_TBL	cdTable;
static	LPD_DERIVED_TBL	ddTable;
static	JSAMPLE	rangeLimitTable[(5 * (MAXJSAMPLE+1) +
				CENTERJSAMPLE) * sizeof(JSAMPLE)];
static	JSAMPLE	*sampleRangeLimit;
static	INT32	Cr_r_tab[MAXJSAMPLE+1];
static	INT32	Cb_b_tab[MAXJSAMPLE+1];
static	INT32	Cr_g_tab[MAXJSAMPLE+1];
static	INT32	Cb_g_tab[MAXJSAMPLE+1];

static	void	SetQuality1(LPCINFO, int, int, int);
static	void	SetHuffTable1(JHUFF_TBL *, LPC_DERIVED_TBL);
static	void	MONO8_Y_Convert(LPCINFO, int, int, YCCTAB);
static	void	RGB16_YCC_Convert(LPCINFO, int, int, YCCTAB);
static	void	RGB24_YCC_Convert(LPCINFO, int, int, YCCTAB);
static	void	ForwardDCT(LPCINFO, int, JSAMPROW, JCOEFPTR);
static	void	FDCT(DCTELEM *);
static	BOOL	EncodeMCU(LPCINFO, JCOEFPTR, int,
					LPC_DERIVED_TBL, LPC_DERIVED_TBL);
static	void	FlushBits (LPCINFO);
static	void	EmitBits(LPCINFO, unsigned int, int);

static	void	SetQuality2(LPDINFO, int, int);
static	void	SetHuffTable2(JHUFF_TBL *, LPD_DERIVED_TBL);
static	void	FillBitBuffer(LPDINFO, int);
static	int	SlowDECODE(LPDINFO, LPD_DERIVED_TBL, int);
static	void	DecodeMCU(LPDINFO, JCOEFPTR, int,
					LPD_DERIVED_TBL, LPD_DERIVED_TBL);
static	void	InverseDCT(LPDINFO, int, JCOEFPTR, JSAMPROW);
static	void	Y_MONO8_Convert (LPDINFO, int, int, YCCTAB);
static	void	YCC_RGB16_Convert (LPDINFO, int, int, YCCTAB);
static	void	YCC_RGB24_Convert (LPDINFO, int, int, YCCTAB);

BOOL	InitVideoCompress()
{
	int	i;

	if ((rgb_ycc_tab = new INT32[TABLE_SIZE]) == NULL)
		return(FALSE);

	if ((cdTable = new C_DERIVED_TBL[4]) == NULL)
		return(FALSE);

	for (i = 0; i <= MAXJSAMPLE; i++) {
		rgb_ycc_tab[i+R_Y_OFF] = FIX(0.29900) * i;
		rgb_ycc_tab[i+G_Y_OFF] = FIX(0.58700) * i;
		rgb_ycc_tab[i+B_Y_OFF] = FIX(0.11400) * i + ONE_HALF;
		rgb_ycc_tab[i+R_CB_OFF] = (-FIX(0.16874)) * i;
		rgb_ycc_tab[i+G_CB_OFF] = (-FIX(0.33126)) * i;
		rgb_ycc_tab[i+B_CB_OFF] = FIX(0.50000) * i
						+ CBCR_OFFSET + ONE_HALF-1;
		rgb_ycc_tab[i+G_CR_OFF] = (-FIX(0.41869)) * i;
		rgb_ycc_tab[i+B_CR_OFF] = (-FIX(0.08131)) * i;
	}

	for (i = 0; i < 4; i++)
		SetHuffTable1(&hTable[i], &cdTable[i]);

	return(TRUE);
}

void ExitVideoCompress()
{
	if (rgb_ycc_tab != NULL) {
		delete [] rgb_ycc_tab;
		rgb_ycc_tab = NULL;
	}

	if (cdTable != NULL) {
		delete [] cdTable;
		cdTable = NULL;
	}
}

LPCINFO	AllocVideoCInfo()
{
	LPCINFO	cinfo;

	if ((cinfo = new CINFO) == NULL)
		return(NULL);

	memset(cinfo, 0, sizeof(CINFO));

	return(cinfo);
}

void	StartVideoCompress(LPCINFO cinfo, char *outbuf,
			char *inbuf, int width, int color, int qtblNo)
{
	int	i;

	cinfo->bitbuf = 0U;
	cinfo->putcount = 8;
	cinfo->outcount = 0;
	cinfo->putptr = outbuf;
	cinfo->inbuf= inbuf;
	cinfo->width = width;
	cinfo->color = color;
	cinfo->qtblNo = qtblNo;

	for (i = 0; i < 3; i++)
		cinfo->last_dc_val[i] = 0;
}

int	DoVideoCompress(LPCINFO cinfo, int cx, int cy)
{
	if (cinfo->color == 0) {
		MONO8_Y_Convert(cinfo, cx, cy, yccBuf);

		ForwardDCT(cinfo, 0, yccBuf[0], MCUBuf[0]);

		EncodeMCU(cinfo, MCUBuf[0], 0, &cdTable[0], &cdTable[2]);
	} else {
		if (cinfo->color == 1)
			RGB16_YCC_Convert(cinfo, cx, cy, yccBuf);
		else
			RGB24_YCC_Convert(cinfo, cx, cy, yccBuf);

		ForwardDCT(cinfo, 0, yccBuf[0], MCUBuf[0]);
		ForwardDCT(cinfo, 1, yccBuf[1], MCUBuf[1]);
		ForwardDCT(cinfo, 1, yccBuf[2], MCUBuf[2]);

		EncodeMCU(cinfo, MCUBuf[0], 0, &cdTable[0], &cdTable[2]);
		EncodeMCU(cinfo, MCUBuf[1], 1, &cdTable[1], &cdTable[3]);
		EncodeMCU(cinfo, MCUBuf[2], 2, &cdTable[1], &cdTable[3]);
	}

	return(cinfo->outcount);
}

int	FinishVideoCompress(LPCINFO cinfo)
{
	FlushBits(cinfo);

	return(cinfo->outcount);
}

void	SetVideoCompressQuality(LPCINFO cinfo, int quality, int edge)
{
	SetQuality1(cinfo, quality, edge, 0);
	SetQuality1(cinfo, quality >= 30 ? quality / LO_QUALITY1 : quality,
								edge, 1);
	SetQuality1(cinfo, quality >= 30 ? quality / LO_QUALITY2 : quality,
								edge / 2, 2);
}

static	void	SetHuffTable1(JHUFF_TBL *htbl, LPC_DERIVED_TBL dtbl)
{
	int	p, i, l, lastp, si;
	char	huffsize[257];
	unsigned int huffcode[257];
	unsigned int code;

	p = 0;
	for (l = 1; l <= 16; l++) {
		for (i = 1; i <= (int)htbl->bits[l]; i++)
			huffsize[p++] = (char)l;
	}
	huffsize[p] = 0;
	lastp = p;

	code = 0;
	si = huffsize[0];
	p = 0;
	while (huffsize[p]) {
		while (((int) huffsize[p]) == si) {
			huffcode[p++] = code;
			code++;
		}
		code <<= 1;
		si++;
	}

	memset(dtbl->ehufsi, 0, sizeof(dtbl->ehufsi));

	for (p = 0; p < lastp; p++) {
		dtbl->ehufco[htbl->huffval[p]] = huffcode[p];
		dtbl->ehufsi[htbl->huffval[p]] = huffsize[p];
	}
}

static	void	SetQuality1(LPCINFO cinfo, int quality, int edge, int qtblNo)
{
	int	i, r, k;
	long	temp;

	if (quality <= 0)
		quality = 1;

	if (quality > 100)
		quality = 100;

	if (quality < 50)
		quality = 5000 / quality;
	else
		quality = 200 - quality * 2;

	if (edge > 10)
		edge = 10;

	for (i = 0; i < DCTSIZE2; i++) {
		temp = ((long)std_luminance_quant_tbl[i] * quality + 50L)
								/ 100L;

		if (edge != 0) {
			r = (ZAG[i] / 8) + (ZAG[i] % 8);
			if (r > 8)
				r = 8;
			k = 40 / edge;
			temp = temp * k / (k + r);
		}

		if (temp <= 0L) temp = 1L;
		if (temp > 32767L) temp = 32767L;
		cinfo->divisor[qtblNo][0][i] = (DCTELEM)
				DESCALE(MULTIPLY16V16((INT32)temp,
				(INT32)aanscales[ZAG[i]]), CONST_BITS - 3);

		temp = ((long)std_chrominance_quant_tbl[i] * quality + 50L)
								/ 100L;
		if (temp <= 0L) temp = 1L;
		if (temp > 32767L) temp = 32767L;
		cinfo->divisor[qtblNo][1][i] = (DCTELEM)
				DESCALE(MULTIPLY16V16((INT32)temp,
				(INT32)aanscales[ZAG[i]]), CONST_BITS - 3);
	}
}

static	void	MONO8_Y_Convert(LPCINFO cinfo, int cx, int cy, YCCTAB pOutputBuf)
{
	int	row, col;
	JSAMPROW	outptr0;
	char	*inbuf;
	char	*inptr;
	int	width;

	inbuf = cinfo->inbuf;
	width = cinfo->width;

	outptr0 = pOutputBuf[0];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		inptr = inbuf + ((long)cy * width + cx);

		for (col = 0; col < DCTSIZE; col++)
			*outptr0++ = *inptr++;
	}
}

static	void	RGB16_YCC_Convert(LPCINFO cinfo, int cx, int cy, YCCTAB pOutputBuf)
{
	int	r, g, b;
	int	row, col;
	JSAMPROW	outptr0, outptr1, outptr2;
	char	*inbuf;
	char	*inptr;
	int	width;
	UINT16	tmp;

	inbuf = cinfo->inbuf;
	width = cinfo->width;

	outptr0 = pOutputBuf[0];
	outptr1 = pOutputBuf[1];
	outptr2 = pOutputBuf[2];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		inptr = inbuf + ((long)cy * width + cx) * 2;

		for (col = 0; col < DCTSIZE; col++) {
			tmp = *(UINT16 *)inptr;
			b = (UINT8)(tmp << 3);
			tmp >>= 5;
			g = (UINT8)(tmp << 3);
			tmp >>= 5;
			r = (UINT8)(tmp << 3);
			inptr += 2;

			/* Y */
			*outptr0++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_Y_OFF]
					+ rgb_ycc_tab[g+G_Y_OFF]
					+ rgb_ycc_tab[b+B_Y_OFF])
					>> SCALEBITS);

			/* Cb */
			*outptr1++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_CB_OFF]
					+ rgb_ycc_tab[g+G_CB_OFF]
					+ rgb_ycc_tab[b+B_CB_OFF])	
					 >> SCALEBITS);

			/* Cr */
			*outptr2++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_CR_OFF]
					+ rgb_ycc_tab[g+G_CR_OFF]
					+ rgb_ycc_tab[b+B_CR_OFF])
					 >> SCALEBITS);
		}
	}
}

static	void	RGB24_YCC_Convert(LPCINFO cinfo, int cx, int cy, YCCTAB pOutputBuf)
{
	int	r, g, b;
	int	row, col;
	JSAMPROW	outptr0, outptr1, outptr2;
	char	*inbuf;
	char	*inptr;
	int	width;

	inbuf = cinfo->inbuf;
	width = cinfo->width;

	outptr0 = pOutputBuf[0];
	outptr1 = pOutputBuf[1];
	outptr2 = pOutputBuf[2];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		inptr = inbuf + ((long)cy * width + cx) * 3;

		for (col = 0; col < DCTSIZE; col++) {
			b = *inptr++;
			g = *inptr++;
			r = *inptr++;

			/* Y */
			*outptr0++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_Y_OFF]
					+ rgb_ycc_tab[g+G_Y_OFF]
					+ rgb_ycc_tab[b+B_Y_OFF])
					>> SCALEBITS);

			/* Cb */
			*outptr1++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_CB_OFF]
					+ rgb_ycc_tab[g+G_CB_OFF]
					+ rgb_ycc_tab[b+B_CB_OFF])	
					 >> SCALEBITS);

			/* Cr */
			*outptr2++ = (JSAMPLE)
					((rgb_ycc_tab[r+R_CR_OFF]
					+ rgb_ycc_tab[g+G_CR_OFF]
					+ rgb_ycc_tab[b+B_CR_OFF])
					 >> SCALEBITS);
		}
	}
}

static	void	ForwardDCT(LPCINFO cinfo, int index, JSAMPROW inputBuf, JCOEFPTR outputBuf)
{
	DCTELEM	*divisors = cinfo->divisor[cinfo->qtblNo][index];
	DCTELEM	workspace[DCTSIZE2];
	DCTELEM	*workspaceptr;
	int	elemr;
	DCTELEM temp, qval;
	int i;

	workspaceptr = workspace;
	for (elemr = 0; elemr < DCTSIZE; elemr++) {
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
		*workspaceptr++ = (DCTELEM)(*inputBuf++) - CENTERJSAMPLE;
	}

	FDCT(workspace);

	for (i = 0; i < DCTSIZE2; i++) {
		qval = *divisors++;
		temp = workspace[ZAG[i]];
		if (temp < 0) {
			temp = -temp;
			temp += qval>>1;
			temp /= qval;
			temp = -temp;
		} else {
			temp += qval>>1;
			temp /= qval;
		}
		*outputBuf++ = (JCOEF)temp;
	}
}

static	void	FDCT(DCTELEM *data)
{
	DCTELEM tmp0, tmp1, tmp2, tmp3, tmp4, tmp5, tmp6, tmp7;
	DCTELEM tmp10, tmp11, tmp12, tmp13;
	DCTELEM z1, z2, z3, z4, z5, z11, z13;
	DCTELEM *dataptr;
	int ctr;

	dataptr = data;
	for (ctr = DCTSIZE-1; ctr >= 0; ctr--) {
		tmp0 = dataptr[0] + dataptr[7];
		tmp7 = dataptr[0] - dataptr[7];
		tmp1 = dataptr[1] + dataptr[6];
		tmp6 = dataptr[1] - dataptr[6];
		tmp2 = dataptr[2] + dataptr[5];
		tmp5 = dataptr[2] - dataptr[5];
		tmp3 = dataptr[3] + dataptr[4];
		tmp4 = dataptr[3] - dataptr[4];

		tmp10 = tmp0 + tmp3;
		tmp13 = tmp0 - tmp3;
		tmp11 = tmp1 + tmp2;
		tmp12 = tmp1 - tmp2;

		dataptr[0] = tmp10 + tmp11;
		dataptr[4] = tmp10 - tmp11;

		z1 = MULTIPLY(tmp12 + tmp13, FIX_0_707106781);
		dataptr[2] = tmp13 + z1;
		dataptr[6] = tmp13 - z1;

		tmp10 = tmp4 + tmp5;
		tmp11 = tmp5 + tmp6;
		tmp12 = tmp6 + tmp7;

		z5 = MULTIPLY(tmp10 - tmp12, FIX_0_382683433);
		z2 = MULTIPLY(tmp10, FIX_0_541196100) + z5;
		z4 = MULTIPLY(tmp12, FIX_1_306562965) + z5;
		z3 = MULTIPLY(tmp11, FIX_0_707106781);

		z11 = tmp7 + z3;
		z13 = tmp7 - z3;

		dataptr[5] = z13 + z2;
		dataptr[3] = z13 - z2;
		dataptr[1] = z11 + z4;
		dataptr[7] = z11 - z4;

		dataptr += DCTSIZE;
	}

	dataptr = data;
	for (ctr = DCTSIZE-1; ctr >= 0; ctr--) {
		tmp0 = dataptr[DCTSIZE*0] + dataptr[DCTSIZE*7];
		tmp7 = dataptr[DCTSIZE*0] - dataptr[DCTSIZE*7];
		tmp1 = dataptr[DCTSIZE*1] + dataptr[DCTSIZE*6];
		tmp6 = dataptr[DCTSIZE*1] - dataptr[DCTSIZE*6];
		tmp2 = dataptr[DCTSIZE*2] + dataptr[DCTSIZE*5];
		tmp5 = dataptr[DCTSIZE*2] - dataptr[DCTSIZE*5];
		tmp3 = dataptr[DCTSIZE*3] + dataptr[DCTSIZE*4];
		tmp4 = dataptr[DCTSIZE*3] - dataptr[DCTSIZE*4];

		tmp10 = tmp0 + tmp3;
		tmp13 = tmp0 - tmp3;
		tmp11 = tmp1 + tmp2;
		tmp12 = tmp1 - tmp2;

		dataptr[DCTSIZE*0] = tmp10 + tmp11;
		dataptr[DCTSIZE*4] = tmp10 - tmp11;

		z1 = MULTIPLY(tmp12 + tmp13, FIX_0_707106781);
		dataptr[DCTSIZE*2] = tmp13 + z1;
		dataptr[DCTSIZE*6] = tmp13 - z1;

		tmp10 = tmp4 + tmp5;
		tmp11 = tmp5 + tmp6;
		tmp12 = tmp6 + tmp7;

		z5 = MULTIPLY(tmp10 - tmp12, FIX_0_382683433);
		z2 = MULTIPLY(tmp10, FIX_0_541196100) + z5;
		z4 = MULTIPLY(tmp12, FIX_1_306562965) + z5;
		z3 = MULTIPLY(tmp11, FIX_0_707106781);

		z11 = tmp7 + z3;
		z13 = tmp7 - z3;

		dataptr[DCTSIZE*5] = z13 + z2;
		dataptr[DCTSIZE*3] = z13 - z2;
		dataptr[DCTSIZE*1] = z11 + z4;
		dataptr[DCTSIZE*7] = z11 - z4;

		dataptr++;
	}
}

static	BOOL	EncodeMCU(LPCINFO cinfo, JCOEFPTR block, int index,
	 		LPC_DERIVED_TBL dctbl, LPC_DERIVED_TBL actbl)
{
	int	temp, temp2;
	int	nbits;
	int	k, r, i;

	temp = temp2 = block[0] - cinfo->last_dc_val[index];

	if (temp < 0) {
		temp = -temp;
		temp2--;
	}

	nbits = 0;
	while (temp) {
		nbits++;
		temp >>= 1;
	}

	EmitBits(cinfo, dctbl->ehufco[nbits], dctbl->ehufsi[nbits]);

	if (nbits)
		EmitBits(cinfo, (unsigned int)temp2, nbits);

	r = 0;

	for (k = 1; k < DCTSIZE2; k++) {
		if ((temp = block[k]) == 0) {
			r++;
		} else {
			while (r > 15) {
				EmitBits(cinfo, actbl->ehufco[0xF0],
							actbl->ehufsi[0xF0]);
				r -= 16;
			}

			temp2 = temp;
			if (temp < 0) {
				temp = -temp;
				temp2--;
			}

			nbits = 1;
			while ((temp >>= 1))
				nbits++;

			i = (r << 4) + nbits;
			EmitBits(cinfo, actbl->ehufco[i], actbl->ehufsi[i]);

			EmitBits(cinfo, (unsigned int) temp2, nbits);

			r = 0;
		}
	}

	if (r > 0)
		EmitBits(cinfo, actbl->ehufco[0], actbl->ehufsi[0]);

	cinfo->last_dc_val[index] = block[0];

	return TRUE;
}

static	void	FlushBits (LPCINFO cinfo)
{
	EmitBits(cinfo, 0x7F, 7);

	cinfo->bitbuf = 0U;
	cinfo->putcount = 8;
}

static	void	EmitBits(LPCINFO cinfo, unsigned int x, int n)
{
	while (n >= cinfo->putcount) {
		n -= cinfo->putcount;
		cinfo->bitbuf |= rightbits(cinfo->putcount, x >> n);
		*cinfo->putptr++ = cinfo->bitbuf;
		cinfo->bitbuf = 0U;  cinfo->putcount = 8;  cinfo->outcount++;
	}
	cinfo->putcount -= n;
	cinfo->bitbuf |= rightbits(n, x) << cinfo->putcount;
}

BOOL	InitVideoDecompress()
{
	JSAMPLE	*table;
	INT32	x;
	int	i;

	if ((ddTable = new D_DERIVED_TBL[4]) == NULL)
		return(FALSE);

	for (i = 0; i < 4; i++)
		SetHuffTable2(&hTable[i], &ddTable[i]);

	table = rangeLimitTable;
	table += (MAXJSAMPLE+1);
	sampleRangeLimit = table;

	memset(table - (MAXJSAMPLE+1), 0, (MAXJSAMPLE+1) * sizeof(JSAMPLE));

	for (i = 0; i <= MAXJSAMPLE; i++)
		table[i] = (JSAMPLE) i;
	table += CENTERJSAMPLE;

	for (i = CENTERJSAMPLE; i < 2*(MAXJSAMPLE+1); i++)
		table[i] = MAXJSAMPLE;

	memset(table + (2 * (MAXJSAMPLE+1)), 0,
		(2 * (MAXJSAMPLE+1) - CENTERJSAMPLE) * sizeof(JSAMPLE));
	memcpy(table + (4 * (MAXJSAMPLE+1) - CENTERJSAMPLE),
		sampleRangeLimit, CENTERJSAMPLE * sizeof(JSAMPLE));

	for (i = 0, x = -CENTERJSAMPLE; i <= MAXJSAMPLE; i++, x++) {
		Cr_r_tab[i] = (int)
			RIGHT_SHIFT(FIX(1.40200) * x + ONE_HALF, SCALEBITS);
		Cb_b_tab[i] = (int)
			RIGHT_SHIFT(FIX(1.77200) * x + ONE_HALF, SCALEBITS);
		Cr_g_tab[i] = (-FIX(0.71414)) * x;
		Cb_g_tab[i] = (-FIX(0.34414)) * x + ONE_HALF;
	}

	return(TRUE);
}

void ExitVideoDecompress()
{
	if (ddTable != NULL) {
		delete [] ddTable;
		ddTable = NULL;
	}
}

LPDINFO	AllocVideoDInfo()
{
	LPDINFO	dinfo;

	if ((dinfo = new DINFO) == NULL)
		return(NULL);

	memset(dinfo, 0, sizeof(DINFO));

	return(dinfo);
}

void	StartVideoDecompress(LPDINFO dinfo, char *inbuf,
			char *outbuf, int width, int color, int qtblNo)
{
	int	i;

	dinfo->get_buffer = 0;
	dinfo->bits_left = 0;
	dinfo->next_input_byte = (JOCTET *)inbuf;
	dinfo->outbuf= outbuf;
	dinfo->width = width;
	dinfo->color = color;
	dinfo->qtblNo = qtblNo;

	for (i = 0; i < 3; i++)
		dinfo->last_dc_val[i] = 0;
}

void	DoVideoDecompress(LPDINFO dinfo, int x, int y)
{
	if (dinfo->color == 0) {
		memset(MCUBuf, 0, sizeof(MCUBuf) / 3);

		DecodeMCU(dinfo, MCUBuf[0], 0, &ddTable[0], &ddTable[2]);
		InverseDCT(dinfo, 0, MCUBuf[0], yccBuf[0]);
		Y_MONO8_Convert(dinfo, x, y, yccBuf);
	} else {
		memset(MCUBuf, 0, sizeof(MCUBuf));

		DecodeMCU(dinfo, MCUBuf[0], 0, &ddTable[0], &ddTable[2]);
		DecodeMCU(dinfo, MCUBuf[1], 1, &ddTable[1], &ddTable[3]);
		DecodeMCU(dinfo, MCUBuf[2], 2, &ddTable[1], &ddTable[3]);

		InverseDCT(dinfo, 0, MCUBuf[0], yccBuf[0]);
		InverseDCT(dinfo, 1, MCUBuf[1], yccBuf[1]);
		InverseDCT(dinfo, 1, MCUBuf[2], yccBuf[2]);

		if (dinfo->color == 1)
			YCC_RGB16_Convert(dinfo, x, y, yccBuf);
		else
			YCC_RGB24_Convert(dinfo, x, y, yccBuf);
	}
}

void	SetVideoDecompressQuality(LPDINFO dinfo, int quality)
{
	SetQuality2(dinfo, quality, 0);
	SetQuality2(dinfo, quality >= 30 ? quality / LO_QUALITY1 : quality, 1);
	SetQuality2(dinfo, quality >= 30 ? quality / LO_QUALITY2 : quality, 2);
}

static	void	SetQuality2(LPDINFO dinfo, int quality, int qtblNo)
{
	int	i;
	long	temp;
	UINT16	quantTable[2][DCTSIZE2];

	if (quality <= 0)
		quality = 1;

	if (quality > 100)
		quality = 100;

	if (quality < 50)
		quality = 5000 / quality;
	else
		quality = 200 - quality * 2;

	for (i = 0; i < DCTSIZE2; i++) {
		temp = ((long)std_luminance_quant_tbl[i] * quality + 50L)
								/ 100L;
		if (temp <= 0L) temp = 1L;
		if (temp > 32767L) temp = 32767L;
		quantTable[0][i] = (UINT16)temp;

		temp = ((long)std_chrominance_quant_tbl[i] * quality + 50L)
								/ 100L;
		if (temp <= 0L) temp = 1L;
		if (temp > 32767L) temp = 32767L;
		quantTable[1][i] = (UINT16)temp;
	}

	for (i = 0; i < DCTSIZE2; i++) {
		dinfo->dctTable[qtblNo][0][i] = (IFAST_MULT_TYPE)
			DESCALE(MULTIPLY16V16(
			(INT32)quantTable[0][ZIG[i]],
			(INT32)aanscales[i]), CONST_BITS-IFAST_SCALE_BITS);

		dinfo->dctTable[qtblNo][1][i] = (IFAST_MULT_TYPE)
			DESCALE(MULTIPLY16V16(
			(INT32)quantTable[1][ZIG[i]],
			(INT32)aanscales[i]), CONST_BITS-IFAST_SCALE_BITS);
	}
}

static	void	SetHuffTable2(JHUFF_TBL *htbl, LPD_DERIVED_TBL dtbl)
{
	int p, i, l, si;
	int lookbits, ctr;
	char huffsize[257];
	unsigned int huffcode[257];
	unsigned int code;

	dtbl->pub = htbl;

	p = 0;
	for (l = 1; l <= 16; l++) {
		for (i = 1; i <= (int) htbl->bits[l]; i++)
			huffsize[p++] = (char)l;
	}
	huffsize[p] = 0;

	code = 0;
	si = huffsize[0];
	p = 0;
	while (huffsize[p]) {
		while (((int) huffsize[p]) == si) {
			huffcode[p++] = code;
			code++;
		}
		code <<= 1;
		si++;
	}

	p = 0;
	for (l = 1; l <= 16; l++) {
		if (htbl->bits[l]) {
			dtbl->valptr[l] = p;
			dtbl->mincode[l] = huffcode[p];
			p += htbl->bits[l];
			dtbl->maxcode[l] = huffcode[p-1];
		} else {
			dtbl->maxcode[l] = -1;
		}
	}
	dtbl->maxcode[17] = 0xFFFFFL;

	memset(dtbl->look_nbits, 0, sizeof(dtbl->look_nbits));

	p = 0;
	for (l = 1; l <= HUFF_LOOKAHEAD; l++) {
		for (i = 1; i <= (int) htbl->bits[l]; i++, p++) {
			lookbits = huffcode[p] << (HUFF_LOOKAHEAD-l);
			for (ctr = 1 << (HUFF_LOOKAHEAD-l); ctr > 0; ctr--) {
				dtbl->look_nbits[lookbits] = l;
				dtbl->look_sym[lookbits] = htbl->huffval[p];
				lookbits++;
			}
		}
	}
}

static	void	FillBitBuffer(LPDINFO dinfo, int nbits)
{
	while (dinfo->bits_left < MIN_GET_BITS) {
		dinfo->get_buffer =
			(dinfo->get_buffer << 8) | *dinfo->next_input_byte++;
		dinfo->bits_left += 8;
	}
}

static	int	SlowDECODE(LPDINFO dinfo, LPD_DERIVED_TBL htbl, int min_bits)
{
	int	l = min_bits;
	INT32	code;

	check_bit_buffer(l);
	code = get_bits(l);

	while (code > htbl->maxcode[l]) {
		code <<= 1;
		check_bit_buffer(1);
		code |= get_bits(1);
		l++;
	}

	if (l > 16)
		return 0;

	return htbl->pub->huffval[htbl->valptr[l] +
			    ((int)(code - htbl->mincode[l]))];
}

static	void	DecodeMCU(LPDINFO dinfo, JCOEFPTR block, int index,
	 		LPD_DERIVED_TBL dctbl, LPD_DERIVED_TBL actbl)
{
	int	s, k, r;

	huff_DECODE(s, dctbl, label1);
	if (s) {
		check_bit_buffer(s);
		r = get_bits(s);
		s = huff_EXTEND(r, s);
	}

	s += dinfo->last_dc_val[index];
	dinfo->last_dc_val[index] = s;

	block[0] = (JCOEF)s;

	for (k = 1; k < DCTSIZE2; k++) {
		huff_DECODE(s, actbl, label2);

		r = s >> 4;
		s &= 15;

		if (s) {
			k += r;
			check_bit_buffer(s);
			r = get_bits(s);
			s = huff_EXTEND(r, s);
			block[ZAG[k]] = (JCOEF) s;
		} else {
			if (r != 15)
				break;
			k += 15;
		}
	}
}

static	void	InverseDCT(LPDINFO dinfo, int index,
			 JCOEFPTR coef_block, JSAMPROW output_buf)
{
	DCTELEM	tmp0, tmp1, tmp2, tmp3, tmp4, tmp5, tmp6, tmp7;
	DCTELEM	tmp10, tmp11, tmp12, tmp13;
	DCTELEM	z5, z10, z11, z12, z13;
	IFAST_MULT_TYPE *quantptr = dinfo->dctTable[dinfo->qtblNo][index];
	JCOEFPTR inptr;
	int	*wsptr;
	JSAMPROW outptr;
	JSAMPLE	*range_limit = sampleRangeLimit + CENTERJSAMPLE;
	int	ctr;
	int	workspace[DCTSIZE2];

	inptr = coef_block;
	wsptr = workspace;
	for (ctr = DCTSIZE; ctr > 0; ctr--) {
		if ((inptr[DCTSIZE*1] | inptr[DCTSIZE*2] | inptr[DCTSIZE*3] |
				inptr[DCTSIZE*4] | inptr[DCTSIZE*5] |
				inptr[DCTSIZE*6] | inptr[DCTSIZE*7]) == 0) {
			int dcval = (int)DEQUANTIZE(inptr[DCTSIZE*0],
							quantptr[DCTSIZE*0]);

			wsptr[DCTSIZE*0] = dcval;
			wsptr[DCTSIZE*1] = dcval;
			wsptr[DCTSIZE*2] = dcval;
			wsptr[DCTSIZE*3] = dcval;
			wsptr[DCTSIZE*4] = dcval;
			wsptr[DCTSIZE*5] = dcval;
			wsptr[DCTSIZE*6] = dcval;
			wsptr[DCTSIZE*7] = dcval;

			inptr++;
			quantptr++;
			wsptr++;
			continue;
		}

		tmp0 = DEQUANTIZE(inptr[DCTSIZE*0], quantptr[DCTSIZE*0]);
		tmp1 = DEQUANTIZE(inptr[DCTSIZE*2], quantptr[DCTSIZE*2]);
		tmp2 = DEQUANTIZE(inptr[DCTSIZE*4], quantptr[DCTSIZE*4]);
		tmp3 = DEQUANTIZE(inptr[DCTSIZE*6], quantptr[DCTSIZE*6]);

		tmp10 = tmp0 + tmp2;
		tmp11 = tmp0 - tmp2;

		tmp13 = tmp1 + tmp3;
		tmp12 = MULTIPLY(tmp1 - tmp3, FIX_1_414213562) - tmp13;

		tmp0 = tmp10 + tmp13;
		tmp3 = tmp10 - tmp13;
		tmp1 = tmp11 + tmp12;
		tmp2 = tmp11 - tmp12;

		tmp4 = DEQUANTIZE(inptr[DCTSIZE*1], quantptr[DCTSIZE*1]);
		tmp5 = DEQUANTIZE(inptr[DCTSIZE*3], quantptr[DCTSIZE*3]);
		tmp6 = DEQUANTIZE(inptr[DCTSIZE*5], quantptr[DCTSIZE*5]);
		tmp7 = DEQUANTIZE(inptr[DCTSIZE*7], quantptr[DCTSIZE*7]);

		z13 = tmp6 + tmp5;
		z10 = tmp6 - tmp5;
		z11 = tmp4 + tmp7;
		z12 = tmp4 - tmp7;

		tmp7 = z11 + z13;
		tmp11 = MULTIPLY(z11 - z13, FIX_1_414213562);

		z5 = MULTIPLY(z10 + z12, FIX_1_847759065);
		tmp10 = MULTIPLY(z12, FIX_1_082392200) - z5;
		tmp12 = MULTIPLY(z10, - FIX_2_613125930) + z5;

		tmp6 = tmp12 - tmp7;
		tmp5 = tmp11 - tmp6;
		tmp4 = tmp10 + tmp5;

		wsptr[DCTSIZE*0] = (int) (tmp0 + tmp7);
		wsptr[DCTSIZE*7] = (int) (tmp0 - tmp7);
		wsptr[DCTSIZE*1] = (int) (tmp1 + tmp6);
		wsptr[DCTSIZE*6] = (int) (tmp1 - tmp6);
		wsptr[DCTSIZE*2] = (int) (tmp2 + tmp5);
		wsptr[DCTSIZE*5] = (int) (tmp2 - tmp5);
		wsptr[DCTSIZE*4] = (int) (tmp3 + tmp4);
		wsptr[DCTSIZE*3] = (int) (tmp3 - tmp4);

		inptr++;
		quantptr++;
		wsptr++;
	}

	wsptr = workspace;
	for (ctr = 0; ctr < DCTSIZE; ctr++) {
		outptr = &output_buf[ctr * DCTSIZE];

		if ((wsptr[1] | wsptr[2] | wsptr[3] | wsptr[4] | wsptr[5] |
				wsptr[6] | wsptr[7]) == 0) {
			JSAMPLE dcval = range_limit[IDESCALE(wsptr[0],
						PASS1_BITS+3) & RANGE_MASK];

			outptr[0] = dcval;
			outptr[1] = dcval;
			outptr[2] = dcval;
			outptr[3] = dcval;
			outptr[4] = dcval;
			outptr[5] = dcval;
			outptr[6] = dcval;
			outptr[7] = dcval;

			wsptr += DCTSIZE;
			continue;
		}

		tmp10 = ((DCTELEM) wsptr[0] + (DCTELEM) wsptr[4]);
		tmp11 = ((DCTELEM) wsptr[0] - (DCTELEM) wsptr[4]);

		tmp13 = ((DCTELEM) wsptr[2] + (DCTELEM) wsptr[6]);
		tmp12 = MULTIPLY((DCTELEM) wsptr[2] -
				(DCTELEM) wsptr[6], FIX_1_414213562) - tmp13;

		tmp0 = tmp10 + tmp13;
		tmp3 = tmp10 - tmp13;
		tmp1 = tmp11 + tmp12;
		tmp2 = tmp11 - tmp12;

		z13 = (DCTELEM) wsptr[5] + (DCTELEM) wsptr[3];
		z10 = (DCTELEM) wsptr[5] - (DCTELEM) wsptr[3];
		z11 = (DCTELEM) wsptr[1] + (DCTELEM) wsptr[7];
		z12 = (DCTELEM) wsptr[1] - (DCTELEM) wsptr[7];

		tmp7 = z11 + z13;
		tmp11 = MULTIPLY(z11 - z13, FIX_1_414213562);

		z5 = MULTIPLY(z10 + z12, FIX_1_847759065);
		tmp10 = MULTIPLY(z12, FIX_1_082392200) - z5;
		tmp12 = MULTIPLY(z10, - FIX_2_613125930) + z5;

		tmp6 = tmp12 - tmp7;
		tmp5 = tmp11 - tmp6;
		tmp4 = tmp10 + tmp5;

		outptr[0] = range_limit[IDESCALE(tmp0 + tmp7, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[7] = range_limit[IDESCALE(tmp0 - tmp7, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[1] = range_limit[IDESCALE(tmp1 + tmp6, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[6] = range_limit[IDESCALE(tmp1 - tmp6, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[2] = range_limit[IDESCALE(tmp2 + tmp5, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[5] = range_limit[IDESCALE(tmp2 - tmp5, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[4] = range_limit[IDESCALE(tmp3 + tmp4, PASS1_BITS+3)
						& RANGE_MASK];
		outptr[3] = range_limit[IDESCALE(tmp3 - tmp4, PASS1_BITS+3)
						& RANGE_MASK];

		wsptr += DCTSIZE;
	}
}

static	void	Y_MONO8_Convert (LPDINFO dinfo, int cx, int cy,
							YCCTAB input_buf)
{
	int	row, col;
	int	y;
	JSAMPROW	inptr0;
	char	*outbuf;
	char	*outptr;
	int	width;

	outbuf = dinfo->outbuf;
	width = dinfo->width;

	inptr0 = input_buf[0];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		outptr = outbuf + ((long)cy * width + cx) * 3;

		for (col = 0; col < DCTSIZE; col++) {
			y = *inptr0++;
			*outptr++ = y;
			*outptr++ = y;
			*outptr++ = y;
		}
	}
}

static	void	YCC_RGB16_Convert (LPDINFO dinfo, int cx, int cy,
							YCCTAB input_buf)
{
	int	y, cb, cr;
	int	r, g, b;
	int	row, col;
	UINT16	tmp;
	JSAMPROW	inptr0, inptr1, inptr2;
	char	*outbuf;
	char	*outptr;
	int	width;

	outbuf = dinfo->outbuf;
	width = dinfo->width;

	inptr0 = input_buf[0];
	inptr1 = input_buf[1];
	inptr2 = input_buf[2];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		outptr = outbuf + ((long)cy * width + cx) * 2;

		for (col = 0; col < DCTSIZE; col++) {
			y  = *inptr0++;
			cb = *inptr1++;
			cr = *inptr2++;

			b = sampleRangeLimit[y + Cb_b_tab[cb]];
			g = sampleRangeLimit[y +
			      ((int) RIGHT_SHIFT(Cb_g_tab[cb] + Cr_g_tab[cr],
						 SCALEBITS))];
			r = sampleRangeLimit[y + Cr_r_tab[cr]];

			tmp = (r >> 3);
			tmp <<= 5;
			tmp |= (g >> 3);
			tmp <<= 5;
			tmp |= (b >> 3);
			*(UINT16 *)outptr = tmp;
			outptr += 2;
		}
	}
}

static	void	YCC_RGB24_Convert (LPDINFO dinfo, int cx, int cy,
							YCCTAB input_buf)
{
	int	y, cb, cr;
	int	row, col;
	JSAMPROW	inptr0, inptr1, inptr2;
	char	*outbuf;
	char	*outptr;
	int	width;

	outbuf = dinfo->outbuf;
	width = dinfo->width;

	inptr0 = input_buf[0];
	inptr1 = input_buf[1];
	inptr2 = input_buf[2];

	for (row = 0; row < DCTSIZE; row++, cy++) {
		outptr = outbuf + ((long)cy * width + cx) * 3;

		for (col = 0; col < DCTSIZE; col++) {
			y  = *inptr0++;
			cb = *inptr1++;
			cr = *inptr2++;

			*outptr++ = sampleRangeLimit[y + Cb_b_tab[cb]];
			*outptr++ = sampleRangeLimit[y +
			      ((int) RIGHT_SHIFT(Cb_g_tab[cb] + Cr_g_tab[cr],
						 SCALEBITS))];
			*outptr++ = sampleRangeLimit[y + Cr_r_tab[cr]];
		}
	}
}
