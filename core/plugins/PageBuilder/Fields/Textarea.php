<?php


namespace plugins\PageBuilder\Fields;


use plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use plugins\PageBuilder\PageBuilderField;

class Textarea extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        // Implement render() method.
        $output = '';
        $output .= $this->field_before();
        $output .= $this->label();
        $output .= '<textarea name="'.$this->name().'"  placeholder="'.$this->placeholder().'"  cols="10" rows="5" class="'.$this->field_class().'">'.$this->value().'</textarea>';
        $output .= $this->field_after();

        return $output;
    }
}
