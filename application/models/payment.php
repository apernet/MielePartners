<?php
require_once('base.php');
require_once(APPPATH.'libraries/VPCPaymentConnection.php');

class Payment extends Base
{

    public function __construct()
    {
        parent::__construct();
    }

    var $bancos=array(
        1=>'American Express',
        2=>'Banamex',
    );

    /**
     * Obtiene los datos necesarios para realizar el pago mediante american express
     */
    function ae_payment_form($cotizaciones_id, $months)
    {
        $rs = array();

        $rs['description'] = $this->description($cotizaciones_id);
        $config = $this->config->item('american_express');
        $rs['name'] = $config['name'];
        $rs["session"] = $this->ae_payment_session_create($cotizaciones_id, $months);

        return $rs;
    }

    /**
     * Obtiene la descripcion del pago
     */
    function description($cotizaciones_id)
    {
        $folio = $this->base->get_dato('folio_compra','cotizaciones',array('id'=>$cotizaciones_id));
        $desc = "Miele: Folio de pedido {$folio}";

        return $desc;
    }

    /**
     * Creates a payment session in american express
     */
    function ae_payment_session_create($cotizaciones_id, $months)
    {
        $config = $this->config->item('american_express');
        $cotizacion = $this->base->read('cotizaciones', $cotizaciones_id);

        if(empty($cotizacion)) {
            return false;
        }

        $config['data']['apiOperation'] = 'CREATE_PAYMENT_PAGE_SESSION';
        $config['data']['order']['id'] = $cotizacion->folio_compra;
        $config['data']['order']['amount'] = $cotizacion->total_cliente;

        $config['data']['paymentPage']['cancelUrl'] = $this->config->item('shop_url') .'frontends/confirmacion/'. $cotizaciones_id;
        $config['data']['paymentPage']['returnUrl'] = $this->config->item('shop_url') .'payments/callback';

        if(!$months) {
            $config['data']['constraints']['paymentPlans']['numberOfPayments'] = 1;
        }

        unset($config['data']['paymentPage.merchant.name']);

        $process = curl_init(sprintf($config['url'], $config['merchant']));

        $data = json_encode($config['data'], JSON_NUMERIC_CHECK);

        $headers = array("Content-Length: " . strlen($data));

        //curl_setopt($process, CURLOPT_VERBOSE, true);
        curl_setopt($process, CURLOPT_USERPWD, $config['user'] . ":" . $config['password'] );
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($process);

        if (!curl_errno($process)) {
            $rs = json_decode($result);
            if ($rs->result && $rs->result === "SUCCESS") {
                $cotizacion->successIndicator = $rs->successIndicator;
                $this->db->where('id', $cotizacion->id);
                $this->db->update('cotizaciones', $cotizacion);
                return $rs->session->id;
            } else {
                return false;
            }
        } else {
            return false;

        }
        curl_close($process);
        return false;
    }

