<?php 
    require_once("config.php");
?>

<html>
    <head>
        <title>Order</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container pt-5">
            <div class="card" style="box-shadow:0 0 25px 0 lightgrey">
                <div class="card-header">Order</div>
                <div class="card-body text-center">
                    <form action="thankyou.php" method="POST">
                        <div class="form-group row pt-3">
                            <label  class="col-3 col-form-label">Date and time</label>
                            <div class="col-6">
                                <input name="date" class="form-control" type="date" value="Date" >
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <label  class="col-3 col-form-label">Name</label>
                            <div class="col-6">
                                <select class="custom-select" name='client'>
                                    <option selected>select Customer Name</option>
                                    <?php
                                        $query = mysqli_query($connection,"select * from clients");
                                        if(!$query) {
                                            die("QUERY FAILED " . mysqli_error($connection));
                                        }
                                        while($row=mysqli_fetch_assoc($query)){
                                            echo "<option value = '{$row['clientID']}'>". $row['name'] ."</option>";
                                            
                                        }
                                    ?>                             
                                </select>
                            </div>
                        </div>
                        <div class="container pt-3">
                            <div class="card" style="box-shadow:0 0 25px 0 lightgrey">
                                <div class="card-header" align="left">Order List</div>
                                <div class="card-body text-center">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                            
                                            $i = 1;
                                            $query = mysqli_query($connection,"select * from item");
                                            if(!$query) {
                                                die("QUERY FAILED " . mysqli_error($connection));
                                            }
                                            
                                            while($row=mysqli_fetch_assoc($query)){
                                                echo "<tr>";
                                                echo "<td>". $i . "</td>";
                                                echo "<td value = '{$row['itemID']}'>". $row['itemName'] ."</td>";
                                                
                                                echo "<td><select name='quantity[]' class='val' onchange = 'jvalue($i)'>";
                                                echo "<option selected>Select Quantity</option>";
                                                $i++;
                                               
                                                for($j=0;$j<=5;$j++)  {
                                                    echo "<option value='{$j}'>" . $j . "</option>";
                                                } 
                                            
                                                echo "</select></td>";
                                                echo "<td class='price'>" . $row['amount'] ."</td>";
                                                
                                                
                                                echo "<td class='sub-total'></td>";
                                                echo "</tr>";

                                                echo "<input type='hidden' name='item_id[]' value='{$row['itemID']}'>";
                                                

                                            }
                                            echo "";
                                                                                            
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                            <div class="form-group row pt-5">
                                <label for="disabledTextInput" class="col-3 col-form-label">Total</label>
                                <div class="col-6">
                                    <input name='totalamount' type="text" id="total" class="form-control" >
                                </div>
                            </div>
                        
                        <div class="pt-5">
                            <button type="submit" class="btn btn-primary" name="order">Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php 
        $script = <<<DELIMETER
        <script type='text/javascript'>
            function jvalue(i) {
                var x = document.getElementsByClassName('val')[i-1].value;
                var y = document.getElementsByClassName('price')[i-1].innerHTML;
                document.getElementsByClassName('sub-total')[i-1].innerHTML= x * y;
                
                var t = 0;
                var n = document.getElementsByClassName('sub-total').length;
                for(i=0;i<n;i++){
                    var s = document.getElementsByClassName('sub-total')[i].innerHTML;
                    if(s!= ""){
                    t = t + parseInt(s);}
                }
                
                document.getElementById('total').value = t;
            }
        </script>
DELIMETER;
        echo $script;
        
        ?>
    </body>
</html>