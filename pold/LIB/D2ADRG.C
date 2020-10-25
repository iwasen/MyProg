/******************************************************************************	
		解析データからのＬＡデータセット生成・登録
		FUNC.NAME	: d2adrg() 
		INPUT		: ladir		; user directory 
				  ladata	; user index file name 
				  sfile		; setsumei-bu file
				  lfile		; label-bu file
				  kfile		; kaiseki-data file 
				  w		; over write check
		OUTPUT		: error code
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	"d2adrg.h"
static char	sccsid[]="@(#)d2adrg.c 1.2 88/06/12 14:37:46";

d2adrg ( ladir , ladata , sfile , lfile , kfile , w )
	char	*ladir , *ladata , *sfile , *lfile , *kfile ;
	int 	*w ;
{
	int	dkind = 2 ;
	return ( d2dtmk ( ladir,ladata,sfile,lfile,kfile,dkind,w ) );
}
