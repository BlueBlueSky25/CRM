import React, { useState, useEffect } from "react";
import { Calendar, dateFnsLocalizer } from "react-big-calendar";
import { format, parse, startOfWeek, getDay } from "date-fns";
import { enUS, id } from "date-fns/locale";
import withDragAndDrop from "react-big-calendar/lib/addons/dragAndDrop";
import "react-big-calendar/lib/css/react-big-calendar.css";
import "react-big-calendar/lib/addons/dragAndDrop/styles.css";
import axios from "axios";

// ============================================
// SETUP AXIOS DENGAN CSRF TOKEN
// ============================================
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

const DnDCalendar = withDragAndDrop(Calendar);

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
    const [events, setEvents] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
    const [selectedEvent, setSelectedEvent] = useState(null);
    const [formData, setFormData] = useState({
        title: "",
        start: "",
        end: "",
        allDay: false,
        description: "",
    });

    // Fetch events dari backend
    useEffect(() => {
        fetchEvents();
    }, []);

    const fetchEvents = async () => {
        try {
        const response = await axios.get("/api/calendar/events");
        const formattedEvents = response.data.map((event) => ({
            ...event,
            start: new Date(event.start),
            end: new Date(event.end),
        }));
        setEvents(formattedEvents);
        } catch (error) {
        console.error("Error fetching events:", error);
        setEvents([]);
        }
    };

    // CREATE: Tambah event baru
    const handleSelectSlot = ({ start, end }) => {
        setSelectedEvent(null);
        setFormData({
        title: "",
        start: start.toISOString().slice(0, 16),
        end: end.toISOString().slice(0, 16),
        allDay: false,
        description: "",
        });
        setShowModal(true);
    };

    // OPEN EDIT MODAL: Klik event untuk edit
    const handleSelectEvent = (event) => {
        console.log("Selected event:", event);
        setSelectedEvent(event);
        setFormData({
        title: event.title,
        start: new Date(event.start).toISOString().slice(0, 16),
        end: new Date(event.end).toISOString().slice(0, 16),
        allDay: event.allDay || false,
        description: event.description || "",
        });
        setShowModal(true);
    };

    // SAVE: Create atau Update
    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
        if (selectedEvent) {
            // UPDATE
            const response = await axios.put(
            `/api/calendar/events/${selectedEvent.id}`,
            formData
            );
            setEvents(
            events.map((ev) =>
                ev.id === selectedEvent.id
                ? {
                    ...response.data,
                    start: new Date(response.data.start),
                    end: new Date(response.data.end),
                    }
                : ev
            )
            );
            alert("Event berhasil diupdate!");
        } else {
            // CREATE
            const response = await axios.post("/api/calendar/events", formData);
            setEvents([
            ...events,
            {
                ...response.data,
                start: new Date(response.data.start),
                end: new Date(response.data.end),
            },
            ]);
            alert("Event berhasil ditambahkan!");
        }

        setShowModal(false);
        resetForm();
        } catch (error) {
        console.error("Error saving event:", error);
        let errorMsg = "Gagal menyimpan event";
        if (error.response?.status === 419) {
            errorMsg = "CSRF Token tidak valid. Refresh halaman dan coba lagi.";
        } else if (error.response?.data?.message) {
            errorMsg = error.response.data.message;
        } else if (error.response?.data?.errors) {
            const errors = Object.values(error.response.data.errors).flat();
            errorMsg = errors.join(", ");
        }
        alert(`ERROR: ${errorMsg}`);
        }
    };

    // DELETE: Tampilkan popup konfirmasi
    const handleDeleteClick = () => {
        setShowModal(false);
        setShowDeleteConfirm(true);
    };

    // DELETE: Proses delete setelah konfirmasi
    const confirmDelete = async () => {
        if (!selectedEvent || !selectedEvent.id) {
        alert("Error: Event tidak valid");
        return;
        }

        try {
        await axios.delete(`/api/calendar/events/${selectedEvent.id}`);
        setEvents(events.filter((ev) => ev.id !== selectedEvent.id));
        alert("Event berhasil dihapus!");
        setShowDeleteConfirm(false);
        resetForm();
        } catch (error) {
        console.error("DELETE ERROR:", error);
        let errorMsg = "Gagal menghapus event";
        if (error.response?.status === 404) {
            errorMsg = "Event tidak ditemukan";
        } else if (error.response?.data?.message) {
            errorMsg = error.response.data.message;
        }
        alert(`ERROR: ${errorMsg}`);
        setShowDeleteConfirm(false);
        }
    };

    // DRAG & DROP: Pindah atau resize event
    const moveEvent = async ({ event, start, end }) => {
        try {
        await axios.put(`/api/calendar/events/${event.id}`, {
            start: start.toISOString(),
            end: end.toISOString(),
        });

        const updatedEvents = events.map((e) =>
            e.id === event.id ? { ...e, start, end } : e
        );
        setEvents(updatedEvents);
        } catch (error) {
        console.error("Error moving event:", error);
        alert("Gagal memindahkan event");
        fetchEvents();
        }
    };

    const resetForm = () => {
        setFormData({
        title: "",
        start: "",
        end: "",
        allDay: false,
        description: "",
        });
        setSelectedEvent(null);
    };

    return (
        <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">Kalender CRM</h2>

        <DnDCalendar
            localizer={localizer}
            events={events}
            startAccessor="start"
            endAccessor="end"
            style={{ height: 600 }}
            selectable
            resizable
            onSelectSlot={handleSelectSlot}
            onSelectEvent={handleSelectEvent}
            onEventDrop={moveEvent}
            onEventResize={moveEvent}
            views={["month", "week", "day", "agenda"]}
        />

        {/* MODAL FORM */}
        {showModal && (
            <div 
            style={{
                position: 'fixed',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                zIndex: 9999
            }}
            >
            <div 
                style={{
                backgroundColor: 'white',
                borderRadius: '8px',
                padding: '24px',
                width: '90%',
                maxWidth: '500px',
                maxHeight: '90vh',
                overflow: 'auto'
                }}
            >
                <h3 className="text-xl font-bold mb-4">
                {selectedEvent ? "Edit Event" : "Tambah Event"}
                </h3>

                <form onSubmit={handleSubmit}>
                <div className="mb-4">
                    <label className="block text-sm font-medium mb-1">Judul</label>
                    <input
                    type="text"
                    className="w-full border border-gray-300 rounded px-3 py-2"
                    value={formData.title}
                    onChange={(e) =>
                        setFormData({ ...formData, title: e.target.value })
                    }
                    required
                    />
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-medium mb-1">Mulai</label>
                    <input
                    type="datetime-local"
                    className="w-full border border-gray-300 rounded px-3 py-2"
                    value={formData.start}
                    onChange={(e) =>
                        setFormData({ ...formData, start: e.target.value })
                    }
                    required
                    />
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-medium mb-1">Selesai</label>
                    <input
                    type="datetime-local"
                    className="w-full border border-gray-300 rounded px-3 py-2"
                    value={formData.end}
                    onChange={(e) =>
                        setFormData({ ...formData, end: e.target.value })
                    }
                    required
                    />
                </div>

                <div className="mb-4">
                    <label className="flex items-center">
                    <input
                        type="checkbox"
                        className="mr-2"
                        checked={formData.allDay}
                        onChange={(e) =>
                        setFormData({ ...formData, allDay: e.target.checked })
                        }
                    />
                    <span className="text-sm">All Day Event</span>
                    </label>
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-medium mb-1">
                    Deskripsi (Opsional)
                    </label>
                    <textarea
                    className="w-full border border-gray-300 rounded px-3 py-2"
                    rows="3"
                    value={formData.description}
                    onChange={(e) =>
                        setFormData({ ...formData, description: e.target.value })
                    }
                    ></textarea>
                </div>

                <div className="flex justify-between">
                    <div>
                    {selectedEvent && (
                        <button
                        type="button"
                        onClick={handleDeleteClick}
                        className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        >
                        Hapus
                        </button>
                    )}
                    </div>
                    <div className="flex gap-2">
                    <button
                        type="button"
                        onClick={() => {
                        setShowModal(false);
                        resetForm();
                        }}
                        className="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Simpan
                    </button>
                    </div>
                </div>
                </form>
            </div>
            </div>
        )}

        {/* MODAL DELETE CONFIRMATION */}
        {showDeleteConfirm && (
            <div 
            style={{
                position: 'fixed',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                zIndex: 10000
            }}
            >
            <div 
                style={{
                backgroundColor: 'white',
                borderRadius: '8px',
                padding: '24px',
                width: '90%',
                maxWidth: '400px'
                }}
            >
                <h3 className="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
                <p className="mb-6">
                Apakah Anda yakin ingin menghapus event <strong>"{selectedEvent?.title}"</strong>?
                </p>
                <div className="flex justify-end gap-2">
                <button
                    onClick={() => {
                    setShowDeleteConfirm(false);
                    setShowModal(true);
                    }}
                    className="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                >
                    Batal
                </button>
                <button
                    onClick={confirmDelete}
                    className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                >
                    Ya, Hapus
                </button>
                </div>
            </div>
            </div>
        )}
        </div>
    );
}