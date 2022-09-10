
/**
 * Computes all variables related to views
 *
 * @param {array} settings Settings received from the shortcode parameters
 * @returns object
 */
const pgcal_resolve_views = (settings) => {
  const gridViews = ["dayGridMonth"]
  const listViews = ["listDay", "listWeek", "listMonth", "listYear", "listCustom"];
  const allowedViews = [...listViews, ...gridViews]

  const wantsToEnforceListviewOnMobile = pgcal_is_truthy(
    settings["enforce_listview_on_mobile"]
  )

  let initialView = 'dayGridMonth'

  if (allowedViews.includes(settings['initial_view'])) {
    initialView = settings['initial_view']
  }

  const viewsArray = pgcal_csv_to_array(settings["views"])
  const viewsIncludesList = pgcal_get_item_by_fuzzy_value(viewsArray, 'list')
  const listType = pgcal_get_item_by_fuzzy_value(viewsArray, settings["list_type"]);

  if (pgcal_is_mobile() && wantsToEnforceListviewOnMobile) {
    initialView = listType
  }

  const views = {
    all: viewsArray,
    length: viewsArray.length,
    hasList: !!viewsIncludesList,
    listType,
    initial: initialView,
    wantsToEnforceListviewOnMobile,
  }

  return views
}

/**
 * Tests if the given array has the value in any part of each item
 *
 * @param {string} csv Array to be tested
 * @returns array
 */
const pgcal_csv_to_array = (csv) => csv
  .split(',')
  .map(view => view.trim())

/**
 * Tests if the given array has the value in any part of each item
 *
 * @param {array} array Array to be tested
 * @param {string} value String to be checked
 * @returns boolean
 */
const pgcal_get_item_by_fuzzy_value = (array, value) => array.find(
  item => item.toLowerCase().includes(value.toLowerCase())
)

/**
 * Tests if a value is truthy
 *
 * @param {string} value String to be tested
 * @returns boolean
 */
function pgcal_is_truthy(value) {
  const lowercaseValue = (typeof value === 'string') ? value.toLowerCase() : value
  return ['true', '1', true, 1].includes(lowercaseValue);
}

/**
 * Tests whether the window size is equal to or less than 768... an arbitrary
 * standard for what is mobile...
 *
 * @returns boolean
 */
function pgcal_is_mobile(width = 768) {
  return window.innerWidth <= width;
}

/**
 * Detect URLs and encase them in <a>
 *
 * @param {*} text
 * @returns
 */
function pgcal_urlify(text) {
  const urlRegex = /(https?:\/\/[^\s]+)/g;
  if (text) {
    return text.replace(urlRegex, '<a target="_blank" href="$1">$1</a>');
  }
  return "";
}

/**
 * Find breaks, and add <br />
 *
 * @param {string} text
 * @returns
 */
function pgcal_breakify(text) {
  if (text) {
    return text.replace(/(?:\r\n|\r|\n)/g, "<br />");
  }
  return "";
}

/**
 * Create map button
 *
 * @param {string} text Text of map link
 * @returns Formatted map button
 */
function pgcal_mapify(text) {
  let footer = "";
  if (text) {
    footer += `<br /><a class="button" target="_blank" href="https://www.google.com/maps/search/?api=1&query=${encodeURI(
      text
    )}">Map</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp`;
  }
  return footer;
}

/**
 * Converts url to a formatted <a href=... link
 *
 * @param {string} url
 * @returns formatted HTML url
 */
function pgcal_linkify(url) {
  if (url) {
    return `<a class="button" href="${url}" target="_blank">Add to Google Calendar</a>`;
  }
}
