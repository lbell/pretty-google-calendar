function pgcal_tippyRender(info, currCal) {
  // console.log(info.event); // DEBUG
  // console.table(info.event.extendedProps); // DEBUG

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

  const location = info.event.extendedProps.location || "";

  const locString = location
    ? `<p class="pgcal-event-location">${location}</p>`
    : "";

  let toolContent = `
    <button class="pgcal-tooltip-close" aria-label="Close" type="button" style="position: absolute; top: 8px; right: 8px; background: none; border: none; font-size: 24px; cursor: pointer; padding: 0; line-height: 1; color: inherit; opacity: 0.7;">
      <span aria-hidden="true">&times;</span>
    </button>
    <h2 class="pgcal-event-title">${info.event.title} </h2>
    <p class="pgcal-event-time">${startTime}${endTime}</p>
    ${locString}`;

  const description = pgcal_breakify(
    pgcal_urlify(info.event.extendedProps.description)
  );
  toolContent += description
    ? `<div class="pgcal-event-description">${description}</div>`
    : "";

  const mapHtml = location ? pgcal_mapify(location) : "";
  const addToGoogleHtml = info.event.url ? pgcal_addToGoogle(info.event.url) : "";
  const actionsHtml = [mapHtml, addToGoogleHtml].filter(Boolean).join(" ");

  if (actionsHtml) {
    toolContent += `<div class="toolloc pgcal-event-actions">${actionsHtml}</div>`;
  }

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
    onShow(instance) {
      // Attach close button handler when tooltip is shown
      const closeBtn = instance.popper.querySelector(".pgcal-tooltip-close");
      if (closeBtn) {
        const handleCloseClick = (e) => {
          e.stopPropagation(); // Prevent triggering other click handlers
          instance.hide();
        };
        closeBtn.addEventListener("click", handleCloseClick);
        // Store reference for cleanup
        closeBtn._pgcalCloseHandler = handleCloseClick;
      }
    },
    onHide(instance) {
      // Remove close button handler when tooltip is hidden
      const closeBtn = instance.popper.querySelector(".pgcal-tooltip-close");
      if (closeBtn && closeBtn._pgcalCloseHandler) {
        closeBtn.removeEventListener("click", closeBtn._pgcalCloseHandler);
        delete closeBtn._pgcalCloseHandler;
      }
    },
  });
}
