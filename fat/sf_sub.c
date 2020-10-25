/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Common subroutines                        *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#endif
#include <string.h>
#include <time.h>
#include <stdio.h>
#include <stdarg.h>
#include "sf_sub.h"

static INT   Debug_Level = 0; /* Debug Message Level Switch */

/*========== CPU��C�R���p�C���̎d�l�̈Ⴂ�ɂ��ăR�[�f�B���O���K�v�ȃ��[�`�� ==========*/

/* ���g���E�G���f�B�A���̃��[�h�l�̓Ǐo�� */
WORD BS_wPeek(PVOID pvMemAdr)
{

#if SF_ENDIAN_TYPE==SF_LITTLE_ENDIAN
	PWORD p = pvMemAdr;
	return *p;
#else
	PBYTE p = pvMemAdr;
	return ((WORD) * (p + 1) << 8) | *p;
#endif
}


/* ���g���E�G���f�B�A���̃_�u���E���[�h�l�̓Ǐo�� */
DWORD BS_dwPeek(PVOID pvMemAdr)
{

#if SF_ENDIAN_TYPE==SF_LITTLE_ENDIAN
	PDWORD p = pvMemAdr;
	return *p;
#else
	PBYTE p = pvMemAdr;
	register DWORD x;
	x = *(p + 3);
	x = (x << 8) | *(p + 2);
	x = (x << 8) | *(p + 1);
	x = (x << 8) | *p;
	return x;
#endif
}


/* ���g���E�G���f�B�A���̃��[�h�l�̏����� */
VOID BS_wPoke(PVOID pvMemAdr, WORD wData)
{

#if SF_ENDIAN_TYPE==SF_LITTLE_ENDIAN
	PWORD p = pvMemAdr;
	*p = wData;
#else
	PBYTE p = pvMemAdr;
	*p = (SF_BYTE)wData;
	*(p + 1) = (SF_BYTE)(wData >> 8);
#endif
}


/* ���g���E�G���f�B�A���̃_�u���E���[�h�l�̏����� */
VOID BS_dwPoke(PVOID pvMemAdr, DWORD dwData)
{

#if SF_ENDIAN_TYPE==SF_LITTLE_ENDIAN
	PDWORD p = pvMemAdr;
	*p = dwData;
#else
	PBYTE p = pvMemAdr;
	*p = (SF_BYTE)dwData;
	*(p + 1) = (SF_BYTE)(dwData >> 8);
	*(p + 2) = (SF_BYTE)(dwData >> 16);
	*(p + 3) = (SF_BYTE)(dwData >> 24);
#endif
}


/*================= CPU��C�R���p�C���̎d�l�̉e�����󂯂Ȃ����[�`�� =================*/

/* ������FAT�̃t�@�C�����Ƃ��ēK�������ׂ�(TRUE=OK) */
BOOL BS_isfnchr(SF_BYTE bChr)
{
	if (bChr >= '0' && bChr <= '9')
		return TRUE;
	if (bChr >= 'A' && bChr <= 'Z')
		return TRUE;
	if (bChr >= 'a' && bChr <= 'z')
		return TRUE;
	if (bChr == '.')
		return TRUE;
	if (bChr == '/')
		return TRUE;
	return FALSE;
}

BOOL BS_isfname(SF_BYTE *fname)
{
	SF_BYTE	*p;

	for (p = fname; *p != 0; p++) {
		if (! BS_isfnchr(*p))
			return FALSE;
	}

	return TRUE;
}

/* ���t�A�����̎擾 */
BOOL _CLK_GetDateTime( /* �߂�l : TRUE=OK,FALSE=���t�Ǝ����̎擾���s */
  PBYTE dt  /* �擾�������t�Ǝ����̊i�[�A�h���X */
             /* dt[0] : �N(0=1980�N�`119=2099�N) */
             /* dt[1] : ��(1�`12) */
             /* dt[2] : ��(1�`31) */
             /* dt[3] : ��(0�`23) */
             /* dt[4] : ��(0�`59) */
             /* dt[5] : �b(0�`59) */
             /* dt[6] : 1/100�b(0�`99) */
)
{
	time_t ltime;
	struct tm *tmp;

	time(&ltime);
	tmp = localtime(&ltime);

	dt[0] = tmp->tm_year - 80;
	dt[1] = tmp->tm_mon + 1;
	dt[2] = tmp->tm_mday;
	dt[3] = tmp->tm_hour;
	dt[4] = tmp->tm_min;
	dt[5] = tmp->tm_sec;
	dt[6] = 0;

    return TRUE; /* OK */
}

/* �f�B���N�g���E�G���g���Ŏg������t�Ǝ��Ԃ̓ǂݏo�� */
DWORD _CLK_ReadFileDT( /* ���t�Ǝ��Ԃ�\��32�r�b�g�̒l */
  VOID                /* �����Ȃ� */
)
{
    WORD d,t; SF_BYTE x[7];
    
    if( !_CLK_GetDateTime(x) ) return 0;
    d = x[0] * 512U + x[1] * 32U + x[2];
    t = x[3] * 2048U + x[4] * 32U + x[5] / 2U;

    return (DWORD)d << 16 | t;
}

DWORD _CLK_ConvertFileTime(WORD date, WORD time)
{
	struct tm tm;

	memset(&tm, 0, sizeof(tm));
	tm.tm_year = (date >> 9) + 80;
	tm.tm_mon = ((date >> 5) & 0xf) - 1;
	tm.tm_mday = (date & 0x1f);
	tm.tm_hour = (time >> 11);
	tm.tm_min = (time >> 5) & 0x3f;
	tm.tm_sec = (time & 0x1f) * 2;

	return mktime(&tm);
}

INT _SetDebugMsgLevel(INT dbglevel)
{
    if (dbglevel <= 0)
        Debug_Level = 0;
    else
        Debug_Level = dbglevel;

    return Debug_Level;
}

VOID _PrintDebugMessage(INT msglevel, const char *pfmt, ...)
{
    va_list   vlist;

    if ( msglevel <= Debug_Level ) {
        va_start(vlist,pfmt);
        vprintf(pfmt,vlist);
        va_end(vlist);
    }
}
