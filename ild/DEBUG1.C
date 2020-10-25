/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: �f�o�b�O�R�}���h����			*
 *		�t�@�C����	: debug1.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=debug1

#include "sg_cpu.h"

/*	�����֐�	*/
static	int	cmd_dump(void);
static	int	cmd_byte_write(void);
static	int	cmd_word_write(void);
static	int	cmd_test_data(void);
static	int	cmd_manual_data(void);
static	int	cmd_check_sum(void);
static	int	cmd_go(void);
static	int	cmd_dsp_update(void);
static	char	*hextoshort(char *, short *);
static	char	*hextolong(char *, long *);
static	char	*dectoshort(char *, short *);

/*=======================================================================
 |
 |		�f�o�b�O�R�}���h���s����
 |
 |	void	debug_command()
 |
 =======================================================================*/
void	debug_command(void)
{
	static	char	command_error[] = "COMMAND ERROR\x0d\x0a";
	int	err;

	rsb_send("\x0d\x0a", 2);

	switch (rsb_rcvbuf[0]) {
	case 'D':
		err = cmd_dump();
		break;
	case 'B':
		err = cmd_byte_write();
		break;
	case 'W':
		err = cmd_word_write();
		break;
	case 'T':
		err = cmd_test_data();
		break;
	case 'M':
		err = cmd_manual_data();
		break;
	case 'S':
		err = cmd_check_sum();
		break;
	case 'G':
		err = cmd_go();
		break;
	case 'U':
		err = cmd_dsp_update();
		break;
	case '\0':
		err = 0;
		break;
	default:
		err = 1;
		break;

	}

	if (err != 0)
		rsb_send(command_error, sizeof(command_error) - 1);

	rsb_send("\x0d\x0a>", 3);
}

/*=======================================================================
 |
 |		�_���v�R�}���h���s����
 |
 |	short	cmd_dump()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_dump(void)
{
	char	*bp, *p;
	long	address;
	short	nbyte, n, offset;
	int	i;
	char	buf[60];

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &nbyte)) == NULL)
		return(1);

	rsb_send(" ADDRESS   0  1  2  3  4  5  6  7   8  9  A  B  C  D  E  F\x0d\x0a", 60);
	for (n = 0; n < nbyte; n++) {
		p = (char *)address;
		offset = (short)(address & 0xf);
		if (offset == 0 && n != 0)
			rsb_send(buf, 60);
		if (offset == 0 || n == 0) {
			for (i = 0; i < 58; i++)
				buf[i] = ' ';
			buf[58] = 0x0d;
			buf[59] = 0x0a;
			longtohex(address & ~0xfL, buf, 8);
		}
		shorttohex(*p, &buf[10 + offset*3 + (offset >= 8 ? 1 : 0)], 2);
		address++;
	}

	if (nbyte != 0)
		rsb_send(buf, 60);

	return(0);
}

/*=======================================================================
 |
 |		�o�C�g�f�[�^�������݃R�}���h���s����
 |
 |	int	cmd_byte_write()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_byte_write(void)
{
	char	*bp, *p;
	long	address;
	short	data;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &data)) == NULL)
		return(1);

	p = (char *)address;
	*p = (char)data;

	return(0);
}

/*=======================================================================
 |
 |		���[�h�f�[�^�������݃R�}���h���s����
 |
 |	int	cmd_word_write()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_word_write(void)
{
	char	*bp;
	short	*p;
	long	address;
	short	data;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &data)) == NULL)
		return(1);

	p = (short *)address;
	*p = data;

	return(0);
}

/*=======================================================================
 |
 |		�e�X�g�f�[�^�ԍ��ݒ�R�}���h���s����
 |
 |	int	cmd_test_data()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_test_data(void)
{
	char	*bp;
	short	data;

	bp = &rsb_rcvbuf[1];

	if ((bp = dectoshort(bp, &data)) == NULL)
		return(1);

	test_data_change = (data != 0) ? data : -1;

	return(0);
}

/*=======================================================================
 |
 |		�}�j���A���f�[�^���̓R�}���h���s����
 |
 |	int	cmd_manual_data()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_manual_data(void)
{
	char	*bp;
	short	no, data;

	bp = &rsb_rcvbuf[1];

	if ((bp = dectoshort(bp, &no)) == NULL)
		return(1);

	if ((bp = dectoshort(bp, &data)) == NULL)
		return(1);

	input_data[no] = data;
	ibuf_flag = 1;

	return(0);
}

/*=======================================================================
 |
 |		�`�F�b�N�T���\���R�}���h���s����
 |
 |	int	cmd_check_sum()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_check_sum(void)
{
	char	buf[6];

	rsb_send("CHECK_SUM=", 10);

	shorttohex(check_sum, buf, 4);
	buf[4] = 0x0d;
	buf[5] = 0x0a;
	rsb_send(buf, 6);

	return(0);
}

/*=======================================================================
 |
 |		�W�����v�R�}���h���s����
 |
 |	int	cmd_go()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_go(void)
{
	char	*bp;
	long	address;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	(*(void (*)())address)();

	return(0);
}

/*=======================================================================
 |
 |		�X�V���x�\���R�}���h���s����
 |
 |	int	cmd_dsp_update()
 |
 |		int	�Ԓl		�O�F����@�P�F�G���[
 |
 =======================================================================*/
