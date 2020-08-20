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

use Joomla\Registry\Registry;

class ModCountDownBigHelper
{
	/**
	 * Calculates countdown or countup
	 *
	 * @param integer $eventup
	 *
	 * @return array
	 */
	public function countDown($eventup)
	{
		$difference = $eventup - time();
		$up = 0;

		// Countup
		if($difference < 0)
		{
			$up = 1;
			$difference = $difference * (-1);
		}

		$days_left = floor($difference / 60 / 60 / 24);
		$hours_left = floor(($difference - $days_left * 60 * 60 * 24) / 60 / 60);
		$minutes_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60) / 60);
		$seconds_left = floor(($difference - $days_left * 60 * 60 * 24 - $hours_left * 60 * 60 - $minutes_left * 60));

		return array($days_left, $hours_left, $minutes_left, $seconds_left, $up);
	}

	/**
	 * Sets CSS instructions to the head section
	 *
	 * @param $module_id
	 * @param $color
	 */
	public function setHeadData($module_id, $color)
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet('modules/mod_count_down_big/count_down_big.css');
		$css = '.cdub_color'.$module_id.'{color:'.$color.';}'."\n";
		$document->addStyleDeclaration($css);
	}

	public function getEventText($event)
	{
		$ev_title = false;
		$ev_text = false;

		if(!empty($event[6]))
		{
			$ev_title = JText::_($event[6]);
		}

		if(!empty($event[7]))
		{
			$ev_text = JText::_($event[7]);
		}

		return array($ev_title, $ev_text);
	}

	/**
	 * Loads JS script for the dynamic counter
	 * The code has to be executed after the output of the module was loaded, so the script code is only outputted
	 * directly after the html output and not via the document object to the head section. This could also be solved
	 * with the help of "onload" or a clean JS code in a Framework as Mootools or jQuery (maybe in another release?)
	 *
	 * @param integer $eventup
	 * @param integer $module_id
	 */
	public function countDownDyn($eventup, $module_id)
	{
		// Create JS code in dependence of the module ID to allow more than one dynamic counter per page
		$js = '<script type="text/javascript">// <![CDATA[
            var bigcountdown_now'.$module_id.' = '.time().';
            var bigcountdown_to'.$module_id.' = '.$eventup.';

            bigcountdown_timebetween'.$module_id.' = bigcountdown_to'.$module_id.' - bigcountdown_now'.$module_id.';

            var up = 0;
            if (bigcountdown_timebetween'.$module_id.' < 0) {
                bigcountdown_timebetween'.$module_id.' = bigcountdown_timebetween'.$module_id.' * -1;
                var up = 1;
            }

            var bigcountdown_daysremain'.$module_id.' = 0;
            var bigcountdown_hoursremain'.$module_id.' = 0;
            var bigcountdown_minutesremain'.$module_id.' = 0;
            var bigcountdown_secondsremain'.$module_id.' = bigcountdown_timebetween'.$module_id.';

            if (bigcountdown_timebetween'.$module_id.' >= 60) {
                bigcountdown_secondsremain'.$module_id.' = bigcountdown_timebetween'.$module_id.' % 60;
                bigcountdown_minutesremain'.$module_id.' = (bigcountdown_timebetween'.$module_id.' - bigcountdown_secondsremain'.$module_id.') / 60;
            }

            if (bigcountdown_minutesremain'.$module_id.' >= 60) {
                bigcountdown_timebetween'.$module_id.' = bigcountdown_minutesremain'.$module_id.';
                bigcountdown_minutesremain'.$module_id.' = bigcountdown_timebetween'.$module_id.' % 60;
                bigcountdown_hoursremain'.$module_id.' = (bigcountdown_timebetween'.$module_id.' - bigcountdown_minutesremain'.$module_id.') / 60;
            }

            if (bigcountdown_hoursremain'.$module_id.' >= 24) {
                bigcountdown_timebetween'.$module_id.' = bigcountdown_hoursremain'.$module_id.';
                bigcountdown_hoursremain'.$module_id.' = bigcountdown_timebetween'.$module_id.' % 24;
                bigcountdown_daysremain'.$module_id.' = (bigcountdown_timebetween'.$module_id.' - bigcountdown_hoursremain'.$module_id.') / 24;
            }

            var bigtime'.$module_id.' = document.getElementById("bigtime'.$module_id.'");
            var bigtimetext'.$module_id.' = "";

            if (up == 0) {
                var bigcountdown_timer'.$module_id.' = setInterval(bigCountDownTimer'.$module_id.', 1000);
                var bigtime'.$module_id.' = document.getElementById("bigtime'.$module_id.'");
                var bigtimetext'.$module_id.' = "";
            } else {
                var bigcountdown_timer'.$module_id.' = setInterval(bigCountUpTimer'.$module_id.', 1000);
                document.getElementById("bigafter'.$module_id.'").style.display = "inline";
                var bigtime'.$module_id.' = document.getElementById("bigtime_up'.$module_id.'");
                var bigtimetext'.$module_id.' = "";
            }

            function bigRewriteCountDownSpan'.$module_id.'() {
                bigtimetext'.$module_id.' = "";
                if (up == 0) {
                    bigtimetext'.$module_id.' += (bigcountdown_daysremain'.$module_id.') ? "<span class=\"cdub_font_dyn\">" + bigcountdown_daysremain'.$module_id.' + " " + (bigcountdown_daysremain'.$module_id.' <= 1 ? "'.JText::_('MOD_COUNT_DOWN_BIG_DAY').'</span>" : "'.JText::_("MOD_COUNT_DOWN_BIG_DAYS").'</span>") : "";
                } else {
                    bigtimetext'.$module_id.' += (bigcountdown_daysremain'.$module_id.') ? "<span class=\"cdub_font_dyn\">" + bigcountdown_daysremain'.$module_id.' + " " + (bigcountdown_daysremain'.$module_id.' <= 1 ? "'.JText::_('MOD_COUNT_DOWN_BIG_DAY').'</span>" : "'.JText::_('MOD_COUNT_DOWN_BIG_DAYS2').'</span>") : "";
                }
                if (bigcountdown_daysremain'.$module_id.' > 0) {
                    bigtimetext'.$module_id.' += "<br /><br /><span class=\"cdub_font2\">"
                } else {
                    bigtimetext'.$module_id.' += "<span class=\"cdub_font3\">"
                }
                bigtimetext'.$module_id.' += (bigcountdown_hoursremain'.$module_id.' || bigcountdown_daysremain'.$module_id.') ? bigcountdown_hoursremain'.$module_id.' + (bigcountdown_hoursremain'.$module_id.' <= 1 ? " '.JText::_("MOD_COUNT_DOWN_BIG_HOURSHORT").' : " : " '.JText::_('MOD_COUNT_DOWN_BIG_HOURSHORT').' : ") : "";
                bigtimetext'.$module_id.' += (bigcountdown_minutesremain'.$module_id.' || bigcountdown_hoursremain'.$module_id.' || bigcountdown_daysremain'.$module_id.') ? bigcountdown_minutesremain'.$module_id.' + (bigcountdown_minutesremain'.$module_id.' <= 1 ? " '.JText::_('MOD_COUNT_DOWN_BIG_MINUTESHORT').' : " : " '.JText::_('MOD_COUNT_DOWN_BIG_MINUTESHORT').' : ") : "";
                bigtimetext'.$module_id.' += bigcountdown_secondsremain'.$module_id.' + (bigcountdown_secondsremain'.$module_id.' <= 1 ? " '.JText::_('MOD_COUNT_DOWN_BIG_SECONDSHORT').' " : " '.JText::_('MOD_COUNT_DOWN_BIG_SECONDSHORT').' ");
                bigtimetext'.$module_id.' += "</span>"
                bigtime'.$module_id.'.innerHTML = bigtimetext'.$module_id.';
            }

            function bigCountDownTimer'.$module_id.'() {
                if (bigcountdown_secondsremain'.$module_id.' == 0 && bigcountdown_minutesremain'.$module_id.' == 0 && bigcountdown_hoursremain'.$module_id.' == 0 && bigcountdown_daysremain'.$module_id.' == 0) {
                    clearInterval(bigcountdown_timer'.$module_id.');
                    document.getElementById("bigbefore'.$module_id.'").style.display = "none";
                    document.getElementById("bigafter'.$module_id.'").style.display = "inline";
                    return;
                }

                if (bigcountdown_secondsremain'.$module_id.' > 0) bigcountdown_secondsremain'.$module_id.'--;
                else {
                    bigcountdown_secondsremain'.$module_id.' = (bigcountdown_minutesremain'.$module_id.' || bigcountdown_hoursremain'.$module_id.' || bigcountdown_daysremain'.$module_id.') ? 59 : 0;
                    if (bigcountdown_minutesremain'.$module_id.' > 0) bigcountdown_minutesremain'.$module_id.'--;
                    else {
                        bigcountdown_minutesremain'.$module_id.' = (bigcountdown_hoursremain'.$module_id.' || bigcountdown_daysremain'.$module_id.') ? 59 : 0;
                        if (bigcountdown_hoursremain'.$module_id.' > 0) bigcountdown_hoursremain'.$module_id.'--;
                        else {
                            bigcountdown_hoursremain'.$module_id.' = (bigcountdown_daysremain'.$module_id.') ? 23 : 0;
                            if (bigcountdown_daysremain'.$module_id.') bigcountdown_daysremain'.$module_id.'--;
                        }
                    }
                }

                bigRewriteCountDownSpan'.$module_id.'();
            }

            function bigCountUpTimer'.$module_id.'() {

                if (bigcountdown_secondsremain'.$module_id.' < 59) bigcountdown_secondsremain'.$module_id.'++;
                else {
                    bigcountdown_secondsremain'.$module_id.' = 0;
                    if (bigcountdown_minutesremain'.$module_id.' < 59) bigcountdown_minutesremain'.$module_id.'++;
                    else {
                        bigcountdown_minutesremain'.$module_id.' = 0;
                        if (bigcountdown_hoursremain'.$module_id.' < 23) bigcountdown_hoursremain'.$module_id.'++;
                        else {
                            bigcountdown_hoursremain'.$module_id.' = 0;
                            bigcountdown_daysremain'.$module_id.'++;
                        }
                    }
                }
                bigRewriteCountDownSpan'.$module_id.'();
            }
            // ]]></script>';

		echo $js;

		return;
	}

	/**
	 * Calculates the next or last event
	 *
	 * @param Registry $params
	 *
	 * @return array
	 */
	function dateArray($params)
	{
		$ev_dates = explode("\n", $params->get('ev_dates', ''));
		$ev_year = $params->get('ev_year');
		$ev_month = $params->get('ev_month');
		$ev_day = $params->get('ev_day');
		$ev_hour = $params->get('ev_hour');
		$ev_minute = $params->get('ev_minute');
		$ev_title = $params->get('$ev_title');
		$ev_text = $params->get('ev_text');

		$ev_dates[] = $ev_year.'@'.$ev_month.'@'.$ev_day.'@'.$ev_hour.'@'.$ev_minute.'@'.$ev_title.'@'.$ev_text;

		$ev_dates = array_filter($ev_dates);
		sort($ev_dates);

		$dates_array = array();

		foreach($ev_dates as $ev_value)
		{
			$dates_array[] = explode('@', $ev_value);
		}

		foreach($dates_array as &$dates_value)
		{
			$timestamp = $this->timeStamp($dates_value);
			array_unshift($dates_value, $timestamp);
		}

		$now = time();

		foreach($dates_array as $retun_value)
		{
			if($now < $retun_value[0])
			{
				return $retun_value;
			}
		}

		return array_pop($dates_array);
	}

	/**
	 * Calculates timestamp of an event
	 *
	 * @param array $date
	 *
	 * @return integer
	 */
	private function timeStamp($date)
	{
		$siteOffset = JFactory::getApplication()->get('offset');
		date_default_timezone_set($siteOffset);

		$timestamp = mktime((int)$date[3], (int)$date[4], 0, (int)$date[1], (int)$date[2], (int)$date[0]);

		return $timestamp;
	}

	/**
	 * Calculates dateformat and determines the correct clock title
	 *
	 * @param string $date_format
	 * @param string $ev_year
	 * @param string $ev_month
	 * @param string $ev_day
	 * @param string $ev_hour
	 *
	 * @return array
	 */
	public function dateFormat($date_format, $ev_year, $ev_month, $ev_day, &$ev_hour)
	{
		$event_date = $oclock = '';

		if($date_format == 'dmy')
		{
			$event_date = $ev_day.'.'.$ev_month.'.'.$ev_year;
			$oclock = JText::_('MOD_COUNT_DOWN_BIG_OCLOCK');
		}
		elseif($date_format == 'mdy')
		{
			$event_date = $ev_month.'.'.$ev_day.'.'.$ev_year;
			$oclock = JText::_('MOD_COUNT_DOWN_BIG_OCLOCK');
		}
		elseif($date_format == 'mdy_eng')
		{
			$event_date = $ev_month.'/'.$ev_day.'/'.$ev_year;
			$oclock = $this->timeFormatEng($ev_hour);
		}
		elseif($date_format == 'dmy_eng')
		{
			$event_date = $ev_day.'/'.$ev_month.'/'.$ev_year;
			$oclock = $this->timeFormatEng($ev_hour);
		}

		return array($event_date, $oclock);
	}

	/**
	 * Transformation into the english format
	 *
	 * @param string $ev_hour
	 *
	 * @return string
	 */
	private function timeFormatEng(&$ev_hour)
	{
		$meridiem = 'am';

		if($ev_hour > 12 AND $ev_hour < 25)
		{
			$ev_hour = $ev_hour - 12;
			$meridiem = 'pm';
		}

		return $meridiem;
	}

	/**
	 * Gets name of day - singular or plural
	 *
	 * @param integer $days_left
	 * @param integer $up
	 *
	 * @return string
	 */
	public function daysName($days_left, $up)
	{
		if($days_left == '1')
		{
			return JText::_('MOD_COUNT_DOWN_BIG_DAY');
		}

		if($up == 0)
		{
			return JText::_('MOD_COUNT_DOWN_BIG_DAYS');
		}

		return JText::_('MOD_COUNT_DOWN_BIG_DAYS2');
	}
}
