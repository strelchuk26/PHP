<?php
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST["name"]) || $_FILES['image']['name'] == "") {
        exit("ENTER ALL DATA!");
    }
    else {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $dir="/images/";
        $image_name = uniqid().".jpg";
        $destination = $_SERVER["DOCUMENT_ROOT"].$dir.$image_name;
        move_uploaded_file($imageTmpName, $destination);
        include($_SERVER["DOCUMENT_ROOT"] . "/config/connection_database.php");
        $sql = "INSERT INTO categories (name, image, description) VALUES (:name, :image, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image_name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        header("Location: /");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати категорію</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="./css/create-category.css">
</head>
<body>

    <?php include ($_SERVER["DOCUMENT_ROOT"]."/_header.php"); ?>

    <main>
        <div class="container">
            <h1 class="text-center">Додати категорію</h1>
            <form class="offset-md-3 col-md-6" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Назва</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>

                <div class="mb-3 d-flex">
                    <div class="image">
                        <button type="button" id="imageBtn">
                            <div id="imagePreview"></div>
                        </button>
                        <input type="file" id="fileInput" style="display: none;">
                    </div>
                    <div>
                        <label for="image" class="form-label">Фото</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Опис</label>
                    <textarea rows="5" class="form-control" id="description" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Додати</button>
            </form>
        </div>
    </main>

    <style>
        #imagePreview {
            max-width: 200px;
            margin-top: 10px;
        }
        #imagePreview img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>

    <script>
        document.getElementById('image').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    document.getElementById('imagePreview').innerHTML = '';
                    document.getElementById('imagePreview').appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
        document.querySelector("#imageBtn").addEventListener('click', function() {
            document.getElementById('image').click();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>