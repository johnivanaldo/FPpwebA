<?php
    // Cek jika user belum log in
    session_start();

    if (!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != true) {
        header("location: login.php");
        exit;
    }

    // Proses pendaftaran
    require_once "config.php";

    // Variables
    $error_msg = "";

    // Get data
    $sql = "SELECT nama, email, alamat, jenis_kelamin, agama, sekolah_asal FROM students WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $_SESSION["id"];

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $nama, $email, $alamat, $jenis_kelamin, $agama, $sekolah_asal);
                mysqli_stmt_fetch($stmt);
            }
        }

        mysqli_stmt_close($stmt);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Cek jika email sudah dipakai jika email diedit
        if (trim($_POST["email"]) !== $email) {
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
        }

        // Update table
        if (empty($error_msg)) {
            $sql = "UPDATE students SET nama = ?, email = ?, alamat = ?, jenis_kelamin = ?, agama = ?, sekolah_asal = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssssss", $param_nama, $param_email, $param_alamat, $param_jenis_kelamin, $param_agama, $param_sekolah_asal, $param_id);

                $param_nama = trim($_POST["nama"]);
                $param_email = trim($_POST["email"]);
                $param_alamat = trim($_POST["alamat"]);
                $param_jenis_kelamin = trim($_POST["jenis_kelamin"]);
                $param_agama = trim($_POST["agama"]);
                $param_sekolah_asal = trim($_POST["sekolah_asal"]);
                $param_id = $_SESSION["id"];

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);

                // Redirect ke halaman index
                header("location: dashboard.php?status=edit_sukses");
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
    <title>AyoSekolah | Edit Data</title>
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
                    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo $nama; ?>" required>
                </p>

                <p>
                    <label for="nama">Email: </label>
                    <input type="email" name="email" placeholder="Alamat Email" value="<?php echo $email; ?>" required>
                </p>

                <p>
                    <label for="alamat">Alamat: </label>
                    <textarea name="alamat" required><?php echo $alamat; ?></textarea>
                </p>

                <p>
                    <label for="jenis_kelamin">Jenis Kelamin: </label>
                    <label><input type="radio" name="jenis_kelamin" value="laki-laki" required <?php if ($jenis_kelamin === "laki-laki") { echo "checked"; } ?>> Laki-laki</label>
                    <label><input type="radio" name="jenis_kelamin" value="perempuan" required <?php if ($jenis_kelamin === "perempuan") { echo "checked"; } ?>> Perempuan</label>
                </p>

                <p>
                    <label for="agama">Agama: </label>
                    <select name="agama" required>
                        <option <?php if ($agama === "Islam") { echo "selected"; } ?>>Islam</option>
                        <option <?php if ($agama === "Kristen Protestan") { echo "selected"; } ?>>Kristen Protestan</option>
                        <option <?php if ($agama === "Katolik") { echo "selected"; } ?>>Katolik</option>
                        <option <?php if ($agama === "Hindu") { echo "selected"; } ?>>Hindu</option>
                        <option <?php if ($agama === "Buddha") { echo "selected"; } ?>>Buddha</option>
                        <option <?php if ($agama === "Khonghucu") { echo "selected"; } ?>>Khonghucu</option>
                        <option <?php if ($agama === "Atheis") { echo "selected"; } ?>>Atheis</option>
                    </select>
                </p>

                <p>
                    <label for="sekolah_asal">Sekolah Asal: </label>
                    <input type="text" name="sekolah_asal" placeholder="Nama Sekolah" value="<?php echo $sekolah_asal; ?>" required>
                </p>

                <p>
                    <input class="button" type="submit" value="Perbarui">
                </p>
            </fieldset>
        </form>

        <a href="dashboard.php">
            <button class="button">Dashboard</button>
        </a>

        <p style="color: red;"><?php if (isset($error_msg)) { echo $error_msg; } ?></p>
    </div>

    </body>
</html>
