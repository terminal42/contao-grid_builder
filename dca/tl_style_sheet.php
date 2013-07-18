<?php

$GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['default'] = str_replace('{vars_legend}',
    '{grid_builder_legend},grid_builder_enable;{vars_legend}',
    $GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['__selector__'][] = 'grid_builder_enable';

$GLOBALS['TL_DCA']['tl_style_sheet']['subpalettes']['grid_builder_enable'] = 'grid_builder_width,grid_builder_columns,grid_builder_gutter';

$GLOBALS['TL_DCA']['tl_style_sheet']['fields']['grid_builder_enable'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['grid_builder_enable'],
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_style_sheet']['fields']['grid_builder_width'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['grid_builder_width'],
    'inputType'               => 'text',
    'default'                 => 960,
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'                     => "smallint(5) NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_style_sheet']['fields']['grid_builder_columns'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['grid_builder_columns'],
    'inputType'               => 'text',
    'default'                 => 12,
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'                     => "smallint(2) NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_style_sheet']['fields']['grid_builder_gutter'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['grid_builder_gutter'],
    'inputType'               => 'text',
    'default'                 => 20,
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'                     => "smallint(3) NOT NULL default '0'"
);