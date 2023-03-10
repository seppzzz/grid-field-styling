# Silverstripe GridField styling
Add some style to your Silverstripe GridField

## Installation
Install the module trough composer:
```bash
composer require xddesigners/grid-field-styling
```

## Examples
```php
// Configure the gridfield filter form to always show
$gridField->visibleFilterForm();

// Set the gridfield to a dense format
$gridField->denseGrid();

// Removes the GridField_ActionMenu and the class 'grid-field__icon-action--hidden-on-hover'
$gridField->unhideActions();

// Add color to a single column in the row
$gridField->coloredColumn('NameOfColomnToColor');

// Add color to a row by the value of a colomn
$gridField->coloredRows('NameOfColomnThatDeterminesRowColor');
```

You can define your colors in yml on the LeftAndMain.
These are named with the ColomnName and ColomnValue.
```yml
SilverStripe\Admin\LeftAndMain:
  grid_field_colors:
    colomn-value: '#ff6600'
```