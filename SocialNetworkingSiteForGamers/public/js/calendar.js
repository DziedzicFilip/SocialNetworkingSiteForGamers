document.addEventListener("DOMContentLoaded", function () {
    var calendar = new FullCalendar.Calendar(
        document.getElementById("calendar"),
        {
            initialView: "dayGridMonth",
            events: window.calendarEvents,
        }
    );
    calendar.render();
});
