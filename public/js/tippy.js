function pgcal_tippyRender(info, currCal, pgcalSettings) {
  // console.log(info.event); // DEBUG
  // console.table(info.event.extendedProps); // DEBUG
  console.log(info.el.classList); // DEBUG

  // Extract calendar index from event element for styling
  let popupClass = "";
  for (let className of info.el.classList) {
    if (className.startsWith("pgcal-event-")) {
      const calendarIndex = className.replace("pgcal-event-", "");
      popupClass = `pgcal-calendar-${calendarIndex}-popup`;
      break;
    }
  }

  const startTime = info.event.allDay
    ? "All Day"
    : new Date(info.event.startStr).toLocaleTimeString([], {
        hour: "numeric",
        minute: "2-digit",
      });

  // Check if displayEventEnd is disabled via fc_args
  let displayEventEnd = true;
  if (pgcalSettings && pgcalSettings["fc_args"]) {
    try {
      const fcArgs = JSON.parse(pgcalSettings["fc_args"]);
      if (fcArgs.hasOwnProperty("displayEventEnd")) {
        displayEventEnd = fcArgs.displayEventEnd;
      }
    } catch (e) {
      // Invalid JSON, use default
    }
  }

  const endTime =
    !displayEventEnd || info.event.allDay
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

  // Handle free/busy calendars with undefined titles
  // Google Calendar API returns the string "undefined" for free/busy events
  const eventTitle =
    !info.event.title || info.event.title === "undefined"
      ? __("Busy", "pretty-google-calendar")
      : info.event.title;

  let toolContent = `
    <button class="pgcal-tooltip-close" aria-label="Close" type="button" style="position: absolute; top: 8px; right: 8px; background: none; border: none; font-size: 24px; cursor: pointer; padding: 0; line-height: 1; color: inherit; opacity: 0.7;">
      <span aria-hidden="true">&times;</span>
    </button>
    <h2 class="pgcal-event-title">${eventTitle} </h2>
    <p class="pgcal-event-time"><span class="pgcal-event-start-time">${startTime}</span><span class="pgcal-event-end-time">${endTime}</span></p>
    ${locString}`;

  const description = pgcal_breakify(
    pgcal_urlify(info.event.extendedProps.description)
  );
  toolContent += description
    ? `<div class="pgcal-event-description">${description}</div>`
    : "";

  const mapHtml = location ? pgcal_mapify(location) : "";
  const addToGoogleHtml = info.event.url
    ? pgcal_addToGoogle(info.event.url)
    : "";
  const downloadICSHtml = pgcal_downloadEventICS(info.event);
  const actionsHtml = [mapHtml, addToGoogleHtml, downloadICSHtml]
    .filter(Boolean)
    .join(" ");

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
      // Add popup class to tooltip for styling
      if (popupClass) {
        instance.popper.classList.add(popupClass);
      }
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
