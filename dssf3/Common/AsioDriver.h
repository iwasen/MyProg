#pragma once

#define PPC 0
#define WINDOWS 1
#define SGI 0
#define SUN 0
#define LINUX 0
#define BEOS 0

#define NATIVE_INT64 0
#define IEEE754_64FLOAT 1

#define DRVERR			-5000
#define DRVERR_INVALID_PARAM		DRVERR-1
#define DRVERR_DEVICE_ALREADY_OPEN	DRVERR-2
#define DRVERR_DEVICE_NOT_FOUND		DRVERR-3

#define MAXPATHLEN			512
#define MAXDRVNAMELEN		128

struct asiodrvstruct
{
	int						drvID;
	CLSID					clsid;
	char					dllpath[MAXPATHLEN];
	char					drvname[MAXDRVNAMELEN];
	LPVOID					asiodrv;
	struct asiodrvstruct	*next;
};

typedef struct ASIOSamples {
	unsigned long hi;
	unsigned long lo;
} ASIOSamples;

// Time format is Nanoseconds.
typedef struct ASIOTimeStamp {
	unsigned long hi;
	unsigned long lo;
} ASIOTimeStamp;

// Samplerates are expressed in IEEE 754 64 bit double float,
// native format as host computer
typedef double ASIOSampleRate;

// Boolean values are expressed as long
typedef long ASIOBool;
enum {
	ASIOFalse = 0,
	ASIOTrue = 1
};

// Sample Types are expressed as long
typedef long ASIOSampleType;
enum {
	ASIOSTInt16MSB   = 0,
	ASIOSTInt24MSB   = 1,		// used for 20 bits as well
	ASIOSTInt32MSB   = 2,
	ASIOSTFloat32MSB = 3,		// IEEE 754 32 bit float
	ASIOSTFloat64MSB = 4,		// IEEE 754 64 bit double float
	ASIOSTInt32MSB16 = 8,		// 32 bit data with 16 bit alignment
	ASIOSTInt32MSB18 = 9,		// 32 bit data with 18 bit alignment
	ASIOSTInt32MSB20 = 10,		// 32 bit data with 20 bit alignment
	ASIOSTInt32MSB24 = 11,		// 32 bit data with 24 bit alignment
	ASIOSTInt16LSB   = 16,
	ASIOSTInt24LSB   = 17,		// used for 20 bits as well
	ASIOSTInt32LSB   = 18,
	ASIOSTFloat32LSB = 19,		// IEEE 754 32 bit float, as found on Intel x86 architecture
	ASIOSTFloat64LSB = 20, 		// IEEE 754 64 bit double float, as found on Intel x86 architecture
	ASIOSTInt32LSB16 = 24,		// 32 bit data with 18 bit alignment
	ASIOSTInt32LSB18 = 25,		// 32 bit data with 18 bit alignment
	ASIOSTInt32LSB20 = 26,		// 32 bit data with 20 bit alignment
	ASIOSTInt32LSB24 = 27,		// 32 bit data with 24 bit alignment
	ASIOSTDSDInt8LSB1   = 32,		// DSD 1 bit data, 8 samples per byte. First sample in Least significant bit.
	ASIOSTDSDInt8MSB1   = 33,		// DSD 1 bit data, 8 samples per byte. First sample in Most significant bit.
	ASIOSTDSDInt8NER8	= 40,		// DSD 8 bit data, 1 sample per byte. No Endianness required.
	ASIOSTLastEntry
};

typedef long ASIOError;
enum {
	ASE_OK = 0,             // This value will be returned whenever the call succeeded
	ASE_SUCCESS = 0x3f4847a0,	// unique success return value for ASIOFuture calls
	ASE_NotPresent = -1000, // hardware input or output is not present or available
	ASE_HWMalfunction,      // hardware is malfunctioning (can be returned by any ASIO function)
	ASE_InvalidParameter,   // input parameter invalid
	ASE_InvalidMode,        // hardware is in a bad mode or used in a bad mode
	ASE_SPNotAdvancing,     // hardware is not running when sample position is inquired
	ASE_NoClock,            // sample clock or rate cannot be determined or is not present
	ASE_NoMemory            // not enough memory for completing the request
};

