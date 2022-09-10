document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("pgcalendar");
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  const views = pgcal_resolve_views(pgcalSettings)

  // console.log(':: pgcalSettings')
  // console.table(pgcalSettings)
  // console.log(':: views')
  // console.table(views)

  let selectedView = views.initial

  const calendar = new FullCalendar.Calendar(calendarEl, {
    locale: pgcalSettings["locale"],
    googleCalendarApiKey: pgcalSettings["google_api"],
    events: {
      googleCalendarId: pgcalSettings["gcal"],
    },

    views: {
      // Options apply to dayGridMonth, dayGridWeek, and dayGridDay views
      dayGrid: {
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
          meridiem: "short",
        },
      },
      // Custom List View
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

    // List options
    listDayFormat: { weekday: "long", month: "long", day: "numeric" },

    timeZone: pgcalSettings["fixed_tz"], // TODO: Necessary?

    initialView: views.initial,

    headerToolbar: pgcal_is_mobile()
      ? {
        left: toolbarLeft,
        center: "",
        right: toolbarRight,
      }
      : {
        left: toolbarLeft,
        center: toolbarCenter,
        right: toolbarRight,
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

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (
        window.innerWidth !== width &&
        views.hasList &&
        views.wantsToEnforceListviewOnMobile
      ) {
        if (pgcal_is_mobile()) {
          selectedView = calendar.view.type
          return calendar.changeView(views.listType);
        }

        return calendar.changeView(selectedView);
      }

    }
  });

  calendar.render();
});
