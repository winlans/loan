<?php
/**
 * Created by Tony Tao
 * Date: 2016/3/10
 * Time: 14:46
 */

namespace App\Entity;

use App\Util\StringUtil;
use DateTime;

class BaseEntity
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    private $objArray = array();

    public function toArray($keyUnderscore = true){
        if(!$this->objArray)
            foreach(get_class_methods($this) as $method)
                if(preg_match('/^get([A-Z]+\w*)$/i', $method, $matches))
                    if(property_exists($this, lcfirst($matches[1]))){
                        $value = $this->$method();
                        if($value instanceof DateTime && $value)
                            $value = $value->getTimestamp();
                        if(!is_array($value))
                            $value = (string)$value;

                        $key = $keyUnderscore ? StringUtil::camelToUnderscore($matches[1]) : lcfirst($matches[1]);
                        $this->objArray[$key] = $value;
                    }

        return $this->objArray;
    }

    public function setProperties(array $properties){
        foreach($properties as $k => $v){
            $method = 'set' . ucfirst($k);
            if(method_exists($this, $method)){
                $this->$method($v);
            }
        }
    }
}