<?php

namespace  App\Util;

use App\Exceptions\CommonException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 上传文件的工具类
 * Class UploadFileUtil
 * @package App\Util
 */
class UploadFileUtil{

    const IMG_MAX_SIZE = 5*1024*1024;
    const AVATAR_MAX_SIZE = 2 * 1024 * 1024;

    public static $image_mimes = ["image/jpg","image/jpeg","image/png"];


    /**
     * 上传多张图片
     * @param $files
     * @param string $tag
     * @return array
     * @throws CommonException
     */
    public static function upLoadImages($files,$tag=""){
        $ret_path = [];
        foreach ($files as $f){
            $relative_path = static::handle($f,$tag);
            array_push($ret_path,$relative_path);
        }
        return $ret_path;
    }


    //上传头像
    public static  function uploadAvatar($file,$tag="avatars"){
        return static::handle($file,$tag);
    }


    /**
     * 上传一张图片
     * @param UploadedFile $file
     * @param string $tag
     * @return string
     */
    public static  function upLoadImage(UploadedFile $file,$tag=""){
        return static::handle($file,$tag);
    }


    /**
     * 处理文件上传 返回文件保存路径
     * @param UploadedFile $f
     * @param string $tag
     * @return string
     * @throws CommonException
     */
    private static function  handle(UploadedFile $f,$tag=""){
        if($f->isValid()){

            if(!in_array($f->getClientMimeType(),static::$image_mimes)) throw new CommonException(1044);

            if($f->getClientSize() > self::IMG_MAX_SIZE) throw new CommonException(1045);

            $extension = $f->getClientOriginalExtension();
            $file_name = time().mt_rand(10000,99999).'.'.$extension;

            $base_path = public_path();

            $relative_path = "uploads/".static::generateImgPath($tag);

            $file_path = $base_path.'/'.$relative_path;

            $f->move($file_path,$file_name);

            $ff = $file_path.'/'.$file_name;

            $arr = @getimagesize($ff);
            $width = 0;
            $height = 0;
            if($arr){
                $width = $arr[0];
                $height = $arr[1];
            }
            $ret = ["src"=>$relative_path.'/'.$file_name,'width'=>$width,'height'=>$height];

            return $ret;
        }else{
            throw new CommonException(1043);
        }

    }

    /**
     * @deprecated
     * @param UploadedFile $img
     * @param string $tag
     * @throws CommonException
     */
    public static function handleImage(UploadedFile $img,$tag=''){

        if(!$img->isValid()) throw new CommonException(1043);
        if(!in_array($img->getClientMimeType(),static::$image_mimes)) throw new CommonException(1044);

        if($img->getClientSize() > self::IMG_MAX_SIZE) throw new CommonException(1045);

        if($tag == 'avatars' && $img->getClientSize() > self::AVATAR_MAX_SIZE) throw new CommonException(1058);


        $extension = $img->getClientOriginalExtension();

        $file_name = time().mt_rand(10000,99999).'.'.$extension;
        $relative_path = "uploads/".static::generateImgPath($tag);
        $absolute_path = public_path().'/'.$relative_path;

        //

    }




    /**
     * 生成文件保存的相对路径
     * @param string $tag
     * @return string
     */
    private static function generateImgPath($tag=""){
        $path = date('Ymd',time());
        if(!empty($tag)){
            return $tag.'/'.$path;
        }else{
            return $path;
        }
    }




}



