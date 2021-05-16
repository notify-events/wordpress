<?php

/**
 * @var View   $this
 * @var Model  $model
 * @var string $title
 * @var string $field
 */

use notify_events\models\Model;
use notify_events\models\View;

?>

<tr class="wpne-form-group <?= $model->has_error($field) ? 'wpne-has-error' : '' ?>">
    <th scope="row"></th>
    <td>
        <label>
            <input type="checkbox" name="form[<?= $field ?>]" <?= $model->{$field} ? 'checked' : '' ?>>
            <?= $title ?>
        </label>
        <?php if ($model->has_error($field)) { ?>
            <div class="wpne-error"><?= esc_html($model->get_error($field)) ?></div>
        <?php } ?>
    </td>
</tr>
