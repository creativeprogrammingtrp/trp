<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sitemap_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('lib');
    }
	public function load_sitemap_shopping()
	{
		return false;
	}
	
	function get_sitemap_shopping($li = "", $ul = "", $a = "", $dis = "")
	{
		$this->load->library('lib');
		
		$str = '';
		$sql1 =  "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid = 0 order by `cat_id` asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 group by cat_id) T2 on T1.cat_id = T2.cid";
		$re1 = $this->db->query($sql1);
		$f = $re1->result_array();
		
		$sql2 =  "SElECT * FROM (SELECT * FROM `categories` WHERE status = 1 and fid <> 0 order by fid asc) T1 LEFT JOIN (SELECT cat_id as cid, COUNT(*) as total_item FROM `items` WHERE `itm_status` = 1 group by cat_id) T2 on T1.cat_id = T2.cid";
		$re2 = $this->db->query($sql2);
		$c = $re2->result_array();

		for ($j=0;$j<count($c);$j++)
		{
			$c[$j]['total_item'] = $c[$j]['total_item']==NULL?0:$c[$j]['total_item'];
		}
		for ($i=0;$i<count($f);$i++)
		{
			$f[$i]['total_item'] = $f[$i]['total_item']==NULL?0:$f[$i]['total_item'];
			$total = $f[$i]['total_item'];
			$child_str = $this->get_left_child($li, $ul, $a, $dis, $f[$i]['cat_id'], $f[$i]['cat_key'], $f[$i]['cat_name'], $f[$i]['total_item'],$c,$total);
			$str .= $child_str;
		}
		return $str;
	}
	
	private function get_left_child($li = "", $ul = "", $a = "", $dis = "", $id, $cat_key, $catname, $total_item, $arr_child,&$total)
	{
		$str = "";
		$info_cat = "";
		
		for ($i = 0; $i < count($arr_child) ;$i++)
		{
			if ($arr_child[$i]['fid'] == $id)
			{
				$total_c = $arr_child[$i]['total_item'];
				$child_str = $this->get_left_child($li, $ul, $a, $dis,  $arr_child[$i]['cat_id'], $arr_child[$i]['cat_key'],$arr_child[$i]['cat_name'],$arr_child[$i]['total_item'], $arr_child,$total_c);
				$total += $total_c;
				$info_cat .= $child_str;
			}
		}
		if ($total != 0)
		{
			$str .= '<a class="'.$a.'" href="'.$this->system->URL_server__() .'shop/pcategories?catkey='.$cat_key.'"> '.$catname.($total_item>0?" (".$total_item.")":"");
		}
		else
		{
			$str .= '<a class="'.$dis.'" href="'.$this->system->URL_server__() .'shop/pcategories?catkey='.$cat_key.'"> '.$catname.($total_item>0?" (".$total_item.")":"");
		}
		
		if ($info_cat != "")
		$str = '<li class="'.$li.'">'.$str.'<i class="icon-caret-down"></i></a>'."<ul class='".$ul."'>".$info_cat."</ul>".'</li>';
		else $str = '<li>'.$str.'</a></li>';
		
		return $str;
	}
	
}