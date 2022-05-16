document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("pgcalendar");
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  console.log(pgcalSettings); // DEBUG

  const calendar = new FullCalendar.Calendar(calendarEl, {
    // Pull GCal from settings.
    googleCalendarApiKey: pgcalSettings["google_api"],
    events: {
      googleCalendarId: pgcalSettings["gcal"],
    },

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
      daysListCustom: {
        type: "list",
        duration: { days: parseInt(pgcalSettings["list_days"]) },
        buttonText: "list",
      },
    },

    // Day grid options
    eventDisplay: "block", // Adds border and bocks to events instead of bulleted list (default)
    height: "auto",
    fixedWeekCount: false, // True: 6 weeks, false: flex for month

    // list options
    listDayFormat: { weekday: "long", month: "long", day: "numeric" },

    timeZone: pgcalSettings["fixed_tz"], // TODO: Necessary?

    headerToolbar: pgcal_is_mobile()
      ? {
          left: "prev,next today",
          center: "",
          right: "dayGridMonth,daysListCustom",
        }
      : {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,daysListCustom",
        },

    eventDidMount: function (info) {
      if (pgcalSettings["use_tooltip"]) {
        pgcal_tippyRender(info);
      }
    },

    eventClick: function (info) {
      if (pgcalSettings["use_tooltip"] || pgcalSettings["no_link"]) {
        info.jsEvent.preventDefault(); // Prevent following link
      }
    },

    initialView: pgcal_is_mobile() ? "daysListCustom" : "dayGridMonth",

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (window.innerWidth !== width) {
        if (pgcal_is_mobile()) {
          calendar.changeView("daysListCustom");
        } else {
          calendar.changeView("dayGridMonth");
        }
      }
    },
  });

  calendar.render();
});
