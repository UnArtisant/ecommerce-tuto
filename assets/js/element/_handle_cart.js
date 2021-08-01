import {add_item} from "../utils/api/cartApi";
import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll(".add-cart")
    const cardItemCount = document.getElementById("cardItemCount")
    buttons.forEach(b => {
        b.addEventListener("click", async (e) => {
            e.preventDefault()
            const product = b.dataset.value;
            const r = await add_item({product, quantity: 1})
            if(r.error) {
                Swal.fire({
                    title: 'Error',
                    text: r.error.error,
                    icon: 'error',
                    confirmButtonText: 'ok'
                })
            } else {
                cardItemCount.innerText = parseInt(cardItemCount.innerText, 10) + 1;
                Swal.fire({
                    title: 'Success',
                    text: r.data.message,
                    icon: 'success',
                    confirmButtonText: 'Cool'
                })
            }
        })
    })
})