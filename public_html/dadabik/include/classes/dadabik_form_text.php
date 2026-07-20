<?php
#[\AllowDynamicProperties]
class dadabik_form_text
{
	
	function __construct($name, $label, $value, $disabled, $required)
	{
		// set default values
        $this->type = 'text';
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->required = $required;
        
    }
}

?>
