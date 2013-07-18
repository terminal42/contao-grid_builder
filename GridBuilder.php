<?php

class GridBuilder extends \DC_Table
{
    private $preparedDefinitions = array();
    private $lb = "\n";
    private $gutterWidth = 0;
    private $columnWidth = 0;

    public function __construct() {}

    public function checkBuildGrid(\DataContainer $dc)
    {
        if (($styleSheetModel = \StyleSheetModel::findByPk($dc->intId)) !== null) {
            if ($styleSheetModel->grid_builder_enable) {
                $this->buildGrid($styleSheetModel, $dc);
            }
        }
    }

    private function buildGrid($styleSheetModel, $dc)
    {
        $this->gutterWidth = (int) $styleSheetModel->grid_builder_gutter;
        $margin =  $this->gutterWidth / 2;
        $columns = (int) $styleSheetModel->grid_builder_columns;
        $totalWidth = (int) $styleSheetModel->grid_builder_width;
        $this->columnWidth = (int) (($totalWidth / $columns) - $this->gutterWidth);

        // min-width on body
        $this->prepareDefinition('body', array
        (
            'min-width:' . $totalWidth . 'px;'
        ));

        // grid classes
        for ($i=1;$i<=$columns;$i++) {
            $this->prepareDefinition('grid_' . $i, array
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
            $this->prepareDefinition('push_' . $i, array
            (
                'position:relative;',
                'left:' . $this->calculatePushWidth($i) . 'px;'
            ));
        }

        // pull classes
        for ($i=1;$i<$columns;$i++) {
            $this->prepareDefinition('pull_' . $i, array
            (
                'position:relative;',
                'left:' . $this->calculatePullWidth($i) . 'px;'
            ));
        }

        $this->cleanUpExistingDefinitions($styleSheetModel);
        $this->writeDefinitions($dc);
    }

    private function calculateGridWidth($i)
    {
        return ($i * ($this->columnWidth + $this->gutterWidth)) - $this->gutterWidth;
    }

    private function calculatePushWidth($i)
    {
        return ($i * $this->columnWidth) + ($i * $this->gutterWidth);
    }

    private function calculatePullWidth($i)
    {
        return -$this->calculatePushWidth($i);
    }

    private function prepareDefinition($selector, $data)
    {
        $set = '';
        foreach ($data as $line) {
            $set .= $line . $this->lb;
        }
        $this->preparedDefinitions[$selector] = $set;
    }

    private function cleanUpExistingDefinitions($styleSheetModel)
    {
        \Database::getInstance()->prepare('DELETE FROM tl_style WHERE pid=? AND is_grid_builder_definition=?')
            ->execute($styleSheetModel->id, 1);
    }

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