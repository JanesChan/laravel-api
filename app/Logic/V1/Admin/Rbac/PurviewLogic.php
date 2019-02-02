<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2019/1/22
 * Time: 23:09
 */

namespace App\Logic\V1\Admin\Rbac;

use App\Logic\LoadDataLogic;
use App\Model\Exception;
use App\Model\V1\Rbac\Purview\ManageModel;
use App\Model\V1\Rbac\Purview\RoleToNodeModel;
use App\Model\V1\Rbac\Purview\UserToRoleModel;
use App\Model\V1\Rbac\Role\RoleModel;
use App\Model\V1\User\UserBaseModel;

class PurviewLogic extends LoadDataLogic
{
    protected $uid = 0;

    protected $uids = [];

    protected $roleIds = [];

    protected $roleId = 0;

    protected $nodeIds = [];

    protected $name = '';

    protected $state = 0;

    protected $type = 0;

    /**
     * 用户权限列表
     */
    public function index(){

    }

    /**
     * 添加用户角色关系
     * @return bool
     * @throws Exception
     */
    public function userToRole(){
        $manageModel = (new ManageModel())->where('uid',$this->uid)->first();
        if (!empty($manageModel)){
            throw new Exception('管理员已存在','HAVE_FIND_USER');
        }
        foreach ($this->roleIds as $roleId){
            (new UserToRoleModel)->firstOrCreate([
                'uid' => $this->uid,
                'role_id' => $roleId
            ]);
        }
        $manageModel = (new ManageModel())->firstOrNew([
            'uid' => $this->uid
        ]);
        $manageModel->state = $this->state;
        $manageModel->type = $this->type;
        if (!$manageModel->save()){
            throw new Exception('添加管理员失败','MANAGE_HAVE_EXISTED');
        }
        return true;
    }

    /**
     * 添加角色节点关系
     * @return bool
     * @throws Exception
     */
    public function roleToNode(){
        $roleModel = (new RoleModel())->where('role_id',$this->roleId)->firstHump();
        if (empty($roleModel)){
            throw new Exception('角色不存在','ROLE_NOT_FIND');
        }
        foreach ($this->nodeIds as $nodeId){
            (new RoleToNodeModel())->firstOrCreate([
                'role_id' => $roleModel->roleId,
                'node_id' => $nodeId
            ]);
        }
        return true;
    }
}