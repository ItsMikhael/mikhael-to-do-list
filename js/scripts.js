jQuery(document).ready(function() {

    jQuery('.to-do-list').on("click", ".to-do-list__delete-task", function() {
        const task_id = jQuery(this).parents('.to-do-list__item').data('task-id');
        delete_task(task_id);
    })

    jQuery(".create-task-input").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            if (e.target.value !== '') {
                create_task(e);
            }

        }
    });

    function create_task(e) {
        let data = {
            'action': 'todolist_create_new_task',
            'task': e.target.value,
        }

        jQuery.post(ajaxurl, data, function(response) {
                const response_decoded = JSON.parse(response);
                if (response_decoded.result === 'success') {
                    jQuery(".to-do-list").append(`
                                <div class="to-do-list__item" data-task-id="${ response_decoded.task_id }">
                                    <span class="to-do-list__item-title">
                                        ${ e.target.value }
                                    </span>
                                    <span class="to-do-list__priority">
                                        High priority
                                    </span>
                                    <span class="to-do-list__deadline">
                                        March 10
                                    </span>
                                    <span class="to-do-list__buttons">
                                        <span class="to-do-list__delete-task">X</span>
                                    </span>
                                </div>
                            `)
                    e.target.value = '';
                }
            }
        );
    }

    function delete_task(task_id) {
        let data = {
            'action': 'todolist_delete_task',
            'task_id': task_id,
        }

        jQuery.post(ajaxurl, data, function(response) {
                const response_decoded = JSON.parse(response);
                if (response_decoded.result === 'success') {
                    jQuery('[data-task-id=' + task_id + ']').remove();
                }
            }
        );
    }

})

