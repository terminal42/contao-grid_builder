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

class GridBuilder extends \DC_Table
{
    private $preparedDefinitions = array();
    private $lb = "\n";
    private $gutterWidth = 0;
    private $columnWidth = 0;

    /**
     * Public constructor without any params to prevent calling the parent that needs a table
     */
    public function __construct() {}

    /**
     * Checks if the grid has to be built for the given stylesheet
     * @param   \DataContainer
     */
    public function checkBuildGrid(\DataContainer $dc)
    {
        if ($dc->activeRecord->grid_builder_enable && $this->settingsChanged($dc)) {

            $this->buildGrid($dc);
        }
    }

    /**
     * Checks whether the settings have changed to prevent useless generation of definitions
     * @param   \DataContainer
     * @return  boolean
     */
    private function settingsChanged($dc)
    {
        $fields = array_filter($GLOBALS['TL_DCA']['tl_style_sheet']['fields'], function($field) {
            return $field['eval']['isGridBuilderField'];
        });

        // sort fields
        ksort($fields);

        $key = '';
        foreach (array_keys($fields) as $field) {
            $key .= $dc->activeRecord->{$field};
        }

        $hash = md5($key);

        if ($hash == $dc->activeRecord->grid_builder_hash) {
            return false;
        }

        \Database::getInstance()->prepare('UPDATE tl_style_sheet SET grid_builder_hash=? WHERE id=?')
            ->execute($hash, $dc->activeRecord->id);

        return true;
    }

    /**
     * Builds the grid definitions
     * @param   \DataContainer
     */
    private function buildGrid($dc)
    {
        $this->gutterWidth = (int) $dc->activeRecord->grid_builder_gutter;
        $margin =  $this->gutterWidth / 2;
        $columns = (int) $dc->activeRecord->grid_builder_columns;
        $totalWidth = (int) $dc->activeRecord->grid_builder_width;
        $this->columnWidth = (int) (($totalWidth / $columns) - $this->gutterWidth);

        // min-width on body
        $this->prepareDefinition('body', array
        (
            'min-width:' . $totalWidth . 'px;'
        ));

        // grid classes
        for ($i=1;$i<=$columns;$i++) {
            $this->prepareDefinition('.grid_' . $i, array
            (
                'display:inline;',
                'float:left;',
                'position:relative;',
                'margin-left:' . $margin . 'px;',
                'margin-right:' . $margin . 'px;',
                'width:' . $this->calculateGridWidth($i) . 'px;'
            ));
        }

        // push classes
        for ($i=1;$i<$columns;$i++) {
            $this->prepareDefinition('.push_' . $i, array
            (
                'position:relative;',
                'left:' . $this->calculatePushWidth($i) . 'px;'
            ));
        }

        // pull classes
        for ($i=1;$i<$columns;$i++) {
            $this->prepareDefinition('.pull_' . $i, array
            (
                'position:relative;',
                'left:' . $this->calculatePullWidth($i) . 'px;'
            ));
        }

        $this->cleanUpExistingDefinitions($dc);
        $this->writeDefinitions($dc);
    }

    /**
     * Calculate with of a grid class
     * @param   int
     * @return  int
     */
    private function calculateGridWidth($i)
    {
        return ($i * ($this->columnWidth + $this->gutterWidth)) - $this->gutterWidth;
    }

    /**
     * Calculate with of push class
     * @param   int
     * @return  int
     */
    private function calculatePushWidth($i)
    {
        return ($i * $this->columnWidth) + ($i * $this->gutterWidth);
    }

    /**
     * Calculate with of pull class
     * @param   int
     * @return  int
     */
    private function calculatePullWidth($i)
    {
        return -$this->calculatePushWidth($i);
    }

    /**
     * Prepares the definitions array
     * @param   string
     * @param   array
     */
    private function prepareDefinition($selector, $data)
    {
        $set = '';
        foreach ($data as $line) {
            $set .= $line . $this->lb;
        }
        $this->preparedDefinitions[$selector] = $set;
    }

    /**
     * Cleans up old the existing definitions so new ones can be created
     * @param   \DataContainer
     */
    private function cleanUpExistingDefinitions($dc)
    {
        \Database::getInstance()->prepare('DELETE FROM tl_style WHERE pid=? AND is_grid_builder_definition=?')
            ->execute($dc->activeRecord->id, 1);
    }

    /**
     * Writes the definitions to the database
     * @param   \DataContainer
     */
    private function writeDefinitions($dc)
    {
        // reverse order so the sorting is correct
        foreach (array_reverse($this->preparedDefinitions, true) as $selector => $definition) {
            $dc->getNewPosition('new', \Input::get('id'), true);
            $dc->set['selector']                    = $selector;
            $dc->set['own']                         = $definition;
            $dc->set['is_grid_builder_definition']  = 1;
            \Database::getInstance()->prepare('INSERT INTO tl_style %s')
                ->set($dc->set)
                ->execute();
        }
    }
}
