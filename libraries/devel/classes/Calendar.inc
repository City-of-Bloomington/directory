<?php
	class Calendar
	{
		private $date;
		private $url;

		public function __construct($year=null,$month=null)
		{
			if ($year || $month)
			{
				if (!$year) { $year = $now['year']; }
				if (!$month) { $month = $now['mon']; }
				$this->date = getdate(mktime(0,0,0,$month,1,$year));
			}
			else { $this->date = getdate(); }

		}


		public function asHTML()
		{
			$firstWeekDay = $this->date['wday'];
			$currentWeekDay = $firstWeekDay;
			$firstweek = true;
			$currentDay = 1;

			$HTML = "
			<table class=\"calendar\">
			<tr><th colspan=\"7\">{$this->date['month']} {$this->date['year']}</th></tr>
			<tr><th>S</th><th>M</th><th>T</th><th>W</th><th>R</th><th>F</th><th>S</th></tr>
			";

			# Loop through all the days of the month
			while($currentDay <= $this->lastDayOfMonth())
			{
				# Draw blank days for the first week
				if ($firstweek)
				{
					$HTML.= "<tr>";
					for($i=1; $i<=$firstWeekDay; $i++) { $HTML.= "<td></td>"; }
					$firstweek = false;
				}

				# Start a new row each sunday
				if ($currentWeekDay == 0) { $HTML.= "<tr>"; }

				# Markup for a single day in the calendar
				if ($this->url) { $HTML.= "<td><a href=\"{$this->url}?date={$this->date['year']}-{$this->date['mon']}-$currentDay\">$currentDay</a></td>"; }
				else { $HTML.= "<td>$currentDay</td>"; }


				# Close off each row on Saturday
				if ($currentWeekDay == 6) { $HTML.= "</tr>"; }



				$currentWeekDay++;
				$currentWeekDay = $currentWeekDay % 7;
				$currentDay++;
			}

			# Finish out the table with blank days
			for($i=$currentWeekDay; $i % 7 != 0; $i++) { $HTML.= "<td></td>"; }


			$HTML.= "
			</tr>
			</table>
			";

			return $HTML;
		}

		private function lastDayOfMonth()
		{
			# Get the last day of the month
			$d = 27;
			while($d <= 32)
			{
				$temp = getdate(mktime(0,0,0,$this->date['mon'],$d,$this->date['year']));
				if ($temp['mon'] != $this->date['mon'])
				{
					return $d-1;
				}
				$d++;
			}
		}

		public function getURL() { return $this->url; }
		public function setURL($url) { $this->url = $url; }

	}
?>