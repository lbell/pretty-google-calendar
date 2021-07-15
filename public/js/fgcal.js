function tooltipRender(info) {
  // console.log(info); // DEBUG

  var calID = info.event.source.internalEventSource.meta.googleCalendarId;
  var eventID = getEventId(info.event.url);

  var toolTitle = `<div class="toolhead"><h2> ${info.event.title} </h2></div> `;
  toolTitle += "<hr />";
  // toolTitle += breakify(urlify(info.event.extendedProps.description));
  toolTitle += breakify(info.event.extendedProps.description);

  // toolTitle += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(gcalLink(eventID, calID))}</div>`;
  toolTitle += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(info.event.url)}</div>`;

  var tooltip = new Tooltip(info.el, {
    // title: info.event.extendedProps.description,
    title: toolTitle,
    // placement: 'bottom',
    // reference: 'fgcalendar',
    // trigger: 'hover',
    trigger: "click",
    closeOnClickOutside: true,
    container: "body",
    html: true,
  });

  // Style all day events:
  // TODO add class instead
  if (info.event.allDay) {
    info.el.style.background = "#8bccec";
  }

  // TODO style sub events:
  if (info.event.title.includes("sub")) {
    // info.el.style.background="red";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("fgcalendar");
  calendarEl.innerHTML = "";

  console.log(fgcalSettings); // DEBUG

  var calendar = new FullCalendar.Calendar(calendarEl, {
    // Locale is untested -- proceed with caution.
    // locale: fgcalSettings['wplocale'],
    plugins: ["googleCalendar", "dayGrid", "list"],
    defaultView: "dayGridMonth",

    height: "auto",

    timeZone: fgcalSettings["fixed_tz"],
    timeZoneImpl: "UTC-coercion",

    // Note: Old Version
    // views: {
    //   month: {
    //     buttonText: 'month',
    //     // aspectRatio: 2,
    // },
    //   list: {
    //     buttonText: 'list',
    //     duration: { days: 10 },
    //   },
    // },

    header: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,listMonth",
    },

    // displayEventTime: true, // don't show the time column in list view

    googleCalendarApiKey: fgcalSettings["google_api"],
    events: fgcalSettings["gcal"],
  });

  calendar.render();

  // Turn on tooltip if set:
  if (fgcalSettings["use_tooltip"]) {
    calendar.setOption("eventRender", function (info) {
      tooltipRender(info);
    });
    calendar.setOption("eventClick", function (info) {
      info.jsEvent.preventDefault();
    });
  }

  var tzMessage =
    fgcalSettings["fixed_tz"] === "local"
      ? "(Times may be adjusted to your computer's timezone.)"
      : "(Times displayed in timezone of venue.)";
  document.getElementById("tz_message").innerHTML = tzMessage;
});
