<?php
namespace Webit\Tools\Date;
class DateRange {
	const SPLIT_TO_PERIOD = 'period';
	const SPLIT_TO_RANGES = 'ranges';
	
	/**
	 * 
	 * @var \DateTime
	 */
	private $dateFrom;

	/**
	 * 
	 * @var \DateTime
	 */
	private $dateTo;

	public function __construct(\DateTime $dateFrom, \DateTime $dateTo = null) {
		$this->dateFrom = clone ($dateFrom);
		$this->dateTo = $dateTo ? clone ($dateTo) : new \DateTime();
	}

	/**
	 * 
	 * @return \DateTime
	 */
	public function getDateFrom() {
		return $this->dateFrom;
	}

	/**
	 * 
	 * @return \DateTime
	 */
	public function getDateTo() {
		return $this->dateTo;
	}
	
	/**
	 * 
	 * @param \DateInterval $interval
	 * @param \DateInterval $precision
	 * @return array<DateRange>
	 */
	public function splitToRanges(\DateInterval $interval,\DateInterval $precision = null) {
		$precision = $precision ?: new \DateInterval('PT1S');
		$arRanges = array();
		$period = $this->splitToPeriod($interval);
		// TODO: 
	}
	
	/**
   * @return \DatePeriod
	 */
	public function splitToPeriod(\DateInterval $interval) {
		return new \DatePeriod($this->dateFrom, $interval, $this->dateTo);
	}
}
?>
