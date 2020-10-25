/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : FAT handling functions                    *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include "sf_fat.h"
#include "sf_diskio.h"
#include "sf_sub.h"

/*-------------------------------------------------------------------*/
/*                  Definition of Global Variables                   */
/*-------------------------------------------------------------------*/

SF_err_t SF_err;				/* Last Error information occured in FAT File System */
SF_BYTE SF_volLb_NO_NAME[11] = "NO NAME    ";	/* Default Volume Label */

/* Table for Disk Management */
static DFM_dskTbl_t DSKIO_dskTbl[MAX_DISK_NUM];

/*-------------------------------------------------------------------*/
/*              Definition of Local Variables and Macros             */
/*-------------------------------------------------------------------*/

#define setHi28Bit1(x)  (0xFFFFFFF0 | (x))  /* bit on at Upper 28bit */

#define RET_EOF		2
#define RET_OK		1
#define RET_ERR		0

/* Table for Disk Control Parameters */
static SF_fatDskPara_t DSKIO_fdp[MAX_DISK_NUM];

/* Table for File Control Parameters */
static SF_fcs_t DSKIO_fcs[DSKIO_FILES];

/* Area of Sector Buffer */
static SF_pSecBuf_t SF_pSecBuf;	/* pointer of the top of sector Buffer */

static INT32 SF_nextFileID;		/* Next File ID */
static INT32 SF_FileIDmax;		/* Maximum of File ID */

/* If any empty buffer does not exist in SBserEmpty(),
   this variable is the address of the last buffer.    */
static SF_pSecBuf_t SB_last;

/* the result of executing fncSer0() and fnxSer() */
static DWORD subDirLaxxxluNo;	/* the last cluster number of sub directory                    */
static DWORD fstDirEmpSno;		/* the sector number of the 1st empty directory entry          */
static DWORD fstDirEmpSbp;		/* the position in the sector of the 1st empty directory entry */

/* Path Exist Flag in DirSer() */
static SF_BYTE dirEntExist;		/* 0:Not Exist, 1:Exist */

/* the Last Cluster Number of the File set by AllocClu() */
static DWORD endCluNo;

/* Index of the FVAT Entry File Name Position */
static int fnIndex[VFAT_CHARS] = {1, 3, 5, 7, 9, 14, 16, 18, 20, 22, 24, 28, 30};

/*-------------------------------------------------------------------*/
/*                         Local Subroutines                         */
/*-------------------------------------------------------------------*/


/* Get Disk Number */
static DWORD GetDskNo(		/* return : DiskNo or (DWORD)-1(If Device Name is Not Found) */
	PSZ pszPathName,		/* the string of the path including the device name */
	PSZ *ppszPathName		/* the string of the path excluding the device name */
)
{
	int i;
	SF_BYTE devName[MAX_FILENAME + 1];

	if (*pszPathName == SF_dirDlm) {
        /* Get the Device Name of the Path */
		devName[0] = *pszPathName++;
		for (i = 1; *pszPathName != SF_dirDlm && *pszPathName != '\0' && i < MAX_FILENAME; i++)
			devName[i] = *pszPathName++;
		devName[i] = '\0';

        /* Find the device Name from the table for disk management */
		for (i = 0; i < MAX_DISK_NUM; i++) {
			if (strcmp(DSKIO_dskTbl[i].devName, devName) == 0) {
				if (ppszPathName != NULL)
					*ppszPathName = pszPathName;
				return i;
			}
		}
	}

    /* Device Name is Not Found */
	return (DWORD)-1;
}


/* Cut the elements From path */
static INT CutPath(		/* return : TRUE=OK, FALSE=ERROR */
	PSZ *ppszPath,		/* the string of the path */
	PSZ pszFileName		/* the string of the path elements */
)
{
	PBYTE cp = *ppszPath;

    /* If the length of the path string is zero, return as error */
	if (cp == NULL || *cp == '\0')
		return RET_ERR;

    /* skip the path delimiter */
	if (*cp == SF_dirDlm)
		cp++;

    /* If next character is a delimiter or null terminator, return as error */
	if (*cp == SF_dirDlm || *cp == '\0')
		return RET_ERR;

    /* cut the elements until finding the delimiter or null terminator */
	while (*cp != SF_dirDlm && *cp != '\0')
		*pszFileName++ = *cp++;

    /* terminate the element of path */
	*pszFileName = '\0';

    /* go to next position */
	*ppszPath = cp;

	return RET_OK;
}


/* Add the directory names to the absolute path */
static INT AddAbsPath(	/* return : TRUE=OK, FALSE=cannot add */
	PBYTE pbAbsPath,	/* the absolute path */
	PBYTE pszDirName	/* directory name */
)
{
	INT len;

	len = strlen(pbAbsPath);
	pbAbsPath += len;

	/* current directory (.) or parent directory (..) */
	if (pszDirName[0] == '.') {
		if (pszDirName[1] != '.')
			return RET_OK;

		if (len == 0)
			return RET_ERR;	/* the parent directory of the root directory */

		while (len > 0) {
			if (*pbAbsPath == SF_dirDlm)
				break;

			--pbAbsPath;
			--len;
		}

		*pbAbsPath = '\0';
		return RET_OK;
	}

	/* add the directory name */
	*pbAbsPath++ = SF_dirDlm;
	strcpy(pbAbsPath, pszDirName);

	return RET_OK;
}


/* get a mask value and a bit shift value from a length of the sector and cluster */
static void GetMskShf(	/* return : TRUE=OK, FALSE=ERROR */
	DWORD wBl,			/* byte length */
	PDWORD pwMask,		/* pointer of the bit mask value  */
	PDWORD pwShift		/* pointer of the bit shift value */
)
{
	DWORD shf;
	DWORD wTmp;

    /* caluculate a shift value */
	for (shf = 7, wTmp = wBl >> 8; wTmp != 0; shf++)
		wTmp >>= 1;

    /* set a mask value */
	if (pwMask)
		*pwMask = wBl - 1;

    /* set a shift value */
	if (pwShift)
		*pwShift = shf;
}


/* Clear the Error Information */
static VOID ClearErrorInfo(void)
{
    /* clear the error information to all zero */
	memset(&SF_err, 0, sizeof SF_err);
}


/* Set the Error Information */
static RESULT SetErrorInfo(		/* return : Result */
	RESULT errCd,				/* Error Code    */
	DWORD type,					/* Error Type    */
	DWORD dskNo,				/* Disk Number   */
	DWORD secNo					/* Sector Number */
)
{
    /* set the error information */
	SF_err.errCd = errCd;
	SF_err.type = SF_ET_GENE;
	SF_err.dskNo = (DWORD)-1;
	SF_err.secNo = 0;

	return errCd;
}


/* create a short file name from a long file name (8.3 format) */
int MakeShortName(PBYTE un, SF_BYTE dn[], int unlen, int gen)
{
	int i, j, l;
	int conv = 1;
	const SF_BYTE *cp, *dp, *dp1;
	SF_BYTE gentext[6], *wcp;
	SF_BYTE c;

#define U2D(c) (toupper(c))

	/*
	 * Fill the dos filename string with blanks. These are DOS's pad
	 * characters.
	 */
	memset(dn, ' ', 11);

	/*
	 * The filenames "." and ".." are handled specially, since they
	 * don't follow dos filename rules.
	 */
	if (un[0] == '.' && unlen == 1) {
		dn[0] = '.';
		return gen <= 1;
	}
	if (un[0] == '.' && un[1] == '.' && unlen == 2) {
		dn[0] = '.';
		dn[1] = '.';
		return gen <= 1;
	}

	/*
	 * Filenames with only blanks and dots are not allowed!
	 */
	for (cp = un, i = unlen; --i >= 0; cp++)
		if (*cp != ' ' && *cp != '.')
			break;
	if (i < 0)
		return 0;


	/*
	 * Filenames with some characters are not allowed!
	 */
	for (cp = un, i = unlen; --i >= 0; cp++)
		if (U2D(*cp) == 0)
			return 0;

	/*
	 * Now find the extension
	 * Note: dot as first char doesn't start extension
	 *	 and trailing dots and blanks are ignored
	 */
	dp = dp1 = 0;
	for (cp = un + 1, i = unlen - 1; --i >= 0;) {
		switch (*cp++) {
		case '.':
			if (!dp1)
				dp1 = cp;
			break;
		case ' ':
			break;
		default:
			if (dp1)
				dp = dp1;
			dp1 = 0;
			break;
		}
	}

	/*
	 * Now convert it
	 */
	if (dp) {
		if (dp1)
			l = dp1 - dp;
		else
			l = unlen - (dp - un);
		for (i = 0, j = 8; i < l && j < 11; i++, j++) {
			c = dp[i];
/*			c = lu_loaded && (c & 0x80) ? */
/*			    lu[c & 0x7f] : l2u[c]; */
			c = U2D(c);
			if (dp[i] != (dn[j] = c)
			    && conv != 3)
				conv = 2;
			if (dn[j] == 1) {
				conv = 3;
				dn[j] = '_';
			}
			if (dn[j] == 2) {
				conv = 3;
				dn[j--] = ' ';
			}
		}
		if (i < l)
			conv = 3;
		dp--;
	} else {
		for (dp = cp; *--dp == ' ' || *dp == '.';);
		dp++;
	}

	/*
	 * Now convert the rest of the name
	 */
	for (i = j = 0; un < dp && j < 8; i++, j++, un++) {
/*		c = lu_loaded && (*un & 0x80) ? */
/*		    lu[*un & 0x7f] : l2u[*un]; */
		c = *un;
		c = U2D(c);
		if (*un != (dn[j] = c)
		    && conv != 3)
			conv = 2;
		if (dn[j] == 1) {
			conv = 3;
			dn[j] = '_';
		}
		if (dn[j] == 2) {
			conv = 3;
			dn[j--] = ' ';
		}
	}
	if (un < dp)
		conv = 3;
	/*
	 * If we didn't have any chars in filename,
	 * generate a default
	 */
	if (!j)
		dn[0] = '_';

	/*
	 * The first character cannot be E5,
	 * because that means a deleted entry
	 */
	if (dn[0] == 0xe5)
		dn[0] = 0x05;

	/*
	 * If there wasn't any char dropped,
	 * there is no place for generation numbers
	 */
	if (conv != 3) {
		if (gen > 1)
			return 0;
		return conv;
	}

	/*
	 * Now insert the generation number into the filename part
	 */
	if (gen == 0)
		return conv;
	for (wcp = gentext + sizeof(gentext); wcp > gentext && gen; gen /= 10)
		*--wcp = gen % 10 + '0';
	if (gen)
		return 0;
	for (i = 8; dn[--i] == ' ';);
	i++;
	if (gentext + sizeof(gentext) - wcp + 1 > 8 - i)
		i = 8 - (gentext + sizeof(gentext) - wcp + 1);
	dn[i++] = '~';
	while (wcp < gentext + sizeof(gentext))
		dn[i++] = *wcp++;
	return 3;
}

/*-------------------------------------------------------------------*/
/*                      INPUT/OUTPUT ROUTINES                        */
/*-------------------------------------------------------------------*/

/* non buffering read/write by sector */
#define DSKSEC_RD    (FALSE)
#define DSKSEC_WR    (TRUE)
static INT DskSecRW(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* Disk number */
	DWORD secNo,		/* R/W Start Sector Number(0～) */
	DWORD rwArea,		/* R/W Area(SF_BF_XXXX value) */
	PVOID buf,			/* buffer address */
	DWORD len,			/* the num of sector */
	BOOL rw				/* R/W mode(DSKSEC_RD or DSKSEC_WR) */
)
{
	SF_pFatDskPara_t pFdp;
	DSKIO_para_t dskIOpara;

	pFdp = DSKIO_dskTbl[dskNo].pDskPara;

    /* if length is 0, return immediately */
	if (len == 0)
		return RET_OK;

    /* set the parameters to be used by DISK I/O module */
	dskIOpara.devInfo = DSKIO_dskTbl[dskNo].devInfo;
	dskIOpara.validSector = 0;
	dskIOpara.dwSecLen = len;
	dskIOpara.pTransAdr = buf;
	dskIOpara.dwStaSecNo = secNo;

    /* update all sub directories in the cluster */
	if ((rwArea & SF_ET_MSK_AR) == SF_BF_DIR)
		dskIOpara.validSector = SECTOR_PER_CLUSTER;

    /* reflect a request of cluster fill */
	if (rwArea & SF_BF_FILL)
		dskIOpara.validSector = SECTOR_PER_CLUSTER;

    /* read disk or write disk */
	if ((rw == DSKSEC_RD) ? _DSKIO_read(&dskIOpara) : _DSKIO_write(&dskIOpara))
		return RET_OK;

    /* set the error information */
	SF_err.errCd = dskIOpara.errCd;
	SF_err.type = (rwArea & SF_ET_MSK_AR) | (rw == DSKSEC_RD ? SF_ET_DSK_RD : SF_ET_DSK_WR);
	SF_err.dskNo = dskNo;
	SF_err.secNo = dskIOpara.dwStaSecNo + dskIOpara.dwSecLen;
	return RET_ERR;
}


/* flush the sector buffer */
static INT SBbufFlush(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* sector number */
	DWORD rwArea		/* R/W Area(SF_BF_XXXX) */
)
{
	SF_pFatDskPara_t pFdp;
	DWORD startSecNo, endSecNo;
	SF_pSecBuf_t psb;

	if ((rwArea & SF_BF_MSK_AR) == SF_BF_DATA) {
        /* get the start and the end sector number in the cluster */
		pFdp = DSKIO_dskTbl[dskNo].pDskPara;
		startSecNo = pFdp->sn.data + ((secNo - pFdp->sn.data) & ~(pFdp->bpb.sectorsPerCluster - 1));
		endSecNo = startSecNo + pFdp->bpb.sectorsPerCluster - 1;

        /* sector buffer list loop */
		psb = SF_pSecBuf;
		while (psb != NULL) {
            /* find a unused buffer */
			if (psb->dskNo == dskNo && psb->secNo != secNo && psb->secNo >= startSecNo && psb->secNo <= endSecNo && (psb->flag & SF_BF_WR)) {
                /* write to the disk */
				if (DskSecRW(dskNo, psb->secNo, psb->flag, psb->buf, 1, DSKSEC_WR) == RET_ERR)
					return RET_ERR;

                /* off a unused flag */
				psb->flag &= ~SF_BF_WR;
			}

            /* to the next buffer */
			psb = psb->pNextSecBuf;
		}
	}

	return RET_OK;
}


/* Find an empty buffer from the list of the sector buffers */
static SF_pSecBuf_t SBserEmpty(		/* return : sector buffer address. if all buffer is used , NULL */
	VOID							/* no arguments */
)
{
	SF_pSecBuf_t psb;
	psb = SF_pSecBuf;

    /* loop the list of the sector buffers */
	while (psb != NULL) {
        /* if empty, return it */
		if (psb->dskNo == SF_BF_EMPTY)
			break;

        /* save the position of the last buffer */
		SB_last = psb;

        /* to the next buffer */
		psb = psb->pNextSecBuf;
	}

	return psb;
}


/* Set sector buffers to empty */
static VOID SBsetEmpty2(	/* return : nothing */
	DWORD dskNo,			/* disk number */
	DWORD secNo,			/* sector number */
	DWORD n					/* the number of sectors */
)
{
	DWORD endSecNo;
	SF_pSecBuf_t psb;

    /* if n is 0, return immediately */
	if (n == 0)
		return;

    /* get the last sector number */
	endSecNo = secNo + (n - 1);

    /* loop the list of the sector buffers */
	psb = SF_pSecBuf;
	while (psb != NULL) {
        /* if in the range, set empty */
		if (psb->dskNo == dskNo && psb->secNo >= secNo && psb->secNo <= endSecNo) {
			psb->dskNo = SF_BF_EMPTY;
		}

        /* to the next sector buffer */
		psb = psb->pNextSecBuf;
	}
}


