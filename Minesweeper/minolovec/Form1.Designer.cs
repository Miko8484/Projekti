namespace minolovec
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
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.server_button = new System.Windows.Forms.Button();
            this.clientPovezi = new System.Windows.Forms.Button();
            this.stIgralcev = new System.Windows.Forms.TextBox();
            this.clientName = new System.Windows.Forms.TextBox();
            this.sirinaGrida = new System.Windows.Forms.TextBox();
            this.visinaGrida = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.label5 = new System.Windows.Forms.Label();
            this.label6 = new System.Windows.Forms.Label();
            this.serverName = new System.Windows.Forms.TextBox();
            this.label7 = new System.Windows.Forms.Label();
            this.tocke_label = new System.Windows.Forms.Label();
            this.label8 = new System.Windows.Forms.Label();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.serverPort_textBox = new System.Windows.Forms.TextBox();
            this.label10 = new System.Windows.Forms.Label();
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.label11 = new System.Windows.Forms.Label();
            this.clientPort_textBox = new System.Windows.Forms.TextBox();
            this.ipaddres = new System.Windows.Forms.TextBox();
            this.label9 = new System.Windows.Forms.Label();
            this.toolTip1 = new System.Windows.Forms.ToolTip(this.components);
            this.lestvica_listView = new System.Windows.Forms.ListView();
            this.ime = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.tocke = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.krog = new System.Windows.Forms.Label();
            this.groupBox1.SuspendLayout();
            this.groupBox2.SuspendLayout();
            this.SuspendLayout();
            // 
            // server_button
            // 
            this.server_button.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.server_button.Location = new System.Drawing.Point(3, 259);
            this.server_button.Name = "server_button";
            this.server_button.Size = new System.Drawing.Size(175, 38);
            this.server_button.TabIndex = 5;
            this.server_button.Text = "Postavi server";
            this.server_button.UseVisualStyleBackColor = true;
            this.server_button.Click += new System.EventHandler(this.server_button_Click);
            // 
            // clientPovezi
            // 
            this.clientPovezi.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.clientPovezi.Location = new System.Drawing.Point(6, 205);
            this.clientPovezi.Name = "clientPovezi";
            this.clientPovezi.Size = new System.Drawing.Size(175, 37);
            this.clientPovezi.TabIndex = 7;
            this.clientPovezi.Text = "Povezi";
            this.clientPovezi.UseVisualStyleBackColor = true;
            this.clientPovezi.Click += new System.EventHandler(this.client_button);
            // 
            // stIgralcev
            // 
            this.stIgralcev.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.stIgralcev.Location = new System.Drawing.Point(130, 105);
            this.stIgralcev.Name = "stIgralcev";
            this.stIgralcev.Size = new System.Drawing.Size(38, 28);
            this.stIgralcev.TabIndex = 3;
            this.stIgralcev.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            this.stIgralcev.Enter += new System.EventHandler(this.stIgralcev_Enter);
            this.stIgralcev.Leave += new System.EventHandler(this.stIgralcev_Leave);
            // 
            // clientName
            // 
            this.clientName.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.clientName.Location = new System.Drawing.Point(10, 171);
            this.clientName.Name = "clientName";
            this.clientName.Size = new System.Drawing.Size(158, 28);
            this.clientName.TabIndex = 8;
            // 
            // sirinaGrida
            // 
            this.sirinaGrida.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.sirinaGrida.Location = new System.Drawing.Point(130, 36);
            this.sirinaGrida.Name = "sirinaGrida";
            this.sirinaGrida.Size = new System.Drawing.Size(38, 28);
            this.sirinaGrida.TabIndex = 1;
            this.sirinaGrida.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            this.sirinaGrida.Enter += new System.EventHandler(this.sirinaGrida_Enter);
            this.sirinaGrida.Leave += new System.EventHandler(this.sirinaGrida_Leave);
            // 
            // visinaGrida
            // 
            this.visinaGrida.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.visinaGrida.Location = new System.Drawing.Point(130, 73);
            this.visinaGrida.Name = "visinaGrida";
            this.visinaGrida.Size = new System.Drawing.Size(38, 28);
            this.visinaGrida.TabIndex = 2;
            this.visinaGrida.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            this.visinaGrida.Enter += new System.EventHandler(this.visinaGrida_Enter);
            this.visinaGrida.Leave += new System.EventHandler(this.visinaGrida_Leave);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label1.Location = new System.Drawing.Point(6, 40);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(120, 24);
            this.label1.TabIndex = 6;
            this.label1.Text = "Širina polja:";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label2.Location = new System.Drawing.Point(6, 73);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(124, 24);
            this.label2.TabIndex = 7;
            this.label2.Text = "Višina polja:";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label3.Location = new System.Drawing.Point(6, 109);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(113, 24);
            this.label3.TabIndex = 8;
            this.label3.Text = "Št igralcev:";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label4.Location = new System.Drawing.Point(6, 144);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(50, 24);
            this.label4.TabIndex = 9;
            this.label4.Text = "Ime:";
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(27, 227);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(153, 17);
            this.label5.TabIndex = 10;
            this.label5.Text = "-----------------------------";
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label6.Location = new System.Drawing.Point(6, 179);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(50, 24);
            this.label6.TabIndex = 11;
            this.label6.Text = "Ime:";
            // 
            // serverName
            // 
            this.serverName.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.serverName.Location = new System.Drawing.Point(10, 215);
            this.serverName.Name = "serverName";
            this.serverName.Size = new System.Drawing.Size(158, 28);
            this.serverName.TabIndex = 0;
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label7.Location = new System.Drawing.Point(7, 607);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(131, 25);
            this.label7.TabIndex = 13;
            this.label7.Text = "Število točk:";
            // 
            // tocke_label
            // 
            this.tocke_label.AutoSize = true;
            this.tocke_label.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.tocke_label.Location = new System.Drawing.Point(144, 607);
            this.tocke_label.Name = "tocke_label";
            this.tocke_label.Size = new System.Drawing.Size(23, 25);
            this.tocke_label.TabIndex = 14;
            this.tocke_label.Text = "0";
            // 
            // label8
            // 
            this.label8.AutoSize = true;
            this.label8.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label8.Location = new System.Drawing.Point(10, 646);
            this.label8.Name = "label8";
            this.label8.Size = new System.Drawing.Size(102, 25);
            this.label8.TabIndex = 15;
            this.label8.Text = "Rezultati:";
            this.label8.Visible = false;
            // 
            // groupBox1
            // 
            this.groupBox1.BackColor = System.Drawing.Color.Gainsboro;
            this.groupBox1.Controls.Add(this.serverPort_textBox);
            this.groupBox1.Controls.Add(this.label10);
            this.groupBox1.Controls.Add(this.server_button);
            this.groupBox1.Controls.Add(this.stIgralcev);
            this.groupBox1.Controls.Add(this.sirinaGrida);
            this.groupBox1.Controls.Add(this.visinaGrida);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Controls.Add(this.serverName);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.label6);
            this.groupBox1.Controls.Add(this.label3);
            this.groupBox1.Font = new System.Drawing.Font("Microsoft Sans Serif", 16.2F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.groupBox1.Location = new System.Drawing.Point(12, 12);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(182, 303);
            this.groupBox1.TabIndex = 0;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Server";
            // 
            // serverPort_textBox
            // 
            this.serverPort_textBox.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.serverPort_textBox.Location = new System.Drawing.Point(117, 142);
            this.serverPort_textBox.Name = "serverPort_textBox";
            this.serverPort_textBox.Size = new System.Drawing.Size(51, 28);
            this.serverPort_textBox.TabIndex = 5;
            this.serverPort_textBox.Text = "8888";
            this.serverPort_textBox.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            // 
            // label10
            // 
            this.label10.AutoSize = true;
            this.label10.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label10.Location = new System.Drawing.Point(6, 145);
            this.label10.Name = "label10";
            this.label10.Size = new System.Drawing.Size(53, 24);
            this.label10.TabIndex = 12;
            this.label10.Text = "Port:";
            // 
            // groupBox2
            // 
            this.groupBox2.BackColor = System.Drawing.Color.Gainsboro;
            this.groupBox2.Controls.Add(this.label11);
            this.groupBox2.Controls.Add(this.clientPort_textBox);
            this.groupBox2.Controls.Add(this.ipaddres);
            this.groupBox2.Controls.Add(this.label9);
            this.groupBox2.Controls.Add(this.clientPovezi);
            this.groupBox2.Controls.Add(this.clientName);
            this.groupBox2.Controls.Add(this.label4);
            this.groupBox2.Font = new System.Drawing.Font("Microsoft Sans Serif", 16.2F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.groupBox2.Location = new System.Drawing.Point(12, 335);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(182, 248);
            this.groupBox2.TabIndex = 18;
            this.groupBox2.TabStop = false;
            this.groupBox2.Text = "Client";
            // 
            // label11
            // 
            this.label11.AutoSize = true;
            this.label11.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label11.Location = new System.Drawing.Point(6, 109);
            this.label11.Name = "label11";
            this.label11.Size = new System.Drawing.Size(53, 24);
            this.label11.TabIndex = 13;
            this.label11.Text = "Port:";
            // 
            // clientPort_textBox
            // 
            this.clientPort_textBox.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.clientPort_textBox.Location = new System.Drawing.Point(117, 105);
            this.clientPort_textBox.Name = "clientPort_textBox";
            this.clientPort_textBox.Size = new System.Drawing.Size(51, 28);
            this.clientPort_textBox.TabIndex = 7;
            this.clientPort_textBox.Text = "8888";
            this.clientPort_textBox.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            // 
            // ipaddres
            // 
            this.ipaddres.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.ipaddres.Location = new System.Drawing.Point(10, 74);
            this.ipaddres.Name = "ipaddres";
            this.ipaddres.Size = new System.Drawing.Size(158, 28);
            this.ipaddres.TabIndex = 6;
            this.ipaddres.Text = "127.0.0.1";
            // 
            // label9
            // 
            this.label9.AutoSize = true;
            this.label9.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.label9.Location = new System.Drawing.Point(6, 47);
            this.label9.Name = "label9";
            this.label9.Size = new System.Drawing.Size(100, 24);
            this.label9.TabIndex = 10;
            this.label9.Text = "IP naslov:";
            // 
            // lestvica_listView
            // 
            this.lestvica_listView.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.ime,
            this.tocke});
            this.lestvica_listView.Location = new System.Drawing.Point(15, 674);
            this.lestvica_listView.Name = "lestvica_listView";
            this.lestvica_listView.Size = new System.Drawing.Size(182, 144);
            this.lestvica_listView.TabIndex = 19;
            this.lestvica_listView.UseCompatibleStateImageBehavior = false;
            this.lestvica_listView.View = System.Windows.Forms.View.Details;
            this.lestvica_listView.Visible = false;
            // 
            // ime
            // 
            this.ime.Text = "Igralec";
            this.ime.Width = 86;
            // 
            // tocke
            // 
            this.tocke.Text = "Točke";
            // 
            // krog
            // 
            this.krog.AutoSize = true;
            this.krog.Font = new System.Drawing.Font("Microsoft Sans Serif", 10.8F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(238)));
            this.krog.Location = new System.Drawing.Point(270, 12);
            this.krog.Name = "krog";
            this.krog.Size = new System.Drawing.Size(0, 24);
            this.krog.TabIndex = 20;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(800, 729);
            this.Controls.Add(this.krog);
            this.Controls.Add(this.lestvica_listView);
            this.Controls.Add(this.groupBox2);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.label8);
            this.Controls.Add(this.tocke_label);
            this.Controls.Add(this.label7);
            this.Controls.Add(this.label5);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "Form1";
            this.Text = "Minolovec";
            this.WindowState = System.Windows.Forms.FormWindowState.Maximized;
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.Form1_FormClosing);
            this.FormClosed += new System.Windows.Forms.FormClosedEventHandler(this.Form1_FormClosed);
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.groupBox2.ResumeLayout(false);
            this.groupBox2.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button server_button;
        private System.Windows.Forms.Button clientPovezi;
        private System.Windows.Forms.TextBox stIgralcev;
        private System.Windows.Forms.TextBox clientName;
        private System.Windows.Forms.TextBox sirinaGrida;
        private System.Windows.Forms.TextBox visinaGrida;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.TextBox serverName;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.Label tocke_label;
        private System.Windows.Forms.Label label8;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.GroupBox groupBox2;
        private System.Windows.Forms.ToolTip toolTip1;
        private System.Windows.Forms.TextBox ipaddres;
        private System.Windows.Forms.Label label9;
        private System.Windows.Forms.ListView lestvica_listView;
        private System.Windows.Forms.ColumnHeader ime;
        private System.Windows.Forms.ColumnHeader tocke;
        private System.Windows.Forms.TextBox serverPort_textBox;
        private System.Windows.Forms.Label label10;
        private System.Windows.Forms.Label label11;
        private System.Windows.Forms.TextBox clientPort_textBox;
        private System.Windows.Forms.Label krog;
    }
}

