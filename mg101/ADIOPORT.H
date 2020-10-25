/*	Z80-SIO i/o port address	*/

#define	P_SAD		(char)0x70	/* A chanel data	*/
#define	P_SBD		(char)0x71	/* B chanel data	*/
#define	P_SAC		(char)0x72	/* A chanel command	*/
#define	P_SBC		(char)0x73	/* B chanel command	*/

/*	Z80-CTC i/o port address	*/

#define	P_C0C		(char)0xb0	/* 0 chanel command	*/
#define	P_C1C		(char)0xb1	/* 1 chanel command	*/
#define	P_C2C		(char)0xb2	/* 2 chanel command	*/
#define	P_C3C		(char)0xb3	/* 3 chanel command	*/

/*	i8255 i/o port address		*/

#define	P_PAD		(char)0xd0	/* A port data		*/
#define	P_PBD		(char)0xd1	/* B port data		*/
#define	P_PCD		(char)0xd2	/* C port data		*/
#define	P_PMC		(char)0xd3	/* mode control		*/
