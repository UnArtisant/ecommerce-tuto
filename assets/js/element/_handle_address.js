import {setDefaultAddress} from "../utils/api/userAddressApi";
import Swal from "sweetalert2"

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById("handleDefaultAddress")
    const select = document.getElementById("updateDefaultAddress")

    let defaultAddress = document.getElementById("defaultValue").value

    select.addEventListener("change", (e) => {
        defaultAddress = e.target.value
    })

    form.addEventListener("submit", async (e) => {
        e.preventDefault()
        const r = await setDefaultAddress(defaultAddress)
        console.log(r)
        if(r.error) {
            Swal.fire({
                title: 'Error',
                text: r.error.error,
                icon: 'error',
                confirmButtonText: 'ok'
            })
        } else {
            Swal.fire({
                title: 'Success',
                text: r.data.message,
                icon: 'success',
                confirmButtonText: 'Cool'
            }).then(function () {
                window.location = window.location.href
            });
        }
    })
})
