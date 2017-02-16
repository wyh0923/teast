<?php

/**
 * 命令返回类封装
 * @author shf
 *
 */
class ResPacket {
	/**
	 * 命令返回状态 0表示处理失败,1表示处理成功,-9表示用户session丢失;
	 *
	 * @var unknown
	 */
	public $status = 1;
	
	/**
	 * 命令返回消息
	 *
	 * @var unknown
	 */
	public $msg = '';
	
	/**
	 * 命令返回的统计总条数,此属性用于分页显示总记录条数
	 *
	 * @var unknown
	 */
	public $count;
	
	/**
	 * 数据，一般是array
	 *
	 * @var unknown
	 */
	public $result;
	
	/**
	 * 每页显示条数,此属性非命令返回，后续添加,用户分页处理
	 *
	 * @var unknown
	 */
	public $perPageSize = 6;
	
	/**
	 * 总页码数,此属性非命令返回，后续添加
	 *
	 * @var unknown
	 */
	public $totalPageNum = 1;
	
	/**
	 * 当前页码
	 *
	 * @var unknown
	 */
	public $currentPageNum = 1;
	
	/**
	 * 显示页码数,默认显示5个页码
	 *
	 * @var unknown
	 */
	private $viewCount = 5;
	
	/**
	 * 当前页面显示的页码,为一个数组
	 *
	 * @var unknown
	 */
	public $viewNumbers;
	
	
	public $attackList;
	public $defenseList;
	
	/**
	 * 構造函數
	 */
	public function __construct() {
		$this->status = 1;
		$this->msg = '';
		$this->count = 0;
		
		$this->currentPageNum = 1;
		$this->totalPageNum = 1;
		$this->viewCount = 5;
		$this->viewNumbers = array ();
	}
	
	/**
	 * 获取总页码
	 */
	public function getTotalPageNum() {
		if ($this->count > 0) {
			if (fmod ( $this->count, $this->perPageSize ) == 0) {
				$this->totalPageNum = $this->count / $this->perPageSize;
			} else {
				$this->totalPageNum = intval ( $this->count / $this->perPageSize ) + 1;
			}
		} else {
			$this->totalPageNum = 1;
		}
	}
	
	/**
	 * 当前要显示的页码
	 */
	public function viewPageNumbers() {
		// 若总页码小于等于默认显示页码,则全部显示
		if ($this->totalPageNum <= $this->viewCount) {
			for($i = 1; $i <= $this->totalPageNum; $i ++) {
				$this->viewNumbers [] = $i;
			}
		} else {
			// if (fmod ( $this->currentPageNum, $this->viewCount ) == 0) {
			// $index = intval ( $this->currentPageNum / $this->viewCount );
			// } else {
			// $index = intval ( $this->currentPageNum / $this->viewCount ) + 1;
			// }
			// for($i = ($index - 1) * $this->viewCount + 1; $i <= $index * $this->viewCount; $i ++) {
			// if ($i > $this->totalPageNum) {
			// } else {
			// $this->viewNumbers [] = $i;
			// }
			// }
			$this->getViewNums ( 1, $this->totalPageNum, $this->currentPageNum, $this->viewCount );
		}
	}
	
	/**
	 * 获取当前显示的页面
	 * 进入此方法前置条件：总页码大于要显示的页码数了
	 *
	 * @param unknown $min
	 *        	最小页码
	 * @param unknown $max
	 *        	最大页面
	 * @param unknown $current
	 *        	当前页面
	 * @param unknown $viewCount
	 *        	显示页码数
	 */
	private function getViewNums($min, $max, $current, $viewCount) {
		if (empty ( $min ) || empty ( $max ) || empty ( $current ) || empty ( $viewCount )) {
			return;
		}
		if ($max < $min) {
			return;
		}
		
		if ($current == $min) { // 当前为第一页
			$this->viewNumbers = array (
					$min,
					$min + 1,
					$min + 2,
					$min + 3,
					$min + 4 
			);
			return;
		}
		if ($current == $max) { // 当前为最大页
			$this->viewNumbers = array (
					$max - 4,
					$max - 3,
					$max - 2,
					$max - 1,
					$max 
			);
			return;
		}
		if (($current - $min) >= 2 && ($max - $current) >= 2) { // 当前页码正好处于中间
			$this->viewNumbers = array (
					$current - 2,
					$current - 1,
					$current,
					$current + 1,
					$current + 2 
			);
			return;
		}
		if (($current - $min) < 2) { // 距离最小不到2
			$this->viewNumbers = array (
					$current - 1,
					$current,
					$current + 1,
					$current + 2,
					$current + 3 
			);
			return;
		}
		if (($max - $current) < 2) { // 距离最大不到2
			$this->viewNumbers = array (
					$current - 3,
					$current - 2,
					$current - 1,
					$current,
					$current + 1 
			);
			return;
		}
	}
}