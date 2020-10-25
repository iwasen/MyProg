/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: ���ʃT�u���[�`��			*
 *		�t�@�C����	: csub.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <jctype.h>
#include "isam.h"

static	void	(*_db_err_handler)() = 0;	/* �G���[�����֐��|�C���^ */

/*=======================================================================
 |
 |		�G���[�n���h���ݒ�
 |
 |	void	db_err_handler(func)
 |
 |		void	(*func)();	�G���[�����֐��ւ̃|�C���^
 |
 =======================================================================*/
void	db_err_handler(void (*func)())
{
	_db_err_handler = func;
}

/*=======================================================================
 |
 |		�G���[����
 |
 |	void	db_err(err_code)
 |
 |		int	err_code;	�G���[�R�[�h
 |
 =======================================================================*/
void	db_err(int err_code)
{
	_db_err_code = err_code;

	if (_db_err_handler != NULL) {
		/* �G���[�n���h�����ݒ肳��Ă�����Ăяo�� */
		(*_db_err_handler)(err_code);
	}
}

/*=======================================================================
 |
 |		�����񂩂�����ւ̕ϊ�
 |
 |	double	natof(str, len)
 |
 |		char	*str;		������
 |		int	len;		����
 |
 |		�Ԓl			�����l
 |
 =======================================================================*/
double	natof(char *str, int len)
{
	char	buf[32];

	memcpy(buf, str, len);
	buf[len] = '\0';
	return(atof(buf));
}

/*=======================================================================
 |
 |		�����񂩂琮���ւ̕ϊ�
 |
 |	int	natoi(str, len)
 |
 |		char	*str;		������
 |		int	len;		����
 |
 |		�Ԓl			�����l
 |
 =======================================================================*/
int	natoi(char *str, int len)
{
	char	buf[32];

	memcpy(buf, str, len);
	buf[len] = '\0';
	return(atoi(buf));
}

/*=======================================================================
 |
 |		�L�[���o��
 |
 |	int	get_key(dp, ihp, kp)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		IDX_H	*ihp;		�h�c�w�Q�g�|�C���^
 |		char	*kp;		�L�[�o�b�t�@
 |
 |		�Ԓl			�O�F�L�[�Ȃ��@�P�F�L�[����
 |
 =======================================================================*/
int	get_key(DBF *dp, IDX_H *ihp, char *kp)
{
	FIELD	*flp;
	char	buf[100], *p;
	int	flag;

	strcpy(buf, ihp->index);
	p = strtok(buf, "+ ");
	if (ihp->type == 0)
		*kp = '\0';
	else
		*(double *)kp = 0;
	flag = 0;
	while (p) {
		if ((flp = get_field(dp, p)) != NULL) {
			if (ihp->type == 0) {
				memcpy(kp, flp->fad, flp->l_field);
				kp += flp->l_field;
			} else {
				*(double *)kp += natof(flp->fad, flp->l_field);
			}
			flag = 1;
		} else
			return(0);

		p = strtok(NULL, "+ ");
	}

	return(flag);
}

/*=======================================================================
 |
 |		�t�B�[���h�����o��
 |
 |	FIELD	*get_field(dp, field)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		char	*field;		�t�B�[���h��
 |
 |		�Ԓl			�t�B�[���h���ւ̃|�C���^
 |
 =======================================================================*/
FIELD	*get_field(DBF *dp, char *field)
{
	register int	i;
	FIELD	*flp;
	char	buf[11], *p;

	strcpy(buf, field);
	for (p = buf; *p; p++) {
		if (iskanji(*p))
			p++;
		else if (islower(*p))
			*p -= 0x20;
	}

	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (strcmp(buf, flp->name) == 0)
			return(flp);
	}
	return(NULL);
}