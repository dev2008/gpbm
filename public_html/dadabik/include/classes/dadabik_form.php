<?php
require './include/classes/dadabik_form_text.php';
require './include/classes/dadabik_form_checkbox.php';
require './include/classes/dadabik_form_password.php';
require './include/classes/dadabik_form_separator.php';

#[\AllowDynamicProperties]
class dadabik_form
{
	var $elements;
	
	function __construct($name, $method = 'POST', $action = NULL, $submit_button = NULL, $back_button = NULL, $back_button_url = NULL, $disable_autocomplete = 0)
	{
		
		// todo
		// check method should be post or get
		
		// set default values
        $this->properties = array(
            'action'                    =>  $action,
            'method'                    =>  strtoupper($method),
            'name'                      =>  $name,
            'submit_button'             =>  $submit_button,
            'back_button'            	=>  $back_button,
            'back_button_url'           =>  $back_button_url,
            'disable_autocomplete'      =>  $disable_autocomplete,
        );
    }
        
	function add_element($type, $name, $label, $value=NULL, $disabled=0, $required=0)
	{
		if (isset($this->elements[$name])){
			die ('Two form elements having the same name, unexpected error');
		}
		switch ($type)
		{
			case 'text':
				$this->elements[$name] = new dadabik_form_text($name, $label, $value, $disabled, $required);
				break;
			case 'checkbox':
				$this->elements[$name] = new dadabik_form_checkbox($name, $label, $value, $disabled, $required);
				break;
			case 'password':
				$this->elements[$name] = new dadabik_form_password($name, $label, $disabled, $required);
				break;
			case 'separator':
				$this->elements[$name] = new dadabik_form_separator($name, $value);
				break;
		}
	}
	
	function render()
	{
		echo '<form class="css_form" method="'.$this->properties['method'].'"';
		
		if (!is_null($this->properties['action'])){
			echo ' action="'.$this->properties['action'].'"';
		}
		
		if ( $this->properties['disable_autocomplete'] === 1){
			echo ' autocomplete="off"';
		}
		
		echo '>';
		
		
		
		

		$elements_cnt = 0;
		foreach($this->elements as $element)
		{
			$elements_cnt++;
			
			if ($element->type === 'separator'){
				if ($elements_cnt > 1){
					echo '</span>'; // close the previous form_fields_set, but not if this is the first field
				}
				
				echo '<div class="form_separator">'.$element->value.'</div>';
			}
			if ($element->type === 'separator' || $elements_cnt === 1){
				echo '<span class="form_fields_set">';
			}
			if ($element->type !== 'separator'){
				echo '<div class="form_row">';
				echo '<label for="'.$element->name.'">'.$element->label;
				
				if ($element->required === 1){
				    echo '<span id="number_order_req" style="color:red">*</span>';
				}
				
				echo '</label> ';
				
			
			
				switch($element->type)
				{
					case 'text':
						echo '<input type="text" class="form-control" name="'.$element->name.'" value="'.$element->value.'"';
					
						if ($element->disabled === 1){
							echo ' disabled';
						}
					
						echo '>';
						break;
					case 'checkbox':
						echo '<input type="checkbox" name="'.$element->name.'" value="1"';
					
						if ($element->disabled === 1){
							echo ' disabled="disabled"';
						}
						
						if ($element->value == '1'){
							echo ' checked="checked"';
						}
					
						echo '>';
						break;
					case 'password':
						echo '<input type="password" autocomplete="off" name="'.$element->name.'">';
						break;
				}
				echo '</div>';
			}
		}
		
		
		
		echo '</span>'; // close the last form_fields_set
		
		echo '<div style="margin-top:10px;">';
		
		if (!is_null($this->properties['submit_button'])){
			echo '<input class="button_form btn btn-primary" type="submit" value="'.($this->properties['submit_button']).'">';
		}
		
		if (!is_null($this->properties['back_button'])){
			
			if (!is_null($this->properties['back_button_url'])){
				$back_button_url = $this->properties['back_button_url'];
			}
			else{
				$back_button_url = 'javascript:history.back(-1)';
			}	
			
			echo ' <input class="button_form btn btn-outline-primary" type="button" onclick="javascript:document.location=\''.($back_button_url).'\'" value="'.($this->properties['back_button']).'">';
		}
		
		echo '</div>';
		
		echo '</form>';
	}
}

?>
