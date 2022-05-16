function pgcal_tippyRender(info) {
  //   console.log(info.event); // DEBUG

  const timeString = info.event.allDay ? "All Day" : new Date(info.event.startStr).toLocaleTimeString();

  let toolContent = `
		<h2> ${info.event.title} </h2>
		<p> ${timeString}</p>`;
  toolContent += pgcal_breakify(info.event.extendedProps.description);

  toolContent += `<div class="toolloc">${pgcal_mapify(info.event.extendedProps.location)} ${pgcal_linkify(
    info.event.url
  )}</div>`;

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
    appendTo: document.getElementById("pgcalendar"),
    maxWidth: 600, // TODO: from settings
    boundary: "window",
  });
}
