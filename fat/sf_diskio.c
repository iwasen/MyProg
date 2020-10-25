/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Disk I/O functions                        *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#include "scsiLib.h"
#else
#include <io.h>
#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <fcntl.h>
#include <sys/stat.h>
#include "sf_define.h"
#include "sf_fat.h"
#include "sf_sub.h"
#include "sf_diskio.h"

/* type of disk area */
#define BLOCK_TYPE_BPB	0
#define BLOCK_TYPE_FAT1	1
#define BLOCK_TYPE_FAT2	2
#define BLOCK_TYPE_ROOT	3
#define BLOCK_TYPE_DATA	4

#define CHECK_DATA	0xaa
#define	INIT_DATA	0xff

int	gFatWrtCount;

/* disk I/O Buffer */
static SF_disk_buf_t *diskBuf;

/* Buffer Access Counter */
static DWORD accessCounter = 0;

/* File Handle for Pseudo FAT System (For Debug) */
#ifdef SF_DEBUG_WINDOWS
static int file_handle;
#endif

/*-------------------------------------------------------------------*/

/* Initialise Drive */
BOOL _DSKIO_init(	/* return : TRUE=OK, FALSE=Error */
	VOID			/* argument is nothing           */
)
{
#ifdef SF_DEBUG_WINDOWS
	if (file_handle)
		close(file_handle);

	file_handle = open("c:\\fat.dat", O_CREAT | O_RDWR | O_BINARY, S_IWRITE);
#endif

	if (diskBuf == NULL) {
		if ((diskBuf = malloc(sizeof(SF_disk_buf_t))) == NULL)
			return FALSE;
	}

	memset(diskBuf, 0, sizeof(SF_disk_buf_t));

	return TRUE; /* success */
}

/* Setting of BPB */
BOOL _DSKIO_setBPB(				/* return : TRUE=OK,FALSE=Error       */
	DSKIO_pPara_t pIOpara		/* Pointer of the Parameter Structure */
)
{
	BOOL result;
	PBYTE pb;
	SF_bpb_t *pBpb;
	static	SF_BYTE buf1sec[SF_MAXSECBYTE];

    /* read the track0, head0, sector1 of the FD */
	result = _DSKIO_ReadBlock(pIOpara->devInfo, 0, 1, buf1sec);
	if (!result) { /* read error? */
		pIOpara->errCd = SF_EC_ReadFault;
		return FALSE; /* ERROR */
	}

    /* copy the BPB of drive to the variables */
	pBpb = pIOpara->pTransAdr;
	pb = buf1sec + 0x0B;
	pBpb->bytesPerSector = BS_wPeek(pb);
	pb += sizeof(WORD);
	pBpb->sectorsPerCluster = *pb++;
	pBpb->reservedSectors   = BS_wPeek(pb);
	pb += sizeof(WORD);
	pBpb->numberOfFATs      = *pb++;
	pBpb->rootEntries       = BS_wPeek(pb);
	pb += sizeof(WORD);
	pBpb->totalSectors      = BS_wPeek(pb);
	pb += sizeof(WORD);
	pBpb->mediaDescriptor   = *pb++;
	pBpb->sectorsPerFAT     = BS_wPeek(pb);
	pb += sizeof(WORD);
/*	spt = BS_wPeek(pb); */
	pb += sizeof(WORD);
/*	hds = BS_wPeek(pb); */
	pb += sizeof(WORD);
/*	hid = BS_dwPeek(pb); */
	pb += sizeof(DWORD);
	if( pBpb->totalSectors==0 ) {
		pBpb->totalSectors = BS_dwPeek(pb);
		pb += sizeof(DWORD);
	}
	pBpb->volumeSerialNumber = 1;
	memcpy(pBpb->volumeLabel, SF_volLb_NO_NAME, 11);

    /* check the read data whether it is BPB or not */
	if (buf1sec[0] != 0xE9) { /* head of data is not 0xE9? */
		if (buf1sec[0] != 0xEB || buf1sec[2] != 0x90) /* head of data is not EB, XX, 90 ? */
			goto errNoDosDisk;
	}
	if (pBpb->sectorsPerCluster == 0)	/* sector per Cluster is 0 ? */
		goto errNoDosDisk;
	if (pBpb->numberOfFATs == 0)		/* number of FAT is 0 ? */
		goto errNoDosDisk;
    if (pBpb->mediaDescriptor != 0xF0) { /* media discriptor is not 0xF0 ? */
		if (pBpb->mediaDescriptor < 0xF8)	/* media discriptor is less than 0xF8 ? */
			goto errNoDosDisk;
	}

	if (buf1sec[0x26] == 0x29) { /* Extended boot record signature ? */
        /* copy Volume Serial Number */
		pBpb->volumeSerialNumber = BS_dwPeek(buf1sec + 0x27);

        /* copy Volume Label */
		memcpy(pBpb->volumeLabel, buf1sec + 0x2B, 11);
	}

	pIOpara->errCd = SF_EC_Ok;
	return TRUE;	/* OK */

errNoDosDisk:
	pIOpara->errCd = SF_EC_NotDOSdisk;
	return FALSE;	/* ERROR */
}

