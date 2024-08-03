$(document).ready(function () {
    $(function () {
        $.scrollUp({
            animation: 'fade',
            activeOverlay: '#00FFFF',
            scrollImg: {
                active: true,
                type: 'background',
                src: 'img/top.png'
            }
        });
    });
})


// Contact Us Form start

var form = document.getElementById("my-form");
      
async function handleSubmit(event) {
event.preventDefault();
var status = document.getElementById("my-form-status");
var data = new FormData(event.target);
fetch(event.target.action, {
    method: form.method,
    body: data,
    headers: {
        'Accept': 'application/json'
    }
}).then(response => {
    if (response.ok) {
        Swal.fire({
            title: "Thank You",
            text: "Form submission done!",
            icon: "success"
          });
    //status.innerHTML = "Thanks for your submission!";
    form.reset()
    } else {
    response.json().then(data => {
        if (Object.hasOwn(data, 'errors')) {
        status.innerHTML = data["errors"].map(error => error["message"]).join(", ")
        } else {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "There was a problem submitting your form",
              });
        //status.innerHTML = "Oops! There was a problem submitting your form"
        }
    })
    }
}).catch(error => {
    status.innerHTML = "Oops! There was a problem submitting your form"
});
}
form.addEventListener("submit", handleSubmit)
// Contact Us Form End


document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('confettiCanvas');
    const ctx = canvas.getContext('2d');
    const emojiContainer = document.getElementById('emojiContainer');

    // Set canvas size
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // Confetti properties
    const colors = ['#ff6f61', '#f3a683', '#63cdda', '#f6b93b', '#d0e1f9'];
    let particles = [];

    // Emoji properties
    const emojis = ['❤️', '😍', '🥳', '🎉', '🌟'];

    function createConfetti() {
        const x = Math.random() * canvas.width;
        const y = Math.random() * canvas.height;
        const color = colors[Math.floor(Math.random() * colors.length)];
        const size = Math.random() * 5 + 2;
        const speed = Math.random() * .3 + 2;
        const angle = Math.random() * 2 * Math.PI;
        particles.push({ x, y, color, size, speed, angle });
    }

    console.log("afas");
    

    function updateConfetti() {
        particles.forEach(p => {
            p.x += p.speed * Math.cos(p.angle);
            p.y += p.speed * Math.sin(p.angle);
            p.size *= 0.98; // Shrink particles over time
        });

        // Remove particles that are too small
        particles = particles.filter(p => p.size > 0.5);
    }

    function drawConfetti() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, 2 * Math.PI);
            ctx.fillStyle = p.color;
            ctx.fill();
        });
    }

    function animate() {
        createConfetti();
        //createEmoji();
       updateConfetti();
        drawConfetti();
        requestAnimationFrame(animate);
    }

    // Start animation
    animate();

    // Handle resizing
    window.addEventListener('resize', () => {
        
        canvas.height = window.innerHeight;
    });

    window.addEventListener('resize', () => {
        
        canvas.width = window.innerWidth;
    });
});

