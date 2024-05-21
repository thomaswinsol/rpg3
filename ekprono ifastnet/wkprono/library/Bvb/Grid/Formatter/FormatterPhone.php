<?php

/**
 * LICENSE
 *
 * This source file is subject to the new BSD license
 * It is  available through the world-wide-web at this URL:
 * http://www.petala-azul.com/bsd.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to geral@petala-azul.com so we can send you a copy immediately.
 *
 * @package   Bvb_Grid
 * @author    Bento Vilas Boas <geral@petala-azul.com>
 * @copyright 2010 ZFDatagrid
 * @license   http://www.petala-azul.com/bsd.txt   New BSD License
 * @version   $Id: Date.php 1507 2010-12-10 00:00:44Z bento.vilas.boas@gmail.com $
 * @link      http://zfdatagrid.com
 */

class Bvb_Grid_Formatter_FormatterPhone implements Bvb_Grid_Formatter_FormatterInterface
{

    /**
     * Locale to be applied
     * @var mixed
     */
    protected $locale = null;

    /**
     * Date formay
     * @var mixed
     */
    protected $date_format = null;

    /**
     * Format Type to be aplied
     * @var mixed
     */
    protected $type = null;


    /**
     * Constructor
     * @param array $options
     */
    public function __construct ($options = array())
    {
        if ( $options instanceof Zend_Locale ) {
            $this->locale = $options;
        } elseif ( is_string($options) ) {
            $this->date_format = $options;
        } elseif ( is_array($options) ) {
            foreach ( $options as $k => $v ) {
                switch ($k) {
                    case 'locale':
                        $this->locale = $v;
                        break;
                    case 'date_format':
                        $this->date_format = $v;
                        break;
                    case 'type':
                    case 'format_type':
                        $this->type = $v;
                        break;
                    default:
                        throw new Bvb_Grid_Exception(Bvb_Grid_Translator::getInstance()->__("Unknown option '$k'."));
                }
            }
        } elseif ( Zend_Registry::isRegistered('Zend_Locale') ) {
            $this->locale = Zend_Registry::get('Zend_Locale');
        }
    }


    /**
     * Formats a given value
     * @see library/Bvb/Grid/Formatter/Bvb_Grid_Formatter_FormatterInterface::format()
     */
    public function format ($value)
    {
        if (!is_numeric($value)) {
            return "";
        }
    	if ($value==0) {
            return "";
        }
        $value = str_pad($value, 9, "0", STR_PAD_LEFT);       
    	$number = trim($value);    	
    	if(strlen($number) == "9" && ($number{0} == "0"))
    	{
        	if(($number{1} == "2") || ($number{1} == "3") || ($number{1} == "9"))
        	{
            	sscanf($number, "%2s%3s%2s%2s", $zone, $d1, $d2, $d3);
            	$format = @$zone ? "($zone) " : "";
            	$format .= $d1 . '.' . $d2 . '.' . $d3;        
            	return $format;
        	}
        	else
        	{
            	sscanf($number, "%3s%2s%2s%2s", $zone, $d1, $d2, $d3);
            	$format = @$zone ? "($zone) " : "";
            	$format .= $d1 . '.' . $d2 . '.' . $d3;
            	return $format;
        	}        	
    	}   
    	else { 
    		if(strlen($number) == "9" && ($number{0} == "4"))
    		{
    			$number = str_pad($number, 10, "0", STR_PAD_LEFT);  
    			sscanf($number, "%4s%2s%2s%2s", $zone, $d1, $d2, $d3);
            	$format = @$zone ? "($zone) " : "";
            	$format .= $d1 . '.' . $d2 . '.' . $d3;
            	return $format;
    		}
    		else {
    			if(strlen($number) == "9")
    			{
    			$number = str_pad($number, 10, "0", STR_PAD_LEFT);  
    			sscanf($number, "%2s%2s%2s%2s%2s", $d1, $d2, $d3, $d4, $d5);
            	$format = $d1 . '.' . $d2 . '.' . $d3. '.'.$d4.'.'.$d5;
            	return $format;
    			}		
    		}  	
    	}	
    	return $number;
    }
}