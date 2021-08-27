function tippyRender(info) {
  console.log(info); // DEBUG
  console.log("Test!"); // DEBUG
  console.log(info.event.start.toLocaleString()); // DEBUG
  console.log(info.event.start.getHours()); // DEBUG
  console.log(info.event.start.getMinutes()); // DEBUG
  console.log(info.event.start.toISOString()); // DEBUG

  //   console.log(Calendar.formatDate(info.event.startStr)); // DEBUG

  var calID = info.event.source.internalEventSource.meta.googleCalendarId;
  var eventID = getEventId(info.event.url);

  var toolContent = `
		<h2> ${info.event.title} </h2>
		<p> ${info.event.start.toLocaleString()}</p>`;
  // toolContent += breakify(urlify(info.event.extendedProps.description));
  toolContent += breakify(info.event.extendedProps.description);

  // toolContent += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(gcalLink(eventID, calID))}</div>`;
  toolContent += `<div class="toolloc">${mapify(info.event.extendedProps.location)} ${linkify(info.event.url)}</div>`;

  //   tippy(".fc-daygrid-event", {
  //   tippy(document.querySelectorAll(".fc-daygrid-event"), {
  tippy(info.el, {
    trigger: "click",
    // content: info.timeText,
    content: toolContent,
    theme: "light",
    allowHTML: true,
    placement: "auto",
    interactive: "true", // Allows clicking inside
    // appendTo: document.body,
    appendTo: document.getElementById("fgcalendar"),
    maxWidth: 600,
  });

  //   var tooltip = new Tooltip(info.el, {
  //     // title: info.event.extendedProps.description,
  //     title: toolContent,
  //     // placement: 'bottom',
  //     // reference: 'fgcalendar',
  //     trigger: "hover",
  //     // trigger: "click",
  //     closeOnClickOutside: true,
  //     container: "body",
  //     html: true,
  //   });

  // Style all day events:
  //   // TODO add class instead
  //   if (info.event.allDay) {
  //     info.el.style.background = "#8bccec";
  //   }

  //   // TODO style sub events:
  //   if (info.event.title.includes("sub")) {
  //     // info.el.style.background="red";
  //   }
}
