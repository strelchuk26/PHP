<?php
include($_SERVER["DOCUMENT_ROOT"] . "/config/connection_database.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "Record deleted successfully";
        } else {
            echo "No record found to delete";
        }
    } 
    else {
        echo '<script>
                if(confirm("Are you sure you want to delete this record?")) {
                    window.location.href = "/delete.php?id=' . $id . '&confirmed=true";
                } else {
                    window.location.href = "/"; // Redirect to homepage or another URL
                }
              </script>';
    }
} else {
    echo "Invalid request";
}
?>