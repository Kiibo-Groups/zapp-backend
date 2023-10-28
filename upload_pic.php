<?php
    header('Access-Control-Allow-Origin: *');
    header("Content-type:multipart/form-data");
    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Validamos la ruta
    $target_path = "public/upload/tickets/";
    if (!file_exists("public/upload/tickets/")) {
      mkdir("public/upload/ticketstickets/", 0777, true);
    }

    // Recibimos nombre del archivo y lo asociamos a la ruta
    $target_path = $target_path .basename($_FILES['file']['name']);
    // Movemos el archivo blob a la ruta especificada
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        echo true; // Retornamos valor
    } else {
        echo false; // Retornamos valor
    }
   
?>