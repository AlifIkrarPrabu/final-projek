<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'member');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password untuk keamanan
    $email = $_POST['email'];

    // Cek apakah username atau email sudah terdaftar
    $check = $conn->prepare("SELECT * FROM members WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Username atau email sudah digunakan!";
    } else {
        // Masukkan data ke database
        $stmt = $conn->prepare("INSERT INTO members (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "Pendaftaran berhasil! Silakan <a href='login.php'>Login</a>.";
        } else {
            echo "Pendaftaran gagal. Silakan coba lagi.";
        }

        $stmt->close();
    }
    
    $check->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up Anggota Klub Renang</title>
</head>
<body>
    <h2>Daftar Anggota Baru</h2>
    <form method="post" action="signup.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Daftar">
    </form>
</body>
</html>
