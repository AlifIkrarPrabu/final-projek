<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'member');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data pengguna dari database berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM members WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            echo "Login berhasil! Selamat datang, " . $user['username'];
            // Di sini Anda bisa mengatur session atau redirect ke halaman lain
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Username tidak ditemukan!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Anggota Klub Renang</title>
</head>
<body>
    <h2>Login Anggota</h2>
    <form method="post" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