static	int	cmd_dsp_update(void)
{
	rate_dsp_flag ^= 0x01;
	return(0);
}

/*=======================================================================
 |
 |		�P�U�i��������short�ɕϊ�
 |
 |	short	hextoshort(bp, n)
 |
 |		char	*bp;		�P�U�i�����f�[�^
 |		short	*n;		short�l
 |
 |		char	*�Ԓl		�ϊ����bp
 |
 =======================================================================*/
static	char	*hextoshort(char *bp, short *n)
{
	int	c;

	while (*bp == ' ')
		bp++;

	*n = 0;
	for (;;) {
		c = *bp;
		*n <<= 4;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else if (c >= 'A' && c <= 'F')
			*n += c - 'A' + 10;
		else if (c >= 'a' && c <= 'f')
			*n += c - 'a' + 10;
		else
			return(NULL);
		bp++;
		if (*bp == ' ' || *bp == '\0')
			break;
	}
	return(bp);
}

/*=======================================================================
 |
 |		�P�U�i��������long�ɕϊ�
 |
 |	short	hextolong(bp, n)
 |
 |		char	*bp;		�P�U�i�����f�[�^
 |		long	*n;		long�l
 |
 |		char	*�Ԓl		�ϊ����bp
 |
 =======================================================================*/
static	char	*hextolong(char *bp, long *n)
{
	int	c;

	while (*bp == ' ')
		bp++;

	*n = 0;
	for (;;) {
		c = *bp;
		if (c == ' ' || c == '\0')
			break;
		*n <<= 4;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else if (c >= 'A' && c <= 'F')
			*n += c - 'A' + 10;
		else if (c >= 'a' && c <= 'f')
			*n += c - 'a' + 10;
		else
			return(NULL);
		bp++;
	}
	return(bp);
}

/*=======================================================================
 |
 |		�P�O�i��������short�ɕϊ�
 |
 |	char	*dectoshort(bp, n)
 |
 |		char	*bp;		�P�O�i�����f�[�^
 |		short	*n;		short�l
 |
 |		char	*�Ԓl		�ϊ����bp
 |
 =======================================================================*/
static	char	*dectoshort(char *bp, short *n)
{
	int	c, flag;

	while (*bp == ' ')
		bp++;

	if (*bp == '$')
		return(hextoshort(bp+1, n));

	if (*bp == '-') {
		flag = -1;
		bp++;
	} else
		flag = 1;

	*n = 0;
	for (;;) {
		c = *bp;
		*n *= 10;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else
			return(NULL);
		bp++;
		if (*bp == ' ' || *bp == '\0')
			break;
	}
	*n *= flag;

	return(bp);
}