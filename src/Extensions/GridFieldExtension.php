<?php

namespace XD\GridFieldStyling\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ActionMenu;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\URLSegmentFilter;
use XD\GridFieldStyling\Forms\GridField\GridFieldTitleField;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Dev\Debug;

/**
 * Class GridFieldExtension
 * @package XD\GridFieldFilters\Forms\GridField
 * @property GridField $owner
 */
class GridFieldExtension extends Extension
{
    public $colorColumn = 'Status';
    public $colorColumns = [];

    public function visibleFilterForm()
    {
        $this->owner->addExtraClass('visible-filter-form');
        $config = $this->owner->getConfig();
        $config->addComponent(new GridFieldTitleField($this->owner->Title(), 'buttons-before-left'));
        return $this->owner;
    }

    public function denseGrid()
    {
        $this->owner->addExtraClass('dense-grid');
        $this->unhideActions();
        return $this->owner;
    }

    public function unhideActions(){
        $config = $this->owner->getConfig();
        $config->removeComponentsByType(GridField_ActionMenu::class);
        $editButton = $config->getComponentByType(GridFieldEditButton::class);
        $editButton->removeExtraClass('grid-field__icon-action--hidden-on-hover');
        return $this->owner;
    }

    public function coloredColumn($column)
    {
        if( empty( $this->owner->colorColumns )) {
            $this->owner->colorColumns = [];
        }

        $config = $this->owner->getConfig();
        $columns = $config->getComponentByType(GridFieldDataColumns::class);

        if( !isset($this->owner->colorColumns[$column] )) {
            $this->owner->colorColumns[$column] = function ($value, $item) use ($column) {
                $filter = new URLSegmentFilter();
                $class = 'grid-field-cell--' . strtolower($column) . '-' . $filter->filter($value);
                return "<span class='$class'>$value</span>";
            };
        }

        $columns->setFieldFormatting($this->owner->colorColumns);

        return $this->owner;
    }

    public function coloredRows($colorColumn = 'Status')
    {
        $this->owner->colorColumn = $colorColumn;
        $this->owner->addExtraClass('colored-rows');
        return $this->owner;
    }
	
	
	public function conditionColoredRows(...$dateFields)
    {
       
		if (empty($this->owner->colorColumns)) {
            $this->owner->colorColumns = [];
        }

        $this->owner->colorColumns = array_merge($this->owner->colorColumns, $dateFields);
        $this->owner->addExtraClass('c-colored-rows');
		
        return $this->owner;
    }

	
	public function updateNewRowClasses(&$classes, $total, $index, $record)
    {
        if ( !$this->owner->hasExtraClass( 'colored-rows' ) && !$this->owner->hasExtraClass( 'c-colored-rows' ) ) {
        	return;
        }

        $filter = new URLSegmentFilter();
        $config = $this->owner->getConfig();
		
		
		if( $this->owner->hasExtraClass('colored-rows') ) {
			
			/** @var GridFieldDataColumns $columns */
			// $columns = $config->getComponentByType(GridFieldDataColumns::class);
			// die($columns->getColumnContent($this->owner, $record, $this->owner->colorColumn));
			// $value = strip_tags($columns->getColumnContent($this->owner, $record, $this->owner->colorColumn));
			if( $record->hasMethod($this->owner->colorColumn) ){
				$value = ($record->{$this->owner->colorColumn}());
			} else {
				$value = ($record->{$this->owner->colorColumn});
			}
			$classes[] = 'grid-field-row--' . strtolower($this->owner->colorColumn) . '-' . $filter->filter($value);
		}
		
		
		
		
		/**
		* pass as Array at GridField!!
		* $gridField->conditionColoredRows(['DateFieldStart', 'DateFieldEnd']);
		*/
		
        if ( $this->owner->hasExtraClass( 'c-colored-rows' ) ) {

        	$now = DBDatetime::now()->Format( 'Y-m-d' );
        	$i = 0;

        	foreach ( $this->owner->colorColumns as $dateFields ) {

        		$start = $record->getField( $dateFields[ 0 ] );
        		$end = $record->getField( $dateFields[ 1 ] );

        		/*if($i == 0){
        			//Debug::dump("Start Field: {$dateFields[0]}, Value: {$record->getField($dateFields[0])}");
    				//Debug::dump("End Field: {$dateFields[1]}, Value: {$record->getField($dateFields[1])}");
					Debug::dump($start);
        			Debug::dump($end);
				}else{
				die();	
				}*/

        		// Modify or customize the logic based on your requirements
        		switch ( true ) {
        			case $start < $now && $end < $now:
        				$this->addClassOnce( $classes, 'grid-field-row--past-date' );
        				break;
        			case $start <= $now && $end >= $now:
        				$this->addClassOnce( $classes, 'grid-field-row--live-date' );
        				break;
        			case $start > $now && $end > $now:
        				$this->addClassOnce( $classes, 'grid-field-row--future-date' );
        				break;
        		}

        		$i++;

        	}
        }
     }
	
	
	/**
	* Add a class to the array only if it doesn't exist.
	*
	* @param array $classes
	* @param string $classToAdd
	*/
	
	private function addClassOnce(&$classes, $classToAdd) {
		if (!in_array($classToAdd, $classes)) {
			$classes[] = $classToAdd;
		}
	}


}















