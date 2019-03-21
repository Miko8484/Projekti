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
using System.Globalization;

namespace vaja14
{
    public partial class VsiIzdelki : Form
    {
        Form1 form1;
        public VsiIzdelki(Form1 form)
        {
            InitializeComponent();
            form1 = form;
        }

        SqlConnection cn = new SqlConnection(global::vaja14.Properties.Settings.Default.IzdelkiConnectionString);

        private void VsiIzdelki_Load_1(object sender, EventArgs e)
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
            while(reader.Read())
            {
                comboBox1.Items.Add(reader[0]);
            }
            reader.Close();
            cn.Close();

            comboBox1.SelectedIndex = 0;
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
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
            finally
            {
                cn.Close();
            }

            int rowCount = dataGridView1.RowCount;

            SqlDataReader reader = null;
            for (int i = 0; i < rowCount; i++)
            {
                decimal text = Decimal.Parse(dataGridView1.Rows[i].Cells[4].Value.ToString());
                dataGridView1.Rows[i].Cells[4].Value = text;
                dataGridView1.Columns[4].DefaultCellStyle.BackColor = Color.LightSalmon;

                cn.Open();
                SqlCommand cmd = new SqlCommand("SELECT kategorija FROM Kategorije WHERE id=@idkategorije", cn);
                cmd.Parameters.AddWithValue("@idkategorije", dataGridView1.Rows[i].Cells[2].Value.ToString());
                reader = cmd.ExecuteReader();

                if(reader.Read())
                {
                    dataGridView1.Rows[i].Cells[2].ValueType = typeof(System.String);
                    dataGridView1.Rows[i].Cells[2].Value = reader[0];
                }
                reader.Close();
                cn.Close();
            }
        }

        public void valute()
        {
            string valuta;
            int rowCount = dataGridView1.RowCount;
            decimal decimalka = 0;
            decimal value = 0;
            for (int i = 0; i < rowCount; i++)
            {
                string text = dataGridView1.Rows[i].Cells[4].Value.ToString();

                if (evro_check.Checked)
                {
                    decimalka = Decimal.Parse(text.Replace(',', '.'), CultureInfo.InvariantCulture);

                    if (dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightGreen)
                        value = Math.Round(decimalka / 1.13473m, 2); //dolar

                    else if (dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.SkyBlue)
                        value = Math.Round(decimalka / 0.73374m, 2); //funt

                    else
                        value = Decimal.Parse(text); //evro

                    dataGridView1.Rows[i].Cells[4].Value = value.ToString();
                    dataGridView1.Columns[4].DefaultCellStyle.BackColor = Color.LightSalmon;
                }
                else if (dolar_check.Checked)
                {
                    decimalka = Decimal.Parse(text.Replace(',', '.'), CultureInfo.InvariantCulture);

                    if (dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightSalmon || (i>0 &&dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightGreen))
                        value = Math.Round(decimalka / 0.882394m, 2); //evro

                    else if (dataGridView1.Rows[4].DefaultCellStyle.BackColor == Color.SkyBlue ||(i > 0 && dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightGreen))
                        value = Math.Round(decimalka / 0.64679m, 2);  //funt

                    else
                        value = Decimal.Parse(text);  //evro

                    dataGridView1.Rows[i].Cells[4].Value = value.ToString();
                    dataGridView1.Columns[4].DefaultCellStyle.BackColor = Color.LightGreen;
                }
                else
                {
                    decimalka = Decimal.Parse(text.Replace(',', '.'), CultureInfo.InvariantCulture);

                    if (dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightSalmon || (i > 0 && dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.SkyBlue))
                        value = Math.Round(decimalka / 1.36345m, 2); //evro

                    else if (dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.LightGreen || (i > 0 && dataGridView1.Columns[4].DefaultCellStyle.BackColor == Color.SkyBlue))
                        value = Math.Round(decimalka / 1.54695m, 2);  //dolar

                    else
                        value = Decimal.Parse(text); //funt

                    dataGridView1.Rows[i].Cells[4].Value = value;
                    dataGridView1.Columns[4].DefaultCellStyle.BackColor = Color.SkyBlue;
                }

            }
            prikaz();
        }

        private void VsiIzdelki_FormClosing(object sender, FormClosingEventArgs e)
        {
            evro_check.Checked = true;
            this.Hide();
            this.Parent = null;
            e.Cancel = true;
            form1.TopMost = true;
            form1.Show();
            form1.TopMost = false;
        }

        private void dolar_check_Click(object sender, EventArgs e)
        {
            osvezi();
            valute();
        }

        private void evro_check_Click(object sender, EventArgs e)
        {
            osvezi();
            valute();
        }

        private void funt_check_Click(object sender, EventArgs e)
        {
            osvezi();
            valute();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            evro_check.Checked = true;
            osvezi();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            osvezi();
            valute();
            int rowCount = dataGridView1.RowCount;

            for(int i=0;i<rowCount;i++)
            {
                if (dataGridView1.Rows[i].Cells[2].Value.ToString() != comboBox1.SelectedItem.ToString())
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = false;
                    currencyManager1.ResumeBinding();
                }
                if(comboBox1.SelectedItem.ToString()=="Vsi")
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = true;
                    currencyManager1.ResumeBinding();
                }
            }
            dataGridView1.Refresh();
        }

        public void prikaz()
        {
            int rowCount = dataGridView1.RowCount;

            for(int i=0;i<rowCount;i++)
            {
                if (dataGridView1.Rows[i].Cells[2].Value.ToString() != comboBox1.SelectedItem.ToString())
                {
                    CurrencyManager currencyManager1 = (CurrencyManager)BindingContext[dataGridView1.DataSource];
                    currencyManager1.SuspendBinding();
                    dataGridView1.Rows[i].Visible = false;
                    currencyManager1.ResumeBinding();
                }
                if(comboBox1.SelectedItem.ToString()=="Vsi")
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
