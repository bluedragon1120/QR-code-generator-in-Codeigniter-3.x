<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model(array('M_Pegawai'  => 'pegawai'));
    }
 
    function index(){
        $data['data']=$this->pegawai->get_all_pegawai();
        $this->load->view('v_pegawai',$data);
    }
 
    function simpan(){
        $nip=$this->input->post('nip');
        $nama_pegawai=$this->input->post('nama_pegawai');
 
        $this->load->library('ciqrcode'); //pemanggilan library QR CODE
 
        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/'; //string, the default is application/cache/
        $config['errorlog']     = './assets/'; //string, the default is application/logs/
        $config['imagedir']     = './assets/images/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224,255,255); // array, default is array(255,255,255)
        $config['white']        = array(70,130,180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);
 
        $image_name=$nip.'.png'; //buat name dari qr code sesuai dengan nip
 
        $params['data'] = $nip; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
 
        $this->pegawai->simpan_pegawai($nip,$nama_pegawai,$image_name); //simpan ke database
        redirect('pegawai'); //redirect ke pegawai usai simpan data
    }
}