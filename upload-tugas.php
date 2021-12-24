<?php
    // Cek jika user belum log in
    session_start();

    if (!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != true) {
        header("location: login.php");
        exit;
    }

    // Proses upload
    require_once "config.php";

    // Variables
    $error_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Upload file tugas
        if ($_POST["upload"]) {
            // Info file
            $file_name = $_FILES["file"]["name"];
            $file_tmp_name = $_FILES["file"]["tmp_name"];
            $file_size = filesize($file_tmp_name);
            $file_name_tokenized = explode(".", $file_name);
            $file_extension = end($file_name_tokenized);

            if ($file_size > 524288) { // File size melebihi 500 KB
                $error_msg = "Ukuran file melebihi 500 KB!";
            }

            if ($file_extension !== "pdf") {
                $error_msg = "Tipe file harus PDF!";
            }

            if (empty($error_msg)) {
                // Name file dengan timestamp
                $timestamped_file_name = "assignments/" . time() . $file_name;

                // Upload file ke folder assignments
                move_uploaded_file($file_tmp_name, $timestamped_file_name);

                // Insert ke table submissions assignments
                $sql = "INSERT INTO submissions_assignments (assignment_id, student_id, url) VALUES (?, ?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "iis", $param_assignment_id, $param_student_id, $param_url);
                    $param_assignment_id = $_POST["assignment_id"];
                    $param_student_id = $_SESSION["id"];
                    $param_url = $timestamped_file_name;

                    mysqli_stmt_execute($stmt);

                    mysqli_stmt_close($stmt);

                    // Redirect kembali ke halaman tugas
                    header("location: tugas.php");
                    exit;
                }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <fieldset>
                <p>
                    <label for="nama">Pilih File: </label>
                    <input type="file" name="file" required>
                </p>

                <input type="hidden" name="assignment_id" value="<?php echo $_GET["id"]; ?>">

                <p>
                    <input class="button" type="submit" value="Upload Tugas" name="upload">
                </p>
            </fieldset>
        </form>

        <p style="color: red;"><?php if (isset($error_msg)) { echo $error_msg; } ?></p>
    </div>

    </body>
</html>
