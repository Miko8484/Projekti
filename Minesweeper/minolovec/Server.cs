using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace minolovec
{
    public partial class Form1
    {
        private void makeGridServer()  //zgradimo polje za igro
        {
            int randomHorizotnal, randomVertical;

            //postavimo širino in višino polja v primeru da je izven min/max vednosti
            if (grid_width < 5) 
                grid_width = 5;
            if (grid_height < 5)
                grid_height = 5;
            if (grid_width > 40)
                grid_width = 40;
            if (grid_height > 22)
                grid_height = 22;

            int bombNumber = (grid_height*grid_width)/3;  //število bomb
            int[] bomb_i = new int[bombNumber];
            int[] bomb_j = new int[bombNumber];

            Random r = new Random();
            for (int i = 0; i < bombNumber; i++) //naključne koordinate za bombe
            {
                randomHorizotnal = r.Next(0, grid_width);
                randomVertical = r.Next(0, grid_height);
                bomb_i[i] = randomHorizotnal;
                bomb_j[i] = randomVertical;
            }


            for (int i = 0; i < grid_height; i++)  //ustvarimo gumbe za posamezno polje
            {
                for (int j = 0; j < grid_width; j++)
                {
                    Button newButton = new Button();
                    newButton.Width = 32;
                    newButton.Height = 32;
                    newButton.Top = 32 + (i * 32);
                    newButton.Left = 200 + (j * 32);
                    newButton.Name = i + "|" + j;

                    for (int z = 0; z < bombNumber; z++)
                    {
                        if (i == bomb_i[z] && j == bomb_j[z])
                        {
                            newButton.Tag = "B";
                            break;
                        }
                        else
                            newButton.Tag = "0";
                    }
                    newButton.MouseDown += new MouseEventHandler(server_minefield_button_click);
                    newButton.Enabled = false;

                    buttons.Add(newButton);
                    this.Controls.Add(newButton);
                }
            }
        }

        private void server_button_Click(object sender, EventArgs e)
        {
            //preverimo če so izpolnjena vsa polja
            if (sirinaGrida.Text != "" && visinaGrida.Text != "" && stIgralcev.Text != "" && serverName.Text != "" && serverPort_textBox.Text!="")
            {
                if (Int32.Parse(stIgralcev.Text) < 1)
                    MessageBox.Show("Število igralcev more biti minimalno 1");
                else
                {
                    //v primeru ponovne igre izbrišemo prejšnje gumbe, pobrišemo polja, resetiramo nekatere spremenljivke
                    foreach (Button b in buttons)
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
                    if (players != null)
                        Array.Clear(players, 0, players.Length);
                    server_igralci = "";
                    server_points = "";
                    serverTurn = true;
                    this.BeginInvoke((Action)(() =>
                    {
                        krog.Text = "Počakajte da se povežejo vsi igralci"; ;
                    }));

                    serverPort = Int32.Parse(serverPort_textBox.Text);

                    server = new TcpListener(IPAddress.Any, serverPort);
                    serverThread = new Thread(new ThreadStart(LiseningForClient));
                    serverThread.Start(); //zaženemo nit, ki posluša za sprejemanje clientov

                    igralec = new Player(); //ustvarimo igralca na strani serverja
                    igralec.name = serverName.Text;

                    tocke_label.Text = igralec.tocke.ToString();

                    grid_height = Int32.Parse(visinaGrida.Text);
                    grid_width = Int32.Parse(sirinaGrida.Text);

                    makeGridServer(); // ustvarimo igralno polje
                    markGrid(); //označimo igralno polje
                }
            }
            else
                MessageBox.Show("Izpolnite vsa polja");
        }

        protected void server_minefield_button_click(object sender, EventArgs e)
        {
            MouseEventArgs c = (MouseEventArgs)e;
            Button polje = sender as Button;
            bool stevilka = false;
            string state = "";
            string send = "";

            if (polje.BackgroundImage == null) //preverimo če je polje že kliknjeno
            {
                if (c.Button == MouseButtons.Right) //preverimo če je desni klik (predstavlja zastavico)
                {
                    if (polje.Tag == "B") //če je bomba postavi zastavico, +5točke
                    {
                        polje.BackgroundImage = Properties.Resources.flag;
                        igralec.tocke += 5;
                        state = "f+";
                    }
                    else // drugače postavi številko, -3točke
                    {
                        stevilka = true;
                        igralec.tocke -= 3;
                        state = "f-";
                    }
                }
                else //levi klik
                {
                    if (polje.Tag == "B") //kliknili smo bombo, -10točk
                    {
                        polje.BackgroundImage = Properties.Resources.bomb;
                        state = "b";
                        igralec.tocke -= 10;
                    }
                    else //odkrili številko, točke enake odkriti številki
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
                    state = "s";
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
                }

                byte[] buffer = new byte[512];

                bool over = CheckGameOver(); //preverimo konec igre
                server_points = "";
                foreach (Player p in players)
                    server_points += p.tocke + "|";

                tocke_label.Text = igralec.tocke.ToString();

                if (!over) //če je igre konec pošljemo še zadnje kliknjeno polje, stanje in igralce ter točke igralcev
                {
                    server_igralci = serverName.Text + "|" + server_igralci;
                    server_points = tocke_label.Text + "|" + server_points;
                    send = "GO$" + polje.Name + "$" + state + "$" + server_igralci + "$" + server_points + "$";
                    this.BeginInvoke((Action)(() =>
                    {
                        krog.Text = "Konec igre"; ;
                    }));
                }
                else //drugače pošljemo kliknjeno polje,stanje in igralec ki je na vrsti
                {
                    send = "SP$" + polje.Name + "$" + state + "$" + players[0].name + "$";
                    this.BeginInvoke((Action)(() =>
                    {
                        krog.Text = "Počakajte na vaš krog"; ;
                    }));
                }
                buffer = Encoding.ASCII.GetBytes(send);
                for (int i = 0; i < Int32.Parse(stIgralcev.Text); i++) //pošljemo vsem igralcem
                {
                    try
                    {
                        clientStreams[i].Write(buffer, 0, send.Length); //izvedemo dejansko pošiljanje
                    }
                    catch (Exception ex)
                    {

                    }
                }

                foreach (Button b in buttons) //onemogočimo polja
                    b.Enabled = false;

                serverTurn = false; //predamo krog clientu
                polje.Enabled = false;
            }
        }

        private void LiseningForClient()
        {
            server.Start(); //zaženemo server
            clientThreads = new Thread[Int32.Parse(stIgralcev.Text)]; //ustvarimo niti za posameznega clienta
            clients = new TcpClient[Int32.Parse(stIgralcev.Text)]; //
            clientStreams = new NetworkStream[Int32.Parse(stIgralcev.Text)];
            players = new Player[Int32.Parse(stIgralcev.Text)]; //polje igralcev

            byte[] data = new byte[512];
            string prejeto = "";

            this.BeginInvoke((Action)(() =>
            {
                sirinaGrida.Enabled = false; visinaGrida.Enabled = false; stIgralcev.Enabled = false; serverName.Enabled = false;
                ipaddres.Enabled = false; clientName.Enabled = false; clientPovezi.Enabled = false; server_button.Enabled = false;
                serverPort_textBox.Enabled = false; clientPort_textBox.Enabled = false;
            }));

            for (int i = 0; i < Int32.Parse(stIgralcev.Text); i++)
            {
                serverGame = true;
                clients[i] = server.AcceptTcpClient();
                clientThreads[i] = new Thread(new ParameterizedThreadStart(HandleClient));
                clientThreads[i].Start(i); //nova nit za vsakega clienta

                clientStreams[i] = clients[i].GetStream();

                clientStreams[i].Read(data, 0, data.Length); //dobimo ime igralca na clientu
                prejeto = Encoding.ASCII.GetString(data);
                string[] name = prejeto.Split('$');

                Player p = new Player();
                p.name = name[0];
                server_igralci += name[0] + "|"; //shranimo ime povezanega igralca

                players[i] = p;
            }

            if (players[Int32.Parse(stIgralcev.Text) - 1] != null) //ko je število igralcev enako željenemu, omogočimo polja na serverju
            {
                foreach (Button b in buttons)
                {
                    this.BeginInvoke((Action)(() =>
                    {
                        b.Enabled = true;
                    }));
                }
                this.BeginInvoke((Action)(() =>
                {
                    krog.Text = "Na vrsti ste vi";
                }));
            }
        }

        private void HandleClient(object OBJindex)
        {
            int index = (int)OBJindex; //zaporedna številka clienta
            clientStreams[index] = clients[index].GetStream();
            byte[] data = new byte[512];
            byte[] buffer = new byte[512];
            string send = "";

            int counter = 0;
            string bomb_cords = "";
            int limit = grid_height * grid_width;
            for (int i = 0; i < grid_height; i++) //sestaivmo string z lokacijami bomb
            {
                for (int j = 0; j < grid_width; j++)
                {
                    if (counter < limit)
                    {
                        if (buttons[counter].Tag.ToString() == "B")
                        {
                            bomb_cords += i.ToString() + "|" + j.ToString() + "/";
                        }
                    }
                    counter++;
                }
            }

            send = grid_height.ToString() + "|" + grid_width.ToString() + "#" + bomb_cords + "#";
            data = Encoding.ASCII.GetBytes(send);  
            clientStreams[index].Write(data, 0, send.Length); //poljšemo višino in širino grida ter lokacijo bomb

            string playerName = "";
            while (serverGame) //dokler traja igra
            {
                serverGame = CheckGameOver(); //preverjamo konec igre
                if (!serverGame) //prekinemo ob koncu igre
                    break;
                if (!serverTurn) //če je na vrsti client
                {
                    for (int j = 0; j < players.Length; j++) //gremo po vrsti skozi igralce
                    {
                        playerName = players[j].name; //ime igralca ki je na vrsti
                        buffer = Encoding.ASCII.GetBytes("P$" + playerName + "$");
                        try
                        {
                            clientStreams[j].Write(buffer, 0, buffer.Length); //pošljemo njegovo ime

                            clientStreams[j].Read(data, 0, data.Length); //preberemo njegov odgovor
                            string prejeto = Encoding.ASCII.GetString(data);

                            string[] hit = prejeto.Split('$');

                            if (hit[0] == "CP") //poteza na clientu
                            {
                                Array.Clear(buffer, 0, buffer.Length);
                                buffer = Encoding.ASCII.GetBytes("SP$" + hit[1] + "$" + hit[2] + "$");
                                for (int z = 0; z < Int32.Parse(stIgralcev.Text); z++)
                                {
                                    try
                                    {
                                        clientStreams[z].Write(buffer, 0, buffer.Length); //pošljemo še ostalim igralcem
                                    }
                                    catch (Exception ex)
                                    {

                                    }
                                }

                                foreach (Button b in buttons) //prikažemo potezu 
                                {
                                    if (b.Name == hit[1])
                                    {
                                        if (hit[2].StartsWith("b"))
                                        {
                                            b.BackgroundImage = Properties.Resources.bomb;
                                            players[j].tocke -= 10;
                                        }
                                        else if (hit[2].StartsWith("f+"))
                                        {
                                            b.BackgroundImage = Properties.Resources.flag;
                                            players[j].tocke += 5;
                                        }
                                        else if (hit[2].StartsWith("f-"))
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


                                            players[j].tocke -= 3;
                                        }
                                        else if (hit[2].StartsWith("s"))
                                        {
                                            if (b.Tag.ToString() == "0")
                                                players[j].tocke += 1;
                                            else
                                                players[j].tocke += Int32.Parse(b.Tag.ToString());

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
                                    }
                                }
                            }
                            if (hit[0] == "GO") //konec igre
                            {
                                foreach (Button b in buttons)
                                {
                                    if (b.Name == hit[1])
                                    {
                                        if (hit[2].StartsWith("b"))
                                        {
                                            b.BackgroundImage = Properties.Resources.bomb;
                                            players[j].tocke -= 10;
                                        }
                                        else if (hit[2].StartsWith("f+"))
                                        {
                                            b.BackgroundImage = Properties.Resources.flag;
                                            players[j].tocke += 5;
                                        }
                                        else if (hit[2].StartsWith("f-"))
                                        {
                                            b.BackgroundImage = Properties.Resources.flag;
                                            players[j].tocke -= 3;
                                        }
                                        else if (hit[2].StartsWith("s"))
                                        {
                                            if (b.Tag.ToString() == "0")
                                                players[j].tocke += 1;
                                            else
                                                players[j].tocke += Int32.Parse(b.Tag.ToString());

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
                                    }
                                }

                                server_points = "";
                                foreach (Player p in players)
                                    server_points += p.tocke + "|";

                                server_igralci = serverName.Text + "|" + server_igralci;
                                server_points = tocke_label.Text + "|" + server_points;
                                Array.Clear(buffer, 0, buffer.Length);
                                send = "GO$" + hit[1] + "$" + hit[2] + "$" + server_igralci + "$" + server_points + "$";
                                buffer = Encoding.ASCII.GetBytes(send);
                                for (int z = 0; z < Int32.Parse(stIgralcev.Text); z++)
                                {
                                    try
                                    {
                                        clientStreams[z].Write(buffer, 0, buffer.Length); //pošljemo vsem konec igre, z imeni igralcev in njihovimi točkami
                                    }
                                    catch (Exception ex)
                                    {

                                    }
                                }

                                serverGame = false;
                            }
                            if (!serverGame)
                                break;
                        }
                        catch (Exception ex)
                        {
                        }
                    }
                    if (serverGame) //ko končamo z clienti je na vrsti server
                    {
                        foreach (Button b in buttons)
                        {
                            this.BeginInvoke((Action)(() =>
                            {
                                b.Enabled = true;
                            }));
                        }
                        serverTurn = true;
                        this.BeginInvoke((Action)(() =>
                        {
                            krog.Text = "Na vrsti ste vi";
                        }));
                        
                    }
                }
            }
            this.BeginInvoke((Action)(() =>
            {
                krog.Text = "Konec igre"; ;
            }));

            List<Player> lestvica = new List<Player>();
            Player server = new Player();
            server.name = serverName.Text;
            server.tocke = Int32.Parse(tocke_label.Text);
            lestvica.Add(server);

            foreach (Player p in players) //dodamo igralce v lestvico
            {
                lestvica.Add(p);
            }

            //sotiramo lestvico po točkah
            List<Player> SortiranaLestvica = lestvica.OrderByDescending(o => o.tocke).ToList();

            foreach (Player p in SortiranaLestvica) //dodamo na listview
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
            })); //oblikujemo listview

            //prikažemo zmagovalca
            MessageBox.Show("Zmagovalec je: " + SortiranaLestvica[0].name + " z " + SortiranaLestvica[0].tocke.ToString() + "točkami");

            if (sirinaGrida.Enabled == false)
            {
                this.BeginInvoke((Action)(() =>
                {
                    sirinaGrida.Enabled = true; visinaGrida.Enabled = true; stIgralcev.Enabled = true; serverName.Enabled = true;
                    ipaddres.Enabled = true; clientName.Enabled = true; clientPovezi.Enabled = true; server_button.Enabled = true;
                    serverPort_textBox.Enabled = true;
                }));
            }

            closeServer(); //zapremo server

        }
    }
}
