<?php
function renderBreadcrumbs(array $data)
{
    $length = count($data);

    $i = 1;

    echo "<ul class='breadcrumbs'>";

    foreach ($data as $breadCrumb) {
        $breadCrumbTitle = ucfirst(strtolower($breadCrumb['title']));

        echo "<li class='breadcrumbs-item'>";

        if ($i < $length) {
            echo "<a href='" . $breadCrumb['url'] . "'>" . $breadCrumbTitle . "</a>";
        } else {
            echo $breadCrumbTitle;
        }

        echo "</li>";

        if ($i < $length) {
            echo "<span class='breadcrumbs-arrow'>›</span>";
        }

        $i++;
    }

    echo "</ul>";
}


