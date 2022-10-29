import { alertUser } from "../menu";

/**
 * Formats a byte value to a human readable string
 */
export function formatBytes(bytes: number, decimals = 2) {
  if (bytes === 0) return "0 Bytes";

  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

  const i = Math.floor(Math.log(bytes) / Math.log(k));

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

const urlRegex =
  /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/;

function isValidURL(string: string) {
  const res = string.match(urlRegex);
  return res !== null;
}

export const validateForm = () => {
  const inputValue = document.forms["urlform"]["input"].value;
  if (!isValidURL(inputValue) || inputValue === "") {
    alertUser({
      title: "Something's wrong here",
      text: "You have to enter a valid URL",
      icon: "error",
    });
    return false;
  }
}

