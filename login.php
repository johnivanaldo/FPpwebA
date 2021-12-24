<?php
    // Cek kalo user sudah log in
    session_start();

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
        header("location: dashboard.php");
        exit;
    }

    require_once "config.php";

    $password = "";
    $error_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Cek jika email terdaftar
        $sql = "SELECT id, password FROM students WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 0) {
                    $error_msg = "Email tidak terdaftar!";
                } else {
                    // Simpan password untuk validasi
                    mysqli_stmt_bind_result($stmt, $id, $check_password);
                    mysqli_stmt_fetch($stmt);
                }
            } else {
                echo "Terjadi kesalahan, silahkan dicoba lagi.";
            }

            mysqli_stmt_close($stmt);
        }

        // Validasi password jika email terdaftar
        if (empty($error_msg)) {
            $password = trim($_POST["password"]);
            if (password_verify($password, $check_password)) {
                // Buat session baru
                session_start();

                // Session variables
                $_SESSION["logged_in"] = true;
                $_SESSION["id"] = $id;

                // Redirect ke dashboard
                header("location: dashboard.php");
                exit;
            } else {
                $error_msg = "Password salah!";
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
    <title>AyoSekolah | Login</title>

    <style type="text/css">
        body {
            /*padding-left: 4rem;*/
            padding-top: 6rem;
            padding-bottom: 5rem;
            background-color: #ccf8ff;
            font-family: "Times New Roman", Times, serif;
        }

        .formulir {
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
                <label for="email">Email: </label>
                <input type="email" name="email" placeholder="Alamat Email" required>
            </p>

            <p>
                <label for="password">Password: </label>
                <input type="password" name="password" placeholder="Password" required>
            </p>
        
            <p>
                <input class="button" type="submit" value="Login" name="login" />
            </p>

            </fieldset>
        </form>

        <p style="color: red;"><?php if (isset($error_msg)) { echo $error_msg; } ?></p>
    </div>

    </body>
</html>
