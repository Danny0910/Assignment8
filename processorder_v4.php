<?php
// change to true when running in production
$production_mode = false;

// create short variable names
$tireqty = (int) $_POST['tireqty'];
$oilqty = (int) $_POST['oilqty'];
$sparkqty = (int) $_POST['sparkqty'];
$notes = htmlspecialchars($_POST['notes']);
$how_find_bob = htmlspecialchars($_POST['find']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bob's Auto Parts - Order Results</title>
</head>
<body>
<h1>Bob's Auto Parts</h1>
<h2>Order Results</h2>
<?php
try {
    // check for errors in the submitted data first
    if ($tireqty < 0 || $oilqty < 0 || $sparkqty < 0) {
        throw new Exception("All quantities must be 0 or greater.");
    }

    if (($tireqty + $oilqty + $sparkqty) == 0) {
        throw new Exception("You must order at least 1 of something.");
    }

    switch ($how_find_bob) {
        case 'a':
            $how_find_bobs_string = 'I\'m a regular customer';
            break;
        case 'b':
            $how_find_bobs_string = 'TV advertising';
            break;
        case 'c':
            $how_find_bobs_string = 'Phone directory';
            break;
        case 'd':
            $how_find_bobs_string = 'Word of mouth';
            break;
        default:
            throw new Exception("An invalid value was sent for how you found Bob's.");
    }

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

    // Follow the insert example shown here:
    //   http://php.net/manual/en/pdo.prepared-statements.php
    // Use this variable for the order date/time to be inserted into Mysql:
    $order_datetime = date("Y-m-d H:i:s");
    // YOUR CODE HERE
    //prepare statement
    $stmt = $db->prepare("INSERT INTO ORDERS (Date, TireQty, OilQty, SparkQty, HowFindBobId, Notes) VALUES (:orderdate,:tireqty,:oilqty,:sparkqty,:howfind,:notes)");
    //bind variables to placeholders
    $stmt->bindParam(":orderdate",$order_datetime);
    $stmt->bindParam(":tireqty",$tireqty);
    $stmt->bindParam(":oilqty",$oilqty);
    $stmt->bindParam(":sparkqty",$sparkqty);
    $stmt->bindParam(":howfind",$how_find_bob);
    $stmt->bindParam(":notes",$notes);

    //execute insert
    $stmt->execute();


    // show the user the order results
    // for info on $db->lastInsertId() see http://php.net/manual/en/pdo.lastinsertid.php
    echo "<p>Order number:" . $db->lastInsertId();
    echo "</p>\n<p>Order processed at ";
    echo date('H:i, jS F Y');
    echo "</p>\n";

    echo '<p>Your order is as follows: </p>';

    echo $tireqty . ' tires<br />';
    echo $oilqty . ' bottles of oil<br />';
    echo $sparkqty . ' spark plugs<br />';

    $totalqty = 0;
    $totalqty = $tireqty + $oilqty + $sparkqty;
    echo "<p>Items ordered: " . $totalqty . "<br />";
    $totalamount = 0.00;

    define('TIREPRICE', 100);
    define('OILPRICE', 10);
    define('SPARKPRICE', 4);

    $totalamount = $tireqty * TIREPRICE
        + $oilqty * OILPRICE
        + $sparkqty * SPARKPRICE;

    echo "Subtotal: $" . number_format($totalamount, 2) . "<br />";

    $taxrate = 0.10;  // local sales tax is 10%
    $totalamount = $totalamount * (1 + $taxrate);
    echo "Total including tax: $" . number_format($totalamount, 2) . "</p>";
    echo "You found Bob's via: " . $how_find_bobs_string . "<br />\n";
    echo "Order notes: " . $notes . "<br />\n";


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