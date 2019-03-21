using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace minolovec
{
    public partial class Form1
    {
        private void makeGridClient(string[] bombs) //ustvarimo igralno polje
        {
            string cor = "";
            int z = 0;
            for (int i = 0; i < grid_height; i++)
            {
                for (int j = 0; j < grid_width; j++)
                {
                    Button newButton = new Button();
                    newButton.Width = 32;
                    newButton.Height = 32;
                    newButton.Top = 32 + (i * 32);
                    newButton.Left = 200 + (j * 32);
                    newButton.Name = i + "|" + j;

                    cor = i + "|" + j;
                    if (cor == bombs[z])
                    {
                        newButton.Tag = "B";
                        z++;
                    }
                    else
                    {
                        newButton.Tag = "0";
                    }

                    newButton.MouseDown += new MouseEventHandler(client_minefield_button_click);
                    newButton.Enabled = false;

                    buttons.Add(newButton);

                    this.BeginInvoke((Action)(() =>
                    {
                        this.Controls.Add(newButton);
                    }));

                }
            }
        }

        private void client_button(object sender, EventArgs e)
        {
            if (clientName.Text != "" && clientPort_textBox.Text!="" && ipaddres.Text!="")
            {
                clientPort = Int32.Parse(clientPort_textBox.Text);
                client = new TcpClient();
                try
                {
                    client.Connect(ipaddres.Text, clientPort); //povežemo se na vneseni ip
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Napaka pri povezovanju \n" + ex.Message);
                    return;
                }

                //resetiramo določene spremenljivke ob ponovni igri
                foreach (Button b in buttons) //resetiramo določene spremenljivke ob ponovni igri
                {
                    this.BeginInvoke((Action)(() =>
                    {
                        b.Dispose();
                    }));
                }
                this.BeginInvoke((Action)(() =>
                {
                    tocke_label.Text = "0";
                    lestvica_listView.Items.Clear();
                }));
                buttons.Clear();
                if (polja != null)
                    Array.Clear(polja, 0, polja.Length);
                if (client_igralci != null)
                    Array.Clear(client_igralci, 0, client_igralci.Length);
                if (client_points != null)
                    Array.Clear(client_points, 0, client_points.Length);

                this.BeginInvoke((Action)(() =>
                {
                    krog.Text = "Počakajte na vaš krog"; ;
                }));

                clientThread = new Thread(new ThreadStart(ListeningClient));
                clientThread.Start();  //nit za izvajanje clienta

                igralec = new Player();
                igralec.name = clientName.Text;

                tocke_label.Text = igralec.tocke.ToString();

                ipaddres.Enabled = false; clientName.Enabled = false; clientPovezi.Enabled = false; server_button.Enabled = false;
                sirinaGrida.Enabled = false; visinaGrida.Enabled = false; stIgralcev.Enabled = false; serverName.Enabled = false;
                clientPort_textBox.Enabled = false; serverPort_textBox.Enabled = false;
            }
            else
                MessageBox.Show("Vnesite ime, IP in port");
        }

        protected void client_minefield_button_click(object sender, EventArgs e) //klik na polje
        {
            MouseEventArgs c = (MouseEventArgs)e;
            Button polje = sender as Button;
            bool stevilka = false;
            string state = "", send = "";

            if (polje.BackgroundImage == null) //preverimo če je polje že kliknjeno
            {
                if (c.Button == MouseButtons.Right) //preverimo če je desni klik (predstavlja zastavico)
                {
                    if (polje.Tag.ToString() == "B") //če je bomba postavi zastavico, +5točke
                    {
                        polje.BackgroundImage = Properties.Resources.flag;
                        igralec.tocke += 5;
                        state = "f+";
                    }
                    else // drugače postavi številko, -3točke
                    {
                        igralec.tocke -= 3;
                        stevilka = true;
                        state = "f-";
                    }
                }
                else //levi klik
                {
                    if (polje.Tag.ToString() == "B") //kliknili smo bombo, -10točk
                    {
                        polje.BackgroundImage = Properties.Resources.bomb;
                        state = "b";
                        igralec.tocke -= 10;
                    }
                    else  //odkrili številko, točke enake odkriti številki
                    {
                        stevilka = true;
                        if (polje.Tag.ToString() == "0")
                            igralec.tocke += 1;
                        else
                            igralec.tocke += Int32.Parse(polje.Tag.ToString());
                    }
                }

                if (stevilka)
                {
                    if (polje.Tag.ToString() == "0")
                        checkForEmpty(polje);  //funkcija za odkritje povezanih praznih polj
                    else if (polje.Tag.ToString() == "1")
                        polje.BackgroundImage = Properties.Resources.m1;
                    else if (polje.Tag.ToString() == "2")
                        polje.BackgroundImage = Properties.Resources.m2;
                    else if (polje.Tag.ToString() == "3")
                        polje.BackgroundImage = Properties.Resources.m3;
                    else if (polje.Tag.ToString() == "4")
                        polje.BackgroundImage = Properties.Resources.m4;
                    else if (polje.Tag.ToString() == "5")
                        polje.BackgroundImage = Properties.Resources.m5;
                    else if (polje.Tag.ToString() == "6")
                        polje.BackgroundImage = Properties.Resources.m6;
                    else if (polje.Tag.ToString() == "7")
                        polje.BackgroundImage = Properties.Resources.m7;
                    else if (polje.Tag.ToString() == "8")
                        polje.BackgroundImage = Properties.Resources.m8;

                    if (state != "f-")
                        state = "s";
                }

                byte[] buffer = new byte[512];

                bool over = CheckGameOver(); //preverimo konec igre
                if (over)
                {
                    this.BeginInvoke((Action)(() =>
                    {
                        krog.Text = "Počakajte na vaš krog"; ;
                    }));
                    send = "CP$" + polje.Name + "$" + state; //pošljemo kliknjeno polje
                }
                else
                {
                    this.BeginInvoke((Action)(() =>
                    {
                        krog.Text = "Konec igre"; ;
                    }));
                    send = "GO$" + polje.Name + "$" + state; //pošljemo polje in game over
                }
                buffer = Encoding.ASCII.GetBytes(send);
                clientStream.Write(buffer, 0, send.Length);

                foreach (Button b in buttons)
                    b.Enabled = false;
            }
            tocke_label.Text = igralec.tocke.ToString();
            
        }      

        private void ListeningClient()
        {
            clientStream = client.GetStream();
            byte[] buffer = new byte[512];
            byte[] data = new byte[512];
            string prejeto = "";

            buffer = Encoding.ASCII.GetBytes(clientName.Text + "$");
            clientStream.Write(buffer, 0, buffer.Length);

            //dobimo sirino in višino grida in lokacijo bomb
            clientStream.Read(data, 0, data.Length);
            prejeto = Encoding.ASCII.GetString(data);
            string[] dobljeno = prejeto.Split('#');
            string[] mere = dobljeno[0].Split('|');
            grid_height = Int32.Parse(mere[0]);
            grid_width = Int32.Parse(mere[1]);

            string[] bombs = dobljeno[1].Split('/');

            //ustvari grid na clientu in označi bombe
            makeGridClient(bombs);
            markGrid();
            clientGame = true;
            string[] hit;
            while (clientGame)
            {
                clientGame = CheckGameOver(); //prevere konec igre
                if (!clientGame) //prekini če je konec
                    break;
                Array.Clear(data, 0, data.Length);
                try
                {
                    clientStream.Read(data, 0, 512); //beremo z serverja: potezo na serverju ali pa od drugi igralcev

                    prejeto = Encoding.ASCII.GetString(data);
                    hit = prejeto.Split('$');

                    if (hit[0] == "P") //na vrsti sem "jaz"
                    {
                        if (hit[1].Substring(0, clientName.Text.Length) == clientName.Text)
                        {
                            this.BeginInvoke((Action)(() =>
                            {
                                krog.Text = "Na vrsti ste vi"; ;
                            }));
                            foreach (Button b in buttons)
                            {
                                this.BeginInvoke((Action)(() =>
                                {
                                    b.Enabled = true;
                                }));
                            } 
                        }
                        else
                        {
                            foreach (Button b in buttons)
                            {
                                this.BeginInvoke((Action)(() =>
                                {
                                    b.Enabled = false;
                                }));
                            }
                        }
                    }
                    if (hit[0] == "SP") //poteza na serverju ali clientu
                    {
                        foreach (Button b in buttons)
                        {
                            if (b.Name == hit[1])
                            {
                                if (hit[2].StartsWith("b"))
                                    b.BackgroundImage = Properties.Resources.bomb;
                                else if (hit[2].StartsWith("f+"))
                                    b.BackgroundImage = Properties.Resources.flag;
                                else if (hit[2].StartsWith("s") || hit[2].StartsWith("f-"))
                                {
                                    if (b.Tag.ToString() == "0")
                                        checkForEmpty(b);
                                    else if (b.Tag.ToString() == "1")
                                        b.BackgroundImage = Properties.Resources.m1;
                                    else if (b.Tag.ToString() == "2")
                                        b.BackgroundImage = Properties.Resources.m2;
                                    else if (b.Tag.ToString() == "3")
                                        b.BackgroundImage = Properties.Resources.m3;
                                    else if (b.Tag.ToString() == "4")
                                        b.BackgroundImage = Properties.Resources.m4;
                                    else if (b.Tag.ToString() == "5")
                                        b.BackgroundImage = Properties.Resources.m5;
                                    else if (b.Tag.ToString() == "6")
                                        b.BackgroundImage = Properties.Resources.m6;
                                    else if (b.Tag.ToString() == "7")
                                        b.BackgroundImage = Properties.Resources.m7;
                                    else if (b.Tag.ToString() == "8")
                                        b.BackgroundImage = Properties.Resources.m8;
                                }

                                this.BeginInvoke((Action)(() =>
                                {
                                    b.Enabled = false;
                                }));
                            }
                        }
                    }
                    if (hit[0] == "GO") //game over
                    {
                        foreach (Button b in buttons)
                        {
                            if (b.Name == hit[1].Substring(0, 3))
                            {
                                if (hit[2].StartsWith("b"))
                                    b.BackgroundImage = Properties.Resources.bomb;
                                else if (hit[2].StartsWith("f+"))
                                    b.BackgroundImage = Properties.Resources.flag;
                                else if (hit[2].StartsWith("s") || hit[2].StartsWith("f-"))
                                {
                                    if (b.Tag.ToString() == "0")
                                        checkForEmpty(b);
                                    else if (b.Tag.ToString() == "1")
                                        b.BackgroundImage = Properties.Resources.m1;
                                    else if (b.Tag.ToString() == "2")
                                        b.BackgroundImage = Properties.Resources.m2;
                                    else if (b.Tag.ToString() == "3")
                                        b.BackgroundImage = Properties.Resources.m3;
                                    else if (b.Tag.ToString() == "4")
                                        b.BackgroundImage = Properties.Resources.m4;
                                    else if (b.Tag.ToString() == "5")
                                        b.BackgroundImage = Properties.Resources.m5;
                                    else if (b.Tag.ToString() == "6")
                                        b.BackgroundImage = Properties.Resources.m6;
                                    else if (b.Tag.ToString() == "7")
                                        b.BackgroundImage = Properties.Resources.m7;
                                    else if (b.Tag.ToString() == "8")
                                        b.BackgroundImage = Properties.Resources.m8;
                                }

                                this.BeginInvoke((Action)(() =>
                                {
                                    b.Enabled = false;
                                }));
                            }
                        }

                        client_igralci = hit[3].Split('|');
                        client_points = hit[4].Split('|');

                        clientGame = false;
                    }
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Server se je zaprl");
                    closeClient();
                    break;
                }
            }

            this.BeginInvoke((Action)(() =>
            {
                krog.Text = "Konec igre"; ;
            }));

            List<Player> lestvica = new List<Player>();

            for (int i = 0; i < client_igralci.Length - 1; i++) //lestvica
            {
                Player p = new Player();
                p.name = client_igralci[i];
                p.tocke = Int32.Parse(client_points[i]);
                lestvica.Add(p);
            }

            //sortirana lestvica po točkah
            List<Player> SortiranaLestvica = lestvica.OrderByDescending(o => o.tocke).ToList();

            foreach (Player p in SortiranaLestvica)
            {
                ListViewItem l = new ListViewItem(p.name);
                l.SubItems.Add(p.tocke.ToString());

                this.BeginInvoke((Action)(() =>
                {
                    lestvica_listView.Items.Add(l);
                }));
            }

            this.BeginInvoke((Action)(() =>
            {
                lestvica_listView.AutoResizeColumns(ColumnHeaderAutoResizeStyle.ColumnContent);
                lestvica_listView.AutoResizeColumn(0, ColumnHeaderAutoResizeStyle.HeaderSize);
                lestvica_listView.AutoResizeColumn(1, ColumnHeaderAutoResizeStyle.HeaderSize);
                lestvica_listView.Columns[1].TextAlign = HorizontalAlignment.Right;
                label8.Visible = true; lestvica_listView.Visible = true;
            }));

            MessageBox.Show("Zmagovalec je: " + SortiranaLestvica[0].name + " z " + SortiranaLestvica[0].tocke.ToString() + "točkami");

            sirinaGrida.Enabled = true; visinaGrida.Enabled = true; stIgralcev.Enabled = true; serverName.Enabled = true;
            ipaddres.Enabled = true; clientName.Enabled = true; clientPovezi.Enabled = true; server_button.Enabled = true;
            clientPort_textBox.Enabled = true; serverPort_textBox.Enabled = true;
        }
    }
}
