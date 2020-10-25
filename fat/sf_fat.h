/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : FAT header                                *
**********************************************************/

#ifndef	__SF_FAT_H__
#define	__SF_FAT_H__

#include "sf_define.h"

#define	SF_VERSION	"FAT system for SSMSD Ver 0.9 2004/08/03"

/* Size of 1 Sector (BYTE) */
#define	SF_MAXSECBYTE  (SECTOR_SIZE)

/* The Largest Value of the FILE ID : the largest value of INT32 type value */
#define	SF_FILE_ID_MAX	(0x7FFFFFFF)

#define MAX_DISK_NUM	(1)			/* a largest number of disk */
#define DSKIO_FILES		(20)		/* a largest number of files which is opened simulatneously */
#define DSKIO_BUFFERS	(20)		/* a number of sector buffer */

#define MAX_FILENAME	254
#define MAX_PATHNAME	259

#define SF_dirDlm		'/'

/*------ definition of the struct and constant for management of disk and file -------*/

/* definition of disk table */
#define MAX_DEVICE_NAME	64
typedef	struct {
	BOOL	valid;		/* valid flag */
	char	devName[MAX_DEVICE_NAME];	/* device Name */
	PVOID	devInfo;	/* pointer of the device */
	PVOID	pDskPara;	/* pointer of the disk parameter For the File System */
} DFM_dskTbl_t, *DFM_pDskTbl_t;

/* definition of BPB structure */
typedef	struct {
	WORD  bytesPerSector;		/* bytes per sector   */
	SF_BYTE  sectorsPerCluster;	/* sector per cluster */
	WORD  reservedSectors;		/* a number of reserved Sectors */
	SF_BYTE  numberOfFATs;		/* a number of FAT */
	WORD  rootEntries;			/* a number of root Directory Entry */
	DWORD totalSectors;			/* a number of total Sectors */
	SF_BYTE  mediaDescriptor;	/* media Discriptor */
	WORD  sectorsPerFAT;		/* sectors per FAT */
	/*-----	Added Information -----*/
	DWORD volumeSerialNumber;	/* Volume Serial Number */
	SF_BYTE  volumeLabel[11];	/* Volume Label         */
} SF_bpb_t;


/* definition of the FAT Disk Parameter Structure */
typedef	struct {
	DWORD	dskNo;				/* Disk No                   */
	DWORD	openFileCnt;		/* a number of open Files    */
	SF_bpb_t bpb;				/* a copy of BPB on Disk     */
	struct {					/* Sector Number Information */
		DWORD	boot;			/* boot sector number        */
		DWORD	fat1;			/* FAT1 sector number        */
		DWORD	dir;			/* DIR sector number         */
		DWORD	data;			/* DATA sector number        */
		DWORD	eov;			/* The next sector number of the last sector number */
	} sn;
	DWORD	freeClu;			/* a number of empty cluster(Initialised (DWORD)-1) */
	DWORD	defscn;				/* default search position of the empty cluster */
	DWORD	eoc;				/* The next custer number of the last cluster number */
} SF_fatDskPara_t, *SF_pFatDskPara_t;

/* definition of the atr member(SF_fatDskPara_t) bit */
/* Only when SF_AT_RM is set, SF_AT_BUSYCHK-SF_AT_EJ is effective */
#define	SF_AT_EJ	  (0x01)	/* enable eject(only removable media) */
#define	SF_AT_RM	  (0x10)	/* removable disk                     */
#define	SF_AT_IOCTL	  (0x40)	/* support IOCTL */

/* definition of directory entry type */
#define	SF_DIRENT_SIZ  (32)	 /* A length of 1 directory entry is 32 byte fixed */

typedef	struct {
	SF_BYTE	fname[11];		/* File Name(Name 8 + ext 3) */
	SF_BYTE	atr;			/* File Attribute            */
	SF_BYTE	rev[10];		/* reserved                  */
	WORD	upTime;			/* update time               */
	WORD	upDate;			/* update date               */
	DWORD	fatent;			/* FAT entry                 */
	DWORD	fsize;			/* file size                 */
	SF_BYTE	lfname[MAX_FILENAME + 1]; /* Long File Name  */
	SF_BYTE	checkSum;		/* check sum                 */
} SF_dirEnt_t, *SF_pDirEnt_t;

