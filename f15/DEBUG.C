/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �f�o�b�O���r�f�I�^�[�~�i���`���o��	*
 *		�t�@�C����	: debug.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*	�����֐�	*/
static	void	bintohex(ushort, char *, short);

/*=======================================================================
 |
 |		�f�o�b�O���r�f�I�^�[�~�i���`���o�͏���
 |
 |	void	debug()
 |
 =======================================================================*/
void	debug()
{
	char	buf[19];
	ushort	bin;
	short	i;

	/* �w�b�_�Z�b�g */
	buf[0] = '*';

	/* �r�^�f���[�h�Z�b�g */
	bin = sg_mode;
	if (gsc_start_flag)	/* �f�r�b�{�[�h�N���ς݁H */
		bin |= BIT7;
	if (mbi_start_flag)	/* �l�a�h�{�[�h�N���ς݁H */
		bin |= BIT6;
	bintohex(bin, &buf[1], 2);

	/* �r�^�f�G���[�X�e�[�^�X�Z�b�g */
	bin = 0;
	bintohex(bin, &buf[3], 2);

	/* �l�a�h�@�a�h�s���ʃZ�b�g */
	bin = *REG_MBI_BRCS;
	bintohex(bin, &buf[5], 4);

	/* �c�t�`�q�s�@�b�g�|�a�X�e�[�^�X�Z�b�g */
	buf[9] = (char)(tmo_rsblock ? 'N' : 'Y');

	/* �x�[���r�v�f�[�^�Z�b�g */
	bintohex(rs_receive_data, &buf[10], 2);

	/* �l�a�h�X�e�[�^�X�Z�b�g */
	buf[12] = (char)(tmo_1553B ? 'N' : 'Y');

	/* �P�T�T�R�a�R�}���h���[�h�Z�b�g */
	bintohex(command_word, &buf[13], 4);

	/* �^�[�~�l�[�^�Z�b�g	*/
	buf[17] = 0x0d;		/* �b�q */
	buf[18] = 0x0a;		/* �k�e */

	/* �f�[�^���M */
	for (i = 0; i < 19; i++) {
		if (*REG_DUART_SRA & BIT2)	/* TXRDY ? */
			*REG_DUART_THRA = buf[i];	/* �P�������M */
	}
}

/*=======================================================================
 |
 |		�o�C�i���|���P�U�i�����ϊ�����
 |
 |	void	bintohex(bin, bufp, length)
 |
 |		ushort	bin;		�o�C�i���f�[�^
 |		char	*bufp;		�P�U�i�����i�[�o�b�t�@
 |		short	length;		����
 |
 =======================================================================*/
static	void	bintohex(ushort bin, char *bufp, short length)
{
	static	char	hex[] = "0123456789ABCDEF";

	bufp += length - 1;
	while (length--) {
		*bufp-- = hex[bin & 0x0f];
		bin >>= 4;
	}
}
