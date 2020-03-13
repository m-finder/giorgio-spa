<?php

namespace GiorgioSpa\Http\Controllers;

use GiorgioSpa\Http\Requests\ElementRequest;
use GiorgioSpa\Models\Element;
use Illuminate\Support\Facades\Log;


class ElementController extends Controller
{

    public function lists()
    {
        $page = request('limit', 20);
        $elements = Element::query()->menuId()->paginate($page);
        return $this->success($elements);
    }

    public function detail($id)
    {
        $detail = Element::query()->findOrFail($id);
        return $this->success($detail);
    }

    public function create(ElementRequest $request)
    {
        $model = new Element();
        if (Element::isUnique()) {
            $model->query()->create(request_intersect([
                'menu_id', 'name', 'code', 'method', 'path',
            ]));
        } else {
            return $this->error('该资源已存在');
        }
        return $this->success();
    }

    public function update(ElementRequest $request, $id)
    {
        $element = Element::query()->findOrFail($id);
        if (Element::isUnique()) {
            $element->update(request_intersect([
                'name', 'code', 'method', 'path',
            ]));
        } else {
            return $this->error('该资源已存在');
        }

        return $this->success();
    }

    public function delete($id)
    {
        $element = Element::query()->findOrFail($id);
        $element->delete();
        return $this->success();
    }
}
