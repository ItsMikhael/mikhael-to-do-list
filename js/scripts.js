jQuery(document).ready(function() {

    jQuery(".create-task-input").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {

            if (e.target.value !== '') {
                let data = {
                    'action': 'todolist_create_new_task',
                    'task': e.target.value,
                }

                jQuery.post(ajaxurl, data, function(response) {
                        if(response.success === 1) {
                            "success";
                        } else{
                            "error";
                        }
                    }
                );
            }

        }
    });

})

