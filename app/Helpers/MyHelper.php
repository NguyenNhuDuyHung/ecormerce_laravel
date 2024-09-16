<?php

if (!function_exists('convert_price')) {
    function convert_price(string $price = '')
    {
        return str_replace('.', '', $price);
    }
}

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '')
    {
        return '<input 
            type="text" 
            name="' . $name . '"
            value="' . old($name) . '" 
            placeholder=""
            autocomplete="off" 
            class="form-control" 
        />';
    }
}

if (!function_exists('renderSystemImages')) {
    function renderSystemImages(string $name = '')
    {
        return '<input 
            type="" 
            name="' . $name . '"
            value="' . old($name) . '" 
            placeholder=""
            autocomplete="off" 
            class="form-control upload-image" 
        />';
    }
}

if (!function_exists('renderSystemTextArea')) {
    function renderSystemTextArea(string $name = '')
    {
        return '<textarea class="system-textarea" name="' . $name . '" value="' . old($name) . '" placeholder="" autocomplete="off" class="form-control"></textarea>';
    }
}


if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = [])
    {
        return isset($item['link']) ? '<a class="system-link" target="' . $item['link']['target'] . '" href="' . $item['link']['href'] . '">' . $item['link']['text'] . '</a>' : '';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item = [], string $name = '')
    {
        $html = '';
        $html .= '<select name="' . $name . '" class="form-control select2">';   
            foreach ($item['option'] as $key => $option) {
                $html .= '<option value="' . $key . '">' . $option . '</option>';
            }
        $html .= '</select>';
        return $html;
    }
}