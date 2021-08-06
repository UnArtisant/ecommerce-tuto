import {remove_item, update_item} from "../utils/api/cartApi";
import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', () => {
    const parents = document.querySelectorAll(".cart-item")

    parents.forEach(p => {
        const btn = p.querySelector(".remove-item")
        const quantityInput = p.querySelector(".quantity-input-js");
        const product_id = btn.dataset.value

        //delete item
        btn.addEventListener("click", e => removeItem(e, p, product_id))

        //update quantity
        quantityInput.addEventListener("change", e => updateQuantity(e, p, product_id, e.target.value))
    })

})

async function removeItem(e, parent, product_id) {
    e.preventDefault()
    const r = await remove_item(product_id)
    if (r.error) {
        Swal.fire({
            title: 'Error',
            text: r.error.error,
            icon: 'error',
            confirmButtonText: 'ok'
        })
    } else {
        parent.parentNode.removeChild(parent)
    }
}

async function updateQuantity(e, parent, product_id, quantity) {
    e.preventDefault()
    const quantityInput = parent.querySelector(".quantity-input-js");
    const totalPrice = parent.querySelector(".total-price-js");
    const prevValue = quantityInput.dataset.prevValue
    const price = parent.dataset.price
    const r = await update_item({id: product_id, quantity})
    if (r.error) {
        quantityInput.value = prevValue
        Swal.fire({
            title: 'Error',
            text: r.error.error,
            icon: 'error',
            confirmButtonText: 'ok'
        })
    } else {
        totalPrice.innerHTML = quantity * price + "€"
    }
}