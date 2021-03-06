<?php namespace app\modules\content\models; 
use app\modules\content\models\Widget;
use yii\helpers\Html;
use app\core\DB;
use app\modules\content\Classes;
class Field extends \app\core\ActiveRecord 
{ 
 
	public static function tableName()
    {
        return 'content_type_field';
    } 
    function scenarios() {
		 return array( 
		 	'all' => array('slug','name','pid','memo'), 
		 );
	}
	
	public function rules()
	{ 
		return array(
			array('slug, name, pid', 'required'), 
		 	array('slug', 'match','pattern'=>'/^[a-z_]/', 'message'=>__('match')), 
		  	array('slug', 'check'),
		);
	} 
	//检查原密码是否正确
	function check($attribute){
		if(in_array($this->$attribute, Classes::default_columns())){
			$this->addError('slug',__('slug not allowed')); 
		}
		$model = static::find()->where(array('slug'=>$this->$attribute,'pid'=>$this->pid))->one();
		if($model){
			if(!$this->id){
				$this->addError('slug',__('slug & name is unique')); 
			}else if($this->id !== $model->id){
				$this->addError('slug',__('slug & name is unique')); 
			}
		}
		 
	}
	function create_table($name){
		$sql = "CREATE TABLE IF NOT EXISTS `node_".$name."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `created` int(11) NOT NULL,
		  `updated` int(11) NOT NULL,
		  `uid` int(11) NOT NULL,
		  `admin` tinyint(1) NOT NULL DEFAULT '1',
		  `display` tinyint(1) NOT NULL DEFAULT '1',
		  `sort` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; 
		CREATE TABLE IF NOT EXISTS `node_".$name."_relate` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nid` int(11) NOT NULL,
		  `fid` int(11) NOT NULL, 
		  `value` int(11) NOT NULL, 
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	 
		\Yii::$app->db->createCommand($sql)->execute();
	}
	function getLink(){
		/**
		* 判断是否有下一级的URL
		*/
		if($model = static::find(array('pid'=>$this->id)))
			return Html::a(__('link'),url('content/site/index',array('pid'=>$this->id)));
		return Html::a(__('return back'),url('content/site/index',array('pid'=>$model->pid)));
	}
	function beforeSave($insert){
		parent::beforeSave($insert);
		$this->relate = $_POST['Field']['relate'];  
		return true;
	}
	function afterSave($insert){  
		parent::afterSave($insert);
 		$model = Widget::find(array(
 			'field_id'=>$this->id 
	 	));
	 	if(!$model){
	 		$model = new Widget;
	 	} 
	 	$model->field_id = $this->id ;
	 	$model->name = $_POST['widget'] ;
	 	$model->save();  
	 	 
	 	//create table
	 	$slug = $this->slug; 
	 	if($this->pid!=0){
	 		$m = static::find(array('id'=>$this->pid));
	 		$slug = $m->slug;
	 	}
	 	$this->create_table($slug);
	 	
	 	\Yii::import('@app/vendor/Spyc');
 	    $rule = \Spyc::YAMLLoad($_POST['rule']);
 	    $this->_validate($rule);
 	    
 	    $widget_config = \Spyc::YAMLLoad($_POST['widget_config']);
 	    $this->_widget_config($widget_config);
 	    //cache Classes cache
 	    
 	    if($this->pid == 0){
 	    	$slug = $this->slug;
 	    }else{
 	    	$model = static::find(array('id'=>$this->pid));
 	    	$slug = $model->slug;
 	    }
 	    $cacheId = "modules_content_Class_structure".$slug;
		cache($cacheId,false);
	  	
	}
	function beforeDelete(){
		parent::beforeDelete();
		$model = Widget::find(array('field_id'=>$this->id ));
		if($model)
			$model->delete();
	 	$model = \app\modules\content\models\Validate::find(array('field_id'=>$this->id ));
	 	if($model)
	 		$model->delete();
	 	return true;
	}
 
	function getwidget(){
		$model = Widget::find(array(
 			'field_id'=>$this->id 
	 	));
		return $model->name;
	}
	function getwidget_config(){
		$model = Widget::find(array(
 			'field_id'=>$this->id 
	 	));
	 	
	 	$all = unserialize($model->memo);
	 	if($all){
	 		foreach($all as $k=>$v){
	 			$str .= $k.":".$v.chr(13);
	 		}
	 	}
		return $str; 
	}
	function getrule(){
		$model = \app\modules\content\models\Validate::find(array(
 			'field_id'=>$this->id 
	 	));
	 	$all = unserialize($model->value);
	 	if($all){
	 		foreach($all as $k=>$v){
	 			$str .= $k.":".$v.chr(13);
	 		}
	 	}
		return $str;
	}
 	function widgets($flag=true,$selected=null){
 		$list = scandir(__DIR__.'/../widget/');
		foreach($list as $vo){   
			if($vo !="."&& $vo !=".." && $vo !=".svn" )
			{ 
				$li[$vo] = $vo;
				$cls = "\app\modules\content\widget\\$vo\widget";
				if(method_exists($cls,'content_type')  ){  
					$rt[$vo] = $cls::content_type($selected);
				}
			}
		}
		if($flag === true)
			return $li;	
		return $rt;
 	}
 	
 	function _widget_config($value){
 		$one = \app\modules\content\models\Widget::find(array(
 			'field_id'=>$this->id
 		));
 		if(!$one){
 			$one = new \app\modules\content\models\Widget; 
 		} 
 		
 		$one->field_id = $this->id;
		$one->memo = serialize($value);
		$one->save();
 	} 
 	/**
 	* set validate
 	*/
 	function _validate($value){
 		$one = \app\modules\content\models\Validate::find(array(
 			'field_id'=>$this->id
 		));
 		if(!$one){
 			$one = new \app\modules\content\models\Validate; 
 		} 
 		
 		$one->field_id = $this->id;
		$one->value = serialize($value);
		$one->save();
 	} 
 	
	public static function active($query)
    {
    	$pid = (int)$_GET['pid']?:0;
        $query->andWhere('pid = '.$pid);
    }
	/**
    * for yaml dropDownList
    */
	function value(){
		$first[0] = __('please select');
		$data = static::find()->where(array('pid'=>0))->all();
		if($data){ 
			foreach($data as $s){
				$out[$s->id] = $s->name;
			}
			$out = $first+$out; 
		}else{
			$out = $first;
		}
		return $out;
	}  
 
	 
}