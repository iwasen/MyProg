Public Class frmTanaoroshi
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents btnDisplay As System.Windows.Forms.Button
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents cmbPartsShubetsu As System.Windows.Forms.ComboBox
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents dtgTanaoroshi As MyDataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsWork1 As zaiko.dsWork
    Friend WithEvents �p�[�c���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���݌ɐ� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���I���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���� As System.Windows.Forms.DataGridTextBoxColumn
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.cmbPartsShubetsu = New System.Windows.Forms.ComboBox()
        Me.btnDisplay = New System.Windows.Forms.Button()
        Me.dtgTanaoroshi = New zaiko.MyDataGrid()
        Me.DsWork1 = New zaiko.dsWork()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.�p�[�c���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���݌ɐ� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���I���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        CType(Me.dtgTanaoroshi, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).BeginInit()
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
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "�I������"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(28, 52)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(73, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "�p�[�c���"
        '
        'cmbPartsShubetsu
        '
        Me.cmbPartsShubetsu.DisplayMember = "�p�[�c��ʃR�[�h"
        Me.cmbPartsShubetsu.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbPartsShubetsu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsShubetsu.Location = New System.Drawing.Point(108, 48)
        Me.cmbPartsShubetsu.Name = "cmbPartsShubetsu"
        Me.cmbPartsShubetsu.Size = New System.Drawing.Size(176, 23)
        Me.cmbPartsShubetsu.TabIndex = 3
        '
        'btnDisplay
        '
        Me.btnDisplay.Enabled = False
        Me.btnDisplay.Location = New System.Drawing.Point(300, 48)
        Me.btnDisplay.Name = "btnDisplay"
        Me.btnDisplay.Size = New System.Drawing.Size(88, 24)
        Me.btnDisplay.TabIndex = 4
        Me.btnDisplay.Text = "�\��(&D)"
        '
        'dtgTanaoroshi
        '
        Me.dtgTanaoroshi.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgTanaoroshi.CaptionVisible = False
        Me.dtgTanaoroshi.DataMember = ""
        Me.dtgTanaoroshi.DataSource = Me.DsWork1.�I������
        Me.dtgTanaoroshi.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtgTanaoroshi.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgTanaoroshi.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgTanaoroshi.Location = New System.Drawing.Point(24, 86)
        Me.dtgTanaoroshi.Name = "dtgTanaoroshi"
        Me.dtgTanaoroshi.Size = New System.Drawing.Size(484, 364)
        Me.dtgTanaoroshi.TabIndex = 5
        Me.dtgTanaoroshi.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsWork1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgTanaoroshi
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.�p�[�c����, Me.���݌ɐ�, Me.���I����, Me.����})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�I������"
        '
        '�p�[�c����
        '
        Me.�p�[�c����.Format = ""
        Me.�p�[�c����.FormatInfo = Nothing
        Me.�p�[�c����.HeaderText = "�p�[�c����"
        Me.�p�[�c����.MappingName = "�p�[�c����"
        Me.�p�[�c����.NullText = ""
        Me.�p�[�c����.ReadOnly = True
        Me.�p�[�c����.Width = 185
        '
        '���݌ɐ�
        '
        Me.���݌ɐ�.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.���݌ɐ�.Format = ""
        Me.���݌ɐ�.FormatInfo = Nothing
        Me.���݌ɐ�.HeaderText = "���݌ɐ�"
        Me.���݌ɐ�.MappingName = "���݌ɐ�"
        Me.���݌ɐ�.NullText = ""
        Me.���݌ɐ�.ReadOnly = True
        Me.���݌ɐ�.Width = 80
        '
        '���I����
        '
        Me.���I����.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.���I����.Format = ""
        Me.���I����.FormatInfo = Nothing
        Me.���I����.HeaderText = "���I����"
        Me.���I����.MappingName = "���I����"
        Me.���I����.NullText = ""
        Me.���I����.Width = 80
        '
        '����
        '
        Me.����.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.����.Format = "+0;-0;0"
        Me.����.FormatInfo = Nothing
        Me.����.HeaderText = "����"
        Me.����.MappingName = "����"
        Me.����.NullText = ""
        Me.����.ReadOnly = True
        Me.����.Width = 80
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 472)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 6
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(273, 472)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 7
        Me.btnClose.Text = "����(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 458)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�I������", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�p�[�c����", "�p�[�c����"), New System.Data.Common.DataColumnMapping("���݌ɐ�", "���I����"), New System.Data.Common.DataColumnMapping("�I��������", "�I��������")})})
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT �p�[�c���, �p�[�c����, ���i�ɂȂ�����, ���݌ɐ�, �����敪, �I��������, �����_���� FROM �p�[�c�݌Ƀ}�X�^ WHERE (�p�[�c���" & _
        " = ?) ORDER BY �p�[�c����"
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�p�[�c���", System.Data.OleDb.OleDbType.VarWChar, 3, "�p�[�c���"))
        '
        'frmTanaoroshi
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 505)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.btnOK, Me.btnClose, Me.dtgTanaoroshi, Me.btnDisplay, Me.cmbPartsShubetsu, Me.Label1, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmTanaoroshi"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Tanaoroshi"
        CType(Me.dtgTanaoroshi, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub Tanaoroshi_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        PartsShubetsuSelect(cmbPartsShubetsu)

        SetDbConnection(OleDbDataAdapter1)

        DsWork1.�I������.DefaultView.AllowNew = False
        DsWork1.�I������.DefaultView.AllowDelete = False

        With dtgTanaoroshi.TableStyles(0)
            '�ő���͕������ݒ�
            CType(.GridColumnStyles("���I����"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            '�L�[���͐����p�C�x���g�n���h���ݒ�
            AddHandler CType(.GridColumnStyles("���I����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
        End With
    End Sub

    '*** [����]�{�^���N���b�N�C�x���g
    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        '�f�[�^�ύX�`�F�b�N
        If CheckTableUpdate(DsWork1.�I������) Then
            If MsgBox("�f�[�^���ύX����Ă��܂��B�o�^���Ȃ��ŕ��Ă�낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** [�\��]�{�^���N���b�N�C�x���g
    Private Sub btnDisplay_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnDisplay.Click
        Dim table As DataTable = DsWork1.�I������

        '�f�[�^�ύX�`�F�b�N
        If CheckTableUpdate(DsWork1.�I������) Then
            If MsgBox("�f�[�^���ύX����Ă��܂��B�o�^���Ȃ��Ńf�[�^��\�����Ă�낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If

        RemoveHandler table.ColumnChanging, AddressOf DsWork1_Column_Changed

        DsWork1.�I������.Clear()
        OleDbDataAdapter1.SelectCommand.Parameters("�p�[�c���").Value = Me.cmbPartsShubetsu.SelectedValue
        OleDbDataAdapter1.Fill(DsWork1)

        '���I�����ƍ��ق��Z�b�g
        Dim row As dsWork.�I������Row
        For Each row In DsWork1.�I������.Rows
            row.���݌ɐ� = row.���I����
            row.���� = 0
        Next
        table.AcceptChanges()

        AddHandler table.ColumnChanged, New DataColumnChangeEventHandler(AddressOf DsWork1_Column_Changed)
    End Sub

    '*** �f�[�^�O���b�h�t�H�[�J�X�擾�C�x���g
    Private Sub dtgTanaoroshi_Enter(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles dtgTanaoroshi.Enter
        dtgTanaoroshi.CurrentCell = New DataGridCell(0, 2)
    End Sub

    '*** �p�[�c��ʑI���C�x���g
    Private Sub cmbPartsShubetsu_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmbPartsShubetsu.SelectedIndexChanged
        If cmbPartsShubetsu.SelectedValue = "" Then
            Me.btnDisplay.Enabled = False
        Else
            Me.btnDisplay.Enabled = True
        End If
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�p�[�c��ʑI���`�F�b�N
        If cmbPartsShubetsu.SelectedIndex = 0 Then
            MsgBox("�p�[�c��ʂ�I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            cmbPartsShubetsu.Focus()
            Return
        End If

        '�\���m�F
        If DsWork1.�I������.Rows.Count = 0 Then
            MsgBox("�f�[�^���\������Ă��܂���B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            btnDisplay.Focus()
            Return
        End If

        '�o�^�m�F
        If MsgBox("�I�����͂�o�^���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        '�g�����U�N�V�����쐬
        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        '�p�[�c�݌Ƀ}�X�^�X�VSQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE �p�[�c�݌Ƀ}�X�^ SET ���݌ɐ�=?, �I��������=? WHERE �p�[�c����=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("���݌ɐ�", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("�I��������", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�����_�x�����ݒ�SQL
        Dim dbcKeikokuUpdate As New OleDb.OleDbCommand("UPDATE �p�[�c�݌Ƀ}�X�^ SET �����_�x����=? WHERE �p�[�c����=? AND ���݌ɐ� < �����_���� AND �����_�x���� IS NULL", dbConnection, trans)
        dbcKeikokuUpdate.Parameters.Add("�����_�x����", Data.OleDb.OleDbType.DBDate)
        dbcKeikokuUpdate.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�݌ɕύX����ǉ�SQL
        Dim dbcRirekiInert As New OleDb.OleDbCommand("INSERT INTO �݌ɕύX���� (�p�[�c����, ��������, ���o�ɋ敪, ����) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInert.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInert.Parameters.Add("��������", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInert.Parameters.Add("���o�ɋ敪", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInert.Parameters.Add("����", Data.OleDb.OleDbType.Integer)

        'DB�X�V
        Try
            '�p�[�c�݌Ƀ}�X�^�X�V
            Dim row As dsWork.�I������Row
            Dim dtNow As DateTime = Now
            For Each row In DsWork1.�I������.Rows
                If Not row.Is���I����Null Then
                    '���݌ɐ��X�V
                    dbcZaikoUpdate.Parameters("�p�[�c����").Value = row.�p�[�c����
                    dbcZaikoUpdate.Parameters("���݌ɐ�").Value = row.���I����
                    dbcZaikoUpdate.Parameters("�I��������").Value = dtNow
                    dbcZaikoUpdate.ExecuteNonQuery()

                    '�����_�x���`�F�b�N
                    dbcKeikokuUpdate.Parameters("�p�[�c����").Value = row.�p�[�c����
                    dbcKeikokuUpdate.Parameters("�����_�x����").Value = dtNow
                    dbcKeikokuUpdate.ExecuteNonQuery()
                End If
            Next

            '�݌ɕύX�����X�V
            Dim kubun As String
            For Each row In DsWork1.�I������.Rows
                If row.���� <> 0 Then
                    If row.���� > 0 Then
                        kubun = "08"
                    Else
                        kubun = "09"
                    End If

                    dbcRirekiInert.Parameters("�p�[�c����").Value = row.�p�[�c����
                    dbcRirekiInert.Parameters("��������").Value = dtNow
                    dbcRirekiInert.Parameters("���o�ɋ敪").Value = kubun
                    dbcRirekiInert.Parameters("����").Value = row.����
                    dbcRirekiInert.ExecuteNonQuery()
                End If
            Next

            trans.Commit()
        Catch ex As Exception
            trans.Rollback()
            MsgBox("�I�����͂̓o�^�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�������b�Z�[�W
        MsgBox("�I�����͂̓o�^���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '�����ē��͂ɔ����ăf�[�^�N���A
        Me.cmbPartsShubetsu.SelectedIndex = 0
        DsWork1.Clear()
    End Sub

    '*** �f�[�^�O���b�h�J�����ύX�C�x���g
    Private Sub DsWork1_Column_Changed(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        Dim table As dsWork.�I������DataTable = sender
        Select Case e.Column.ColumnName
            Case "���I����"
                If e.ProposedValue Is DBNull.Value Then
                    e.Row("����") = 0
                Else
                    e.Row("����") = e.ProposedValue - e.Row("���݌ɐ�")
                End If
        End Select
    End Sub
End Class
