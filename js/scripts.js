jQuery(document).ready(function () {

    /* Delete the task after pressing the delete button */
    jQuery('.to-do-list').on("click", ".to-do-list__delete-task", function () {
        const task_id = jQuery(this).parents('.to-do-list__item').data('task-id');
        delete_task(task_id);
    })

    /* Create a task after pressing enter within the input */
    jQuery('.create-task-input').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            if (e.target.value !== '') {
                jQuery('.todolist-modal_create').show();
                jQuery('.todolist-modal_create input[type=text]').val(e.target.value);
                e.target.value = '';
            }

        }
    });

    jQuery('.create-task-button').on('click', function () {
        const task_text = jQuery('.todolist-modal_create input[type=text]').val();
        const task_prio = jQuery('.todolist-modal_create select').val();
        const task_deadline = jQuery('.todolist-modal_create input[type=date]').val();

        create_task(task_text, task_prio, task_deadline);
    })

    jQuery('.close-modal-button').on('click', function () {
        jQuery(this).closest('.todolist-modal').hide();
    })

    jQuery('.todolist-modal__background').on('click', function () {
        jQuery(this).closest('.todolist-modal').hide();
    })

    jQuery('.todolist-modal__wrapper').on('click', function (e) {
        e.stopPropagation();
    })

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
                    jQuery(".to-do-list").append(`
                                <div class="to-do-list__item" data-task-id="${response_decoded.task_id}">
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
                                        <span class="to-do-list__delete-task">X</span>
                                    </span>
                                </div>
                            `)
                    jQuery('.todolist-modal_create input[type=text]').val('');
                    jQuery(this).closest('.todolist-modal').hide();
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

})

