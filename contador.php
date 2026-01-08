<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscando Señal... | Jose Luis Arriola</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');

        body {
            background-color: #0a0a0a;
            color: #fdf2d9;
            font-family: 'Courier Prime', monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .radio-case {
            background: linear-gradient(180deg, #4e342e 0%, #2d1b0e 100%);
            padding: 40px;
            border-radius: 8px;
            border-bottom: 10px solid #1a0f08;
            box-shadow: 0 30px 60px rgba(0,0,0,0.8), inset 0 0 20px rgba(0,0,0,0.5);
            text-align: center;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        /* Pantalla de frecuencia Ámbar */
        .frequency-display {
            background-color: #1a0f08;
            border: 4px solid #111;
            padding: 30px 10px;
            border-radius: 4px;
            box-shadow: inset 0 0 15px rgba(255, 179, 71, 0.4);
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
        }

        .time-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .number {
            font-size: 3.5rem;
            font-weight: bold;
            color: #ffb347;
            text-shadow: 0 0 10px rgba(255, 179, 71, 0.7);
            line-height: 1;
        }

        .label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #8d6e63;
            margin-top: 10px;
        }

        .status-light {
            width: 12px;
            height: 12px;
            background-color: #450000;
            border-radius: 50%;
            margin: 0 auto 10px;
            transition: background-color 0.3s;
        }

        .status-light.active {
            background-color: #ff0000;
            box-shadow: 0 0 10px #ff0000;
        }

        .static-noise {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.05;
            pointer-events: none;
        }

        h1 {
            font-size: 1.2rem;
            letter-spacing: 4px;
            color: #ffb347;
            margin-bottom: 2rem;
            opacity: 0.8;
        }

        .message {
            color: #8d6e63;
            font-size: 0.8rem;
            margin-top: 2rem;
            text-transform: uppercase;
            border-top: 1px solid rgba(141, 110, 99, 0.2);
            padding-top: 1rem;
        }
    </style>
</head>
<body>
<div class="static-noise"></div>

<div class="radio-case">
    <div class="status-light active animate-pulse" id="pwrLed"></div>
    <h1>BUSCANDO SEÑAL...</h1>

    <div class="frequency-display">
        <div class="time-unit">
            <span class="number" id="days">00</span>
            <span class="label">Días</span>
        </div>
        <div class="time-unit">
            <span class="number" id="hours">00</span>
            <span class="label">Hrs</span>
        </div>
        <div class="time-unit">
            <span class="number" id="minutes">00</span>
            <span class="label">Min</span>
        </div>
        <div class="time-unit">
            <span class="number" id="seconds">00</span>
            <span class="label">Seg</span>
        </div>
    </div>

    <div class="message">
        Próxima transmisión: 11 de Enero | 00:00 hs<br>
        <span class="text-[10px] opacity-50">Sintonizando frecuencia: Jose Luis Arriola</span>
    </div>
</div>

<script>
    // Fecha objetivo: 11 de Enero de 2026, 00:00:00
    const targetDate = new Date("Jan 11, 2026 00:00:00").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
            // Si el tiempo terminó, recargar la página
            window.location.reload();
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days.toString().padStart(2, '0');
        document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
        document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
        document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');
    }

    // Actualizar cada segundo
    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
</body>
</html>