/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �q�`�l�f�[�^				*
 *		�t�@�C����	: ramdata.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*	�\�����[�h	*/
short	sg_mode;

/*	�g�O�O�X�f�[�^	*/
struct	H009	orgdata;	/* ���f�[�^ */
struct	H009	tmpdata;	/* �t�B���^�����O�p�e���|�����G���A */
struct	H009	fltdata;	/* �t�B���^�����O��f�[�^ */

/*	�O���Ɠx�f�[�^	*/
struct	GAIKO	lumdata;

/*	�c�^�o�e���|�����G���A	*/
struct	DP_AI	dp_ai;		/* �`�h�\�� */
struct	DP_HUD	dp_hud;		/* �g�t�c�\�� */
struct	DP_HDD	dp_hdd;		/* �g�c�c�\�� */
struct	DP_DATA	dp_data;	/* �O���Ɠx�f�[�^�\�� */

/*	�o�^�`�r�b�g�e���|�����G���A	*/
struct	PA_BIT	pa_bit;

/*	�P�T�T�R�a�R�}���h���[�h	*/
word	command_word;

/*	�P�T�T�R�a��M�f�[�^���	*/
short	r1553B_data;		/* �O�F����M,�P�F��s�f�[�^,�Q�F�O���f�[�^ */

/*	�q�r�|�Q�R�Q�b��M�f�[�^	*/
word	rs_receive_data;

/*	�q�r�|�Q�R�Q�b��M�J�E���^	*/
short	rs_counter;

/*	�f�r�b�{�[�h�N���t���O	*/
short	gsc_start_flag;

/*	�l�a�h�{�[�h�N���t���O	*/
short	mbi_start_flag;

/*	�r�f�I�R���g���[�����W�X�^�ݒ�l	*/
word	video_control;

/*	�^�C�}�[����t���O�i�O�F��~�C�P�F���쒆�j	*/
short	tmf_mbibit;		/* �l�a�h�{�[�h�a�h�s�I���҂��^�C�}�[ */
short	tmf_1553B;		/* �P�T�T�R�a�f�[�^�u���b�N�ԃ^�C�}�[ */
short	tmf_rsblock;		/* �q�r�|�Q�R�Q�b�u���b�N�ԃ^�C�}�[ */
short	tmf_rschar;		/* �q�r�|�Q�R�Q�b�����ԃ^�C�}�[ */

/*	�^�C�}�[�J�E���^	*/
short	tmc_mbibit;		/* �l�a�h�{�[�h�a�h�s�I���҂��^�C�}�[ */
short	tmc_1553B;		/* �P�T�T�R�a�f�[�^�u���b�N�ԃ^�C�}�[ */
short	tmc_rsblock;		/* �q�r�|�Q�R�Q�b�u���b�N�ԃ^�C�}�[ */
short	tmc_rschar;		/* �q�r�|�Q�R�Q�b�����ԃ^�C�}�[ */
short	tmc_debug;		/* �f�o�b�O���r�f�I�^�[�~�i���o�̓^�C�}�[ */

/*	�^�C���A�E�g�t���O	*/
short	tmo_1553B;		/* �P�T�T�R�a�f�[�^�u���b�N�ԃ^�C�}�[ */
short	tmo_rsblock;		/* �q�r�|�Q�R�Q�b�u���b�N�ԃ^�C�}�[ */

/*	�O���Ɠx�f�[�^�L�^���^�L�^�I���t���O	*/
short	memorizing_flag;	/* �O�F�L�^�I���C�P�F�L�^�� */

/*	�f�o�b�N���[�h�t���O	*/
short	debug_mode_flag;	/* �O�F�r�f�I�^�[�~�i���o�͖����@�P�F�L�� */
