<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Ator_model
 *
 * @author 2813232
 */
class Ator_model extends CI_Model{
    public $id;
    public $nome;
    public $descricao;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function insert($dados){
        $this->db->insert("tb_ator", $dados);
    }
    
    public function get($id = 0){
        if ($id){
            $this->db->where('ator_id', $id);    
        }
        $query = $this->db->get('tb_ator');
        return $query->result_array();
    }
        
    
    public function delete($id){
       $this->db->where('ator_id', $id);
       $this->db->delete('tb_ator');
    }
    
    public function update($dados, $id){
        $this->db->set($dados);
        $this->db->where('ator_id', $id);
        $this->db->update('tb_ator');
    }
}   