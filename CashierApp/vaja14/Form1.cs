using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Data.SqlClient;
using System.Drawing.Printing;
using MySql.Data.MySqlClient;
using System.Globalization;
using Microsoft.Win32;
using System.IO;

namespace vaja14
{
    public partial class Form1 : Form
    {
        RegistryKey reg = Registry.CurrentUser.OpenSubKey(@"Software\Microsoft\Windows\CurrentVersion\Run", true);
        VsiIzdelki vsiizdelki;
        Dodaj dodaj;
        Izbrisi izbrisi;
        Spremeni spremeni;
        public Form1()
        {
            reg.SetValue("Blagajna", Application.ExecutablePath.ToString());
            InitializeComponent();
            vsiizdelki = new VsiIzdelki(this);
            dodaj = new Dodaj(this);
            izbrisi = new Izbrisi(this);
            spremeni = new Spremeni(this);

            this.ActiveControl = textBox1;
        }

        private void vsiIzdelkiToolStripMenuItem_Click(object sender, EventArgs e)
        {
            vsiizdelki.Show();
            vsiizdelki.osvezi();
        }

        private void izhodToolStripMenuItem_Click(object sender, EventArgs e)
        {
            dodaj.Close(); izbrisi.Close(); spremeni.Close(); vsiizdelki.Close();
            Environment.Exit(1);
        }

        List<decimal> list_cena = new List<decimal>();
        List<int> list_kolicina = new List<int>();
        List<string> list_izdelek = new List<string>();
        List<decimal> list_znesek = new List<decimal>();

        string sifra = "";
        public decimal skupaj = 0;
        decimal cena0=0;
        decimal[] cene;
        public string znak;

        bool evro = true;
        bool funt = false;
        bool dolar = false;

        
        string[] izdelek;
        int[] zaloga;
        decimal[] cena;
        decimal[] zneski;
        int velikostPolja = 0;

