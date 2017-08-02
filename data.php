<?php
    require_once "kit.php";
    session_start();
    $modify_filename = $_REQUEST["filename"];
    $act = $_REQUEST["act"];
    switch($act)
    {
        case "create_dir":
            createDir($modify_filename);
            break;
        case "delete_dir":
            deleteDir($modify_filename);
            break;
        case "delete_file":
            deleteFile($modify_filename);
            break;
        case "upload_file":
            uploadFile($_FILES["myfile"]);
            break;
    }
    //print_r ($_FILES);
?>