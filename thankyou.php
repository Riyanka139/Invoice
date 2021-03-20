<?php
    require_once('config.php');
?>

<?php
//call the FPDF library
require('fpdf182/fpdf.php');

class Item{
    public $id;
    public $name;
    public $price;
    public $quan;
}

$subtotal = 0;

if(isset($_POST['order'])){
    $date = $_POST['date'];
    $clientid = $_POST['client'];
    $count = count($_POST['item_id']);
    for($i=0; $i < $count; $i++) {
        $item[$i] = new Item;
        $item[$i]->id = $_POST['item_id'][$i];
        $item[$i]->quan = $_POST['quantity'][$i];

        $query = mysqli_query($connection,"select * from item where itemID = '{$_POST['item_id'][$i]}'");
        if(!$query) {
            die("QUERY FAILED " . mysqli_error($connection));
        }
        $items = mysqli_fetch_array($query);
        $item[$i]->name = $items['itemName'];
        $item[$i]->price = $items['amount'];

        $amount = $item[$i]->quan * $item[$i]->price;
        $subtotal = $subtotal + $amount;

    }
    $query = mysqli_query($connection,"insert into invoice (clientID,datetime,total) VALUES ('{$clientid}','{$date}' , '{$subtotal}')");
        if(!$query) {
            die("QUERY FAILED " . mysqli_error($connection));
        }
}
$query = mysqli_query($connection,"select * from clients where clientID = '{$clientid}'");
if(!$query) {
     die("QUERY FAILED " . mysqli_error($connection));
}
$client = mysqli_fetch_array($query);

$query = mysqli_query($connection,"select invoiceID from invoice where clientID = '{$clientid}'");
if(!$query) {
     die("QUERY FAILED " . mysqli_error($connection));
}
$invoice = mysqli_fetch_array($query);

//create pdf object
$pdf = new FPDF('P','mm','A4');
//add new page
$pdf->AddPage();

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(150 ,5,'Date and Time: ',0,0,'R');
$pdf->Cell(34 ,5,$date,0,1);//end of line

$pdf->Cell(137 ,5,'Invoice: #',0,0,'R');
$pdf->Cell(34 ,5,$invoice['invoiceID'],0,1);//end of line

$pdf->Cell(146 ,5,'Customer ID: ',0,0,'R');
$pdf->Cell(34 ,5,$clientid,0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(43 ,5,'Customer Name: ',0,0);
$pdf->Cell(90 ,5,$client['name'],0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(43 ,5,'Customer Address: ',0,0);
$pdf->Cell(90 ,5,$client['address'],0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(43 ,5,'Customer PhoneNo.: ',0,0);
$pdf->Cell(90 ,5,$client['phone'],0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(100 ,5,'Description',1,0);
$pdf->cell(30,5, 'Quantity',1,0);
$pdf->Cell(25 ,5,'Unit price',1,0);
$pdf->Cell(34 ,5,'Amount',1,1);//end of line

$pdf->SetFont('Arial','',12);

//Numbers are right-aligned so we give 'R' after new line parameter
for($i=0; $i < $count; $i++) {
    $pdf->Cell(100 ,5,$item[$i]->name,1,0);
    $pdf->Cell(30 ,5,$item[$i]->quan,1,0);
    $pdf->Cell(25 ,5,$item[$i]->price,1,0);
    $pdf->Cell(34 ,5,$amount,1,1,'R');//end of line
}

//summary
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Subtotal',0,0);
$pdf->Cell(8 ,5,'Rs',1,0);
$pdf->Cell(26 ,5,$subtotal,1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Taxable',0,0);
$pdf->Cell(8 ,5,'Rs',1,0);
$pdf->Cell(26 ,5,'0',1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Tax Rate',0,0);
$pdf->Cell(8 ,5,'Rs',1,0);
$pdf->Cell(26 ,5,'10%',1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Total Due',0,0);
$pdf->Cell(8 ,5,'Rs',1,0);
$pdf->Cell(26 ,5,($subtotal*0.1)+$subtotal,1,1,'R');//end of line
//output the result
$pdf->Output();
?>