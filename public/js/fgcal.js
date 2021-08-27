document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("fgcalendar");
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  function mobileCheck() {
    if (window.innerWidth <= 768) {
      return true;
    } else {
      return false;
    }
  }
  console.log(mobileCheck()); // DEBUG

  //   console.log(fgcalSettings); // DEBUG

  var calendar = new FullCalendar.Calendar(calendarEl, {
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
    },
    eventDisplay: "block", // Adds border and bocks to events instead of bulleted list (default)
    height: "auto",

    timeZone: fgcalSettings["fixed_tz"],
    // timeZoneImpl: "UTC-coercion",

    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listMonth",
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

    initialView: window.innerWidth <= 768 ? "listMonth" : "dayGridMonth",

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (window.innerWidth !== width) {
        if (window.innerWidth <= 768) {
          calendar.changeView("listMonth");
        } else {
          calendar.changeView("dayGridMonth");
        }
      }
    },

    // displayEventTime: true, // don't show the time column in list view
  });

  calendar.render();

  var tzMessage =
    fgcalSettings["fixed_tz"] === "local"
      ? "(Times may be adjusted to your computer's timezone.)"
      : "(Times displayed in timezone of venue.)";
  document.getElementById("tz_message").innerHTML = tzMessage;
});
