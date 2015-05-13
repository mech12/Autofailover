<?php
function IMAGE_TO_PNG($file_path, $size = 720){
    $path_parts = pathinfo($file_path);
    $file_type = $path_parts['extension'];
    $saveFileType = "png";
    $image = new Imagick();
    $image->readimage($file_path);

    if($file_type == "psd"){
        $image->setIteratorIndex(0);
    }

    $dimensions = $image->getImageGeometry();
    $width = $dimensions['width'];
    $height = $dimensions['height'];

    if($height > $width){
        //Portrait
        if($height > $size)
            $image->thumbnailImage(0, $size);
        $image->getImageGeometry();
        if($width > $size){
            $image->thumbnailImage($size, 0);
        }
    }else{
        $image->thumbnailImage($size, 0);
    }

    $output_file = $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$saveFileType;
    $image->writeImage($output_file);
    return $output_file;
}

/**
 * PDF 파일을 이미지로 변환해 준다.
 */
function PDF_TO_PNG($file_path, $img_path, $size = 720)
{
    $image = new Imagick();
    $image->readimage($file_path."[0]");
    //$image->scaleImage($size,0);
    $image->setImageFormat( "png" );
    $image->writeImage($img_path);
}

function THUMBNAIL($file_path, $out_path, $width = 150, $height = 150, $quality = 80)
{
    $ci =& get_instance();
    $ci->load->library("image_moo");
    $ci->image_moo
        ->load($file_path)
        ->set_background_colour("#FFF")
        ->resize_crop($width,$height)
        ->set_jpeg_quality($quality)
        ->save($out_path);
}

function IMAGE_RESIZE($file_path, $width = 800, $height = 600, $quality = 80)
{
    $allowed = array('png', 'jpg', 'gif', 'jpeg');
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    if(!in_array($extension, $allowed)){
       return false;
    }


    $file_path = HTMLPATH.$file_path;
    $tmp_file = str_replace(".".pathinfo($file_path, PATHINFO_EXTENSION), "_r.".pathinfo($file_path, PATHINFO_EXTENSION), $file_path);
    $ci =& get_instance();
    $ci->load->library("image_moo");
    $ci->image_moo
        ->load($file_path)
        ->set_background_colour("#FFF")
        ->resize($width,$height)
        ->set_jpeg_quality($quality)
        ->save($tmp_file);
    unlink($file_path);
    rename($tmp_file, $file_path);
    return true;
}

function JPG_AUTO_ROTATE($file_path)
{
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    if($extension != 'jpg')
        return;

    $exif = exif_read_data($file_path);
    if(!isset($exif['Orientation'])) {
        return;
    }
    $ort = $exif['Orientation'];
    $degrees = 0;
    switch ($ort) {
        case 1:
            return;
            break;
        case 2:
            return;
            break;
        case 3:
        case 4:
            $degrees  = 180;
            break;
        case 5:
        case 6:
            $degrees  = 270;
            break;
        case 7:
        case 8:
            $degrees  = 90;
            break;
        default:
            return;
    }

    $ci =& get_instance();
    $ci->load->library("image_moo");
    $ci->image_moo
        ->load($file_path)
        ->rotate($degrees)
        ->save($file_path, true);
}
