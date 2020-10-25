/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÉVÅ[ÉPÉìÉXî‘çÜä÷òAíËã`
 *		ÉtÉ@ÉCÉãñº	: pcssn.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#define	FN_SNDBF	"_SEQNO.DBF"
#define	FN_SNNDX	"_SEQNO.NDX"

#define	SN_SEQNOID	"SEQNOID"
#define	SN_COMMENT	"COMMENT"
#define	SN_STARTNO	"STARTNO"
#define	SN_ENDNO	"ENDNO"
#define	SN_STEP		"STEP"
#define	SN_COLUMN	"COLUMN"
#define	SN_SEQNO	"SEQNO"

#define	LEN_SEQNOID	8
#define	LEN_COMMENT	30
#define	LEN_STARTNO	10
#define	LEN_ENDNO	10
#define	LEN_STEP	10
#define	LEN_COLUMN	1
#define	LEN_SEQNO	10

typedef	struct	{
	CHAR	seqNoID[LEN_SEQNOID];
	CHAR	comment[LEN_COMMENT];
	CHAR	startNo[LEN_STARTNO];
	CHAR	endNo[LEN_ENDNO];
	CHAR	step[LEN_STEP];
	CHAR	column[LEN_COLUMN];
	CHAR	seqNo[LEN_SEQNO];
} SEQNO;

extern	SHORT	SNCreate(CHAR *, CHAR *, LONG, LONG, LONG, SHORT);
extern	SHORT	SNGetNo(CHAR *, CHAR *);
extern	SHORT	SNSetNo(CHAR *, LONG);
extern	SHORT	SNDelete(CHAR *);
