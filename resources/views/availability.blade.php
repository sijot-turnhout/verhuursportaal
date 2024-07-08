<x-layouts.guest>
    <div id="calendar" class="pt-3"></div>

    @section ('scripts')
        <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    buttonText: {today: 'vandaag'},
                    locale: 'nl',
                    initialView: 'dayGridMonth',
                    weekNumbers: true,
                    events: @json($leases),
            });

            calendar.render();
        });

        </script>
    @endsection
</x-layouts.guest>
