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

    public function updateNewRowClasses(&$classes, $total, $index, $record)
    {
        if( !$this->owner->hasExtraClass('colored-rows') ) return;
        $filter = new URLSegmentFilter();
        /** @var DataObject $record */
        $config = $this->owner->getConfig();
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

}
