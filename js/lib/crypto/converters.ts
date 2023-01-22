export function convertStringToArrayBufferView(str)
{
    let bytes = new Uint8Array(str.length);
    for (var iii = 0; iii < str.length; iii++)
    {
        bytes[iii] = str.charCodeAt(iii);
    }

    return bytes;
}

export function convertArrayBufferViewToString(buffer)
{
    let string = "";
    for (var iii = 0; iii < buffer.byteLength; iii++)
    {
        string += String.fromCharCode(buffer[iii]);
    }

    return string;
}