namespace vaja14
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.menuStrip1 = new System.Windows.Forms.MenuStrip();
            this.datotekaToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.izhodToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.izdelkiToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.vsiIzdelkiToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.dodajIzdelekToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.spremeniToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.izbrišiToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.checkBox1 = new System.Windows.Forms.CheckBox();
            this.numericUpDown1 = new System.Windows.Forms.NumericUpDown();
            this.button1 = new System.Windows.Forms.Button();
            this.label2 = new System.Windows.Forms.Label();
            this.textBox1 = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.Košarica = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.kosarica = new System.Windows.Forms.ListBox();
            this.label5 = new System.Windows.Forms.Label();
            this.button2 = new System.Windows.Forms.Button();
            this.button3 = new System.Windows.Forms.Button();
            this.printDocument1 = new System.Drawing.Printing.PrintDocument();
            this.printDialog1 = new System.Windows.Forms.PrintDialog();
            this.label6 = new System.Windows.Forms.Label();
            this.textBox2 = new System.Windows.Forms.TextBox();
            this.label7 = new System.Windows.Forms.Label();
            this.label8 = new System.Windows.Forms.Label();
            this.funt_check = new System.Windows.Forms.RadioButton();
            this.dolar_check = new System.Windows.Forms.RadioButton();
            this.evro_check = new System.Windows.Forms.RadioButton();
            this.label9 = new System.Windows.Forms.Label();
            this.menuStrip1.SuspendLayout();
            this.groupBox1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).BeginInit();
            this.SuspendLayout();
            // 
            // menuStrip1
            // 
            this.menuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.datotekaToolStripMenuItem,
            this.izdelkiToolStripMenuItem});
            this.menuStrip1.Location = new System.Drawing.Point(0, 0);
            this.menuStrip1.Name = "menuStrip1";
            this.menuStrip1.Size = new System.Drawing.Size(905, 24);
            this.menuStrip1.TabIndex = 2;
            this.menuStrip1.Text = "menuStrip1";
            // 
            // datotekaToolStripMenuItem
            // 
            this.datotekaToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.izhodToolStripMenuItem});
            this.datotekaToolStripMenuItem.Name = "datotekaToolStripMenuItem";
            this.datotekaToolStripMenuItem.Size = new System.Drawing.Size(66, 20);
            this.datotekaToolStripMenuItem.Text = "Datoteka";
            // 
            // izhodToolStripMenuItem
            // 
            this.izhodToolStripMenuItem.Name = "izhodToolStripMenuItem";
            this.izhodToolStripMenuItem.Size = new System.Drawing.Size(103, 22);
            this.izhodToolStripMenuItem.Text = "Izhod";
            this.izhodToolStripMenuItem.Click += new System.EventHandler(this.izhodToolStripMenuItem_Click);
            // 
            // izdelkiToolStripMenuItem
            // 
            this.izdelkiToolStripMenuItem.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.vsiIzdelkiToolStripMenuItem,
            this.dodajIzdelekToolStripMenuItem,
            this.spremeniToolStripMenuItem,
            this.izbrišiToolStripMenuItem});
            this.izdelkiToolStripMenuItem.Name = "izdelkiToolStripMenuItem";
            this.izdelkiToolStripMenuItem.Size = new System.Drawing.Size(52, 20);
            this.izdelkiToolStripMenuItem.Text = "Izdelki";
            // 
            // vsiIzdelkiToolStripMenuItem
            // 
            this.vsiIzdelkiToolStripMenuItem.Name = "vsiIzdelkiToolStripMenuItem";
            this.vsiIzdelkiToolStripMenuItem.Size = new System.Drawing.Size(144, 22);
            this.vsiIzdelkiToolStripMenuItem.Text = "Vsi izdelki";
            this.vsiIzdelkiToolStripMenuItem.Click += new System.EventHandler(this.vsiIzdelkiToolStripMenuItem_Click);
            // 
            // dodajIzdelekToolStripMenuItem
            // 
            this.dodajIzdelekToolStripMenuItem.Name = "dodajIzdelekToolStripMenuItem";
            this.dodajIzdelekToolStripMenuItem.Size = new System.Drawing.Size(144, 22);
            this.dodajIzdelekToolStripMenuItem.Text = "Dodaj izdelek";
            this.dodajIzdelekToolStripMenuItem.Click += new System.EventHandler(this.dodajIzdelekToolStripMenuItem_Click);
            // 
            // spremeniToolStripMenuItem
            // 
            this.spremeniToolStripMenuItem.Name = "spremeniToolStripMenuItem";
            this.spremeniToolStripMenuItem.Size = new System.Drawing.Size(144, 22);
            this.spremeniToolStripMenuItem.Text = "Spremeni";
            this.spremeniToolStripMenuItem.Click += new System.EventHandler(this.spremeniToolStripMenuItem_Click);
            // 
            // izbrišiToolStripMenuItem
            // 
            this.izbrišiToolStripMenuItem.Name = "izbrišiToolStripMenuItem";
            this.izbrišiToolStripMenuItem.Size = new System.Drawing.Size(144, 22);
            this.izbrišiToolStripMenuItem.Text = "Izbriši";
            this.izbrišiToolStripMenuItem.Click += new System.EventHandler(this.izbrišiToolStripMenuItem_Click);
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.checkBox1);
            this.groupBox1.Controls.Add(this.numericUpDown1);
            this.groupBox1.Controls.Add(this.button1);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.textBox1);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Location = new System.Drawing.Point(28, 79);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(210, 218);
            this.groupBox1.TabIndex = 0;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Vstavi v košarico";
            // 
            // checkBox1
            // 
            this.checkBox1.AutoSize = true;
            this.checkBox1.Location = new System.Drawing.Point(100, 125);
            this.checkBox1.Name = "checkBox1";
            this.checkBox1.Size = new System.Drawing.Size(15, 14);
            this.checkBox1.TabIndex = 7;
            this.checkBox1.UseVisualStyleBackColor = true;
            this.checkBox1.CheckedChanged += new System.EventHandler(this.checkBox1_CheckedChanged);
            // 
            // numericUpDown1
            // 
            this.numericUpDown1.Enabled = false;
            this.numericUpDown1.Location = new System.Drawing.Point(33, 124);
            this.numericUpDown1.Name = "numericUpDown1";
            this.numericUpDown1.Size = new System.Drawing.Size(61, 20);
            this.numericUpDown1.TabIndex = 6;
            this.numericUpDown1.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            this.numericUpDown1.Value = new decimal(new int[] {
            1,
            0,
            0,
            0});
            this.numericUpDown1.Click += new System.EventHandler(this.numericUpDown1_Click);
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(58, 164);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(75, 23);
            this.button1.TabIndex = 4;
            this.button1.Text = "Vstavi";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(30, 108);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(44, 13);
            this.label2.TabIndex = 5;
            this.label2.Text = "Količina";
            // 
            // textBox1
            // 
            this.textBox1.Location = new System.Drawing.Point(33, 68);
            this.textBox1.Name = "textBox1";
            this.textBox1.Size = new System.Drawing.Size(100, 20);
            this.textBox1.TabIndex = 1;
            this.textBox1.KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.textBox1_KeyPress);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(30, 52);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(64, 13);
            this.label1.TabIndex = 4;
            this.label1.Text = "Šifra izdelka";
            // 
            // Košarica
            // 
            this.Košarica.AutoSize = true;
            this.Košarica.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Košarica.Location = new System.Drawing.Point(287, 52);
            this.Košarica.Name = "Košarica";
            this.Košarica.Size = new System.Drawing.Size(90, 24);
            this.Košarica.TabIndex = 3;
            this.Košarica.Text = "Košarica";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label3.Location = new System.Drawing.Point(383, 344);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(21, 24);
            this.label3.TabIndex = 5;
            this.label3.Text = "0";
            this.label3.SizeChanged += new System.EventHandler(this.label3_SizeChanged);
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label4.Location = new System.Drawing.Point(440, 345);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(52, 24);
            this.label4.TabIndex = 6;
            this.label4.Text = "EUR";
            // 
            // kosarica
            // 
            this.kosarica.Font = new System.Drawing.Font("Courier New", 15.75F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.kosarica.FormattingEnabled = true;
            this.kosarica.ItemHeight = 23;
            this.kosarica.Location = new System.Drawing.Point(291, 93);
            this.kosarica.Name = "kosarica";
            this.kosarica.SelectionMode = System.Windows.Forms.SelectionMode.MultiExtended;
            this.kosarica.Size = new System.Drawing.Size(382, 188);
            this.kosarica.TabIndex = 7;
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label5.Location = new System.Drawing.Point(286, 343);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(91, 25);
            this.label5.TabIndex = 8;
            this.label5.Text = "Skupaj:";
            // 
            // button2
            // 
            this.button2.Location = new System.Drawing.Point(471, 393);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(75, 23);
            this.button2.TabIndex = 9;
            this.button2.Text = "Račun";
            this.button2.UseVisualStyleBackColor = true;
            this.button2.Click += new System.EventHandler(this.button2_Click);
            // 
            // button3
            // 
            this.button3.Location = new System.Drawing.Point(598, 287);
            this.button3.Name = "button3";
            this.button3.Size = new System.Drawing.Size(75, 23);
            this.button3.TabIndex = 10;
            this.button3.Text = "Odstrani";
            this.button3.UseVisualStyleBackColor = true;
            this.button3.Click += new System.EventHandler(this.button3_Click);
            // 
            // printDialog1
            // 
            this.printDialog1.UseEXDialog = true;
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label6.Location = new System.Drawing.Point(286, 393);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(91, 24);
            this.label6.TabIndex = 11;
            this.label6.Text = "Vplačilo:";
            // 
            // textBox2
            // 
            this.textBox2.Location = new System.Drawing.Point(377, 396);
            this.textBox2.Name = "textBox2";
            this.textBox2.Size = new System.Drawing.Size(69, 20);
            this.textBox2.TabIndex = 12;
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label7.Location = new System.Drawing.Point(714, 445);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(179, 20);
            this.label7.TabIndex = 13;
            this.label7.Text = "© 2015 Mitja Celec, 4R2";
            // 
            // label8
            // 
            this.label8.AutoSize = true;
            this.label8.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label8.Location = new System.Drawing.Point(716, 93);
            this.label8.Name = "label8";
            this.label8.Size = new System.Drawing.Size(74, 24);
            this.label8.TabIndex = 14;
            this.label8.Text = "Valuta:";
            // 
            // funt_check
            // 
            this.funt_check.AutoSize = true;
            this.funt_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.funt_check.Location = new System.Drawing.Point(720, 191);
            this.funt_check.Name = "funt_check";
            this.funt_check.Size = new System.Drawing.Size(73, 24);
            this.funt_check.TabIndex = 17;
            this.funt_check.Text = "Funt £";
            this.funt_check.UseVisualStyleBackColor = true;
            this.funt_check.CheckedChanged += new System.EventHandler(this.funt_check_CheckedChanged);
            this.funt_check.Click += new System.EventHandler(this.funt_check_Click);
            // 
            // dolar_check
            // 
            this.dolar_check.AutoSize = true;
            this.dolar_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.dolar_check.Location = new System.Drawing.Point(720, 161);
            this.dolar_check.Name = "dolar_check";
            this.dolar_check.Size = new System.Drawing.Size(78, 24);
            this.dolar_check.TabIndex = 16;
            this.dolar_check.Text = "Dolar $";
            this.dolar_check.UseVisualStyleBackColor = true;
            this.dolar_check.CheckedChanged += new System.EventHandler(this.dolar_check_CheckedChanged);
            this.dolar_check.Click += new System.EventHandler(this.dolar_check_Click);
            // 
            // evro_check
            // 
            this.evro_check.AutoSize = true;
            this.evro_check.Checked = true;
            this.evro_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.evro_check.Location = new System.Drawing.Point(720, 131);
            this.evro_check.Name = "evro_check";
            this.evro_check.Size = new System.Drawing.Size(72, 24);
            this.evro_check.TabIndex = 15;
            this.evro_check.TabStop = true;
            this.evro_check.Text = "Evro €";
            this.evro_check.UseVisualStyleBackColor = true;
            this.evro_check.CheckedChanged += new System.EventHandler(this.evro_check_CheckedChanged);
            this.evro_check.Click += new System.EventHandler(this.evro_check_Click);
            // 
            // label9
            // 
            this.label9.AutoSize = true;
            this.label9.Location = new System.Drawing.Point(288, 284);
            this.label9.Name = "label9";
            this.label9.Size = new System.Drawing.Size(100, 13);
            this.label9.TabIndex = 18;
            this.label9.Text = "Vključen DDV: 22%";
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(905, 474);
            this.Controls.Add(this.label9);
            this.Controls.Add(this.funt_check);
            this.Controls.Add(this.dolar_check);
            this.Controls.Add(this.evro_check);
            this.Controls.Add(this.label8);
            this.Controls.Add(this.label7);
            this.Controls.Add(this.textBox2);
            this.Controls.Add(this.label6);
            this.Controls.Add(this.button3);
            this.Controls.Add(this.button2);
            this.Controls.Add(this.label5);
            this.Controls.Add(this.kosarica);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.Košarica);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.menuStrip1);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MainMenuStrip = this.menuStrip1;
            this.Name = "Form1";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Blagajna";
            this.menuStrip1.ResumeLayout(false);
            this.menuStrip1.PerformLayout();
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.MenuStrip menuStrip1;
        private System.Windows.Forms.ToolStripMenuItem datotekaToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem izhodToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem izdelkiToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem vsiIzdelkiToolStripMenuItem;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox textBox1;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label Košarica;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.DataGridViewTextBoxColumn idDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn sifraDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn imeIzdelkaDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn zalogaDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn cenaDataGridViewTextBoxColumn;
        private System.Windows.Forms.ToolStripMenuItem dodajIzdelekToolStripMenuItem;
        private System.Windows.Forms.DataGridViewTextBoxColumn dataGridViewTextBoxColumn1;
        private System.Windows.Forms.DataGridViewTextBoxColumn dataGridViewTextBoxColumn2;
        private System.Windows.Forms.DataGridViewTextBoxColumn dataGridViewTextBoxColumn3;
        private System.Windows.Forms.DataGridViewTextBoxColumn dataGridViewTextBoxColumn4;
        private System.Windows.Forms.ToolStripMenuItem spremeniToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem izbrišiToolStripMenuItem;
        private System.Windows.Forms.ListBox kosarica;
        private System.Windows.Forms.NumericUpDown numericUpDown1;
        private System.Windows.Forms.CheckBox checkBox1;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Button button2;
        private System.Windows.Forms.Button button3;
        private System.Drawing.Printing.PrintDocument printDocument1;
        private System.Windows.Forms.PrintDialog printDialog1;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.TextBox textBox2;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.Label label8;
        private System.Windows.Forms.RadioButton funt_check;
        private System.Windows.Forms.RadioButton dolar_check;
        private System.Windows.Forms.RadioButton evro_check;
        private System.Windows.Forms.Label label9;
    }
}