    /**
     * Guarda que el pago ya fue realizado
     */
    function pago_realizado()
    {
        //PAGO CANCELADA DE BANAMEX
        $resultado = @$_GET['vpc_TxnResponseCode'];//debug(@$_GET);exit;
        if($resultado == 'C')
        {
            $cotizaciones_id = $this->session->userdata('cotizaciones_id');
            $this->session->set_flashdata('error', 'Pago cancelado.');
            redirect('frontends/confirmacion/'.$cotizaciones_id);
        }

        // Limpiar carro
        $CI =& get_instance();
        $CI->load->model('Frontend');

        // AMERICAN EXPRESS
        // Obtener indicadores
        $cotizaciones_id = $this->session->userdata('cotizaciones_id');
        $sucessIndicator = $this->base->get_dato('successIndicator','cotizaciones',array('id'=>$cotizaciones_id));
        $resultIndicator = @$_GET['resultIndicator'];
        $CI->Frontend->cotizacion_limpiar();

        if(!empty($sucessIndicator) && !empty($resultIndicator) && $sucessIndicator == $resultIndicator)
        {
            $this->proceso_compra_exitosa($cotizaciones_id,1); // Guardar exito y envia correos
        }
        
        // BANAMEX
        $vpc_MerchTxnRef = @$_GET['vpc_MerchTxnRef'];
        $vpc_TxnResponseCode = @$_GET['vpc_TxnResponseCode'];
        if(!empty($vpc_MerchTxnRef))
        {
            //$data = $_GET;
            
            $data = array(
            	'cotizaciones_id'=>$cotizaciones_id,
            	'vpc_3DSECI'=>@$_GET['vpc_3DSECI'],
            	'vpc_3DSXID'=>@$_GET['vpc_3DSXID'],
            	'vpc_3DSenrolled'=>@$_GET['vpc_3DSenrolled'],
            	'vpc_3DSstatus'=>@$_GET['vpc_3DSstatus'],
            	'vpc_AVSRequestCode'=>@$_GET['vpc_AVSRequestCode'],
            	'vpc_AcqCSCRespCode'=>@$_GET['vpc_AcqCSCRespCode'],
            	'vpc_AcqResponseCode'=>@$_GET['vpc_AcqResponseCode'],
            	'vpc_Amount'=>@$_GET['vpc_Amount'],
            	'vpc_AuthorizeId'=>@$_GET['vpc_AuthorizeId'],
            	'vpc_BatchNo'=>@$_GET['vpc_BatchNo'],
            	'vpc_CSCResultCode'=>@$_GET['vpc_CSCResultCode'],
            	'vpc_Card'=>@$_GET['vpc_Card'],
            	'vpc_CardNum'=>@$_GET['vpc_CardNum'],
            	'vpc_Command'=>@$_GET['vpc_Command'],
            	'vpc_Locale'=>@$_GET['vpc_Locale'],
            	'vpc_MerchTxnRef'=>@$_GET['vpc_MerchTxnRef'],
            	'vpc_Merchant'=>@$_GET['vpc_Merchant'],
            	'vpc_Message'=>@$_GET['vpc_Message'],
            	'vpc_OrderInfo'=>@$_GET['vpc_OrderInfo'],
            	'vpc_ReceiptNo'=>@$_GET['vpc_ReceiptNo'],
            	'vpc_TransactionNo'=>@$_GET['vpc_TransactionNo'],
            	'vpc_TxnResponseCode'=>@$_GET['vpc_TxnResponseCode'],
            	'vpc_VerSecurityLevel'=>@$_GET['vpc_VerSecurityLevel'],
            	'vpc_VerStatus'=>@$_GET['vpc_VerStatus'],
            	'vpc_VerToken'=>@$_GET['vpc_VerToken'],
            	'vpc_VerType'=>@$_GET['vpc_VerType'],
            	'vpc_Version'=>@$_GET['vpc_Version'],
            );
            
            /*$data['cotizaciones_id'] = $cotizaciones_id;
            unset($data['vpc_AVSResultCode']);
            unset($data['vpc_AcqAVSRespCode']);
            unset($data['vpc_SecureHash']);
            unset($data['vpc_SecureHashType']);
            //unset($data['vpc_AcqResponseCode']);
            //unset($data['vpc_AuthorizeId']);*/
            $this->base->guarda('cotizaciones_pagos', $data);

            if($data['vpc_TxnResponseCode']==='0')
            {
                $this->proceso_compra_exitosa($cotizaciones_id,2); // Guardar exito y envia correos
            }
        }

        $this->compra_mail($cotizaciones_id);
        $this->session->set_flashdata('error', 'Compra pendiente de verificación de pago.');
        redirect('frontends/mis_pedidos/id/'.$cotizaciones_id);
    }

    /**
     * Proceso final de compra exitosa
     */
    function proceso_compra_exitosa($cotizaciones_id,$bancos_id)
    {
        // Guardar exito
        $data=array(
            'id'=>$cotizaciones_id,
            'pago_realizado'=>1,
            'bancos_id'=>$bancos_id,
        	'status_id'=>4
        );
        $this->base->guarda('cotizaciones', $data);
        $this->compra_mail($cotizaciones_id);
        $this->session->set_flashdata('done', 'Compra realizada correctamente. Gracias por su compra.');
        redirect('frontends/pedido_detalle/'.$cotizaciones_id);
    }

