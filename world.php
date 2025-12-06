<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    // connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // check if the 'country' GET parameter exists
    if (isset($_GET['country']) && !empty($_GET['country'])) {
        $country = $_GET['country'];

        // prepare the query 
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
        $stmt->execute(['country' => "%$country%"]);
    } else {
        //return all countries for default
        $stmt = $conn->query("SELECT * FROM countries");
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php if (count($results) > 0): ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Continent</th>
            <th>Independence </th>
            <th>Head of State</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['continent']) ?></td>
            <td><?= htmlspecialchars($row['independence_year']) ?></td>
            <td><?= htmlspecialchars($row['head_of_state']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No countries found.</p>
<?php endif; ?>

