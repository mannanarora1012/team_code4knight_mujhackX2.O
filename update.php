<?php
$host = 'localhost';
$db = 'scooter';
$user = 'root'; 
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num = mysqli_real_escape_string($conn, $_POST['num']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $gps_latitude = mysqli_real_escape_string($conn, $_POST['gps_latitude']);
    $gps_longitude = mysqli_real_escape_string($conn, $_POST['gps_longitude']);
    
    // Assuming your table 'scooter' has columns: 'id', 'location', 'status', 'gps_latitude', 'gps_longitude'
    $sql = "INSERT INTO `scooter`(`id`, `location`, `status`, `gps_latitude`, `gps_longitude`) 
            VALUES ('$num', '$location', '$status', '$gps_latitude', '$gps_longitude')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
