<?php

namespace GiorgioSpa\Http\Controllers;

use GiorgioSpa\Http\Requests\MenuRequest;
use GiorgioSpa\Models\Element;
use GiorgioSpa\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{

    public function lists() {
        $page = request('limit', 20);
        $menus = Menu::query()->name(request('name'))->paginate($page);
        return $this->success($menus);
    }

    public function all() {
        $menus = Menu::query()->orderBy('order_num', 'asc')->get();
        return $this->success(make_tree($menus->toArray()));
    }

    public function allWithElements(){
        $menu = Menu::query()->orderBy('order_num', 'asc')->with('elements')->get();
        return $this->success($menu);
    }

    public function parents() {
        $menus = Menu::query()->where('parent_id', 0)->get();
        return $this->success($menus);
    }

    public function detail($id) {
        $detail = Menu::query()->findOrFail($id);
        return $this->success($detail);
    }

    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::query()->findOrFail($id);
        $menu->update(request_intersect([
            'parent_id', 'name', 'title', 'path', 'icon', 'component', 'redirect', 'order', 'hidden', 'affix'
        ]));
        return $this->success($menu);
    }

    public function create(MenuRequest $request)
    {
        $model = new Menu();
        $data = request_intersect([
            'parent_id', 'name', 'title', 'path', 'icon', 'component', 'redirect', 'order', 'hidden', 'affix'
        ]);
        $data['icon'] = is_null($data['icon']) ? 'smile' : $data['icon'];
        $menu = $model->query()->firstOrCreate($data);

        return $this->success($menu);
    }

    public function delete($id)
    {
        if ($id == 1){
            return $this->error($msg = '该菜单内置，不可删除。');
        }
        $menu = Menu::query()->findOrFail($id);

        if (Menu::query()->where('parent_id', $id)->count()) {
            return $this->error($msg = '请先删除该菜单下的子菜单。');
        }
        if (Element::query()->where('menu_id', $id)->count()) {
            return $this->error($msg = '请先删除该菜单下的资源。');
        }

        $menu->delete();
        return $this->success();
    }
}
