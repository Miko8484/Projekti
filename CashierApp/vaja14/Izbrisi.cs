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
    public partial class Izbrisi : Form
    {
        Form1 form1;
        public Izbrisi(Form1 form)
        {
            form1 = form;
            InitializeComponent();
            
        }

        private void Izbrisi_Load(object sender, EventArgs e)
        {
            osvezi();
            dataGridView1.Columns[0].HeaderText = "Šifra";
            dataGridView1.Columns[1].HeaderText = "Ime izdelka";
            dataGridView1.Columns[2].HeaderText = "Kategorija";
            dataGridView1.Columns[3].HeaderText = "Količina";
            dataGridView1.Columns[4].HeaderText = "Cena";

            comboBox1.Items.Add("Vsi");
            SqlDataReader reader = null;
            cn.Open();
            SqlCommand cmd = new SqlCommand("SELECT kategorija FROM Kategorije", cn);
            reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                comboBox1.Items.Add(reader[0]);
            }
            reader.Close();
            cn.Close();

            comboBox1.SelectedIndex = 0;
        }

        SqlConnection cn = new SqlConnection(global::vaja14.Properties.Settings.Default.IzdelkiConnectionString);

        private void button1_Click(object sender, EventArgs e)
        {
            SqlDataReader reader = null;
            try
            {
                cn.Open();
                SqlCommand cmd = new SqlCommand("SELECT sifra FROM Izdelki WHERE sifra=@sifra", cn);
                cmd.Parameters.AddWithValue("@sifra", textBox1.Text);
                reader = cmd.ExecuteReader();

                if (!reader.Read())
                {
                    MessageBox.Show("Izdelek z šifro ne obstaja");
                }
                else
                {
                    SqlCommand cmd2 = new SqlCommand("DELETE FROM Izdelki WHERE sifra=@sifra", cn);
                    cmd2.Parameters.AddWithValue("@sifra", textBox1.Text);
                    reader.Close();
                    cmd2.ExecuteNonQuery();

                    textBox1.Clear();
                    cn.Close();
                    osvezi();
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

        private void Izbrisi_FormClosing(object sender, FormClosingEventArgs e)
        {
            this.Hide();
            this.Parent = null;
            e.Cancel = true;
            form1.TopMost = true;
            form1.Show();
            form1.TopMost = false;
        }

        private void textBox1_KeyPress(object sender, KeyPressEventArgs e)
        {
            DateTime _lastKeystroke = new DateTime(0);
            List<char> _barcode = new List<char>(10);

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
                button1.PerformClick();
                textBox1.Clear();
                textBox1.Focus();
            }
        }

        public void osvezi()
        {
            try
            {
                cn.Open();
                SqlCommand cmd = new SqlCommand("SELECT sifra,ime_izdelka,id_kategorija,zaloga,cena FROM Izdelki ORDER BY sifra ASC", cn);
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

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            osvezi();

            int rowCount = dataGridView1.RowCount;
            for (int i = 0; i < rowCount; i++)
            {
                if (dataGridView1.Rows[i].Cells[2].Value.ToString() != comboBox1.SelectedItem.ToString())
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = false;
                    currencyManager1.ResumeBinding();
                }
                if (comboBox1.SelectedItem.ToString() == "Vsi")
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
