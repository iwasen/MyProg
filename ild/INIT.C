/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: �p���[�I������			*
 *		�t�@�C����	: init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=init

#include "sg_cpu.h"

/*	�u�h�b�O�U�W�R���g���[�����W�X�^�ݒ�l */
static	BYTE_REG_DATA	VIC068[] = {
        {REG_VIC_EGIVBR, 0x50, 100},
        {REG_VIC_ICGSIVBR, 0x58, 100},
        {REG_VIC_ICMSIVBR, 0x5C, 100},
        {REG_VIC_LIVBR,  0x40, 100},
        {REG_VIC_LICR1,  0xb2, 100},
        {REG_VIC_LICR2,  0xb1, 100},
        {REG_VIC_VIVR1,  0x48, 100},
        {REG_VIC_VIVR2,  0x49, 100},
        {REG_VIC_VIVR3,  0x4a, 100},
        {REG_VIC_VIVR4,  0x4b, 100},
        {REG_VIC_VIVR5,  0x4c, 100},
        {REG_VIC_VIVR6,  0x4d, 100},
        {REG_VIC_VIVR7,  0x4e, 100},
        {REG_VIC_SS0CR0, 0xc0, 100},
        {REG_VIC_SS1CR0, 0x00, 100},
        {REG_VIC_LBTR,   0x00, 100},
        {REG_VIC_TTR,    0x6a, 100},
        {REG_VIC_RCR,    0x00, 100},
        {REG_VIC_BESR,   0x80, 100},
        {REG_VIC_ARCR,   0x60, 100}
};

/*	�c�t�`�q�s�R���g���[�����W�X�^�ݒ�l */
static	BYTE_REG_DATA	DUART[] = {
	{REG_DUART_CRA,  0x0a, 100},
	{REG_DUART_CRA,  0x10, 100},
	{REG_DUART_CRA,  0x20, 100},
	{REG_DUART_CRA,  0x30, 100},
	{REG_DUART_CRA,  0x40, 100},
	{REG_DUART_CRA,  0x50, 100},
	{REG_DUART_MR1A, 0x07, 100},
	{REG_DUART_MR2A, 0x0f, 100},
	{REG_DUART_CSRA, 0xbb, 100},
	{REG_DUART_CRA,  0x05, 100},
	{REG_DUART_CRB,  0x0a, 100},
	{REG_DUART_CRB,  0x10, 100},
	{REG_DUART_CRB,  0x20, 100},
	{REG_DUART_CRB,  0x30, 100},
	{REG_DUART_CRB,  0x40, 100},
	{REG_DUART_CRB,  0x50, 100},
	{REG_DUART_MR1B, 0x17, 100},
	{REG_DUART_MR2B, 0x07, 100},
	{REG_DUART_CSRB, 0xbb, 100},
	{REG_DUART_CRB,  0x05, 100},
	{REG_DUART_IMR,  0x22, 100}
};

/*=======================================================================
 |
 |		�p���[�I������
 |
 |	void	power_on()
 |
 =======================================================================*/
void	power_on(void)
{
	register word	*p;

	/* �k�d�c�_�� */
	*REG_WDT = 0x9d;

	/* �r�q�`�l�[���N���A */
	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
		*p = 0;

	/* �u�h�b�O�U�W�R���g���[�����W�X�^�ݒ� */
	write_byte_register(VIC068,
			sizeof(VIC068) / sizeof(BYTE_REG_DATA));

	/* �c�t�`�q�s�R���g���[�����W�X�^�ݒ� */
	set_DUART();

	/* �f�o�b�O���[�h�����l�ݒ� */
	debug_flag = debug_mode;

	/* �d�d�o�q�n�l�T���`�F�b�N */
	rom_check();

	/* �r�q�`�l�`�F�b�N */
	ram_check();

	/* �q�`�o�[���N���A */
	for (p = ADR_RAP; p < ADR_RAP_END; p++)
		*p = 0;

	/* �l�a�h�{�[�h�a�h�s�`�F�b�N�J�n */
	mbibit();

	/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
	tmc_mbibit = 0;			/* �^�C�}�[�J�E���^�N���A */
	tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */

	/* �r�f�I���[�h�Z�b�g */
	video_mode = 0x0a;

	/* �p���[�I���V�[�P���X�ԍ������ݒ� */
	seq_power_on = 1;

	/* ���荞�݃}�X�N���� */
	mskclr();
	int_enable();

	/* �f�o�b�O���[�h���b�Z�[�W�\�� */
	msgout("\x0d\x0a*DEBUG MODE ? ");
	if (debug_flag) {
		msgout("Y\x0d\x0a");
		msgout(">");
	}

	/* �f�r�b���W�X�^�Z�b�g */
	*REG_GSC_OUTPUT = 0x0001;
	*REG_GSC_OPRATE = 0x2000;

	/* ���̓f�[�^���� */
	inpdata();
}

/*=======================================================================
 |
 |		�c�t�`�q�s�R���g���[�����W�X�^�̐ݒ�
 |
 |	void	set_DUART()
 |
 =======================================================================*/
void	set_DUART(void)
{
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(BYTE_REG_DATA));

	/* set DUART int mask data */
	rs_IMR = 0x22;
}

