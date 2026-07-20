<?php
#[\AllowDynamicProperties]
class dadabik_form_separator
{
	
	function __construct($name, $value)
	{
		// set default values
        $this->type = 'separator';
        $this->name = $name;
        $this->value = $value;
        
    }
}

?>
