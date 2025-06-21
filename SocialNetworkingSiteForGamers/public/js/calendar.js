document.addEventListener("DOMContentLoaded", function () {
    var calendar = new FullCalendar.Calendar(
        document.getElementById("calendar"),
        {
            initialView: "dayGridMonth",
            events: window.calendarEvents,
            eventContent: function (arg) {
                return { html: arg.event.title };
            },
        }
    );
    calendar.render();
});
