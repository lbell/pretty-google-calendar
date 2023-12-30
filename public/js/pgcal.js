// document.addEventListener("DOMContentLoaded", function () {

/**
 * Get global settings securely via Ajax
 *
 * @returns global settings
 */
async function pgcalFetchGlobals(ajaxurl) {
  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", ajaxurl, true);
    xhr.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded; charset=UTF-8"
    );
    var data = "action=pgcal_ajax_action";
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        var response = JSON.parse(xhr.responseText);
        resolve(response);
      } else {
        reject("AJAX request failed with status " + xhr.status);
      }
    };
    xhr.send(data);
  });
}

async function pgcal_render_calendar(pgcalSettings, ajaxurl) {
  const globalSettings = await pgcalFetchGlobals(ajaxurl);
  console.log(globalSettings["google_api"]);

  const currCal = `pgcalendar-${pgcalSettings["id_hash"]}`;
  const calendarEl = document.getElementById(currCal);
  calendarEl.innerHTML = "";
  let width = window.innerWidth;

  const views = pgcal_resolve_views(pgcalSettings);
  const cals = pgcal_resolve_cals(pgcalSettings);

  // console.table(cals); // DEBUG
  // console.table(pgcalSettings); // DEBUG
  // console.table(views); // DEBUG

  const toolbarLeft = pgcal_is_truthy(pgcalSettings["show_today_button"])
    ? "prev,next today"
    : "prev,next";
  const toolbarCenter = pgcal_is_truthy(pgcalSettings["show_title"])
    ? "title"
    : "";
  const toolbarRight = views.length > 1 ? views.all.join(",") : "";

  let selectedView = views.initial;

  const pgcalDefaults = {
    locale: pgcalSettings["locale"],
    googleCalendarApiKey: globalSettings["google_api"],

    eventSources: cals,

    views: {
      // Options apply to dayGridMonth, dayGridWeek, and dayGridDay views
      dayGrid: {
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
          meridiem: "short",
        },
      },
      // Custom List View
      listCustom: {
        type: "list",
        duration: { days: parseInt(pgcalSettings["custom_days"]) },
        buttonText: pgcalSettings["custom_list_button"],
      },
    },

    // Day grid options
    eventDisplay: "block", // Adds border and bocks to events instead of bulleted list (default)
    height: "auto",
    fixedWeekCount: false, // True: 6 weeks, false: flex for month

    // List options
    listDayFormat: { weekday: "long", month: "long", day: "numeric" },

    timeZone: pgcalSettings["fixed_tz"], // TODO: Necessary?

    initialView: views.initial,

    headerToolbar: {
      left: toolbarLeft,
      center: toolbarCenter,
      right: toolbarRight,
    },

    eventDidMount: function (info) {
      if (pgcalSettings["use_tooltip"] === "true") {
        pgcal_tippyRender(info, currCal);
      }
    },

    eventClick: function (info) {
      if (
        pgcalSettings["use_tooltip"] === "true" ||
        pgcalSettings["no_link"] === "true"
      ) {
        info.jsEvent.preventDefault(); // Prevent following link
      }
    },

    // Change view on window resize
    windowResize: function (view) {
      // Catch mobile chrome, which changes window size as nav bar appears
      // so only fire if width has changed.
      if (
        window.innerWidth !== width &&
        views.hasList &&
        views.wantsToEnforceListviewOnMobile
      ) {
        if (pgcal_is_mobile()) {
          calendar.changeView(views.listType);
        } else {
          calendar.changeView(selectedView);
        }
      }
    },
  };
  
  const pgcalOverrides = JSON.parse(pgcalSettings["fc_args"]);
  const pgCalArgs = pgcal_argmerge(pgcalDefaults, pgcalOverrides);
  
  // console.log(pgcalSettings["fc_args"]);
  // console.log(JSON.stringify(pgcalDefaults));
  // console.log(JSON.stringify(pgCalArgs));

  const calendar = new FullCalendar.Calendar(calendarEl, pgCalArgs);
  calendar.render();
}
