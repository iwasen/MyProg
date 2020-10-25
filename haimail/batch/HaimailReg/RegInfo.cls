VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsRegInfo"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Public seq_no As String
Public mail_adr As String
Public password As String
Public Seibetsu As String
Public Mikikon As String
Public seinengappi As Date
Public zip_kyojuu As String
Public zip_kinmu As String
Public Shokugyou As String
Public html_mail As String
Public kyoumi_genre As String
Public yokuiku_mise As String
Public docchi_ha As String
Public oshirase_mail As String
Public shoukai_no As String
Public pr_id As String
Public regist_type As String
Public present_no As String

Private Sub Class_Initialize()
    kyoumi_genre = String(MAX_KYOUMI_GENRE, "0")
    yokuiku_mise = String(MAX_YOKUIKU_MISE, "0")
    docchi_ha = String(MAX_DOCCHI_HA, "0")
End Sub

'=== DB‚É•Û‘¶ ===
Public Sub StoreDB()
    Dim sql As String
    Dim ds As OraDynaset
    Dim present_id As String

    sql = "SELECT * FROM T_KARI_TOUROKU WHERE KT_mail_adr='" & mail_adr & "'"
    Set ds = oraDB.CreateDynaset(sql, ORADYN_DEFAULT)
    If ds.EOF Then
        ds.AddNew
        seq_no = GetNextVal("KT_seq_no")
        ds.Fields("KT_password").Value = password
    Else
        ds.Edit
        seq_no = ds("KT_seq_no")
        password = ds.Fields("KT_password").Value
    End If

    ds.Fields("KT_seq_no").Value = seq_no
    ds.Fields("KT_mail_adr").Value = mail_adr
    ds.Fields("KT_seibetsu").Value = Seibetsu
    ds.Fields("KT_mikikon").Value = Mikikon
    ds.Fields("KT_seinengappi").Value = seinengappi
    ds.Fields("KT_zip_kyojuu").Value = zip_kyojuu
    ds.Fields("KT_zip_kinmu").Value = zip_kinmu
    ds.Fields("KT_shokugyou").Value = Shokugyou
    ds.Fields("KT_html_mail").Value = html_mail
    ds.Fields("KT_kyoumi_genre").Value = kyoumi_genre
    ds.Fields("KT_yokuiku_mise").Value = yokuiku_mise
    ds.Fields("KT_docchiha").Value = docchi_ha
    ds.Fields("KT_oshirase_mail").Value = oshirase_mail
    ds.Fields("KT_shoukai_no").Value = shoukai_no
    ds.Fields("KT_pr_id").Value = pr_id
    ds.Fields("KT_regist_date").Value = Now
    ds.Fields("KT_status").Value = "0"
    ds.Fields("KT_regist_type").Value = regist_type
    ds.Update

    If present_no <> "" Then
        'ƒCƒ“ƒvƒŒƒX‘Î‰ž
        present_id = "06"
'        If pr_id <> "" Then
'            sql = "SELECT PR_baitai_id FROM T_PR WHERE PR_pr_id='" & pr_id & "'"
'            Set ds = oraDB.CreateDynaset(sql, ORADYN_READONLY)
'            If Not ds.EOF Then
'                If ds(0).Value = "30" Then
'                    present_id = "05"
'                End If
'            End If
'        End If

        sql = "SELECT * FROM T_PRESENT WHERE PS_present_id='" & present_id & "' AND PS_seq_no=" & seq_no
        Set ds = oraDB.CreateDynaset(sql, ORADYN_DEFAULT)
        If ds.EOF Then
            ds.AddNew
            ds("PS_present_id").Value = present_id
            ds("PS_seq_no").Value = seq_no
        Else
            ds.Edit
        End If
        ds("PS_present_no").Value = present_no
        ds.Update
    End If
End Sub