/* copy write ready buffer of the disk to the disk */
static INT SBbufWr(		/* return : RET_OK=OK, RET_ERR=error */
	DWORD dskNo			/* disk number */
){
	SF_pSecBuf_t psb;

    /* loop the list of the sector buffer */
	psb = SF_pSecBuf;
	while (psb != NULL) {
        /* find a unused buffer */
		if (psb->dskNo == dskNo && (psb->flag & SF_BF_WR)) {
            /* flush all sector buffers in the cluster */
			if (SBbufFlush(dskNo, psb->secNo, psb->flag) == RET_ERR)
				return RET_ERR;

            /* write to the disk */
			if (DskSecRW(dskNo, psb->secNo, psb->flag, psb->buf, 1, DSKSEC_WR) == RET_ERR)
				return RET_ERR;

            /* off the unused flag */
			psb->flag &= ~SF_BF_WR;
		}

        /* to the next buffer */
		psb = psb->pNextSecBuf;
	}

	return RET_OK;
}


/* copy write ready buffer of the sector buffer to the disk */
static INT SBbufWr2(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* sector number */
	DWORD n				/* the number of the sectors */
){
	SF_pSecBuf_t psb;
	DWORD endSecNo;

    /* if n is 0, return immediately */
	if (n == 0)
		return RET_OK;

    /* get the last sector number */
	endSecNo = secNo + (n - 1);

    /* loop the list of the sector buffer */
	psb = SF_pSecBuf;
	while (psb != NULL) {
        /* find a unused buffer */
		if (psb->dskNo == dskNo && (psb->flag & SF_BF_WR)) {
			if (psb->secNo >= secNo && psb->secNo <= endSecNo ) {
                /* flush all sector buffers in the cluster */
				if (SBbufFlush(dskNo, psb->secNo, psb->flag) == RET_ERR)
					return RET_ERR;

				if (DskSecRW(dskNo, psb->secNo, psb->flag, psb->buf, 1, DSKSEC_WR) == RET_ERR)
					return RET_ERR;

                /* off the unused flag */
				psb->flag &= ~SF_BF_WR;
			}
		}

        /* to the next buffer */
		psb = psb->pNextSecBuf;
	}

	return RET_OK;
}


/* search the buffer address from the disk number and the sector number */
static SF_pSecBuf_t SBsearch(	/* return : address of the sector buffer,if cannot find, NULL */
	DWORD dskNo,				/* disk number   */
	DWORD secNo					/* sector number */
)
{
	SF_pSecBuf_t psb;

    /* loop the list of the sector buffer */
	psb = SF_pSecBuf;
	while (psb != NULL) {
        /* if disk number and sector number is same, break */
		if (psb->dskNo == dskNo && psb->secNo == secNo)
			break;

        /* to the next buffer */
		psb = psb->pNextSecBuf;
	}

	return psb;
}


/* move the sector buffer to the top */
static INT SBmovTop(	/* return : TRUE=OK,FALSE=Error */
	SF_pSecBuf_t psb	/* sector buffer address */
)
{
	SF_pSecBuf_t prevsb, nextsb;

	nextsb = SF_pSecBuf;

    /* if the sector buffer is on the top, return immediately return. */
	if (nextsb == psb)
		return RET_OK;

	do {
		prevsb = nextsb;
		nextsb = prevsb->pNextSecBuf;

        /* if cannot find the sector buffer, return error. */
		if (nextsb == NULL)
			return RET_ERR;
	} while (nextsb != psb);

    /* rewrite a pointer to move to the top */
	prevsb->pNextSecBuf = psb->pNextSecBuf;
	psb->pNextSecBuf = SF_pSecBuf;
	SF_pSecBuf = psb;

	return RET_OK;		/* finish moving */
}


/* buffered 1 sector read from disk (less than 1 sector disk read) */
static INT DskRd1s(		/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* start sector number(0～) */
	DWORD offset,		/* offset in the start sector */
	DWORD rdArea,		/* read area(SF_BF_XXXX)      */
	PVOID buf,			/* buffer address */
	DWORD len,			/* the length of read(less than 1 sector size) */
	BOOL readFlag		/* sector read flag */
)
{
	SF_pSecBuf_t psb;

    /* search the sector buffer in the disk */
	if ((psb = SBsearch(dskNo, secNo)) == NULL) {
        /* search an empty sector buffer */
		if ((psb = SBserEmpty()) == NULL) {
            /* if cannot find an empty sector buffer, set empty and use the last sector buffer */
			psb = SB_last;

            /* check whether wait writing or not */
			if (psb->flag & SF_BF_WR) {
                /* flush all the sector buffer in the cluster */
				if (SBbufFlush(psb->dskNo, psb->secNo, psb->flag) == RET_ERR)
					return RET_ERR;

                /* copy wait writing buffer to the disk */
				if (DskSecRW(psb->dskNo, psb->secNo, psb->flag, psb->buf, 1, DSKSEC_WR) == RET_ERR)
					return RET_ERR;
			}

			psb->dskNo = SF_BF_EMPTY;
		}

        /* read 1 sector from the disk to the empty buffer */
		if (readFlag) {
			if (DskSecRW(dskNo, secNo, rdArea, psb->buf, 1, DSKSEC_RD) == RET_ERR)
				return RET_ERR;
		} else {
			/* clear secter buffer */
			memset(psb->buf, 0, SF_MAXSECBYTE);
		}

        /* set the sector buffer information */
		psb->dskNo = dskNo;
		psb->flag = (rdArea & SF_BF_MSK_AR) | SF_BF_RD;
		psb->secNo = secNo;
	}

    /* copy the sector buffer to the user buffer */
	if (buf != NULL && len != 0)
		memcpy(buf, psb->buf + offset, len);

    /* move the sector buffer to the top of the list */
	if (psb != SF_pSecBuf)
		SBmovTop(psb);

	return RET_OK;
}


/* read disk by 1 sector */
static INT DskRdSec(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* R/W start number(0-) */
	DWORD rwArea,		/* R/W area(SF_BF_XXXX) */
	PVOID buf,			/* buffer address */
	DWORD len			/* the number of sector */
)
{
    /* flush the unwritten buffer in the sector */
	if (SBbufWr2(dskNo, secNo, len) == RET_ERR)
		return RET_OK;

    /* read from the disk */
	return DskSecRW(dskNo, secNo, rwArea, buf, len, DSKSEC_RD);
}


/* buffered 1 sector write to the disk (less than 1 sector disk write) */
static INT DskWr1s(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* start sector number(0-) */
	DWORD offset,		/* offset in the sector */
	DWORD wrArea,		/* write area(SF_BF_XXXX) */
	PVOID buf,			/* buffer address */
	DWORD len,			/* write length(less than 1 sector size) */
	BOOL readFlag		/* sector read flag */
)
{
	SF_pSecBuf_t psb;
	SF_pFatDskPara_t pFdp;

    /* read 1 sector from the disk */
	if (DskRd1s(dskNo, secNo, 0, wrArea, NULL, 0, readFlag) == RET_ERR)
		return RET_ERR;

	psb = SF_pSecBuf;

    /* set a read flag */
    psb->flag = wrArea | SF_BF_RD;

    /* write data to the buffer */
	if (buf != NULL && len != 0)
		memcpy(psb->buf + offset, buf, len);

	pFdp = DSKIO_dskTbl[dskNo].pDskPara;
	if (offset + len >= pFdp->bpb.bytesPerSector) {
        /* write to the disk */
		return DskSecRW(dskNo, secNo, wrArea, psb->buf, 1, DSKSEC_WR);
	} else {
        /* set writeing wait flag */
		psb->flag |= SF_BF_WR;

		return RET_OK;
	}
}


/* セクタ単位のディスク・ライト */
/* write to the disk by sector */
static INT DskWrSec(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD secNo,		/* R/W start sector number(0-) */
	DWORD rwArea,		/* R/W area (SF_BF_XXXX) */
	PVOID buf,			/* buffer address */
	DWORD len			/* write length */
)
{
    /* release the buffer in the sector */
	SBsetEmpty2(dskNo, secNo, len);

    /* flush all the sector buffer in the cluster */
	if (SBbufFlush(dskNo, secNo, rwArea) == RET_ERR)
		return RET_ERR;

    /* write to the disk */
	return DskSecRW(dskNo, secNo, rwArea, buf, len, DSKSEC_WR);
}


/* flush buffer (disk number) */
static RESULT _SF_Sync3(	/* return : 0=OK,more than 1=Error Code */
	DWORD dskNo				/* in:disk number */
)
{
    /* flush the sector buffer */
	if (SBbufWr(dskNo) == RET_ERR)
		return SF_EC_WriteFault;

    /* flush the disk I/O buffer */
	if(!_DSKIO_FlashDiskBuf(DSKIO_dskTbl[dskNo].devInfo))
		return SF_EC_WriteFault;

	return 0;
}

/*-------------------------------------------------------------------*/
/*            The Rountines of controling FAT,directory,file         */
/*-------------------------------------------------------------------*/

/* read a BPB of a new disk, and reset the disk control parameter. */
/* (1) do the sequence for an inserted disk first.
       You must copy the disk contorol parameter.                  */
static INT ResetFdp(			/* return : RET_OK=OK, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,		/* pointer of the disk control parameter */
	DWORD flag					/* bit0 : 1=do not call error handler */
								/* bit1 : ignore any value */
								/* bit2 : 1=when matching disk,
                                            omit volume serial number and volume label */
)
{
	DWORD sn, ns, fss, dss;
	DSKIO_para_t dskIOpara;

    /* set a BPB to the disk control parameter */
	dskIOpara.devInfo = DSKIO_dskTbl[pFdp->dskNo].devInfo;
	dskIOpara.validSector = 0;
	dskIOpara.pTransAdr = &pFdp->bpb;
	if (!_DSKIO_setBPB(&dskIOpara)) {	/* fail to set BPB */
        /* set an error information */
        SF_err.errCd = dskIOpara.errCd;
		SF_err.type = SF_ET_SYS | SF_ET_BPB_RD;
		SF_err.dskNo = pFdp->dskNo;
		SF_err.secNo = 0;
		return RET_ERR;
	}

    /* get the start sector number and the end sector number of BOOT,FAT,DIR,DATA from BPB */
	fss = (DWORD)pFdp->bpb.numberOfFATs * pFdp->bpb.sectorsPerFAT;
	dss = 32 * pFdp->bpb.rootEntries / pFdp->bpb.bytesPerSector;
	pFdp->sn.boot = (sn = 0);
	pFdp->sn.fat1 = (sn += pFdp->bpb.reservedSectors);
	pFdp->sn.dir = (sn += fss);
	pFdp->sn.data = (sn += dss);
	ns = pFdp->bpb.totalSectors;
	ns -= pFdp->bpb.reservedSectors + fss + dss;
	pFdp->eoc = ns / pFdp->bpb.sectorsPerCluster;
	pFdp->sn.eov = sn + pFdp->eoc * pFdp->bpb.sectorsPerCluster;
	pFdp->eoc += 2;
	pFdp->openFileCnt = 0;	/* no open file */
	pFdp->defscn = 2;		/* set the position of searching an empty cluster to the top cluster */
	pFdp->freeClu = (DWORD)-1;	/* set the number of empty clusters to uninitialised */

	return RET_OK;
}

/* search an empty file control structure */
static SF_pFcs_t GetEmpFcs(	/* return : an empty file control structure address */
	VOID					/* no arguments */
)
{
	INT i;
	SF_pFcs_t pFcs;
	pFcs = DSKIO_fcs;

    /* search an empty file control structure */
	for (i = 0; i < DSKIO_FILES; ++i, ++pFcs) {
        /* if empty, return it */
		if (pFcs->dskNo == SF_FF_EMPTY)
			return pFcs;
	}

    /* Any file control structure does not exist */
	return NULL;
}


/* search a file control structure which has the file ID */
static SF_pFcs_t FcsSerID(	/* return : file control structure address */
	INT32 fileID,			/* file ID number */
	SF_pFcs_t pFcs			/* the start file control structure address */
)
{
	INT i;

    /* If start address is not set, search from the top. */
	if (pFcs == NULL)
		pFcs = DSKIO_fcs;

    /* search a file control structure */
	for (i = 0; i < DSKIO_FILES; ++i, ++pFcs) {
        /* skip empty file control structure */
		if (pFcs->dskNo == SF_FF_EMPTY)
			continue;

        /* If finding it, return it */
		if (pFcs->fileID == fileID)
			return pFcs;
	}

    /* if not finding it, return NULL */
	return NULL;
}


/* search a file control structure of same path */
static SF_pFcs_t FcsSerPath(	/* return : a file control structure address */
	SF_pFcs_t pFcs0,			/* a file control structure address */
	SF_pFcs_t pFcs1				/* a start file control structure address */
)
{
	INT i;

    /* if not set a start file control structure address, search from the top */
	if (!pFcs1)
		pFcs1 = DSKIO_fcs;

    /* search a file control structure */
	for (i = 0; i < DSKIO_FILES; ++i, ++pFcs1) {
        /* if empty, go to the next */
		if (pFcs1->dskNo == SF_FF_EMPTY)
			continue;

        /* if different about the top cluster number, go to the next */
		if (pFcs0->dirFxxxluNo != pFcs1->dirFxxxluNo)
			continue;

        /* if different about the directory entry sector number, go to the next */
		if (pFcs0->dirSecNo != pFcs1->dirSecNo)
			continue;

        /* if different about the offset in the sector, go to the next */
		if (pFcs0->dirSbpos != pFcs1->dirSbpos)
			continue;

        /* find the file control structure */
		return pFcs1;
	}

    /* fail to find the file control structure */
	return NULL;
}


/* get a new file ID */
static INT32 GetNewfID(		/* return : new file ID */
	VOID					/* no arguments */
)
{
	INT i;

	for (;;) {
        /* increment the file ID */
		if (++SF_nextFileID > SF_FileIDmax)
			SF_nextFileID = 1;

        /* check whether it is already used. */
		for (i = 0; i < DSKIO_FILES; i++) {
			if (DSKIO_fcs[i].fileID == SF_nextFileID)
				goto skip1;
		}
		break;
skip1:
		;
	}

	return SF_nextFileID;
}


/* convert a cluster number to a sector number */
static DWORD CvClu2Sec(			/* return : 0=Error,more than 1=sector number */
	SF_pFatDskPara_t pFdp,		/* pointer of the disk control parameter */
	DWORD cluNo					/* cluster number */
)
{
    /* if the cluster number is invalid, return error */
	if (cluNo < 2 || cluNo >= pFdp->eoc)
		return 0;

    /* caluculate the sector number from the cluster number */
	return pFdp->sn.data + (cluNo - 2) * pFdp->bpb.sectorsPerCluster;
}


/* read FAT entry value (next cluster number) in the cluster */
static INT FatRd(				/* return : RET_OK=OK, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,		/* a pointer of the disk control structure */
	DWORD cluNo,				/* the cluster number */
	PDWORD pFatEnt				/* the FAT entry buffer address */
)
{
	DWORD fbp;
	DWORD msk, shf, rsn, sbp;
	DWORD i;
	SF_BYTE buf[2];

    /* if the FAT entry buffer address is NULL, return error. */
	if (pFatEnt == NULL)
		goto paraErr;

    /* if the cluster number is invalid, return error. */
	if (cluNo < 2 || cluNo >= pFdp->eoc)
		goto paraErr;

    /* get mask value and shift value of the sector size */
	GetMskShf(pFdp->bpb.bytesPerSector, &msk, &shf);

    /* set cluster number * 2 to the position of FAT */
	fbp = cluNo * 2;

    /* set the sector number and the offset in the sector */
	rsn = (DWORD)(pFdp->sn.fat1 + (fbp >> shf));
	sbp = (DWORD)fbp & msk;

    /* loop by the number of FAT (2) */
	for (i = 0; i < pFdp->bpb.numberOfFATs; ++i) {
        /* read FAT from disk */
		if (DskRd1s(pFdp->dskNo, rsn, sbp, SF_BF_FAT, buf, 2, TRUE) == RET_OK)
			goto FatRd_1;	/* OK */

        /* read next FAT area */
		rsn += pFdp->bpb.sectorsPerFAT;
	}
	return RET_ERR;

FatRd_1:
    /* get FAT values by little endian */
	*pFatEnt = BS_wPeek(buf);
	*pFatEnt &= 0xFFFF;
	if (*pFatEnt >= 0xFFF7)
		*pFatEnt = setHi28Bit1(*pFatEnt);

	return RET_OK;

paraErr:	/* parameter error is set to the "Not DOS Disk" Error. */
	/* set an error information */
	SetErrorInfo(SF_EC_NotDOSdisk, SF_ET_FAT | SF_ET_BADFAT, pFdp->dskNo, 0);
	return RET_ERR;		/* error parameter */
}


