using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace minolovec
{
    public partial class Form1 : Form
    {
        public class Player
        {
            public string name;
            public int tocke;
        }

        private TcpListener server;
        private Thread serverThread;
        private TcpClient[] clients;
        private Thread[] clientThreads;
        private NetworkStream[] clientStreams; 
        private Player[] players; //igralci na clientu
        private Player igralec; //igralec na serverju

        private TcpClient client;
        private Thread clientThread;
        private NetworkStream clientStream;

        private bool serverGame = true; //spremljanje nadaljevanja igre na serverju
        private bool clientGame = true; //spremljanje nadaljevanje igre na clientu
        private bool serverTurn = true; //spremljanje ali je na vrsti server ali clienti

        private string server_igralci = "";  //string ki vsebuje vse igralce
        private string server_points = ""; //string ki vsebuje vse točke
        private string[] client_igralci;  //igralci
        private string[] client_points; //točke
        private int grid_height = 0; //višina polja
        private int grid_width = 0; //širina polja
        private string[] cords; //polje za shranjevanje koordinat dobitih iz imena polja
        private int serverPort;
        private int clientPort;

        private List<Button> buttons = new List<Button>(); //gumbi (polja)
        private Button[,] polja; //gumbi v obliki 2D polja
        private Stack<Button> vrsta = new Stack<Button>(); //stack kamor nalagmo polja ob ikanju povezanih praznih polj


        public Form1()
        {
            InitializeComponent();
        }

        public void closeServer()  //zapremo server, ob koncu igre ali pa ob zaprtju okna
        {
            for (int i = clientStreams.Length - 1; i < 0; i--) //zapremo povezave do clientov in njihove niti
            {
                if (clientStreams[i] != null) clientStreams[i].Close();
                if (clients[i] != null) clients[i].Close();
                if (clientThreads[i] != null) { clientThreads[i].Abort(); clientThreads[i] = null; }
            }
            if (server != null) server.Stop();  //zapremo server in vse niti
            if (serverThread != null)
            {
                serverThread.Abort();
                serverThread = null;
            }
            this.BeginInvoke((Action)(() =>
            {
                ipaddres.Enabled = true; clientName.Enabled = true; clientPovezi.Enabled = true; server_button.Enabled = true;
                sirinaGrida.Enabled = true; visinaGrida.Enabled = true; stIgralcev.Enabled = true; serverName.Enabled = true;
                serverPort_textBox.Enabled = true;
            }));  //omogočimo spet vse kontrole
        }

        public void closeClient()  //zapremo client ob koncu igre ali pa ob zaprtju okna
        {
            if (clientStream != null) clientStream.Close();
            if (client != null) client.Close();
            this.BeginInvoke((Action)(() =>
            {
                ipaddres.Enabled = true; clientName.Enabled = true; clientPovezi.Enabled = true; server_button.Enabled = true;
                sirinaGrida.Enabled = true; visinaGrida.Enabled = true; stIgralcev.Enabled = true; serverName.Enabled = true;
                clientPort_textBox.Enabled = true;
            })); //omogočimo kontrole
            foreach (Button b in buttons) //pobrišemo polja
            {
                this.BeginInvoke((Action)(() =>
                {
                    b.Dispose();
                }));
            }
            this.BeginInvoke((Action)(() => //nastvimo točke nazaj na 0
            {
                tocke_label.Text = "0";
            }));
            buttons.Clear(); //pobrišemo polja
            Array.Clear(polja, 0, polja.Length);
            if (clientThread != null) clientThread.Abort();
            
        }

        public void markGrid() //označimo grid (številke za posamezno polje)
        {
            polja = new Button[grid_height, grid_width];

            int z = 0;
            for (int i = 0; i < grid_height; i++) //polja pretvorimo v 2D polje
            {
                for (int j = 0; j < grid_width; j++)
                {
                    polja[i, j] = buttons[z];
                    z++;
                }
            }

            int count;
            for (int i = 0; i < grid_height; i++) //gremo skozi vsa polja
            {
                for (int j = 0; j < grid_width; j++)
                {
                    if (polja[i, j].Tag.ToString() == "B") //preverimo če polje vsebuje bombo
                    {
                        //v primeru da jo vsebuje povečamo število pri vseh sosednjih poljih in pazimo
                        //da ne gremo izven polja. Število bomb v okolici polja se hrani v .Tag lastnosti
                        if (i - 1 >= 0 && j - 1 >= 0 && polja[i - 1, j - 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i - 1, j - 1].Tag.ToString());
                            count++;
                            polja[i - 1, j - 1].Tag = count;
                        }
                        if (j - 1 >= 0 && polja[i, j - 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i, j - 1].Tag.ToString());
                            count++;
                            polja[i, j - 1].Tag = count;
                        }
                        if (i + 1 < grid_height && j - 1 >= 0 && polja[i + 1, j - 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i + 1, j - 1].Tag.ToString());
                            count++;
                            polja[i + 1, j - 1].Tag = count;
                        }
                        if (i + 1 < grid_height && j + 1 < grid_width && polja[i + 1, j + 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i + 1, j + 1].Tag.ToString());
                            count++;
                            polja[i + 1, j + 1].Tag = count;
                        }
                        if (j + 1 < grid_width && polja[i, j + 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i, j + 1].Tag.ToString());
                            count++;
                            polja[i, j + 1].Tag = count;
                        }
                        if (i - 1 >= 0 && j + 1 < grid_width && polja[i - 1, j + 1].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i - 1, j + 1].Tag.ToString());
                            count++;
                            polja[i - 1, j + 1].Tag = count;
                        }
                        if (i - 1 >= 0 && polja[i - 1, j].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i - 1, j].Tag.ToString());
                            count++;
                            polja[i - 1, j].Tag = count;
                        }
                        if (i + 1 < grid_height && polja[i + 1, j].Tag.ToString() != "B")
                        {
                            count = Int32.Parse(polja[i + 1, j].Tag.ToString());
                            count++;
                            polja[i + 1, j].Tag = count;
                        }
                    }

                }
            }
        }

        
        public void checkForEmpty(Button b) //flood-fill algoritem, s pomočjo stacka obiščemo vsa prazno povezana polja
        {
            int i, j;
            vrsta.Push(b);
            while (vrsta.Count != 0)
            {
                b = vrsta.Pop();

                cords = b.Name.Split('|');
                i = Int32.Parse(cords[0]);
                j = Int32.Parse(cords[1]);

                if (b.BackgroundImage == null && b.Tag != "B")
                {
                    if (b.Tag.ToString() == "0")
                    {
                        b.BackgroundImage = Properties.Resources.m0;
                        if (i - 1 >= 0 && j - 1 >= 0 && polja[i - 1, j - 1].Tag != "B" && polja[i - 1, j - 1].BackgroundImage == null)
                            vrsta.Push(polja[i - 1, j - 1]);
                        if (j - 1 >= 0 && polja[i, j - 1].Tag != "B" && polja[i, j - 1].BackgroundImage == null)
                            vrsta.Push(polja[i, j - 1]);
                        if (i + 1 < grid_height && j - 1 >= 0 && polja[i + 1, j - 1].Tag != "B" && polja[i + 1, j - 1].BackgroundImage == null)
                            vrsta.Push(polja[i + 1, j - 1]);
                        if (i + 1 < grid_height && polja[i + 1, j].Tag != "B" && polja[i + 1, j].BackgroundImage == null)
                            vrsta.Push(polja[i + 1, j]);
                        if (i + 1 < grid_height && j + 1 < grid_width && polja[i + 1, j + 1].Tag != "B" && polja[i + 1, j + 1].BackgroundImage == null)
                            vrsta.Push(polja[i + 1, j + 1]);
                        if (j + 1 < grid_width && polja[i, j + 1].Tag != "B" && polja[i, j + 1].BackgroundImage == null)
                            vrsta.Push(polja[i, j + 1]);
                        if (i - 1 >= 0 && j + 1 < grid_width && polja[i - 1, j + 1].Tag != "B" && polja[i - 1, j + 1].BackgroundImage == null)
                            vrsta.Push(polja[i - 1, j + 1]);
                        if (i - 1 >= 0 && polja[i - 1, j].Tag != "B" && polja[i - 1, j].BackgroundImage == null)
                            vrsta.Push(polja[i - 1, j]);
                    }
                }

            }
        }

        public bool CheckGameOver() //preverimo če so odkrita vsa polja kar pomeni da je konec igre
        {
            foreach (Button b in buttons)
                if (b.BackgroundImage == null)
                    return true;

            return false;
        }     

        private void Form1_FormClosed(object sender, FormClosedEventArgs e) //ko se okno zapre, zaključimo vse niti itd.
        {
            Environment.Exit(Environment.ExitCode);
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e) //ob zapiranju
        {
            if (clientName.Text != "") //zapremo client
                closeClient();
            else if (serverName.Text != "") //zapremo server
                closeServer();
        }

        private ToolTip tt; //nasveti ob izpolnjevanju 
        private void visinaGrida_Enter(object sender, EventArgs e)
        {
            tt = new ToolTip();
            tt.Show("Min: 5, Max: 22", visinaGrida, 3000);
        }

        private void visinaGrida_Leave(object sender, EventArgs e)
        {
            tt.Dispose();
        }

        private void sirinaGrida_Enter(object sender, EventArgs e)
        {
            tt = new ToolTip();
            tt.Show("Min: 5, Max: 40", sirinaGrida, 3000);
        }

        private void sirinaGrida_Leave(object sender, EventArgs e)
        {
            tt.Dispose();
        }

        private void stIgralcev_Enter(object sender, EventArgs e)
        {
            tt = new ToolTip();
            tt.Show("Min: 1", stIgralcev, 3000);
        }

        private void stIgralcev_Leave(object sender, EventArgs e)
        {
            tt.Dispose();
        }

        
    }
}
