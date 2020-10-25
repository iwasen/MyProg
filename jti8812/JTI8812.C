/************************************************************************
 *									*
 *		�i�s�h�|�W�W�P�Q�@�e�팟���v���O����			*
 *									*
 *		�t�@�C����	: jti8812.c				*
 *		�쐬��		: s.aizawa				*
 *		�쐬��		: 88/12/31				*
 *									*
 ************************************************************************/

#include	<stdio.h>
#include	<stdlib.h>
#include	<string.h>
#include	<ctype.h>

/*	�h�b�J�[�h�f�[�^�t�@�C����	*/

#define	FN_DATA		"I:JTI8812.DAT"

/*	�L�[�R�[�h��`		*/

#define	KEY_STOP	0x03
#define	KEY_BS		0x08
#define	KEY_CR		0x0d
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_3ZERO	0xfe

/*	�a�h�n�r�G���g��	*/

#define	BIOS_CONST	(char)1
#define	BIOS_CONIN	(char)2
#define	BIOS_CONOUT	(char)3
#define	BIOS_TIMDAT	(char)25
#define	BIOS_RSIOX	(char)27

/*	�g�b�|�S�T�V�X�e���G���A	 */

#define	ATSHUTOFF	*(char *)0xef40
#define	IT3ZERO		*(char *)0xf0f2
#define	IFRCECNT	*(char *)0xf312

/*	�f�[�^�o�b�t�@	*/

struct	kdata	{
	char	date[8];
	char	time[5];
	char	j_code[8];
	struct	{
		char	flag;
		char	tank_no[8];
		char	shubetu;
		char	aturyoku[8][11];
		char	ondo[4][11];
		char	hantei;
	} rdata[5];
} kbuf;

/*	�Œ蕔��ʕ\���e�[�u��	*/

struct	scrdat	{
	char	pos_x;			/* �\�����ʒu */
	char	pos_y;			/* �\���s�ʒu */
	char	*data;			/* �\���f�[�^ */
};
typedef	struct scrdat SCRDAT;

/*	�K�C�h���b�Z�[�W 	*/

static	char	msg00[] = "��� � ���� �ø�޻�";
static	char	msg01[] = "�ݻ� �Ͻ� �ޭ��� � �ۼ��޽�  [Y]/N";
static	char	msg10[] = "��޹ � ƭ�خ� �ø�޻�";
static	char	msg11[] = "�޺� � ƭ�خ� �ø�޻�";
static	char	msg12[] = "�޷ޮ��� ���� � ƭ�خ� �ø�޻�";
static	char	msg13[] = "�ۼ��޽�  [Y]/N";
static	char	msg20[] = "�ݸ No � ƭ�خ� �ø�޻�  (E:���خ�)";
static	char	msg21[] = "����� � �ò �ø�޻�  1:�ݸ / 2:ʲ��";
static	char	msg22[] = "�ް� � �ޭ�� �Ͻ� �ۼ��޽�  [Y]/N";
static	char	msg23[] = "��� � �ݻ� �Ͻ� (1 ϲ�)  [Y]/N";
static	char	msg24[] = "�ݻ���� !!";
static	char	msg30[] = "�ް� �ޭ�����  (A:����)";
static	char	msg31[] = "�ޭ�� ���خ�� ���� � �ۼ��޽�  [Y]";
static	char	msg90[] = "��۸��� � ���� �Ͻ� �ۼ��޽�  [Y]/N";

/*	�G���[���b�Z�[�W	*/

static	char	EM_ICCARD[] = "IC ���� �� ���� �޷Ͼ�";
static	char	EM_SEND[] = "�ް� �� ���� �޷Ͼ�";
static	char	EM_DATA[] = "�ޭ�� �� �ް� �� ��ޮ��޽";
static	char	EM_PRINT[] = "������ �װ  ([R]:���ޯ�� A:����)";

/*	�����֐���`	*/

void	select(void), initial(void), set_datetime(void);
char	inp_date(void), inp_time(void), inp_jcode(void);
void	set_data(void);
char	inp_tankno(char);
void	inp_shubetu(char);
char	receive(char);
void	fmc_data(char *, char *), hantei(char);
void	print(void);
void	endmsg(void);
char	inputs(char *, char, char, char, char), kakunin(char *);
void	dsp_scr(SCRDAT *), dsp_msg(char *), dsp_err(char *);
void	dsp_str(char, char, char *);
void	dsp_nstr(char, char, char *, char);
void	locate(char, char), csr_on(void), csr_off(void), beep(void);
char	input(void);
void	rs_open(void), rs_close(void), rs_clear(void);
char	rs_get(char *, int), rs_put(char *, int);
void	cputs(char *);
char	_bios();

