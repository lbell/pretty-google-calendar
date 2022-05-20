document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("pgcalendar");
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  //   console.log(pgcalSettings); // DEBUG

  function getItemByFuzzyValue(array, value) {
    return array.find((item) => item.toLowerCase().includes(value.toLowerCase()));
  }
  const listArray = ["listDay", "listWeek", "listMonth", "listYear", "listCustom"];
  const listType = getItemByFuzzyValue(listArray, pgcalSettings["list_type"]);
  //   console.log(listType); // DEBUG

  const calendar = new FullCalendar.Calendar(calendarEl, {
    locale: pgcalSettings["locale"],
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
      //   Custom View
      listCustom: {
        type: "list",
        duration: { days: parseInt(pgcalSettings["custom_days"]) },
        buttonText: pgcalSettings["custom_list_button"],
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
          right: `dayGridMonth,${listType}`,
        }
      : {
          left: "prev,next today",
          center: "title",
          right: `dayGridMonth,${listType}`,
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

    initialView: pgcal_is_mobile() ? listType : "dayGridMonth",

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (window.innerWidth !== width) {
        if (pgcal_is_mobile()) {
          calendar.changeView(listType);
        } else {
          calendar.changeView("dayGridMonth");
        }
      }
    },
  });

  calendar.render();
});
