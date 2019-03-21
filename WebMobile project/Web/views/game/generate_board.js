
var board = new Array(x_size);
for( i=0;i<y_size;i++){

    board[i] = new Array(y_size);

    for( j=0;j<x_size;j++)
    {
        board[i][j] = new Array(2);
        board[i][j][0] = 0;
        board[i][j][1] = 'field';
    }
}
