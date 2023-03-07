class Mdata extends CI_Model
{

    function tampil_data()
    {
    /
        *untuk menampilkan data
    */
        return $this->db->get('kompetensi_dasar');
    }

    function input_data($data, $table)
    {
    /
        *untuk memasukkan data
    */
        $this->db->insert($table, $data);
    }

    function hapus_data($where, $table)
    {
    /
        *untuk menghapus data
    */
        $this->db->where($where);
        $this->db->delete($table);
    }

    function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    function update_data($where, $data, $table)
    {
    /
        *untuk mengubah data
    */
        $this->db->where($where);
        $this->db->update($table, $data);
    }
    function sortbynama($table,$field)
    {
    /
        *untuk mengambil data berdasarkan parameter
        *@param $table = untuk memilih table yg dimaksud
        *@author Fairuz
    /
        $this->db->select('');
        $this->db->from($table);

//        sintaks db->orderby salah karena query orderby tidak ada
//      $this->db->orderby($field);
//        harusnya seperti yang dibawah
//
        $this->db->order_by($field);
        return $this->db->get();

    }
    function search($table,$field,$search)
    {
    /
        *untuk melakukan search
    /
        $this->db->select('');
        $this->db->from($table);
        $this->db->like($field,$search);

        return $this->db->get();
    }
    function tampilsiswa()
    {
        $sql_query = $this->db->query("call data_siswa()");
        mysqli_next_result($this->db->conn_id);
        if($sql_query->num_rows()>0){
            return $sql_query->result();
        }

    }

}
------------
    public function insertGuru()
    {
        /** 
        *Note : Klaster 1
        *
        *Function untuk insert guru
Expand
message.txt
8 KB
﻿
    public function insertGuru()
    {
        /** 
        *Note : Klaster 1
        *
        *Function untuk insert guru
        *@author Nur Azizah
        *$nip = menyimpan input post nip, $nama = menyimpan input post nama, $level = menyimpan input post level, $alamat = menyimpan input post alamat
        *$tlp = menyimpan input post telpon, $user = menyimpan input post username, $pass = menyimpan input post password, dan $foto = menyimpan input variabel global $_FILES
        *Setelah seluruh input di simpan pada satu variable, inputan dimasukkan ke variable $data dan disesuaikan dengan value untuk input.
        */
        $nip = $this->input->post('nip');
        $nama = $this->input->post('nama');
        $level = $this->input->post('level');
        $alamat = $this->input->post('alamat');
        $tlp = $this->input->post('telpon');
        $user = $this->input->post('username');
        $pass = $this->input->post('password');
        $bs = $this->input->post('studi');
        $foto = $_FILES['foto'];

        if($foto==''){
            $foto = "default.jpg";
        }else{
            $config['upload_path'] = 'assets/img/foto';
            $config['allowed_types'] = 'jpg|png|jpeg|pdf';

            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('foto')){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">upload gagal!</div>');
                redirect('Admin/tambahGuru');
            }else{
                $foto = $this->upload->data('file_name');
            }
        }

        $data = [
            'id_level' => $level,
            'nip' => $nip,
            'nama_guru' => $nama,
            'alamat_guru' => $alamat,
            'telpon' => $tlp,
            'username' => $user,
            'password' => $pass,
            'foto' => $foto
        ];

        // $this->Admin_model->inputData($data, 'guru');
        $id = $this->Admin_model->inputData('guru', $data);
        
        $detail = [
            'id_guru' => $id,
            'id_bidang_studi' => $bs
        ];
        /* Ketika akan insert ke table lain, muncul error field id_level tidak ditemukan. 
        Setelah mengecheck model, controller, sampai view, ternyata masalah dengan data yang dikirim. Alih-alih mengirim data 
        detail, script di bawah ini justru mengirimkan data untuk table guru.  */
        // $this->Admin_model->input_data($data, 'detail_guru');
        /* Maka script perbaikan untuk input ini adalah sebagai berikut : */
        $this->Admin_model->input_data($detail, 'detail_guru');
        
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tambah Data Berhasil!</div>');
        redirect('Admin/guru');
    }
    public function updateGuru($id)
    {
        /** 
        *Note : Klaster 1
        *
        *Function untuk update guru
        *@author Nur Azizah
        *@param $id = id guru yang dipilih sebelumnya, digunakan untuk menentukan data mana yang di update
        *$nip = menyimpan input post nip, $nama = menyimpan input post nama, $level = menyimpan input post level, $alamat = menyimpan input post alamat
        *$tlp = menyimpan input post telpon, $user = menyimpan input post username, $pass = menyimpan input post password, dan $foto = menyimpan input variabel global $_FILES
        *Setelah seluruh input di simpan pada satu variable, inputan dimasukkan ke variable $data dan disesuaikan dengan value untuk input.
        *sistem akan mengupdate data dengan id guru yang sesuai dengan $id
        */
        $nip = $this->input->post('nip');
        $nama = $this->input->post('nama');
        $level = $this->input->post('level');
        $alamat = $this->input->post('alamat');
        $tlp = $this->input->post('telpon');
        $user = $this->input->post('username');
        $pass = $this->input->post('password');
        $bs = $this->input->post('studi');
        $foto = $this->input->post('foto');

        $data = [
            'id_level' => $level,
            'nip' => $nip,
            'nama_guru' => $nama,
            'alamat_guru' => $alamat,
            'telpon' => $tlp,
            'username' => $user,
            'password' => $pass,
            'foto' => $foto
        ];
        $where = ['id' => $id];
        $this->Admin_model->update_data($where, $data, 'guru');
        
        $key = ['id_guru' => $id];
        $data2 = [
            'id_bidang_studi' => $bs
        ];

        $this->Admin_model->update_data($key,  $data2, 'detail_guru');
        
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Ubah Data Berhasil!</div>');
        redirect('Admin/guru');
    }
    public function detailGuru($id)
    {
        /** 
        *Note : Klaster 1
        *
        *Function untuk detail guru
        *@author Nur Azizah
        *@param $id = id guru
        *parameter $id digunakan untuk mencari data guru yang di pilih user. setelah itu, sistem akan mengirimkan seluruh data dalam $data['guru]
        */
        $where = ['id_guru' => $id];
        $data['user'] = $this->db->get_where('guru',['username' => $this->session->userdata['username']])->row_array();
        /* ketika melakukan var_dump($data['guru']);, diharapkan memuat data spesifikasi satu guru tetapi justru tampil:
           object(CI_DB_mysqli_result)#23 (8) { ["conn_id"]=> object(mysqli)#15 (19) { ["affected_rows"]=> int(1)
           ["client_info"]=> string(79) "mysqlnd 5.0.12-dev - 20150407 - $Id: b396954eeb2d1d9ed7902b8bae237b287f21ad9e $" 
           ["client_version"]=> int(50012) ["connect_errno"]=> int(0) ["connect_error"]=> NULL ["errno"]=> int(0) ["error"]=> string(0)
           "" ["error_list"]=> array(0) { } ["field_count"]=> int(13) ["host_info"]=> string(20) "localhost via TCP/IP" ["info"]=> NULL ["insert_id"]=> int(0) ["server_info"]=> string(21) "5.5.5-10.1.29-MariaDB" ["server_version"]=> int(50505) ["stat"]=> string(135) "Uptime: 11755 Threads: 1 Questions: 3251 Slow queries: 0 Opens: 41 Flush tables: 1 Open tables: 35 Queries per second avg: 0.276" ["sqlstate"]=> string(5) "00000" ["protocol_version"]=> int(10) ["thread_id"]=> int(563) ["warning_count"]=> int(0) } ["result_id"]=> object(mysqli_result)#22 (5) { ["current_field"]=> int(0) ["field_count"]=> int(13) ["lengths"]=> NULL ["num_rows"]=> int(1) ["type"]=> int(0) } ["result_array"]=> array(0) { } ["result_object"]=> array(0) { } ["custom_result_object"]=> array(0) { } ["current_row"]=> int(0) ["num_rows"]=> NULL ["row_data"]=> NULL }
        Setelah mengecheck kembali, maka penulisan kode salah :
            $data['guru'] = $this->Admin_model->edit_data($where, 'listguru');
        Diubah menjadi kode dibawah ini dan tampil dengan benar
        */
        $data['guru'] = $this->Admin_model->edit_data($where, 'listguru')->result();
        // var_dump($data['guru']); die();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/gurudetail', $data);
    }
message.txt
8 KB