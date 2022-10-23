# Pretty Google Calendar

**Contributors:** LBell \
**Donate link:** https://github.com/sponsors/lbell \
**Tags:** calendar, google calendar, events, gcal, cal, fullcalendar, pretty calendar, pretty \
**Requires at least:** 3.0 \
**Tested up to:** 6.0.3 \
**Stable tag:** 1.4.1 \
**License:** GPLv2 or later \
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Embedded Google Calendars that don't suck.

## Description

**You:** I just want to embed a Google Calendar in my WordPress site. \
**Google:** Here's a special kind of ugly! \
**Pretty Google Calendar:** I got this.

This is a light and simple to use plugin that embeds Google Calendars in your website with style, beauty and grace.

Highlights:

- Out of the box support for Calendar grid and agenda list view
- Responsive design switches to list view on smaller screens
- List view customizable to day, week, month, year or custom number of days

How it works:

1. Continue to manage events using Google Calendar as you've always done.
1. Add a shortcode to your page.
1. Sigh with relief.
1. That's it.

## Installation

1. Upload the `pretty-google-calendar` folder to the `/wp-content/plugins/` directory.
1. Activate `Pretty Google Calendar` through the 'Plugins' menu in WordPress dashboard.
1. Obtain your Google Calendar API key (see below)
1. Add the API key to Settings -> Pretty Google Calendar Settings
1. Use the shortcode `[pretty_google_calendar gcal="calendarID@group.calendar.google.com"]` directly in your page or post content.

### Shortcode Options

`gcal="CalendarID,CalendarID"`\
Calendar ID of the desired google calendar (note: must be set to 'Make available to public'. To display multiple calendars, separate ID's by a comma. (Note: calendars must fall under same API access.))

`locale="en"` \
Sets the locale for calendar. Defaults to "en".

`list_type="listCustom"` \
Sets the list type. Options: `listDay`, `listWeek`, `listMonth`, `listYear`, and `listCustom`. (Also accepts day, week, month, year, and custom).
Defaults to `listCustom` for backward compatibility.

Note: `listCustom` allows you to set the number of days you want to display from the current date. Whereas listMonth shows all the events from this month (including past events), `list_type="custom" custom_days="28"` will show the next 28 days
across months.

`custom_days="28"` \
Sets the number of days to show in the list tab. Defaults to 28. Only used with listCustom.

`custom_list_button="list"` \
Sets the label for the listCustom button. Defaults to "list".

`views="dayGridMonth, listCustom"` \
Sets the view types available. If only one view is provided, no view switch buttons will be shown. Defaults to `dayGridMonth, listCustom`.

`initial_view="dayGridMonth"` \
Sets the default view to be displayed when opening the page. Defaults to `dayGridMonth`.

`enforce_listview_on_mobile="true"` \
Sets the change to the list view behavior on small screens. Options: `true` and `false`. Defaults to `true`. This option has no effect if there is no list view declared in the `views` option.

`show_today_button="true"` \
Sets the visibility of the `Today` button. Options: `true` and `false`. Defaults to `true`.

`show_title="true"` \
Sets the visibility of the calendar `title`. Options: `true` and `false`. Defaults to `true`.

### Obtaining Google Calendar API Key

1. Go to the Google Developer Console and create a new project (it might take a second).
1. Once in the project, go to **APIs & auth > APIs** on the sidebar.
1. Find “Calendar API” in the list and turn it ON.
1. On the sidebar, click **APIs & auth > Credentials**.
1. In the “Public API access” section, click “Create new Key”.
1. Choose “Browser key”.
1. If you know what domains will host your calendar, enter them into the box. Otherwise, leave it blank. You can always change it later.
1. Your new API key will appear. Copy this value into the Pretty Google Calendar settings box.
1. It might take second or two before your API starts working.

Make your Google Calendar public:

1. In the Google Calendar interface, locate the “My calendars” area on the left.
1. Hover over the calendar you need and click the downward arrow.
1. A menu will appear. Click “Share this Calendar”.
1. Check “Make this calendar public”.
1. Make sure “Share only my free/busy information” is unchecked.
1. Click “Save”.

Obtain your Google Calendar’s ID:

1. In the Google Calendar interface, locate the “My calendars” area on the left.
1. Hover over the calendar you need and click the downward arrow.
1. A menu will appear. Click “Calendar settings”.
1. In the “Calendar Address” section of the screen, you will see your Calendar ID. It will look something like “abcd1234@group.calendar.google.com” this is the value you enter into the shortcode.

## Screenshots

1. Pretty Google Calendar.

   <img src="assets/screenshot-1.png" height="400" />
&nbsp;
&nbsp;

2. List View.

   <img src="assets/screenshot-2.png" height="400" />
&nbsp;
&nbsp;

3. Optional Event Popover.

   <img src="assets/screenshot-3.png" height="400" />
&nbsp;
&nbsp;

4. Settings page. It's that simple.

   <img src="assets/screenshot-4.png" height="200" />
&nbsp;
&nbsp;

## Frequently Asked Questions

### What sorcery is this?!

Pretty Google Calendar impliments the excellent [Full Calendar](https://fullcalendar.io/) for you, and tosses in a little [Tippy.js](https://atomiks.github.io/tippyjs/) and [Popper](https://popper.js.org/) to make things... well... pop.

### Can I use this to manage a calendar?

No. All calendar events are maintaned via Google Calendar, this plugin just displays them in a non-shitty way.

### How do I theme the calendar?

Add custom css to your theme to tweak to your desire.

### Can this plugin do X,Y or Z?

Probably not. But it maybe could!

Pretty Google Calendar is purposefully simple and easy, set up with a few defaults to make things just work. However, there may be a killer feature you want that others are clammering for.

Since it is based on Full Calendar, theoretically, anything that is possible there is possible here. Contact me for requests for additional functionality, and let's see what we can create together!

## Changelog

### 1.4.1

- Fixed: localization text domain

### 1.4.0

- Added: support for multiple calendars displayed in one
- Added: full internationalization (Thanks @mwguerra!)
- Added: new shortcode parameters (view, initial_view, enforce_listview_on_mobile, show_today_button, show_title) (Heroic work by @mwguerra!)
- Tested to WordPress 6.0.3

### 1.3.1

- Version bump for WP's awkward versioning system

### 1.3.0

- Added: list type switcher
- Added: custom list button label
- Added: locale support

### 1.2.0

- Added: disable link option
- Added: "list_days" shortcode option
- Fixed: timezone on tooltip
- FullCalendar update to v5.11.0
- Tested to WordPress 5.9.3

### 1.1.0

Initial Public Release
