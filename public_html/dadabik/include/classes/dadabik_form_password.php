<?php
#[\AllowDynamicProperties]
class dadabik_form_password
{
	
	function __construct($name, $label, $disabled, $required)
	{
		// set default values
        $this->type = 'password';
        $this->name = $name;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->required = $required;
        
    }
}

?>
