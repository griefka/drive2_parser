<?php
/**
 * Created by PhpStorm.
 * User: kai
 * Date: 20.03.17
 * Time: 16:03
 */

namespace Helpers\Images;


trait SaveImage
{
    /** save image to folder
     * @param $image
     * @param $directory
     * @return string
     */
    public function saveImage($image, $directory){
        $array = explode('.', $image);
        $extension = end($array);
        $imagePath = $_SERVER['DOCUMENT_ROOT'].'/src/images/'.$directory.'/'.time().rand(0,10000).'.'.$extension;
        file_put_contents($imagePath, file_get_contents($image));
        return $imagePath;
    }
}