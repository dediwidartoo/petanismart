<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Categories_model');
        $this->load->model('Payment_model');
        $this->load->model('Settings_model');
        $this->load->library('cart');
    }

    public function index(){
        $data['title'] = 'Pembayaran - ' . $this->config->item('app_name');
        $data['css'] = 'payment';
        $data['provinces'] = $this->Payment_model->getProvinces();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('page/payment', $data);
        $this->load->view('templates/footerv2');
    }

    public function getLocation(){
        $id = $this->input->post('id');
        $getLocation = $this->Payment_model->getCity($id);
        $list = "<option></option>";
        foreach($getLocation as $d){
            $list .= "<option value='".$d['city_id']."'>".$d['type'].' '.$d['city_name']."";
        }
        echo json_encode($list);
    }

    public function getService(){
        $jne = $this->Payment_model->getService("jne");
        $pos = $this->Payment_model->getService("pos");
        $tiki = $this->Payment_model->getService("tiki");
        $destination = $this->input->post('destination');
        $db = $this->db->get_where('cost_delivery', ['destination' => $destination])->row_array();
        $list = "<option></option>";
        $cost = "";
        if($db){
            $list .= '<option value="'.$db['price'].'-antar-antar">Diantar oleh Penjual (COD)</option>';
        }
        if(count($jne) > 0){
            foreach($jne as $s){
                $list .= '<option value="'.$s['cost'][0]['value']."-".$s['service'].'-jne">'."JNE"." ".$s['description']." (".$s['service'].")".'</option>';
            };
        }
        if(count($pos) > 0){
            foreach($pos as $s){
                $list .= '<option value="'.$s['cost'][0]['value']."-".$s['service'].'-pos">'."POS"." ".$s['description']." (".$s['service'].")".'</option>';
            };
        }
        if(count($tiki) > 0){
            foreach($tiki as $s){
                $list .= '<option value="'.$s['cost'][0]['value']."-".$s['service'].'-tiki">'."TIKI"." ".$s['description']." (".$s['service'].")".'</option>';
            };
        }
        echo json_encode($list);
    }

    public function succesfully(){
        if($this->cart->total() == ""){
            redirect(base_url());
        }
        $suc = $this->Payment_model->succesfully();
        $rek = $this->db->get('rekening');
        $data['title'] = 'Berhasil - ' . $this->config->item('app_name');
        $data['css'] = '';
        $data['invoice_id'] = $suc;
        $data['rekening'] = $rek;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');
        $this->load->view('page/succesfully', $data);
        $this->load->view('templates/footer_notmpl');
    }
}
