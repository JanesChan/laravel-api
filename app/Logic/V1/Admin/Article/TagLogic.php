<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2019/3/14
 * Time: 0:08
 */

namespace App\Logic\V1\Admin\Article;


use App\Logic\LoadDataLogic;
use App\Model\Exception;
use App\Model\V1\Article\TagModel;

class TagLogic extends LoadDataLogic
{
    protected $tagId = 0;

    protected $name = '';

    protected $status = 0;

    protected $description = '';

    /**
     * @return \DdvPhp\DdvPage
     */
    public function lists(){
        $res = (new TagModel())
            ->latest()
            ->getDdvPage();
        return $res->toHump();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function store(){
        $tagModel = new TagModel();
        $tagModel->name = $this->name;
        $tagModel->status = $this->status;
        $tagModel->description = $this->description;
        if (!$tagModel->save()){
            throw new Exception('添加标签失败','STORE_TAG_FAIL');
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function update(){
        $tagModel = (new TagModel())->where('tag_id',$this->tagId)->first();
        if (empty($tagModel)){
            throw new Exception('标签不存在','TAG_NOT_FIND');
        }
        $tagModel->name = $this->name;
        $tagModel->status = $this->status;
        $tagModel->description = $this->description;
        if (!$tagModel->save()){
            throw new Exception('添加标签失败','STORE_TAG_FAIL');
        }
        return true;
    }

    /**
     * @return \DdvPhp\DdvUtil\Laravel\Model
     * @throws Exception
     */
    public function show(){
        $tagModel = (new TagModel())->where('tag_id',$this->tagId)->first();
        if (empty($tagModel)){
            throw new Exception('标签不存在','TAG_NOT_FIND');
        }
        return $tagModel->toHump();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function destroy(){
        $tagModel = (new TagModel())->where('tag_id',$this->tagId)->first();
        if (empty($tagModel)){
            throw new Exception('标签不存在','TAG_NOT_FIND');
        }
        if (!$tagModel->delete()){
            throw new Exception('标签删除失败','TAG_DESTROY_FIND');
        }
        return true;
    }
}