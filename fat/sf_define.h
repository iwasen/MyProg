/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Common definition                         *
**********************************************************/

#ifndef	__SF_DEFINE_H__
#define	__SF_DEFINE_H__

/*************************/
#define CYCLIC_FAT
/*************************/

#define	SECTOR_SIZE			8192
#define	SECTOR_PER_CLUSTER	64
#define	CLUSTER_SIZE		(SECTOR_SIZE*SECTOR_PER_CLUSTER)
#define	BPB_SECTOR_NUM		1
#define	FAT_SECTOR_NUM		5
#define	ROOT_SECTOR_NUM		2
#define BPB_BLOCK_OFFSET	0
#ifdef CYCLIC_FAT
#define FAT1_BLOCK_OFFSET	256
#define FAT2_BLOCK_OFFSET	320
#define ROOT_BLOCK_OFFSET	192
#define DATA_BLOCK_OFFSET	384
#define	TOTAL_CLUSTER		18198
#define FAT_SECTORS			8
#define FAT_COPYS			8
#else
#define FAT1_BLOCK_OFFSET	64
#define FAT2_BLOCK_OFFSET	128
#define ROOT_BLOCK_OFFSET	192
#define DATA_BLOCK_OFFSET	256
#define	TOTAL_CLUSTER		18200
#endif
#define CLUSTER_BUF_NUM		3
#define	FAT_HISTORY_NUM		8
#define	FAT_NUM				2

/*=============== Definition of the supported CPU type and C Compiler ===============*/

/* set the number of the CPU type to DEF_CPUTYPE */
#define	CPU80X86 (8632)		/* 32bit 8086 CPU(386 32bit protect mode) */
#define	CPU680X0 (6800)		/* 68000 CPU */

/*=============== Definition of CPU and Compiler	===============*/
#ifdef SF_DEBUG_WINDOWS
#define	DEF_CPUTYPE	 (CPU80X86)	/* 32bit 8086 CPU */
#else
#define	DEF_CPUTYPE	 (CPU680X0)	/* 68000 CPU */
#endif
/*=============== CPU,compiler ===============*/

/* 32bit CPU */
#define	INTBITS	 (32)			/* the number of INT TYPE bit */

/*=============== Definition of ENDIAN ===============*/

#define SF_BIG_ENDIAN			1
#define SF_LITTLE_ENDIAN		2

#if (DEF_CPUTYPE==CPU680X0)
#define SF_ENDIAN_TYPE	SF_BIG_ENDIAN
#else
#define SF_ENDIAN_TYPE	SF_LITTLE_ENDIAN
#endif

/*=============== Definition of Basic Type And Constant ==============*/

typedef	int	 INT;				 /*	int */
typedef	unsigned char  SF_BYTE;	 /*	BYTE(usigned 8bit integer)          */
typedef	unsigned short WORD;	 /*	WORD(unsigned 16bit integer)        */
typedef	unsigned long  DWORD;	 /*	DOUBLE WORD(unsigned 32bit integer) */
#ifdef SF_DEBUG_WINDOWS
typedef	void VOID;				 /*	void */
typedef	signed char	 INT8;		 /*	signed 8bit integer  */
typedef	signed short INT16;		 /*	signed 16bit integer */
typedef	signed long	 INT32;		 /* signed 32bit integer */
typedef	INT BOOL;				 /*	bool */
#endif

typedef	VOID   *PVOID;			 /*	Pointer of VOID valueable    */
typedef	INT	   *PINT;			 /*	Pointer of INT valueable     */
typedef	INT8   *PINT8;			 /*	Pointer of INT8 valueable    */
typedef	INT16  *PINT16;			 /*	Pointer of INT16 valueable   */
typedef	INT32  *PINT32;			 /*	Pointer of INT32 valueable   */
typedef	SF_BYTE   *PBYTE;        /*	Pointer of SF_BYTE valueable */
typedef	WORD   *PWORD;			 /*	Pointer of WORD valueable    */
typedef	DWORD  *PDWORD;			 /*	Pointer of DWROD valueable   */
typedef	BOOL   *PBOOL;			 /*	Pointer of BOOL valueable    */

typedef	PBYTE  PSZ;				/* Pointer NULL-terminated String */

typedef	INT	RESULT;		   /* the return type of INT16 */

#ifndef TRUE
#define	TRUE	 (1)	   /* TRUE (BOOL) */
#define	FALSE	 (0)	   /* FALSE(BOOL) */
#endif

#ifndef	NULL
  #define NULL	((PVOID)0)	  /* NULL pointer */
#endif

#endif /* __SF_DEFINE_H__ */
