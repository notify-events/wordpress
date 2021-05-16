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
        <select name="form[<?= $field ?>]" id="wpne_<?= $field ?>">
            <?php foreach ($items as $key => $title) { ?>
                <option value="<?= esc_attr($key) ?>" <?= ($model->{$field} == $key) ? 'selected' : '' ?>><?= esc_html($title) ?></option>
            <?php } ?>
        </select>
        <?php if ($model->has_error($field)) { ?>
            <div class="wpne-error"><?= esc_html($model->get_error($field)) ?></div>
        <?php } ?>
    </td>
</tr>
