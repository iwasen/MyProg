Public Class frmPartsTenkai
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
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents txtMishori As System.Windows.Forms.TextBox
    Friend WithEvents txtShoriNo As System.Windows.Forms.TextBox
    Friend WithEvents OleDbDataAdapter1 As System.Data.OleDb.OleDbDataAdapter
    Friend WithEvents OleDbSelectCommand1 As System.Data.OleDb.OleDbCommand
    Friend WithEvents DsWork1 As zaiko.dsWork
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtMishori = New System.Windows.Forms.TextBox()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.txtShoriNo = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.OleDbDataAdapter1 = New System.Data.OleDb.OleDbDataAdapter()
        Me.OleDbSelectCommand1 = New System.Data.OleDb.OleDbCommand()
        Me.DsWork1 = New zaiko.dsWork()
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
        Me.lblTitle.Text = "�p�[�c�W�J�E�݌Ɉ���"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(32, 96)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(103, 15)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "�������f�[�^��"
        '
        'txtMishori
        '
        Me.txtMishori.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtMishori.Location = New System.Drawing.Point(140, 92)
        Me.txtMishori.Name = "txtMishori"
        Me.txtMishori.ReadOnly = True
        Me.txtMishori.Size = New System.Drawing.Size(72, 22)
        Me.txtMishori.TabIndex = 3
        Me.txtMishori.TabStop = False
        Me.txtMishori.Text = ""
        Me.txtMishori.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(165, 160)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 7
        Me.btnOK.Text = "���s(&E)"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(264, 96)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(129, 15)
        Me.Label2.TabIndex = 5
        Me.Label2.Text = "�݌Ɉ��������ԍ�"
        '
        'txtShoriNo
        '
        Me.txtShoriNo.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtShoriNo.Location = New System.Drawing.Point(396, 92)
        Me.txtShoriNo.Name = "txtShoriNo"
        Me.txtShoriNo.ReadOnly = True
        Me.txtShoriNo.Size = New System.Drawing.Size(52, 22)
        Me.txtShoriNo.TabIndex = 6
        Me.txtShoriNo.TabStop = False
        Me.txtShoriNo.Text = ""
        Me.txtShoriNo.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(220, 96)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(21, 15)
        Me.Label3.TabIndex = 4
        Me.Label3.Text = "��"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(274, 160)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "����(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 144)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(72, 48)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 13
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(32, 52)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(36, 15)
        Me.Label4.TabIndex = 12
        Me.Label4.Text = "�ڋq"
        '
        'OleDbDataAdapter1
        '
        Me.OleDbDataAdapter1.SelectCommand = Me.OleDbSelectCommand1
        Me.OleDbDataAdapter1.TableMappings.AddRange(New System.Data.Common.DataTableMapping() {New System.Data.Common.DataTableMapping("Table", "�p�[�c�W�J", New System.Data.Common.DataColumnMapping() {New System.Data.Common.DataColumnMapping("�ڋq�R�[�h", "�ڋq�R�[�h"), New System.Data.Common.DataColumnMapping("�󒍔ԍ�", "�󒍔ԍ�"), New System.Data.Common.DataColumnMapping("���i����", "���i����"), New System.Data.Common.DataColumnMapping("�󒍐���", "�󒍐���"), New System.Data.Common.DataColumnMapping("�݌Ɉ�������", "�݌Ɉ�������"), New System.Data.Common.DataColumnMapping("�݌Ɉ��������ԍ�", "�݌Ɉ��������ԍ�"), New System.Data.Common.DataColumnMapping("�p�[�c1", "�p�[�c1"), New System.Data.Common.DataColumnMapping("����1", "����1"), New System.Data.Common.DataColumnMapping("�p�[�c2", "�p�[�c2"), New System.Data.Common.DataColumnMapping("����2", "����2"), New System.Data.Common.DataColumnMapping("�p�[�c3", "�p�[�c3"), New System.Data.Common.DataColumnMapping("����3", "����3"), New System.Data.Common.DataColumnMapping("�p�[�c4", "�p�[�c4"), New System.Data.Common.DataColumnMapping("����4", "����4"), New System.Data.Common.DataColumnMapping("�p�[�c5", "�p�[�c5"), New System.Data.Common.DataColumnMapping("����5", "����5"), New System.Data.Common.DataColumnMapping("�p�[�c6", "�p�[�c6"), New System.Data.Common.DataColumnMapping("����6", "����6"), New System.Data.Common.DataColumnMapping("�p�[�c7", "�p�[�c7"), New System.Data.Common.DataColumnMapping("����7", "����7"), New System.Data.Common.DataColumnMapping("�p�[�c8", "�p�[�c8"), New System.Data.Common.DataColumnMapping("����8", "����8"), New System.Data.Common.DataColumnMapping("�p�[�c9", "�p�[�c9"), New System.Data.Common.DataColumnMapping("����9", "����9"), New System.Data.Common.DataColumnMapping("�p�[�c10", "�p�[�c10"), New System.Data.Common.DataColumnMapping("����10", "����10"), New System.Data.Common.DataColumnMapping("�p�[�c11", "�p�[�c11"), New System.Data.Common.DataColumnMapping("����11", "����11"), New System.Data.Common.DataColumnMapping("�p�[�c12", "�p�[�c12"), New System.Data.Common.DataColumnMapping("����12", "����12"), New System.Data.Common.DataColumnMapping("�p�[�c13", "�p�[�c13"), New System.Data.Common.DataColumnMapping("����13", "����13"), New System.Data.Common.DataColumnMapping("�p�[�c14", "�p�[�c14"), New System.Data.Common.DataColumnMapping("����14", "����14"), New System.Data.Common.DataColumnMapping("�p�[�c15", "�p�[�c15"), New System.Data.Common.DataColumnMapping("����15", "����15")})})
        '
        'OleDbSelectCommand1
        '
        Me.OleDbSelectCommand1.CommandText = "SELECT �󒍃f�[�^.�󒍔ԍ�, �󒍃f�[�^.���i����, �󒍃f�[�^.�󒍐���, �p�[�c�W�J�}�X�^.�p�[�c1, �p�[�c�W�J�}�X�^.����1, �p�[�c�W�J�}�X�^." & _
        "�p�[�c2, �p�[�c�W�J�}�X�^.����2, �p�[�c�W�J�}�X�^.�p�[�c3, �p�[�c�W�J�}�X�^.����3, �p�[�c�W�J�}�X�^.�p�[�c4, �p�[�c�W�J�}�X�^.����4, �p�[" & _
        "�c�W�J�}�X�^.�p�[�c5, �p�[�c�W�J�}�X�^.����5, �p�[�c�W�J�}�X�^.�p�[�c6, �p�[�c�W�J�}�X�^.����6, �p�[�c�W�J�}�X�^.�p�[�c7, �p�[�c�W�J�}�X�^." & _
        "����7, �p�[�c�W�J�}�X�^.�p�[�c8, �p�[�c�W�J�}�X�^.����8, �p�[�c�W�J�}�X�^.�p�[�c9, �p�[�c�W�J�}�X�^.����9, �p�[�c�W�J�}�X�^.�p�[�c10, �p" & _
        "�[�c�W�J�}�X�^.����10, �p�[�c�W�J�}�X�^.�p�[�c11, �p�[�c�W�J�}�X�^.����11, �p�[�c�W�J�}�X�^.�p�[�c12, �p�[�c�W�J�}�X�^.����12, �p�[�c�W" & _
        "�J�}�X�^.�p�[�c13, �p�[�c�W�J�}�X�^.����13, �p�[�c�W�J�}�X�^.�p�[�c14, �p�[�c�W�J�}�X�^.����14, �p�[�c�W�J�}�X�^.�p�[�c15, �p�[�c�W�J�}" & _
        "�X�^.����15 FROM �󒍃f�[�^ INNER JOIN �p�[�c�W�J�}�X�^ ON �󒍃f�[�^.�W�����i���� = �p�[�c�W�J�}�X�^.���i���� WHERE (��" & _
        "�f�[�^.�ڋq�R�[�h = ?) AND (�󒍃f�[�^.�݌Ɉ��������ԍ� = ?)"
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�ڋq�R�[�h", System.Data.OleDb.OleDbType.VarWChar, 20, "�ڋq�R�[�h"))
        Me.OleDbSelectCommand1.Parameters.Add(New System.Data.OleDb.OleDbParameter("�݌Ɉ��������ԍ�", System.Data.OleDb.OleDbType.Integer, 0, System.Data.ParameterDirection.Input, False, CType(10, Byte), CType(0, Byte), "�݌Ɉ��������ԍ�", System.Data.DataRowVersion.Current, Nothing))
        '
        'DsWork1
        '
        Me.DsWork1.DataSetName = "dsWork"
        Me.DsWork1.Locale = New System.Globalization.CultureInfo("en-US")
        Me.DsWork1.Namespace = "http://tempuri.org/Work.xsd"
        '
        'frmPartsTenkai
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 195)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.cmbKokyaku, Me.Label4, Me.Label1, Me.Label2, Me.Label3, Me.GroupBox1, Me.btnOK, Me.txtMishori, Me.lblTitle, Me.txtShoriNo, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmPartsTenkai"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "PartsTenkai"
        CType(Me.DsWork1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub PartsTenkai_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)
        Me.txtShoriNo.Text = GetNextShoriNo()

        SetDbConnection(OleDbDataAdapter1)
    End Sub

    '*** [���s]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�ڋq�I���`�F�b�N
        If Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("�ڋq��I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        '�������f�[�^���`�F�b�N
        If Me.txtMishori.Text = 0 Then
            MsgBox("��������f�[�^������܂���B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '�󒍃f�[�^�ƃp�[�c�W�J�}�X�^�ǂݎ��
        OleDbDataAdapter1.SelectCommand.Parameters("�ڋq�R�[�h").Value = Me.cmbKokyaku.SelectedValue
        OleDbDataAdapter1.SelectCommand.Parameters("�݌Ɉ��������ԍ�").Value = 0
        OleDbDataAdapter1.Fill(DsWork1)

        '�p�[�c�݌Ɉ�������
        Dim dtPartsHikiete As dsWork.�p�[�c�݌Ɉ���DataTable
        dtPartsHikiete = PartsZaikoHikiate(DsWork1.�p�[�c�W�J)

        '�g�����U�N�V�����쐬
        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        '�󒍃f�[�^�X�VSQL
        Dim dbcJuchuUpdate As New OleDb.OleDbCommand("UPDATE �󒍃f�[�^ SET �݌Ɉ�������=?, �݌Ɉ��������ԍ�=? WHERE �ڋq�R�[�h=? AND �󒍔ԍ�=?", dbConnection, trans)
        dbcJuchuUpdate.Parameters.Add("�݌Ɉ�������", Data.OleDb.OleDbType.DBDate)
        dbcJuchuUpdate.Parameters.Add("�݌Ɉ��������ԍ�", Data.OleDb.OleDbType.Integer)
        dbcJuchuUpdate.Parameters.Add("�ڋq�R�[�h", Data.OleDb.OleDbType.VarWChar)
        dbcJuchuUpdate.Parameters.Add("�󒍔ԍ�", Data.OleDb.OleDbType.VarWChar)

        '�p�[�c�݌Ƀ}�X�^�I��SQL
        Dim dbcZaikoSelect As New OleDb.OleDbCommand("SELECT ���݌ɐ�, �����_����, �����_�x����, ���i�ɂȂ����� FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c����=?", dbConnection, trans)
        dbcZaikoSelect.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�p�[�c�݌Ƀ}�X�^�X�VSQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE �p�[�c�݌Ƀ}�X�^ SET ���݌ɐ�=?, �����_�x���� = ?, ���i�ɂȂ�����=? WHERE �p�[�c����=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("���݌ɐ�", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("�����_�x����", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("���i�ɂȂ�����", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�݌ɕύX����ǉ�SQL
        Dim dbcRirekiInsert As New OleDb.OleDbCommand("INSERT INTO �݌ɕύX���� (�p�[�c����, ��������, ���o�ɋ敪, ����) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInsert.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("��������", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInsert.Parameters.Add("���o�ɋ敪", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("����", Data.OleDb.OleDbType.Integer)

        'DB�X�V����
        Dim dtNow As DateTime = Now
        Try
            '�󒍃f�[�^�X�V
            Dim rowTenkai As dsWork.�p�[�c�W�JRow
            For Each rowTenkai In DsWork1.�p�[�c�W�J.Rows
                dbcJuchuUpdate.Parameters("�ڋq�R�[�h").Value = cmbKokyaku.SelectedValue
                dbcJuchuUpdate.Parameters("�󒍔ԍ�").Value = rowTenkai.�󒍔ԍ�
                dbcJuchuUpdate.Parameters("�݌Ɉ�������").Value = dtNow
                dbcJuchuUpdate.Parameters("�݌Ɉ��������ԍ�").Value = Me.txtShoriNo.Text
                dbcJuchuUpdate.ExecuteNonQuery()
            Next

            '�p�[�c�݌Ƀ}�X�^�X�V�A�݌ɕύX����ǉ�
            Dim zaikoOld, zaikoNew, hacchuuten As Integer
            Dim dtrZaiko As OleDb.OleDbDataReader
            Dim rowHikiate As dsWork.�p�[�c�݌Ɉ���Row
            For Each rowHikiate In dtPartsHikiete.Rows
                '�݌ɐ��擾
                dbcZaikoSelect.Parameters("�p�[�c����").Value = rowHikiate.�p�[�c����
                dtrZaiko = dbcZaikoSelect.ExecuteReader()
                dtrZaiko.Read()
                zaikoOld = dtrZaiko("���݌ɐ�")
                zaikoNew = zaikoOld - rowHikiate.������

                '�����_�x���`�F�b�N
                If Not dtrZaiko("�����_����") Is DBNull.Value Then
                    hacchuuten = dtrZaiko("�����_����")
                    If zaikoNew >= hacchuuten Then
                        dbcZaikoUpdate.Parameters("�����_�x����").Value = DBNull.Value
                    ElseIf dtrZaiko("�����_�x����") Is DBNull.Value Or (zaikoOld >= hacchuuten And zaikoNew < hacchuuten) Then
                        dbcZaikoUpdate.Parameters("�����_�x����").Value = dtNow
                    Else
                        dbcZaikoUpdate.Parameters("�����_�x����").Value = dtrZaiko("�����_�x����")
                    End If
                End If

                '���i�`�F�b�N
                If zaikoNew >= 0 Then
                    dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = DBNull.Value
                ElseIf dtrZaiko("���i�ɂȂ�����") Is DBNull.Value Or (zaikoOld >= 0 And zaikoNew < 0) Then
                    dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = dtNow
                Else
                    dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = dtrZaiko("���i�ɂȂ�����")
                End If

                dtrZaiko.Close()

                '�p�[�c�݌Ƀ}�X�^�X�V
                dbcZaikoUpdate.Parameters("�p�[�c����").Value = rowHikiate.�p�[�c����
                dbcZaikoUpdate.Parameters("���݌ɐ�").Value = zaikoNew
                dbcZaikoUpdate.ExecuteNonQuery()

                '�݌ɕύX����ǉ�
                dbcRirekiInsert.Parameters("�p�[�c����").Value = rowHikiate.�p�[�c����
                dbcRirekiInsert.Parameters("��������").Value = dtNow
                dbcRirekiInsert.Parameters("���o�ɋ敪").Value = "02"
                dbcRirekiInsert.Parameters("����").Value = -rowHikiate.������
                dbcRirekiInsert.ExecuteNonQuery()
            Next

            trans.Commit()
        Catch ex As Exception
            'DB�G���[
            trans.Rollback()
            MsgBox("�p�[�c�W�J�E�݌Ɉ����ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�������b�Z�[�W
        MsgBox("�p�[�c�W�J�E�݌Ɉ��������s���܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        Me.DialogResult = DialogResult.OK
    End Sub

    '*** ���̍݌Ɉ��������ԍ��擾����
    Private Function GetNextShoriNo() As Integer
        Dim dbCmd As New OleDb.OleDbCommand()

        dbCmd.Connection = Common.dbConnection
        dbCmd.CommandType = CommandType.Text
        dbCmd.CommandText = "SELECT MAX(�݌Ɉ��������ԍ�) FROM �󒍃f�[�^"

        Dim shoriNo As Object = dbCmd.ExecuteScalar()
        If shoriNo Is DBNull.Value Then
            Return 1
        Else
            Return shoriNo + 1
        End If
    End Function

    '*** �ڋq�h���b�v�_�E�����X�g�I���C�x���g
    Private Sub cmbKokyaku_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmbKokyaku.SelectedIndexChanged
        If cmbKokyaku.SelectedIndex <> 0 Then
            '�������f�[�^���擾SQL
            Dim dbCmd As New OleDb.OleDbCommand()
            dbCmd.Connection = Common.dbConnection
            dbCmd.CommandType = CommandType.Text
            dbCmd.CommandText = "SELECT COUNT(*) FROM �󒍃f�[�^ WHERE �ڋq�R�[�h=? AND �݌Ɉ��������ԍ�=0"
            dbCmd.Parameters.Add("�ڋq�R�[�h", Me.cmbKokyaku.SelectedValue)

            '�������f�[�^���\��
            Me.txtMishori.Text = dbCmd.ExecuteScalar()
        Else
            Me.txtMishori.Text = ""
        End If
    End Sub
End Class