typedef struct ASIOTimeCode
{
	double          speed;                  // speed relation (fraction of nominal speed)
	ASIOSamples     timeCodeSamples;        // time in samples
	unsigned long   flags;                  // some information flags (see below)
	char future[64];
} ASIOTimeCode;

typedef enum ASIOTimeCodeFlags
{
	kTcValid                = 1,
	kTcRunning              = 1 << 1,
	kTcReverse              = 1 << 2,
	kTcOnspeed              = 1 << 3,
	kTcStill                = 1 << 4,
	kTcSpeedValid           = 1 << 8
} ASIOTimeCodeFlags;

typedef struct AsioTimeInfo
{
	double          speed;                  // absolute speed (1. = nominal)
	ASIOTimeStamp   systemTime;             // system time related to samplePosition, in nanoseconds
	ASIOSamples     samplePosition;
	ASIOSampleRate  sampleRate;             // current rate
	unsigned long flags;                    // (see below)
	char reserved[12];
} AsioTimeInfo;

typedef enum AsioTimeInfoFlags
{
	kSystemTimeValid        = 1,            // must always be valid
	kSamplePositionValid    = 1 << 1,       // must always be valid
	kSampleRateValid        = 1 << 2,
	kSpeedValid             = 1 << 3,
	kSampleRateChanged      = 1 << 4,
	kClockSourceChanged     = 1 << 5
} AsioTimeInfoFlags;

typedef struct ASIOTime                          // both input/output
{
	long reserved[4];                       // must be 0
	struct AsioTimeInfo     timeInfo;       // required
	struct ASIOTimeCode     timeCode;       // optional, evaluated if (timeCode.flags & kTcValid)
} ASIOTime;

typedef struct ASIOCallbacks
{
	void (*bufferSwitch) (long doubleBufferIndex, ASIOBool directProcess);
	void (*sampleRateDidChange) (ASIOSampleRate sRate);
	long (*asioMessage) (long selector, long value, void* message, double* opt);
	ASIOTime* (*bufferSwitchTimeInfo) (ASIOTime* params, long doubleBufferIndex, ASIOBool directProcess);
} ASIOCallbacks;

enum
{
	kAsioSelectorSupported = 1,	// selector in <value>, returns 1L if supported,
    kAsioEngineVersion,			// returns engine (host) asio implementation version,
	kAsioResetRequest,			// request driver reset. if accepted, this
	kAsioBufferSizeChange,		// not yet supported, will currently always return 0L.
	kAsioResyncRequest,			// the driver went out of sync, such that
	kAsioLatenciesChanged, 		// the drivers latencies have changed. The engine
	kAsioSupportsTimeInfo,		// if host returns true here, it will expect the
	kAsioSupportsTimeCode,		//
	kAsioMMCCommand,			// unused - value: number of commands, message points to mmc commands
	kAsioSupportsInputMonitor,	// kAsioSupportsXXX return 1 if host supports this
	kAsioSupportsInputGain,     // unused and undefined
	kAsioSupportsInputMeter,    // unused and undefined
	kAsioSupportsOutputGain,    // unused and undefined
	kAsioSupportsOutputMeter,   // unused and undefined
	kAsioOverload,              // driver detected an overload
	kAsioNumMessageSelectors
};

typedef struct ASIODriverInfo
{
	long asioVersion;		// currently, 2
	long driverVersion;		// driver specific
	char name[32];
	char errorMessage[124];
	void *sysRef;			// on input: system reference
} ASIODriverInfo;

typedef struct ASIOClockSource
{
	long index;					// as used for ASIOSetClockSource()
	long associatedChannel;		// for instance, S/PDIF or AES/EBU
	long associatedGroup;		// see channel groups (ASIOGetChannelInfo())
	ASIOBool isCurrentSource;	// ASIOTrue if this is the current clock source
	char name[32];				// for user selection
} ASIOClockSource;

