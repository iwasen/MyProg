/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �p���[�I������			*
 *		�t�@�C����	: cpu20_init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=cpu20_init

#include "sg_cpu.h"

/*	�o�C�g���W�X�^�ݒ�f�[�^	*/
struct	byte_reg_data	{
	byte	*address;
	byte	data;
	short	wait_time;
};

/*	�u�h�b�O�U�W�R���g���[�����W�X�^�ݒ�l */
static	struct	byte_reg_data VIC068[] = {
	{REG_VIC_LIVBR,  0x40, 0},
	{REG_VIC_LICR1,  0xaa, 0},
	{REG_VIC_LICR2,  0xa9, 0},
	{REG_VIC_VIVR1,  0x48, 0},
	{REG_VIC_VIVR2,  0x49, 0},
	{REG_VIC_VIVR3,  0x4a, 0},
	{REG_VIC_VIVR4,  0x4b, 0},
	{REG_VIC_VIVR5,  0x4c, 0},
	{REG_VIC_VIVR6,  0x4d, 0},
	{REG_VIC_VIVR7,  0x4e, 0},
	{REG_VIC_SS0CR0, 0xc0, 0},
	{REG_VIC_SS1CR0, 0x00, 0},
	{REG_VIC_LBTR,   0x00, 0},
	{REG_VIC_TTR,    0x6c, 0},
	{REG_VIC_RCR,    0x00, 0},
	{REG_VIC_BESR,   0x80, 0},
	{REG_VIC_ARCR,   0x60, 0}
};

/*	�c�t�`�q�s�R���g���[�����W�X�^�ݒ�l */
static	struct	byte_reg_data DUART[] = {
	{REG_DUART_CRA,  0x0a, 0},
	{REG_DUART_CRA,  0x10, 100},
	{REG_DUART_CRA,  0x20, 100},
	{REG_DUART_CRA,  0x30, 100},
	{REG_DUART_CRA,  0x40, 100},
	{REG_DUART_CRA,  0x50, 100},
	{REG_DUART_MR1A, 0x0f, 0},
	{REG_DUART_MR2A, 0x0f, 0},
	{REG_DUART_CSRA, 0xbb, 0},
	{REG_DUART_CRA,  0x06, 0},
	{REG_DUART_CRB,  0x0a, 0},
	{REG_DUART_CRB,  0x10, 100},
	{REG_DUART_CRB,  0x20, 100},
	{REG_DUART_CRB,  0x30, 100},
	{REG_DUART_CRB,  0x40, 100},
	{REG_DUART_CRB,  0x50, 100},
	{REG_DUART_MR1B, 0x0f, 0},
	{REG_DUART_MR2B, 0x0f, 0},
	{REG_DUART_CSRB, 0xbb, 0},
	{REG_DUART_CRB,  0x09, 0},
	{REG_DUART_IMR,  0x20, 0}
};

/*	�����֐�	*/
static	void	write_byte_register(struct byte_reg_data *, short);
static	void	memory_error(void);

/*=======================================================================
 |
 |		�p���[�I������
 |
 |	void	power_on()
 |
 =======================================================================*/
void	power_on()
{
	register short	i;
	register word	sum, *p;

	/* �v�c�s���Z�b�g */
	*REG_WDT = 0xfd;

	/* �d�d�o�q�n�l�T���`�F�b�N */
	for (i = 0; i < 3; i++) {
		sum = 0;
		for (p = ADR_EEPROM; p < ADR_PRGEND; p++)
			sum += *p;
		if (sum != *ADR_PRGEND)
			memory_error();
	}

	/* �r�q�`�l�`�F�b�N */
	for (i = 0; i < 3; i++) {
		/* 0x5555���������� */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			*p = 0x5555;

		/* 0x5555�ł��邱�Ƃ��`�F�b�N���� */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			if (*p != 0x5555)
				memory_error();

		/* 0xaaaa���������� */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			*p = 0xaaaa;

		/* 0xaaaa�ł��邱�Ƃ��`�F�b�N���� */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			if (*p != 0xaaaa)
				memory_error();
	}

	/* �r�q�`�l�[���N���A */
	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
		*p = 0;

	/* �u�h�b�O�U�W�R���g���[�����W�X�^�ݒ� */
	write_byte_register(VIC068,
			sizeof(VIC068) / sizeof(struct byte_reg_data));

	/* �q�`�o�[���N���A */
	for (p = ADR_RAP; p < ADR_RAP_END; p++)
		*p = 0;

	/* �c�t�`�q�s�R���g���[�����W�X�^�ݒ� */
	set_DUART();

	/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
	tmc_mbibit = 0;			/* �^�C�}�[�J�E���^�N���A */
	tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */

	/* �n�q�j�h�c�R�[�� */
	ORKID();
}

/*=======================================================================
 |
 |		�c�t�`�q�s�R���g���[�����W�X�^�̐ݒ�
 |
 |	void	set_DUART()
 |
 =======================================================================*/
void	set_DUART()
{
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(struct byte_reg_data));
}

/*=======================================================================
 |
 |		�o�C�g���W�X�^�������ݏ���
 |
 |	void	write_byte_register(data_tbl, size)
 |
 |		struct byte_reg_data	*data_tbl;	���W�X�^�ݒ�e�[�u��
 |		short	size;		�e�[�u���T�C�Y
 |
 =======================================================================*/
static	void	write_byte_register(struct byte_reg_data *data_tbl, short size)
{
	short	i;

	while (size--) {
		/* �f�[�^�������� */
		*data_tbl->address = data_tbl->data;
		data_tbl++;		/* �e�[�u���|�C���^�X�V */

		/* �E�G�C�g */
		for (i = 0; i < data_tbl->wait_time; i++)
			;
	}
}

/*=======================================================================
 |
 |		�������G���[����
 |
 |	void	memory_error()
 |
 =======================================================================*/
static	void	memory_error()
{
	long	a;

	/* �T�b�҂� */
	for (a = 0; a < 1000000L; a++)
		*REG_WDT = 0xfd;	/* �v�c�s���Z�b�g */

	/* �������[�v�i�v�c�s�^�C���A�E�g��҂j */
	for (;;);
}