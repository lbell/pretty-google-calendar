function tippyRender(info) {
  console.log(info.event); // DEBUG

  //   const calID = info.event.source.internalEventSource.meta.googleCalendarId;
  //   const eventID = getEventId(info.event.url);
  //   const isMobile = window.innerWidth <= 768;

  const timeString = info.event.allDay
    ? `${info.event.start.toDateString()}, All Day`
    : info.event.start.toLocaleString();

  let toolContent = `
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
    placement: isMobile() ? "bottom" : "auto",
    popperOptions: isMobile()
      ? {
          modifiers: [
            {
              name: "flip",
              enabled: false,
              options: {
                // flipBehavior: ['bottom', 'right', 'top']
                // fallbackPlacements: ['right', 'top'],
              },
            },
          ],
        }
      : "",
    interactive: "true", // Allows clicking inside
    appendTo: document.getElementById("pgcalendar"),
    maxWidth: 600, // TODO: from settings
    boundary: "window",
  });
}
