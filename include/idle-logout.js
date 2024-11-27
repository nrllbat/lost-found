(function () {
    let idleTime = 0; // Time in seconds

    // Increment the idle time counter every second
    const idleInterval = setInterval(timerIncrement, 1000); // 1 second interval

    function timerIncrement() {
        idleTime++;
        if (idleTime >= 900) { // 900 seconds = 15 minutes
            window.location.href = '../../include/logout.php'; // Redirect to logout
        }
    }

    // Reset idle time on any user interaction
    document.addEventListener("mousemove", resetIdleTime, false);
    document.addEventListener("keypress", resetIdleTime, false);
    document.addEventListener("click", resetIdleTime, false);
    document.addEventListener("scroll", resetIdleTime, false);

    function resetIdleTime() {
        idleTime = 0; // Reset the idle time to 0
    }
})();