/* write BPB */
BOOL _DSKIO_writeBPB(PVOID devInfo, SF_bpb_t *bpb)
{
	SF_bootsector_t *pBootSector;
	SF_bpb50_t *pBpb;
	static	SF_BYTE buf1sec[SF_MAXSECBYTE];

	/* Clear Sector Buffer */
	memset(buf1sec, 0, sizeof(buf1sec));
	pBootSector = (SF_bootsector_t *)buf1sec;

	/* set boot record and OEM-ID */
	pBootSector->bsJump[0] = 0xeb;
	pBootSector->bsJump[1] = 0x3e;
	pBootSector->bsJump[2] = 0x90;
	memcpy(pBootSector->bsOemName, "SFDOS16 ", 8);

	/* set BPB data */
	pBpb = (SF_bpb50_t *)pBootSector->bsBPB;
	BS_wPoke(pBpb->bpbBytesPerSec, bpb->bytesPerSector);
	pBpb->bpbSecPerClust = bpb->sectorsPerCluster;
	BS_wPoke(pBpb->bpbResSectors, bpb->reservedSectors);
	pBpb->bpbFATs = bpb->numberOfFATs;
	BS_wPoke(pBpb->bpbRootDirEnts, bpb->rootEntries);
	pBpb->bpbMedia = bpb->mediaDescriptor;
	BS_wPoke(pBpb->bpbFATsecs, bpb->sectorsPerFAT);
	BS_dwPoke(pBpb->bpbHugeSectors, bpb->totalSectors);

    /* write BPB data to the disk */
	_DSKIO_WriteBlock(devInfo, 0, 1, buf1sec, FALSE);

	return TRUE;
}

/* adjust block number for SSMSD */
static DWORD _AdjustBlockNo(DWORD blockNo)
{
	if (blockNo >= 13)			/* data Area           */
		blockNo += DATA_BLOCK_OFFSET - 13;
	else if (blockNo >= 11)		/* root directory Area */
		blockNo += ROOT_BLOCK_OFFSET - 11;
	else if (blockNo >= 6)		/* FAT2 Area           */
		blockNo += FAT2_BLOCK_OFFSET - 6;
	else if (blockNo >= 1)		/* FAT1 Area           */
		blockNo += FAT1_BLOCK_OFFSET - 1;

	return blockNo;
}

/* read from disk */
BOOL _DSKIO_read( /* return : TRUE=OK,FALSE=Error */
	DSKIO_pPara_t pIOpara  /* Pointer of Parameter Structure */
)
{
	int n;

	n = pIOpara->dwSecLen;
	pIOpara->dwSecLen = 0;

	if (_DSKIO_ReadBlock(pIOpara->devInfo, _AdjustBlockNo(pIOpara->dwStaSecNo), n, pIOpara->pTransAdr)) {
		pIOpara->errCd = SF_EC_Ok;
		return TRUE;	/* OK */
	} else {
		pIOpara->errCd = SF_EC_ReadFault;
		return FALSE;	/* ERROR */
	}
}

/* write to disk */
BOOL _DSKIO_write( /* return : TRUE=OK,FALSE=Error */
	DSKIO_pPara_t pIOpara  /* Pointer of Parameter Structure */
)
{
	int n;

	n = pIOpara->dwSecLen;
	pIOpara->dwSecLen = 0;

	if (_DSKIO_WriteBlock(pIOpara->devInfo, _AdjustBlockNo(pIOpara->dwStaSecNo), n, pIOpara->pTransAdr, pIOpara->validSector)) {
		pIOpara->errCd = SF_EC_Ok;
		return TRUE;	/* OK */
	} else {
		pIOpara->errCd = SF_EC_WriteFault;
		return FALSE;	/* ERROR */
	}
}

