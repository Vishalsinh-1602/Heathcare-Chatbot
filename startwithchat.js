// Include the StartWithChat project script
// Assuming the StartWithChat project exposes a global object named StartWithChat

(function() {
    // Load the StartWithChat script dynamically
    const script = document.createElement('script');
    script.src = 'path/to/startwithchat.js'; // Update with the correct path to the StartWithChat script
    script.onload = function() {
        console.log('StartWithChat script loaded successfully.');
    };
    script.onerror = function() {
        console.error('Failed to load StartWithChat script.');
    };
    document.head.appendChild(script);
})();
