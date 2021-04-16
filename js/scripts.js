jQuery(document).ready(function () {
    let todolist = jQuery('.to-do-list');

    /* Open up the task creation modal after pressing enter on the main input */
    jQuery('.create-task-input').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            if (e.target.value !== '') {
                jQuery('.todolist-modal_create').show();
                jQuery('.todolist-modal_create input[type=text]').val(e.target.value);
                e.target.value = '';
            }

        }
    });

    /* Open up the task edition modal */
    todolist.on('click', '.to-do-list__edit-task', function () {
        const list_item = jQuery(this).parents('.to-do-list__item')

        jQuery('.todolist-modal_edit').show();

        // Get current task details
        const task_id = list_item.data('task-id');
        const task_text = list_item.children('.to-do-list__item-title').html().trim();
        const task_prio = list_item.children('.to-do-list__priority').html().trim();
        const task_deadline = list_item.children('.to-do-list__deadline').html().trim();

        // Update the modal with current task details
        jQuery('.todolist-modal_edit input[type=text]').attr('data-task-id', task_id);
        jQuery('.todolist-modal_edit input[type=text]').val(task_text);
        jQuery('.todolist-modal_edit #priority').val(task_prio);
        jQuery('.todolist-modal_edit input[type=date]').val(task_deadline);
    });

    /* Set task to done after clicking the icon */
    todolist.on('click', '.to-do-list__task-done', function () {
        const list_item = jQuery(this).parents('.to-do-list__item')
        const task_id = list_item.data('task-id');

        change_task_status_to_done(task_id);
    });

    // Run create task function after pressing save on create task modal
    jQuery('.create-task-button').on('click', function () {
        const task_text = jQuery('.todolist-modal_create input[type=text]').val();
        const task_prio = jQuery('.todolist-modal_create select').val();
        const task_deadline = jQuery('.todolist-modal_create input[type=date]').val();

        create_task(task_text, task_prio, task_deadline);
    });

    // Run update task function after pressing save on edit task modal
    jQuery('.edit-task-button').on('click', function () {
        const task_id = jQuery('.todolist-modal_edit input[type=text]').data('task-id');
        const task_text = jQuery('.todolist-modal_edit input[type=text]').val();
        const task_prio = jQuery('.todolist-modal_edit select').val();
        const task_deadline = jQuery('.todolist-modal_edit input[type=date]').val();

        update_task(task_text, task_prio, task_deadline, task_id);
    });

    /* Delete the task after pressing the delete button */
    todolist.on("click", ".to-do-list__delete-task", function () {
        const task_id = jQuery(this).parents('.to-do-list__item').data('task-id');
        delete_task(task_id);
    });

    /* Save task creation when Enter is pressed */
    jQuery('.todolist-modal_create').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            const task_text = jQuery('.todolist-modal_create input[type=text]').val();
            const task_prio = jQuery('.todolist-modal_create select').val();
            const task_deadline = jQuery('.todolist-modal_create input[type=date]').val();

            create_task(task_text, task_prio, task_deadline);
        }
    });

    /* Save task edition when Enter is pressed */
    jQuery('.todolist-modal_edit').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            const task_id = jQuery('.todolist-modal_edit input[type=text]').data('task-id');
            const task_text = jQuery('.todolist-modal_edit input[type=text]').val();
            const task_prio = jQuery('.todolist-modal_edit select').val();
            const task_deadline = jQuery('.todolist-modal_edit input[type=date]').val();

            update_task(task_text, task_prio, task_deadline, task_id);
        }
    });

    jQuery('.close-modal-button').on('click', function () {
        jQuery(this).closest('.todolist-modal').hide();
    });

    jQuery('.todolist-modal__background').on('click', function () {
        jQuery(this).closest('.todolist-modal').hide();
    });

    jQuery('.todolist-modal__wrapper').on('click', function (e) {
        e.stopPropagation();
    });

    /* The function handling task creation */
    function create_task(task_text, task_prio, task_deadline) {
        let data = {
            'action': 'todolist_create_new_task',
            'task_text': task_text,
            'task_prio': task_prio,
            'task_deadline': task_deadline,
        }

        jQuery.post(ajaxurl, data, function (response) {
                const response_decoded = JSON.parse(response);
                // Append the list with the new task after it has been successfully added
                if (response_decoded.result === 'success') {
                    const prio_class = get_prio_class(task_prio);
                    jQuery(".to-do-list").prepend(`
                                <div class="to-do-list__item ${prio_class}" data-task-id="${response_decoded.task_id}">
                                    <span class="to-do-list__item-title">
                                        ${task_text}
                                    </span>
                                    <span class="to-do-list__priority">
                                        ${task_prio}
                                    </span>
                                    <span class="to-do-list__deadline">
                                        ${task_deadline}
                                    </span>
                                    <span class="to-do-list__buttons">
                                        <span class="to-do-list__task-done">
                                            <img src="` + plugin_url + `images/done.svg" alt="done icon">
                                        </span>
                                        <span class="to-do-list__edit-task">
                                            <img src="` + plugin_url + `images/edit_icon.svg" alt="edit icon">
                                        </span>
                                        <span class="to-do-list__delete-task">X</span>
                                    </span>
                                </div>
                            `)
                    jQuery('.todolist-modal_create input[type=text]').val('');
                    jQuery('.todolist-modal_create').hide();
                    jQuery(".to-do-list").pagify(5, ".to-do-list__item")
                }
            }
        );
    }

    /* The function handling task edition */
    function update_task(task_text, task_prio, task_deadline, task_id) {
        let data = {
            'action': 'todolist_update_task',
            'task_text': task_text,
            'task_prio': task_prio,
            'task_deadline': task_deadline,
            'task_id': task_id,
        }

        jQuery.post(ajaxurl, data, function (response) {
                const response_decoded = JSON.parse(response);
                // Append the list with the new task after it has been successfully added
                if (response_decoded.result === 'success') {
                    const prio_class = get_prio_class(task_prio);

                    let list_item = jQuery('.to-do-list__item[data-task-id=' + task_id + ']');
                    list_item.removeClass();
                    list_item.addClass('to-do-list__item');
                    list_item.addClass(prio_class);
                    jQuery(list_item).find('.to-do-list__item-title').html(task_text);
                    jQuery(list_item).find('.to-do-list__priority').html(task_prio);
                    jQuery(list_item).find('.to-do-list__deadline').html(task_deadline);
                    jQuery('.todolist-modal_edit input[type=text]').val('');
                    jQuery('.todolist-modal_edit').hide();
                }
            }
        );
    }

    /* The function handling task status change to finished */
    function change_task_status_to_done(task_id) {
        let data = {
            'action': 'todolist_task_done',
            'task_id': task_id,
        }

        jQuery.post(ajaxurl, data, function (response) {
                const response_decoded = JSON.parse(response);
                // Append the list with the new task after it has been successfully added
                if (response_decoded.result === 'success') {
                    let list_item = jQuery('.to-do-list__item[data-task-id=' + task_id + ']');
                    list_item.addClass('task_done');
                    list_item.find('.to-do-list__task-done').remove();
                    list_item.find('.to-do-list__edit-task').remove();
                    list_item.find('.to-do-list__priority').html('');
                }
            }
        );
    }

    /* The function handling task deletion */
    function delete_task(task_id) {
        let data = {
            'action': 'todolist_delete_task',
            'task_id': task_id,
        }

        jQuery.post(ajaxurl, data, function (response) {
                const response_decoded = JSON.parse(response);
                // Remove the task from the list after it has been successfully removed from the database
                if (response_decoded.result === 'success') {
                    jQuery('[data-task-id=' + task_id + ']').remove();
                }
            }
        );
    }

    /* Function for getting prio class based on priority */
    function get_prio_class(task_prio) {
        let prio_class = '';
        if (task_prio === 'Low Priority') {
            prio_class = 'task_prio_low';
        } else if (task_prio === 'Medium Priority') {
            prio_class = 'task_prio_med';
        } else {
            prio_class = 'task_prio_high';
        }

        return prio_class;
    }

})
;

