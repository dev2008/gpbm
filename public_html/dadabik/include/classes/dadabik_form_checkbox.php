<?php
#[\AllowDynamicProperties]
class dadabik_form_checkbox
{
	
	function __construct($name, $label, $value, $disabled, $required)
	{
		// set default values
        $this->type = 'checkbox';
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->required = $required;
        
    }
}

?>
