Public Class frmPickingList
    Inherits System.Windows.Forms.Form

#Region " Windows �t�H�[�� �f�U�C�i�Ő������ꂽ�R�[�h "

    Public Sub New()
        MyBase.New()

        ' ���̌Ăяo���� Windows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
        InitializeComponent()

        ' InitializeComponent() �Ăяo���̌�ɏ�������ǉ����܂��B

    End Sub

    ' Form �� dispose ���I�[�o�[���C�h���ăR���|�[�l���g�ꗗ���������܂��B
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    ' Windows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
    Private components As System.ComponentModel.IContainer

    ' ���� : �ȉ��̃v���V�[�W���́AWindows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
    ' Windows �t�H�[�� �f�U�C�i���g���ĕύX���Ă��������B  
    ' �R�[�h �G�f�B�^�͎g�p���Ȃ��ł��������B
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents PrintDocument1 As System.Drawing.Printing.PrintDocument
    Friend WithEvents PrintDialog1 As System.Windows.Forms.PrintDialog
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents dtpNouki As System.Windows.Forms.DateTimePicker
    Friend WithEvents Label2 As System.Windows.Forms.Label
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnPrint = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.PrintDocument1 = New System.Drawing.Printing.PrintDocument()
        Me.PrintDialog1 = New System.Windows.Forms.PrintDialog()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.dtpNouki = New System.Windows.Forms.DateTimePicker()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.SuspendLayout()
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 136)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 6
        Me.GroupBox1.TabStop = False
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(166, 152)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 7
        Me.btnPrint.Text = "���(&P)..."
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(273, 152)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "����(&C)"
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Navy
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "�s�b�L���O���X�g���"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'PrintDialog1
        '
        Me.PrintDialog1.Document = Me.PrintDocument1
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(96, 56)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 3
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(52, 60)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(36, 15)
        Me.Label4.TabIndex = 2
        Me.Label4.Text = "�ڋq"
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(52, 96)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(36, 15)
        Me.Label1.TabIndex = 4
        Me.Label1.Text = "�[��"
        '
        'dtpNouki
        '
        Me.dtpNouki.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpNouki.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtpNouki.Location = New System.Drawing.Point(96, 92)
        Me.dtpNouki.Name = "dtpNouki"
        Me.dtpNouki.Size = New System.Drawing.Size(124, 22)
        Me.dtpNouki.TabIndex = 5
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(228, 96)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(58, 15)
        Me.Label2.TabIndex = 9
        Me.Label2.Text = "�܂ł̕�"
        '
        'frmPickingList
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 187)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.Label2, Me.dtpNouki, Me.Label1, Me.cmbKokyaku, Me.Label4, Me.lblTitle, Me.GroupBox1, Me.btnPrint, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmPickingList"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PickingList"
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub frmPickingList_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)
    End Sub

    '*** [���]�{�^���N���b�N�C�x���g
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        Dim i As Integer

        '�ڋq�I���`�F�b�N
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("�ڋq��I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '�Y���[���擾
        Dim dtaNouki As New OleDb.OleDbDataAdapter( _
            "SELECT DISTINCT �[�� FROM �󒍃f�[�^ WHERE �󒍃f�[�^.�ڋq�R�[�h=? AND �[��<=? AND �s�b�L���O���X�g�o��=False", _
            dbConnection)
        dtaNouki.SelectCommand.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dtaNouki.SelectCommand.Parameters.Add("�[��", Data.OleDb.OleDbType.DBDate)
        dtaNouki.SelectCommand.Parameters("�ڋq�R�[�h").Value = cmbKokyaku.SelectedValue
        dtaNouki.SelectCommand.Parameters("�[��").Value = dtpNouki.Text
        Dim dsNouki As New DataSet()
        dtaNouki.Fill(dsNouki)

        '�󒍃f�[�^�̃p�[�c�W�J�f�[�^�擾
        Dim dtaParts As New OleDb.OleDbDataAdapter( _
            "SELECT �󒍔ԍ�, �󒍃f�[�^.���i����, �󒍐���, " & _
            " �p�[�c1, ����1, �p�[�c2, ����2, �p�[�c3, ����3, �p�[�c4, ����4, �p�[�c5, ����5, " & _
            " �p�[�c6, ����6, �p�[�c7, ����7, �p�[�c8, ����8, �p�[�c9, ����9, �p�[�c10, ����10," & _
            " �p�[�c11, ����11, �p�[�c12, ����12, �p�[�c13, ����13, �p�[�c14, ����14, �p�[�c15, ����15" & _
            " FROM �󒍃f�[�^" & _
            " INNER JOIN �p�[�c�W�J�}�X�^ ON �󒍃f�[�^.�W�����i����=�p�[�c�W�J�}�X�^.���i����" & _
            " WHERE �󒍃f�[�^.�ڋq�R�[�h=? AND �[��=? AND �s�b�L���O���X�g�o��=False", _
            dbConnection)
        dtaParts.SelectCommand.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dtaParts.SelectCommand.Parameters.Add("�[��", Data.OleDb.OleDbType.DBDate)

        Dim aryJuchuNo As New ArrayList()
        Dim rowNouki As DataRow
        Dim dsWork1 As New dsWork()
        Dim rowPartsTenkai As dsWork.�p�[�c�W�JRow
        Dim dtPartsHikiete As dsWork.�p�[�c�݌Ɉ���DataTable
        Dim rowPartsHikiete As dsWork.�p�[�c�݌Ɉ���Row
        Dim dtPickingList As New dsWork.�s�b�L���O���X�gDataTable()
        For Each rowNouki In dsNouki.Tables(0).Rows
            '�󒍃f�[�^�ƃp�[�c��ǂݍ���
            dtaParts.SelectCommand.Parameters("�ڋq�R�[�h").Value = cmbKokyaku.SelectedValue
            dtaParts.SelectCommand.Parameters("�[��").Value = rowNouki(0)
            dsWork1.�p�[�c�W�J.Clear()
            dtaParts.Fill(dsWork1, "�p�[�c�W�J")

            '�p�[�c�W�J����
            dtPartsHikiete = PartsZaikoHikiate(dsWork1.�p�[�c�W�J)
            Dim dtvPartsHikiete As DataView = dtPartsHikiete.DefaultView
            dtvPartsHikiete.Sort = "�p�[�c����"
            Dim count = dtPartsHikiete.DefaultView.Count - 1
            For i = 0 To count
                dsWork1.�s�b�L���O���X�g.Add�s�b�L���O���X�gRow(cmbKokyaku.Text, rowNouki(0), dtvPartsHikiete(i)("�p�[�c����"), dtvPartsHikiete(i)("������"), Nothing)
            Next

            '��ň���ς݂ɂ��邽�ߎ󒍔ԍ���ۑ�
            For Each rowPartsTenkai In dsWork1.�p�[�c�W�J.Rows
                aryJuchuNo.Add(rowPartsTenkai.�󒍔ԍ�)
            Next
        Next

        '���l�擾
        Dim dbCmd As New OleDb.OleDbCommand( _
            "SELECT ���l FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c����=?", _
            dbConnection)
        dbCmd.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)
        Dim rowPickingList As dsWork.�s�b�L���O���X�gRow
        Dim bikou As Object
        For Each rowPickingList In dsWork1.�s�b�L���O���X�g
            dbCmd.Parameters("�p�[�c����").Value = rowPickingList.�p�[�c����
            bikou = dbCmd.ExecuteScalar()
            If (Not bikou Is DBNull.Value) Then
                rowPickingList.���l = bikou
            End If
        Next

        If dsWork1.Tables("�s�b�L���O���X�g").Rows.Count = 0 Then
            MsgBox("���݁A�������f�[�^�͂���܂���B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information)
            Return
        End If

        '�v���r���[�\��
        Dim rcPickingList As ReportClass = New PickingList()
        ShowPrintForm(lblTitle.Text, rcPickingList, dsWork1)

        '����ς݂ɃZ�b�g
        If MsgBox("�s�b�L���O���X�g������ς݂ɂ��܂����H", MsgBoxStyle.YesNo Or MsgBoxStyle.Question) = MsgBoxResult.Yes Then
            Dim dbcJuchuUpdate As New OleDb.OleDbCommand( _
                "UPDATE �󒍃f�[�^ SET �s�b�L���O���X�g�o��=True WHERE �ڋq�R�[�h=? AND �󒍔ԍ�=?", _
                dbConnection)
            dbcJuchuUpdate.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
            dbcJuchuUpdate.Parameters.Add("�󒍔ԍ�", Data.OleDb.OleDbType.VarWChar)

            dbcJuchuUpdate.Parameters("�ڋq�R�[�h").Value = cmbKokyaku.SelectedValue
            Dim juchuNo As String
            For Each juchuNo In aryJuchuNo
                dbcJuchuUpdate.Parameters("�󒍔ԍ�").Value = juchuNo
                dbcJuchuUpdate.ExecuteNonQuery()
            Next
        End If
    End Sub
End Class
