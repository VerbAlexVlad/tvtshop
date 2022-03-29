<style>
    .table-params {
        border: 1px solid #A0A0A0;
        margin-bottom: 20px;
        width: 100%;
        max-width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        display: flex;
        flex-wrap: wrap;justify-content: space-evenly;
    }

    .table-params .text-center {
        text-align: center;
    }
    .title_table-params{
        padding: 8px 5px;
        position: relative;
        background-color: #eee;
        flex-basis: 100%;
        border-bottom: 1px solid #A0A0A0;
    }
    .title_table-params .atteption-link {
        cursor: pointer;
        color: #F93C00;
        border-bottom: 1px dotted;
    }
    .params_table-params .not-value {
        color: #F93C00;
    }

    .param-name_table-params {
        margin-right: 10px;
    }

    .table-params .params_table-params {
        white-space: nowrap;
        flex-basis: <?= 100/count($param_list)?>%;
        padding: 8px 5px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>

<div class="table-params">
    <div class="title_table-params text-center">
        Параметры <strong>Вашего</strong> телосложения: (<a href="/userparams/update" class="atteption-link">Изменить</a>)
    </div>

    <?php foreach ($param_list as $param_item) { ?>
        <div class="params_table-params text-center">
            <div class="param-name_table-params"><?= $param_item->parameter_name ?>:</div>

            <?php if (isset($userParam_list[$param_item->id]->userparam_value)) {
                $class = 'param-value_table-params';
                $value = $userParam_list[$param_item->id]->userparam_value.' см';
            } else {
                $class = 'param-value_table-params not-value';
                $value = 'Не указано';
            } ?>
            <div class="<?= $class ?>"><?= $value ?></div>
        </div>
    <?php } ?>

</div>