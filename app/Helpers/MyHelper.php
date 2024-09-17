<?php

if (!function_exists('convert_price')) {
    function convert_price(string $price = '')
    {
        return str_replace('.', '', $price);
    }
}

if (!function_exists('convert_array')) {
    function convert_array($system = null, $keyword = '', $value = '')
    {
        $temp = [];
        if (is_array($system)) {
            foreach ($system as $k => $v) {
                $temp[$v[$keyword]] = $v[$value];
            }
        }

        if (is_object($system)) {
            foreach ($system as $k => $v) {
                $temp[$v->{$keyword}] = $v->{$value};
            }
        }

        return $temp;
    }
}

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '', $systems = null)
    {
        return '<input 
            type="text" 
            name="config[' . $name . ']"
            value="' . old($name, $systems[$name] ?? '') . '" 
            placeholder=""
            autocomplete="off" 
            class="form-control" 
        />';
    }
}

if (!function_exists('renderSystemImages')) {
    function renderSystemImages(string $name = '', $systems = null)
    {
        return '<input 
            type="" 
            name="config[' . $name . ']"
            value="' . old($name, $systems[$name] ?? '') . '" 
            placeholder=""
            autocomplete="off" 
            class="form-control upload-image" 
        />';
    }
}

if (!function_exists('renderSystemTextArea')) {
    function renderSystemTextArea(string $name = '', $systems = null)
    {
        return '<textarea class="system-textarea" name="config[' . $name . ']" placeholder="" autocomplete="off" class="form-control">' . old($name, ($systems[$name]) ?? '') . '</textarea>';
    }
}


if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = [])
    {
        return isset($item['link']) ? '<a class="system-link" target="' . $item['link']['target'] . '" href="' . $item['link']['href'] . '">' . $item['link']['text'] . '</a>' : '';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item = [], string $name = '', $systems = null)
    {
        $html = '';
        $html .= '<select name="config[' . $name . ']" class="form-control select2">';
        foreach ($item['option'] as $key => $option) {
            $html .= '<option ' . ((isset($systems[$name]) && $key == $systems[$name]) ? 'selected' : '') . ' value="' . $key . '">' . $option . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}

if (!function_exists('renderSystemEditor')) {
    function renderSystemEditor(string $name = '', $systems = null)
    {
        return '<textarea id="' . $name . '" name="config[' . $name . ']" placeholder="" autocomplete="off" class="form-control ck-editor">' . old($name, ($systems[$name]) ?? '') . '</textarea>';
    }
}