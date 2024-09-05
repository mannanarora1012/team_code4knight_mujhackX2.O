<?php
$host = 'localhost';
$db = 'scooter';
$user = 'root'; 
$pass = '';
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch scooter data for dashboard
$sql_fetch = "SELECT * FROM scooter";
$result_fetch = mysqli_query($conn, $sql_fetch);

// Fetch summary statistics
$total_scooters = mysqli_num_rows($result_fetch);

$status_count = [
    'Manufacturer' => 0,
    'BIKESETU Yard' => 0,
    'Franchisee Store' => 0,
    'Customer' => 0,
    'Ownership with Customer' => 0
];

while ($row = mysqli_fetch_assoc($result_fetch)) {
    if (array_key_exists($row['status'], $status_count)) {
        $status_count[$row['status']]++;
    }
}

// Reset result set for table rendering
mysqli_data_seek($result_fetch, 0);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(bg.jpeg);
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
        }
        .dashboard {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }
        .dashboard div {
            background-color: whitesmoke;   
            box-shadow: 0 0 15px rgb(19, 245, 3);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 50%;
            text-align: center;
        }
        .dashboard div h3 {
            margin: 10px 0;
            color: whitesmoke;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #009a05;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h2 style="text-align: center; color: whitesmoke;">Scooter Dashboard</h2>

    <!-- Dashboard section -->
    <div class="dashboard">
        <div>
            <h3>Scooters by Status</h3>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Scooter Data Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Status</th>
            <th>GPS Latitude</th>
            <th>GPS Longitude</th>
        </tr>
        <?php
        if (mysqli_num_rows($result_fetch) > 0) {
            while($row = mysqli_fetch_assoc($result_fetch)) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["location"] . "</td>
                        <td>" . $row["status"] . "</td>
                        <td>" . $row["gps_latitude"] . "</td>
                        <td>" . $row["gps_longitude"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No data available</td></tr>";
        }
        ?>
    </table>
</div>

<script>
    // Prepare data for the chart
    var ctx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Manufacturer', 'BIKESETU Yard', 'Franchisee Store', 'Customer', 'Ownership with Customer'],
            datasets: [{
                label: 'Scooters by Status',
                data: [
                    <?php echo $status_count['Manufacturer']; ?>, 
                    <?php echo $status_count['BIKESETU Yard']; ?>,
                    <?php echo $status_count['Franchisee Store']; ?>,
                    <?php echo $status_count['Customer']; ?>,
                    <?php echo $status_count['Ownership with Customer']; ?>
                ],
                backgroundColor: [
                    '#ff6384',
                    '#36a2eb',
                    '#ffce56',
                    '#4caf50',
                    '#8e44ad'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

</body>
</html>

<?php
mysqli_close($conn);
?>