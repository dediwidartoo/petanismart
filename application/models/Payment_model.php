<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    public function getCity($id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=".$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: ". $this->config->item('api_rajaongkir')
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response =  json_decode($response, true);
            return $response['rajaongkir']['results'];
        }
    }

    public function getProvinces(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: ". $this->config->item('api_rajaongkir')
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response =  json_decode($response, true);
            return $response['rajaongkir']['results'];
        }
    }

    public function getService($kurir){
        $dbSetting = $this->db->get('settings')->row_array();
        $origin = $dbSetting['regency_id'];
        $destination = $this->input->post('destination');

        $weight = 0;
        foreach ($this->cart->contents() as $key) {
            $weight += ($key['weight'] * $key['qty']);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=".$origin."&destination=".$destination."&weight=".$weight."&courier=".$kurir."",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ". $this->config->item('api_rajaongkir')
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            return $response['rajaongkir']['results'][0]['costs'];
        }
    }

    public function succesfully(){
        $invoice = substr(time(),7) . substr(rand(),0,3);
        $label = $this->input->post('label', true);
        $name = $this->input->post('name', true);
        $email = $this->input->post('email', true);
        $telp = $this->input->post('telp', true);
        $province = $this->input->post('paymentSelectProvinces', true);
        $regency = $this->input->post('paymentSelectRegencies', true);
        $district = $this->input->post('district', true);
        $village = $this->input->post('village', true);
        $zipcode = $this->input->post('zipcode', true);
        $address = $this->input->post('address', true);
        $courier = $this->input->post('paymentSelectKurir', true);
        $service1 = explode("-", $courier);
        $service2 = $service1[2];
        $ongkir = $service1[0];
        $kurir = $service1[1];
        $totalPrice = $this->cart->total();
        $totalAll = intval($ongkir) + intval($totalPrice);
        $dateInput = date('Y-m-d H:i:s');
        $dateLimit = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d') + 2, date('Y')));

        $data = [
            'invoice_code' => $invoice,
            'label' => $label,
            'name' => $name,
            'email' => $email,
            'telp' => $telp,
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'zipcode' => $zipcode,
            'address' => $address,
            'courier' => $service2,
            'courier_service' => $kurir,
            'ongkir' => $ongkir,
            'total_price' => $totalPrice,
            'total_all' => $totalAll,
            'date_input' => $dateInput,
            'date_limit' => $dateLimit
        ];
        $this->db->insert('invoice', $data);

        $ada = $this->db->get_where('subscriber', ['email' => $email])->row_array();
        if(!$ada){
            $code_subs = time() . rand();
            $data = [
                'email' => $email,
                'date_subs' => $dateInput,
                'code' => $code_subs
            ];
            $this->db->insert('subscriber', $data);
        }

        foreach($this->cart->contents() as $c){
            $data = [
                'id_invoice' => $invoice,
                'product_name' => $c['name'],
                'price' => $c['price'],
                'qty' => $c['qty'],
                'slug' => $c['slug']
            ];
            $this->db->insert('transaction', $data);
        }

        $this->load->library('email');

        $config['charset'] = 'utf-8';
        $config['useragent'] = $this->config->item('app_name');
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '5';
        $config['smtp_user'] = $this->config->item('account_gmail');
        $config['smtp_pass'] = $this->config->item('pass_gmail');
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $table = '';
        foreach ($this->cart->contents() as $c) {
            $table .= '<tr><td>'.$c['name'].'</td><td style="text-align: center">'.$c['qty'].'</td><td>Rp'.number_format($c['price'], 0, ',', '.').'</td><td>Rp'.number_format($c['subtotal'], 0, ',', '.').'</td></tr>';
        };

        $rek = $this->db->get('rekening');
        $rekening = '';
        foreach($rek->result_array() as $r){
            $rekening .= '<p><strong>'.$r['rekening'].'</strong><br/>
            Atas Nama : '.$r['name'].'<br/>
            No Rekening : '.$r['number'].'</p>';
        }

        $this->email->initialize($config);
        $this->email->from($this->config->item('account_gmail'), $this->config->item('app_name'));
        $this->email->to($email);
        $this->email->subject('Konfirmasi Pesanan '.$invoice);
        $this->email->message(
            '<p><strong>Halo '.$name.'</strong><br>
            Terima Kasih telah melakukan pemesanan di toko kami, mohon untuk melakukan pembayaran.<br/>
            Jika sudah melakukan pembayaran, silakan konfirmasi melalui Whatsapp '.$this->config->item('whatsapp').' atau <a href="https://wa.me/'.$this->config->item('whatsappv2').'">klik disini</a> dengan format sebagai berikut:</p>
            <table>
                <tr>
                    <td>1. Kode Pesanan</td>
                </tr>
                <tr>
                    <td>2. Nama Pemilik Rekening</td>
                </tr>
                <tr>
                    <td>3. Transfer Dari Bank</td>
                </tr>
                <tr>
                    <td>4. Transfer Ke Bank</td>
                </tr>
                <tr>
                    <td>Sertakan juga bukti transfer</td>
                </tr>
            </table>
            <br/>
            <table>
                <tr>
                    <td>Kode Pesanan</td>
                    <td><strong>'.$invoice.'</strong></td>
                </tr>
                <tr>
                    <td style="padding-right:20px;">Tanggal Pesanan</td>
                    <td>'.$dateInput.'</td>
                </tr>
            </table>
            <br/>
            <table border="1" style="border-collapse: collapse;">
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            '.$table.'
            </table>
            <br/>
            <table>
                <tr>
                    <td>Total Harga</td>
                    <td>Rp'.number_format($this->cart->total(), 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td>Biaya Pengiriman</td>
                    <td>Rp'.number_format($ongkir, 0, ',', '.').'</td>
                </tr>
                <tr>
                    <td style="padding-right:20px;"><strong>Total Keseluruhan</strong></td>
                    <td><strong>Rp'.number_format($totalAll, 0, ',', '.').'</strong></td>
                </tr>
            </table>
            <p>Silakan pilih metode pembayaran yang tersedia dibawah ini:</p>
            '.$rekening.'
            <br/>
            </p>Pesanan akan dikirim setelah kamu menerima pembayaran Anda. <br/>
            Terima Kasih</p>
            ');
        $this->email->send();
        $this->cart->destroy();
        return ['invoice' => $invoice, 'total' => $totalAll];
    }

}
