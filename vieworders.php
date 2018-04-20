<!DOCTYPE html>
<html>
<head>
    <title>Bob's Auto Parts - View Orders</title>
</head>
<body>
<?php

// change to true when running in production
$production_mode = false;

try {
    // set up the database connection
    // set up for using PDO
    $user = 'root';
    $pass = '';
    $host = 'localhost';
    $db_name = 'bobs';

    // set up DSN
    $dsn = "mysql:host=$host;dbname=$db_name";

    $db = new PDO($dsn, $user, $pass);

    // Make sure that PDO will throw exceptions if there is an error.
    // see http://php.net/manual/en/pdo.error-handling.php
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // YOUR CODE HERE: SELECT all the orders from the database.
    // Make sure to join the orders table with the how_find_bobs table.

    //execute select all orders
    $stmt = $db->prepare("SELECT o.date, o.tireqty, o.oilqty, o.sparkqty, h.how, o.notes from orders o join how_found_bob h ON o.HowFindBobId = h.id");
    $stmt->execute();

    define('TIREPRICE', 100);
    define('OILPRICE', 10);
    define('SPARKPRICE', 4);
    $taxrate = 0.10;  // local sales tax is 10%

    // YOUR CODE: Modify the while loop below to loop through a SQL SELECT result
    while($result = $stmt->fetch(PDO::FETCH_OBJ)) {

        $totalamount = $result->tireqty * TIREPRICE
            + $result->oilqty * OILPRICE
            + $result->sparkqty * SPARKPRICE;

        $totalamount = $totalamount * (1 + $taxrate);

        // print out the order for this line
        echo "Date: $result->date<br/>\n";
        echo "Tire Qty: $result->tireqty<br/>\n";
        echo "Oil Qty: $result->oilqty<br/>\n";
        echo "Spark Qty: $result->sparkqty<br/>\n";
        echo "Total Cost: $totalamount<br/>\n";
        echo "How did you find Bob's?:" . $result->how . "<br/>\n";
        echo "Notes: $result->notes<br/>\n<br/>\n";
    }

    // disconnect from the database
    $db = NULL;
} catch (PDOException $exception) {
    if ($production_mode === false) {
        echo "PDO Error: " . $exception->getMessage();
        echo "<br/>Any extended information: " . $exception->errorInfo . "\n";
    } else {
        echo "Error: There was an internal error during your order.  Please contact support.<br />\n";
    }
} catch (Exception $exception) {
    echo "Sorry, there was a problem with your order.<br />\n";
    echo "Error: " . $exception->getMessage() . "\n";
}

?>
</body>
</html>
