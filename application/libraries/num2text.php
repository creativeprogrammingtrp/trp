<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Num2text {
    private $_original = 0;
    private $_parsed_number_text = '';
    private $_single_nums = array(1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 =>'Nine');
    private $_teen_nums = array(0 => 'Ten', 1 => 'Eleven', 2 => 'Twelve', 3 => 'Thirteen', 4 => 'Fourteen', 5 => 'Fifteen', 6 => 'Sixteen', 7 =>'Seventeen', 8 => 'Eighteen', 9 => 'Nineteen');
    private $_tens_nums = array(2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty', 6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 =>'Ninety');
    private $_chunks_nums = array(1 => 'Thousand', 2 => 'Million', 3 => 'Billion', 4 => 'Trillion', 5 => 'Quadrillion', 6 => 'Quintrillion', 7 =>'Sextillion', 8 => 'Septillion', 9 => 'Octillion', 9 => 'Nonillion', 9 => 'Decillion');
    function __construct($number = NULL) {
		$this->CI =& get_instance();
		if($number !== NULL) {
            $this->_original = trim($number);
        }
    }
	public function convertDolla($number = NULL){
		if($number !== NULL) {
            $number = trim($number);
        }else{
			$number = $this->_original;	
		}
		if($number == 0) return 'Zero cent';
		if(!is_numeric($number)) return "";
		$text = '';
		$arr_num = explode(".", (string)$number);
		if(isset($arr_num[0]) && is_numeric($arr_num[0]) && $arr_num[0] > 0){
			$text .= $this->parse($arr_num[0]);
			if((int)$arr_num[0] > 1) $text .= ' dollars';
			else $text .= ' dollar';
		}
		if(isset($arr_num[1]) && is_numeric($arr_num[1]) && $arr_num[1] > 0){
			if($arr_num[1] < 10) $arr_num[1] .= '0';
			if($text != '') $text .= ' and ';
			$text .= $this->parse($arr_num[1]);
			if((int)$arr_num[1] > 1) $text .= ' cents';
			else $text .= ' cent';
		}
		return $text;	
	}
    public function parse($new_number = NULL) {
        if($new_number !== NULL) {
            $this->_original = trim($new_number);
        }
        if($this->_original == 0) return 'Zero';
        $num = str_split($this->_original, 1);
        krsort($num);
        $chunks = array_chunk($num, 3);
        krsort($chunks);
        $final_num = array();
        foreach ($chunks as $k => $v) {
            ksort($v);
            $temp = trim($this->_parse_num(implode('', $v)));
            if($temp != '') {
                $final_num[$k] = $temp;
                if (isset($this->_chunks_nums[$k]) && $this->_chunks_nums[$k] != '') {
                    $final_num[$k] .= ' '.$this->_chunks_nums[$k];
                }
            }
        }
        $this->_parsed_number_text = implode(', ', $final_num);
        return $this->_parsed_number_text;
    }
    public function __toString() {
        return $this->_parsed_number_text;
    }
    private function _parse_num($num) {
        $temp = array();
        if (isset($num[2])) {
            if (isset($this->_single_nums[$num[2]])) {
                $temp['h'] = $this->_single_nums[$num[2]].' Hundred';
            }
        }
        if (isset($num[1])) {
            if ($num[1] == 1) {
                $temp['t'] = $this->_teen_nums[$num[0]];
            } else {
                if (isset($this->_tens_nums[$num[1]])) {
                    $temp['t'] = $this->_tens_nums[$num[1]];
                }
            }
        }
        if (!isset($num[1]) || $num[1] != 1) {
            if (isset($this->_single_nums[$num[0]])) {
                if (isset($temp['t'])) {
                    $temp['t'] .= ' '.$this->_single_nums[$num[0]];
                } else {
                    $temp['u'] = $this->_single_nums[$num[0]];
                }
            }
        }
        return implode(' and ', $temp);
    }
}
?>