/* write a FAT entry value(next cluster number) of the cluster */
static INT FatWr(				/* return : RET_OK=OK, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,		/* a pointer of FAT disk parameter */
	DWORD cluNo,				/* cluster number */
	DWORD fatEnt				/* FAT entry value */
)
{
	DWORD fbp;
	DWORD msk, shf, rsn, sbp;
	DWORD i, j;
	SF_BYTE buf[2];

    /* if cluster number is invalid, go to the error handler rountine. */
	if (cluNo < 2 || cluNo >= pFdp->eoc)
		goto paraErr;

    /* get a mask value and a shift value of sector size */
	GetMskShf(pFdp->bpb.bytesPerSector, &msk, &shf);

	/* クラスタ番号*2がFATバイト位置 */
    /* set FAT offset to cluster number * 2 */
	fbp = cluNo * 2;

    /* the position of the sector */
	sbp = fbp & msk;

    /* write FAT value by little endian */
	BS_wPoke(buf, (WORD)fatEnt);

    /* sector number to write */
	rsn = pFdp->sn.fat1 + (fbp >> shf);

    /* write FAT area of the disk */
	for (j = i = 0; i < pFdp->bpb.numberOfFATs; ++i) {
		if (DskWr1s(pFdp->dskNo, rsn, sbp, SF_BF_FAT, buf, 2, TRUE) == RET_ERR)
			++j;

        /* write to the next FAT area */
		rsn += pFdp->bpb.sectorsPerFAT;
	}

    /* if fail to write all FAT, return Error */
	if (j == pFdp->bpb.numberOfFATs)
		return RET_ERR;

	return RET_OK;

paraErr:	/* parameter error is "not DOS DISK Error". */
    /* set error information */
	SetErrorInfo(SF_EC_NotDOSdisk, SF_ET_FAT | SF_ET_BADFAT, pFdp->dskNo, 0);
	return RET_ERR;			/* parameter error */
}


/* check whether disk is ready or not */
static INT DskRdyChk(	/* return : RET_OK=OK, RET_ERR=Error */
	DWORD dskNo,		/* disk number */
	DWORD flag,			/* bit0 : 1=do not call error handler */
						/* bit1 : 1=check remove and insert a same disk */
						/* bit2 : 1=when matching disk, omit s volume serial number and volume label */
	SF_pFatDskPara_t *ppFdp /* pointer of the parameter structure address */
)
{
	SF_pFatDskPara_t pFdp;

    /* if disk number is not in  the range, go to error handler */
	if (dskNo > MAX_DISK_NUM)
		goto invDsk;

    /* if a disk control structure is invalid, go to the error handler. */
	if (!DSKIO_dskTbl[dskNo].valid)
		goto invDsk;

    /* return disk control parameter */
	pFdp = DSKIO_dskTbl[dskNo].pDskPara;
	if (ppFdp)
		*ppFdp = pFdp;

	return RET_OK;

invDsk:
	SetErrorInfo(SF_EC_InvalidDrive, SF_ET_GENE, dskNo, 0);
	return RET_ERR;
}


/* the definition of the stuture used in CvFrwp() */
struct CVFRWP_S {
	DWORD ccn;		/* the cluster number which is assigned by file R/W pointer */
	DWORD csnt;		/* the top sector number of ccn cluster */
	DWORD csnr;		/* the offset sector number from csn  */
	DWORD csbp;		/* the offset position of csnr */
};

/* get the cluster number , the top sector number of the cluster ,
   and the offset position of the sector(root directory) from the file R/W pointer */
static INT CvFrwp1(				/* return : RET_OK=OK, RET_EOF=EOF */
	SF_pFatDskPara_t pFdp,		/* a pointer of the disk control structure */
	DWORD dwRwPtr,				/* R/W pointer */
	struct CVFRWP_S *pRes		/* conversion result */
)
{
	DWORD mskS, shfS, mskC, shfC;

    /* get the cluster R/W pointer assigns fro FAT entry of directory entry */
	GetMskShf(pFdp->bpb.bytesPerSector, &mskS, &shfS);
	GetMskShf(pFdp->bpb.bytesPerSector * pFdp->bpb.sectorsPerCluster, &mskC, &shfC);
	pRes->csbp = (DWORD)(dwRwPtr & mskS);
	pRes->csnr = (DWORD)((dwRwPtr & mskC) >> shfS);
	pRes->ccn = 0;
	pRes->csnt = pFdp->sn.dir;

    /* If R/W pointer is less than the end entry of the root directory, return OK */
	if (dwRwPtr < (DWORD)pFdp->bpb.rootEntries * SF_DIRENT_SIZ)
		return RET_OK;
	else
		return RET_EOF;
}

/* get the cluster number , the top sector number of the cluster ,
   and the offset position of the sector(sub directory) from the file R/W pointer */
static INT CvFrwp2(				/* return : RET_OK=OK, RET_ERR=Error, RET_EOF=EOF */
	SF_pFatDskPara_t pFdp,		/* a pointer of disk parameter */
	DWORD dwRwPtr,				/* R/W pointer */
	DWORD dwFatEnt,				/* FAT entry */
	struct CVFRWP_S *pRes		/* coversion result */
)
{
	DWORD i;
	DWORD mskS, shfS, mskC, shfC;

    /* get the cluster R/W pointer assigns from FAT entry of directory entry */
	GetMskShf(pFdp->bpb.bytesPerSector, &mskS, &shfS);
	GetMskShf(pFdp->bpb.bytesPerSector * pFdp->bpb.sectorsPerCluster, &mskC, &shfC);
	pRes->csbp = (DWORD)(dwRwPtr & mskS);
	pRes->csnr = (DWORD)((dwRwPtr & mskC) >> shfS);
	dwRwPtr >>= shfC;
	pRes->ccn = dwFatEnt;

    /* search in FAT */
	for (i = 0; i < dwRwPtr; ++i) {
		if (FatRd(pFdp, pRes->ccn, &pRes->ccn) == RET_ERR)
			return RET_ERR;

		if (pRes->ccn >= setHi28Bit1(7)) {
			pRes->csnt = (DWORD)-1;
			return RET_EOF;		/* EOF */
		}
	}
	pRes->csnt = CvClu2Sec(pFdp, pRes->ccn);
	return RET_OK;
}


/* get the cluster number , the top sector number of the cluster ,
   offset sector number, and the offset position of the sector
   from the file R/W pointer */
static INT CvFrwp(				/* return : RET_OK=OK, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,		/* a pointer of disk control parameter */
	SF_pFcs_t pFcs,				/* a pointer of file control parameter */
	struct CVFRWP_S *pRes		/* conversion result */
)
{
    /* if not set a pointer of file control parameter, get result from a previous result */
	if (!pFcs) {
        /* if not set a file control parameter, get the next cluster number of pRes->ccn */
		pRes->csbp = pRes->csnr = 0;
		if (FatRd(pFdp, pRes->ccn, &pRes->ccn) == RET_ERR)
			return RET_ERR;

		if (pRes->ccn >= setHi28Bit1(7)) {
			pRes->csnt = (DWORD)-1;
			return RET_EOF;
		}

		pRes->csnt = CvClu2Sec(pFdp, pRes->ccn);
		return RET_OK;
	}

    /* get a cluster number R/W pointer assigns from FAT entry of directory entry */
	if (pFcs->de.fatent == 0) {
        /* root directory */
		return CvFrwp1(pFdp, pFcs->rwPtr, pRes);
	} else {
        /* sub directory */
		return CvFrwp2(pFdp, pFcs->rwPtr, pFcs->de.fatent, pRes);
	}
}


/* search the continuous empty clusters */
static INT SerEmpClu(		/* return : RET_OK=OK, RET_ERR=Error, RET_EOF=empty cluster is nothing */
	DWORD startClu,			/* start cluster number(if invalid, default number) */
	DWORD reqBytes,			/* request size */
	PDWORD pwStrLen,		/* pwStrLen[0] : byte length of the empty clusters */
							/* pwStrLen[1] : sector length of the empty clusters */
							/* pwStrLen[2] : cluster length of the empty clusters */
	PDWORD pdwCluNo,		/* start number of empty cluster */
	SF_pFatDskPara_t pFdp	/* a pointer of the empty disk parameter */
)
{
	DWORD nc, msk, shf;
	DWORD i, nextClu;

	pwStrLen[0] = pwStrLen[1] = pwStrLen[2] = *pdwCluNo = 0;

    /* if start cluster number is invalid, start cluster is default position. */
	if (startClu < 2 || startClu >= pFdp->eoc)
		startClu = pFdp->defscn;

    /* if request size is 0, return OK immediately. */
	if (!reqBytes)
		return RET_OK;

    /* get mask value and shift value of cluster size */
	GetMskShf(pFdp->bpb.bytesPerSector * pFdp->bpb.sectorsPerCluster, &msk, &shf);
	nc = (reqBytes + msk) >> shf;

	/* 空きクラスタが無ければEOF */
    /* if any empty clusters are nothing, return EOF */
	if (pFdp->freeClu == 0)
		return RET_EOF;		/* any empty clusters are nothing */

    /* search the 1st empty cluster */
	for (i = startClu;;) {
        /* read FAT */
		if (FatRd(pFdp, i, &nextClu) == RET_ERR)
			return RET_ERR;

        /* if 0, it is an empty cluster. */
		if (nextClu == 0)
			break;			/* find an empty cluster */

        /* back to the top cluster when reaching the last cluster. */
		if (++i >= pFdp->eoc)
			i = 2;

        /* if all clusters are not empty, any empty clusters are nothing. */
		if (i == startClu)
			return RET_EOF;		/* nothing */
	}

    /* set the position of the empty cluster */
	*pdwCluNo = i;

    /* get a number of continuous empty clusters from this cluster */
	for (;;) {
		++pwStrLen[2];
		if (!--nc)
			break;

		if (++i >= pFdp->eoc)
			break;

		if (FatRd(pFdp, i, &nextClu) == RET_ERR)
			return RET_ERR;

		if (nextClu)
			break;		/* find an occupied cluster */
	}

    /* set the sector length */
	pwStrLen[1] = pwStrLen[2] * pFdp->bpb.sectorsPerCluster;

    /* set the byte length */
	i = (DWORD)pwStrLen[1] * pFdp->bpb.bytesPerSector;
	if (i > reqBytes)
		i = reqBytes;
	pwStrLen[0] = (DWORD)i;

	return RET_OK;
}


/* Allocate clusters */
/* Before executing this function,
   you must use the information of the empty cluster by serEmpClu() call. */
static INT AllocClu(			/* return : RET_OK=OK, RET_ERR=Error */
	PDWORD pdwEndCluNo,			/* pointer of the last cluster number to add the allocated clusters */
	DWORD startClu,				/* start cluster number */
	DWORD cluN,					/* the number of the allocating cluster */
	SF_pFatDskPara_t pFdp		/* pointer of the disk control parameter */

)
{
	INT rv;
	DWORD i;

    /* if the number of allocating cluster is 0, return OK immediately */
	if (cluN == 0)
		return RET_OK;

    /* link the start cluster number to the end cluster */
	if (pdwEndCluNo) {
		if (FatWr(pFdp, *pdwEndCluNo, startClu) == RET_ERR)
			return RET_ERR;
	}

    /* write allocated clusters to FAT */
	for (i = 0; i < cluN; ++i) {
        /* set a new cluster number to the previus position of the end cluster */
		if (pdwEndCluNo)
			*pdwEndCluNo = startClu;

		endCluNo = startClu;

        /* write to FAT */
		if (i < cluN - 1) {
            /* continue to write */
			rv = FatWr(pFdp, startClu, startClu + 1);
		} else {
            /* last cluster */
			rv = FatWr(pFdp, startClu, setHi28Bit1(0xF));
		}

        /* error check */
		if (rv == RET_ERR)
			return rv;

        /* decrease the number of empty clusters */
		if (pFdp->freeClu != (DWORD)-1)
			--pFdp->freeClu;

        /* the next continuous cluster */
		++startClu;
	}

    /* save the next default search position */
	if (startClu >= pFdp->eoc)
		startClu = 2;
	pFdp->defscn = startClu;

	return RET_OK;
}


/* Release Clusters */
static INT ReleaseClu(			/* return : RET_OK=OK, RET_ERR=Error */
								/*       2=bad cluster,3=invalid cluster number */
	DWORD cluNo,				/* the top of the release cluster number */
	SF_pFatDskPara_t pFdp,		/* pointer of disk control parameter */
	BOOL wrFlag					/* FALSE=no disk write,TRUE=disk write */

)
{
	DWORD nextClu;

    /* if the top of the release cluster number is 0, return OK immediatly */
	if (cluNo == 0)
		return RET_OK;

    /* loop in FAT */
	for (;;) {
		/* Read FAT */
		if (FatRd(pFdp, cluNo, &nextClu) == RET_ERR)
			return RET_ERR;

        /* release the sector buffer in the cluster */
		SBsetEmpty2(pFdp->dskNo, CvClu2Sec(pFdp, cluNo), pFdp->bpb.sectorsPerCluster);
		if (nextClu == setHi28Bit1(7)) {	/* bad cluster */
			return 2;
		}

		if (nextClu < 2) {	/* invalid cluster number */
			return 3;
		}

        /* write 0(unused) to FAT */
		if (FatWr(pFdp, cluNo, 0) == RET_ERR)
			return RET_ERR;

        /* increment the number of free clusters */
		if (pFdp->freeClu != (DWORD)-1)
			++pFdp->freeClu;

        /* check whethe next cluster exists or not */
		if (nextClu >= setHi28Bit1(8))
			break;		/* the last cluster */

		cluNo = nextClu;
	}

	return RET_OK;
}


/* make a writable directory entry from the dirctory entry parameter */
static VOID MkDirEnt(	/* return : nothing */
	SF_pDirEnt_t pde,	/* pointer of directory entry parameter */
	PBYTE pb			/* pointer of the size of directory entry parameter */
)
{
	memcpy(pb, pde->fname, 11);
	pb += 11;
	*pb++ = pde->atr;
	memcpy(pb, pde->rev, 10);
	pb += 10;
	BS_wPoke(pb, pde->upTime);
	pb += sizeof(WORD);
	BS_wPoke(pb, pde->upDate);
	pb += sizeof(WORD);
	BS_wPoke(pb, (WORD)pde->fatent);
	pb += sizeof(WORD);
	BS_dwPoke(pb, pde->fsize);
}

/* read directory entry to the structure */
static VOID LoadDirEnt(		/* return : nothing */
	SF_pDirEnt_t pde,		/* pointer of directory entry parameter */
	PBYTE pb			    /* pointer of the size of directory entry parameter */
)
{
	memcpy(pde->fname, pb, 11);
	pb += 11;
	pde->atr = *pb++;
	memcpy(pde->rev, pb, 10);
	pb += 10;
	pde->upTime = BS_wPeek(pb);
	pb += sizeof(WORD);
	pde->upDate = BS_wPeek(pb);
	pb += sizeof(WORD);
	pde->fatent = BS_wPeek(pb);
	pb += sizeof(WORD);
	pde->fsize = BS_dwPeek(pb);
}

