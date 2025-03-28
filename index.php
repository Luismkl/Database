<?php

//Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database_user";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Hinzufügen eines Namens (Prepared Statements)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $prep = $conn->prepare("INSERT INTO names (name) VALUES (?)");
    $prep->bind_param("s", $name);
    $prep->execute();
}

// Löschen eines Namens (Prepared Statements)
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $prep = $conn->prepare("DELETE FROM names WHERE id = ?"); 
    $prep->bind_param("i", $id);
    $prep->execute();
}

$result = $conn->query("SELECT * FROM names");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Namensverwaltung</title>
</head>
<body>
    <h1>Namensverwaltung</h1>
    
    <form method="post" action="">
        <input type="text" name="name" required placeholder = "Name eingeben">
        <button type="submit">Hinzufügen</button>
    </form>

    <h2>Gespeicherte Namen:</h2>
    <ul>
        <?php while ($row = $result -> fetch_assoc()):?>
            <li>
                <?php echo htmlspecialchars($row['name']); ?>
                <a href="?delete_id=<?php echo $row['id'];?>">X</a>
            </li>
        <?php endwhile;?>
    </ul>
</body>
</html>

<?php $conn->close();?>
