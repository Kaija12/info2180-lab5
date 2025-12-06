<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $country =  $_GET['country'] ?? '';
    $lookup =  $_GET['lookup'] ?? '';

    if ($lookup === 'cities' && $country !== '') {
        $stmt = $conn->prepare(
            "SELECT cities.name AS city_name, cities.district, cities.population
             FROM cities
             JOIN countries ON cities.country_code = countries.code
             WHERE countries.name LIKE :country
             ORDER BY cities.population DESC"
        );
        $stmt->execute(['country' => "%$country%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // default for lookup countries
        if ($country !== '') {
            $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
            $stmt->execute(['country' => "%$country%"]);
        } else {
            $stmt = $conn->query("SELECT * FROM countries");
        }
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<?php if (count($results) > 0): ?>
<?php if ($lookup === 'cities'): ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>City Name</th>
            <th>District</th>
            <th>Population</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $city): ?>
        <tr>
            <td><?= htmlspecialchars($city['city_name']) ?></td>
            <td><?= htmlspecialchars($city['district']) ?></td>
            <td><?= htmlspecialchars($city['population']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Country Name</th>
            <th>Continent</th>
            <th>Independence Year</th>
            <th>Head of State</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $country_data): ?>
        <tr>
            <td><?= htmlspecialchars($country_data['name']) ?></td>
            <td><?= htmlspecialchars($country_data['continent']) ?></td>
            <td><?= htmlspecialchars($country_data['independence_year']) ?></td>
            <td><?= htmlspecialchars($country_data['head_of_state']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php else: ?>
<p>No results found.</p>
<?php endif; ?>