/* entry type for Long File Name */
#pragma pack(1)
typedef struct {
	SF_BYTE	weCnt;
#define	VFAT_LAST	0x40
#define	VFAT_CNT	0x3f
	SF_BYTE	wePart1[10];
	SF_BYTE	weAttributes;
#define	ATTR_VFAT	0x0f
	SF_BYTE	weReserved1;
	SF_BYTE	weChksum;
	SF_BYTE	wePart2[12];
	WORD	weReserved2;
	SF_BYTE	wePart3[4];
} SF_dirEntEx_t, *SF_pDirEntEx_t;
#pragma pack()
#define	VFAT_CHARS	13	/* Number of chars per winentry */
#define MAX_VFAT_ENTRY	20

/* definition of the atr member(SF_dirEnt_t) bit */
#define	SF_AT_NOR  (0x00)	/* Normal File       */
#define	SF_AT_ROL  (0x01)	/* Read Only File    */
#define	SF_AT_HID  (0x02)	/* Hidden File       */
#define	SF_AT_SYS  (0x04)	/* System File       */
#define	SF_AT_VOL  (0x08)	/* Volume Label      */
#define	SF_AT_DIR  (0x10)	/* Sub Directory     */
#define	SF_AT_ARC  (0x20)	/* Archive Attribute */

/* definition of file control structure */
typedef	struct {
	DWORD	dskNo;			/* Disk No */
	DWORD	flag;			/* Flag    */
	DWORD	dupCnt;			/* the count of DUP  */
	SF_dirEnt_t	de;			/* directory entry of the file */
	DWORD	rwPtr;			/* R/W pointer(BYTE) */
	DWORD	dirFxxxluNo;	/* Cluster Number where exists directory entry(0:Root) */
	DWORD	dirSecNo;		/* Sector Number where exists directory entry          */
	DWORD	dirSbpos;		/* Position in Sector where exists indirectory entry   */
	DWORD	dirSecNoVfat[MAX_VFAT_ENTRY];	/* Sector Number where exists VFAT directory entry */
	DWORD	dirSbposVfat[MAX_VFAT_ENTRY];	/* Position in Sector where VFAT exists indirectory entry */
	DWORD	vfatEntNum;		/* the number of VFAT entry */
	INT32	fileID;			/* File ID number */
} SF_fcs_t, *SF_pFcs_t;

/* definition of the empty mark set in dsk member of SF_fcs_t type valueable */
#define	SF_FF_EMPTY	((DWORD)-1)

/* definition of the flag member(SF_fcs_t) bit */
/* (It is same with the bit definition of SF_OPEN_XXX */
/* Access Code(bit 3-0) */
#define	SF_FF_RDMD		(0x01)		/* Read Mode           */
#define	SF_FF_WRMD		(0x02)		/* Write Mode          */
#define	SF_FF_FILE		(0x10)		/* File Open Mode      */
#define	SF_FF_DIR		(0x20)		/* Directory Open Mode */
#define	SF_FF_DEVICE	(0x40)		/* Device Open Mode    */
#define	SF_FF_RF		(0x0100)	/* Read is Executed    */
#define	SF_FF_WF		(0x0200)	/* Write is Executed   */
#define	SF_FF_DEUPD		(0x8000)	/* Update Directory Entry */


/* definition of the sector Buffer */
typedef	struct SF_SECBUF_STRUC {
    struct SF_SECBUF_STRUC *pNextSecBuf; /* pointer of the next sector buffer */
	DWORD dskNo;				/* disk Letter(NO) ((DWORD)-1=empty,0=A:,1=B:,2=C:,...,25=Z:) */
	DWORD flag;					/* Flag */
	DWORD secNo;				/* Sector Number */
	SF_BYTE buf[SF_MAXSECBYTE];	/* Sector Buffer */
} SF_secBuf_t, *SF_pSecBuf_t;

/* definition of the empty mark set in dskNo member of SF_secBuf_t type valueable */
#define	SF_BF_EMPTY	((DWORD)-1)

