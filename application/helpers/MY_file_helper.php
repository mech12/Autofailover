<?php

function file_path_generate($extension, $type = "i")
{
    $upload_path = HTMLPATH."files/";
    $type_path = $upload_path.$type."/";
    $year_path = $type_path.date("Y");
    $month_path = $year_path."/".date("m");
    $day_path = $month_path."/".date("ymd");

    if(!file_exists($upload_path)) mkdir($upload_path, 0777, true);
    if(!file_exists($type_path)) mkdir($type_path, 0777, true);
    if(!file_exists($year_path)) mkdir($year_path, 0777, true);
    if(!file_exists($month_path)) mkdir($month_path, 0777, true);
    if(!file_exists($day_path)) mkdir($day_path, 0777, true);


    $cnt = 0;
    $file_path = $day_path."/".uniqid().".".$extension;

    while(file_exists($file_path))
    {
        $cnt++;
        $file_path = $day_path."/".uniqid().".".$extension;
    }


    $file_path = str_replace(HTMLPATH, "/", $file_path);

    return $file_path;
}

function file_size_convert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}


function upload_image_files($field='userfile', $thumbnail_width = 0, $thumbnail_height = 0){
    $allowed = array('png', 'jpg', 'gif', 'jpeg');

    $files_uploaded = array();
    if(!isset($_FILES[$field])) return $files_uploaded;
    for($i=0; $i<count($_FILES[$field]['name']); $i++) {
        $extension = strtolower(pathinfo($_FILES[$field]['name'][$i], PATHINFO_EXTENSION));
        if(!in_array($extension, $allowed)){
            $files_uploaded[$i] = false;
            continue;
        }

        $tmpFilePath = $_FILES[$field]['tmp_name'][$i];
        $newFilePath = file_path_generate($extension);

        if(move_uploaded_file($tmpFilePath, HTMLPATH.$newFilePath)) {
            $ci =& get_instance();
            $ci->load->helper("image");
            JPG_AUTO_ROTATE(HTMLPATH.$newFilePath);
            $files_uploaded[$i] = array(
                'filename' => $_FILES[$field]['name'][$i],
                'path' => $newFilePath,
            );
            if($thumbnail_width > 0 || $thumbnail_height> 0){
                $thumb_file_path = str_replace(".".pathinfo($newFilePath, PATHINFO_EXTENSION), "_t.".pathinfo($newFilePath, PATHINFO_EXTENSION), $newFilePath);
                THUMBNAIL(HTMLPATH.$newFilePath, HTMLPATH.$thumb_file_path, $thumbnail_width, $thumbnail_height);
            }
        }
    }
    return $files_uploaded;
}