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

namespace vaja14
{
    public partial class Spremeni : Form
    {
        Form1 form1;
        public Spremeni(Form1 form)
        {
            form1 = form;
            InitializeComponent();
        }

        private void Spremeni_Load(object sender, EventArgs e)
        {
            osvezi();
            dataGridView1.Columns[0].HeaderText = "Šifra";
            dataGridView1.Columns[1].HeaderText = "Ime izdelka";
            dataGridView1.Columns[2].HeaderText = "Kategorija";
            dataGridView1.Columns[3].HeaderText = "Zaloga";
            dataGridView1.Columns[4].HeaderText = "Cena";

            comboBox2.Items.Add("Vsi");
            SqlDataReader reader = null;
            cn.Open();
            SqlCommand cmd = new SqlCommand("SELECT kategorija FROM Kategorije", cn);
            reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                comboBox1.Items.Add(reader[0]);
                comboBox2.Items.Add(reader[0]);
            }
            reader.Close();
            cn.Close();

            comboBox1.SelectedIndex = 0;
            comboBox2.SelectedIndex = 0;
        }

        SqlConnection cn = new SqlConnection(global::vaja14.Properties.Settings.Default.IzdelkiConnectionString);

        bool sifra = false, imeIzdelka = false, kategorija=false, zaloga = false, cena = false;
        string polje = "";

        private void radioButton1_Click(object sender, EventArgs e)
        {
            sifra = true;
            visibleChange();
            checkChanged();
        }
        private void radioButton2_Click(object sender, EventArgs e)
        {
            imeIzdelka = true;
            visibleChange();
            checkChanged();
        }
        private void radioButton5_Click(object sender, EventArgs e)
        {
            kategorija = true;
            visibleChange();
            checkChanged();
        }
        private void radioButton3_Click(object sender, EventArgs e)
        {
            zaloga = true;
            visibleChange();
            checkChanged();
        }
        private void radioButton4_Click(object sender, EventArgs e)
        {
            cena = true;
            visibleChange();
            checkChanged();
        }

        public void visibleChange()
        {
            if (kategorija)
            {
                comboBox1.Visible = true;
                textBox2.Visible = false;
            }
            else
            {
                comboBox1.Visible = false;
                textBox2.Visible = true;
            }
        }

