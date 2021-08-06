import {resolve} from "../hook/resolver";
import axios from "axios";
import {ADD_ITEM_CARD_API} from "../../constant/path";

export const add_item = (data) =>  {
    return resolve(axios.post(ADD_ITEM_CARD_API, data).then(r => r.data))
}

export const remove_item = (id) => {
    return resolve(axios.delete(ADD_ITEM_CARD_API + "/" + id).then(r => r.data))
}

export const update_item = (data) => {
    return resolve(axios.put(ADD_ITEM_CARD_API + "/" + data.id, data).then(r => r.data))
}