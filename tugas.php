<?php
    // Cek jika user belum log in
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
        header("location: login.php");
        exit;
    }

    require_once "config.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Pendaftaran Siswa Baru | Kejuaraan Coding</title>
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
            border-radius:10px;
        }
        
        .button:hover {
            background-color: #6af77d;
            color: white;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header style="text-align: center">
        <h3>Tugas Anda</h3>
    </header>

    <div class="tabel">
        <br>

        <table class="table table-striped" border="1">
            <thead>
                <tr>
                    <th>Nama Tugas</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    // Query semua tugas yang ada
                    $sql = "SELECT a.id, a.nama, sa.url FROM assignments a LEFT JOIN submissions_assignments sa ON sa.assignment_id = a.id AND sa.student_id = ?";

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $param_id);
                        $param_id = $_SESSION["id"];

                        if (mysqli_stmt_execute($stmt)) {
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt, $assignment_id, $assignment_name, $assignment_url);

                            while (mysqli_stmt_fetch($stmt)) {
                                echo "<tr>";
                                echo "<td>" . $assignment_name . "</td>";

                                if (empty($assignment_url)) {
                                    echo "<td>Anda belum mengumpulkan tugas ini</td>";
                                    echo "<td><a href='/upload-tugas.php?id=" . $assignment_id . "'>Upload tugas</a></td>";
                                } else {
                                    echo "<td>Anda sudah mengumpulkan tugas ini</td>";
                                    echo "<td><a href='/hapus-tugas.php?id=" . $assignment_id . "'>Hapus tugas</a></td>";
                                }

                                echo "</tr>";
                            }
                        } else {
                            echo "Terjadi kesalahan, silahkan dicoba lagi.";
                        }

                        mysqli_stmt_close($stmt);
                    }
                ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php">
        <button class="button">Dashboard</button>
    </a>
</body>
</html>
