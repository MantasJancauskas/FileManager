<?php
session_start();

if (!isset($_SESSION['UserData']['Username'])) {
    header("location:login.php");
    exit;
}

print('<h2 class="logoutText">Eureka! You are logged in.<a href="logout.php" style="color:#fff">Press me</a> to Logout.</h2>');

//Greeter with user name
isset($_SESSION['UserData']['Username']) and ($_SESSION['UserData']['Username'] == true)
    ? print '<div class="main"><header><div class="container"><h3 style="color:#fff; text-align:center;"> Welcome, ' . $_SESSION['UserData']['Username'] . '!</h3>'
    : null;


print('<br>');

print('<h1 style="color:#fff; text-align:center;"> This is your File Browser</h1>');

// creating a new folder
$destiny = isset($_GET["destination"]) ? './' . $_GET["destination"] : './';
if (isset($_POST['createfolder'])) {
    $foldername = ($_POST['createfolder']);
    if (!file_exists($destiny . $foldername)) {
        mkdir($destiny . "/" . $foldername);
        print('<p class="warning">You created a new directory!</p>');
        header("refresh: 2");
    } else if ($foldername) {
        print('<p class="warning">This folder name already exists!</p>');
        header('refresh: 2');
    } else {
        print('<p class="warning">Failed to create folder! Please write a new folder name</p>');
        header("refrsh: 2");
    }
}

//upload an image
if (isset($_FILES['image'])) {
    $errors = "";
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $exploded = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($exploded));
    $extensions = ["jpeg", "jpg", "png"];
    if (in_array($file_ext, $extensions) === false) {
        $errors = '<p class="warningUpl">File format is not allowed, please choose a JPEG or PNG file.</p>';
        header("refresh:2");
    }
    if ($file_size > 2097152) {
        $errors = '<p class="warningUpl">File size must be smaller than 2 MB</p>';
        header("refresh:2");
    }
    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, $destiny . $file_name);
        echo '<p class="warningUpl">Success!Image uploaded</p>';
        header("refresh:2");
    } else {
        print_r($errors);
    }
}
print(' 
    <div class="">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="createField">
                <input type="text" name="createfolder" class="createInput " />
                <input type="submit" name="submit" value="Create folder" class="btn btn-danger" />
            </div>
        </form>

        <form action="" method="POST" enctype="multipart/form-data">
                <div class="uploadField">
                    <input type="file" name="image" class="uploadInput" />
                    <input type="submit" value="Upload image" class="btn btn-danger" />
                </div>
        </form>
    </div>
        ');

// file downloader
if (isset($_POST['download'])) {
    $file = './' . $_GET['destination'] . $_POST['download'];
    $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));
    ob_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileToDownloadEscaped));
    ob_end_flush();
    readfile($fileToDownloadEscaped);
    exit;
}

// file deleter
$deleteError = "";

if (isset($_POST['delete']) && $_POST['delete'] !== 'index.php' && $_POST['delete'] !== 'README.md' && $_POST['delete'] !== 'test11.bmp' && $_POST['delete'] !== 'test12.bmp') {
    $deleteError = 'This file can not be deleted!';
    $fileDelete = './' . $destiny  . $_POST['delete'];
    $fileDeleteEscaped = str_replace("&nbsp;", " ", htmlentities($fileDelete, 0, 'utf-8'));

    if (is_file($fileDelete)) {
        if (file_exists($fileDelete)) {
            unlink($fileDelete);
            header('refresh:1');
            print('<p class="warningDlt">File is deleted</p>');
        } else {
            header('refresh:1');
            echo '<p class="warningDlt">File is not deleted!</p>';
        }
    }
}
if (isset($_POST['delete']) && ($_POST['delete'] === 'index.php' || $_POST['delete'] === 'README.md' || $_POST['delete'] !== 'test11.bmp' || $_POST['delete'] !== 'test12.bmp')) {
    $deleteError = 'This file can not be deleted!';
}

// folder and files list
$destiny = isset($_GET["destination"]) ? './' . $_GET["destination"] : './';
$files_and_dirs = scandir($destiny);


print('<table>
            <th>Type</th>
            <th>Name</th>
            <th>Actions</th>'
);
foreach ($files_and_dirs as $fnd) {
    if ($fnd != "." && $fnd != ".." && $fnd != ".git") {

        print('<tr>');
        print('<td>' . (is_dir($destiny . $fnd) ? "Directory" : "File") . '</td>');
        print('<td>' . (is_dir($destiny . $fnd)
            ? '<img src= "./images/folder.png" class="dir" />' .
            '<a href="' . (isset($_GET['destination'])
                ? $_SERVER['REQUEST_URI'] . $fnd . '/'
                : $_SERVER['REQUEST_URI']  . '?destination=' . $fnd . '/') . '">' . $fnd . '</a>'
            : '<img src= "./images/file.jpg" class="file" />' . $fnd)
            . '</td>');

        print('<td class="md-3">' . (is_dir($destiny . $fnd)
            ? ''
            : ($fnd === 'index.php' || $fnd === 'login.php' || $fnd === 'logout.php' || $fnd === 'Readme.md' || $fnd === 'file.jpg' || $fnd === 'folder.png' || $fnd === 'login.css' || $fnd === 'style.css'
                ? '<form class="former" style="display: inline-block" action="" method="post">
                <div class="button">
                    <input type="hidden" name="download" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                    <input class=" btn btn-danger" type="submit" value="Download">
                    </div>
                </form>'
                : '<form class="former" style="display: inline-block" action="" method="post">
                <div class="button">
                   <input type="hidden" name="download" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                   <input class="btn btn-danger" type="submit" value="Download">
                   </div>
                </form>
                <form class="former" style="display: inline-block" action="" method="post">
                    <div class="button">
                        <input type="hidden" name="delete" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                        <input class="btn btn-danger" type="submit" value="Delete">
                    </div>
                </form>'
            )
        )
            . '</form></td>');
        print('</tr>');
    }
}

print('</table>');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <style>
        <?php include 'css/style.css'; ?>
    </style>
    <title>File System Browser</title>

</head>

<body>

    <nav style="display: flex; justify-content: space-evenly">

        <a class="btn btn-danger" href="<?php

                                        print('./')
                                        ?>" class="navButton">Home!</a>

        <a class="btn btn-danger" href="<?php $q_string = explode('/', rtrim($_SERVER['QUERY_STRING'], '/'));
                                        array_pop($q_string);
                                        count($q_string) == 0
                                            ? print('./')
                                            : print('?' . implode('/', $q_string) . '/');
                                        ?>" class="navButton">Go Back!</a>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>

</html>