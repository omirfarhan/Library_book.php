<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"], input[type="file"] {
            margin: 10px 0;
            padding: 10px;
            width: 300px;
        }
        input[type="submit"] {
            padding: 10px 15px;
        }
    </style>
</head>
<body>

<h2>Upload Form</h2>
<form action="upload_form.php" method="post" enctype="multipart/form-data">
    <label for="name">Name:</label><br>
    <input type="text" name="name" required><br>
    
    <label for="pdf">Upload PDF:</label><br>
    <input type="file" name="pdf" accept=".pdf" required><br>
    
    <label for="image">Upload Image:</label><br>
    <input type="file" name="image" accept="image/*" required><br>
    
    <input type="submit" value="Upload">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library_section";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    
    // Handle PDF upload
    $pdfFileName = basename($_FILES["pdf"]["name"]);
    $pdfTargetDir = "uploads/pdf/";
    $pdfTargetFile = $pdfTargetDir . $pdfFileName;

    // Handle image upload
    $imageFileName = basename($_FILES["image"]["name"]);
    $imageTargetDir = "uploads/images/";
    $imageTargetFile = $imageTargetDir . $imageFileName;

    // Create directories if not exist
    if (!is_dir($pdfTargetDir)) {
        mkdir($pdfTargetDir, 0755, true);
    }
    if (!is_dir($imageTargetDir)) {
        mkdir($imageTargetDir, 0755, true);
    }

    // Move uploaded files to the respective directories
    if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfTargetFile) && move_uploaded_file($_FILES["image"]["tmp_name"], $imageTargetFile)) {
        $sql = "INSERT INTO books (book_name, book_pdf, book_picture) VALUES ('$name', '$pdfFileName', '$imageFileName')";

        if ($conn->query($sql) === TRUE) {
            echo "Upload successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your files.";
    }

    $conn->close();
}
?>

</body>
</html>
