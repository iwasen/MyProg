/*	FILE DESCRIPTION
 * ----- %Z%%M% %I% %E% %U% -----

		FILE NAME	: d3item.h
		ROUTINE		: ���ڈꗗ�\���E�I��
		REVISION	:
		REMARKS		:	88.04.19	S.Aizawa
*/

/*	���ڃe�[�u����`	*/

typedef	struct	{
	short	int	flag;
	short	int	result;
	char		*ptr;
} D_CELL;

/*	���샂�[�h	*/

#define	IT_MUL		0x01		/* �����I��		*/
#define	IT_NUM		0x02		/* �ԍ��w��		*/
#define	IT_ALL		0x04		/* �S�w��^�S����	*/
#define	IT_CEN		0x08		/* �Z���^�����O 	*/
#define	IT_DSP		0x10		/* �\���̂�		*/
#define	IT_NSL		0x20		/* �I�𖳂��i���]�������j*/