/* Get Block Type */
static int _GetBlockType(DWORD blockNo)
{
#ifdef CYCLIC_FAT
	if (blockNo >= DATA_BLOCK_OFFSET)
		return BLOCK_TYPE_DATA;
	else if (blockNo >= FAT2_BLOCK_OFFSET)
		return BLOCK_TYPE_FAT2;
	else if (blockNo >= FAT1_BLOCK_OFFSET)
		return BLOCK_TYPE_FAT1;
	else if (blockNo >= ROOT_BLOCK_OFFSET)
		return BLOCK_TYPE_ROOT;
	else
		return BLOCK_TYPE_BPB;
#else
	if (blockNo >= DATA_BLOCK_OFFSET)
		return BLOCK_TYPE_DATA;
	else if (blockNo >= ROOT_BLOCK_OFFSET)
		return BLOCK_TYPE_ROOT;
	else if (blockNo >= FAT2_BLOCK_OFFSET)
		return BLOCK_TYPE_FAT2;
	else if (blockNo >= FAT1_BLOCK_OFFSET)
		return BLOCK_TYPE_FAT1;
	else
		return BLOCK_TYPE_BPB;
#endif
}

/* Read Sector Data */
BOOL _DSKIO_ReadSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
#ifdef SF_DEBUG_WINDOWS
	int size, n;

	_PrintDebugMessage(5, "***[Disk Read ]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);
/*	printf("***[Disk Read ]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);*/

	size = SECTOR_SIZE * blockNum;
	lseek(file_handle, SECTOR_SIZE * blockNo, SEEK_SET);
	n = read(file_handle, pData, size);

	if (n < 0)
		return FALSE;

	if (n >= 0 && n < size)
		memset((PBYTE)pData + n, 0xff, size - n);

	return TRUE;
#else
	STATUS status;

	_PrintDebugMessage(5, "***[Disk Read ]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);
	status = scsiRdSecs((SCSI_BLK_DEV *)devInfo, blockNo, blockNum, pData);
#ifdef SF_DEBUG_MSG
	printf("***[Disk Read ]:status=%d, blockNo=%d, blockNum=%d\n", status, blockNo, blockNum);
#endif /* SF_DEBUG_MSG */
	if (status != OK)
		printf("--[ERROR(Read) %d Sector]-----------\n", blockNo);

	return status == OK ? TRUE : FALSE;
#endif	/* SF_DEBUG_WINDOWS */
}

/* Write Sector Data */
BOOL _DSKIO_WriteSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
#ifdef SF_DEBUG_WINDOWS
	int size, n, i;
	char buf[SECTOR_SIZE * SECTOR_PER_CLUSTER];

	_PrintDebugMessage(5, "***[Disk Write]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);
/*	printf("***[Disk Write]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);*/

	if (blockNo % SECTOR_PER_CLUSTER == 0) {
		memset(buf, 0xff, SECTOR_SIZE * SECTOR_PER_CLUSTER);
		lseek(file_handle, blockNo * SECTOR_SIZE, SEEK_SET);
		n = write(file_handle, buf, SECTOR_SIZE * SECTOR_PER_CLUSTER);
	}	

	size = SECTOR_SIZE * blockNum;

	lseek(file_handle, blockNo * SECTOR_SIZE, SEEK_SET);
	read(file_handle, buf, size);
	for (i = 0; i < size; i++)
		buf[i] &= ((PBYTE)pData)[i];

	lseek(file_handle, blockNo * SECTOR_SIZE, SEEK_SET);
	n = write(file_handle, buf, size);

	return (n == size) ? TRUE : FALSE;
#else
	STATUS status;

	_PrintDebugMessage(5, "***[Disk Write]:devInfo=%d, blockNo=%d, blockNum=%d\n", devInfo, blockNo, blockNum);
	status = scsiWrtSecs((SCSI_BLK_DEV *)devInfo, blockNo, blockNum, pData);

#ifdef SF_DEBUG_MSG
	printf("***[Disk Write ]:status=%d, blockNo=%d, blockNum=%d\n", status, blockNo, blockNum);
#endif /* SF_DEBUG_MSG */
	if (status != OK)
		printf("--[ERROR(Write) %d Sector]-----------\n", blockNo);

	return status == OK ? TRUE : FALSE;
#endif	/* SF_DEBUG_WINDOWS */
}

/* ERASE CLUSTER */
BOOL _DSKIO_EraseCluster(PVOID devInfo, DWORD blockNo, PVOID pData)
{
#ifdef SF_DEBUG_WINDOWS
	return TRUE;
#else
	int	i;
	SF_BYTE *p;

	memset(pData, CHECK_DATA, SECTOR_SIZE * SECTOR_PER_CLUSTER);

	if (! _DSKIO_WriteSector(devInfo, blockNo, SECTOR_PER_CLUSTER, pData))
		return FALSE;

	memset(pData, 0, SECTOR_SIZE * SECTOR_PER_CLUSTER);

	if (! _DSKIO_ReadCluster(devInfo, blockNo, pData))
		return FALSE;

	p = (SF_BYTE *)pData;
	for (i = 0; i < SECTOR_SIZE * SECTOR_PER_CLUSTER; i++) {
		if (*p++ != CHECK_DATA) {
			printf("--[ERROR(DATA ERR) %d Sector]-----------\n", blockNo);
			return FALSE;
		}
	}

	memset(pData, INIT_DATA, SECTOR_SIZE * SECTOR_PER_CLUSTER);
	if (! _DSKIO_WriteSector(devInfo, blockNo, 1, pData))
		return FALSE;

	return	TRUE;
#endif
}

/* READ CLUSTER */
BOOL _DSKIO_ReadCluster(PVOID devInfo, DWORD blockNo, PVOID pData)
{
#ifdef SF_DEBUG_WINDOWS
	return TRUE;
#else
	return	_DSKIO_ReadSector(devInfo, blockNo, SECTOR_PER_CLUSTER, pData);
#endif
}

/* WRITE CLUSTER */
BOOL _DSKIO_WriteCluster(PVOID devInfo, DWORD blockNo, PVOID pData)
{
#ifdef SF_DEBUG_WINDOWS
	return TRUE;
#else
	return	_DSKIO_WriteSector(devInfo, blockNo, SECTOR_PER_CLUSTER, pData);
#endif
}

BOOL _DSKIO_DummyWriteFAT(PVOID devInfo)
{
#ifdef SF_DEBUG_WINDOWS
	return TRUE;
#else
	static	char	buf[SECTOR_SIZE];
	int	i;

	memset(buf, 0xff, sizeof(buf));

	/* FAT Dummy Write 8 times */
	for (i = 0; i < FAT_HISTORY_NUM; i++) {
		if (! _DSKIO_WriteSector(devInfo, FAT1_BLOCK_OFFSET, 1, buf))
			return FALSE;
		if (! _DSKIO_WriteSector(devInfo, FAT2_BLOCK_OFFSET, 1, buf))
			return FALSE;
	}
	return TRUE;
#endif	/* SF_DEBUG_WINDOWS */
}

/* Fill the Buffer */
static BOOL _FillBuf(PVOID devInfo, PBYTE pValidFlag, SF_sector_buf *pBuf, DWORD blockNo, DWORD blockNum, DWORD blockOffset)
{
	DWORD readBlockNo, readBlockNum;
	DWORD i;
	int n;

	readBlockNo = 0;
	readBlockNum = 0;
	for (i = 0 ; i < blockNum; i++, blockNo++) {
		n = blockNo - blockOffset;
		if (!pValidFlag[n]) {
			if (readBlockNum == 0)
				readBlockNo = blockNo;
			readBlockNum++;
		} else {
			if (readBlockNum != 0) {
				n = readBlockNo - blockOffset;
				if (!_DSKIO_ReadSector(devInfo, readBlockNo, readBlockNum, pBuf + n))
					return FALSE;

				memset(pValidFlag + n, 1, readBlockNum);
				readBlockNum = 0;
			}
		}
	}

	if (readBlockNum != 0) {
		n = readBlockNo - blockOffset;
		if (!_DSKIO_ReadSector(devInfo, readBlockNo, readBlockNum, pBuf + n))
			return FALSE;

		memset(pValidFlag + n, 1, readBlockNum);
	}

	return TRUE;
}

/* Read From the Buffer */
static BOOL _ReadBuf(PVOID devInfo, PBYTE pValidFlag, SF_sector_buf *pBuf, DWORD blockNo, DWORD blockNum, DWORD blockOffset, PVOID pData)
{
	/* Fill the Buffer */
	if (!_FillBuf(devInfo, pValidFlag, pBuf, blockNo, blockNum, blockOffset))
		return FALSE;

	/* copy from the Buffer */
	memcpy(pData, pBuf + (blockNo - blockOffset), blockNum * SECTOR_SIZE);

	return TRUE;
}

/* Read BPB Data */
static BOOL _ReadBpb(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	return _ReadBuf(devInfo, diskBuf->bpbBuf.validFlag, diskBuf->bpbBuf.buf, blockNo, blockNum, BPB_BLOCK_OFFSET, pData);
}

#ifdef CYCLIC_FAT
/* Read FAT area */
static BOOL _ReadCurrentFAT(PVOID devInfo, SF_fat_buf_t *pFatBuf, DWORD blockNo)
{
	int i;
	DWORD fatCounter;

	if (!pFatBuf->readFlag) {
		/* Find current FAT counter */
		for (i = 0; i < FAT_COPYS; i++) {
			if (!_DSKIO_ReadSector(devInfo, blockNo + i * FAT_SECTORS, 1, pFatBuf->buf))
				return FALSE;

			if (*(PBYTE)pFatBuf->buf == 0xff)
				break;
		}

		if (i == 0)
			return FALSE;	/* Current FAT not found */

		/* Get current FAT counter */
		fatCounter = i - 1;

		/* Read FAT area */
		if (!_DSKIO_ReadSector(devInfo, blockNo + fatCounter * FAT_SECTORS, FAT_SECTOR_NUM, pFatBuf->buf))
			return FALSE;

		/* Save FAT counter */
		pFatBuf->fatCounter = (fatCounter + 1) % FAT_COPYS;

		/* Set validFlag */
		memset(pFatBuf->validFlag, 1, FAT_SECTOR_NUM);

		pFatBuf->readFlag = TRUE;
	}

	return TRUE;
}
#endif

/* Read FAT1 Area */
static BOOL _ReadFat1(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
#ifdef CYCLIC_FAT
	if (!_ReadCurrentFAT(devInfo, &diskBuf->fat1Buf, FAT1_BLOCK_OFFSET))
		return FALSE;
#endif
	return _ReadBuf(devInfo, diskBuf->fat1Buf.validFlag, diskBuf->fat1Buf.buf, blockNo, blockNum, FAT1_BLOCK_OFFSET, pData);
}

/* Read FAT2 Area */
static BOOL _ReadFat2(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
#ifdef CYCLIC_FAT
	if (!_ReadCurrentFAT(devInfo, &diskBuf->fat2Buf, FAT2_BLOCK_OFFSET))
		return FALSE;
#endif
	return _ReadBuf(devInfo, diskBuf->fat2Buf.validFlag, diskBuf->fat2Buf.buf, blockNo, blockNum, FAT2_BLOCK_OFFSET, pData);
}

/* Read RootDirectory Area */
static BOOL _ReadRoot(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	return _ReadBuf(devInfo, diskBuf->rootBuf.validFlag, diskBuf->rootBuf.buf, blockNo, blockNum, ROOT_BLOCK_OFFSET, pData);
}

/* Initialise Cluster Buffer */
static void _InitClusterBuf(SF_cluster_buf_t *pClusterBuf) {
	pClusterBuf->clusterNo = 0;
	pClusterBuf->useSector = 0;
	pClusterBuf->writeFlag = FALSE;
	memset(pClusterBuf->validFlag, 0, sizeof(pClusterBuf->validFlag));
}

/* Flush the Buffer */
static BOOL _FlushBuf(PVOID devInfo, PBYTE pValidFlag, SF_sector_buf *pBuf, DWORD blockNo, DWORD blockNum) {

	_PrintDebugMessage(6, "***[SYNC3 _FillBuf]:\n");
    /* Fill the Buffer */
	if (!_FillBuf(devInfo, pValidFlag, pBuf, blockNo, blockNum, blockNo))
		return FALSE;

	_PrintDebugMessage(6, "***[SYNC3 _DSKIO_WriteSector]:\n");
    /* Write to the disk from the Buffer */
    if (!_DSKIO_WriteSector(devInfo, blockNo, blockNum, pBuf))
		return FALSE;

	return TRUE;
}

/* Flush Cluster Buffer */
static BOOL _FlushClusterBuf(PVOID devInfo, SF_cluster_buf_t *pClusterBuf) {
	DWORD blockNo;

    /* get the number of Cluster Block */
	blockNo = (pClusterBuf->clusterNo - 2) * SECTOR_PER_CLUSTER + DATA_BLOCK_OFFSET;
	_PrintDebugMessage(6, "***[SYNC3 _FlushClusterBuf]:blockNo=%d\n", blockNo);

    /* Flush the Buffer */
	if (!_FlushBuf(devInfo, pClusterBuf->validFlag, pClusterBuf->buf, blockNo, pClusterBuf->useSector))
		return FALSE;

    /* Clear PreWrite Flag */
	pClusterBuf->writeFlag = FALSE;

	return TRUE;	
}

/* Find the Cluster Buffer */
static SF_cluster_buf_t *_FindClusterBuf(PVOID devInfo, DWORD clusterNo)
{
	int i;
	SF_cluster_buf_t *pClusterBuf = NULL;
	DWORD minAccessCounter;

    /* Find the Cluster Buffer from Cluster Number */
	for (i = 0; i < CLUSTER_BUF_NUM; i++) {
		if (diskBuf->clusterBuf[i].clusterNo == clusterNo) {
			pClusterBuf = &diskBuf->clusterBuf[i];
			break;
		}
	}
	if (pClusterBuf == NULL) {
        /* Find an empty Cluster Buffer */
		for (i = 0; i < CLUSTER_BUF_NUM; i++) {
			if (diskBuf->clusterBuf[i].clusterNo == 0) {
				pClusterBuf = &diskBuf->clusterBuf[i];
				pClusterBuf->clusterNo = clusterNo;
				break;
			}
		}

        /* Any Empty Cluster Buffer does not exist */
		if (pClusterBuf == NULL) {
            /* Find the Buffer whose access counter is the least */
			minAccessCounter = diskBuf->clusterBuf[0].accessCounter;
			pClusterBuf = &diskBuf->clusterBuf[0];
			for (i = 1; i < CLUSTER_BUF_NUM; i++) {
				if (diskBuf->clusterBuf[i].accessCounter < minAccessCounter) {
					minAccessCounter = diskBuf->clusterBuf[i].accessCounter;
					pClusterBuf = &diskBuf->clusterBuf[i];
				}
			}

            /* If not yet written, Flush the Cluster Buffer */
			if (pClusterBuf->writeFlag) {
				if (!_FlushClusterBuf(devInfo, pClusterBuf))
					return NULL;
			}

            /* Initialise Cluster Buffer */
			_InitClusterBuf(pClusterBuf);

			pClusterBuf->clusterNo = clusterNo;
		}
	}

	return pClusterBuf;
}

/* Read Data Area */
static BOOL _ReadData(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	DWORD clusterNo;
	DWORD offset;
	DWORD useSector;
	SF_cluster_buf_t *pClusterBuf = NULL;

    /* get cluster Number */
	clusterNo = (blockNo - DATA_BLOCK_OFFSET) / SECTOR_PER_CLUSTER + 2;

    /* Find the Cluster Buffer */
	if ((pClusterBuf = _FindClusterBuf(devInfo, clusterNo)) == NULL)
		return FALSE;

    /* Get the Head Block of the Cluster */
	offset = DATA_BLOCK_OFFSET + SECTOR_PER_CLUSTER * (pClusterBuf->clusterNo - 2);

    /* Fill the Buffer */
	if (!_FillBuf(devInfo, pClusterBuf->validFlag, pClusterBuf->buf, blockNo, blockNum, offset))
		return FALSE;

    /* Copy from the Cluster */
	memcpy(pData, pClusterBuf->buf + (blockNo - offset), blockNum * SECTOR_SIZE);

    /* Update Access Counter of the Cluster */
	pClusterBuf->accessCounter = accessCounter++;

    /* Set the Number of used Sector */
	useSector = blockNo - offset + blockNum;
	if (useSector > pClusterBuf->useSector)
		pClusterBuf->useSector = useSector;

	return TRUE;
}

/* Write to the Buffer */
static BOOL _WriteBuf(PVOID devInfo, PBYTE pValidFlag, SF_sector_buf *pBuf, DWORD blockNo, DWORD blockNum, DWORD blockOffset, PVOID pData)
{
    /* Copy to the Buffer */
	memcpy(pBuf + (blockNo - blockOffset), pData, blockNum * SECTOR_SIZE);

	return TRUE;
}

/* Write BPB */
static BOOL _WriteBpb(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	int n;

	if (!_WriteBuf(devInfo, diskBuf->bpbBuf.validFlag, diskBuf->bpbBuf.buf, blockNo, blockNum, BPB_BLOCK_OFFSET, pData))
		return FALSE;

	n = blockNo - BPB_BLOCK_OFFSET;
	memset(diskBuf->bpbBuf.validFlag + n, 1, blockNum);
	diskBuf->bpbBuf.writeFlag = TRUE;

	return TRUE;
}

/* Write FAT1 */
static BOOL _WriteFat1(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	int n;

	if (!_WriteBuf(devInfo, diskBuf->fat1Buf.validFlag, diskBuf->fat1Buf.buf, blockNo, blockNum, FAT1_BLOCK_OFFSET, pData))
		return FALSE;

	n = blockNo - FAT1_BLOCK_OFFSET;
	memset(diskBuf->fat1Buf.validFlag + n, 1, blockNum);
	diskBuf->fat1Buf.writeFlag = TRUE;

	return TRUE;
}

/* Write FAT2 */
static BOOL _WriteFat2(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	int n;

	if (!_WriteBuf(devInfo, diskBuf->fat2Buf.validFlag, diskBuf->fat2Buf.buf, blockNo, blockNum, FAT2_BLOCK_OFFSET, pData))
		return FALSE;

	n = blockNo - FAT2_BLOCK_OFFSET;
	memset(diskBuf->fat2Buf.validFlag + n, 1, blockNum);
	diskBuf->fat2Buf.writeFlag = TRUE;

	return TRUE;
}

/* Write RootDirectory */
static BOOL _WriteRoot(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	int n;

	if (!_WriteBuf(devInfo, diskBuf->rootBuf.validFlag, diskBuf->rootBuf.buf, blockNo, blockNum, ROOT_BLOCK_OFFSET, pData))
		return FALSE;

	n = blockNo - ROOT_BLOCK_OFFSET;
	memset(diskBuf->rootBuf.validFlag + n, 1, blockNum);
	diskBuf->rootBuf.writeFlag = TRUE;

	return TRUE;
}

/* Write Data */
static BOOL _WriteData(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData, DWORD validSector)
{
	DWORD clusterNo;
	DWORD useSector;
	DWORD offset;
	SF_cluster_buf_t *pClusterBuf = NULL;

    /* Get the Cluster Number */
	clusterNo = (blockNo - DATA_BLOCK_OFFSET) / SECTOR_PER_CLUSTER + 2;

    /* Find the Cluster Buffer */
	if ((pClusterBuf = _FindClusterBuf(devInfo, clusterNo)) == NULL)
		return FALSE;

    /* Get the Head block Number of the Cluster */
	offset = DATA_BLOCK_OFFSET + SECTOR_PER_CLUSTER * (pClusterBuf->clusterNo - 2);

    /* Copy to the Buffer */
	if (pData != NULL)
		memcpy(pClusterBuf->buf + (blockNo - offset), pData, blockNum * SECTOR_SIZE);
	else
		memset(pClusterBuf->buf + (blockNo - offset), 0, blockNum * SECTOR_SIZE);

    /* Set the Sector Valid Flag of the Cluster */
	memset(pClusterBuf->validFlag + (blockNo - offset), 1, blockNum);

    /* Set the Write Flag of the Cluster */
	pClusterBuf->writeFlag = TRUE;

    /* Update Access Couner of the Cluster */
	pClusterBuf->accessCounter = accessCounter++;

    /* Set the number of used sector of the Cluster */
	useSector = blockNo - offset + blockNum;
	if (useSector > pClusterBuf->useSector)
		pClusterBuf->useSector = useSector;

    /* Set the valid Sector of the Cluster */
	if (validSector > pClusterBuf->useSector)
		pClusterBuf->useSector = validSector;

	return TRUE;
}

/* Read the Block */
BOOL _DSKIO_ReadBlock(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData)
{
	BOOL result;

	switch (_GetBlockType(blockNo)) {
	case BLOCK_TYPE_BPB:
		result = _ReadBpb(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_FAT1:
		result = _ReadFat1(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_FAT2:
		result = _ReadFat2(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_ROOT:
		result = _ReadRoot(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_DATA:
		result = _ReadData(devInfo, blockNo, blockNum, pData);
		break;
	default:
		result = FALSE;
		break;
	}

	return result;
}

/* Write the Block */
BOOL _DSKIO_WriteBlock(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData, DWORD validSector)
{
	BOOL result;

	switch (_GetBlockType(blockNo)) {
	case BLOCK_TYPE_BPB:
		result = _WriteBpb(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_FAT1:
		result = _WriteFat1(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_FAT2:
		result = _WriteFat2(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_ROOT:
		result = _WriteRoot(devInfo, blockNo, blockNum, pData);
		break;
	case BLOCK_TYPE_DATA:
		result = _WriteData(devInfo, blockNo, blockNum, pData, validSector);
		break;
	default:
		result = FALSE;
		break;
	}

	return result;
}

#ifdef CYCLIC_FAT
/* Flush FAT buffer */
static BOOL _FlushFat(PVOID devInfo, SF_fat_buf_t *pFatBuf, DWORD blockNo)
{
	/* Write FAT area */
    if (!_DSKIO_WriteSector(devInfo, blockNo + pFatBuf->fatCounter * FAT_SECTORS, FAT_SECTOR_NUM, pFatBuf->buf))
		return FALSE;

	/* next FAT counter */
	pFatBuf->fatCounter = (pFatBuf->fatCounter + 1) % FAT_COPYS;

	return TRUE;
}
#endif


/* Flush the all buffer of the disk */
BOOL _DSKIO_FlashDiskBuf(PVOID devInfo)
{
	int i;

    /* Flush BPB */
	if (diskBuf->bpbBuf.writeFlag) {
		if (!_FlushBuf(devInfo, diskBuf->bpbBuf.validFlag, diskBuf->bpbBuf.buf, BPB_BLOCK_OFFSET, BPB_SECTOR_NUM))
			return FALSE;

		diskBuf->bpbBuf.writeFlag = FALSE;
	}

    /* Flush FAT1 */
	if (diskBuf->fat1Buf.writeFlag) {
#ifdef CYCLIC_FAT
		if (!_FlushFat(devInfo, &diskBuf->fat1Buf, FAT1_BLOCK_OFFSET))
			return FALSE;
#else
		if (!_FlushBuf(devInfo, diskBuf->fat1Buf.validFlag, diskBuf->fat1Buf.buf, FAT1_BLOCK_OFFSET, FAT_SECTOR_NUM))
			return FALSE;
#endif

		diskBuf->fat1Buf.writeFlag = FALSE;
	}

    /* Flush FAT2 */
	if (diskBuf->fat2Buf.writeFlag) {
#ifdef CYCLIC_FAT
		if (!_FlushFat(devInfo, &diskBuf->fat2Buf, FAT2_BLOCK_OFFSET))
			return FALSE;
#else
		if (!_FlushBuf(devInfo, diskBuf->fat2Buf.validFlag, diskBuf->fat2Buf.buf, FAT2_BLOCK_OFFSET, FAT_SECTOR_NUM))
			return FALSE;
#endif

		diskBuf->fat2Buf.writeFlag = FALSE;
		gFatWrtCount++;
	}

    /* Flush RootDirectory */
	if (diskBuf->rootBuf.writeFlag) {
		if (!_FlushBuf(devInfo, diskBuf->rootBuf.validFlag, diskBuf->rootBuf.buf, ROOT_BLOCK_OFFSET, ROOT_SECTOR_NUM))
			return FALSE;

		diskBuf->rootBuf.writeFlag = FALSE;
	}

    /* Flush Data Area */
	for (i = 0; i < CLUSTER_BUF_NUM; i++) {
		_PrintDebugMessage(6, "***[SYNC3 _FlushClusterBuf%d writeFlag=%d]:\n", i, diskBuf->clusterBuf[i].writeFlag);
		if (diskBuf->clusterBuf[i].writeFlag) {
			if (!_FlushClusterBuf(devInfo, &diskBuf->clusterBuf[i]))
				return FALSE;
		}
	}

	return TRUE;
}

