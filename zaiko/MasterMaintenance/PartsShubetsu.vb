Public Class PartsShubetsu
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
    Friend WithEvents �p�[�c��ʃR�[�h As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �p�[�c��ʖ� As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.dtgMaster = New System.Windows.Forms.DataGrid()
        Me.DsTable1 = New zaiko.dsTable()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.�p�[�c��ʃR�[�h = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�p�[�c��ʖ� = New System.Windows.Forms.DataGridTextBoxColumn()
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
        Me.btnOK.Location = New System.Drawing.Point(164, 444)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 4
        Me.btnOK.Text = "OK"
        '
        'btnCancel
        '
        Me.btnCancel.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnCancel.Location = New System.Drawing.Point(272, 444)
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
        Me.dtgMaster.CaptionText = "�p�[�c��ʃ}�X�^�["
        Me.dtgMaster.DataMember = ""
        Me.dtgMaster.DataSource = Me.DsTable1.�p�[�c��ʃ}�X�^
        Me.dtgMaster.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgMaster.Location = New System.Drawing.Point(0, 38)
        Me.dtgMaster.Name = "dtgMaster"
        Me.dtgMaster.Size = New System.Drawing.Size(532, 386)
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
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.�p�[�c��ʃR�[�h, Me.�p�[�c��ʖ�})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�p�[�c��ʃ}�X�^"
        Me.DataGridTableStyle1.PreferredRowHeight = 18
        '
        '�p�[�c��ʃR�[�h
        '
        Me.�p�[�c��ʃR�[�h.Format = ""
        Me.�p�[�c��ʃR�[�h.FormatInfo = Nothing
        Me.�p�[�c��ʃR�[�h.HeaderText = "�p�[�c��ʃR�[�h"
        Me.�p�[�c��ʃR�[�h.MappingName = "�p�[�c��ʃR�[�h"
        Me.�p�[�c��ʃR�[�h.NullText = ""
        '
        '�p�[�c��ʖ�
        '
        Me.�p�[�c��ʖ�.Format = ""
        Me.�p�[�c��ʖ�.FormatInfo = Nothing
        Me.�p�[�c��ʖ�.HeaderText = "�p�[�c��ʖ�"
        Me.�p�[�c��ʖ�.MappingName = "�p�[�c��ʖ�"
        Me.�p�[�c��ʖ�.NullText = ""
        Me.�p�[�c��ʖ�.Width = 200
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
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "�}�X�^�[�����e�i���X"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 432)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 3
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.DeleteCommand = Me.OleDbDeleteCommand1
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�p�[�c��ʃ}�X�^", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�p�[�c��ʃR�[�h", "�p�[�c��ʃR�[�h"), New System.Data.Common.DataColumnMapping("�p�[�c��ʖ�", "�p�[�c��ʖ�")})})
        Me.OleDbDataAdapter1.UpdateCommand = Me.OleDbUpdateCommand1
        '
        'OleDbDeleteCommand1
        '
        Me.OleDbDeleteCommand1.CommandText = "DELETE FROM �p�[�c��ʃ}�X�^ WHERE (�p�[�c��ʃR�[�h = ?) AND (�p�[�c��ʖ� = ? OR ? IS NULL AND �p�[�c��ʖ�" & _
        " IS NULL)"
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʃR�[�h", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʃR�[�h", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʖ�", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʖ�", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbDeleteCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʖ�1", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʖ�", System.Data.DataRowVersion.Original, Nothing))
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO �p�[�c��ʃ}�X�^(�p�[�c��ʃR�[�h, �p�[�c��ʖ�) VALUES (?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c��ʃR�[�h", System.Data.OleDb.OleDbType.VarWChar, 3, "�p�[�c��ʃR�[�h"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c��ʖ�", System.Data.OleDb.OleDbType.VarWChar, 30, "�p�[�c��ʖ�"))
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT �p�[�c��ʃR�[�h, �p�[�c��ʖ� FROM �p�[�c��ʃ}�X�^ ORDER BY �p�[�c��ʃR�[�h"
        '
        'OleDbUpdateCommand1
        '
        Me.OleDbUpdateCommand1.CommandText = "UPDATE �p�[�c��ʃ}�X�^ SET �p�[�c��ʃR�[�h = ?, �p�[�c��ʖ� = ? WHERE (�p�[�c��ʃR�[�h = ?) AND (�p�[�c��ʖ� = ?" & _
        " OR ? IS NULL AND �p�[�c��ʖ� IS NULL)"
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c��ʃR�[�h", System.Data.OleDb.OleDbType.VarWChar, 3, "�p�[�c��ʃR�[�h"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c��ʖ�", System.Data.OleDb.OleDbType.VarWChar, 30, "�p�[�c��ʖ�"))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʃR�[�h", System.Data.OleDb.OleDbType.VarWChar, 3, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʃR�[�h", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʖ�", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʖ�", System.Data.DataRowVersion.Original, Nothing))
        Me.OleDbUpdateCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("Original_�p�[�c��ʖ�1", System.Data.OleDb.OleDbType.VarWChar, 30, System.Data.ParameterDirection.Input, False, CType(0, Byte), CType(0, Byte), "�p�[�c��ʖ�", System.Data.DataRowVersion.Original, Nothing))
        '
        'PartsShubetsu
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 475)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnCancel, Me.dtgMaster, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "PartsShubetsu"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsShubetsu"
        CType(Me.dtgMaster, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsTable1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub PartsShubetsu_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        '�^�C�g���ݒ�
        Me.Text = Common.TITLE

        '�}�X�^����f�[�^�Z�b�g�ɓǂݍ���
        SetDbConnection(OleDbDataAdapter1)
        OleDbDataAdapter1.Fill(DsTable1)

        With dtgMaster.TableStyles(0)
            '�ő���͕������ݒ�
            CType(.GridColumnStyles("�p�[�c��ʃR�[�h"), DataGridTextBoxColumn).TextBox.MaxLength = 3
            CType(.GridColumnStyles("�p�[�c��ʖ�"), DataGridTextBoxColumn).TextBox.MaxLength = 30

            '�L�[���͐����p�C�x���g�n���h���ݒ�
            AddHandler CType(.GridColumnStyles("�p�[�c��ʃR�[�h"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
        End With
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�X�V�m�F
        If MsgBox("�p�[�c��ʃ}�X�^�[���X�V���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        'DB�X�V����
        Try
            OleDbDataAdapter1.Update(DsTable1)
        Catch ex As Exception
            'DB�G���[
            MsgBox("�p�[�c��ʃ}�X�^�[�̍X�V�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�����m�F
        MsgBox("�p�[�c��ʃ}�X�^�[�̍X�V���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub
End Class
