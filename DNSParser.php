<?php

namespace Mhr\DNSParser;

class DNSParser {

    private $txt = "";
    static public $PACAKGE_ROOT = __DIR__;
    private $dnsList =[];
    private $accepted_types = [
        "NS","MX","TXT","SRV","CNAME","A"
    ];

    /**
     * @param string $txt
     */
    public function setTxt($txt)
    {
        $this->txt = $txt;
    }

    /**
     * @return string
     */
    public function getTxt()
    {
        return $this->txt;
    }

    /**
     * @return array
     */
    public function getAcceptedTypes()
    {
        return $this->accepted_types;
    }

    /**
     * @param array $accepted_types
     */
    public function setAcceptedTypes($accepted_types)
    {
        $this->accepted_types = $accepted_types;
    }


    /**
     * load text from string
     * @param $text string
     */
    public function loadFromText($text){
        $this->setTxt($text);
    }

    /**
     * Load Text from file
     * @param $file_path string file path
     * @throws \Exception
     */
    public function loadFromFile($file_path){
        if(!is_file($file_path))
            throw new \Exception('file not founded');
        $this->setTxt(file_get_contents($file_path));
    }

    /**
     * filter texts
     * @return void
     */

    public function filterText(){
        $text= $this->getTxt();
        $text=  str_replace("\r","",$text);
        $this->setTxt($text);
    }

    /**
     * parse the raw text
     * @return void
     */

    public function parse(){
        $this->dnsList = [];
        $this->filterText();
        $raw_array = explode("\n",$this->getTxt());

        $line_n = 0 ;

        foreach ($raw_array as $line_raw){
            $line_n++;
            // skip check
            $line_raw = trim($line_raw);
            if(
                strlen($line_raw) == 0 ||
                $line_raw[0] == ";" || // comments
                trim($line_raw) == "" || // whitespace lines
//                !(strpos($line_raw,"SOA") === false) || // skipping SOA types
                !(strpos($line_raw,'$ORIGIN') === false) // $ORIGIN ? skip !

            ){
//                var_dump(  [!(strpos($line_raw,";") === false) , // comments
//                    trim($line_raw) == "" , // whitespace lines
//                    !(strpos($line_raw,"SOA") === false) , // skipping SOA types
//                    !(strpos($line_raw,'$ORIGIN') === false) ]);
//                var_dump($line_raw);
//                echo "SKIPPING LINE : $line_n \n";
                continue;
            }
            $text=  str_replace("  ","\t",$line_raw);
            $line = preg_split("/[\r,\t,\n,\f]+/",$text);
            $res = [];
            foreach ($line as $item){
                preg_match('#\((.*?)\)#', $item, $match);
                if($match && isset($match[1]))
                    $res[]= $match[1];
                else $res[]=$item;
            }



            $dns =  DNS::parseLine($res,$this->accepted_types);
            if($dns)
                $this->dnsList [] = $dns;

        }

//        dd($this->dnsList);

//        foreach ($this->dnsList as $dns){
//            echo $dns."\n";
//        }

    }

    /**
     * @return DNS[]
     */
    public function fetchAll(){
        return $this->dnsList;
    }


    /**
     * @return array
     */
    public function fetchTypes(){
        $types = [];
        foreach ($this->fetchAll() as $dns){
            $types[$dns->getType()]=false;
        }
        return array_keys($types);
    }
    /**
     * @return DNS[]
     */
    public function fetchByType($type){
        $filter = [];
        foreach ($this->fetchAll() as $dns){
            if($dns->getType() == $type)
            $filter []= $dns;
        }
        return array_values($filter);
    }
    /**
     * @return DNS[]
     */
    public function fetchByName($name){
        $filter = [];
        foreach ($this->fetchAll() as $dns){
//            echo $dns->getName()."\n";
            if($dns->getName() == $name)
                $filter []= $dns;
        }
        return array_values($filter);
    }

    /**
     * @return DNS[]
     */
    public function fetchByValue($name){
        $filter = [];
        foreach ($this->fetchAll() as $dns){
//            echo $dns->getName()."\n";
            if($dns->getValue() == $name)
                $filter []= $dns;
        }
        return array_values($filter);
    }



}
