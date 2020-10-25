/************************************************************************
 *
 *			ÇoÇbÅ|ÇuÇhÇcÇdÇn
 *
 *		ñºèÃ		: âπê∫à≥èkèàóù
 *		ÉtÉ@ÉCÉãñº	: cdaudio.cpp
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "pcvcomm.h"

#define	MAX_LENGTH	2048

#define rightbits(n, x) ((x) & ((1U << (n)) - 1U))
#define HUFF_LOOKAHEAD	8
#define MIN_GET_BITS  25

#define check_bit_buffer(nbits) \
	{ if (dhuff.bits_left < (nbits))  \
	    FillBitBuffer(nbits);  \
	      }

#define get_bits(nbits) \
	(((int) (dhuff.get_buffer >> \
	(dhuff.bits_left -= (nbits)))) & ((1<<(nbits))-1))

#define peek_bits(nbits) \
	(((int) (dhuff.get_buffer >> \
	(dhuff.bits_left -  (nbits)))) & ((1<<(nbits))-1))

#define drop_bits(nbits) \
	(dhuff.bits_left -= (nbits))

#define huff_DECODE(result,htbl,donelabel) \
{ if (dhuff.bits_left < HUFF_LOOKAHEAD) {  \
    FillBitBuffer(0);  \
    if (dhuff.bits_left < HUFF_LOOKAHEAD) {  \
      result = SlowDECODE(htbl, 1);  \
      goto donelabel;  \
    }  \
  }  \
  { int nb, look;  \
    look = peek_bits(HUFF_LOOKAHEAD);  \
    if ((nb = htbl->look_nbits[look]) != 0) {  \
      drop_bits(nb);  \
      result = htbl->look_sym[look];  \
    } else {  \
      result = SlowDECODE(htbl, HUFF_LOOKAHEAD+1);  \
    }  \
  }  \
donelabel:;  \
}

#define huff_EXTEND(x,s)  ((x) < extend_test[s] ? (x) + extend_offset[s] : (x))

typedef	unsigned char	UINT8;
//typedef	long	INT32;

typedef	struct	{
	int	outcount;
	int	getcount;
	int	putcount;
	unsigned bitbuf;
	char	*putptr;
	int	bufSize;
} CHUFFINFO;

typedef	struct	{
	INT32	get_buffer;
	int	bits_left;
	char	*next_input_byte;
} DHUFFINFO;

typedef struct {
	unsigned int ehufco[256];
	char ehufsi[256];
} C_DERIVED_TBL, *LPC_DERIVED_TBL;

typedef struct {
	INT32	mincode[17];
	INT32	maxcode[18];
	int	valptr[17];
	int	look_nbits[1<<HUFF_LOOKAHEAD];
	UINT8	look_sym[1<<HUFF_LOOKAHEAD];
} D_DERIVED_TBL, *LPD_DERIVED_TBL;

static const UINT8 bits_huff[17] =
/*
	{ 0, 0, 2, 1, 3, 3, 2, 4, 3, 5, 5, 4, 4, 0, 0, 1, 0x7d };
*/
	{ 0, 0, 2, 1, 2, 4, 4, 3, 4, 7, 5, 4, 4, 0, 1, 2, 0x77 };

