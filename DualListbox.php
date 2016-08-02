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
 * Dual listbox widget.
 * TODO class documentation
 */
class DualListbox extends InputWidget
{
    /**
     * @var string origin list name
     */
    const ORIGIN_LIST = 'origin';

    /**
     * @var string destination list name
     */
    const DEST_LIST = 'dest';

    /**
     * @var string
     */
    public $inputList = self::DEST_LIST;

    /**
     * @var boolean whether to render filter inputs
     */
    public $filter = true;

    /**
     * @var string filter placeholder text
     */
    public $filterText = 'Type to search...';

    /**
     * @var boolean whether to render showing count
     */
    public $showing = true;

    /**
     * @var string showing count text, e.g. "Showing 10"
     */
    public $showingText = 'Showing ';

    /**
     * @var string origin list label text.
     * 
     * The origin label will be rendered if this property is set.
     */
    public $originLabel;

    /**
     * @var string origin list label text.
     * 
     * The origin label will be rendered if this property is set.
     */    public $destLabel;

    /**
     * @var string|array action bootstrap button class
     */
    public $buttonClass = 'btn btn-default';

    /**
     * @var array items for the widget's list.
     * 
     * Array should be in the form value => label.
     */
    public $items = [];

    /**
     * array|string the URL for loading origin items JSON. 
     * 
     * This property will be processed by [[Url::to()]].
     * @var string
     */
    public $originItemsUrl;

    /**
     * array|string the URL for destination items JSON array.
     * The JSON array should be in the format ```{ value: label } 
     * 
     * This property will be processed by [[Url::to()]].
     * @var string
     */
    public $destItemsUrl;

    /**
     * @var boolean whether to allow multiple selections in the lists.
     */
    public $multiSelect = true;

    /**
     * @var array Html options for the showing results span tags.
     */
    public $showingOptions = [
        'class' => 'hidden-xs'
    ];

    /**
     * @var array Html options for the filter inputs.
     */
    public $filterOptions = [];
    
    /**
     * @var array Html options for the hidden list box input
     */
    public $options = ['class' => 'hidden', 'multiple' => true];
    // TODO add sort and delay data attributes
    /**
     * @var boolean whether to sort list items
     */
    public $sort;
    
    /**
     * @var integer filter delay in ms
     */
    public $delay;
    
    
    /**
     * Generates the horizontal buttons that are shown when the screen width >= 768px;
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
            'class' => $this->buttonClass,
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'selected'
        ]);
        
        $allDest = Html::button($rightIcon . $rightIcon, [
            'class' => $this->buttonClass,
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'all'
        ]);
        
        $oneOrigin = Html::button($leftIcon, [
            'class' => $this->buttonClass,
            'data-move-to' => self::ORIGIN_LIST,
            'data-move' => 'selected'
        ]);
        
        $allOrigin = Html::button($leftIcon . $leftIcon, [
            'class' => $this->buttonClass,
            'data-move-to' => self::ORIGIN_LIST,
            'data-move' => 'all'
        ]);
        
        $offset = ! (empty($this->originLabel) && empty($this->destLabel)) ? '25px' : '0px';
        
        $btnsDest = Html::tag('div', $oneDest . $allDest, [
            'class' => 'btn-group-vertical'
        ]);
        $btnsOrigin = Html::tag('div', $oneOrigin . $allOrigin, [
            'class' => 'btn-group-vertical',
            'style' => "margin-top: $offset"
        ]);
        
        return Html::tag('div', $btnsOrigin, [
            'class' => 'hidden-xs large-buttons'
        ]) . Html::tag('div', $btnsDest, [
            'class' => 'hidden-xs large-buttons'
        ]);
    }

    /**
     * Generates the horizontal buttons that are shown when the screen width < 768px;
     * 
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
            'class' => $this->buttonClass,
            'data-move-to' => self::DEST_LIST,
            'data-move' => 'selected'
        ]);
        
        $oneOrigin = Html::button($upIcon, [
            'class' => $this->buttonClass,
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
     * Generates a list box with optional label, filter input and showing count.
     *  
     * @param string $name list name
     * @param string $label list label
     * @param string $itemsUrl url to get the items JSON array    
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
        
        $list .= Html::listBox(null, [], $this->items, $listBoxOptions);
        
        if ($this->showing) {
            $list .= Html::tag('span', $this->showingText . Html::tag('strong', 0, [
                'data-list-count' => $name
            ]), $this->showingOptions);
        }
        return $list;
    }

    /**
     * Register asset bundles / js.
     * 
     * The generated js is for passing the blur event to the hidden listbox so that blur on 
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        $list = $this->inputList;
        $js = "$('#$id').siblings().find('[data-list=\"$list\"]').blur(function() { $('#$id').blur(); })";
        $view->registerJs($js);
        $view->registerAssetBundle(DualListboxStyleAsset::className());
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $options = $this->options;
        $options['data-list-inputs'] = $this->inputList;
        
        if ($this->hasModel()) {
            $content = Html::activeListBox($this->model, $this->attribute, $this->items, $options);
        } else {
            $content = Html::listBox($this->name, null, $this->items, $options);
        }
        
        $content .= Html::tag('div', $this->generateList(self::ORIGIN_LIST, $this->originLabel, $this->originItemsUrl), [
                'class' => 'col-sm-5'
            ]) . Html::tag('div', $this->generateMainButtons() . $this->generateMobileButtons(), [
                'class' => 'col-sm-2 text-center'
            ]) . Html::tag('div', $this->generateList(self::DEST_LIST, $this->destLabel, $this->destItemsUrl), [
                'class' => 'col-sm-5'
            ]);

        echo Html::Tag('div', $content, ['data-role' => 'dual-listbox', 'class' => 'row']);
        $this->registerClientScript();
    }
}
