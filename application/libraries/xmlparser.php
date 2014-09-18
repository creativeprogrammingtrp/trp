<?php
class xmlparser
{

    function GetChildren($vals, &$i) 
    {
        $children = array(); 
    
    
        if (isset($vals[$i]['value'])) 
            $children['VALUE'] = $vals[$i]['value']; 
    
    
        while (++$i < count($vals))
        { 
            switch ($vals[$i]['type']) 
            { 
                case 'cdata': 
                    if (isset($children['VALUE']))
                        $children['VALUE'] .= $vals[$i]['value']; 
                    else
                        $children['VALUE'] = $vals[$i]['value']; 
                    break;
    
                case 'complete': 
                    if (isset($vals[$i]['attributes'])) {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index = count($children[$vals[$i]['tag']])-1;
    
                        if (isset($vals[$i]['value'])) 
                            $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value']; 
                        else
                            $children[$vals[$i]['tag']][$index]['VALUE'] = ''; 
                    } else {
                        if (isset($vals[$i]['value'])) 
                            $children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value']; 
                        else
                            $children[$vals[$i]['tag']][]['VALUE'] = ''; 
    		}
                    break; 
    
                case 'open': 
                    if (isset($vals[$i]['attributes'])) {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index = count($children[$vals[$i]['tag']])-1;
                        $children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index],$this->GetChildren($vals, $i));
                    } else {
                        $children[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
                    }
                    break; 
    
                case 'close': 
                    return $children; 
            } 
        } 
    } 

    function GetXMLTree($xml) 
    { 
        $data = $xml;
       
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
        xml_parse_into_struct($parser, $data, $vals, $index); 
        xml_parser_free($parser);
        
        //print_r($index);
    
        $tree = array(); 
        $i = 0; 
        
        if (isset($vals[$i]['attributes'])) {
    	$tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes']; 
    	$index = count($tree[$vals[$i]['tag']])-1;
    	$tree[$vals[$i]['tag']][$index] =    array_merge($tree[$vals[$i]['tag']][$index], $this->GetChildren($vals, $i));
        }
        else
            $tree[$vals[$i]['tag']][] = $this->GetChildren($vals, $i); 
        
        return $tree; 
    } 
    
    function printa($obj) {
        global $__level_deep;
        if (!isset($__level_deep)) $__level_deep = array();
    
        if (is_object($obj))
            print '[obj]';
        elseif (is_array($obj)) {
            foreach(array_keys($obj) as $keys) {
                array_push($__level_deep, "[".$keys."]");
                $this->printa($obj[$keys]);
                array_pop($__level_deep);
            }
        }
        else print implode(" ",$__level_deep)." = $obj\n";
    }
}
class uspsxmlParser {
		
	var $params = array(); //Stores the object representation of XML data
	var $root = NULL;
	var $global_index = -1;
	var $fold = false;
	
	/* Constructor for the class
	* Takes in XML data as input( do not include the <xml> tag
	*/
	function xmlparser($input, $xmlParams=array(XML_OPTION_CASE_FOLDING => 0)) {
		$xmlp = xml_parser_create();
			foreach($xmlParams as $opt => $optVal) {
				switch( $opt ) {
				case XML_OPTION_CASE_FOLDING:
					$this->fold = $optVal;
				break;
				default:
				break;
				}
				xml_parser_set_option($xmlp, $opt, $optVal);
		}
	
		if(xml_parse_into_struct($xmlp, $input, $vals, $index)) {
			$this->root = $this->_foldCase($vals[0]['tag']);
			$this->params = $this->xml2ary($vals);
		}
		xml_parser_free($xmlp);
	}
	
	function _foldCase($arg) {
		return( $this->fold ? strtoupper($arg) : $arg);
	}
	
	/*
	 * Credits for the structure of this function
	 * http://mysrc.blogspot.com/2007/02/php-xml-to-array-and-backwards.html
	 *
	 * Adapted by Ropu - 05/23/2007
	 *
	*/
	
	function xml2ary($vals) {
	
		$mnary=array();
		$ary=&$mnary;
		foreach ($vals as $r) {
			$t=$r['tag'];
			if ($r['type']=='open') {
				if (isset($ary[$t]) && !empty($ary[$t])) {
					if (isset($ary[$t][0])){
						$ary[$t][]=array();
					} else {
						$ary[$t]=array($ary[$t], array());
					}
					$cv=&$ary[$t][count($ary[$t])-1];
				} else {
					$cv=&$ary[$t];
				}
				$cv=array();
				if (isset($r['attributes'])) {
					foreach ($r['attributes'] as $k=>$v) {
					$cv[$k]=$v;
					}
				}
	
				$cv['_p']=&$ary;
				$ary=&$cv;
	
				} else if ($r['type']=='complete') {
					if (isset($ary[$t]) && !empty($ary[$t])) { // same as open
						if (isset($ary[$t][0])) {
							$ary[$t][]=array();
						} else {
							$ary[$t]=array($ary[$t], array());
						}
					$cv=&$ary[$t][count($ary[$t])-1];
				} else {
					$cv=&$ary[$t];
				}
				if (isset($r['attributes'])) {
					foreach ($r['attributes'] as $k=>$v) {
						$cv[$k]=$v;
					}
				}
				$cv['VALUE'] = (isset($r['value']) ? $r['value'] : '');
	
				} elseif ($r['type']=='close') {
					$ary=&$ary['_p'];
				}
		}
	
		$this->_del_p($mnary);
		return $mnary;
	}
	
	// _Internal: Remove recursion in result array
	function _del_p(&$ary) {
		foreach ($ary as $k=>$v) {
		if ($k==='_p') {
			  unset($ary[$k]);
			}
			else if(is_array($ary[$k])) {
			  $this->_del_p($ary[$k]);
			}
		}
	}
	
	/* Returns the root of the XML data */
	function GetRoot() {
	  return $this->root;
	}
	
	/* Returns the array representing the XML data */
	function GetData() {
	  return $this->params;
	}
}
?>