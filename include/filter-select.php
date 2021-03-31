<?php

function renderSelectPicker($data, $type, $label)
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

    foreach ($data as $item) {
        echo "<option value='" . $item['data']['id'] . "'";

        if (isset($_GET[$type])) {
            foreach ($_GET[$type] as $param) {
                if ($param === $item['data']['id']) {
                    echo 'selected';
                }
            }
        }

        echo ">";

        echo $item['data']['title'] . "</option>";
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

?>

<!--<div class="form-group">-->
<!--    <label for="sort">-->
<!--        Sort-->
<!--    </label>-->
<!--    <select class="selectpicker show-tick"-->
<!--            name="sort"-->
<!--            id="sort"-->
<!--            title='Nothing selected'>-->
<!--        --><?php //foreach ($sort as $item): ?>
<!--            <option value="--><?//= $item['value'] ?><!--"-->
<!--                --><?php //if (isset($_GET['sort']) && $_GET['sort'] === $item['value']): ?>
<!--                    selected-->
<!--                --><?php //endif; ?>
<!--            >--><?//= $item['text'] ?><!--</option>-->
<!--        --><?php //endforeach; ?>
<!--    </select>-->
<!--</div>-->