/*	���ʃf�[�^	*/

FILE	*ic_fp;			/* �h�b�J�[�h�f�[�^�t�@�C���|�C���^ */
double	hantei_ti[5];		/* ����l�Z�[�u�G���A */
char	*sv_msg;		/* �K�C�h���b�Z�[�W�Z�[�u�G���A */
char	sv_x, sv_y;		/* �J�[�\���ʒu�Z�[�u�G���A */
char	dcount;			/* ��荞�񂾃f�[�^�� */


/*=======================================================================
 *
 *		���C������
 *
 *	void	main()
 *
 =======================================================================*/
void	main()
{
	memset((char *)&kbuf, ' ', sizeof(kbuf)); /* �o�b�t�@������ */

	/* �h�b�J�[�h�f�[�^�t�@�C���I�[�v�� */
	if ((ic_fp = fopen(FN_DATA, "r+")) == 0) {
		/* �V�K�쐬 */
		if ((ic_fp = fopen(FN_DATA, "w+")) == 0) {
			printf(EM_ICCARD);
			exit(1);
		}
		dcount = 0;
	} else {
		/* �k�r�h�J�[�h�f�[�^�ǂݍ��� */
		fread(&kbuf, sizeof(kbuf), 1, ic_fp);

		/* �����̃f�[�^�̔���l���Z�b�g */
		for (dcount = 0; dcount < 5; dcount++) {
			if (kbuf.rdata[dcount].flag != 'A')
				break;
			hantei(dcount);
		}
	}

	ATSHUTOFF = 0;		/* auto shut off �֎~ */
	IT3ZERO = KEY_3ZERO;	/* "000"�L�[�̃R�[�h�ݒ� */
	IFRCECNT = 0;		/* ���X�^�[�g���[�h�Z�b�g */

	select();		/* �����I�� */

	fclose(ic_fp);		/* �h�b�J�[�h�f�[�^�t�@�C���N���[�Y */

	endmsg();		/* �I�����b�Z�[�W�\�� */

	ATSHUTOFF = 5;		/* auto shut off �Z�b�g */
}

/*=======================================================================
 *
 *		�����I������
 *
 *	void	select()
 *
 =======================================================================*/
void	select()
{
	static	SCRDAT	scr[] = {
		{5, 1, "*** JTI-8812 ֳ� �ݻ ��۸��� ***"},
		{12, 3, "1 �ް� �غ�"},
		{12, 4, "2 ���� �����"},
		{12, 6, "9 ���خ�"},
		{0 ,0, 0}
	};
	char	c;

	dsp_scr(scr);		/* ��ʕ\�� */

	for (;;) {
		dsp_msg(msg00);
		locate(40, 8);

		csr_on();		/* �J�[�\���\�� */
		c = input();		/* �P�������� */
		csr_off();		/* �J�[�\������ */
		switch (c) {
		case KEY_CR:
		case '1':
			if (dcount == 0)
				initial();	/* �����ݒ菈�� */
			set_data();		/* �f�[�^�ݒ�^��M���� */
			dsp_scr(scr);		/* ��ʕ\�� */
			break;
		case '2':
			if (kakunin(msg01) != 0) {
				dsp_msg(msg24);
				print();
			}
			break;
		case '9':
			return;
		}
	}
}

/*=======================================================================
 *
 *		�����ݒ菈��
 *
 *	void	initial()
 *
 =======================================================================*/
void	initial()
{
	static	SCRDAT	scr[] = {
		{5, 1, "*** JTI-8812 ֳ� �ݻ ��۸��� ***"},
		{8, 3, "��޹(YY/MM/DD)"},
		{8, 4, "�޺�(HH:MM)"},
		{8, 5, "�޷ޮ��� ����  ________"},
		{0 ,0, 0}
	};
	char	select;

	dsp_scr(scr);		/* ��ʕ\�� */

	set_datetime();		/* ���t�^�����Z�b�g */
	dsp_nstr(23, 3, kbuf.date, 8);	/* �����t�\�� */
	dsp_nstr(23, 4, kbuf.time, 5);	/* �������\�� */
	memset(kbuf.j_code, ' ', 80);	/* ���Ə��R�[�h�N���A */

	select = 0;
	for (;;) {
		switch (select) {
		case 0:
			switch (inp_date()) {	/* ���t���� */
			case KEY_CR:
			case KEY_DOWN:
				select = 1;
				break;
			}
			break;
		case 1:
			switch (inp_time()) {	/* ���ԓ��� */
			case KEY_CR:
			case KEY_DOWN:
				select = 2;
				break;
			case KEY_UP:
				select = 0;
				break;
			}
			break;
		case 2:
			switch (inp_jcode()) {	/* ���Ə��R�[�h���� */
			case KEY_CR:
				if (kakunin(msg13) != 0)
					return;
				else
					select = 0;
				break;
			case KEY_UP:
				select = 1;
				break;
			}
			break;
		}
	}
}

