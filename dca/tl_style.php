<?php

$GLOBALS['TL_DCA']['tl_style']['config']['onload_callback'][] = array('GridBuilder', 'checkBuildGrid');
$GLOBALS['TL_DCA']['tl_style']['fields']['is_grid_builder_definition'] = array
(
    'sql'   => "char(1) NOT NULL default ''"
);