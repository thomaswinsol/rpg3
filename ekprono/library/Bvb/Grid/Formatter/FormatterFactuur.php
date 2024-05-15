<?php
class Bvb_Grid_Formatter_FormatterFactuur implements Bvb_Grid_Formatter_FormatterInterface
{

  protected $_options = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->_options = $options;

        if (!isset($this->_options['locale'])) {

            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $locale = Zend_Registry::get('Zend_Locale');
            } else {
                $locale = new Zend_Locale();
            }

            $this->_options = array('locale' => $locale);
        }
    }


    /**
     * Formats a given value
     * @see library/Bvb/Grid/Formatter/Bvb_Grid_Formatter_FormatterInterface::format()
     */
    public function format ($value)
    {
        try {
            $year= substr($value, 0 , 4);
            $volgnr= substr($value, 4 , 4);
        }
        catch (Exception $e) {
            return $value;
        }

        return $year.'-'.$volgnr;
    }
}