/*=======================================================================
 *
 *		���t�^�����Z�b�g����
 *
 *	void	set_datetime()
 *
 =======================================================================*/
void	set_datetime()
{
	char	buf[12];

	bios(BIOS_TIMDAT, 0, (int)buf);	/* ���t�^�����ǂݏo�� */

	/* ���t�Z�b�g */
	kbuf.date[0] = (buf[0] >> 4) | '0';
	kbuf.date[1] = (buf[0] & 0x0f) | '0';
	kbuf.date[2] = '/';
	kbuf.date[3] = (buf[1] >> 4) | '0';
	kbuf.date[4] = (buf[1] & 0x0f) | '0';
	kbuf.date[5] = '/';
	kbuf.date[6] = (buf[2] >>4) | '0';
	kbuf.date[7] = (buf[2] & 0x0f) | '0';

	/* �����Z�b�g */
	kbuf.time[0] = (buf[3] >> 4) | '0';
	kbuf.time[1] = (buf[3] & 0x0f) | '0';
	kbuf.time[2] = ':';
	kbuf.time[3] = (buf[4] >> 4) | '0';
	kbuf.time[4] = (buf[4] & 0x0f) | '0';
}

/*=======================================================================
 *
 *		���t���͏���
 *
 *	char	inp_date()
 *
 =======================================================================*/
char	inp_date()
{
	dsp_msg(msg10);
	return(inputs(kbuf.date, 23, 3, 8, 0x05));
}

/*=======================================================================
 *
 *		�������͏���
 *
 *	char	inp_time()
 *
 =======================================================================*/
char	inp_time()
{
	dsp_msg(msg11);
	return(inputs(kbuf.time, 23, 4, 5, 0x05));
}

/*=======================================================================
 *
 *		���Ə��R�[�h���͏���
 *
 *	char	inp_jcode()
 *
 =======================================================================*/
char	inp_jcode()
{
	char	c;

	dsp_msg(msg12);		/* �K�C�h���b�Z�[�W�\�� */

	for (;;) {
		/* ���Ə��R�[�h���� */
		c = inputs(kbuf.j_code, 23, 5, 8, 0x04);
		if (kbuf.j_code[0] == ' ' && c == KEY_CR)
			beep();
		else
			break;
	}
	return(c);
}

/*=======================================================================
 *
 *		�f�[�^�ݒ菈��
 *
 *	void	set_data()
 *
 =======================================================================*/
void	set_data()
{
	static	SCRDAT	scr[] = {
		{1, 1, "�޷ޮ��� ����"},
		{7, 2, "�ݸ No  �����    ��ò�    ���"},
		{0, 0, 0}
	};
	char	i, cl, st, buf[42];

	cl = 0;
	for (;;) {
		dsp_scr(scr);				/* ��ʕ\�� */
		dsp_nstr(15, 1, kbuf.j_code, 8);	/* ���Ə��R�[�h�\�� */
		dsp_nstr(27, 1, kbuf.date, 8);		/* ���t�\�� */
		dsp_nstr(36, 1, kbuf.time, 5);		/* �����\�� */
		for (i = 0; i < dcount; i++) {
			fp_sprintf(buf, "%d %.8s  %s  %7.2f  %s",
				i+1, kbuf.rdata[i].tank_no,
				kbuf.rdata[i].shubetu == '1'
						? "�ݸ " : "ʲ��",
				hantei_ti[i],
				kbuf.rdata[i].hantei == 'N'
						? "��ޮ�ż" : "��ޮ���");
			dsp_str(4, i+3, buf);
		}
		if (cl != 0) {
			sprintf(buf, "%d %.8s  %s",
				i+1, kbuf.rdata[i].tank_no,
				kbuf.rdata[i].shubetu == '1'
						? "�ݸ " : "ʲ��");
			dsp_str(4, i+3, buf);
			i++;
		}
		for ( ; i < 5; i++) {
			sprintf(buf, "%d ________  ____", i+1);
			dsp_str(4, i+3, buf);
		}
retry:
		if (dcount == 5)
			break;

		if (inp_tankno(dcount) == 0)
			break;

		inp_shubetu(dcount);

		if (kakunin(msg22) != 0) {
			/* �q�r�|�Q�R�Q�b�I�[�v�� */
			rs_open();

			/* �f�[�^��M */
			st = receive(dcount);

			/* �q�r�|�Q�R�Q�b�N���[�Y */
			rs_close();

			if (st == 0) {
				kbuf.rdata[dcount].flag = 'A';
				rewind(ic_fp);
				fwrite(&kbuf, sizeof(kbuf), 1, ic_fp);
				fflush(ic_fp);
				hantei(dcount);
				dcount++;
				cl = 0;
			} else
				cl = 1;
		} else
			goto retry;
	}

	/* ��� */
	for (i = 0; i < 3; i++) {
		msg23[17] = i + '1';
		if (kakunin(msg23) == 0)
			break;
		dsp_msg(msg24);
		print();
	}
}

