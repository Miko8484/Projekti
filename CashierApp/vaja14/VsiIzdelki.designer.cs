namespace vaja14
{
    partial class VsiIzdelki
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(VsiIzdelki));
            this.dataGridView1 = new System.Windows.Forms.DataGridView();
            this.evro_check = new System.Windows.Forms.RadioButton();
            this.dolar_check = new System.Windows.Forms.RadioButton();
            this.label1 = new System.Windows.Forms.Label();
            this.funt_check = new System.Windows.Forms.RadioButton();
            this.button1 = new System.Windows.Forms.Button();
            this.button2 = new System.Windows.Forms.Button();
            this.comboBox1 = new System.Windows.Forms.ComboBox();
            this.label2 = new System.Windows.Forms.Label();
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView1)).BeginInit();
            this.SuspendLayout();
            // 
            // dataGridView1
            // 
            this.dataGridView1.AllowUserToAddRows = false;
            this.dataGridView1.AllowUserToDeleteRows = false;
            this.dataGridView1.AutoSizeColumnsMode = System.Windows.Forms.DataGridViewAutoSizeColumnsMode.Fill;
            this.dataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dataGridView1.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.dataGridView1.Location = new System.Drawing.Point(0, 56);
            this.dataGridView1.Name = "dataGridView1";
            this.dataGridView1.Size = new System.Drawing.Size(713, 386);
            this.dataGridView1.TabIndex = 0;
            // 
            // evro_check
            // 
            this.evro_check.AutoSize = true;
            this.evro_check.BackColor = System.Drawing.Color.LightSalmon;
            this.evro_check.Checked = true;
            this.evro_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.evro_check.Location = new System.Drawing.Point(232, 15);
            this.evro_check.Name = "evro_check";
            this.evro_check.Size = new System.Drawing.Size(72, 24);
            this.evro_check.TabIndex = 1;
            this.evro_check.TabStop = true;
            this.evro_check.Text = "Evro €";
            this.evro_check.UseVisualStyleBackColor = false;
            this.evro_check.Click += new System.EventHandler(this.evro_check_Click);
            // 
            // dolar_check
            // 
            this.dolar_check.AutoSize = true;
            this.dolar_check.BackColor = System.Drawing.Color.LightGreen;
            this.dolar_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.dolar_check.Location = new System.Drawing.Point(310, 15);
            this.dolar_check.Name = "dolar_check";
            this.dolar_check.Size = new System.Drawing.Size(78, 24);
            this.dolar_check.TabIndex = 2;
            this.dolar_check.Text = "Dolar $";
            this.dolar_check.UseVisualStyleBackColor = false;
            this.dolar_check.Click += new System.EventHandler(this.dolar_check_Click);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label1.Location = new System.Drawing.Point(160, 15);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(67, 24);
            this.label1.TabIndex = 3;
            this.label1.Text = "Valuta:";
            // 
            // funt_check
            // 
            this.funt_check.AutoSize = true;
            this.funt_check.BackColor = System.Drawing.Color.SkyBlue;
            this.funt_check.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.funt_check.Location = new System.Drawing.Point(394, 15);
            this.funt_check.Name = "funt_check";
            this.funt_check.Size = new System.Drawing.Size(73, 24);
            this.funt_check.TabIndex = 4;
            this.funt_check.Text = "Funt £";
            this.funt_check.UseVisualStyleBackColor = false;
            this.funt_check.Click += new System.EventHandler(this.funt_check_Click);
            // 
            // button1
            // 
            this.button1.Image = global::vaja14.Properties.Resources.refresh;
            this.button1.Location = new System.Drawing.Point(81, 8);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(47, 42);
            this.button1.TabIndex = 7;
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // button2
            // 
            this.button2.Image = global::vaja14.Properties.Resources.rsz_back;
            this.button2.Location = new System.Drawing.Point(21, 8);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(45, 42);
            this.button2.TabIndex = 6;
            this.button2.UseVisualStyleBackColor = true;
            this.button2.Click += new System.EventHandler(this.button2_Click);
            // 
            // comboBox1
            // 
            this.comboBox1.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.comboBox1.FormattingEnabled = true;
            this.comboBox1.Location = new System.Drawing.Point(580, 18);
            this.comboBox1.Name = "comboBox1";
            this.comboBox1.Size = new System.Drawing.Size(121, 21);
            this.comboBox1.TabIndex = 8;
            this.comboBox1.SelectedIndexChanged += new System.EventHandler(this.comboBox1_SelectedIndexChanged);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Font = new System.Drawing.Font("Microsoft Sans Serif", 11.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label2.Location = new System.Drawing.Point(496, 18);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(78, 18);
            this.label2.TabIndex = 9;
            this.label2.Text = "Kategorije:";
            // 
            // VsiIzdelki
            // 
            this.ClientSize = new System.Drawing.Size(713, 442);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.comboBox1);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.button2);
            this.Controls.Add(this.funt_check);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.dolar_check);
            this.Controls.Add(this.evro_check);
            this.Controls.Add(this.dataGridView1);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "VsiIzdelki";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Vsi izdelki";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.VsiIzdelki_FormClosing);
            this.Load += new System.EventHandler(this.VsiIzdelki_Load_1);
            ((System.ComponentModel.ISupportInitialize)(this.dataGridView1)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.DataGridView dataGridView1;
        private System.Windows.Forms.DataGridViewTextBoxColumn sifraDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn imeizdelkaDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn zalogaDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn cenaDataGridViewTextBoxColumn;
        private System.Windows.Forms.RadioButton evro_check;
        private System.Windows.Forms.RadioButton dolar_check;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.RadioButton funt_check;
        private System.Windows.Forms.Button button2;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.ComboBox comboBox1;
        private System.Windows.Forms.Label label2;
    }
}