/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Common defines                            *
**********************************************************/

#ifndef __SF_DISKIO_H__
#define __SF_DISKIO_H__

#include "sf_define.h"
#include "sf_faterr.h"

#pragma pack(1)

typedef struct {
	INT8 bpbBytesPerSec[2];		/* bytes per sector */
	INT8 bpbSecPerClust;		/* sectors per cluster */
	INT8 bpbResSectors[2];		/* number of reserved sectors */
	INT8 bpbFATs;				/* number of FATs */
	INT8 bpbRootDirEnts[2];		/* number of root directory entries */
	INT8 bpbSectors[2];			/* total number of sectors */
	INT8 bpbMedia;				/* media descriptor */
	INT8 bpbFATsecs[2];			/* number of sectors per FAT */
	INT8 bpbSecPerTrack[2];		/* sectors per track */
	INT8 bpbHeads[2];			/* number of heads */
	INT8 bpbHiddenSecs[4];		/* number of hidden sectors */
	INT8 bpbHugeSectors[4];		/* # of sectors if bpbSectors == 0 */
} SF_bpb50_t;

/* Structure of Boot Sector */
typedef struct {
	SF_BYTE	bsJump[3];			/* jump inst E9xxxx or EBxx90 */
	INT8	bsOemName[8];		/* OEM name and version */
	INT8	bsBPB[25];			/* BIOS parameter block */
	INT8	bsExt[26];			/* Bootsector Extension */
	INT8	bsBootCode[448];	/* pad so structure is 512b */
	SF_BYTE	bsBootSectSig0;
	SF_BYTE	bsBootSectSig1;
} SF_bootsector_t;

#pragma pack()

/* Parameter Structure For Disk IN/OUT Routine */
typedef struct {			/* following R,W in [] assignes the data direction for Disk IN/OUT Routine. */
	PVOID	devInfo;		/* [R] Pointer of Device Information */
	DWORD	validSector;	/* the Number of Valid Sector        */
	RESULT	errCd;			/* [W] result(defined in sf_faterr.h */
	DWORD	dwSecLen;		/* [R/W] length of the sector(byte in IOCTL) */
	PVOID	pTransAdr;		/* [R] data transfered address */
	DWORD	dwStaSecNo;		/* [R] the number of the start sector(LSN) */
} DSKIO_para_t;

/* Sector Buffer */
typedef SF_BYTE SF_sector_buf[SECTOR_SIZE];

/* BPB Buffer */
typedef struct {
	SF_BYTE	validFlag[BPB_SECTOR_NUM];
	BOOL	writeFlag;
	SF_sector_buf	buf[BPB_SECTOR_NUM];
} SF_bpb_buf_t;

/* FAT Buffer */
typedef struct {
	SF_BYTE	validFlag[FAT_SECTOR_NUM];
	BOOL	writeFlag;
#ifdef CYCLIC_FAT
	BOOL	readFlag;
	DWORD	fatCounter;
#endif
	SF_sector_buf	buf[FAT_SECTOR_NUM];
} SF_fat_buf_t;

/* RootDirectory Buffer */
typedef struct {
	SF_BYTE	validFlag[ROOT_SECTOR_NUM];
	BOOL	writeFlag;
	SF_sector_buf	buf[ROOT_SECTOR_NUM];
} SF_root_buf_t;

/* Cluster Buffer */
typedef struct {
	DWORD	clusterNo;
	DWORD	useSector;
	DWORD	accessCounter;
	BOOL	writeFlag;
	SF_BYTE	validFlag[SECTOR_PER_CLUSTER];
	SF_sector_buf	buf[SECTOR_PER_CLUSTER];
} SF_cluster_buf_t;

/* Disk Buffer */
typedef struct {
	SF_bpb_buf_t	bpbBuf;
	SF_fat_buf_t	fat1Buf;
	SF_fat_buf_t	fat2Buf;
	SF_root_buf_t	rootBuf;
	SF_cluster_buf_t clusterBuf[CLUSTER_BUF_NUM];
} SF_disk_buf_t;

typedef DSKIO_para_t *DSKIO_pPara_t;

/* Initialise Disk buffer */
extern void _DSKIO_InitDiskBuf();

/* Initialise Disk */
extern BOOL _DSKIO_init(		/* return : TRUE=OK,FALSE=Error */
	    VOID					/* No Arguments */
);

/* Set BPB */
extern BOOL _DSKIO_setBPB(		/* return : TRUE=OK,FALSE=Error       */
	    DSKIO_pPara_t pIOpara	/* Pointer of the Parameter Structure */
);

extern BOOL _DSKIO_writeBPB(PVOID devInfo, SF_bpb_t *bpb);

/* Read Disk */
extern BOOL _DSKIO_read(		/* return : TRUE=OK,FALSE=Error       */
	    DSKIO_pPara_t pIOpara	/* Pointer of the Parameter Structure */
);

/* Write Disk */
extern BOOL _DSKIO_write(		/* return : TRUE=OK,FALSE=Error       */
	    DSKIO_pPara_t pIOpara	/* Pointer of the Parameter Structure */
);

extern BOOL _DSKIO_ReadSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData);
extern BOOL _DSKIO_WriteSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData);
extern BOOL _DSKIO_ReadBlock(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID bufp);
extern BOOL _DSKIO_WriteBlock(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID bufp, DWORD validSector);
extern BOOL _DSKIO_FlashDiskBuf(PVOID devInfo);
extern BOOL _DSKIO_EraseCluster(PVOID devInfo, DWORD blockNo, PVOID pData);
extern BOOL _DSKIO_ReadCluster(PVOID devInfo, DWORD blockNo, PVOID pData);
extern BOOL _DSKIO_WriteCluster(PVOID devInfo, DWORD blockNo, PVOID pData);
extern BOOL _DSKIO_DummyWriteFAT(PVOID devInfo);

extern	int	gFatWrtCount;

#endif /* __SF_DISKIO_H__ */
