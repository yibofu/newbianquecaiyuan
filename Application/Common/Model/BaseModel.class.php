<?php
namespace Common\Model;

use Think\Model;

class BaseModel extends Model
{
    protected $notSoftDelete = false;
    /**
     * 新增数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @param boolean $replace 是否replace
     * @return mixed
     */
    public function add($data='',$options=array(),$replace=false)
    {
        $data['create_time'] = formateTime();
        return parent::add($data,$options,$replace);
    }

    /**
     * 保存数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @return boolean
     */
    public function save($data='', $options=array())
    {
        $data['update_time'] = formateTime();
        return parent::save($data,$options);
    }

    /**
     * 删除数据
     * @access public
     * @param mixed $options 表达式
     * @return mixed
     */
    public function delete($options=array())
    {
        $data['delete_time'] = formateTime();
        $data['is_delete'] = '1';
        if(empty($data)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $data           =   $this->data;
                // 重置数据
                $this->data     =   array();
            }else{
                $this->error    =   L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // 数据处理
        $data       =   $this->_facade($data);
        if(empty($data)){
            // 没有数据则不执行
            $this->error    =   L('_DATA_TYPE_INVALID_');
            return false;
        }

        $pk   =  $this->getPk();
        if(empty($options) && empty($this->options['where'])) {
            // 如果删除条件为空 则删除当前数据对象所对应的记录
            if(!empty($this->data) && isset($this->data[$pk]))
                return $this->delete($this->data[$pk]);
            else
                return false;
        }
        if(is_numeric($options)  || is_string($options)) {
            // 根据主键删除记录
            if(strpos($options,',')) {
                $where[$pk]     =  array('IN', $options);
            }else{
                $where[$pk]     =  $options;
            }
            $options            =  array();
            $options['where']   =  $where;
        }
        // 根据复合主键删除记录
        if (is_array($options) && (count($options) > 0) && is_array($pk)) {
            $count = 0;
            foreach (array_keys($options) as $key) {
                if (is_int($key)) $count++;
            }
            if ($count == count($pk)) {
                $i = 0;
                foreach ($pk as $field) {
                    $where[$field] = $options[$i];
                    unset($options[$i++]);
                }
                $options['where']  =  $where;
            } else {
                return false;
            }
        }
        // 分析表达式
        $options    =   $this->_parseOptions($options);
        if(empty($options['where'])){
            // 如果条件为空 不进行删除操作 除非设置 1=1
            return false;
        }

        if(!isset($options['where']) ) {
            // 如果存在主键数据 则自动作为更新条件
            if (is_string($pk) && isset($data[$pk])) {
                $where[$pk]     =   $data[$pk];
                unset($data[$pk]);
            } elseif (is_array($pk)) {
                // 增加复合主键支持
                foreach ($pk as $field) {
                    if(isset($data[$field])) {
                        $where[$field]      =   $data[$field];
                    } else {
                        // 如果缺少复合主键数据则不执行
                        $this->error        =   L('_OPERATION_WRONG_');
                        return false;
                    }
                    unset($data[$field]);
                }
            }
            if(!isset($where)){
                // 如果没有任何更新条件则不执行
                $this->error        =   L('_OPERATION_WRONG_');
                return false;
            }else{
                $options['where']   =   $where;
            }
        }

        if(is_array($options['where']) && isset($options['where'][$pk])){
            $pkValue    =   $options['where'][$pk];
        }

        if(false === $this->_before_delete($options)) {
            return false;
        }

        $result     =   $this->db->update($data,$options);
        if(false !== $result && is_numeric($result)) {
            if(isset($pkValue)) $data[$pk]   =  $pkValue;
            $this->_after_delete($data,$options);
        }
        return $result;
    }

    /**
     * 查询数据集
     * @access public
     * @param array $options 表达式参数
     * @return mixed
     */
    public function select($options=array())
    {
        $this->_addSoftDeleteWhere();
        return parent::select($options);
    }

    /**
     * 获取一条记录的某个字段值
     * @access public
     * @param string $field  字段名
     * @param string $spea  字段数据间隔符号 NULL返回数组
     * @return mixed
     */
    public function getField($field,$sepa=null)
    {
        $this->_addSoftDeleteWhere();
        return parent::getField($field,$sepa);
    }

    /**
     * 创建数据对象 但不保存到数据库
     * @access public
     * @param mixed $data 创建数据
     * @param string $type 状态
     * @return mixed
     */
//    public function create($data='',$type='')
//    {
//        $data = parent::create($data,$type);
//        $data['create_time'] = formateTime();
//        $this->data =   $data;
//        return $data;
//    }



    /**
     * 查询数据
     * @access public
     * @param mixed $options 表达式参数
     * @return mixed
     */
    public function find($options=array())
    {
        $this->_addSoftDeleteWhere();
        return parent::find($options);
    }

    public function setNotSoftDelete($mode = true)
    {
        $this->notSoftDelete = $mode;
        return $this;
    }

    /**
     * 添加软删除查询条件
     * @access protected
     */
    protected function _addSoftDeleteWhere(){
        if($this->notSoftDelete){
            return;
        }
        if(array_key_exists('where', $this->options)){
            $where = $this->options['where'];
        }else{
            $where = array();
        }
        $where['is_delete'] = '0' ;
        $this->options['where'] = $where;
    }
}
