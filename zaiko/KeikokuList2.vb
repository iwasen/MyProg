Public Class frmKeikokuList2
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
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents DsZaiko1 As zaiko.dsWork
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents lblSeizouKubun As System.Windows.Forms.Label
    Friend WithEvents dtgKeikokuList As System.Windows.Forms.DataGrid
    Friend WithEvents �x���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �����_ As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �݌ɔ䗦 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �݌ɐ� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���l As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.lblSeizouKubun = New System.Windows.Forms.Label()
        Me.dtgKeikokuList = New System.Windows.Forms.DataGrid()
        Me.DsZaiko1 = New zaiko.dsWork()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.�x���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�����_ = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�݌ɔ䗦 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�݌ɐ� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���l = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.btnPrint = New System.Windows.Forms.Button()
        CType(Me.dtgKeikokuList, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsZaiko1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Green
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(718, 28)
        Me.lblTitle.TabIndex = 2
        Me.lblTitle.Text = "�����_�x�����X�g�ꗗ�\��"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'lblSeizouKubun
        '
        Me.lblSeizouKubun.AutoSize = True
        Me.lblSeizouKubun.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblSeizouKubun.Location = New System.Drawing.Point(20, 40)
        Me.lblSeizouKubun.Name = "lblSeizouKubun"
        Me.lblSeizouKubun.Size = New System.Drawing.Size(52, 15)
        Me.lblSeizouKubun.TabIndex = 3
        Me.lblSeizouKubun.Text = "������"
        '
        'dtgKeikokuList
        '
        Me.dtgKeikokuList.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgKeikokuList.CaptionVisible = False
        Me.dtgKeikokuList.DataMember = ""
        Me.dtgKeikokuList.DataSource = Me.DsZaiko1.�����_�x�����X�g
        Me.dtgKeikokuList.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgKeikokuList.Location = New System.Drawing.Point(16, 64)
        Me.dtgKeikokuList.Name = "dtgKeikokuList"
        Me.dtgKeikokuList.Size = New System.Drawing.Size(688, 384)
        Me.dtgKeikokuList.TabIndex = 4
        Me.dtgKeikokuList.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsZaiko1
        '
        Me.DsZaiko1.DataSetName = "dsZaiko"
        Me.DsZaiko1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsZaiko1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgKeikokuList
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.�x����, Me.�p�[�c����, Me.�����_, Me.�݌ɔ䗦, Me.�݌ɐ�, Me.���l})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�����_�x�����X�g"
        '
        '�x����
        '
        Me.�x����.Format = ""
        Me.�x����.FormatInfo = Nothing
        Me.�x����.HeaderText = "�x����"
        Me.�x����.MappingName = "�����_�x����"
        Me.�x����.NullText = ""
        Me.�x����.ReadOnly = True
        Me.�x����.Width = 75
        '
        '�p�[�c����
        '
        Me.�p�[�c����.Format = ""
        Me.�p�[�c����.FormatInfo = Nothing
        Me.�p�[�c����.HeaderText = "�p�[�c����"
        Me.�p�[�c����.MappingName = "�p�[�c����"
        Me.�p�[�c����.NullText = ""
        Me.�p�[�c����.ReadOnly = True
        Me.�p�[�c����.Width = 90
        '
        '�����_
        '
        Me.�����_.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.�����_.Format = ""
        Me.�����_.FormatInfo = Nothing
        Me.�����_.HeaderText = "�����_"
        Me.�����_.MappingName = "�����_����"
        Me.�����_.NullText = ""
        Me.�����_.ReadOnly = True
        Me.�����_.Width = 75
        '
        '�݌ɔ䗦
        '
        Me.�݌ɔ䗦.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.�݌ɔ䗦.Format = "0\%"
        Me.�݌ɔ䗦.FormatInfo = Nothing
        Me.�݌ɔ䗦.HeaderText = "�݌ɔ䗦"
        Me.�݌ɔ䗦.MappingName = "�݌ɔ䗦"
        Me.�݌ɔ䗦.NullText = ""
        Me.�݌ɔ䗦.ReadOnly = True
        Me.�݌ɔ䗦.Width = 75
        '
        '�݌ɐ�
        '
        Me.�݌ɐ�.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.�݌ɐ�.Format = ""
        Me.�݌ɐ�.FormatInfo = Nothing
        Me.�݌ɐ�.HeaderText = "�݌ɐ�"
        Me.�݌ɐ�.MappingName = "���݌ɐ�"
        Me.�݌ɐ�.NullText = ""
        Me.�݌ɐ�.ReadOnly = True
        Me.�݌ɐ�.Width = 75
        '
        '���l
        '
        Me.���l.Format = ""
        Me.���l.FormatInfo = Nothing
        Me.���l.HeaderText = "���l�i�����j"
        Me.���l.MappingName = "���l"
        Me.���l.NullText = ""
        Me.���l.Width = 240
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(12, 460)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(698, 4)
        Me.GroupBox1.TabIndex = 5
        Me.GroupBox1.TabStop = False
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(205, 476)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 6
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(421, 476)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "����(&C)"
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(313, 476)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 7
        Me.btnPrint.Text = "���(&P)..."
        '
        'frmKeikokuList2
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(718, 509)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnPrint, Me.lblTitle, Me.lblSeizouKubun, Me.GroupBox1, Me.btnOK, Me.btnClose, Me.dtgKeikokuList})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmKeikokuList2"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "KeikokuList2"
        CType(Me.dtgKeikokuList, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsZaiko1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    Public seizouKubunCode As String
    Public seizouKubunText As String

    '*** �t�H�[�����[�h�C�x���g
    Private Sub frmKeikokuList2_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        Me.lblSeizouKubun.Text = seizouKubunText

        DsZaiko1.Clear()
        DsZaiko1.�����_�x�����X�g.DefaultView.AllowNew = False
        DsZaiko1.�����_�x�����X�g.DefaultView.AllowDelete = False

        Dim dataAdapter As New OleDb.OleDbDataAdapter( _
            "SELECT '" & seizouKubunText & "' AS �����敪, �����_�x����, �p�[�c����, �����_����, round(���݌ɐ� / �����_���� * 100) AS �݌ɔ䗦, ���݌ɐ�, ���l" & _
            " FROM �p�[�c�݌Ƀ}�X�^" & _
            " WHERE �����敪='" & seizouKubunCode & "' AND ���݌ɐ� < �����_����" & _
            " ORDER BY �����_�x����,�p�[�c����", _
            dbConnection)
        Dim rcKeikokuList As ReportClass = New KeikokuList()

        dataAdapter.Fill(DsZaiko1, "�����_�x�����X�g")

        With dtgKeikokuList.TableStyles(0)
            '�ő���͕������ݒ�
            CType(.GridColumnStyles("���l"), DataGridTextBoxColumn).TextBox.MaxLength = 50
        End With
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        Dim dataAdapter As New OleDb.OleDbDataAdapter()
        dataAdapter.UpdateCommand = New OleDb.OleDbCommand( _
            "UPDATE �p�[�c�݌Ƀ}�X�^ SET ���l=? WHERE �p�[�c����=?", _
            dbConnection)
        dataAdapter.UpdateCommand.Parameters.Add("���l", OleDb.OleDbType.VarWChar, 50, "���l")
        dataAdapter.UpdateCommand.Parameters.Add("�p�[�c����", OleDb.OleDbType.VarWChar, 9, "�p�[�c����")

        Try
            dataAdapter.Update(DsZaiko1, "�����_�x�����X�g")
        Catch ex As Exception
            MsgBox("�p�[�c�݌Ƀ}�X�^�̍X�V�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        MsgBox("�����_�x�����X�g�̓o�^���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        DialogResult = DialogResult.OK
    End Sub

    '*** [���]�{�^���N���b�N�C�x���g
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        Dim rcKeikokuList As ReportClass = New KeikokuList()
        ShowPrintForm(lblTitle.Text, rcKeikokuList, DsZaiko1)
    End Sub

    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        '�f�[�^�ύX�`�F�b�N
        If CheckTableUpdate(DsZaiko1.�����_�x�����X�g) Then
            If MsgBox("�f�[�^���ύX����Ă��܂��B�o�^���Ȃ��ŕ��Ă�낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        DialogResult = DialogResult.Cancel
    End Sub
End Class
