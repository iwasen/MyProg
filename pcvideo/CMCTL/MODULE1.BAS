Attribute VB_Name = "Module1"
Option Explicit

Type CAMERAOPT
    PanSpeed As Integer
    TiltSpeed As Integer
    ZoomSpeed As Integer
    ShutterSpeed As Integer
    AutoFocus As Integer
    LightAdjust As Integer
    AutoWhiteBalance As Integer
    WideConverter As Integer
End Type

Type CAMERAPOS
    Pan As Integer
    Tilt As Integer
    Zoom As Integer
End Type

Type PRESET
    Pos As CAMERAPOS
    Comment As String * 31
End Type

Type CAPTUREMODE
    size As Integer
    color As Integer
    compressMode As Integer
    compressQuality As Integer
End Type

Type RGBBUF24
    blue As Byte
    green As Byte
    red As Byte
End Type

Type VIDEOBUF
    width As Integer
    height As Integer
End Type

Global handle As Integer
Global mode As CAPTUREMODE
Global VideoSize(15) As VIDEOBUF
Global CameraOptBuf As CAMERAOPT
Global CameraPosBuf As CAMERAPOS
Global PresetBuf(7) As PRESET
Global OKFlag As Integer

Public Const CM_RESET = 0
Public Const CM_HOME_POSITION = 1
Public Const CM_PAN_RIGHT = 2
Public Const CM_PAN_LEFT = 3
Public Const CM_TILT_UP = 4
Public Const CM_TILT_DOWN = 5
Public Const CM_STOP = 6
Public Const CM_ZOOM_WIDE = 7
Public Const CM_ZOOM_TELE = 8
Public Const CM_ZOOM_STOP = 9
Public Const CM_FOCUS_AUTO = 10
Public Const CM_FOCUS_MANUAL = 11
Public Const CM_FOCUS_NEAR = 12
Public Const CM_FOCUS_FAR = 13
Public Const CM_POWER_ON = 14
Public Const CM_POWER_OFF = 15

Declare Function CMOpen Lib "cmctl.dll" (ByVal Conenct As String, ByVal UserName As String, ByVal option1 As Integer, handle As Integer) As Integer
Declare Function CMClose Lib "cmctl.dll" (ByVal handle As Integer) As Integer
Declare Function CMControl Lib "cmctl.dll" (ByVal handle As Integer, ByVal command As Integer) As Integer
Declare Function CMGetCameraOpt Lib "cmctl.dll" (ByVal handle As Integer, opt As CAMERAOPT) As Integer
Declare Function CMSetCameraOpt Lib "cmctl.dll" (ByVal handle As Integer, opt As CAMERAOPT) As Integer
Declare Function CMGetPreset Lib "cmctl.dll" (ByVal handle As Integer, pre As PRESET) As Integer
Declare Function CMSetPreset Lib "cmctl.dll" (ByVal handle As Integer, pre As PRESET, ByVal no As Integer) As Integer
Declare Function CMMovePreset Lib "cmctl.dll" (ByVal handle As Integer, ByVal no As Integer) As Integer
Declare Function CMGetCameraPos Lib "cmctl.dll" (ByVal handle As Integer, Pos As CAMERAPOS) As Integer
Declare Function CMSetCameraPos Lib "cmctl.dll" (ByVal handle As Integer, Pos As CAMERAPOS) As Integer
Declare Function CMLock Lib "cmctl.dll" (ByVal handle As Integer, ByVal LockFlag As Integer) As Integer
Declare Function CMCaptureVideo Lib "cmctl.dll" (ByVal handle As Integer, mode As CAPTUREMODE, buf As Any) As Integer
Declare Function CMDraw Lib "cmctl.dll" (ByVal hdc As Integer, mode As CAPTUREMODE, buf As Any) As Integer

