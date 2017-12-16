<?php

namespace Mhr\DNSParser;
/**
 * Class DNS
 * @package Mhr\DNSParser
 */
class DNS {


    private $name;
    private $ttl=false;
    private $value;
    private $type;
    private $raw = "";
    private $priority = false;
    private $weight = false;
    private $port = false;

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $port = trim($port);
        $this->port = $port;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $weight = trim($weight);
        $this->weight = $weight;
    }

    /**
     * @return int|boolean
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return int|boolean
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $raw
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $name = trim($name);
        $this->name = $name;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $ttl = trim($ttl);
        $this->ttl = $ttl;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $type = trim($type);
        $this->type = $type;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $value = trim($value);
        $this->value = $value;
    }

    /**
     * @param boolean|integer $priority
     */
    public function setPriority($priority)
    {
        $priority = trim($priority);
        $this->priority = $priority;
    }

    /**
     * @return integer|boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    static function itemsWithIndexBiggetThan($array,$index){
        $n = [];
        for($i = 0 ; $i < count($array) ; $i++)
            if($index < $i ) $n[]= $array[$i];
        return $n;
    }


    /**
     * @param $line
     * @return DNS
     */
    static function parseLine($line , array  $accepted_type = []){
        $line = array_values($line);
        $instance = new DNS();
        $instance->setRaw($line);

        $parsed = false;

        if(in_array($line[0],$accepted_type)
            ||
            in_array($line[1],$accepted_type) && is_numeric($accepted_type[0])
        ){
            $new_array = ["@"];
            foreach ($line as $item)
                $new_array[]=$item;
            $line = $new_array;
        }

        $instance->setName($line[0]);
        $new = [];

//        if(in_array($line[0])

        if(is_numeric($line[1])) {
            $instance->setTtl($line[1]);
            $new = self::itemsWithIndexBiggetThan($line,1);
        }
        else{
            $new = self::itemsWithIndexBiggetThan($line,0);
        }



        if(in_array($new[0] , ['IN','OUT']))
            $new = self::itemsWithIndexBiggetThan($new,0);
//        echo(trim($new[0])."\n");
        switch (trim($new[0])){

            case "MX":{
                $parsed = true;
                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $instance->setValue($new[2]);
            }break;

            case "TXT":{
                $parsed = true;
//                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $values = implode(" ",self::itemsWithIndexBiggetThan($new,0));
                $instance->setValue($values);
            }break;

            case "A":{
//                echo "1\n";
                $parsed = true;
//                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $instance->setValue($new[1]);
            }break;

            case "NS":{
                $parsed = true;
//                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $instance->setValue($new[1]);
            }break;

            case "SRV":{
                $parsed = true;
//                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $instance->setPriority($new[1]);
                $instance->setWeight($new[2]);
                $instance->setPort($new[3]);
            }break;

            case "CNAME":{
                $parsed = true;
//                $instance->setPriority($new[1]);
                $instance->setType($new[0]);
                $instance->setValue($new[1]);
            }break;

            case "SOA":{
                return null;
            }break;

            default:{
//                print_r($line);
                return null;
            }break;


        }

//        if(count($line) == 3){
//            $instance->setName($line[0]);
//            $instance->setType($line[1]);
//            $instance->setValue($line[2]);
//            $parsed = true;
//        }
//
//        if(count($line) == 5){
//            $instance->setName($line[0]);
//            $instance->setType($line[3]);
//            $instance->setValue($line[4]);
//            $instance->setTtl($line[1]);
//            $parsed = true;
//        }
//
//        if(count($line) == 6){
//            $instance->setName($line[0]);
//            $instance->setType($line[3]);
//            $instance->setValue($line[5]);
//            $instance->setTtl($line[1]);
//            $parsed = true;
//        }
//
//        if(count($line) == 7){
//            $instance->setName($line[0]);
//            $instance->setType($line[3]);
//            $instance->setValue($line[6]);
//            $instance->setTtl($line[1]);
//            $parsed = true;
//        }
//        if(count($line) == 8){
//            $instance->setName($line[0]);
//            $instance->setType($line[3]);
//            $instance->setValue($line[7]);
//            $instance->setPort($line[6]);
//            $instance->setWeight($line[5]);
//            $instance->setWeight($line[4]);
//            $instance->setTtl($line[1]);
//            $parsed = true;
//        }




        if(!$parsed){
            dd($line);
        }

        if(in_array($instance->getType(),$accepted_type))
            return $instance;
        return null;
    }

    function __toString()
    {
      return implode("-",$this->raw);
    }

}
