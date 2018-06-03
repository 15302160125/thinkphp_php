<?php 
	namespace app\common\model;
	use think\Model;
	class Article extends Model
	{
		public function author()
		{
			return $this->belongsTo('Author');
		}
		public function category()
		{
			return $this->belongsTo('category');
		}
	}

?>