typedef struct ASIOChannelInfo
{
	long channel;			// on input, channel index
	ASIOBool isInput;		// on input
	ASIOBool isActive;		// on exit
	long channelGroup;		// dto
	ASIOSampleType type;	// dto
	char name[32];			// dto
} ASIOChannelInfo;

typedef struct ASIOBufferInfo
{
	ASIOBool isInput;			// on input:  ASIOTrue: input, else output
	long channelNum;			// on input:  channel index
	void *buffers[2];			// on output: double buffer addresses
} ASIOBufferInfo;

enum
{
	kAsioEnableTimeCodeRead = 1,	// no arguments
	kAsioDisableTimeCodeRead,		// no arguments
	kAsioSetInputMonitor,			// ASIOInputMonitor* in params
	kAsioTransport,					// ASIOTransportParameters* in params
	kAsioSetInputGain,				// ASIOChannelControls* in params, apply gain
	kAsioGetInputMeter,				// ASIOChannelControls* in params, fill meter
	kAsioSetOutputGain,				// ASIOChannelControls* in params, apply gain
	kAsioGetOutputMeter,			// ASIOChannelControls* in params, fill meter
	kAsioCanInputMonitor,			// no arguments for kAsioCanXXX selectors
	kAsioCanTimeInfo,
	kAsioCanTimeCode,
	kAsioCanTransport,
	kAsioCanInputGain,
	kAsioCanInputMeter,
	kAsioCanOutputGain,
	kAsioCanOutputMeter,
	kAsioSetIoFormat			= 0x23111961,		/* ASIOIoFormat * in params.			*/
	kAsioGetIoFormat			= 0x23111983,		/* ASIOIoFormat * in params.			*/
	kAsioCanDoIoFormat			= 0x23112004,		/* ASIOIoFormat * in params.			*/
};

typedef struct ASIOInputMonitor
{
	long input;		// this input was set to monitor (or off), -1: all
	long output;	// suggested output for monitoring the input (if so)
	long gain;		// suggested gain, ranging 0 - 0x7fffffffL (-inf to +12 dB)
	ASIOBool state;	// ASIOTrue => on, ASIOFalse => off
	long pan;		// suggested pan, 0 => all left, 0x7fffffff => right
} ASIOInputMonitor;

typedef struct ASIOChannelControls
{
	long channel;			// on input, channel index
	ASIOBool isInput;		// on input
	long gain;				// on input,  ranges 0 thru 0x7fffffff
	long meter;				// on return, ranges 0 thru 0x7fffffff
	char future[32];
} ASIOChannelControls;

typedef struct ASIOTransportParameters
{
	long command;		// see enum below
	ASIOSamples samplePosition;
	long track;
	long trackSwitches[16];		// 512 tracks on/off
	char future[64];
} ASIOTransportParameters;

enum
{
	kTransStart = 1,
	kTransStop,
	kTransLocate,		// to samplePosition
	kTransPunchIn,
	kTransPunchOut,
	kTransxxxOn,		// track
	kTransxxxOff,		// track
	kTransMonitorOn,	// track
	kTransMonitorOff,	// track
	kTransxxx,			// trackSwitches
	kTransMonitor		// trackSwitches
};

typedef long int ASIOIoFormatType;

enum ASIOIoFormatType_e
{
	kASIOFormatInvalid = -1,
	kASIOPCMFormat = 0,
	kASIODSDFormat = 1,
};

typedef struct ASIOIoFormat_s
{
	ASIOIoFormatType	FormatType;
	char				future[512-sizeof(ASIOIoFormatType)];
} ASIOIoFormat;

typedef struct asiodrvstruct ASIODRVSTRUCT;
typedef ASIODRVSTRUCT	*LPASIODRVSTRUCT;

interface IASIO : public IUnknown
{

