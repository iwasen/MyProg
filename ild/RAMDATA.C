/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: �q�`�l�f�[�^				*
 *		�t�@�C����	: ramdata.c				*
 *									*
 ************************************************************************/
#pragma	SECTION DATA=ramdata

#include "sg_cpu.h"

short	debug_flag;

/*	�q�r�Q�R�Q�b�@�b�g�|�`�֘A	*/
char	rsa_sndbuf[RSA_SBUFSIZE];	/* ���M�o�b�t�@ */
short	rsa_sndwptr;			/* ���M�o�b�t�@�������݃|�C���^ */
short	rsa_sndrptr;			/* ���M�o�b�t�@�ǂݍ��݃|�C���^ */
short	rsa_rcvcounter;			/* ��M�J�E���^ */
byte	rsa_rcvchar;			/* ��M�f�[�^ */

/*	�q�r�Q�R�Q�b�@�b�g�|�a�֘A	*/
char	rsb_sndbuf[RSB_SBUFSIZE];	/* ���M�o�b�t�@ */
short	rsb_sndwptr;			/* ���M�o�b�t�@�������݃|�C���^ */
short	rsb_sndrptr;			/* ���M�o�b�t�@�ǂݍ��݃|�C���^ */
char	rsb_rcvbuf[RSB_RBUFSIZE];	/* ��M�o�b�t�@ */
short	rsb_rcvcounter;			/* ��M�J�E���^ */

/*	�P�T�T�R�a�֘A	*/
short	sub_adrs_count;			/* ��M�҂��T�u�A�h���X */
short	mbi_rcvbuf[512];		/* �P�T�T�R�a��M�f�[�^�o�b�t�@ */
short	input_data[512];		/* ���̓f�[�^�o�b�t�@ */
short	ibuf_flag;			/* ��M�����t���O */

/*	�^�C�}�[����t���O�i�O�F��~�C�P�F���쒆�j	*/
short	tmf_mbibit;		/* �l�a�h�{�[�h�a�h�s�I���҂��^�C�}�[ */
short	tmf_1553B;		/* ���̓f�[�^�X�V�����Ď��^�C�}�[ */
short	tmf_dspmode;		/* �c�^�t�փf�[�^���M�^�C�}�[ */
short	tmf_dubit;		/* �c�^�t����̂a�h�s���ʎ�M�҂��^�C�}�[ */
short	tmf_poweron;		/* �p���[�I���e�X�g�V�[�P���X�^�C�}�[ */
short	tmf_rschar;		/* �q�r�|�Q�R�Q�b�����ԃ^�C�}�[ */

/*	�^�C�}�[�J�E���^	*/
short	tmc_mbibit;		/* �l�a�h�{�[�h�a�h�s�I���҂��^�C�}�[ */
short	tmc_1553B;		/* ���̓f�[�^�X�V�����Ď��^�C�}�[ */
short	tmc_dspmode;		/* �c�^�t�փf�[�^���M�^�C�}�[ */
short	tmc_dubit;		/* �c�^�t����̂a�h�s���ʎ�M�҂��^�C�}�[ */
short	tmc_poweron;		/* �p���[�I���e�X�g�V�[�P���X�^�C�}�[ */
short	tmc_rschar;		/* �q�r�|�Q�R�Q�b�����ԃ^�C�}�[ */
short	tmc_blink;		/* �u�����N�^�C�}�[ */
short	tmc_digital;		/* �f�W�^�����l�X�V�^�C�}�[ */
short	tmc_simulation;

short	sg_mode;
short	old_sg_mode;

short	dot_video;		/* �r�f�I�̉������̃h�b�g�� */
short	data_error_flag;	/* ���̓f�[�^�G���[�t���O */
DP	dp;			/* �c�^�o */
DP	dp_tmp;			/* �c�^�o�ۑ��p�e���|�����G���A */
ushort	pa[N_PABIT];		/* �o�^�` */
ushort	pa_tmp[N_PABIT];	/* �o�^�`�ۑ��p�e���|�����G���A */
short	message_data[4];	/* �O��̃��b�Z�[�W�ۑ��G���A */
short	message_no[4];		/* ���b�Z�[�W�\���e�[�u�� */
short	blink_flag;		/* �u�����N�t���O */
short	count_1553B;		/* �P�T�T�R�a��M�J�E���^�i�����Ď��p�j */
short	count_gscint;		/* �f�r�b�`��I�����荞�݃J�E���^ */
short	rate_1553B;		/* �P�T�T�R�a��M���� */
short	rate_gscint;		/* �f�r�b�`��I�����荞�ݎ��� */
short	rate_dsp_flag;		/* �X�V���[�g�\���t���O */
short	digital_update_flag;	/* �f�W�^�����l�\���X�V�t���O */
short	digital_update_rate;
short	rcv_1553B_flag;

short	seq_power_on;		/* �p���[�I���e�X�g�V�[�P���X */
ushort	check_sum;		/* �`�F�b�N�T���ۑ��G���A */
byte	video_mode;		/* �r�f�I�\�����[�h */
short	bit_status;		/* �a�h�s�`�F�b�N�X�e�[�^�X */
short	bit_data;		/* �O��a�h�s�J�n�f�[�^�ۑ��G���A */
uchar	rs_IMR;			/* �c�t�`�q�s���荞�݃}�X�N�f�[�^ */

short	test_data_no;		/* �e�X�g�f�[�^�ԍ� */
short	test_data_change;	/* �e�X�g�f�[�^�ύX */
SIM_WORK	sim_work[512];	/* ���̓f�[�^�V�~�����[�V�����p���[�N�G���A */

short	count_calc;		/* for debug */
short	rate_calc;
