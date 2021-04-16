<?php
global $wpdb;
$table_name = $wpdb->prefix . 'todolist';

/*$tasksPendingLow = $wpdb->get_results(
        "SELECT * FROM " . $table_name .
              " WHERE status='pending'
               AND priority='Low priority'
                ORDER BY date ASC;"
);*/
$tasksPendingLow = $mikhael_to_do_list->get_pending_tasks('Low Priority');
$tasksPendingMedium = $mikhael_to_do_list->get_pending_tasks('Medium Priority');
$tasksPendingHigh = $mikhael_to_do_list->get_pending_tasks('High Priority');
$tasksDone = $wpdb->get_results(
        "SELECT * FROM " . $table_name .
        " WHERE status='done'
        ORDER BY date ASC;"
);
?>

<div class="todolist-wrapper">

    <h1>To-do list</h1>

    <input type="text" placeholder="What do you need to do?" class="input_underline input_color_main input_width_full create-task-input">

    <div class="to-do-list">

        <?php if ($tasksPendingHigh): ?>
            <?php foreach ($tasksPendingHigh as $task): ?>
                <div class="to-do-list__item task_prio_high" data-task-id="<?php echo $task->id ?>">
                    <span class="to-do-list__item-title">
                        <?php echo $task->text ?>
                    </span>
                    <span class="to-do-list__priority">
                        <?php echo $task->priority ?>
                    </span>
                    <span class="to-do-list__deadline" data-date="<?php echo $task->date ?>">
                        <?php
                        $date = date_create_from_format('Y-m-d', $task->date);
                        $formattedDate = $date->format('M d')
                        ;?>
                        <?php echo $formattedDate ?>
                    </span>
                    <span class="to-do-list__buttons">
                        <span class="to-do-list__task-done">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/done.svg' ?>" alt="done icon">
                        </span>
                        <span class="to-do-list__edit-task">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/edit_icon.svg' ?>" alt="edit icon">
                        </span>
                        <span class="to-do-list__delete-task">X</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($tasksPendingMedium): ?>
            <?php foreach ($tasksPendingMedium as $task): ?>
                <div class="to-do-list__item task_prio_med" data-task-id="<?php echo $task->id ?>">
                    <span class="to-do-list__item-title">
                        <?php echo $task->text ?>
                    </span>
                    <span class="to-do-list__priority">
                        <?php echo $task->priority ?>
                    </span>
                    <span class="to-do-list__deadline" data-date="<?php echo $task->date ?>">
                        <?php
                        $date = date_create_from_format('Y-m-d', $task->date);
                        $formattedDate = $date->format('M d')
                        ;?>
                        <?php echo $formattedDate ?>
                    </span>
                    <span class="to-do-list__buttons">
                        <span class="to-do-list__task-done">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/done.svg' ?>" alt="done icon">
                        </span>
                        <span class="to-do-list__edit-task">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/edit_icon.svg' ?>" alt="edit icon">
                        </span>
                        <span class="to-do-list__delete-task">X</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($tasksPendingLow): ?>
            <?php foreach ($tasksPendingLow as $task): ?>
                <div class="to-do-list__item task_prio_low" data-task-id="<?php echo $task->id ?>">
                    <span class="to-do-list__item-title">
                        <?php echo $task->text ?>
                    </span>
                    <span class="to-do-list__priority">
                        <?php echo $task->priority ?>
                    </span>
                    <span class="to-do-list__deadline" data-date="<?php echo $task->date ?>">
                        <?php
                        $date = date_create_from_format('Y-m-d', $task->date);
                        $formattedDate = $date->format('M d')
                        ;?>
                        <?php echo $formattedDate ?>
                    </span>
                    <span class="to-do-list__buttons">
                        <span class="to-do-list__task-done">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/done.svg' ?>" alt="done icon">
                        </span>
                        <span class="to-do-list__edit-task">
                            <img src="<?php echo plugin_dir_url(__DIR__) . 'images/edit_icon.svg' ?>" alt="edit icon">
                        </span>
                        <span class="to-do-list__delete-task">X</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($tasksDone): ?>
            <?php foreach ($tasksDone as $task): ?>
                <div class="to-do-list__item task_done" data-task-id="<?php echo $task->id ?>">
                    <span class="to-do-list__item-title">
                        <?php echo $task->text ?>
                    </span>
                    <span class="to-do-list__priority">
                    </span>
                    <span class="to-do-list__deadline" data-date="<?php echo $task->date ?>">
                        <?php
                        $date = date_create_from_format('Y-m-d', $task->date);
                        $formattedDate = $date->format('M d')
                        ;?>
                        <?php echo $formattedDate ?>
                    </span>
                    <span class="to-do-list__buttons">
                        <span class="to-do-list__delete-task">X</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>

<div class="todolist-modal todolist-modal_create">
    <div class="todolist-modal__background">
        <div class="todolist-modal__wrapper">
            <div class="todolist-modal__container">
                <div class="todolist-modal__header">
                    <h2 class="todolist-modal__heading heading_inline">Task details</h2>
                    <span class="close-mobile-icon">
                        X
                    </span>
                </div>
                <div class="todolist-modal__body">
                    <input type="text" class="input_underline input_width_full">
                    <div class="split-flex">
                        <select name="priority" id="priority">
                            <option value="Low Priority">Low Priority</option>
                            <option value="Medium Priority">Medium Priority</option>
                            <option value="High Priority">High Priority</option>
                        </select>
                        <input type="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="todolist-modal__footer">
                    <button class="button_todolist close-modal-button">Close</button>
                    <button class="button_todolist button_color_accent_darker create-task-button">Save task</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="todolist-modal todolist-modal_edit">
    <div class="todolist-modal__background">
        <div class="todolist-modal__wrapper">
            <div class="todolist-modal__container">
                <div class="todolist-modal__header">
                    <h2 class="todolist-modal__heading heading_inline">Task details</h2>
                    <span class="close-mobile-icon">
                        X
                    </span>
                </div>
                <div class="todolist-modal__body">
                    <input type="text" class="input_underline input_width_full">
                    <div class="split-flex">
                        <select name="priority" id="priority">
                            <option value="Low Priority">Low Priority</option>
                            <option value="Medium Priority">Medium Priority</option>
                            <option value="High Priority">High Priority</option>
                        </select>
                        <input type="date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="todolist-modal__footer">
                    <button class="button_todolist close-modal-button">Close</button>
                    <button class="button_todolist button_color_accent_darker edit-task-button">Save task</button>
                </div>
            </div>
        </div>
    </div>
</div>