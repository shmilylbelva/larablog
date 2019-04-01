<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\NodeRequest;
use App\Models\Node;
use App\Handlers\Level;

class NodeController extends BaseController
{
	public function index(Level $level)
	{
		$list = Node::all();
		$list = $level->formatOne($list);

		return view('admin.node.index', ['list'=>$list]);
	}

	public function create(Level $level)
	{
		$list = Node::all();
		$list = $level->formatOne($list);
		return view('admin.node.create', ['list'=> $list]);
	}

	public function store(NodeRequest $request)
	{
		$node = Node::create([
			'title' => $request->title,
			'alias' => $request->alias,
			'name'  => $request->name,
			'pid' => $request->pid,
			'description' => $request->description,
			'class_name' => $request->class_name,
			'sidebar' => $request->sidebar,
		]);

		session()->flash('success', '创建成功');

		return redirect()->route('admin.node.index');
	}

	public function edit(Node $node, Level $level)
	{
		$list = Node::all();
		$list = $level->formatOne($list);

		return view('admin.node.create', ['list'=>$list, 'node'=>$node]);
	}

    public function state(Node $node)
    {
        $status = $node->status == 1 ? 0 : 1;
        $title = $status == 1 ? '显示' : '隐藏';
        $node->update(['status'=> $status]);
        return redirect()->back()->with('success', $title.'成功');
    }

	public function update(NodeRequest $request, Node $node, Level $level)
	{
		$data = $request->all();

		$list = Node::all();
		$childs_id_arr = $level->formatChild($list, $node->id);

		if(in_array($data['pid'], $childs_id_arr)){
			return redirect()->back()->with('danger', '父级节点不能选取子级作为父级');
		}

		$node->update($data);
		return redirect()->back()->with('success', '编辑成功');
	}

	public function destroy(Node $node)
	{
		if($node->hasChild()){
			return redirect()->back()->with('danger', '请先删除子级节点');
		}

		$node->delete();
		return redirect()->route('admin.node.index')->with('success', '删除成功');
	}
}
