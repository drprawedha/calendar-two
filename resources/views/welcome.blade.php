<!DOCTYPE html>
<html>
<head>

<title>Kalender Manajemen</title>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
#calendar{
max-width:1100px;
margin:40px auto;
}
</style>

</head>

<body>

<div class="container mt-5">

<h2 class="text-center mb-4">📅 Kalender Manajemen</h2>

<a href="{{ url('events-calendar/download') }}" class="btn btn-success mb-3">Download CSV</a>

<input
type="text"
id="searchEvent"
class="form-control mb-3"
placeholder="Cari nama event..."
>

<div id="calendar"></div>

</div>


<!-- MODAL TAMBAH EVENT -->

<div class="modal fade" id="eventModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ url('event') }}">
@csrf

<div class="modal-header">
<h5>Tambah Event</h5>
</div>

<div class="modal-body">

<input type="hidden" name="tanggal" id="tanggal">

<div class="mb-2">
<label>Judul</label>
<input type="text" name="judul" class="form-control">
</div>

<div class="mb-2">
<label>Deskripsi</label>
<textarea name="deskripsi" class="form-control"></textarea>
</div>

</div>

<div class="modal-footer">
<button class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
</div>



<!-- MODAL EDIT EVENT -->

<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" id="editForm">
@csrf
@method('PUT')

<div class="modal-header">
<h5>Edit Event</h5>
</div>

<div class="modal-body">

<input type="hidden" id="delete_id">

<div class="mb-2">
<label>Tanggal</label>
<input type="date" name="tanggal" id="edit_tanggal" class="form-control">
</div>

<div class="mb-2">
<label>Judul</label>
<input type="text" name="judul" id="edit_judul" class="form-control">
</div>

<div class="mb-2">
<label>Deskripsi</label>
<textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea>
</div>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-danger" id="deleteBtn">
Hapus
</button>

<button class="btn btn-primary">Update</button>

</div>

</form>

</div>
</div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

var calendarEl = document.getElementById('calendar');

var allEvents = [

@foreach($events as $event)
{
id:'{{ $event->id }}',
title:'{{ $event->judul }}',
start:'{{ $event->tanggal }}',
deskripsi: {!! json_encode($event->deskripsi) !!}
},
@endforeach

];

var calendar = new FullCalendar.Calendar(calendarEl, {

initialView:'dayGridMonth',

events: allEvents,

dateClick:function(info){

document.getElementById('tanggal').value = info.dateStr;

new bootstrap.Modal(document.getElementById('eventModal')).show();

},

eventClick:function(info){

var event = info.event;

document.getElementById('edit_tanggal').value = event.startStr;
document.getElementById('edit_judul').value = event.title;
document.getElementById('edit_deskripsi').value = event.extendedProps.deskripsi;

document.getElementById('editForm').action = "/events-calendar/event/update/" + event.id;
document.getElementById('delete_id').value = event.id;

new bootstrap.Modal(document.getElementById('editModal')).show();

}

});

calendar.render();


// SEARCH
document.getElementById("searchEvent").addEventListener("keyup", function(){

var keyword = this.value.toLowerCase();

var filtered = allEvents.filter(e =>
e.title.toLowerCase().includes(keyword)
);

calendar.removeAllEvents();
calendar.addEventSource(filtered);

});


// DELETE
document.getElementById("deleteBtn").addEventListener("click", function(){

var id = document.getElementById("delete_id").value;

if(confirm("Yakin hapus event?")){

fetch("/events-calendar/event/delete/" + id, {
method: "POST",
headers: {
"X-CSRF-TOKEN": "{{ csrf_token() }}",
"Content-Type": "application/json"
},
body: JSON.stringify({_method:"DELETE"})
})
.then(() => location.reload());

}

});

});

</script>


</body>
</html>
