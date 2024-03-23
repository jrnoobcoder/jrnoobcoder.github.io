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