/* search volume label */
static INT SearchVolumeLabal(	/* return : RET_OK=OK, RET_ERR=Error, RET_EOF=nothing */
	SF_pFatDskPara_t pFdp,		/* pointer of disk control parameter */
	SF_pFcs_t pFcs				/* pointer of file control parameter */
)
{
	DWORD i, sbp, dsn;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* get the sector number of the root directory */
	dsn = pFdp->sn.dir;

    /* search in the root directory */
	for (sbp = i = 0; i < pFdp->bpb.rootEntries; ++i) {
        /* read a directory ntry(32 byte) */
		if (DskRd1s(pFdp->dskNo, dsn, sbp, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return RET_ERR;

		if (!fstDirEmpSno && wkDir[0] == 0xE5) {	/* find the 1st empty DIR entry */
            /* save the position of an empty directry entry */
			fstDirEmpSno = dsn;
			fstDirEmpSbp = sbp;
		} else if (!wkDir[0]) {		/* find the end marak of DIR */
            /* save the position of an empty directory entry */
			if (!fstDirEmpSno) {
				fstDirEmpSno = dsn;
				fstDirEmpSbp = sbp;
			}
			break;
		} else if (wkDir[0x0B] == ATTR_VFAT)	/* VFAT entry */
			;
		else if (wkDir[0x0B] & SF_AT_VOL) {	/* find a volume label */
            /* copy directory entry to file control parameter structure. */
			LoadDirEnt(&pFcs->de, wkDir);

            /* set values to the uninitialised field of the file control parameter */
			pFcs->flag = 0;
			pFcs->dupCnt = 0;
			pFcs->rwPtr = 0;
			pFcs->dirSecNo = dsn;
			pFcs->dirSbpos = sbp;
			pFcs->fileID = 0;
			return RET_OK;
		}

        /* go to the next directory entry */
		sbp += SF_DIRENT_SIZ;
		if (sbp >= pFdp->bpb.bytesPerSector) {
            /* go to the next sector */
			++dsn;
			sbp = 0;
		}
	}

	return RET_EOF;
}

/* Get VFAT entry */
static BOOL GetVfatEntry(SF_pDirEntEx_t pDirEntEx, SF_pDirEnt_t pDirEnt)
{
	int i, j;
	int cnt;
	SF_BYTE c;

    /* get the number of the VFAT entry */
	cnt = (pDirEntEx->weCnt & VFAT_CNT);

    /* if the number of entry is 0, return error */
	if (cnt == 0)
		return FALSE;

    /* About checksum */
	if (pDirEntEx->weCnt & VFAT_LAST) {
        /* if the directory entry is last, save the checksum. */
		pDirEnt->checkSum = pDirEntEx->weChksum;
	} else if (pDirEnt->checkSum != pDirEntEx->weChksum) {
        /* if the checksum is different from the saved checksum, set -1 to the checksum.(NG) */
		pDirEnt->checkSum = -1;
		return FALSE;
	}

    /* get file name from VFAT entry */
	for (i = 0, j = (cnt - 1) * VFAT_CHARS; i < VFAT_CHARS && j < MAX_FILENAME; i++, j++) {
		c = ((SF_BYTE *)pDirEntEx)[fnIndex[i]];
		if (c == 0)
			break;
		pDirEnt->lfname[j] = c;
	}

    /* null-terminate the file name */
	if (pDirEntEx->weCnt & VFAT_LAST)
		pDirEnt->lfname[j] = '\0';

	return TRUE;
}

/* Create CheckSum */
static SF_BYTE CheckSum(PBYTE name)
{
	int i;
	SF_BYTE s = 0;

	for (i = 0; i < 11; i++) {
		s = (s << 7) | (s >> 1);
		s += *name++;
	}

	return s;
}


/* search a filename (directory name) from continuous sectors */
static INT FnxSer0(			/* return : RET_OK=OK, RET_ERR=Error, RET_EOF=cannot find */
	DWORD dsn,				/* the 1st sector number of the directory entry */
	DWORD en,				/* the number of the directory entry */
	PSZ pszFnx,				/* file name(8-characters Name + 3-characters extension) */
	SF_pFatDskPara_t pFdp,	/* pointer of disk control parameter */
	SF_pFcs_t pFcs			/* pointer of file control parameter */
)
{
	DWORD i, sbp;
	BOOL bFind = FALSE;
	int vfatCnt;
	SF_BYTE fname[13];
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* loop by the number of the directory entry in the sector */
	for (sbp = i = 0; i < en; i++) {
        /* read the directory entry */
		if (DskRd1s(pFdp->dskNo, dsn, sbp, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return RET_ERR;

		if (wkDir[0] == 0xE5) {	/* find the 1st empty DIR entry */
            /* save the position of an empty directory entry */
			if (!fstDirEmpSno) {
				fstDirEmpSno = dsn;
				fstDirEmpSbp = sbp;
			}
		} else if (!wkDir[0]) {			/* find the end mark of DIR */
            /* save the position of an empty directory entry */
			if (!fstDirEmpSno) {
				fstDirEmpSno = dsn;
				fstDirEmpSbp = sbp;
			}
			break;
		} else if (wkDir[0x0B] == ATTR_VFAT) {	/* VFAT entry */
            /* get a file name from VFAT entry */
			if (GetVfatEntry((SF_pDirEntEx_t)wkDir, &pFcs->de)) {
                /* save the number of VFAT entry */
				vfatCnt = wkDir[0] & VFAT_CNT;
				if (wkDir[0] & VFAT_LAST)
					pFcs->vfatEntNum = vfatCnt;

                /* save the sector number and the byte position in the sector */
				pFcs->dirSecNoVfat[vfatCnt - 1] = dsn;
				pFcs->dirSbposVfat[vfatCnt - 1] = sbp;
			}
		} else if (!(wkDir[0x0B] & SF_AT_VOL)) {	/* find something except volume label */
            /* check the checksum and the long file name */
			if (CheckSum(wkDir) == pFcs->de.checkSum && strcmp(pszFnx, pFcs->de.lfname) == 0) {
                /* if both match, find it */
				bFind = TRUE;
			} else {
				pFcs->vfatEntNum = 0;

                /* check short file name */
				_SF_CvFname(wkDir, wkDir[0x0B], fname);
				if (strcmp(pszFnx, fname) == 0) {
                    /* if matches, find it */
					strcpy(pFcs->de.lfname, fname);
					bFind = TRUE;
				}
			}

            /* the case of finding the file name */
			if (bFind) {
                /* copy the finding directory entry to the file control parameter */
				LoadDirEnt(&pFcs->de, wkDir);

                /* set the value of the uninitialised field of the file control parameter */
				pFcs->flag = 0;
				pFcs->dupCnt = 0;
				pFcs->rwPtr = 0;
				pFcs->dirSecNo = dsn;
				pFcs->dirSbpos = sbp;
				pFcs->fileID = 0;
				return RET_OK;
			}
		}

        /* go to the next directory entry */
		sbp += SF_DIRENT_SIZ;
		if (sbp >= pFdp->bpb.bytesPerSector) {
			++dsn;
			sbp = 0;
		}
	}

	return RET_EOF;
}


/* search a file name(directory name) */
static INT FnxSer(			/* return : RET_OK=OK, RET_ERR=Error, RET_EOF=nothing */
	PSZ pszFnx,				/* file name(8-characters Name + 3-characters extension) */
	DWORD dirCluNo,			/* the start cluster number of DIR(0:root directory) */
	SF_pFatDskPara_t pFdp,	/* pointer of  */
	SF_pFcs_t pFcs			/* 検索結果を入れるファイル制御構造体を示すポインタ */
)
{
	INT rv;
	DWORD dsn, cn0;
	DWORD n;
	subDirLaxxxluNo = fstDirEmpSno = fstDirEmpSbp = 0;

    /* get the cluter number of the directory */
	pFcs->dirFxxxluNo = dirCluNo;

    /* if the cluster number is 0, search the root directory */
	if (dirCluNo == 0)
		return FnxSer0(pFdp->sn.dir, pFdp->bpb.rootEntries, pszFnx, pFdp, pFcs);

    /* get the number of director entry of sub directory per cluster */
	n = pFdp->bpb.sectorsPerCluster * (pFdp->bpb.bytesPerSector / SF_DIRENT_SIZ);

    /* search sub directory */
	for (;;) {
        /* get the sector number from the cluster number */
		if (!(dsn = CvClu2Sec(pFdp, dirCluNo)))
			goto badFat;

        /* search file name in the sector */
		rv = FnxSer0(dsn, n, pszFnx, pFdp, pFcs);
		if (rv != RET_EOF)
			return rv;

        /* if not find, get the next cluster number from FAT */
		if (FatRd(pFdp, cn0 = dirCluNo, &dirCluNo) == RET_ERR)
			return RET_ERR;

        /* if cluster number is invalid, go to the error sequence */
		if (dirCluNo <= 1 || dirCluNo == setHi28Bit1(7))
			goto badFat;

        /* check whether it is the last cluster */
		if (dirCluNo >= setHi28Bit1(8)) {
            /* if it is the last cluster, save the number of it */
			subDirLaxxxluNo = cn0;
			break;
		}
	}
	return RET_EOF;			/* file name is nothing */

    /* set the error information */
badFat:
	SetErrorInfo(SF_EC_NotDOSdisk, SF_ET_FAT | SF_ET_BADFAT, pFdp->dskNo, 0);
	return RET_ERR;
}


/* search from the nested directory */
#define DirSer_ROL   (1)
#define DirSer_DIR   (2)
static INT DirSer(		/* return : RET_OK=OK, RET_ERR=Error */
	PSZ pszPath,		/* string of path */
	DWORD flag,			/* bit0=1 check whether the file is readonly or not */
						/* bit1=1 search the directory */
	SF_pFcs_t pFcs,		/* pointer of the file conrol parameter */
	PBYTE pbAbsPath,	/* pointer of the absolute path byte array */
	PBYTE pbFileName	/* pointer of the file name */
)
{
	DWORD dskNo =0;
	INT rv;
	DWORD dirCluNo;
	SF_pFatDskPara_t pFdp;
	SF_BYTE fname[MAX_FILENAME + 1];

	dirEntExist = 0;

    /* clear the file control parameter */
	memset(pFcs, 0, sizeof(*pFcs));

    /* check the length of the path */
	if (strlen(pszPath) > MAX_PATHNAME)
		goto tooLong;

    /* get the disk number */
	dskNo = GetDskNo(pszPath, &pszPath);
	if (dskNo == (DWORD)-1) {
		if (DSKIO_dskTbl[0].valid == TRUE)
			dskNo = 0;
		else
			goto pathNF;
	}

    /* set the disk number */
	pFcs->dskNo = dskNo;

    /* get the disk control parameter */
	pFdp = DSKIO_dskTbl[dskNo].pDskPara;

    /* set the directory of finding first */
	if (*pszPath == '/') {	/* from the root directory */
		if (*++pszPath == '/')
			goto accErr;
	}

    /* the cluster number of the root directory */
	dirCluNo = 0;

    /* clear the absolute path */
	if (pbAbsPath)
		*pbAbsPath = '\0';

    /* clear the file name */
	if (pbFileName)
		*pbFileName = '\0';

    /* check whether it is device open or not */
	if (*pszPath == '\0')
		goto foundDevice;

    /* search directory from dirCluNo */
	for (;;) {
        /* cut a directory name from the path */
		if ((flag & DirSer_DIR) && *pszPath == '\0') {
			strcpy(fname, ".");
		} else {
			if (CutPath(&pszPath, fname) == RET_ERR)
				goto badFname;
		}

        /* if cluster number is 0 and filename is ".", directory is the root direcotry */
		if (!dirCluNo && fname[0] == '.' && fname[1] == '\0') {
			if (flag & DirSer_DIR)
				goto foundRootDir;
			continue;
		}

        /* search the directory(file) */
		rv = FnxSer(fname, dirCluNo, pFdp, pFcs);

        /* save the filename */
		if (pbFileName != NULL)
			strcpy(pbFileName, fname);

        /* error check */
		if (rv == RET_ERR)
			return rv;

        /* case of finding nothing */
		if (rv == RET_EOF) {		/* nothing? */
            /* If directory search or on the searching, cannot find any directories */
			if ((flag & DirSer_DIR) || *pszPath == SF_dirDlm)
				goto pathNF;

            /* cannot find any files */
			goto fileNF;
		}

        /* if next character is not delimiter, finish searching */
		if (*pszPath != SF_dirDlm)
			break;

        /* if finding a file on searching, go to error sequence */
		if (!(pFcs->de.atr & SF_AT_DIR))
			goto accErr;

        /* get the next cluster number of sub directory */
		dirCluNo = pFcs->de.fatent;

        /* edit absolute path string */
		if (pbAbsPath) {
			if (AddAbsPath(pbAbsPath, fname) == RET_ERR)
				goto pathOvf;
		}
	}

    /* set a directory entry exist flag */
	dirEntExist = 1;

    /* search file or diectory */
	if (!(flag & DirSer_DIR)) {	/* file */
        /* If directory entry is sub directory, go to error sequence */
		if (pFcs->de.atr & SF_AT_DIR)
			goto accErr;
	} else {			/* directory */
        /* If directory entry is not sub irectory, go to error sequence */
		if (!(pFcs->de.atr & SF_AT_DIR))
			goto accErr;

        /* add the last directory name to the absolute path */
		if (pbAbsPath) {
			if (AddAbsPath(pbAbsPath, fname) == RET_ERR)
				goto pathOvf;
		}
	}

    /* check whether the file is readonly or not */
	if (flag & DirSer_ROL) {
		if (pFcs->de.atr & SF_AT_ROL)
			goto accErr;
	}
	return RET_OK;			/* OK */

    /* the case of finding the device file on searching directory */
foundDevice:
	memset(pFcs->de.fname, ' ', 11);
	return RET_OK;			/* OK */

    /* the case of finding the root directory on searching directory */
foundRootDir:
	pFcs->de.fname[0] = '.';
	memset(pFcs->de.fname + 1, ' ', 10);
	return RET_OK;			/* OK */

    /* set an error information */
tooLong:
	SF_err.errCd = SF_EC_FileNameTooLong;
	goto errRet;
fileNF:
	SF_err.errCd = SF_EC_FileNotFound;
	goto errRet;
badFname:
	SF_err.errCd = SF_EC_PathNotFound;
	SF_err.type = SF_ET_BADFNAME;
	goto errRet1;
pathNF:
pathOvf:
	SF_err.errCd = SF_EC_PathNotFound;
	goto errRet;
accErr:
	SF_err.errCd = SF_EC_AccessDenied;

	/*  goto errRet; */
errRet:
	SF_err.type = SF_ET_GENE;
errRet1:
	SF_err.dskNo = dskNo;
	SF_err.secNo = 0;
	return RET_ERR;			/* ERROR */
}


/* search continuous empty directory entries */
static INT SearchEmptyDirEntry(	/* return : RET_OK=OK, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,		/* pointer of disk control parameter */
	DWORD dirCluNo,				/* start cluster number of directory */
	INT entryNum,				/* the number of directory entry */
	PDWORD pdwDsn,				/* the sector number */
	PDWORD pdwSbp				/* the byte position in the sector */
)
{
	INT rv;
	INT en, i, cnt;
	SF_BYTE ent1;
	DWORD dsn, sbp, cn0;
	DWORD w[3];
	DWORD dw;
	DWORD cluNo;

    /* clear the number of the entries */
	cnt = 0;

    /* get the number of the directory entry */
	if (dirCluNo == 0) {
        /* if the directory is the root directory, get from the disk control parameter */
		en = pFdp->bpb.rootEntries;
	} else {
        /* if the directory is the sub directory, calculate from the 1 cluster size */
		en = pFdp->bpb.sectorsPerCluster * (pFdp->bpb.bytesPerSector / SF_DIRENT_SIZ);
	}

    /* search the continuous empty directory entry */
	for (;;) {
        /* get the sector number */
		if (dirCluNo == 0) {
            /* if the directory is the root directory, get from the disk control parameter */
			dsn = pFdp->sn.dir;
		} else {
            /* if the directory is the sub directory, get from the cluster number */
			if (!(dsn = CvClu2Sec(pFdp, dirCluNo)))
				goto badFat;
		}

        /* search in the cluster */
		for (sbp = i = 0; i < en; ++i) {
            /* read the 1st byte of the directory entry */
			if (DskRd1s(pFdp->dskNo, dsn, sbp, SF_BF_DIR, &ent1, 1, TRUE) == RET_ERR)
				return RET_ERR;

            /* if 1st byte is 0x00 or 0xE5, this entry is empty */
			if (ent1 == 0 || ent1 == 0xE5) {
                /* save the empty position */
				pdwDsn[cnt] = dsn;
				pdwSbp[cnt] = sbp;

                /* increment the number of the entry */
				cnt++;

                /* if the number of the entry equals the required number, return OK */
				if (cnt >= entryNum)
					return RET_OK;
			} else {
                /* if not empty, clear the counter */
				cnt = 0;
			}

            /* go to the next entry */
			sbp += SF_DIRENT_SIZ;
			if (sbp >= pFdp->bpb.bytesPerSector) {
				++dsn;
				sbp = 0;
			}
		}

        /* if it is the root directory, empty directory entry is nothing */
		if (dirCluNo == 0)
			goto accErr;

        /* get the next cluster number */
		if (FatRd(pFdp, cn0 = dirCluNo, &dirCluNo) == RET_ERR)
			return RET_ERR;

        /* if the cluster number is invalid, go to error sequence */
		if (dirCluNo <= 1 || dirCluNo == setHi28Bit1(7))
			goto badFat;

        /* if it is the last cluster, add a cluster */
		if (dirCluNo >= setHi28Bit1(8)) {	/* no more subdirectory exists */
            /* set an original cluster number */
			dirCluNo = cn0;

            /* search an empty cluster */
			rv = SerEmpClu(dirCluNo + 1, SF_DIRENT_SIZ, w, &cluNo, pFdp);
			if (rv == RET_ERR)
				return SF_err.errCd;

            /* if empty cluster is nothing, go to error sequence */
			if (rv == RET_EOF || w[0] == 0)
				goto accErr;

            /* get the sector number */
			dw = CvClu2Sec(pFdp, cluNo);

            /* zeroclear 1 cluster in the directory entry */
			if (DskWrSec(pFdp->dskNo, dw, SF_BF_DIR, NULL, pFdp->bpb.sectorsPerCluster) == RET_ERR)
				return RET_ERR;

            /* allocate an empty cluster in FAT */
			if (AllocClu(&dirCluNo, cluNo, 1, pFdp) == RET_ERR)
				return RET_ERR;
		}
	}

	return RET_OK;

    /* set an error information */
badFat:
	SetErrorInfo(SF_EC_NotDOSdisk, SF_ET_FAT | SF_ET_BADFAT, pFdp->dskNo, 0);
	return RET_ERR;

accErr:
	SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFdp->dskNo, 0);
	return RET_ERR;
}

/* write VFAT directory entry */
static INT WriteVfatDirEnt(
	PBYTE lfname,
	SF_pFatDskPara_t pFdp,
	SF_pFcs_t pFcs
)
{
	int rv, rv2;
	int i, j;
	int len;
	int gen;
	int vfatEntNum;
	int cnt;
	PBYTE pb;
	DWORD dirCluNo;
	DWORD dsn[MAX_VFAT_ENTRY + 1], sbp[MAX_VFAT_ENTRY + 1];
	SF_BYTE checkSum;
	SF_BYTE dirEnt[SF_DIRENT_SIZ];
	SF_pDirEntEx_t pDirEntEx = (SF_pDirEntEx_t)dirEnt;
	SF_BYTE sfname[13];

    /* get the start cluster number of the directory */
	dirCluNo = pFcs->dirFxxxluNo;

    /* get the length of the file name */
	len = strlen(lfname);

    /* initial number of short file name */
	gen = 1;

	for (;;) {
        /* Make a short file name */
		rv = MakeShortName(lfname, pFcs->de.fname, len, gen);
		if (rv == 0)
			return RET_ERR;

        /* check whether short file name exists or not */
		_SF_DirFn(&pFcs->de, sfname);
		rv2 = FnxSer(sfname, dirCluNo, pFdp, pFcs);
		if (rv2 == RET_ERR)
			return rv2;		/* error */
		else if (rv2 == RET_OK) {
			/* exist */
			if (rv == RET_OK)
				return RET_ERR;	/* if long file name does not exists, error */
			else
				gen++;		/* increment the number of short file name */
		} else if (rv2 == 2) {
            /* save the file name */
			strcpy(pFcs->de.lfname, lfname);

            /* get the number of VFAT entry */
			if (rv == 1) {
                /* set the number of VFAT entry to zero */
				vfatEntNum = 0;
			} else {
                /* get the number of VFAT entry from the length of the file name */
				vfatEntNum = (len + VFAT_CHARS - 1) / VFAT_CHARS;
			}
			break;		/* if not exists, break */
		}
	}

    /* search the empty directory entry */
	if (SearchEmptyDirEntry(pFdp, dirCluNo, vfatEntNum + 1, dsn, sbp) == RET_ERR)
		return RET_ERR;

    /* get the number of VFAT entry */
	pFcs->vfatEntNum = vfatEntNum;

    /* calculate checksum */
	checkSum = CheckSum(pFcs->de.fname);

    /* write to VFAT */
	for (i = 0; i < vfatEntNum; i++) {
        /* clear VFAT entry */
		memset(dirEnt, 0, sizeof(dirEnt));

        /* set the file name */
		cnt = vfatEntNum - i;
		pb = pFcs->de.lfname + (cnt - 1) * VFAT_CHARS;
		for (j = 0; j < VFAT_CHARS && *pb != '\0'; j++)
			dirEnt[fnIndex[j]] = *pb++;

        /* set VFAT counter and the last entry flag */
		pDirEntEx->weCnt = cnt;
		if (i == 0)
			pDirEntEx->weCnt |= VFAT_LAST;

        /* set attribute and checksum */
		pDirEntEx->weAttributes = ATTR_VFAT;
		pDirEntEx->weChksum = checkSum;

        /* write VFAT entry to the disk */
		if (DskWr1s(pFcs->dskNo, dsn[i], sbp[i], SF_BF_DIR, dirEnt, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return RET_ERR;	/* return error */

        /* save the position of the VFAT entry */
		pFcs->dirSecNoVfat[i] = dsn[i];
		pFcs->dirSbposVfat[i] = sbp[i];
	}

    /* save the position of directory entry */
	pFcs->dirSecNo = dsn[i];
	pFcs->dirSbpos = sbp[i];

	return RET_OK;
}


/* count the number of entry recorded in continuous sector (directory entry) */
static INT CntDirEnt(		/* return : RET_OK=OK, RET_EOF=last entry, RET_ERR=Error */
	SF_pFatDskPara_t pFdp,	/* pointer of the disk control parameter */
	DWORD dsn,				/* sector number */
	DWORD n,				/* the number of directory entry */
	PINT32 pi32Cnt			/* counter results(three INT32 values) */
							/* pi32Cnt[0] : the number of all entries */
							/* pi32Cnt[1] : the number of file, sub directory entries */
							/* pi32Cnt[2] : the number of the entry which begin from "." */
)
{
	DWORD i, sbp = 0;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* loop by the number of directory entry */
	for (i = 0; i < n; ++i) {
        /* read directory entry */
		if (DskRd1s(pFdp->dskNo, dsn, sbp, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return RET_ERR;		/* ERROR */

        /* if the top is 0, the entry is last */
		if (!wkDir[0])
			return RET_EOF;		/* last entry */

        /* sum the number of the all entry */
		++pi32Cnt[0];

        /* add the number of the entry which begins from "." */
		if (wkDir[0] == '.')
			++pi32Cnt[2];

        /* add the number of file, sub directory entries */
        if (wkDir[0] != 0xE5 && !(wkDir[0x0B] & SF_AT_VOL))
			++pi32Cnt[1];

        /* go to the next directory entry */
		sbp += SF_DIRENT_SIZ;
		if (sbp >= pFdp->bpb.bytesPerSector) {
			++dsn;
			sbp = 0;
		}
	}

	return RET_OK;			/* OK */
}


/*-------------------------------------------------------------------*/
/*                       Global Functions                            */
/*-------------------------------------------------------------------*/


/* Initialise FAT File System */
BOOL	_SF_Init(			/* return : TRUE=OK,FALSE=fail to initialise */
	VOID					/* no arguments */
)
{
	DWORD dskNo;
	SF_pSecBuf_t psb;
	int i;

	printf("%s\n", SF_VERSION);

    /* initialise disk */
	if (! _DSKIO_init())
		return FALSE;

    /* set the disk information to the disk table and disk control parameter table */
	for (dskNo = 0; dskNo < MAX_DISK_NUM; dskNo++) {
		DSKIO_dskTbl[dskNo].valid = FALSE;
		DSKIO_dskTbl[dskNo].pDskPara = &DSKIO_fdp[dskNo];
		memset(&DSKIO_fdp[dskNo], 0 ,sizeof DSKIO_fdp[dskNo]); /* zero clear */
		DSKIO_fdp[dskNo].dskNo = dskNo;     /* disk number of the file system */
	}

    /* initialise file control parameter table */
	for (i = 0; i < DSKIO_FILES; i++) {
		DSKIO_fcs[i].dskNo = SF_FF_EMPTY;
	}

    /* initialise sector buffers */
	psb = NULL;
    for (i = 0; i < DSKIO_BUFFERS; i++) {
		SF_pSecBuf = malloc(sizeof(SF_secBuf_t));
		if (SF_pSecBuf == NULL)
			return FALSE;

		memset(SF_pSecBuf, 0, sizeof(SF_secBuf_t));
		SF_pSecBuf->pNextSecBuf = psb;
		SF_pSecBuf->dskNo = SF_BF_EMPTY;
		psb = SF_pSecBuf;
    }

    /* initialise file ID */
	SF_nextFileID = 0;
	SF_FileIDmax = SF_FILE_ID_MAX;

	return TRUE;		/* initialise end */
}


/* mount disk */
RESULT _SF_Mount(				/* return : 0=success,more than 1=failure */
	PSZ devName,				/* device Name */
	PVOID devInfo				/* device Information */
)
{
	DWORD dskNo;
	SF_pFatDskPara_t pFdp;

    /* search empty disk manage table */
	for (dskNo = 0; dskNo < MAX_DISK_NUM; dskNo++) {
		if (DSKIO_dskTbl[dskNo].valid == FALSE)
			break;
	}

    /* if empty table does not exists, return Error */
	if (dskNo == MAX_DISK_NUM)
		return SF_EC_GeneralFailure;

    /* set used state to the disk manage table */
	DSKIO_dskTbl[dskNo].valid = TRUE;

    /* save the device name */
	strcpy(DSKIO_dskTbl[dskNo].devName, devName);

    /* save the pointer of the device information */
	DSKIO_dskTbl[dskNo].devInfo = devInfo;

    /* get the disk control parameter */
	pFdp = DSKIO_dskTbl[dskNo].pDskPara;

    /* read BPB of the new disk, and set the disk control parameter */
	if (ResetFdp(pFdp, 1) == RET_ERR) {		/* Error ? */
		DSKIO_dskTbl[dskNo].valid = FALSE;	/* cut off the conxxxtion */
		return SF_err.errCd;
	}

	return 0;
}


/* Check Disk Ready */
BOOL	_SF_RdyChk(			/* return : TRUE=Disk Ready, FALSE=Disk Not Ready or Error */
	DWORD dskNo				/* in:disk Number */
)
{
    /* check the status of the disk */
	if (DskRdyChk(dskNo, 1, NULL) == RET_ERR)
		return FALSE;	/* error or disk not ready occured */

    /* clear error information */
	ClearErrorInfo();

	return TRUE;		/* return disk ready */
}


/* get a number of empty cluster */
RESULT _SF_GetFreeClu(	    /* return : 0=OK,more than 1=error code */
	INT32 i32FileID,		/* in:File ID Number */
	PDWORD pdwSectSize,		/* out:the number of bytes per 1 sector  */
	PDWORD pdwSectPerClu,	/* out:the number of bytes per 1 cluster */
	PDWORD pdwFreeClu,		/* out:the number of available clusters  */
	PDWORD pdwTotalClu		/* out:the number of clusters per 1 disk */
)
{
	DWORD dskNo;
	SF_pFcs_t pFcs;
	DWORD m;
	DWORD i, s, dwv, fsn, fee;
	SF_pFatDskPara_t pFdp;
	PBYTE pb = NULL;
	DWORD j;
	static	SF_BYTE wkArea[SF_MAXSECBYTE];

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);
	dskNo = pFcs->dskNo;

    /* check the status of the disk */
	if (DskRdyChk(dskNo, 0, NULL) == RET_ERR)
		return 0xFFFF;		/* error occured */

    /* clear error information */
	ClearErrorInfo();

    /* get the disk control information */
	pFdp = DSKIO_dskTbl[dskNo].pDskPara;

    /* get the number of available clusters */
	if (pdwFreeClu) {
        /* check whether the number of empty cluster is written or not */
		if (pFdp->freeClu == (DWORD)-1) {
            /* if it is not written, calculate from FAT information */
			m = pFdp->bpb.bytesPerSector / 2;

			fsn = pFdp->sn.fat1;
			fee = 0;
			for (s = 0, i = 0; i < pFdp->eoc; ++i) {
				if ((i % m) == 0) {
					dwv = fsn;
					for (j = 0; j < pFdp->bpb.numberOfFATs; ++j) {
                        /* read 1 sector from FAT area */
						if (DskRdSec(dskNo, dwv, SF_BF_FAT, wkArea, 1) == RET_OK)
							goto skip1;

                        /* if read error occured in this FAT area, try to read from the next FAT area */
						dwv += pFdp->bpb.sectorsPerFAT;
					}

                    /* read error in all FAT area */
					return 0xFFFF;	/* error occured */
skip1:	/* success to read */
					pb = wkArea;
					fsn += 1;
				}
				dwv = (*(pb + 1) << 8) | *pb;
				pb += 2;
				if (i >= 2 && !dwv) {	/* find an empty cluster */
					if (!fee)
						fee = i;	/* record the 1st empty cluster number */
					++s;
				}
			}
			*pdwFreeClu = s;
			pFdp->freeClu = s;
			if (pFdp->defscn < fee) {
				pFdp->defscn = fee;	/* set the search position of empty cluster */
			}
		} else {
            /* if alread set, set the number of Free cluster */
			*pdwFreeClu = pFdp->freeClu;
		}
	}

    /* set the bytes per 1 sector */
	if (pdwSectSize)
		*pdwSectSize = pFdp->bpb.bytesPerSector;

    /* set the number of sectors per 1 cluster */
	if (pdwSectPerClu)
		*pdwSectPerClu = pFdp->bpb.sectorsPerCluster;

    /* set the number of clusters per 1 disk */
	if (pdwTotalClu)
		*pdwTotalClu = pFdp->eoc - 2;

	return 0;
}


/* make a new Sub Directory */
RESULT _SF_MkDir(		/* return :	0=success,more than 1=error code */
	PSZ	pszPath			/* in:the path of sub directory */
)
{
	INT rv;
	SF_BYTE wkDir[SF_DIRENT_SIZ];
	DWORD dw, cluNo;
	DWORD w[3];
	SF_pFatDskPara_t pFdp;
	SF_fcs_t pFcs[1];
	SF_BYTE fname[MAX_FILENAME + 1];

    /* check the character of file name */
	if (! BS_isfname(pszPath))
		goto accErr;

    /* search the sub directory name */
	rv = DirSer(pszPath, 0, pFcs, NULL, fname);
	if (rv == RET_OK)
		goto accErr;		/* same file name exists */

    /* error check */
	if (SF_err.errCd != SF_EC_FileNotFound)
		return SF_err.errCd;	/* failure */

	if (SF_err.type != SF_ET_GENE)
		goto badPath;		/* failure */

    /* get disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* make initial directory entry */
	memset(pFcs->de.fname, ' ', 11);
	pFcs->de.atr = SF_AT_DIR;
	memset(pFcs->de.rev, 0, 10);
	dw = _CLK_ReadFileDT();
	pFcs->de.upTime = (WORD)dw;
	pFcs->de.upDate = (WORD)(dw >> 16);
	pFcs->de.fatent = pFcs->de.fsize = 0;

    /* get the area of new sub directories in the disk */
	rv = SerEmpClu(0, SF_DIRENT_SIZ, w, &cluNo, pFdp);
	if (rv == RET_ERR)
		return SF_err.errCd;	/* failure */
	if (rv == RET_EOF || w[0] == 0)
		goto accErr;		/* failure */

	/* ディレクトリエントリを１クラスタ分ゼロクリア */
    /* zero clear directory entry by 1 cluster */
	dw = CvClu2Sec(pFdp, cluNo);
	if (DskWrSec(pFdp->dskNo, dw, SF_BF_DIR, NULL, pFdp->bpb.sectorsPerCluster) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* register a cluster on FAT */
	if (AllocClu(NULL, cluNo, 1, pFdp) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* write the entry of sub directory "." */
	pFcs->de.fname[0] = '.';
	pFcs->de.fatent = cluNo;
	MkDirEnt(&pFcs->de, wkDir);
	if (DskWr1s(pFdp->dskNo, dw, 0, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* write the entry of sub directory ".." */
	pFcs->de.fname[1] = '.';
	pFcs->de.fatent = pFcs->dirFxxxluNo;
	MkDirEnt(&pFcs->de, wkDir);
	if (DskWr1s(pFdp->dskNo, dw, SF_DIRENT_SIZ, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* make new VFAT directory entry */
	if (WriteVfatDirEnt(fname, pFdp, pFcs) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* write a new sub directory entry */
	pFcs->de.fatent = cluNo;
	MkDirEnt(&pFcs->de, wkDir);
	if (DskWr1s(pFdp->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

	/* set error information */
badPath:
/*pathOvf: */
	SF_err.errCd = SF_EC_PathNotFound;
	goto errRet;
accErr:
	SF_err.errCd = SF_EC_AccessDenied;

	/*  goto errRet; */
errRet:
	SF_err.type = SF_ET_GENE;
	SF_err.dskNo = pFcs->dskNo;
	SF_err.secNo = 0;
	return SF_err.errCd;	/* failure */
}


/* remove a Sub Directory */
RESULT _SF_RmDir(		/* return :	0=success,more than 1=error code */
	PSZ	pszPath			/* in:the path of sub directory */
)
{
	INT rv;
	DWORD n;
	DWORD dsn, cluNo;
	INT32 s[3];
	SF_pFatDskPara_t pFdp;
	SF_fcs_t pFcs[1];
	SF_BYTE absPath[MAX_PATHNAME];
	DWORD i;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* search the sub directory name */
	rv = DirSer(pszPath, DirSer_DIR, pFcs, absPath, NULL);
	if (rv == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* get the start cluster number of sub directory */
	cluNo = pFcs->de.fatent;
	s[0] = s[1] = s[2] = 0;

    /* cannot remove the root directory */
	if (cluNo == 0)
		goto accErr;

    /* get the disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* check whether valid entry exists on  sub directory or not */
	n = pFdp->bpb.bytesPerSector / SF_DIRENT_SIZ;
	n *= pFdp->bpb.sectorsPerCluster;
	for (;;) {
		if (!(dsn = CvClu2Sec(pFdp, cluNo)))
			goto badFat;
		rv = CntDirEnt(pFdp, dsn, n, s);
		if (s[1] - s[2])
			goto accErr;
		if (rv == RET_EOF)
			break;
		if (rv == RET_ERR)
			return SF_err.errCd;	/* failure */

		if (FatRd(pFdp, cluNo, &cluNo) == RET_ERR)
			return SF_err.errCd;	/* failure */

		if (cluNo < 2 || cluNo == setHi28Bit1(7))
			goto badFat;
		if (cluNo >= setHi28Bit1(8))
			break;		/* no more sub directories */
	}
	if (s[2] != 2)
		goto noEmpty;

    /* check whether sub directory is open or not */
	if (FcsSerPath(pFcs, NULL) != NULL)
		goto accErr;

    /* remove VFAT entry */
	wkDir[0] = 0xE5;
	for (i = 0; i < pFcs->vfatEntNum; i++) {
		if (DskWr1s(pFcs->dskNo, pFcs->dirSecNoVfat[i], pFcs->dirSbposVfat[i], SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */
	}

    /* remove sub directory entry */
	if (DskWr1s(pFcs->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* release the cluster sub directory use */
	rv = ReleaseClu(pFcs->de.fatent, pFdp, TRUE);
	if (rv >= 2)		/* bad cluster or invalid cluster */
		goto badFat;
	if (rv == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

    /* set error information */
badFat:
	SF_err.errCd = SF_EC_NotDOSdisk;
	SF_err.type = SF_ET_FAT | SF_ET_BADFAT;
	goto errRet1;
accErr:
	SF_err.errCd = SF_EC_AccessDenied;
	goto errRet1;
noEmpty:
	SF_err.errCd = SF_EC_NotDirEmpty;
	/*  goto errRet; */
errRet1:
	SF_err.dskNo = pFcs->dskNo;
	SF_err.secNo = 0;
	return SF_err.errCd;	/* failure */
}


/* remove a file */
RESULT _SF_Remove(		/* return : 0=success,more than 1=error code */
	PSZ	pszPath			/* in:path of file path */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_fcs_t pFcs[1];
	DWORD i;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* search file name */
	rv = DirSer(pszPath, DirSer_ROL, pFcs, NULL, NULL);
	if (rv == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* get the disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* check whether the file is open or not */
	if (FcsSerPath(pFcs, NULL) != NULL)
		goto accErr;

    /* remove VFAT entry */
	wkDir[0] = 0xE5;
	for (i = 0; i < pFcs->vfatEntNum; i++) {
		if (DskWr1s(pFcs->dskNo, pFcs->dirSecNoVfat[i], pFcs->dirSbposVfat[i], SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */
	}

    /* remove directory entry */
	if (DskWr1s(pFcs->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* release the cluster file use */
	rv = ReleaseClu(pFcs->de.fatent, pFdp, TRUE);
	if (rv >= 2)		/* bad cluster or invalid cluster number */
		goto badFat;
	if (rv == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

	/* set error information */
badFat:
	SF_err.errCd = SF_EC_NotDOSdisk;
	SF_err.type = SF_ET_FAT | SF_ET_BADFAT;
	goto errRet1;
accErr:
	SF_err.errCd = SF_EC_AccessDenied;

	/*  goto errRet; */
	SF_err.type = SF_ET_GENE;
errRet1:
	SF_err.dskNo = pFcs->dskNo;
	SF_err.secNo = 0;
	return SF_err.errCd;	/* failure */
}


/* change file name */
RESULT _SF_Rename(		/* return : 0=success,more than 1=error code */
	PSZ	pszOldPath,		/* in:old file name */
	PSZ	pszNewPath		/* in:new file name */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_fcs_t pFcsOld[1], pFcsNew[1];
	static	SF_BYTE pbOldp[MAX_PATHNAME], pbNewp[MAX_PATHNAME];
	DWORD dskNo;
	static	SF_BYTE newFileName[MAX_FILENAME + 1];
	DWORD i;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* check the validity of new path name */
	if (!pszNewPath || *pszNewPath <= ' ')
		goto badPath;

    /* check the character of file name */
	if (! BS_isfname(pszNewPath))
		goto accErr;

    /* get the disk number of the new file name */
	dskNo = GetDskNo(pszNewPath, NULL);
	if (dskNo == (DWORD)-1)
		goto nsDev;

    /* search old file (directory) */
	rv = DirSer(pszOldPath, 0, pFcsOld, pbOldp, NULL);
	if (rv == RET_OK) {			/* file exists */
		if (pFcsOld->dskNo != dskNo)
			goto nsDev;
		goto fileRename;
	}
	if (dirEntExist) {		/* entry exists */
		if (pFcsOld->de.atr & SF_AT_DIR) {	/* sub directory entry */
			if (pFcsOld->dskNo != dskNo)
				goto nsDev;
			goto dirRename;
		}
	}
	return SF_err.errCd;	/* failure */

dirRename:		/* change the sub directory name */
    /* if moving sub direcotry, error */
	if (strcmp(pbOldp, pbNewp))
		goto accErr;

fileRename:		/* move file or change file name */
    /* get disk control parameter */
	pFdp = DSKIO_dskTbl[pFcsOld->dskNo].pDskPara;

    /* check whether file is the hidden file or system file or neither */
	if (pFcsOld->de.atr & (SF_AT_HID | SF_AT_SYS))
		goto accErr;

    /* check whether file is open or not */
	if (FcsSerPath(pFcsOld, NULL) != NULL)
		goto accErr;

    /* search a new file (if not exists, OK) */
	rv = DirSer(pszNewPath, 0, pFcsNew, pbNewp, newFileName);
	if (rv == RET_OK)
		goto accErr;			/* same file name exists */
	if (SF_err.errCd != SF_EC_FileNotFound)
		return SF_err.errCd;	/* failure */
	if (SF_err.type != SF_ET_GENE)
		goto badPath;			/* failure */

    /* make a new VFAT directory entry */
	if (WriteVfatDirEnt(newFileName, pFdp, pFcsNew) == RET_ERR)
		return SF_err.errCd;	/* failure */

    /* write a new directory entry */
	memcpy(pFcsOld->de.fname, pFcsNew->de.fname, 11);
	MkDirEnt(&pFcsOld->de, wkDir);
	if (DskWr1s(dskNo, pFcsNew->dirSecNo, pFcsNew->dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

	/* remove old VFAT entry */
	wkDir[0] = 0xE5;
	for (i = 0; i < pFcsOld->vfatEntNum; i++) {
		if (DskWr1s(pFcsOld->dskNo, pFcsOld->dirSecNoVfat[i], pFcsOld->dirSbposVfat[i], SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */
	}

    /* remove old directory entry */
	if (DskWr1s(dskNo, pFcsOld->dirSecNo, pFcsOld->dirSbpos, SF_BF_DIR, wkDir, 1, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

    /* set error information */
badPath:
	SF_err.errCd = SF_EC_PathNotFound;
	goto errRet;
nsDev:
	SF_err.errCd = SF_EC_NotSameDevice;
	goto errRet;
accErr:
	SF_err.errCd = SF_EC_AccessDenied;

	/*  goto errRet; */
errRet:
	SF_err.type = SF_ET_GENE;
	SF_err.dskNo = pFcsOld->dskNo;
	SF_err.secNo = 0;
	return SF_err.errCd;	/* failure */
}


/* Get the Attibute of file */
RESULT _SF_GetFattr(		/* return : 0=success,more than 1=error code,-1=argument error */
	PSZ	  pszPath,			/* in:file path */
	PWORD pwAttr			/* out:file attribute */
)
{
	INT rv;
	SF_fcs_t pFcs[1];

    /* search file name */
	rv = DirSer(pszPath, 0, pFcs, NULL, NULL);
	if (rv == RET_ERR) {
		if (dirEntExist) {	/* entry exists */
			if (pFcs->de.atr & SF_AT_DIR) {	/* sub directory entry */
				goto skip1;
			}
		}
		return SF_err.errCd;	/* failure */
	}

skip1:
	/* set attribute */
	if (pwAttr)
		*pwAttr = pFcs->de.atr;

	/* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* Set the Attribute of file */
RESULT _SF_SetFattr(		/* return : 0=success,more than 1=error code,-1=argument error */
	PSZ	  pszPath,			/* in:file path */
	WORD   attr				/* in:file attribute */
)
{
	INT rv;
	SF_fcs_t pFcs[1];
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* search file name */
	rv = DirSer(pszPath, 0, pFcs, NULL, NULL);
	if (rv == RET_ERR) {
		if (dirEntExist) {	/* entry exists */
			if (pFcs->de.atr & SF_AT_DIR) {	/* sub directory entry */
				goto skip1;
			}
		}
		return SF_err.errCd;	/* failure */
	}
skip1:
    /* check whether file is open or not */
	if (FcsSerPath(pFcs, NULL) != NULL)
		goto accErr;


    /* if trying to change volume label or directory  of attribute, error */
	if ((pFcs->de.atr ^ attr) & (SF_AT_VOL | SF_AT_DIR))
		goto accErr;

    /* change attribute */
	pFcs->de.atr = (SF_BYTE)(attr & 0x3F);

    /* update directory entry */
	MkDirEnt(&pFcs->de, wkDir);
	if (DskWr1s(pFcs->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

accErr:
    /* set error information */
	return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);
}


/* Open File */
RESULT _SF_Open(			/* return : 0=success,more than 1=error code */
	PSZ	   pszPath,			/* in:file path       */
	DWORD	flag,			/* in:open flag       */
	PINT32 pi32FileID		/* out:File ID Number */
)
{
	INT ac;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	DWORD bv;

    /* check the character of file name */
	if (! BS_isfname(pszPath))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_PARA, (DWORD)-1, 0);


    /* check the pointer of File ID number */
	if (!pi32FileID)
		return SetErrorInfo(SF_EC_InvalidData, SF_ET_PARA, (DWORD)-1, 0);

    /* check access code(bit3-0) */
	ac = flag & 0x0F;
	if (!(ac >= SF_OPEN_RD && ac <= SF_OPEN_RW) && ac != SF_OPEN_DIR_RD)
		return SetErrorInfo(SF_EC_InvalidAccessCode, SF_ET_GENE, (DWORD)-1, 0);

    /* check flag(bit7-4) */
	if (flag & 0xF0)
		return SetErrorInfo(SF_EC_InvalidAccessCode, SF_ET_GENE, (DWORD)-1, 0);

    /* get an empty file control parameter */
	pFcs = GetEmpFcs();
	if (pFcs == NULL)			/* any empty file control parameters do not exist */
		return SetErrorInfo(SF_EC_TooManyOpen, SF_ET_GENE, (DWORD)-1, 0);

    /* get Read/Write mode */
	bv = 0;
	if (ac == SF_OPEN_WR || ac == SF_OPEN_RW)
		bv = DirSer_ROL;
	if (ac == SF_OPEN_DIR_RD)
		bv = DirSer_DIR;

    /* search file name */
	if (DirSer(pszPath, bv, pFcs, NULL, NULL) == RET_ERR) {
		pFcs->dskNo = SF_FF_EMPTY;
		return SF_err.errCd;	/* failure */
	}

    /* if file name is " ", open device */
	if (pFcs->de.fname[0] == ' ')
		pFcs->flag = SF_FF_DEVICE;
	else
		pFcs->flag = SF_FF_FILE;

    /* set flag from access code */
	switch (ac) {
	case SF_OPEN_RD:
		pFcs->flag |= SF_FF_RDMD;
		break;
	case SF_OPEN_WR:
		pFcs->flag |= SF_FF_WRMD;
		break;
	case SF_OPEN_RW:
		pFcs->flag |= SF_FF_RDMD | SF_FF_WRMD;
		break;
	case SF_OPEN_DIR_RD:
		pFcs->flag = SF_FF_RDMD | SF_FF_DIR;
		break;
	}

    /* increment of the number of the open file on the disk */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
	++pFdp->openFileCnt;

    /* clear error information */
	ClearErrorInfo();

    /* set file ID */
	*pi32FileID = pFcs->fileID = GetNewfID();

	return 0;			/* succcess */
}


/* Create File */
RESULT _SF_Create(		/* return : 0=success,more than 1=error code */
	PSZ	   pszPath,		/* in:file path       */
	DWORD	attr,		/* in:file attribute  */
	PINT32 pi32FileID	/* out:File ID Number */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_fcs_t pFcs[1];
	SF_pFcs_t pFcs0;
	DWORD dw;
	SF_BYTE fname[MAX_FILENAME + 1];
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* check the character of file name */
	if (! BS_isfname(pszPath))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_PARA, (DWORD)-1, 0);

    /* check the pointer of File ID */
	if (!pi32FileID)
		return SetErrorInfo(SF_EC_InvalidData, SF_ET_PARA, (DWORD)-1, 0);

    /* check the attribute */
	if (attr & (~0x3F | SF_AT_DIR | SF_AT_VOL))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, (DWORD)-1, 0);

    /* search file */
	rv = DirSer(pszPath, DirSer_ROL, pFcs, NULL, fname);
	if (rv == RET_OK) {		/* file already exists */
						   /* set file size to zero */
		pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
		rv = ReleaseClu(pFcs->de.fatent, pFdp, TRUE);
		if (rv >= 2)		/* bad cluster or invalid cluster number */
			goto badFat;
		if (rv == RET_ERR)
			return SF_err.errCd;	/* error occured */
	} else {
		if (SF_err.errCd != SF_EC_FileNotFound)
			return SF_err.errCd;	/* failure */

		if (SF_err.type != SF_ET_GENE)
			goto badPath;	/* failure */

		pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

        /* make a new VFAT directory entry */
		if (WriteVfatDirEnt(fname, pFdp, pFcs) == RET_ERR)
			return SF_err.errCd;	/* failure */
	}

    /* make a directory entry */
	pFcs->de.atr = (SF_BYTE)attr;
	pFcs->de.fatent = 0;
	pFcs->de.fsize = 0;
	dw = _CLK_ReadFileDT();
	pFcs->de.upTime = (WORD)dw;
	pFcs->de.upDate = (WORD)(dw >> 16);

    /* write a new or updated directory entry */
	MkDirEnt(&pFcs->de, wkDir);
	if (DskWr1s(pFcs->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* get an empty file control parameter */
	pFcs0 = GetEmpFcs();
	if (!pFcs0)			/* any empty file control parametrs do not exist */
		return SetErrorInfo(SF_EC_TooManyOpen, SF_ET_GENE, (DWORD)-1, 0);

    /* set access mode to file R/W */
	*pFcs0 = *pFcs;
	pFcs0->flag = SF_FF_RDMD | SF_FF_WRMD | SF_FF_FILE;

    /* increment of the number of open file on the disk */
	++pFdp->openFileCnt;

    /* clear error information */
	ClearErrorInfo();

    /* set file ID */
	*pi32FileID = pFcs0->fileID = GetNewfID();

	return 0;			/* success */

    /* set error information */
badFat:
	SF_err.errCd = SF_EC_NotDOSdisk;
	SF_err.type = SF_ET_FAT | SF_ET_BADFAT;
	goto errRet1;
badPath:
	SF_err.errCd = SF_EC_PathNotFound;
	goto errRet;

	/*  goto errRet; */
errRet:
	SF_err.type = SF_ET_GENE;
errRet1:
	SF_err.dskNo = pFcs->dskNo;
	SF_err.secNo = 0;
	return SF_err.errCd;	/* failure */
}


/* Close File */
RESULT _SF_Close(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID	/* in:File ID Number */
)
{
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* if directory entry is changed, update */
	if (pFcs->flag & SF_FF_DEUPD) {
		MkDirEnt(&pFcs->de, wkDir);
		if (DskWr1s(pFcs->dskNo, pFcs->dirSecNo, pFcs->dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */
	}

    /* flush buffer */
	if (pFcs->flag & (SF_FF_WF | SF_FF_DEUPD))
		_SF_Sync3(pFcs->dskNo);

    /* decreament of the number of open file in the disk */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
	--pFdp->openFileCnt;

    /* release file control parameter */
	pFcs->dskNo = SF_FF_EMPTY;

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* Read File */
RESULT _SF_Read(			/* return : 0=success,more than 1=error code */
	INT32  i32FileID,		/* in:File ID Number */
	PVOID pvBuf,			/* out:Buffer for Read Data */
	DWORD	blen,			/* in:Read Data Size */
	PDWORD pwRlen			/* out:real size of read data */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	PBYTE badr;
	struct CVFRWP_S pFrwp[1];
	DWORD bpc, w, w0, w1, shf;

    /* check parameter */
	if ((blen && !pvBuf) || !pwRlen)
		return SetErrorInfo(SF_EC_InvalidData, SF_ET_PARA, (DWORD)-1, 0);

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* check access mode */
	if (!(pFcs->flag & SF_FF_FILE) || !(pFcs->flag & SF_FF_RDMD))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);

    /* get disk parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
	bpc = pFdp->bpb.sectorsPerCluster * pFdp->bpb.bytesPerSector;
	GetMskShf(pFdp->bpb.bytesPerSector, NULL, &shf);

	*pwRlen = 0;

    /* if R/W pointer is more than file size, return OK */
	if (pFcs->rwPtr >= pFcs->de.fsize)
		goto okRet;

    /* do not read more than file size */
	if (pFcs->rwPtr + blen > pFcs->de.fsize)
		blen = (DWORD)(pFcs->de.fsize - pFcs->rwPtr);

    /* if read byte size is 0, return OK */
	if (!blen)
		goto okRet;

    /* get the cluster number ,sector number and  offset in the sector from R/W pointer */
	rv = CvFrwp(pFdp, pFcs, pFrwp);
	if (rv == RET_EOF)
		goto okRet;		/* EOF occured */
	if (rv == RET_ERR)
		return SF_err.errCd;	/* failure */

	badr = pvBuf;

    /* read the sector from the middle */
	if (pFrwp->csbp) {		/* rwPtr begins from the sector of the middle */
		w = pFdp->bpb.bytesPerSector - pFrwp->csbp;
		if (w >= blen)
			w = blen;

		if (DskRd1s(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, pFrwp->csbp, SF_BF_DATA, badr, w, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_RF;
		*pwRlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;

		badr += w;
		pFrwp->csbp = 0;
		++pFrwp->csnr;
		if (pFrwp->csnr >= pFdp->bpb.sectorsPerCluster) {
			rv = CvFrwp(pFdp, NULL, pFrwp);
			if (rv == RET_EOF)
				goto okRet;	/* EOF occured */
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */
		}
	}

    /* read from the middle of the cluster */
	w0 = blen >> shf;
	if (pFrwp->csnr && w0) {	/* rwPtr begins from the middle of the cluster */
		w1 = pFdp->bpb.sectorsPerCluster - pFrwp->csnr;
		if (w1 >= w0)
			w1 = w0;
		w = w1 << shf;

		if (DskRdSec(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, SF_BF_DATA, badr, w1) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_RF;
		*pwRlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;
		badr += w;
		pFrwp->csnr += w1;
		if (pFrwp->csnr >= pFdp->bpb.sectorsPerCluster) {
			rv = CvFrwp(pFdp, NULL, pFrwp);
			if (rv == RET_EOF)
				goto okRet;	/* EOF ocurred */
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */
		}
	}

	/* read by cluster */
	while (blen >= bpc) {
		if (DskRdSec(pFcs->dskNo, pFrwp->csnt, SF_BF_DATA, badr, pFdp->bpb.sectorsPerCluster) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_RF;
		*pwRlen += bpc;
		pFcs->rwPtr += bpc;
		if (!(blen -= bpc))
			goto okRet;

		badr += bpc;
		rv = CvFrwp(pFdp, NULL, pFrwp);
		if (rv == RET_EOF)
			goto okRet;		/* EOF occured */
		if (rv == RET_ERR)
			return SF_err.errCd;	/* failure */
	}

	/* read the rest of the sector */
	w1 = blen >> shf;
	if (w1) {
		w = w1 << shf;

		if (DskRdSec(pFcs->dskNo, pFrwp->csnt, SF_BF_DATA, badr, w1) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_RF;
		*pwRlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;

		badr += w;
		pFrwp->csnr = w1;
	}

	/* read the rest bytes */
	if (blen) {
		if (DskRd1s(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, 0, SF_BF_DATA, badr, blen, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_RF;
		*pwRlen += blen;
		pFcs->rwPtr += blen;
		blen = 0;
	}
okRet:
	/* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* Write File */
RESULT _SF_Write(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID,	/* in:File ID Number */
	PVOID pvBuf,		/* in:Buffer Write Data */
	DWORD	blen,		/* in:Write Data Size */
	PDWORD pwWlen		/* out:real size of write data */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	PBYTE badr;
	struct CVFRWP_S pFrwp[1];
	DWORD dw, dw0, dw1, rwp;
	DWORD bpc, w, w0, w1, ww[3], msk, shf;
	DWORD flag = SF_BF_DATA;
	BOOL sectorFill;

    /* check parameters */
	if ((blen && !pvBuf) || !pwWlen)
		return SetErrorInfo(SF_EC_InvalidData, SF_ET_PARA, (DWORD)-1, 0);

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (!pFcs)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* check access mode */
	if (!(pFcs->flag & SF_FF_FILE) || !(pFcs->flag & SF_FF_WRMD))
		goto accErr;

    /* get disk parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
	bpc = pFdp->bpb.sectorsPerCluster * pFdp->bpb.bytesPerSector;
	GetMskShf(bpc, &msk, &shf);
	*pwWlen = 0;

    /* check whether write data length is zero or not */
	if (blen == 0)
		goto okRet;

    /* check the number of cluster after writing */
	rwp = pFcs->rwPtr;
	dw0 = (pFcs->de.fsize + msk) >> shf;	/* the number of the current cluster */
	dw1 = (rwp + blen + msk) >> shf;		/* the number of the cluster after writing */
	if (dw1 > dw0) {
        /* if the current number is more than after writing, add clusters */
		dw1 -= dw0;
		if (pFcs->de.fsize == 0) {
            /* new cluster */
			pFrwp->ccn = 0;
		} else {
            /* add to the old cluster */
			pFcs->rwPtr = pFcs->de.fsize - 1;
			rv = CvFrwp(pFdp, pFcs, pFrwp);
			pFcs->rwPtr = rwp;
			if (rv == RET_EOF)
				goto accErr;	/* EOF occured */
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */
		}

        /* get the clusters on FAT */
		while (dw1) {
			dw0 = dw1 * bpc;
			rv = SerEmpClu(pFrwp->ccn + 1, dw0, ww, &dw, pFdp);
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */

			if (rv == RET_EOF)
				goto accErr;

			if (ww[0] == 0)
				goto accErr;	/* failure */

			if (pFrwp->ccn == 0) {	/* set a new cluster to the fatent of directory entry */
				if (AllocClu(NULL, dw, ww[2], pFdp) == RET_ERR)
					return SF_err.errCd;	/* failure */

				pFcs->de.fatent = dw;
				pFrwp->ccn = endCluNo;
			} else {		/* add to the old cluster */
				if (AllocClu(&pFrwp->ccn, dw, ww[2], pFdp) == RET_ERR)
					return SF_err.errCd;	/* failure */
			}
			dw1 -= ww[2];
		}

	}

	/* update */
	pFcs->flag |= SF_FF_WF;

    /* get mask value and shift value of the sector size */
	GetMskShf(pFdp->bpb.bytesPerSector, NULL, &shf);

    /* get the cluster , sector number, and the offset byte position from R/W pointer */
	rv = CvFrwp(pFdp, pFcs, pFrwp);
	if (rv == RET_EOF)
		goto okRet;		/* EOF occured */
	if (rv == RET_ERR)
		return SF_err.errCd;	/* failure */
	badr = pvBuf;

	/* update file size */
	rwp += blen;
	if (pFcs->de.fsize <= rwp) {
		pFcs->de.fsize = rwp;
		sectorFill = FALSE;
	} else
		sectorFill = TRUE;

	/* if R/W pointer advances more than file size, fill the cluster buffer */
	if ((rwp >> shf) < (pFcs->de.fsize >> shf))
		flag |= SF_BF_FILL;

    /* write from the middle of the sector */
	if (pFrwp->csbp) {		/* rwPtr begins from the middle of the sector */
		w = pFdp->bpb.bytesPerSector - pFrwp->csbp;
		if (w >= blen)
			w = blen;

		if (DskWr1s(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, pFrwp->csbp, flag, badr, w, TRUE) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_WF;
		*pwWlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;

		badr += w;
		pFrwp->csbp = 0;
		++pFrwp->csnr;
		if (pFrwp->csnr >= pFdp->bpb.sectorsPerCluster) {
			rv = CvFrwp(pFdp, NULL, pFrwp);
			if (rv == RET_EOF)
				goto okRet;				/* EOF occured */
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */
		}
	}

    /* write from the middle of the cluster */
	w0 = blen >> shf;
	if (pFrwp->csnr && w0) {	/* rwPtr begins from the middle of the cluster */
		w1 = pFdp->bpb.sectorsPerCluster - pFrwp->csnr;
		if (w1 >= w0)
			w1 = w0;
		w = w1 << shf;

		if (DskWrSec(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, flag, badr, w1) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_WF;
		*pwWlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;

		badr += w;
		pFrwp->csnr += w1;
		if (pFrwp->csnr >= pFdp->bpb.sectorsPerCluster) {
			rv = CvFrwp(pFdp, NULL, pFrwp);
			if (rv == RET_EOF)
				goto okRet;				/* EOF occured */
			if (rv == RET_ERR)
				return SF_err.errCd;	/* failure */
		}
	}

    /* write by a cluster */
	while (blen >= bpc) {
		if (DskWrSec(pFcs->dskNo, pFrwp->csnt, flag, badr, pFdp->bpb.sectorsPerCluster) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_WF;
		*pwWlen += bpc;
		pFcs->rwPtr += bpc;
		if (!(blen -= bpc))
			goto okRet;

		badr += bpc;
		rv = CvFrwp(pFdp, NULL, pFrwp);
		if (rv == RET_EOF)
			goto okRet;				/* EOF occured */
		if (rv == RET_ERR)
			return SF_err.errCd;	/* failure */
	}

    /* write the rest of the sector */
	w1 = blen >> shf;
	if (w1) {
		w = w1 << shf;

		if (DskWrSec(pFcs->dskNo, pFrwp->csnt, flag, badr, w1) == RET_ERR)
			return SF_err.errCd;	/* error occured */

		pFcs->flag |= SF_FF_WF;
		*pwWlen += w;
		pFcs->rwPtr += w;
		if (!(blen -= w))
			goto okRet;

		badr += w;
		pFrwp->csnr = w1;
	}

    /* write the rest bytes */
	if (blen) {
		if (DskWr1s(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, 0, flag, badr, blen, sectorFill) == RET_ERR)
			return SF_err.errCd;

		pFcs->flag |= SF_FF_WF;
		*pwWlen += blen;
		pFcs->rwPtr += blen;
		blen = 0;
	}
okRet:
    /* set an directory entry update flag */
	pFcs->flag |= SF_FF_DEUPD;

    /* update File Date */
	dw = _CLK_ReadFileDT();
	pFcs->de.upTime = (WORD)dw;
	pFcs->de.upDate = (WORD)(dw >> 16);

	/* アーカイブビットセット */
    /* set an archive bit */
	pFcs->de.atr |= SF_AT_ARC;

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

    /* set error information */
accErr:
	return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);
}


/* Move file pointer */
RESULT _SF_Seek(			/* return : 0=success,more than 1=error code */
	INT32	i32FileID,		/* in:File ID Number */
	SF_BYTE	origin,			/* in:Seek Origin */
	INT32	offset,			/* in:Seek Ofset  */
	PDWORD pdwRwPtr			/* out:File Pointer After Moving */
)
{
	SF_pFcs_t pFcs;
	DWORD rwp;

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (!pFcs)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* cannot seek except files */
	if (!(pFcs->flag & SF_FF_FILE))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);

    /* update R/W pointer */
	rwp = pFcs->rwPtr;
	switch (origin) {
	case SF_SEEK_SET:	/* make the top of file as offset 0 */
		rwp = offset;
		break;
	case SF_SEEK_CUR:	/* make the cuurent position of file as offset 0 */
		rwp += offset;
		break;
	case SF_SEEK_END:	/* make the end of file as offset 0 */
		rwp = pFcs->de.fsize + offset;
		break;
	default:
		return SetErrorInfo(SF_EC_InvalidFunctionCode, SF_ET_GENE, (DWORD)-1, 0);
	}

    /* cannot move toward minus direction */
	if ((INT32)rwp < 0)
		rwp = 0;

    /* save R/W pointer */
	pFcs->rwPtr = rwp;

    /* set R/W pointer */
	if (pdwRwPtr)
		*pdwRwPtr = rwp;

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* Truncate File */
RESULT _SF_Truncate(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID		/* in:File ID Number */
)
{
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	DWORD dw;

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (!pFcs)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* check access mode */
	if (!(pFcs->flag & SF_FF_FILE) || !(pFcs->flag & SF_FF_WRMD))
		goto accErr;

    /* get disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* release cluster from FAT */
	if (ReleaseClu(pFcs->de.fatent, pFdp, FALSE) == RET_ERR)
		return SF_err.errCd;

    /* clear the start cluster number and file size */
	pFcs->de.fatent = 0;
	pFcs->de.fsize = 0;

    /* clear R/W pointer */
	pFcs->rwPtr = 0;

    /* updated */
	pFcs->flag |= SF_FF_WF;
	pFcs->flag |= SF_FF_DEUPD;

    /* update File Date */
	dw = _CLK_ReadFileDT();
	pFcs->de.upTime = (WORD)dw;
	pFcs->de.upDate = (WORD)(dw >> 16);

    /* set archive bit */
	pFcs->de.atr |= SF_AT_ARC;

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

accErr:
    /* set error information */
	return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);
}


/* Read Directory Entry */
RESULT _SF_ReadDir(		/* return	: 0=success,more than 1=error code */
	INT32  i32FileID,	/* in:File ID Number */
	PVOID pvBuf,		/* out:Buffer Address for directory Entry */
	DWORD	n,			/* in:a number of directory entry */
	PDWORD pwRn			/* out:a real number of directory entry */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	DWORD i;
	SF_pDirEnt_t pde;
	struct CVFRWP_S pFrwp[1];
	SF_BYTE wkDir[32];
	SF_dirEnt_t dirEnt;
	BOOL vfat_flag;

    /* check parameters */
	if ((n && !pvBuf) || !pwRn)
		return SetErrorInfo(SF_EC_InvalidData, SF_ET_PARA, (DWORD)-1, 0);

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (!pFcs)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* if access mode is not DIR Read, return error */
	if (!(pFcs->flag & SF_FF_DIR) || !(pFcs->flag & SF_FF_RDMD))
		return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, pFcs->dskNo, 0);

    /* get disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;
	*pwRn = 0;
	pde = pvBuf;

	memset(&dirEnt, 0, sizeof(dirEnt));

    /* loop by the number of the entries */
	vfat_flag = FALSE;
	for (i = 0; i < n; pFcs->rwPtr += SF_DIRENT_SIZ) {
		rv = CvFrwp(pFdp, pFcs, pFrwp);
		if (rv == RET_EOF)
			break;		/* EOF occured */

		if (rv == RET_ERR)
			return SF_err.errCd;	/* failure */

        /* read disk entry */
		if (DskRd1s(pFcs->dskNo, pFrwp->csnt + pFrwp->csnr, pFrwp->csbp, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
			return SF_err.errCd;

        /* if the top byte is 0, break */
		if (wkDir[0] == 0)
			break;

        /* if the top byte is 0xE5, skip (already removed) */
		if (wkDir[0] == 0xE5)
			continue;

        /* check attribute */
		if (wkDir[0x0B] == ATTR_VFAT) {
            /* Get VFAT entry */
			GetVfatEntry((SF_pDirEntEx_t)wkDir, &dirEnt);
			vfat_flag = TRUE;
			continue;
		} else if (wkDir[0x0B] & SF_AT_VOL) {
            /* skip volume label */
			continue;
		}

        /* if checksum matches, VFAT file name is vaild */
		if (!vfat_flag || CheckSum(wkDir) != dirEnt.checkSum)
			_SF_CvFname(wkDir, wkDir[0x0B], dirEnt.lfname);

		vfat_flag = FALSE;

        /* get directory entry */
		LoadDirEnt(pde, wkDir);

        /* set long file name */
		strcpy(pde->lfname, dirEnt.lfname);

		++pde;
		++(*pwRn);

		i++;
	}

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* create a period delimited string(name.ext) from 11 characters fixed filename string */
void _SF_CvFname(		/* return : nothing */
	PBYTE	p,			/* in:11 charcter fixed file name string */
	DWORD	atr,		/* in:file attribute(if not know, set all zero) */
	PBYTE	fnp         /* out:new file name string */
)
{
	INT i;

	if (p != NULL && p[0] != '\0' && p[0] != 0xE5) {
		if (atr & SF_AT_VOL) {	/* ボリューム・ラベル */
			for (i = 0; i < 11 && p[i] != ' '; i++)
				*fnp++ = p[i];
		} else {
			if (p[0] == '.') {
				*fnp++ = '.';
				if (p[1] == '.')
					*fnp++ = '.';
			} else {
				for (i = 0; i < 8 && p[i] != ' '; i++)
					*fnp++ = p[i];

				if (p[8] != ' ' && p[9] != ' ' && p[10] != ' ')
					*fnp++ = '.';

				for (i = 8; i < 11 && p[i] != ' '; i++)
					*fnp++ = p[i];
			}
		}
	}

	*fnp = '\0';
}


/* get file name from directory entry */
void _SF_DirFn(			    /* return : nothing */
	SF_pDirEnt_t pDirEnt,	/* in:buffer address for directory entry */
	PBYTE pFileName         /* out:file name */
)
{
	_SF_CvFname(pDirEnt->fname, pDirEnt->atr, pFileName);
}


/* Create Date String from WORD Value for Date used in Directory Entry */
/* The Format of Date String is "YY-MM-DD"	10 characters fixed string */
PSZ _SF_CvFdate(			/* return : date string */
	WORD Date				/* in:WORD Value for date used in Directory Entry */
)
{
	WORD w;
	PBYTE p;
	static SF_BYTE ymd[11];
	p = ymd;

	/* Year */
	w = (Date >> 9) + 1980;
	*p++ = '0' + w / 1000;
	w %= 1000;
	*p++ = '0' + w / 100;
	w %= 100;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p++ = '-';

	/* Month */
	w = (Date >> 5) & 0xF;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p++ = '-';

	/* Day */
	w = Date & 0x1F;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p = '\0';
	return ymd;
}


/* Create Time String from WORD Value for Time used in Directory Entry */
/* The Format of Time String is "HH:MM:SS"	8 characters fixed string */
PSZ _SF_CvFtime(			/* return : time string */
	WORD Time				/* in:WORD Value for Time used in Directory Entry */
)
{
	WORD w;
	PBYTE p;
	static SF_BYTE hms[9];
	p = hms;

	/* Hour */
	w = Time >> 11;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p++ = ':';

	/* Minute */
	w = (Time >> 5) & 0x3F;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p++ = ':';

	/* Second */
	w = (Time & 0x1F) << 1;
	*p++ = '0' + w / 10;
	*p++ = '0' + w % 10;
	*p = '\0';
	return hms;
}


/* Create Date String from Directory Entry */
/* The Format of Time String is "YY-MM-DD	 HH:MM"	15 characters fixed string */
PSZ _SF_DirTm(			    /* return : date and time string */
	SF_pDirEnt_t pDirEnt	/* in:Buffer Address for directory entry */
)
{
	static SF_BYTE dt[16];

	dt[0] = '\0';
	if (!pDirEnt)
		return dt;

	memcpy(dt, _SF_CvFdate(pDirEnt->upDate) + 2, 8);
	dt[8] = dt[9] = ' ';
	memcpy(dt + 10, _SF_CvFtime(pDirEnt->upTime), 5);
	dt[15] = '\0';

	return dt;
}


/* Close all open file */
RESULT _SF_CloseAll(		/* return : 0=success,more than 1=error code */
	PINT32 pi32FileID		/* out:Error File ID Number when closing */
)
{
	INT32 fid;
	RESULT res;
	INT i;
	SF_pFcs_t pFcs;

	pFcs = DSKIO_fcs;
	for (i = 0; i < DSKIO_FILES; ++i) {
		if (pFcs->dskNo != SF_FF_EMPTY) {
			fid = pFcs->fileID;
			res = _SF_Close(fid);	/* close the file */
			if (res) {		/* error occured */
				if (pi32FileID)
					*pi32FileID = fid;
				return res;
			}
		}
		++pFcs;
	}

	/* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}


/* Initialise FAT and register bad clusters */
static BOOL _InitFAT_and_EraseDATA(PVOID devInfo)
{
	SF_BYTE *fat, *cluster, *p;
	DWORD i, j;
	int	secNo, errcnt;

	if (! _DSKIO_DummyWriteFAT(devInfo))
		return FALSE;

	if ((fat = malloc(SECTOR_SIZE * FAT_SECTOR_NUM)) == NULL)
		return	FALSE;
	if ((cluster = malloc(SECTOR_SIZE * SECTOR_PER_CLUSTER)) == NULL) {
		free(fat);
		return FALSE;
	}

    /* clear FAT Data buffer to zero */
	memset(fat, 0, SECTOR_SIZE * FAT_SECTOR_NUM);

    /* write the headder of FAT */
	memcpy(fat, "\xf8\xff\xff\xff", 4);

    /* save the top of FAT */
	p = fat + 4;

	errcnt = 0;

	printf("--[FORMAT START]------------------\n");
	/* loop by the number of all clusters */
	for (i = 0; i < TOTAL_CLUSTER; i++, p += 2) {
		secNo = DATA_BLOCK_OFFSET + i * SECTOR_PER_CLUSTER;
		/* Erase Block */
		if (! _DSKIO_EraseCluster(devInfo, secNo, cluster)) {
			memcpy(p, "\xf7\xff", 2);
			errcnt++;
		}
		if ((i + 1) % 10 == 0)
			printf("--[%d-%d Cluster end]--\n", i - 9, i);
	}

	printf("--[FORMAT END]------------------\n");
	printf("ERROR CLUSTER=%d\n", errcnt);

    /* write to FAT */
	for (i = 0; i < FAT_NUM; i++) {
		for (j = 0; j < FAT_SECTOR_NUM; j++) {
			if (!_DSKIO_WriteBlock(devInfo
					, FAT1_BLOCK_OFFSET + i * (FAT2_BLOCK_OFFSET - FAT1_BLOCK_OFFSET) + j, 1
					, fat + j * SECTOR_SIZE, 0)) {
				free(fat);
				free(cluster);
				return FALSE;
			}
		}
	}
	free(fat);
	free(cluster);

	return TRUE;
}


/* initialise the root directory */
static BOOL _InitRootDir(PVOID devInfo, DWORD blockNo, DWORD sectors)
{
	static	SF_BYTE dir[SF_MAXSECBYTE];
	DWORD i;

    /* clear directory date buffer to zero */
	memset(dir, 0, sizeof(dir));

    /* write to the disk by the number of the sectors of the root directory */
	for (i = 0; i < sectors; i++) {
		if (!_DSKIO_WriteBlock(devInfo, blockNo++, 1, dir, 0))
			return FALSE;
	}

	return TRUE;
}


/* Format Disk */
RESULT _SF_Format(PVOID devInfo)
{
	SF_bpb_t bpb;

	/* Initialise Disk */
	_DSKIO_init();

    /* Initialise BPB */
	bpb.bytesPerSector = SECTOR_SIZE;		/* byte / sector */
	bpb.sectorsPerCluster = SECTOR_PER_CLUSTER;		/* sector / cluster */
	bpb.reservedSectors = 1;				/* the number of reserved sectors */
	bpb.numberOfFATs = FAT_NUM;				/* the number of FAT */
	bpb.rootEntries = 512;					/* the number of the root directory entries */
	bpb.totalSectors = TOTAL_CLUSTER * SECTOR_PER_CLUSTER;	/* the number of all sectors on the disk */
	bpb.mediaDescriptor = 0xf8;				/* media discriptor */
	bpb.sectorsPerFAT = FAT_SECTOR_NUM;		/* sector / FAT */
	if(!_DSKIO_writeBPB(devInfo, &bpb))
		return SF_EC_WriteFault;

	/* Initialise FAT */
	if (! _InitFAT_and_EraseDATA(devInfo))
		return SF_EC_WriteFault;

	/* Initialise the root directory */
	if(!_InitRootDir(devInfo, ROOT_BLOCK_OFFSET, ROOT_SECTOR_NUM))
		return SF_EC_WriteFault;

    /* flush DISK I/O buffer */
	if(!_DSKIO_FlashDiskBuf(devInfo))
		return SF_EC_WriteFault;

	return 0;
}

/* Flush File Buffer（Device Information） */
RESULT _SF_Sync(			/* return : 0=success,more than 1=error code */
	PSZ pDevName			/* in:device name */
)
{
	DWORD dskNo;

    /* get disk number */
	if ((dskNo = GetDskNo(pDevName, NULL)) == (DWORD)-1)
		return SF_EC_InvalidDrive;

	return _SF_Sync3(dskNo);
}

/* Flush File Buffer（File ID Number） */
RESULT _SF_Sync2(		/* return : 0=success,more than 1=error code */
	INT32 i32FileID				/* in:File ID Number */
)
{
	SF_pFcs_t pFcs;

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SF_EC_InvalidHandle;

	return _SF_Sync3(pFcs->dskNo);
}

/* check EOF */
RESULT _SF_Eof(			/* return : 0=success,more than 1=error code */
	INT32 i32FileID,			/* in:File ID Number */
	PBOOL pEof					/* out:TRUE=EOF FALSE=Not EOF */
)
{
	SF_pFcs_t pFcs;

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* if R/W pointer is more than file size, Set EOF */
	*pEof = (pFcs->rwPtr >= pFcs->de.fsize) ? TRUE : FALSE;

	return 0;
}

/* Get File Status */
RESULT _SF_Stat(			/* return : 0=success,more than 1=error code,-1=Argument Error */
	PSZ pszPath,			/* in:File Path    */
	SF_stat_t *pStat		/* out:File Status */
)
{
	INT rv;
	SF_fcs_t pFcs[1];

    /* search file name */
	rv = DirSer(pszPath, 0, pFcs, NULL, NULL);
	if (rv == RET_ERR) {
		if (dirEntExist) {	/* entry exists */
			if (pFcs->de.atr & SF_AT_DIR) {	/* sub directory entry */
				goto skip1;
			}
		}
		return SF_err.errCd;	/* failure */
	}

skip1:
    /* return file status */
	if (pStat) {
		pStat->st_attr = pFcs->de.atr;
		pStat->st_size = pFcs->de.fsize;
		pStat->st_time = _CLK_ConvertFileTime(pFcs->de.upDate, pFcs->de.upTime);
	}

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */
}

/* Read Volume Label */
RESULT _SF_ReadVolumeLabel(	/* return : 0=success,more than 1=error code */
	INT32 i32FileID,		/* in:File ID Number */
	PSZ pVolumeLabel		/* out:Volume Label */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	SF_fcs_t fcsLabel;
	SF_BYTE name[12];

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* check access mode */
	if (!(pFcs->flag & SF_FF_DEVICE) || !(pFcs->flag & SF_FF_RDMD))
		goto accErr;

	*pVolumeLabel = '\0';

    /* get disl control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* search volume label */
	rv = SearchVolumeLabal(pFdp, &fcsLabel);
	if (rv == RET_OK) {
		 _SF_DirFn(&fcsLabel.de, name);
		strncpy(pVolumeLabel, name, 11);
	} else if (rv == RET_EOF)
		;
	else
		return SF_err.errCd;

	return 0;

accErr:
    /* set error information */
	return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, 0, 0);
}

/* Write Volume Label */
RESULT _SF_WriteVolumeLabel(	/* return : 0=success,more than 1=error code */
	INT32 i32FileID,			/* in:File ID Number */
	PSZ pVolumeLabel			/* in:Volume Label   */
)
{
	INT rv;
	SF_pFatDskPara_t pFdp;
	SF_pFcs_t pFcs;
	SF_fcs_t fcsLabel;
	DWORD dw;
	int len;
	SF_BYTE wkDir[SF_DIRENT_SIZ];

    /* get file control parameter */
	pFcs = FcsSerID(i32FileID, NULL);
	if (pFcs == NULL)
		return SetErrorInfo(SF_EC_InvalidHandle, SF_ET_GENE, (DWORD)-1, 0);

    /* check access mode */
	if (!(pFcs->flag & SF_FF_DEVICE) || !(pFcs->flag & SF_FF_WRMD))
		goto accErr;

    /* get disk control parameter */
	pFdp = DSKIO_dskTbl[pFcs->dskNo].pDskPara;

    /* search volume label */
	rv = SearchVolumeLabal(pFdp, &fcsLabel);
	if (rv == RET_OK) {		/* label exists */
		;
	} else if (rv == RET_EOF) {
        /* create a new directory entry */
		if (!fstDirEmpSno)	/* directory area is full */
			goto accErr;	/* the root directory */

		/* create a new directory entry */
		memset(fcsLabel.de.rev, 0, 10);
		fcsLabel.dirFxxxluNo = 0;
		fcsLabel.dirSecNo = fstDirEmpSno;
		fcsLabel.dirSbpos = fstDirEmpSbp;
	} else
		return SF_err.errCd;	/* failure */

	/* make volume label */
	len = strlen(pVolumeLabel);
	memset(fcsLabel.de.fname, ' ', sizeof(fcsLabel.de.fname));
	memcpy(fcsLabel.de.fname, pVolumeLabel, len < sizeof(fcsLabel.de.fname) ? len : sizeof(fcsLabel.de.fname));
	fcsLabel.de.atr = SF_AT_VOL;
	fcsLabel.de.fatent = 0;
	fcsLabel.de.fsize = 0;
	dw = _CLK_ReadFileDT();
	fcsLabel.de.upTime = (WORD)dw;
	fcsLabel.de.upDate = (WORD)(dw >> 16);

    /* write a new op updated directory entry */
	MkDirEnt(&fcsLabel.de, wkDir);
	if (DskWr1s(0, fcsLabel.dirSecNo, fcsLabel.dirSbpos, SF_BF_DIR, wkDir, SF_DIRENT_SIZ, TRUE) == RET_ERR)
		return SF_err.errCd;	/* error occured */

    /* clear error information */
	ClearErrorInfo();

	return 0;			/* success */

accErr:
    /* set error information */
	return SetErrorInfo(SF_EC_AccessDenied, SF_ET_GENE, 0, 0);
}

/* Get FAT WRITE COUNTER */
RESULT _SF_GetFatCount(void)
{
	return	gFatWrtCount;
}

/* RESET FAT WRITE COUNTER */
void	_SF_ResetFatCount(void)
{
	gFatWrtCount = 0;
}
