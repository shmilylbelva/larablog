<?php

namespace App\Handlers;

class Level {
	public function formatOne($array, $pid=0, $separator='&nbsp;', $level=0, $primary='id', $parent='pid')
	{
		$arr = array();

		foreach($array as $val){
			if($val[$parent] == $pid){
				$val->level = $level + 1;
				$arr[] = $val;
				$arr = array_merge($arr,$this->formatOne($array,$val[$primary],$separator,$level+1,$primary,$parent));
			}
		}

		return $arr;
	}

	public function formatMulti($array, $pid=0)
	{
		$arr = array();

		foreach($array as $val){
			if($val['pid'] == $pid){
				$child = $this->formatMulti($array,$val['id']);
				if($child){
					$val->child = $child;
				}
				$arr[] = $val;
			}
		}

		return $arr;
	}

	public function formatParent($array, $id)
	{
		$arr = array();

		foreach($array as $val){
			if($val['id'] == $id){
				$arr[] = $val;
				$arr = array_merge($arr,$this->formatParent($array,$val['pid']));
			}
		}

		return $arr;
	}

	public function formatChild($array, $id, $flag=true, $primary='id', $parent='pid')
	{
		$arr = array();

		foreach($array as $val){
			if($val[$parent] == $id){
				$arr[] = $val[$primary];
				$arr = array_merge($arr,$this->formatChild($array,$val[$primary],false,$primary,$parent));
			}
		}
		if($flag) $arr[] = $id;

		return $arr;
	}
}
