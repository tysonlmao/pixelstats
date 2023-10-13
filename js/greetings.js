var now = new Date();

var currentHour = now.getHours();

var greeting;
if (currentHour >= 5 && currentHour < 12) {
    greeting = 'Good morning, ';
} else if (currentHour >= 12 && currentHour < 18) {
    greeting = 'Good afternoon, ';
} else {
    greeting = 'Good evening, ';
}

window.onload = function () {
    document.getElementById('greeting').innerHTML = greeting;
};
