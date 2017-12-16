<?php

namespace Mhr\DNSParser;

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
        $this->port = $port;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
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
        $this->name = $name;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param boolean|integer $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return integer|boolean
     */
    public function getPriority()
    {
        return $this->priority;
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

        if(count($line) == 3){
            $instance->setName($line[0]);
            $instance->setType($line[1]);
            $instance->setValue($line[2]);
            $parsed = true;
        }

        if(count($line) == 5){
            $instance->setName($line[0]);
            $instance->setType($line[3]);
            $instance->setValue($line[4]);
            $instance->setTtl($line[1]);
            $parsed = true;
        }

        if(count($line) == 6){
            $instance->setName($line[0]);
            $instance->setType($line[3]);
            $instance->setValue($line[5]);
            $instance->setTtl($line[1]);
            $parsed = true;
        }

        if(count($line) == 7){
            $instance->setName($line[0]);
            $instance->setType($line[3]);
            $instance->setValue($line[6]);
            $instance->setTtl($line[1]);
            $parsed = true;
        }
        if(count($line) == 8){
            $instance->setName($line[0]);
            $instance->setType($line[3]);
            $instance->setValue($line[7]);
            $instance->setPort($line[6]);
            $instance->setWeight($line[5]);
            $instance->setWeight($line[4]);
            $instance->setTtl($line[1]);
            $parsed = true;
        }




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