/*=======================================================================
 |
 |		�p���[�I���V�[�P���X����
 |
 |	void	power_on_seq()
 |
 =======================================================================*/
void	power_on_seq(void)
{
	/* �p���[�I���V�[�P���X�^�C�}�[���Z�b�g */
	tmc_poweron = 0;

	switch (seq_power_on++) {
	case 1:
		/* �p���[�I���V�[�P���X�^�C�}�[�N�� */
		tmf_poweron = TIMER_START;

		/* �e�X�g�p�^�[���Q�i�S�ʔ��j�\�� */
		SET_PABIT(PA_T001);
		break;
	case 2:
		/* �p���[�I���V�[�P���X�^�C�}�[�N�� */
		tmf_poweron = TIMER_START;

		/* �e�X�g�p�^�[���P�i�S�ʍ��j�\�� */
		SET_WORD(pa, 0, N_PABIT);
		SET_PABIT(PA_T000);
		break;
	case 3:
		/* �p���[�I���V�[�P���X�^�C�}�[�N�� */
		tmf_poweron = TIMER_START;

		/* �e�X�g�p�^�[���R�i�W�F�j�\�� */
		SET_WORD(pa, 0, N_PABIT);
		SET_PABIT(PA_T002);
		break;
	case 4:
		/* �p���[�I���V�[�P���X�^�C�}�[�N�� */
		tmf_poweron = TIMER_START;

		/* �V���{���\�� */
		input_data[0] = 300;
		input_data[1] = 15000;
		input_data[7] = 1840;
		input_data[10] = 1;
		input_data[11] = 0x9990;
		input_data[12] = 2;
		input_data[13] = 995;
		input_data[14] = 9995;
		input_data[21] = 1;
		sg_mode = MODE_SAKUTEKI1;
		calc_dp(0);		/* �c�^�o�v�Z */
		break;
	case 5:
		/* �p���[�I���V�[�P���X�^�C�}�[��~ */
		tmf_poweron = TIMER_STOP;

		/* �o�^�`�r�b�g�N���A */
		SET_WORD(pa, 0, N_PABIT);

		/* ���̓f�[�^�N���A */
		SET_WORD(input_data, 0, 512);

		/* �r�^�f���[�h���Z�b�g */
		sg_mode = MODE_NOSYMBOL;

		/* �r�f�I���[�h�Z�b�g */
		video_mode = 0x02;

		if (debug_flag == 0) {
			/* �f�o�b�O���[�h�����\�� */
			msgout("N\x0d\x0a");

			/* �q�r�|�Q�R�Q�b�`���l���a��M��~ */
			*REG_DUART_CRB = 0x02;
			rs_IMR &= ~0x20;
			*REG_DUART_IMR = rs_IMR;
		}

		/* �p���[�I���V�[�P���X�I�� */
		seq_power_on = 0;
		break;
	}

	/* �c�^�o�C�o�^�`���e���|�����G���A�ɃR�s�[ */
	*REG_VIC_VICR4 = 0x84;	/* �f�r�b�`��I�����荞�݋֎~ */
	dp_tmp = dp;			/* �c�^�o�R�s�[ */
	COPY_WORD(pa_tmp, pa, N_PABIT);	/* �o�^�`�R�s�[ */
	*REG_VIC_VICR4 = 0x04;	/* �f�r�b�`��I�����荞�݋֎~���� */
}