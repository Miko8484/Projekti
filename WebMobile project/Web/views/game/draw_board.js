
for( i=y_size-1;i>=0;i--){

    document.write("<tr>") ;
    document.write("<td class='coordinates_cell' >"+i+"</td>") ;
    for( j=0;j<x_size;j++)
    {
        id=j+","+i;
        document.write("<td class='cell neutral' id="+"'cell_"+id+"' onclick='play("+id+")'><img id='slika_"+id+"' /></td>") ;
    }
    document.write("</tr>") ;

}
document.write("<tr>") ;
document.write("<td  class='coordinates_cell' ></td>") ;
for( j=0;j<x_size;j++)
{
    document.write("<td  class='coordinates_cell' >"+j+"</td>") ;
}
document.write("</tr>") ;