/* definition of flag member(SF_secBuf_t) value */
/* R/W(bit3Å`0) */
#define	SF_BF_RD		(0x01)		/* already read(same with the data on the disk) */
#define	SF_BF_WR		(0x02)		/* wait for write */
#define	SF_BF_MSK_RM	(0x0F)		/* bit mask for R/W bit */
#define SF_BF_FILL		(0x10)		/* Fill the Cluster */
/* Area(bit7,6) */
#define	SF_BF_SYS		(0x00)		/* System Area(except FAT,directory,data) */
#define	SF_BF_FAT		(0x40)		/* FAT Area */
#define	SF_BF_DIR		(0x80)		/* Directory Area(include sub directories in data Area) */
#define	SF_BF_DATA		(0xC0)		/* Data Area */
#define	SF_BF_MSK_AR	(0xC0)		/* bit Mask For Area bit */
#define	SF_BF_SHF_AR	(6)			/* bit shift value For Area bit */


/* definition of Error Information */
typedef	struct {
	RESULT errCd;	/* Error Code    */
	DWORD   type;	/* Error Type    */
	DWORD   dskNo;	/* Disk Number   */
	DWORD  secNo;	/* Sector Number */
} SF_err_t;

typedef	SF_err_t *SF_pErr_t;

/* definition of File Information */
typedef struct {
	SF_BYTE st_attr;
	DWORD st_size;
	DWORD st_time;
} SF_stat_t;

/* definition of type member(SF_err_t) value */
/* Error Type(bit3-0) */
#define	SF_ET_GENE		(0)		/* general fault(*1,*2,*4) */
#define	SF_ET_CHRDEV	(1)		/* open charcter device(*1,*2) */
#define	SF_ET_NOFAT		(2)		/* The file system of the disk is not FAT(*1,*2) */
#define	SF_ET_BADFNAME	(3)		/* bad File Name character(*1,*2) */
#define	SF_ET_BADFAT	(4)		/* Bad Information about FAT and cluster(*2) */
#define	SF_ET_PARA		(5)		/* Bad parameter(*1,*2,*3) */
#define	SF_ET_BPB_RD  (0x8)		/* fail to read BPB(*2) */
#define	SF_ET_DSK_RD  (0x9)		/* fail to read disk */
#define	SF_ET_DSK_WR  (0xA)		/* fail to write disk */
#define	SF_ET_MSK_RM (0x0F)		/* bit mask for error type */
/* (*1)	do not use the Error Area(bit7,6) */
/* (*2)	do not use the sector number      */
/* (*3)	do not use the sector number      */
/* (*4)	it is possible not to use the disk number */
/* Request For Abort(bit4) */
#define	SF_ET_ABORT	 (0x10)		/* Program Abort Request by Error Handler */
/* Reserved(bit5) :	always 0h */
/* Error Area(bit7,6) :	same with the definition of Area of SF_BF_XXX */
#define	SF_ET_SYS	 (0x00)		/* System Area(except FAT,directory,data Area) */
#define	SF_ET_FAT	 (0x40)		/* FAT Area */
#define	SF_ET_DIR	 (0x80)		/* Directory Area(include sub directory on the disk) */
#define	SF_ET_DATA	 (0xC0)		/* Data Area */
#define	SF_ET_MSK_AR (0xC0)		/* bit mask fot Area */
#define	SF_ET_SHF_AR (6)		/* bit shift value for Area */


/*----------------------- Declaration of Global Valueables -------------------------*/
extern SF_err_t	SF_err;			/* the latest Error Information in FAT File System */
extern SF_BYTE	SF_volLb_NO_NAME[11]; /* Default Volume Label */

/*----------------------- Declaration of Global Functions -------------------------*/

/* Initialise FAT File System */
extern BOOL	_SF_Init(			/* return : TRUE=OK,FALSE=fail to initialise */
	VOID						/* no arguments */
);

/* mount disk */
RESULT _SF_Mount(				/* return : 0=success,more than 1=failure */
	PSZ devName,				/* device Name */
	PVOID devInfo				/* device Information */
);

/* Check Disk Ready */
extern BOOL	_SF_RdyChk(			/* return : TRUE=Disk Ready, FALSE=Disk Not Ready or Error */
	DWORD dskNo					/* in:disk Number(0=default disk,1=A:,2=B:,...,26=Z:) */
);

/* get a number of empty cluster */
extern RESULT _SF_GetFreeClu(	/* return : 0=OK,more than 1=error code */
	INT32 i32FileID,			/* in:File ID Number */
	PDWORD pdwSectSize,			/* out:the number of bytes per 1 sector  */
	PDWORD pdwSectPerClu,		/* out:the number of bytes per 1 cluster */
	PDWORD pdwFreeClu,			/* out:the number of available clusters  */
	PDWORD pdwTotalClu			/* out:the number of clusters per 1 disk */
);

