<?php
// write your name and student id here
class Admin_model extends CI_model
{
	public function ubah_data($id, $data)
	{
		$this->db->where('id_pesanan', $id);
		$this->db->update('pesanan', $data);
	}

	public function getOrderData()
	{
		$data = $this->db->get('pesanan');
		return $data->result_array();
	}

	// public function getHistoryData($id){
	//     $this->db->where('id_user',$id);
	//     $this->db->where('status', 'done'); 
	//     $data = $this->db->get('pesanan');
	//     return $data->result_array();
	// }
}
