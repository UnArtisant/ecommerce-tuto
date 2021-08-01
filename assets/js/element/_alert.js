const alerts = document.querySelectorAll(".alert")

if(alerts) {
    alerts.forEach(a => {
        const close = a.querySelector(".close-alert")
        close.addEventListener("click", () => {
            a.style.display = "none"
            a.setAttribute("aria-hidden", true)
        })
    })
}