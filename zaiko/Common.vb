Module Common
    '�萔��`
    Public Const APPNAME = "�݌ɊǗ��V�X�e��"
    Public Const VERSION = "Ver0.70"
    Public Const TITLE = APPNAME & " " & VERSION
    Public Const COPYRIGHT = "Copyright (C) 2003 ������ЃP�[�A�C���@ All Rights Reserved."

    'DB�R�l�N�V����
    Public dbConnection As OleDb.OleDbConnection

    '���[�U���x��
    Public userLevel As Integer

    '�f�o�b�O���[�h
    Public debugMode As Boolean

    '*** �A�v���P�[�V�����J�n����
    Sub main()
        Dim frm As Form
        Dim args As String
        Dim dbName As String = "Zaiko.mdb"

        '�R�}���h���C�������`�F�b�N
        For Each args In System.Environment.GetCommandLineArgs()
            If args.Substring(0, 3) = "db=" Then
                dbName = args.Substring(3)
            ElseIf args = "debug" Then
                debugMode = True
            End If
        Next

        'DB�ɐڑ�
        Try
            dbConnection = New OleDb.OleDbConnection()
            dbConnection.ConnectionString = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & dbName
            dbConnection.Open()
        Catch ex As Exception
            MsgBox("�f�[�^�x�[�X�ɐڑ��ł��܂���ł����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

        '���O�C���_�C�A���O��\��
        Try
            frm = New zaiko.frmLogin()
            If frm.ShowDialog() = DialogResult.OK Then
                '���O�C��OK�Ȃ烁�j���[��\��
                frm.Dispose()
                frm = New frmMenu()
                frm.ShowDialog()
            End If
        Catch ex As Exception
            MsgBox("�V�X�e���G���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

    End Sub

    '*** �f�[�^�A�_�v�^��DB�R�l�N�V������ݒ�
    Sub SetDbConnection(ByRef dataAdapter As OleDb.OleDbDataAdapter)
        If Not dataAdapter.SelectCommand Is Nothing Then
            dataAdapter.SelectCommand.Connection = dbConnection
        End If

        If Not dataAdapter.InsertCommand Is Nothing Then
            dataAdapter.InsertCommand.Connection = dbConnection
        End If

        If Not dataAdapter.UpdateCommand Is Nothing Then
            dataAdapter.UpdateCommand.Connection = dbConnection
        End If

        If Not dataAdapter.DeleteCommand Is Nothing Then
            dataAdapter.DeleteCommand.Connection = dbConnection
        End If
    End Sub

    '*** �p�[�c��ʑI���h���b�v�_�E�����X�g
    Sub PartsShubetsuSelect(ByRef cmb As ComboBox)
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT * FROM �p�[�c��ʃ}�X�^ ORDER BY �p�[�c��ʃR�[�h", dbConnection)
        Dim dataSet As New dsTable()

        dataSet.�p�[�c��ʃ}�X�^.Add�p�[�c��ʃ}�X�^Row("", "�I�����Ă�������")
        dataAdapter.Fill(dataSet, "�p�[�c��ʃ}�X�^")
        cmb.DataSource = dataSet.�p�[�c��ʃ}�X�^
        cmb.DisplayMember = "�p�[�c��ʖ�"
        cmb.ValueMember = "�p�[�c��ʃR�[�h"
    End Sub

    '*** �ڋq�I���h���b�v�_�E�����X�g
    Sub KokyakuSelect(ByRef cmb As ComboBox)
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT * FROM �ڋq�}�X�^ ORDER BY ���я�", dbConnection)
        Dim dataSet As New dsTable()

        dataSet.�ڋq�}�X�^.Add�ڋq�}�X�^Row(0, "", "�I�����Ă�������")
        dataAdapter.Fill(dataSet, "�ڋq�}�X�^")
        cmb.DataSource = dataSet.�ڋq�}�X�^
        cmb.DisplayMember = "�ڋq��"
        cmb.ValueMember = "�ڋq�R�[�h"
    End Sub

    '*** ����v���r���[�\��
    Sub ShowPrintForm(ByVal title As String, ByRef report As ReportClass, ByRef dataSet As DataSet)
        Dim frm As New zaiko.frmPrint()

        frm.Text = title
        report.SetDataSource(dataSet)
        frm.crvPrint.ReportSource = report
        frm.crvPrint.DisplayGroupTree = False
        frm.ShowDialog()
    End Sub

    '*** �p�[�c�W�J����
    Function PartsZaikoHikiate(ByVal dtTenkai As dsWork.�p�[�c�W�JDataTable) As dsWork.�p�[�c�݌Ɉ���DataTable
        Dim dtHikiate As New dsWork.�p�[�c�݌Ɉ���DataTable()
        Dim rowTenkai As dsWork.�p�[�c�W�JRow
        Dim rowHikiate As dsWork.�p�[�c�݌Ɉ���Row
        Dim i As Integer
        Dim parts As String
        Dim suuryou As Integer

        For Each rowTenkai In dtTenkai
            For i = 1 To 15
                If Not rowTenkai.IsNull("�p�[�c" & i) AndAlso Not rowTenkai.IsNull("����" & i) Then
                    parts = rowTenkai("�p�[�c" & i)
                    suuryou = rowTenkai("����" & i) * rowTenkai.�󒍐���

                    rowHikiate = dtHikiate.Rows.Find(parts)
                    If rowHikiate Is Nothing Then
                        dtHikiate.Add�p�[�c�݌Ɉ���Row(parts, suuryou)
                    Else
                        rowHikiate.������ += suuryou
                    End If
                End If
            Next
        Next

        Return dtHikiate
    End Function

    '*** ���i���̑��݃`�F�b�N
    Function ShouhinCheck(ByVal shouhin As String) As string
        '���i���̃`�F�b�N�pSQL
        Dim dbCmd As New OleDb.OleDbCommand()
        dbCmd.Connection = dbConnection
        dbCmd.CommandType = CommandType.Text
        dbCmd.CommandText = "SELECT COUNT(*) FROM �p�[�c�W�J�}�X�^ WHERE ���i����=?"
        dbCmd.Parameters.Add("���i����", Data.OleDb.OleDbType.WChar)

        dbCmd.Parameters("���i����").Value = shouhin
        If CType(dbCmd.ExecuteScalar(), Integer) = 0 Then
            '���������ꍇ�A�Ō�̃n�C�t���ȍ~���폜���čēx����
            Dim n As Integer
            Dim s As String
            n = shouhin.LastIndexOf("-")
            If n > 0 Then
                shouhin = shouhin.Substring(0, n)
                dbCmd.Parameters("���i����").Value = shouhin
                If CType(dbCmd.ExecuteScalar(), Integer) = 0 Then
                    shouhin = Nothing
                End If
            Else
                shouhin = Nothing
            End If
        End If

        Return shouhin
    End Function

    '*** �e�[�u���ɕύX�����������ǂ������`�F�b�N
    Function CheckTableUpdate(ByRef table As DataTable)
        Dim row As DataRow

        For Each row In table.Rows
            If row.RowState <> DataRowState.Unchanged Then
                Return True
            End If
        Next

        Return False
    End Function

    '*** ���X�g�{�b�N�X�i�R���{�{�b�N�X�j�̑I���f�[�^�N���X
    Class ListSource
        Private display As String
        Private value As String

        Sub New(ByVal disp As String, ByVal val As String)
            Me.display = disp
            Me.value = val
        End Sub

        Public Property DisplayMember()
            Get
                Return Me.display
            End Get
            Set(ByVal Value)
                Me.display = Value
            End Set
        End Property

        Public Property ValueMember()
            Get
                Return Me.value
            End Get
            Set(ByVal Value)
                Me.value = Value
            End Set
        End Property
    End Class
End Module
