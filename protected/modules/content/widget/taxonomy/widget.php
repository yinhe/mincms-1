<?php namespace app\modules\content\widget\taxonomy;  
use app\modules\content\Classes;
use yii\helpers\Html;
use app\core\Arr;
use app\core\DB;
/**
* 
* @author Sun < mincms@outlook.com >
*/
class Widget extends \app\modules\content\widget\taxonomyOne\Widget
{  
 	function value_type(){
	 	return true;
	 }
	function run(){  
		unset($all);
		$name = $this->name;   
 		$relate = $this->structure[$name]['relate'];
 		$root = str_replace('taxonomy:','',$relate); 
 		$all = Classes::all('taxonomy',array('orderBy'=>'sort desc,id desc'),true);   
  	 	foreach($all as $v){
			$taxonomy[$v->id] = $v;
		}  
		$a1[''] = __('please select');    
  		$all = \app\core\Arr::tree($taxonomy,'name','id','pid',$root);  
  		if(!$all) $all = array(); 
  		else{
  			$all = $a1+$all;
  		}  
 		
 		$this->multiple($all);
	}
}