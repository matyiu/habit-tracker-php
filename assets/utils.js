const DateHelper = {
    getWeekDayMondayStart(date) {
        const defaultWeekDay = date.getDay();

        return (defaultWeekDay + 6) % 7;
    }
};

export {
    DateHelper
};