/************************************************************************
 *									*
 *		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
 *									*
 *		����		: �P�O������������荞�ݏ���		*
 *		�t�@�C����	: int10ms.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=int10ms

#include "sg_cpu.h"

/*	�����֐�	*/
static	void	tmc_mbibit(void);
static	void	tmc_dustart(void);
static	void	tmc_update(void);
static	void	tmc_dudata(void);
static	void	tmc_duchar(void);
static	void	tmc_digital(void);
static	void	tmc_ibit(void);
static	void	tmc_gscbackup(void);
static	void	tmc_simulation(void);

/*=======================================================================
 |
 |		�P�O������������荞�ݏ���
 |
 |	void	int10ms()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	int10ms(void)
{
//	tmc_mbibit();	/* �l�a�h�{�[�h�a�h�s�I�����荞�݃^�C���A�E�g���o */
	tmc_dustart();	/* �c�^�t�����オ�茟�o */
	tmc_update();	/* �f�[�^�X�V�����̊Ď� */
	tmc_dudata();	/* �c�^�t�փf�[�^���M�y�уf�[�^��M�Ď� */
	tmc_duchar();	/* �c�^�t����̃f�[�^�̕����ԃ^�C���A�E�g���o */
	tmc_digital();	/* �f�W�^���\���̍X�V�^�C�~���O�̃J�E���g */
	tmc_ibit();	/* �h�|�a�h�s�^�C�}�[ */
	tmc_gscbackup();/* �f�r�b�`��I�����荞�ݒ�~���̃o�b�N�A�b�v���� */
	tmc_simulation();/* �f�o�b�O�p���̓f�[�^�V�~�����[�V�������� */
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		�l�a�h�{�[�h�a�h�s�I�����荞�݃^�C���A�E�g���o����
 |
 |	void	tmc_mbibit()
 |
 =======================================================================*/
static	void	tmc_mbibit(void)
{
	if (tm_mbibit != TM_STOP) {
		if (--tm_mbibit == 0) {
			if (++counter_mbibit < 3)
				mbibit();/* �l�a�h�{�[�h�a�h�s�`�F�b�N�Ď��s */
			else {
/*@@@@@@@@@@@*/
extern	short	test_mbi_error;
test_mbi_error = 1;
/*@@@@@@@@@@@*/
//				halt_cpu("MBI BIT ERROR", 1);
			}
		}
	}
}

/*=======================================================================
 |
 |		�c�^�t�����オ�茟�o����
 |
 |	void	tmc_dustart()
 |
 =======================================================================*/
static	void	tmc_dustart(void)
{
	if (tm_dustart != TM_STOP) {
		if (--tm_dustart == 0) {	/* �R�O�b */
			sg_mode = SGM_NORMAL;
			sg_bit_status |= BIT_SG_232T;
		}
	}
}

/*=======================================================================
 |
 |		�f�[�^�X�V�����̊Ď�����
 |
 |	void	tmc_update()
 |
 =======================================================================*/
static	void	tmc_update(void)
{
	if (tm_update != TM_STOP) {
		if (--tm_update == 0) {		/* �P�b */
			/* �P�T�T�R�a�f�[�^�X�V���[�g�ۑ� */
			rate_1553B = count_1553B;

			/* �P�T�T�R�a�f�[�^��M�J�E���^�N���A */
			count_1553B = 0;

			/* �a�t�r �e�`�h�k�`�F�b�N */
			if ((debug_flag & DBF_1553B) == 0) {
				if (rate_1553B == 0) {
					if (bus_fail == 0) {
						if (++count_nodata == 5)
							bus_fail = 1;
					}
				} else {
					count_nodata = 0;
					bus_fail = 0;
				}
			}

			/* �\���X�V���[�g�ۑ� */
			rate_gscint = count_gscint;

			/* �\���X�V�J�E���^�N���A */
			count_gscint = 0;

			/* �^�C�}�[�ăZ�b�g */
			tm_update = TM_UPDATE;
		}
	}
}

/*=======================================================================
 |
 |		�c�^�t�փf�[�^���M�y�уf�[�^��M�Ď�����
 |
 |	void	tmc_dudata()
 |
 =======================================================================*/
static	void	tmc_dudata(void)
{
	if (tm_dudata != TM_STOP) {
		if (--tm_dudata == 0) {
			/* �f�[�^��M�`�F�b�N */
			if (du_rcvflag == 0) {
				if (sg_mode != SGM_PBIT)
					sg_bit_status |= BIT_SG_232T;

				/* �c�t�`�q�s������ */
				if (debug_mode != DBM_VT)
					set_DUART();
			} else
				sg_bit_status &= ~BIT_SG_232T;

			/* ���M�o�b�t�@�N���A */
			SET_WORD(du_send_data, 0, 3);

			/* ��M�Ď��^�C�}�[�Z�b�g */
			tm_dudata = TM_DUDATA;

			switch (sg_mode) {
			case SGM_IBIT:
				switch (ibit_mode) {
				case IBIT_START:
					du_send_data[5] |= 0x01;
					ibit_status(BIT_OK);
					tm_dudata = TM_DUIBIT;
					break;
				case IBIT_DUBIT:
					ibit_status(BIT_NG);
					break;
				}
				break;
			case SGM_RBIT:
				if ((rbit_mode & RBIT_DUBIT) == 0) {
					du_send_data[5] |= 0x02;
					tm_dudata = TM_DURBIT;
					rbit_mode |= RBIT_DUBIT;
				} else {
					rbit_mode |= RBIT_DU_END;
				}
				if ((rbit_mode & (RBIT_SG_END | RBIT_DU_END))
						== (RBIT_SG_END | RBIT_DU_END))
					sg_mode = SGM_NORMAL;
				break;
			case SGM_NORMAL:
				switch (echo_mode) {
				case ECHO_START:
					du_send_data[4] |= 0x80;
					echo_mode = ECHO_WAIT;
					break;
				case ECHO_WAIT:
					echo_status = ECHO_NG;
					echo_mode = ECHO_END;
					break;
				}
				break;
			}

			/* �f�[�^���M */
			rsa_send(du_send_data, 6);

			du_rcvflag = 0;
		}
	}
}

/*=======================================================================
 |
 |		�c�^�t����̃f�[�^�̕����ԃ^�C���A�E�g���o����
 |
 |	void	tmc_duchar()
 |
 =======================================================================*/
static	void	tmc_duchar(void)
{
	if (tm_duchar != TM_STOP) {
		if (--tm_duchar == 0) {
			rsa_rcvcounter = 0;	/* ��M�f�[�^���̂Ă� */
		}
	}
}

/*=======================================================================
 |
 |		�f�W�^���\���̍X�V�^�C�~���O�̃J�E���g����
 |
 |	void	tmc_digital()
 |
 =======================================================================*/
static	void	tmc_digital(void)
{
	if (tm_digital != TM_STOP) {
		if (--tm_digital == 0) {
			/* �f�W�^���\���X�V�t���O�Z�b�g */
			digital_update_flag = 1;

			/* �f�W�^���\���X�V�^�C�}�[�ăZ�b�g */
			tm_digital = TM_DIGITAL;
		}
	}
}

/*=======================================================================
 |
 |		�h�|�a�h�s�^�C�}�[
 |
 |	void	tmc_ibit()
 |
 =======================================================================*/
static	void	tmc_ibit(void)
{
	if (tm_ibit != TM_STOP) {
		if (--tm_ibit == 0) {
			switch (ibit_mode) {
			case IBIT_TPTN1:
			case IBIT_TPTN2:
			case IBIT_TPTN3:
			case IBIT_TPTN4:
				ibit_status(BIT_OK);
				break;
			case IBIT_SWITCH:
				sg_bit_status |= BIT_SG_SW;
				ibit_status(BIT_NG);
				break;
			case IBIT_RESULT:
				break;
			}
		}
	}
}

/*=======================================================================
 |
 |		�f�r�b�`��I�����荞�ݒ�~���̃o�b�N�A�b�v����
 |
 |	void	tmc_gscbackup()
 |
 =======================================================================*/
static	void	tmc_gscbackup(void)
{
	if (tm_gscbackup != TM_STOP) {
		if (--tm_gscbackup == 0) {
			/* �c�^�o���q�`�o�ɃZ�b�g */
			*(DP *)GSC_RAP_DP = dp_tmp;

			/* �o�^�`�r�b�g���q�`�o�ɃZ�b�g */
			COPY_WORD(GSC_RAP_PA, pa_tmp, N_PABIT);

			/* �v�Z�J�n�t���O�Z�b�g */
			calc_flag = 1;

			/* �f�r�b�{�[�h�ُ� */
			gsc_board_test = BIT_NG;

			tm_gscbackup = TM_GSCBACKUP;
		}
	}
}

/*=======================================================================
 |
 |		�f�o�b�O�p���̓f�[�^�V�~�����[�V��������
 |
 |	void	tmc_simulation()
 |
 =======================================================================*/
static	void	tmc_simulation(void)
{
	if (tm_simulation != TM_STOP) {
		if (--tm_simulation == 0) {
			debug_simulation();
			tm_simulation = TM_SIMULATION;
		}
	}
}