/*=======================================================================
 *
 *		�^���N�m�����͏���
 *
 *	char	inp_tankno(count)
 *
 *		char	count;		�f�[�^�m��
 *
 *		char	�Ԓl		�O�F�f�d�f���́@�P�F�^���N�m������
 *
 =======================================================================*/
char	inp_tankno(count)
char	count;
{
	dsp_msg(msg20);		/* �K�C�h���b�Z�[�W�\�� */

	for (;;) {
		if (inputs(kbuf.rdata[count].tank_no, 6, count+3, 8, 0x02) == 'E')
			return(0);

		if (kbuf.rdata[count].tank_no[0] == ' ')
			beep();
		else
			return(1);
	}
}

/*=======================================================================
 *
 *		��ʓ��͏���
 *
 *	void	inp_shubetu(count)
 *
 *		char	count;		�f�[�^�m��
 *
 =======================================================================*/
void	inp_shubetu(count)
char	count;
{
	char	c;

	dsp_msg(msg21);		/* �K�C�h���b�Z�[�W�\�� */

	for (;;) {
		locate(16, count+3);		/* �J�[�\���ʒu�Z�b�g */
		csr_on();			/* �J�[�\���\�� */
		c = input();			/* �P�������� */
		csr_off();
		switch(c) {
		case KEY_CR:
			if (kbuf.rdata[count].shubetu != ' ')
				return;
			beep();
			break;
		case '1':		/* �^���N */
			dsp_str(16, count+3, "�ݸ ");
			kbuf.rdata[count].shubetu = '1';
			return;
		case '2':		/* �z�� */
			dsp_str(16, count+3, "ʲ��");
			kbuf.rdata[count].shubetu = '2';
			return;
		default:
			beep();
		}
	}
}

/*=======================================================================
 *
 *		�f�[�^��M����
 *
 *	char	receive(count)
 *
 *		char	count;		�f�[�^�m��
 *
 *		char	�Ԓl		�O�F����@�P�F�ُ�
 *
 =======================================================================*/
char	receive(count)
char	count;
{
	static	SCRDAT	scr[] = {
		{3, 1, "�ް� �ޭ��  �ݸ No          �����"},
		{2, 2, "��خ�   �����������   �����������"},
		{10, 3, "�����������   �����������"},
		{10, 4, "�����������   �����������"},
		{10, 5, "�����������   �����������"},
		{2, 6, "����    �����������   �����������"},
		{10, 7, "�����������   �����������"},
		{0, 0, 0}
	};
	static	char	start[] = "SSSSSSSSSSSSSSSSSSSSSSSSS";
	static	char	end[] = "*****\x1a\x1a\x1a";
	char	i;
	char	buf[14];

	dsp_scr(scr);				/* ��ʕ\�� */

	/* �ԍ��\�� */
	locate(1, 1);
	bios(BIOS_CONOUT, count + '1');

	/* �^���N�m���\�� */
	dsp_nstr(22, 1, kbuf.rdata[count].tank_no, 8);

	/* ��ʕ\�� */
	dsp_str(37, 1, kbuf.rdata[count].shubetu == '1' ? "�ݸ" : "ʲ��");

	dsp_msg(msg30);			/* �K�C�h���b�Z�[�W�\�� */

	rs_clear();			/* �q�r�|�Q�R�Q�b��M�o�b�t�@�N���A */

	/* �X�^�[�g�R�}���h���M */
	if (rs_put(start, 25) != 0) {
		dsp_err(EM_SEND);
		return(1);
	}

	/* ���̓f�[�^��M */
	for (i = 0; i < 8; i++) {
		if (rs_get(kbuf.rdata[count].aturyoku[i], 11) != 0)
			return(1);
		fmc_data(kbuf.rdata[count].aturyoku[i], buf);
		dsp_str((i%2)*14+9, i/2+2, buf);
	}

	/* ���x�f�[�^��M */
	for (i = 0; i < 4; i++) {
		if (rs_get(kbuf.rdata[count].ondo[i], 11) != 0)
			return(1);
		fmc_data(kbuf.rdata[count].ondo[i], buf);
		dsp_str((i%2)*14+9, i/2+6, buf);
	}

	/* �G���h�}�[�N��M */
	if (rs_get(buf, 5) != 0)
		return(1);

	/* �G���h�}�[�N�`�F�b�N */
	if (strncmp(buf, end, 5) != 0) {
		dsp_err(EM_DATA);
		return(1);
	}

	for (;;) {
		if (kakunin(msg31) != 0)
			break;
	}

	return(0);
}

