[Setup]
AppName=音響分析システム
AppVerName=音響分析システム Version 5J
AppVersion=5.2.0
AppCopyright=Copyright (C) 2000-2020 Yoshimasa Electronic Inc.
VersionInfoVersion=5.2.0
PrivilegesRequired=admin
DefaultDirName={commonpf}\YMEC\DSSF3J
DefaultGroupName=音響分析システム
OutputBaseFilename=DSSF3J32
LicenseFile=..\..\MyDev\RTF\LicenseAgreementJ.rtf
UninstallDisplayIcon={app}\RTA.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: japanese; MessagesFile: compiler:Languages\Japanese.isl

[Files]
Source: "..\win32\JpnRel\DSS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\RTA.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\SAS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\NMS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\RegistJ.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\RTA_J.chm"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\SAS_J.chm"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\NMS_J.chm"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\dssf3j_ra.pdf"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\Jpn\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "..\SampleJpn\ACPARAM.BIN"; DestDir: "{app}\DataBase"
Source: "..\SampleJpn\ACPARAM.DBF"; DestDir: "{app}\DataBase"
Source: "..\SampleJpn\FOLDER.DBF"; DestDir: "{app}\DataBase"
Source: "..\SampleJpn\IMPULSE.BIN"; DestDir: "{app}\DataBase"
Source: "..\SampleJpn\IMPULSE.DBF"; DestDir: "{app}\DataBase"
Source: "..\..\MyDev\DLL32\HardKey.dll"; DestDir: "{app}"; Flags: dontcopy

[Icons]
Name: "{group}\設定ユーティリティ"; Filename: "{app}\DSS.exe"; WorkingDir: "{app}"
Name: "{group}\リアルタイムアナライザ"; Filename: "{app}\RTA.exe"; WorkingDir: "{app}"
Name: "{group}\音響分析システム"; Filename: "{app}\SAS.exe"; WorkingDir: "{app}"
Name: "{group}\騒音計測システム"; Filename: "{app}\NMS.exe"; WorkingDir: "{app}"

[Code]
procedure GetHardKey(nAddSeed: Integer; sHardKey: AnsiString);
external 'GetHardKey@files:HardKey.dll stdcall setuponly';

function GetHardKey2(param: String): String;
var
  asHardKey: AnsiString;
  sHardKey: String;
  rc: Boolean;
begin
  rc := RegQueryStringValue(HKLM, 'SoftWare\Yoshimasa Electronic Inc.\Analyzing System for Sound Fields Ver5', 'HardKey', sHardKey);
  if rc = false then begin
    asHardKey := '00000000';
    GetHardKey(0, asHardKey);
    sHardKey := String(asHardKey);
  end;
  Result := sHardKey;
end;

[Registry]
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Analyzing System for Sound Fields Ver5; ValueType: string; ValueName: HardKey; ValueData: {code:GetHardKey2};
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Analyzing System for Sound Fields Ver5; ValueType: dword; ValueName: Version; ValueData: 1;

Root: HKCR; Subkey: ".wavdssf"; ValueType: string; ValueName: ""; ValueData: "Dssf3.AutoRec"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "Dssf3.AutoRec"; ValueType: string; ValueName: ""; ValueData: "Dssf3 Automatic Recorded file"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "Dssf3.AutoRec\shell\open\command"; ValueType: string; ValueName: ""; ValueData: """{app}\RTA.exe"" /A ""%1"""; Flags: uninsdeletevalue deletevalue
