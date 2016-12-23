<?php

class BootstrapPagination
{
	public $total = 0;
	public $perPage = 0;
	public $currentPage = 1;
	public $pages;
	protected $links = [];
	
	public $start = 1;
	public $dataStart = 1;
	public $dataEnd = null;
	public $linkHtmlOptions = [];
	public $location = null;
	public $params = [];
	public $pathSeparator = '';
	//'	?';
	public $querySeparator = '&';
	public $keyValueSeparator = '/';//'=';
	public $activePageClass = 'active';
	public $linkTag = 'a';
	public $activeTag = 'span';
	public $classes = [
		'first' => 'first',
		'previous' => 'previous',
		'next' => 'next',
		'last' => 'last',
	];
	public $pagesCutOff = 20;
	public $itemTemplate = "<li class='items'>{item}</li>\n";
	public $itemsTemplate = "<ul data-start='{
		dataStart
	}
	' data-end='{
		dataEnd
	}
	' data-range='{
		dataRange
	}
	' data-current='{
		dataCurrent
	}
	'>\n{first}{previous}{items}{next}{last}</ul>";
	public $strings = [
		'first' => '&laquo;
	&laquo;
	First',
		'previous' => '&laquo;
	Previous',
		'next' => 'Next &raquo;
	',
		'last' => 'Last &raquo;
	&raquo;
	',
	];
	public function __construct($total=0, $page=1, $perPage=0) {
		// Set defaults
		$this->setTotal($total)->setPerPage($perPage)->setPage($page);
	}
	public static function getCountItemPage(){
		return 5;	
	}	
	public function getPages() {
		// Get the number of pages
		$this->pages = ceil($this->total / $this->perPage);
		// Validate current page
		if($this->currentPage < 1) {
			$this->currentPage = 1;
		} elseif($this->currentPage > $this->pages) {
			$this->currentPage = $this->pages;
		}
		// Get our page cut off
		$pageNumbers = $this->getPagesCutOff();
		// Init
		$next = $previous = $first = $last = null;
		// If we have more than one page add the first and previous
		if($this->pages > 1 && $this->currentPage > 1) {
			$first = $this->buildLink($this->strings['first'], $this->start, $this->getLocation(), $this->getParams(), array_merge($this->getLinkHtmlOptions(), array('_class' => 'first')));
			$previous = $this->buildLink($this->strings['previous'], $this->currentPage-1, $this->getLocation(), $this->getParams(), array_merge($this->getLinkHtmlOptions(), array('_class' => 'previous')));
		}
		// Loop and create links
		foreach($pageNumbers as $i) {
			$this->links[] = $this->buildLink($i, $i, $this->getLocation(), $this->getParams(), $this->getLinkHtmlOptions());
		}
		// If we have more than one page add the next and last
		if($this->pages > 1 && $this->currentPage < $this->pages) {
			$next = $this->buildLink($this->strings['next'], $this->currentPage+1, $this->getLocation(), $this->getParams(), array_merge($this->getLinkHtmlOptions(), array('_class' => 'next')));
			$last = $this->buildLink($this->strings['last'], $this->pages, $this->getLocation(), $this->getParams(), array_merge($this->getLinkHtmlOptions(), array('_class' => 'last')));
		}
		return str_replace(
					array(
						'{
		items
	}
	', '{
		total
	}
	', '{
		pages
	}
	', '{
		current
	}
	', '{
		first
	}
	', '{
		previous
	}
	', '{
		next
	}
	', '{
		last
	}
	',
						'{
		dataStart
	}
	', '{
		dataCurrent
	}
	', '{
		dataRange
	}
	', '{
		dataEnd
	}
	', '{
		dataLast
	}
	'
					), 
					array(
						implode(' ', $this->links), $this->total, $this->pages, $this->currentPage, $first, $previous, $next, $last,
						$this->dataStart, $this->currentPage, $this->perPage, $this->dataEnd, $this->pages
					), 
				$this->itemsTemplate);
	}
	protected function getPagesCutOff() {
		$range = floor(($this->pagesCutOff-1)/2);
		if (!$this->pagesCutOff) {
			$pageNumbers = range(1, $this->pages);
		} else {
			$lowerLimit = max($this->currentPage - $range, 1);
			$upperLimit = min($this->pages, $this->currentPage + $range);
			$pageNumbers = range($lowerLimit, $upperLimit);
		}
		// Set data start and end
		$this->dataStart = $lowerLimit;
		$this->dataEnd = $upperLimit;
		return $pageNumbers;
	}
	protected function buildLink($title, $page, $link, $params, $linkHtmlOptions=[]) {
		$htmlOptions = [];
		if($linkHtmlOptions) {
			foreach($linkHtmlOptions as $key => $value) {
				$htmlOptions[$key] = $value;
			}
		}
		$tag = $this->linkTag;
		// Is this page active
		if($this->currentPage == $page) {
			$htmlOptions['_class'] = $this->activePageClass;
			$tag = $this->activeTag;
		} elseif(isset($linkHtmlOptions['first'])) {
			$htmlOptions['_class'] = $this->classes['first'];
		} elseif(isset($linkHtmlOptions['next'])) {
			$htmlOptions['_class'] = $this->classes['next'];
		} elseif(isset($linkHtmlOptions['last'])) {
			$htmlOptions['_class'] = $this->classes['last'];
		} elseif(isset($linkHtmlOptions['previous'])) {
			$htmlOptions['_class'] = $this->classes['previous'];
		}
		$htmlOptions = $this->getHtmlOptions($htmlOptions);
		// Add current page
		$params['page'] = $page;
		$item = "<".$tag.($tag == 'a' ? " href='".$link . $this->getPathSeparator() . str_replace('=', $this->getKeyValueSeparator(), http_build_query($params, '', $this->getQuerySeparator()))."'" : '').$htmlOptions.">".$title."</".$tag.">";
		return str_replace('{
		item
	}
	', $item, $this->itemTemplate);
	}
	protected function getHtmlOptions(array $items) {
		$values = '';
		// Merge _class with class
		if(isset($items['_class'])) {
			if(isset($items['class'])) {
				$class = $items['class'] . ' ' . $items['_class'];
				$items['class'] = $class;
			} else {
				$items['class'] = $items['_class'];
			}
			unset($items['_class']);
		}
		if($items) {
			foreach($items as $key => $value) {
				$rows = [];
				if(is_array($value)) {
					foreach($value as $v) {
						$rows[] = $v;
					}
				} else {
					$rows[] = $value;
				}
				$values .= $key . '="' . implode(' ', $rows) . '" ';
			}
		}
		return !empty($values) ? (' ' . trim($values)) : $values;
	}
	public function getPathSeparator() {
		return $this->pathSeparator;
	}
	public function setPathSeparator($val) {
		$this->pathSeparator = $val;
		return $this;
	}
	public function getKeyValueSeparator() {
		return $this->keyValueSeparator;
	}
	public function setKeyValueSeparator($val) {
		$this->keyValueSeparator = $val;
		return $this;
	}
	public function getQuerySeparator() {
		return $this->querySeparator;
	}
	public function setQuerySeparator($val) {
		$this->querySeparator = $val;
		return $this;
	}
	public function getLinkHtmlOptions() {
		return $this->linkHtmlOptions;
	}
	public function getLocation() {
		if(!$this->location) {
			$this->location = $_SERVER['SCRIPT_NAME'];
		}
		return $this->location;
	}
	public function getParams() {
		return $this->params;
	}
	public function setLocation($location) {
		$this->location = $location;
		return $this;
	}
	public function setLinkHtmlOptions(array $options) {
		$this->linkHtmlOptions = $options;
		return $this;
	}
	public function setParams(array $params) {
		$this->params = $params;
		return $this;
	}
	public function setPage($int) {
		$this->currentPage = (int) $int;
		return $this;
	}
	public function setPerPage($int) {
		$this->perPage = (int) $int;
		return $this;
	}
	public function setTotal($int) {
		$this->total = (int) $int;
		return $this;
	}
}