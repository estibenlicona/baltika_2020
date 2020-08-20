<?php
/**
 * @Copyright
 *
 * @package    CountDown-Up Big Module
 * @author     Viktor Vogel <admin@kubik-rubik.de>
 * @version    3.1.1 - 2016-01-22
 * @link       https://joomla-extensions.kubik-rubik.de/cdub-countdown-up-big
 *
 * @license    GNU/GPL
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');

$color = $params->get('color');
$countup = $params->def('countup', 1);
$date_format = $params->get('datef');
$dynamic = $params->def('dynamic', 1);
$ev_ddaysleft = $params->get('ev_ddaysleft');
$ev_dhourshow = $params->get('ev_dhourshow');
$ev_displaydate = $params->get('ev_displaydate');
$ev_displayday = $params->get('ev_displayday');
$ev_displaytitle = $params->get('ev_displaytitle');
$ev_displayurl = $params->get('ev_displayurl');
$ev_displayurl_end = $params->get('ev_displayurl_end');
$ev_dlink_countup = $params->get('ev_dlink_countup');
$ev_url = $params->get('ev_url');
$ev_url_end = $params->get('ev_url_end');
$ev_urltitle = $params->get('ev_urltitle');
$ev_urltitle_end = $params->get('ev_urltitle_end');
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$newwindow = $params->def('newwindow', 1);
$newwindow_end = $params->def('newwindow_end', 1);
$poweredby = $params->def('poweredby', 1);
$showexpired = $params->get('showexpired');
$module_id = $module->id;

// Create a helper object
$start = new ModCountDownBigHelper();

// Set CSS information to the head section
$start->setHeadData($module_id, $color);

// Get next or last event - Array with all needed information
$date_array = $start->dateArray($params);

// Get time to event and countdown status
list($days_left, $hours_left, $minutes_left, $seconds_left, $up) = $start->countDown($date_array[0]);
list($eventdate, $oclock) = $start->dateFormat($date_format, $date_array[1], $date_array[2], $date_array[3], $date_array[4]);
$days_switch = $start->daysName($days_left, $up);

// Set variables of event for the template file
$ev_hour = $date_array[4];
$ev_minute = $date_array[5];

// Get title and description of the event
list($ev_title, $ev_text) = $start->getEventText($date_array);

// Load template for the output
require JModuleHelper::getLayoutPath('mod_count_down_big', $params->get('layout', 'default'));

// Load JS code for dynamic counter
if($dynamic == 1)
{
	$start->countDownDyn($date_array[0], $module_id);
}
