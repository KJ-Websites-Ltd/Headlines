/**
 * Utilities
 */

let rtn = {};


/**
* Update the document create element default by allowing for attribute setting
*/
rtn.createElement = (type:string, attr:string, name:string) => {

    let res = document.createElement(type);
    res.setAttribute(attr, name);

    return res;

}


rtn.getDate = (timestamp: string) => {

    timestamp = parseInt(timestamp);

    let date = new Date(timestamp * 1000);

    return date.toLocaleDateString('en-GB', {  
        day : 'numeric',
        month : 'short',
        year : 'numeric'
    });
}


export default rtn;


