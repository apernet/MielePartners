<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Session Class
 *
 * Extends Session library
 *
 * Evita regeneración del id de la sesión
 * 
 */
class MY_Session extends CI_Session {

	function sess_update()
	{   
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}
		
		$sessid = $this->userdata['session_id'];
		$this->userdata['last_activity'] = $this->now;
		
		$cookie_data = NULL;

		// Update the session ID and last_activity field in the DB if needed
		if ($this->sess_use_database === TRUE)
		{
			// set cookie explicitly to only have our session data
			$cookie_data = array();
			foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
			{
				$cookie_data[$val] = $this->userdata[$val];
			}
			$this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now), array('session_id' => $sessid)));
		}
		// Write the cookie
		$this->_set_cookie($cookie_data);	   
	} 
}
?>