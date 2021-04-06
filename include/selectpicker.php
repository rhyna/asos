<?php

function renderOption($array, $type)
{
    foreach ($array as $item) {
        echo "<option value='" . $item['id'] . "'";

        if (isset($_GET[$type])) {
            foreach ($_GET[$type] as $param) {
                if ($param === $item['id']) {
                    echo 'selected';
                }
            }
        }

        echo ">";

        echo $item['title'] . "</option>";
    }
}

function renderSelectPicker($data, $type, $label, $settings)
{
    echo "
            <div class='form-group'>
                <label for='" . $type . "'>" . $label . "</label>
                <select class='selectpicker'
                    multiple
                    data-live-search='true'
                    data-actions-box='true'
                    name='" . $type . "[]'
                    id='" . $type . "'>
         ";

    if (isset($settings['optGroups'])) {

        $optGroups = [];

        foreach ($settings['optGroups'] as $optGroup) {
            $optGroups[$optGroup['parentCategoryTitle']] = [];
        }

        foreach ($settings['optGroups'] as $optGroup) {
            $optGroups[$optGroup['parentCategoryTitle']][] = [
                'id' => $optGroup['id'],
                'title' => $optGroup['title']
            ];
        }

        foreach ($optGroups as $key => $optGroup) {
            echo "<optgroup label='" . $key . "'>";

            renderOption($optGroup, $type);

            echo "</optgroup>";
        }
    } else {
        renderOption($data, $type);
    }

    echo "</select></div>";
}

function renderSortSelectPicker()
{
    $sort = [
        [
            'value' => 'price-asc',
            'text' => 'Price low to high'
        ],
        [
            'value' => 'price-desc',
            'text' => 'Price high to low'
        ],
    ];

    echo "
            <div class='form-group'>
                <label for='sort'>Sort</label>
                <select class='selectpicker show-tick'
                        name='sort'
                        id='sort'
                        title='Nothing selected'>
          ";

    foreach ($sort as $item) {
        echo "<option value='" . $item['value'] . "'";
        if (isset($_GET['sort']) && $_GET['sort'] === $item['value']) {
            echo "selected";
        }

        echo ">" . $item['text'];

        echo "</option>";
    }

    echo "</select></div>";
}
