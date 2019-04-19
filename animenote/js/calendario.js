$(document).ready(function() {
    $('#datePicker')
        .datepicker({
            format: 'mm/dd/yyyy'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#eventForm').formValidation('revalidateField', 'eventDate');
        });

    $('#eventForm')
        .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'The name is required'
                        }
                    }
                },
                eventDate: {
                    validators: {
                        notEmpty: {
                            message: 'The date is required'
                        },
                        date: {
                            format: 'MM/DD/YYYY',
                            message: 'The date is not a valid'
                        }
                    }
                }
            }
        })
        .on('success.validator.fv', function(e, data) {
            if (data.field === 'eventDate' && data.validator === 'date' && data.result.date) {
                // The eventDate field passes the date validator
                // We can get the current date as a Javascript Date object
                var currentDate = data.result.date,
                    day         = currentDate.getDay();

                // If the selected date is Sunday
                if (day === 0) {
                    // Treat the field as invalid
                    data.fv
                        .updateStatus(data.field, data.fv.STATUS_INVALID, data.validator)
                        .updateMessage(data.field, data.validator, 'Please choose a day except Sunday');
                } else {
                    // Reset the message
                    data.fv.updateMessage(data.field, data.validator, 'The date is not valid');
                }
            }
        });
});