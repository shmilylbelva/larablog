<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Http\Requests\Admin\LinkRequest;

class LinkController extends BaseController
{
	public function index()
	{
		$list = Link::paginate(20);
		return view('admin.link.index', ['list'=>$list]);
	}

	public function create()
	{
		return view('admin.link.create');
	}

	public function store(LinkRequest $request)
	{
        $data = $request->all();
		$link = Link::create($data);

		return redirect()->route('admin.link.index')->with('success', '创建成功');
	}

	public function edit(Link $link)
	{
		return view('admin.link.create', ['link'=> $link]);
	}

    public function state(Link $link)
    {
        $status = $link->status == 1 ? 0 : 1;
        $title = $status == 1 ? '显示' : '隐藏';
        $link->update(['status'=> $status]);
        return redirect()->back()->with('success', $title.'成功');
    }

	public function update(LinkRequest $request, Link $link)
	{
		$link->update($request->all());
		return redirect()->back()->with('success', '编辑成功');
	}

	public function destroy(Link $link)
	{
		$link->delete();
		return redirect()->route('admin.link.index')->width('success', '删除成功');
	}
}
