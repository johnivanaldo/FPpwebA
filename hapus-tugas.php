<?php
    // Cek jika user belum log in
    session_start();

    if (!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != true) {
        header("location: login.php");
        exit;
    }

    // Proses upload
    require_once "config.php";

    // Query submission
    $sql = "SELECT id, url FROM submissions_assignments WHERE assignment_id = ? AND student_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $param_assignment_id, $param_student_id);
        $param_assignment_id = $_GET["id"];
        $param_student_id = $_SESSION["id"];

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $submission_id, $submission_url);
                mysqli_stmt_fetch($stmt);
            }
        }

        mysqli_stmt_close($stmt);
    }

    // Hapus submission
    unlink($submission_url);

    // Hapus dari table
    $sql = "DELETE FROM submissions_assignments WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $submission_id;

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect ke halaman tugas.php
        header("location: tugas.php");
        exit;
    }
?>