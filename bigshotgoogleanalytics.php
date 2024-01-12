<?php
######################################################################
# BIGSHOT Google Analytics                                           #
# Copyright (C) 2013 by BIGSHOT                                      #
# Homepage   : www.thinkBIGSHOT.com                                  #
# Author     : Jeff Henry                                            #
# Email      : JeffH@thinkBIGSHOT.com                                #
# Version    : 1.8                                                   #
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL          #
######################################################################

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');
jimport( 'joomla.html.parameter');

class plgSystemBigshotgoogleanalytics extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->_plugin = JPluginHelper::getPlugin( 'system', 'bigshotgoogleanalytics' );
        $this->_params = new JRegistry( $this->_plugin->params );
    }
    
    function onAfterRender()
    {
        $mainframe = JFactory::getApplication();
        $web_property_id = $this->params->get('web_property_id', '');
        if($web_property_id == '' || $mainframe->isClient('admin') || strpos($_SERVER["PHP_SELF"], "index.php") === false)
        {
            return;
        }

        $buffer = JFactory::getApplication()->getBody();
        $google_analytics_javascript = "
                  <script async src='https://www.googletagmanager.com/gtag/js?id=".$web_property_id."'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '".$web_property_id."');
</script>";

        
        $buffer = str_replace ("</head>", $google_analytics_javascript."</head>", $buffer);
        JFactory::getApplication()->setBody($buffer);
        return true;
    }
}
?>
