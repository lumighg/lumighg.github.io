<?php
require_once __DIR__ . '/../app/bootstrap.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Kalender - KJG Albachten</title>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<style>
    .sidebar{
        position:fixed;
        right:-420px;
        top:0;
        width:380px;
        height:100vh;
        background:white;
        box-shadow:-10px 0 30px rgba(0,0,0,0.25);
        padding:25px;
        transition:0.35s ease;
        z-index:2000;
        border-radius:20px 0 0 20px;
    }

    .sidebar.active{
        right:0;
    }

    #overlay{
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
        display:none;
        z-index:1500;
    }

    #overlay.active{
        display:block;
    }

    .btn{
        display:block;
        background:#00b7b3;
        color:white;
        padding:12px;
        text-align:center;
        margin:15px 0;
        text-decoration:none;
        border-radius:10px;
        font-weight:bold;
    }

    #sb-title{
        color:#00b7b3;
        margin-bottom:10px;
    }

    .info-box{
        background:#f4f4f4;
        padding:10px;
        border-radius:10px;
        margin:10px 0;
    }
</style>

</head>
<body>

<header>
    <h1>KJG Albachten Kalender</h1>
</header>

<div id="calendar"></div>
<div id="sidebar" class="sidebar">

    <h2 id="sb-title"></h2>

    <div class="info-box">
        <p id="sb-date"></p>
    </div>

    <div class="info-box">
        <p id="sb-desc"></p>
    </div>

    <div class="info-box">
        <p id="sb-spots"></p>
    </div>

    <a id="sb-link" href="#" class="btn">
        Zur Anmeldung
    </a>

    <button onclick="closeSidebar()">Schließen</button>

</div>
    <div id="overlay" onclick="closeSidebar()"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        events: '/api/events.php',

        eventClick: function(info){
            document.getElementById("sb-title").innerText =
                    info.event.title;

                document.getElementById("sb-date").innerText =
                    "📅 " + info.event.start.toLocaleString();

                document.getElementById("sb-desc").innerText =
                    info.event.extendedProps.description || "Keine Beschreibung";

                document.getElementById("sb-link").href =
                    "/event.php?id=" + info.event.id;

                document.getElementById("sidebar").classList.add("active");
                document.getElementById("overlay").classList.add("active");
        }

    });

    calendar.render();
});
</script>

</body>
</html>