/*=======================================================================
 *
 *		���́^���x�f�[�^�\���`���ϊ�����
 *
 *	void	fmc_data(x, y, data)
 *
 *		char	*rdata;		��M�f�[�^
 *		char	*ddata;		�\���f�[�^
 *
 =======================================================================*/
void	fmc_data(rdata, ddata)
char	*rdata, *ddata;
{
	/* �f�[�^�R�s�[ */
	memcpy(ddata+1, rdata, 11);
	ddata[12] = '\0';

	if (rdata[0] & 0x80) {		/* �������� */
		/* �v���X */
		ddata[1] &= 0x7f;	/* �����r�b�g�N���A */
		ddata[0] = '-';
	} else {
		/* �}�C�i�X */
		ddata[0] = ' ';
	}
}

/*=======================================================================
 *
 *		���菈��
 *
 *	void	hantei(count)
 *
 *		char	count;		�f�[�^�m��
 *
 *		double	�Ԓl		����l
 *
 =======================================================================*/
void	hantei(count)
char	count;
{
	double	atu15, atu30, hanteiti;
	char	buf[13];

	/* �P�T���ƂR�O���̃f�[�^�𕂓������f�[�^�ɕϊ� */
	fmc_data(kbuf.rdata[count].aturyoku[4], buf);	/* �P�T���̃f�[�^ */
	atu15 = atof(buf);
	fmc_data(kbuf.rdata[count].aturyoku[7], buf);	/* �R�O���̃f�[�^ */
	atu30 = atof(buf);

	hanteiti = atu15 - atu30;		/* ����l�v�Z */

	/* ���� */
	if (kbuf.rdata[count].shubetu == '1') {
		/* �^���N */
		if (hanteiti <= 6)
			kbuf.rdata[count].hantei = 'N';
		else
			kbuf.rdata[count].hantei = 'E';
	} else {
		/* �z�� */
		if (hanteiti <= 15)
			kbuf.rdata[count].hantei = 'N';
		else
			kbuf.rdata[count].hantei = 'E';
	}

	hantei_ti[count] = hanteiti;	/* ����l�Z�[�u */
}

/*=======================================================================
 *
 *		�������
 *
 *	void	print()
 *
 =======================================================================*/
