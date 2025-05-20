<?php
include 'database.php';
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT attachment FROM register WHERE id=$id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['attachment']) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($row['attachment']);
    header("Content-Type: $mime");
    // For PDF and images, allow inline preview
    if (strpos($mime, 'pdf') !== false || strpos($mime, 'image') !== false) {
        header("Content-Disposition: inline; filename=attachment.$mime");
    } else {
        header("Content-Disposition: attachment; filename=attachment");
    }
    echo $row['attachment'];
    exit;
}
echo "No attachment found.";
?>
