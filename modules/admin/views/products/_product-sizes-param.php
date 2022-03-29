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
<h1 class="page-header">Параметры размера: <?= $size_info->size_name ?></h1>
<table style="overflow: auto;" class="table-param table-param-bordered table-dimension-ruler">
    <tbody class="text-center">
    <?php foreach ($model as $item) { ?>
        <tr>
            <th><?= $item['paramParameters']['parameter_name'] ?></th>
            <td><?= $item['param_value'] ?> см</td>
        </tr>
    <?php } ?>

    </tbody>
</table>