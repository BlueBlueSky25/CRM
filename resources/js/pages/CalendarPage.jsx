import React, { useState } from "react";
import { Calendar, dateFnsLocalizer } from "react-big-calendar";
import { format, parse, startOfWeek, getDay } from "date-fns";
import { enUS, id } from "date-fns/locale";
import withDragAndDrop from "react-big-calendar/lib/addons/dragAndDrop";
import "react-big-calendar/lib/css/react-big-calendar.css";
import "react-big-calendar/lib/addons/dragAndDrop/styles.css";

// ✅ drag & drop wrapper
const DnDCalendar = withDragAndDrop(Calendar);

// locale config (pakai bahasa Indonesia, default en-US)
const locales = {
    "id-ID": id,
    "en-US": enUS,
    };

    const localizer = dateFnsLocalizer({
    format,
    parse,
    startOfWeek: () => startOfWeek(new Date(), { weekStartsOn: 1 }),
    getDay,
    locales,
});

export default function CalendarPage() {
    const [events, setEvents] = useState([
        {
        id: 1,
        title: "Meeting dengan Client",
        start: new Date(2025, 9, 7, 10, 0),
        end: new Date(2025, 9, 7, 11, 0),
        },
        {
        id: 2,
        title: "Deadline Proposal",
        start: new Date(2025, 9, 8),
        end: new Date(2025, 9, 8),
        allDay: true,
        },
    ]);

  // ✅ handler kalau event dipindah / resize
const moveEvent = ({ event, start, end }) => {
    const updatedEvents = events.map((e) =>
        e.id === event.id ? { ...e, start, end } : e
    );
    setEvents(updatedEvents);
    };

return (
    <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">📅 Kalender CRM (Drag & Drop)</h2>
        <DnDCalendar
        localizer={localizer}
        events={events}
        startAccessor="start"
        endAccessor="end"
        style={{ height: 600 }}
        selectable
        resizable
        onEventDrop={moveEvent}   // <-- drag event
        onEventResize={moveEvent} // <-- resize event
        views={["month", "week", "day", "agenda"]}
        />
    </div>
    );
}