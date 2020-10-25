/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Common subroutines header                 *
**********************************************************/

#ifndef	__SF_SUB_H__
#define	__SF_SUB_H__

#include "sf_define.h"

#define	BS_HIBYTE(x)  ((SF_BYTE)((WORD)(x)	>> 8))
#define	BS_LOWBYTE(x) ((SF_BYTE)((WORD)(x)	& 0xFF))
#define	BS_HIWORD(x)  ((WORD)((DWORD)(x) >>	16))
#define	BS_LOWWORD(x) ((WORD)((DWORD)(x) & 0xFFFF))

extern WORD  BS_wPeek(PVOID pvMemAdr);					/* Read WORD Data of Little Endian   */
extern DWORD BS_dwPeek(PVOID pvMemAdr);					/* Read DWORD Data of Little Endian  */
extern VOID  BS_wPoke(PVOID pvMemAdr,WORD	wData);		/* Write WORD Data of Little Endian  */
extern VOID  BS_dwPoke(PVOID pvMemAdr,DWORD dwData);	/* Write DWORD Data of Little Endian */
extern BOOL  BS_isfnchr(SF_BYTE bChr);					/* Check a character whether it is valid as FAT File Name or not */
extern BOOL BS_isfname(SF_BYTE *fname);
extern BOOL _CLK_GetDateTime(PBYTE dt);	                  /* Get Date and Time */
extern DWORD _CLK_ReadFileDT(VOID );					  /* Read Date and Time in Directory Entry */
extern DWORD _CLK_ConvertFileTime(WORD date, WORD time);
extern INT _SetDebugMsgLevel(INT dbglevel);               /* Set Debug Message Level */
extern VOID _PrintDebugMessage(INT msglevel, const char *pfmt, ...); /* Output Debug Message */

#endif /* __SF_SUB_H__ */
