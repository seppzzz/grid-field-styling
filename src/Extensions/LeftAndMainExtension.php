<?php

namespace XD\GridFieldStyling\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Admin\LeftAndMainExtension as OriginalLeftAndMainExtension;
use SilverStripe\View\Requirements;

/**
 * Workaround to remove CMS Help Button
 *
 * @property LeftAndMain owner
 */
class LeftAndMainExtension extends OriginalLeftAndMainExtension
{
    private static $grid_field_colors = [];

    public function init()
    {
        parent::init();

        $css = '';
        $colors = $this->owner->config()->get('grid_field_colors');
        foreach ($colors as $key => $value) {
            $css .= '.grid-field-cell--' . $key . '{color:' . $value .';} ';
            $css .= '.table tbody tr.grid-field-row--' . $key . '{background:' . $value .'25;}' . "\r\n";
            $css .= '.table tbody tr.grid-field-row--' . $key . ':hover{background:' . $value .'40;}' . "\r\n";
        }

        if (!empty($css)) {
            Requirements::customCSS($css);
        }
    }
}
