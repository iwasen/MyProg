Public Class PartsTenkai
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
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents dtgMaster As System.Windows.Forms.DataGrid
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbUpdateCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbDeleteCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsTable1 As zaiko.dsTable
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents ���i���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c1 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����1 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c2 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����2 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c3 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����3 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c4 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����4 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c5 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����5 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c6 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����6 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c7 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����7 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c8 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����8 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c9 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����9 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c10 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����10 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c11 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����11 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c12 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����12 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c13 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����13 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c14 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����14 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c15 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ����15 As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.���i���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c1 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����1 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c2 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����2 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c3 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����3 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c4 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����4 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c5 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����5 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c6 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����6 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c7 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����7 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c8 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����8 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c9 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����9 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c10 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����10 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c11 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����11 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c12 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����12 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c13 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����13 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c14 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����14 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c15 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.����15 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbDeleteCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbUpdateCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(326, 442)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 4
        Me.btnOK.Text = "OK"
        '
        'btnCancel
        '
        Me.btnCancel.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnCancel.Location = New System.Drawing.Point(434, 442)
        Me.btnCancel.Name = "btnCancel"
        Me.btnCancel.Size = New System.Drawing.Size(92, 24)
        Me.btnCancel.TabIndex = 5
        Me.btnCancel.Text = "�L�����Z��"
        '
        'dtgMaster
        '
        Me.dtgMaster.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgMaster.CaptionText = "�p�[�c�W�J�}�X�^�["
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.�p�[�c�W�J�}�X�^
        Me.dtgMaster.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgMaster.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgMaster.Location = New System.Drawing.Point(0, 38)
        Me.dtgMaster.Name = "dtgMaster"
        Me.dtgMaster.Size = New System.Drawing.Size(854, 386)
        Me.dtgMaster.TabIndex = 2
        Me.dtgMaster.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsTable1
        '
        Me.DsTable1.DataSetName = "dsTable"
        Me.DsTable1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsTable1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgMaster
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.���i����, Me.�p�[�c1, Me.����1, Me.�p�[�c2, Me.����2, Me.�p�[�c3, Me.����3, Me.�p�[�c4, Me.����4, Me.�p�[�c5, Me.����5, Me.�p�[�c6, Me.����6, Me.�p�[�c7, Me.����7, Me.�p�[�c8, Me.����8, Me.�p�[�c9, Me.����9, Me.�p�[�c10, Me.����10, Me.�p�[�c11, Me.����11, Me.�p�[�c12, Me.����12, Me.�p�[�c13, Me.����13, Me.�p�[�c14, Me.����14, Me.�p�[�c15, Me.����15})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�p�[�c�W�J�}�X�^"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        '���i����
        '
        Me.���i����.Format = ""
        Me.���i����.FormatInfo = Nothing
        Me.���i����.HeaderText = "���i����"
        Me.���i����.MappingName = "���i����"
        Me.���i����.NullText = ""
        Me.���i����.Width = 95
        '
        '�p�[�c1
        '
        Me.�p�[�c1.Format = ""
        Me.�p�[�c1.FormatInfo = Nothing
        Me.�p�[�c1.HeaderText = "�p�[�c1"
        Me.�p�[�c1.MappingName = "�p�[�c1"
        Me.�p�[�c1.NullText = ""
        Me.�p�[�c1.Width = 95
        '
        '����1
        '
        Me.����1.Format = ""
        Me.����1.FormatInfo = Nothing
        Me.����1.HeaderText = "����1"
        Me.����1.MappingName = "����1"
        Me.����1.NullText = ""
        Me.����1.Width = 50
        '
        '�p�[�c2
        '
        Me.�p�[�c2.Format = ""
        Me.�p�[�c2.FormatInfo = Nothing
        Me.�p�[�c2.HeaderText = "�p�[�c2"
        Me.�p�[�c2.MappingName = "�p�[�c2"
        Me.�p�[�c2.NullText = ""
        Me.�p�[�c2.Width = 95
        '
        '����2
        '
        Me.����2.Format = ""
        Me.����2.FormatInfo = Nothing
        Me.����2.HeaderText = "����2"
        Me.����2.MappingName = "����2"
        Me.����2.NullText = ""
        Me.����2.Width = 50
        '
        '�p�[�c3
        '
        Me.�p�[�c3.Format = ""
        Me.�p�[�c3.FormatInfo = Nothing
        Me.�p�[�c3.HeaderText = "�p�[�c3"
        Me.�p�[�c3.MappingName = "�p�[�c3"
        Me.�p�[�c3.NullText = ""
        Me.�p�[�c3.Width = 95
        '
        '����3
        '
        Me.����3.Format = ""
        Me.����3.FormatInfo = Nothing
        Me.����3.HeaderText = "����3"
        Me.����3.MappingName = "����3"
        Me.����3.NullText = ""
        Me.����3.Width = 50
        '
        '�p�[�c4
        '
        Me.�p�[�c4.Format = ""
        Me.�p�[�c4.FormatInfo = Nothing
        Me.�p�[�c4.HeaderText = "�p�[�c4"
        Me.�p�[�c4.MappingName = "�p�[�c4"
        Me.�p�[�c4.NullText = ""
        Me.�p�[�c4.Width = 95
        '
        '����4
        '
        Me.����4.Format = ""
        Me.����4.FormatInfo = Nothing
        Me.����4.HeaderText = "����4"
        Me.����4.MappingName = "����4"
        Me.����4.NullText = ""
        Me.����4.Width = 50
        '
        '�p�[�c5
        '
        Me.�p�[�c5.Format = ""
        Me.�p�[�c5.FormatInfo = Nothing
        Me.�p�[�c5.HeaderText = "�p�[�c5"
        Me.�p�[�c5.MappingName = "�p�[�c5"
        Me.�p�[�c5.NullText = ""
        Me.�p�[�c5.Width = 95
        '
        '����5
        '
        Me.����5.Format = ""
        Me.����5.FormatInfo = Nothing
        Me.����5.HeaderText = "����5"
        Me.����5.MappingName = "����5"
        Me.����5.NullText = ""
        Me.����5.Width = 50
        '
        '�p�[�c6
        '
        Me.�p�[�c6.Format = ""
        Me.�p�[�c6.FormatInfo = Nothing
        Me.�p�[�c6.HeaderText = "�p�[�c6"
        Me.�p�[�c6.MappingName = "�p�[�c6"
        Me.�p�[�c6.NullText = ""
        Me.�p�[�c6.Width = 95
        '
        '����6
        '
        Me.����6.Format = ""
        Me.����6.FormatInfo = Nothing
        Me.����6.HeaderText = "����6"
        Me.����6.MappingName = "����6"
        Me.����6.NullText = ""
        Me.����6.Width = 50
        '
        '�p�[�c7
        '
        Me.�p�[�c7.Format = ""
        Me.�p�[�c7.FormatInfo = Nothing
        Me.�p�[�c7.HeaderText = "�p�[�c7"
        Me.�p�[�c7.MappingName = "�p�[�c7"
        Me.�p�[�c7.NullText = ""
        Me.�p�[�c7.Width = 95
        '
        '����7
        '
        Me.����7.Format = ""
        Me.����7.FormatInfo = Nothing
        Me.����7.HeaderText = "����7"
        Me.����7.MappingName = "����7"
        Me.����7.NullText = ""
        Me.����7.Width = 50
        '
        '�p�[�c8
        '
        Me.�p�[�c8.Format = ""
        Me.�p�[�c8.FormatInfo = Nothing
        Me.�p�[�c8.HeaderText = "�p�[�c8"
        Me.�p�[�c8.MappingName = "�p�[�c8"
        Me.�p�[�c8.NullText = ""
        Me.�p�[�c8.Width = 95
        '
        '����8
        '
        Me.����8.Format = ""
        Me.����8.FormatInfo = Nothing
        Me.����8.HeaderText = "����8"
        Me.����8.MappingName = "����8"
        Me.����8.NullText = ""
        Me.����8.Width = 50
        '
        '�p�[�c9
        '
        Me.�p�[�c9.Format = ""
        Me.�p�[�c9.FormatInfo = Nothing
        Me.�p�[�c9.HeaderText = "�p�[�c9"
        Me.�p�[�c9.MappingName = "�p�[�c9"
        Me.�p�[�c9.NullText = ""
        Me.�p�[�c9.Width = 95
        '
        '����9
        '
        Me.����9.Format = ""
        Me.����9.FormatInfo = Nothing
        Me.����9.HeaderText = "����9"
        Me.����9.MappingName = "����9"
        Me.����9.NullText = ""
        Me.����9.Width = 50
        '
        '�p�[�c10
        '
        Me.�p�[�c10.Format = ""
        Me.�p�[�c10.FormatInfo = Nothing
        Me.�p�[�c10.HeaderText = "�p�[�c10"
        Me.�p�[�c10.MappingName = "�p�[�c10"
        Me.�p�[�c10.NullText = ""
        Me.�p�[�c10.Width = 95
        '
        '����10
        '
        Me.����10.Format = ""
        Me.����10.FormatInfo = Nothing
        Me.����10.HeaderText = "����10"
        Me.����10.MappingName = "����10"
        Me.����10.NullText = ""
        Me.����10.Width = 50
        '
        '�p�[�c11
        '
        Me.�p�[�c11.Format = ""
        Me.�p�[�c11.FormatInfo = Nothing
        Me.�p�[�c11.HeaderText = "�p�[�c11"
        Me.�p�[�c11.MappingName = "�p�[�c11"
        Me.�p�[�c11.NullText = ""
        Me.�p�[�c11.Width = 95
        '
        '����11
        '
        Me.����11.Format = ""
        Me.����11.FormatInfo = Nothing
        Me.����11.HeaderText = "����11"
        Me.����11.MappingName = "����11"
        Me.����11.NullText = ""
        Me.����11.Width = 50
        '
        '�p�[�c12
        '
        Me.�p�[�c12.Format = ""
        Me.�p�[�c12.FormatInfo = Nothing
        Me.�p�[�c12.HeaderText = "�p�[�c12"
        Me.�p�[�c12.MappingName = "�p�[�c12"
        Me.�p�[�c12.NullText = ""
        Me.�p�[�c12.Width = 95
        '
        '����12
        '
        Me.����12.Format = ""
        Me.����12.FormatInfo = Nothing
        Me.����12.HeaderText = "����12"
        Me.����12.MappingName = "����12"
        Me.����12.NullText = ""
        Me.����12.Width = 50
        '
        '�p�[�c13
        '
        Me.�p�[�c13.Format = ""
        Me.�p�[�c13.FormatInfo = Nothing
        Me.�p�[�c13.HeaderText = "�p�[�c13"
        Me.�p�[�c13.MappingName = "�p�[�c13"
        Me.�p�[�c13.NullText = ""
        Me.�p�[�c13.Width = 95
        '
        '����13
        '
        Me.����13.Format = ""
        Me.����13.FormatInfo = Nothing
        Me.����13.HeaderText = "����13"
        Me.����13.MappingName = "����13"
        Me.����13.NullText = ""
        Me.����13.Width = 50
        '
        '�p�[�c14
        '
        Me.�p�[�c14.Format = ""
        Me.�p�[�c14.FormatInfo = Nothing
        Me.�p�[�c14.HeaderText = "�p�[�c14"
        Me.�p�[�c14.MappingName = "�p�[�c14"
        Me.�p�[�c14.NullText = ""
        Me.�p�[�c14.Width = 95
        '
        '����14
        '
        Me.����14.Format = ""
        Me.����14.FormatInfo = Nothing
        Me.����14.HeaderText = "����14"
        Me.����14.MappingName = "����14"
        Me.����14.NullText = ""
        Me.����14.Width = 50
        '
        '�p�[�c15
        '
        Me.�p�[�c15.Format = ""
        Me.�p�[�c15.FormatInfo = Nothing
        Me.�p�[�c15.HeaderText = "�p�[�c15"
        Me.�p�[�c15.MappingName = "�p�[�c15"
        Me.�p�[�c15.NullText = ""
        Me.�p�[�c15.Width = 95
        '
        '����15
        '
        Me.����15.Format = ""
        Me.����15.FormatInfo = Nothing
        Me.����15.HeaderText = "����15"
        Me.����15.MappingName = "����15"
        Me.����15.NullText = ""
        Me.����15.Width = 50
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Maroon
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(852, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "�}�X�^�[�����e�i���X"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(8, 430)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(838, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�p�[�c�W�J�}�X�^", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("���i����", "���i����"), New System.Data.Common.DataColumnMapping("�p�[�c1", "�p�[�c1"), New System.Data.Common.DataColumnMapping("����1", "����1"), New System.Data.Common.DataColumnMapping("�p�[�c2", "�p�[�c2"), New System.Data.Common.DataColumnMapping("����2", "����2"), New System.Data.Common.DataColumnMapping("�p�[�c3", "�p�[�c3"), New System.Data.Common.DataColumnMapping("����3", "����3"), New System.Data.Common.DataColumnMapping("�p�[�c4", "�p�[�c4"), New System.Data.Common.DataColumnMapping("����4", "����4"), New System.Data.Common.DataColumnMapping("�p�[�c5", "�p�[�c5"), New System.Data.Common.DataColumnMapping("����5", "����5"), New System.Data.Common.DataColumnMapping("�p�[�c6", "�p�[�c6"), New System.Data.Common.DataColumnMapping("����6", "����6"), New System.Data.Common.DataColumnMapping("�p�[�c7", "�p�[�c7"), New System.Data.Common.DataColumnMapping("����7", "����7"), New System.Data.Common.DataColumnMapping("�p�[�c8", "�p�[�c8"), New System.Data.Common.DataColumnMapping("����8", "����8"), New System.Data.Common.DataColumnMapping("�p�[�c9", "�p�[�c9"), New System.Data.Common.DataColumnMapping("����9", "����9"), New System.Data.Common.DataColumnMapping("�p�[�c10", "�p�[�c10"), New System.Data.Common.DataColumnMapping("����10", "����10"), New System.Data.Common.DataColumnMapping("�p�[�c11", "�p�[�c11"), New System.Data.Common.DataColumnMapping("����11", "����11"), New System.Data.Common.DataColumnMapping("�p�[�c12", "�p�[�c12"), New System.Data.Common.DataColumnMapping("����12", "����12"), New System.Data.Common.DataColumnMapping("�p�[�c13", "�p�[�c13"), New System.Data.Common.DataColumnMapping("����13", "����13"), New System.Data.Common.DataColumnMapping("�p�[�c14", "�p�[�c14"), New System.Data.Common.DataColumnMapping("����14", "����14"), New System.Data.Common.DataColumnMapping("�p�[�c15", "�p�[�c15"), New System.Data.Common.DataColumnMapping("����15", "����15")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM �p�[�c�W�J�}�X�^ WHERE (���i���� = ?) AND (�p�[�c1 = ? OR ? IS NULL AND �p�[�c1 IS NULL" & _
        ") AND (�p�[�c10 = ? OR ? IS NULL AND �p�[�c10 IS NULL) AND (�p�[�c11 = ? OR ? IS NULL AND" & _
        " �p�[�c11 IS NULL) AND (�p�[�c12 = ? OR ? IS NULL AND �p�[�c12 IS NULL) AND (�p�[�c13 = ? OR" & _
        " ? IS NULL AND �p�[�c13 IS NULL) AND (�p�[�c14 = ? OR ? IS NULL AND �p�[�c14 IS NULL) AND" & _
        " (�p�[�c15 = ? OR ? IS NULL AND �p�[�c15 IS NULL) AND (�p�[�c2 = ? OR ? IS NULL AND �p�[�c2 " & _
        "IS NULL) AND (�p�[�c3 = ? OR ? IS NULL AND �p�[�c3 IS NULL) AND (�p�[�c4 = ? OR ? IS NULL" & _
        " AND �p�[�c4 IS NULL) AND (�p�[�c5 = ? OR ? IS NULL AND �p�[�c5 IS NULL) AND (�p�[�c6 = ? OR" & _
        " ? IS NULL AND �p�[�c6 IS NULL) AND (�p�[�c7 = ? OR ? IS NULL AND �p�[�c7 IS NULL) AND (�p" & _
        "�[�c8 = ? OR ? IS NULL AND �p�[�c8 IS NULL) AND (�p�[�c9 = ? OR ? IS NULL AND �p�[�c9 IS NU" & _
        "LL) AND (����1 = ? OR ? IS NULL AND ����1 IS NULL) AND (����10 = ? OR ? IS NULL AND ����" & _
        "10 IS NULL) AND (����2 = ? OR ? IS NULL AND ����2 IS NULL) AND (����3 = ? OR ? IS NULL" & _
        " AND ����3 IS NULL) AND (����4 = ? OR ? IS NULL AND ����4 IS NULL) AND (����5 = ? OR ? I" & _
        "S NULL AND ����5 IS NULL) AND (����6 = ? OR ? IS NULL AND ����6 IS NULL) AND (����7 = ? " & _
        "OR ? IS NULL AND ����7 IS NULL) AND (����8 = ? OR ? IS NULL AND ����8 IS NULL) AND (����" & _
        "9 = ? OR ? IS NULL AND ����9 IS NULL)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i����", System.Data.OleDb.OleDbType.VarWChar, 12, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c1", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c11", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c10", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c101", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c111", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c112", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c12", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c121", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c13", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c131", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c14", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c141", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c15", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c151", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c2", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c21", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c3", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c31", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c4", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c41", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c5", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c51", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c6", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c61", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c7", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c71", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c8", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c81", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c9", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c91", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����11", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����101", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����21", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����31", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����41", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����51", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����61", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����71", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����81", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����91", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO �p�[�c�W�J�}�X�^(�p�[�c1, �p�[�c10, �p�[�c11, �p�[�c12, �p�[�c13, �p�[�c14, �p�[�c15, �p�[�c2, �p�[�c3, " & _
        "�p�[�c4, �p�[�c5, �p�[�c6, �p�[�c7, �p�[�c8, �p�[�c9, ���i����, ����1, ����10, ����11, ����12, ����13, ����14, ����1" & _
        "5, ����2, ����3, ����4, ����5, ����6, ����7, ����8, ����9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?," & _
        " ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c1", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c1"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c10", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c10"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c11", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c11"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c12", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c12"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c13", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c13"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c14", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c14"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c15", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c15"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c2", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c2"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c3", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c3"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c4", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c4"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c5", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c5"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c6", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c6"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c7", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c7"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c8", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c8"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c9", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c9"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���i����", System.Data.OleDb.OleDbType.VarWChar, 12, "���i����"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����11", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����11", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����12", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����12", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����13", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����13", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����14", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����14", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����15", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����15", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Current, Nothing))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT �p�[�c1, �p�[�c10, �p�[�c11, �p�[�c12, �p�[�c13, �p�[�c14, �p�[�c15, �p�[�c2, �p�[�c3, �p�[�c4, �p�[�c5, �p�[" & _
        "�c6, �p�[�c7, �p�[�c8, �p�[�c9, ���i����, ����1, ����10, ����11, ����12, ����13, ����14, ����15, ����2, ����3, ��" & _
        "��4, ����5, ����6, ����7, ����8, ����9 FROM �p�[�c�W�J�}�X�^ ORDER BY ���i����"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE �p�[�c�W�J�}�X�^ SET �p�[�c1 = ?, �p�[�c10 = ?, �p�[�c11 = ?, �p�[�c12 = ?, �p�[�c13 = ?, �p�[�c14 =" & _
        " ?, �p�[�c15 = ?, �p�[�c2 = ?, �p�[�c3 = ?, �p�[�c4 = ?, �p�[�c5 = ?, �p�[�c6 = ?, �p�[�c7 = ?, �p�[�c8 " & _
        "= ?, �p�[�c9 = ?, ���i���� = ?, ����1 = ?, ����10 = ?, ����11 = ?, ����12 = ?, ����13 = ?, ����14 =" & _
        " ?, ����15 = ?, ����2 = ?, ����3 = ?, ����4 = ?, ����5 = ?, ����6 = ?, ����7 = ?, ����8 = ?, ����9" & _
        " = ? WHERE (���i���� = ?) AND (�p�[�c1 = ? OR ? IS NULL AND �p�[�c1 IS NULL) AND (�p�[�c10 = " & _
        "? OR ? IS NULL AND �p�[�c10 IS NULL) AND (�p�[�c11 = ? OR ? IS NULL AND �p�[�c11 IS NULL)" & _
        " AND (�p�[�c12 = ? OR ? IS NULL AND �p�[�c12 IS NULL) AND (�p�[�c13 = ? OR ? IS NULL AND " & _
        "�p�[�c13 IS NULL) AND (�p�[�c14 = ? OR ? IS NULL AND �p�[�c14 IS NULL) AND (�p�[�c15 = ? OR " & _
        "? IS NULL AND �p�[�c15 IS NULL) AND (�p�[�c2 = ? OR ? IS NULL AND �p�[�c2 IS NULL) AND (�p" & _
        "�[�c3 = ? OR ? IS NULL AND �p�[�c3 IS NULL) AND (�p�[�c4 = ? OR ? IS NULL AND �p�[�c4 IS NU" & _
        "LL) AND (�p�[�c5 = ? OR ? IS NULL AND �p�[�c5 IS NULL) AND (�p�[�c6 = ? OR ? IS NULL AND " & _
        "�p�[�c6 IS NULL) AND (�p�[�c7 = ? OR ? IS NULL AND �p�[�c7 IS NULL) AND (�p�[�c8 = ? OR ? IS" & _
        " NULL AND �p�[�c8 IS NULL) AND (�p�[�c9 = ? OR ? IS NULL AND �p�[�c9 IS NULL) AND (����1 = " & _
        "? OR ? IS NULL AND ����1 IS NULL) AND (����10 = ? OR ? IS NULL AND ����10 IS NULL) AND" & _
        " (����2 = ? OR ? IS NULL AND ����2 IS NULL) AND (����3 = ? OR ? IS NULL AND ����3 IS NUL" & _
        "L) AND (����4 = ? OR ? IS NULL AND ����4 IS NULL) AND (����5 = ? OR ? IS NULL AND ����5 " & _
        "IS NULL) AND (����6 = ? OR ? IS NULL AND ����6 IS NULL) AND (����7 = ? OR ? IS NULL AN" & _
        "D ����7 IS NULL) AND (����8 = ? OR ? IS NULL AND ����8 IS NULL) AND (����9 = ? OR ? IS N" & _
        "ULL AND ����9 IS NULL)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c1", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c1"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c10", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c10"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c11", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c11"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c12", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c12"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c13", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c13"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c14", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c14"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c15", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c15"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c2", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c2"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c3", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c3"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c4", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c4"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c5", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c5"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c6", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c6"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c7", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c7"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c8", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c8"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c9", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c9"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���i����", System.Data.OleDb.OleDbType.VarWChar, 12, "���i����"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����11", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����11", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����12", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����12", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����13", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����13", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����14", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����14", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����15", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "����15", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("����9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i����", System.Data.OleDb.OleDbType.VarWChar, 12, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c1", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c11", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c10", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c101", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c111", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c112", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c11", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c12", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c121", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c12", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c13", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c131", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c13", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c14", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c141", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c14", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c15", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c151", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c15", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c2", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c21", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c3", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c31", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c4", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c41", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c5", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c51", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c6", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c61", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c7", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c71", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c8", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c81", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c9", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c91", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����1", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����11", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����1", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����10", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����101", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����10", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����2", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����21", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����2", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����3", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����31", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����3", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����4", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����41", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����4", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����5", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����51", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����5", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����6", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����61", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����6", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����7", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����71", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����7", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����8", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����81", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����8", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����9", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_����91", System.Data.OleDb.OleDbType.SmallInt, 0, System.Data.ParameterDirection.Input, False, CType(5, Byte), CType(0, Byte), "����9", System.Data.DataRowVersion.Original, Nothing))
        '
        'PartsTenkai
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(852, 473)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "PartsTenkai"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsTenkai"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    Private dtcPartsCheck As OleDb.OleDbCommand

    '*** �t�H�[�����[�h�C�x���g
    Private Sub PartsTenkai_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        '�^�C�g���ݒ�
        Me.Text = Common.TITLE

        '�}�X�^����f�[�^�Z�b�g�ɓǂݍ���
        SetDbConnection(OleDbDataAdapter1)
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            Dim i As Integer

            '�ő���͕������ݒ�
            CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            For i = 1 To 15
                CType(.GridColumnStyles("�p�[�c" & i), DataGridTextBoxColumn).TextBox.MaxLength = 12
                CType(.GridColumnStyles("����" & i), DataGridTextBoxColumn).TextBox.MaxLength = 5
            Next

            '�啶���̂ݓ��͂�����
            CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper
            For i = 1 To 15
                CType(.GridColumnStyles("�p�[�c" & i), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper
            Next

            '�L�[���͐����p�C�x���g�n���h���ݒ�
            AddHandler CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_ShouhinMeishou
            For i = 1 To 15
                AddHandler CType(.GridColumnStyles("�p�[�c" & i), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_PartsMeishou
                AddHandler CType(.GridColumnStyles("����" & i), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            Next

        End With

        '�p�[�c�o�^�`�F�b�N�pSQL
        dtcPartsCheck = New OleDb.OleDbCommand("SELECT COUNT(*) FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c����=?", dbConnection)
        dtcPartsCheck.Parameters.Add("�p�[�c����", OleDb.OleDbType.VarWChar)

        '�p�[�c�o�^�`�F�b�N�p�C�x���g�n���h���ݒ�
        AddHandler DsTable1.�p�[�c�W�J�}�X�^.ColumnChanging, New DataColumnChangeEventHandler(AddressOf DsTable1_Column_Changing)
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�X�V�m�F
        If MsgBox("�p�[�c�W�J�}�X�^�[���X�V���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB�X�V����
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DB�G���[
            MsgBox("�p�[�c�W�J�}�X�^�[�̍X�V�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�����m�F
        MsgBox("�p�[�c�W�J�}�X�^�[�̍X�V���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub

    Private Sub DsTable1_Column_Changing(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        If e.Column.ColumnName.Substring(0, 3) = "�p�[�c" Then
            dtcPartsCheck.Parameters("�p�[�c����").Value = e.ProposedValue
            If dtcPartsCheck.ExecuteScalar() = 0 Then
                Beep()
                e.Row.SetColumnError(e.Column.ColumnName, "���͂����p�[�c�̓p�[�c�݌Ƀ}�X�^�ɓo�^����Ă��܂���B")
            Else
                e.Row.SetColumnError(e.Column.ColumnName, Nothing)
            End If
        End If
    End Sub
End Class
