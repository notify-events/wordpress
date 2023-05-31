<?php

/**
 * @var View   $this
 * @var Model  $model
 * @var string $title
 * @var string $field
 * @var array  $items
 */

use notify_events\models\Model;
use notify_events\models\View;

?>

<style>
    .checkbox-list {
        border: 1px solid #c3c4c7;
        border-radius: 4px;
        padding: 0 4px;
        max-height: 150px;
        overflow-y: auto;
    }
    .checkbox-list label {
        display: block;
        margin: 4px 0;
    }
</style>

<tr class="wpne-form-group <?= $model->has_error($field) ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= $title ?></th>
    <td>
        <div class="checkbox-list">
            <?php foreach ($items as $key => $title) { ?>
                <label>
                    <input type="checkbox" name="form[<?= $field ?>][]" value="<?= $key ?>" <?= in_array($key, (array)$model->{$field}) ? 'checked' : '' ?>>
                    <?= esc_html($title) ?>
                </label>
            <?php } ?>
        </div>
        <?php if ($model->has_error($field)) { ?>
            <div class="wpne-error"><?= esc_html($model->get_error($field)) ?></div>
        <?php } ?>
    </td>
</tr>
