<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Michael M Chiwere
 * Description: Library for managing errors. Requires session library
 *
 */
class Error
{

	private $success, $info, $error, $data, $session;
	
	function __construct()
	{
		$this->ci = &get_instance();
		
		//	Name of array variable you use in your controllers to store data that will be sent to the output class
		if(!$this->data) $this->data = & $this->ci->data;	// replace $this->ci->data with name of your data array variable used in controllers
		
		//	Name of variable that will be used to store the errors array. This will be used as the key in the above named array variable and in session data
		$this->session = $this->ci->config->item('error_session_variable');
		if(!$this->session) $this->session = 'session_errors';	// set your value here if not defined in config file
		
		//	Define display container css class names. You can use config file or define right here
		//	Bootstrap classes used by default
		$this->success = $this->ci->config->item('success_class');
		if(!$this->success) $this->success = 'alert alert-success'; // set your value here if not defined in config file
		$this->info = $this->ci->config->item('info_class'); 
		if(!$this->info) $this->info = 'alert alert-info';	// set your value here if not defined in config file
		$this->error = $this->ci->config->item('error_class');
		if(!$this->error) $this->error = 'alert alert-error alert-warning';	// set your value here if not defined in config file
	}


	private function test_err()
	{
		$this->sem('test error message successfully set');
	}


	/* public sem()
	*	Name: Set Error Message 
	*	Descrption: Saves error message to session
	*	$msg: either string or array( 'error_msg'=>'error message', 'error'=>TRUE/FALSE )
	*	'error' / $flag: if there was an error, $flag = TRUE; if operation was successful, then there was no error, therefore $flag = FALSE
	*	if 'error' / $flag === 2, then the message is just informative - just a simple notification
	*	$log = FALSE/ERROR/DEBUG; Whether to write error message to system log
	*	$show = TRUE/FALSE; whether to display error to user or not;
	*
	*/
	public function sem( $msg='', $flag=1, $log=FALSE, $show=1)
	{
		if( !empty($msg) )
		{
			if( is_array($msg) )
			{
				if( isset($msg['error_msg']) && isset($msg['error']) )
				{
					$ar = $msg;
					$msg = $ar['error_msg'];
					$flag = $ar['error'];
				}
			}
			if( $flag===FALSE || $flag===0 ) $flag = $this->success;
			else
			{
				if( $flag===2 ) $flag = $this->info;
				else $flag = $this->error;
			}
			
			$old = $this->ci->session->userdata($this->session);
			$usermsg = $msg;
			
			if($show)
			{
				if( $log!==FALSE && is_string($log)) log_message($log, $msg);
			}
			else
			{
				if( $log!==FALSE && is_string($log)) log_message($log, $msg);
				return;
			}
			
			if(empty($this->data[$this->session]))
			{
				$this->data[$this->session] = array( bin2hex($usermsg)=>array('msg'=>$usermsg,'f'=>$flag) );
				if(is_array($old))
					$this->data[$this->session] = array_merge( $old, $this->data[$this->session] );
			}
			else
			{
				$error = $this->data[$this->session];
				$error[bin2hex($usermsg)] = array('msg'=>$usermsg,'f'=>$flag);
				$this->data[$this->session] = $error;
				
				if(is_array($old))
					$this->data[$this->session] = array_merge( $old, $this->data[$this->session] );
			}
		}
		elseif( !empty($this->data[$this->session]) )
			$this->ci->session->set_userdata( $this->session, $this->data[$this->session] );

	}

	/* 
	*	Get Error Message 
	*	returns array of error messages in the format:
	*	array(
	*		unique_key1=>array( 'msg'=>'...', 'f'=>'error_flag' ),
	*		unique_key2=>array( 'msg'=>'...', 'f'=>'error_flag' ),
	*		unique_key3=>array( 'msg'=>'...', 'f'=>'error_flag' ),
	*		......
	*	)
	*/
	public function gem()
	{
		$msg = $this->ci->session->userdata($this->session);
		
		if(is_array($msg))
		{
			$this->ci->session->unset_userdata($this->session);
			$current = $this->data[$this->session];
			if( is_array($current) )
				return array_merge( $current, $msg );
			else return $msg;
		}
		else
		{
			$msg = $this->data[$this->session];
			if( is_array($msg) )
			{
				return $msg;
			}
			else 
			{
				return array();
			}
		}
	}

}
