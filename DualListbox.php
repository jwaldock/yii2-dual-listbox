<?php

namespace jwaldock\duallistbox;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Url;

class DualListbox extends InputWidget
{   
    /**
     * @var boolean
     */
    public $filter = true;
    
    /**
     * @var string
     */
    public $filterText = 'Type to search...';
    
    /**
     * @var boolean
     */
    public $showing = true;
    
    /**
     * @var string
     */
    public $showingText = 'Showing ';
    
    /**
     * @var boolean
     */
    public $label = true;

    /**
     * @var string
     */
    public $origin = 'origin';
    
    /**
     * @var string
     */
    public $dest = 'dest';
    
    /**
     * @var string
     */
    public $buttonClass = 'btn-default';
    
    /**
     * @var array
     */
    public $items = [];
    
    /**
     * @var string
     */
    public $itemsUrl;
    
    /**
     * @var boolean
     */
    public $multiSelect = true;

    protected function generateItems()
    {
        $output = '';
        foreach ($this->items as $key => $value) {
            $output .= Html::tag('option', $value, ['value' => $key]);
        }
        return $output;
    }
    
    protected function generateButtons()
    {
        return $this->generateMainButtons() . $this->generateMobileButtons();
    }
    
    protected function generateMainButtons()
    {   
        $rightIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-right']);
        $leftIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-left']);
        
        $oneDest = Html::button($rightIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->dest,
            'data-move' => 'selected'
        ]);
        
        $allDest = Html::button($rightIcon . $rightIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->dest,
            'data-move' => 'all'
        ]);

        $oneOrigin = Html::button($leftIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->origin,
            'data-move' => 'selected'
        ]);

        $allOrigin = Html::button($leftIcon . $leftIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->origin,
            'data-move' => 'all'
        ]);

        $offset = $this->label ? '15px' : '0px';
       
        $btnsDest = Html::tag('div', $oneDest . $allDest, ['class' => 'btn-group-vertical']);
        $btnsOrigin = Html::tag('div', $oneOrigin . $allOrigin, ['class' => 'btn-group-vertical', 'style' => "margin-bottom: 5px; margin-top: $offset"]);
        
        return Html::tag('div', $btnsOrigin, ['class' => 'hidden-xs large-buttons']) .
            Html::tag('div', $btnsDest, ['class' => 'hidden-xs large-buttons']);
    }
    
    protected function generateMobileButtons()
    {
        $upIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-up']);
        $downIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-down']);
        
        $oneDest = Html::button($downIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->dest,
            'data-move' => 'selected'
        ]);
        
        $oneOrigin = Html::button($upIcon, [
            'class' => 'btn btn-default',
            'data-move-to' => $this->origin,
            'data-move' => 'selected'
        ]);
        
        $buttons = Html::tag('div', $oneDest, ['class' => 'btn-group']) . 
            Html::tag('div', $oneOrigin, ['class' => 'btn-group']);
        
        return Html::tag('div', $buttons, ['class' => 'btn-group btn-group-justified visible-xs']);       
    }

    protected function generateList($name)
    {
        $list = '';
        
        if ($this->label) {
            $list .= Html::tag('label', $name, [
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
        
        
        $list .= Html::listBox(null,[], $this->items,[
            'class' => 'form-control',
            'data-list' => $name,
            'data-items-url' => Url::to(['site/items']),
            'multiple' => 'multiple'
        ]);
        
        if ($this->showing) {
            $list .= Html::tag('span', 'Showing ' . Html::tag('strong', 0, [
                'data-list-count' => $name
            ]));
        }
        return $list;
    }
    
    /**
     * {@inheritDoc}
     * @see \yii\widgets\InputWidget::init()
     */
    public function init()
    {
        parent::init();
        $this->options['data-role'] = 'dual-listbox';
        Html::addCssClass($this->options, 'row');
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = Html::tag('div', $this->generateList($this->origin), ['class' => 'col-sm-5']) .
            Html::tag('div', $this->generateButtons(), ['class' => 'col-sm-2 text-center']) .
            Html::tag('div', $this->generateList($this->dest),['class' => 'col-sm-5']);
        
        echo Html::Tag('div', $content, $this->options);
    }
}
