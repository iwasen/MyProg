/******************************************************************************	
		各表示のヘッダーの表示
		FUNC.NAME	: d2head() 
		INPUT		: kind		; header kind
						; 1 = hyou keisiki header
						; 2 = Des header
						; 3 = Lab header
				  fp		; 
		REMARKS		:	88.01.28	S.Takahashi
******************************************************************************/
#include	<stdio.h>
static	char	sccsid[]="@(#)d2head.c 1.2 88/06/12 14:42:55";

d2head ( kind , fp )
int	kind ;
FILE	*fp ;
{
	int	i ;
static	char	*pd_hyou_header[] = {
	"/*---------------------------------------------*/" ,
	"/* #L ; キーワードの記述                       */" ,
	"/* #D ; データの記述                           */" ,
	"/*---------------------------------------------*/" ,
			NULL } ; 
static	char	*pd_des_header[] = {
	"/*---------------------------------------------*/" ,
	"/* #K ; キーワード（値）の記述                 */" ,
	"/*---------------------------------------------*/" ,
	NULL } ; 
static	char	*pd_lab_header[] = {
	"/*---------------------------------------------*/" ,
	"/* #An  ; 軸ラベルの記述                       */" ,
	"/* #AnA ; 軸ラベルの記述  ( 自動作成 )         */" ,
	"/*                  ( n = 1,2,3 )              */" ,
	"/*---------------------------------------------*/" ,	
	NULL } ; 

	switch ( kind ) {
		case 1 :
			i=0;
			for ( ;; ) {
				if ( pd_hyou_header[i] == NULL ) break ;
				d2dspl ( fp , pd_hyou_header[i] ) ;
				d2dspl ( fp , "\n" ) ;
				i++;
			}
			break ;
		case 2 :
			i=0;
			for ( ;; ) {
				if ( pd_des_header[i] == NULL ) break ;
				d2dspl ( fp , pd_des_header[i] ) ;
				d2dspl ( fp , "\n" ) ;
				i++;
			}
			break ;
		case 3 : 
			i=0;
			for ( ;; ) {
				if ( pd_lab_header[i] == NULL ) break ;
				d2dspl ( fp , pd_lab_header[i] ) ;
				d2dspl ( fp , "\n" ) ;
				i++;
			}
			break ;
		default :
			break ;
	}
}
