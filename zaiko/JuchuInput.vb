Public Class frmJuchuInput
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents dtgJuchu As System.Windows.Forms.DataGrid
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents dtpJuchuuBi As System.Windows.Forms.DateTimePicker
    Friend WithEvents dtbNouki As System.Windows.Forms.DateTimePicker
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbInsertCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents �󒍔ԍ� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents ���i���� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents �󒍐��� As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents DsWork1 As zaiko.dsWork
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.dtgJuchu = New System.Windows.Forms.DataGrid()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.�󒍔ԍ� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.���i���� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.�󒍐��� = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.dtpJuchuuBi = New System.Windows.Forms.DateTimePicker()
        Me.dtbNouki = New System.Windows.Forms.DateTimePicker()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbInsertCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.DsWork1 = New zaiko.dsWork()
        CType(Me.dtgJuchu, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
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
        Me.lblTitle.Text = "�󒍃f�[�^����"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(20, 52)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(52, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "�󒍓�"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(348, 52)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(36, 15)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "�ڋq"
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(384, 48)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 7
        '
        'dtgJuchu
        '
        Me.dtgJuchu.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgJuchu.CaptionVisible = False
        Me.dtgJuchu.DataMember = ""
        Me.dtgJuchu.DataSource = Me.DsWork1.�󒍖���
        Me.dtgJuchu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtgJuchu.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgJuchu.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.dtgJuchu.Location = New System.Drawing.Point(24, 86)
        Me.dtgJuchu.Name = "dtgJuchu"
        Me.dtgJuchu.Size = New System.Drawing.Size(484, 238)
        Me.dtgJuchu.TabIndex = 0
        Me.dtgJuchu.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AllowSorting = False
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgJuchu
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.�󒍔ԍ�, Me.���i����, Me.�󒍐���})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "�󒍖���"
        '
        '�󒍔ԍ�
        '
        Me.�󒍔ԍ�.Format = ""
        Me.�󒍔ԍ�.FormatInfo = Nothing
        Me.�󒍔ԍ�.HeaderText = "�󒍔ԍ�"
        Me.�󒍔ԍ�.MappingName = "�󒍔ԍ�"
        Me.�󒍔ԍ�.NullText = ""
        Me.�󒍔ԍ�.Width = 150
        '
        '���i����
        '
        Me.���i����.Format = ""
        Me.���i����.FormatInfo = Nothing
        Me.���i����.HeaderText = "���i����"
        Me.���i����.MappingName = "���i����"
        Me.���i����.NullText = ""
        Me.���i����.Width = 200
        '
        '�󒍐���
        '
        Me.�󒍐���.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.�󒍐���.Format = ""
        Me.�󒍐���.FormatInfo = Nothing
        Me.�󒍐���.HeaderText = "�󒍐���"
        Me.�󒍐���.MappingName = "�󒍐���"
        Me.�󒍐���.NullText = ""
        Me.�󒍐���.Width = 75
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 352)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 9
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(273, 352)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 10
        Me.btnClose.Text = "����(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(8, 336)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(512, 4)
        Me.GroupBox1.TabIndex = 8
        Me.GroupBox1.TabStop = False
        '
        'dtpJuchuuBi
        '
        Me.dtpJuchuuBi.CalendarFont = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpJuchuuBi.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpJuchuuBi.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtpJuchuuBi.Location = New System.Drawing.Point(72, 48)
        Me.dtpJuchuuBi.Name = "dtpJuchuuBi"
        Me.dtpJuchuuBi.Size = New System.Drawing.Size(108, 22)
        Me.dtpJuchuuBi.TabIndex = 3
        '
        'dtbNouki
        '
        Me.dtbNouki.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtbNouki.Format = System.Windows.Forms.DateTimePickerFormat.Short
        Me.dtbNouki.Location = New System.Drawing.Point(228, 48)
        Me.dtbNouki.Name = "dtbNouki"
        Me.dtbNouki.Size = New System.Drawing.Size(108, 22)
        Me.dtbNouki.TabIndex = 5
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(192, 52)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(36, 15)
        Me.Label2.TabIndex = 4
        Me.Label2.Text = "�[��"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.InsertCommand = Me.OleDbInsertCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�󒍖���", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�ڋq�R�[�h", "�ڋq�R�[�h"), New System.Data.Common.DataColumnMapping("�󒍔ԍ�", "�󒍔ԍ�"), New System.Data.Common.DataColumnMapping("�󒍓�", "�󒍓�"), New System.Data.Common.DataColumnMapping("�[��", "�[��"), New System.Data.Common.DataColumnMapping("���i����", "���i����"), New System.Data.Common.DataColumnMapping("�󒍐���", "�󒍐���")})})
        '
        'OleDbInsertCommand1
        '
        Me.OleDbInsertCommand1.CommandText = "INSERT INTO [�󒍃f�[�^] ([�ڋq�R�[�h], �󒍔ԍ�, �󒍓�, �[��, ���i����, �󒍐���, �W�����i����) VALUES (?, ?, ?," & _
        " ?, ?, ?, ?)"
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�ڋq�R�[�h", System.Data.OleDb.OleDbType.VarWChar, 20, "�ڋq�R�[�h"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�󒍔ԍ�", System.Data.OleDb.OleDbType.VarWChar, 15, "�󒍔ԍ�"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�󒍓�", System.Data.OleDb.OleDbType.DBDate, 0, "�󒍓�"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�[��", System.Data.OleDb.OleDbType.DBDate, 0, "�[��"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("���i����", System.Data.OleDb.OleDbType.VarWChar, 12, "���i����"))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�󒍐���", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "�󒍐���", System.Data.DataRowVersion.Current, Nothing))
        Me.OleDbInsertCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�W�����i����", System.Data.OleDb.OleDbType.VarWChar, 12, "�W�����i����"))
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("en-US")
        Me.DsWork1.Namespace = "http://tempuri.org/Work.xsd"
        '
        'frmJuchuInput
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 385)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.dtbNouki, Me.Label2, Me.Label3, Me.Label1, Me.dtpJuchuuBi, Me.GroupBox1, Me.btnOK, Me.dtgJuchu, Me.cmbKokyaku, Me.lblTitle, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmJuchuInput"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "JuchuInput"
        CType(Me.dtgJuchu, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub JuchuInput_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        '�^�C�g���ݒ�
        Me.Text = Common.TITLE

        '�ڋq�I�����X�g�ݒ�
        KokyakuSelect(cmbKokyaku)

        'DB�R�l�N�V�����ݒ�
        SetDbConnection(OleDbDataAdapter1)

        '�e�[�u���p�C�x���g�n���h���ݒ�
        AddHandler DsWork1.�󒍖���.ColumnChanged, AddressOf DsWork1_Column_Changed

        With dtgJuchu.TableStyles(0)
            '�ő���͕������ݒ�
            CType(.GridColumnStyles("�󒍔ԍ�"), DataGridTextBoxColumn).TextBox.MaxLength = 15
            CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.MaxLength = 12
            CType(.GridColumnStyles("�󒍐���"), DataGridTextBoxColumn).TextBox.MaxLength = 5

            '�啶���̂ݓ��͂�����
            CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.CharacterCasing = CharacterCasing.Upper

            '�L�[���͐����p�C�x���g�n���h���ݒ�
            AddHandler CType(.GridColumnStyles("�󒍔ԍ�"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
            AddHandler CType(.GridColumnStyles("���i����"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_ShouhinMeishou
            AddHandler CType(.GridColumnStyles("�󒍐���"), DataGridTextBoxColumn).TextBox.KeyPress, AddressOf KeyPress_NumOnly
        End With
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�ڋq�I��L���`�F�b�N
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("�ڋq��I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '�󒍃f�[�^���͗L���`�F�b�N
        If DsWork1.�󒍖���.Count = 0 Then
            MsgBox("�󒍃f�[�^����͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.dtgJuchu.Focus()
            Return
        End If

        '�󒍔ԍ��`�F�b�N�pSQL
        Dim dbcJuchuSelect As New OleDb.OleDbCommand()
        dbcJuchuSelect.Connection = Common.dbConnection
        dbcJuchuSelect.CommandType = CommandType.Text
        dbcJuchuSelect.CommandText = "SELECT COUNT(*) FROM �󒍃f�[�^ WHERE �ڋq�R�[�h=? AND �󒍔ԍ�=?"
        dbcJuchuSelect.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuSelect.Parameters.Add("�󒍔ԍ�", Data.OleDb.OleDbType.VarWChar)

        '�󒍓��̓f�[�^�`�F�b�N
        Dim errFlag As Boolean
        Dim errExist As Boolean = False
        Dim errList As String
        Dim row, row2 As dsWork.�󒍖���Row
        Dim shouhin As String
        For Each row In DsWork1.�󒍖���.Rows
            errFlag = False

            '�󒍔ԍ����̓`�F�b�N
            If row.Is�󒍔ԍ�Null Then
                row.SetColumnError(0, "�󒍔ԍ������͂���Ă��܂���B")
                errFlag = True
            Else
                '�󒍔ԍ��d���`�F�b�N�i���́j
                For Each row2 In DsWork1.�󒍖���.Rows
                    If row Is row2 Then
                        Exit For
                    End If
                    If row("�󒍔ԍ�") = row2("�󒍔ԍ�") Then
                        row.SetColumnError(0, "���̎󒍔ԍ��͂��łɓ��͂���Ă��܂��B")
                        errFlag = True
                    End If
                Next

                '�󒍔ԍ��d���`�F�b�N�i�c�a�j
                dbcJuchuSelect.Parameters("�ڋq�R�[�h").Value = Me.cmbKokyaku.SelectedValue
                dbcJuchuSelect.Parameters("�󒍔ԍ�").Value = row.�󒍔ԍ�
                If CType(dbcJuchuSelect.ExecuteScalar(), Integer) <> 0 Then
                    row.SetColumnError(0, "���̎󒍔ԍ��͂��łɓo�^����Ă��܂��B")
                    errFlag = True
                End If
            End If

            '���i���̓��̓`�F�b�N
            If row.Is���i����Null Then
                row.SetColumnError(1, "���i���̂����͂���Ă��܂���B")
                errFlag = True
            Else
                '���i���̑��݃`�F�b�N
                shouhin = ShouhinCheck(row.���i����)
                If shouhin Is Nothing Then
                    row.SetColumnError(1, "���̏��i���͓̂o�^����Ă��܂���B")
                    errFlag = True
                Else
                    row.�W�����i���� = shouhin
                End If
            End If

            '�󒍐��ʃ`�F�b�N
            If row.Is�󒍐���Null OrElse CType(row.�󒍐���, Integer) <= 0 Then
                row.SetColumnError(2, "�󒍐��ʂ����͂���Ă��܂���B")
                errFlag = True
            End If

            '�G���[���b�Z�[�W
            If errFlag Then
                errList &= "�󒍔ԍ��F" & row("�󒍔ԍ�") & vbCrLf
                errList &= "���i���́F" & row("���i����") & vbCrLf & vbCrLf
                errExist = True
            End If
        Next

        '�G���[���������烊�^�[��
        If errExist Then
            MsgBox("�󒍃f�[�^�ɃG���[������܂����B" & vbCrLf & vbCrLf & _
                errList _
                , MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '�o�^�m�F
        If MsgBox("�󒍃f�[�^��o�^���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        '�󒍓��A�[���A�ڋq�R�[�h���Z�b�g
        For Each row In DsWork1.�󒍖���.Rows
            row.�󒍓� = Me.dtpJuchuuBi.Value
            row.�[�� = Me.dtbNouki.Value
            row.�ڋq�R�[�h = Me.cmbKokyaku.SelectedValue
        Next

        '�󒍃f�[�^�e�[�u���ɒǉ�
        Try
            OleDbDataAdapter1.Update(DsWork1)
        Catch ex As Exception
            MsgBox("�󒍓��͂̓o�^�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�����m�F���b�Z�[�W
        MsgBox("�󒍃f�[�^�̓o�^���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '�����ē��͂���ꍇ�ɔ����ăf�[�^�N���A
        DsWork1.Clear()
    End Sub

    '*** [����]�{�^���N���b�N�C�x���g
    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        '�󒍃f�[�^���͗L���`�F�b�N
        If DsWork1.�󒍖���.Count > 0 Then
            If MsgBox("�󒍃f�[�^�����͂���Ă��܂��B�o�^���Ȃ��ŕ��Ă�낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** �f�[�^�O���b�h�J�����ύX�C�x���g
    Private Sub DsWork1_Column_Changed(ByVal sender As Object, ByVal e As DataColumnChangeEventArgs)
        '�G���[��Ԃ��N���A
        e.Row.SetColumnError(e.Column, Nothing)
    End Sub
End Class
