/************************************************************************
 *									*
 *		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
 *									*
 *		����		: �q�`�l�f�[�^				*
 *		�t�@�C����	: ramdata.c				*
 *									*
 ************************************************************************/
#pragma	SECTION DATA=ramdata

#include "sg_cpu.h"

short	cpu;				/* �b�o�t�ԍ� */
char	*adr_gsc;			/* �f�r�b�擪�A�h���X */
short	display_mode;			/* �\�����[�h */
short	old_display_mode;		/* �O��\�����[�h */
short	rom_error_flag;			/* �q�n�l�`�F�b�N�T���G���[�t���O */
short	debug_mode;			/* �f�o�b�O���[�h */

/*	�q�r�Q�R�Q�b�@�b�g�|�`�֘A	*/
char	rsa_sndbuf[RSA_SBUFSIZE];	/* ���M�o�b�t�@ */
short	rsa_sndwptr;			/* ���M�o�b�t�@�������݃|�C���^ */
short	rsa_sndrptr;			/* ���M�o�b�t�@�ǂݍ��݃|�C���^ */
short	rsa_rcvcounter;			/* ��M�J�E���^ */
short	du_rcvflag;			/* �f�[�^��M�t���O */
uchar	du_rcv_data[10];			/* ��M�o�b�t�@ */

/*	�q�r�Q�R�Q�b�@�b�g�|�a�֘A	*/
char	rsb_sndbuf[RSB_SBUFSIZE];	/* ���M�o�b�t�@ */
short	rsb_sndwptr;			/* ���M�o�b�t�@�������݃|�C���^ */
short	rsb_sndrptr;			/* ���M�o�b�t�@�ǂݍ��݃|�C���^ */
char	rsb_rcvbuf[RSB_RBUFSIZE];	/* ��M�o�b�t�@ */
short	rsb_rcvcounter;			/* ��M�J�E���^ */

/*	�P�T�T�R�a�֘A	*/
short	sub_adrs_count;			/* ��M�҂��T�u�A�h���X */
INPUT_DATA	input_data;		/* ���̓f�[�^�o�b�t�@ */
FILTER_DATA	filter_data;		/* �t�B���^�����ς݃f�[�^ */
FILTER_DATA	tmp_data;
short	calc_flag;			/* �v�Z�J�n�t���O */
short	count_nodata;			/* ����M�J�E���^ */
short	bus_fail;			/* �T�b�ȏ㖢��M */
short	counter_mbibit;			/* �l�a�h �a�h�s���g���C�J�E���^ */

/*	�^�C�}�[�J�E���^	*/
short	tm_mbibit;		/* �l�a�h�{�[�h�a�h�s�I���҂��^�C�}�[ */
short	tm_dustart;		/* �c�^�t�����オ�茟�o�^�C�}�[ */
short	tm_update;		/* �f�[�^�X�V�����Ď��^�C�}�[ */
short	tm_dudata;		/* �c�^�t�փf�[�^���M,�f�[�^��M�Ď��^�C�}�[ */
short	tm_duchar;		/* �c�^�t�̕����ԃ^�C���A�E�g���o�^�C�}�[ */
short	tm_digital;		/* �f�W�^���\���X�V�^�C�}�[ */
short	tm_ibit;		/* �h�|�a�h�s���s�^�C�}�[ */
short	tm_gscbackup;		/* �f�r�b���荞�ݒ�~���̃o�b�N�A�b�v���� */
short	tm_simulation;		/* ���̓f�[�^�V�~�����[�V�����^�C�}�[ */

/*	�a�h�s�֘A	*/
short	rbit_mode;		/* �q�|�a�h�s���[�h */
long	ibit_switch;		/* �X�C�b�`�e�X�g�p�t���O */
short	ibit_request;		/* �h�|�a�h�s�J�n�v���t���O�i�f�o�b�O�p�j */
short	rbit_request;		/* �q�|�a�h�s�J�n�v���t���O */
short	mbi_board_test;		/* �l�a�h�{�[�h�`�F�b�N���� */

DP	dp;			/* �c�^�o */
DP	dp_tmp;			/* �c�^�o�ۑ��p�e���|�����G���A */
ushort	pa[N_PABIT];		/* �o�^�` */
ushort	pa_tmp[N_PABIT];	/* �o�^�`�ۑ��p�e���|�����G���A */
short	count_1553B;		/* �P�T�T�R�a��M�J�E���^�i�����Ď��p�j */
short	count_gscint;		/* �f�r�b�`��I�����荞�݃J�E���^ */
short	rate_1553B;		/* �P�T�T�R�a��M���� */
short	rate_gscint;		/* �f�r�b�`��I�����荞�ݎ��� */
short	rate_dsp_flag;		/* �X�V���[�g�\���t���O */
short	digital_update_flag;	/* �f�W�^���\���X�V�t���O */
short	digital_update_rate;	/* �f�W�^���\���X�V���� */
long	bezel_sw_old;		/* �x�[���X�C�b�`�O��f�[�^ */
short	str_count;		/* �����\���p�J�E���^ */
short	echo_mode;		/* �G�R�[���[�h */
short	echo_status;		/* �G�R�[���� */
uchar	du_send_data[6];	/* �c�^�t�ւ̑��M�f�[�^ */

ushort	check_sum;		/* �`�F�b�N�T���ۑ��G���A */
uchar	rs_IMR;			/* �c�t�`�q�s���荞�݃}�X�N�f�[�^ */

short	test_data_no;		/* �e�X�g�f�[�^�ԍ� */
short	test_data_change;	/* �e�X�g�f�[�^�ύX�t���O */
SIM_WORK sim_work[44*32];	/* ���̓f�[�^�V�~�����[�V�����p���[�N�G���A */

short	tm_test;
short	test_flag;
short	test_count;
short	grid_flag;