import axios from "axios"
import {resolve} from "../hook/resolver";
import {SET_DEFULT_ADDRESS_API} from "../../constant/path";

export const setDefaultAddress = (id) => {
    return resolve(axios.post(SET_DEFULT_ADDRESS_API + id).then(r => r.data))
}