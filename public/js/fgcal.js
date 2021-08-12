document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("fgcalendar");
  calendarEl.innerHTML = "";

  console.log(fgcalSettings); // DEBUG

  var calendar = new FullCalendar.Calendar(calendarEl, {
    // Locale is untested -- proceed with caution.
    // locale: fgcalSettings['wplocale'],
    // defaultView: "dayGridMonth",
    views: {
      // options apply to dayGridMonth, dayGridWeek, and dayGridDay views
      dayGrid: {
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
          meridiem: "short",
        },
      },
    },
    eventDisplay: "block", // Adds border and bocks to events instead of bulleted list (default)
    // height: "auto",

    timeZone: fgcalSettings["fixed_tz"],
    // timeZoneImpl: "UTC-coercion",

    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listMonth",
    },

    eventClick: function (info) {
      if (fgcalSettings["use_tooltip"]) {
        info.jsEvent.preventDefault(); // Prevent following link
        console.log(info.event.startStr); // DEBUG
        // tippyRender(info);
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

    // Pull GCal from settings.
    googleCalendarApiKey: fgcalSettings["google_api"],
    events: {
      googleCalendarId: fgcalSettings["gcal"],
    },
  });

  calendar.render();

  var tzMessage =
    fgcalSettings["fixed_tz"] === "local"
      ? "(Times may be adjusted to your computer's timezone.)"
      : "(Times displayed in timezone of venue.)";
  document.getElementById("tz_message").innerHTML = tzMessage;
});
