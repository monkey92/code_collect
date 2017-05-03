<?php


    /**
     * 手机号检验
     * @param $m
     * @return bool
     */
    public static function isValidateMobile($m){
        return preg_match('/^1[0-9]{10}$/',$m) ? true : false;
    }

    /**
     * 检验密码
     * @param $pwd
     * @return bool
     */
    public static function isValidatePwd($pwd){
        if(preg_match('/^[\w]{6,20}$/',$pwd)) return true;
        return false;
    }

    /**
     * 用户名校验
     * @param $name
     * @return bool
     */
    private function isValidateUserName($name){
        $not_allow_names = ["admin","administrator","fuck"];
        if(in_array($name,$not_allow_names) || !preg_match('/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,18}$/u',$name)) return false;
        return true;
    }


    function removeHtmlTag($html){
        return preg_replace('#(<img(.*?)>|<[^>.*]*>)#', '', $html);
    }

    function findImg($html){
        $pattern = '#<\s*img\s*[^>]*src=[\'\"]([^\s\'\"]+)[\'\"][^>]*/?>#i';
        $matches = [];
        preg_match_all($pattern,'', $matches);

        return $matches;
    }


    //找到以固定字符串开头的图片链接并且替换掉
    function findImgAndReplace($subject){

        $pattern = '#<\s*img\s*[^>]*src=[\'\"]([^\s\'\"]+)[\'\"][^>]*/?>#i';

        preg_replace_callback($pattern, function($matches) use (&$subject){
            
            $pic_domain = 'http://pic-domain.com';

            $img_url = $matches[1];

            if(substr($img_url, 0,strlen($pic_domain)) == $pic_domain){
                $subject = str_replace($img_url, substr($img_url, strlen($pic_domain)), $subject);
            }
        }, $subject);


    }



    


