function pgcal_tippyRender(info, currCal) {
  // console.log(info.event); // DEBUG

  const startTime = info.event.allDay
    ? "All Day"
    : new Date(info.event.startStr).toLocaleTimeString([], {
        hour: "numeric",
        minute: "2-digit",
      });

  const endTime = info.event.allDay
    ? ""
    : " - " +
      new Date(info.event.endStr).toLocaleTimeString([], {
        hour: "numeric",
        minute: "2-digit",
      });

  const locString = info.event.extendedProps.location
    ? `<p>${info.event.extendedProps.location}</p>`
    : "";

  let toolContent = `
    <h2>${info.event.title} </h2>
    <p>${startTime}${endTime}</p>
    ${locString}`;

  toolContent += pgcal_breakify(
    pgcal_urlify(info.event.extendedProps.description)
  );

  toolContent += `<div class="toolloc">${pgcal_mapify(
    info.event.extendedProps.location
  )} ${pgcal_linkify(info.event.url)}</div>`;

  tippy(info.el, {
    trigger: "click",
    content: toolContent,
    theme: "light", // TODO: from settings
    allowHTML: true,
    placement: pgcal_is_mobile() ? "bottom" : "auto",
    popperOptions: pgcal_is_mobile()
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
    appendTo: document.getElementById(currCal),
    maxWidth: 600, // TODO: from settings
    boundary: "window",
  });
}
