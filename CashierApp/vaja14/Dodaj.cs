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
using MySql.Data.MySqlClient;

namespace vaja14
{
    public partial class Dodaj : Form
    {
        Form1 form1;
        public int id=0;
        public Dodaj(Form1 form)
        {
            InitializeComponent();
            form1 = form;
        }
        private void Dodaj_Load(object sender, EventArgs e)
        {
            osvezi();
            dataGridView1.Columns[0].HeaderText = "Šifra";
            dataGridView1.Columns[1].HeaderText = "Ime izdelka";
            dataGridView1.Columns[2].HeaderText = "Kategorija";
            dataGridView1.Columns[3].HeaderText = "Količina";
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

        private void button1_Click(object sender, EventArgs e)
        {
            SqlDataReader reader = null;
            try
            {
                cn.Open();
                SqlCommand cmd = new SqlCommand("SELECT sifra FROM Izdelki WHERE sifra=@sifra", cn);
                cmd.Parameters.Add("@sifra", SqlDbType.VarChar).Value = textBox1.Text;
                reader = cmd.ExecuteReader();

                if (reader.Read())
                {
                    MessageBox.Show("Izdelek z šifro že obstaja");
                }
                else
                {
                    reader.Close();
                    SqlCommand cmd2 = new SqlCommand("INSERT INTO Izdelki (sifra,ime_izdelka,id_kategorija,zaloga,cena) VALUES (@sifra,@ime,@kategorija,@kolicina,@cena)", cn);
                    cmd2.Parameters.AddWithValue("@sifra", textBox1.Text);
                    cmd2.Parameters.AddWithValue("@ime", textBox2.Text);
                    getIDkategorije(comboBox1.SelectedItem.ToString());
                    cmd2.Parameters.AddWithValue("@kategorija", id);
                    cmd2.Parameters.AddWithValue("@kolicina", textBox3.Text);
                    cmd2.Parameters.AddWithValue("@cena", Decimal.Parse(textBox4.Text));
                    
                    cmd2.ExecuteNonQuery();

                    textBox1.Clear();
                    textBox2.Clear();
                    textBox3.Clear();
                    textBox4.Clear();
                    textBox1.Focus();
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
            osvezi();
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

        private void Dodaj_FormClosing(object sender, FormClosingEventArgs e)
        {
            this.Hide();
            comboBox1.SelectedIndex = 0;
            comboBox2.SelectedIndex = 0;
            this.Parent = null;
            e.Cancel = true;
            form1.TopMost = true;
            form1.Show();
            form1.TopMost = false;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            this.Close();
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
        
        public void getIDkategorije(string kat)
        {
            SqlDataReader reader = null;
            SqlCommand cmd3 = new SqlCommand("SELECT id FROM Kategorije WHERE kategorija=@kat", cn);
            cmd3.Parameters.AddWithValue("@kat", kat);
            reader = cmd3.ExecuteReader();
            if(reader.Read())
            {
                id = Int32.Parse(reader[0].ToString());
            }
            reader.Close();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            osvezi();
        }

    }
}