	virtual ASIOBool init(void *sysHandle) = 0;
	virtual void getDriverName(char *name) = 0;
	virtual long getDriverVersion() = 0;
	virtual void getErrorMessage(char *string) = 0;
	virtual ASIOError start() = 0;
	virtual ASIOError stop() = 0;
	virtual ASIOError getChannels(long *numInputChannels, long *numOutputChannels) = 0;
	virtual ASIOError getLatencies(long *inputLatency, long *outputLatency) = 0;
	virtual ASIOError getBufferSize(long *minSize, long *maxSize, long *preferredSize, long *granularity) = 0;
	virtual ASIOError canSampleRate(ASIOSampleRate sampleRate) = 0;
	virtual ASIOError getSampleRate(ASIOSampleRate *sampleRate) = 0;
	virtual ASIOError setSampleRate(ASIOSampleRate sampleRate) = 0;
	virtual ASIOError getClockSources(ASIOClockSource *clocks, long *numSources) = 0;
	virtual ASIOError setClockSource(long reference) = 0;
	virtual ASIOError getSamplePosition(ASIOSamples *sPos, ASIOTimeStamp *tStamp) = 0;
	virtual ASIOError getChannelInfo(ASIOChannelInfo *info) = 0;
	virtual ASIOError createBuffers(ASIOBufferInfo *bufferInfos, long numChannels, long bufferSize, ASIOCallbacks *callbacks) = 0;
	virtual ASIOError disposeBuffers() = 0;
	virtual ASIOError controlPanel() = 0;
	virtual ASIOError future(long selector,void *opt) = 0;
	virtual ASIOError outputReady() = 0;
};

class CAsioDriver
{
public:
	CAsioDriver(void);

	static void Initialize(void);
	static void UnInitialize(void);
	LONG asioOpenDriver(INT_PTR);
	LONG asioCloseDriver();
	static LONG asioGetNumDev(VOID);
	static LONG asioGetDriverName(int drvID, CString &drvname);
	static long getDriverNames(CStringArray &names);
	INT_PTR getCurrentDriverIndex() {return m_nCurIndex;}
	void DispErrorMessage();

	ASIOError ASIOInit();
	ASIOError ASIOExit(void);
	ASIOError ASIOStart(void);
	ASIOError ASIOStop(void);
	ASIOError ASIOGetChannels(long *numInputChannels, long *numOutputChannels);
	ASIOError ASIOGetLatencies(long *inputLatency, long *outputLatency);
	ASIOError ASIOGetBufferSize(long *minSize, long *maxSize, long *preferredSize, long *granularity);
	ASIOError ASIOCanSampleRate(ASIOSampleRate sampleRate);
	ASIOError ASIOGetSampleRate(ASIOSampleRate *currentRate);
	ASIOError ASIOSetSampleRate(ASIOSampleRate sampleRate);
	ASIOError ASIOGetClockSources(ASIOClockSource *clocks, long *numSources);
	ASIOError ASIOSetClockSource(long index);
	ASIOError ASIOGetSamplePosition(ASIOSamples *sPos, ASIOTimeStamp *tStamp);
	ASIOError ASIOGetChannelInfo(ASIOChannelInfo *info);
	ASIOError ASIOCreateBuffers(ASIOBufferInfo *bufferInfos, long numChannels, long bufferSize, ASIOCallbacks *callbacks);
	ASIOError ASIODisposeBuffers(void);
	ASIOError ASIOControlPanel(void);
	ASIOError ASIOFuture(long selector, void *params);
	ASIOError ASIOOutputReady(void);

	static LPASIODRVSTRUCT m_pDrvList;
	static int m_nDrv;
	ASIODriverInfo m_asioDriverInfo;

protected:
	static LONG findDrvPath(char *clsidstr, char *dllpath, int dllpathsize);
	static LPASIODRVSTRUCT newDrvStruct(HKEY hkey, char *keyname, int drvID, LPASIODRVSTRUCT lpdrv);
	static void deleteDrvStruct(LPASIODRVSTRUCT lpdrv);
	static LPASIODRVSTRUCT getDrvStruct(INT_PTR drvID, LPASIODRVSTRUCT lpdrv);

	IASIO *m_pAsioDriver;
	INT_PTR m_nCurIndex;
};
