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
