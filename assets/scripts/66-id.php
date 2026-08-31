<?php
if ($USER['access'] < 9) {
    echo 'Нет доступа.';
    exit;
}

global $PAGE;

$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();

$beton_price = GetBetonPrice();
$beton_mark = array(
    'М-100',
    'М-150',
    'М-200',
    'М-250',
    'М-300',
    'М-350',

    'М-200 на гравийном щебне',
    'М-250 на гравийном щебне',
    'М-300 на гравийном щебне',

    'М-350 на гравийном щебне w6',
    'М-400 на гравийном щебне w8',
    'М-450 на гравийном щебне w8'
);

?>

<table id='beton_price_table'>
    <thead>
    <tr>
        <th>Класс бетона</th>
        <th>Цена без добавки</th>
        <th>Цена с добавкой -10°С</th>
        <th>Цена с добавкой -25°С</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($beton_price as $key => $price) {
        echo '<tr> <td>' . $beton_mark[$key] . '</td><td> <div contentEditable="true">' . $price['not_add'] . '</div></td> <td> <div contentEditable="true">' . $price['minus10'] . '</div></td> <td> <div contentEditable="true">' . $price['minus25'] . '</div></td> </tr>';
    }
    ?>
    </tbody>
</table>


<style>
    table {
        width: 100%;
        margin-top: 40px;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #999;
        padding: 6px 15px;
    }

    th {
        text-align: left;
    }

    tbody tr td:first-child {
        font-size: 14px;
        font-weight: bold;
    }

    tbody tr:nth-child(odd) {
        background-color: rgba(255, 102, 0, .3);
    }

    div[contenteditable] {
        border: 1px solid #999;
        padding: 2px 10px;
    }

</style>

<script>


    document.addEventListener('DOMContentLoaded', function () {

        start_price = '';  //цена при клике на ячейку

        $('#beton_price_table td div').click(function () {
            start_price = $(this).text() * 1;
        });

        $('#beton_price_table td div').keypress(function (e) {
            if (e.which == 13) {
                $(this).blur();
                return false;
            }
        })


        $('#beton_price_table td div').blur(function () {

            var new_price = $(this).text() * 1;

            if (new_price != start_price) {  //Если произошло изменение цены


                var column;
                var index_column = $(this).parent('td').index();


                if (index_column == 1) {
                    column = 'not_add';
                } else if (index_column == 2) {
                    column = 'minus10';
                } else if (index_column == 3) {
                    column = 'minus25';
                } else
                    alert('Ошибка...Обратитесь к создателям сайта');

                var id = $(this).parent().parent('tr').index() + 1;

                $current_cell = $(this);


                if (isNum(new_price) && new_price >= 0) {
                    $.post(
                        "/scripts/ajax.php",
                        {
                            ajax_name: 'change_price_beton',
                            id: id,
                            column: column,
                            new_price: new_price
                        },
                        onPriceChange
                    );
                } else {
                    alert('Вы ввели некорректное значение');
                }
            }
        });


        function onPriceChange(data) {
            if (data == 'ok') {
                $current_cell.css({'color': '#006600', 'font-weight': 'bold'});
            } else
                alert(data);
        }


        function isNum(num) {
            return res = (num / num) ? true : false;
        }
    })
</script>