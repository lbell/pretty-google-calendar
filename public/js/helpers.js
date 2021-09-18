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
