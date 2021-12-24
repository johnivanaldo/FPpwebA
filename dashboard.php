<?php
    // Cek jika user belum log in
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>AyoSekolah | Dashboard</title>

    <style type="text/css">
        body {
            /*padding-left: 4rem;*/
            padding-top: 6rem;
            padding-bottom: 5rem;
            background-color: #ccf8ff;
            font-family: "Times New Roman", Times, serif;
        }

        .float-container {
            padding: 20px;
        }
        .menu{
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
        <h1>AyoSekolah</h1>
    </header>
    
    <div class="menu">
        <h4 style="text-align: center;">Menu</h4>

        <hr>

        <nav style="text-align: center;">
            <a href="tugas.php">
                <button class="button">Lihat Tugas</button>
            </a>

            <a href="nilai.php">
                <button class="button">Lihat Nilai</button>
            </a>

            <a href="edit-data.php">
                <button class="button">Edit Data</button>
            </a>

            <a href="logout.php">
                <button class="button">Logout</button>
            </a>
        </nav>
    </div>
    
    </body>
</html>
