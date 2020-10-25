/******************************************************************************	
		�e�\���̃w�b�_�[�̕\��
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
	"/* #L ; �L�[���[�h�̋L�q                       */" ,
	"/* #D ; �f�[�^�̋L�q                           */" ,
	"/*---------------------------------------------*/" ,
			NULL } ; 
static	char	*pd_des_header[] = {
	"/*---------------------------------------------*/" ,
	"/* #K ; �L�[���[�h�i�l�j�̋L�q                 */" ,
	"/*---------------------------------------------*/" ,
	NULL } ; 
static	char	*pd_lab_header[] = {
	"/*---------------------------------------------*/" ,
	"/* #An  ; �����x���̋L�q                       */" ,
	"/* #AnA ; �����x���̋L�q  ( �����쐬 )         */" ,
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