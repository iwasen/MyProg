Public Class PartsZaiko
    Inherits System.Windows.Forms.Form

#Region " Windows �t�H�[�� �f�U�C�i�Ő������ꂽ�R�[�h "

    Public Sub New()
        MyBase.New()

        ' ���̌Ăяo���� Windows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
        InitializeComponent()

        ' InitializeComponent() �Ăяo���̌�ɏ�������ǉ����܂��B

        '�A�_�v�^��DB�R�l�N�V�����ݒ�
        SetDbConnection(OleDbDataAdapter1)
        SetDbConnection(OleDbDataAdapter2)

        �����敪.AddList("", "")
        �����敪.AddList("������", "1")
        �����敪.AddList("������", "2")

        OleDbDataAdapter2.Fill(DsTable1)
        �p�[�c���.ColumnComboBox.DataSource = DsTable1.�p�[�c��ʃ}�X�^.DefaultView
        �p�[�c���.ColumnComboBox.DisplayMember = "�p�[�c��ʖ�"
        �p�[�c���.ColumnComboBox.ValueMember = "�p�[�c��ʃR�[�h"
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
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents dtgMaster As System.Windows.Forms.DataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents DsTable1 As zaiko.dsTable
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents OleDbDataAdapter2 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand2 As System.Data.OleDb.OleDbCommand
    Friend WithEvents �p�[�c���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �����敪 As zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn
    Friend WithEvents �p�[�c��� As zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn
    Friend WithEvents ���݌ɐ� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �����_���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���l As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���i�ɂȂ����� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �I�������� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �����_�x���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbUpdateCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents OleDbDeleteCommand1 As System.Data.OleDb.OleDbCommand
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.�p�[�c���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�����敪 = New zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn()
        Me.�p�[�c��� = New zaiko.DataGridTextBoxCombo.DataGridComboBoxColumn()
        Me.���݌ɐ� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�����_���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���l = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�����_�x���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���i�ɂȂ����� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�I�������� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter2 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand2 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbDeleteCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.OleDbUpdateCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
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
        'dtgMaster
        '
        Me.dtgMaster.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgMaster.CaptionText = "�p�[�c�݌Ƀ}�X�^�["
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.�p�[�c�݌Ƀ}�X�^
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
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.�p�[�c����, Me.�����敪, Me.�p�[�c���, Me.���݌ɐ�, Me.�����_����, Me.���l, Me.�����_�x����, Me.���i�ɂȂ�����, Me.�I��������})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�p�[�c�݌Ƀ}�X�^"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        '�p�[�c����
        '
        Me.�p�[�c����.Format = ""
        Me.�p�[�c����.FormatInfo = Nothing
        Me.�p�[�c����.HeaderText = "�p�[�c����"
        Me.�p�[�c����.MappingName = "�p�[�c����"
        Me.�p�[�c����.NullText = ""
        Me.�p�[�c����.Width = 85
        '
        '�����敪
        '
        Me.�����敪.Format = ""
        Me.�����敪.FormatInfo = Nothing
        Me.�����敪.HeaderText = "�����敪"
        Me.�����敪.MappingName = "�����敪"
        Me.�����敪.NullText = ""
        Me.�����敪.Width = 70
        '
        '�p�[�c���
        '
        Me.�p�[�c���.Format = ""
        Me.�p�[�c���.FormatInfo = Nothing
        Me.�p�[�c���.HeaderText = "�p�[�c���"
        Me.�p�[�c���.MappingName = "�p�[�c���"
        Me.�p�[�c���.NullText = ""
        Me.�p�[�c���.Width = 120
        '
        '���݌ɐ�
        '
        Me.���݌ɐ�.Format = ""
        Me.���݌ɐ�.FormatInfo = Nothing
        Me.���݌ɐ�.HeaderText = "���݌ɐ�"
        Me.���݌ɐ�.MappingName = "���݌ɐ�"
        Me.���݌ɐ�.NullText = ""
        Me.���݌ɐ�.ReadOnly = True
        Me.���݌ɐ�.Width = 80
        '
        '�����_����
        '
        Me.�����_����.Format = ""
        Me.�����_����.FormatInfo = Nothing
        Me.�����_����.HeaderText = "�����_����"
        Me.�����_����.MappingName = "�����_����"
        Me.�����_����.NullText = ""
        Me.�����_����.Width = 80
        '
        '���l
        '
        Me.���l.Format = ""
        Me.���l.FormatInfo = Nothing
        Me.���l.HeaderText = "���l�i�����j"
        Me.���l.MappingName = "���l"
        Me.���l.NullText = ""
        Me.���l.ReadOnly = True
        Me.���l.Width = 120
        '
        '�����_�x����
        '
        Me.�����_�x����.Format = ""
        Me.�����_�x����.FormatInfo = Nothing
        Me.�����_�x����.HeaderText = "�����_�x����"
        Me.�����_�x����.MappingName = "�����_�x����"
        Me.�����_�x����.NullText = ""
        Me.�����_�x����.ReadOnly = True
        Me.�����_�x����.Width = 80
        '
        '���i�ɂȂ�����
        '
        Me.���i�ɂȂ�����.Format = ""
        Me.���i�ɂȂ�����.FormatInfo = Nothing
        Me.���i�ɂȂ�����.HeaderText = "���i�ɂȂ�����"
        Me.���i�ɂȂ�����.MappingName = "���i�ɂȂ�����"
        Me.���i�ɂȂ�����.NullText = ""
        Me.���i�ɂȂ�����.ReadOnly = True
        Me.���i�ɂȂ�����.Width = 80
        '
        '�I��������
        '
        Me.�I��������.Format = ""
        Me.�I��������.FormatInfo = Nothing
        Me.�I��������.HeaderText = "�I��������"
        Me.�I��������.MappingName = "�I��������"
        Me.�I��������.NullText = ""
        Me.�I��������.ReadOnly = True
        Me.�I��������.Width = 80
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(327, 442)
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
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 430)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(838, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter2
        '
        Me.OleDbDataAdapter2.SelectCommand = Me.OleDbSelectCommand2
        Me.OleDbDataAdapter2.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�p�[�c��ʃ}�X�^", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�p�[�c��ʃR�[�h", "�p�[�c��ʃR�[�h"), New System.Data.Common.DataColumnMapping("�p�[�c��ʖ�", "�p�[�c��ʖ�")})})
        '
        'OleDbSelectCommand2
        '
        Me.OleDbSelectCommand2.CommandText = "SELECT [�p�[�c��ʃR�[�h], [�p�[�c��ʖ�] FROM [�p�[�c��ʃ}�X�^] ORDER BY [�p�[�c��ʃR�[�h]"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�p�[�c�݌Ƀ}�X�^", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�p�[�c����", "�p�[�c����"), New System.Data.Common.DataColumnMapping("�����敪", "�����敪"), New System.Data.Common.DataColumnMapping("�p�[�c���", "�p�[�c���"), New System.Data.Common.DataColumnMapping("���݌ɐ�", "���݌ɐ�"), New System.Data.Common.DataColumnMapping("�����_�x����", "�����_�x����"), New System.Data.Common.DataColumnMapping("�����_����", "�����_����"), New System.Data.Common.DataColumnMapping("���l", "���l"), New System.Data.Common.DataColumnMapping("���i�ɂȂ�����", "���i�ɂȂ�����"), New System.Data.Common.DataColumnMapping("�I��������", "�I��������")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM [�p�[�c�݌Ƀ}�X�^] WHERE ([�p�[�c����] = ?) AND ([�p�[�c���] = ?) AND (���l = ? OR ? IS " & _
        "NULL AND ���l IS NULL) AND (�I�������� = ? OR ? IS NULL AND �I�������� IS NULL) AND (���i�ɂȂ�����" & _
        " = ? OR ? IS NULL AND ���i�ɂȂ����� IS NULL) AND (�����_�x���� = ? OR ? IS NULL AND �����_�x���� I" & _
        "S NULL) AND (�����敪 = ?)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c����", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c���", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c���", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���l", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���l", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���l1", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���l", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�I��������", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�I��������", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�I��������1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�I��������", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i�ɂȂ�����", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i�ɂȂ�����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i�ɂȂ�����1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i�ɂȂ�����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����_�x����", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����_�x����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����_�x����1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����_�x����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����敪", System.Data.OleDb.OleDbType.VarWChar, 1, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����敪", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO [�p�[�c�݌Ƀ}�X�^] ([�p�[�c����], �����敪, [�p�[�c���], ���݌ɐ�, �����_�x����, �����_����, ���l, ���i�ɂȂ�����," & _
        " �I��������) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c����", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c����"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����敪", System.Data.OleDb.OleDbType.VarWChar, 1, "�����敪"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c���", System.Data.OleDb.OleDbType.VarWChar, 3, "�p�[�c���"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���݌ɐ�", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "���݌ɐ�", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����_�x����", System.Data.OleDb.OleDbType.DBDate, 0, "�����_�x����"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����_����", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "�����_����", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���l", System.Data.OleDb.OleDbType.VarWChar, 50, "���l"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���i�ɂȂ�����", System.Data.OleDb.OleDbType.DBDate, 0, "���i�ɂȂ�����"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�I��������", System.Data.OleDb.OleDbType.DBDate, 0, "�I��������"))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT [�p�[�c����], �����敪, [�p�[�c���], ���݌ɐ�, �����_�x����, �����_����, ���l, ���i�ɂȂ�����, �I�������� FROM [�p�[�c��" & _
        "�Ƀ}�X�^] ORDER BY [�p�[�c����]"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE [�p�[�c�݌Ƀ}�X�^] SET [�p�[�c����] = ?, �����敪 = ?, [�p�[�c���] = ?, ���݌ɐ� = ?, �����_�x���� = ?, ��" & _
        "���_���� = ?, ���l = ?, ���i�ɂȂ����� = ?, �I�������� = ? WHERE ([�p�[�c����] = ?) AND ([�p�[�c���] = ?) A" & _
        "ND (���l = ? OR ? IS NULL AND ���l IS NULL) AND (�I�������� = ? OR ? IS NULL AND �I�������� IS" & _
        " NULL) AND (���i�ɂȂ����� = ? OR ? IS NULL AND ���i�ɂȂ����� IS NULL) AND (�����_�x���� = ? OR ? I" & _
        "S NULL AND �����_�x���� IS NULL) AND (�����敪 = ?)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c����", System.Data.OleDb.OleDbType.VarWChar, 9, "�p�[�c����"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����敪", System.Data.OleDb.OleDbType.VarWChar, 1, "�����敪"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c���", System.Data.OleDb.OleDbType.VarWChar, 3, "�p�[�c���"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���݌ɐ�", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "���݌ɐ�", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����_�x����", System.Data.OleDb.OleDbType.DBDate, 0, "�����_�x����"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�����_����", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "�����_����", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���l", System.Data.OleDb.OleDbType.VarWChar, 50, "���l"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���i�ɂȂ�����", System.Data.OleDb.OleDbType.DBDate, 0, "���i�ɂȂ�����"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�I��������", System.Data.OleDb.OleDbType.DBDate, 0, "�I��������"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c����", System.Data.OleDb.OleDbType.VarWChar, 9, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c���", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c���", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���l", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���l", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���l1", System.Data.OleDb.OleDbType.VarWChar, 50, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���l", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�I��������", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�I��������", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�I��������1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�I��������", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i�ɂȂ�����", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i�ɂȂ�����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_���i�ɂȂ�����1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "���i�ɂȂ�����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����_�x����", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����_�x����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����_�x����1", System.Data.OleDb.OleDbType.DBDate, 0, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����_�x����", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�����敪", System.Data.OleDb.OleDbType.VarWChar, 1, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�����敪", System.Data.DataRowVersion.Original, Nothing))
        '
        'PartsZaiko
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(852, 473)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "PartsZaiko"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsZaiko"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub PartsZaiko_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        '�^�C�g���ݒ�
        Me.Text = Common.TITLE

        '�}�X�^����f�[�^�Z�b�g�ɓǂݍ���
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            '�ő���͕������ݒ�
            CType(.GridColumnStyles("�p�[�c����"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            CType(.GridColumnStyles("�����_����"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            '�啶���̂ݓ��͂�����
            CType(.GridColumnStyles("�p�[�c����"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper

            '�L�[���͐����p�C�x���g�n���h���ݒ�
            AddHandler CType(.GridColumnStyles("�p�[�c����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_PartsMeishou
            AddHandler CType(.GridColumnStyles("���݌ɐ�"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("�����_����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("�����_�x����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
            AddHandler CType(.GridColumnStyles("���i�ɂȂ�����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
            AddHandler CType(.GridColumnStyles("�I��������"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_Date
        End With
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�X�V�m�F
        If MsgBox("�p�[�c�݌Ƀ}�X�^�[���X�V���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB�X�V����
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DB�G���[
            MsgBox("�p�[�c�݌Ƀ}�X�^�[�̍X�V�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�����m�F
        MsgBox("�p�[�c�݌Ƀ}�X�^�[�̍X�V���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub
End Class
