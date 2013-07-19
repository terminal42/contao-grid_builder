<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2013
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_style_sheet']['config']['onsubmit_callback'][] = array('GridBuilder', 'checkBuildGrid');

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['default'] = str_replace('{vars_legend}',
    '{grid_builder_legend},grid_builder_enable;{vars_legend}',
    $GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_style_sheet']['palettes']['__selector__'][] = 'grid_builder_enable';

$GLOBALS['TL_DCA']['tl_style_sheet']['subpalettes']['grid_builder_enable'] = 'grid_builder_width,grid_builder_columns,grid_builder_gutter';


/**
 * Fields
 */
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
