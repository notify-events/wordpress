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

<tr class="wpne-form-group <?= $model->has_error($field) ? 'wpne-has-error' : '' ?>">
    <th scope="row"><?= $title ?></th>
    <td>
        <?php foreach ($items as $key => $title) { ?>
            <label>
                <input type="checkbox" name="form[<?= $field ?>][]" value="<?= $key ?>" <?= in_array($key, (array)$model->{$field}) ? 'checked' : '' ?>>
                <?= esc_html($title) ?>
            </label><br>
        <?php } ?>
        <?php if ($model->has_error($field)) { ?>
            <div class="wpne-error"><?= esc_html($model->get_error($field)) ?></div>
        <?php } ?>
    </td>
</tr>
