
/**
 * validates data as far as is possible without model access.
 *
 * @param data - The data to validate.
 * @param type - The type the data should conform to.
 * @param suppress - when true console logs are not made (good for avoiding console spam in test cases)
 * @returns {boolean} - True if conforms to requested type false otherwise.
 *                      console logs may be produced in error states unless suppressed.
 */
function SANE_sane(data, type, suppress = false){
    data = data.trim();
    switch (type){
        case "comment_text":
        case "room_text":
        case "block_desc":
            return (null !== data.match(/^[a-zA-Z0-9 !"£$%^&*()_+\-=[\]{};'#:@~<>?,.\/¬|\n]{1,500}$/));
        case "room_name":
        case "block_name":
            return (null !== data.match(/^[a-zA-Z0-9 !?,.£$()@#~:;]{3,32}$/));
        case "hall":
            return (null !== data.match(/^(Academic|Community|Society)$/));
        case "password":
            return (null !== data.match(/^[\S]{8,32}$/));
        case "username":
            return (null !== data.match(/^[a-zA-Z\-_ 0-9]{4,30}$/));
        case "exeter_email":
            return (null !== data.match(/^[a-zA-Z.0-9]+@exeter\.ac\.uk$/));
        default:
            if(!suppress){console.log("Type "+type+" invalid!");}
            return false;
    }
}