(() => {
    window.addEventListener('DOMContentLoaded', init);

    let addHabitForm;

    function init() {
        addHabitForm = document.forms['addHabit'];
        addHabitForm.addEventListener('submit', addHabit)
    }

    function addHabit(e) {
        e.preventDefault();

        const jsonData = JSON.stringify({
            'name': addHabitForm['habit-name'].value,
            'duration': addHabitForm['habit-duration'].value,
        });
        
        RequestJson.post('', jsonData, json => console.log(json));
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