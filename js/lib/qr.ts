import { Theme } from "../new";
import * as qrcode from "./generated/qr_code_wasm/generated/qr_code_wasm";

let loaded = false;

qrcode.default("/out/lib/qr_code_wasm/generated/qr_code_wasm_bg.wasm").then(() => {
    console.trace("Loaded QR code WASM module");
    loaded = true;
});

const waitForLoad = () => {
    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if (loaded) {
                clearInterval(interval);
                resolve(true);
            }
        }, 100);
    });
};

export const generate = async (url: string, theme: Theme) => {
    if (!loaded) {
        await waitForLoad();
    }

    const lightColor = theme === "light" ? "#fff" : "#e4e4e4";
    const darkColor = theme === "light" ? "#157EFB" : "#151515";

    const data = qrcode.generate_qr_code(url, lightColor, darkColor);
    return data;
}