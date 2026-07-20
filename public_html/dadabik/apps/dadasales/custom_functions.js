function dadabik_copy_customer_address(field)
{

    $('[name="invoice_address_account"]')[0].value = $('[name="address_account"]')[0].value;
    $('[name="invoice_id_country"]')[0].value = $('[name="id_country"]')[0].value;
    
    var element = document.getElementsByName('invoice_id_country')[0];
    var event = new Event('change');
    element.dispatchEvent(event);
    setTimeout(dadabik_copy_city, 2000);
}

function dadabik_copy_city()
{
    $('[name="invoice_id_city"]')[0].value = $('[name="id_city"]')[0].value;
}

function dadabik_capitalize_name(field)
{
    field.value = field.value.toUpperCase();
}
