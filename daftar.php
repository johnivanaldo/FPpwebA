<?php
    // Cek kalo user sudah log in
    session_start();

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
        header("location: dashboard.php");
        exit;
    }

    // Proses pendaftaran
    require_once "config.php";

    // Variables
    $password = $confirm_password = "";
    $error_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Cek jika email sudah dipakai
        $sql = "SELECT id FROM students WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $error_msg = "Email sudah dipakai, silahkan menggunakan email lain.";
                }
            } else {
                echo "Terjadi kesalahan, silahkan dicoba lagi.";
            }

            mysqli_stmt_close($stmt);
        }

        // Validasi password
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if ($password !== $confirm_password) {
            $error_msg = "Password tidak sama!";
        }

        // Insert ke table
        if (empty($error_msg)) {
            $sql = "INSERT INTO students (nama, email, alamat, jenis_kelamin, agama, sekolah_asal, password) VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssss", $param_nama, $param_email, $param_alamat, $param_jenis_kelamin, $param_agama, $param_sekolah_asal, $param_password);

                $param_nama = trim($_POST["nama"]);
                $param_email = trim($_POST["email"]);
                $param_alamat = trim($_POST["alamat"]);
                $param_jenis_kelamin = trim($_POST["jenis_kelamin"]);
                $param_agama = trim($_POST["agama"]);
                $param_sekolah_asal = trim($_POST["sekolah_asal"]);
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);

                // Redirect ke halaman index
                header("location: index.php?status=daftar_sukses");
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>AyoSekolah | Daftar</title>
    <style type="text/css">
        body {
            /*padding-left: 4rem;*/
            padding-top: 6rem;
            padding-bottom: 5rem;
            background-color: #ccf8ff;
            font-family: "Times New Roman", Times, serif;
        }
        .formulir{
            border-radius:20px;
            padding : 3rem 5rem;
            margin-top: 4rem;
            margin-left:25rem;
            margin-right:25rem;
            background-color: #d9fcff;
        }
        .button {
            background-color: white; 
            color: black; 
            border: 2px solid #FFAFAF;
            padding: 10px 28px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius:10px; */
        }
        
        .button:hover {
            background-color: #6af77d;
            color: white;
        }
    </style>
</head>

<body>
    <header style="text-align: center;">
        <h3>Formulir Pendaftaran Siswa Baru</h3>
    </header>

    <div class="formulir">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <fieldset>
                <p>
                    <label for="nama">Nama Lengkap: </label>
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </p>

                <p>
                    <label for="nama">Email: </label>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                </p>

                <p>
                    <label for="alamat">Alamat: </label>
                    <textarea name="alamat" required></textarea>
                </p>

                <p>
                    <label for="jenis_kelamin">Jenis Kelamin: </label>
                    <label><input type="radio" name="jenis_kelamin" value="laki-laki" required> Laki-laki</label>
                    <label><input type="radio" name="jenis_kelamin" value="perempuan" required> Perempuan</label>
                </p>

                <p>
                    <label for="agama">Agama: </label>
                    <select name="agama" required>
                        <option>Islam</option>
                        <option>Kristen Protestan</option>
                        <option>Katolik</option>
                        <option>Hindu</option>
                        <option>Buddha</option>
                        <option>Khonghucu</option>
                        <option>Atheis</option>
                    </select>
                </p>

                <p>
                    <label for="sekolah_asal">Sekolah Asal: </label>
                    <input type="text" name="sekolah_asal" placeholder="Nama Sekolah" required>
                </p>

                <p>
                    <label for="password">Password: </label>
                    <input type="password" name="password" placeholder="Password" required>
                </p>

                <p>
                    <label for="password">Konfirmasi Password: </label>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                </p>

                <p>
                    <input class="button" type="submit" value="Daftar" name="daftar">
                </p>
            </fieldset>
        </form>

        <p style="color: red;"><?php if (isset($error_msg)) { echo $error_msg; } ?></p>
    </div>

    </body>
</html>
