<?php


namespace App\Service;


class PhotoBlob
{
    public function addPhoto($request,$key){
        //Gestion de l'image
        return $strm = fopen($request->files->get($key), 'rb');
    }
}