void	print()
{
	FILE	*fp;
	char	loop_sw, count, i;
	char	buf[13];

	for (;;) {
		if ((fp = fopen("LST:", "w")) == 0) {
			dsp_err(EM_PRINT);
			loop_sw = 1;
			while (loop_sw) {
				switch (input()) {
				case 0x0d:
				case 'R':
					loop_sw = 0;
					break;
				case 'A':
					return;
				default:
					beep();
				}
			}
		} else
			break;
	}

	fprintf(fp, "\n");
	fprintf(fp, "**********************\n");
	fprintf(fp, "*  JTI-8812 �ݻ ���  *\n");
	fprintf(fp, "**********************\n\n");

	fprintf(fp, "��޹ : %.8s    �޺� : %.5s\n", kbuf.date, kbuf.time);
	fprintf(fp, "�޷ޮ��� ���� : %.8s\n\n", kbuf.j_code);

	for (count = 0; count < 5; count++) {
		if (kbuf.rdata[count].flag != 'A')
			continue;
		fprintf(fp, "�ݸ No : %.8s\n", kbuf.rdata[count].tank_no);
		fprintf(fp, "��خ�  : ");
		for (i = 0; i < 4; i++) {
			fmc_data(kbuf.rdata[count].aturyoku[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n");
		fprintf(fp, "         ");
		for (i = 4; i < 8; i++) {
			fmc_data(kbuf.rdata[count].aturyoku[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n");
		fprintf(fp, "����   : ");
		for (i = 0; i < 4; i++) {
			fmc_data(kbuf.rdata[count].ondo[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n\n");
		fp_fprintf(fp, "��ò�  : %7.2f       ��ò : %s %s\n",
		    hantei_ti[count],
		    kbuf.rdata[count].shubetu == '1' ? "�ݸ" : "ʲ��",
		    kbuf.rdata[count].hantei == 'N' ? "��ޮ�ż" : "��ޮ���");
		fprintf(fp, "\n\n");
	}
	fprintf(fp, "\n\n��ò�� :\n\n\n������ :\n\n\n\n");
	fclose(fp);
}

/*=======================================================================
 *
 *		�I�����b�Z�[�W�\������
 *
 *	void	endmsg()
 *
 =======================================================================*/
void	endmsg()
{
	static	SCRDAT	scr[] = {
		{11, 3, "< ��۸��� ���خ� >"},
		{0, 0, 0}
	};
	int	i;

	dsp_scr(scr);		/* �I�����b�Z�[�W�\�� */

	/* ���ԑ҂��_�~�[���[�v */
	for (i = 0; i < 30000; i++);
		;
}


/*=======================================================================
 *
 *		���������
 *
 *	char	inputs(buf, x, y, len, flag)
 *
 *		char	*buf;		���̓o�b�t�@
 *		char	x;		���͌��ʒu
 *		char	y;		���͍s�ʒu
 *		char	len;		���͕�����
 *		char	flag;		bit0�@0/1 = �ʏ�^���t����
 *					bit1  0/1 = �ʏ�^'E'�ŏI��
 *					bit2  0/1 = UP,DOWN�L�[�����^�L��
 *
 =======================================================================*/
char	inputs(buf, x, y, len, flag)
char	*buf;
char	x, y, len;
char	flag;
{
	char	c;		/* ���͕��� */
	char	count;		/* ���͕������J�E���^ */
	char	loop_sw;	/* ���[�v����X�C�b�` */
	char	zero;

	csr_on();		/* �J�[�\���\�� */

	zero = 0;
	count = 0;
	loop_sw = 1;
	while(loop_sw) {
		locate(x+count, y);
		if (zero != 0) {
			c = '0';
			zero--;
		} else
			c = input();	/* �L�[���� */

		switch (c) {
		case KEY_CR:		/* �b�q�L�[ */
			loop_sw = 0;
			break;
		case KEY_UP:		/* cursor up */
		case KEY_DOWN:		/* cursor down */
			if (flag & 0x04)
				loop_sw = 0;
			else
				beep();
			break;
		case KEY_BS:		/* back space */
		case KEY_LEFT:		/* cursor left */
			if (count != 0) {
				count--;
				if ((flag & 0x01) != 0 && (count % 3) == 2)
					count--;
			}
			break;
		case KEY_RIGHT:		/* cursor right */
			if (count < len-1) {
				count++;
				if ((flag & 0x01) != 0 && (count % 3) == 2)
					count++;
			}
			break;
		case 'E':
			if ((flag & 0x02) != 0 && count == 0)
				loop_sw = 0;
			else
				beep();
			break;
		case KEY_3ZERO:
			zero = 3;
			break;
		default:
			if (count < len && (isdigit(c) || c == ' ')) {
				bios(BIOS_CONOUT, c);
				buf[count] = c;
				if (count < len-1) {
					count++;
					if ((flag & 0x01) != 0
							&& (count % 3) == 2)
						count++;
				}
			} else
				beep();
			break;
		}
	}

	csr_off();		/* �J�[�\������ */

	return(c);
}

/*=======================================================================
 *
 *		�Œ蕔��ʕ\��
 *
 *	void	dsp_scr(scrdat)
 *
 *		SCRDAT	*scrdat;	�Œ蕔��ʃf�[�^
 *
 =======================================================================*/
void	dsp_scr(scrdat)
SCRDAT	*scrdat;
{
	cputs("\x1b*");		/* ��ʏ��� */

	/* ��ʕ\�� */
	while (scrdat->data) {
		dsp_str(scrdat->pos_x, scrdat->pos_y, scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 *
 *		�K�C�h���b�Z�[�W�\��
 *
 *	void	dsp_msg(msg)
 *
 *		char	*msg;		���̓K�C�h���b�Z�[�W
 *
 =======================================================================*/
void	dsp_msg(msg)
char	*msg;
{
	char	buf[46];

	sprintf(buf, "\x1b0 %-39s\x1b1", msg);
	dsp_str(1, 8, buf);
	sv_msg = msg;
}

/*=======================================================================
 *
 *		�G���[���b�Z�[�W�\��
 *
 *	void	dsp_err(msg)
 *
 *		char	*msg;		���̓K�C�h���b�Z�[�W
 *
 =======================================================================*/
void	dsp_err(msg)
char	*msg;
{
	beep();
	dsp_msg(msg);		/* �G���[���b�Z�[�W�\�� */
	input();		/* �P�������͑� */
}

/*=======================================================================
 *
 *		�m�F����
 *
 *	char	kakunin(msg)
 *
 *		char	*msg;		�K�C�h���b�Z�[�W
 *
 *		�Ԓl			�P�F�x�����b�q�@�O�F�m
 *
 =======================================================================*/
char	kakunin(msg)
char	*msg;
{
	char	c;

	dsp_msg(msg);
	locate(40, 8);
	for (;;) {
		csr_on();		/* �J�[�\���\�� */
		c = input();		/* �P�������� */
		csr_off();		/* �J�[�\������ */
		switch (c) {
		case KEY_CR:
		case 'Y':
			return((char)1);
		case 'N':
			return((char)0);
		}
	}
}

/*=======================================================================
 *
 *		������\��
 *
 *	void	dsp_str(x, y, str)
 *
 *		char	x;		�\�����ʒu
 *		char	y;		�\���s�ʒu
 *		char	*str;		�\���f�[�^
 *
 =======================================================================*/
void	dsp_str(x, y, str)
char	x, y;
char	*str;
{
	locate(x, y);
	while (*str)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		������\���i�����w��j
 *
 *	void	dsp_nstr(x, y, str, len)
 *
 *		char	x;		�\�����ʒu
 *		char	y;		�\���s�ʒu
 *		char	*str;		�\���f�[�^
 *		char	len;		����
 *
 =======================================================================*/
void	dsp_nstr(x, y, str, len)
char	x, y;
char	*str;
char	len;
{
	locate(x, y);

	while (len--)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		�J�[�\���ʒu�Z�b�g
 *
 *	void	locate(x, y)
 *
 *		char	x;		���ʒu
 *		char	y;		�s�ʒu
 *
 =======================================================================*/
void	locate(x, y)
char	x, y;
{
	char	buf[6];

	sprintf(buf, "\x1b=%c%c", y+0x1f, x+0x1f);
	cputs(buf);

	sv_x = x;
	sv_y = y;
}

/*=======================================================================
 *
 *		�J�[�\���\��
 *
 *	void	csr_on()
 *
 =======================================================================*/
void	csr_on()
{
	cputs("\x1b3");
}

/*=======================================================================
 *
 *		�J�[�\������
 *
 *	void	csr_off()
 *
 =======================================================================*/
void	csr_off()
{
	cputs("\x1b2");
}

/*=======================================================================
 *
 *		�L�[�{�[�h�P�������͏���
 *
 *	char	input()
 *
 =======================================================================*/
char	input()
{
	char	c, x, y, buf[46];

	for (;;) {
		c = bios(BIOS_CONIN);		/* �L�[���� */
		if (c == KEY_STOP) {
			x = sv_x;
			y = sv_y;
			sprintf(buf, "\x1b0 %-39s\x1b1", msg90);
			dsp_str(1, 8, buf);
			for (;;) {
				locate(40, 8);
				c = bios(BIOS_CONIN);
				if (c == KEY_CR || c == 'Y') {
					ATSHUTOFF = 5;
					exit(0);
				} else if (c == 'N') {
					dsp_msg(sv_msg);
					locate(x, y);
					break;
				}
			}
		} else
			break;
	}
	return(c);
}
	
/*=======================================================================
 *
 *		�q�r�|�Q�R�Q�b�I�[�v������
 *
 *	void	rs_open()
 *
 =======================================================================*/
void	rs_open()
{
	static	char	rsbuf[256];	/* �q�r�Q�R�Q�b��M�o�b�t�@ */
	static	struct	{		/* �q�r�|�Q�R�Q�b�I�[�v���p�����[�^ */
		char	*rbufp;		/* ��M�o�b�t�@�̐擪�A�h���X */
		int	rbufsiz;	/* ��M�o�b�t�@�̑傫�� */
		char	bit_rate;	/* �r�b�g���[�g */
		char	chr_leng;	/* �L�����N�^�� */
		char	parity;		/* �p���e�B�`�F�b�N */
		char	stop_bit;	/* �X�g�b�v�r�b�g */
		char	spc_prm;	/* �X�y�V�����p�����[�^ */
	} rs_param = {
		rsbuf,			/* ��M�o�b�t�@ */
		sizeof(rsbuf),		/* ��M�o�b�t�@�T�C�Y */
		0x06,			/* 300 bps */
		0x03,			/* 8 bit/character */
		0x00,			/* non parity */
		0x01,			/* 1 stop bit */
		0x17			/* DTR ON, RTS ON */
	};
	char	ret_inf[9];

	memcpy(ret_inf, &rs_param, 9);
	_bios(BIOS_RSIOX, 0x1000, 0, ret_inf); /* �q�r�|�Q�R�Q�b�I�[�v�� */
}

/*=======================================================================
 *
 *		�f�[�^���M�i�q�r�|�Q�R�Q�b�j
 *
 *	char	rs_put(buf, len)
 *
 *		char	*buf;		���M�f�[�^�o�b�t�@
 *		int	len;		���M�f�[�^��
 *
 *		char	�Ԓl		�O�F����@�P�F�ُ�
 *
 =======================================================================*/
char	rs_put(buf, len)
char	*buf;
int	len;
{
	char	ret_inf[9];

	while (len--) {
		/* �P�������M */
		_bios(BIOS_RSIOX, 0x6000 | (int)*buf++, 0, ret_inf);
	}
	return(0);
}

/*=======================================================================
 *
 *		�f�[�^��M�i�q�r�|�Q�R�Q�b�j
 *
 *	char	rs_get(buf, len)
 *
 *		char	*buf;		��M�f�[�^�o�b�t�@
 *		int	len;		��M�f�[�^��
 *
 *		char	�Ԓl		�O�F����@�P�F�ُ�
 *
 =======================================================================*/
char	rs_get(buf, len)
char	*buf;
int	len;
{
	char	ret_inf[9];

	while (len) {
		/* �L�[�{�[�h���̓`�F�b�N */
		if (_bios(BIOS_CONST) != 0) {
			/* 'A'�����͂��ꂽ�璆�~ */
			if (bios(BIOS_CONIN) == 'A')
				return(1);
		}

		/* �q�r�|�Q�R�Q�b��M�`�F�b�N */
		if (_bios(BIOS_RSIOX, 0x3000, 0, ret_inf) == 0xff) {
			/* �P������M */
			*buf++ = _bios(BIOS_RSIOX, 0x5000, 0, ret_inf);
			len--;
		}
	}
	return(0);
}

/*=======================================================================
 *
 *		�q�r�|�Q�R�Q�b��M�o�b�t�@�N���A
 *
 *	void	rs_clear()
 *
 =======================================================================*/
void	rs_clear()
{
	char	ret_inf[9];

	/* ��M�������������炷�Ă� */
	while (_bios(BIOS_RSIOX, 0x3000, 0, ret_inf) == 0xff) {
		/* �P������M */
		_bios(BIOS_RSIOX, 0x5000, 0, ret_inf);
	}
}

/*=======================================================================
 *
 *		�q�r�|�Q�R�Q�b�N���[�Y����
 *
 *	void	rs_close()
 *
 =======================================================================*/
void	rs_close()
{
	_bios(BIOS_RSIOX, 0x2000);
}

/*=======================================================================
 *
 *		�u�U�[��炷
 *
 *	void	beep()
 *
 =======================================================================*/
void	beep()
{
	bios(BIOS_CONOUT, 0x07);
}

/*=======================================================================
 *
 *		������o��
 *
 *	void	cputs(str)
 *
 *		char	*str;		�o�̓f�[�^
 *
 =======================================================================*/
void	cputs(str)
char	*str;
{
	while (*str)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		�a�h�n�r�R�[��
 *
 *	char	_bios(a, bc, de, hl)
 *
 =======================================================================*/
char	_bios(a, bc, de, hl)
char	a;
int	bc, de, hl;
{
	void	_asm_c(char *);

	_asm_c("\nPUSH B\n");
	_asm_c("\nPUSH D\n");
	_asm_c("\nMVI D,0\n");
	_asm_c("\nMOV E,A\n");
	_asm_c("\nLHLD 1\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nPOP B\n");
	_asm_c("\nPOP D\n");
	_asm_c("\nPUSH H\n");
	_asm_c("\nPUSH D\n");
	_asm_c("\nCALL LAUTO6\n");
	_asm_c("\nPOP D\n");
	_asm_c("\nRET\n");
	_asm_c("\nEXTRN LAUTO6\n");
}