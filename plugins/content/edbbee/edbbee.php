<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*********************************************************
*
* edbbee.php plugin for including dbBee projects as embedded
* javascript code v 1.2
* author    dbBee
* copyright Copyright (C) 2017 dbBee. All rights reserved.
* @license - http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Websites: http://www.dbbee.com
* Technical Support: http://www.dbbee.com
***********************************************************/

jimport( 'joomla.plugin.plugin' );

class plgContentedbbee extends JPlugin
{
        function plgContentedbbee(&$subject, $params)
       {
        
          parent::__construct($subject, $params);
       }
	function onContentPrepare($context, &$article, &$params)
	{
              
		$mainframe = JFactory::getApplication();
 
		if ($mainframe->isAdmin()) {
                    return;
                } 
        $defaults = array(
			'server' => 'thyme.dbbee.com'
		);       
		while (($ini = strpos(strtolower($article->text),"{edbbee")) !== false) {
			$fin = strpos(strtolower($article->text),"}",$ini);
			$code = substr($article->text,$ini+7,$fin-$ini-7);
			$parameters = explode (" ", $code);
				$atts = array();
				foreach ($parameters as $param) {
					$mp=explode("=", $param);
					if ( ! isset($mp[1])) {
					   $mp[1] = null;
					}					
					$atts[$mp[0]] = str_replace(array('"', "'"), '', $mp[1]);
				}
				foreach ( $defaults as $default => $value ) {
					if ( ! @array_key_exists( $default, $atts ) ) {
						$atts[$default] = $value;
					}
				}
				if ($atts['projectkey'] == ''){
				   $html = 'Required edbbee parameter &quot;projectkey&quot; missing!<br/>Please provide valid dbBee project key from your dbBee dashboard at ';
				   $html .= '<a href="https://'.$atts['server'].'" target="_blank">'.$atts['server'].'</a>';
				}else{
					$html ="\n".'<!-- dbBee embed script code plugin v.1.2  http://www.dbbee.com/plugins/joomla/edbbee.zip -->'."\n";
					$html .= '<div id="divdbBeeEmbed"></div>'."\n";
					$html .= '<!--Start_dbBee_Widget-->'."\n";
					$html .= '<script>'."\n";
					$html .= '(function(){'."\n";
					$html .= 'var dbBeeSrv="'.$atts['server'].'";'."\n";
					$html .= 'var dbBeeKey="'.$atts['projectkey'].'";'."\n";
					$html .= 'var scriptElement = document.createElement("script");'."\n";
					$html .= 'scriptElement.type = "text/javascript";'."\n";
					$html .= 'scriptElement.setAttribute("async", true);'."\n";
					$html .= 'scriptElement.src = ("https:" == document.location.protocol ? "https://" : "http://") + dbBeeSrv+"/widget/?url="+dbBeeKey;'."\n";
					$html .= 'var s = document.currentScript || (function() {'."\n";
					$html .= 'var scripts = document.getElementsByTagName(\'script\');'."\n";
					$html .= 'return scripts[scripts.length - 1];'."\n";
					$html .= '})();'."\n";
					$html .= 's.parentNode.insertBefore(scriptElement, s);'."\n";
					$html .= '})();'."\n";
					$html .= '</script>'."\n";
					$html .= '<!--End_dbBee_Widget-->'."\n";
				}
			$article->text = substr_replace($article->text,$html,$ini,$fin+1-$ini);
		}
	 }
}
?>	
