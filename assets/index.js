(() => {
    window.addEventListener('DOMContentLoaded', init);

    let addHabitForm;

    function init() {
        addHabitForm = document.forms['addHabit'];
        addHabitForm.addEventListener('submit', addHabit)
    }

    function addHabit(e) {
        e.preventDefault();

        const formData = {
            'name': addHabitForm['habit-name'].value,
            'duration': addHabitForm['habit-duration'].value,
        };

        const errors = validate(formData);
        if (errors.status) {
            console.error(errors);
            return;
        }
        
        const jsonData = JSON.stringify(formData);
        RequestJson.post('', jsonData, json => console.log(json));
    }

    function validate(data) {
        const errors = {
            'name': [],
            'duration': [],
            'status': false,
            addError: (field, msg) => {
                errors[field].push(msg);
                errors.status = true;
            }
        };
        const name = data['name'];
        const duration = data['duration'];

        if (!isPresent(name)) {
            errors.addError('name', 'Name is required');
        }

        if (isNullOrEmpty(name)) {
            errors.addError('name', 'Name can\'t be empty');
        }

        if (!isPresent(duration)) {
            errors.addError('duration', 'Duration is required');
        }

        if (isNullOrEmpty(duration)) {
            errors.addError('duration', 'Duration can\'t be empty');
        }

        if (!isNumber(duration)) {
            errors.addError('duration', 'Duration should be a number');
        }

        if (!isGreaterOrEqual(duration, 14)) {
            errors.addError('duration', 'Duration should be 14 or higher');
        }

        return errors;
    }

    function isPresent(value) {
        return value !== undefined;
    }

    function isNullOrEmpty(value) {
        return value === null || value === '';
    }

    function isNumber(value) {
        return !isNaN(value);
    }

    function isGreaterOrEqual(value, number) {
        return value >= number;
    }

    class RequestJson {
        static async post(url, data, callback) {
            const rawResponse = await fetch(url, RequestJson.#buildFetchConfig(data, 'POST'));
            const jsonData = await rawResponse.json();

            callback(jsonData);
        }

        static #buildFetchConfig(body, method) {
            return {
                method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body,
            }
        }
    }
})()