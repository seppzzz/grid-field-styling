<?php

namespace XD\GridFieldStyling\Forms\GridField;

use SilverStripe\Forms\GridField\GridField_HTMLProvider;

class GridFieldTitleField implements GridField_HTMLProvider
{

    protected $targetFragment;
    protected $title;

    /**
     * @param string $targetFragment The HTML fragment to write the button into
     * @param array $exportColumns The columns to include in the export
     */
    public function __construct($title, $targetFragment = "after")
    {
        $this->targetFragment = $targetFragment;
        $this->title = $title;
    }

    /**
     * Place the export button in a <p> tag below the field
     */
    public function getHTMLFragments($gridField)
    {
        return array(
            $this->targetFragment => '<h2 class="grid-field__title title">' . $this->title . '</h2>'
        );
    }
}
