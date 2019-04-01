<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Handlers\Level;
use DB;

class Article extends Model
{
	protected $fillable = [
		'title', 'content', 'user_id' ,'category_id', 'keyword', 'description', 'status', 'views', 'thumb'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function comment()
	{
		return $this->hasMany(Comment::class);
	}

	public function getLinkUrl()
	{
		return route('article', $this->id);
	}

	public function getTimeUrl()
	{
		return route('time', $this->created_at->toDateString());
	}

	public static function getRecent($limit = 10)
	{
		return self::where('status', 1)->orderBy('id', 'desc')->limit($limit)->get();
	}

	public static function getHot($limit = 10)
	{
		return self::where('status', 1)->orderBy('views', 'desc')->limit($limit)->get();
	}

	public static function getFile()
	{
		$files = DB::table('articles')->select(DB::raw('count(*) as num, substring(created_at, 1, 7) as pub_date'))->groupBy('pub_date')->orderBy('pub_date', 'desc')->get();
		return $files;
	}

	public function getPrev()
	{
		$category_ids = $this->getChildArr($this->category_id);
		$article = self::where('status', 1)->where('id','<',$this->id)->whereIn('category_id', $category_ids)->orderBy('id','desc')->first();

		if($article){
			return '<a href="'.$article->getLinkUrl().'">'.e($article->title).'</a>';
		}

		return '没有了';
	}

	public function getNext()
	{
		$category_ids = $this->getChildArr($this->category_id);
		$article = self::where('status', 1)->where('id','>',$this->id)->whereIn('category_id', $category_ids)->orderBy('id','asc')->first();

		if($article){
			return '<a href="'.$article->getLinkUrl().'">'.e($article->title).'</a>';
		}

		return '没有了';
	}

	public function getTags()
	{
		if($this->keyword){
			return explode(',', $this->keyword);
		}

		return [];
	}

	public static function getSearch($request)
	{
		$tag = $request->tag;
		$time = $request->time;
		$keyword = $request->keyword;

		$map = [
			'status' => 1,
		];

		$search = '';

		if($tag){
			$map[] = ['keyword', 'like', "%$tag%"];
			$search = $tag;
		}
		if($keyword){
			$map[] = ['title', 'like', "%$keyword%"];
			$search = $keyword;
		}
		if($time){
			$map[] = ['created_at', 'like', "$time%"];
			$search = $time;
		}

		$list = self::where($map)->orderBy('id', 'desc')->paginate(10);

		return ['list'=> $list, 'search'=> $search];
	}

	public function getChildArr($category_id)
	{
		static $childs_id_arr = [];

		if(empty($childs_id_arr)){
			$categorys = Category::all();
			$level = new Level;
			$childs_id_arr = $level->formatChild($categorys, $category_id);
		}

		return $childs_id_arr;
	}
}
