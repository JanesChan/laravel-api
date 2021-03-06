<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2019/1/1
 * Time: 14:37
 */

namespace App\Http\Controllers\V1\Admin\Rbac;


use App\Http\Controllers\Controller;
use App\Logic\V1\Admin\Rbac\RoleLogic;

class RoleController extends Controller
{
    /**
     * @return array
     * @throws \DdvPhp\DdvRestfulApi\Exception\RJsonError
     * @throws \ReflectionException
     */
    public function index(){
        $this->validate(null, [
            'name' => 'string'
        ]);
        $roleLogic = new RoleLogic();
        $roleLogic->load($this->verifyData);
        return $roleLogic->index();
    }

    /**
     * @return array
     * @throws \App\Model\Exception
     * @throws \DdvPhp\DdvRestfulApi\Exception\RJsonError
     * @throws \ReflectionException
     */
    public function store(){
        $this->validate(null, [
            'name' => 'required|string',
            'state' => 'required|integer',
            'description' => 'required|string',
        ]);
        $roleLogic = new RoleLogic();
        $roleLogic->load($this->verifyData);
        if ($roleLogic->store()){
            return [];
        }
    }

    /**
     * 角色详情
     * @param $roleId
     * @return array
     * @throws \App\Model\Exception
     * @throws \DdvPhp\DdvRestfulApi\Exception\RJsonError
     * @throws \ReflectionException
     */
    public function show($roleId){
        $this->validate(['roleId' => $roleId], [
            'roleId' => 'required|integer',
        ]);
        $roleLogic = new RoleLogic();
        $roleLogic->load($this->verifyData);
        return [
            'data' => $roleLogic->show()
        ];
    }

    /**
     * @param $roleId
     * @return array
     * @throws \App\Model\Exception
     * @throws \DdvPhp\DdvRestfulApi\Exception\RJsonError
     * @throws \ReflectionException
     */
    public function update($roleId){
        $this->validate(['roleId' => $roleId], [
            'roleId' => 'required|integer',
            'name' => 'required|string',
            'state' => 'required|integer',
            'description' => 'required|string',
        ]);
        $roleLogic = new RoleLogic();
        $roleLogic->load($this->verifyData);
        if ($roleLogic->update()){
            return [];
        }
    }

    /**
     * @return array
     * @throws \App\Model\Exception
     * @throws \DdvPhp\DdvRestfulApi\Exception\RJsonError
     * @throws \ReflectionException
     */
    public function destroy($roleId){
        $this->validate(['roleId' => $roleId], [
            'roleId' => 'required|integer'
        ]);
        $roleLogic = new RoleLogic();
        $roleLogic->load($this->verifyData);
        if ($roleLogic->destroy()){
            return [];
        }
    }
}