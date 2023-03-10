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

    private static $grid_field_colors = [
        'statusnice-nieuw' => '#ff6600'
    ];

    public function init()
    {
        parent::init();
        $colors = $this->owner->config()->get('grid_field_colors');
        $css = '';
        foreach ($colors as $key => $value) {
            $css .= '.grid-field-cell--' . $key . '{color:' . $value .';} ';
            $css .= '.table tbody tr.grid-field-row--' . $key . '{background:' . $value .'25;}' . "\r\n";
            $css .= '.table tbody tr.grid-field-row--' . $key . ':hover{background:' . $value .'40;}' . "\r\n";
        }
        Requirements::customCSS($css);
    }
}
