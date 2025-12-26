window.onscroll = function() {
    // 1. Calculamos el porcentaje de scroll
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrollPercentage = scrollTop / scrollHeight;

    // 2. Movemos la aguja (Needle)
    const dialContainer = document.querySelector('.dial-container');
    const needle = document.getElementById('needle');
    
    // Calculamos el límite de movimiento dentro del contenedor
    const margin = 40; // Margen superior e inferior
    const maxMove = dialContainer.offsetHeight - needle.offsetHeight - margin * 2;
    const moveAmount = scrollPercentage * maxMove + margin;

    
    needle.style.transform = `translateY(${moveAmount}px)`;

    // 3. Giramos la perilla (Knob)
    const knob = document.getElementById('knob');
    const rotation = scrollPercentage * 360 * 2; // Gira 2 veces completas
    knob.style.transform = `rotate(${rotation}deg)`;
};