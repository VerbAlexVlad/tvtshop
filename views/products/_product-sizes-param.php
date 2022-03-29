<style>
    .table-param {
        margin-bottom: 20px;
        width: 100%;
        max-width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .table-param .text-center {
        text-align: center;
    }

    .table-param > tbody > tr > th, .table-param > tbody > tr > td {
        padding: 5px 15px;
    }

    .table-param-bordered > tbody > tr > th, .table-param-bordered > tbody > tr > td {
        border: 1px solid #ddd;
    }

    .table-param th {
        text-align: left;
        background-color: #f7f6f6;
        font-weight: normal;
        white-space: nowrap;
    }

    .atteption-link {
        color: #000;
    }

    .atteption-link a {
        border-bottom: 1px dashed;
        cursor: pointer;
        color: #F93C00;
    }


    .atteption-link a:hover {
        color: #F93C00;
    }
    .table-param-section{
        margin-bottom: 25px;
    }
</style>

<table style="overflow: auto;" class="table-param table-param-bordered table-dimension-ruler">
    <tbody class="text-center">
    <tr>
        <th rowspan="2"></th>
        <th colspan="2" class="text-center">Параметры</th>
    </tr>
    <tr>
        <th class="text-center">Товара</th>
        <th class="text-center" style="white-space: normal">Ваши</th>
    </tr>

    <?php foreach ($model as $item) { ?>
        <tr>
            <th><?= $item['paramParameters']['parameter_name'] ?></th>
            <td><?= $item['param_value'] ?> см</td>

            <td>
                <span class="atteption-link">
                    <?php if (isset($userParam[$item['param_parameters_id']])) { ?>
                        <?= $userParam[$item['param_parameters_id']]['userparam_value'] ?> см
                    <?php } else { ?>
                        <a href="/userparams/update" title="Указать параметры">Указать</a>
                    <?php } ?>
                </span>
            </td>
        </tr>
    <?php } ?>

    </tbody>
</table>