/* make a new Sub Directory */
extern RESULT _SF_MkDir(		/* return :	0=success,more than 1=error code */
	PSZ	pszPath					/* in:the path of sub directory */
);

/* remove a Sub Directory */
extern RESULT _SF_RmDir(		/* return :	0=success,more than 1=error code */
	PSZ	pszPath					/* in:the path of sub directory */
);

/* remove a file */
extern RESULT _SF_Remove(		/* return : 0=success,more than 1=error code */
	PSZ	pszPath					/* in:path of file path */
);

/* change file name */
extern RESULT _SF_Rename(		/* return : 0=success,more than 1=error code */
	PSZ	pszOldPath,				/* in:old file name */
	PSZ	pszNewPath				/* in:new file name */
);

/* Get the Attibute of file */
extern RESULT _SF_GetFattr(		/* return : 0=success,more than 1=error code,-1=argument error */
	PSZ	  pszPath,				/* in:file path */
	PWORD pwAttr				/* out:file attribute */
);

/* Set the Attribute of file */
extern RESULT _SF_SetFattr(		/* return : 0=success,more than 1=error code,-1=argument error */
	PSZ	  pszPath,				/* in:file path */
	WORD   attr					/* in:file attribute */
);

/* definition of file open flag */
/* File Access Code(bit3-0) */
#define	SF_OPEN_RD		(0x00)	/* Read Mode       */
#define	SF_OPEN_WR		(0x01)	/* Write Mode      */
#define	SF_OPEN_RW		(0x02)	/* Read/Write Mode */
#define	SF_OPEN_APPEND	(0x08)	/* Append Mode     */
#define	SF_OPEN_DIR_RD	(0x0C)	/* Directory Read Mode */
/* Reserved(bit7-4) : always 0h */

/* Open File */
extern RESULT _SF_Open(			/* return : 0=success,more than 1=error code */
	PSZ	   pszPath,				/* in:file path       */
	DWORD	flag,				/* in:open flag       */
	PINT32 pi32FileID			/* out:File ID Number */
);

/* Create File */
extern RESULT _SF_Create(		/* return : 0=success,more than 1=error code */
	PSZ	   pszPath,				/* in:file path       */
	DWORD	attr,				/* in:file attribute  */
	PINT32 pi32FileID			/* out:File ID Number */
);

/* Close File */
extern RESULT _SF_Close(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID			/* in:File ID Number */
);

/* Read File */
extern RESULT _SF_Read(			/* return : 0=success,more than 1=error code */
	INT32  i32FileID,			/* in:File ID Number */
	PVOID pvBuf,				/* out:Buffer for Read Data */
	DWORD	blen,				/* in:Read Data Size */
	PDWORD pwRlen				/* out:real size of read data */
);

/* Write File */
extern RESULT _SF_Write(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID,			/* in:File ID Number */
	PVOID pvBuf,				/* in:Buffer Write Data */
	DWORD	blen,				/* in:Write Data Size */
	PDWORD pwWlen				/* out:real size of write data */
);

/* definition of the seek Origin */
#define	SF_SEEK_SET	  (0)		/* From the top of file      */
#define	SF_SEEK_CUR	  (1)		/* From the current position */
#define	SF_SEEK_END	  (2)		/* From the end of file      */

/* Move file pointer */
extern RESULT _SF_Seek(			/* return : 0=success,more than 1=error code */
	INT32	i32FileID,			/* in:File ID Number */
	SF_BYTE	origin,				/* in:Seek Origin */
	INT32	offset,				/* in:Seek Ofset  */
	PDWORD pdwRwPtr				/* out:File Pointer After Moving */
);

/* Truncate File */
extern RESULT _SF_Truncate(		/* return :	0=success,more than 1=error code */
	INT32  i32FileID			/* in:File ID Number */
);

/* Get File Date And Time */
extern RESULT _SF_GetDateTime(	/* return	: 0=success,more than 1=error code */
	INT32	i32FileID,			/* in:File ID Number */
	PWORD  pwDate,				/* out:Buffer Address for File Date */
	PWORD  pwTime				/* out:Buffer Address for File Time */
);

