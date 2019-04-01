<?php
/**
 * Created by shmilyelva
 * Date: 2019/3/27
 * Time: 下午3:00
 */

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryComposer
{
    private $category;
    //共享变量
    public function __construct(Category $category) {

        $this->category = $category;
    }

    public function compose(View $view) {
        $view->with('navs', $this->category->getNavs());
    }
}
