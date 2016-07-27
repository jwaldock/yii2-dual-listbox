<?php

namespace jwaldock\duallistbox;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class DualListbox extends InputWidget
{

    public function init()
    {
        parent::init();
    }

    
    protected function generateButtons()
    {
        $rightIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-right']);
        $leftIcon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-left']);
        
        $buttons = Html::button($rightIcon, ['class' => 'btn btn-default one-to-dest']) . 
            Html::button($rightIcon . $rightIcon, ['class' => 'btn btn-default all-to-dest']) .
            Html::button($leftIcon, ['class' => 'btn btn-default one-to-origin']) .
            Html::button($leftIcon . $leftIcon, ['class' => 'btn btn-default all-to-origin']);
        
        return Html::tag('div', $buttons, ['class' => 'btn-group-vertical form-group']);
    }
    
    
    /**
     * @inheritdoc
     */
    public function run()
    {
//         $this->registerClientScript();
        echo Html::beginTag('div', ['class' => 'row dual-list']);
        // origin list
        echo Html::beginTag('div', ['class' => 'col-sm-5']);
        
        echo Html::tag('label', 'Origin Label', ['class' => 'control-label']);
        echo Html::textInput(null, null, ['class' => 'origin-filter form-control', 'placeholder' => 'Type to filter...']);
        
        echo Html::listBox(null,[], [],['class' => 'form-control origin']);
        echo Html::tag('div', 'Showing ' . Html::tag('strong', 0, ['class' => 'results']));
        echo Html::endTag('div');
        
        // buttons
        echo Html::tag('div', $this->generateButtons(), ['class' => 'col-sm-2 text-center']);
        
        // destination list
        echo Html::beginTag('div', ['class' => 'col-sm-5']);
        echo Html::tag('label', 'Destination Label', ['class' => 'control-label']);
        echo Html::textInput(null, null, ['class' => 'dest-filter form-control', 'placeholder' => 'Type to filter...']);
        if ($this->hasModel()) {
            echo Html::activeListBox($this->model, $this->attribute, [],['class' => 'form-control dest']);
        } else {
            echo Html::listBox($this->name, [], [], ['class' => 'form-control dest']);
        }
        echo Html::tag('div', 'Showing ' . Html::tag('strong', 0, ['class' => 'results']));
        echo Html::endTag('div');
        
        echo Html::endTag('div');
    }
}
