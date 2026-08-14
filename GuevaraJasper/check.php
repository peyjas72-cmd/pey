```php
<?php
session_start();


$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$database = "jasperbsis3c";

$conn = new mysqli($host, $dbUser, $dbPassword, $database);


if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}


$username = trim($_POST['username'] ?? '');
$loginPassword = trim($_POST['password'] ?? '');


if ($username === '' || $loginPassword === '') {
    echo "<script>
        alert('Please enter your username and password.');
        window.location='index.php';
    </script>";
    exit();
}


$sql = "SELECT 
            doctorID,
            firstname,
            lastname,
            specialty,
            departmentID,
            username,
            password
        FROM doctor
        WHERE username = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $doctor = $result->fetch_assoc();

   
    if ($loginPassword === $doctor['password']) {

       
        $_SESSION['username'] = $doctor['username'];
        $_SESSION['doctorID'] = $doctor['doctorID'];
        $_SESSION['firstname'] = $doctor['firstname'];
        $_SESSION['lastname'] = $doctor['lastname'];
        $_SESSION['specialty'] = $doctor['specialty'];
        $_SESSION['departmentID'] = $doctor['departmentID'];

        
        header("Location: home.php");
        exit();

    } else {

        
        echo "<script>
            alert('Invalid Username or Password!');
            window.location='index.php';
        </script>";
        exit();
    }

} else {

    
    echo "<script>
        alert('Invalid Username or Password!');
        window.location='index.php';
    </script>";
    exit();
}

$stmt->close();
$conn->close();
?>
```
