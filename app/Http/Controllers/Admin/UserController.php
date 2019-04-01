<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Role;

class UserController extends BaseController
{
	public function index()
	{
		$list = User::where('status', '<>', 2)->orderBy('id', 'desc')->paginate(20);
		return view('admin.user.index', ['list'=> $list]);
	}

	public function create()
	{
		$roles = Role::all();
		return view('admin.user.create', ['roles'=> $roles]);
	}

	public function store(UserRequest $request)
	{
		$user = User::add($request->all());

		return redirect()->route('admin.user.index')->with('success', '创建成功');
	}

    public function state(User $user)
    {
        if($user->id == 1){
            return redirect()->back()->with('success', '禁用超级管理员失败');
        }
        $status = $user->status == 1 ? 0 : 1;
        $title = $status == 1 ? '正常' : '禁用';
        $user->update(['status'=> $status]);
        return redirect()->back()->with('success', $title.'成功');
    }

    public function edit(User $user)
	{
		$roles = Role::all();
		return view('admin.user.create', ['user'=> $user, 'roles'=> $roles]);
	}

	public function update(UserRequest $request, User $user)
	{
		$data = $request->all();

		if($request->password){
			$data['password'] = bcrypt($request->password);
		}else{
			unset($data['password']);
		}

		$user->update($data);

		return redirect()->back()->with('success', '编辑成功');
	}

	public function destroy(User $user)
	{
		$user->update(['status'=> 2]);

		return redirect()->route('admin.user.index')->with('success', '删除成功');
	}
}