static const UINT8 val_huff[] = {
/*
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
*/
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

static	CHUFFINFO	chuff;
static	DHUFFINFO	dhuff;
static	LPC_DERIVED_TBL	cdTable;
static	LPD_DERIVED_TBL	ddTable;
static	int	bInit;

static	void	SetHuffTable1(const UINT8 *, const UINT8 *, LPC_DERIVED_TBL);
static	void	SetHuffTable2(const UINT8 *, const UINT8 *, LPD_DERIVED_TBL);
static	void	EncodeHuff(short *, int);
static	void	FlushBits ();
static	void	EmitBits(unsigned int, int);
static	void	FillBitBuffer(int);
static	int	SlowDECODE(LPD_DERIVED_TBL, int);
static	void	DecodeHuff(short *, int);

void	InitializeAudioCompress()
{
	if (bInit)
		return;

	bInit = 1;

	/* Huffman ÉRÅ[ÉhÉeÅ[ÉuÉãçÏê¨ */
	if ((cdTable = new C_DERIVED_TBL) == NULL)
		return;
	SetHuffTable1(bits_huff, val_huff, cdTable);

	/* Huffman ÉfÉRÅ[ÉhÉeÅ[ÉuÉãçÏê¨ */
	if ((ddTable = new D_DERIVED_TBL) == NULL)
		return;
	SetHuffTable2(bits_huff, val_huff, ddTable);
}

void ExitAudioCompress()
{
	if (cdTable != NULL) {
		delete [] cdTable;
		cdTable = NULL;
	}

	if (ddTable != NULL) {
		delete [] ddTable;
		ddTable = NULL;
	}
}

static	void	SetHuffTable1(const UINT8 *bits, const UINT8 *val,
							LPC_DERIVED_TBL dtbl)
{
	int	p, i, l, lastp, si;
	char	huffsize[257];
	unsigned int huffcode[257];
	unsigned int code;

	p = 0;
	for (l = 1; l <= 16; l++) {
		for (i = 1; i <= (int)bits[l]; i++)
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
		dtbl->ehufco[val[p]] = huffcode[p];
		dtbl->ehufsi[val[p]] = huffsize[p];
	}
}

static	void	SetHuffTable2(const UINT8 *bits, const UINT8 *val,
							LPD_DERIVED_TBL dtbl)
{
	int p, i, l, si;
	int lookbits, ctr;
	char huffsize[257];
	unsigned int huffcode[257];
	unsigned int code;

	p = 0;
	for (l = 1; l <= 16; l++) {
		for (i = 1; i <= (int)bits[l]; i++)
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
		if (bits[l]) {
			dtbl->valptr[l] = p;
			dtbl->mincode[l] = huffcode[p];
			p += bits[l];
			dtbl->maxcode[l] = huffcode[p-1];
		} else {
			dtbl->maxcode[l] = -1;
		}
	}
	dtbl->maxcode[17] = 0xFFFFFL;

	memset(dtbl->look_nbits, 0, sizeof(dtbl->look_nbits));

	p = 0;
	for (l = 1; l <= HUFF_LOOKAHEAD; l++) {
		for (i = 1; i <= (int)bits[l]; i++, p++) {
			lookbits = huffcode[p] << (HUFF_LOOKAHEAD-l);
			for (ctr = 1 << (HUFF_LOOKAHEAD-l); ctr > 0; ctr--) {
				dtbl->look_nbits[lookbits] = l;
				dtbl->look_sym[lookbits] = val[p];
				lookbits++;
			}
		}
	}
}

int	CompressAudio(char *inData, char *outData, int len, int bufSize)
{
	int	i;
	short	c, data;
	short	sBuf[MAX_LENGTH];

	*((short *)outData) = len;
	outData += 2;

	chuff.bitbuf = 0U;
	chuff.putcount = 8;
	chuff.outcount = 2;
	chuff.putptr = outData;
	chuff.bufSize = bufSize;

	data = 0;
	for (i = 0; i < len; i++) {
		c = (signed char)(*inData++ - 0x80);
		sBuf[i] = c - data;
		data = c;
	}

	EncodeHuff(sBuf, len);
	FlushBits();

	return(chuff.outcount);
}

int	DecompressAudio(char *inData, char *outData)
{
	int	i, len;
	short	c, data;
	short	sBuf[MAX_LENGTH];

	len = *((short *)inData);
	inData += 2;

	dhuff.get_buffer = 0;
	dhuff.bits_left = 0;
	dhuff.next_input_byte = inData;

	memset(sBuf, 0, sizeof(sBuf));
	DecodeHuff(sBuf, len);

	data = 0;
	for (i = 0; i < len; i++) {
		c = data + sBuf[i];
		*outData++ = (char)(c + 0x80);
		data = c;
	}

	return(len);
}

static	void	EncodeHuff(short *block, int n)
{
	int	temp, temp2;
	int	nbits;
	int	k, r, i;

	r = 0;

	for (k = 0; k < n; k++) {
		temp = block[k];
		if ((temp = block[k]) == 0) {
			r++;
		} else {
			while (r > 15) {
				EmitBits(cdTable->ehufco[0xF0],
							cdTable->ehufsi[0xF0]);
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
			EmitBits(cdTable->ehufco[i], cdTable->ehufsi[i]);

			EmitBits((unsigned int)temp2, nbits);

			r = 0;
		}
	}

	if (r > 0)
		EmitBits(cdTable->ehufco[0], cdTable->ehufsi[0]);
}

static	void	FlushBits ()
{
	EmitBits(0x7F, 7);

	chuff.bitbuf = 0U;
	chuff.putcount = 8;
}

static	void	EmitBits(unsigned int x, int n)
{
	while (n >= chuff.putcount) {
		n -= chuff.putcount;
		chuff.bitbuf |= rightbits(chuff.putcount, x >> n);
		if (++chuff.outcount < chuff.bufSize)
			*chuff.putptr++ = chuff.bitbuf;
		chuff.bitbuf = 0U;
		chuff.putcount = 8;
	}
	chuff.putcount -= n;
	chuff.bitbuf |= rightbits(n, x) << chuff.putcount;
}

static	void	FillBitBuffer(int nbits)
{
	while (dhuff.bits_left < MIN_GET_BITS) {
		dhuff.get_buffer =
			(dhuff.get_buffer << 8) | *dhuff.next_input_byte++;
		dhuff.bits_left += 8;
	}
}

static	int	SlowDECODE(LPD_DERIVED_TBL htbl, int min_bits)
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

	return val_huff[htbl->valptr[l] +
			    ((int)(code - htbl->mincode[l]))];
}

static	void	DecodeHuff(short *block, int n)
{
	int	s, k, r;

	for (k = 0; k < n; k++) {
		huff_DECODE(s, ddTable, label2);

		r = s >> 4;
		s &= 15;

		if (s) {
			k += r;
			check_bit_buffer(s);
			r = get_bits(s);
			s = huff_EXTEND(r, s);
			block[k] = (short)s;
		} else {
			if (r != 15)
				break;
			k += 15;
		}
	}
}
