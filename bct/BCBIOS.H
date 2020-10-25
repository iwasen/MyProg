		/*	bcbios.h	*/

struct	IREG	{
		int	af;
		int	bc;
		int	de;	
		int	hl;
		};

struct	CREG	{
		char	f;
		char	a;
		char	c;
		char	b;
		char	e;
		char	d;
		char	l;
		char	h;
		};

typedef	union	{
			struct	IREG	i;
			struct	CREG	c;
		} REGISTER;

extern	void		b_bios(REGISTER *, int);
extern	REGISTER	regs;

#define		BOOT		0xFD
#define		WBOOT		0x00
#define		CONST		0x03
#define		CONIN		0x06
#define		CONOUT		0x09
#define		LIST		0x0c
#define		PUNCH		0x0f
#define		READER		0x12
#define		HOME		0x15
#define		SELDSK		0x18
#define		SETTRK		0x1b
#define		SETSEC		0x1e
#define		SETDMA		0x21
#define		READ		0x24
#define		WRITE		0x27
#define		LISTST		0x2a
#define		SECTRN		0x2d
#define		PSET		0x30
#define		SCRNDUMP	0x33
#define		BEEP		0x36
#define		RSOPEN		0x39
#define		RSCLOSE		0x3c
#define		RSINST		0x3f
#define		RSOUTST		0x42
#define		RSIN		0x45
#define		RSOUT		0x48
#define		TIMDAT		0x4b
#define		MEMORY		0x4e
#define		RSIOX		0x51

#define		MASKI		0x57
#define		LOADX		0x5a
#define		STORX		0x5d
#define		LDIRX		0x60
#define		JUMPX		0x63
#define		CALLX		0x66
#define		GETPFK		0x69
#define		PUTPFK		0x6c
#define		READSW		0x6f

#define		RDVRAM		0x75

#define		POWEROFF	0x7b
#define		USERBIOS	0x7e

#define		CONTINUE	0x87
#define		BARCODE		0x8a
#define		TCAM		0x8d
#define		GRAPHICS	0x90
#define		TOUCH		0x93
#define		ICCARD		0x96
#define		KEYIN		0x99
#define		KANJI		0x9c
#define		BACKLIGHT	0x9f
#define		INFORM		0xa2

/* 	file mode	*/

#define	INPUT	0x01
#define	OUTPUT	0x02
#define	RANDOM	0x04
#define	APPEND	0x08
