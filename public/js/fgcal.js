document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("fgcalendar");
  calendarEl.innerHTML = "";

  console.log(fgcalSettings); // DEBUG

  var calendar = new FullCalendar.Calendar(calendarEl, {
    // Locale is untested -- proceed with caution.
    // locale: fgcalSettings['wplocale'],
    // plugins: ["googleCalendarPlugin", "dayGrid", "listPlugin"],
    defaultView: "dayGridMonth",
    // height: "auto",

    timeZone: fgcalSettings["fixed_tz"],
    timeZoneImpl: "UTC-coercion",

    // Add buttons to toolbar
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listMonth",
    },

    // Take over event click
    eventClick: function (info) {
      if (fgcalSettings["use_tooltip"]) {
        info.jsEvent.preventDefault();
        tooltipRender(info);
        // alert("hi"); // DEBUG
      }
    },

    // Change view on window resize
    windowResize: function (view) {
      if (window.innerWidth >= 768) {
        calendar.changeView("dayGridMonth");
      } else {
        calendar.changeView("listMonth");
      }
    },

    // displayEventTime: true, // don't show the time column in list view

    googleCalendarApiKey: fgcalSettings["google_api"],
    events: {
      googleCalendarId: fgcalSettings["gcal"],
    },
  });

  calendar.render();

  // Turn on tooltip if set:
  //   if (fgcalSettings["use_tooltip"]) {
  // calendar.setOption("eventRender", function (info) {
  //   tooltipRender(info);
  // });
  // calendar.setOption("eventClick", function (info) {
  //   info.jsEvent.preventDefault();
  // });

  // }

  var tzMessage =
    fgcalSettings["fixed_tz"] === "local"
      ? "(Times may be adjusted to your computer's timezone.)"
      : "(Times displayed in timezone of venue.)";
  document.getElementById("tz_message").innerHTML = tzMessage;
});