/* Set File Date And Time */
extern RESULT _SF_SetDateTime(	/* return	: 0=success,more than 1=error code */
	INT32	i32FileID,			/* in:File ID Number */
	WORD	Date,				/* in:Buffer Address for File Date */
	WORD	Time				/* in:Buffer Address for File Time */
);

/* Read Directory Entry */
extern RESULT _SF_ReadDir(		/* return	: 0=success,more than 1=error code */
	INT32  i32FileID,			/* in:File ID Number */
	PVOID pvBuf,				/* out:Buffer Address for directory Entry */
	DWORD	n,					/* in:a number of directory entry */
	PDWORD pwRn					/* out:a real number of directory entry */
);

/* create a period delimited string(name.ext) from 11 characters fixed filename string */
extern void _SF_CvFname(		/* return : nothing */
	PBYTE	p,					/* in:11 charcter fixed file name string */
	DWORD	atr,				/* in:file attribute(if not know, set all zero) */
	PBYTE	fnp                 /* out:new file name string */
);

/* get file name from directory entry */
extern void _SF_DirFn(			/* return : nothing */
	SF_pDirEnt_t pDirEnt,		/* in:buffer address for directory entry */
	PBYTE pFilename             /* out:file name */
);

/* Create Date String from WORD Value for Date used in Directory Entry */
/* The Format of Date String is "YY-MM-DD"	10 characters fixed string */
extern PSZ _SF_CvFdate(			/* return : date string */
	WORD Date					/* in:WORD Value for date used in Directory Entry */
);

/* Create Time String from WORD Value for Time used in Directory Entry */
/* The Format of Time String is "HH:MM:SS"	8 characters fixed string */
extern PSZ _SF_CvFtime(			/* return : time string */
	WORD Time					/* in:WORD Value for Time used in Directory Entry */
);

/* Create Date String from Directory Entry */
/* The Format of Time String is "YY-MM-DD	 HH:MM"	15 characters fixed string */
extern PSZ _SF_DirTm(			/* return : date and time string */
	SF_pDirEnt_t pDirEnt		/* in:Buffer Address for directory entry */
);

/* Close all open file */
extern RESULT _SF_CloseAll(		/* return : 0=success,more than 1=error code */
	PINT32 pi32FileID			/* out:Error File ID Number when closing */
);

/* get a number of open file */
extern INT16 _SF_OpenFiles(		/* return : -1=invalid disk number,0Å`255=a number of open file on disk */
	DWORD  dskNo				/* in:Disk Number(0=A:,1=B:,...,25=Z:) */
);

/* Format Disk */
extern RESULT _SF_Format(PVOID devInfo);

/* Flush File BufferÅiDevice InformationÅj */
extern RESULT _SF_Sync(			/* return : 0=success,more than 1=error code */
	PSZ pDevName				/* in:device name */
);

/* Flush File BufferÅiFile ID NumberÅj */
extern RESULT _SF_Sync2(		/* return : 0=success,more than 1=error code */
	INT32 i32FileID				/* in:File ID Number */
);

/* check EOF */
extern RESULT _SF_Eof(			/* return : 0=success,more than 1=error code */
	INT32 i32FileID,			/* in:File ID Number */
	PBOOL pEof					/* out:TRUE=EOF FALSE=Not EOF */
);

/* Get File Status */
extern RESULT _SF_Stat(			/* return : 0=success,more than 1=error code,-1=Argument Error */
	PSZ pszPath,				/* in:File Path    */
	SF_stat_t *pStat			/* out:File Status */
);

/* Read Volume Label */
extern RESULT _SF_ReadVolumeLabel(	/* return : 0=success,more than 1=error code */
	INT32 i32FileID,				/* in:File ID Number */
	PSZ pVolumeLabel				/* out:Volume Label */
);

/* Write Volume Label */
extern RESULT _SF_WriteVolumeLabel(	/* return : 0=success,more than 1=error code */
	INT32 i32FileID,				/* in:File ID Number */
	PSZ pVolumeLabel				/* in:Volume Label   */
);

/* Get FAT WRITE COUNTER */
extern	RESULT _SF_GetFatCount(void);
/* RESET FAT WRITE COUNTER */
extern	void	_SF_ResetFatCount(void);

#endif /* __SF_FAT_H__ */
