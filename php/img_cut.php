<?php

/*
按固定尺寸裁剪 可能会丢失像素
*/
function imageCropper($source_path, $target_width, $target_height){
  $source_info  = getimagesize($source_path);
  $source_width = $source_info[0];
  $source_height = $source_info[1];
  $source_mime  = $source_info['mime'];
  $source_ratio = $source_height / $source_width;// 0.5
  $target_ratio = $target_height / $target_width;//1
  if ($source_ratio > $target_ratio){
    // image-to-height
    $cropped_width = $source_width;
    $cropped_height = $source_width * $target_ratio;
    $source_x = 0;
    $source_y = ($source_height - $cropped_height) / 2;
  }elseif ($source_ratio < $target_ratio){
    //image-to-widht
    $cropped_width = $source_height / $target_ratio;
    $cropped_height = $source_height;
    $source_x = ($source_width - $cropped_width) / 2;
    $source_y = 0;
  }else{
    //image-size-ok
    $cropped_width = $source_width;
    $cropped_height = $source_height;
    $source_x = 0;
    $source_y = 0;
  }
  switch ($source_mime){
    case 'image/gif':
      $source_image = imagecreatefromgif($source_path);
      break;
    case 'image/jpeg':
      $source_image = imagecreatefromjpeg($source_path);
      break;
    case 'image/png':
      $source_image = imagecreatefrompng($source_path);
      break;
    default:
      return ;
      break;
  }
  $target_image = imagecreatetruecolor($target_width, $target_height);
  $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
  // copy
  imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
  // zoom
  imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
  header('Content-Type: image/jpeg');
  imagejpeg($target_image);
  imagedestroy($source_image);
  imagedestroy($target_image);
  imagedestroy($cropped_image);
}

/*
  等比例裁剪
*/
function imageZoom($filename,$zoom=0.6){
  //baseinfo
  $sourceImageInfo = getimagesize($filename);
  $sourceWidth = $sourceImageInfo[0];
  $sourceHeight = $sourceImageInfo[1];
  $sourceMine = $sourceImageInfo['mime'];
  $sourceRatio = $sourceWidth/$sourceHeight;
  $sourceX = 0;
  $sourceY = 0;
  //zoom
  $targetRatio = $zoom;
  //target-widht-height
  $targetWidth = $sourceWidth*$targetRatio;
  $targetHeight = $sourceHeight*$targetRatio;
  //init-params
  $sourceImage = null;
  switch($sourceMine){
    case 'image/gif':
      $sourceImage = imagecreatefromgif($filename);
      break;
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($filename);
      break;
    case 'image/png':
      $sourceImage = imagecreatefrompng($filename);
      break;
    default:
      return ;
      break;
  }
  //temp-target-image
  $tempSourceImage = imagecreatetruecolor($sourceWidth, $sourceHeight);
  $targetImage = imagecreatetruecolor($targetWidth,$targetHeight);
  //copy
  imagecopy($tempSourceImage, $sourceImage, 0, 0, $sourceX, $sourceY, $sourceWidth, $sourceHeight);
  //zoom
  imagecopyresampled($targetImage, $tempSourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
  //header
  header('Content-Type: image/jpeg');
  //image-loading
  imagejpeg($targetImage);
  //destroy
  imagedestroy($tempSourceImage);
  imagedestroy($sourceImage);
  imagedestroy($targetImage);
}