        public void checkChanged()
        {
            
            if (textBox3.Text != "")
            {
                SqlDataReader reader = null;
                try
                {
                    cn.Open();
                    SqlCommand cmd = new SqlCommand("SELECT * FROM Izdelki WHERE sifra=@sifra", cn);
                    cmd.Parameters.AddWithValue("@sifra", textBox3.Text);
                    reader = cmd.ExecuteReader();

                    if (!reader.Read())
                    {
                        MessageBox.Show("Izdelek z to šifro ne obstaja");
                    }
                    else
                    {
                        if (sifra)
                        {
                            polje = "sifra";
                            label4.Text = reader[1].ToString();
                        }
                        else if (imeIzdelka)
                        {
                            polje = "ime_izdelka";
                            label4.Text = reader[2].ToString();
                        }
                        else if (kategorija)
                        {
                            polje = "id_kategorija";
                            int kat = Int32.Parse(reader[3].ToString());
                            reader.Close();

                            SqlDataReader reader2 = null;
                            SqlCommand cmd2 = new SqlCommand("SELECT kategorija FROM Kategorije WHERE id=@idkategorije", cn);
                            cmd2.Parameters.AddWithValue("@idkategorije", kat.ToString());
                            reader2 = cmd2.ExecuteReader();

                            if (reader2.Read())
                            {
                                label4.Text = reader2[0].ToString();
                            }
                            reader2.Close();
                        }
                        else if (zaloga)
                        {
                            label4.Text = reader[4].ToString();
                            polje = "zaloga";
                        }
                        else if (cena)
                        {
                            polje = "cena";
                            label4.Text = reader[5].ToString();
                        }

                        sifra = false; imeIzdelka = false; kategorija = false; zaloga = false; cena = false;
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
                MessageBox.Show("Vnesti morate šifro izdelka");
        }

        private void Spremeni_FormClosing(object sender, FormClosingEventArgs e)
        {
            this.Hide();
            this.Parent = null;
            e.Cancel = true;
            form1.TopMost = true;
            form1.Show();
            form1.TopMost = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if ((textBox2.Text != "" || !(textBox2.Visible)) && textBox3.Text != "")
            {
                try
                {
                    cn.Open();
                    SqlCommand cmd2 = new SqlCommand("UPDATE Izdelki SET " + polje + " = @vrednost WHERE sifra = @sifra", cn); //sql stavek
                    cmd2.Parameters.AddWithValue("@sifra", textBox3.Text);
                    
                    if(polje=="id_kategorija")
                    {
                        SqlDataReader reader = null;
                        SqlCommand cmd = new SqlCommand("SELECT id FROM Kategorije WHERE kategorija=@kat", cn);
                        cmd.Parameters.AddWithValue("@kat", comboBox1.SelectedItem);
                        reader = cmd.ExecuteReader();
                        if (reader.Read())
                        {
                            cmd2.Parameters.AddWithValue("@vrednost", reader[0]);
                        }
                        reader.Close();
                    }
                    else if(polje=="cena")
                        cmd2.Parameters.AddWithValue("@vrednost", Decimal.Parse(textBox2.Text));
                    else
                        cmd2.Parameters.AddWithValue("@vrednost", textBox2.Text);
                    cmd2.ExecuteNonQuery();

                    textBox2.Clear(); textBox3.Clear(); label4.Text = "";
                    cn.Close();
                    osvezi();
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message);
                }
                finally
                {
                    cn.Close();
                }

                radioButton1.Checked = false; radioButton2.Checked = false; radioButton3.Checked = false; radioButton4.Checked = false; radioButton5.Checked = false;
                comboBox1.SelectedIndex = 0; comboBox1.Visible = false; textBox2.Visible = true;
            }
            else
                MessageBox.Show("Izpolniti morate vsa polja");
        }

        public void osvezi()
        {
            try
            {
                cn.Open();
                SqlCommand cmd = new SqlCommand("SELECT sifra,ime_izdelka,id_kategorija,zaloga,cena FROM Izdelki", cn);
                SqlDataAdapter sda = new SqlDataAdapter();
                sda.SelectCommand = cmd;
                DataTable dbdataset = new DataTable();
                sda.Fill(dbdataset);
                BindingSource bSource = new BindingSource();
                bSource.DataSource = dbdataset;
                dataGridView1.DataSource = bSource;
                sda.Update(dbdataset);

                int rowCount = dataGridView1.RowCount;
                SqlDataReader reader = null;
                for (int i = 0; i < rowCount; i++)
                {
                    SqlCommand cmd2 = new SqlCommand("SELECT kategorija FROM Kategorije WHERE id=@idkategorije", cn);
                    cmd2.Parameters.AddWithValue("@idkategorije", dataGridView1.Rows[i].Cells[2].Value.ToString());
                    reader = cmd2.ExecuteReader();

                    if (reader.Read())
                    {
                        dataGridView1.Rows[i].Cells[2].ValueType = typeof(System.String);
                        dataGridView1.Rows[i].Cells[2].Value = reader[0];
                    }
                    reader.Close();
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
            finally
            {
                cn.Close();
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            osvezi();
        }

        private void comboBox2_SelectedIndexChanged(object sender, EventArgs e)
        {
            osvezi();

            int rowCount = dataGridView1.RowCount;
            for (int i = 0; i < rowCount; i++)
            {
                if (dataGridView1.Rows[i].Cells[2].Value.ToString() != comboBox2.SelectedItem.ToString())
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = false;
                    currencyManager1.ResumeBinding();
                }
                if (comboBox2.SelectedItem.ToString() == "Vsi")
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = true;
                    currencyManager1.ResumeBinding();
                }
            }
            dataGridView1.Refresh();
        }
    }
}
