/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �J�����_�[�֘A��`
 *		�t�@�C����	: pcscl.h
 *		�쐬��		: y.kaneko
 *
 ************************************************************************/

#define	FN_CLDBF	"_CALENDR.DBF"
#define	FN_CLNDX	"_CALENDR.NDX"


typedef	struct _CALENDR {
	char	Code[8];
	char	Year[4];
	char	Flag[366];
} CALENDR;

extern	SHORT	CLGetDate(CHAR *, PCLDATE, SHORT, PCLDATE);
extern	SHORT	CLGetCalendar(CHAR *, PCLDATE, CHAR *);
