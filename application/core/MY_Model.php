<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Models
 * File: MY_Model.php
 * Description: This is a MY model class for getting/saving/deleting data and extends by other models
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */
class MY_Model extends CI_Model
{
	 
	public $CI, $db ;
	
	function __construct()
	{
		parent::__construct();
		$this->CI = & get_instance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
	}
        
            /*** Generic CRUD functions ***/
        public function getMax($data) {
            $this->db->select_max($data['field']);
            $this->db->where($data['condition_fields_values']);
            $query = $this->db->get($data['table']);
            $max_record = $query->row_array();
            return $max_record[$data['field']];
        }

        public function getMin($data) {
            $this->db->select_min($data['field']);
            $this->db->where($data['condition_fields_values']);
            $query = $this->db->get($data['table']);
            $min_record = $query->row_array();

            return $min_record[$data['field']];
        }

        public function countRecords($data) {
            $query = $this->db->get_where($data['table'], $data['conditions']);
            return $query->num_rows();
        }    

        public function insertRecord($data) {
            //var_dump($data);
            $this->db->insert($data['table'], $data['fields']);
            return $this->db->insert_id();
        }
        
        public function insertBatchRecords($table, $data) {
         
            return $this->db->insert_batch($table, $data); 
        }

        
        public function getRecord($select_fields, $condition_fields_values, $table) {
            $this->db->select($select_fields);
            $this->db->where($condition_fields_values);
            $this->db->from($table);
            $query = $this->db->get();

            return $query->row_array();
        }

        public function getRecords($select_fields, $condition_fields_values, $table) {
            $this->db->select($select_fields);
            $this->db->where($condition_fields_values);
            $this->db->from($table);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function deleteRecords($condition_fields_values, $table) {
            $this->db->where($condition_fields_values);
            $this->db->delete($table);
        }

        public function updateRecords($condition_fields_values, $update_fields_values, $table) {
            $this->db->where($condition_fields_values);
            $this->db->update($table, $update_fields_values);
        }
        
        public function get_data_from_sqlQuery($sql) {
         
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        public function delete_data_from_sqlQuery($sql) {

            $query = $this->db->query($sql);

        }
    
        /*** END Generic CRUD functions ***/
       
}
/* End of file MY_model.php*/
/* Location: ./system/application/core/MY_model.php */
?>