        SqlConnection cn = new SqlConnection(global::vaja14.Properties.Settings.Default.IzdelkiConnectionString);
        private void button1_Click(object sender, EventArgs e)
        {
            SqlDataReader reader0 = null;
            SqlDataReader reader = null;
            SqlDataReader reader2 = null;
            if (textBox1.Text != "")
            {
                cn.Open();
                SqlCommand cmd0 = new SqlCommand("SELECT sifra FROM Izdelki WHERE sifra=@sifra", cn);
                cmd0.Parameters.AddWithValue("@sifra", textBox1.Text);
                reader0 = cmd0.ExecuteReader();

                if (reader0.Read())
                {
                    try
                    {
                        reader0.Close();
                        SqlCommand cmd = new SqlCommand("SELECT sifra FROM Izdelki WHERE sifra=@sifra AND zaloga>@kolicina", cn);
                        cmd.Parameters.AddWithValue("@sifra", textBox1.Text);
                        cmd.Parameters.AddWithValue("@kolicina", numericUpDown1.Value);
                        reader = cmd.ExecuteReader();

                        if (reader.Read())
                        {
                            reader.Close();

                            sifra = textBox1.Text;

                            SqlCommand cmd2 = new SqlCommand("SELECT sifra,ime_izdelka,zaloga,cena FROM Izdelki WHERE sifra=@sifra", cn);
                            cmd2.Parameters.AddWithValue("@sifra", sifra);
                            reader2 = cmd2.ExecuteReader();

                            if (reader2.Read())
                            {
                                cena0 = Decimal.Parse(reader2[3].ToString());

                                if (numericUpDown1.Value == 1)
                                    skupaj = cena0;
                                else
                                    skupaj = Decimal.Parse(numericUpDown1.Value.ToString()) * cena0;

                                decimal z_davek = Math.Round(skupaj * 0.22m, 2) + skupaj;
                                kosarica.Items.Add(reader2[1].ToString().PadRight(17) + numericUpDown1.Value.ToString() + "x".PadRight(5) + z_davek.ToString() + "€");

                                list_izdelek.Add(reader2[1].ToString());
                                list_kolicina.Add(Int32.Parse(numericUpDown1.Value.ToString()));
                                list_cena.Add(z_davek);
                                cene = list_cena.ToArray();

                                izdelek = list_izdelek.ToArray();
                                zaloga = list_kolicina.ToArray();
                                cena = list_cena.ToArray();

                                velikostPolja++;
                            }
                            label3.Text = (Decimal.Parse(label3.Text) + cena[velikostPolja - 1]).ToString();
                            reader2.Close();
                            textBox1.Clear();
                            numericUpDown1.Value = 1;
                            textBox1.Focus();
                        }
                        else
                        {
                            MessageBox.Show("Premajhna zaloga", "Napaka pri vstavlanju", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                        }

                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message);
                    }
                    finally
                    {
                        cn.Close();
                        reader.Close();
                    }
                }
                else
                    MessageBox.Show("Napačna šifra", "Napaka pri vstavlanju", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                reader0.Close();
            }
            else
            {
                MessageBox.Show("Vnesite šifro", "Napaka pri vstavlanju", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            }
            cn.Close(); 
        }

        private void dodajIzdelekToolStripMenuItem_Click(object sender, EventArgs e)
        {
            dodaj.Show();
        }

        private void izbrišiToolStripMenuItem_Click(object sender, EventArgs e)
        {
            izbrisi.Show();
            izbrisi.osvezi();
        }

        DateTime _lastKeystroke = new DateTime(0);
        List<char> _barcode = new List<char>(10);

        private void textBox1_KeyPress(object sender, KeyPressEventArgs e)
        {
            // check timing (keystrokes within 100 ms)
            TimeSpan elapsed = (DateTime.Now - _lastKeystroke);
            if (elapsed.TotalMilliseconds > 100)
                _barcode.Clear();

            // record keystroke & timestamp
            _barcode.Add(e.KeyChar);
            _lastKeystroke = DateTime.Now;

            // process barcode
            if (e.KeyChar == 13 && _barcode.Count > 0)
            {
                string msg = new String(_barcode.ToArray());
                _barcode.Clear();
                if (!numericUpDown1.Enabled)
                {
                    button1.PerformClick();
                    textBox1.Clear();
                    textBox1.Focus();
                }
            }
        }

        private void spremeniToolStripMenuItem_Click(object sender, EventArgs e)
        {
            spremeni.Show();
            spremeni.osvezi();
        }

        private void numericUpDown1_Click(object sender, EventArgs e)
        {
            numericUpDown1.Select(0, numericUpDown1.Text.Length);
        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox1.Checked)
                numericUpDown1.Enabled = true;
            else
                numericUpDown1.Enabled = false;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (kosarica.Items.Count != 0)
            {
                if (textBox2.Text != "")
                {
                    if (Decimal.Parse(textBox2.Text) >= Decimal.Parse(label3.Text))
                    {
                        SqlDataReader reader = null;
                        int steviloIzdelkov = 0;
                        steviloIzdelkov = list_izdelek.Count();

                        for (int i = 0; i < steviloIzdelkov; i++)
                        {
                            try
                            {
                                cn.Open();
                                SqlCommand cmd = new SqlCommand("UPDATE Izdelki SET zaloga=zaloga-@kolicina WHERE ime_izdelka=@izdelek", cn);
                                cmd.Parameters.AddWithValue("@izdelek", list_izdelek.ElementAt(i).ToString());
                                cmd.Parameters.AddWithValue("@kolicina", list_kolicina.ElementAt(i));
                                reader = cmd.ExecuteReader();
                            }
                            catch (Exception ex)
                            {
                                MessageBox.Show(ex.Message);
                            }
                            finally
                            {
                                cn.Close();
                                reader.Close();
                            }
                        }


                        PrintDialog printDialog = new PrintDialog();

                        PrintDocument printDocument = new PrintDocument();

                        printDialog.Document = printDocument;

                        printDocument.PrintPage += new System.Drawing.Printing.PrintPageEventHandler(CreateReceipt);

                        DialogResult result = printDialog.ShowDialog();

                        if (result == DialogResult.OK)
                        {
                            printDocument.Print();
                        }

                        kosarica.Items.Clear();
                        list_cena.Clear();
                        list_izdelek.Clear();
                        list_kolicina.Clear();
                        textBox2.Clear();
                        cena0 = 0; skupaj = 0; sifra = ""; Array.Clear(cene, 0, cene.Length); label3.Text = "0"; velikostPolja = 0;
                    }
                    else
                        MessageBox.Show("Premajhno vplačilo", "Napaka pri vplačilu", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                }
                else
                    MessageBox.Show("Vnesite vplačilo", "Napaka pri vplačilu", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            }
            else
                MessageBox.Show("Vnesite izdelek", "Napaka pri vnosu izdelka", MessageBoxButtons.OK, MessageBoxIcon.Warning);
        }

        public void CreateReceipt(object sender, System.Drawing.Printing.PrintPageEventArgs e)
        {

            Graphics graphics = e.Graphics;
            Font font = new Font("Courier New", 10);
            float fontHeight = font.GetHeight();
            int startX = 50;
            int startY = 55;
            int Offset = 40;
            graphics.DrawString("SPTS trgovina", new Font("Courier New", 14),
                                new SolidBrush(Color.Black), startX, startY + Offset);
            Offset = Offset + 40;
            graphics.DrawString("Izdelek",
                     new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX, startY + Offset);
            graphics.DrawString("Količina",
                     new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX + 150, startY + Offset);
            graphics.DrawString("DDV",
                     new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX + 280, startY + Offset);
            graphics.DrawString("Cena",
                     new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX + 353, startY + Offset);
            Offset = Offset + 20;
            String underLine = "-------------------------------------------------";
            graphics.DrawString(underLine, new Font("Courier New", 10),
                     new SolidBrush(Color.Black), startX, startY + Offset);

            int i = 0;
            foreach (string item in kosarica.Items)
            {
                Offset = Offset + 20;
                graphics.DrawString(list_izdelek.ElementAt(i),
                         new Font("Courier New", 14),
                         new SolidBrush(Color.Black), startX, startY + Offset);
                graphics.DrawString(list_kolicina.ElementAt(i).ToString(),
                         new Font("Courier New", 14),
                         new SolidBrush(Color.Black), startX + 190, startY + Offset);
                graphics.DrawString("22%",
                         new Font("Courier New", 14),
                         new SolidBrush(Color.Black), startX + 283, startY + Offset);
                graphics.DrawString(list_cena.ElementAt(i).ToString()+"€",
                         new Font("Courier New", 14),
                         new SolidBrush(Color.Black), startX + 350, startY + Offset);
                i++;
            }
            i = 0;

            Offset = Offset + 20;
            underLine = "-------------------------------------------------";
            graphics.DrawString(underLine, new Font("Courier New", 10),
                     new SolidBrush(Color.Black), startX, startY + Offset);
            Offset = Offset + 20; //make some room so that the total stands out.

            Offset = Offset + 20;
            graphics.DrawString("Skupaj:".PadRight(15) + label3.Text + "€", new Font("Courier New", 14, FontStyle.Bold),
                     new SolidBrush(Color.Black), startX, startY + Offset);

            Offset = Offset + 40;
            graphics.DrawString("Vplačilo:".PadRight(15) + textBox2.Text + "€", new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX, startY + Offset);

            Offset = Offset + 20;
            graphics.DrawString("Izplačilo:".PadRight(15) + (Decimal.Parse(textBox2.Text) - Decimal.Parse(label3.Text)) + "€", new Font("Courier New", 14),
                     new SolidBrush(Color.Black), startX, startY + Offset);

            Offset = Offset + 40;
            graphics.DrawString("Hvala za nakup", new Font("Courier New", 14, FontStyle.Bold),
                     new SolidBrush(Color.Black), startX+100, startY + Offset);
        }

        private void button3_Click(object sender, EventArgs e)
        {
            for (int i = kosarica.SelectedIndices.Count - 1; i >= 0; i--)
            {
                velikostPolja = 0;
                kosarica.Items.RemoveAt(kosarica.SelectedIndices[i]);
                list_cena.RemoveAt(i);
                //list_znesek.RemoveAt(i);
                list_izdelek.RemoveAt(i);
                list_kolicina.RemoveAt(i);
                Array.Clear(cene, 0, cene.Length);
                Array.Clear(zaloga, 0, zaloga.Length);
                Array.Clear(izdelek, 0, izdelek.Length);
                izdelek = list_izdelek.ToArray();
                zaloga = list_kolicina.ToArray();
                cene = list_cena.ToArray();
            }
            skupaj = 0;
            for(int i=0;i<cene.Length;i++)
            {
                skupaj = skupaj + cene[i];
            }
            label3.Text = skupaj.ToString();
        }

        public void valuta()
        {
            if (kosarica.Items.Count > 0)
            {
                string tekst = kosarica.Items[0].ToString();
                string valuta = tekst[tekst.Length - 1].ToString();
                var pos = tekst.LastIndexOf(' ');
                var part = tekst.Substring(pos);
                char last = part[part.Length - 1];

                decimal decimalka = 0;
                decimal value = 0;
                for (int i = 0; i < izdelek.Length; i++)
                {
                    if (evro_check.Checked)
                    {
                        decimalka = Decimal.Parse(cena[i].ToString().Replace(',', '.'), CultureInfo.InvariantCulture);

                        value = Decimal.Parse(cena[i].ToString()); //evro

                        znak = "€";
                        list_znesek.Add(value);
                        label4.Text = "EUR";
                    }
                    else if (dolar_check.Checked)
                    {
                        decimalka = Decimal.Parse(cena[i].ToString().Replace(',', '.'), CultureInfo.InvariantCulture);

                        if (evro == true || funt == true)
                            value = Math.Round(decimalka / 0.92060m, 2);  //evro -> dolar

                        else
                            value = Decimal.Parse(cena[i].ToString()); //dolar

                        znak = "$";
                        list_znesek.Add(value);
                        label4.Text = "USD";
                    }
                    else
                    {
                        decimalka = Decimal.Parse(cena[i].ToString().Replace(',', '.'), CultureInfo.InvariantCulture);

                        if (evro == true || dolar == true)
                            value = Math.Round(decimalka / 1.36730m, 2); //evro -> funt        

                        else
                            value = Decimal.Parse(cena[i].ToString()); //funt

                        znak = "£";
                        list_znesek.Add(value);
                        label4.Text = "GBP";
                    }
                }
                zneski = list_znesek.ToArray();
                Array.Clear(zneski, 0, zneski.Length);
                zneski = list_znesek.ToArray();

                evro = false; dolar = false; funt = false;
                kosarica.Items.Clear();

                decimal skupni_znesek = 0;
                for (int i = 0; i < izdelek.Length; i++)
                {
                    kosarica.Items.Add(izdelek[i].ToString().PadRight(17) + zaloga[i].ToString() + "x".PadRight(5) + zneski[i].ToString() + znak);
                    skupni_znesek = skupni_znesek + zneski[i];
                }
                label3.Text = skupni_znesek.ToString();
                list_znesek.Clear();
            }
        }

        private void evro_check_Click(object sender, EventArgs e)
        {
            valuta();
        }

        private void dolar_check_Click(object sender, EventArgs e)
        {
            valuta();
        }

        private void funt_check_Click(object sender, EventArgs e)
        {
            valuta();
        }

        private void evro_check_CheckedChanged(object sender, EventArgs e)
        {
            label4.Text = "EUR";
            if(!dolar || !funt)
                evro = true;
        }

        private void dolar_check_CheckedChanged(object sender, EventArgs e)
        {
            label4.Text = "USD";
            if (!evro || !funt)
                dolar = true;
        }
        private void funt_check_CheckedChanged(object sender, EventArgs e)
        {
            label4.Text = "GBP";
            if (!evro || !dolar)
                funt = true;
        }

        private void label3_SizeChanged(object sender, EventArgs e)
        {
            label4.Location = new Point(label3.Right+15, 345);
        }
        

    }
}
