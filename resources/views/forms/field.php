<?php
/**
 * Доступные переменные шаблона
 * @var string $type
 * @var string $required
 * @var string $value
 * @var string $label
 * @var string $checked
 * @var string $readonly
 * @var string $autocomplete
 *
 */

switch ($type):
    case "textarea":
        ?>
        <textarea class="doc" style="width:85%;" name="<?= $name ?? ""; ?>" placeholder="<?= $label ?? "" ?>"
                  id="<?= $name ?? ""; ?>" <?= $required; ?> <?= $readonly; ?>><?= $value ?? ""; ?></textarea>
        <?php
        break;
    case "select":
        ?>
        <select id="<?= $name ?? ""; ?>" name="<?= $name ?? ""; ?>" style="width:85%;"
                class="doc" <?= $required; ?> <?= $readonly; ?>>
            <?php
            foreach ($options["items"] ?? [] as $key => $itemLabel):
                $selected = $value == $key ? " selected" : "";
                ?>
                <option class="doc" value="<?= $key; ?>" <?= $selected; ?>><?= $itemLabel; ?></option>
            <?php
            endforeach;
            ?>
        </select>
        <?php
        break;
    default:
    case "text":
    case "password":
        $autocomplete = " autocomplete='new-password'";
    case "hidden":
        ?>
        <input type="<?= $type; ?>" value="<?= $value ?? ""; ?>"
               name="<?= $name ?? ""; ?>" placeholder="<?= $label ?? "" ?>" id="<?= $name ?? ""; ?>" style="width:85%;"
               class="doc" <?= $required; ?> <?= $checked; ?> <?= $readonly; ?> <?= $autocomplete; ?>>
        <?php
        break;
    case "checkbox":
        ?>
        <input type="checkbox" name="<?= $name ?? ""; ?>" id="<?= $name ?? ""; ?>" class="doc" <?= $checked; ?> <?= $readonly; ?>>
        <?php
        break;

endswitch;
