/****************************************************************	
	生データからのＬＡデータセット生成・登録
	FUNC.NAME	: d2adrg() 
	INPUT		: ladir		; user directory 
			  ladata	; user index file name 
			  sfile		; setsumei-bu file
			  lfile		; label-bu file
			  bfile		; nama data file 
			  w		; over write check
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	"d2ndrg.h"
static	char	sccsid[]="@(#)d2ndrg.c 1.2 88/06/12 14:44:23";

d2ndrg ( ladir , ladata , sfile , lfile , bfile , w )
char	*ladir , *ladata , *sfile , *lfile , *bfile ;
int 	*w ;
{
	int	dkind = 1 ;
	return ( d2dtmk ( ladir,ladata,sfile,lfile,bfile,dkind,w ) );
}
