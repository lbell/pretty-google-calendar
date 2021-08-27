document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("fgcalendar");
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  //   console.log(fgcalSettings); // DEBUG

  const calendar = new FullCalendar.Calendar(calendarEl, {
    // Pull GCal from settings.
    googleCalendarApiKey: fgcalSettings["google_api"],
    events: {
      googleCalendarId: fgcalSettings["gcal"],
    },

    // Locale is untested -- proceed with caution.
    // locale: fgcalSettings['wplocale'],

    views: {
      // options apply to dayGridMonth, dayGridWeek, and dayGridDay views
      dayGrid: {
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
          meridiem: "short",
        },
      },
      // Custom View
      listFourWeeks: {
        type: "list",
        duration: { days: 28 },
        buttonText: "list",
      },
    },

    // Day grid options
    eventDisplay: "block", // Adds border and bocks to events instead of bulleted list (default)
    height: "auto",
    fixedWeekCount: false, // True: 6 weeks, false: flex for month

    // list options
    listDayFormat: { weekday: "long", month: "long", day: "numeric" },

    timeZone: fgcalSettings["fixed_tz"],
    // timeZoneImpl: "UTC-coercion",

    headerToolbar: isMobile()
      ? {
          left: "prev,next today",
          center: "",
          right: "dayGridMonth,listFourWeeks",
        }
      : {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,listFourWeeks",
        },

    eventDidMount: function (info) {
      if (fgcalSettings["use_tooltip"]) {
        tippyRender(info);
      }
    },

    eventClick: function (info) {
      if (fgcalSettings["use_tooltip"]) {
        info.jsEvent.preventDefault(); // Prevent following link
      }
    },

    initialView: isMobile() ? "listFourWeeks" : "dayGridMonth",

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (window.innerWidth !== width) {
        if (isMobile()) {
          calendar.changeView("listFourWeeks");
        } else {
          calendar.changeView("dayGridMonth");
        }
      }
    },

    // displayEventTime: true, // don't show the time column in list view
  });

  calendar.render();

  // TODO: may be fixed in v6
  const tzMessage =
    fgcalSettings["fixed_tz"] === "local"
      ? "(Times may be adjusted to your computer's timezone.)"
      : "(Times displayed in timezone of venue.)";
  //   document.getElementById("tz_message").innerHTML = tzMessage;
});
