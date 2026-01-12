<header
    class="radio-top-bar fixed top-0 left-0 w-full p-4 flex flex-col md:flex-row items-center justify-between gap-4 px-8">
    <div
        class="knob-unit flex flex-col items-center gap-1 fixed bottom-4 right-4 z-50 min-[771px]:static min-[771px]:z-auto">
        <div class="button-bezel bg-[#1a0f08] p-1.5 rounded shadow-inner">
            <button id="powerBtn" class="push-button btn-power-off">OFF</button>
        </div>
        <p class="text-[10px] uppercase font-bold text-[#8d6e63] hidden min-[771px]:block">Señal</p>
        <span id="powerLed" class="w-1.5 h-1.5 rounded-full bg-[#ff0000]"></span>
    </div>

    <div class="flex-1 w-full max-w-4xl">
        <div class="dial-window h-20">
            <div id="needleContainer" class="needle-container" style="left: 0%;">
                <div class="needle-plastic"></div>
                <div class="needle-line"></div>
            </div>
            <div class="dial-markings">
                <span><a href="#s1">INICIO</a></span>
                <span><a href="#s2">GALERÍA</a></span>
                <span><a href="#s3">TOUR</a></span>
                <span><a href="#s4">DISCOS</a></span>
                <span><a href="#s5">MULTIMEDIA</a></span>
                <span><a href="#s6">REDES</a></span>
            </div>
        </div>
    </div>

    <div class="knob-unit hidden min-[771px]:flex flex-col items-center gap-1">
        <div class="knob-container">
            <div class="knob-shadow"></div>
            <div id="tuningKnob" class="knob">
                <div class="knob-indicator"></div>
            </div>
        </div>
        <p class="text-[10px] uppercase font-bold text-[#8d6e63]">Sintonía</p>
    </div>
</header>