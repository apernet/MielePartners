<?php
//require_once('main.php');
class Cron extends CI_Controller {
	
	function Cron()
	{
		parent::__construct();
	}

	function phpinfo()
	{
		phpinfo();
		exit;
	}

	function send_mail_calificaciones()
	{
		//* 11  * * * /Sites/miele_branch/application/scripts/cron.php miele cron/send_mail_calificaciones/
		$this->load->model('Calificacion');
		$this->Calificacion->get_mail_calificacion();
	}

	function send_mail_calificaciones_intentos()
	{
		//* 7 * * * 5 /Sites/miele_branch/application/scripts/cron.php miele cron/send_mail_calificaciones_intentos/
		$this->load->model('Calificacion');
		$this->Calificacion->send_mail_calificaciones_intentos();
	}

	function notificaciones_referidos_vencidos()
	{
		//* 10  * * * /Sites/miele_branch/application/scripts/cron.php miele cron/notificaciones_referidos_vencidos/
		$this->load->model('Referido');
		$this->Referido->notificaciones_referidos_vencidos();
	}

}