    /**
     * Enviar correo de venta
     */
    function compra_mail($cotizaciones_id, $output = 'F')
    {
        $CI =& get_instance();
        $CI->load->model('Cotizacion');
        $CI->load->model('Accesorio');
        $CI->load->model('Frontend');
        $CI->load->model('Calificacion');

        $datos['mensualidades'] = $CI->Frontend->mensualidades_datos_get($cotizaciones_id);
        $datos['r']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);
        $datos['productos'] = $CI->Cotizacion->get_productos_cotizacion($cotizaciones_id);
        $datos['accesorios_individuales'] = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);
        if($datos['r']['promociones_id'])
        {
            $datos['regalos'] = $this->Cotizacion->get_regalos($cotizaciones_id);
            $datos['alianzas'] = $this->Cotizacion->get_cotizacion_alianzas($cotizaciones_id);
        }
        $datos['titulo']='Pedido No. '.$datos['r']['folio_compra'];

        $this->db->where('id', $cotizaciones_id);
        $datos['externo']=TRUE;
        $datos['cotizaciones'] = $this->db->get('cotizaciones')->row();
        $datos['cotizaciones']->status_compra = ($datos['cotizaciones']->pago_realizado)?"Pago realizado":"Pendiente de verificación de pago";
        if(@$datos['r']['producto_regalo_id'])
            $datos['cupones_regalos']=$this->base->get_datos('id, nombre, modelo, precio','productos', array('id'=>$datos['r']['producto_regalo_id']));
        if(!empty($datos['cotizaciones']->pago_realizado))
        {
            $this->Calificacion->set_calificacion_mail_productos($datos['cotizaciones']);
        }

        $this->load->library('email');
        $email_sax = $this->config->item('email');
        $this->email->from($email_sax [0], $email_sax [1]);
        $this->email->to($datos['cotizaciones']->email);
        $bcc = $this->config->item('mail_venta_cliente_externo');
        if (! empty($bcc))
            $this->email->bcc($bcc);

        $this->email->subject("Confimación de pedido folio de compra {$datos['cotizaciones']->folio_compra}");
        $mensaje = $this->load->view('email/venta_cliente_externo', $datos, true);

        // Adjuntar PDF
        if($datos['cotizaciones']->pago_realizado)
        {
            $this->Cotizacion->imprimir($cotizaciones_id,$output,TRUE,4);
            $cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
            $path = APPPATH."files/cotizaciones_pdfs/{$cotizacion->folio_compra}.pdf";
            $this->email->attach($path);
        }

        $this->email->message($mensaje);
        $this->email->send();
        if ($this->config->item('debug_mail')) // DEBUG DE MAIL
        {
            debug($this->email->print_debugger(), null, 1);
        }

        return TRUE;
    }

    function banamex_payment_pay($cotizaciones_id, $months=FALSE, $debug=FALSE)
    {
        // Libreria de conexion
        $this->load->library("VPCPaymentConnection");
        $conn = new VPCPaymentConnection();

        // Datos de config
        $config = $this->config->item('banamex');
        $secureSecret = $config['secure_hash'];
        $conn->setSecureSecret($secureSecret);

        // Datos de cotizacion
        $this->db->select('folio_compra,total_cliente');
        $this->db->where('id', $cotizaciones_id);
        $pedido = $this->db->get('cotizaciones')->row();
        $folio = $this->base->get_dato('folio_compra','cotizaciones',array('id'=>$cotizaciones_id));

        if ($debug)
            debug($pedido);

        // Datos complementarios
        $data = $config['data'];
        $data['vpc_MerchTxnRef'] = $folio;
        $data['vpc_Amount'] = str_replace(',','',num($pedido->total_cliente*100,0));
        $data['vpc_OrderInfo'] = 'Miele pedido: '.$folio;

        if($months)
        {
            $meses = $this->base->get_dato('mensualidades','cotizaciones',array('id'=>$cotizaciones_id));
            $data['vpc_CustomPaymentPlanPlanId'] = $config['msi_18_plan_id'];
            $data['vpc_NumPayments'] = $meses;
            //$data['vpc_NumDeferrals'] = $meses;
        }
        else{
            $data['vpc_CppHide'] = 'Y';
        }//debug($data);exit;


        // Ordenar
        ksort($data);

        // Pagina de banamex
        $vpcURL = $config['url'];

        // Datos que se utilizan para crear HASH
        foreach($data as $key => $value)
        {
            if(strlen($value) > 0)
            {
                $conn->addDigitalOrderField($key, $value);
            }
        }

        // Obtain a one-way hash of the Digital Order data and add this to the Digital Order
        $secureHash = $conn->hashAllFields();
        $conn->addDigitalOrderField("vpc_SecureHash", $secureHash);
        $conn->addDigitalOrderField("vpc_SecureHashType", "SHA256");

        // Obtain the redirection URL and redirect the web browser
        $vpcURL = $conn->getDigitalOrder($vpcURL);

        header("Location: ".$vpcURL);
        //echo "<a href=$vpcURL>$vpcURL</a>";
    }

}