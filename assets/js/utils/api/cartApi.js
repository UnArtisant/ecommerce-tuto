import {resolve} from "../hook/resolver";
import axios from "axios";
import {ADD_ITEM_CARD_API} from "../../constant/path";

export const add_item = (data) =>  {
    return resolve(axios.post(ADD_ITEM_CARD_API, data).then(r => r.data))
}