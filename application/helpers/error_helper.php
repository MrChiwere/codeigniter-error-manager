<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CodeIgniter Error Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Michael M Chiwere
 * @link		
 */

// ------------------------------------------------------------------------

if ( ! function_exists('sem'))
{
	function sem($msg='', $f=TRUE, $log=FALSE, $show=TRUE)
	{
		$ci =& get_instance();
		$ci->load->library('error');
		$ci->error->sem($msg,$f,$log,$show);
	}
}

if ( ! function_exists('gem'))
{
	function gem()
	{
		$ci =& get_instance();
		$ci->load->library('error');
		return $ci->error->gem();
	}
}

if(!function_exists('show_errors'))
{
	function show_errors( $inc_ve=TRUE, $flash=FALSE, $field=FALSE )
	{
		$ci =& get_instance();
		$err = gem();
		$echo = '';
		
		if( !empty( $err ) )
		{
			$echo.='<div class="container" id="error_div" style="margin-top:10px;">';
			foreach( $err as $e )
			{
				$echo.='<div class="'.$e['f'].'"><a href="#" class="close" data-dismiss="alert">&times;</a>'.$e['msg'].'</div>';
			}
			$echo.='</div>';
		}

		//	if flashdata errors available
		if($flash&&$field)
		{
			$fa = $ci->session->flashdata($field);
			if($fa)
			{
				$echo.='<div class="container"><div style="margin-top:10px;">';
					if( isset($$field) )
					{
						if( $fa!=$$field ) $echo.=$fa;
						$echo.=$$field;
					}
					else $echo.=$fa;
				$echo.='</div></div>';
			}
		}
		
		//	also display validation_errors
		if($inc_ve)
		{
			$ve = validation_errors('<span>','</span>');
			if(!empty($ve))
			{
				$echo.='<div class="container">';
				$echo.='<div class="alert alert-warning" style="margin-top:10px;"><a href="#" class="close" data-dismiss="alert">&times;</a>'.$ve.'</div></div>';
			}
		}
		echo $echo;
	}
}


/* End of file error_helper.php */
/* Location: ./system/helpers/error_helper.php */
