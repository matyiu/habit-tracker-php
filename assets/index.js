import { DateHelper } from './utils.js';

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
        RequestJson.post('', jsonData, createHabit);
    }

    // To Do: create habit class to manage the habits more easily
    function createHabit(res) {
        const habit = res.data;

        const habitsContainer = document.querySelector('.habits');
        const habitTemplate = document.querySelector('template#habit').content.cloneNode(true);
        const fragment = document.createDocumentFragment();

        // Get subcomponents of Habit
        const title = habitTemplate.querySelector('.habit-title');
        const months = habitTemplate.querySelector('thead tr:first-child th');
        const tbody = habitTemplate.querySelector('tbody');

        // Format raw data from habit response
        const startDate = new Date(habit['start']);
        const endDate = new Date(habit['end']);

        const startMonth = startDate.toLocaleString('default', { month: 'long' });
        const endMonth = endDate.toLocaleString('default', { month: 'long' });

        // Set data
        title.textContent = habit.name;
        months.textContent = `${startMonth} - ${endMonth}`;
        tbody.appendChild(createCalendar(startDate, endDate, habit['duration']));

        fragment.appendChild(habitTemplate);
        habitsContainer.appendChild(fragment);
    }

    function createCalendar(start, end, duration) {
        end.setDate(end.getDate() - 1);

        const fragment = document.createDocumentFragment();
        const startWeekDay = DateHelper.getWeekDayMondayStart(start);
        const endWeekDay = DateHelper.getWeekDayMondayStart(end);
        const currentDate = new Date(start.getTime());
        const totalWeeks = ((duration % 7) === 0 && startWeekDay > 0) ? 
            Number(duration) / 7 + 1 : 
            Math.ceil(Number(duration) / 7);

        for (let y = 0; y < totalWeeks; y++) {
            const week = document.createElement('tr');

            for (let x = 0; x < 7; x++) {
                const cell = document.createElement('td');

                if (y == 0 && x < startWeekDay) {
                    week.appendChild(cell);
                } else if (y == (totalWeeks - 1) && x > endWeekDay) {
                    week.appendChild(cell);
                } else {
                    cell.textContent = currentDate.getDate();
                    week.appendChild(cell);
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            }

            fragment.appendChild(week);
        }

        return fragment;
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