<?php
    namespace App\Core\Abstract;


    abstract class AbstractEntity{

        abstract public static  function toObject($data):static;
        abstract public function toArray():array;
        
        public function toJson():string{
            return json_encode(static::toArray());
        }
    }