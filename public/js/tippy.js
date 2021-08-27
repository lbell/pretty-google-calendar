function tippyRender(info) {
  //   console.log(info); // DEBUG

  var calID = info.event.source.internalEventSource.meta.googleCalendarId;
  var eventID = getEventId(info.event.url);

  let timeString = info.event.allDay
    ? `${info.event.start.toDateString()}, All Day`
    : info.event.start.toLocaleString();

  var toolContent = `
		<h2> ${info.event.title} </h2>
		<p> ${timeString}</p>`;
  // toolContent += breakify(urlify(info.event.extendedProps.description));
  toolContent += breakify(info.event.extendedProps.description);

  // toolContent += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(gcalLink(eventID, calID))}</div>`;
  toolContent += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(info.event.url)}</div>`;

  tippy(info.el, {
    trigger: "click",
    content: toolContent,
    theme: "light", // TODO: from settings
    allowHTML: true,
    placement: "auto",
    interactive: "true", // Allows clicking inside
    appendTo: document.getElementById("fgcalendar"),
    maxWidth: 600, // TODO: from settings
  });
}
