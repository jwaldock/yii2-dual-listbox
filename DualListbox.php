<?php
/**
 * @link https://github.com/jwaldock/yii2-dual-listbox/
 * @copyright Copyright (c) 2016 Joel Waldock
 */

namespace jwaldock\duallistbox;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Url;

/**
 * Dual Listbox widget.
 */
class DualListbox extends InputWidget
{
    /**
     *
     * @var string
     */
    const ORIGIN_LIST = 'origin';

    /**
     *
     * @var string
     */
    const DEST_LIST = 'dest';

    /**
     *
     * @var string
     */
    public $inputList = self::DEST_LIST;

    /**
     *
     * @var boolean
     */
    public $filter = true;

    /**
     *
     * @var string
     */
    public $filterText = 'Type to search...';

    /**
     *
     * @var boolean
     */
    public $showing = true;

    /**
     *
     * @var string
     */
    public $showingText = 'Showing ';

    /**
     *
     * @var string
     */
    public $originLabel;

    /**
     *
     * @var string
     */
    public $destLabel;

    /**
     *
     * @var string
     */
    public $buttonClass = 'btn-default';

    /**
     *
     * @var array
     */
    public $items = [];

    /**
     *
     * @var string
     */
    public $originItemsUrl;

    /**
     *
     * @var string
     */
    public $destItemsUrl;

    /**
     *
     * @var boolean
     */
    public $multiSelect = true;

    public $showingOptions = [
        'class' => 'hidden-xs'
    ];

    public $filterOptions = [];

    /**
     *
     * @return string
     */
    protected function generateMainButtons()
    {
        $rightIcon = Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-chevron-right'
        ]);
        $leftIcon = Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-chevron-left'
        ]);
        
        $oneDest = Html::button($rightIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'selected'
        ]);
        
        $allDest = Html::button($rightIcon . $rightIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'all'
        ]);
        
        $oneOrigin = Html::button($leftIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::ORIGIN_LIST,
            'data-move' => 'selected'
        ]);
        
        $allOrigin = Html::button($leftIcon . $leftIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::ORIGIN_LIST,
            'data-move' => 'all'
        ]);
        
        $offset = ! (empty($this->originLabel) && empty($this->destLabel)) ? '25px' : '0px';
        
        $btnsDest = Html::tag('div', $oneDest . $allDest, [
            'class' => 'btn-group-vertical'
        ]);
        $btnsOrigin = Html::tag('div', $oneOrigin . $allOrigin, [
            'class' => 'btn-group-vertical',
            'style' => "margin-bottom: 5px; margin-top: $offset"
        ]);
        
        return Html::tag('div', $btnsOrigin, [
            'class' => 'hidden-xs large-buttons'
        ]) . Html::tag('div', $btnsDest, [
            'class' => 'hidden-xs large-buttons'
        ]);
    }

    /**
     * @return string
     */
    protected function generateMobileButtons()
    {
        $upIcon = Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-chevron-up'
        ]);
        $downIcon = Html::tag('span', '', [
            'class' => 'glyphicon glyphicon-chevron-down'
        ]);
        
        $oneDest = Html::button($downIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'selected'
        ]);
        
        $oneOrigin = Html::button($upIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => self::ORIGIN_LIST,
            'data-move' => 'selected'
        ]);
        
        $buttons = Html::tag('div', $oneDest, [
            'class' => 'btn-group'
        ]) . Html::tag('div', $oneOrigin, [
            'class' => 'btn-group'
        ]);
        
        return Html::tag('div', $buttons, [
            'class' => 'btn-group btn-group-justified small-buttons visible-xs'
        ]);
    }

    /**
     *
     * @param string $name            
     * @param string $label            
     * @param string $itemsUrl            
     * @return string
     */
    protected function generateList($name, $label, $itemsUrl)
    {
        $list = '';
        
        if (! empty($label)) {
            $list .= Html::tag('label', $label, [
                'class' => 'control-label'
            ]);
        }
        
        if ($this->filter) {
            $list .= Html::textInput(null, null, [
                'class' => 'form-control',
                'data-filter' => $name,
                'placeholder' => $this->filterText
            ]);
        }
        
        $listBoxOptions = [
            'class' => 'form-control',
            'data-list' => $name
        ];
        
        if (! empty($itemsUrl)) {
            $listBoxOptions['data-items-url'] = Url::to($itemsUrl);
        }
        
        if ($this->multiSelect) {
            $listBoxOptions['multiple'] = 'multiple';
        }
        
        $listBox = Html::listBox(null, [], $this->items, $listBoxOptions);
        
        $list .= $listBox;
        
        if ($this->showing) {
            $list .= Html::tag('span', $this->showingText . Html::tag('strong', 0, [
                'data-list-count' => $name
            ]), $this->showingOptions);
        }
        return $list;
    }

    /**
     * @return string
     */
    protected function generateHiddenInputsContainer()
    {
        if ($this->hasModel()) {
            $name = Html::getInputName($this->model, $this->attribute);
        } else  {
            $name = $this->name;
        }
        
        return Html::tag('div', '', ['data-list-inputs' => $this->inputList, 'data-input-name' => $name]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = Html::tag('div', $this->generateList(self::ORIGIN_LIST, $this->originLabel, $this->originItemsUrl), [
            'class' => 'col-sm-5'
        ]) . Html::tag('div', $this->generateMainButtons() . $this->generateMobileButtons(), [
            'class' => 'col-sm-2 text-center'
        ]) . Html::tag('div', $this->generateList(self::DEST_LIST, $this->destLabel, $this->destItemsUrl), [
            'class' => 'col-sm-5'
        ]) . $this->generateHiddenInputsContainer();

        echo Html::Tag('div', $content, ['data-role' => 'dual-listbox', 'class' => 'row']);
    }
}
